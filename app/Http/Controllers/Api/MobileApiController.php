<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\FileUpload;
use App\Models\User;
use App\Services\ApplicationService;
use App\Services\AuthService;
use App\Services\DashboardService;
use App\Services\OtpService;
use App\Services\RegistrationValidationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
    ) {
    }

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
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'last_name' => 'required|string|max:50',
            'username' => 'required|string|max:20|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'mobile_number' => 'required|string',
            'birthdate' => 'required|date',
            'municipality' => 'required|string',
            'barangay' => 'required|string',
            'gender' => 'required|string|in:Male,Female',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $customErrors = RegistrationValidationService::validateRegistration($request->all());
        if (!empty($customErrors)) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $customErrors,
            ], 422);
        }

        // Age check
        $dob = new \DateTime($request->birthdate);
        $age = (new \DateTime())->diff($dob)->y;
        if ($age < 18) {
            return response()->json([
                'success' => false,
                'message' => 'You must be at least 18 years old to register.',
                'errors' => ['birthdate' => ['You must be at least 18 years old to register.']],
            ], 422);
        }

        try {
            $user = $this->auth->register($request->all(), $age);
            $otpCode = $this->otp->generate($user);
            $this->otp->sendVerificationEmail($user, $otpCode);

            return response()->json([
                'success' => true,
                'message' => 'Registration successful! Check your Gmail for your password and OTP verification code.',
                'data' => ['email' => $user->email],
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
            'otp' => 'required|string',
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

        $otpCode = $this->otp->generate($user);
        $this->otp->sendVerificationEmail($user, $otpCode);

        return response()->json([
            'success' => true,
            'message' => 'A new OTP has been sent to your email address.',
        ]);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        // Always return success to prevent user enumeration
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $otpCode = $this->otp->generate($user);
            $this->otp->sendPasswordResetEmail($user, $otpCode);
        }

        return response()->json([
            'success' => true,
            'message' => 'If that email exists, a password reset OTP has been sent.',
        ]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string',
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
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $user = $this->auth->attemptLogin($request->login, $request->password);
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            $status = match ($e->getMessage()) {
                'User not found' => 404,
                'Incorrect password' => 401,
                'Account not yet verified' => 403,
                default => 401,
            };
            return response()->json(['success' => false, 'message' => $e->getMessage()], $status);
        }

        $token = $this->auth->createToken($user);

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'username' => $user->username,
                'role' => $user->role,
                'municipality' => $user->municipality,
                'barangay' => $user->barangay,
            ],
        ]);
    }

    // ── PROTECTED ENDPOINTS ────────────────────────────────────────────────

    public function dashboard(Request $request): JsonResponse
    {
        $user = $request->user();
        $stats = $this->dashboardService->getMobileStats($user);
        $recentApps = $this->dashboardService->getRecentApplications($user);
        $announcements = $this->dashboardService->getAnnouncements($user);

        // Calculate unread count
        $unreadSystemCount = \DB::table('notifications')
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->count();

        $lastViewed = \App\Models\NotificationView::where('user_id', $user->id)->first();
        $lastViewedAt = $lastViewed ? $lastViewed->last_viewed_at : null;

        $unreadAnnouncementsCount = $lastViewedAt
            ? $announcements->filter(function ($ann) use ($lastViewedAt) {
                return \Carbon\Carbon::parse($ann['created_at'])->gt(\Carbon\Carbon::parse($lastViewedAt));
            })->count()
            : $announcements->count();

        return response()->json([
            'success' => true,
            'data' => [
                'stats' => $stats,
                'recent_applications' => $recentApps->map(fn($app) => [
                    'id' => $app->id,
                    'program_type' => $app->program_type,
                    'status' => $app->status,
                    'barangay' => $app->barangay ?? '',
                    'municipality' => $app->municipality ?? '',
                    'created_at' => $app->application_date ? $app->application_date->format('F d, Y') : null,
                ]),
                'announcements' => $announcements,
                'unread_notifications_count' => $unreadSystemCount + $unreadAnnouncementsCount,
            ],
        ]);
    }

    public function notifications(Request $request): JsonResponse
    {
        $user = $request->user();

        // 1. Get system notifications
        $systemNotifs = \DB::table('notifications')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($notif) {
                return [
                    'id' => 'sys_' . $notif->id,
                    'title' => $notif->title,
                    'body' => $notif->body,
                    'type' => $notif->type, // e.g., 'solo_parent'
                    'is_read' => (bool) $notif->is_read,
                    'created_at' => \Carbon\Carbon::parse($notif->created_at)->format('Y-m-d H:i:s'),
                ];
            });

        // 2. Get announcements
        $announcements = $this->dashboardService->getAllAnnouncements($user);
        $lastViewed = \App\Models\NotificationView::where('user_id', $user->id)->first();
        $lastViewedAt = $lastViewed ? $lastViewed->last_viewed_at : null;

        $announcementNotifs = $announcements->map(function ($ann) use ($lastViewedAt) {
            $isRead = $lastViewedAt && \Carbon\Carbon::parse($ann['created_at'])->lte(\Carbon\Carbon::parse($lastViewedAt));
            return [
                'id' => 'ann_' . $ann['id'],
                'title' => $ann['title'],
                'body' => \Illuminate\Support\Str::limit($ann['content'] ?? 'New Announcement', 120),
                'type' => 'announcement',
                'is_read' => $isRead,
                'created_at' => \Carbon\Carbon::parse($ann['created_at'])->format('Y-m-d H:i:s'),
            ];
        });

        // 3. Merge and sort
        $all = $systemNotifs->concat($announcementNotifs)
            ->sortByDesc('created_at')
            ->values()
            ->all();

        return response()->json(['success' => true, 'data' => $all]);
    }

    public function markNotificationsRead(Request $request): JsonResponse
    {
        $user = $request->user();

        // 1. Mark system notifications as read
        \DB::table('notifications')
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        // 2. Update announcement view timestamp
        \App\Models\NotificationView::updateOrCreate(
            ['user_id' => $user->id],
            ['last_viewed_at' => now()]
        );

        return response()->json(['success' => true, 'message' => 'Notifications marked as read']);
    }

    public function announcements(Request $request): JsonResponse
    {
        $announcements = $this->dashboardService->getAllAnnouncements($request->user());
        return response()->json(['success' => true, 'data' => $announcements]);
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
                'error' => $e->getMessage(),
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to fetch applications'], 500);
        }

        $mapped = $applications->map(function ($app) {
            $files = [];
            if ($app->fileMonitoring) {
                foreach ($app->fileMonitoring->fileUploads as $fu) {
                    $files[] = [
                        'id' => $fu->id,
                        'requirement_name' => $fu->requirement_name,
                        'file_name' => $fu->file_name,
                        'status' => $fu->status ?? 'pending',
                        'admin_remarks' => $fu->admin_remarks,
                    ];
                }
            }

            return [
                'id' => $app->id,
                'program_type' => $app->program_type,
                // Keep API stable even if some legacy DB rows have NULL status.
                'status' => $app->status ?? Application::STATUS_PENDING,
                'barangay' => $app->barangay ?? '',
                'municipality' => $app->municipality ?? '',
                'created_at' => $app->application_date ? $app->application_date->format('F d, Y') : null,
                'rejection_reason' => $app->admin_remarks,
                'files' => $files,
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
                'errors' => $validator->errors(),
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
                'code' => 'APPLICATION_BLOCKED',
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
            ->whereHas('fileMonitoring', fn($q) => $q->where('user_id', $user->id))
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
                'id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'username' => $user->username,
                'role' => $user->role,
                'municipality' => $user->municipality,
                'barangay' => $user->barangay,
                'mobile_number' => $user->mobile_number,
                'birthdate' => $user->birthdate,
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
                    'id' => $fu->id,
                    'requirement_name' => $fu->requirement_name,
                    'file_name' => $fu->file_name,
                    'file_url' => $fu->file_path ? asset('storage/' . $fu->file_path) : null,
                    'status' => $fu->status ?? 'pending',
                    'admin_remarks' => $fu->admin_remarks,
                    'uploaded_at' => $fu->uploaded_at ? $fu->uploaded_at->format('F d, Y h:i A') : null,
                ];
            }
        }

        $statusMessage = match ($application->status) {
            Application::STATUS_APPROVED => 'Congratulations! Your application has been approved.',
            Application::STATUS_REJECTED => 'Your application has been rejected.',
            Application::STATUS_PENDING => 'Your application is being processed. Please wait for approval.',
            default => 'Status unknown.',
        };

        return response()->json([
            'success' => true,
            'message' => 'Application details retrieved.',
            'data' => [
                'id' => $application->id,
                'program_type' => $application->program_type,
                'full_name' => $application->full_name,
                'age' => $application->age,
                'gender' => $application->gender,
                'contact_number' => $application->contact_number,
                'barangay' => $application->barangay ?? '',
                'municipality' => $application->municipality ?? '',
                'status' => $application->status ?? Application::STATUS_PENDING,
                'status_message' => $statusMessage,
                'rejection_reason' => $application->admin_remarks,
                'can_resubmit' => ($application->status ?? Application::STATUS_PENDING) === Application::STATUS_REJECTED,
                'application_date' => $application->application_date ? $application->application_date->format('F d, Y') : null,
                'stage' => $application->stage,
                'files' => $files,
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
                'errors' => $validator->errors(),
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
            'token' => 'required|string',
            'device_type' => 'nullable|string|in:android,ios',
            'device_name' => 'nullable|string',
        ]);

        \DB::table('device_tokens')->updateOrInsert(
            ['user_id' => $request->user()->id, 'token' => $request->token],
            [
                'device_type' => $request->device_type,
                'device_name' => $request->device_name,
                'is_active' => true,
                'last_used_at' => now(),
                'updated_at' => now(),
                'created_at' => now(),
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
