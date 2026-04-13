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
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; transition: all 0.25s; border-radius: 8px; padding: 10px 18px !important; font-size: 0.93rem; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .user-info { color:white; display:flex; align-items:center; gap:12px; background:rgba(255,255,255,0.1); padding:9px 22px; border-radius:40px; font-size:0.9rem; font-weight:600; }
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
        .hero-banner p { opacity:0.82; font-size:0.93rem; margin:0; }
        .muni-badge { background:rgba(253,185,19,0.18); border:1px solid rgba(253,185,19,0.35); color:var(--secondary-yellow); border-radius:12px; padding:14px 24px; text-align:center; }
        .muni-badge .muni-name { font-size:1.35rem; font-weight:900; display:block; }
        .muni-badge .muni-sub  { font-size:0.72rem; opacity:0.75; font-weight:600; text-transform:uppercase; letter-spacing:0.08em; }
        .today-date { font-size:0.8rem; opacity:0.65; margin-top:10px; }

        /* ── STAT CARDS ── */
        .stat-card { background:#f1f5f9; border-radius:18px; border:1px solid #e2e8f0; box-shadow:0 4px 15px rgba(0,0,0,0.06); height:100%; transition:all 0.3s ease; position:relative; overflow:hidden; }
        .stat-card::before { content:''; position:absolute; top:0; left:0; right:0; height:4px; background:var(--primary-gradient); }
        .stat-card.yellow::before { background:var(--secondary-gradient); }
        .stat-card.green::before  { background:linear-gradient(135deg,#28a745,#1e7e34); }
        .stat-card.red::before    { background:linear-gradient(135deg,#C41E24,#8B0000); }
        .stat-card .card-body { padding:24px 26px; }
        .stat-pill { font-size:0.68rem; font-weight:800; letter-spacing:0.1em; text-transform:uppercase; border-radius:20px; padding:3px 10px; display:inline-block; margin-bottom:10px; }
        .stat-label { font-size:0.78rem; font-weight:600; color:#64748b; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:6px; }
        .stat-value { font-size:2.8rem; font-weight:900; line-height:1; }
        .stat-sub { font-size:0.75rem; color:#94a3b8; margin-top:8px; font-weight:500; }

        /* ── MAIN CONTENT ── */
        .main-content { flex:1; }

        /* ── PANEL CARDS ── */
        .panel-card { background:white; border-radius:18px; border:1px solid #e2e8f0; box-shadow:0 4px 15px rgba(0,0,0,0.06); overflow:hidden; height:100%; }
        .panel-header { background:var(--primary-gradient); color:white; padding:16px 22px; font-size:0.93rem; font-weight:700; display:flex; align-items:center; justify-content:space-between; }
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
                <img src="/images/mswd-logo.png" alt="MSWD" style="width:36px;height:36px;object-fit:contain;"> MSWDO
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
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.data.*') ? 'active' : '' }}"
                           href="{{ route('admin.data.dashboard') }}">Data Management</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.detailed*') ? 'active' : '' }}"
                           href="{{ route('admin.detailed-analysis') }}">Analysis</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/analysis/programs">Comparative Analysis</a></li>
                </ul>
                <div class="d-flex">
                    @auth
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

        @if(session('success'))
            <div class="alert-styled alert-success-c">{{ session('success') }}</div>
        @endif

        <!-- STAT CARDS -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="card-body">
                        <span class="stat-pill" style="background:#E5EEFF;color:#2C3E8F;">Total</span>
                        <div class="stat-label">Applications</div>
                        <div class="stat-value" style="color:var(--primary-blue);">{{ number_format($totalApplications) }}</div>
                        <div class="stat-sub">All time in {{ $municipality->name }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card yellow">
                    <div class="card-body">
                        <span class="stat-pill" style="background:#FFF3D6;color:#856404;">Pending</span>
                        <div class="stat-label">For Review</div>
                        <div class="stat-value" style="color:#856404;">{{ number_format($pendingApplications) }}</div>
                        <div class="stat-sub">Awaiting action</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card green">
                    <div class="card-body">
                        <span class="stat-pill" style="background:#d4edda;color:#155724;">Approved</span>
                        <div class="stat-label">Completed</div>
                        <div class="stat-value" style="color:#155724;">{{ number_format($approvedApplications) }}</div>
                        <div class="stat-sub">Successfully processed</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card red">
                    <div class="card-body">
                        <span class="stat-pill" style="background:#FCE8E8;color:#C41E24;">Rejected</span>
                        <div class="stat-label">Declined</div>
                        <div class="stat-value" style="color:#C41E24;">{{ number_format($rejectedApplications) }}</div>
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
                        <span class="panel-header-badge">4 shortcuts</span>
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
                                <span class="action-sub">View comparative data across all three municipalities</span>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
