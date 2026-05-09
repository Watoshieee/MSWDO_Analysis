<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Application;
use App\Models\User;

class SoloParentClaimedMail extends Mailable
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
        return $this->subject('✅ Solo Parent ID Successfully Claimed!')
                    ->view('emails.solo-parent-claimed');
    }
}
