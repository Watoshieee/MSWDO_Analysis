<?php

namespace App\Mail;

use App\Models\Appointment;
use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SoloParentEligibilityMail extends Mailable
{
    use Queueable, SerializesModels;

    public Appointment  $appointment;
    public Application  $application;

    public function __construct(Appointment $appointment, Application $application)
    {
        $this->appointment   = $appointment;
        $this->application   = $application;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[MSWDO] ✅ You are Eligible for Solo Parent ID – Submit Your Requirements',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.solo-parent-eligibility',
            with: [
                'appointment'   => $this->appointment,
                'application'   => $this->application,
            ],
        );
    }
}
