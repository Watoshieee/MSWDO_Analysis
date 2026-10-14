<?php

namespace App\Mail;

use App\Models\RenewalRequest;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RenewalIdApprovedMail extends Mailable
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
            subject: "[MSWDO] ✅ Your {$progLabel} Renewal Valid ID Has Been Approved",
        );
    }

    public function content(): Content
    {
        $progLabel = $this->renewal->program_type === 'PWD' ? 'PWD' : 'Solo Parent';
        $portalUrl = $this->renewal->program_type === 'PWD'
            ? url('/user/pwd-application')
            : url('/user/solo-parent-application');

        $appt = $this->renewal->appointment;
        $formattedDate = null;
        $formattedTime = null;
        if ($appt) {
            $formattedDate = $appt->appointment_date
                ? Carbon::parse($appt->appointment_date)->format('F d, Y')
                : null;
            $formattedTime = $appt->appointment_time
                ? Carbon::parse($appt->appointment_time)->format('h:i A')
                : null;
        }

        return new Content(
            view: 'emails.renewal-id-approved',
            with: [
                'renewal'       => $this->renewal,
                'user'          => $this->renewal->user,
                'appointment'   => $appt,
                'progLabel'     => $progLabel,
                'portalUrl'     => $portalUrl,
                'formattedDate' => $formattedDate,
                'formattedTime' => $formattedTime,
            ],
        );
    }
}
