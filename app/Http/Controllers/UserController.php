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

class UserController extends Controller
{
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

        return view('user.dashboard', compact(
            'totalApplications',
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'recentApplications',
            'announcements'
        ));
    }

    public function programs()
    {
        return view('user.programs');
    }

    public function announcements()
    {
        try {
            $announcements = \App\Models\Announcement::where('is_active', true)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }
        catch (\Exception $e) {
            // If table doesn't exist yet, return empty collection
            $announcements = collect();
        }

        return view('user.announcements', compact('announcements'));
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

        return view('user.my-requirements', compact('requirementsData'));
    }
    /**
     * Resubmit a rejected requirement
     */public function resubmitRequirement(Request $request, $fileUploadId)
    {
        $user = Auth::user();

        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120'
        ]);

        $fileUpload = FileUpload::where('id', $fileUploadId)
            ->whereHas('fileMonitoring', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->firstOrFail();

        // Delete old file
        if ($fileUpload->file_path && Storage::disk('public')->exists($fileUpload->file_path)) {
            Storage::disk('public')->delete($fileUpload->file_path);
        }

        // Upload new file
        $file = $request->file('file');
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
        return view('user.solo-parent-application');
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

    public function uploadAicsMedical(Request $request)
    {
        return $this->uploadAicsRequirement($request, 'AICS_Medical', 'user.aics-medical');
    }

    public function uploadAicsBurial(Request $request)
    {
        return $this->uploadAicsRequirement($request, 'AICS_Burial', 'user.aics-burial');
    }

}
