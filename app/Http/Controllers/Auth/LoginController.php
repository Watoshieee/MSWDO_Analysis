<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail; // ADD THIS

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        // Determine if login input is email or username
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        
        $credentials = [
            $loginType => $request->login,
            'password' => $request->password,
        ];

        // Find the user first to check status and role
        $user = \App\Models\User::where($loginType, $request->login)->first();

        // Check if user exists
        if (!$user) {
            return back()->withErrors([
                'login' => 'The provided credentials do not match our records.',
            ])->withInput($request->only('login'));
        }

        // Check if user is active
        if ($user->status !== 'active') {
            return back()->withErrors([
                'login' => 'Your account is inactive. Please contact administrator.',
            ])->withInput($request->only('login'));
        }

        // CHECK IF EMAIL IS VERIFIED FOR REGULAR USERS
        if ($user->role === 'user' && !$user->hasVerifiedEmail()) {
            // Generate and send new OTP
            $otp = $user->generateOtp();
            
            try {
                Mail::send('emails.otp', ['user' => $user, 'otp' => $otp], function ($message) use ($user) {
                    $message->to($user->email, $user->full_name)
                        ->subject('Email Verification OTP - MSWDO Analysis');
                });
            } catch (\Exception $e) {
                Log::error('OTP Email failed: ' . $e->getMessage());
            }
            
            session(['otp_user_id' => $user->id]);
            return redirect()->route('otp.verify.form')
                ->with('success', 'Please verify your email first. A new OTP has been sent.');
        }

        // Attempt to log in
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            $authenticatedUser = Auth::user();
            
            Log::info('User logged in:', [
                'id' => $authenticatedUser->id,
                'username' => $authenticatedUser->username,
                'role' => $authenticatedUser->role,
                'time' => now()
            ]);
            
            // Redirect based on role
            if ($authenticatedUser->role === 'super_admin') {
                return redirect()->intended('/superadmin/dashboard')
                    ->with('success', 'Welcome back, Super Admin!');
            } elseif ($authenticatedUser->role === 'admin') {
                return redirect()->intended('/admin/dashboard')
                    ->with('success', 'Welcome back, ' . $authenticatedUser->full_name . '!');
            } else {
                return redirect()->intended('/dashboard')
                    ->with('success', 'Welcome back, ' . $authenticatedUser->full_name . '!');
            }
        }

        return back()->withErrors([
            'login' => 'The provided credentials are incorrect.',
        ])->withInput($request->only('login'));
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        
        if ($user) {
            Log::info('User logged out:', [
                'id' => $user->id,
                'username' => $user->username,
                'role' => $user->role,
                'time' => now()
            ]);
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/analysis')->with('success', 'You have been logged out successfully.');
    }
}