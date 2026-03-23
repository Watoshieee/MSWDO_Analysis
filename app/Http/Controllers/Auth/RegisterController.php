<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Municipality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // Ito ang tama, hindi 'Auth' lang
use Exception;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $municipalities = Municipality::whereIn('name', ['Magdalena', 'Liliw', 'Majayjay'])->get();
        $roles = [
            'user' => 'Regular User',
            'admin' => 'Municipality Admin',
            'super_admin' => 'Super Admin'
        ];
        return view('auth.register', compact('municipalities', 'roles'));
    }

    public function register(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'username' => 'required|string|max:50|unique:users',
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'full_name' => 'required|string|max:100',
                'municipality' => 'required_if:role,admin|nullable|string|max:50',
                'role' => 'required|in:user,admin,super_admin',
            ]);

            // Prepare user data
            $userData = [
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'full_name' => $validated['full_name'],
                'role' => $validated['role'],
                'status' => 'active',
            ];

            // Add municipality if provided
            if (!empty($validated['municipality'])) {
                $userData['municipality'] = $validated['municipality'];
            }

            // Create user
            $user = User::create($userData);

            // For super_admin and admin, auto-verify
            if (in_array($validated['role'], ['super_admin', 'admin'])) {
                $user->email_verified_at = now();
                $user->save();
                
                // Log in using Auth facade
                Auth::login($user);
                
                if ($user->role === 'super_admin') {
                    return redirect()->route('superadmin.dashboard')
                        ->with('success', 'Super Admin account created successfully!');
                } else {
                    return redirect()->route('dashboard')
                        ->with('success', 'Admin account created successfully!');
                }
            }

            // For regular users, generate OTP
            try {
                $otp = $user->generateOtp();
                
                // Try to send email
                Mail::send('emails.otp', ['user' => $user, 'otp' => $otp], function ($message) use ($user) {
                    $message->to($user->email, $user->full_name)
                        ->subject('Email Verification OTP - MSWDO Analysis');
                });
                
                // Store user id in session for OTP verification
                session(['otp_user_id' => $user->id]);
                
                return redirect()->route('otp.verify.form')
                    ->with('success', 'Registration successful! Please check your email for OTP.');
                    
            } catch (Exception $e) {
                Log::error('Failed to send OTP email: ' . $e->getMessage());
                
                // If email fails, auto-verify na lang muna for development
                $user->email_verified_at = now();
                $user->save();
                Auth::login($user);
                
                return redirect()->route('dashboard')
                    ->with('success', 'Registration successful! (Email sending failed, but you are logged in)');
            }

        } catch (Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }
}