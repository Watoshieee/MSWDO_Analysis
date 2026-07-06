<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function showRegistrationForm(Request $request)
    {
        $allowedMunicipalities = ['Magdalena', 'Liliw', 'Majayjay'];


    $municipalities = Municipality::whereIn('name', $allowedMunicipalities)
    ->select('id', 'name')
    ->distinct('name')
    ->orderBy('name')
    ->get()
    ->unique('name');


        $barangays = Barangay::whereIn('municipality', $allowedMunicipalities)
            ->select('municipality', 'name')
            ->distinct()
            ->orderBy('name')
            ->get()
            ->groupBy('municipality')
            ->map(fn($brgy) => $brgy->pluck('name')->values());

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
                    function ($attribute, $value, $fail) {
                        if (preg_match('/(\d)\1{4,}/', $value)) {
                            $fail('Mobile number cannot contain 5 or more repeated digits in a row.');
                        }
                    },
                ],
                'gender' => [
                    'required', 'string',
                    'in:Male,Female',
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
                'valid_id' => [
                    'required', 'file',
                    'mimes:jpeg,jpg,png,pdf',
                    'max:5120',
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
                'gender.required'         => 'Please select your gender.',
                'gender.in'               => 'Please select a valid gender option.',
                'birthdate.before'        => 'You must be at least 18 years old to register.',
                'birthdate.after'         => 'Birthdate cannot be more than 150 years ago.',
                'municipality.in'         => 'Please select a valid municipality.',
                'barangay.exists'         => 'Please select a valid barangay.',
                'valid_id.required'       => 'Please upload a valid government-issued ID.',
                'valid_id.mimes'          => 'Valid ID must be a JPG, PNG, or PDF file.',
                'valid_id.max'            => 'Valid ID file must not exceed 5 MB.',
            ]);

            $validated['mobile_number'] = '+63' . $validated['mobile_number'];

            $tempPassword = bin2hex(random_bytes(6));

            $birthdate = Carbon::parse($validated['birthdate']);
            $age = $birthdate->age;

            $validIdFile = $request->file('valid_id');
            $validIdPath = $validIdFile->store('valid-ids/pending', 'public');
            $validIdFilename = $validIdFile->getClientOriginalName();

            $otp = rand(100000, 999999);
            $otpExpiresAt = now()->addMinutes(10)->toDateTimeString();

            session([
                'pending_registration' => [
                    'username'             => trim($validated['username']),
                    'email'                => strtolower(trim($validated['email'])),
                    'mobile_number'        => trim($validated['mobile_number']),
                    'password'             => Hash::make($tempPassword),
                    'full_name'            => trim($validated['full_name']),
                    'gender'               => $validated['gender'],
                    'birthdate'            => $validated['birthdate'],
                    'age'                  => $age,
                    'municipality'         => $validated['municipality'],
                    'barangay'             => trim($validated['barangay']),
                    'must_change_password' => true,
                    'valid_id_path'        => $validIdPath,
                    'valid_id_filename'    => $validIdFilename,
                ],
                'pending_otp'            => (string) $otp,
                'pending_otp_expires_at' => $otpExpiresAt,
                'pending_email'          => strtolower(trim($validated['email'])),
                'pending_full_name'      => trim($validated['full_name']),
                'temp_password'          => $tempPassword,
            ]);

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
            }

            return redirect()->route('otp.verify.form')
                ->with('success', 'Please check your email for your OTP verification code. After verifying your email, your valid ID will be reviewed by an admin before you can login.');

        } catch (Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());

            if (isset($validIdPath) && Storage::disk('public')->exists($validIdPath)) {
                Storage::disk('public')->delete($validIdPath);
            }

            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    public function checkUsername(Request $request)
    {
        $username = $request->input('username');

        if (empty($username)) {
            return response()->json(['available' => true]);
        }

        $exists = User::where('username', $username)->exists();

        return response()->json(['available' => !$exists]);
    }
}
