<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Appointment;
use App\Models\FileMonitoring;
use App\Models\FileUpload;
use App\Models\User;
use App\Models\ProgramRequirement;
use App\Mail\NewAppointmentAdminMail;
use App\Services\AdminNotificationService;
use App\Services\PhilippineHolidayService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AicsApiController extends Controller
{
    /**
     * GET available time slots for a specific date (scoped by municipality).
     * GET /mobile-api/aics/{type}/slots?date=YYYY-MM-DD
     */
    public function getAvailableSlots(Request $request): JsonResponse
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
     * Booking helper (mirrors existing web appointment booking validation).
     */
    private function bookAppointment(Request $request, string $programType): JsonResponse
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

        // Validate weekday and Philippine holiday
        $holidayCheck = app(PhilippineHolidayService::class)->isAllowedBookingDate($date);
        if (!$holidayCheck['allowed']) {
            return response()->json([
                'success' => false,
                'message' => $holidayCheck['reason'],
            ], 422);
        }

        // Block only if user has an active appointment (pending/confirmed/validated)
        // for this program. A CANCELLED appointment allows rebooking.
        $hasActiveAppointment = Appointment::where('user_id', $user->id)
            ->where('program_type', $programType)
            ->whereIn('status', ['pending', 'confirmed', 'validated'])
            ->exists();

        if ($hasActiveAppointment) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an active appointment for this program. Please cancel it before booking a new one.',
            ], 409);
        }

        // Also block if user has an approved or processing application
        // (rejected or pending-only apps that came from a cancelled appointment should NOT block)
        $hasCompletedApp = Application::where('user_id', $user->id)
            ->where('program_type', $programType)
            ->whereIn('status', ['approved'])
            ->exists();

        if ($hasCompletedApp) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an approved AICS application for this assistance type.',
            ], 409);
        }

        // Check slot capacity (scoped to user's municipality)
        if (Appointment::slotCount($date, $time, $user->municipality) >= Appointment::maxPerSlot()) {
            return response()->json([
                'success' => false,
                'message' => 'That time slot is already full for your municipality. Please choose another.',
            ], 422);
        }

        $appt = Appointment::create([
            'user_id' => $user->id,
            'municipality' => $user->municipality,
            'appointment_date' => $date,
            'appointment_time' => $time,
            'interview_type' => $request->interview_type,
            'program_type' => $programType,
            'status' => 'pending',
            'user_notes' => $request->user_notes,
        ]);

        // Send push notification to user
        $label = $programType === 'AICS_Medical' ? 'AICS Medical Assistance' : 'AICS Burial Assistance';
        $this->sendPushNotification(
            $user->id,
            'Appointment Booked',
            'Your ' . $label . ' appointment has been submitted and is pending admin confirmation.',
            'aics',
            $programType
        );

        // Admin email notifications
        $admins = User::where('role', 'admin')
            ->where('municipality', $user->municipality)
            ->get();

        foreach ($admins as $admin) {
            try {
                Mail::to($admin->email)->send(new NewAppointmentAdminMail($appt, $user));
            } catch (\Exception $e) {
                Log::error('Admin appointment notification email failed', [
                    'admin_id' => $admin->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Windows push notification to admin
        try {
            AdminNotificationService::newAppointment($appt);
        } catch (\Exception $e) {
            Log::error('AdminNotificationService::newAppointment (AICS API) failed: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Appointment booked successfully! The admin will confirm your schedule.',
            'appointment' => $this->formatAppointment($appt),
        ], 201);
    }

        // ── Shared push notification helper (DB first, then OneSignal) ───────────
    private function sendPushNotification(int $userId, string $title, string $body, string $type = 'aics', ?string $programType = null): void
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
            $extraData = $programType ? ['program_type' => $programType] : [];
            \App\Services\OneSignalService::sendPush($userId, $title, $body, $type, $notifId, $extraData);
        } catch (\Exception $e) {
            Log::error('AICS: Failed to insert or push notification', ['error' => $e->getMessage()]);
        }
    }

    public function bookMedicalAppointment(Request $request): JsonResponse
    {
        return $this->bookAppointment($request, 'AICS_Medical');
    }

    public function bookBurialAppointment(Request $request): JsonResponse
    {
        return $this->bookAppointment($request, 'AICS_Burial');
    }

    /**
     * GET the user's most relevant appointment (mirrors web ordering).
     * GET /mobile-api/aics/{type}/appointment
     */
    private function getAppointment(string $programType): JsonResponse
    {
        $user = Auth::user();

        // Prioritize active statuses (pending, confirmed, validated) over terminal ones
        $appointment = Appointment::where('user_id', $user->id)
            ->where('program_type', $programType)
            ->orderByRaw("FIELD(status,'pending','confirmed','validated','rejected','cancelled')")
            ->orderBy('appointment_date', 'desc')
            ->first();

        return response()->json([
            'appointment' => $appointment ? $this->formatAppointment($appointment) : null,
        ]);
    }

    public function getMedicalAppointment(): JsonResponse
    {
        return $this->getAppointment('AICS_Medical');
    }

    public function getBurialAppointment(): JsonResponse
    {
        return $this->getAppointment('AICS_Burial');
    }

    /**
     * GET AICS Medical application + requirements statuses.
     * GET /mobile-api/aics/medical/application
     */
    public function getMedicalApplication(): JsonResponse
    {
        return $this->getAicsApplication('AICS_Medical');
    }

    /**
     * GET AICS Burial application + requirements statuses.
     * GET /mobile-api/aics/burial/application
     */
    public function getBurialApplication(): JsonResponse
    {
        return $this->getAicsApplication('AICS_Burial');
    }

    // Backward-compat alias for a misspelled cached route
    // (some route cache entries point to this typo).
    public function getBuriaApplicaton(): JsonResponse
    {
        return $this->getAicsApplication('AICS_Burial');
    }

    /**
     * Request cancellation with reason (matches Solo Parent flow).
     * POST /mobile-api/aics/{type}/appointments/{id}/cancel
     */
    public function requestMedicalCancellation(Request $request, int $id): JsonResponse
    {
        return $this->requestCancellation($request, 'AICS_Medical', $id);
    }

    public function requestBurialCancellation(Request $request, int $id): JsonResponse
    {
        return $this->requestCancellation($request, 'AICS_Burial', $id);
    }

    private function requestCancellation(Request $request, string $programType, int $id): JsonResponse
    {
        $user = Auth::user();
        $appt = Appointment::where('id', $id)
            ->where('user_id', $user->id)
            ->where('program_type', $programType)
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

        // Notify admins of user's municipality
        $admins = User::where('role', 'admin')
            ->where('municipality', $user->municipality)
            ->get();

        foreach ($admins as $admin) {
            try {
                \DB::table('notifications')->insert([
                    'user_id'    => $admin->id,
                    'type'       => 'aics',
                    'title'      => '🚫 Cancellation Request',
                    'body'       => $user->full_name . ' has requested to cancel their ' .
                                   str_replace('_', ' ', $appt->program_type) . ' appointment.',
                    'is_read'    => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                Log::error('Admin AICS cancellation notification failed: ' . $e->getMessage());
            }
        }

        // Notify user
        try {
            \DB::table('notifications')->insert([
                'user_id'    => $user->id,
                'type'       => 'aics',
                'title'      => 'Cancellation Request Submitted',
                'body'       => 'Your cancellation request for your ' .
                               str_replace('_', ' ', $appt->program_type) .
                               ' appointment has been submitted. Awaiting admin approval.',
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('User AICS cancellation notification failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Cancellation request submitted. Awaiting admin approval.',
        ]);
    }

    /**
     * Cancel appointment immediately (legacy/fallback).
     * DELETE /mobile-api/aics/{type}/appointments/{id}
     */
    private function cancelAppointment(string $programType, int $id): JsonResponse
    {
        $user = Auth::user();
        $appt = Appointment::where('id', $id)
            ->where('user_id', $user->id)
            ->where('program_type', $programType)
            ->first();

        if (!$appt) {
            return response()->json(['success' => false, 'message' => 'Appointment not found'], 404);
        }

        if (!in_array($appt->status, ['pending', 'confirmed'], true)) {
            return response()->json(['success' => false, 'message' => 'This appointment cannot be cancelled.'], 422);
        }

        $appt->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Appointment cancelled successfully.'], 200);
    }

    public function cancelMedicalAppointment(int $id): JsonResponse
    {
        return $this->cancelAppointment('AICS_Medical', $id);
    }

    public function cancelBurialAppointment(int $id): JsonResponse
    {
        return $this->cancelAppointment('AICS_Burial', $id);
    }

    private function formatAppointment($appt): array
    {
        return [
            'id'                       => $appt->id,
            'appointment_date'         => $appt->appointment_date->format('Y-m-d'),
            'appointment_time'         => $appt->appointment_time,
            'formatted_date'           => $appt->formatted_date,
            'formatted_time'           => $appt->formatted_time,
            'interview_type'           => $appt->interview_type,
            'interview_label'          => $appt->interview_label,
            'status'                   => $appt->status,
            'user_notes'               => $appt->user_notes,
            'admin_notes'              => $appt->admin_notes,
            'created_at'               => $appt->created_at?->toIso8601String(),
            // Reschedule fields
            'reschedule_date'          => $appt->reschedule_date
                ? (is_string($appt->reschedule_date) ? $appt->reschedule_date : $appt->reschedule_date->format('Y-m-d'))
                : null,
            'reschedule_time'          => $appt->reschedule_time,
            'reschedule_reason'        => $appt->reschedule_reason,
            'reschedule_status'        => $appt->reschedule_status,
            // Cancellation fields
            'cancel_reason'            => $appt->cancel_reason,
            'cancellation_status'      => $appt->cancellation_status,
            'cancellation_admin_notes' => $appt->cancellation_admin_notes,
        ];
    }

    /**
     * Shared helper for AICS application + requirements statuses.
     *
     * Response shape is aligned with SoloParent mobile flow:
     * {
     *   application: { id, status, application_date, admin_remarks },
     *   appointment: null,
     *   requirements: [{ name, status, uploaded_at, admin_remarks }]
     * }
     */
    private function getAicsApplication(string $programType): JsonResponse
    {
        $user = Auth::user();

        $application = Application::with(['fileMonitoring.fileUploads'])
            ->where('user_id', $user->id)
            ->where('program_type', $programType)
            ->latest('id')
            ->first();

        // If interview was validated before the fix was deployed and no
        // Application record exists, log the gap so it can be repaired
        // by an admin.  GET endpoints must remain read-only.
        if (!$application) {
            $hasValidated = Appointment::where('user_id', $user->id)
                ->where('program_type', $programType)
                ->where('status', 'validated')
                ->exists();

            if ($hasValidated) {
                Log::warning('AICS: Validated appointment exists without Application record — requires admin repair.', [
                    'user_id'      => $user->id,
                    'program_type' => $programType,
                ]);
            }
        }

        $requiredDocs = ProgramRequirement::where('program_type', $programType)
            ->pluck('requirement_name')
            ->toArray();

        // Fallback: ensures endpoint still works even if program_requirements is not seeded.
        if (empty($requiredDocs)) {
            $requiredDocs = $programType === 'AICS_Medical'
                ? [
                    'Certificate of Indigency (Original)',
                    'Medical Certificate',
                    'Marriage Contract (if spouse)',
                    'Birth Certificate (if parent/children)',
                    'Photocopy of ID (patient & claimant)',
                    'Authorization Letter (if applicable)',
                ]
                : [
                    'Certificate of Indigency',
                    'Death Certificate',
                    'Marriage Contract',
                    'Birth Certificate',
                    'Valid IDs',
                    'Authorization Letter',
                ];
        }

        $uploads = collect();
        if ($application?->fileMonitoring) {
            $uploads = $application->fileMonitoring->fileUploads;
        }

        $requirements = array_map(function (string $reqName) use ($uploads) {
            $upload = $uploads->firstWhere('requirement_name', $reqName);

            return [
                'name' => $reqName,
                'status' => $upload?->status ?? 'not_uploaded',
                'uploaded_at' => $upload?->uploaded_at?->toIso8601String(),
                'admin_remarks' => $upload?->admin_remarks,
            ];
        }, $requiredDocs);

        return response()->json([
            'appointment' => null,
            'application' => $application ? [
                'id' => $application->id,
                'status' => $application->status,
                'application_date' => $application->application_date?->format('Y-m-d'),
                'admin_remarks' => $application->admin_remarks,
            ] : null,
            'requirements' => $requirements,
        ]);
    }

    // ── Upload & Re-upload Requirements ──────────────────────────────────────

    public function uploadMedicalRequirement(Request $request): JsonResponse
    {
        return $this->uploadRequirement($request, 'AICS_Medical');
    }

    public function uploadBurialRequirement(Request $request): JsonResponse
    {
        return $this->uploadRequirement($request, 'AICS_Burial');
    }

    public function reuploadMedicalRequirement(Request $request, int $fileId): JsonResponse
    {
        return $this->reuploadRequirement($request, 'AICS_Medical', $fileId);
    }

    public function reuploadBurialRequirement(Request $request, int $fileId): JsonResponse
    {
        return $this->reuploadRequirement($request, 'AICS_Burial', $fileId);
    }

    private function uploadRequirement(Request $request, string $programType): JsonResponse
    {
        $user = Auth::user();

        $request->validate([
            'application_id'   => 'required|exists:applications,id',
            'requirement_name' => 'required|string',
            'file'             => 'required|file|mimes:pdf,jpg,jpeg,png|max:25600',
        ]);

        $application = Application::find($request->application_id);
        if (!$application || $application->user_id !== $user->id || $application->program_type !== $programType) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Strictly enforce interview validation before allowing requirements upload
        $hasValidatedAppointment = Appointment::where('user_id', $user->id)
            ->where('program_type', $programType)
            ->where('status', 'validated')
            ->exists();

        if (!$hasValidatedAppointment) {
            return response()->json([
                'success' => false,
                'message' => 'Interview validation is required before uploading documents.',
            ], 403);
        }

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $request->requirement_name) . '.' . $ext;
        $path = $file->storeAs('aics_documents', $filename, 'public');

        $fileMonitoring = $application->fileMonitoring;
        if (!$fileMonitoring) {
            $fileMonitoring = FileMonitoring::create([
                'application_id' => $application->id,
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
            'overall_status' => $hasRemainingRejected ? 'rejected' : 'in_review',
        ]);

        if (!$hasRemainingRejected && $application->status === 'rejected') {
            $application->update(['status' => 'pending']);
        }

        Log::info('AICS document uploaded', [
            'user_id'        => $user->id,
            'application_id' => $application->id,
            'program_type'   => $programType,
            'requirement'    => $request->requirement_name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document uploaded successfully.',
        ]);
    }

    private function reuploadRequirement(Request $request, string $programType, int $fileId): JsonResponse
    {
        $user = Auth::user();

        $fileUpload = FileUpload::with(['fileMonitoring.application'])
            ->where('id', $fileId)
            ->where('user_id', $user->id)
            ->first();

        if (!$fileUpload) {
            return response()->json(['success' => false, 'message' => 'File not found.'], 404);
        }

        if ($fileUpload->fileMonitoring?->application?->program_type !== $programType) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        if ($fileUpload->status !== 'rejected') {
            return response()->json(['success' => false, 'message' => 'Only rejected files can be re-uploaded.'], 400);
        }

        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:25600',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $fileUpload->requirement_name) . '.' . $ext;
        $path = $file->storeAs('aics_documents', $filename, 'public');

        if ($fileUpload->file_path && Storage::disk('public')->exists($fileUpload->file_path)) {
            Storage::disk('public')->delete($fileUpload->file_path);
        }

        $fileUpload->update([
            'file_name'     => $file->getClientOriginalName(),
            'file_path'     => $path,
            'status'        => 'pending',
            'admin_remarks' => null,
            'uploaded_at'   => now(),
        ]);

        $fm = $fileUpload->fileMonitoring;
        if ($fm) {
            $hasRemainingRejected = $fm->fileUploads()->where('status', 'rejected')->exists();
            $fm->update([
                'overall_status' => $hasRemainingRejected ? 'rejected' : 'in_review',
            ]);

            if (!$hasRemainingRejected && $fm->application?->status === 'rejected') {
                $fm->application->update(['status' => 'pending']);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Document re-uploaded successfully.',
        ]);
    }
}

