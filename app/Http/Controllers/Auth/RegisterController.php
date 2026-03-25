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
        return view('auth.register', compact('municipalities'));
    }

    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'full_name'             => 'required|string|max:100',
                'username'              => 'required|string|max:50|unique:users',
                'email'                 => 'required|string|email|max:100|unique:users',
                'mobile_number'         => 'nullable|string|max:20',
                'birthdate'             => 'required|date|before:today',
                'municipality'          => 'nullable|string|max:50',
                'password'              => 'required|string|min:8|confirmed',
            ]);

            $birthdate = Carbon::parse($validated['birthdate']);
            $age = $birthdate->age;

            $userData = [
                'username'       => $validated['username'],
                'email'          => $validated['email'],
                'mobile_number'  => $validated['mobile_number'] ?? null,
                'password'       => Hash::make($validated['password']),
                'full_name'      => $validated['full_name'],
                'birthdate'      => $validated['birthdate'],
                'age'            => $age,
                'role'           => 'user',   // Always user — registration is public-facing only
                'status'         => 'active',
                'municipality'   => $validated['municipality'] ?? 'Majayjay',
            ];

            $user = User::create($userData);

            // Send OTP for email verification
            try {
                $otp = $user->generateOtp();

                Mail::send('emails.otp', ['user' => $user, 'otp' => $otp], function ($message) use ($user) {
                    $message->to($user->email, $user->full_name)
                        ->subject('Email Verification – MSWDO Member Portal');
                });

                session(['otp_user_id' => $user->id]);

                return redirect()->route('otp.verify.form')
                    ->with('success', 'Account created! Please check your email for your OTP verification code.');

            } catch (Exception $e) {
                Log::error('Failed to send OTP email: ' . $e->getMessage());

                session(['otp_user_id' => $user->id]);
                return redirect()->route('otp.verify.form')
                    ->with('warning', 'Account created but we could not send the verification email. Please contact the MSWDO office for your OTP.');
            }

        } catch (Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }
}