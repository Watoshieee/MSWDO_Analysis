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
        $subject = match($this->newStatus) {
            'confirmed' => '[MSWDO] Appointment Confirmed – ' . $this->appointment->appointment_date->format('F d, Y'),
            'rejected'  => '[MSWDO] Appointment Rejected',
            'reminder'  => '[MSWDO] Reminder: Your Appointment is Tomorrow!',
            default     => '[MSWDO] Appointment Update',
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
