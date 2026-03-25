<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->first();
        
        // Generate reset token
        $token = $user->generateResetToken();

        // Send email with reset link
        try {
            Mail::send('emails.reset-password', ['user' => $user, 'token' => $token], function ($message) use ($user) {
                $message->to($user->email, $user->full_name)
                    ->subject('Password Reset Request - MSWDO Analysis');
            });

            return back()->with('status', 'We have emailed your password reset link!');
        } catch (\Exception $e) {
            \Log::error('Failed to send reset email: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send email. Please try again.']);
        }
    }
}