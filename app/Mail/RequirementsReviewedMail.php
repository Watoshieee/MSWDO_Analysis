<?php

namespace App\Mail;

use App\Models\FileMonitoring;
use App\Models\FileUpload;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequirementsReviewedMail extends Mailable
{
    use Queueable, SerializesModels;

    public FileMonitoring $fileMonitoring;
    public string         $overallStatus; // 'approved' | 'rejected' | 'in_review'

    public function __construct(FileMonitoring $fileMonitoring, string $overallStatus)
    {
        $this->fileMonitoring = $fileMonitoring;
        $this->overallStatus  = $overallStatus;
    }

    public function envelope(): Envelope
    {
        $programType = $this->fileMonitoring->application->program_type ?? '';
        $programLabel = match ($programType) {
            'AICS_Medical'          => 'AICS Medical Assistance',
            'AICS_Burial'           => 'AICS Burial Assistance',
            'PWD_Assistance',
            'PWD_New',
            'PWD_Renewal'           => 'PWD ID',
            'Senior_Citizen_Pension',
            'Senior_Citizen'        => 'Senior Citizen Assistance',
            '4Ps'                   => '4Ps',
            'SLP'                   => 'SLP',
            'ESA'                   => 'ESA',
            'Solo_Parent'           => 'Solo Parent ID',
            default                 => str_replace('_', ' ', $programType ?: 'Program'),
        };

        $subject = match ($this->overallStatus) {
            'approved'  => "[MSWDO] ✅ {$programLabel} requirements approved",
            'rejected'  => "[MSWDO] ❌ {$programLabel} requirements need action",
            default     => "[MSWDO] 📋 {$programLabel} requirements update",
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.requirements-reviewed',
            with: [
                'fileMonitoring' => $this->fileMonitoring,
                'overallStatus'  => $this->overallStatus,
                'fileUploads'    => $this->fileMonitoring->fileUploads,
            ],
        );
    }
}
