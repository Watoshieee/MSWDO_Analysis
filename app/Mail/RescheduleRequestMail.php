<?php

namespace App\Mail;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RescheduleRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $user;

    public function __construct(Appointment $appointment, User $user)
    {
        $this->appointment = $appointment;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Reschedule Request - ' . $this->user->full_name)
            ->view('emails.reschedule-request');
    }
}
