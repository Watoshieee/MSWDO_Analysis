<?php

namespace App\Mail;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CancellationResponseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $appointment;
    public $response;

    public function __construct(Appointment $appointment, string $response)
    {
        $this->appointment = $appointment;
        $this->response = $response;
    }

    public function build()
    {
        $subject = $this->response === 'approved'
            ? 'Cancellation Request Approved'
            : 'Cancellation Request Rejected';

        return $this->subject($subject)
            ->view('emails.cancellation-response');
    }
}
