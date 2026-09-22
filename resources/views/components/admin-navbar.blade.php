{{-- 
MSWDO Shared Admin Navigation Bar
Included with: @include('components.admin-navbar', ['active' => 'dashboard'])
Or simply: @include('components.admin-navbar') with automatic active-route detection.
--}}

@php
    $currentActive = $active ?? null;
    if (!$currentActive) {
        if (request()->routeIs('admin.dashboard') || request()->is('admin/dashboard')) {
            $currentActive = 'dashboard';
        } elseif (
            request()->routeIs('admin.requirements*') || 
            request()->routeIs('admin.applications*') || 
            request()->routeIs('admin.view-requirement*') ||
            request()->is('admin/requirements*') || 
            request()->is('admin/applications*')
        ) {
            $currentActive = 'applications';
        } elseif (request()->routeIs('admin.users*') || request()->is('admin/users*')) {
            $currentActive = 'users';
        } elseif (
            request()->routeIs('admin.data.*') || 
            request()->is('admin/data*') || 
            request()->routeIs('admin.yearly.*') || 
            request()->is('admin/yearly*')
        ) {
            $currentActive = 'data';
        } elseif (request()->routeIs('admin.announcements*') || request()->is('admin/announcements*')) {
            $currentActive = 'announcements';
        } elseif (request()->routeIs('admin.detailed*') || request()->is('admin/detailed*')) {
            $currentActive = 'analysis';
        } elseif (request()->routeIs('analysis.programs*') || request()->is('analysis/programs*')) {
            $currentActive = 'comparative';
        } elseif (request()->routeIs('admin.settings*') || request()->is('admin/settings*')) {
            $currentActive = 'settings';
        }
    }
@endphp

<style>
    /* ===== SHARED ADMIN NAVBAR STYLES ===== */
    .navbar.admin-navbar {
        background: var(--primary-gradient, linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%)) !important;
        box-shadow: 0 4px 24px rgba(44, 62, 143, 0.18) !important;
        padding: 12px 0 !important;
        position: relative;
        z-index: 1020;
    }
    .navbar.admin-navbar .navbar-brand {
        font-weight: 800;
        font-size: 1.55rem;
        color: white !important;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0;
        margin: 0;
        line-height: 1;
        letter-spacing: -0.01em;
    }
    .navbar.admin-navbar .navbar-toggler {
        order: -1;
        border: 1px solid rgba(255, 255, 255, 0.35);
        padding: 6px 10px;
        border-radius: 8px;
        outline: none;
        box-shadow: none;
    }
    .navbar.admin-navbar .navbar-toggler:focus {
        box-shadow: 0 0 0 3px rgba(253, 185, 19, 0.35);
    }
    .navbar.admin-navbar .navbar-brand {
        order: 0;
        margin-left: auto !important;
        margin-right: 0 !important;
    }
    @media (min-width: 992px) {
        .navbar.admin-navbar .navbar-toggler {
            order: 0;
        }
        .navbar.admin-navbar .navbar-brand {
            order: 0;
            margin-left: 0 !important;
            margin-right: auto !important;
        }
    }
    .navbar.admin-navbar .nav-link {
        color: rgba(255, 255, 255, 0.88) !important;
        font-weight: 600;
        transition: all 0.25s ease;
        border-radius: 8px;
        padding: 8px 12px !important;
        font-size: 0.86rem;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
    }
    .navbar.admin-navbar .nav-link:hover {
        background: rgba(255, 255, 255, 0.15);
        color: white !important;
    }
    .navbar.admin-navbar .nav-link.active {
        background: var(--secondary-yellow, #FDB913);
        color: var(--primary-blue, #2C3E8F) !important;
        font-weight: 700;
    }
    .navbar.admin-navbar .navbar-nav {
        flex-wrap: nowrap;
        gap: 2px;
    }
    .navbar.admin-navbar .nav-actions-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .navbar.admin-navbar .admin-notif-bell-btn {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        font-weight: 700;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        transition: all 0.3s ease;
        position: relative;
        flex-shrink: 0;
        cursor: pointer;
    }
    .navbar.admin-navbar .admin-notif-bell-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }
    .navbar.admin-navbar .admin-bell-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 0.7rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #2C3E8F;
    }
    .navbar.admin-navbar .user-info {
        color: white;
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255, 255, 255, 0.1);
        padding: 7px 18px;
        border-radius: 40px;
        font-size: 0.90rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .navbar.admin-navbar .user-info .name-text {
        display: inline-block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 100px;
        vertical-align: middle;
        cursor: default;
    }
    .navbar.admin-navbar .logout-btn {
        background: transparent;
        border: 2px solid rgba(255, 255, 255, 0.8);
        color: white;
        border-radius: 30px;
        padding: 5px 16px;
        font-weight: 700;
        transition: all 0.25s ease;
        font-size: 0.85rem;
        cursor: pointer;
        line-height: 1.3;
    }
    .navbar.admin-navbar .logout-btn:hover {
        background: var(--secondary-yellow, #FDB913);
        color: var(--primary-blue, #2C3E8F);
        border-color: var(--secondary-yellow, #FDB913);
    }
    @media (min-width: 992px) and (max-width: 1199.98px) {
        .navbar.admin-navbar .nav-link {
            padding: 6px 8px !important;
            font-size: 0.80rem;
        }
        .navbar.admin-navbar .navbar-brand {
            font-size: 1.35rem;
            gap: 8px;
        }
        .navbar.admin-navbar .user-info {
            padding: 5px 12px;
            font-size: 0.82rem;
            gap: 8px;
        }
        .navbar.admin-navbar .user-info .name-text {
        display: inline-block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 100px;
        vertical-align: middle;
        cursor: default;
    }
    .navbar.admin-navbar .logout-btn {
            padding: 4px 12px;
            font-size: 0.80rem;
        }
    }
    @media (max-width: 991.98px) {
        .navbar.admin-navbar .navbar-collapse {
            padding-top: 14px;
            padding-bottom: 8px;
        }
        .navbar.admin-navbar .navbar-nav {
            flex-wrap: wrap;
            gap: 4px;
            margin-bottom: 12px;
        }
        .navbar.admin-navbar .nav-link {
            width: 100%;
            padding: 9px 14px !important;
            font-size: 0.92rem;
        }
        .navbar.admin-navbar .nav-actions-wrap {
            padding-top: 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            width: 100%;
            justify-content: space-between;
        }
        .navbar.admin-navbar .user-info {
            padding: 6px 14px;
            font-size: 0.85rem;
        }
    }
    @media (max-width: 480px) {
        .navbar.admin-navbar .navbar-brand {
            font-size: 1.35rem;
        }
        .navbar.admin-navbar .nav-actions-wrap {
            flex-direction: row;
            gap: 8px;
        }
        .navbar.admin-navbar .user-info span {
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark admin-navbar">
    <div class="container">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('images/mswd-logo.png') }}" alt="MSWD" style="width:36px;height:36px;object-fit:contain;"> MSWDO
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbarNav" aria-controls="adminNavbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ $currentActive === 'dashboard' ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $currentActive === 'applications' ? 'active' : '' }}" href="{{ route('admin.requirements') }}">Applications</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $currentActive === 'users' ? 'active' : '' }}" href="{{ route('admin.users') }}">Users Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $currentActive === 'data' ? 'active' : '' }}" href="{{ route('admin.data.dashboard') }}">Data Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $currentActive === 'announcements' ? 'active' : '' }}" href="{{ route('admin.announcements.index') }}">Announcements</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $currentActive === 'analysis' ? 'active' : '' }}" href="{{ route('admin.detailed-analysis') }}">Analysis</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $currentActive === 'comparative' ? 'active' : '' }}" href="/analysis/programs">Comparative Analysis</a>
                </li>
            </ul>
            <div class="nav-actions-wrap">
                @auth
                <button type="button" class="admin-notif-bell-btn" onclick="if(typeof openAdminNotifModal==='function'){openAdminNotifModal();}else{window.location.href='{{ route('admin.requirements') }}';}" title="Application Notifications">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/>
                    </svg>
                    @if(isset($adminNotifCount) && $adminNotifCount > 0)
                    <span class="admin-bell-badge">{{ $adminNotifCount > 9 ? '9+' : $adminNotifCount }}</span>
                    @endif
                </button>
                <div class="user-info">
                    <span class="name-text" title="{{ Auth::user()->full_name }}">{{ Auth::user()->full_name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
                @endauth
            </div>
        </div>
    </div>
</nav>
