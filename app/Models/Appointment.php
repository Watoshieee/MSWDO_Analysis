<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Appointment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id', 'municipality', 'appointment_date', 'appointment_time',
        'interview_type', 'program_type', 'status',
        'user_notes', 'admin_notes', 'reminded_at',
        'solo_parent_app_id', 'validated_at',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'reminded_at'      => 'datetime',
        'validated_at'     => 'datetime',
        'deleted_at'       => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soloParentApplication()
    {
        return $this->belongsTo(\App\Models\Application::class, 'solo_parent_app_id');
    }

    // ── Slot configuration ─────────────────────────────────────────────────────
    /** Hour slots available (excludes lunch 12:00) */
    public static function availableSlots(): array
    {
        return ['08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00'];
    }

    public static function maxPerSlot(): int { return 5; }

    /**
     * Count confirmed appointments in a specific date+time slot.
     */
    public static function slotCount(string $date, string $time, string $municipality): int
    {
        return static::where('appointment_date', $date)
            ->where('appointment_time', $time)
            ->where('municipality', $municipality)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();
    }

    /**
     * Returns slot data for a given date scoped to a municipality.
     */
    public static function slotsForDate(string $date, string $municipality): array
    {
        $slots = [];
        foreach (static::availableSlots() as $time) {
            $taken = static::slotCount($date, $time, $municipality);
            $slots[] = [
                'time'      => $time,
                'label'     => Carbon::createFromFormat('H:i', $time)->format('h:i A'),
                'taken'     => $taken,
                'remaining' => max(0, static::maxPerSlot() - $taken),
                'full'      => $taken >= static::maxPerSlot(),
            ];
        }
        return $slots;
    }

    // ── Helpers ────────────────────────────────────────────────────────────────
    public function getFormattedDateAttribute(): string
    {
        return $this->appointment_date->format('F d, Y (l)');
    }

    public function getFormattedTimeAttribute(): string
    {
        return Carbon::createFromFormat('H:i', $this->appointment_time)->format('h:i A');
    }

    public function getInterviewLabelAttribute(): string
    {
        return $this->interview_type === 'online' ? 'Online Interview' : 'Face-to-Face';
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'confirmed'  => '<span style="background:#d4edda;color:#155724;border-radius:20px;padding:3px 12px;font-size:.75rem;font-weight:700;">✅ Confirmed</span>',
            'validated'  => '<span style="background:#cce5ff;color:#004085;border-radius:20px;padding:3px 12px;font-size:.75rem;font-weight:700;">🏆 Validated</span>',
            'rejected'   => '<span style="background:#f8d7da;color:#721c24;border-radius:20px;padding:3px 12px;font-size:.75rem;font-weight:700;">❌ Rejected</span>',
            'cancelled'  => '<span style="background:#e9ecef;color:#6c757d;border-radius:20px;padding:3px 12px;font-size:.75rem;font-weight:700;">🚫 Cancelled</span>',
            default      => '<span style="background:#FFF3D6;color:#856404;border-radius:20px;padding:3px 12px;font-size:.75rem;font-weight:700;">⏳ Pending</span>',
        };
    }
}
