<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    public function showVerifyForm()
    {
        if (!session('otp_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ]);

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
            // Clear session and log in
            session()->forget('otp_user_id');
            Auth::login($user);
            
            // Redirect based on role
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

        // Check if OTP is still valid
        if ($user->otp_expires_at && $user->otp_expires_at > now()) {
            return back()->with('warning', 'Your current OTP is still valid. Please check your email.');
        }

        // Generate new OTP
        $otp = $user->generateOtp();
        
        // Send OTP email
        try {
            Mail::send('emails.otp', ['user' => $user, 'otp' => $otp], function ($message) use ($user) {
                $message->to($user->email, $user->full_name)
                    ->subject('New OTP - MSWDO Analysis');
            });
            return back()->with('success', 'New OTP has been sent to your email.');
        } catch (\Exception $e) {
            Log::error('Failed to send OTP: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to send OTP. Please try again.']);
        }
    }
}