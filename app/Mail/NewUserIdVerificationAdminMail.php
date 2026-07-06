<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewUserIdVerificationAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $newUser;

    public function __construct(User $newUser)
    {
        $this->newUser = $newUser;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New User Registration – Valid ID Review Required (' . $this->newUser->municipality . ')',
            replyTo: [
                new \Illuminate\Mail\Mailables\Address(config('mail.from.address'), config('mail.from.name')),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-user-id-verification-admin',
            with: [
                'newUser' => $this->newUser,
                'reviewUrl' => url('/admin/users'),
            ],
        );
    }
}
