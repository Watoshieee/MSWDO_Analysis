@php
    // Blade entries are intentionally empty — the modal is API-only.
    // All notifications come from admin_notifications via /admin/notifications.
    // Blade-side application/appointment lists used a separate data source
    // (pending Applications/Appointments records) that cannot be marked read
    // through the notification API, causing bell ↔ modal count mismatches.
    $bladeEntries = [];
@endphp

{{-- Admin Notification Modal --}}
<div id="adminNotifModal"
    style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:10000;align-items:center;justify-content:center;"
    onclick="if(event.target===this) closeAdminNotifModal()">
    <div style="background:white;width:90%;max-width:920px;border-radius:20px;display:flex;flex-direction:column;box-shadow:0 10px 40px rgba(0,0,0,0.15);animation:fadeInScale .25s ease;max-height:88vh;overflow:hidden;">

        {{-- Header --}}
        <div style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;padding:22px 28px 18px;border-radius:20px 20px 0 0;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;">
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

        {{-- Tab Bar --}}
        <div id="adminNotifTabBar" style="background:#f0f2fa;border-bottom:1px solid #e2e8f0;display:flex;gap:0;flex-shrink:0;">
            <button type="button" class="an-tab active" data-tab="all" onclick="switchAdminNotifTab('all')"
                style="flex:1;padding:11px 0;font-size:0.82rem;font-weight:700;border:none;background:transparent;cursor:pointer;border-bottom:3px solid #2C3E8F;color:#2C3E8F;">
                All
                <span id="anTabBadge-all" style="display:inline-block;margin-left:5px;background:#2C3E8F;color:#fff;border-radius:99px;font-size:0.68rem;font-weight:800;padding:1px 7px;vertical-align:middle;"></span>
            </button>
            <button type="button" class="an-tab" data-tab="registrations" onclick="switchAdminNotifTab('registrations')"
                style="flex:1;padding:11px 0;font-size:0.82rem;font-weight:700;border:none;background:transparent;cursor:pointer;border-bottom:3px solid transparent;color:#64748b;">
                Registrations
                <span id="anTabBadge-registrations" style="display:none;margin-left:5px;background:#8b5cf6;color:#fff;border-radius:99px;font-size:0.68rem;font-weight:800;padding:1px 7px;vertical-align:middle;"></span>
            </button>
            <button type="button" class="an-tab" data-tab="applications" onclick="switchAdminNotifTab('applications')"
                style="flex:1;padding:11px 0;font-size:0.82rem;font-weight:700;border:none;background:transparent;cursor:pointer;border-bottom:3px solid transparent;color:#64748b;">
                Applications
                <span id="anTabBadge-applications" style="display:none;margin-left:5px;background:#f59e0b;color:#fff;border-radius:99px;font-size:0.68rem;font-weight:800;padding:1px 7px;vertical-align:middle;"></span>
            </button>
        </div>

        {{-- Scrollable List --}}
        <div id="adminNotifBody" style="padding:18px 28px;overflow-y:auto;background:#fafbfc;flex:1;min-height:0;">
            <div id="adminNotifList">
                <div style="text-align:center;padding:20px;color:#94a3b8;font-size:0.84rem;">
                    <span>Loading notifications…</span>
                </div>
            </div>
            {{-- Empty state --}}
            <div id="adminNotifEmptyState" style="display:none;text-align:center;padding:60px 20px;">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin:0 auto 12px;display:block;">
                    <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.7 21a2 2 0 0 1-3.4 0"/>
                </svg>
                <h6 style="font-weight:700;color:#64748b;margin-bottom:6px;">No notifications</h6>
                <p style="font-size:.85rem;color:#94a3b8;margin:0;">All caught up! New activity will appear here.</p>
            </div>
        </div>

        {{-- Pagination --}}
        <div id="adminNotifPagination" style="display:none;padding:8px 28px;background:#f8f9fa;border-top:1px solid #e9ecef;flex-shrink:0;display:flex;align-items:center;justify-content:space-between;gap:8px;">
            <span id="anPageInfo" style="font-size:0.78rem;color:#64748b;"></span>
            <div style="display:flex;align-items:center;gap:4px;" id="anPageBtns"></div>
        </div>

        {{-- Footer --}}
        <div style="background:#f8f9fa;padding:14px 28px;display:flex;justify-content:space-between;align-items:center;border-radius:0 0 20px 20px;border-top:1px solid #e9ecef;flex-shrink:0;">
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
.admin-bell-item.type-new_application,
.admin-bell-item.type-new_aics_application { border-left-color: #f59e0b !important; }
.admin-bell-item.type-new_appointment { border-left-color: #3b82f6 !important; }
.admin-bell-item.type-new_renewal     { border-left-color: #10b981 !important; }
.admin-bell-item.type-document_upload { border-left-color: #dc2626 !important; }

.an-tab { transition: color .15s, border-color .15s; }
.an-tab:hover { color: #2C3E8F !important; background: rgba(44,62,143,.05) !important; }
.an-tab.active { color: #2C3E8F !important; border-bottom-color: #2C3E8F !important; }

.an-pg-btn {
    min-width: 30px; height: 30px; padding: 0 8px;
    border-radius: 7px; border: 1px solid #e2e8f0;
    background: #fff; color: #1e293b; font-size: 0.78rem; font-weight: 700;
    cursor: pointer; transition: all .15s;
}
.an-pg-btn:hover { background: #2C3E8F; color: #fff; border-color: #2C3E8F; }
.an-pg-btn.active { background: #2C3E8F; color: #fff; border-color: #2C3E8F; }
.an-pg-btn:disabled { opacity: .4; cursor: default; }

@keyframes fadeInScale {
    from { transform: scale(.95); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}
</style>

<script>
/* =============================================================
   ADMIN NOTIFICATION MODAL — tabs, pagination, bell API
   ============================================================= */

var _adminNotifCsrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

// All notifications merged from API + blade
var _allNotifs    = [];
var _anLoading    = false;   // prevents concurrent fetches

// Tab & pagination state
var _anTab  = 'all';   // 'all' | 'registrations' | 'applications'
var _anPage = { all: 1, registrations: 1, applications: 1 };
var _anPerPage = 10;

// Registration types
var _regTypes = ['new_user_registration'];
// Application types (everything else)
var _appTypes = [
    'new_application', 'new_aics_application', 'new_renewal',
    'new_appointment', 'reschedule_request', 'requirements_submitted',
    'app_status_change', 'id_verification', 'document_upload'
];

// ── Modal open/close ──────────────────────────────────────────
function openAdminNotifModal() {
    document.getElementById('adminNotifModal').style.display = 'flex';
    loadAdminBellNotifications();
}
function closeAdminNotifModal() {
    document.getElementById('adminNotifModal').style.display = 'none';
}

// ── Tab switching ─────────────────────────────────────────────
function switchAdminNotifTab(tab) {
    _anTab = tab;
    document.querySelectorAll('.an-tab').forEach(function(btn) {
        var isActive = btn.dataset.tab === tab;
        btn.classList.toggle('active', isActive);
        btn.style.borderBottomColor = isActive ? '#2C3E8F' : 'transparent';
        btn.style.color = isActive ? '#2C3E8F' : '#64748b';
    });
    renderAdminNotifList();
}

// ── Filter by tab ─────────────────────────────────────────────
function filterByTab(notifs, tab) {
    if (tab === 'registrations') return notifs.filter(function(n){ return _regTypes.indexOf(n.type) !== -1; });
    if (tab === 'applications')  return notifs.filter(function(n){ return _appTypes.indexOf(n.type) !== -1; });
    return notifs;
}

// ── Render list for current tab + page ───────────────────────
function renderAdminNotifList() {
    var listEl   = document.getElementById('adminNotifList');
    var emptyEl  = document.getElementById('adminNotifEmptyState');
    var pgEl     = document.getElementById('adminNotifPagination');
    var footerEl = document.getElementById('adminNotifFooterCount');

    var filtered = filterByTab(_allNotifs, _anTab);
    var total    = filtered.length;
    var page     = _anPage[_anTab];
    var pages    = Math.max(1, Math.ceil(total / _anPerPage));
    if (page > pages) { _anPage[_anTab] = page = pages; }

    var start = (page - 1) * _anPerPage;
    var slice = filtered.slice(start, start + _anPerPage);

    // Empty state
    if (total === 0) {
        listEl.innerHTML = '';
        emptyEl.style.display = 'block';
        pgEl.style.display = 'none';
        if (footerEl) footerEl.textContent = '0 notifications';
        return;
    }
    emptyEl.style.display = 'none';

    // Build cards
    var html = '';
    slice.forEach(function(n) {
        var typeIcon = getAdminNotifIcon(n.type);
        var isUnread = !n.is_read;
        var cardBg   = isUnread ? '#f5f3ff' : 'white';
        var cardBdr  = isUnread ? '#ddd6fe' : '#e2e8f0';
        var isBlade  = !!n.blade;

        html += '<div class="admin-notif-card admin-bell-item type-' + n.type + '" ';
        if (!isBlade) {
            html += 'id="admin-notif-' + n.id + '" ';
            html += 'style="background:' + cardBg + ';border-radius:14px;padding:14px 18px;margin-bottom:10px;border:1px solid ' + cardBdr + ';border-left:4px solid #8b5cf6;cursor:pointer;" ';
            html += 'onclick="handleAdminNotifClick(' + n.id + ', \'' + escHtml(n.action_url) + '\')">';
        } else {
            html += 'style="background:' + cardBg + ';border-radius:14px;padding:14px 18px;margin-bottom:10px;border:1px solid ' + cardBdr + ';border-left:4px solid #8b5cf6;">';
        }

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

        var btnLabel = n.action_label || 'View';
        var btnStyle = isBlade && n.type === 'document_upload'
            ? 'background:linear-gradient(135deg,#dc2626,#b91c1c);'
            : 'background:linear-gradient(135deg,#8b5cf6,#6d28d9);';
        html += '<a href="' + escHtml(n.action_url) + '" onclick="event.stopPropagation();" ';
        html += 'style="display:inline-flex;align-items:center;gap:4px;' + btnStyle + 'color:white;border-radius:10px;padding:7px 14px;font-size:.75rem;font-weight:700;white-space:nowrap;text-decoration:none;flex-shrink:0;">' + escHtml(btnLabel) + '</a>';
        html += '</div></div>';
    });

    listEl.innerHTML = html;

    // Footer count
    var unread = _allNotifs.filter(function(n){ return !n.is_read; }).length;
    if (footerEl) {
        footerEl.textContent = unread > 0
            ? unread + ' unread notification' + (unread !== 1 ? 's' : '')
            : _allNotifs.length + ' notification' + (_allNotifs.length !== 1 ? 's' : '');
    }

    // Pagination
    renderAdminNotifPagination(total, page, pages);
}

// ── Render pagination controls ────────────────────────────────
function renderAdminNotifPagination(total, page, pages) {
    var pgEl     = document.getElementById('adminNotifPagination');
    var infoEl   = document.getElementById('anPageInfo');
    var btnsEl   = document.getElementById('anPageBtns');

    if (total <= _anPerPage) {
        pgEl.style.display = 'none';
        return;
    }

    pgEl.style.display = 'flex';
    var start = (page - 1) * _anPerPage + 1;
    var end   = Math.min(page * _anPerPage, total);
    infoEl.textContent = start + '–' + end + ' of ' + total;

    var html = '';
    // Prev
    html += '<button class="an-pg-btn" onclick="goAdminNotifPage(' + (page - 1) + ')"' + (page <= 1 ? ' disabled' : '') + '>&#8249;</button>';

    // Page numbers (show up to 5 around current)
    var lo = Math.max(1, page - 2);
    var hi = Math.min(pages, page + 2);
    if (lo > 1) html += '<button class="an-pg-btn" onclick="goAdminNotifPage(1)">1</button>' + (lo > 2 ? '<span style="padding:0 4px;color:#94a3b8;font-size:.78rem;">…</span>' : '');
    for (var p = lo; p <= hi; p++) {
        html += '<button class="an-pg-btn' + (p === page ? ' active' : '') + '" onclick="goAdminNotifPage(' + p + ')">' + p + '</button>';
    }
    if (hi < pages) html += (hi < pages - 1 ? '<span style="padding:0 4px;color:#94a3b8;font-size:.78rem;">…</span>' : '') + '<button class="an-pg-btn" onclick="goAdminNotifPage(' + pages + ')">' + pages + '</button>';

    // Next
    html += '<button class="an-pg-btn" onclick="goAdminNotifPage(' + (page + 1) + ')"' + (page >= pages ? ' disabled' : '') + '>&#8250;</button>';

    btnsEl.innerHTML = html;
}

function goAdminNotifPage(p) {
    _anPage[_anTab] = p;
    renderAdminNotifList();
    // Scroll list to top
    var body = document.getElementById('adminNotifBody');
    if (body) body.scrollTop = 0;
}

// ── Update tab badges ─────────────────────────────────────────
function updateAdminNotifTabBadges() {
    var tabs = { all: 0, registrations: 0, applications: 0 };
    _allNotifs.forEach(function(n) {
        if (n.is_read) return;
        if (_regTypes.indexOf(n.type) !== -1) tabs.registrations++;
        else if (_appTypes.indexOf(n.type) !== -1) tabs.applications++;
        tabs.all++;
    });
    ['all', 'registrations', 'applications'].forEach(function(tab) {
        var el = document.getElementById('anTabBadge-' + tab);
        if (!el) return;
        var count = tabs[tab];
        if (count > 0) {
            el.textContent = count > 99 ? '99+' : count;
            el.style.display = 'inline-block';
        } else {
            el.style.display = 'none';
        }
    });
}

// ── Update the navbar bell badge from a known count ──────────
function setAdminBellBadge(count) {
    var badge = document.getElementById('adminNotifBadge') || document.querySelector('.hd-ic .hd-bd');
    if (!badge) return;
    if (count > 0) {
        badge.textContent = count > 9 ? '9+' : count;
        badge.style.display = 'flex';
    } else {
        badge.style.display = 'none';
    }
}

// ── Sort helper: unread newest-first, then read newest-first ─
function sortNotifs(notifs) {
    return notifs.slice().sort(function(a, b) {
        // Primary: unread before read
        if (!a.is_read && b.is_read) return -1;
        if (a.is_read && !b.is_read) return  1;
        // Secondary: newer created_ts first (ISO string or epoch ms stored on load)
        return (b._ts || 0) - (a._ts || 0);
    });
}

// ── Load bell notifications from API ─────────────────────────
function loadAdminBellNotifications() {
    if (_anLoading) return;
    _anLoading = true;

    fetch('{{ route('admin.notifications.index') }}')
        .then(function(r){ return r.json(); })
        .then(function(data) {
            _allNotifs = (data.notifications || []).map(function(n) {
                n.blade = false;
                n.action_label = n.action_label || 'View';
                // Store a numeric timestamp for reliable sorting
                n._ts = n.created_fmt
                    ? (new Date(n.created_fmt)).getTime() || 0
                    : 0;
                return n;
            });
            _allNotifs = sortNotifs(_allNotifs);

            // Use the authoritative DB count for the bell badge
            // (unread_count in the index response covers only the 30-item page;
            //  the /count endpoint always queries the full table)
            setAdminBellBadge(data.unread_count || 0);
            updateAdminNotifTabBadges();
            renderAdminNotifList();
        })
        .catch(function() {
            _allNotifs = [];
            updateAdminNotifTabBadges();
            renderAdminNotifList();
        })
        .finally(function() {
            _anLoading = false;
        });
}

// ── Notification click (mark read + navigate) ─────────────────
function handleAdminNotifClick(notifId, actionUrl) {
    var n = _allNotifs.find(function(x){ return x.id === notifId; });

    if (!n || n.is_read) {
        // Already read — navigate immediately
        window.location.href = actionUrl;
        return;
    }

    // Optimistically update UI before the network round-trip
    n.is_read = true;
    _allNotifs = sortNotifs(_allNotifs);
    var remaining = _allNotifs.filter(function(x){ return !x.is_read; }).length;
    setAdminBellBadge(remaining);
    updateAdminNotifTabBadges();
    renderAdminNotifList();

    // Persist to DB, then navigate regardless of outcome
    fetch('/admin/notifications/' + notifId + '/read', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': _adminNotifCsrfToken, 'Content-Type': 'application/json' }
    })
    .catch(function() {
        // Revert optimistic update on network failure so state stays honest
        n.is_read = false;
        _allNotifs = sortNotifs(_allNotifs);
        setAdminBellBadge(_allNotifs.filter(function(x){ return !x.is_read; }).length);
        updateAdminNotifTabBadges();
    })
    .finally(function() {
        window.location.href = actionUrl;
    });
}

// ── Mark all read ─────────────────────────────────────────────
function adminNotifMarkAll() {
    fetch('/admin/notifications/read-all', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': _adminNotifCsrfToken, 'Content-Type': 'application/json' }
    }).then(function() {
        _allNotifs.forEach(function(n){ n.is_read = true; });
        _allNotifs = sortNotifs(_allNotifs);
        updateAdminNotifTabBadges();
        renderAdminNotifList();
        setAdminBellBadge(0);
    }).catch(function(){});
}

// ── Icon helper ───────────────────────────────────────────────
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

// ── Bell badge polling ────────────────────────────────────────
// Always sets the badge from the authoritative DB count (replaces any
// stale Blade-rendered value that may have been computed server-side).
function pollAdminNotifBadge() {
    fetch('{{ route('admin.notifications.count') }}')
        .then(function(r) { return r.json(); })
        .then(function(data) { setAdminBellBadge(data.count || 0); })
        .catch(function(){});
}
(function initBadge() {
    // Apply the PHP-inlined count immediately (zero network cost)
    if (typeof window._adminNotifInitCount === 'number') {
        setAdminBellBadge(window._adminNotifInitCount);
        window._adminNotifInitCount = undefined;
    }
    // Poll every 30 s for live updates
    setInterval(pollAdminNotifBadge, 30000);
})();

/* =============================================================
   WEB PUSH (VAPID) REGISTRATION
   ============================================================= */
(function initAdminWebPush() {
    if (!('serviceWorker' in navigator) || !('PushManager' in window)) return;
    var vapidPublicKey = '{{ config('services.vapid.public_key') }}';
    if (!vapidPublicKey) return;
    navigator.serviceWorker.register('/sw-admin-push.js', { scope: '/' })
        .then(function(registration) {
            return registration.pushManager.getSubscription().then(function(existing) {
                if (existing) return sendSubscriptionToServer(existing);
                if (Notification.permission === 'denied') return;
                return Notification.requestPermission().then(function(permission) {
                    if (permission !== 'granted') return;
                    return subscribeForPush(registration, vapidPublicKey);
                });
            });
        })
        .catch(function(e) { console.warn('[MSWDO Push] SW registration failed:', e); });
})();

function subscribeForPush(registration, vapidPublicKey) {
    return registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(vapidPublicKey),
    }).then(function(subscription) {
        return sendSubscriptionToServer(subscription);
    }).catch(function(e) { console.warn('[MSWDO Push] Push subscription failed:', e); });
}

function sendSubscriptionToServer(subscription) {
    var json = subscription.toJSON();
    return fetch('/admin/push/subscribe', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': _adminNotifCsrfToken },
        body: JSON.stringify({ endpoint: json.endpoint, keys: { p256dh: json.keys.p256dh, auth: json.keys.auth } }),
    }).catch(function(){});
}

function urlBase64ToUint8Array(base64String) {
    var padding = '='.repeat((4 - base64String.length % 4) % 4);
    var base64  = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
    var rawData = window.atob(base64);
    var output  = new Uint8Array(rawData.length);
    for (var i = 0; i < rawData.length; ++i) output[i] = rawData.charCodeAt(i);
    return output;
}
</script>
