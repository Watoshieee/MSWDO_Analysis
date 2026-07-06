<?php

namespace App\Mail;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewAppointmentAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public Appointment $appointment;
    public User $applicant;

    public function __construct(Appointment $appointment, User $applicant)
    {
        $this->appointment = $appointment;
        $this->applicant   = $applicant;
    }

    public function envelope(): Envelope
    {
        $programLabel = match($this->appointment->program_type) {
            'AICS_Medical' => 'AICS Medical Assistance',
            'AICS_Burial'  => 'AICS Burial Assistance',
            default        => 'Solo Parent',
        };

        return new Envelope(
            subject: 'MSWDO: New ' . $programLabel . ' Appointment – ' . $this->appointment->municipality,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-appointment-admin',
            with: [
                'appointment' => $this->appointment,
                'applicant'   => $this->applicant,
            ],
        );
    }
}
