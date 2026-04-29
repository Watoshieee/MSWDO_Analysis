<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fullName;
    public $password;
    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct(string $fullName, string $password, string $email)
    {
        $this->fullName = $fullName;
        $this->password = $password;
        $this->email = $email;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), 'MSWDO Member Portal')
                    ->subject('Your MSWDO Account Password')
                    ->view('emails.registration_password');
    }
}
