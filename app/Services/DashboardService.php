<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    /**
     * Get aggregated application stats for the mobile dashboard.
     * Results are cached per-user for 60 seconds.
     */
    public function getMobileStats(User $user): array
    {
        return Cache::remember("dashboard.stats.{$user->id}", 60, function () use ($user) {
            $counts = Application::where('user_id', $user->id)
                ->selectRaw('
                    COUNT(*) as total,
                    SUM(status = "pending")  as pending,
                    SUM(status = "approved") as approved,
                    SUM(status = "rejected") as rejected
                ')
                ->first();

            return [
                'total'    => (int) ($counts->total    ?? 0),
                'pending'  => (int) ($counts->pending  ?? 0),
                'approved' => (int) ($counts->approved ?? 0),
                'rejected' => (int) ($counts->rejected ?? 0),
            ];
        });
    }

    /**
     * Invalidate the cached stats for a user (call after status changes).
     */
    public function invalidateStats(int $userId): void
    {
        Cache::forget("dashboard.stats.{$userId}");
    }

    /**
     * Get the 5 most recent applications for the mobile dashboard.
     */
    public function getRecentApplications(User $user): Collection
    {
        try {
            return Application::where('user_id', $user->id)
                ->orderBy('application_date', 'desc')
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error fetching recent applications', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
            return collect();
        }
    }

    /**
     * Get active announcements relevant to the user's municipality.
     * Cached per municipality for 5 minutes.
     */
    public function getAnnouncements(User $user): Collection
    {
        $key = 'announcements.' . ($user->municipality ?? 'all');

        return Cache::remember($key, 300, function () use ($user) {
            return Announcement::where('is_active', true)
                ->where(function ($q) use ($user) {
                    $q->whereNull('municipality')
                      ->orWhere('municipality', 'all')
                      ->orWhere('municipality', '')
                      ->orWhere('municipality', $user->municipality);
                })
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
                ->map(function ($ann) {
                    return [
                        'id'         => $ann->id,
                        'title'      => $ann->title,
                        'created_at' => $ann->created_at->format('F d, Y'),
                        'is_new'     => $ann->created_at && $ann->created_at->diffInDays(now()) <= 3,
                    ];
                });
        });
    }

    /**
     * Get all announcements (not limited to 5) for the announcements list screen.
     */
    public function getAllAnnouncements(User $user): Collection
    {
        return Announcement::where('is_active', true)
            ->where(function ($q) use ($user) {
                $q->whereNull('municipality')
                  ->orWhere('municipality', 'all')
                  ->orWhere('municipality', '')
                  ->orWhere('municipality', $user->municipality);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($ann) {
                return [
                    'id'         => $ann->id,
                    'title'      => $ann->title,
                    'content'    => $ann->content,
                    'created_at' => $ann->created_at->format('F d, Y'),
                    'is_new'     => $ann->created_at && $ann->created_at->diffInDays(now()) <= 3,
                ];
            });
    }
}
