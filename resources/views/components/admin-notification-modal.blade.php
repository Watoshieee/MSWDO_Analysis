{{--
    Admin Notification Bell Modal — MSWDO
    Shows:
      1. Admin bell notifications (new user registrations, renewals, etc.) — from admin_notifications table
      2. New pending applications
      3. New Solo Parent appointments
      4. New document uploads

    Variables expected (from adminNotifData()):
        $adminNewApplications   – Application collection
        $adminNewAppointments   – Appointment collection
        $adminNewUploads        – FileMonitoring collection
        $adminNotifCount        – int (badge count total)
--}}

@php
    $hasNew        = isset($adminNewApplications) && $adminNewApplications->count() > 0;
    $hasNewAppts   = isset($adminNewAppointments) && $adminNewAppointments->count() > 0;
    $hasNewUploads = isset($adminNewUploads) && $adminNewUploads->count() > 0;
    $total         = ($hasNew ? $adminNewApplications->count() : 0)
                   + ($hasNewAppts ? $adminNewAppointments->count() : 0)
                   + ($hasNewUploads ? $adminNewUploads->count() : 0);
    $programLabels = [
        'Senior_Citizen_Pension' => 'Senior Citizen',
        'PWD_Assistance'         => 'PWD Assistance',
        'PWD_New'                => 'PWD (New)',
        'PWD_Renewal'            => 'PWD (Renewal)',
        'AICS_Medical'           => 'AICS Medical',
        'AICS_Burial'            => 'AICS Burial',
        '4Ps'                    => '4Ps',
        'SLP'                    => 'SLP',
        'ESA'                    => 'ESA',
        'Solo_Parent'            => 'Solo Parent',
        'solo_parent'            => 'Solo Parent',
    ];
    $fmtAdminTs = function ($ts) {
        if (!$ts) return null;
        try {
            $c = $ts instanceof \Carbon\CarbonInterface ? $ts : \Carbon\Carbon::parse($ts);
            return $c->copy()->setTimezone('Asia/Manila');
        } catch (\Exception $e) { return null; }
    };
@endphp

{{-- Admin Notification Modal --}}
<div id="adminNotifModal"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:10000;align-items:center;justify-content:center;"
    onclick="if(event.target===this) closeAdminNotifModal()">
    <div style="background:white;width:90%;max-width:920px;border-radius:20px;max-height:88vh;display:flex;flex-direction:column;box-shadow:0 10px 40px rgba(0,0,0,0.15);animation:fadeInScale .25s ease;">

        {{-- Header --}}
        <div style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;padding:22px 28px;border-radius:20px 20px 0 0;display:flex;align-items:center;justify-content:space-between;">
            <div>
                <h5 style="font-weight:800;font-size:1.2rem;margin:0;">Notifications</h5>
                <p style="font-size:0.82rem;opacity:0.82;margin:3px 0 0;">New activity in your municipality</p>
            </div>
            <div style="display:flex;align-items:center;gap:10px;">
                <button id="adminNotifMarkAllBtn" type="button"
                    onclick="adminNotifMarkAll()"
                    style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);color:white;border-radius:8px;padding:6px 13px;font-size:0.78rem;font-weight:700;cursor:pointer;transition:background 0.15s;">
                    Mark all read
                </button>
                <button onclick="closeAdminNotifModal()"
                    style="background:rgba(255,255,255,.15);border:none;color:white;border-radius:50%;width:34px;height:34px;font-size:1.1rem;cursor:pointer;line-height:1;">&times;</button>
            </div>
        </div>

        {{-- Body --}}
        <div id="adminNotifBody" style="padding:22px 28px;overflow-y:auto;background:#fafbfc;">

            {{-- ── Section 1: Admin Bell Notifications (dynamic, from API) ─────── --}}
            <div id="adminBellNotifSection">
                <div style="text-align:center;padding:20px;color:#94a3b8;font-size:0.84rem;" id="adminBellLoading">
                    <span>Loading notifications…</span>
                </div>
            </div>

            {{-- ── Section 2: New Applications ─────────────────────────────────── --}}
            @if($hasNew)
            <div id="adminNewAppsSection" style="margin-top:16px;">
                <h6 style="font-size:.78rem;text-transform:uppercase;letter-spacing:.08em;color:#f59e0b;font-weight:700;margin-bottom:12px;">
                    New Applications ({{ $adminNewApplications->count() }})
                </h6>
                @foreach($adminNewApplications as $app)
                @php
                    $label   = $programLabels[$app->program_type] ?? str_replace('_', ' ', $app->program_type);
                    $appDate = $fmtAdminTs($app->created_at ?? $app->application_date);
                @endphp
                <div class="admin-notif-card" style="background:white;border-radius:14px;padding:16px 20px;margin-bottom:10px;border:1px solid #e2e8f0;border-left:4px solid #f5b82e;box-shadow:0 2px 6px rgba(0,0,0,.04);">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;margin-bottom:6px;">
                        <div>
                            <h6 style="font-weight:800;font-size:.9rem;color:#1e293b;margin:0 0 2px;">New Application</h6>
                            <p style="font-size:.74rem;color:#94a3b8;margin:0;">
                                {{ $appDate ? $appDate->diffForHumans() : 'Recently' }}
                                @if($appDate) <span style="color:#cbd5e1;">•</span> {{ $appDate->format('M d, Y h:i A') }} @endif
                            </p>
                        </div>
                        <span style="padding:3px 10px;border-radius:12px;font-size:.68rem;font-weight:800;text-transform:uppercase;white-space:nowrap;background:#FFF8E1;color:#856404;flex-shrink:0;">PENDING</span>
                    </div>
                    <div style="background:#f8fafc;border-radius:10px;padding:10px 14px;margin-bottom:8px;font-size:.84rem;color:#475569;line-height:1.7;">
                        <p style="margin:0;"><strong style="color:#1e293b;">Applicant:</strong> {{ $app->user->full_name ?? ($app->first_name . ' ' . $app->last_name) }}</p>
                        <p style="margin:0;"><strong style="color:#1e293b;">Program:</strong> {{ $label }}</p>
                        @if($app->barangay)<p style="margin:0;"><strong style="color:#1e293b;">Barangay:</strong> {{ $app->barangay }}</p>@endif
                    </div>
                    <a href="{{ route('admin.view-requirement', $app->id) }}"
                       style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;padding:8px 16px;border-radius:10px;font-size:.82rem;font-weight:700;text-decoration:none;">
                        Review Application →
                    </a>
                </div>
                @endforeach
            </div>
            @endif

            {{-- ── Section 3: New Solo Parent Appointments ─────────────────────── --}}
            @if($hasNewAppts)
            <div style="margin-top:16px;">
                <h6 style="font-size:.78rem;text-transform:uppercase;letter-spacing:.08em;color:#6366f1;font-weight:700;margin-bottom:12px;">
                    New Solo Parent Appointments ({{ $adminNewAppointments->count() }})
                </h6>
                @foreach($adminNewAppointments as $appt)
                <div class="admin-notif-card" style="background:white;border-radius:14px;border:1.5px solid #e0e7ff;padding:14px 18px;margin-bottom:10px;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
                        <div style="flex:1;">
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:5px;">
                                <span style="background:#e0e7ff;color:#3730a3;border-radius:20px;padding:3px 10px;font-size:.72rem;font-weight:700;">Solo Parent Appt</span>
                                <span style="background:#fff8e1;color:#856404;border-radius:20px;padding:3px 10px;font-size:.72rem;font-weight:700;">Pending</span>
                            </div>
                            <p style="margin:0;font-weight:700;color:#1e293b;font-size:.9rem;">{{ $appt->user?->full_name ?? 'Unknown' }}</p>
                            @php $apptCreated = $fmtAdminTs($appt->created_at); @endphp
                            <p style="margin:1px 0 0;font-size:.78rem;color:#64748b;">{{ $apptCreated ? $apptCreated->diffForHumans() : '' }}</p>
                        </div>
                        <a href="{{ route('admin.requirements') }}"
                           style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border:none;border-radius:10px;padding:8px 16px;font-size:.78rem;font-weight:700;white-space:nowrap;text-decoration:none;">Review</a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- ── Section 4: New Document Uploads ─────────────────────────────── --}}
            @if($hasNewUploads)
            <div style="margin-top:16px;">
                <h6 style="font-size:.78rem;text-transform:uppercase;letter-spacing:.08em;color:#dc2626;font-weight:700;margin-bottom:12px;">
                    New Documents Uploaded ({{ $adminNewUploads->count() }})
                </h6>
                @foreach($adminNewUploads as $fm)
                <div class="admin-notif-card" style="background:white;border-radius:14px;border:1.5px solid #fecaca;padding:14px 18px;margin-bottom:10px;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
                        <div style="flex:1;">
                            <div style="display:flex;align-items:center;gap:8px;margin-bottom:5px;">
                                <span style="background:#fee2e2;color:#991b1b;border-radius:20px;padding:3px 10px;font-size:.72rem;font-weight:700;">Solo Parent Docs</span>
                                <span style="background:#fff8e1;color:#856404;border-radius:20px;padding:3px 10px;font-size:.72rem;font-weight:700;">Pending Review</span>
                            </div>
                            <p style="margin:0;font-weight:700;color:#1e293b;font-size:.9rem;">{{ $fm->application?->full_name ?? 'Unknown' }}</p>
                            @php $latestUploadAt = $fmtAdminTs($fm->fileUploads->whereNotNull('file_path')->max('uploaded_at')); @endphp
                            <p style="margin:1px 0 0;font-size:.78rem;color:#64748b;">
                                {{ $latestUploadAt ? $latestUploadAt->diffForHumans() : 'Recently' }}
                                &nbsp;|&nbsp; {{ $fm->fileUploads->whereNotNull('file_path')->count() }} file(s)
                            </p>
                        </div>
                        <a href="{{ route('admin.view-requirement', $fm->application_id) }}"
                           style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#dc2626,#b91c1c);color:white;border:none;border-radius:10px;padding:8px 16px;font-size:.78rem;font-weight:700;white-space:nowrap;text-decoration:none;">Review</a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- Empty state (shown by JS if no bell notifs and no Blade sections) --}}
            @if(!$hasNew && !$hasNewAppts && !$hasNewUploads)
            <div id="adminNotifEmptyState" style="display:none;text-align:center;padding:60px 20px;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 12px;display:block;">
                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.7 21a2 2 0 0 1-3.4 0"/>
                </svg>
                <h6 style="font-weight:700;color:#64748b;margin-bottom:6px;">No notifications</h6>
                <p style="font-size:.85rem;color:#94a3b8;margin:0;">All caught up! New activity will appear here.</p>
            </div>
            @endif
        </div>

        {{-- Footer --}}
        <div style="background:#f8f9fa;border:none;padding:14px 28px;display:flex;justify-content:space-between;align-items:center;border-radius:0 0 20px 20px;">
            <span id="adminNotifFooterCount" style="font-size:.8rem;color:#64748b;"></span>
            <div style="display:flex;gap:10px;">
                <a href="{{ route('admin.users') }}"
                   style="background:#6366f1;color:white;border:none;border-radius:10px;padding:9px 20px;font-weight:700;font-size:.85rem;text-decoration:none;">
                    View Users
                </a>
                <a href="{{ route('admin.requirements') }}"
                   style="background:#f5b82e;color:#1A2A5C;border:none;border-radius:10px;padding:9px 20px;font-weight:700;font-size:.85rem;text-decoration:none;">
                    View Applications
                </a>
                <button type="button" onclick="closeAdminNotifModal()"
                    style="background:#2C3E8F;color:white;border:none;border-radius:10px;padding:9px 20px;font-weight:700;font-size:.85rem;cursor:pointer;">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.admin-notif-card { transition: box-shadow .2s, transform .2s; }
.admin-notif-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); transform: translateY(-2px); }
.admin-bell-item { border-left: 4px solid #6366f1 !important; }
.admin-bell-item.type-new_user_registration { border-left-color: #8b5cf6 !important; }
.admin-bell-item.type-new_application { border-left-color: #f59e0b !important; }
.admin-bell-item.type-new_appointment { border-left-color: #3b82f6 !important; }
.admin-bell-item.type-new_renewal     { border-left-color: #10b981 !important; }

@keyframes fadeInScale {
    from { transform: scale(.95); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}
</style>

<script>
/* =============================================================
   ADMIN NOTIFICATION MODAL & WEB PUSH REGISTRATION
   ============================================================= */

var _adminNotifLoaded = false;
var _adminNotifCsrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

// ── Modal open/close ──────────────────────────────────────────
function openAdminNotifModal() {
    document.getElementById('adminNotifModal').style.display = 'flex';
    loadAdminBellNotifications();
    markAdminNotificationsViewed();
}
function closeAdminNotifModal() {
    document.getElementById('adminNotifModal').style.display = 'none';
}

// ── Mark old NotificationView as viewed (existing badge reset) ─
function markAdminNotificationsViewed() {
    fetch('{{ route('admin.mark-notifications-viewed') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': _adminNotifCsrfToken }
    }).catch(function(){});
}

// ── Load admin bell notifications from API ─────────────────────
function loadAdminBellNotifications() {
    var section = document.getElementById('adminBellNotifSection');
    if (!section) return;

    fetch('{{ route('admin.notifications.index') }}')
        .then(function(r){ return r.json(); })
        .then(function(data) {
            var notifs = data.notifications || [];
            var unread = data.unread_count || 0;

            if (notifs.length === 0) {
                section.innerHTML = '';
                var hasAnyBladeContent = {{ ($hasNew || $hasNewAppts || $hasNewUploads) ? 'true' : 'false' }};
                if (!hasAnyBladeContent) {
                    var emptyEl = document.getElementById('adminNotifEmptyState');
                    if (emptyEl) emptyEl.style.display = 'block';
                }
                return;
            }

            // Build notification cards
            var html = '<h6 style="font-size:.78rem;text-transform:uppercase;letter-spacing:.08em;color:#8b5cf6;font-weight:700;margin-bottom:12px;">Recent Events</h6>';
            notifs.forEach(function(n) {
                var typeIcon = getAdminNotifIcon(n.type);
                var isUnread = !n.is_read;
                html += '<div class="admin-notif-card admin-bell-item type-' + n.type + '" ';
                html += 'id="admin-notif-' + n.id + '" ';
                html += 'style="background:' + (isUnread ? '#f5f3ff' : 'white') + ';border-radius:14px;padding:14px 18px;margin-bottom:10px;border:1px solid ' + (isUnread ? '#ddd6fe' : '#e2e8f0') + ';border-left:4px solid #8b5cf6;cursor:pointer;" ';
                html += 'onclick="handleAdminNotifClick(' + n.id + ', \'' + escHtml(n.action_url) + '\')">';
                html += '<div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">';
                html += '<div style="flex:1;">';
                html += '<div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">';
                html += typeIcon;
                if (isUnread) html += '<span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#8b5cf6;flex-shrink:0;"></span>';
                html += '</div>';
                html += '<p style="margin:0;font-weight:' + (isUnread ? '800' : '700') + ';color:#1e293b;font-size:.9rem;">' + escHtml(n.title) + '</p>';
                html += '<p style="margin:2px 0 0;font-size:.8rem;color:#64748b;">' + escHtml(n.body) + '</p>';
                html += '<p style="margin:3px 0 0;font-size:.72rem;color:#94a3b8;">' + escHtml(n.created_at) + (n.created_fmt ? ' &bull; ' + escHtml(n.created_fmt) : '') + '</p>';
                html += '</div>';
                html += '<a href="' + escHtml(n.action_url) + '" onclick="event.stopPropagation();" ';
                html += 'style="display:inline-flex;align-items:center;gap:4px;background:linear-gradient(135deg,#8b5cf6,#6d28d9);color:white;border-radius:10px;padding:7px 14px;font-size:.75rem;font-weight:700;white-space:nowrap;text-decoration:none;flex-shrink:0;">View</a>';
                html += '</div></div>';
            });

            section.innerHTML = html;

            // Update footer count
            var footerEl = document.getElementById('adminNotifFooterCount');
            if (footerEl) {
                if (unread > 0) {
                    footerEl.textContent = unread + ' unread notification' + (unread !== 1 ? 's' : '');
                } else {
                    footerEl.textContent = notifs.length + ' notification' + (notifs.length !== 1 ? 's' : '');
                }
            }
        })
        .catch(function() {
            var section = document.getElementById('adminBellNotifSection');
            if (section) section.innerHTML = '';
        });
}

function handleAdminNotifClick(notifId, actionUrl) {
    // Mark as read
    fetch('/admin/notifications/' + notifId + '/read', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': _adminNotifCsrfToken, 'Content-Type': 'application/json' }
    }).catch(function(){});

    // Remove unread styling
    var el = document.getElementById('admin-notif-' + notifId);
    if (el) {
        el.style.background = 'white';
        el.style.borderColor = '#e2e8f0';
        var dot = el.querySelector('span[style*="border-radius:50%"]');
        if (dot) dot.remove();
    }

    window.location.href = actionUrl;
}

function adminNotifMarkAll() {
    fetch('/admin/notifications/read-all', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': _adminNotifCsrfToken, 'Content-Type': 'application/json' }
    }).then(function() {
        loadAdminBellNotifications();
        // Update bell badge
        var badge = document.querySelector('#hd .hd-bd, .hd-bd');
        if (badge) badge.style.display = 'none';
    }).catch(function(){});
}

function getAdminNotifIcon(type) {
    var icons = {
        'new_user_registration':  '<span style="background:#ede9fe;color:#7c3aed;border-radius:20px;padding:3px 10px;font-size:.72rem;font-weight:700;">👤 New User Registration</span>',
        'new_application':        '<span style="background:#fef3c7;color:#92400e;border-radius:20px;padding:3px 10px;font-size:.72rem;font-weight:700;">📋 New Application</span>',
        'new_aics_application':   '<span style="background:#fef3c7;color:#92400e;border-radius:20px;padding:3px 10px;font-size:.72rem;font-weight:700;">🏥 New AICS Application</span>',
        'new_renewal':            '<span style="background:#d1fae5;color:#065f46;border-radius:20px;padding:3px 10px;font-size:.72rem;font-weight:700;">🔄 New Renewal Request</span>',
        'new_appointment':        '<span style="background:#dbeafe;color:#1e40af;border-radius:20px;padding:3px 10px;font-size:.72rem;font-weight:700;">📅 New Appointment</span>',
        'reschedule_request':     '<span style="background:#fce7f3;color:#9d174d;border-radius:20px;padding:3px 10px;font-size:.72rem;font-weight:700;">📆 Reschedule Request</span>',
        'requirements_submitted': '<span style="background:#ecfdf5;color:#065f46;border-radius:20px;padding:3px 10px;font-size:.72rem;font-weight:700;">📁 Requirements Submitted</span>',
        'document_upload':        '<span style="background:#fee2e2;color:#991b1b;border-radius:20px;padding:3px 10px;font-size:.72rem;font-weight:700;">📁 Documents Uploaded</span>',
    };
    return icons[type] || '<span style="background:#f1f5f9;color:#475569;border-radius:20px;padding:3px 10px;font-size:.72rem;font-weight:700;">🔔 Notification</span>';
}

function escHtml(str) {
    if (!str) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Live bell badge polling (replaces existing admin.mark-notifications-viewed based badge) ──
function pollAdminNotifBadge() {
    fetch('{{ route('admin.notifications.count') }}')
        .then(function(r) { return r.json(); })
        .then(function(data) {
            var count = (data.count || 0);
            // Also add legacy counts from old mark-notifications-viewed flow
            var badge = document.querySelector('.hd-ic .hd-bd, .hd-bd');
            if (badge) {
                if (count > 0) {
                    badge.textContent = count > 9 ? '9+' : count;
                    badge.style.display = 'flex';
                } else {
                    // If no admin_notifications unread, the badge may still be set by Blade — keep it
                }
            }
        })
        .catch(function(){});
}
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() { pollAdminNotifBadge(); setInterval(pollAdminNotifBadge, 30000); });
} else {
    pollAdminNotifBadge();
    setInterval(pollAdminNotifBadge, 30000);
}

/* =============================================================
   WEB PUSH (VAPID) REGISTRATION FOR WINDOWS DESKTOP NOTIFICATIONS
   ============================================================= */
(function initAdminWebPush() {
    // Only for admin pages
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) return;

    var vapidPublicKey = '{{ config('services.vapid.public_key') }}';
    if (!vapidPublicKey) return; // VAPID not configured

    // Register the service worker
    navigator.serviceWorker.register('/sw-admin-push.js', { scope: '/' })
        .then(function(registration) {
            // Check current subscription state
            return registration.pushManager.getSubscription().then(function(existing) {
                if (existing) {
                    // Already subscribed — refresh on server (in case it changed)
                    return sendSubscriptionToServer(existing);
                }

                // Check if permission was explicitly denied — don't re-prompt
                if (Notification.permission === 'denied') return;

                // Ask for permission and subscribe
                return Notification.requestPermission().then(function(permission) {
                    if (permission !== 'granted') return;
                    return subscribeForPush(registration, vapidPublicKey);
                });
            });
        })
        .catch(function(e) {
            console.warn('[MSWDO Push] Service worker registration failed:', e);
        });
})();

function subscribeForPush(registration, vapidPublicKey) {
    var applicationServerKey = urlBase64ToUint8Array(vapidPublicKey);
    return registration.pushManager.subscribe({
        userVisibleOnly:      true,
        applicationServerKey: applicationServerKey,
    }).then(function(subscription) {
        return sendSubscriptionToServer(subscription);
    }).catch(function(e) {
        console.warn('[MSWDO Push] Push subscription failed:', e);
    });
}

function sendSubscriptionToServer(subscription) {
    var json = subscription.toJSON();
    return fetch('/admin/push/subscribe', {
        method:  'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': _adminNotifCsrfToken,
        },
        body: JSON.stringify({
            endpoint: json.endpoint,
            keys: {
                p256dh: json.keys.p256dh,
                auth:   json.keys.auth,
            },
        }),
    }).catch(function(){});
}

function urlBase64ToUint8Array(base64String) {
    var padding   = '='.repeat((4 - base64String.length % 4) % 4);
    var base64    = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    var rawData   = window.atob(base64);
    var outputArr = new Uint8Array(rawData.length);
    for (var i = 0; i < rawData.length; ++i) {
        outputArr[i] = rawData.charCodeAt(i);
    }
    return outputArr;
}
</script>
