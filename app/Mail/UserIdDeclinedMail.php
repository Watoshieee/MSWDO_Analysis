<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserIdDeclinedMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $fullName;
    public string $reason;
    public string $municipality;

    public function __construct(string $fullName, string $reason, string $municipality)
    {
        $this->fullName = $fullName;
        $this->reason = $reason;
        $this->municipality = $municipality;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[MSWDO] Valid ID Declined – Registration Not Approved',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user-id-declined',
            with: [
                'fullName' => $this->fullName,
                'reason' => $this->reason,
                'municipality' => $this->municipality,
                'registerUrl' => url('/register'),
            ],
        );
    }
}
