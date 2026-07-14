<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RescheduleResponseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $action;

    public function __construct(Appointment $appointment, string $action)
    {
        $this->appointment = $appointment;
        $this->action = $action;
    }

    public function build()
    {
        $subject = $this->action === 'approved' 
            ? 'Reschedule Request Approved' 
            : 'Reschedule Request Rejected';

        return $this->subject($subject)
            ->view('emails.reschedule-response');
    }
}
