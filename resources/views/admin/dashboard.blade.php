<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $municipality->name }} Admin Dashboard – MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @include('components.admin-colors')
    <style>
        :root {
            --primary-blue: {{ $adminPrimaryColor ?? '#2C3E8F' }};
            --secondary-yellow: {{ $adminSecondaryColor ?? '#FDB913' }};
            --accent-red: {{ $adminAccentColor ?? '#C41E24' }};
            --primary-gradient: linear-gradient(135deg, var(--primary-blue) 0%, color-mix(in srgb, var(--primary-blue) 80%, black) 100%);
            --secondary-gradient: linear-gradient(135deg, var(--secondary-yellow) 0%, color-mix(in srgb, var(--secondary-yellow) 90%, black) 100%);
            --bg-light: #F8FAFC;
            --bg-white: #FFFFFF;
            --bg-soft-blue: #F0F5FF;
            --border-light: #E2E8F0;
            --text-dark: #1E293B;
        }

        body { background: var(--bg-light); font-family: 'Inter', sans-serif; color: var(--text-dark); display:flex; flex-direction:column; min-height:100vh; margin:0; }
        html, body { overscroll-behavior: none; }
        a { text-decoration: none; }

        /* ── NAVBAR ── */
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,0.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.55rem; color: white !important; display:flex; align-items:center; gap:12px; }
        .navbar-toggler { order: -1; }
        .navbar-brand { order: 0; margin-left: auto !important; margin-right: 0 !important; }
        @media (min-width: 992px) {
            .navbar-toggler { order: 0; }
            .navbar-brand { order: 0; margin-left: 0 !important; margin-right: auto !important; }
        }
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; transition: all 0.25s; border-radius: 8px; padding: 10px 18px !important; font-size: 0.85rem; white-space: nowrap; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .user-info { color:white; display:flex; align-items:center; gap:12px; background:rgba(255,255,255,0.1); padding:9px 22px; border-radius:40px; font-size:0.9rem; font-weight:600; max-width: 100%; }
        .user-info span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 200px; }
        .logout-btn { background:transparent; border:2px solid rgba(255,255,255,0.8); color:white; border-radius:30px; padding:6px 18px; font-weight:700; transition:all 0.3s; font-size:0.88rem; cursor:pointer; }
        .logout-btn:hover { background:var(--secondary-yellow); color:var(--primary-blue); border-color:var(--secondary-yellow); }

        /* ── HERO BANNER ── */
        .hero-banner { background: var(--primary-gradient); color: white; padding: 44px 0 38px; position: relative; overflow: hidden; }
        .hero-banner::before { content:''; position:absolute; top:-80px; right:-80px; width:340px; height:340px; border-radius:50%; background:rgba(253,185,19,0.09); }
        .hero-banner::after  { content:''; position:absolute; bottom:-60px; left:-40px; width:230px; height:230px; border-radius:50%; background:rgba(255,255,255,0.05); }
        .hero-inner { position:relative; z-index:2; }
        .hero-badge { display:inline-block; background:rgba(253,185,19,0.18); color:var(--secondary-yellow); border:1px solid rgba(253,185,19,0.35); border-radius:30px; padding:4px 16px; font-size:0.72rem; font-weight:800; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:10px; }
        .hero-banner h1 { font-size:2rem; font-weight:900; margin-bottom:4px; }
        .hero-divider { width:44px; height:4px; background:var(--secondary-yellow); border-radius:2px; margin:10px 0 8px; }
        .hero-banner p { opacity:0.82; font-size: 0.85rem; margin:0; }
        .muni-badge { background:rgba(253,185,19,0.18); border:1px solid rgba(253,185,19,0.35); color:var(--secondary-yellow); border-radius:12px; padding:14px 24px; text-align:center; }
        .muni-badge .muni-name { font-size:1.35rem; font-weight:900; display:block; }
        .muni-badge .muni-sub  { font-size:0.72rem; opacity:0.75; font-weight:600; text-transform:uppercase; letter-spacing:0.08em; }
        .today-date { font-size:0.8rem; opacity:0.65; margin-top:10px; }

        /* ── STAT CARDS ── */
        .stat-card { background:#f1f5f9; border-radius:18px; border:1px solid #e2e8f0; box-shadow:0 4px 15px rgba(0,0,0,0.06); height:100%; transition:all 0.3s ease; position:relative; overflow:hidden; }
        .stat-card::before { content:''; position:absolute; top:0; left:0; right:0; height:4px; background:var(--primary-gradient); }
        .stat-card.yellow::before { background:var(--primary-gradient); }
        .stat-card.green::before  { background:var(--primary-gradient); }
        .stat-card.red::before    { background:var(--primary-gradient); }
        .stat-card .card-body { padding:24px 26px; }
        .stat-pill { font-size:0.68rem; font-weight:800; letter-spacing:0.1em; text-transform:uppercase; border-radius:20px; padding:3px 10px; display:inline-block; margin-bottom:10px; }
        .stat-label { font-size:0.78rem; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px; }
        .stat-value { font-size:2.8rem; font-weight:900; line-height:1; }
        .stat-sub { font-size:0.75rem; color:#94a3b8; margin-top:8px; font-weight:500; }

        /* ── MAIN CONTENT ── */
        .main-content { flex:1; }

        /* ── PANEL CARDS ── */
        .panel-card { background:white; border-radius:18px; border:1px solid #e2e8f0; box-shadow:0 4px 15px rgba(0,0,0,0.06); overflow:hidden; height:100%; }
        .panel-header { background:var(--primary-gradient); color:white; padding:16px 22px; font-size: 0.85rem; font-weight:700; display:flex; align-items:center; justify-content:space-between; }
        .panel-header-badge { font-size:0.7rem; background:rgba(255,255,255,0.15); border-radius:20px; padding:3px 12px; font-weight:700; }
        .panel-body { padding:20px; background:#f8fafc; }

        /* ── SECTION TITLE ── */
        .section-title { font-size:1.05rem; font-weight:800; color:var(--primary-blue); position:relative; padding-bottom:10px; margin-bottom:0; }
        .section-title::after { content:''; position:absolute; bottom:0; left:0; width:32px; height:3px; background:var(--secondary-yellow); border-radius:2px; }

        /* ── ACTION ITEMS ── */
        .action-item { display:flex; align-items:center; gap:14px; padding:14px 16px; border-radius:13px; background:var(--primary-gradient); color:white; border:none; transition:all 0.25s ease; margin-bottom:10px; text-decoration:none; }
        .action-item:hover { box-shadow:0 8px 24px rgba(44,62,143,0.35); transform:translateY(-2px); color:white; }
        .action-text  { flex:1; }
        .action-num   { font-size:0.6rem; font-weight:800; letter-spacing:0.12em; text-transform:uppercase; color:rgba(253,185,19,0.9); display:block; margin-bottom:2px; }
        .action-title { font-size:0.92rem; font-weight:700; color:white; display:block; line-height:1.25; }
        .action-sub   { font-size:0.74rem; color:rgba(255,255,255,0.72); display:block; margin-top:2px; }
        .action-arrow { font-size:1.3rem; font-weight:300; color:rgba(255,255,255,0.6); flex-shrink:0; transition:color 0.2s; }
        .action-item:hover .action-arrow { color:var(--secondary-yellow); }

        /* ── PROGRAM BREAKDOWN ── */
        .prog-row { display:flex; align-items:center; gap:14px; padding:12px 0; border-bottom:1px solid #e2e8f0; }
        .prog-row:last-child { border-bottom:none; }
        .prog-num { font-size:0.68rem; font-weight:800; letter-spacing:0.08em; text-transform:uppercase; background:#e2e8f0; color:var(--primary-blue); border-radius:20px; padding:2px 10px; flex-shrink:0; }
        .prog-name { font-size:0.88rem; font-weight:700; flex:1; }
        .prog-bar-wrap { flex:2; height:6px; background:#E2E8F0; border-radius:3px; overflow:hidden; }
        .prog-bar { height:100%; border-radius:3px; background:var(--primary-gradient); transition:width 0.8s ease; }
        .prog-count { font-size:0.85rem; font-weight:800; color:var(--primary-blue); min-width:28px; text-align:right; }

        /* ── RECENT TABLE ── */
        .recent-table { width:100%; }
        .recent-table th { font-size:0.72rem; font-weight:800; text-transform:uppercase; letter-spacing:0.07em; color:#64748b; padding:6px 12px 10px; border-bottom:2px solid #e2e8f0; }
        .recent-table td { padding:11px 12px; font-size:0.86rem; border-bottom:1px solid #e2e8f0; vertical-align:middle; }
        .recent-table tr:last-child td { border-bottom:none; }
        .recent-table tr:hover td { background:#f1f5f9; }
        .status-badge { font-size:0.68rem; font-weight:800; letter-spacing:0.06em; text-transform:uppercase; border-radius:20px; padding:3px 11px; display:inline-block; }
        .badge-pending  { background:#FFF3D6; color:#856404; }
        .badge-approved { background:#d4edda; color:#155724; }
        .badge-rejected { background:#fce8e8; color:#721c24; }
        .no-apps { text-align:center; padding:32px 0; color:#94a3b8; font-size:0.88rem; }

        /* ── INFO TABLE ── */
        .info-table tr td { padding:11px 4px; font-size:0.88rem; border-color:#e2e8f0; }
        .info-table tr td:first-child { color:#64748b; font-weight:500; }
        .info-table tr td:last-child { font-weight:800; color:var(--primary-blue); text-align:right; }

        /* ── SUMMARY BOX ── */
        .summary-box { background:#f1f5f9; border-radius:12px; padding:14px 18px; border-left:4px solid var(--primary-blue); margin-top:16px; font-size:0.86rem; color:#334155; line-height:1.7; }

        /* ── ALERTS ── */
        .alert-styled { border-radius:12px; font-size:0.88rem; padding:12px 16px; margin-bottom:16px; }
        .alert-success-c { background:#d4edda; border-left:4px solid #28a745; color:#155724; }

        /* ── FOOTER ── */
        .footer-strip { background:var(--primary-gradient); color:rgba(255,255,255,0.75); text-align:center; padding:20px 0; font-size:0.85rem; margin-top:48px; }
        .footer-strip strong { color:white; }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/admin/dashboard">
                <img src="{{ asset('images/mswd-logo.png') }}" alt="MSWD" style="width:36px;height:36px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link active" href="/admin/dashboard">Dashboard</a></li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.requirements*') ? 'active' : '' }}"
                           href="{{ route('admin.requirements') }}">Applications</a>
                    </li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">Users Management</a></li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.data.*') ? 'active' : '' }}"
                           href="{{ route('admin.data.dashboard') }}">Data Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.detailed*') ? 'active' : '' }}"
                           href="{{ route('admin.detailed-analysis') }}">Analysis</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/analysis/programs">Comparative Analysis</a></li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}"
                           href="{{ route('admin.announcements.index') }}">Announcements</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    @auth
                    {{-- Admin notification bell --}}
                    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#adminNotifModal"
                        style="background:rgba(255,255,255,0.1);color:white;border:none;border-radius:50%;width:40px;height:40px;font-size:1.1rem;display:flex;align-items:center;justify-content:center;padding:0;transition:all 0.3s;position:relative;"
                        title="Application Notifications">
                        <i class="bi bi-bell-fill"></i>
                        @if(isset($adminNotifCount) && $adminNotifCount > 0)
                        <span class="admin-bell-badge" style="position:absolute;top:-4px;right:-4px;background:#dc3545;color:white;border-radius:50%;width:20px;height:20px;font-size:0.7rem;font-weight:800;display:flex;align-items:center;justify-content:center;border:2px solid #2C3E8F;">{{ $adminNotifCount > 9 ? '9+' : $adminNotifCount }}</span>
                        @endif
                    </button>
                    <div class="user-info">
                        <span>{{ Auth::user()->full_name }}</span>
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

    <!-- HERO BANNER -->
    <section class="hero-banner">
        <div class="container">
            <div class="hero-inner">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="hero-badge">Admin Dashboard</div>
                        <h1>Welcome back, {{ Auth::user()->full_name }}!</h1>
                        <div class="hero-divider"></div>
                        <p>Here's an overview of applications and program activity in {{ $municipality->name }}.</p>
                        <p class="today-date">{{ now()->format('l, F j, Y') }}</p>
                    </div>
                    <div class="col-md-4 d-none d-md-flex justify-content-end">
                        <div class="muni-badge">
                            <span class="muni-name">{{ $municipality->name }}</span>
                            <span class="muni-sub">Municipality</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="main-content">
    <div class="container mt-4">

        @php
            $topNotice = session('success') ?: session('error');
        @endphp
        @if($topNotice)
            <div style="position:fixed;top:84px;right:18px;z-index:1080;max-width:420px;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border:1px solid rgba(255,255,255,.18);border-radius:12px;padding:12px 16px;box-shadow:0 10px 28px rgba(26,42,92,.35);font-size:.84rem;font-weight:700;">
                {{ $topNotice }}
            </div>
        @endif

        <!-- STAT CARDS -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="card-body">
                        <span class="stat-pill" style="background:#E5EEFF;color:#2C3E8F;">Total</span>
                        <div class="stat-label">APPLICATIONS</div>
                        <div class="stat-value" style="color:var(--primary-blue);">{{ number_format($totalApplications) }}</div>
                        <div class="stat-sub">All time in {{ $municipality->name }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="card-body">
                        <span class="stat-pill" style="background:#E5EEFF;color:#2C3E8F;">Pending</span>
                        <div class="stat-label">FOR REVIEW</div>
                        <div class="stat-value" style="color:var(--primary-blue);">{{ number_format($pendingApplications) }}</div>
                        <div class="stat-sub">Awaiting action</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="card-body">
                        <span class="stat-pill" style="background:#E5EEFF;color:#2C3E8F;">Approved</span>
                        <div class="stat-label">COMPLETED</div>
                        <div class="stat-value" style="color:var(--primary-blue);">{{ number_format($approvedApplications) }}</div>
                        <div class="stat-sub">Successfully processed</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="card-body">
                        <span class="stat-pill" style="background:#E5EEFF;color:#2C3E8F;">Rejected</span>
                        <div class="stat-label">DECLINED</div>
                        <div class="stat-value" style="color:var(--primary-blue);">{{ number_format($rejectedApplications) }}</div>
                        <div class="stat-sub">Did not meet requirements</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MIDDLE ROW: Quick Actions + Programs Breakdown -->
        <div class="row g-4 mb-4">

            <!-- QUICK ACTIONS -->
            <div class="col-md-5">
                <div class="panel-card">
                    <div class="panel-header">
                        Quick Actions
                        <span class="panel-header-badge">7 shortcuts</span>
                    </div>
                    <div class="panel-body">
                        <a href="{{ route('admin.requirements') }}" class="action-item">
                            <div class="action-text">
                                <span class="action-num">01 &mdash; Priority</span>
                                <span class="action-title">Manage Applications</span>
                                <span class="action-sub">View, approve, or reject pending submissions</span>
                            </div>
                            <span class="action-arrow">&rsaquo;</span>
                        </a>
                        <a href="{{ route('admin.detailed-analysis') }}" class="action-item">
                            <div class="action-text">
                                <span class="action-num">02 &mdash; Analysis</span>
                                <span class="action-title">View Detailed Analysis</span>
                                <span class="action-sub">Applications by program, status &amp; barangay</span>
                            </div>
                            <span class="action-arrow">&rsaquo;</span>
                        </a>
                        <a href="{{ route('admin.data.dashboard') }}" class="action-item">
                            <div class="action-text">
                                <span class="action-num">03 &mdash; Data</span>
                                <span class="action-title">Data Management</span>
                                <span class="action-sub">Update municipality, barangay &amp; program data</span>
                            </div>
                            <span class="action-arrow">&rsaquo;</span>
                        </a>
                        <a href="/analysis/programs" class="action-item">
                            <div class="action-text">
                                <span class="action-num">04 &mdash; Public</span>
                                <span class="action-title">Comparative Analysis</span>
                                <span class="action-sub">View comparative data across municipalities</span>
                            </div>
                            <span class="action-arrow">&rsaquo;</span>
                        </a>
                        <a href="{{ route('admin.announcements.index') }}" class="action-item">
                            <div class="action-text">
                                <span class="action-num">05 &mdash; Announcements</span>
                                <span class="action-title">Manage Announcements</span>
                                <span class="action-sub">Post updates and notices for residents</span>
                            </div>
                            <span class="action-arrow">&rsaquo;</span>
                        </a>
                        {{-- Edit Vision/Mission/Goals button --}}
                        <button type="button" class="action-item" data-bs-toggle="modal" data-bs-target="#vmgEditModal"
                                style="width:100%;text-align:left;cursor:pointer;">
                            <div class="action-text">
                                <span class="action-num">06 &mdash; Content</span>
                                <span class="action-title">Vision, Mission &amp; Strategic Goals</span>
                                <span class="action-sub">Edit your municipality's VMG &amp; strategic goals</span>
                            </div>
                            <span class="action-arrow">&#9998;</span>
                        </button>
                        {{-- User Management --}}
                        <a href="{{ route('admin.users') }}" class="action-item">
                            <div class="action-text">
                                <span class="action-num">07 &mdash; Users</span>
                                <span class="action-title">User Management</span>
                                <span class="action-sub">View registered users &amp; admins in your municipality</span>
                            </div>
                            <span class="action-arrow">&rsaquo;</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- PROGRAMS BREAKDOWN -->
            <div class="col-md-7">
                <div class="panel-card">
                    <div class="panel-header">
                        Applications by Program
                        <span class="panel-header-badge">{{ $applicationsByProgram->count() }} programs</span>
                    </div>
                    <div class="panel-body">
                        @php $maxProgram = $applicationsByProgram->max('total') ?: 1; $pIdx = 1; @endphp
                        @forelse($applicationsByProgram as $program => $stats)
                        <div class="prog-row">
                            <span class="prog-num">{{ str_pad($pIdx++, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="prog-name">{{ str_replace('_', ' ', $program) }}</span>
                            <div class="prog-bar-wrap">
                                <div class="prog-bar" style="width:{{ ($stats['total'] / $maxProgram) * 100 }}%"></div>
                            </div>
                            <span class="prog-count">{{ $stats['total'] }}</span>
                        </div>
                        @empty
                        <div class="no-apps">No applications recorded yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        <!-- BOTTOM ROW: Recent Applications + Municipality Info -->
        <div class="row g-4 mb-4">

            <!-- RECENT APPLICATIONS -->
            <div class="col-md-7">
                <div class="panel-card">
                    <div class="panel-header">
                        Recent Applications
                        <a href="{{ route('admin.requirements') }}" style="font-size:0.78rem;color:rgba(255,255,255,0.80);font-weight:700;">View All</a>
                    </div>
                    <div class="panel-body" style="padding:0 22px 8px; background:#f8fafc;">
                        @php $recent = $applications->sortByDesc('application_date')->take(6); @endphp
                        @if($recent->count())
                        <table class="recent-table">
                            <thead>
                                <tr>
                                    <th>Applicant</th>
                                    <th>Program</th>
                                    <th>Barangay</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent as $app)
                                <tr>
                                    <td><strong>{{ $app->full_name }}</strong></td>
                                    <td>{{ str_replace('_', ' ', $app->program_type) }}</td>
                                    <td>{{ $app->barangay ?: '—' }}</td>
                                    <td>{{ optional($app->application_date)->format('M d, Y') ?? '—' }}</td>
                                    <td>
                                        <span class="status-badge badge-{{ $app->status }}">
                                            {{ ucfirst($app->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="no-apps">No applications recorded yet.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- MUNICIPALITY INFO -->
            <div class="col-md-5">
                <div class="panel-card">
                    <div class="panel-header">
                        Municipality Information
                    </div>
                    <div class="panel-body">
                        <table class="table info-table mb-2">
                            <tr>
                                <td>Municipality</td>
                                <td>{{ $municipality->name }}</td>
                            </tr>
                            <tr>
                                <td>Total Barangays</td>
                                <td>{{ $totalBarangays ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td>Social Programs</td>
                                <td>{{ $totalPrograms ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td>Total Population</td>
                                <td>{{ number_format($municipality->male_population + $municipality->female_population) }}</td>
                            </tr>
                            <tr>
                                <td>Total Households</td>
                                <td>{{ number_format($municipality->total_households) }}</td>
                            </tr>
                        </table>

                        <div class="summary-box">
                            <strong>{{ $municipality->name }}</strong> currently has
                            <strong style="color:#856404;">{{ $pendingApplications }} pending</strong> and
                            <strong style="color:var(--primary-blue);">{{ $approvedApplications }} approved</strong>
                            applications out of <strong>{{ $totalApplications }} total</strong>.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    @include('components.admin-chat-modal')
    @include('components.admin-settings-modal')
    @include('components.admin-notification-modal')

    {{-- ═══════════════════════════════════════════════════════════
         VMG & STRATEGIC GOALS EDIT MODAL
         ═══════════════════════════════════════════════════════════ --}}
    <div class="modal fade" id="vmgEditModal" tabindex="-1" aria-labelledby="vmgEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content" style="border-radius:20px;overflow:hidden;border:none;box-shadow:0 24px 80px rgba(44,62,143,.22);">

                {{-- Header --}}
                <div class="modal-header" style="background:var(--primary-gradient);border:none;padding:20px 28px;">
                    <div>
                        <div style="font-size:.72rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:var(--secondary-yellow);margin-bottom:4px;">
                            {{ $municipality->name }} &mdash; Content Editor
                        </div>
                        <h5 class="modal-title" id="vmgEditModalLabel" style="color:#fff;font-weight:800;font-size:1.15rem;margin:0;">
                            Vision, Mission &amp; Goals + Strategic Goals
                        </h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                {{-- Tabs --}}
                <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:0 28px;">
                    <ul class="nav" id="vmgTabs" role="tablist" style="gap:4px;margin:0;">
                        <li class="nav-item"><button class="vmg-tab active" id="tab-vmg" data-bs-toggle="tab" data-bs-target="#pane-vmg" type="button" role="tab">Vision / Mission / Goals</button></li>
                        <li class="nav-item"><button class="vmg-tab" id="tab-sg" data-bs-toggle="tab" data-bs-target="#pane-sg" type="button" role="tab">Strategic Goals</button></li>
                    </ul>
                </div>

                {{-- Body --}}
                <div class="modal-body" style="padding:28px;background:#fff;">
                    <div class="tab-content">

                        {{-- PANE 1: Vision / Mission / Goals --}}
                        <div class="tab-pane fade show active" id="pane-vmg" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="vmg-field-label">
                                        <span class="vmg-field-icon" style="background:#2C3E8F;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                        </span>
                                        Vision
                                    </label>
                                    <textarea id="vmgVision" class="vmg-textarea" rows="4" placeholder="Enter the vision statement for {{ $municipality->name }}...">{{ $visionData['vision'] }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="vmg-field-label">
                                        <span class="vmg-field-icon" style="background:#FDB913;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1A2A5C" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                        </span>
                                        Mission
                                    </label>
                                    <textarea id="vmgMission" class="vmg-textarea" rows="4" placeholder="Enter the mission statement for {{ $municipality->name }}...">{{ $visionData['mission'] }}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="vmg-field-label">
                                        <span class="vmg-field-icon" style="background:#6366f1;">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                                        </span>
                                        Goals
                                    </label>
                                    <textarea id="vmgGoals" class="vmg-textarea" rows="4" placeholder="Enter the goals for {{ $municipality->name }}...">{{ $visionData['goals'] }}</textarea>
                                </div>
                            </div>
                            <div class="vmg-save-row">
                                <button onclick="saveVMG()" class="vmg-save-btn" id="vmgSaveBtn">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    Save Vision, Mission &amp; Goals
                                </button>
                                <span id="vmgSaveStatus" style="font-size:.82rem;font-weight:600;"></span>
                            </div>
                        </div>

                        {{-- PANE 2: Strategic Goals --}}
                        <div class="tab-pane fade" id="pane-sg" role="tabpanel">
                            <div style="margin-bottom:16px;">
                                <div style="font-size:.8rem;color:#64748b;line-height:1.6;">
                                    Add up to <strong>10 strategic goals</strong> for <strong>{{ $municipality->name }}</strong>. Each goal will be displayed on the public analysis page.
                                </div>
                            </div>
                            <div id="sgGoalsList" style="display:flex;flex-direction:column;gap:10px;">
                                @php $existingGoals = $visionData['strategic_goals'] ?? []; @endphp
                                @if(count($existingGoals) > 0)
                                    @foreach($existingGoals as $gi => $goal)
                                        <div class="sg-input-row" data-idx="{{ $gi }}">
                                            <span class="sg-input-num">{{ str_pad($gi+1, 2, '0', STR_PAD_LEFT) }}</span>
                                            <input type="text" class="sg-input" value="{{ $goal }}" placeholder="Enter strategic goal...">
                                            <button type="button" onclick="removeSGRow(this)" class="sg-remove-btn" title="Remove">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="sg-input-row" data-idx="0">
                                        <span class="sg-input-num">01</span>
                                        <input type="text" class="sg-input" value="" placeholder="Enter strategic goal...">
                                        <button type="button" onclick="removeSGRow(this)" class="sg-remove-btn" title="Remove">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" onclick="addSGRow()" id="addSGBtn"
                                    style="margin-top:12px;background:#f1f5f9;border:2px dashed #cbd5e1;border-radius:10px;color:#64748b;font-weight:700;font-size:.82rem;padding:10px 18px;cursor:pointer;width:100%;transition:all .2s;"
                                    onmouseover="this.style.background='#e5eeff';this.style.borderColor='#2C3E8F';this.style.color='#2C3E8F'" onmouseout="this.style.background='#f1f5f9';this.style.borderColor='#cbd5e1';this.style.color='#64748b'">
                                + Add Strategic Goal
                            </button>
                            <div class="vmg-save-row">
                                <button onclick="saveSG()" class="vmg-save-btn" id="sgSaveBtn">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    Save Strategic Goals
                                </button>
                                <span id="sgSaveStatus" style="font-size:.82rem;font-weight:600;"></span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .vmg-tab { background:none;border:none;padding:12px 18px;font-size:.88rem;font-weight:700;color:#64748b;border-bottom:3px solid transparent;transition:all .2s;cursor:pointer;border-radius:0; }
        .vmg-tab.active,.vmg-tab:focus { color:var(--primary-blue);border-bottom-color:var(--primary-blue);outline:none; }
        .vmg-field-label { display:flex;align-items:center;gap:10px;font-size:.78rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#334155;margin-bottom:8px; }
        .vmg-field-icon { width:26px;height:26px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;flex-shrink:0; }
        .vmg-textarea { width:100%;border:2px solid #e2e8f0;border-radius:12px;padding:14px 16px;font-size:.88rem;font-family:'Inter',sans-serif;color:#1e293b;resize:vertical;line-height:1.7;transition:border-color .2s;background:#f8fafc; }
        .vmg-textarea:focus { outline:none;border-color:var(--primary-blue);background:#fff;box-shadow:0 0 0 4px rgba(44,62,143,.08); }
        .vmg-save-row { display:flex;align-items:center;gap:16px;margin-top:24px;padding-top:20px;border-top:1px solid #e2e8f0; }
        .vmg-save-btn { display:inline-flex;align-items:center;gap:8px;background:var(--primary-gradient);color:#fff;border:none;border-radius:12px;padding:11px 24px;font-size:.88rem;font-weight:800;cursor:pointer;transition:all .25s; }
        .vmg-save-btn:hover { box-shadow:0 8px 24px rgba(44,62,143,.35);transform:translateY(-2px); }
        .sg-input-row { display:flex;align-items:center;gap:10px; }
        .sg-input-num { font-size:.7rem;font-weight:900;color:#fff;background:var(--primary-blue);border-radius:8px;padding:4px 8px;flex-shrink:0;letter-spacing:.05em; }
        .sg-input { flex:1;border:2px solid #e2e8f0;border-radius:10px;padding:10px 14px;font-size:.88rem;font-family:'Inter',sans-serif;transition:border-color .2s;background:#f8fafc; }
        .sg-input:focus { outline:none;border-color:var(--primary-blue);background:#fff;box-shadow:0 0 0 4px rgba(44,62,143,.08); }
        .sg-remove-btn { background:#fce8e8;border:none;border-radius:8px;width:34px;height:34px;display:flex;align-items:center;justify-content:center;color:#dc2626;cursor:pointer;flex-shrink:0;transition:all .2s; }
        .sg-remove-btn:hover { background:#dc2626;color:#fff; }
    </style>

    <script>
    const MUNI_NAME = @json($municipality->name);
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
    const SAVE_URL = '{{ route("admin.vision-mission.save") }}';

    // ── Save Vision/Mission/Goals ────────────────────────────────────────────
    async function saveVMG() {
        const btn = document.getElementById('vmgSaveBtn');
        const status = document.getElementById('vmgSaveStatus');
        btn.disabled = true;
        btn.textContent = 'Saving…';
        status.style.color = '#94a3b8';
        status.textContent = '';

        const payload = {
            _token: CSRF_TOKEN,
            municipality_name: MUNI_NAME,
            vision:  document.getElementById('vmgVision').value,
            mission: document.getElementById('vmgMission').value,
            goals:   document.getElementById('vmgGoals').value,
        };

        try {
            const res = await fetch(SAVE_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (data.success) {
                status.style.color = '#16a34a';
                status.textContent = '✓ Saved successfully!';
            } else {
                status.style.color = '#dc2626';
                status.textContent = data.message || 'Error saving.';
            }
        } catch (e) {
            status.style.color = '#dc2626';
            status.textContent = 'Network error. Please try again.';
        }

        btn.disabled = false;
        btn.innerHTML = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Save Vision, Mission &amp; Goals`;
    }

    // ── Strategic Goals helpers ──────────────────────────────────────────────
    function renumberSG() {
        document.querySelectorAll('#sgGoalsList .sg-input-row').forEach((row, i) => {
            row.dataset.idx = i;
            row.querySelector('.sg-input-num').textContent = String(i + 1).padStart(2, '0');
        });
    }

    function addSGRow() {
        const list = document.getElementById('sgGoalsList');
        if (list.children.length >= 10) { alert('Maximum 10 strategic goals allowed.'); return; }
        const idx = list.children.length;
        const div = document.createElement('div');
        div.className = 'sg-input-row';
        div.dataset.idx = idx;
        div.innerHTML = `
            <span class="sg-input-num">${String(idx + 1).padStart(2, '0')}</span>
            <input type="text" class="sg-input" value="" placeholder="Enter strategic goal...">
            <button type="button" onclick="removeSGRow(this)" class="sg-remove-btn" title="Remove">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>`;
        list.appendChild(div);
        div.querySelector('.sg-input').focus();
    }

    function removeSGRow(btn) {
        const row = btn.closest('.sg-input-row');
        const list = document.getElementById('sgGoalsList');
        if (list.children.length <= 1) { row.querySelector('.sg-input').value = ''; return; }
        row.remove();
        renumberSG();
    }

    // ── Save Strategic Goals ─────────────────────────────────────────────────
    async function saveSG() {
        const btn = document.getElementById('sgSaveBtn');
        const status = document.getElementById('sgSaveStatus');
        btn.disabled = true;
        btn.textContent = 'Saving…';
        status.textContent = '';

        const goals = Array.from(document.querySelectorAll('#sgGoalsList .sg-input'))
            .map(i => i.value.trim())
            .filter(v => v !== '');

        const payload = {
            _token: CSRF_TOKEN,
            municipality_name: MUNI_NAME,
            strategic_goals: goals,
        };

        try {
            const res = await fetch(SAVE_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if (data.success) {
                status.style.color = '#16a34a';
                status.textContent = '✓ Strategic goals saved!';
            } else {
                status.style.color = '#dc2626';
                status.textContent = data.message || 'Error saving.';
            }
        } catch (e) {
            status.style.color = '#dc2626';
            status.textContent = 'Network error. Please try again.';
        }

        btn.disabled = false;
        btn.innerHTML = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Save Strategic Goals`;
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
