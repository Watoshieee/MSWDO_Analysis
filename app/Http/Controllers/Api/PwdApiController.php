<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\FileMonitoring;
use App\Models\FileUpload;
use App\Models\PwdRequirementCheck;
use App\Models\User;
use App\Mail\NewApplicationNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PwdApiController extends Controller
{
    /**
     * PWD requirement labels — kept in sync with UserController::pwdApplication().
     */
    private const PWD_REQUIREMENTS = [
        'Completed PRPWD Application Form',
        'Certificate of Disability (original + 1 photocopy)',
        'Two (2) recent 1×1 ID pictures',
        'Valid government-issued ID',
    ];

    // ──────────────────────────────────────────────────────────────────────
    //  GET /mobile-api/pwd/application
    //  Returns the user's latest PWD application, its requirements, and
    //  overall statuses. Mirrors the web UserController::pwdApplication().
    // ──────────────────────────────────────────────────────────────────────
    public function getApplication(): JsonResponse
    {
        $user = Auth::user();

        $application = Application::with(['fileMonitoring.fileUploads'])
            ->where('user_id', $user->id)
            ->whereIn('program_type', ['PWD_Assistance', 'PWD_New', 'PWD_Renewal'])
            ->latest('id')
            ->first();

        // Build requirement list with upload statuses
        $uploads = collect();
        if ($application?->fileMonitoring) {
            $uploads = $application->fileMonitoring->fileUploads;
        }

        $requirements = array_map(function (string $reqName) use ($uploads) {
            $upload = $uploads->firstWhere('requirement_name', $reqName);

            return [
                'name'          => $reqName,
                'status'        => $upload?->status ?? 'not_uploaded',
                'uploaded_at'   => $upload?->uploaded_at?->toIso8601String(),
                'admin_remarks' => $upload?->admin_remarks,
                'file_id'       => $upload?->id,
            ];
        }, self::PWD_REQUIREMENTS);

        $isPwdBeneficiary = Application::where('user_id', $user->id)
            ->whereIn('program_type', ['PWD_Assistance', 'PWD_New', 'PWD_Renewal'])
            ->whereIn('id_status', ['processing', 'ready_for_pickup', 'released'])
            ->exists();

        return response()->json([
            'application' => $application ? [
                'id'               => $application->id,
                'program_type'     => $application->program_type,
                'status'           => $application->status,
                'id_status'        => $application->id_status,
                'stage'            => $application->stage,
                'admin_remarks'    => $application->admin_remarks,
                'application_date' => $application->application_date?->format('Y-m-d'),
                'id_ready_at'      => $application->id_ready_at,
                'completed_at'     => $application->completed_at,
            ] : null,
            'requirements'       => $requirements,
            'is_pwd_beneficiary' => $isPwdBeneficiary,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────
    //  POST /mobile-api/pwd/requirements/upload
    //  Upload a single PWD requirement document.
    //  Mirrors UserController::uploadPwdRequirement() from the web app.
    // ──────────────────────────────────────────────────────────────────────
    public function uploadRequirement(Request $request): JsonResponse
    {
        $user = Auth::user();

        // Block if already a beneficiary
        $isBeneficiary = Application::where('user_id', $user->id)
            ->whereIn('program_type', ['PWD_Assistance', 'PWD_New', 'PWD_Renewal'])
            ->whereIn('id_status', ['processing', 'ready_for_pickup', 'released'])
            ->exists();

        if ($isBeneficiary) {
            return response()->json([
                'success' => false,
                'message' => 'PWD re-application is disabled because you are already a PWD beneficiary.',
            ], 409);
        }

        $request->validate([
            'requirement_name' => 'required|string',
            'file'             => 'required|file|mimes:jpg,jpeg,png,pdf|max:25600',
        ]);

        // Find or create the application
        $application = Application::where('user_id', $user->id)
            ->whereIn('program_type', ['PWD_Assistance', 'PWD_New', 'PWD_Renewal'])
            ->latest('id')
            ->first();

        $isNewApplication = !$application;

        if (!$application) {
            $genderRaw = strtolower(trim($user->gender ?? ''));
            $gender = $genderRaw === 'female' ? 'Female' : 'Male';

            $application = Application::create([
                'user_id'          => $user->id,
                'program_type'     => 'PWD_Assistance',
                'municipality'     => $user->municipality ?? '',
                'barangay'         => $user->barangay ?? '',
                'full_name'        => $user->full_name ?? '',
                'age'              => is_numeric($user->age ?? null) ? (int) $user->age : 0,
                'gender'           => $gender,
                'contact_number'   => $user->mobile_number ?? '',
                'status'           => 'pending',
                'application_date' => now(),
                'year'             => now()->year,
                'stage'            => 'documents_upload',
            ]);

            // Notify admin(s) by email
            if ($isNewApplication) {
                try {
                    $admins = User::where('role', 'admin')
                        ->where('municipality', $application->municipality)
                        ->get();
                    foreach ($admins as $admin) {
                        Mail::to($admin->email)->send(new NewApplicationNotification($application));
                    }
                } catch (\Exception $e) {
                    Log::error('PWD admin email notification failed: ' . $e->getMessage(), [
                        'application_id' => $application->id,
                    ]);
                }
            }
        }

        // Ensure file_monitoring record exists
        $fileMonitoring = FileMonitoring::firstOrCreate(
            ['application_id' => $application->id],
            [
                'overall_status' => 'pending',
                'municipality'   => $application->municipality ?? $user->municipality ?? '',
                'user_id'        => $user->id,
            ]
        );

        if (!$fileMonitoring->municipality) {
            $fileMonitoring->municipality = $application->municipality ?? $user->municipality ?? '';
            $fileMonitoring->user_id = $user->id;
            $fileMonitoring->save();
        }

        // Store the uploaded file
        $file     = $request->file('file');
        $folder   = 'applications/' . $application->id . '/requirements';
        $filePath = $file->store($folder, 'public');

        // Upsert into file_uploads
        FileUpload::updateOrCreate(
            [
                'file_monitoring_id' => $fileMonitoring->id,
                'requirement_name'   => $request->requirement_name,
            ],
            [
                'file_path'     => $filePath,
                'file_name'     => $file->getClientOriginalName(),
                'status'        => 'pending',
                'uploaded_at'   => now(),
                'admin_remarks' => null,
            ]
        );

        // Also save/update pwd_requirement_checks
        $requirementKey = Str::slug($request->requirement_name, '_');
        PwdRequirementCheck::updateOrCreate(
            [
                'application_id'  => $application->id,
                'requirement_key' => $requirementKey,
            ],
            [
                'requirement_label' => $request->requirement_name,
                'status'            => 'submitted',
                'file_path'         => $filePath,
                'admin_notes'       => null,
            ]
        );

        // Refresh overall status
        $uploads = FileUpload::where('file_monitoring_id', $fileMonitoring->id)->get();
        if ($uploads->where('status', 'rejected')->count() > 0) {
            $fileMonitoring->overall_status = 'rejected';
        } elseif ($uploads->where('status', 'approved')->count() === $uploads->count()) {
            $fileMonitoring->overall_status = 'approved';
        } else {
            $fileMonitoring->overall_status = 'in_review';
        }
        $fileMonitoring->save();

        return response()->json([
            'success' => true,
            'message' => '"' . $request->requirement_name . '" uploaded successfully!',
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────
    //  POST /mobile-api/pwd/requirements/{fileId}/reupload
    //  Re-upload a rejected requirement (mirrors MobileApiController::reuploadFile)
    // ──────────────────────────────────────────────────────────────────────
    public function reuploadRequirement(Request $request, int $fileId): JsonResponse
    {
        $user = Auth::user();

        $fileUpload = FileUpload::with(['fileMonitoring.application'])
            ->where('id', $fileId)
            ->whereHas('fileMonitoring', fn ($q) => $q->where('user_id', $user->id))
            ->firstOrFail();

        if ($fileUpload->status !== 'rejected') {
            return response()->json([
                'success' => false,
                'message' => 'Only rejected files can be re-uploaded.',
            ], 400);
        }

        $request->validate(['file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:25600']);

        $file = $request->file('file');

        // Delete old file
        if ($fileUpload->file_path && Storage::disk('public')->exists($fileUpload->file_path)) {
            Storage::disk('public')->delete($fileUpload->file_path);
        }

        // Upload new file
        $applicationId = $fileUpload->fileMonitoring->application_id;
        $folder   = 'applications/' . $applicationId . '/requirements';
        $filePath = $file->store($folder, 'public');

        $fileUpload->update([
            'file_name'     => $file->getClientOriginalName(),
            'file_path'     => $filePath,
            'status'        => 'pending',
            'admin_remarks' => null,
            'verified_at'   => null,
            'verified_by'   => null,
            'uploaded_at'   => now(),
        ]);

        // Update overall status
        $fm = $fileUpload->fileMonitoring;
        $allUploads = $fm->fileUploads()->get();
        if ($allUploads->where('status', 'rejected')->count() > 0) {
            $fm->overall_status = 'rejected';
        } elseif ($allUploads->where('status', 'pending')->count() > 0) {
            $fm->overall_status = 'pending';
            $fm->application?->update(['status' => 'pending']);
        } else {
            $fm->overall_status = 'in_review';
        }
        $fm->save();

        return response()->json([
            'success' => true,
            'message' => 'File re-uploaded successfully. Pending admin review.',
        ]);
    }
}
