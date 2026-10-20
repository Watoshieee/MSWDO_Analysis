<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * AdminNotification model
 *
 * Persistent admin-side notifications: new user registrations, new program
 * applications, renewal requests, etc.
 *
 * Columns:
 *   id, admin_id, type, title, body, data (JSON), is_read, read_at,
 *   push_sent_at, created_at, updated_at
 */
class AdminNotification extends Model
{
    protected $table = 'admin_notifications';

    protected $fillable = [
        'admin_id',
        'type',
        'title',
        'body',
        'data',
        'is_read',
        'read_at',
        'push_sent_at',
    ];

    protected $casts = [
        'data'         => 'array',
        'is_read'      => 'boolean',
        'read_at'      => 'datetime',
        'push_sent_at' => 'datetime',
    ];

    // ── Notification type constants ────────────────────────────────────────────
    const TYPE_NEW_USER_REGISTRATION  = 'new_user_registration';
    const TYPE_NEW_APPLICATION        = 'new_application';
    const TYPE_NEW_AICS_APPLICATION   = 'new_aics_application';
    const TYPE_NEW_RENEWAL            = 'new_renewal';
    const TYPE_NEW_APPOINTMENT        = 'new_appointment';
    const TYPE_RESCHEDULE_REQUEST     = 'reschedule_request';
    const TYPE_REQUIREMENTS_SUBMITTED = 'requirements_submitted';
    const TYPE_APP_STATUS_CHANGE      = 'app_status_change';
    const TYPE_ID_VERIFICATION        = 'id_verification';
    const TYPE_DOCUMENT_UPLOAD        = 'document_upload';

    // ── Relationships ──────────────────────────────────────────────────────────
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    // ── Scopes ────────────────────────────────────────────────────────────────
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForAdmin($query, int $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    // ── Actions ───────────────────────────────────────────────────────────────
    public function markAsRead(): void
    {
        if (! $this->is_read) {
            $this->update(['is_read' => true, 'read_at' => now()]);
        }
    }

    /**
     * Human-readable URL to navigate to when the admin clicks the notification.
     * Routes to the appropriate admin page based on notification type.
     */
    public function getActionUrl(): string
    {
        if (isset($this->data['action_url'])) {
            return $this->data['action_url'];
        }
        return match ($this->type) {
            self::TYPE_NEW_APPOINTMENT, self::TYPE_RESCHEDULE_REQUEST => route('admin.admin.appointments.index'),
            self::TYPE_NEW_RENEWAL => route('admin.renewals.index'),
            self::TYPE_NEW_USER_REGISTRATION => route('admin.users'),
            default => route('admin.requirements'),
        };
    }
}
