<?php

namespace App\Mail;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $user;
    public $reason;

    public function __construct(Appointment $appointment, User $user, string $reason)
    {
        $this->appointment = $appointment;
        $this->user = $user;
        $this->reason = $reason;
    }

    public function build()
    {
        return $this->subject('Appointment Cancelled - ' . $this->user->full_name)
            ->view('emails.appointment-cancelled');
    }
}
