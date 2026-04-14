<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use App\Models\Barangay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $allowedMunicipalities = ['Magdalena', 'Liliw', 'Majayjay'];

        $municipalities = Municipality::whereIn('name', $allowedMunicipalities)->get();

        // Group barangays by municipality name for JS dropdown
        $barangays = Barangay::whereIn('municipality', $allowedMunicipalities)
            ->select('municipality', 'name')
            ->distinct()
            ->orderBy('name')
            ->get()
            ->groupBy('municipality')
            ->map(fn($brgy) => $brgy->pluck('name'));

        return view('auth.register', compact('municipalities', 'barangays'));
    }

    public function register(Request $request)
    {
        $allowedMunicipalities = ['Magdalena', 'Liliw', 'Majayjay'];

        try {
            $validated = $request->validate([
                'full_name' => [
                    'required', 'string', 'min:3', 'max:100',
                    'regex:/^[a-zA-ZÀ-ÿ\s\'\-\.]+$/',
                ],
                'username' => [
                    'required', 'string', 'min:4', 'max:20',
                    'regex:/^[a-zA-Z0-9_]+$/',
                    'unique:users,username',
                ],
                'email' => [
                    'required', 'string', 'email:rfc', 'max:100',
                    'unique:users,email',
                ],
                'mobile_number' => [
                    'required', 'string',
                    'regex:/^9\d{9}$/',
                ],
                'birthdate' => [
                    'required', 'date',
                    'before:' . now()->subYears(18)->format('Y-m-d'),
                    'after:' . now()->subYears(150)->format('Y-m-d'),
                ],
                'municipality' => [
                    'required', 'string',
                    'in:' . implode(',', $allowedMunicipalities),
                ],
                'barangay' => [
                    'required', 'string',
                    'exists:barangays,name',
                ],
            ], [
                'full_name.min'           => 'Full name must be at least 3 characters.',
                'full_name.regex'         => 'Full name may only contain letters, spaces, hyphens, and apostrophes.',
                'username.min'            => 'Username must be at least 4 characters.',
                'username.max'            => 'Username cannot exceed 20 characters.',
                'username.regex'          => 'Username may only contain letters, numbers, and underscores (no spaces).',
                'username.unique'         => 'This username is already taken. Please choose another.',
                'email.unique'            => 'This email address is already registered.',
                'mobile_number.required'  => 'Mobile number is required.',
                'mobile_number.regex'     => 'Mobile number must be 10 digits starting with 9 (e.g., 9171234567).',
                'birthdate.before'        => 'You must be at least 18 years old to register.',
                'birthdate.after'         => 'Birthdate cannot be more than 150 years ago.',
                'municipality.in'         => 'Please select a valid municipality.',
                'barangay.exists'         => 'Please select a valid barangay.',
            ]);

            // Prepend +63 to mobile number for storage
            $validated['mobile_number'] = '+63' . $validated['mobile_number'];

            // Generate random temporary password
            $tempPassword = bin2hex(random_bytes(6)); // 12 character random password

            // Calculate age
            $birthdate = Carbon::parse($validated['birthdate']);
            $age = $birthdate->age;

            // Generate OTP — no DB involved at this point
            $otp = rand(100000, 999999);
            $otpExpiresAt = now()->addMinutes(10)->toDateTimeString();

            // Store all registration data + OTP + temp password in session (NO DB write yet)
            session([
                'pending_registration' => [
                    'username'      => trim($validated['username']),
                    'email'         => strtolower(trim($validated['email'])),
                    'mobile_number' => trim($validated['mobile_number']),
                    'password'      => Hash::make($tempPassword),
                    'full_name'     => trim($validated['full_name']),
                    'birthdate'     => $validated['birthdate'],
                    'age'           => $age,
                    'municipality'  => $validated['municipality'],
                    'barangay'      => trim($validated['barangay']),
                ],
                'pending_otp'            => (string) $otp,
                'pending_otp_expires_at' => $otpExpiresAt,
                'pending_email'          => strtolower(trim($validated['email'])),
                'pending_full_name'      => trim($validated['full_name']),
                'temp_password'          => $tempPassword,
            ]);

            // Send OTP + temp password email
            try {
                Mail::send('emails.otp', [
                    'full_name'     => trim($validated['full_name']),
                    'otp'           => $otp,
                    'temp_password' => $tempPassword,
                ], function ($message) use ($validated) {
                    $message->from(config('mail.from.address'), 'MSWDO Member Portal')
                        ->to(strtolower(trim($validated['email'])), trim($validated['full_name']))
                        ->subject('Email Verification – MSWDO Member Portal');
                });
            } catch (Exception $e) {
                Log::error('Failed to send OTP email: ' . $e->getMessage());
                // Still proceed — user can request resend on the OTP page
            }

            return redirect()->route('otp.verify.form')
                ->with('success', 'Please check your email for your OTP verification code.');

        } catch (Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }
}