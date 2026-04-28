<?php

namespace App\Mail;

use App\Models\Application;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PwdIdReadyMail extends Mailable
{
    use Queueable, SerializesModels;

    public Application $application;
    public User $user;

    public function __construct(Application $application, User $user)
    {
        $this->application = $application;
        $this->user = $user;
    }

    public function build(): self
    {
        return $this->subject('🎫 Your PWD ID is Ready for Pick-Up')
            ->view('emails.pwd-id-ready');
    }
}
