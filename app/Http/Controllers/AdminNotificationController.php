<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\AdminPushSubscription;
use App\Services\AdminNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * AdminNotificationController
 *
 * Handles:
 *  1. Web Push subscription management (subscribe/unsubscribe)
 *  2. Admin bell notification API (unread count, list, mark-read)
 *  3. VAPID public key endpoint (needed by browser for PushManager.subscribe)
 */
class AdminNotificationController extends Controller
{
    // ── VAPID public key ───────────────────────────────────────────────────────

    /**
     * GET /admin/push/vapid-key
     * Returns the VAPID public key so the browser can subscribe.
     */
    public function vapidPublicKey()
    {
        return response()->json([
            'publicKey' => AdminNotificationService::vapidPublicKey(),
        ]);
    }

    // ── Push Subscription Management ───────────────────────────────────────────

    /**
     * POST /admin/push/subscribe
     * Saves or updates a Web Push subscription for the logged-in admin.
     * Called by the browser service worker after user grants permission.
     */
    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'endpoint' => 'required|string|max:2048',
            'keys.p256dh' => 'required|string',
            'keys.auth'   => 'required|string',
        ]);

        $admin = Auth::user();

        AdminPushSubscription::updateOrCreate(
            [
                'admin_id' => $admin->id,
                'endpoint' => $validated['endpoint'],
            ],
            [
                'p256dh'       => $validated['keys']['p256dh'],
                'auth'         => $validated['keys']['auth'],
                'user_agent'   => substr((string) $request->userAgent(), 0, 300),
                'last_used_at' => now(),
            ]
        );

        return response()->json(['success' => true]);
    }

    /**
     * POST /admin/push/unsubscribe
     * Removes a push subscription (when admin revokes permission or logs out).
     */
    public function unsubscribe(Request $request)
    {
        $validated = $request->validate([
            'endpoint' => 'required|string',
        ]);

        AdminPushSubscription::where('admin_id', Auth::id())
            ->where('endpoint', $validated['endpoint'])
            ->delete();

        return response()->json(['success' => true]);
    }

    // ── Bell Notification API ──────────────────────────────────────────────────

    /**
     * GET /admin/notifications/count
     * Returns the unread admin notification count — used for bell badge polling.
     */
    public function unreadCount()
    {
        $count = AdminNotification::where('admin_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * GET /admin/notifications
     * Returns recent admin notifications (unread first, then read, max 30).
     */
    public function index()
    {
        $notifs = AdminNotification::where('admin_id', Auth::id())
            ->orderBy('is_read')
            ->orderByDesc('created_at')
            ->limit(30)
            ->get()
            ->map(fn ($n) => [
                'id'         => $n->id,
                'type'       => $n->type,
                'title'      => $n->title,
                'body'       => $n->body,
                'is_read'    => (bool) $n->is_read,
                'action_url' => $n->getActionUrl(),
                'data'       => $n->data,
                'created_at' => $n->created_at?->diffForHumans(),
                'created_fmt' => $n->created_at?->setTimezone('Asia/Manila')->format('M d, Y h:i A'),
            ]);

        return response()->json([
            'notifications' => $notifs,
            'unread_count'  => $notifs->where('is_read', false)->count(),
        ]);
    }

    /**
     * POST /admin/notifications/{id}/read
     * Marks a single notification as read.
     */
    public function markRead($id)
    {
        $notif = AdminNotification::where('id', $id)
            ->where('admin_id', Auth::id())
            ->firstOrFail();

        $notif->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * POST /admin/notifications/read-all
     * Marks ALL unread notifications as read.
     */
    public function markAllRead()
    {
        AdminNotification::where('admin_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        return response()->json(['success' => true]);
    }
}
