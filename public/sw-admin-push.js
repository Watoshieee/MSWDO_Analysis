/**
 * MSWDO Admin Web Push Service Worker
 *
 * Handles background push notifications for admin users.
 * This file MUST be at the root of the web application (/sw-admin-push.js)
 * so it has scope over the entire admin section.
 *
 * Windows Desktop Notifications:
 *   - Works in Chrome, Edge, Firefox on Windows
 *   - Appears in Windows Action Center
 *   - Works even when the browser tab is closed (as long as browser is running)
 *   - On Edge, works even when browser is fully closed (via Edge's background process)
 */

const MSWDO_SW_VERSION = '1.0.2';
const MSWDO_LOGO_URL   = '/images/logo_mswdo.jpg';

// ── Install & Activate ────────────────────────────────────────────────────────

self.addEventListener('install', function (event) {
    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    event.waitUntil(self.clients.claim());
});

// ── Push Event Handler ────────────────────────────────────────────────────────

self.addEventListener('push', function (event) {
    let payload = {};
    try {
        payload = event.data ? event.data.json() : {};
    } catch (e) {
        payload = { title: 'MSWDO Notification', body: event.data ? event.data.text() : '' };
    }

    const title   = payload.title   || 'MSWDO Admin Notification';
    const body    = payload.body    || 'You have a new notification.';
    const icon    = payload.icon    || MSWDO_LOGO_URL;
    const badge   = payload.badge   || MSWDO_LOGO_URL;
    const tag     = payload.tag     || 'mswdo-admin';
    const data    = payload.data    || {};
    const actions = buildActions(payload.type || '');

    const options = {
        body:             body,
        icon:             icon,
        badge:            badge,
        tag:              tag,
        renotify:         true,
        requireInteraction: false,
        data:             data,
        actions:          actions,
        vibrate:          [200, 100, 200],
        timestamp:        Date.now(),
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

/**
 * Build action buttons based on notification type.
 */
function buildActions(type) {
    switch (type) {
        case 'new_user_registration':
            return [
                { action: 'view_users', title: 'View Users' },
            ];
        case 'new_application':
        case 'new_aics_application':
            return [
                { action: 'view_applications', title: 'Review' },
            ];
        case 'new_appointment':
            return [
                { action: 'view_appointments', title: 'View' },
            ];
        default:
            return [
                { action: 'view', title: 'View' },
            ];
    }
}

// ── Notification Click Handler ────────────────────────────────────────────────

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    const data       = event.notification.data || {};
    const actionType = event.action;
    const notifId    = data.notifId;
    let   targetUrl  = data.url || '/admin/dashboard';

    // Override URL based on which button was clicked
    if (actionType === 'view_users') {
        targetUrl = '/admin/users';
    } else if (actionType === 'view_applications') {
        targetUrl = data.url || '/admin/requirements';
    } else if (actionType === 'view_appointments') {
        targetUrl = '/admin/requirements';
    }

    event.waitUntil(
        self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (clientList) {
            // Mark notification as read via fetch (best-effort)
            if (notifId) {
                fetch('/admin/notifications/' + notifId + '/read', {
                    method:  'POST',
                    headers: { 'X-CSRF-TOKEN': '', 'Content-Type': 'application/json' },
                }).catch(function () {});
            }

            // Try to focus existing admin window/tab
            for (const client of clientList) {
                if (client.url.includes('/admin') && 'focus' in client) {
                    client.focus();
                    if (client.navigate) {
                        return client.navigate(targetUrl);
                    }
                    return;
                }
            }

            // No existing window — open a new one
            if (self.clients.openWindow) {
                return self.clients.openWindow(targetUrl);
            }
        })
    );
});

// ── Push Subscription Change ──────────────────────────────────────────────────

/**
 * Fired when the browser automatically re-generates the push subscription
 * (e.g. key rotation). We re-subscribe and update the server.
 */
self.addEventListener('pushsubscriptionchange', function (event) {
    event.waitUntil(
        self.registration.pushManager.subscribe(event.oldSubscription.options)
            .then(function (subscription) {
                return fetch('/admin/push/subscribe', {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body:    JSON.stringify({
                        endpoint: subscription.endpoint,
                        keys: {
                            p256dh: btoa(String.fromCharCode.apply(null, new Uint8Array(subscription.getKey('p256dh')))),
                            auth:   btoa(String.fromCharCode.apply(null, new Uint8Array(subscription.getKey('auth')))),
                        },
                    }),
                });
            })
    );
});
