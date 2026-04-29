<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\FileUpload;
use App\Models\FileMonitoring;
use App\Models\PwdRequirementCheck;
use App\Models\User; // <-- ADD THIS
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes; // <-- ADD THIS (optional, for type hinting)
use App\Mail\NewApplicationNotification;
use App\Mail\AppointmentStatusMail;
use App\Models\Appointment;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UserController extends Controller
{
    // ── Shared notification helper ──────────────────────────────────────────
    private function notificationData($user): array
    {
        $lastViewed   = \App\Models\NotificationView::where('user_id', $user->id)->first();
        $lastViewedAt = $lastViewed ? $lastViewed->last_viewed_at : null;

        $documentNotifications = FileUpload::whereHas('fileMonitoring', fn($q) => $q->where('user_id', $user->id))
            ->whereIn('status', ['approved', 'rejected'])
            ->with(['fileMonitoring.application'])
            ->orderBy('verified_at', 'desc')
            ->orderBy('uploaded_at', 'desc')
            ->get();

        $rejectedApplications = Application::where('user_id', $user->id)
            ->where('status', 'rejected')
            ->with('fileMonitoring.fileUploads')
            ->orderBy('application_date', 'desc')
            ->get();

        // New announcements — created after last-viewed, or last 7 days if never viewed
        try {
            $annQuery = \App\Models\Announcement::forUser($user)->orderBy('created_at', 'desc');
            $newAnnouncements = $lastViewedAt
                ? (clone $annQuery)->where('created_at', '>', $lastViewedAt)->get()
                : (clone $annQuery)->where('created_at', '>=', now()->subDays(7))->get();
        } catch (\Exception $e) {
            $newAnnouncements = collect();
        }

        $newDocCount = $lastViewedAt
            ? $documentNotifications->filter(function ($d) use ($lastViewedAt) {
                $ts = $d->verified_at ?? $d->uploaded_at;
                return $ts && Carbon::parse($ts)->gt(Carbon::parse($lastViewedAt));
            })->count()
            : $documentNotifications->count();

        $newAppCount = $lastViewedAt
            ? $rejectedApplications->filter(function ($a) use ($lastViewedAt) {
                return $a->application_date
                    && Carbon::parse($a->application_date)->gt(Carbon::parse($lastViewedAt));
            })->count()
            : $rejectedApplications->count();

        // ── Validated Solo Parent appointment (user is now eligible) ──────────
        $validatedAppointment = Appointment::where('user_id', $user->id)
            ->where('program_type', 'Solo_Parent')
            ->where('status', 'validated')
            ->latest('validated_at')
            ->first();

        $newValidatedCount = 0;
        if ($validatedAppointment) {
            $validatedTs = $validatedAppointment->validated_at ?? $validatedAppointment->updated_at;
            if (!$lastViewedAt || ($validatedTs && Carbon::parse($validatedTs)->gt(Carbon::parse($lastViewedAt)))) {
                $newValidatedCount = 1;
            }
        }

        // ── Solo Parent ID ready for pickup ───────────────────────────────────
        $idReadyApplication = Application::where('user_id', $user->id)
            ->where('program_type', 'Solo_Parent')
            ->where('id_status', 'ready_for_pickup')
            ->latest('id_ready_at')
            ->first();

        $idReadyCount = 0;
        if ($idReadyApplication) {
            $readyTs = $idReadyApplication->id_ready_at;
            if (!$lastViewedAt || ($readyTs && Carbon::parse($readyTs)->gt(Carbon::parse($lastViewedAt)))) {
                $idReadyCount = 1;
            }
        }

        $notificationCount = $newDocCount + $newAppCount + $newAnnouncements->count() + $newValidatedCount + $idReadyCount;

        return compact('documentNotifications', 'rejectedApplications', 'newAnnouncements', 'notificationCount', 'validatedAppointment', 'idReadyApplication');
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Check if user is logged in
        if (!$user) {
            return redirect()->route('login');
        }

        $totalApplications = Application::where('user_id', $user->id)->count();
        $pendingCount = Application::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();
        $approvedCount = Application::where('user_id', $user->id)
            ->where('status', 'approved')
            ->count();
        $rejectedCount = Application::where('user_id', $user->id)
            ->where('status', 'rejected')
            ->count();

        $recentApplications = Application::where('user_id', $user->id)
            ->orderBy('application_date', 'desc')
            ->take(5)
            ->get();

        $announcements = collect();

        $notifData = $this->notificationData($user);
        extract($notifData); // documentNotifications, rejectedApplications, newAnnouncements, notificationCount

        return view('user.dashboard', compact(
            'totalApplications',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'recentApplications',
            'announcements',
            'documentNotifications',
            'rejectedApplications',
            'newAnnouncements',
            'notificationCount'
        ));
    }

    public function programs()
    {
        $user = Auth::user();
        extract($this->notificationData($user));
        return view('user.programs', compact('documentNotifications', 'rejectedApplications', 'newAnnouncements', 'notificationCount'));
    }

    public function announcements(Request $request)
    {
        $user = Auth::user();

        // Notification bell data (shared helper)
        extract($this->notificationData($user));

        // ── Announcements page query ─────────────────────────────────────────
        try {
            $query = \App\Models\Announcement::forUser($user)
                ->orderBy('created_at', 'desc');

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }
            if ($request->filled('program')) {
                $query->where('program_type', $request->program);
            }

            $announcements = $query->paginate(12)->withQueryString();
        } catch (\Exception $e) {
            $announcements = collect();
        }

        $types    = \App\Models\Announcement::TYPES;
        $programs = \App\Models\Announcement::PROGRAMS;

        return view('user.announcements', compact(
            'announcements', 'types', 'programs',
            'documentNotifications', 'rejectedApplications', 'newAnnouncements', 'notificationCount'
        ));
    }





    
public function myRequirements()
    {
        $user = Auth::user();

        // Get all applications of the user with their file uploads
        $applications = Application::where('user_id', $user->id)
            ->with(['fileMonitoring.fileUploads'])
            ->orderBy('application_date', 'desc')
            ->get();

        // Prepare data for requirements view
        $requirementsData = [];

        foreach ($applications as $application) {
            $fileMonitoring = $application->fileMonitoring;
            $fileUploads = $fileMonitoring ? $fileMonitoring->fileUploads : collect();

            // Determine overall status
            $overallStatus = $application->status; // 'pending', 'approved', 'rejected'

            $requirementsData[] = [
                'application' => $application,
                'fileUploads' => $fileUploads,
                'totalRequirements' => $fileUploads->count(),
                'uploadedCount' => $fileUploads->where('file_path', '!=', null)->count(),
                'approvedCount' => $fileUploads->where('status', 'approved')->count(),
                'rejectedCount' => $fileUploads->where('status', 'rejected')->count(),
                'pendingCount' => $fileUploads->where('status', 'pending')->count(),
                'overallStatus' => $overallStatus
            ];
        }

        extract($this->notificationData($user));

        return view('user.my-requirements', compact('requirementsData', 'documentNotifications', 'rejectedApplications', 'newAnnouncements', 'notificationCount'));
    }
    /**
     * Resubmit a rejected requirement
     */
    public function resubmitRequirement(Request $request, $fileUploadId)
    {
        try {
            $user = Auth::user();

            $request->validate([
                'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:25600' // 25MB max for PDF, 5MB for images (validated client-side)
            ]);

            $fileUpload = FileUpload::with('fileMonitoring.application')
                ->findOrFail($fileUploadId);
            
            // Verify ownership
            if (!$fileUpload->fileMonitoring || $fileUpload->fileMonitoring->user_id != $user->id) {
                return redirect()->back()->with('error', 'Unauthorized access to this file.');
            }

            // Validate file size based on type
            $file = $request->file('file');
            $isImage = in_array($file->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png']);
            $maxSize = $isImage ? 5 * 1024 * 1024 : 25 * 1024 * 1024; // 5MB for images, 25MB for PDF
            
            if ($file->getSize() > $maxSize) {
                $maxSizeLabel = $isImage ? '5MB' : '25MB';
                return redirect()->back()->with('error', "File size must be less than {$maxSizeLabel} for " . ($isImage ? 'images' : 'PDF files') . '.');
            }

            // Delete old file
            if ($fileUpload->file_path && Storage::disk('public')->exists($fileUpload->file_path)) {
                Storage::disk('public')->delete($fileUpload->file_path);
            }

            // Upload new file
            $originalName = $file->getClientOriginalName();
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $originalName);
            $applicationId = $fileUpload->fileMonitoring->application_id;
            $path = $file->storeAs("applications/{$applicationId}/requirements", $filename, 'public');

            // Update file upload record
            $fileUpload->update([
                'file_name' => $originalName,
                'file_path' => $path,
                'status' => 'pending', // Reset to pending
                'remarks' => null, // Clear old remarks
                'admin_remarks' => null, // Clear admin remarks
                'verified_at' => null,
                'verified_by' => null,
                'uploaded_at' => now()
            ]);

            // Update overall status of file monitoring
            $fileMonitoring = $fileUpload->fileMonitoring;
            $totalFiles = $fileMonitoring->fileUploads()->count();
            $pendingFiles = $fileMonitoring->fileUploads()->where('status', 'pending')->count();
            $rejectedFiles = $fileMonitoring->fileUploads()->where('status', 'rejected')->count();

            if ($rejectedFiles > 0) {
                $fileMonitoring->overall_status = 'rejected';
            }
            elseif ($pendingFiles > 0) {
                $fileMonitoring->overall_status = 'pending';
                // Also update application status back to pending
                $fileMonitoring->application->update(['status' => 'pending']);
            }
            else {
                $fileMonitoring->overall_status = 'in_review';
            }
            $fileMonitoring->save();

            return redirect()->back()->with('success', 'Document re-uploaded successfully! Waiting for admin review.');
        } catch (\Exception $e) {
            \Log::error('Resubmit Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to upload file: ' . $e->getMessage());
        }
    }
    public function pwdApplication()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $pwdRequirements = [
            'Completed PRPWD Application Form',
            'Certificate of Disability (original + 1 photocopy)',
            'Two (2) recent 1×1 ID pictures',
            'Valid government-issued ID',
        ];

        // Find the user's latest PWD application
        $application = Application::where('user_id', $user->id)
            ->whereIn('program_type', ['PWD_Assistance', 'PWD_New', 'PWD_Renewal'])
            ->latest('id')
            ->first();

        // Get uploaded files for this application (if any)
        $uploadedFiles = collect();
        if ($application) {
            $fm = FileMonitoring::where('application_id', $application->id)->first();
            if ($fm) {
                $uploadedFiles = FileUpload::where('file_monitoring_id', $fm->id)->get();
            }
        }

        return view('user.pwd-application', compact('user', 'application', 'uploadedFiles', 'pwdRequirements'));
    }
    public function pwdFillableForm()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        return view('user.pwd-fillable-form', ['user' => Auth::user()]);
    }
    public function pwdFormSubmit(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'date_of_birth' => 'required|date',
            'sex' => 'required|in:male,female',
            'civil_status' => 'required|string',
            'barangay_address' => 'required|string|max:100',
            'municipality_address' => 'required|string|max:100',
            'signed_copy' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // Collect all form data as JSON
        $formData = $request->except(['_token', 'signed_copy', 'municipality_hidden', 'program_type']);

        // Handle checkbox arrays
        $formData['disability'] = $request->input('disability', []);
        $formData['cause_type'] = $request->input('cause_type', []);
        $formData['cause_inborn'] = $request->input('cause_inborn', []);

        // Handle photo upload
        if ($request->hasFile('applicant_photo')) {
            $path = $request->file('applicant_photo')->store('pwd-photos', 'public');
            $formData['applicant_photo_path'] = $path;
        }

        // Handle signed copy upload
        $signedCopyPath = null;
        if ($request->hasFile('signed_copy')) {
            $signedCopyPath = $request->file('signed_copy')->store('pwd-signed-forms', 'public');
            $formData['signed_copy_path'] = $signedCopyPath;
        }

        $fullName = trim($request->first_name . ' ' . ($request->middle_name ?? '') . ' ' . $request->last_name);

        // Create Application record
        $application = Application::create([
            'user_id' => $user->id,
            'program_type' => 'PWD_Assistance',
            'municipality' => $request->municipality_address ?? $user->municipality ?? '',
            'barangay' => $request->barangay_address ?? '',
            'full_name' => $fullName,
            'age' => $user->age ?? 0,
            'gender' => $request->sex,
            'contact_number' => $request->mobile ?? $user->contact_number ?? '',
            'status' => 'pending',
            'application_date' => now(),
            'year' => now()->year,
            'form_data' => $formData,
            'stage' => 'form_submitted',
        ]);

        return redirect()->route('user.pwd-form')->with('pwd_form_success', true);
    }

    public function soloParentApplication()
    {
        $user = Auth::user();

        // Load user's active/recent appointment
        $appointment = Appointment::where('user_id', $user->id)
            ->where('program_type', 'Solo_Parent')
            ->orderByRaw("FIELD(status,'pending','confirmed','rejected','cancelled')")
            ->orderBy('appointment_date', 'desc')
            ->first();

        // Tomorrow reminder: send email once if appointment is tomorrow and not yet reminded
        if ($appointment
            && in_array($appointment->status, ['pending', 'confirmed'])
            && $appointment->reminded_at === null
            && Carbon::parse($appointment->appointment_date)->isTomorrow()
        ) {
            try {
                Mail::to($user->email)->send(new AppointmentStatusMail($appointment, 'reminder'));
                $appointment->update(['reminded_at' => now()]);
            } catch (\Exception $e) {
                Log::error('Appointment reminder email failed: ' . $e->getMessage());
            }
        }

        $minDate = Carbon::tomorrow()->format('Y-m-d');
        $maxDate = Carbon::now()->addDays(30)->format('Y-m-d');

        // If validated, load the associated Application with its file monitoring / uploads
        $soloParentApplication = null;
        if ($appointment && $appointment->solo_parent_app_id) {
            $soloParentApplication = Application::with([
                'fileMonitoring.fileUploads'
            ])->find($appointment->solo_parent_app_id);
        }

        return view('user.solo-parent-application', compact('appointment', 'minDate', 'maxDate', 'soloParentApplication'));
    }

    public function uploadPwdRequirement(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'requirement_name' => 'required|string',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $user = Auth::user();

        // Auto-create PWD application for this user if none exists
        $application = Application::where('user_id', $user->id)
            ->whereIn('program_type', ['PWD_Assistance', 'PWD_New', 'PWD_Renewal'])
            ->latest('id')
            ->first();

        $isNewApplication = !$application;

        if (!$application) {
            // gender column is ENUM('Male','Female') NOT NULL — must be valid
            $genderRaw = strtolower(trim($user->gender ?? ''));
            $gender = $genderRaw === 'female' ? 'Female' : 'Male'; // default Male if unknown

            $application = Application::create([
                'user_id' => $user->id,
                'program_type' => 'PWD_Assistance',
                'municipality' => $user->municipality ?? '',
                'barangay' => $user->barangay ?? '',
                'full_name' => $user->name ?? $user->full_name ?? '',
                'age' => is_numeric($user->age ?? null) ? (int)$user->age : 0,
                'gender' => $gender,
                'contact_number' => $user->contact_number ?? '',
                'status' => 'pending',
                'application_date' => now(),
                'year' => now()->year,
                'stage' => 'documents_upload',
            ]);

            // Notify admin(s) by email on first application creation
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
                        'municipality'   => $application->municipality,
                    ]);
                }
            }
        }

        // Ensure file_monitoring record exists
        $fileMonitoring = FileMonitoring::firstOrCreate(
        ['application_id' => $application->id],
        [
            'overall_status' => 'pending',
            'municipality' => $application->municipality ?? $user->municipality ?? '',
            'user_id' => $user->id,
        ]
        );
        // Patch municipality if it's still null (existing records)
        if (!$fileMonitoring->municipality) {
            $fileMonitoring->municipality = $application->municipality ?? $user->municipality ?? '';
            $fileMonitoring->user_id = $user->id;
            $fileMonitoring->save();
        }

        // Store the uploaded file
        $file = $request->file('file');
        $folder = 'applications/' . $application->id . '/requirements';
        $filePath = $file->store($folder, 'public');

        // Upsert into file_uploads
        FileUpload::updateOrCreate(
        [
            'file_monitoring_id' => $fileMonitoring->id,
            'requirement_name' => $request->requirement_name,
        ],
        [
            'file_path' => $filePath,
            'file_name' => $file->getClientOriginalName(),
            'status' => 'pending',
            'uploaded_at' => now(),
            'admin_remarks' => null,
        ]
        );

        // Also save/update pwd_requirement_checks
        $requirementKey = Str::slug($request->requirement_name, '_');
        PwdRequirementCheck::updateOrCreate(
        [
            'application_id' => $application->id,
            'requirement_key' => $requirementKey,
        ],
        [
            'requirement_label' => $request->requirement_name,
            'status' => 'submitted',
            'file_path' => $filePath,
            'admin_notes' => null,
        ]
        );

        // Refresh overall status
        $uploads = FileUpload::where('file_monitoring_id', $fileMonitoring->id)->get();
        if ($uploads->where('status', 'rejected')->count() > 0) {
            $fileMonitoring->overall_status = 'rejected';
        }
        elseif ($uploads->where('status', 'approved')->count() === $uploads->count()) {
            $fileMonitoring->overall_status = 'approved';
        }
        else {
            $fileMonitoring->overall_status = 'in_review';
        }
        $fileMonitoring->save();

        return redirect()->route('user.pwd-application')
            ->with('upload_success', '"' . $request->requirement_name . '" uploaded successfully!');
    }

    /**
     * AICS category selection page for Solo Parent Support
     */
    public function aicsCategory()
    {
        $user = Auth::user();
        return view('user.aics-category', compact('user'));
    }

    public function aicsMedical()
    {
        $user = Auth::user();
        $requirements = [
            'Certificate of Indigency (Original)',
            'Medical Certificate',
            'Marriage Contract (If claimant is spouse)',
            'Birth Certificate (If claimant is parent or children)',
            'Photocopy of Valid ID of patient and claimant',
            'Authorization Letter (If claimant is married children)',
        ];
        $application = Application::where('user_id', $user->id)
            ->where('program_type', 'AICS_Medical')
            ->latest('id')->first();
        $uploadedFiles = collect();
        if ($application) {
            $fm = FileMonitoring::where('application_id', $application->id)->first();
            if ($fm)
                $uploadedFiles = FileUpload::where('file_monitoring_id', $fm->id)->get();
        }
        return view('user.aics-medical', compact('user', 'application', 'uploadedFiles', 'requirements'));
    }

    public function aicsBurial()
    {
        $user = Auth::user();
        $requirements = [
            'Certificate of Indigency (Original)',
            'Death Certificate with Registry No.',
            'Marriage Contract (If claimant is spouse)',
            'Birth Certificate (If claimant is parent, children, or siblings)',
            "Photocopy of Valid ID of claimant (Voter's ID)",
            'Authorization Letter and ID of the authorizing person',
        ];
        $application = Application::where('user_id', $user->id)
            ->where('program_type', 'AICS_Burial')
            ->latest('id')->first();
        $uploadedFiles = collect();
        if ($application) {
            $fm = FileMonitoring::where('application_id', $application->id)->first();
            if ($fm)
                $uploadedFiles = FileUpload::where('file_monitoring_id', $fm->id)->get();
        }
        return view('user.aics-burial', compact('user', 'application', 'uploadedFiles', 'requirements'));
    }

    private function uploadAicsRequirement(Request $request, string $programType, string $redirectRoute): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'requirement_name' => 'required|string',
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $user = Auth::user();

        $genderRaw = strtolower(trim($user->gender ?? ''));
        $gender = $genderRaw === 'female' ? 'Female' : 'Male';

        $application = Application::firstOrCreate(
        ['user_id' => $user->id, 'program_type' => $programType],
        [
            'municipality' => $user->municipality ?? '',
            'barangay' => $user->barangay ?? '',
            'full_name' => $user->full_name ?? $user->name ?? '',
            'age' => is_numeric($user->age ?? null) ? (int)$user->age : 0,
            'gender' => $gender,
            'contact_number' => $user->contact_number ?? '',
            'status' => 'pending',
            'application_date' => now(),
            'year' => now()->year,
            'stage' => 'documents_upload',
        ]
        );

        $fileMonitoring = FileMonitoring::firstOrCreate(
        ['application_id' => $application->id],
        ['overall_status' => 'pending', 'municipality' => $user->municipality ?? '']
        );

        // Notify admin by email only when the application is FIRST created
        if ($application->wasRecentlyCreated) {
            try {
                $admins = User::where('role', 'admin')
                    ->where('municipality', $application->municipality)
                    ->get();
                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(new NewApplicationNotification($application));
                }
            } catch (\Exception $e) {
                Log::error('Admin email notification failed: ' . $e->getMessage(), [
                    'application_id' => $application->id,
                    'municipality'   => $application->municipality,
                ]);
            }
        }

        $file = $request->file('file');
        $folder = 'applications/' . $application->id . '/requirements';
        $filePath = $file->store($folder, 'public');

        FileUpload::updateOrCreate(
        ['file_monitoring_id' => $fileMonitoring->id, 'requirement_name' => $request->requirement_name],
        ['file_path' => $filePath, 'file_name' => $file->getClientOriginalName(), 'status' => 'pending', 'uploaded_at' => now(), 'admin_remarks' => null]
        );

        // Refresh overall_status
        $uploads = FileUpload::where('file_monitoring_id', $fileMonitoring->id)->get();
        if ($uploads->where('status', 'rejected')->count() > 0)
            $fileMonitoring->overall_status = 'rejected';
        elseif ($uploads->where('status', 'approved')->count() === $uploads->count())
            $fileMonitoring->overall_status = 'approved';
        else
            $fileMonitoring->overall_status = 'in_review';
        $fileMonitoring->save();

        return redirect()->route($redirectRoute)
            ->with('upload_success', '"' . $request->requirement_name . '" uploaded successfully!');
    }

    /**
     * Batch upload: accept multiple files (one per requirement) in a single request.
     * Each file input is named files[REQUIREMENT_NAME].
     */
    private function uploadAicsBatch(Request $request, string $programType, string $redirectRoute): \Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'files'   => 'required|array|min:1',
            'files.*' => 'required|file|mimes:jpg,jpeg,png,pdf|max:25600',
        ]);

        $genderRaw = strtolower(trim($user->gender ?? ''));
        $gender    = $genderRaw === 'female' ? 'Female' : 'Male';

        $application = Application::firstOrCreate(
            ['user_id' => $user->id, 'program_type' => $programType],
            [
                'municipality'    => $user->municipality ?? '',
                'barangay'        => $user->barangay ?? '',
                'full_name'       => $user->full_name ?? $user->name ?? '',
                'age'             => is_numeric($user->age ?? null) ? (int)$user->age : 0,
                'gender'          => $gender,
                'contact_number'  => $user->contact_number ?? '',
                'status'          => 'pending',
                'application_date'=> now(),
                'year'            => now()->year,
                'stage'           => 'documents_upload',
            ]
        );

        $fileMonitoring = FileMonitoring::firstOrCreate(
            ['application_id' => $application->id],
            ['overall_status' => 'pending', 'municipality' => $user->municipality ?? '']
        );

        // Notify admin only on first application creation
        if ($application->wasRecentlyCreated) {
            try {
                $admins = User::where('role', 'admin')
                    ->where('municipality', $application->municipality)
                    ->get();
                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(new NewApplicationNotification($application));
                }
            } catch (\Exception $e) {
                Log::error('Admin email notification failed (batch): ' . $e->getMessage(), [
                    'application_id' => $application->id,
                ]);
            }
        }

        $uploadedCount = 0;
        foreach ($request->file('files') as $reqName => $file) {
            $reqNameClean = str_replace(['[', ']'], '', $reqName);
            $folder   = 'applications/' . $application->id . '/requirements';
            $filePath = $file->store($folder, 'public');

            FileUpload::updateOrCreate(
                ['file_monitoring_id' => $fileMonitoring->id, 'requirement_name' => $reqNameClean],
                [
                    'file_path'    => $filePath,
                    'file_name'    => $file->getClientOriginalName(),
                    'status'       => 'pending',
                    'uploaded_at'  => now(),
                    'admin_remarks'=> null,
                ]
            );
            $uploadedCount++;
        }

        // Refresh overall status
        $uploads = FileUpload::where('file_monitoring_id', $fileMonitoring->id)->get();
        if ($uploads->where('status', 'rejected')->count() > 0)
            $fileMonitoring->overall_status = 'rejected';
        elseif ($uploads->where('status', 'approved')->count() === $uploads->count())
            $fileMonitoring->overall_status = 'approved';
        else
            $fileMonitoring->overall_status = 'in_review';
        $fileMonitoring->save();

        return redirect()->route($redirectRoute)
            ->with('upload_success', $uploadedCount . ' document(s) uploaded successfully!');
    }

    public function uploadAicsMedicalBatch(Request $request)
    {
        return $this->uploadAicsBatch($request, 'AICS_Medical', 'user.aics-medical');
    }

    public function uploadAicsBurialBatch(Request $request)
    {
        return $this->uploadAicsBatch($request, 'AICS_Burial', 'user.aics-burial');
    }

    public function uploadAicsMedical(Request $request)
    {
        return $this->uploadAicsRequirement($request, 'AICS_Medical', 'user.aics-medical');
    }

    public function uploadAicsBurial(Request $request)
    {
        return $this->uploadAicsRequirement($request, 'AICS_Burial', 'user.aics-burial');
    }

    public function markNotificationsViewed()
    {
        $user = Auth::user();
        
        \App\Models\NotificationView::updateOrCreate(
            ['user_id' => $user->id],
            ['last_viewed_at' => now()]
        );
        
        return response()->json(['success' => true]);
    }

}
