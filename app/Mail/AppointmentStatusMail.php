<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AppointmentStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public Appointment $appointment;
    public string $newStatus; // 'confirmed' | 'rejected' | 'reminder'

    public function __construct(Appointment $appointment, string $newStatus)
    {
        $this->appointment = $appointment;
        $this->newStatus   = $newStatus;
    }

    public function envelope(): Envelope
    {
        $programLabel = match($this->appointment->program_type ?? 'Solo_Parent') {
            'AICS_Medical' => 'AICS Medical Assistance',
            'AICS_Burial'  => 'AICS Burial Assistance',
            'Solo_Parent'  => 'Solo Parent ID',
            default        => 'MSWDO Assistance',
        };

        $subject = match($this->newStatus) {
            'confirmed' => "MSWDO: {$programLabel} Appointment Confirmed – " . \Carbon\Carbon::parse($this->appointment->appointment_date)->format('F d, Y'),
            'rejected'  => "MSWDO: {$programLabel} Appointment Rejected",
            'reminder'  => "MSWDO: Reminder – {$programLabel} Appointment Tomorrow",
            default     => "MSWDO: {$programLabel} Appointment Update",
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.appointment-status',
            with: [
                'appointment' => $this->appointment,
                'newStatus'   => $this->newStatus,
            ],
        );
    }
}
