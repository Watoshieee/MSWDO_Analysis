<?php

namespace App\Services;

use App\Mail\RegistrationPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    /**
     * Register a new user and return the created model.
     * Does NOT send OTP — that is OtpService's responsibility.
     */
    public function register(array $data, int $age): User
    {
        $fullName = trim(
            $data['first_name'] . ' ' .
            ($data['middle_name'] ? $data['middle_name'] . ' ' : '') .
            $data['last_name']
        );

        $generatedPassword = RegistrationValidationService::generateSecurePassword();

        $user = User::create([
            'full_name'     => $fullName,
            'username'      => trim($data['username']),
            'email'         => strtolower(trim($data['email'])),
            'mobile_number' => trim($data['mobile_number']),
            'birthdate'     => $data['birthdate'],
            'age'           => $age,
            'gender'        => $data['gender'],
            'municipality'  => $data['municipality'],
            'barangay'      => $data['barangay'],
            'password'      => Hash::make($generatedPassword),
            'role'          => User::ROLE_USER,
            'status'        => 'inactive',
            'must_change_password' => true,
        ]);

        // Send the generated password to the user's email
        try {
            Mail::to($user->email)->send(
                new RegistrationPasswordMail($fullName, $generatedPassword, $user->email)
            );
        } catch (\Exception $e) {
            Log::error('Failed to send registration password email', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
        }

        return $user;
    }

    /**
     * Attempt to find a user by email or username and verify their password.
     * Throws a descriptive exception on failure so the controller can return the right HTTP status.
     */
    public function attemptLogin(string $login, string $password): User
    {
        $loginType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $user = User::where($loginType, $login)->first();

        if (!$user) {
            throw new \Illuminate\Auth\AuthenticationException('User not found');
        }

        if (!Hash::check($password, $user->password)) {
            throw new \Illuminate\Auth\AuthenticationException('Incorrect password');
        }

        if ($user->role === User::ROLE_USER) {
            if (!$user->hasVerifiedEmail() || $user->status !== 'active') {
                throw new \Illuminate\Auth\AuthenticationException('Account not yet verified');
            }
        }

        return $user;
    }

    /**
     * Create a Sanctum token for the given user and return the plain-text token.
     */
    public function createToken(User $user, string $deviceName = 'mobile-app'): string
    {
        return $user->createToken($deviceName)->plainTextToken;
    }
}
