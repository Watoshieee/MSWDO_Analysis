<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Application;
use App\Models\User;

class AicsGrantReleasedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Application $application;
    public User $user;

    public function __construct(Application $application, User $user)
    {
        $this->application = $application;
        $this->user        = $user;
    }

    public function build(): self
    {
        $programLabel = $this->application->program_type === 'AICS_Burial'
            ? 'AICS Burial Assistance'
            : 'AICS Medical Assistance';

        return $this->subject('✅ ' . $programLabel . ' Grant Successfully Released!')
                    ->view('emails.aics-grant-released');
    }
}
