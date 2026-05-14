<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    /**
     * Generate a new OTP for the user and persist it.
     */
    public function generate(User $user): int
    {
        return $user->generateOtp();
    }

    /**
     * Verify an OTP for the user. Returns true on success and clears the OTP.
     */
    public function verify(User $user, string $otp): bool
    {
        return $user->verifyOtp($otp);
    }

    /**
     * Send an email verification OTP to the user.
     * Throws on failure so the controller can report the error to the mobile app.
     */
    public function sendVerificationEmail(User $user, int $otp): void
    {
        try {
            Mail::send('emails.otp', [
                'full_name' => $user->full_name,
                'otp'       => $otp,
            ], function ($message) use ($user) {
                $message->from(config('mail.from.address'), config('mail.from.name', 'MSWDO Member Portal'))
                    ->to($user->email, $user->full_name)
                    ->subject('Email Verification – MSWDO Member Portal');
            });
        } catch (\Exception $e) {
            Log::error('Failed to send verification OTP email', [
                'user_id' => $user->id,
                'email'   => $user->email,
                'error'   => $e->getMessage(),
            ]);
            // Re-throw so caller knows the email failed
            throw new \RuntimeException('Unable to send verification email. Please try again later.');
        }
    }

    /**
     * Send a password reset OTP to the user.
     * Throws on failure so the controller can report the error to the mobile app.
     */
    public function sendPasswordResetEmail(User $user, int $otp): void
    {
        try {
            Mail::send('emails.otp', [
                'full_name' => $user->full_name,
                'otp'       => $otp,
            ], function ($message) use ($user) {
                $message->from(config('mail.from.address'), config('mail.from.name', 'MSWDO Member Portal'))
                    ->to($user->email, $user->full_name)
                    ->subject('Password Reset – MSWDO Member Portal');
            });
        } catch (\Exception $e) {
            Log::error('Failed to send password reset OTP email', [
                'user_id' => $user->id,
                'email'   => $user->email,
                'error'   => $e->getMessage(),
            ]);
            // Re-throw so caller knows the email failed
            throw new \RuntimeException('Unable to send password reset email. Please try again later.');
        }
    }
}
