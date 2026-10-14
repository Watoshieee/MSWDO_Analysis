<?php

namespace App\Mail;

use App\Models\RenewalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PwdRenewalSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public RenewalRequest $renewal;

    public function __construct(RenewalRequest $renewal)
    {
        $this->renewal = $renewal;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[MSWDO] Your PWD Renewal ID Has Been Received',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pwd-renewal-submitted',
            with: [
                'renewal'   => $this->renewal,
                'user'      => $this->renewal->user,
                'portalUrl' => url('/user/pwd-application'),
            ],
        );
    }
}
