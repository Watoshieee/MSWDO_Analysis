{{-- 
MSWDO Shared User Navigation Bar
Included with: @include('components.user-navbar', ['active' => 'dashboard'])
Or simply: @include('components.user-navbar') with automatic active-route detection.
--}}

@php
    $currentActive = $active ?? null;
    if (!$currentActive) {
        if (request()->routeIs('user.dashboard') || request()->is('user/dashboard') || request()->is('dashboard')) {
            $currentActive = 'dashboard';
        } elseif (request()->routeIs('user.programs') || request()->is('user/programs')) {
            $currentActive = 'programs';
        } elseif (request()->routeIs('user.profile') || request()->is('user/profile')) {
            $currentActive = 'profile';
        } elseif (request()->routeIs('user.my-requirements') || request()->is('user/my-requirements')) {
            $currentActive = 'my-requirements';
        } elseif (request()->routeIs('user.announcements') || request()->is('user/announcements')) {
            $currentActive = 'announcements';
        } elseif (request()->routeIs('user.download-app') || request()->is('user/download-app')) {
            $currentActive = 'download-app';
        } elseif (request()->is('analysis*')) {
            $currentActive = 'analysis';
        }
    }
@endphp

<style>
    /* ===== SHARED USER NAVBAR STYLES ===== */
    .user-navbar {
        background: var(--primary-gradient, linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%)) !important;
        box-shadow: 0 4px 24px rgba(44, 62, 143, 0.18);
        padding: 12px 0 !important;
        position: relative;
        z-index: 1020;
    }
    .user-navbar .navbar-brand {
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
    .user-navbar .navbar-toggler {
        order: -1;
        border: 1px solid rgba(255, 255, 255, 0.35);
        padding: 6px 10px;
        border-radius: 8px;
        outline: none;
        box-shadow: none;
    }
    .user-navbar .navbar-toggler:focus {
        box-shadow: 0 0 0 3px rgba(253, 185, 19, 0.35);
    }
    .user-navbar .navbar-brand {
        order: 0;
        margin-left: auto !important;
        margin-right: 0 !important;
    }
    @media (min-width: 992px) {
        .user-navbar .navbar-toggler {
            order: 0;
        }
        .user-navbar .navbar-brand {
            order: 0;
            margin-left: 0 !important;
            margin-right: auto !important;
        }
    }
    .user-navbar .nav-link {
        color: rgba(255, 255, 255, 0.88) !important;
        font-weight: 600;
        transition: all 0.25s ease;
        border-radius: 8px;
        padding: 8px 14px !important;
        font-size: 0.88rem;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
    }
    .user-navbar .nav-link:hover {
        background: rgba(255, 255, 255, 0.15);
        color: white !important;
    }
    .user-navbar .nav-link.active {
        background: var(--secondary-yellow, #FDB913);
        color: var(--primary-blue, #2C3E8F) !important;
        font-weight: 700;
    }
    .user-navbar .navbar-nav {
        flex-wrap: nowrap;
        gap: 2px;
    }
    .user-navbar .nav-actions-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .user-navbar .notif-bell-btn {
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
    .user-navbar .notif-bell-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }
    .user-navbar .bell-badge {
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
    .user-navbar .user-info {
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
    .user-navbar .logout-btn {
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
    .user-navbar .logout-btn:hover {
        background: var(--secondary-yellow, #FDB913);
        color: var(--primary-blue, #2C3E8F);
        border-color: var(--secondary-yellow, #FDB913);
    }
    @media (max-width: 991.98px) {
        .user-navbar .navbar-collapse {
            padding-top: 14px;
            padding-bottom: 8px;
        }
        .user-navbar .navbar-nav {
            flex-wrap: wrap;
            gap: 4px;
            margin-bottom: 12px;
        }
        .user-navbar .nav-link {
            width: 100%;
            padding: 9px 14px !important;
            font-size: 0.92rem;
        }
        .user-navbar .nav-actions-wrap {
            padding-top: 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            width: 100%;
            justify-content: space-between;
        }
        .user-navbar .user-info {
            padding: 6px 14px;
            font-size: 0.85rem;
        }
    }
    @media (max-width: 480px) {
        .user-navbar .navbar-brand {
            font-size: 1.4rem;
        }
        .user-navbar .nav-actions-wrap {
            flex-direction: row;
            gap: 8px;
        }
        .user-navbar .user-info span {
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }
</style>

<nav class="navbar navbar-expand-lg navbar-dark user-navbar">
    <div class="container">
        <a class="navbar-brand" href="{{ route('user.dashboard') }}">
            <img src="{{ asset('images/mswd-logo.png') }}" alt="MSWD" style="width:36px;height:36px;object-fit:contain;"> MSWDO
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#userNavbarNav" aria-controls="userNavbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="userNavbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ $currentActive === 'dashboard' ? 'active' : '' }}" href="{{ route('user.dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $currentActive === 'programs' ? 'active' : '' }}" href="{{ route('user.programs') }}">Programs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $currentActive === 'profile' ? 'active' : '' }}" href="{{ route('user.profile') }}">User Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $currentActive === 'my-requirements' ? 'active' : '' }}" href="{{ route('user.my-requirements') }}">My Requirements</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $currentActive === 'announcements' ? 'active' : '' }}" href="{{ route('user.announcements') }}">Announcements</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $currentActive === 'analysis' ? 'active' : '' }}" href="/analysis">Public Analysis</a>
                </li>
            </ul>
            <div class="nav-actions-wrap">
                <button type="button" class="notif-bell-btn" data-bs-toggle="modal" data-bs-target="#announcementsModal" title="Notifications">
                    <i class="bi bi-bell-fill"></i>
                    @if(isset($notificationCount) && $notificationCount > 0)
                    <span class="bell-badge">{{ $notificationCount > 9 ? '9+' : $notificationCount }}</span>
                    @endif
                </button>
                <div class="user-info">
                    <span class="name-text" title="{{ Auth::user()->full_name }}">{{ Auth::user()->full_name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>