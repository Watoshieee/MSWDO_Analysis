<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Application;
use App\Models\Barangay;
use App\Models\Municipality;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class MobileAuthController extends Controller
{
    // ─── Allowed municipalities ───────────────────────────────────────
    private const ALLOWED_MUNICIPALITIES = ['Magdalena', 'Liliw', 'Majayjay'];

    // ══════════════════════════════════════════════════════════════════
    //  GET /mobile-api/municipalities
    //  Returns municipalities with their barangay lists
    // ══════════════════════════════════════════════════════════════════
    public function municipalities(): JsonResponse
    {
        try {
            $data = [];

            foreach (self::ALLOWED_MUNICIPALITIES as $muni) {
                $barangays = Barangay::where('municipality', $muni)
                    ->select('name')
                    ->distinct()
                    ->orderBy('name')
                    ->pluck('name')
                    ->toArray();

                $data[] = [
                    'name'      => $muni,
                    'barangays' => $barangays,
                ];
            }

            return response()->json(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            Log::error('[MobileAPI] municipalities: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to load municipalities.'], 500);
        }
    }

    // ══════════════════════════════════════════════════════════════════
    //  POST /mobile-api/register
    //  Validates, stores pending data in cache, sends OTP email
    // ══════════════════════════════════════════════════════════════════
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name'    => ['required', 'string', 'min:2', 'max:50', 'regex:/^[a-zA-ZÀ-ÿ\s\'\-\.]+$/'],
            'middle_name'   => ['nullable', 'string', 'max:50', 'regex:/^[a-zA-ZÀ-ÿ\s\'\-\.]*$/'],
            'last_name'     => ['required', 'string', 'min:2', 'max:50', 'regex:/^[a-zA-ZÀ-ÿ\s\'\-\.]+$/'],
            'username'      => ['required', 'string', 'min:4', 'max:20', 'regex:/^[a-zA-Z0-9_]+$/', 'unique:users,username'],
            'email'         => ['required', 'string', 'email:rfc', 'max:100', 'unique:users,email'],
            'mobile_number' => ['required', 'string', 'regex:/^\+639\d{9}$/'],
            'birthdate'     => [
                'required',
                'date',
                'after_or_equal:1920-01-01',
                'before:' . now()->subYears(18)->format('Y-m-d'),
            ],
            'municipality'  => ['required', 'string', 'in:' . implode(',', self::ALLOWED_MUNICIPALITIES)],
            'barangay'      => ['required', 'string', 'exists:barangays,name'],
            'password'      => [
                'required', 'string', 'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#_\-\.])[A-Za-z\d@$!%*?&#_\-\.]{8,}$/',
            ],
        ], [
            'first_name.required'    => 'First name is required.',
            'first_name.min'         => 'First name must be at least 2 characters.',
            'first_name.regex'       => 'First name may only contain letters, spaces, hyphens, apostrophes.',
            'last_name.required'     => 'Last name is required.',
            'last_name.min'          => 'Last name must be at least 2 characters.',
            'last_name.regex'        => 'Last name may only contain letters, spaces, hyphens, apostrophes.',
            'middle_name.regex'      => 'Middle name may only contain letters, spaces, hyphens, apostrophes.',
            'username.min'           => 'Username must be at least 4 characters.',
            'username.max'           => 'Username cannot exceed 20 characters.',
            'username.regex'         => 'Username may only contain letters, numbers, and underscores.',
            'username.unique'        => 'This username is already taken.',
            'email.unique'           => 'This email address is already registered.',
            'mobile_number.regex'    => 'Mobile number must be in format +639XXXXXXXXX.',
            'birthdate.after_or_equal' => 'Birthdate cannot be earlier than 1920.',
            'birthdate.before'       => 'You must be at least 18 years old to register.',
            'municipality.in'        => 'Please select a valid municipality.',
            'barangay.exists'        => 'Please select a valid barangay.',
            'password.min'           => 'Password must be at least 8 characters.',
            'password.regex'         => 'Password must include uppercase, lowercase, number, and special character.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $data = $validator->validated();

            // Build full_name from parts
            $fullName = trim($data['first_name']);
            if (!empty($data['middle_name'])) {
                $fullName .= ' ' . trim($data['middle_name']);
            }
            $fullName .= ' ' . trim($data['last_name']);

            // Calculate age
            $birthdate = Carbon::parse($data['birthdate']);
            $age = $birthdate->age;

            // Generate 6-digit OTP
            $otp = rand(100000, 999999);
            $cacheKey = 'mobile_otp_' . strtolower(trim($data['email']));

            // Store pending registration in cache for 15 minutes
            Cache::put($cacheKey, [
                'registration' => [
                    'username'      => trim($data['username']),
                    'email'         => strtolower(trim($data['email'])),
                    'mobile_number' => trim($data['mobile_number']),
                    'password'      => Hash::make($data['password']),
                    'full_name'     => $fullName,
                    'birthdate'     => $data['birthdate'],
                    'age'           => $age,
                    'municipality'  => $data['municipality'],
                    'barangay'      => trim($data['barangay']),
                ],
                'otp'         => (string) $otp,
                'otp_expires' => now()->addMinutes(10)->toDateTimeString(),
            ], 900); // 15 min cache TTL

            // Send OTP email
            try {
                Mail::send('emails.otp', [
                    'full_name' => $fullName,
                    'otp'       => $otp,
                ], function ($message) use ($data, $fullName) {
                    $message->from(config('mail.from.address'), 'MSWDO Member Portal')
                        ->to(strtolower(trim($data['email'])), $fullName)
                        ->subject('Email Verification – MSWDO Member Portal');
                });
            } catch (Exception $e) {
                Log::error('[MobileAPI] Failed to send OTP email: ' . $e->getMessage());
                // Proceed — user can resend
            }

            return response()->json([
                'success' => true,
                'message' => 'Verification OTP sent to your email.',
                'email'   => strtolower(trim($data['email'])),
            ]);

        } catch (Exception $e) {
            Log::error('[MobileAPI] register: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Registration failed. ' . $e->getMessage()], 500);
        }
    }

    // ══════════════════════════════════════════════════════════════════
    //  POST /mobile-api/verify-otp
    //  Verifies OTP and creates user in DB
    // ══════════════════════════════════════════════════════════════════
    public function verifyOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp'   => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid request.', 'errors' => $validator->errors()], 422);
        }

        $email    = strtolower(trim($request->email));
        $cacheKey = 'mobile_otp_' . $email;
        $cached   = Cache::get($cacheKey);

        if (!$cached) {
            return response()->json(['success' => false, 'message' => 'OTP expired or not found. Please register again.'], 400);
        }

        if ($request->otp !== $cached['otp']) {
            return response()->json(['success' => false, 'message' => 'Invalid OTP. Please try again.'], 400);
        }

        if (now()->gt($cached['otp_expires'])) {
            Cache::forget($cacheKey);
            return response()->json(['success' => false, 'message' => 'OTP has expired. Please request a new one.'], 400);
        }

        try {
            // Create user in DB
            $user = User::create(array_merge($cached['registration'], [
                'role'              => 'user',
                'status'            => 'active',
                'email_verified_at' => now(),
            ]));

            // Clear cache
            Cache::forget($cacheKey);

            return response()->json([
                'success' => true,
                'message' => 'Account verified successfully! Welcome to MSWDO.',
                'user'    => [
                    'id'         => $user->id,
                    'username'   => $user->username,
                    'full_name'  => $user->full_name,
                    'email'      => $user->email,
                    'role'       => $user->role,
                ],
            ]);

        } catch (Exception $e) {
            Log::error('[MobileAPI] verifyOtp user creation: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Account creation failed. ' . $e->getMessage()], 500);
        }
    }

    // ══════════════════════════════════════════════════════════════════
    //  POST /mobile-api/resend-otp
    //  Resends OTP if the current one is expired
    // ══════════════════════════════════════════════════════════════════
    public function resendOtp(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Invalid email.'], 422);
        }

        $email    = strtolower(trim($request->email));
        $cacheKey = 'mobile_otp_' . $email;
        $cached   = Cache::get($cacheKey);

        if (!$cached) {
            return response()->json(['success' => false, 'message' => 'Session expired. Please register again.'], 400);
        }

        // If current OTP is still valid, don't resend
        if (now()->lt($cached['otp_expires'])) {
            return response()->json(['success' => false, 'message' => 'Your current OTP is still valid. Please check your email.'], 400);
        }

        // Generate new OTP and update cache
        $otp = rand(100000, 999999);
        $cached['otp']         = (string) $otp;
        $cached['otp_expires'] = now()->addMinutes(10)->toDateTimeString();
        Cache::put($cacheKey, $cached, 900);

        $fullName = $cached['registration']['full_name'];

        try {
            Mail::send('emails.otp', [
                'full_name' => $fullName,
                'otp'       => $otp,
            ], function ($message) use ($email, $fullName) {
                $message->from(config('mail.from.address'), 'MSWDO Member Portal')
                    ->to($email, $fullName)
                    ->subject('New OTP – MSWDO Member Portal');
            });

            return response()->json(['success' => true, 'message' => 'New OTP sent to your email.']);
        } catch (Exception $e) {
            Log::error('[MobileAPI] resendOtp: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send OTP. Please try again.'], 500);
        }
    }

    // ══════════════════════════════════════════════════════════════════
    //  POST /mobile-api/login
    //  Validates credentials and returns user data + Sanctum token
    // ══════════════════════════════════════════════════════════════════
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Username/email and password are required.'], 422);
        }

        $loginInput = trim($request->login);
        $loginType  = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($loginType, $loginInput)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'The credentials do not match our records.'], 401);
        }

        // Only 'user' role allowed in mobile app
        if ($user->role !== 'user') {
            return response()->json(['success' => false, 'message' => 'Admin accounts cannot log in through the mobile app.'], 403);
        }

        if ($user->status !== 'active') {
            return response()->json(['success' => false, 'message' => 'Your account is inactive. Please contact the MSWDO office.'], 403);
        }

        if (!$user->hasVerifiedEmail()) {
            return response()->json(['success' => false, 'message' => 'Your email is not yet verified. Please complete OTP verification.'], 403);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'The password is incorrect.'], 401);
        }

        // Revoke old mobile tokens and issue a fresh one
        $user->tokens()->where('name', 'mobile-app')->delete();
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful. Welcome back, ' . $user->full_name . '!',
            'token'   => $token,
            'user'    => [
                'id'           => $user->id,
                'username'     => $user->username,
                'full_name'    => $user->full_name,
                'email'        => $user->email,
                'mobile_number'=> $user->mobile_number,
                'birthdate'    => $user->birthdate,
                'municipality' => $user->municipality,
                'barangay'     => $user->barangay,
                'role'         => $user->role,
                'status'       => $user->status,
            ],
        ]);
    }

    // ══════════════════════════════════════════════════════════════════
    //  PRIVATE HELPER — resolve user from Bearer token
    // ══════════════════════════════════════════════════════════════════
    private function resolveTokenUser(Request $request): ?User
    {
        $bearer = $request->bearerToken();
        if (!$bearer) return null;
        $pat = PersonalAccessToken::findToken($bearer);
        if (!$pat) return null;
        /** @var User $user */
        $user = $pat->tokenable;
        return ($user instanceof User) ? $user : null;
    }

    // ══════════════════════════════════════════════════════════════════
    //  GET /mobile-api/dashboard   (Bearer token required)
    //  Returns: stats, recent_applications, announcements, user
    // ══════════════════════════════════════════════════════════════════
    public function dashboard(Request $request): JsonResponse
    {
        $user = $this->resolveTokenUser($request);
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);

        try {
            $apps = Application::where('user_id', $user->id)->get();

            $stats = [
                'total'    => $apps->count(),
                'pending'  => $apps->where('status', 'pending')->count(),
                'approved' => $apps->where('status', 'approved')->count(),
                'rejected' => $apps->where('status', 'rejected')->count(),
            ];

            $recent = $apps->sortByDesc('application_date')->take(5)->map(function ($a) {
                return [
                    'id'           => $a->id,
                    'program_type' => $a->program_type,
                    'status'       => $a->status,
                    'barangay'     => $a->barangay,
                    'municipality' => $a->municipality,
                    'created_at'   => $a->application_date
                        ? Carbon::parse($a->application_date)->format('F j, Y')
                        : '',
                ];
            })->values();

            $announcements = Announcement::where('is_active', true)
                ->orderByDesc('created_at')->take(10)
                ->get()->map(function ($ann) {
                    return [
                        'id'         => $ann->id,
                        'title'      => $ann->title,
                        'content'    => $ann->content ?? '',
                        'created_at' => $ann->created_at
                            ? Carbon::parse($ann->created_at)->format('F j, Y')
                            : '',
                    ];
                });

            return response()->json([
                'success' => true,
                'data'    => [
                    'stats'                => $stats,
                    'recent_applications'  => $recent,
                    'announcements'        => $announcements,
                    'user'                 => [
                        'id'        => $user->id,
                        'full_name' => $user->full_name,
                        'email'     => $user->email,
                    ],
                ],
            ]);
        } catch (Exception $e) {
            Log::error('[MobileAPI] dashboard: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to load dashboard.'], 500);
        }
    }

    // ══════════════════════════════════════════════════════════════════
    //  GET /mobile-api/applications   (Bearer token required)
    //  Query params: ?status=all|pending|approved|rejected
    // ══════════════════════════════════════════════════════════════════
    public function getApplications(Request $request): JsonResponse
    {
        $user = $this->resolveTokenUser($request);
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);

        try {
            $query = Application::where('user_id', $user->id);
            $status = $request->query('status', 'all');
            if ($status !== 'all') $query->where('status', $status);

            $apps = $query->orderByDesc('application_date')->get()->map(function ($a) {
                return [
                    'id'           => $a->id,
                    'program_type' => $a->program_type,
                    'status'       => $a->status,
                    'barangay'     => $a->barangay,
                    'municipality' => $a->municipality,
                    'stage'        => $a->stage,
                    'admin_remarks'=> $a->admin_remarks ?? '',
                    'created_at'   => $a->application_date
                        ? Carbon::parse($a->application_date)->format('F j, Y')
                        : '',
                ];
            });

            return response()->json(['success' => true, 'applications' => $apps]);
        } catch (Exception $e) {
            Log::error('[MobileAPI] getApplications: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to load applications.'], 500);
        }
    }

    // ══════════════════════════════════════════════════════════════════
    //  POST /mobile-api/applications   (Bearer token required)
    //  Body: { program_type, full_name, age, gender, contact_number,
    //          municipality, barangay, form_data{} }
    // ══════════════════════════════════════════════════════════════════
    public function submitApplication(Request $request): JsonResponse
    {
        $user = $this->resolveTokenUser($request);
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);

        $validator = Validator::make($request->all(), [
            'program_type'   => 'required|string',
            'full_name'      => 'required|string|max:255',
            'age'            => 'required|integer|min:1|max:120',
            'gender'         => 'required|string|in:Male,Female,Other',
            'contact_number' => 'required|string|max:20',
            'municipality'   => 'required|string',
            'barangay'       => 'required|string',
            'form_data'      => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $app = Application::create([
                'user_id'        => $user->id,
                'program_type'   => $request->program_type,
                'full_name'      => $request->full_name,
                'age'            => $request->age,
                'gender'         => $request->gender,
                'contact_number' => $request->contact_number,
                'municipality'   => $request->municipality ?? $user->municipality,
                'barangay'       => $request->barangay ?? $user->barangay,
                'status'         => 'pending',
                'stage'          => 'submitted',
                'application_date' => now(),
                'year'           => now()->year,
                'form_data'      => $request->form_data ?? [],
            ]);

            return response()->json([
                'success'        => true,
                'message'        => 'Application submitted successfully! Your application is now pending review.',
                'application_id' => $app->id,
            ], 201);
        } catch (Exception $e) {
            Log::error('[MobileAPI] submitApplication: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Submission failed. ' . $e->getMessage()], 500);
        }
    }

    // ══════════════════════════════════════════════════════════════════
    //  GET /mobile-api/announcements   (Bearer token required)
    // ══════════════════════════════════════════════════════════════════
    public function getAnnouncements(Request $request): JsonResponse
    {
        $user = $this->resolveTokenUser($request);
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);

        try {
            $anns = Announcement::where('is_active', true)
                ->orderByDesc('created_at')->get()
                ->map(function ($ann) {
                    return [
                        'id'         => $ann->id,
                        'title'      => $ann->title,
                        'content'    => $ann->content ?? '',
                        'type'       => $ann->type ?? '',
                        'created_at' => $ann->created_at
                            ? Carbon::parse($ann->created_at)->format('F j, Y')
                            : '',
                    ];
                });

            return response()->json(['success' => true, 'announcements' => $anns]);
        } catch (Exception $e) {
            Log::error('[MobileAPI] getAnnouncements: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to load announcements.'], 500);
        }
    }

    // ══════════════════════════════════════════════════════════════════
    //  POST /mobile-api/logout   (Bearer token required)
    // ══════════════════════════════════════════════════════════════════
    public function logout(Request $request): JsonResponse
    {
        $user = $this->resolveTokenUser($request);
        if ($user) {
            $user->tokens()->where('name', 'mobile-app')->delete();
        }
        return response()->json(['success' => true, 'message' => 'Logged out successfully.']);
    }
}
