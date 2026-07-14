<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\NewUserIdVerificationAdminMail;
use App\Models\User;
use App\Services\DeviceRegistrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class OtpController extends Controller
{
    public function __construct(private DeviceRegistrationService $deviceService) {}
    public function showVerifyForm()
    {
        // Must have pending registration data in session
        if (!session('pending_registration') && !session('otp_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        // ------------------------------------------------------------------
        // CASE 1: Session-based OTP (new registration — user not in DB yet)
        // ------------------------------------------------------------------
        if (session('pending_registration')) {
            $pendingOtp     = session('pending_otp');
            $otpExpiresAt   = session('pending_otp_expires_at');
            $registrationData = session('pending_registration');

            // Normalize both OTPs to strings and trim whitespace
            $inputOtp = trim((string) $request->otp);
            $storedOtp = trim((string) $pendingOtp);

            // Check if OTP has expired
            if (now()->gt($otpExpiresAt)) {
                return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
            }

            // Check OTP code
            if ($inputOtp !== $storedOtp) {
                return back()->withErrors(['otp' => 'Invalid OTP code. Please check and try again.']);
            }

            // OTP is correct — NOW create the user in the database
            $fingerprint = session('pending_device_fingerprint');
            try {
                $validIdPath = $registrationData['valid_id_path'] ?? null;
                $validIdFilename = $registrationData['valid_id_filename'] ?? null;
                unset(
                    $registrationData['valid_id_path'],
                    $registrationData['valid_id_filename']
                );

                $user = User::create(array_merge($registrationData, [
                    'role'                    => 'user',
                    'status'                  => 'inactive',
                    'email_verified_at'       => now(),
                    'valid_id_path'           => $validIdPath,
                    'valid_id_filename'       => $validIdFilename,
                    'id_verification_status'  => User::ID_STATUS_PENDING,
                ]));

                if ($validIdPath && Storage::disk('public')->exists($validIdPath)) {
                    $extension = pathinfo($validIdPath, PATHINFO_EXTENSION);
                    $newPath = 'valid-ids/' . $user->id . '/valid-id.' . $extension;
                    Storage::disk('public')->makeDirectory('valid-ids/' . $user->id);
                    Storage::disk('public')->move($validIdPath, $newPath);
                    $user->update(['valid_id_path' => $newPath]);
                }

                $this->notifyAdminsOfPendingIdVerification($user);

                // Attach device fingerprint and set cookie
                if ($fingerprint) {
                    $this->deviceService->attachUser($fingerprint, $user->id);
                    session()->forget('pending_device_fingerprint');
                }
            } catch (Exception $e) {
                Log::error('Failed to create user after OTP: ' . $e->getMessage());
                return back()->withErrors(['otp' => 'Account creation failed. Please try again.']);
            }

            // Clear all pending session data except temp password
            $tempPassword = session('temp_password');
            session()->forget([
                'pending_registration',
                'pending_otp',
                'pending_otp_expires_at',
                'pending_email',
                'pending_full_name',
            ]);

            // Store user ID and temp password for change password page
            session([
                'change_password_user_id' => $user->id,
                'must_change_password' => true,
            ]);

            return redirect()->route('password.change')
                ->cookie($this->deviceService->makeCookie($fingerprint ?? ''))
                ->with('success', 'Email verified successfully! Please set your new password. Your valid ID will be reviewed by an admin before you can login.');
        }

        // ------------------------------------------------------------------
        // CASE 2: Legacy DB-based OTP (e.g. admin-created accounts / resend)
        // ------------------------------------------------------------------
        $userId = session('otp_user_id');
        if (!$userId) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Session expired. Please register again.']);
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['error' => 'User not found.']);
        }

        if ($user->verifyOtp($request->otp)) {
            session()->forget('otp_user_id');
            Auth::login($user);

            if ($user->role === 'super_admin') {
                return redirect()->route('superadmin.dashboard')
                    ->with('success', 'Email verified successfully! Welcome Super Admin.');
            } elseif ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Email verified successfully! Welcome Admin.');
            } else {
                return redirect()->route('user.dashboard')
                    ->with('success', 'Email verified successfully! Welcome to MSWDO.');
            }
        }

        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }

    public function resend()
    {
        // ------------------------------------------------------------------
        // CASE 1: Resend for session-based pending registration
        // ------------------------------------------------------------------
        if (session('pending_registration')) {
            $fullName = session('pending_full_name');
            $email    = session('pending_email');

            // Check if existing OTP is still valid — don't spam
            $otpExpiresAt = session('pending_otp_expires_at');
            if ($otpExpiresAt && now()->lt(\Carbon\Carbon::parse($otpExpiresAt))) {
                return back()->with('warning', 'Your current OTP is still valid. Please check your email.');
            }

            // Generate and store a fresh OTP
            $otp = rand(100000, 999999);
            session([
                'pending_otp'            => (string) $otp,
                'pending_otp_expires_at' => now()->addMinutes(10)->toDateTimeString(),
            ]);

            try {
                Mail::send('emails.otp', [
                    'full_name' => $fullName,
                    'otp'       => $otp,
                ], function ($message) use ($email, $fullName) {
                    $message->from(config('mail.from.address'), 'MSWDO Member Portal')
                        ->to($email, $fullName)
                        ->subject('New OTP – MSWDO Member Portal');
                });

                return back()->with('success', 'New OTP has been sent to your email.');
            } catch (Exception $e) {
                Log::error('Failed to resend OTP: ' . $e->getMessage());
                return back()->withErrors(['error' => 'Failed to send OTP. Please try again.']);
            }
        }

        // ------------------------------------------------------------------
        // CASE 2: Resend for legacy DB-based OTP
        // ------------------------------------------------------------------
        $userId = session('otp_user_id');
        if (!$userId) {
            return redirect()->route('login')
                ->withErrors(['error' => 'Session expired. Please register again.']);
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['error' => 'User not found.']);
        }

        if ($user->otp_expires_at && Carbon::parse($user->otp_expires_at)->gt(now())) {
            return back()->with('warning', 'Your current OTP is still valid. Please check your email.');
        }

        $otp = $user->generateOtp();

        try {
            Mail::send('emails.otp', [
                'full_name' => $user->full_name,
                'otp'       => $otp,
            ], function ($message) use ($user) {
                $message->from(config('mail.from.address'), 'MSWDO Member Portal')
                    ->to($user->email, $user->full_name)
                    ->subject('New OTP – MSWDO Member Portal');
            });

            return back()->with('success', 'New OTP has been sent to your email.');
        } catch (Exception $e) {
            Log::error('Failed to resend OTP: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to send OTP. Please try again.']);
        }
    }

    private function notifyAdminsOfPendingIdVerification(User $user): void
    {
        try {
            $admins = User::where('role', 'admin')
                ->where('municipality', $user->municipality)
                ->where('status', 'active')
                ->get();

            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new NewUserIdVerificationAdminMail($user));
            }
        } catch (Exception $e) {
            Log::error('Failed to send admin ID verification notification: ' . $e->getMessage());
        }
    }
}