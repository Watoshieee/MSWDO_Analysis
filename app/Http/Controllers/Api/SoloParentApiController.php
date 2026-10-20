<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Application;
use App\Models\RenewalRequest;
use App\Models\User;
use App\Mail\AppointmentStatusMail;
use App\Mail\NewAppointmentAdminMail;
use App\Services\AdminNotificationService;
use App\Services\PhilippineHolidayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SoloParentApiController extends Controller
{
    /**
     * Get available time slots for a specific date
     * GET /api/solo-parent/slots?date=YYYY-MM-DD
     */
    public function getAvailableSlots(Request $request)
    {
        $request->validate(['date' => 'required|date']);
        
        $date = $request->date;
        $user = Auth::user();

        try {
            $carbon = Carbon::createFromFormat('Y-m-d', $date);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Invalid date format'], 422);
        }

        if ($carbon->isWeekend()) {
            return response()->json(['success' => false, 'message' => 'Weekends are not available'], 422);
        }

        if ($carbon->isPast() && !$carbon->isToday()) {
            return response()->json(['success' => false, 'message' => 'Past dates are not available'], 422);
        }

        $holidayCheck = app(PhilippineHolidayService::class)->isAllowedBookingDate($date);
        if (!$holidayCheck['allowed']) {
            return response()->json(['success' => false, 'message' => $holidayCheck['reason']], 422);
        }

        $slots = Appointment::slotsForDate($date, $user->municipality);
        return response()->json(['slots' => $slots]);
    }

    /**
     * Book an appointment
     * POST /api/solo-parent/appointments
     */
    public function bookAppointment(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|in:' . implode(',', Appointment::availableSlots()),
            'interview_type'   => 'required|in:face_to_face,online',
            'user_notes'       => 'nullable|string|max:500',
        ]);

        $date = $request->appointment_date;
        $time = $request->appointment_time;

        // Validate: weekday and Philippine holiday
        $holidayCheck = app(PhilippineHolidayService::class)->isAllowedBookingDate($date);
        if (!$holidayCheck['allowed']) {
            return response()->json(['success' => false, 'message' => $holidayCheck['reason']], 422);
        }

        // ── Block if user already has an active/approved Solo Parent ID or is already validated ──
        $hasActiveId = Application::where('user_id', $user->id)
            ->where('program_type', 'Solo_Parent')
            ->where('status', 'approved')
            ->whereIn('id_status', ['processing', 'ready_for_pickup', 'delivered'])
            ->exists();

        // Also block if appointment is already validated (eligible)
        $alreadyValidated = Appointment::where('user_id', $user->id)
            ->where('program_type', 'Solo_Parent')
            ->where('status', 'validated')
            ->exists();

        if ($hasActiveId || $alreadyValidated) {
            return response()->json([
                'success' => false,
                'message' => $alreadyValidated
                    ? 'You are already eligible for a Solo Parent ID. You cannot book another appointment.'
                    : 'You already have an active Solo Parent ID and cannot book a new appointment.',
                'code'    => 'ALREADY_ELIGIBLE',
            ], 409);
        }

        // Check user doesn't already have an active appointment
        $existing = Appointment::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('program_type', 'Solo_Parent')
            ->first();

        if ($existing) {
            return response()->json(['success' => false, 'message' => 'You already have an active appointment. Please cancel it before booking a new one.'], 422);
        }

        // Check slot capacity
        if (Appointment::slotCount($date, $time, $user->municipality) >= Appointment::maxPerSlot()) {
            return response()->json(['success' => false, 'message' => 'That time slot is already full for your municipality. Please choose another.'], 422);
        }

        $appt = Appointment::create([
            'user_id'          => $user->id,
            'municipality'     => $user->municipality ?? 'Unknown',
            'appointment_date' => $date,
            'appointment_time' => $time,
            'interview_type'   => $request->interview_type,
            'program_type'     => 'Solo_Parent',
            'status'           => 'pending',
            'user_notes'       => $request->user_notes,
        ]);

        // Notify admins by email
        $admins = User::where('role', 'admin')
            ->where('municipality', $user->municipality)
            ->get();

        foreach ($admins as $admin) {
            try {
                Mail::to($admin->email)->send(new NewAppointmentAdminMail($appt, $user));
            } catch (\Exception $e) {
                Log::error('Admin appointment notification email failed', [
                    'admin_id' => $admin->id,
                    'error'    => $e->getMessage(),
                ]);
            }
        }

        // Send push notification to user
        $this->sendPushNotification($user->id, 'Appointment Booked', 'Your Solo Parent appointment has been submitted and is pending admin confirmation.');

        // Windows push notification to admin
        try {
            AdminNotificationService::newAppointment($appt);
        } catch (\Exception $e) {
            Log::error('AdminNotificationService::newAppointment (SoloParent API) failed: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Appointment booked successfully! The admin will confirm your schedule.',
            'appointment' => $this->formatAppointment($appt)
        ], 201);
    }

    /**
     * Get user's appointments
     * GET /api/solo-parent/appointments
     */
    public function getAppointments()
    {
        $user = Auth::user();
        $appointments = Appointment::where('user_id', $user->id)
            ->where('program_type', 'Solo_Parent')
            ->orderBy('appointment_date', 'desc')
            ->get()
            ->map(fn($appt) => $this->formatAppointment($appt));

        return response()->json(['appointments' => $appointments]);
    }

    /**
     * Cancel an appointment
     * DELETE /api/solo-parent/appointments/{id}
     */
    public function cancelAppointment($id)
    {
        $user = Auth::user();
        $appt = Appointment::where('id', $id)->where('user_id', $user->id)->first();

        if (!$appt) {
            return response()->json(['success' => false, 'message' => 'Appointment not found'], 404);
        }

        if (!in_array($appt->status, ['pending', 'confirmed'])) {
            return response()->json(['success' => false, 'message' => 'This appointment cannot be cancelled.'], 422);
        }

        $appt->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Appointment cancelled successfully.']);
    }

    /**
     * Get Solo Parent application details
     * GET /api/solo-parent/application
     */
    public function getApplication()
    {
        $user = Auth::user();
        
        // 1. Check for an active appointment (pending / confirmed / validated)
        $appointment = Appointment::where('user_id', $user->id)
            ->whereIn('program_type', ['Solo_Parent', 'Solo_Parent_Renewal'])
            ->whereIn('status', ['pending', 'confirmed', 'validated'])
            ->latest()
            ->first();

        $application  = null;
        $requirements = [];

        // 2. Validate appointment integrity and load associated active application
        if ($appointment) {
            if ($appointment->solo_parent_app_id) {
                // If appointment is linked to an application, that application MUST exist,
                // have deleted_at IS NULL, and belong to the authenticated user.
                $application = Application::with('fileMonitoring.fileUploads')
                    ->where('id', $appointment->solo_parent_app_id)
                    ->where('user_id', $user->id)
                    ->first();
            }

            // Fallback: If appointment is validated, find latest Solo_Parent application
            if (!$application && $appointment->status === 'validated') {
                $application = Application::with('fileMonitoring.fileUploads')
                    ->where('user_id', $user->id)
                    ->where('program_type', 'Solo_Parent')
                    ->latest('id')
                    ->first();
            }

            // If validated appointment has no application found, treat as orphaned
            if (!$application && $appointment->status === 'validated') {
                $appointment = null;
            } else if ($application && $application->fileMonitoring) {
                $requirements = $this->getRequirements($application);
            }
        }

        return response()->json([
            'appointment'  => $appointment ? $this->formatAppointment($appointment) : null,
            'application'  => $application ? $this->formatApplication($application) : null,
            'requirements' => $requirements,
        ]);
    }

    /**
     * Upload requirement document
     * POST /api/solo-parent/requirements/upload
     */
    public function uploadRequirement(Request $request)
    {
        $request->validate([
            'application_id'   => 'required|exists:applications,id',
            'requirement_name' => 'required|string',
            'file'             => 'required|file|mimes:pdf,jpg,jpeg,png',
        ]);

        $user        = Auth::user();
        $application = Application::find($request->application_id);

        if ($application->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Validate that category has been assigned by admin before allowing requirement uploads
        if ($application->program_type === 'Solo_Parent') {
            if (empty($application->category_code)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your Solo Parent category has not been assigned by the administrator yet. Requirements can only be uploaded after category assignment.',
                ], 422);
            }

            $allowedRequirements = collect(
                \App\Services\SoloParentCategoryService::getRequirementsForApplication($application)
            )->flatMap(fn($group) => array_column($group['options'], 'requirement_name'))->all();

            if (!empty($allowedRequirements) && !in_array($request->requirement_name, $allowedRequirements, true)) {
                return response()->json([
                    'success' => false,
                    'message' => 'The submitted requirement name is not valid for this Solo Parent category.',
                ], 422);
            }
        }

        // Enforce size limits: PDFs ≤ 25 MB, images ≤ 5 MB
        $file  = $request->file('file');
        $ext   = strtolower($file->getClientOriginalExtension());
        $maxMb = ($ext === 'pdf') ? 25 : 5;
        if ($file->getSize() > $maxMb * 1024 * 1024) {
            return response()->json([
                'success' => false,
                'message' => "File exceeds the {$maxMb}MB limit for {$ext} files.",
            ], 422);
        }

        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $request->requirement_name)
                    . '.' . $ext;
        $path = $file->storeAs('solo_parent_documents', $filename, 'public');

        // Ensure FileMonitoring exists with all required fields
        $fileMonitoring = $application->fileMonitoring;
        if (!$fileMonitoring) {
            $fileMonitoring = $application->fileMonitoring()->create([
                'user_id'        => $user->id,
                'municipality'   => $application->municipality ?? $user->municipality,
                'priority'       => 'medium',
                'overall_status' => 'pending',
            ]);
        }

        $fileMonitoring->fileUploads()->updateOrCreate(
            ['requirement_name' => $request->requirement_name],
            [
                'user_id'       => $user->id,
                'municipality'  => $application->municipality ?? $user->municipality,
                'file_name'     => $file->getClientOriginalName(),
                'file_path'     => $path,
                'status'        => 'pending',
                'admin_remarks' => null,
                'uploaded_at'   => now(),
            ]
        );

        $hasRemainingRejected = $fileMonitoring->fileUploads()->where('status', 'rejected')->exists();
        $fileMonitoring->update([
            'overall_status' => $hasRemainingRejected ? 'rejected' : 'in_review'
        ]);

        if (!$hasRemainingRejected && $application->status === 'rejected') {
            $application->update(['status' => 'pending']);
        }

        Log::info('Solo Parent document uploaded', [
            'user_id'        => $user->id,
            'application_id' => $application->id,
            'requirement'    => $request->requirement_name,
            'file_size_kb'   => round($file->getSize() / 1024, 1),
        ]);

        return response()->json([
            'success'      => true,
            'message'      => 'Document uploaded successfully.',
            'requirements' => $this->getRequirements($application->fresh()),
        ]);
    }

    /**
     * Get notifications
     * GET /api/solo-parent/notifications
     */
    public function getNotifications()
    {
        $user = Auth::user();
        $lastViewedAt = \DB::table('notification_views')
            ->where('user_id', $user->id)
            ->value('last_viewed_at');

        $notifications = \DB::table('notifications')
            ->where('user_id', $user->id)
            ->where('type', 'solo_parent')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($notification) use ($lastViewedAt) {
                $createdAt = $notification->created_at ? Carbon::parse($notification->created_at) : null;
                $isNew = $lastViewedAt && $createdAt
                    ? $createdAt->gt(Carbon::parse($lastViewedAt))
                    : false;

                return [
                    'id'         => $notification->id,
                    'type'       => $notification->type,
                    'title'      => $notification->title,
                    'body'       => $notification->body,
                    'data'       => $notification->data ? json_decode($notification->data, true) : null,
                    'is_read'    => (bool) $notification->is_read,
                    'is_new'     => $isNew,
                    'read_at'    => $notification->read_at,
                    'created_at' => $notification->created_at,
                ];
            })
            ->values();

        return response()->json(['notifications' => $notifications]);
    }

    /**
     * Mark notification as read
     * PUT /api/solo-parent/notifications/{id}/read
     */
    public function markNotificationRead($id)
    {
        $user = Auth::user();
        \DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->update(['is_read' => true, 'read_at' => now(), 'updated_at' => now()]);

        \DB::table('notification_views')->updateOrInsert(
            ['user_id' => $user->id],
            [
                'last_viewed_at' => now(),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return response()->json(['message' => 'Notification marked as read']);
    }

    // Helper methods
    private function formatAppointment($appt)
    {
        return [
            'id'                        => $appt->id,
            'program_type'              => $appt->program_type,
            'appointment_date'          => $appt->appointment_date->format('Y-m-d'),
            'appointment_time'          => $appt->appointment_time,
            'formatted_date'            => $appt->formatted_date,
            'formatted_time'            => $appt->formatted_time,
            'interview_type'            => $appt->interview_type,
            'interview_label'           => $appt->interview_label,
            'status'                    => $appt->status,
            'user_notes'                => $appt->user_notes,
            'admin_notes'               => $appt->admin_notes,
            'created_at'                => $appt->created_at?->toIso8601String(),
            // Reschedule fields
            'reschedule_date'           => $appt->reschedule_date
                ? (is_string($appt->reschedule_date) ? $appt->reschedule_date : $appt->reschedule_date->format('Y-m-d'))
                : null,
            'reschedule_time'           => $appt->reschedule_time,
            'reschedule_reason'         => $appt->reschedule_reason,
            'reschedule_status'         => $appt->reschedule_status,
            // Cancellation fields
            'cancel_reason'             => $appt->cancel_reason,
            'cancellation_status'       => $appt->cancellation_status,
            'cancellation_admin_notes'  => $appt->cancellation_admin_notes,
        ];
    }

    private function formatApplication($app)
    {
        $fileMonitoring = $app->fileMonitoring;
        $categoryInfo = null;
        $evaluation = null;
        if (!empty($app->category_code)) {
            $categories = \App\Services\SoloParentCategoryService::getCategories();
            $categoryInfo = $categories[$app->category_code] ?? null;
            $evaluation = \App\Services\SoloParentCategoryService::evaluateCompletion($app, $fileMonitoring);
        }

        return [
            'id'                   => $app->id,
            'category_code'        => $app->category_code,
            'category_title'       => $categoryInfo['title'] ?? null,
            'is_category_assigned' => !empty($app->category_code),
            'status'               => $app->status,
            'overall_status'       => $fileMonitoring?->overall_status ?? 'pending',
            'id_status'            => $app->id_status,
            'id_ready_at'          => $app->id_ready_at?->toIso8601String(),
            'application_date'     => $app->application_date?->format('Y-m-d'),
            'evaluation'           => $evaluation,
        ];
    }

    private function getRequirements($application)
    {
        $fileMonitoring = $application->fileMonitoring;
        $uploads = $fileMonitoring ? $fileMonitoring->fileUploads : collect();
        $uploadedByName = $uploads->keyBy('requirement_name');

        if (!empty($application->category_code)) {
            $groups = \App\Services\SoloParentCategoryService::getRequirementsForApplication($application);
            $result = [];
            foreach ($groups as $group) {
                foreach ($group['options'] as $opt) {
                    $uploaded = $uploadedByName->get($opt['requirement_name']);
                    $result[] = [
                        'name'          => $opt['requirement_name'],
                        'group_key'     => $group['group_key'],
                        'group_title'   => $group['group_title'],
                        'is_or_group'   => (bool)$group['is_or_group'],
                        'status'        => $uploaded?->status ?? 'not_uploaded',
                        'uploaded_at'   => $uploaded?->uploaded_at?->toIso8601String(),
                        'admin_remarks' => $uploaded?->admin_remarks,
                        'file_name'     => $uploaded?->file_name,
                        'file_url'      => $uploaded?->file_path ? asset('storage/' . $uploaded->file_path) : null,
                    ];
                }
            }
            return $result;
        }

        // When no category has been assigned by the admin yet, do not return premature requirements.
        return [];
    }

    private function sendPushNotification(int $userId, string $title, string $body, string $type = 'solo_parent'): void
    {
        try {
            $notifId = \DB::table('notifications')->insertGetId([
                'user_id'    => $userId,
                'type'       => $type,
                'title'      => $title,
                'body'       => $body,
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            \App\Services\OneSignalService::sendPush($userId, $title, $body, $type, $notifId, ['program_type' => 'Solo_Parent']);
        } catch (\Exception $e) {
            Log::error('SoloParent: Failed to insert or push notification', ['error' => $e->getMessage()]);
        }
    }

    // ── Request cancellation with reason ────────────────────────────────────
    /**
     * POST /mobile-api/solo-parent/appointments/{id}/cancel
     * Body: { "cancel_reason": "string" }
     */
    public function requestCancellation(Request $request, $id)
    {
        $user = $request->user();
        $appt = Appointment::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$appt) {
            return response()->json(['success' => false, 'message' => 'Appointment not found.'], 404);
        }

        if (!in_array($appt->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'This appointment cannot be cancelled.',
            ], 422);
        }

        $request->validate(['cancel_reason' => 'required|string|max:500']);

        $appt->update([
            'cancel_reason'             => $request->cancel_reason,
            'cancellation_status'       => 'pending',
            'cancellation_requested_at' => now(),
        ]);

        // Notify admins via notifications table (picked up by push_dispatcher cron)
        $admins = User::where('role', 'admin')
            ->where('municipality', $user->municipality)
            ->get();

        foreach ($admins as $admin) {
            try {
                \DB::table('notifications')->insert([
                    'user_id'    => $admin->id,
                    'type'       => 'solo_parent',
                    'title'      => '🚫 Cancellation Request',
                    'body'       => $user->full_name . ' has requested to cancel their ' .
                                   str_replace('_', ' ', $appt->program_type) . ' appointment.',
                    'is_read'    => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                Log::error('Admin cancellation notification failed: ' . $e->getMessage());
            }
        }

        // Also notify the user that their cancellation request was received
        try {
            \DB::table('notifications')->insert([
                'user_id'    => $user->id,
                'type'       => 'solo_parent',
                'title'      => 'Cancellation Request Submitted',
                'body'       => 'Your cancellation request for your ' .
                               str_replace('_', ' ', $appt->program_type) .
                               ' appointment has been submitted. Awaiting admin approval.',
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('User cancellation notification failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Cancellation request submitted. Awaiting admin approval.',
        ]);
    }

    // ── Request reschedule ──────────────────────────────────────────────────
    /**
     * POST /mobile-api/solo-parent/appointments/{id}/reschedule
     * Body: { "reschedule_date": "YYYY-MM-DD", "reschedule_time": "HH:MM", "reschedule_reason": "string" }
     */
    public function requestReschedule(Request $request, $id)
    {
        $user = $request->user();
        $appt = Appointment::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$appt) {
            return response()->json(['success' => false, 'message' => 'Appointment not found.'], 404);
        }

        if (!in_array($appt->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'This appointment cannot be rescheduled.',
            ], 422);
        }

        $request->validate([
            'reschedule_date'   => 'required|date|after:today',
            'reschedule_time'   => 'required|in:' . implode(',', Appointment::availableSlots()),
            'reschedule_reason' => 'required|string|max:500',
        ]);

        $date = $request->reschedule_date;
        $holidayCheck = app(PhilippineHolidayService::class)->isAllowedBookingDate($date);
        if (!$holidayCheck['allowed']) {
            return response()->json([
                'success' => false,
                'message' => $holidayCheck['reason'],
            ], 422);
        }

        if (Appointment::slotCount($date, $request->reschedule_time, $user->municipality) >= Appointment::maxPerSlot()) {
            return response()->json([
                'success' => false,
                'message' => 'That time slot is already full. Please choose another.',
            ], 422);
        }

        $appt->update([
            'reschedule_date'         => $date,
            'reschedule_time'         => $request->reschedule_time,
            'reschedule_reason'       => $request->reschedule_reason,
            'reschedule_status'       => 'pending',
            'reschedule_requested_at' => now(),
        ]);

        // Notify admins
        $admins = User::where('role', 'admin')
            ->where('municipality', $user->municipality)
            ->get();

        foreach ($admins as $admin) {
            try {
                \DB::table('notifications')->insert([
                    'user_id'    => $admin->id,
                    'type'       => 'solo_parent',
                    'title'      => '🔄 Reschedule Request',
                    'body'       => $user->full_name . ' wants to reschedule their ' .
                                   str_replace('_', ' ', $appt->program_type) . ' appointment to ' .
                                   Carbon::parse($date)->format('F d, Y') . '.',
                    'is_read'    => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                Log::error('Admin reschedule notification failed: ' . $e->getMessage());
            }
        }

        // Notify the user
        try {
            \DB::table('notifications')->insert([
                'user_id'    => $user->id,
                'type'       => 'solo_parent',
                'title'      => 'Reschedule Request Submitted',
                'body'       => 'Your reschedule request to ' . Carbon::parse($date)->format('F d, Y') .
                               ' has been submitted. Awaiting admin approval.',
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('User reschedule notification failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Reschedule request submitted. Awaiting admin approval.',
        ]);
    }

    // =========================================================================
    // SOLO PARENT RENEWAL ENDPOINTS
    // =========================================================================

    /**
     * GET /api/solo-parent/renewal
     * Returns the user's most recent Solo Parent renewal request.
     */
    public function getRenewal(Request $request)
    {
        $user = Auth::user();

        $renewal = RenewalRequest::with('appointment')
            ->where('user_id', $user->id)
            ->where('program_type', 'Solo_Parent')
            ->latest('id')
            ->first();

        if (!$renewal) {
            return response()->json(['renewal' => null]);
        }

        return response()->json(['renewal' => $this->_formatRenewal($renewal)]);
    }

    /**
     * POST /api/solo-parent/renewal
     * Submit a new Solo Parent renewal request (requires appointment date/time + ID file upload).
     */
    public function submitRenewal(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'id_file'          => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => ['required', 'regex:/^([01]\d|2[0-3]):[0-5]\d$/'],
            'user_notes'       => 'nullable|string|max:200',
        ], [
            'id_file.required'         => 'Please upload a clear copy of your Valid ID.',
            'id_file.max'              => 'The uploaded ID file must not exceed 5MB.',
            'appointment_date.required' => 'Please select an appointment date.',
            'appointment_time.required' => 'Please select an appointment time slot.',
            'user_notes.max'           => 'Additional Notes must not exceed 200 characters.',
        ]);

        $date = $request->appointment_date;
        $time = $request->appointment_time;
        $carbon = Carbon::parse($date);

        // Must be a weekday
        if ($carbon->isWeekend()) {
            return response()->json(['success' => false, 'message' => 'Appointments can only be scheduled on weekdays (Monday–Friday).'], 422);
        }

        // Cannot book more than 60 days in advance
        if ($carbon->isAfter(now()->addDays(60))) {
            return response()->json(['success' => false, 'message' => 'Appointments cannot be booked more than 60 days in advance.'], 422);
        }

        // Holiday check
        $holidayCheck = app(PhilippineHolidayService::class)->isAllowedBookingDate($date);
        if (!$holidayCheck['allowed']) {
            return response()->json(['success' => false, 'message' => $holidayCheck['reason']], 422);
        }

        // Office hour check (8-12 and 1-5)
        $hour = (int) substr($time, 0, 2);
        if (!in_array($hour, Appointment::officeHours())) {
            return response()->json(['success' => false, 'message' => 'Selected time is outside MSWDO office hours (8:00 AM–12:00 PM and 1:00 PM–5:00 PM).'], 422);
        }

        // Check if there is an existing rejected renewal with a preserved appointment (re-upload path)
        $existingRejected = RenewalRequest::with('appointment')
            ->where('user_id', $user->id)
            ->where('program_type', 'Solo_Parent')
            ->where('status', 'rejected')
            ->whereHas('appointment', function ($q) {
                $q->whereNotIn('status', ['rejected', 'cancelled']);
            })
            ->latest('id')
            ->first();

        if ($existingRejected) {
            return response()->json([
                'success' => false,
                'message' => 'You have a rejected renewal with a preserved appointment. Please use the re-upload endpoint to submit your replacement ID.',
                'code'    => 'USE_REUPLOAD',
            ], 422);
        }

        // Prevent duplicate active renewal
        $existingRenewal = RenewalRequest::where('user_id', $user->id)
            ->where('program_type', 'Solo_Parent')
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($q) {
                $q->whereNull('appointment_id')
                  ->orWhereHas('appointment', function ($aq) {
                      $aq->whereNotIn('status', ['rejected', 'cancelled']);
                  });
            })
            ->first();

        if ($existingRenewal) {
            $msg = $existingRenewal->status === 'approved'
                ? 'You already have an approved renewal on record.'
                : 'You already have a pending renewal request under review. Please wait for the admin decision.';
            return response()->json(['success' => false, 'message' => $msg], 422);
        }

        // Hour lock + DB transaction (mirrors website exactly)
        try {
            $renewal = Appointment::withHourLock($user->municipality, $date, $hour, function () use ($request, $user, $date, $time, $hour) {
                return DB::transaction(function () use ($request, $user, $date, $time, $hour) {
                    $hStr = sprintf('%02d', $hour);
                    $hourCount = Appointment::whereDate('appointment_date', $date)
                        ->where('municipality', $user->municipality)
                        ->whereIn('status', ['pending', 'confirmed', 'validated'])
                        ->where(function ($q) use ($hour, $hStr) {
                            $q->where('appointment_time', 'like', $hStr . ':%')
                              ->orWhereRaw('HOUR(appointment_time) = ?', [$hour]);
                        })
                        ->count();

                    if ($hourCount >= Appointment::maxPerHour()) {
                        throw new \Exception('That hour block has reached its maximum capacity of 5 appointments. Please choose another time.');
                    }

                    $timeNorm = substr(trim($time), 0, 5);
                    $slotCount = Appointment::whereDate('appointment_date', $date)
                        ->where('municipality', $user->municipality)
                        ->whereIn('status', ['pending', 'confirmed', 'validated'])
                        ->where(function ($q) use ($time, $timeNorm) {
                            $q->where('appointment_time', $time)
                              ->orWhere('appointment_time', $timeNorm)
                              ->orWhere('appointment_time', 'like', $timeNorm . ':%');
                        })
                        ->count();

                    if ($slotCount >= Appointment::maxPerSlot()) {
                        throw new \Exception('That specific time slot is already taken. Please choose another slot.');
                    }

                    $file = $request->file('id_file');
                    $originalName = $file->getClientOriginalName();
                    $storedPath = $file->store('renewals/solo_parent', 'public');

                    $appt = Appointment::create([
                        'user_id'          => $user->id,
                        'municipality'     => $user->municipality,
                        'appointment_date' => $date,
                        'appointment_time' => $time,
                        'interview_type'   => 'face_to_face',
                        'program_type'     => 'Solo_Parent_Renewal',
                        'status'           => 'pending',
                        'user_notes'       => $request->user_notes,
                    ]);

                    $renewal = RenewalRequest::create([
                        'user_id'        => $user->id,
                        'municipality'   => $user->municipality,
                        'program_type'   => 'Solo_Parent',
                        'id_file_path'   => $storedPath,
                        'id_file_name'   => $originalName,
                        'appointment_id' => $appt->id,
                        'status'         => 'pending',
                        'user_notes'     => $request->user_notes,
                    ]);

                    return $renewal->load('appointment');
                });
            });
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        // Notify admins (in-app bell)
        try {
            $admins = User::where('role', 'admin')->where('municipality', $user->municipality)->get();
            $apptDateFormatted = Carbon::parse($date)->format('F d, Y');
            foreach ($admins as $admin) {
                DB::table('notifications')->insert([
                    'user_id'    => $admin->id,
                    'type'       => 'solo_parent',
                    'title'      => '🔄 New Solo Parent Renewal Request',
                    'body'       => $user->full_name . ' submitted a Solo Parent renewal request with appointment on ' . $apptDateFormatted . '.',
                    'is_read'    => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Admin Solo Parent renewal notification failed: ' . $e->getMessage());
        }

        // Windows push notification to admin
        try {
            if (isset($renewal)) {
                AdminNotificationService::newRenewal($renewal);
            }
        } catch (\Exception $e) {
            Log::error('AdminNotificationService::newRenewal (SoloParent API) failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Your renewal request and appointment have been submitted! The admin will review your ID and schedule.',
            'renewal' => $this->_formatRenewal($renewal),
        ]);
    }

    /**
     * POST /api/solo-parent/renewal/cancel
     * Cancel a pending Solo Parent renewal request.
     */
    public function cancelRenewal(Request $request)
    {
        $user = Auth::user();

        $renewal = RenewalRequest::where('user_id', $user->id)
            ->where('program_type', 'Solo_Parent')
            ->whereIn('status', ['pending'])
            ->latest('id')
            ->first();

        if (!$renewal) {
            return response()->json(['success' => false, 'message' => 'No pending renewal request found to cancel.'], 404);
        }

        $reason = $request->input('cancel_reason', 'Cancelled by applicant');

        DB::transaction(function () use ($renewal, $reason) {
            $renewal->update([
                'status'        => 'cancelled',
                'admin_remarks' => $reason,
            ]);

            if ($renewal->appointment) {
                $renewal->appointment->update([
                    'status'                    => 'cancelled',
                    'cancellation_status'       => 'approved',
                    'cancel_reason'             => $reason,
                    'cancellation_requested_at' => now(),
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Your renewal request has been cancelled and the appointment slot has been released.',
        ]);
    }

    /**
     * POST /api/solo-parent/renewal/reupload
     * Re-upload a replacement Valid ID after a rejection (appointment is preserved).
     */
    public function reuploadRenewalId(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'id_file'    => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'user_notes' => 'nullable|string|max:200',
        ], [
            'id_file.required' => 'Please upload a clear copy of your Valid ID.',
            'id_file.max'      => 'The uploaded ID file must not exceed 5MB.',
        ]);

        $existingRejected = RenewalRequest::with('appointment')
            ->where('user_id', $user->id)
            ->where('program_type', 'Solo_Parent')
            ->where('status', 'rejected')
            ->whereHas('appointment', function ($q) {
                $q->whereNotIn('status', ['rejected', 'cancelled']);
            })
            ->latest('id')
            ->first();

        if (!$existingRejected) {
            return response()->json(['success' => false, 'message' => 'No rejected renewal with a preserved appointment was found.'], 404);
        }

        // Guard: make sure no newer active renewal already exists
        $newerActive = RenewalRequest::where('user_id', $user->id)
            ->where('program_type', 'Solo_Parent')
            ->where('id', '>', $existingRejected->id)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($q) {
                $q->whereNull('appointment_id')
                  ->orWhereHas('appointment', function ($aq) {
                      $aq->whereNotIn('status', ['rejected', 'cancelled']);
                  });
            })
            ->first();

        if ($newerActive) {
            $msg = $newerActive->status === 'approved'
                ? 'You already have an approved renewal on record.'
                : 'You already have a pending renewal request under review.';
            return response()->json(['success' => false, 'message' => $msg], 422);
        }

        $file = $request->file('id_file');
        $originalName = $file->getClientOriginalName();
        $storedPath = $file->store('renewals/solo_parent', 'public');

        // Delete old rejected file from disk
        $oldPath = $existingRejected->id_file_path;
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            try {
                Storage::disk('public')->delete($oldPath);
            } catch (\Exception $e) {
                Log::warning('Could not delete old Solo Parent renewal file: ' . $e->getMessage());
            }
        }

        DB::transaction(function () use ($existingRejected, $storedPath, $originalName, $request) {
            $existingRejected->update([
                'id_file_path'  => $storedPath,
                'id_file_name'  => $originalName,
                'status'        => 'pending',
                'reviewed_by'   => null,
                'reviewed_at'   => null,
                'admin_remarks' => null,
                'user_notes'    => $request->filled('user_notes') ? $request->user_notes : $existingRejected->user_notes,
            ]);

            if ($request->filled('user_notes') && $existingRejected->appointment) {
                $existingRejected->appointment->update(['user_notes' => $request->user_notes]);
            }
        });

        // Notify admins
        try {
            $admins = User::where('role', 'admin')->where('municipality', $user->municipality)->get();
            $apptDateFormatted = $existingRejected->appointment
                ? Carbon::parse($existingRejected->appointment->appointment_date)->format('F d, Y')
                : 'scheduled date';
            foreach ($admins as $admin) {
                DB::table('notifications')->insert([
                    'user_id'    => $admin->id,
                    'type'       => 'solo_parent',
                    'title'      => '🔄 Replacement Solo Parent Renewal ID Uploaded',
                    'body'       => $user->full_name . ' uploaded a replacement Valid ID for Solo Parent renewal. Appointment remains preserved for ' . $apptDateFormatted . '.',
                    'is_read'    => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Admin replacement Solo Parent renewal notification failed: ' . $e->getMessage());
        }

        $existingRejected->load('appointment');
        return response()->json([
            'success' => true,
            'message' => 'Your replacement Valid ID has been uploaded successfully! Your existing appointment has been preserved.',
            'renewal' => $this->_formatRenewal($existingRejected),
        ]);
    }

    /**
     * Format a RenewalRequest for mobile API response.
     */
    private function _formatRenewal(RenewalRequest $renewal): array
    {
        $appt = $renewal->appointment;
        return [
            'id'               => $renewal->id,
            'status'           => $renewal->status,
            'id_file_name'     => $renewal->id_file_name,
            'id_file_url'      => $renewal->id_file_path ? Storage::url($renewal->id_file_path) : null,
            'user_notes'       => $renewal->user_notes,
            'admin_remarks'    => $renewal->admin_remarks,
            'created_at'       => $renewal->created_at ? $renewal->created_at->format('M d, Y h:i A') : null,
            'appointment'      => $appt ? [
                'id'               => $appt->id,
                'program_type'     => $appt->program_type,
                'appointment_date' => $appt->appointment_date instanceof Carbon ? $appt->appointment_date->format('Y-m-d') : (string) $appt->appointment_date,
                'appointment_time' => $appt->appointment_time,
                'formatted_date'   => Carbon::parse($appt->appointment_date)->format('F d, Y'),
                'formatted_time'   => Carbon::parse($appt->appointment_time)->format('h:i A'),
                'status'           => $appt->status,
            ] : null,
        ];
    }
}

