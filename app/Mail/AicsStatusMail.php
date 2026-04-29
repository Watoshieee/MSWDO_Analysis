<?php

namespace App\Mail;

use App\Models\Application;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AicsStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public Application $application;
    public User $user;
    public string $stage;

    public function __construct(Application $application, User $user, string $stage)
    {
        $this->application = $application;
        $this->user = $user;
        $this->stage = $stage;
    }

    public function build(): self
    {
        $programLabel = match ($this->application->program_type) {
            'AICS_Medical' => 'AICS Medical Assistance',
            'AICS_Burial'  => 'AICS Burial Assistance',
            default        => 'AICS Assistance',
        };

        $subject = $this->stage === 'ready_for_pickup'
            ? "🎁 {$programLabel} grant is ready for claiming"
            : "✅ {$programLabel} requirements validated";

        return $this->subject($subject)
            ->view('emails.aics-status');
    }
}
