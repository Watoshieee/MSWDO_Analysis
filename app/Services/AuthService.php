<?php

namespace App\Services;

use App\Mail\RegistrationPasswordMail;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    /**
     * Register a new user and return the created model.
     * Does NOT send OTP — that is OtpService's responsibility.
     */
    public function register(array $data, int $age, ?UploadedFile $validIdFile = null): User
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
            'id_verification_status' => User::ID_STATUS_PENDING,
            'must_change_password' => true,
        ]);

        // If a valid ID file is provided, store it immediately.
        // If storage fails, an exception is thrown so caller rolls back user creation.
        if ($validIdFile) {
            $this->storeValidId($user, $validIdFile);
        }

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
    /**
     * Store the uploaded valid ID file and associate it with the given user.
     *
     * Called AFTER user creation so we have a known user ID for path isolation.
     * Uses a server-generated safe filename — never trusts the client name.
     * Stores in valid-ids/{user_id}/ matching the website's OtpController pattern.
     * Sets id_verification_status to 'pending' so admins can review it.
     *
     * @param  User          $user  The already-created user
     * @param  UploadedFile  $file  The validated uploaded ID file
     * @return void
     * @throws \RuntimeException if file storage fails
     */
    public function storeValidId(User $user, UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');

        $safeFilename = 'valid-id.' . $extension;
        $directory = 'valid-ids/' . $user->id;

        Storage::disk('public')->makeDirectory($directory);
        $storedPath = $file->storeAs($directory, $safeFilename, 'public');

        if (!$storedPath) {
            throw new \RuntimeException('Failed to save uploaded valid ID to storage.');
        }

        $user->update([
            'valid_id_path'          => $storedPath,
            'valid_id_filename'      => $file->getClientOriginalName(),
            'id_verification_status' => User::ID_STATUS_PENDING,
        ]);
    }

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
            if (!$user->hasVerifiedEmail()) {
                throw new \Illuminate\Auth\AuthenticationException('Account not yet verified');
            }
            if ($user->status === 'pending') {
                throw new \Illuminate\Auth\AuthenticationException('Your registration has been successfully submitted. Your account is currently under verification. Please wait for your account to be approved before signing in.');
            }
            if ($user->status !== 'active') {
                throw new \Illuminate\Auth\AuthenticationException('Your account is inactive. Please contact your municipal office.');
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
