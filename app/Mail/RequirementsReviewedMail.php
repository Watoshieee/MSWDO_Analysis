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
        $subject = match ($this->overallStatus) {
            'approved'  => '[MSWDO] ✅ Your Solo Parent Requirements have been Approved!',
            'rejected'  => '[MSWDO] ❌ Action Required: Some Requirements were Declined',
            default     => '[MSWDO] Your Solo Parent Requirements are Under Review',
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
