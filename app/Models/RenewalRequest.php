<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class RenewalRequest extends Model
{
    use SoftDeletes;

    protected $table = 'renewal_requests';

    protected $fillable = [
        'user_id',
        'municipality',
        'program_type',
        'id_file_path',
        'id_file_name',
        'appointment_id',
        'status',
        'user_notes',
        'admin_remarks',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'deleted_at'  => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ── Status Helpers ─────────────────────────────────────────────────────────
    public function getStatusBadgeAttribute(): string
    {
        if ($this->status === 'approved') {
            if ($this->appointment && $this->appointment->status === 'validated') {
                return '<span style="background:#cce5ff;color:#004085;border-radius:20px;padding:3px 12px;font-size:.75rem;font-weight:700;">Renewal Validated</span>';
            }
            if ($this->appointment && $this->appointment->status === 'confirmed') {
                return '<span style="background:#d4edda;color:#155724;border-radius:20px;padding:3px 12px;font-size:.75rem;font-weight:700;">Appointment Confirmed</span>';
            }
            return '<span style="background:#d1fae5;color:#065f46;border-radius:20px;padding:3px 12px;font-size:.75rem;font-weight:700;">ID Approved</span>';
        }

        return match ($this->status) {
            'rejected'  => '<span style="background:#f8d7da;color:#721c24;border-radius:20px;padding:3px 12px;font-size:.75rem;font-weight:700;">ID Rejected</span>',
            'cancelled' => '<span style="background:#e9ecef;color:#6c757d;border-radius:20px;padding:3px 12px;font-size:.75rem;font-weight:700;">Cancelled</span>',
            default     => '<span style="background:#FFF3D6;color:#856404;border-radius:20px;padding:3px 12px;font-size:.75rem;font-weight:700;">Pending ID Approval</span>',
        };
    }
}
