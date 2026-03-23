<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            
            return redirect()->intended('/dashboard')
                ->with('success', 'Email verified successfully!');
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

        $otp = $user->generateOtp();
        
        // Send OTP email
        try {
            Mail::send('emails.otp', ['user' => $user, 'otp' => $otp], function ($message) use ($user) {
                $message->to($user->email, $user->full_name)
                    ->subject('New OTP - MSWDO Analysis');
            });
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to send OTP. Please try again.']);
        }

        return back()->with('success', 'New OTP sent to your email.');
    }
}