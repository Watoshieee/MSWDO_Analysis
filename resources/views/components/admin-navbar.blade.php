@php
    $currentActive = $active ?? '';

    if (empty($currentActive)) {
        if (request()->routeIs('admin.dashboard') || request()->is('admin/dashboard') || request()->is('admin')) {
            $currentActive = 'dashboard';
        } elseif (
            request()->routeIs('admin.requirements*') ||
            request()->routeIs('admin.view-requirement*') ||
            request()->routeIs('admin.applications*') ||
            request()->routeIs('admin.appointments*') ||
            request()->routeIs('admin.solo-parent-requirements*') ||
            request()->is('admin/requirements*') ||
            request()->is('admin/applications*') ||
            request()->is('admin/appointments*') ||
            request()->is('admin/solo-parent-requirements*')
        ) {
            $currentActive = 'applications';
        } elseif (request()->routeIs('admin.users*') || request()->is('admin/users*')) {
            $currentActive = 'users';
        } elseif (request()->routeIs('admin.data*') || request()->is('admin/data*')) {
            $currentActive = 'data';
        } elseif (request()->routeIs('admin.announcements*') || request()->is('admin/announcements*')) {
            $currentActive = 'announcements';
        } elseif (
            request()->routeIs('admin.detailed-analysis*') ||
            request()->routeIs('admin.yearly*') ||
            request()->routeIs('admin.analysis*') ||
            request()->is('admin/detailed-analysis*') ||
            request()->is('admin/yearly*') ||
            request()->is('admin/analysis*')
        ) {
            $currentActive = 'analysis';
        }
    }
@endphp

<style>
/* =====================================================================
   MSWDO ADMIN HEADER & NAVIGATION (from admin-dashboard-fixnavbardesign.html)
   Responsive for Desktop (>1200px), Tablet, and Mobile (<520px)
   ===================================================================== */
:root {
    --hd-navy: {{ $adminPrimaryColor ?? '#1a2470' }};
    --hd-navy-d: #141b4d;
    --hd-gold: {{ $adminSecondaryColor ?? '#f5b82e' }};
    --hd-gold-h: #ffc94d;
}

html, body {
    margin: 0;
    padding: 0;
}
.hd-extras {
    position: absolute !important;
    left: 0;
    top: 0;
    width: 0 !important;
    height: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
    overflow: visible !important;
    border: 0;
    pointer-events: none;
    flex: none;
}
.hd-extras .cx-panel.on,
.hd-extras .cx-scrim.on,
.hd-extras #globalConfirmModal {
    pointer-events: auto;
}
.hd {
    position: sticky;
    top: 0;
    z-index: 1000;
    margin: 0;
    padding: 0;
    flex: none;
    align-self: stretch;
    background: linear-gradient(90deg, var(--hd-navy), #243088);
    color: #fff;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    transition: box-shadow 0.2s ease, background 0.2s ease;
    width: 100%;
}
.hd.stuck {
    box-shadow: 0 6px 18px rgba(20, 27, 77, 0.28);
}
.hd-in {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 32px;
    height: 60px;
    box-sizing: border-box;
    display: flex;
    align-items: center;
    gap: 8px;
    position: relative;
}
.hd a:focus-visible, .hd button:focus-visible {
    outline: 2px solid var(--hd-gold);
    outline-offset: 2px;
}
.hd-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: none;
    color: #fff !important;
    font-size: 16px;
    font-weight: 800;
    white-space: nowrap;
    text-decoration: none !important;
}
.hd-logo {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: #fff;
    display: grid;
    place-items: center;
    flex: none;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0,0,0,0.18);
}
.hd-logo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.hd-city {
    font-weight: 600;
    opacity: 0.92;
    color: #e0e6ff;
}
.hd-menu {
    display: none;
    place-items: center;
    width: 40px;
    height: 40px;
    margin-left: -6px;
    border-radius: 10px;
    border: 0;
    padding: 0;
    background: transparent;
    color: #fff;
    cursor: pointer;
    transition: background 0.15s ease;
}
.hd-menu:hover {
    background: rgba(255, 255, 255, 0.14);
}

/* Navigation container */
.nv {
    display: flex;
    align-items: center;
    min-width: 0;
}
.nv-head, .nv-foot, .nv-scrim {
    display: none;
}
.nv-list {
    display: flex;
    gap: 2px;
    list-style: none;
    margin: 0 0 0 20px;
    padding: 0;
}
.nv-list li {
    margin: 0;
    padding: 0;
}
.nv-list a {
    display: flex;
    align-items: center;
    gap: 8px;
    height: 40px;
    padding: 0 12px;
    border-radius: 10px;
    color: #e6eaff;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
    text-decoration: none !important;
    transition: all 0.15s ease;
}
.nv-list a:hover {
    background: rgba(255, 255, 255, 0.12);
    color: #fff;
}
.nv-list a[aria-current="page"],
.nv-list a.active,
.nv-list a[aria-current="page"]:hover,
.nv-list a.active:hover {
    background: var(--hd-gold) !important;
    color: #172066 !important;
    font-weight: 700 !important;
}
.nv-list a[aria-current="page"] svg,
.nv-list a.active svg {
    color: #172066 !important;
    stroke: currentColor !important;
}
.nv-count {
    min-width: 20px;
    height: 20px;
    box-sizing: border-box;
    padding: 0 6px;
    border-radius: 99px;
    background: rgba(255, 255, 255, 0.18);
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    line-height: 20px;
    text-align: center;
}
.nv-list a[aria-current="page"] .nv-count,
.nv-list a.active .nv-count {
    background: #172066 !important;
    color: #fff !important;
}

/* Header Action Controls */
.hd-act {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 8px;
}
.hd-ic, .hd .cx-hdr {
    position: relative;
    display: grid;
    place-items: center;
    width: 38px;
    height: 38px;
    border-radius: 99px;
    border: 0;
    padding: 0;
    background: rgba(255, 255, 255, 0.12);
    color: #fff;
    cursor: pointer;
    text-decoration: none !important;
    transition: background 0.15s ease;
}
.hd-ic:hover, .hd .cx-hdr:hover {
    background: rgba(255, 255, 255, 0.22);
    color: #fff;
}
.hd-bd {
    position: absolute;
    top: -3px;
    right: -3px;
    min-width: 16px;
    height: 16px;
    box-sizing: border-box;
    padding: 0 4px;
    border-radius: 99px;
    background: #d93a3a;
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    line-height: 16px;
    text-align: center;
    border: 2px solid #1f2b7c;
}
.hd .cx-badge {
    position: absolute;
    top: -3px;
    right: -3px;
    min-width: 16px;
    height: 16px;
    box-sizing: border-box;
    padding: 0 4px;
    border-radius: 99px;
    background: var(--hd-gold);
    color: #172066;
    font-size: 10px;
    font-weight: 800;
    line-height: 16px;
    text-align: center;
    border: 2px solid #1f2b7c;
}

/* Account Profile Dropdown (Desktop) */
.pm {
    position: relative;
}
.pm-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    height: 38px;
    padding: 3px 9px 3px 3px;
    border-radius: 99px;
    border: 0;
    background: rgba(255, 255, 255, 0.12);
    color: #fff;
    cursor: pointer;
    transition: background 0.15s ease;
}
.pm-btn:hover {
    background: rgba(255, 255, 255, 0.22);
}
.pm-btn svg {
    transition: transform 0.15s ease;
}
.pm-btn[aria-expanded="true"] svg {
    transform: rotate(180deg);
}
.pm-av {
    display: grid;
    place-items: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--hd-gold);
    color: #172066;
    font-size: 12px;
    font-weight: 800;
    flex: none;
    text-transform: uppercase;
}
.pm-menu {
    position: absolute;
    right: 0;
    top: calc(100% + 10px);
    width: 248px;
    background: #fff;
    color: #1b2559;
    border: 1px solid #e1e6f2;
    border-radius: 12px;
    box-shadow: 0 12px 28px rgba(20, 27, 77, 0.25);
    padding: 6px;
    z-index: 1010;
}
.pm-menu[hidden] {
    display: none;
}
.pm-who {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px;
    margin-bottom: 4px;
    border-bottom: 1px solid #eef1f8;
}
.pm-who b {
    display: block;
    font-size: 13px;
    color: #1b2559;
}
.pm-who > div > span {
    display: block;
    font-size: 11.5px;
    color: #4d5878;
    margin-top: 1px;
}
.pm-menu a, .nv-foot a {
    display: flex;
    align-items: center;
    gap: 10px;
    height: 42px;
    padding: 0 10px;
    border-radius: 8px;
    color: #1b2559;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none !important;
    transition: background 0.15s ease;
}
.pm-menu a:hover, .nv-foot a:hover {
    background: #f4f6fb;
    text-decoration: none;
}
.pm-menu a.out, .nv-foot a.out, .pm-menu button.out, .nv-foot button.out {
    color: #8f1d1d;
}
.pm-menu a:focus-visible, .nv-foot a:focus-visible {
    outline-color: #1e2a78;
}

/* Responsive Breakpoints: Tablet & Mobile (<1200px) */
@media (max-width: 1199px) {
    .hd-in {
        padding: 0 16px;
    }
    .hd-menu {
        display: grid;
    }
    .hd-pm {
        display: none;
    }
    .hd-ic, .hd .cx-hdr {
        width: 40px;
        height: 40px;
    }
    .nv {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 10460;
        width: min(320px, 86vw);
        flex-direction: column;
        align-items: stretch;
        background: #fff;
        color: #1b2559;
        overflow-y: auto;
        overscroll-behavior: contain;
        box-shadow: 12px 0 32px rgba(20, 27, 77, 0.25);
        transform: translateX(-100%);
        visibility: hidden;
        transition: transform 0.26s cubic-bezier(0.2, 0.8, 0.2, 1), visibility 0s linear 0.26s;
    }
    .nv.on {
        transform: none;
        visibility: visible;
        transition: transform 0.26s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    .nv a:focus-visible, .nv button:focus-visible {
        outline-color: #1e2a78;
    }
    .nv-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex: none;
        min-height: 60px;
        box-sizing: border-box;
        padding: env(safe-area-inset-top, 0px) 10px 0 16px;
        background: linear-gradient(135deg, var(--hd-navy), #243088);
        color: #fff;
    }
    .nv-head .hd-menu {
        display: grid;
        margin: 0;
    }
    .nv-list {
        flex: 1;
        flex-direction: column;
        margin: 0;
        padding: 10px;
    }
    .nv-list a {
        height: 48px;
        font-size: 14px;
        color: #1b2559;
    }
    .nv-list a:hover {
        background: #f4f6fb;
        color: #1b2559;
    }
    .nv-list a[aria-current="page"],
    .nv-list a.active,
    .nv-list a[aria-current="page"]:hover,
    .nv-list a.active:hover {
        background: var(--hd-gold) !important;
        color: #172066 !important;
        font-weight: 700 !important;
    }
    .nv-list a[aria-current="page"] svg,
    .nv-list a.active svg {
        color: #172066 !important;
        stroke: currentColor !important;
    }
    .nv-count {
        margin-left: auto;
        background: #eef1fb;
        color: #1e2a78;
    }
    .nv-foot {
        display: block;
        flex: none;
        padding: 8px 10px calc(10px + env(safe-area-inset-bottom, 0px));
        border-top: 1px solid #eef1f8;
        background: #fff;
    }
    .nv-scrim {
        display: block;
        position: fixed;
        inset: 0;
        z-index: 10455;
        background: rgba(20, 27, 77, 0.48);
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.2s ease, visibility 0s linear 0.2s;
    }
    .nv-scrim.on {
        opacity: 1;
        visibility: visible;
        transition: opacity 0.2s ease;
    }
}

/* Mobile Screen <= 520px */
@media (max-width: 520px) {
    .hd-city {
        display: none;
    }
}

@media (prefers-reduced-motion: reduce) {
    .hd, .nv, .nv.on, .nv-scrim, .nv-scrim.on, .pm-btn svg {
        transition: none !important;
    }
}
</style>

<header class="hd" id="hd">
    <div class="hd-in">
        <!-- Mobile Menu Toggle Button (<=1199px) -->
        <button class="hd-menu" id="nvToggle" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="nv">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>

        <!-- Brand / Identity -->
        <a class="hd-brand" href="{{ route('admin.dashboard') }}" aria-label="MSWDO {{ Auth::user()->municipality ?? 'Municipal' }}, go to dashboard">
            <span class="hd-logo">
                <img src="{{ asset('images/logo_mswdo.jpg') }}" alt="MSWDO" style="width:100%;height:100%;border-radius:6px;object-fit:cover;">
            </span>
            <span>MSWDO<span class="hd-city"> &bull; {{ Auth::user()->municipality ?? 'Municipal' }}</span></span>
        </a>

        <!-- Navigation bar (Horizontal on desktop, Slide-in drawer on tablet/mobile) -->
        <nav class="nv" id="nv" aria-label="Main">
            <div class="nv-head">
                <span class="hd-brand">
                    <span class="hd-logo">
                        <img src="{{ asset('images/logo_mswdo.jpg') }}" alt="MSWDO" style="width:100%;height:100%;border-radius:6px;object-fit:cover;">
                    </span>
                    <span>MSWDO<span class="hd-city"> &bull; {{ Auth::user()->municipality ?? 'Municipal' }}</span></span>
                </span>
                <button class="hd-menu" id="nvClose" type="button" aria-label="Close menu">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            <ul class="nv-list">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ $currentActive === 'dashboard' ? 'active' : '' }}" {!! $currentActive === 'dashboard' ? 'aria-current="page"' : '' !!}>
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.requirements') }}" class="{{ $currentActive === 'applications' ? 'active' : '' }}" {!! $currentActive === 'applications' ? 'aria-current="page"' : '' !!}>
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="16" y2="17"/></svg>
                        <span>Applications</span>
                        @php
                            $newAppsCount = 0;
                            if (isset($adminNewApplications)) {
                                $newAppsCount = is_countable($adminNewApplications) ? count($adminNewApplications) : (int)$adminNewApplications;
                            }
                        @endphp
                        @if($newAppsCount > 0)
                        <span class="nv-count" aria-label="{{ $newAppsCount }} pending">{{ $newAppsCount }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users') }}" class="{{ $currentActive === 'users' ? 'active' : '' }}" {!! $currentActive === 'users' ? 'aria-current="page"' : '' !!}>
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span>Users</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.data.dashboard') }}" class="{{ $currentActive === 'data' ? 'active' : '' }}" {!! $currentActive === 'data' ? 'aria-current="page"' : '' !!}>
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.7 4 3 9 3s9-1.3 9-3V5"/><path d="M3 12c0 1.7 4 3 9 3s9-1.3 9-3"/></svg>
                        <span>Data Management</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.announcements.index') }}" class="{{ $currentActive === 'announcements' ? 'active' : '' }}" {!! $currentActive === 'announcements' ? 'aria-current="page"' : '' !!}>
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 11v2a1 1 0 0 0 1 1h2l5 4V6L6 10H4a1 1 0 0 0-1 1z"/><path d="M15.5 8.5a5 5 0 0 1 0 7"/></svg>
                        <span>Announcements</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.detailed-analysis') }}" class="{{ $currentActive === 'analysis' ? 'active' : '' }}" {!! $currentActive === 'analysis' ? 'aria-current="page"' : '' !!}>
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg>
                        <span>Analysis</span>
                    </a>
                </li>
            </ul>

            @auth
            <!-- Drawer Footer (Tablet & Mobile only) -->
            <div class="nv-foot">
                <div class="pm-who">
                    <span class="pm-av">{{ strtoupper(substr(Auth::user()->full_name ?? 'A', 0, 1)) }}</span>
                    <div>
                        <b>{{ Auth::user()->full_name }}</b>
                        <span>{{ ucfirst(Auth::user()->role ?? 'Admin') }} &bull; {{ Auth::user()->municipality ?? '' }}</span>
                    </div>
                </div>
                <a href="{{ route('admin.settings') }}">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/></svg>
                    <span>Settings</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="logout-link out" style="background:none;border:none;width:100%;text-align:left;display:flex;align-items:center;gap:10px;height:42px;padding:0 10px;border-radius:8px;color:#8f1d1d;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        <span>Log out</span>
                    </button>
                </form>
            </div>
            @endauth
        </nav>

        <!-- Right Side Header Controls -->
        <div class="hd-act">
            @auth
            <!-- Chat Trigger with Unread Badge -->
            <button class="cx-hdr" id="adminNavbarChatBtn" type="button" onclick="if(typeof openChatDrawer==='function'){openChatDrawer();}else if(typeof openChatModal==='function'){openChatModal();}" aria-label="Open messages" title="Messages" aria-haspopup="dialog">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                <span class="cx-badge" id="cxHdrBadge" style="display:none;">0</span>
            </button>

            <!-- Notification Bell with Count Badge -->
            <button type="button" class="hd-ic" onclick="if(typeof openAdminNotifModal==='function'){openAdminNotifModal();}else{window.location.href='{{ route('admin.requirements') }}';}" aria-label="Application Notifications" title="Application Notifications">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></svg>
                @if(isset($adminNotifCount) && $adminNotifCount > 0)
                <span class="hd-bd">{{ $adminNotifCount > 9 ? '9+' : $adminNotifCount }}</span>
                @endif
            </button>

            <!-- Profile Menu Dropdown (Desktop >=1200px) -->
            <div class="pm hd-pm" id="pm">
                <button class="pm-btn" id="pmBtn" type="button" aria-label="Account menu" aria-expanded="false" aria-controls="pmMenu">
                    <span class="pm-av">{{ strtoupper(substr(Auth::user()->full_name ?? 'A', 0, 1)) }}</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="pm-menu" id="pmMenu" hidden>
                    <div class="pm-who">
                        <span class="pm-av">{{ strtoupper(substr(Auth::user()->full_name ?? 'A', 0, 1)) }}</span>
                        <div>
                            <b>{{ Auth::user()->full_name }}</b>
                            <span>{{ ucfirst(Auth::user()->role ?? 'Admin') }} &bull; {{ Auth::user()->municipality ?? '' }}</span>
                        </div>
                    </div>
                    <a href="{{ route('admin.settings') }}">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/></svg>
                        <span>Settings</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button type="submit" class="logout-link out" style="background:none;border:none;width:100%;text-align:left;display:flex;align-items:center;gap:10px;height:42px;padding:0 10px;border-radius:8px;color:#8f1d1d;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit;">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            <span>Log out</span>
                        </button>
                    </form>
                </div>
            </div>
            @endauth
        </div>

        <!-- Scrim Backdrop for Mobile Drawer -->
        <div class="nv-scrim" id="nvScrim"></div>
    </div>
</header>

<div class="hd-extras">
<script>
/* =====================================================================
   NAVBAR DYNAMIC & RESPONSIVE CONTROLLER
   - Sticky header elevation on scroll
   - Off-canvas drawer toggle for mobile/tablet (<1200px)
   - Profile menu dropdown toggle for desktop
   - Accessible keyboard traps & Escape dismissal
   - Live unread chat count badge polling
   ===================================================================== */
(function () {
    var $ = function (id) { return document.getElementById(id); };
    var hd = $('hd'), nv = $('nv'), tg = $('nvToggle'), sc = $('nvScrim'), cl = $('nvClose'), pb = $('pmBtn'), pm = $('pmMenu');

    var mq = window.matchMedia('(max-width: 1199px)');
    var lastFocusBeforeNav = null;

    function isNavOpen() {
        return nv && nv.classList.contains('on');
    }

    function openNavDrawer() {
        if (!nv || !tg) return;
        lastFocusBeforeNav = document.activeElement;
        nv.classList.add('on');
        if (sc) sc.classList.add('on');
        tg.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';

        var activeOrFirst = nv.querySelector('[aria-current="page"]') || nv.querySelector('a');
        if (activeOrFirst) activeOrFirst.focus();
    }

    function closeNavDrawer(restoreFocus) {
        if (!nv || !tg) return;
        nv.classList.remove('on');
        if (sc) sc.classList.remove('on');
        tg.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';

        if (restoreFocus && lastFocusBeforeNav && typeof lastFocusBeforeNav.focus === 'function') {
            lastFocusBeforeNav.focus();
        }
    }

    function closeProfileMenu(restoreFocus) {
        if (!pm || !pb) return;
        pm.hidden = true;
        pb.setAttribute('aria-expanded', 'false');
        if (restoreFocus && pb) pb.focus();
    }

    if (tg) tg.addEventListener('click', function () { isNavOpen() ? closeNavDrawer(true) : openNavDrawer(); });
    if (cl) cl.addEventListener('click', function () { closeNavDrawer(true); });
    if (sc) sc.addEventListener('click', function () { closeNavDrawer(true); });

    if (nv) {
        nv.addEventListener('click', function (e) {
            if (mq.matches && e.target.closest('a')) {
                closeNavDrawer(false);
            }
        });
    }

    if (pb && pm) {
        pb.addEventListener('click', function (e) {
            e.stopPropagation();
            var isHidden = pm.hidden;
            pm.hidden = !isHidden;
            pb.setAttribute('aria-expanded', String(isHidden));
        });

        document.addEventListener('click', function (e) {
            if (!pm.hidden && !e.target.closest('#pm')) {
                closeProfileMenu(false);
            }
        });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            if (isNavOpen()) {
                closeNavDrawer(true);
                return;
            }
            if (pm && !pm.hidden) {
                closeProfileMenu(true);
                return;
            }
        }

        if (e.key === 'Tab' && isNavOpen()) {
            var focusables = nv.querySelectorAll('a[href], button');
            if (!focusables.length) return;
            var first = focusables[0];
            var last = focusables[focusables.length - 1];

            if (e.shiftKey && document.activeElement === first) {
                e.preventDefault();
                last.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
                e.preventDefault();
                first.focus();
            }
        }
    });

    function handleMqChange() {
        if (!mq.matches && isNavOpen()) {
            closeNavDrawer(false);
        }
    }
    if (mq.addEventListener) {
        mq.addEventListener('change', handleMqChange);
    } else if (mq.addListener) {
        mq.addListener(handleMqChange);
    }

    function handleScroll() {
        if (hd) {
            hd.classList.toggle('stuck', window.scrollY > 4);
        }
    }
    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();

    var nbChatBtn = $('adminNavbarChatBtn');
    if (nbChatBtn) {
        nbChatBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (typeof openChatDrawer === 'function') {
                openChatDrawer();
            } else if (typeof window.openChatDrawer === 'function') {
                window.openChatDrawer();
            } else if (typeof openChatModal === 'function') {
                openChatModal();
            }
        });
    }

    // Periodic Chat Unread Count
    function updateChatBadge() {
        var unreadUrl = "{{ route('admin.chat.unread') }}";
        fetch(unreadUrl)
            .then(function (r) { return r.json(); })
            .then(function (data) {
                var badge = document.getElementById('cxHdrBadge');
                if (!badge) return;
                var count = data && typeof data.count === 'number' ? data.count : 0;
                if (count > 0) {
                    badge.textContent = count > 9 ? '9+' : count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            })
            .catch(function () {});
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', updateChatBadge);
    } else {
        updateChatBadge();
    }
    setInterval(updateChatBadge, 10000);
})();
</script>

{{-- Shared Admin Chat Drawer (Available across all admin pages) --}}
@include('components.admin-chat-drawer')

{{-- Global Confirm Modal (replaces all browser confirm() dialogs) --}}
@include('components.confirm-modal')
</div>
