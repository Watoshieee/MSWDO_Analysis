<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Application;
use App\Models\Municipality;
use App\Models\NotificationView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * List all announcements + show create form.
     */
    public function index(Request $request)
    {
        $admin = Auth::user();

        $query = Announcement::with('creator')->orderBy('created_at', 'desc');

        // Admins only see their own municipality's announcements (+ "all")
        if ($admin->role === 'admin') {
            $query->where(function ($q) use ($admin) {
                $q->where('municipality', $admin->municipality)
                  ->orWhere('municipality', 'all')
                  ->orWhereNull('municipality')
                  ->orWhere('municipality', '');
            });
        }

        $announcements  = $query->get();
        $municipalities = Municipality::orderBy('name')->pluck('name');

        // Notification bell data
        $lastViewed   = NotificationView::where('user_id', $admin->id)->first();
        $lastViewedAt = $lastViewed ? $lastViewed->last_viewed_at : null;
        $nQuery = Application::where('municipality', $admin->municipality)
            ->where('status', 'pending')
            ->with('user')
            ->orderBy('application_date', 'desc');
        $adminNewApplications = $lastViewedAt
            ? (clone $nQuery)->where('application_date', '>', $lastViewedAt)->get()
            : (clone $nQuery)->where('application_date', '>=', now()->subDays(3))->get();
        $adminNotifCount = $adminNewApplications->count();

        return view('admin.announcements.index', compact(
            'announcements', 'municipalities', 'admin',
            'adminNewApplications', 'adminNotifCount'
        ));
    }

    /**
     * Store a new announcement.
     */
    public function store(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'content'       => 'required|string|max:5000',
            'type'          => 'required|in:general,event,emergency,program_update',
            'program_type'  => 'required|string',
            'municipality'  => 'required|string',
        ]);

        // Admins cannot broadcast to "all municipalities" — lock to their own
        $municipality = $request->municipality;
        if ($admin->role === 'admin' && $municipality === 'all') {
            $municipality = $admin->municipality;
        }

        Announcement::create([
            'title'        => $request->title,
            'content'      => $request->input('content'),
            'type'         => $request->type,
            'program_type' => $request->program_type,
            'municipality' => $municipality,
            'created_by'   => $admin->id,
            'is_active'    => true,
        ]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement posted successfully.');
    }

    /**
     * Deactivate (hide from users without deleting).
     */
    public function deactivate(Announcement $announcement)
    {
        $this->authorizeAnnouncement($announcement);
        $announcement->update(['is_active' => false]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deactivated.');
    }

    /**
     * Re-activate a deactivated announcement.
     */
    public function activate(Announcement $announcement)
    {
        $this->authorizeAnnouncement($announcement);
        $announcement->update(['is_active' => true]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement activated.');
    }

    /**
     * Permanently delete an announcement.
     */
    public function destroy(Announcement $announcement)
    {
        $this->authorizeAnnouncement($announcement);
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement deleted.');
    }

    // ── Private Helpers ────────────────────────────────────────────────────────

    /**
     * Admins can only manage announcements for their own municipality.
     */
    protected function authorizeAnnouncement(Announcement $announcement): void
    {
        $admin = Auth::user();
        if ($admin->role === 'admin') {
            abort_if(
                $announcement->municipality !== $admin->municipality
                && $announcement->municipality !== 'all'
                && $announcement->municipality !== null,
                403,
                'You are not authorized to manage this announcement.'
            );
        }
    }
}
