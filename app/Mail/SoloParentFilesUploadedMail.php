<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\FileMonitoring;

class SoloParentFilesUploadedMail extends Mailable
{
    use Queueable, SerializesModels;

    public FileMonitoring $fileMonitoring;

    public function __construct(FileMonitoring $fileMonitoring)
    {
        $this->fileMonitoring = $fileMonitoring;
    }

    public function envelope(): Envelope
    {
        $applicant = $this->fileMonitoring->application?->full_name ?? 'Unknown';
        return new Envelope(
            subject: "[MSWDO] New Solo Parent Documents Uploaded — {$applicant}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.solo-parent-files-uploaded',
        );
    }
}
