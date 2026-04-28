<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'type',
        'program_type',
        'municipality',
        'created_by',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ── Constants ──────────────────────────────────────────────────────────────

    const TYPES = [
        'general'        => 'General',
        'event'          => 'Event',
        'emergency'      => 'Emergency',
        'program_update' => 'Program Update',
    ];

    const PROGRAMS = [
        'all'                    => 'All Programs',
        '4Ps'                    => '4Ps',
        'Senior Citizen Pension' => 'Senior Citizen Pension',
        'PWD Assistance'         => 'PWD Assistance',
        'AICS'                   => 'AICS',
        'SLP'                    => 'SLP',
        'ESA'                    => 'ESA',
        'Solo Parent'            => 'Solo Parent',
    ];

    /**
     * Maps Application.program_type values -> Announcement.program_type labels.
     */
    const PROGRAM_MAP = [
        'PWD_Assistance'         => 'PWD Assistance',
        'PWD_New'                => 'PWD Assistance',
        'PWD_Renewal'            => 'PWD Assistance',
        'AICS_Medical'           => 'AICS',
        'AICS_Burial'            => 'AICS',
        '4Ps'                    => '4Ps',
        'Senior_Citizen_Pension' => 'Senior Citizen Pension',
        'SLP'                    => 'SLP',
        'ESA'                    => 'ESA',
        'Solo_Parent'            => 'Solo Parent',
        'solo_parent'            => 'Solo Parent',
    ];

    // ── Relations ──────────────────────────────────────────────────────────────

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Query Scopes ───────────────────────────────────────────────────────────

    /**
     * Scope: active announcements visible to the given user.
     * Filters by the user's municipality AND their enrolled programs.
     */
    public function scopeForUser($query, User $user)
    {
        $query->where('is_active', true);

        // ── Municipality filter ──────────────────────────────────────────────
        // null / '' / 'all'  → visible to all users
        // specific value     → only users in that municipality
        // No municipality on user → only show "all" posts (no municipality set)
        if (!empty($user->municipality)) {
            $query->where(function ($q) use ($user) {
                $q->whereNull('municipality')
                  ->orWhere('municipality', '')
                  ->orWhere('municipality', 'all')
                  ->orWhere('municipality', $user->municipality);
            });
        } else {
            $query->where(function ($q) {
                $q->whereNull('municipality')
                  ->orWhere('municipality', '')
                  ->orWhere('municipality', 'all');
            });
        }

        // NOTE: Program field is intentionally NOT used as a filter here.
        // It is admin-side metadata only (e.g., tagging an announcement as
        // "4Ps"-related). Since programs like 4Ps are enrolled outside this
        // portal, users will never have matching application records, making
        // program-gated posts permanently invisible. Municipality targeting
        // is sufficient for correct scoping.

        return $query;
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    /**
     * Returns announcement program labels the user is enrolled in,
     * derived from their Applications table.
     */
    public static function resolveUserPrograms(User $user): array
    {
        $appPrograms = Application::where('user_id', $user->id)
            ->pluck('program_type')
            ->toArray();

        return collect($appPrograms)
            ->map(fn ($p) => self::PROGRAM_MAP[$p] ?? $p)
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     * Human-readable label for the announcement type.
     */
    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? ucfirst(str_replace('_', ' ', $this->type));
    }
}