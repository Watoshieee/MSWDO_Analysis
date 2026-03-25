<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Municipality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;
use Carbon\Carbon;

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
            $validated = $request->validate([
                'username' => 'required|string|max:50|unique:users',
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'full_name' => 'required|string|max:100',
                'birthdate' => 'required|date|before:today',
                'municipality' => 'nullable|string|max:50',
                'role' => 'required|in:user,admin,super_admin',
            ]);

            $birthdate = Carbon::parse($validated['birthdate']);
            $age = $birthdate->age;

            $userData = [
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'full_name' => $validated['full_name'],
                'birthdate' => $validated['birthdate'],
                'age' => $age,
                'role' => $validated['role'],
                'status' => 'active',
            ];

            if ($validated['role'] === 'admin' || $validated['role'] === 'super_admin') {
                if (empty($validated['municipality'])) {
                    throw new Exception('Municipality is required for admin accounts.');
                }
                $userData['municipality'] = $validated['municipality'];
            } else {
                $userData['municipality'] = $validated['municipality'] ?? 'Majayjay';
            }

            $user = User::create($userData);

            // For super_admin and admin, auto-verify (no OTP needed)
            if (in_array($validated['role'], ['super_admin', 'admin'])) {
                $user->email_verified_at = now();
                $user->save();
                
                Auth::login($user);
                
                if ($user->role === 'super_admin') {
                    return redirect()->route('superadmin.dashboard')
                        ->with('success', 'Super Admin account created successfully!');
                } else {
                    return redirect()->route('admin.dashboard')
                        ->with('success', 'Admin account created successfully!');
                }
            }

            // For regular users, generate OTP and send email
            try {
                $otp = $user->generateOtp();
                
                Mail::send('emails.otp', ['user' => $user, 'otp' => $otp], function ($message) use ($user) {
                    $message->to($user->email, $user->full_name)
                        ->subject('Email Verification OTP - MSWDO Analysis');
                });
                
                session(['otp_user_id' => $user->id]);
                
                return redirect()->route('otp.verify.form')
                    ->with('success', 'Registration successful! Please check your email for OTP.');
                    
            } catch (Exception $e) {
                Log::error('Failed to send OTP email: ' . $e->getMessage());
                
                // If email fails, show OTP form anyway with warning
                session(['otp_user_id' => $user->id]);
                return redirect()->route('otp.verify.form')
                    ->with('warning', 'Registration successful but email sending failed. Please contact admin for OTP.');
            }

        } catch (Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }
}