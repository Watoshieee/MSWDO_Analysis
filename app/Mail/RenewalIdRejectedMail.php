<?php

namespace App\Mail;

use App\Models\RenewalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RenewalIdRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public RenewalRequest $renewal;

    public function __construct(RenewalRequest $renewal)
    {
        $this->renewal = $renewal;
    }

    public function envelope(): Envelope
    {
        $progLabel = $this->renewal->program_type === 'PWD' ? 'PWD' : 'Solo Parent';
        return new Envelope(
            subject: "[MSWDO] Your {$progLabel} Renewal Valid ID Was Not Accepted",
        );
    }

    public function content(): Content
    {
        $progLabel = $this->renewal->program_type === 'PWD' ? 'PWD' : 'Solo Parent';
        $portalUrl = $this->renewal->program_type === 'PWD'
            ? url('/user/pwd-application')
            : url('/user/solo-parent-application');

        return new Content(
            view: 'emails.renewal-id-rejected',
            with: [
                'renewal'   => $this->renewal,
                'user'      => $this->renewal->user,
                'progLabel' => $progLabel,
                'portalUrl' => $portalUrl,
                'remarks'   => $this->renewal->admin_remarks,
            ],
        );
    }
}
