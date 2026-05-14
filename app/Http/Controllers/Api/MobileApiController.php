<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Announcement;
use App\Models\FileUpload;
use App\Models\User;
use App\Services\ApplicationService;
use App\Services\AuthService;
use App\Services\DashboardService;
use App\Services\OtpService;
use App\Services\RegistrationValidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MobileApiController extends Controller
{
    public function __construct(
        private AuthService $auth,
        private OtpService $otp,
        private ApplicationService $applicationService,
        private DashboardService $dashboardService,
    ) {}

    // ── PUBLIC ENDPOINTS ───────────────────────────────────────────────────

    public function municipalities(): JsonResponse
    {
        try {
            $municipalitiesList = User::getMunicipalities();
            $data = [];
            foreach ($municipalitiesList as $muni) {
                $barangays = \App\Helpers\BarangayHelper::getDefaultBarangays($muni);
                $data[] = [
                    'name' => $muni,
                    'barangays' => $barangays,
                ];
            }
            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to load municipalities.'], 500);
        }
    }

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name'    => 'required|string|max:50',
            'middle_name'   => 'nullable|string|max:50',
            'last_name'     => 'required|string|max:50',
            'username'      => 'required|string|max:20|unique:users,username',
            'email'         => 'required|email|unique:users,email',
            'mobile_number' => 'required|string',
            'birthdate'     => 'required|date',
            'municipality'  => 'required|string',
            'barangay'      => 'required|string',
            'gender'        => 'required|string|in:Male,Female',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $customErrors = RegistrationValidationService::validateRegistration($request->all());
        if (!empty($customErrors)) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $customErrors,
            ], 422);
        }

        // Age check
        $dob = new \DateTime($request->birthdate);
        $age = (new \DateTime())->diff($dob)->y;
        if ($age < 18) {
            return response()->json([
                'success' => false,
                'message' => 'You must be at least 18 years old to register.',
                'errors'  => ['birthdate' => ['You must be at least 18 years old to register.']],
            ], 422);
        }

        try {
            $user = $this->auth->register($request->all(), $age);
            $otpCode = $this->otp->generate($user);
            $this->otp->sendVerificationEmail($user, $otpCode);

            return response()->json([
                'success' => true,
                'message' => 'Registration successful! Check your Gmail for your password and OTP verification code.',
                'data'    => ['email' => $user->email],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        if ($this->otp->verify($user, $request->otp)) {
            if ($user->status !== 'active') {
                $user->status = 'active';
                $user->save();
            }
            return response()->json(['success' => true, 'message' => 'OTP verified successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.']);
    }

    public function resendOtp(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        try {
            $otpCode = $this->otp->generate($user);
            $this->otp->sendVerificationEmail($user, $otpCode);

            return response()->json([
                'success' => true,
                'message' => 'A new OTP has been sent to your email address.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP email. Please try again later.',
            ], 500);
        }
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        try {
            $otpCode = $this->otp->generate($user);
            $this->otp->sendPasswordResetEmail($user, $otpCode);

            return response()->json([
                'success' => true,
                'message' => 'A password reset OTP has been sent to your email address.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send reset email. Please try again later.',
            ], 500);
        }
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'otp'      => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found.']);
        }

        if ($user->otp_code == $request->otp && $user->otp_expires_at > now()) {
            $user->password = Hash::make($request->password);
            $user->otp_code = null;
            $user->otp_expires_at = null;
            $user->must_change_password = false;
            if (is_null($user->email_verified_at)) {
                $user->email_verified_at = now();
            }
            $user->save();

            return response()->json(['success' => true, 'message' => 'Password has been reset successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.']);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $user = $this->auth->attemptLogin($request->login, $request->password);
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            $status = match ($e->getMessage()) {
                'User not found'           => 404,
                'Incorrect password'       => 401,
                'Account not yet verified' => 403,
                default                    => 401,
            };
            return response()->json(['success' => false, 'message' => $e->getMessage()], $status);
        }

        $token = $this->auth->createToken($user);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token'   => $token,
            'must_change_password' => (bool) $user->must_change_password,
            'user'    => [
                'id'           => $user->id,
                'full_name'    => $user->full_name,
                'email'        => $user->email,
                'username'     => $user->username,
                'role'         => $user->role,
                'municipality' => $user->municipality,
                'barangay'     => $user->barangay,
            ],
        ]);
    }

    // ── POST /change-password (authenticated — first-login forced reset) ───
    public function changePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully! You can now use the app.',
        ]);
    }

    // ── PROTECTED ENDPOINTS ────────────────────────────────────────────────

    public function dashboard(Request $request): JsonResponse
    {
        $user = $request->user();
        $stats = $this->dashboardService->getMobileStats($user);
        $recentApps = $this->dashboardService->getRecentApplications($user);
        $announcements = $this->dashboardService->getAnnouncements($user);
        $lastViewedAt = DB::table('notification_views')
            ->where('user_id', $user->id)
            ->value('last_viewed_at');

        $unreadDbNotifications = DB::table('notifications')
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        $newAnnouncementsCount = Announcement::where('is_active', true)
            ->where(function ($q) use ($user) {
                $q->whereNull('municipality')
                    ->orWhere('municipality', 'all')
                    ->orWhere('municipality', '')
                    ->orWhere('municipality', $user->municipality);
            })
            ->when($lastViewedAt, fn ($q) => $q->where('created_at', '>', $lastViewedAt))
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'unread_notifications_count' => (int) $unreadDbNotifications + (int) $newAnnouncementsCount,
                'recent_applications' => $recentApps->map(fn ($app) => [
                    'id'           => $app->id,
                    'program_type' => $app->program_type,
                    'status'       => $app->status,
                    'barangay'     => $app->barangay ?? '',
                    'municipality' => $app->municipality ?? '',
                    'created_at'   => $app->application_date ? $app->application_date->format('F d, Y') : null,
                ]),
                'announcements' => $announcements,
            ],
        ]);
    }

    public function announcements(Request $request): JsonResponse
    {
        $announcements = $this->dashboardService->getAllAnnouncements($request->user());
        return response()->json(['success' => true, 'data' => $announcements]);
    }

    public function notifications(Request $request): JsonResponse
    {
        $user = $request->user();
        $userId = $user->id;
        $lastViewedAt = DB::table('notification_views')
            ->where('user_id', $userId)
            ->value('last_viewed_at');

        $items = collect();
        $idCounter = 100000; // synthetic IDs for dynamic notifications

        // ── 1. Document-level notifications (approved / rejected files) ──────
        $documentNotifications = FileUpload::where(function ($q) use ($user) {
                $q->whereHas('fileMonitoring', fn($q) => $q->where('user_id', $user->id))
                  ->orWhereHas('fileMonitoring.application', fn($q) => $q->where('user_id', $user->id));
            })
            ->whereIn('status', ['approved', 'rejected'])
            ->with(['fileMonitoring.application'])
            ->orderBy('verified_at', 'desc')
            ->limit(50)
            ->get();

        foreach ($documentNotifications as $doc) {
            $ts = $doc->verified_at ?? $doc->uploaded_at;
            $isNew = $lastViewedAt && $ts
                ? \Carbon\Carbon::parse($ts)->gt(\Carbon\Carbon::parse($lastViewedAt))
                : !$lastViewedAt;
            $program = $doc->fileMonitoring->application->program_type ?? 'application';
            $programLabel = str_replace('_', ' ', $program);
            $type = match (true) {
                str_contains($program, 'PWD')         => 'pwd',
                str_contains($program, 'Solo_Parent') => 'solo_parent',
                str_contains($program, 'AICS')        => 'aics',
                default                               => 'application',
            };

            $items->push([
                'id'         => $idCounter++,
                'type'       => $type,
                'title'      => $doc->status === 'approved' ? 'Document Approved' : 'Document Rejected',
                'body'       => ($doc->status === 'approved'
                    ? 'Your "' . $doc->requirement_name . '" for ' . $programLabel . ' has been approved.'
                    : 'Your "' . $doc->requirement_name . '" for ' . $programLabel . ' was rejected.'
                      . ($doc->admin_remarks ? ' Reason: ' . $doc->admin_remarks : '')),
                'data'       => null,
                'is_read'    => !$isNew,
                'is_new'     => $isNew,
                'read_at'    => null,
                'created_at' => $ts,
            ]);
        }

        // ── 2. Solo Parent ID ready for pickup ──────────────────────────────
        $idReadyApp = Application::where('user_id', $userId)
            ->where('program_type', 'Solo_Parent')
            ->where('id_status', 'ready_for_pickup')
            ->latest('id_ready_at')
            ->first();

        if ($idReadyApp) {
            $ts = $idReadyApp->id_ready_at ?? $idReadyApp->updated_at;
            $isNew = $lastViewedAt && $ts
                ? \Carbon\Carbon::parse($ts)->gt(\Carbon\Carbon::parse($lastViewedAt))
                : !$lastViewedAt;
            $items->push([
                'id'         => $idCounter++,
                'type'       => 'solo_parent',
                'title'      => 'Your Solo Parent ID is Ready',
                'body'       => 'Please pick up your Solo Parent ID at the ' . ($idReadyApp->municipality ?? 'MSWDO') . ' MSWDO Office.',
                'data'       => null,
                'is_read'    => !$isNew,
                'is_new'     => $isNew,
                'read_at'    => null,
                'created_at' => $ts,
            ]);
        }

        // ── 3. Solo Parent requirements validated (approved + processing) ───
        $spValidated = Application::where('user_id', $userId)
            ->where('program_type', 'Solo_Parent')
            ->where('status', 'approved')
            ->where('id_status', 'processing')
            ->latest('completed_at')
            ->first();

        if ($spValidated) {
            $ts = $spValidated->completed_at ?? $spValidated->application_date;
            $isNew = $lastViewedAt && $ts
                ? \Carbon\Carbon::parse($ts)->gt(\Carbon\Carbon::parse($lastViewedAt))
                : !$lastViewedAt;
            $items->push([
                'id'         => $idCounter++,
                'type'       => 'solo_parent',
                'title'      => 'Solo Parent Requirements Validated',
                'body'       => 'Your Solo Parent requirements are fully validated. Please wait for the ID ready notice.',
                'data'       => null,
                'is_read'    => !$isNew,
                'is_new'     => $isNew,
                'read_at'    => null,
                'created_at' => $ts,
            ]);
        }

        // ── 4. Solo Parent appointment confirmed ────────────────────────────
        $spApproved = \App\Models\Appointment::where('user_id', $userId)
            ->where('program_type', 'Solo_Parent')
            ->where('status', 'confirmed')
            ->latest('updated_at')
            ->first();

        if ($spApproved) {
            $ts = $spApproved->updated_at ?? $spApproved->appointment_date;
            $isNew = $lastViewedAt && $ts
                ? \Carbon\Carbon::parse($ts)->gt(\Carbon\Carbon::parse($lastViewedAt))
                : !$lastViewedAt;
            $items->push([
                'id'         => $idCounter++,
                'type'       => 'solo_parent',
                'title'      => 'Solo Parent Appointment Approved',
                'body'       => 'Your Solo Parent appointment has been approved. Please wait for your eligibility result from MSWDO.',
                'data'       => null,
                'is_read'    => !$isNew,
                'is_new'     => $isNew,
                'read_at'    => null,
                'created_at' => $ts,
            ]);
        }

        // ── 5. Solo Parent eligibility validated ────────────────────────────
        $spEligible = \App\Models\Appointment::where('user_id', $userId)
            ->where('program_type', 'Solo_Parent')
            ->where('status', 'validated')
            ->latest('validated_at')
            ->first();

        if ($spEligible) {
            $ts = $spEligible->validated_at ?? $spEligible->updated_at;
            $isNew = $lastViewedAt && $ts
                ? \Carbon\Carbon::parse($ts)->gt(\Carbon\Carbon::parse($lastViewedAt))
                : !$lastViewedAt;
            $items->push([
                'id'         => $idCounter++,
                'type'       => 'solo_parent',
                'title'      => 'Eligible for Solo Parent ID',
                'body'       => 'Congratulations! You passed the eligibility assessment. Please submit your documents to complete your application.',
                'data'       => null,
                'is_read'    => !$isNew,
                'is_new'     => $isNew,
                'read_at'    => null,
                'created_at' => $ts,
            ]);
        }

        // ── 6. PWD requirements validated ───────────────────────────────────
        $pwdValidated = Application::where('user_id', $userId)
            ->whereIn('program_type', ['PWD_Assistance', 'PWD_New', 'PWD_Renewal'])
            ->where('id_status', 'processing')
            ->latest('completed_at')
            ->first();

        if ($pwdValidated) {
            $ts = $pwdValidated->completed_at ?? $pwdValidated->application_date;
            $isNew = $lastViewedAt && $ts
                ? \Carbon\Carbon::parse($ts)->gt(\Carbon\Carbon::parse($lastViewedAt))
                : !$lastViewedAt;
            $items->push([
                'id'         => $idCounter++,
                'type'       => 'pwd',
                'title'      => 'PWD Requirements Validated',
                'body'       => 'Your PWD requirements are validated. Please wait for a follow-up notice when your ID is ready.',
                'data'       => null,
                'is_read'    => !$isNew,
                'is_new'     => $isNew,
                'read_at'    => null,
                'created_at' => $ts,
            ]);
        }

        // ── 7. PWD ID ready ─────────────────────────────────────────────────
        $pwdReady = Application::where('user_id', $userId)
            ->whereIn('program_type', ['PWD_Assistance', 'PWD_New', 'PWD_Renewal'])
            ->where('id_status', 'ready_for_pickup')
            ->latest('id_ready_at')
            ->first();

        if ($pwdReady) {
            $ts = $pwdReady->id_ready_at ?? $pwdReady->updated_at;
            $isNew = $lastViewedAt && $ts
                ? \Carbon\Carbon::parse($ts)->gt(\Carbon\Carbon::parse($lastViewedAt))
                : !$lastViewedAt;
            $items->push([
                'id'         => $idCounter++,
                'type'       => 'pwd',
                'title'      => 'Your PWD ID is Ready',
                'body'       => 'Your PWD ID is ready for pick-up at the ' . ($pwdReady->municipality ?? 'MSWDO') . ' MSWDO Office.',
                'data'       => null,
                'is_read'    => !$isNew,
                'is_new'     => $isNew,
                'read_at'    => null,
                'created_at' => $ts,
            ]);
        }

        // ── 8. AICS appointment confirmed ───────────────────────────────────
        $aicsConfirmed = \App\Models\Appointment::where('user_id', $userId)
            ->whereIn('program_type', ['AICS_Medical', 'AICS_Burial'])
            ->where('status', 'confirmed')
            ->orderBy('updated_at', 'desc')
            ->get();

        foreach ($aicsConfirmed as $appt) {
            $ts = $appt->updated_at ?? $appt->appointment_date;
            $isNew = $lastViewedAt && $ts
                ? \Carbon\Carbon::parse($ts)->gt(\Carbon\Carbon::parse($lastViewedAt))
                : !$lastViewedAt;
            $label = match($appt->program_type) {
                'AICS_Medical' => 'AICS Medical Assistance',
                'AICS_Burial'  => 'AICS Burial Assistance',
                default        => str_replace('_', ' ', $appt->program_type),
            };
            $items->push([
                'id'         => $idCounter++,
                'type'       => 'aics',
                'title'      => 'Appointment Confirmed',
                'body'       => 'Your ' . $label . ' appointment has been confirmed.',
                'data'       => null,
                'is_read'    => !$isNew,
                'is_new'     => $isNew,
                'read_at'    => null,
                'created_at' => $ts,
            ]);
        }

        // ── 9. AICS requirements validated ──────────────────────────────────
        $aicsValidated = Application::where('user_id', $userId)
            ->whereIn('program_type', ['AICS_Medical', 'AICS_Burial'])
            ->where('status', 'approved')
            ->where('id_status', 'processing')
            ->orderBy('completed_at', 'desc')
            ->get();

        foreach ($aicsValidated as $app) {
            $ts = $app->completed_at ?? $app->application_date;
            $isNew = $lastViewedAt && $ts
                ? \Carbon\Carbon::parse($ts)->gt(\Carbon\Carbon::parse($lastViewedAt))
                : !$lastViewedAt;
            $label = match($app->program_type) {
                'AICS_Medical' => 'AICS Medical Assistance',
                'AICS_Burial'  => 'AICS Burial Assistance',
                default        => str_replace('_', ' ', $app->program_type),
            };
            $items->push([
                'id'         => $idCounter++,
                'type'       => 'aics',
                'title'      => 'AICS Requirements Validated',
                'body'       => 'Your ' . $label . ' requirements are validated. Please wait for grant release notice.',
                'data'       => null,
                'is_read'    => !$isNew,
                'is_new'     => $isNew,
                'read_at'    => null,
                'created_at' => $ts,
            ]);
        }

        // ── 10. AICS grant ready for pickup ─────────────────────────────────
        $aicsReady = Application::where('user_id', $userId)
            ->whereIn('program_type', ['AICS_Medical', 'AICS_Burial'])
            ->where('id_status', 'ready_for_pickup')
            ->orderBy('id_ready_at', 'desc')
            ->get();

        foreach ($aicsReady as $app) {
            $ts = $app->id_ready_at ?? $app->application_date;
            $isNew = $lastViewedAt && $ts
                ? \Carbon\Carbon::parse($ts)->gt(\Carbon\Carbon::parse($lastViewedAt))
                : !$lastViewedAt;
            $label = match($app->program_type) {
                'AICS_Medical' => 'AICS Medical Assistance',
                'AICS_Burial'  => 'AICS Burial Assistance',
                default        => str_replace('_', ' ', $app->program_type),
            };
            $items->push([
                'id'         => $idCounter++,
                'type'       => 'aics',
                'title'      => 'AICS Grant Ready for Pickup',
                'body'       => 'Your ' . $label . ' grant is ready for pickup at MSWDO.',
                'data'       => null,
                'is_read'    => !$isNew,
                'is_new'     => $isNew,
                'read_at'    => null,
                'created_at' => $ts,
            ]);
        }

        // ── 11. DB notifications table (real-time admin actions) ─────────────
        // Covers: validate, confirm, reschedule response, cancellation response,
        // and any other admin-triggered bell notifications.
        $dbNotifs = DB::table('notifications')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        foreach ($dbNotifs as $dbN) {
            $ts = $dbN->created_at;
            $isNew = $lastViewedAt && $ts
                ? \Carbon\Carbon::parse($ts)->gt(\Carbon\Carbon::parse($lastViewedAt))
                : !$lastViewedAt;
            $items->push([
                'id'         => 'db_' . $dbN->id,
                'type'       => $dbN->type ?? 'general',
                'title'      => $dbN->title,
                'body'       => $dbN->body,
                'data'       => null,
                'is_read'    => (bool) $dbN->is_read,
                'is_new'     => $isNew && !(bool) $dbN->is_read,
                'read_at'    => null,
                'created_at' => $ts,
            ]);
        }

        // ── 12. Announcements ────────────────────────────────────────────────
        $announcements = Announcement::where('is_active', true)
            ->where(function ($q) use ($user) {
                $q->whereNull('municipality')
                    ->orWhere('municipality', 'all')
                    ->orWhere('municipality', '')
                    ->orWhere('municipality', $user->municipality);
            })
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        foreach ($announcements as $ann) {
            $ts = $ann->created_at;
            $isNew = $lastViewedAt && $ts
                ? \Carbon\Carbon::parse($ts)->gt(\Carbon\Carbon::parse($lastViewedAt))
                : true;
            $items->push([
                'id'         => 'ann_' . $ann->id,
                'type'       => 'announcement',
                'title'      => $ann->title,
                'body'       => $ann->content,
                'data'       => null,
                'is_read'    => !$isNew,
                'is_new'     => $isNew,
                'read_at'    => null,
                'created_at' => $ts,
            ]);
        }

        // Sort all items by created_at descending
        $sorted = $items->sortByDesc(function ($item) {
            return $item['created_at'] ?? now();
        })->values();

        return response()->json([
            'success' => true,
            'data' => $sorted,
        ]);
    }

    public function markNotificationsRead(Request $request): JsonResponse
    {
        $userId = $request->user()->id;

        DB::table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
                'updated_at' => now(),
            ]);

        DB::table('notification_views')->updateOrInsert(
            ['user_id' => $userId],
            [
                'last_viewed_at' => now(),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Notifications marked as read.',
        ]);
    }

    public function applications(Request $request): JsonResponse
    {
        $status = $request->query('status', 'all');
        $query = Application::with(['fileMonitoring.fileUploads'])
            ->where('user_id', $request->user()->id);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        try {
            $applications = $query->orderBy('application_date', 'desc')->get();
        } catch (\Exception $e) {
            Log::error('Error fetching applications', [
                'user_id' => $request->user()->id,
                'error'   => $e->getMessage(),
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to fetch applications'], 500);
        }

        $mapped = $applications->map(function ($app) {
            $files = [];
            if ($app->fileMonitoring) {
                foreach ($app->fileMonitoring->fileUploads as $fu) {
                    $files[] = [
                        'id'               => $fu->id,
                        'requirement_name' => $fu->requirement_name,
                        'file_name'        => $fu->file_name,
                        'status'           => $fu->status ?? 'pending',
                        'admin_remarks'    => $fu->admin_remarks,
                    ];
                }
            }

            return [
                'id'               => $app->id,
                'program_type'     => $app->program_type,
                // Keep API stable even if some legacy DB rows have NULL status.
                'status'           => $app->status ?? Application::STATUS_PENDING,
                'barangay'         => $app->barangay ?? '',
                'municipality'     => $app->municipality ?? '',
                'created_at'       => $app->application_date ? $app->application_date->format('F d, Y') : null,
                'rejection_reason' => $app->admin_remarks,
                'files'            => $files,
            ];
        });

        return response()->json(['success' => true, 'data' => $mapped]);
    }

    public function submitApplication(Request $request): JsonResponse
    {
        $user = $request->user();

        // Check for pending applications
        $hasPending = Application::where('user_id', $user->id)
            ->whereIn('status', ['pending'])
            ->exists();

        if ($hasPending) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot apply for another program while you have a pending application. Please wait for it to be processed.',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'program_type' => 'required|string',
            'requirements' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Validate file sizes using service
        if ($request->hasFile('requirements')) {
            foreach ($request->file('requirements') as $reqName => $file) {
                $error = $this->applicationService->validateFileSize($file);
                if ($error) {
                    return response()->json(['success' => false, 'message' => $error], 422);
                }
            }
        }

        try {
            $this->applicationService->submit(
                $user,
                $request->program_type,
                $request->file('requirements') ?? []
            );

            return response()->json([
                'success' => true,
                'message' => 'Application submitted successfully! Your documents are now pending review.',
            ], 201);
        } catch (\RuntimeException $e) {
            // Business rule violations (e.g. ID already issued, reapplication blocked)
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'code'    => 'APPLICATION_BLOCKED',
            ], 409);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit application: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function reuploadFile(Request $request, $fileId): JsonResponse
    {
        $user = $request->user();

        $fileUpload = FileUpload::with(['fileMonitoring.application'])
            ->where('id', $fileId)
            ->whereHas('fileMonitoring', fn ($q) => $q->where('user_id', $user->id))
            ->firstOrFail();

        if ($fileUpload->status !== 'rejected') {
            return response()->json([
                'success' => false,
                'message' => 'Only rejected files can be re-uploaded.',
            ], 400);
        }

        $request->validate(['file' => 'required|file|max:25600']);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'pdf'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid file type. Only JPG, PNG, PDF are allowed.',
            ], 422);
        }

        try {
            $this->applicationService->reuploadFile($fileUpload, $file);

            return response()->json([
                'success' => true,
                'message' => 'File re-uploaded successfully. Your application is now pending review again.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to re-upload file: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function user(Request $request): JsonResponse
    {
        $user = $request->user();
        return response()->json([
            'success' => true,
            'message' => 'User profile retrieved.',
            'data' => [
                'id'                => $user->id,
                'full_name'         => $user->full_name,
                'first_name'        => $user->first_name,
                'middle_name'       => $user->middle_name,
                'last_name'         => $user->last_name,
                'email'             => $user->email,
                'username'          => $user->username,
                'role'              => $user->role,
                'gender'            => $user->gender,
                'municipality'      => $user->municipality,
                'barangay'          => $user->barangay,
                'mobile_number'     => $user->mobile_number,
                'phone_number'      => $user->phone_number,
                'birthdate'         => $user->birthdate,
                'date_of_birth'     => $user->date_of_birth,
                'age'               => $user->age,
                'status'            => $user->status ?? 'active',
                'email_verified_at' => $user->email_verified_at,
                'created_at'        => $user->created_at,
            ],
        ]);
    }

    public function updateUserProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'last_name' => 'required|string|max:100',
            'phone_number' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
        ]);

        $fullName = trim(
            $validated['first_name'] .
            (!empty($validated['middle_name']) ? ' ' . trim($validated['middle_name']) : '') .
            ' ' . $validated['last_name']
        );

        $birthdate = \Carbon\Carbon::parse($validated['date_of_birth']);
        $age = now()->diffInYears($birthdate);

        $user->update([
            'first_name' => trim($validated['first_name']),
            'middle_name' => isset($validated['middle_name']) ? trim((string) $validated['middle_name']) : null,
            'last_name' => trim($validated['last_name']),
            'full_name' => $fullName,
            'phone_number' => trim($validated['phone_number']),
            'mobile_number' => trim($validated['phone_number']),
            'date_of_birth' => $validated['date_of_birth'],
            'birthdate' => $validated['date_of_birth'],
            'age' => $age,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'first_name' => $user->first_name,
                'middle_name' => $user->middle_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'username' => $user->username,
                'role' => $user->role,
                'municipality' => $user->municipality,
                'barangay' => $user->barangay,
                'mobile_number' => $user->mobile_number,
                'phone_number' => $user->phone_number,
                'birthdate' => $user->birthdate,
                'date_of_birth' => $user->date_of_birth,
                'age' => $user->age,
            ],
        ]);
    }

    public function applicationDetail(Request $request, $id): JsonResponse
    {
        $application = Application::with(['fileMonitoring.fileUploads'])
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$application) {
            return response()->json(['success' => false, 'message' => 'Application not found.'], 404);
        }

        $files = [];
        if ($application->fileMonitoring) {
            foreach ($application->fileMonitoring->fileUploads as $fu) {
                $files[] = [
                    'id'               => $fu->id,
                    'requirement_name' => $fu->requirement_name,
                    'file_name'        => $fu->file_name,
                    'file_url'         => $fu->file_path ? asset('storage/' . $fu->file_path) : null,
                        'status'           => $fu->status ?? 'pending',
                    'admin_remarks'    => $fu->admin_remarks,
                    'uploaded_at'      => $fu->uploaded_at ? $fu->uploaded_at->format('F d, Y h:i A') : null,
                ];
            }
        }

        $statusMessage = match ($application->status) {
            Application::STATUS_APPROVED => 'Congratulations! Your application has been approved.',
            Application::STATUS_REJECTED => 'Your application has been rejected.',
            Application::STATUS_PENDING  => 'Your application is being processed. Please wait for approval.',
            default                      => 'Status unknown.',
        };

        return response()->json([
            'success' => true,
            'message' => 'Application details retrieved.',
            'data' => [
                'id'               => $application->id,
                'program_type'     => $application->program_type,
                'full_name'        => $application->full_name,
                'age'              => $application->age,
                'gender'           => $application->gender,
                'contact_number'   => $application->contact_number,
                'barangay'         => $application->barangay ?? '',
                'municipality'     => $application->municipality ?? '',
                    'status'           => $application->status ?? Application::STATUS_PENDING,
                'status_message'   => $statusMessage,
                'rejection_reason' => $application->admin_remarks,
                    'can_resubmit'     => ($application->status ?? Application::STATUS_PENDING) === Application::STATUS_REJECTED,
                'application_date' => $application->application_date ? $application->application_date->format('F d, Y') : null,
                'stage'            => $application->stage,
                'files'            => $files,
            ],
        ]);
    }

    public function resubmitApplication(Request $request, $id): JsonResponse
    {
        $user = $request->user();

        $application = Application::with(['fileMonitoring.fileUploads'])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$application) {
            return response()->json(['success' => false, 'message' => 'Application not found.'], 404);
        }

        if ($application->status !== Application::STATUS_REJECTED) {
            return response()->json([
                'success' => false,
                'message' => 'Only rejected applications can be re-submitted.',
            ], 400);
        }

        $validator = Validator::make($request->all(), ['requirements' => 'required|array']);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Validate file sizes
        if ($request->hasFile('requirements')) {
            foreach ($request->file('requirements') as $reqName => $file) {
                $error = $this->applicationService->validateFileSize($file);
                if ($error) {
                    return response()->json(['success' => false, 'message' => $error], 422);
                }
            }
        }

        try {
            $this->applicationService->resubmit($application, $request->file('requirements') ?? []);

            return response()->json([
                'success' => true,
                'message' => 'Application re-submitted successfully! Your documents are now pending review again.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to re-submit application: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['success' => true, 'message' => 'Logged out successfully']);
    }

    public function registerDeviceToken(Request $request): JsonResponse
    {
        $request->validate([
            'token'       => 'required|string',
            'device_type' => 'nullable|string|in:android,ios',
            'device_name' => 'nullable|string',
        ]);

        \DB::table('device_tokens')->updateOrInsert(
            ['user_id' => $request->user()->id, 'token' => $request->token],
            [
                'device_type'  => $request->device_type,
                'device_name'  => $request->device_name,
                'is_active'    => true,
                'last_used_at' => now(),
                'updated_at'   => now(),
                'created_at'   => now(),
            ]
        );

        return response()->json(['success' => true, 'message' => 'Device token registered successfully']);
    }

    public function removeDeviceToken(Request $request): JsonResponse
    {
        $request->validate(['token' => 'required|string']);

        \DB::table('device_tokens')
            ->where('user_id', $request->user()->id)
            ->where('token', $request->token)
            ->delete();

        return response()->json(['success' => true, 'message' => 'Device token removed successfully']);
    }
}
