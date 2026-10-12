<?php

namespace App\Http\Controllers;

use App\Models\Municipality;
use App\Models\Barangay;
use App\Models\Application;
use App\Models\Appointment;
use App\Models\SocialWelfareProgram;
use App\Models\MunicipalityYearlySummary;
use App\Models\FileMonitoring;
use App\Models\FileUpload;
use App\Models\MunicipalityVision;
use App\Mail\RequirementsReviewedMail;
use App\Mail\SoloParentFilesUploadedMail;
use App\Mail\SoloParentIdReadyMail;
use App\Mail\SoloParentClaimedMail;
use App\Mail\AicsGrantReleasedMail;
use App\Mail\PwdValidatedMail;
use App\Mail\PwdIdReadyMail;
use App\Mail\AicsStatusMail;
use App\Mail\UserIdApprovedMail;
use App\Mail\UserIdDeclinedMail;
use App\Services\DeviceRegistrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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

        // ── Unread admin-targeted notifications (reschedule/cancel requests etc) ──
        try {
            $adminUnreadCount = \DB::table('notifications')
                ->where('user_id', $user->id)
                ->where('is_read', false)
                ->count();
            $adminNotifCount += $adminUnreadCount;
        } catch (\Exception $e) {
            // If notifications table doesn't exist yet, ignore
        }

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

        // Municipality Snapshot: fetch 2024 (or latest year) from MunicipalityYearlySummary
        $snapshotYear = 2024;
        $yearlySummary = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->where('year', $snapshotYear)
            ->first();

        // If no 2024 data, fall back to the latest available year
        if (!$yearlySummary) {
            $yearlySummary = MunicipalityYearlySummary::where('municipality', $user->municipality)
                ->orderBy('year', 'desc')
                ->first();
            $snapshotYear = $yearlySummary?->year ?? $snapshotYear;
        }

        $snapshotPopulation = $yearlySummary?->total_population ?? 0;
        $snapshotHouseholds = $yearlySummary?->total_households ?? 0;

        // Recent applications (last 5) for dashboard feed
        $recentApplications = $applications->sortByDesc('application_date')->take(5);

        return view('admin.dashboard', compact(
            'municipality',
            'applications',
            'recentApplications',
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
            'visionData',
            'snapshotHouseholds',
            'snapshotPopulation',
            'snapshotYear'
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
            ->orderByRaw("FIELD(status, 'pending', 'approved', 'rejected')")
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

        \DB::transaction(function () use ($application) {
            $application->delete(); // soft delete

            // Soft-delete associated appointment(s)
            Appointment::where('solo_parent_app_id', $application->id)->delete();
        });

        if (class_exists(\App\Services\DashboardService::class)) {
            try {
                app(\App\Services\DashboardService::class)->invalidateStats($application->user_id);
            } catch (\Throwable $e) {
                Log::warning("Failed to invalidate dashboard stats for user {$application->user_id}: " . $e->getMessage());
            }
        }

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

        \DB::transaction(function () use ($application) {
            $application->restore();

            // Find associated trashed appointments
            $trashedAppts = Appointment::onlyTrashed()
                ->where('solo_parent_app_id', $application->id)
                ->get();

            foreach ($trashedAppts as $appt) {
                // If it was validated (eligibility check passed) and application is active, restore it
                if ($appt->status === 'validated') {
                    $appt->restore();
                    continue;
                }

                // If pending or confirmed, check if appointment date/time is already in the past
                if (in_array($appt->status, ['pending', 'confirmed'])) {
                    $isPast = false;
                    try {
                        if ($appt->appointment_date) {
                            $dateStr = $appt->appointment_date->format('Y-m-d');
                            $timeStr = $appt->appointment_time ? substr(trim($appt->appointment_time), 0, 5) : '00:00';
                            $isPast = Carbon::parse("$dateStr $timeStr")->isPast();
                        }
                    } catch (\Throwable $e) {
                        $isPast = true;
                    }

                    if ($isPast) {
                        // Stale past appointment: mark cancelled so user is not trapped on an expired schedule
                        $appt->update(['status' => 'cancelled']);
                    } else {
                        // Future pending/confirmed slot may be restored
                        $appt->restore();
                    }
                }
            }
        });

        if (class_exists(\App\Services\DashboardService::class)) {
            try {
                app(\App\Services\DashboardService::class)->invalidateStats($application->user_id);
            } catch (\Throwable $e) {
                Log::warning("Failed to invalidate dashboard stats for user {$application->user_id}: " . $e->getMessage());
            }
        }

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
        $userId = $application->user_id;

        \DB::transaction(function () use ($application) {
            // Permanently delete associated appointment(s)
            Appointment::withTrashed()
                ->where('solo_parent_app_id', $application->id)
                ->forceDelete();

            $application->forceDelete();
        });

        if (class_exists(\App\Services\DashboardService::class)) {
            try {
                app(\App\Services\DashboardService::class)->invalidateStats($userId);
            } catch (\Throwable $e) {
                Log::warning("Failed to invalidate dashboard stats for user {$userId}: " . $e->getMessage());
            }
        }

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
        $userId = $application->user_id;

        \DB::transaction(function () use ($application) {
            // Permanently delete associated appointment(s)
            Appointment::withTrashed()
                ->where('solo_parent_app_id', $application->id)
                ->forceDelete();

            $application->forceDelete(); // bypasses soft delete entirely
        });

        if (class_exists(\App\Services\DashboardService::class)) {
            try {
                app(\App\Services\DashboardService::class)->invalidateStats($userId);
            } catch (\Throwable $e) {
                Log::warning("Failed to invalidate dashboard stats for user {$userId}: " . $e->getMessage());
            }
        }

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

        if ($isSoloParent) {
            $fileMonitoring = $application->fileMonitoring;
            $completion = \App\Services\SoloParentCategoryService::evaluateCompletion($application, $fileMonitoring);
            if (!$completion['is_complete'] || !($application->status === 'approved' && $application->id_status === 'processing')) {
                $msg = 'Solo Parent requirements must be fully satisfied and approved before marking ID ready.';
                if (request()->expectsJson() || request()->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }
                return redirect()->back()->with('error', $msg);
            }
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

        // ── Create in-app notification for user ─────────────────────────
        $label = $isPwd ? 'PWD ID' : ($isAics ? 'AICS grant' : 'Solo Parent ID');
        $programLabel = $isPwd ? 'pwd' : ($isAics ? 'aics' : 'solo_parent');
        try {
            $notifTitle = $label . ' Ready for Pickup';
            $notifBody  = 'Your ' . $label . ' is now ready for pickup at the MSWDO office. Please bring a valid ID when claiming.';
            $notifId = \DB::table('notifications')->insertGetId([
                'user_id'    => $application->user_id,
                'type'       => $programLabel,
                'title'      => $notifTitle,
                'body'       => $notifBody,
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            \App\Services\OneSignalService::sendPush($application->user_id, $notifTitle, $notifBody, $programLabel, $notifId, ['program_type' => $application->program_type]);
        } catch (\Exception $e) {
            Log::error('markIdReady notification insert failed: ' . $e->getMessage());
        }

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

        $msg = $label . ' marked as ready. Email sent to ' . ($user?->email ?? 'user') . '.';

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $msg,
            ]);
        }

        return redirect()->back()->with('success', $msg);
    }

    // ── Mark Solo Parent ID as claimed ───────────────────────────────────────
    public function markClaimed($id)
    {
        $admin = Auth::user();
        $application = Application::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->where('program_type', 'Solo_Parent')
            ->firstOrFail();

        if ($application->id_status !== 'ready_for_pickup') {
            $msg = 'Application must be in \'Ready for Pickup\' status before marking as claimed.';
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->with('error', $msg);
        }

        // Atomic transition — prevent duplicate operations
        $updated = Application::where('id', $application->id)
            ->where('municipality', $admin->municipality)
            ->where('id_status', 'ready_for_pickup')
            ->update([
                'id_status'    => 'claimed',
                'completed_at' => now(),
            ]);

        if ($updated === 0) {
            $msg = 'Already marked as claimed.';
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => true, 'message' => $msg]);
            }
            return redirect()->back()->with('success', $msg);
        }

        $application->refresh();
        $user = \App\Models\User::find($application->user_id);

        // ── Bell notification ────────────────────────────────────────────────
        try {
            $notifTitle = 'Solo Parent ID Claimed';
            $notifBody  = 'Your Solo Parent ID has been successfully claimed and recorded. Congratulations!';
            $notifId = \DB::table('notifications')->insertGetId([
                'user_id'    => $application->user_id,
                'type'       => 'solo_parent',
                'title'      => $notifTitle,
                'body'       => $notifBody,
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            \App\Services\OneSignalService::sendPush($application->user_id, $notifTitle, $notifBody, 'solo_parent', $notifId, ['program_type' => $application->program_type]);
        } catch (\Exception $e) {
            Log::error('markClaimed notification insert failed: ' . $e->getMessage());
        }

        // ── Email user ───────────────────────────────────────────────────────
        if ($user && $user->email) {
            try {
                Mail::to($user->email)->send(new SoloParentClaimedMail($application, $user));
            } catch (\Exception $e) {
                Log::error('SoloParentClaimedMail failed: ' . $e->getMessage());
            }
        }

        $msg = 'Solo Parent ID marked as claimed. Confirmation email sent to ' . ($user?->email ?? 'user') . '.';

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => $msg]);
        }

        return redirect()->back()->with('success', $msg);
    }

    // ── Mark AICS grant as released (final step) ──────────────────────────────
    public function markReleased($id)
    {
        $admin = Auth::user();
        $application = Application::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->whereIn('program_type', ['AICS_Medical', 'AICS_Burial'])
            ->firstOrFail();

        if ($application->id_status !== 'ready_for_pickup') {
            $msg = 'Application must be in \'Ready for Pickup\' status before marking as released.';
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->with('error', $msg);
        }

        // Atomic transition
        $updated = Application::where('id', $application->id)
            ->where('municipality', $admin->municipality)
            ->where('id_status', 'ready_for_pickup')
            ->update([
                'id_status'    => 'released',
                'completed_at' => now(),
            ]);

        if ($updated === 0) {
            $msg = 'Already marked as released.';
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json(['success' => true, 'message' => $msg]);
            }
            return redirect()->back()->with('success', $msg);
        }

        $application->refresh();
        $user = \App\Models\User::find($application->user_id);

        $programLabel = $application->program_type === 'AICS_Burial'
            ? 'AICS Burial Assistance'
            : 'AICS Medical Assistance';

        // ── Bell notification ──────────────────────────────────────────────────
        try {
            $notifTitle = $programLabel . ' Grant Released';
            $notifBody  = 'Your ' . $programLabel . ' grant has been successfully released. Thank you for availing MSWDO assistance.';
            $notifId = \DB::table('notifications')->insertGetId([
                'user_id'    => $application->user_id,
                'type'       => 'aics',
                'title'      => $notifTitle,
                'body'       => $notifBody,
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            \App\Services\OneSignalService::sendPush($application->user_id, $notifTitle, $notifBody, 'aics', $notifId, ['program_type' => $application->program_type]);
        } catch (\Exception $e) {
            Log::error('markReleased notification insert failed: ' . $e->getMessage());
        }

        // ── Email user ─────────────────────────────────────────────────────────
        if ($user && $user->email) {
            try {
                Mail::to($user->email)->send(new AicsGrantReleasedMail($application, $user));
            } catch (\Exception $e) {
                Log::error('AicsGrantReleasedMail failed: ' . $e->getMessage());
            }
        }

        $msg = $programLabel . ' grant marked as released. Confirmation email sent to ' . ($user?->email ?? 'user') . '.';

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => $msg]);
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

        // Create in-app notification & OneSignal push
        try {
            $label = $application->program_type === 'AICS_Medical' ? 'AICS Medical Assistance' : 'AICS Burial Assistance';
            $notifTitle = 'AICS Requirements Validated';
            $notifBody  = 'Your ' . $label . ' requirements are validated. Please wait for grant release notice.';
            $notifId = \DB::table('notifications')->insertGetId([
                'user_id'    => $application->user_id,
                'type'       => 'aics',
                'title'      => $notifTitle,
                'body'       => $notifBody,
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            \App\Services\OneSignalService::sendPush(
                $application->user_id,
                $notifTitle,
                $notifBody,
                'aics',
                $notifId,
                ['program_type' => $application->program_type]
            );
        } catch (\Exception $e) {
            Log::error('AICS validate notification insert failed: ' . $e->getMessage());
        }

        // ── Create in-app notification ───────────────────────────────────
        try {
            $notifTitle = 'PWD Application Validated';
            $notifBody  = 'Your PWD application requirements have been validated. Your PWD ID is now being processed.';
            $notifId = \DB::table('notifications')->insertGetId([
                'user_id'    => $application->user_id,
                'type'       => 'pwd',
                'title'      => $notifTitle,
                'body'       => $notifBody,
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            \App\Services\OneSignalService::sendPush($application->user_id, $notifTitle, $notifBody, 'pwd', $notifId, ['program_type' => $application->program_type]);
        } catch (\Exception $e) {
            Log::error('PWD validate notification insert failed: ' . $e->getMessage());
        }

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

        // Create in-app notification & OneSignal push
        try {
            $label = $application->program_type === 'AICS_Medical' ? 'AICS Medical Assistance' : 'AICS Burial Assistance';
            $notifTitle = 'AICS Requirements Validated';
            $notifBody  = 'Your ' . $label . ' requirements are validated. Please wait for grant release notice.';
            $notifId = \DB::table('notifications')->insertGetId([
                'user_id'    => $application->user_id,
                'type'       => 'aics',
                'title'      => $notifTitle,
                'body'       => $notifBody,
                'is_read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            \App\Services\OneSignalService::sendPush(
                $application->user_id,
                $notifTitle,
                $notifBody,
                'aics',
                $notifId,
                ['program_type' => $application->program_type]
            );
        } catch (\Exception $e) {
            Log::error('AICS validate notification insert failed: ' . $e->getMessage());
        }

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

        $soloParentCategory = null;
        $soloParentCompletion = null;
        if ($application->program_type === 'Solo_Parent') {
            $categories = \App\Services\SoloParentCategoryService::getCategories();
            $soloParentCategory = $categories[$application->category_code] ?? null;
            $soloParentCompletion = \App\Services\SoloParentCategoryService::evaluateCompletion($application, $fileMonitoring);
            if ($application->category_code) {
                $allApproved = ($soloParentCompletion['is_complete'] ?? false);
            }
        }

        return view('admin.view-requirement', compact(
            'fileMonitoring',
            'application',
            'hasDocuments',
            'allApproved',
            'soloParentCategory',
            'soloParentCompletion'
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
            Storage::disk('public')->path($filePath),
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

        $isSoloParent = ($fileMonitoring->application && $fileMonitoring->application->program_type === 'Solo_Parent');

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
        } elseif ($isSoloParent && $fileMonitoring->application->category_code) {
            $completion = \App\Services\SoloParentCategoryService::evaluateCompletion($fileMonitoring->application, $fileMonitoring);
            if ($completion['is_complete']) {
                $fileMonitoring->overall_status = 'approved';
                $fileMonitoring->application->update([
                    'status' => 'approved',
                    'completed_at' => now(),
                    'id_status' => 'processing',
                ]);
            } else {
                $fileMonitoring->overall_status = 'in_review';
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

        // ── Create in-app notification for the user ──────────────────────
        if ($fileMonitoring->application && in_array($request->status, ['approved', 'rejected'], true)) {
            $programType = $fileMonitoring->application->program_type ?? 'application';
            $notifType = match (true) {
                str_contains($programType, 'PWD')         => 'pwd',
                str_contains($programType, 'Solo_Parent') => 'solo_parent',
                str_contains($programType, 'AICS')        => 'aics',
                default                                   => 'application',
            };
            $programLabel = str_replace('_', ' ', $programType);
            $docName = $fileUpload->requirement_name ?? 'document';

            if ($request->status === 'approved') {
                $notifTitle = 'Document Approved';
                $notifBody  = 'Your "' . $docName . '" for ' . $programLabel . ' has been approved.';
            } else {
                $notifTitle = 'Document Rejected';
                $remarks    = $request->admin_remarks ? ' Reason: ' . $request->admin_remarks : '';
                $notifBody  = 'Your "' . $docName . '" for ' . $programLabel . ' was rejected.' . $remarks;
            }

            // Add overall status context
            $newOverall = $fileMonitoring->overall_status;
            if ($newOverall === 'approved') {
                $notifBody .= ' All requirements are now approved!';
            } elseif ($newOverall === 'rejected') {
                $notifBody .= ' Please re-upload the rejected document(s).';
            }

            try {
                $notifData = json_encode([
                    'requirement_name' => $docName,
                    'status'           => $request->status,
                    'admin_remarks'    => $request->admin_remarks,
                    'program_type'     => $programType,
                    'application_id'   => $fileMonitoring->application_id,
                ]);
                $notifId = \DB::table('notifications')->insertGetId([
                    'user_id'    => $fileMonitoring->application->user_id,
                    'type'       => $notifType,
                    'title'      => $notifTitle,
                    'body'       => $notifBody,
                    'data'       => $notifData,
                    'is_read'    => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                \App\Services\OneSignalService::sendPush($fileMonitoring->application->user_id, $notifTitle, $notifBody, $notifType, $notifId, [
                    'program_type'     => $programType,
                    'requirement_name' => $docName,
                    'status'           => $request->status,
                    'application_id'   => $fileMonitoring->application_id,
                ]);
            } catch (\Exception $e) {
                Log::error('File status notification insert failed: ' . $e->getMessage());
            }
        }

        // ── Email user when requirements are reviewed (all program types) ────────
        if ($fileMonitoring->application && $fileMonitoring->application->user) {
            $fileMonitoring->load('fileUploads', 'application.user');
            $newOverall = $fileMonitoring->overall_status;

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

        $scroll = (int) $request->input('scroll', 0);

        // Return JSON for AJAX requests (no page reload)
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'File status updated successfully!',
                'new_status' => $request->status,
                'admin_remarks' => $request->admin_remarks,
                'overall_status' => $fileMonitoring->overall_status,
                'application_status' => $fileMonitoring->application->status ?? null,
            ]);
        }

        $backUrl = url()->previous();
        $separator = str_contains($backUrl, '?') ? '&' : '?';
        return redirect($backUrl . ($scroll > 0 ? $separator . 'scroll=' . $scroll : ''))
            ->with('success', 'File status updated successfully!');
    }

    /**
     * Detailed Analysis Page — delegates to focused private helpers
     * to keep the public method small and IDE-friendly.
     */
    public function detailedAnalysis(Request $request)
    {
        $user = Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->first();

        $muniYear = (int) ($municipality->year ?? date('Y'));

        $yearsFromBarangays = Barangay::where('municipality', $user->municipality)
            ->whereNotNull('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($y) => (int) $y)
            ->unique()
            ->values()
            ->toArray();

        $yearsFromSummary = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->whereNotNull('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn ($y) => (int) $y)
            ->toArray();

        // Also include years with application records (e.g. 2026) so they always appear as filter chips
        $yearsFromApplications = Application::where('municipality', $user->municipality)
            ->whereNotNull('application_date')
            ->selectRaw('DISTINCT YEAR(application_date) as yr')
            ->pluck('yr')
            ->map(fn ($y) => (int) $y)
            ->toArray();

        $availableAnalysisYears = array_values(array_unique(array_merge($yearsFromBarangays, $yearsFromSummary, $yearsFromApplications)));
        rsort($availableAnalysisYears, SORT_NUMERIC);

        if ($request->filled('year') && $availableAnalysisYears !== []) {
            $reqYear = (int) $request->year;
            if (! in_array($reqYear, $availableAnalysisYears, true)) {
                return redirect()->route('admin.detailed-analysis', ['year' => $availableAnalysisYears[0]]);
            }
            $analysisYear = $reqYear;
        } elseif ($availableAnalysisYears !== []) {
            $analysisYear = in_array($muniYear, $availableAnalysisYears, true)
                ? $muniYear
                : $availableAnalysisYears[0];
        } else {
            $analysisYear = $muniYear;
        }

        $applications = $this->loadApplications($user->municipality);
        $applications = $applications->filter(function ($app) use ($analysisYear) {
            if (! $app->application_date) {
                return false;
            }

            return (int) $app->application_date->year === (int) $analysisYear;
        });

        [
            $totalApplications,
            $pendingApplications,
            $approvedApplications,
            $rejectedApplications,
            $applicationsByProgram
        ] = $this->buildApplicationStats($applications);

        $programShareOverview = $this->buildProgramOverview($user->municipality, $analysisYear);

        $summary = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->where('year', $analysisYear)
            ->first();

        // Prefer yearly summary (kept in sync when barangay data is saved) so Analysis reflects Data Management
        if ($summary && ($summary->total_population > 0 || $summary->total_households > 0)) {
            $totalPop = (int) $summary->total_population;
            $totalHouseholds = (int) $summary->total_households;
            $genderData = [
                'male' => (int) $summary->male_population,
                'female' => (int) $summary->female_population,
            ];
            $ageGroupData = [
                '0-19' => (int) $summary->population_0_19,
                '20-59' => (int) $summary->population_20_59,
                '60+' => (int) $summary->population_60_100,
            ];
            if (($genderData['male'] + $genderData['female']) === 0 && $totalPop > 0) {
                $genderData = [
                    'male' => $municipality->male_population,
                    'female' => $municipality->female_population,
                ];
            }
            if (($ageGroupData['0-19'] + $ageGroupData['20-59'] + $ageGroupData['60+']) === 0) {
                $ageGroupData = [
                    '0-19' => $municipality->population_0_19,
                    '20-59' => $municipality->population_20_59,
                    '60+' => $municipality->population_60_100,
                ];
            }
        } else {
            $totalPop = $municipality->male_population + $municipality->female_population;
            $totalHouseholds = $municipality->total_households;
            $genderData = [
                'male' => $municipality->male_population,
                'female' => $municipality->female_population,
            ];
            $ageGroupData = [
                '0-19' => $municipality->population_0_19,
                '20-59' => $municipality->population_20_59,
                '60+' => $municipality->population_60_100,
            ];
        }

        $programBeneficiaries = $programShareOverview->toArray();

        $barangayRowsForAnalysis = Barangay::where('municipality', $user->municipality)
            ->where('year', $analysisYear)
            ->orderBy('name')
            ->get();

        // Multi-year population & household trend (for Trend panel)
        $yearlyTrendData = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->whereNotNull('year')
            ->orderBy('year')
            ->get(['year', 'total_population', 'total_households', 'male_population', 'female_population'])
            ->keyBy('year');

        // Unique applicants (distinct user_id) in the selected year
        $uniqueApplicants = Application::where('municipality', $user->municipality)
            ->whereNotNull('application_date')
            ->whereYear('application_date', $analysisYear)
            ->distinct('user_id')
            ->count('user_id');

        // Total beneficiaries across all programs for the year
        $totalBeneficiaries = (int) collect($programBeneficiaries)->sum();

        extract($this->adminNotifData($user));

        return view('admin.detailed-analysis', compact(
            'municipality',
            'analysisYear',
            'availableAnalysisYears',
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
            'programBeneficiaries',
            'barangayRowsForAnalysis',
            'yearlyTrendData',
            'uniqueApplicants',
            'totalBeneficiaries'
        ));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ANALYSIS EXPORTS  (CSV + Excel) — scoped to authenticated admin municipality
    // ─────────────────────────────────────────────────────────────────────────

    /** Shared data-gathering helper for both export methods. */
    private function buildAnalysisData(Request $request): array
    {
        $user         = \Illuminate\Support\Facades\Auth::user();
        $municipality = Municipality::where('name', $user->municipality)->first();
        $muniYear     = (int) ($municipality->year ?? date('Y'));

        // Resolve selected year (same logic as detailedAnalysis)
        $yearsFromBarangays = Barangay::where('municipality', $user->municipality)
            ->whereNotNull('year')->distinct()->pluck('year')->map(fn($y)=>(int)$y)->toArray();
        $yearsFromSummary   = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->whereNotNull('year')->distinct()->pluck('year')->map(fn($y)=>(int)$y)->toArray();
        $yearsFromApps      = Application::where('municipality', $user->municipality)
            ->whereNotNull('application_date')->selectRaw('DISTINCT YEAR(application_date) as yr')
            ->pluck('yr')->map(fn($y)=>(int)$y)->toArray();
        $available = array_values(array_unique(array_merge($yearsFromBarangays, $yearsFromSummary, $yearsFromApps)));
        rsort($available, SORT_NUMERIC);

        $analysisYear = $muniYear;
        if ($request->filled('year')) {
            $reqYear = (int) $request->year;
            $analysisYear = in_array($reqYear, $available, true) ? $reqYear : ($available[0] ?? $muniYear);
        } elseif ($available) {
            $analysisYear = in_array($muniYear, $available, true) ? $muniYear : $available[0];
        }

        // Applications for the year — municipality scoped
        $apps = $this->loadApplications($user->municipality)->filter(function ($app) use ($analysisYear) {
            return $app->application_date && (int) $app->application_date->year === (int) $analysisYear;
        });

        [$totalApps, $pendingApps, $approvedApps, $rejectedApps, $appsByProgram] = $this->buildApplicationStats($apps);

        $programOverview = $this->buildProgramOverview($user->municipality, $analysisYear);

        // Population / household summary
        $summary = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->where('year', $analysisYear)->first();

        if ($summary && ($summary->total_population > 0 || $summary->total_households > 0)) {
            $totalPop  = (int) $summary->total_population;
            $totalHH   = (int) $summary->total_households;
            $malePop   = (int) $summary->male_population;
            $femalePop = (int) $summary->female_population;
        } else {
            $totalPop  = $municipality->male_population + $municipality->female_population;
            $totalHH   = $municipality->total_households;
            $malePop   = $municipality->male_population;
            $femalePop = $municipality->female_population;
        }

        // Trend data
        $trend = MunicipalityYearlySummary::where('municipality', $user->municipality)
            ->whereNotNull('year')->orderBy('year')
            ->get(['year','total_population','total_households','male_population','female_population']);

        // Barangay rows
        $barangays = Barangay::where('municipality', $user->municipality)
            ->where('year', $analysisYear)->orderBy('name')->get();

        $uniqueApplicants   = Application::where('municipality', $user->municipality)
            ->whereNotNull('application_date')->whereYear('application_date', $analysisYear)
            ->distinct('user_id')->count('user_id');
        $totalBeneficiaries = (int) $programOverview->sum();
        $approvalRate       = $totalApps > 0 ? round($approvedApps / $totalApps * 100, 1) : null;

        return compact(
            'municipality', 'analysisYear', 'totalApps', 'pendingApps', 'approvedApps', 'rejectedApps',
            'appsByProgram', 'programOverview', 'totalPop', 'totalHH', 'malePop', 'femalePop',
            'trend', 'barangays', 'uniqueApplicants', 'totalBeneficiaries', 'approvalRate'
        );
    }

    /** CSV export — well-organized sections with UTF-8 BOM, visual ASCII chart representations, and clean alignment. */
    public function exportAnalysisCsv(Request $request)
    {
        $d  = $this->buildAnalysisData($request);
        extract($d);

        $bar = function($val, $max, $len = 16) {
            if ($max <= 0 || $val <= 0) return '[' . str_repeat(' ', $len) . ']';
            $filled = (int) round(($val / $max) * $len);
            $filled = max(1, min($len, $filled));
            return '[' . str_repeat('=', $filled) . str_repeat(' ', $len - $filled) . ']';
        };

        $ts  = fn($n) => ($n !== null && $n !== '') ? number_format((float)$n, 0, '.', ',') : 'N/A';
        $pct = fn($v, $t) => ($t > 0 && $v !== null) ? number_format(($v / $t) * 100, 1) . '%' : '0.0%';

        $rows = [];
        $rows[] = ["MSWDO DETAILED ANALYSIS REPORT"];
        $rows[] = ["Municipality", $municipality->name];
        $rows[] = ["Analysis Year", $analysisYear];
        $rows[] = ["Generated Date", now()->format('Y-m-d H:i:s')];
        $rows[] = [""];

        // SECTION 1: KEY PERFORMANCE INDICATORS
        $rows[] = ["1. KEY PERFORMANCE INDICATORS (KPIs)"];
        $rows[] = ["Metric", "Value", "Notes"];
        $rows[] = ["Total Population", $totalPop > 0 ? $ts($totalPop) : "N/A", $totalPop > 0 ? "Official record for $analysisYear" : "No population record recorded for $analysisYear"];
        $rows[] = ["Total Households", $totalHH > 0 ? $ts($totalHH) : "N/A", $totalHH > 0 ? "Registered households" : "No household record recorded for $analysisYear"];
        $rows[] = ["Total Applications", $ts($totalApps), "Total submitted cases in $analysisYear"];
        $rows[] = ["Unique Applicants", $ts($uniqueApplicants), "Distinct applicant individuals"];
        $rows[] = ["Approved Applications", $ts($approvedApps), ($approvalRate !== null ? $approvalRate . "% approval rate" : "N/A")];
        $rows[] = ["Pending Applications", $ts($pendingApps), ($totalApps > 0 ? $pct($pendingApps, $totalApps) . " of total" : "N/A")];
        $rows[] = ["Rejected Applications", $ts($rejectedApps), ($totalApps > 0 ? $pct($rejectedApps, $totalApps) . " of total" : "N/A")];
        $rows[] = ["Total Program Beneficiaries", $totalBeneficiaries > 0 ? $ts($totalBeneficiaries) : "N/A", "Across all welfare programs"];
        $rows[] = [""];

        // SECTION 2: APPLICATION STATUS (WITH VISUAL CHART)
        $rows[] = ["2. APPLICATION STATUS DISTRIBUTION (VISUAL CHART)"];
        $rows[] = ["Status", "Count", "Percentage", "Visual Graph"];
        $maxStatus = max(1, $pendingApps, $approvedApps, $rejectedApps);
        $rows[] = ["Approved", $ts($approvedApps), $pct($approvedApps, $totalApps), $bar($approvedApps, $maxStatus)];
        $rows[] = ["Pending",  $ts($pendingApps),  $pct($pendingApps,  $totalApps), $bar($pendingApps,  $maxStatus)];
        $rows[] = ["Rejected", $ts($rejectedApps), $pct($rejectedApps, $totalApps), $bar($rejectedApps, $maxStatus)];
        $rows[] = ["Total",    $ts($totalApps),    "100.0%", ""];
        $rows[] = [""];

        // SECTION 3: APPLICATIONS BY PROGRAM (WITH VISUAL CHART)
        $rows[] = ["3. APPLICATIONS BY WELFARE PROGRAM (VISUAL CHART)"];
        if ($appsByProgram->isNotEmpty()) {
            $rows[] = ["Program", "Total Applications", "Pending", "Approved", "Rejected", "Visual Share"];
            $maxProgApps = 1;
            foreach ($appsByProgram as $p => $data) {
                $tot = ($data['pending'] ?? 0) + ($data['approved'] ?? 0) + ($data['rejected'] ?? 0);
                if ($tot > $maxProgApps) $maxProgApps = $tot;
            }
            foreach ($appsByProgram as $prog => $data) {
                $tot = ($data['pending'] ?? 0) + ($data['approved'] ?? 0) + ($data['rejected'] ?? 0);
                $rows[] = [
                    str_replace('_', ' ', $prog),
                    $ts($tot),
                    $ts($data['pending'] ?? 0),
                    $ts($data['approved'] ?? 0),
                    $ts($data['rejected'] ?? 0),
                    $bar($tot, $maxProgApps)
                ];
            }
            $rows[] = ["Total", $ts($totalApps), $ts($pendingApps), $ts($approvedApps), $ts($rejectedApps), ""];
        } else {
            $rows[] = ["Program", "Applications", "Note"];
            $rows[] = ["N/A", "0", "No program application records for $analysisYear"];
        }
        $rows[] = [""];

        // SECTION 4: SOCIAL WELFARE PROGRAMS & BENEFICIARIES (WITH VISUAL CHART)
        $rows[] = ["4. SOCIAL WELFARE PROGRAMS BENEFICIARIES (VISUAL CHART)"];
        if ($programOverview->isNotEmpty()) {
            $rows[] = ["Program", "Beneficiaries", "Share of Total", "Visual Graph"];
            $sumBenef = $programOverview->sum() ?: 1;
            $maxBenef = $programOverview->max() ?: 1;
            foreach ($programOverview as $prog => $count) {
                $rows[] = [
                    str_replace('_', ' ', $prog),
                    $ts($count),
                    $pct($count, $sumBenef),
                    $bar($count, $maxBenef)
                ];
            }
            $rows[] = ["Total Beneficiaries", $ts($sumBenef), "100.0%", ""];
        } else {
            $rows[] = ["Program", "Beneficiaries", "Note"];
            $rows[] = ["N/A", "0", "No welfare beneficiary records for $analysisYear"];
        }
        $rows[] = [""];

        // SECTION 5: GENDER DISTRIBUTION (WITH VISUAL CHART)
        $rows[] = ["5. GENDER DISTRIBUTION (VISUAL CHART)"];
        $rows[] = ["Gender", "Population", "Percentage", "Visual Graph"];
        $totGender = ($malePop ?? 0) + ($femalePop ?? 0);
        if ($totGender > 0) {
            $maxG = max(1, $malePop, $femalePop);
            $rows[] = ["Male",   $ts($malePop),   $pct($malePop, $totGender),   $bar($malePop, $maxG)];
            $rows[] = ["Female", $ts($femalePop), $pct($femalePop, $totGender), $bar($femalePop, $maxG)];
            $rows[] = ["Total",  $ts($totGender), "100.0%", ""];
        } else {
            $rows[] = ["Male",   "N/A", "-", ""];
            $rows[] = ["Female", "N/A", "-", ""];
            $rows[] = ["Note", "No gender breakdown recorded for $analysisYear", "", ""];
        }
        $rows[] = [""];

        // SECTION 6: POPULATION & HOUSEHOLD TREND
        $rows[] = ["6. HISTORICAL POPULATION & HOUSEHOLD TREND"];
        $rows[] = ["Year", "Total Population", "Households", "Male Population", "Female Population"];
        if ($trend->isNotEmpty()) {
            foreach ($trend as $row) {
                $rows[] = [
                    $row->year,
                    $row->total_population > 0 ? $ts($row->total_population) : "N/A",
                    $row->total_households  > 0 ? $ts($row->total_households)  : "N/A",
                    $row->male_population   > 0 ? $ts($row->male_population)   : "N/A",
                    $row->female_population > 0 ? $ts($row->female_population) : "N/A",
                ];
            }
        } else {
            $rows[] = ["N/A", "No historical trend records found", "", "", ""];
        }
        $rows[] = [""];

        // SECTION 7: BARANGAY BREAKDOWN
        $rows[] = ["7. BARANGAY DEMOGRAPHICS & WELFARE BREAKDOWN"];
        if ($barangays->isNotEmpty()) {
            $rows[] = ["Barangay", "Population", "Male", "Female", "PWD", "AICS", "Solo Parent", "4Ps", "Senior"];
            foreach ($barangays as $b) {
                $pop = ($b->male_population ?? 0) + ($b->female_population ?? 0);
                $rows[] = [
                    $b->name,
                    $pop > 0 ? $ts($pop) : "N/A",
                    ($b->male_population   ?? 0) > 0 ? $ts($b->male_population)   : "N/A",
                    ($b->female_population ?? 0) > 0 ? $ts($b->female_population) : "N/A",
                    ($b->pwd_count         ?? 0) > 0 ? $ts($b->pwd_count)         : "N/A",
                    ($b->aics_count        ?? 0) > 0 ? $ts($b->aics_count)        : "N/A",
                    ($b->solo_parent_count ?? 0) > 0 ? $ts($b->solo_parent_count) : "N/A",
                    ($b->four_ps_count     ?? 0) > 0 ? $ts($b->four_ps_count)     : "N/A",
                    ($b->senior_count      ?? 0) > 0 ? $ts($b->senior_count)      : "N/A",
                ];
            }
        } else {
            $rows[] = ["Barangay", "Population", "Status"];
            $rows[] = ["N/A", "0", "No barangay records recorded for $analysisYear"];
        }
        $rows[] = [""];
        $rows[] = ["Note: 'N/A' indicates no record exists for that specific period or barangay."];

        // Build CSV output with UTF-8 BOM
        $output = "\xEF\xBB\xBF";
        foreach ($rows as $row) {
            $output .= implode(',', array_map(function ($cell) {
                $cell = (string) $cell;
                if (str_contains($cell, ',') || str_contains($cell, '"') || str_contains($cell, "\n")) {
                    return '"' . str_replace('"', '""', $cell) . '"';
                }
                return $cell;
            }, $row)) . "\r\n";
        }

        $filename = 'analysis-' . $municipality->name . '-' . $analysisYear . '.csv';
        return response($output, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /** Excel workbook export — professional multi-sheet report with non-overlapping native charts. */
    public function exportAnalysisExcel(Request $request)
    {
        $d = $this->buildAnalysisData($request);
        extract($d);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->getProperties()
            ->setTitle("MSWDO Analysis {$municipality->name} {$analysisYear}")
            ->setCreator('MSWDO System')
            ->setSubject("Detailed Analysis Report");

        // Palette & formatting constants
        $navy   = '1E2A78';
        $gold   = 'F5B82E';
        $bg     = 'F4F6FB';
        $white  = 'FFFFFF';

        $headerFill = function(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $ws, $range) use ($navy, $white) {
            $ws->getStyle($range)->applyFromArray([
                'font'      => ['bold' => true, 'color' => ['rgb' => $white], 'size' => 11],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => $navy]],
                'alignment' => ['vertical' => 'center'],
            ]);
        };

        // ══════════════════════════════════════════════════════════════════════
        // SHEET 1: Summary (Executive Dashboard with Clean Side-by-Side Layout)
        // ══════════════════════════════════════════════════════════════════════
        $ws1 = $spreadsheet->getActiveSheet()->setTitle('Summary');

        // Title banner
        $ws1->mergeCells('A1:C1');
        $ws1->setCellValue('A1', "MSWDO Detailed Analysis Report");
        $ws1->getStyle('A1')->applyFromArray(['font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => $navy]]]);
        $ws1->setCellValue('A2', "Municipality"); $ws1->setCellValue('B2', $municipality->name);
        $ws1->setCellValue('A3', "Analysis Year"); $ws1->setCellValue('B3', $analysisYear);
        $ws1->setCellValue('A4', "Generated Date"); $ws1->setCellValue('B4', now()->format('Y-m-d H:i:s'));
        $ws1->getStyle('A2:A4')->getFont()->setBold(true);

        // KPI Table (A6:C13)
        $ws1->setCellValue('A6', 'KEY PERFORMANCE INDICATORS');
        $ws1->getStyle('A6')->applyFromArray(['font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => $navy]]]);
        $ws1->mergeCells('A6:C6');

        $ws1->fromArray([['Metric', 'Value', 'Notes']], null, 'A7');
        $headerFill($ws1, 'A7:C7');

        $kpiData = [
            ['Total Population',   $totalPop > 0 ? $totalPop : null, "Official count for $analysisYear"],
            ['Total Households',   $totalHH  > 0 ? $totalHH  : null, "Registered households"],
            ['Applications',       $totalApps,                       "Submitted in $analysisYear"],
            ['Unique Applicants',  $uniqueApplicants,                "Distinct applicants"],
            ['Approved Cases',     $approvedApps,                    $approvalRate !== null ? $approvalRate . '% approval rate' : ''],
            ['Beneficiaries',      $totalBeneficiaries > 0 ? $totalBeneficiaries : null, "Across all programs"],
        ];

        $r = 8;
        foreach ($kpiData as $i => $row) {
            $ws1->setCellValue("A$r", $row[0]);
            if ($row[1] !== null) {
                $ws1->setCellValueExplicit("B$r", $row[1], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                $ws1->getStyle("B$r")->getNumberFormat()->setFormatCode('#,##0');
            } else {
                $ws1->setCellValue("B$r", 'N/A');
                $ws1->getStyle("B$r")->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF7A84A8'));
            }
            $ws1->setCellValue("C$r", $row[2]);
            if ($i % 2 === 0) {
                $ws1->getStyle("A$r:C$r")->getFill()->setFillType('solid')->getStartColor()->setRGB($bg);
            }
            $r++;
        }

        // Summary Application Status table (A15:C19)
        $ws1->setCellValue('A15', 'APPLICATION STATUS OVERVIEW');
        $ws1->getStyle('A15')->applyFromArray(['font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => $navy]]]);
        $ws1->fromArray([['Status', 'Count', 'Share']], null, 'A16');
        $headerFill($ws1, 'A16:C16');
        $ws1->setCellValue('A17', 'Approved'); $ws1->setCellValueExplicit('B17', $approvedApps, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC); $ws1->setCellValueExplicit('C17', $totalApps > 0 ? round($approvedApps / $totalApps * 100, 1) : 0, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
        $ws1->setCellValue('A18', 'Pending');  $ws1->setCellValueExplicit('B18', $pendingApps,  \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC); $ws1->setCellValueExplicit('C18', $totalApps > 0 ? round($pendingApps  / $totalApps * 100, 1) : 0, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
        $ws1->setCellValue('A19', 'Rejected'); $ws1->setCellValueExplicit('B19', $rejectedApps, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC); $ws1->setCellValueExplicit('C19', $totalApps > 0 ? round($rejectedApps / $totalApps * 100, 1) : 0, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
        $ws1->getStyle('B17:B19')->getNumberFormat()->setFormatCode('#,##0');
        $ws1->getStyle('C17:C19')->getNumberFormat()->setFormatCode('0.0"%"');

        // Chart 1 on Summary: Application Status Pie Chart (Positioned at E6:L18)
        $statusPieChart = new \PhpOffice\PhpSpreadsheet\Chart\Chart(
            'summary_status_chart',
            new \PhpOffice\PhpSpreadsheet\Chart\Title("Application Status · $analysisYear"),
            new \PhpOffice\PhpSpreadsheet\Chart\Legend(\PhpOffice\PhpSpreadsheet\Chart\Legend::POSITION_BOTTOM, null, false),
            new \PhpOffice\PhpSpreadsheet\Chart\PlotArea(null, [
                new \PhpOffice\PhpSpreadsheet\Chart\DataSeries(
                    \PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_PIECHART,
                    null,
                    range(0, 2),
                    [new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', null, null, 1, ['Status'])],
                    [new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', 'Summary!$A$17:$A$19', null, 3)],
                    [new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', 'Summary!$B$17:$B$19', null, 3)]
                )
            ])
        );
        $statusPieChart->setTopLeftPosition('E6');
        $statusPieChart->setBottomRightPosition('L18');
        $ws1->addChart($statusPieChart);

        // Summary Gender Table (A21:C24) and Gender Chart (E20:L32)
        $totG = ($malePop ?? 0) + ($femalePop ?? 0);
        if ($totG > 0) {
            $ws1->setCellValue('A21', 'GENDER DISTRIBUTION');
            $ws1->getStyle('A21')->applyFromArray(['font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => $navy]]]);
            $ws1->fromArray([['Gender', 'Population', 'Share']], null, 'A22');
            $headerFill($ws1, 'A22:C22');
            $ws1->setCellValue('A23', 'Male');   $ws1->setCellValueExplicit('B23', $malePop, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);   $ws1->setCellValueExplicit('C23', round($malePop / $totG * 100, 1), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $ws1->setCellValue('A24', 'Female'); $ws1->setCellValueExplicit('B24', $femalePop, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC); $ws1->setCellValueExplicit('C24', round($femalePop / $totG * 100, 1), \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $ws1->getStyle('B23:B24')->getNumberFormat()->setFormatCode('#,##0');
            $ws1->getStyle('C23:C24')->getNumberFormat()->setFormatCode('0.0"%"');

            $genderSummaryChart = new \PhpOffice\PhpSpreadsheet\Chart\Chart(
                'summary_gender_chart',
                new \PhpOffice\PhpSpreadsheet\Chart\Title("Gender Distribution · $analysisYear"),
                new \PhpOffice\PhpSpreadsheet\Chart\Legend(\PhpOffice\PhpSpreadsheet\Chart\Legend::POSITION_BOTTOM, null, false),
                new \PhpOffice\PhpSpreadsheet\Chart\PlotArea(null, [
                    new \PhpOffice\PhpSpreadsheet\Chart\DataSeries(
                        \PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_PIECHART,
                        null,
                        range(0, 1),
                        [new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', null, null, 1, ['Gender'])],
                        [new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', 'Summary!$A$23:$A$24', null, 2)],
                        [new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', 'Summary!$B$23:$B$24', null, 2)]
                    )
                ])
            );
            $genderSummaryChart->setTopLeftPosition('E20');
            $genderSummaryChart->setBottomRightPosition('L32');
            $ws1->addChart($genderSummaryChart);
        }

        $ws1->getColumnDimension('A')->setWidth(26);
        $ws1->getColumnDimension('B')->setWidth(18);
        $ws1->getColumnDimension('C')->setWidth(30);
        $ws1->getColumnDimension('D')->setWidth(4); // spacer column
        foreach (['E','F','G','H','I','J','K','L'] as $col) {
            $ws1->getColumnDimension($col)->setWidth(11);
        }

        // ══════════════════════════════════════════════════════════════════════
        // SHEET 2: Applications (Clean, Vertically Stacked Non-Overlapping Charts)
        // ══════════════════════════════════════════════════════════════════════
        $ws2 = $spreadsheet->createSheet()->setTitle('Applications');

        $ws2->mergeCells('A1:E1');
        $ws2->setCellValue('A1', "Application Analysis · $analysisYear · {$municipality->name}");
        $ws2->getStyle('A1')->applyFromArray(['font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => $navy]]]);

        // Status table (A3:C8)
        $ws2->setCellValue('A3', 'APPLICATION STATUS OVERVIEW');
        $ws2->getStyle('A3')->applyFromArray(['font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => $navy]]]);
        $ws2->fromArray([['Status', 'Count', 'Percentage']], null, 'A4');
        $headerFill($ws2, 'A4:C4');

        $statusData = [
            ['Approved', $approvedApps, $totalApps > 0 ? round($approvedApps / $totalApps * 100, 1) : 0],
            ['Pending',  $pendingApps,  $totalApps > 0 ? round($pendingApps  / $totalApps * 100, 1) : 0],
            ['Rejected', $rejectedApps, $totalApps > 0 ? round($rejectedApps / $totalApps * 100, 1) : 0],
        ];
        foreach ($statusData as $i => $row) {
            $r = $i + 5;
            $ws2->setCellValue("A$r", $row[0]);
            $ws2->setCellValueExplicit("B$r", $row[1], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $ws2->getStyle("B$r")->getNumberFormat()->setFormatCode('#,##0');
            $ws2->setCellValueExplicit("C$r", $row[2], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
            $ws2->getStyle("C$r")->getNumberFormat()->setFormatCode('0.0"%"');
            if ($i % 2 === 0) $ws2->getStyle("A$r:C$r")->getFill()->setFillType('solid')->getStartColor()->setRGB($bg);
        }
        $ws2->fromArray([['Total', $totalApps, '100.0%']], null, 'A8');
        $ws2->getStyle('A8:C8')->applyFromArray(['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => $bg]]]);
        $ws2->getStyle('B8')->getNumberFormat()->setFormatCode('#,##0');

        // Chart 1: Application Status Pie Chart (Positioned at G4:N17)
        $statusChart2 = new \PhpOffice\PhpSpreadsheet\Chart\Chart(
            'app_status_chart_sheet2',
            new \PhpOffice\PhpSpreadsheet\Chart\Title("Application Status · $analysisYear"),
            new \PhpOffice\PhpSpreadsheet\Chart\Legend(\PhpOffice\PhpSpreadsheet\Chart\Legend::POSITION_BOTTOM, null, false),
            new \PhpOffice\PhpSpreadsheet\Chart\PlotArea(null, [
                new \PhpOffice\PhpSpreadsheet\Chart\DataSeries(
                    \PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_PIECHART,
                    null,
                    range(0, 2),
                    [new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', null, null, 1, ['Status'])],
                    [new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', 'Applications!$A$5:$A$7', null, 3)],
                    [new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', 'Applications!$B$5:$B$7', null, 3)]
                )
            ])
        );
        $statusChart2->setTopLeftPosition('G4');
        $statusChart2->setBottomRightPosition('N17');
        $ws2->addChart($statusChart2);

        // By-program table (A10:E...)
        if ($appsByProgram->isNotEmpty()) {
            $ws2->setCellValue('A10', 'APPLICATIONS BY WELFARE PROGRAM');
            $ws2->getStyle('A10')->applyFromArray(['font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => $navy]]]);
            $ws2->fromArray([['Program', 'Applications', 'Pending', 'Approved', 'Rejected']], null, 'A11');
            $headerFill($ws2, 'A11:E11');

            $progStartRow = 12;
            $r = $progStartRow;
            foreach ($appsByProgram as $prog => $data) {
                $rowTotal = ($data['pending'] ?? 0) + ($data['approved'] ?? 0) + ($data['rejected'] ?? 0);
                $ws2->fromArray([[
                    str_replace('_', ' ', $prog), $rowTotal,
                    $data['pending'] ?? 0, $data['approved'] ?? 0, $data['rejected'] ?? 0,
                ]], null, "A$r");
                $ws2->getStyle("B$r:E$r")->getNumberFormat()->setFormatCode('#,##0');
                if (($r - $progStartRow) % 2 === 0) $ws2->getStyle("A$r:E$r")->getFill()->setFillType('solid')->getStartColor()->setRGB($bg);
                $r++;
            }
            $ws2->fromArray([['Total', $totalApps, $pendingApps, $approvedApps, $rejectedApps]], null, "A$r");
            $ws2->getStyle("A$r:E$r")->applyFromArray(['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => $bg]]]);
            $ws2->getStyle("B$r:E$r")->getNumberFormat()->setFormatCode('#,##0');

            // Chart 2: Applications by Program Clustered Column Chart (Positioned at G19:N32 - strictly BELOW Chart 1!)
            $progEndRow = $r - 1;
            $progChart = new \PhpOffice\PhpSpreadsheet\Chart\Chart(
                'prog_chart',
                new \PhpOffice\PhpSpreadsheet\Chart\Title("Applications by Program · $analysisYear"),
                new \PhpOffice\PhpSpreadsheet\Chart\Legend(\PhpOffice\PhpSpreadsheet\Chart\Legend::POSITION_BOTTOM, null, false),
                new \PhpOffice\PhpSpreadsheet\Chart\PlotArea(null, [
                    new \PhpOffice\PhpSpreadsheet\Chart\DataSeries(
                        \PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_BARCHART,
                        \PhpOffice\PhpSpreadsheet\Chart\DataSeries::GROUPING_CLUSTERED,
                        range(0, 3),
                        [
                            new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', null, null, 1, ['Total Applications']),
                            new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', null, null, 1, ['Pending']),
                            new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', null, null, 1, ['Approved']),
                            new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', null, null, 1, ['Rejected']),
                        ],
                        [new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', "Applications!\$A\$$progStartRow:\$A\$$progEndRow", null, $appsByProgram->count())],
                        [
                            new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', "Applications!\$B\$$progStartRow:\$B\$$progEndRow", null, $appsByProgram->count()),
                            new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', "Applications!\$C\$$progStartRow:\$C\$$progEndRow", null, $appsByProgram->count()),
                            new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', "Applications!\$D\$$progStartRow:\$D\$$progEndRow", null, $appsByProgram->count()),
                            new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', "Applications!\$E\$$progStartRow:\$E\$$progEndRow", null, $appsByProgram->count()),
                        ]
                    )
                ])
            );
            $progChart->setTopLeftPosition('G19');
            $progChart->setBottomRightPosition('N32');
            $ws2->addChart($progChart);
        }

        $ws2->getColumnDimension('A')->setWidth(26);
        $ws2->getColumnDimension('B')->setWidth(16);
        $ws2->getColumnDimension('C')->setWidth(14);
        $ws2->getColumnDimension('D')->setWidth(14);
        $ws2->getColumnDimension('E')->setWidth(14);
        $ws2->getColumnDimension('F')->setWidth(4); // spacer column
        foreach (['G','H','I','J','K','L','M','N'] as $col) {
            $ws2->getColumnDimension($col)->setWidth(11);
        }

        // ══════════════════════════════════════════════════════════════════════
        // SHEET 3: Programs (Social Welfare Beneficiaries & Side Chart)
        // ══════════════════════════════════════════════════════════════════════
        $ws3 = $spreadsheet->createSheet()->setTitle('Programs');

        $ws3->mergeCells('A1:C1');
        $ws3->setCellValue('A1', "Social Welfare Programs · $analysisYear · {$municipality->name}");
        $ws3->getStyle('A1')->applyFromArray(['font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => $navy]]]);

        $ws3->fromArray([['Program', 'Beneficiaries', '% of Largest']], null, 'A3');
        $headerFill($ws3, 'A3:C3');

        if ($programOverview->isEmpty()) {
            $ws3->setCellValue('A4', 'No beneficiary records for ' . $analysisYear);
            $ws3->getStyle('A4')->getFont()->setItalic(true)->getColor()->setRGB('7A84A8');
        } else {
            $maxB = $programOverview->max() ?: 1;
            $r = 4;
            foreach ($programOverview as $prog => $count) {
                $pctVal = round($count / $maxB * 100, 1);
                $ws3->setCellValue("A$r", str_replace('_', ' ', $prog));
                $ws3->setCellValueExplicit("B$r", $count, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                $ws3->getStyle("B$r")->getNumberFormat()->setFormatCode('#,##0');
                $ws3->setCellValueExplicit("C$r", $pctVal, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                $ws3->getStyle("C$r")->getNumberFormat()->setFormatCode('0.0"%"');
                if (($r - 4) % 2 === 0) $ws3->getStyle("A$r:C$r")->getFill()->setFillType('solid')->getStartColor()->setRGB($bg);
                $r++;
            }

            // Horizontal Bar chart for program beneficiaries (Positioned at E3:L18)
            $progBenefChart = new \PhpOffice\PhpSpreadsheet\Chart\Chart(
                'prog_benef_chart',
                new \PhpOffice\PhpSpreadsheet\Chart\Title("Beneficiaries by Program · $analysisYear"),
                null,
                new \PhpOffice\PhpSpreadsheet\Chart\PlotArea(null, [
                    new \PhpOffice\PhpSpreadsheet\Chart\DataSeries(
                        \PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_BARCHART,
                        \PhpOffice\PhpSpreadsheet\Chart\DataSeries::GROUPING_CLUSTERED,
                        [0],
                        [new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', null, null, 1, ['Beneficiaries'])],
                        [new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', "Programs!\$A\$4:\$A\$" . ($r-1), null, $programOverview->count())],
                        [new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', "Programs!\$B\$4:\$B\$" . ($r-1), null, $programOverview->count())]
                    )
                ])
            );
            $progBenefChart->setTopLeftPosition('E3');
            $progBenefChart->setBottomRightPosition('L18');
            $ws3->addChart($progBenefChart);
        }

        $ws3->getColumnDimension('A')->setWidth(26);
        $ws3->getColumnDimension('B')->setWidth(18);
        $ws3->getColumnDimension('C')->setWidth(16);
        $ws3->getColumnDimension('D')->setWidth(4); // spacer
        foreach (['E','F','G','H','I','J','K','L'] as $col) {
            $ws3->getColumnDimension($col)->setWidth(11);
        }

        // ══════════════════════════════════════════════════════════════════════
        // SHEET 4: Demographics (Clean, Vertically Stacked Non-Overlapping Charts)
        // ══════════════════════════════════════════════════════════════════════
        $ws4 = $spreadsheet->createSheet()->setTitle('Demographics');

        $ws4->mergeCells('A1:E1');
        $ws4->setCellValue('A1', "Demographics & Population Trend · {$municipality->name}");
        $ws4->getStyle('A1')->applyFromArray(['font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => $navy]]]);

        // Gender Distribution Table (A3:C7)
        $ws4->setCellValue('A3', "GENDER DISTRIBUTION · $analysisYear");
        $ws4->getStyle('A3')->applyFromArray(['font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => $navy]]]);
        $ws4->fromArray([['Gender', 'Count', 'Percentage']], null, 'A4');
        $headerFill($ws4, 'A4:C4');

        if ($malePop + $femalePop > 0) {
            $gT = $malePop + $femalePop;
            $ws4->fromArray([
                ['Male',   $malePop,   round($malePop   / $gT * 100, 1)],
                ['Female', $femalePop, round($femalePop / $gT * 100, 1)],
                ['Total',  $gT,        100],
            ], null, 'A5');
            $ws4->getStyle('B5:B7')->getNumberFormat()->setFormatCode('#,##0');
            $ws4->getStyle('C5:C6')->getNumberFormat()->setFormatCode('0.0"%"');
            $ws4->getStyle('A7:C7')->applyFromArray(['font' => ['bold' => true], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => $bg]]]);

            // Chart 1: Gender Distribution (Positioned at G4:N17)
            $genderChart = new \PhpOffice\PhpSpreadsheet\Chart\Chart(
                'gender_chart',
                new \PhpOffice\PhpSpreadsheet\Chart\Title("Gender Distribution · $analysisYear"),
                new \PhpOffice\PhpSpreadsheet\Chart\Legend(\PhpOffice\PhpSpreadsheet\Chart\Legend::POSITION_BOTTOM, null, false),
                new \PhpOffice\PhpSpreadsheet\Chart\PlotArea(null, [
                    new \PhpOffice\PhpSpreadsheet\Chart\DataSeries(
                        \PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_PIECHART,
                        null,
                        range(0, 1),
                        [new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', null, null, 1, ['Gender'])],
                        [new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', 'Demographics!$A$5:$A$6', null, 2)],
                        [new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', 'Demographics!$B$5:$B$6', null, 2)]
                    )
                ])
            );
            $genderChart->setTopLeftPosition('G4');
            $genderChart->setBottomRightPosition('N17');
            $ws4->addChart($genderChart);
        } else {
            $ws4->setCellValue('A5', 'No gender data for ' . $analysisYear);
            $ws4->getStyle('A5')->getFont()->setItalic(true)->getColor()->setRGB('7A84A8');
        }

        // Historical Population & Household Trend Table (A10:E...)
        $ws4->setCellValue('A10', 'HISTORICAL POPULATION & HOUSEHOLD TREND');
        $ws4->getStyle('A10')->applyFromArray(['font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => $navy]]]);
        $ws4->fromArray([['Year', 'Population', 'Households', 'Male', 'Female']], null, 'A11');
        $headerFill($ws4, 'A11:E11');

        $trendStartRow = 12;
        $r = $trendStartRow;
        foreach ($trend as $row) {
            $ws4->setCellValue("A$r", $row->year);
            foreach (['total_population' => 'B', 'total_households' => 'C', 'male_population' => 'D', 'female_population' => 'E'] as $field => $col) {
                if ($row->$field > 0) {
                    $ws4->setCellValueExplicit("{$col}{$r}", $row->$field, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                    $ws4->getStyle("{$col}{$r}")->getNumberFormat()->setFormatCode('#,##0');
                } else {
                    $ws4->setCellValue("{$col}{$r}", 'N/A');
                }
            }
            if (($r - $trendStartRow) % 2 === 0) $ws4->getStyle("A$r:E$r")->getFill()->setFillType('solid')->getStartColor()->setRGB($bg);
            $r++;
        }

        // Chart 2: Trend Chart (Positioned at G19:N33 - strictly BELOW Chart 1!)
        if ($trend->count() > 0) {
            $trendEndRow = $r - 1;
            $trendChart = new \PhpOffice\PhpSpreadsheet\Chart\Chart(
                'trend_chart',
                new \PhpOffice\PhpSpreadsheet\Chart\Title("Historical Population & Households Trend"),
                new \PhpOffice\PhpSpreadsheet\Chart\Legend(\PhpOffice\PhpSpreadsheet\Chart\Legend::POSITION_BOTTOM, null, false),
                new \PhpOffice\PhpSpreadsheet\Chart\PlotArea(null, [
                    new \PhpOffice\PhpSpreadsheet\Chart\DataSeries(
                        \PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_BARCHART,
                        \PhpOffice\PhpSpreadsheet\Chart\DataSeries::GROUPING_CLUSTERED,
                        [0, 1],
                        [
                            new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', null, null, 1, ['Population']),
                            new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', null, null, 1, ['Households']),
                        ],
                        [new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', "Demographics!\$A\$$trendStartRow:\$A\$$trendEndRow", null, $trend->count())],
                        [
                            new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', "Demographics!\$B\$$trendStartRow:\$B\$$trendEndRow", null, $trend->count()),
                            new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', "Demographics!\$C\$$trendStartRow:\$C\$$trendEndRow", null, $trend->count()),
                        ]
                    )
                ])
            );
            $trendChart->setTopLeftPosition('G19');
            $trendChart->setBottomRightPosition('N33');
            $ws4->addChart($trendChart);
        }

        $ws4->getColumnDimension('A')->setWidth(20);
        $ws4->getColumnDimension('B')->setWidth(16);
        $ws4->getColumnDimension('C')->setWidth(16);
        $ws4->getColumnDimension('D')->setWidth(16);
        $ws4->getColumnDimension('E')->setWidth(16);
        $ws4->getColumnDimension('F')->setWidth(4); // spacer column
        foreach (['G','H','I','J','K','L','M','N'] as $col) {
            $ws4->getColumnDimension($col)->setWidth(11);
        }

        // ══════════════════════════════════════════════════════════════════════
        // SHEET 5: Barangay Data (with Clean Bottom Bar Chart)
        // ══════════════════════════════════════════════════════════════════════
        $ws5 = $spreadsheet->createSheet()->setTitle('Barangay Data');

        $ws5->mergeCells('A1:I1');
        $ws5->setCellValue('A1', "Barangay Data · $analysisYear · {$municipality->name}");
        $ws5->getStyle('A1')->applyFromArray(['font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => $navy]]]);

        if ($barangays->isEmpty()) {
            $ws5->setCellValue('A3', 'No barangay records for ' . $analysisYear);
            $ws5->getStyle('A3')->getFont()->setItalic(true)->getColor()->setRGB('7A84A8');
        } else {
            $ws5->fromArray([['Barangay', 'Population', 'Male', 'Female', 'PWD', 'AICS', 'Solo Parent', '4Ps', 'Senior']], null, 'A3');
            $headerFill($ws5, 'A3:I3');

            $bgyStart = 4;
            $r = $bgyStart;
            foreach ($barangays as $b) {
                $pop = ($b->male_population ?? 0) + ($b->female_population ?? 0);
                $fields = [
                    $b->name,
                    $pop > 0 ? $pop : null,
                    ($b->male_population   ?? 0) > 0 ? $b->male_population   : null,
                    ($b->female_population ?? 0) > 0 ? $b->female_population : null,
                    ($b->pwd_count         ?? 0) > 0 ? $b->pwd_count         : null,
                    ($b->aics_count        ?? 0) > 0 ? $b->aics_count        : null,
                    ($b->solo_parent_count ?? 0) > 0 ? $b->solo_parent_count : null,
                    ($b->four_ps_count     ?? 0) > 0 ? $b->four_ps_count     : null,
                    ($b->senior_count      ?? 0) > 0 ? $b->senior_count      : null,
                ];
                $ws5->setCellValue("A$r", $b->name);
                $cols = ['B','C','D','E','F','G','H','I'];
                foreach (array_slice($fields, 1) as $k => $val) {
                    $col = $cols[$k];
                    if ($val !== null) {
                        $ws5->setCellValueExplicit("{$col}{$r}", $val, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC);
                        $ws5->getStyle("{$col}{$r}")->getNumberFormat()->setFormatCode('#,##0');
                    } else {
                        $ws5->setCellValue("{$col}{$r}", 'N/A');
                        $ws5->getStyle("{$col}{$r}")->getFont()->getColor()->setRGB('7A84A8');
                    }
                }
                if (($r - $bgyStart) % 2 === 0) $ws5->getStyle("A$r:I$r")->getFill()->setFillType('solid')->getStartColor()->setRGB($bg);
                $r++;
            }

            // Population by barangay bar chart (Positioned cleanly below table)
            $bgyEndRow = $r - 1;
            $bgyChart = new \PhpOffice\PhpSpreadsheet\Chart\Chart(
                'bgy_chart',
                new \PhpOffice\PhpSpreadsheet\Chart\Title("Population by Barangay · $analysisYear"),
                null,
                new \PhpOffice\PhpSpreadsheet\Chart\PlotArea(null, [
                    new \PhpOffice\PhpSpreadsheet\Chart\DataSeries(
                        \PhpOffice\PhpSpreadsheet\Chart\DataSeries::TYPE_BARCHART,
                        \PhpOffice\PhpSpreadsheet\Chart\DataSeries::GROUPING_CLUSTERED,
                        [0],
                        [new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', null, null, 1, ['Population'])],
                        [new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('String', "Barangay Data!\$A\$$bgyStart:\$A\$$bgyEndRow", null, $barangays->count())],
                        [new \PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues('Number', "Barangay Data!\$B\$$bgyStart:\$B\$$bgyEndRow", null, $barangays->count())]
                    )
                ])
            );
            $bgyChart->setTopLeftPosition('A' . ($r + 3));
            $bgyChart->setBottomRightPosition('I' . ($r + 20));
            $ws5->addChart($bgyChart);
        }

        $ws5->getColumnDimension('A')->setWidth(24);
        foreach (['B','C','D','E','F','G','H','I'] as $col) {
            $ws5->getColumnDimension($col)->setWidth(16);
        }

        // ── Footnote on each sheet ──────────────────────────────────────────
        $footnote = "Note: 'N/A' indicates no record exists for that specific year or barangay.";
        foreach ([$ws1, $ws2, $ws3, $ws4, $ws5] as $ws) {
            $lastRow = max(10, $ws->getHighestRow()) + 2;
            $ws->setCellValue("A$lastRow", $footnote);
            $ws->getStyle("A$lastRow")->applyFromArray(['font' => ['italic' => true, 'color' => ['rgb' => '7A84A8'], 'size' => 10]]);
        }

        // ── Set first sheet active ──────────────────────────────────────────
        $spreadsheet->setActiveSheetIndex(0);

        // ── Stream response ─────────────────────────────────────────────────
        $filename = 'analysis-' . $municipality->name . '-' . $analysisYear . '.xlsx';
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->setIncludeCharts(true);

        ob_start();
        $writer->save('php://output');
        $content2 = ob_get_clean();

        return response($content2, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'max-age=0',
        ]);
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

    /** Aggregate social welfare program beneficiary counts for the given year (synced from barangay data). */
    private function buildProgramOverview(string $municipality, ?int $year = null)
    {
        if ($year === null) {
            $year = (int) (Municipality::where('name', $municipality)->value('year') ?? date('Y'));
        }

        $programs = SocialWelfareProgram::where('municipality', $municipality)
            ->where('year', $year)
            ->whereNull('month')
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

        $barangays = Barangay::where('municipality', $user->municipality)
            ->distinct()
            ->orderBy('name')
            ->pluck('name');

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

        // ── Create in-app notification for the user ──────────────────────
        if ($oldStatus !== $request->status && in_array($request->status, ['approved', 'rejected'], true)) {
            $programType = $application->program_type ?? 'application';
            $notifType = match (true) {
                str_contains($programType, 'PWD')         => 'pwd',
                str_contains($programType, 'Solo_Parent') => 'solo_parent',
                str_contains($programType, 'AICS')        => 'aics',
                default                                   => 'application',
            };
            $programLabel = str_replace('_', ' ', $programType);

            if ($request->status === 'approved') {
                $notifTitle = 'Application Approved';
                $notifBody  = 'Your ' . $programLabel . ' application has been approved! Your ID/grant is now being processed.';
            } else {
                $remarks    = $request->admin_remarks ? ' Reason: ' . $request->admin_remarks : '';
                $notifTitle = 'Application Rejected';
                $notifBody  = 'Your ' . $programLabel . ' application was rejected.' . $remarks;
            }

            try {
                $notifId = \DB::table('notifications')->insertGetId([
                    'user_id'    => $application->user_id,
                    'type'       => $notifType,
                    'title'      => $notifTitle,
                    'body'       => $notifBody,
                    'is_read'    => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                \App\Services\OneSignalService::sendPush($application->user_id, $notifTitle, $notifBody, $notifType, $notifId, ['program_type' => $programType]);
            } catch (\Exception $e) {
                Log::error('Application status notification insert failed: ' . $e->getMessage());
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
            ->orderByDesc('year')
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

        $idFilter = (string) $request->query('id_filter', '');
        if ($idFilter === 'pending') {
            $users = $users->filter(fn($u) => $u->id_verification_status === \App\Models\User::ID_STATUS_PENDING)->values();
        }

        $statusFilter = (string) $request->query('status_filter', '');
        if ($statusFilter !== '') {
            $users = $users->filter(fn($u) => $u->status === $statusFilter)->values();
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
                    'status' => $u->status ?? 'pending',
                    'date_of_birth' => $u->date_of_birth,
                    'created_at' => optional($u->created_at)->toDateString(),
                    'email_verified_at' => $u->email_verified_at,
                    'id_verification_status' => $u->id_verification_status,
                    'valid_id_path' => $u->valid_id_path,
                    'valid_id_filename' => $u->valid_id_filename,
                    'id_rejection_reason' => $u->id_rejection_reason,
                    'total_apps' => (int) $u->total_apps,
                    'pending_apps' => (int) $u->pending_apps,
                    'approved_apps' => (int) $u->approved_apps,
                    'rejected_apps' => (int) $u->rejected_apps,
                ];
            })->values(),
        ]);
    }

    public function serveUserValidId($id)
    {
        $admin = Auth::user();
        $user = \App\Models\User::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->where('role', 'user')
            ->firstOrFail();

        if (!$user->valid_id_path) {
            abort(404, 'No valid ID uploaded for this user.');
        }

        $filePath = $user->valid_id_path;
        $candidates = [
            Storage::disk('public')->path($filePath),
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
            abort(404, 'Valid ID file not found.');
        }

        $mime = mime_content_type($fullPath) ?: 'application/octet-stream';
        $filename = $user->valid_id_filename ?: basename($filePath);

        return response()->file($fullPath, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }

    public function approveUserId($id)
    {
        $admin = Auth::user();
        $user = \App\Models\User::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->where('role', 'user')
            ->where('id_verification_status', \App\Models\User::ID_STATUS_PENDING)
            ->firstOrFail();

        $user->update([
            'id_verification_status' => \App\Models\User::ID_STATUS_APPROVED,
            'id_verified_at' => now(),
            'id_verified_by' => $admin->id,
            'id_rejection_reason' => null,
            'status' => 'active',
        ]);

        try {
            Mail::to($user->email)->send(new UserIdApprovedMail($user));
        } catch (\Exception $e) {
            Log::error('Failed to send ID approval email to user: ' . $e->getMessage());
        }

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Valid ID approved. User can now login.',
            ]);
        }

        return back()->with('success', 'Valid ID approved for ' . $user->full_name . '. The user can now login.');
    }

    public function declineUserId(Request $request, $id)
    {
        $admin = Auth::user();
        $user = \App\Models\User::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->where('role', 'user')
            ->where('id_verification_status', \App\Models\User::ID_STATUS_PENDING)
            ->firstOrFail();

        $declineReasonKeys = array_keys(\App\Models\User::ID_DECLINE_REASONS);

        $validated = $request->validate([
            'decline_reason' => 'required|string',
        ], [
            'decline_reason.required' => 'Please select a reason for declining the valid ID.',
        ]);

        $rawReason = $validated['decline_reason'];

        // Handle "other:custom text" format
        if (str_starts_with($rawReason, 'other:')) {
            $reasonText = trim(substr($rawReason, 6));
            if (empty($reasonText)) {
                return response()->json(['success' => false, 'message' => 'Please specify the reason for declining.'], 422);
            }
        } elseif (array_key_exists($rawReason, \App\Models\User::ID_DECLINE_REASONS)) {
            $reasonText = \App\Models\User::ID_DECLINE_REASONS[$rawReason];
        } else {
            return response()->json(['success' => false, 'message' => 'Please select a valid decline reason.'], 422);
        }
        $userEmail = $user->email;
        $userFullName = $user->full_name;
        $userMunicipality = $user->municipality;
        $validIdPath = $user->valid_id_path;
        $userId = $user->id;

        try {
            Mail::to($userEmail)->send(new UserIdDeclinedMail($userFullName, $reasonText, $userMunicipality));
        } catch (\Exception $e) {
            Log::error('Failed to send ID decline email to user: ' . $e->getMessage());
        }

        if ($validIdPath && Storage::disk('public')->exists($validIdPath)) {
            Storage::disk('public')->delete($validIdPath);
        }
        Storage::disk('public')->deleteDirectory('valid-ids/' . $userId);

        app(DeviceRegistrationService::class)->detachUser($userId);
        $user->forceDelete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Valid ID declined. The user has been notified by email and removed from the system.',
            ]);
        }

        return back()->with('success', 'Valid ID declined for ' . $userFullName . '. The user was notified by email.');
    }

    public function approveUserAccount($id)
    {
        $admin = Auth::user();
        $user = \App\Models\User::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->where('role', 'user')
            ->firstOrFail();

        $user->update([
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Account for ' . $user->full_name . ' has been approved successfully.',
        ]);
    }

    public function declineUserAccount($id)
    {
        $admin = Auth::user();
        $user = \App\Models\User::where('id', $id)
            ->where('municipality', $admin->municipality)
            ->where('role', 'user')
            ->firstOrFail();

        $user->update([
            'status' => 'inactive',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Account for ' . $user->full_name . ' has been declined and set to inactive.',
        ]);
    }
}
