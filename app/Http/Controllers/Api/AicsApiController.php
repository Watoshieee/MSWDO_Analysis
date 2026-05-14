<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Appointment;
use App\Models\User;
use App\Models\ProgramRequirement;
use App\Mail\NewAppointmentAdminMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

        $slots = Appointment::slotsForDate($date, $user->municipality);

        return response()->json(['slots' => $slots]);
    }

    /**
     * Booking helper (mirrors existing web appointment booking validation).
     */
    private function bookAppointment(Request $request, string $programType): JsonResponse
    {
        $user = Auth::user();

        // Prevent booking another appointment once user has started or completed
        // an AICS application for the same program type.
        $hasActiveOrCompletedApp = Application::where('user_id', $user->id)
            ->where('program_type', $programType)
            ->whereIn('status', ['pending', 'approved', 'rejected'])
            ->exists();

        if ($hasActiveOrCompletedApp) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an active AICS application for this assistance type.',
            ], 409);
        }

        $request->validate([
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|in:' . implode(',', Appointment::availableSlots()),
            'interview_type' => 'required|in:face_to_face,online',
            'user_notes' => 'nullable|string|max:500',
        ]);

        $date = $request->appointment_date;
        $time = $request->appointment_time;

        // Validate weekday only
        if (Carbon::parse($date)->isWeekend()) {
            return response()->json([
                'success' => false,
                'message' => 'Appointments can only be booked on weekdays (Mon–Fri).',
            ], 422);
        }

        // Check user doesn't already have an active appointment for this program
        $existing = Appointment::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('program_type', $programType)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an active appointment for this program. Please cancel it before booking a new one.',
            ], 422);
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
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'message' => 'Appointment booked successfully! The admin will confirm your schedule.',
            'appointment' => $this->formatAppointment($appt),
        ], 201);
    }

    // ── Shared push notification helper (DB first, then OneSignal) ───────────
    private function sendPushNotification(int $userId, string $title, string $body, string $type = 'aics'): void
    {
        try {
            \DB::table('notifications')->insert([
                'user_id'    => $userId,
                'type'       => $type,
                'title'      => $title,
                'body'       => $body,
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('AICS: Failed to insert notification', ['error' => $e->getMessage()]);
        }

        try {
            $oneSignalKey = env('ONESIGNAL_API_KEY', '');
            if (!$oneSignalKey) return;

            $payload = [
                'app_id'          => env('ONESIGNAL_APP_ID', '3db6828d-49af-4f5a-8d89-ff0b90749aec'),
                'target_channel'  => 'push',
                'include_aliases' => ['external_id' => [(string) $userId]],
                'headings'        => ['en' => $title],
                'contents'        => ['en' => $body],
                'data'            => ['type' => $type],
                'priority'        => 10,
            ];

            $ch = curl_init('https://api.onesignal.com/notifications');
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST           => true,
                CURLOPT_HTTPHEADER     => [
                    'Content-Type: application/json; charset=utf-8',
                    'Authorization: Key ' . $oneSignalKey,
                ],
                CURLOPT_POSTFIELDS     => json_encode($payload),
                CURLOPT_TIMEOUT        => 10,
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200 && $httpCode !== 201) {
                Log::warning('AICS: OneSignal push failed', ['http_code' => $httpCode]);
            }
        } catch (\Exception $e) {
            Log::error('AICS: Push notification exception', ['error' => $e->getMessage()]);
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

        $appointment = Appointment::where('user_id', $user->id)
            ->where('program_type', $programType)
            ->orderByRaw("FIELD(status,'pending','confirmed','rejected','cancelled')")
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
     * Cancel appointment (pending/confirmed only).
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
}

