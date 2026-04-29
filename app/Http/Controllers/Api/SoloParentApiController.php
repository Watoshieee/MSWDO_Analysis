<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Application;
use App\Models\User;
use App\Mail\AppointmentStatusMail;
use App\Mail\NewAppointmentAdminMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
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

        // Validate: weekday only
        if (Carbon::parse($date)->isWeekend()) {
            return response()->json(['success' => false, 'message' => 'Appointments can only be booked on weekdays (Mon–Fri).'], 422);
        }

        // Block if user already has an approved Solo Parent ID
        $hasActiveId = Application::where('user_id', $user->id)
            ->where('program_type', 'Solo_Parent')
            ->where('status', 'approved')
            ->whereIn('id_status', ['ready_for_pickup', 'delivered'])
            ->exists();

        if ($hasActiveId) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an active Solo Parent ID and cannot book a new appointment.',
                'code'    => 'ID_ALREADY_ISSUED',
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

        // Notify admins
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

        // Send push notification
        $this->sendPushNotification($user->id, 'Appointment Booked', 'Your Solo Parent appointment has been submitted and is pending admin confirmation.');

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
            ->where('program_type', 'Solo_Parent')
            ->whereIn('status', ['pending', 'confirmed', 'validated'])
            ->latest()
            ->first();

        $application  = null;
        $requirements = [];

        // 2. If there's an active appointment with a linked application, load it
        if ($appointment && $appointment->solo_parent_app_id) {
            $application = Application::with('fileMonitoring.fileUploads')
                ->find($appointment->solo_parent_app_id);

            if ($application && $application->fileMonitoring) {
                $requirements = $this->getRequirements($application);
            }
        }

        // 3. If no active appointment, check if user already has an approved
        //    Solo Parent application (ID ready / delivered)
        if (!$application) {
            $application = Application::with('fileMonitoring.fileUploads')
                ->where('user_id', $user->id)
                ->where('program_type', 'Solo_Parent')
                ->where('status', 'approved')
                ->latest('id')
                ->first();

            if ($application && $application->fileMonitoring) {
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

        // Enforce size limits: images ≤ 25 MB, PDFs ≤ 5 MB
        $file  = $request->file('file');
        $ext   = strtolower($file->getClientOriginalExtension());
        $maxMb = in_array($ext, ['jpg', 'jpeg', 'png']) ? 25 : 5;
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
                'user_id'     => $user->id,
                'municipality' => $application->municipality ?? $user->municipality,
                'file_name'   => $file->getClientOriginalName(),
                'file_path'   => $path,
                'status'      => 'pending',
                'uploaded_at' => now(),
            ]
        );

        $fileMonitoring->update(['overall_status' => 'in_review']);

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
        $notifications = \DB::table('notifications')
            ->where('user_id', $user->id)
            ->where('type', 'solo_parent')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

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
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['message' => 'Notification marked as read']);
    }

    // Helper methods
    private function formatAppointment($appt)
    {
        return [
            'id' => $appt->id,
            'appointment_date' => $appt->appointment_date->format('Y-m-d'),
            'appointment_time' => $appt->appointment_time,
            'formatted_date' => $appt->formatted_date,
            'formatted_time' => $appt->formatted_time,
            'interview_type' => $appt->interview_type,
            'interview_label' => $appt->interview_label,
            'status' => $appt->status,
            'user_notes' => $appt->user_notes,
            'admin_notes' => $appt->admin_notes,
            'created_at' => $appt->created_at?->toIso8601String(),
        ];
    }

    private function formatApplication($app)
    {
        $fileMonitoring = $app->fileMonitoring;
        return [
            'id'               => $app->id,
            'status'           => $app->status,
            'overall_status'   => $fileMonitoring?->overall_status ?? 'pending',
            'id_status'        => $app->id_status,
            'id_ready_at'      => $app->id_ready_at?->toIso8601String(),
            'application_date' => $app->application_date?->format('Y-m-d'),
        ];
    }

    private function getRequirements($application)
    {
        $requiredDocs = [
            'PSA Birth Certificate of Child/Children',
            'Barangay Certificate (stating you are a solo parent)',
            'Valid Government-Issued ID',
            'CENOMAR or PSA Marriage Certificate',
            'Death Certificate of Spouse (if widowed) / Police Report (if abandoned)',
            '2x2 ID Photo (recent, white background)',
        ];

        $fileMonitoring = $application->fileMonitoring;
        $uploads = $fileMonitoring ? $fileMonitoring->fileUploads : collect();
        $uploadedByName = $uploads->keyBy('requirement_name');

        return collect($requiredDocs)->map(function($req) use ($uploadedByName) {
            $uploaded = $uploadedByName->get($req);
            return [
                'name' => $req,
                'status' => $uploaded?->status ?? 'not_uploaded',
                'uploaded_at' => $uploaded?->uploaded_at?->toIso8601String(),
                'admin_remarks' => $uploaded?->admin_remarks,
            ];
        })->values()->toArray();
    }

    private function sendPushNotification($userId, $title, $body)
    {
        $deviceToken = \DB::table('device_tokens')
            ->where('user_id', $userId)
            ->where('is_active', true)
            ->value('token');

        if (!$deviceToken) return;

        // Store notification in database
        \DB::table('notifications')->insert([
            'user_id'    => $userId,
            'type'       => 'solo_parent',
            'title'      => $title,
            'body'       => $body,
            'is_read'    => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Send FCM notification (implement based on your FCM setup)
        // This is a placeholder - implement actual FCM logic
        try {
            // FCM implementation here
        } catch (\Exception $e) {
            Log::error('Push notification failed: ' . $e->getMessage());
        }
    }
}
