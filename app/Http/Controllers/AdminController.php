<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\Application;
use App\Models\Appointment;
use App\Models\SocialWelfareProgram;
use App\Models\FileMonitoring;
use App\Models\FileUpload;
use App\Models\MunicipalityVision;
use App\Mail\RequirementsReviewedMail;
use App\Mail\SoloParentFilesUploadedMail;
use App\Mail\SoloParentIdReadyMail;
use App\Mail\PwdValidatedMail;
use App\Mail\PwdIdReadyMail;
use App\Mail\AicsStatusMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminController extends Controller
{
    // ── Shared admin notification helper ─────────────────────────────────
    private function adminNotifData($user): array
    {
        $lastViewed = \App\Models\NotificationView::where('user_id', $user->id)->first();
        $lastViewedAt = $lastViewed ? $lastViewed->last_viewed_at : null;

        // ── All pending applications (keep list visible after view) ───────────
        $query = Application::where('municipality', $user->municipality)
            ->where('status', 'pending')
            ->with('user')
            ->orderBy('application_date', 'desc');

        $adminNewApplications = (clone $query)->get();

        $newApplicationsCount = $lastViewedAt
            ? $adminNewApplications->filter(function ($app) use ($lastViewedAt) {
                return $app->application_date
                    && Carbon::parse($app->application_date)->gt(Carbon::parse($lastViewedAt));
            })->count()
            : $adminNewApplications->count();

        // ── All pending appointments (keep list visible after view) ───────────
        $apptQuery = Appointment::where('municipality', $user->municipality)
            ->where('status', 'pending')
            ->with('user')
            ->orderBy('created_at', 'desc');

        $adminNewAppointments = (clone $apptQuery)->get();

        $newAppointmentsCount = $lastViewedAt
            ? $adminNewAppointments->filter(function ($appt) use ($lastViewedAt) {
                return $appt->created_at
                    && Carbon::parse($appt->created_at)->gt(Carbon::parse($lastViewedAt));
            })->count()
            : $adminNewAppointments->count();

        // ── All pending Solo Parent document uploads (keep visible) ────────────
        $uploadsQuery = FileMonitoring::where('municipality', $user->municipality)
            ->where('overall_status', 'pending')
            ->whereHas('application', fn($q) => $q->where('program_type', 'Solo_Parent'))
            ->whereHas('fileUploads', fn($q) => $q->whereNotNull('file_path'))
            ->with(['application.user', 'fileUploads']);

        $adminNewUploads = (clone $uploadsQuery)->get();

        $newUploadsCount = $lastViewedAt
            ? $adminNewUploads->filter(function ($fm) use ($lastViewedAt) {
                $latestUpload = $fm->fileUploads
                    ->whereNotNull('file_path')
                    ->max('uploaded_at');
                return $latestUpload && Carbon::parse($latestUpload)->gt(Carbon::parse($lastViewedAt));
            })->count()
            : $adminNewUploads->count();

        $adminNotifCount = $newApplicationsCount + $newAppointmentsCount + $newUploadsCount;

        return compact('adminNewApplications', 'adminNewAppointments', 'adminNewUploads', 'adminNotifCount');
    }

    // Mark admin notifications as viewed
    public function markNotificationsViewed()
    {
        $user = Auth::user();
        \App\Models\NotificationView::updateOrCreate(
            ['user_id' => $user->id],
            ['last_viewed_at' => now()]
        );
        return response()->json(['success' => true]);
    }
    public function dashboard()
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->first();

        if (!$municipality) {
            return redirect()->route('dashboard')->with('error', 'No municipality assigned to your account.');
        }

        // Get applications for this municipality
        $applications = Application::where('municipality', $user->municipality)
            ->orderBy('application_date', 'desc')
            ->get();

        // Convert application_date to Carbon
        foreach ($applications as $app) {
            if (is_string($app->application_date)) {
                $app->application_date = Carbon::parse($app->application_date);
            }
        }

        // Statistics
        $totalApplications = $applications->count();
        $pendingApplications = $applications->where('status', 'pending')->count();
        $approvedApplications = $applications->where('status', 'approved')->count();
        $rejectedApplications = $applications->where('status', 'rejected')->count();

        // Applications by program type
        $applicationsByProgram = $applications->groupBy('program_type')
            ->map(function ($items) {
                return [
                    'total' => $items->count(),
                    'pending' => $items->where('status', 'pending')->count(),
                    'approved' => $items->where('status', 'approved')->count(),
                ];
            });

        // Barangays
        $barangays = Barangay::where('municipality', $user->municipality)->get();

        // Barangay statistics
        $barangayStats = [];
        foreach ($barangays as $barangay) {
            $barangayApps = $applications->where('barangay', $barangay->name);
            $barangayStats[$barangay->name] = [
                'total' => $barangayApps->count(),
                'pending' => $barangayApps->where('status', 'pending')->count(),
                'approved' => $barangayApps->where('status', 'approved')->count(),
                'population' => $barangay->male_population + $barangay->female_population,
                'households' => $barangay->total_households,
            ];
        }

        $totalBarangays = $barangays->count();
        $totalPrograms = SocialWelfareProgram::where('municipality', $user->municipality)->count();

        extract($this->adminNotifData($user));

        // Load current vision / mission / goals for this municipality
        $visionRow = MunicipalityVision::where('municipality_name', $user->municipality)->first();
        $visionData = [
            'vision' => $visionRow?->vision ?? '',
            'mission' => $visionRow?->mission ?? '',
            'goals' => $visionRow?->goals ?? '',
            'strategic_goals' => $visionRow?->strategic_goals ?? [],
        ];

        return view('admin.dashboard', compact(
            'municipality',
            'applications',
            'totalApplications',
            'pendingApplications',
            'approvedApplications',
            'rejectedApplications',
            'applicationsByProgram',
            'barangayStats',
            'totalBarangays',
            'totalPrograms',
            'adminNewApplications',
            'adminNewAppointments',
            'adminNewUploads',
            'adminNotifCount',
            'visionData'
        ));
    }

    // ── Save Vision / Mission / Goals + Strategic Goals ──────────────────────
    public function saveVisionMission(Request $request)
    {
        $request->validate([
            'municipality_name' => 'required|string|max:255',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'goals' => 'nullable|string',
            'strategic_goals' => 'nullable|array',
            'strategic_goals.*' => 'nullable|string|max:500',
        ]);

        $admin = Auth::user();
        // Ensure admin can only edit their own municipality
        if ($admin->municipality !== $request->municipality_name) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $all = $request->all();
        $data = [];

        // Only update VMG fields if they were sent in this request
        if (array_key_exists('vision', $all)) {
            $data['vision'] = $request->vision;
            $data['mission'] = $request->mission;
            $data['goals'] = $request->goals;
        }

        // Only update strategic_goals if they were sent in this request
        if (array_key_exists('strategic_goals', $all)) {
            $strategicGoals = collect($request->strategic_goals ?? [])
                ->filter(fn($v) => trim($v) !== '')
                ->values()
                ->toArray();
            $data['strategic_goals'] = $strategicGoals;
        }

        if (empty($data)) {
            return response()->json(['success' => false, 'message' => 'Nothing to save.'], 400);
        }

        MunicipalityVision::updateOrCreate(
            ['municipality_name' => $request->municipality_name],
            $data
        );

        return response()->json(['success' => true, 'message' => 'Saved successfully!']);
    }

    public function requirements(Request $request)
    {
        $admin = Auth::user();
        $municipality = $admin->municipality;

        $applicationsQuery = Application::where('municipality', $municipality);

        if ($request->filled('app_search')) {
            $search = trim((string) $request->input('app_search'));
            $applicationsQuery->where(function ($q) use ($search) {
                $q->where('full_name', 'like', '%' . $search . '%')
                    ->orWhere('contact_number', 'like', '%' . $search . '%')
                    ->orWhere('barangay', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('app_status')) {
            $applicationsQuery->where('status', $request->input('app_status'));
        }

        if ($request->filled('app_program')) {
            $applicationsQuery->where('program_type', $request->input('app_program'));
        }

        $applications = $applicationsQuery
            ->orderBy('application_date', 'desc')
            ->paginate(20);

        // Archived (soft-deleted) applications
        $archivedApplications = Application::onlyTrashed()
            ->where('municipality', $municipality)
            ->orderBy('deleted_at', 'desc')
            ->get();

        extract($this->adminNotifData($admin));

        return view('admin.requirements', compact(
            'applications',
            'municipality',
            'adminNewApplications',
            'adminNewUploads',
            'adminNewAppointments',
            'adminNotifCount',
            'archivedApplications'
        ));
    }

    // ── Archive (soft delete) ────────────────────────────────────────────────
    public function archiveApplication($id)
    {
        $admin = Auth::user();
        $application = Application::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->firstOrFail();

        $application->delete(); // soft delete

        return redirect()->route('admin.requirements')
            ->with('success', "Application of \"{$application->full_name}\" has been archived.");
    }

    // ── Restore from archive ─────────────────────────────────────────────────
    public function restoreApplication($id)
    {
        $admin = Auth::user();
        $application = Application::onlyTrashed()
            ->where('id', $id)
            ->where('municipality', $admin->municipality)
            ->firstOrFail();

        $application->restore();

        return redirect()->route('admin.requirements')
            ->with('success', "Application of \"{$application->full_name}\" has been restored.");
    }

    // ── Permanent delete ─────────────────────────────────────────────────────
    public function forceDeleteApplication($id)
    {
        $admin = Auth::user();
        $application = Application::onlyTrashed()
            ->where('id', $id)
            ->where('municipality', $admin->municipality)
            ->firstOrFail();

        $name = $application->full_name;
        $application->forceDelete();

        return redirect()->route('admin.requirements')
            ->with('success', "Application of \"{$name}\" has been permanently deleted.");
    }

    // ── Direct permanent delete (from active table, skips archive) ───────────
    public function directDeleteApplication($id)
    {
        $admin = Auth::user();
        $application = Application::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->firstOrFail();

        $name = $application->full_name;
        $application->forceDelete(); // bypasses soft delete entirely

        return redirect()->route('admin.requirements')
            ->with('success', "Application of \"{$name}\" has been permanently deleted.");
    }

    // ── Mark Solo Parent ID as ready for pickup ───────────────────────────────
    public function markIdReady($id)
    {
        $admin = Auth::user();
        $application = Application::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->firstOrFail();

        $isSoloParent = $application->program_type === 'Solo_Parent';
        $isPwd = in_array($application->program_type, ['PWD_Assistance', 'PWD_New', 'PWD_Renewal'], true);
        $isAics = in_array($application->program_type, ['AICS_Medical', 'AICS_Burial'], true);
        if (!$isSoloParent && !$isPwd && !$isAics) {
            $msg = 'Unsupported program for ID readiness.';
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->with('error', $msg);
        }

        if ($isPwd && $application->id_status !== 'processing') {
            $msg = 'PWD application must be validated before marking ID ready.';
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->with('error', $msg);
        }

        if ($isSoloParent && !($application->status === 'approved' && $application->id_status === 'processing')) {
            $msg = 'Solo Parent requirements must be fully approved before marking ID ready.';
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->with('error', $msg);
        }

        if ($isAics && !($application->status === 'approved' && $application->id_status === 'processing')) {
            $msg = 'AICS application must be validated before marking grants ready.';
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->with('error', $msg);
        }

        if ($application->id_status === 'ready_for_pickup') {
            $msg = 'Already marked as ready. No new email sent.';
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => true, 'message' => $msg]);
            }
            return redirect()->back()->with('success', $msg);
        }

        // Atomic transition to prevent duplicate email sends on rapid repeated clicks.
        $updated = Application::where('id', $application->id)
            ->where('municipality', $admin->municipality)
            ->where('id_status', 'processing')
            ->update([
                'id_status' => 'ready_for_pickup',
                'id_ready_at' => now(),
            ]);

        if ($updated === 0) {
            $msg = 'Already marked as ready. No new email sent.';
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => true, 'message' => $msg]);
            }
            return redirect()->back()->with('success', $msg);
        }

        $application->refresh();

        $user = \App\Models\User::find($application->user_id);
        if ($user && $user->email) {
            try {
                if ($isSoloParent) {
                    Mail::to($user->email)->send(new SoloParentIdReadyMail($application, $user));
                } elseif ($isPwd) {
                    Mail::to($user->email)->send(new PwdIdReadyMail($application, $user));
                } else {
                    Mail::to($user->email)->send(new AicsStatusMail($application, $user, 'ready_for_pickup'));
                }
            } catch (\Exception $e) {
                Log::error('IdReadyMail failed: ' . $e->getMessage());
            }
        }

        $label = $isPwd ? 'PWD ID' : ($isAics ? 'AICS grant' : 'Solo Parent ID');
        $msg = $label . ' marked as ready. Email sent to ' . ($user?->email ?? 'user') . '.';

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
            ]);
        }

        return redirect()->back()->with('success', $msg);
    }

    // ── Validate PWD application after admin approval ─────────────────────────
    public function validatePwdApplication($id)
    {
        $admin = Auth::user();
        $application = Application::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->whereIn('program_type', ['PWD_Assistance', 'PWD_New', 'PWD_Renewal'])
            ->firstOrFail();

        if ($application->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved PWD applications can be validated.');
        }

        if ($application->id_status === 'ready_for_pickup') {
            return redirect()->back()->with('error', 'PWD ID is already marked ready for pickup.');
        }

        $application->update([
            'id_status' => 'processing',
            'completed_at' => now(),
        ]);

        $user = \App\Models\User::find($application->user_id);
        if ($user && $user->email) {
            try {
                Mail::to($user->email)->send(new PwdValidatedMail($application, $user));
            } catch (\Exception $e) {
                Log::error('PwdValidatedMail failed: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'PWD requirements validated. User notified by email.');
    }

    public function validateAicsApplication($id)
    {
        $admin = Auth::user();
        $application = Application::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->whereIn('program_type', ['AICS_Medical', 'AICS_Burial'])
            ->firstOrFail();

        if ($application->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved AICS applications can be validated.');
        }

        if ($application->id_status === 'ready_for_pickup') {
            return redirect()->back()->with('error', 'AICS grant is already marked ready for pickup.');
        }

        $application->update([
            'id_status' => 'processing',
            'completed_at' => now(),
        ]);

        $user = \App\Models\User::find($application->user_id);
        if ($user && $user->email) {
            try {
                Mail::to($user->email)->send(new AicsStatusMail($application, $user, 'processing'));
            } catch (\Exception $e) {
                Log::error('AicsStatusMail validate failed: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'AICS requirements validated. User notified by email.');
    }



    public function viewRequirement($id)
    {
        $admin = Auth::user();

        $application = Application::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->firstOrFail();

        $fileMonitoring = FileMonitoring::with(['fileUploads'])
            ->where('application_id', $id)
            ->first(); // null if no documents uploaded yet

        $hasDocuments = $fileMonitoring
            && $fileMonitoring->fileUploads->whereNotNull('file_path')->count() > 0;

        $allApproved = $fileMonitoring
            && $fileMonitoring->overall_status === 'approved';

        return view('admin.view-requirement', compact(
            'fileMonitoring',
            'application',
            'hasDocuments',
            'allApproved'
        ));
    }

    /**
     * Serve an uploaded requirement file securely through the controller.
     * This bypasses the storage symlink (which often fails on Hostinger)
     * and streams the file directly from disk.
     *
     * Tries multiple path strategies to accommodate different Hostinger
     * deployment layouts (public_html root vs. public/ sub-folder).
     *
     * Pass ?dl=1 to force a file download instead of inline preview.
     */
    public function serveFile(Request $request, $id)
    {
        $admin = Auth::user();

        // Load the file upload and verify it belongs to this admin's municipality
        $fileUpload = FileUpload::with('fileMonitoring')
            ->where('id', $id)
            ->whereHas('fileMonitoring', function ($q) use ($admin) {
                $q->where('municipality', $admin->municipality);
            })
            ->firstOrFail();

        $filePath = $fileUpload->file_path;

        if (!$filePath) {
            abort(404, 'No file path stored for this record.');
        }

        // ── Candidate paths to try in order ─────────────────────────────────
        // 1. Standard Laravel: storage/app/public/{filePath}
        // 2. Hostinger root deployment: {base_path}/storage/app/public/{filePath}
        // 3. Fallback: public/storage/{filePath} (if symlink was manually created)
        $candidates = [
            \Illuminate\Support\Facades\Storage::disk('public')->path($filePath),
            base_path('storage/app/public/' . $filePath),
            public_path('storage/' . $filePath),
        ];

        $fullPath = null;
        foreach ($candidates as $candidate) {
            if (file_exists($candidate) && is_file($candidate)) {
                $fullPath = $candidate;
                break;
            }
        }

        if (!$fullPath) {
            Log::warning('serveFile: file not found for upload ID ' . $id, [
                'file_path' => $filePath,
                'candidates' => $candidates,
            ]);
            abort(404, 'File not found on disk. Path: ' . $filePath);
        }

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        $mimeMap = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'bmp' => 'image/bmp',
        ];

        $mime = $mimeMap[$ext] ?? 'application/octet-stream';
        $forceDownload = $request->query('dl') === '1';
        $disposition = $forceDownload
            ? 'attachment; filename="' . basename($filePath) . '"'
            : 'inline; filename="' . basename($filePath) . '"';

        return response()->file($fullPath, [
            'Content-Type' => $mime,
            'Content-Disposition' => $disposition,
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }


    public function updateFileStatus(Request $request, $id)
    {
        $admin = Auth::user();

        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_remarks' => 'required_if:status,rejected|nullable|string' // Required if rejected
        ]);

        $fileUpload = FileUpload::with(['fileMonitoring'])
            ->where('id', $id)
            ->whereHas('fileMonitoring', function ($q) use ($admin) {
                $q->where('municipality', $admin->municipality);
            })
            ->firstOrFail();

        $oldStatus = $fileUpload->status;
        $fileUpload->status = $request->status;
        $fileUpload->remarks = $request->remarks; // For admin use
        $fileUpload->admin_remarks = $request->admin_remarks; // This will show to user
        $fileUpload->verified_at = now();
        $fileUpload->verified_by = $admin->id;
        $fileUpload->save();

        // Update overall status
        $fileMonitoring = $fileUpload->fileMonitoring;
        $totalFiles = $fileMonitoring->fileUploads()->count();
        $approvedFiles = $fileMonitoring->fileUploads()->where('status', 'approved')->count();
        $rejectedFiles = $fileMonitoring->fileUploads()->where('status', 'rejected')->count();

        if ($rejectedFiles > 0) {
            $fileMonitoring->overall_status = 'rejected';

            // Ensure the application status becomes `rejected` when any file is rejected.
            // This is required so the mobile app can show rejected-file resubmission UI.
            if ($fileMonitoring->application) {
                $fileMonitoring->application->update([
                    'status' => 'rejected',
                    // Show the latest rejection note as the application-level reason.
                    'admin_remarks' => $request->admin_remarks ?? $fileMonitoring->application->admin_remarks,
                ]);
            }
        } elseif ($approvedFiles == $totalFiles && $totalFiles > 0) {
            $fileMonitoring->overall_status = 'approved';
            $appUpdates = ['status' => 'approved', 'completed_at' => now()];
            if (in_array(($fileMonitoring->application->program_type ?? null), ['Solo_Parent', 'AICS_Medical', 'AICS_Burial'], true)) {
                $appUpdates['id_status'] = 'processing';
            }
            $fileMonitoring->application->update($appUpdates);
        } else {
            $fileMonitoring->overall_status = 'in_review';
        }
        $fileMonitoring->save();

        // Create log
        \App\Models\FileStatusLog::create([
            'file_monitoring_id' => $fileMonitoring->id,
            'user_id' => $fileMonitoring->user_id,
            'municipality' => $admin->municipality,
            'changed_by' => $admin->id,
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'remarks' => $request->admin_remarks,
            'requirement_name' => $fileUpload->requirement_name,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ── Email user when requirements are reviewed (all program types) ────────
        if ($fileMonitoring->application && $fileMonitoring->application->user) {
            $fileMonitoring->load('fileUploads', 'application.user');
            $newOverall = $fileMonitoring->overall_status;

            // Notify user on every approve/reject action.
            // - If all files are approved => send "approved"
            // - If any file is rejected   => send "rejected"
            // - Otherwise                 => send generic "in_review" update
            if (in_array($request->status, ['approved', 'rejected'], true)) {
                try {
                    $mailStatus = match ($newOverall) {
                        'approved' => 'approved',
                        'rejected' => 'rejected',
                        default => 'in_review',
                    };

                    Mail::to($fileMonitoring->application->user->email)
                        ->send(new RequirementsReviewedMail($fileMonitoring, $mailStatus));
                } catch (\Exception $e) {
                    Log::error('Requirements reviewed email failed: ' . $e->getMessage());
                }
            }
        }


        return redirect()->back()->with('success', 'File status updated successfully!');
    }

    /**
     * Detailed Analysis Page — delegates to focused private helpers
     * to keep the public method small and IDE-friendly.
     */
    public function detailedAnalysis()
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->first();
        $applications = $this->loadApplications($user->municipality);

        [
            $totalApplications,
            $pendingApplications,
            $approvedApplications,
            $rejectedApplications,
            $applicationsByProgram
        ] = $this->buildApplicationStats($applications);

        $programShareOverview = $this->buildProgramOverview($user->municipality);

        // Get demographic data for this municipality
        $totalPop = $municipality->male_population + $municipality->female_population;
        $totalHouseholds = $municipality->total_households;

        // Gender distribution
        $genderData = [
            'male' => $municipality->male_population,
            'female' => $municipality->female_population,
        ];

        // Age group distribution
        $ageGroupData = [
            '0-19' => $municipality->population_0_19,
            '20-59' => $municipality->population_20_59,
            '60+' => $municipality->population_60_100,
        ];

        // Program beneficiaries - use same data as Program Share Overview
        $programBeneficiaries = $programShareOverview->toArray();

        return view('admin.detailed-analysis', compact(
            'municipality',
            'applications',
            'totalApplications',
            'pendingApplications',
            'approvedApplications',
            'rejectedApplications',
            'applicationsByProgram',
            'programShareOverview',
            'totalPop',
            'totalHouseholds',
            'genderData',
            'ageGroupData',
            'programBeneficiaries'
        ));
    }

    /** Load and date-parse applications for a given municipality. */
    private function loadApplications(string $municipality)
    {
        $applications = Application::where('municipality', $municipality)
            ->orderBy('application_date', 'desc')
            ->get();

        foreach ($applications as $app) {
            if (is_string($app->application_date)) {
                $app->application_date = Carbon::parse($app->application_date);
            }
        }

        return $applications;
    }

    /** Return counts + groupBy-program breakdown from a loaded collection. */
    private function buildApplicationStats($applications): array
    {
        $total = $applications->count();
        $pending = $applications->where('status', 'pending')->count();
        $approved = $applications->where('status', 'approved')->count();
        $rejected = $applications->where('status', 'rejected')->count();

        $byProgram = $applications->groupBy('program_type')->map(function ($items) {
            return [
                'total' => $items->count(),
                'pending' => $items->where('status', 'pending')->count(),
                'approved' => $items->where('status', 'approved')->count(),
                'rejected' => $items->where('status', 'rejected')->count(),
            ];
        });

        return [$total, $pending, $approved, $rejected, $byProgram];
    }

    /** Aggregate social welfare program beneficiary counts for the current year. */
    private function buildProgramOverview(string $municipality)
    {
        $programs = SocialWelfareProgram::where('municipality', $municipality)
            ->where('year', now()->year)
            ->get();

        $overview = [];
        foreach ($programs as $program) {
            $type = $program->program_type;
            $overview[$type] = ($overview[$type] ?? 0) + $program->beneficiary_count;
        }

        return collect($overview)->sortByDesc(fn($count) => $count);
    }

    /** Build per-barangay application statistics. */
    private function buildBarangayStats(string $municipality, $applications): array
    {
        $barangays = Barangay::where('municipality', $municipality)->get();
        $stats = [];

        foreach ($barangays as $barangay) {
            $apps = $applications->where('barangay', $barangay->name);
            $stats[$barangay->name] = [
                'total' => $apps->count(),
                'pending' => $apps->where('status', 'pending')->count(),
                'approved' => $apps->where('status', 'approved')->count(),
                'rejected' => $apps->where('status', 'rejected')->count(),
                'population' => $barangay->total_population ?? 0,
                'households' => $barangay->total_households,
            ];
        }

        return $stats;
    }

    public function applications(Request $request)
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->first();

        $query = Application::where('municipality', $user->municipality);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by program type
        if ($request->filled('program_type')) {
            $query->where('program_type', $request->program_type);
        }

        // Filter by barangay
        if ($request->filled('barangay')) {
            $query->where('barangay', $request->barangay);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('full_name', 'like', '%' . $request->search . '%');
        }

        $applications = $query->orderBy('application_date', 'desc')->paginate(15);

        $programTypes = Application::where('municipality', $user->municipality)
            ->distinct('program_type')
            ->pluck('program_type');

        $barangays = Barangay::where('municipality', $user->municipality)->pluck('name');

        return view('admin.applications', compact(
            'applications',
            'programTypes',
            'barangays',
            'municipality'
        ));
    }

    /**
     * Show demographic data for the admin's municipality only
     */
    public function demographic()
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->firstOrFail();

        // Get demographic data for this municipality only
        $totalPop = $municipality->male_population + $municipality->female_population;

        $demographicData = [
            'total_population' => $totalPop,
            'male' => $municipality->male_population,
            'female' => $municipality->female_population,
            'male_pct' => $totalPop > 0 ? round(($municipality->male_population / $totalPop) * 100, 1) : 0,
            'female_pct' => $totalPop > 0 ? round(($municipality->female_population / $totalPop) * 100, 1) : 0,
            'age_0_19' => $municipality->population_0_19,
            'age_20_59' => $municipality->population_20_59,
            'age_60_100' => $municipality->population_60_100,
            'age_0_19_pct' => $totalPop > 0 ? round(($municipality->population_0_19 / $totalPop) * 100, 1) : 0,
            'age_20_59_pct' => $totalPop > 0 ? round(($municipality->population_20_59 / $totalPop) * 100, 1) : 0,
            'age_60_100_pct' => $totalPop > 0 ? round(($municipality->population_60_100 / $totalPop) * 100, 1) : 0,
        ];

        // Get barangay data for this municipality
        $barangays = Barangay::where('municipality', $user->municipality)
            ->orderBy('name')
            ->get();

        $barangayData = [];
        foreach ($barangays as $barangay) {
            $barangayPop = $barangay->male_population + $barangay->female_population;
            $barangayData[] = [
                'name' => $barangay->name,
                'population' => $barangayPop,
                'male' => $barangay->male_population,
                'female' => $barangay->female_population,
                'households' => $barangay->total_households,
                'single_parents' => $barangay->single_parent_count,
            ];
        }

        return view('admin.demographic', compact(
            'municipality',
            'demographicData',
            'barangayData'
        ));
    }

    public function updateApplicationStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_remarks' => 'required_if:status,rejected|nullable|string|max:1000',
        ]);

        $application = Application::findOrFail($id);

        if ($application->municipality !== Auth::user()->municipality) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Guard: cannot approve if no documents have been uploaded
        if ($request->status === 'approved') {
            $fileMonitoring = FileMonitoring::where('application_id', $application->id)->first();
            $uploadCount = $fileMonitoring
                ? $fileMonitoring->fileUploads()->whereNotNull('file_path')->count()
                : 0;

            if ($uploadCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot approve: this applicant has not submitted any documents yet.',
                ], 422);
            }
        }

        $oldStatus = $application->status;
        $application->status = $request->status;
        $application->admin_remarks = $request->admin_remarks;
        $application->save();

        // Sync FileMonitoring and FileUpload records
        $fileMonitoring = FileMonitoring::where('application_id', $application->id)->first();

        if ($fileMonitoring) {
            if ($request->status === 'rejected') {
                $fileMonitoring->overall_status = 'rejected';
                $fileMonitoring->save();
                FileUpload::where('file_monitoring_id', $fileMonitoring->id)->update([
                    'status' => 'rejected',
                    'admin_remarks' => $request->admin_remarks ?? 'Application rejected',
                    'verified_at' => now(),
                    'verified_by' => Auth::user()->id,
                ]);
            } elseif ($request->status === 'approved') {
                $fileMonitoring->overall_status = 'approved';
                $fileMonitoring->save();
                FileUpload::where('file_monitoring_id', $fileMonitoring->id)->update([
                    'status' => 'approved',
                    'admin_remarks' => null,
                    'verified_at' => now(),
                    'verified_by' => Auth::user()->id,
                ]);
            } elseif ($request->status === 'pending') {
                $fileMonitoring->overall_status = 'pending';
                $fileMonitoring->save();
                FileUpload::where('file_monitoring_id', $fileMonitoring->id)->update([
                    'status' => 'pending',
                    'admin_remarks' => null,
                    'verified_at' => null,
                    'verified_by' => null,
                ]);
            }
        }

        // Update barangay approved count
        if ($oldStatus !== 'approved' && $request->status === 'approved') {
            $barangay = Barangay::where('municipality', $application->municipality)
                ->where('name', $application->barangay)->first();
            if ($barangay) {
                $barangay->total_approved_applications += 1;
                $barangay->save();
            }
        }

        if ($oldStatus === 'approved' && $request->status !== 'approved') {
            $barangay = Barangay::where('municipality', $application->municipality)
                ->where('name', $application->barangay)->first();
            if ($barangay && $barangay->total_approved_applications > 0) {
                $barangay->total_approved_applications -= 1;
                $barangay->save();
            }
        }

        // Audit log
        Log::info('Application status updated', [
            'application_id' => $application->id,
            'applicant' => $application->full_name,
            'changed_by' => Auth::user()->id,
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'municipality' => Auth::user()->municipality,
            'remarks' => $request->admin_remarks,
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Application status updated']);
        }

        return redirect()->back()->with('success', 'Application status updated successfully!');
    }

    public function barangay($name)
    {
        $user = Auth::user();
        $barangay = Barangay::where('municipality', $user->municipality)
            ->where('name', $name)
            ->firstOrFail();

        $applications = Application::where('municipality', $user->municipality)
            ->where('barangay', $name)
            ->orderBy('application_date', 'desc')
            ->get();

        $statistics = [
            'total_applications' => $applications->count(),
            'pending' => $applications->where('status', 'pending')->count(),
            'approved' => $applications->where('status', 'approved')->count(),
            'rejected' => $applications->where('status', 'rejected')->count(),
            'by_program' => $applications->groupBy('program_type')->map->count(),
            'by_gender' => [
                'male' => $applications->where('gender', 'Male')->count(),
                'female' => $applications->where('gender', 'Female')->count(),
            ],
            'average_age' => $applications->avg('age'),
        ];

        return view('admin.barangay', compact('barangay', 'applications', 'statistics'));
    }

    // ── Admin User Management — view users in this municipality ───────────────
    public function users()
    {
        $admin = Auth::user();
        $municipality = Municipality::where('name', $admin->municipality)->first();

        // All regular users in this municipality
        $users = \App\Models\User::where('municipality', $admin->municipality)
            ->where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($user) {
                $apps = Application::where('user_id', $user->id);
                $user->total_apps = (clone $apps)->count();
                $user->pending_apps = (clone $apps)->where('status', 'pending')->count();
                $user->approved_apps = (clone $apps)->where('status', 'approved')->count();
                $user->rejected_apps = (clone $apps)->where('status', 'rejected')->count();
                return $user;
            });

        extract($this->adminNotifData($admin));

        return view('admin.users', compact(
            'municipality',
            'users',
            'adminNewApplications',
            'adminNewAppointments',
            'adminNewUploads',
            'adminNotifCount'
        ));
    }

    public function searchUsers(Request $request)
    {
        $admin = Auth::user();
        $q = strtolower(trim((string) $request->query('q', '')));
        $appFilter = (string) $request->query('app_filter', '');

        $users = \App\Models\User::where('municipality', $admin->municipality)
            ->where('role', 'user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($user) {
                $apps = Application::where('user_id', $user->id);
                $user->total_apps = (clone $apps)->count();
                $user->pending_apps = (clone $apps)->where('status', 'pending')->count();
                $user->approved_apps = (clone $apps)->where('status', 'approved')->count();
                $user->rejected_apps = (clone $apps)->where('status', 'rejected')->count();
                return $user;
            });

        if ($q !== '') {
            $users = $users->filter(function ($u) use ($q) {
                return str_contains(strtolower((string) $u->full_name), $q)
                    || str_contains(strtolower((string) $u->email), $q)
                    || str_contains(strtolower((string) ($u->barangay ?? '')), $q);
            })->values();
        }

        if ($appFilter === 'with') {
            $users = $users->filter(fn($u) => (int) $u->total_apps > 0)->values();
        } elseif ($appFilter === 'without') {
            $users = $users->filter(fn($u) => (int) $u->total_apps === 0)->values();
        }

        return response()->json([
            'count' => $users->count(),
            'users' => $users->map(function ($u) {
                return [
                    'id' => $u->id,
                    'full_name' => $u->full_name,
                    'email' => $u->email,
                    'phone_number' => $u->phone_number,
                    'barangay' => $u->barangay,
                    'gender' => $u->gender,
                    'date_of_birth' => $u->date_of_birth,
                    'created_at' => optional($u->created_at)->toDateString(),
                    'email_verified_at' => $u->email_verified_at,
                    'total_apps' => (int) $u->total_apps,
                    'pending_apps' => (int) $u->pending_apps,
                    'approved_apps' => (int) $u->approved_apps,
                    'rejected_apps' => (int) $u->rejected_apps,
                ];
            })->values(),
        ]);
    }
}