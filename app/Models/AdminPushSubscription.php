<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * AdminPushSubscription model
 *
 * Stores Web Push API (VAPID) subscriptions for admin users.
 * One admin can have multiple subscriptions (one per browser/device).
 */
class AdminPushSubscription extends Model
{
    protected $table = 'admin_push_subscriptions';

    protected $fillable = [
        'admin_id',
        'endpoint',
        'p256dh',
        'auth',
        'user_agent',
        'last_used_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
