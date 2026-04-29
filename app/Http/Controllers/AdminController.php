<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\Application;
use App\Models\Appointment;
use App\Models\SocialWelfareProgram;
use App\Models\FileMonitoring;
use App\Models\FileUpload;
use App\Mail\RequirementsReviewedMail;
use App\Mail\SoloParentFilesUploadedMail;
use App\Mail\SoloParentIdReadyMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    // ── Shared admin notification helper ─────────────────────────────────
    private function adminNotifData($user): array
    {
        $lastViewed   = \App\Models\NotificationView::where('user_id', $user->id)->first();
        $lastViewedAt = $lastViewed ? $lastViewed->last_viewed_at : null;

        // ── New pending applications ──────────────────────────────────────────
        $query = Application::where('municipality', $user->municipality)
            ->where('status', 'pending')
            ->with('user')
            ->orderBy('application_date', 'desc');

        $adminNewApplications = $lastViewedAt
            ? (clone $query)->where('application_date', '>', $lastViewedAt)->get()
            : (clone $query)->where('application_date', '>=', now()->subDays(3))->get();

        // ── New pending Solo Parent appointments (booked after last viewed) ───
        $apptQuery = Appointment::where('municipality', $user->municipality)
            ->where('program_type', 'Solo_Parent')
            ->where('status', 'pending')
            ->with('user')
            ->orderBy('created_at', 'desc');

        $adminNewAppointments = $lastViewedAt
            ? (clone $apptQuery)->where('created_at', '>', $lastViewedAt)->get()
            : (clone $apptQuery)->where('created_at', '>=', now()->subDays(3))->get();

        // ── New Solo Parent document uploads waiting for review ─────────────────
        $uploadsQuery = FileMonitoring::where('municipality', $user->municipality)
            ->where('overall_status', 'pending')
            ->whereHas('application', fn($q) => $q->where('program_type', 'Solo_Parent'))
            ->whereHas('fileUploads', fn($q) => $q->whereNotNull('file_path'))
            ->with(['application.user', 'fileUploads']);

        $adminNewUploads = $lastViewedAt
            ? (clone $uploadsQuery)->whereHas('fileUploads', fn($q) =>
                    $q->where('uploaded_at', '>', $lastViewedAt)->whereNotNull('file_path')
              )->get()
            : (clone $uploadsQuery)->whereHas('fileUploads', fn($q) =>
                    $q->where('uploaded_at', '>=', now()->subDays(3))->whereNotNull('file_path')
              )->get();

        $adminNotifCount = $adminNewApplications->count() + $adminNewAppointments->count() + $adminNewUploads->count();

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
                $app->application_date = \Carbon\Carbon::parse($app->application_date);
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
            'adminNotifCount'
        ));
    }

    public function requirements()
    {
        $admin = Auth::user();
        $municipality = $admin->municipality;

        $applications = Application::where('municipality', $municipality)
            ->orderBy('application_date', 'desc')
            ->paginate(20);

        // Archived (soft-deleted) applications
        $archivedApplications = Application::onlyTrashed()
            ->where('municipality', $municipality)
            ->orderBy('deleted_at', 'desc')
            ->get();

        extract($this->adminNotifData($admin));

        return view('admin.requirements', compact(
            'applications', 'municipality',
            'adminNewApplications', 'adminNewUploads',
            'adminNewAppointments', 'adminNotifCount',
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
        $admin       = Auth::user();
        $application = Application::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->where('program_type', 'Solo_Parent')
            ->firstOrFail();

        // Guard: all documents must be approved before marking ID ready
        $fileMonitoring = FileMonitoring::where('application_id', $application->id)->first();
        if (!$fileMonitoring || $fileMonitoring->overall_status !== 'approved') {
            $errorMsg = 'Cannot mark ID as ready: not all required documents have been approved yet.';

            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }
            return redirect()
                ->route('admin.view-requirement', $application->id)
                ->with('error', $errorMsg);
        }

        $application->update([
            'id_status'   => 'ready_for_pickup',
            'id_ready_at' => now(),
        ]);

        $user = \App\Models\User::find($application->user_id);
        if ($user && $user->email) {
            try {
                Mail::to($user->email)->send(new SoloParentIdReadyMail($application, $user));
            } catch (\Exception $e) {
                Log::error('SoloParentIdReadyMail failed', [
                    'application_id' => $application->id,
                    'user_id'        => $user->id,
                    'error'          => $e->getMessage(),
                ]);
            }
        }

        Log::info('Solo Parent ID marked as ready', [
            'application_id' => $application->id,
            'applicant'      => $application->full_name,
            'marked_by'      => $admin->id,
            'municipality'   => $admin->municipality,
            'notified_email' => $user?->email,
        ]);

        $successMsg = 'ID marked as ready for pickup.' .
            ($user?->email ? ' Email notification sent to ' . $user->email . '.' : '');

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => $successMsg]);
        }

        return redirect()
            ->route('admin.view-requirement', $application->id)
            ->with('success', $successMsg);
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
            'fileMonitoring', 'application', 'hasDocuments', 'allApproved'
        ));
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
        } elseif ($approvedFiles == $totalFiles && $totalFiles > 0) {
            $fileMonitoring->overall_status = 'approved';
            $fileMonitoring->application->update(['status' => 'approved']);
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
            'created_at' => now()
        ]);

        // ── Email user when their Solo Parent requirements are reviewed ────────
        if ($fileMonitoring->application && $fileMonitoring->application->program_type === 'Solo_Parent') {
            $fileMonitoring->load('fileUploads', 'application.user');
            $newOverall = $fileMonitoring->overall_status;

            // Notify user when a file is individually rejected
            if ($request->status === 'rejected') {
                try {
                    Mail::to($fileMonitoring->application->user->email)
                        ->send(new RequirementsReviewedMail($fileMonitoring, 'rejected'));
                } catch (\Exception $e) {
                    Log::error('Requirements reviewed (reject) email failed: ' . $e->getMessage());
                }
            }

            // Notify user when ALL files are approved (overall becomes 'approved')
            if ($newOverall === 'approved') {
                try {
                    Mail::to($fileMonitoring->application->user->email)
                        ->send(new RequirementsReviewedMail($fileMonitoring, 'approved'));
                } catch (\Exception $e) {
                    Log::error('Requirements reviewed (all approved) email failed: ' . $e->getMessage());
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
        $user         = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->first();
        $applications = $this->loadApplications($user->municipality);

        [$totalApplications, $pendingApplications,
         $approvedApplications, $rejectedApplications,
         $applicationsByProgram] = $this->buildApplicationStats($applications);

        $programShareOverview = $this->buildProgramOverview($user->municipality);
        $barangayStats        = $this->buildBarangayStats($user->municipality, $applications);

        return view('admin.detailed-analysis', compact(
            'municipality',
            'applications',
            'totalApplications',
            'pendingApplications',
            'approvedApplications',
            'rejectedApplications',
            'applicationsByProgram',
            'programShareOverview',
            'barangayStats'
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
                $app->application_date = \Carbon\Carbon::parse($app->application_date);
            }
        }

        return $applications;
    }

    /** Return counts + groupBy-program breakdown from a loaded collection. */
    private function buildApplicationStats($applications): array
    {
        $total    = $applications->count();
        $pending  = $applications->where('status', 'pending')->count();
        $approved = $applications->where('status', 'approved')->count();
        $rejected = $applications->where('status', 'rejected')->count();

        $byProgram = $applications->groupBy('program_type')->map(function ($items) {
            return [
                'total'    => $items->count(),
                'pending'  => $items->where('status', 'pending')->count(),
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
            ->where('year', 2024)
            ->get();

        $overview = [];
        foreach ($programs as $program) {
            $type = $program->program_type;
            $overview[$type] = ($overview[$type] ?? 0) + $program->beneficiary_count;
        }

        return collect($overview)->sortByDesc(fn ($count) => $count);
    }

    /** Build per-barangay application statistics. */
    private function buildBarangayStats(string $municipality, $applications): array
    {
        $barangays = Barangay::where('municipality', $municipality)->get();
        $stats     = [];

        foreach ($barangays as $barangay) {
            $apps = $applications->where('barangay', $barangay->name);
            $stats[$barangay->name] = [
                'total'      => $apps->count(),
                'pending'    => $apps->where('status', 'pending')->count(),
                'approved'   => $apps->where('status', 'approved')->count(),
                'rejected'   => $apps->where('status', 'rejected')->count(),
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
            'status'        => 'required|in:pending,approved,rejected',
            'admin_remarks' => 'required_if:status,rejected|nullable|string|max:1000',
        ]);

        $application = Application::findOrFail($id);

        if ($application->municipality !== Auth::user()->municipality) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Guard: cannot approve if no documents have been uploaded
        if ($request->status === 'approved') {
            $fileMonitoring = FileMonitoring::where('application_id', $application->id)->first();
            $uploadCount    = $fileMonitoring
                ? $fileMonitoring->fileUploads()->whereNotNull('file_path')->count()
                : 0;

            if ($uploadCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot approve: this applicant has not submitted any documents yet.',
                ], 422);
            }
        }

        $oldStatus              = $application->status;
        $application->status       = $request->status;
        $application->admin_remarks = $request->admin_remarks;
        $application->save();

        // Sync FileMonitoring and FileUpload records
        $fileMonitoring = FileMonitoring::where('application_id', $application->id)->first();

        if ($fileMonitoring) {
            if ($request->status === 'rejected') {
                $fileMonitoring->overall_status = 'rejected';
                $fileMonitoring->save();
                FileUpload::where('file_monitoring_id', $fileMonitoring->id)->update([
                    'status'        => 'rejected',
                    'admin_remarks' => $request->admin_remarks ?? 'Application rejected',
                    'verified_at'   => now(),
                    'verified_by'   => Auth::user()->id,
                ]);
            } elseif ($request->status === 'approved') {
                $fileMonitoring->overall_status = 'approved';
                $fileMonitoring->save();
                FileUpload::where('file_monitoring_id', $fileMonitoring->id)->update([
                    'status'        => 'approved',
                    'admin_remarks' => null,
                    'verified_at'   => now(),
                    'verified_by'   => Auth::user()->id,
                ]);
            } elseif ($request->status === 'pending') {
                $fileMonitoring->overall_status = 'pending';
                $fileMonitoring->save();
                FileUpload::where('file_monitoring_id', $fileMonitoring->id)->update([
                    'status'        => 'pending',
                    'admin_remarks' => null,
                    'verified_at'   => null,
                    'verified_by'   => null,
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
            'applicant'      => $application->full_name,
            'changed_by'     => Auth::user()->id,
            'old_status'     => $oldStatus,
            'new_status'     => $request->status,
            'municipality'   => Auth::user()->municipality,
            'remarks'        => $request->admin_remarks,
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
}