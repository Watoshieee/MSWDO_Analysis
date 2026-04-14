<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Dashboard – MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --primary-blue-light: #E5EEFF;
            --primary-blue-soft: #5D7BB9;
            --secondary-yellow: #FDB913;
            --secondary-yellow-light: #FFF3D6;
            --accent-red: #C41E24;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --secondary-gradient: linear-gradient(135deg, #FDB913 0%, #E5A500 100%);
            --bg-light: #F8FAFC;
            --border-light: #E2E8F0;
        }

        *,
        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }

        body {
            background: #e2e8f0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        html, body {
            overscroll-behavior: none;
            margin: 0;
            padding: 0;
        }

        /* ── NAVBAR ── */
        .navbar {
            background: var(--primary-gradient) !important;
            box-shadow: 0 4px 24px rgba(44, 62, 143, 0.18);
            padding: 14px 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.55rem;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .navbar-toggler { order: -1; }
        .navbar-brand { order: 0; margin-left: auto !important; margin-right: 0 !important; }
        @media (min-width: 992px) {
            .navbar-toggler { order: 0; }
            .navbar-brand { order: 0; margin-left: 0 !important; margin-right: auto !important; }
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.88) !important;
            font-weight: 600;
            transition: all 0.25s;
            border-radius: 8px;
            padding: 10px 18px !important;
            font-size: 0.95rem;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white !important;
        }

        .nav-link.active {
            background: var(--secondary-yellow);
            color: var(--primary-blue) !important;
            font-weight: 700;
        }

        .user-info {
            color: white;
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.1);
            padding: 9px 22px;
            border-radius: 40px;
            font-size: 0.92rem;
            font-weight: 500;
        }

        .logout-btn {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.8);
            color: white;
            border-radius: 30px;
            padding: 6px 18px;
            font-weight: 700;
            transition: all 0.3s;
            font-size: 0.88rem;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            border-color: var(--secondary-yellow);
        }

        /* ── HERO ── */
        .hero-banner {
            background: var(--primary-gradient);
            color: white;
            padding: 52px 0 42px;
            position: relative;
            overflow: hidden;
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            top: -70px;
            right: -70px;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: rgba(253, 185, 19, 0.10);
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -50px;
            width: 250px;
            height: 250px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
        }

        .hero-badge {
            display: inline-block;
            background: rgba(253, 185, 19, 0.18);
            color: var(--secondary-yellow);
            border: 1px solid rgba(253, 185, 19, 0.35);
            border-radius: 30px;
            padding: 5px 18px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .hero-banner h1 {
            font-size: 2.4rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 10px;
        }

        .hero-divider {
            width: 55px;
            height: 4px;
            background: var(--secondary-yellow);
            border-radius: 2px;
            margin: 14px 0;
        }

        .hero-banner p {
            font-size: 1rem;
            opacity: 0.87;
            max-width: 580px;
        }

        .municipality-badge {
            background: rgba(253, 185, 19, 0.15);
            border: 1px solid rgba(253, 185, 19, 0.3);
            border-radius: 16px;
            padding: 20px 30px;
            text-align: center;
        }

        .municipality-badge .badge-label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--secondary-yellow);
            opacity: 0.9;
        }

        .municipality-badge .badge-name {
            font-size: 1.4rem;
            font-weight: 800;
            color: white;
            margin-top: 4px;
        }

        /* ── MAIN CONTENT ── */
        .main-content {
            flex: 1;
        }

        .section-heading {
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--primary-blue);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-heading::after {
            content: '';
            flex: 1;
            height: 2px;
            background: var(--border-light);
            border-radius: 2px;
        }

        /* ── STAT CARDS ── */
        .stat-card {
            background: #f8fafc;
            border-radius: 20px;
            padding: 28px 24px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border: 1px solid #cbd5e1;
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(44, 62, 143, 0.15);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .stat-number {
            font-size: 2.4rem;
            font-weight: 800;
            color: var(--primary-blue);
            line-height: 1;
            margin: 12px 0 6px;
        }

        .stat-label {
            font-size: 0.88rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .stat-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .stat-badge.blue {
            background: var(--primary-blue-light);
            color: var(--primary-blue);
        }

        .stat-badge.yellow {
            background: var(--secondary-yellow-light);
            color: #92600a;
        }

        .stat-badge.gold {
            background: rgba(253, 185, 19, 0.15);
            color: #92600a;
        }

        .stat-badge.green {
            background: #e6f9f0;
            color: #1a7a4a;
        }

        /* ── MENU CARDS ── */
        .menu-card {
            background: #f8fafc;
            border-radius: 18px;
            padding: 28px 24px;
            border: 1px solid #cbd5e1;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
            height: 100%;
            cursor: pointer;
            text-decoration: none;
            display: block;
            position: relative;
            overflow: hidden;
        }

        .menu-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--secondary-gradient);
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 14px 32px rgba(44, 62, 143, 0.12);
            border-color: var(--primary-blue-soft);
        }

        .menu-card-num {
            font-size: 0.75rem;
            font-weight: 800;
            color: rgba(253, 185, 19, 0.8);
            letter-spacing: 0.15em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .menu-card-title {
            font-size: 1.12rem;
            font-weight: 800;
            color: var(--primary-blue);
            margin-bottom: 6px;
        }

        .menu-card-desc {
            font-size: 0.85rem;
            color: #64748b;
            line-height: 1.55;
        }

        .menu-card-arrow {
            position: absolute;
            bottom: 22px;
            right: 22px;
            font-size: 1.2rem;
            color: var(--secondary-yellow);
            font-weight: 900;
            transition: transform 0.25s ease;
        }

        .menu-card:hover .menu-card-arrow {
            transform: translateX(4px);
        }

        /* ── PANEL CARD ── */
        .panel-card {
            background: #f8fafc;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border: 1px solid #cbd5e1;
            overflow: hidden;
        }

        .panel-header {
            background: var(--primary-gradient);
            color: white;
            padding: 20px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .panel-header h5 {
            font-weight: 700;
            margin: 0;
            font-size: 1.05rem;
        }

        .panel-header .count-badge {
            background: rgba(253, 185, 19, 0.25);
            color: var(--secondary-yellow);
            border: 1px solid rgba(253, 185, 19, 0.4);
            border-radius: 20px;
            padding: 3px 12px;
            font-size: 0.78rem;
            font-weight: 700;
        }

        .panel-body {
            padding: 0;
        }

        table.premium-table {
            width: 100%;
            border-collapse: collapse;
        }

        .premium-table thead th {
            background: #f1f5f9;
            color: var(--primary-blue);
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 12px 20px;
            border-bottom: 2px solid #cbd5e1;
        }

        .premium-table tbody td {
            padding: 14px 20px;
            font-size: 0.88rem;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
            color: #334155;
        }

        .premium-table tbody tr:last-child td {
            border-bottom: none;
        }

        .premium-table tbody tr:hover {
            background: #f1f5f9;
        }

        /* ── ROLE PILLS ── */
        .role-pill {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.04em;
        }

        .role-pill.super-admin {
            background: rgba(196, 30, 36, 0.12);
            color: #C41E24;
            border: 1px solid rgba(196, 30, 36, 0.2);
        }

        .role-pill.admin {
            background: var(--secondary-yellow-light);
            color: #92600a;
            border: 1px solid rgba(253, 185, 19, 0.3);
        }

        .role-pill.user {
            background: var(--primary-blue-light);
            color: var(--primary-blue);
            border: 1px solid rgba(44, 62, 143, 0.2);
        }

        .status-pill {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .status-pill.active {
            background: #e6f9f0;
            color: #1a7a4a;
        }

        .status-pill.inactive {
            background: #fef2f2;
            color: #991b1b;
        }

        /* ── FOOTER ── */
        .footer-strip {
            background: var(--primary-gradient);
            color: rgba(255, 255, 255, 0.75);
            text-align: center;
            padding: 18px 0;
            font-size: 0.85rem;
            margin-top: auto;
        }

        .footer-strip strong {
            color: white;
        }

        /* ── ALERT ── */
        .alert-success {
            background: var(--primary-blue-light);
            color: var(--primary-blue);
            border-left: 4px solid var(--primary-blue);
            border-radius: 12px;
            border-color: transparent;
            border-left-color: var(--primary-blue);
        }
    </style>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ route('superadmin.dashboard') }}">
                <img src="/images/mswd-logo.png" alt="MSWD" style="width:34px;height:34px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link active"
                            href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.users') }}">User Management</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.municipalities.index') }}">Municipalities</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.data.dashboard') }}">Data
                            Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="/analysis/programs">Public View</a></li>
                </ul>
                <div class="d-flex">
                    <div class="user-info">
                        <span>{{ Auth::user()->full_name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero-banner">
        <div class="container" style="position:relative;z-index:2;">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="hero-badge">Super Admin Panel</div>
                    <h1>System Dashboard</h1>
                    <div class="hero-divider"></div>
                    <p>Manage users, municipalities, and system-wide data across all MSWDO municipalities.</p>
                </div>
                <div class="col-lg-4 text-end">
                    <div class="municipality-badge">
                        <div class="badge-label">System</div>
                        <div class="badge-name">MSWDO</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="main-content">
        <div class="container py-5">
            @if(session('success'))
                <div class="alert alert-success mb-4">{{ session('success') }}</div>
            @endif

            <!-- STAT CARDS -->
            <div class="section-heading">System Overview</div>
            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="stat-card" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#allUsersModal">
                        <div class="stat-badge blue">Total Users</div>
                        <div class="stat-number">{{ $totalUsers }}</div>
                        <div class="stat-label">Registered Accounts</div>
                        <small style="color: #94a3b8; font-size: 0.75rem; margin-top: 8px; display: block;">Click to view all</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#superAdminsModal">
                        <div class="stat-badge yellow">Super Admins</div>
                        <div class="stat-number">{{ $totalSuperAdmins }}</div>
                        <div class="stat-label">System Administrators</div>
                        <small style="color: #94a3b8; font-size: 0.75rem; margin-top: 8px; display: block;">Click to view all</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#adminsModal">
                        <div class="stat-badge gold">Admins</div>
                        <div class="stat-number">{{ $totalAdmins }}</div>
                        <div class="stat-label">Municipal Admins</div>
                        <small style="color: #94a3b8; font-size: 0.75rem; margin-top: 8px; display: block;">Click to view all</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#regularUsersModal">
                        <div class="stat-badge green">Users</div>
                        <div class="stat-number">{{ $totalRegularUsers }}</div>
                        <div class="stat-label">Regular Users</div>
                        <small style="color: #94a3b8; font-size: 0.75rem; margin-top: 8px; display: block;">Click to view all</small>
                    </div>
                </div>
            </div>

            <!-- QUICK ACCESS MENU CARDS -->
            <div class="section-heading">Quick Access</div>
            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <a href="{{ route('superadmin.users') }}" class="menu-card">
                        <div class="menu-card-num">01</div>
                        <div class="menu-card-title">User Management</div>
                        <div class="menu-card-desc">Create, update, and manage all system users and their roles.</div>
                        <span class="menu-card-arrow">&#8594;</span>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('superadmin.municipalities.index') }}" class="menu-card">
                        <div class="menu-card-num">02</div>
                        <div class="menu-card-title">Municipalities</div>
                        <div class="menu-card-desc">Add and configure municipalities served by the MSWDO system.</div>
                        <span class="menu-card-arrow">&#8594;</span>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('superadmin.data.municipalities') }}" class="menu-card">
                        <div class="menu-card-num">03</div>
                        <div class="menu-card-title">Municipality Data</div>
                        <div class="menu-card-desc">Update population, households, and demographics per municipality.
                        </div>
                        <span class="menu-card-arrow">&#8594;</span>
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('superadmin.data.barangays') }}" class="menu-card">
                        <div class="menu-card-num">04</div>
                        <div class="menu-card-title">Barangay Data</div>
                        <div class="menu-card-desc">Manage barangay-level statistics across all municipalities.</div>
                        <span class="menu-card-arrow">&#8594;</span>
                    </a>
                </div>
            </div>

            <!-- RECENT USERS TABLE -->
            <div class="section-heading">Recent Users</div>
            <div class="panel-card mb-5">
                <div class="panel-header">
                    <h5>Recent Registrations</h5>
                    <a href="{{ route('superadmin.users') }}"
                        style="color:var(--secondary-yellow);font-size:0.85rem;font-weight:700;text-decoration:none;">View
                        all &rarr;</a>
                </div>
                <div class="panel-body">
                    <table class="premium-table">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentUsers as $user)
                                <tr>
                                    <td style="font-weight:600;">{{ $user->full_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role == 'super_admin')
                                            <span class="role-pill super-admin">Super Admin</span>
                                        @elseif($user->role == 'admin')
                                            <span class="role-pill admin">Admin</span>
                                        @else
                                            <span class="role-pill user">User</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="status-pill {{ $user->status == 'active' ? 'active' : 'inactive' }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- MUNICIPALITIES OVERVIEW -->
            <div class="section-heading">Municipalities Overview</div>
            <div class="panel-card mb-4">
                <div class="panel-header">
                    <h5>Municipality Statistics</h5>
                    <span class="count-badge">{{ count($municipalities) }} municipalities</span>
                </div>
                <div class="panel-body">
                    <table class="premium-table">
                        <thead>
                            <tr>
                                <th>Municipality</th>
                                <th>Total Population</th>
                                <th>Households</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($municipalities as $m)
                                <tr>
                                    <td style="font-weight:700;color:var(--primary-blue);">{{ $m->name }}</td>
                                    <td>{{ number_format($m->male_population + $m->female_population) }}</td>
                                    <td>{{ number_format($m->total_households) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </footer>

    <!-- All Users Modal -->
    <div class="modal fade" id="allUsersModal" tabindex="-1" aria-labelledby="allUsersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
                <div class="modal-header" style="background: var(--primary-gradient); color: white; padding: 24px 32px; border: none;">
                    <div>
                        <h5 class="modal-title" id="allUsersModalLabel" style="font-weight: 700; font-size: 1.3rem; margin: 0;">All Users</h5>
                        <small style="opacity: 0.85; font-size: 0.88rem;">{{ $totalUsers }} total registered users</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 0; background: #f8fafc;">
                    <div style="overflow-x: auto;">
                        <table class="table" style="margin: 0; background: white;">
                            <thead style="background: #f1f5f9; position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th style="padding: 16px 24px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; border: none;">Name</th>
                                    <th style="padding: 16px 24px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; border: none;">Email</th>
                                    <th style="padding: 16px 24px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; border: none;">Role</th>
                                    <th style="padding: 16px 24px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; border: none;">Municipality</th>
                                    <th style="padding: 16px 24px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; border: none;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allUsers as $user)
                                <tr style="transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='white'">
                                    <td style="padding: 16px 24px; font-weight: 600; color: #1e293b; border-bottom: 1px solid #e2e8f0;">{{ $user->full_name }}</td>
                                    <td style="padding: 16px 24px; color: #64748b; border-bottom: 1px solid #e2e8f0;">{{ $user->email }}</td>
                                    <td style="padding: 16px 24px; border-bottom: 1px solid #e2e8f0;">
                                        @if($user->role == 'super_admin')
                                            <span class="role-pill super-admin">Super Admin</span>
                                        @elseif($user->role == 'admin')
                                            <span class="role-pill admin">Admin</span>
                                        @else
                                            <span class="role-pill user">User</span>
                                        @endif
                                    </td>
                                    <td style="padding: 16px 24px; color: #64748b; border-bottom: 1px solid #e2e8f0;">{{ $user->municipality ?? 'N/A' }}</td>
                                    <td style="padding: 16px 24px; border-bottom: 1px solid #e2e8f0;">
                                        <span class="status-pill {{ $user->status == 'active' ? 'active' : 'inactive' }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 20px 32px; background: white; border-top: 1px solid #e2e8f0;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; padding: 10px 24px; font-weight: 600;">Close</button>
                    <a href="{{ route('superadmin.users') }}" class="btn btn-primary" style="background: var(--primary-blue); border: none; border-radius: 8px; padding: 10px 24px; font-weight: 600;">Manage Users</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Super Admins Modal -->
    <div class="modal fade" id="superAdminsModal" tabindex="-1" aria-labelledby="superAdminsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
                <div class="modal-header" style="background: linear-gradient(135deg, #FDB913 0%, #E5A500 100%); color: #1A2A5C; padding: 24px 32px; border: none;">
                    <div>
                        <h5 class="modal-title" id="superAdminsModalLabel" style="font-weight: 700; font-size: 1.3rem; margin: 0;">Super Admins</h5>
                        <small style="opacity: 0.85; font-size: 0.88rem;">{{ $totalSuperAdmins }} system administrators</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 0; background: #f8fafc;">
                    <div style="overflow-x: auto;">
                        <table class="table" style="margin: 0; background: white;">
                            <thead style="background: #f1f5f9; position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th style="padding: 16px 24px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; border: none;">Name</th>
                                    <th style="padding: 16px 24px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; border: none;">Email</th>
                                    <th style="padding: 16px 24px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; border: none;">Username</th>
                                    <th style="padding: 16px 24px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; border: none;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allUsers->where('role', 'super_admin') as $user)
                                <tr style="transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='white'">
                                    <td style="padding: 16px 24px; font-weight: 600; color: #1e293b; border-bottom: 1px solid #e2e8f0;">{{ $user->full_name }}</td>
                                    <td style="padding: 16px 24px; color: #64748b; border-bottom: 1px solid #e2e8f0;">{{ $user->email }}</td>
                                    <td style="padding: 16px 24px; color: #64748b; border-bottom: 1px solid #e2e8f0;">{{ $user->username }}</td>
                                    <td style="padding: 16px 24px; border-bottom: 1px solid #e2e8f0;">
                                        <span class="status-pill {{ $user->status == 'active' ? 'active' : 'inactive' }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 20px 32px; background: white; border-top: 1px solid #e2e8f0;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; padding: 10px 24px; font-weight: 600;">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Admins Modal -->
    <div class="modal fade" id="adminsModal" tabindex="-1" aria-labelledby="adminsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
                <div class="modal-header" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 24px 32px; border: none;">
                    <div>
                        <h5 class="modal-title" id="adminsModalLabel" style="font-weight: 700; font-size: 1.3rem; margin: 0;">Municipal Admins</h5>
                        <small style="opacity: 0.9; font-size: 0.88rem;">{{ $totalAdmins }} municipal administrators</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 0; background: #f8fafc;">
                    <div style="overflow-x: auto;">
                        <table class="table" style="margin: 0; background: white;">
                            <thead style="background: #f1f5f9; position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th style="padding: 16px 24px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; border: none;">Name</th>
                                    <th style="padding: 16px 24px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; border: none;">Email</th>
                                    <th style="padding: 16px 24px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; border: none;">Municipality</th>
                                    <th style="padding: 16px 24px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; border: none;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allUsers->where('role', 'admin') as $user)
                                <tr style="transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='white'">
                                    <td style="padding: 16px 24px; font-weight: 600; color: #1e293b; border-bottom: 1px solid #e2e8f0;">{{ $user->full_name }}</td>
                                    <td style="padding: 16px 24px; color: #64748b; border-bottom: 1px solid #e2e8f0;">{{ $user->email }}</td>
                                    <td style="padding: 16px 24px; color: #64748b; border-bottom: 1px solid #e2e8f0;">{{ $user->municipality ?? 'N/A' }}</td>
                                    <td style="padding: 16px 24px; border-bottom: 1px solid #e2e8f0;">
                                        <span class="status-pill {{ $user->status == 'active' ? 'active' : 'inactive' }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 20px 32px; background: white; border-top: 1px solid #e2e8f0;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; padding: 10px 24px; font-weight: 600;">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Regular Users Modal -->
    <div class="modal fade" id="regularUsersModal" tabindex="-1" aria-labelledby="regularUsersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
                <div class="modal-header" style="background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); color: white; padding: 24px 32px; border: none;">
                    <div>
                        <h5 class="modal-title" id="regularUsersModalLabel" style="font-weight: 700; font-size: 1.3rem; margin: 0;">Regular Users</h5>
                        <small style="opacity: 0.9; font-size: 0.88rem;">{{ $totalRegularUsers }} registered users</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding: 0; background: #f8fafc;">
                    <div style="overflow-x: auto;">
                        <table class="table" style="margin: 0; background: white;">
                            <thead style="background: #f1f5f9; position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th style="padding: 16px 24px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; border: none;">Name</th>
                                    <th style="padding: 16px 24px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; border: none;">Email</th>
                                    <th style="padding: 16px 24px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; border: none;">Municipality</th>
                                    <th style="padding: 16px 24px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; border: none;">Barangay</th>
                                    <th style="padding: 16px 24px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; border: none;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allUsers->where('role', 'user') as $user)
                                <tr style="transition: all 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='white'">
                                    <td style="padding: 16px 24px; font-weight: 600; color: #1e293b; border-bottom: 1px solid #e2e8f0;">{{ $user->full_name }}</td>
                                    <td style="padding: 16px 24px; color: #64748b; border-bottom: 1px solid #e2e8f0;">{{ $user->email }}</td>
                                    <td style="padding: 16px 24px; color: #64748b; border-bottom: 1px solid #e2e8f0;">{{ $user->municipality ?? 'N/A' }}</td>
                                    <td style="padding: 16px 24px; color: #64748b; border-bottom: 1px solid #e2e8f0;">{{ $user->barangay ?? 'N/A' }}</td>
                                    <td style="padding: 16px 24px; border-bottom: 1px solid #e2e8f0;">
                                        <span class="status-pill {{ $user->status == 'active' ? 'active' : 'inactive' }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer" style="padding: 20px 32px; background: white; border-top: 1px solid #e2e8f0;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px; padding: 10px 24px; font-weight: 600;">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>