<?php

namespace App\Services;

use App\Models\Application;
use App\Models\FileMonitoring;
use App\Models\FileUpload;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ApplicationService
{
    /**
     * Submit a new application with uploaded requirement files.
     *
     * @param  User          $user
     * @param  string        $programType
     * @param  UploadedFile[] $files  keyed by requirement name
     * @return Application
     */
    public function submit(User $user, string $programType, array $files = []): Application
    {
        // Block Solo Parent reapplication if an ID has already been issued
        if ($programType === 'Solo_Parent') {
            $idAlreadyIssued = Application::where('user_id', $user->id)
                ->where('program_type', 'Solo_Parent')
                ->where('status', 'approved')
                ->whereIn('id_status', ['ready_for_pickup', 'delivered'])
                ->exists();

            if ($idAlreadyIssued) {
                throw new \RuntimeException('You already have an active Solo Parent ID and cannot reapply.');
            }
        }

        $municipality = $user->municipality ?? 'Majayjay';

        $application = Application::create([
            'user_id'          => $user->id,
            'program_type'     => $programType,
            'full_name'        => $user->full_name ?? '',
            'age'              => $user->age ?? 0,
            'gender'           => $user->gender ?? 'Male',
            'contact_number'   => $user->mobile_number ?? $user->email ?? '',
            'barangay'         => $user->barangay ?? '',
            'municipality'     => $municipality,
            'status'           => Application::STATUS_PENDING,
            'application_date' => now(),
            'year'             => date('Y'),
            'stage'            => 'requirements',
        ]);

        $fileMonitoring = FileMonitoring::create([
            'application_id' => $application->id,
            'user_id'        => $user->id,
            'municipality'   => $municipality,
            'priority'       => 'medium',
            'overall_status' => 'pending',
        ]);

        foreach ($files as $reqName => $file) {
            $this->storeFile($file, $application->id, $fileMonitoring->id, $user->id, $municipality, $reqName);
        }

        return $application;
    }

    /**
     * Re-upload a single rejected file and reset application/monitoring status.
     */
    public function reuploadFile(FileUpload $fileUpload, UploadedFile $file): void
    {
        $application = $fileUpload->fileMonitoring->application;

        if ($fileUpload->file_path) {
            Storage::disk('public')->delete($fileUpload->file_path);
        }

        $originalName = $file->getClientOriginalName();
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $originalName);
        $path = $file->storeAs("applications/{$application->id}/requirements", $filename, 'public');

        $fileUpload->update([
            'file_name'   => $originalName,
            'file_path'   => $path,
            'status'      => 'pending',
            'admin_remarks' => null,
            'remarks'     => null,
            'verified_at' => null,
            'verified_by' => null,
            'uploaded_at' => now(),
        ]);

        $application->update([
            'status'       => Application::STATUS_PENDING,
            'admin_remarks' => null,
        ]);

        $fileMonitoring = $fileUpload->fileMonitoring;
        $fileMonitoring->overall_status = 'pending';
        $fileMonitoring->save();
    }

    /**
     * Re-submit a rejected application with a fresh set of requirement files.
     */
    public function resubmit(Application $application, array $files): void
    {
        $fileMonitoring = $application->fileMonitoring;

        foreach ($files as $reqName => $file) {
            // Delete old file if it exists for this requirement
            $existing = $fileMonitoring->fileUploads()->where('requirement_name', $reqName)->first();
            if ($existing && $existing->file_path) {
                Storage::disk('public')->delete($existing->file_path);
                $existing->delete();
            }

            $this->storeFile(
                $file,
                $application->id,
                $fileMonitoring->id,
                $application->user_id,
                $application->municipality,
                $reqName
            );
        }

        $application->update([
            'status'       => Application::STATUS_PENDING,
            'admin_remarks' => null,
        ]);

        $fileMonitoring->overall_status = 'pending';
        $fileMonitoring->save();
    }

    /**
     * Validate file size constraints: PDF ≤ 5 MB, images ≤ 25 MB.
     * Returns an error message string, or null if valid.
     */
    public function validateFileSize(UploadedFile $file): ?string
    {
        $ext  = strtolower($file->getClientOriginalExtension());
        $size = $file->getSize();
        $name = $file->getClientOriginalName();

        if ($ext === 'pdf' && $size > 5 * 1024 * 1024) {
            return "The PDF {$name} exceeds the 5 MB limit.";
        }

        if (in_array($ext, ['jpg', 'jpeg', 'png']) && $size > 25 * 1024 * 1024) {
            return "The image {$name} exceeds the 25 MB limit.";
        }

        if (!in_array($ext, ['pdf', 'jpg', 'jpeg', 'png'])) {
            return "Invalid file type for {$name}. Only JPG, PNG, PDF are allowed.";
        }

        return null;
    }

    // ── Private helpers ────────────────────────────────────────────────────

    private function storeFile(
        UploadedFile $file,
        int $applicationId,
        int $fileMonitoringId,
        int $userId,
        string $municipality,
        string $reqName
    ): void {
        $originalName = $file->getClientOriginalName();
        $filename     = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $originalName);
        $path         = $file->storeAs("applications/{$applicationId}/requirements", $filename, 'public');

        FileUpload::create([
            'file_monitoring_id' => $fileMonitoringId,
            'user_id'            => $userId,
            'municipality'       => $municipality,
            'file_name'          => $originalName,
            'file_path'          => $path,
            'requirement_name'   => $reqName,
            'status'             => 'pending',
            'uploaded_at'        => now(),
        ]);
    }
}
