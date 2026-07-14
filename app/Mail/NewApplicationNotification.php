<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewApplicationNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Application $application;

    /**
     * Program type display name map.
     * Not hardcoded for the notification — the map gracefully falls back
     * to a humanised version of any key, so new program types work automatically.
     */
    private static array $programLabels = [
        'PWD_Assistance'          => 'PWD Assistance',
        'PWD_New'                 => 'PWD Assistance (New)',
        'PWD_Renewal'             => 'PWD Assistance (Renewal)',
        'Solo_Parent'             => 'Solo Parent Support',
        'AICS'                    => 'AICS (Emergency Aid)',
        'AICS_Medical'            => 'AICS Medical',
        'AICS_Burial'             => 'AICS Burial',
        '4Ps'                     => '4Ps (Pantawid Pamilya)',
        'SLP'                     => 'Sustainable Livelihood Program',
        'ESA'                     => 'Educational Support Assistance',
        'Senior_Citizen_Pension'  => 'Senior Citizen Pension',
        'Senior_Citizen'          => 'Senior Citizen Pension',
    ];

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function envelope(): Envelope
    {
        $program = $this->humanProgram();
        $muni    = $this->application->municipality ?? 'Unknown Municipality';

        return new Envelope(
            subject: "MSWDO: New {$program} Application – {$muni}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-application',
            with: [
                'application'  => $this->application,
                'programLabel' => $this->humanProgram(),
            ],
        );
    }

    /** Convert a program_type key to a human-readable name. */
    public function humanProgram(): string
    {
        $key = $this->application->program_type ?? '';
        return self::$programLabels[$key] ?? ucwords(str_replace('_', ' ', $key));
    }
}
