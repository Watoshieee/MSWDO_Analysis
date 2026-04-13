<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detailed Analysis – {{ $municipality->name }} – MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @include('components.admin-colors')
    <style>
html, body { overscroll-behavior: none; margin: 0; padding: 0; }

        :root {
            --bg-light: #F8FAFC;
            --bg-white: #FFFFFF;
            --bg-soft-blue: #F0F5FF;
            --border-light: #E2E8F0;
            --text-dark: #1E293B;
        }

        * { box-sizing: border-box; }
        body { background: var(--bg-light); font-family: 'Inter', sans-serif; color: var(--text-dark); display: flex; flex-direction: column; min-height: 100vh; margin: 0; }
        a { text-decoration: none; }

        /* ── NAVBAR ── */
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,0.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.55rem; color: white !important; display: flex; align-items: center; gap: 12px; }
        .navbar-toggler { order: -1; }
        .navbar-brand { order: 0; margin-left: auto !important; margin-right: 0 !important; }
        @media (min-width: 992px) {
            .navbar-toggler { order: 0; }
            .navbar-brand { order: 0; margin-left: 0 !important; margin-right: auto !important; }
        }
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; transition: all 0.25s; border-radius: 8px; padding: 10px 18px !important; font-size: 0.93rem; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .user-info { color: white; display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.1); padding: 9px 22px; border-radius: 40px; font-size: 0.9rem; font-weight: 600; }
        .logout-btn { background: transparent; border: 2px solid rgba(255,255,255,0.8); color: white; border-radius: 30px; padding: 6px 18px; font-weight: 700; transition: all 0.3s; font-size: 0.88rem; cursor: pointer; }
        .logout-btn:hover { background: var(--secondary-yellow); color: var(--primary-blue); border-color: var(--secondary-yellow); }

        /* ── HERO BANNER ── */
        .hero-banner { background: var(--primary-gradient); color: white; padding: 44px 0 38px; position: relative; overflow: hidden; }
        .hero-banner::before { content: ''; position: absolute; top: -80px; right: -80px; width: 340px; height: 340px; border-radius: 50%; background: rgba(253,185,19,0.09); }
        .hero-banner::after  { content: ''; position: absolute; bottom: -60px; left: -40px; width: 230px; height: 230px; border-radius: 50%; background: rgba(255,255,255,0.05); }
        .hero-inner { position: relative; z-index: 2; }
        .hero-badge { display: inline-block; background: rgba(253,185,19,0.18); color: var(--secondary-yellow); border: 1px solid rgba(253,185,19,0.35); border-radius: 30px; padding: 4px 16px; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 10px; }
        .hero-banner h1 { font-size: 2rem; font-weight: 900; margin-bottom: 4px; }
        .hero-divider { width: 44px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; margin: 10px 0 8px; }
        .hero-banner p { opacity: 0.82; font-size: 0.93rem; margin: 0; }
        .muni-badge-lg { background: rgba(253,185,19,0.18); border: 1px solid rgba(253,185,19,0.35); color: var(--secondary-yellow); border-radius: 12px; padding: 14px 24px; text-align: center; }
        .muni-badge-lg .muni-name { font-size: 1.35rem; font-weight: 900; display: block; }
        .muni-badge-lg .muni-sub  { font-size: 0.72rem; opacity: 0.75; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; }

        /* ── STAT CARDS ── */
        .stat-card { background: var(--bg-white); border-radius: 18px; border: 1px solid var(--border-light); box-shadow: 0 4px 15px rgba(0,0,0,0.03); height: 100%; transition: all 0.3s; position: relative; overflow: hidden; }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--primary-gradient); }
        .stat-card.yellow::before { background: var(--secondary-gradient); }
        .stat-card.green::before  { background: linear-gradient(135deg,#28a745,#1e7e34); }
        .stat-card.red::before    { background: linear-gradient(135deg,#C41E24,#8B0000); }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(44,62,143,0.10); }
        .stat-card .inner { padding: 24px 26px; }
        .stat-pill  { font-size: 0.68rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; border-radius: 20px; padding: 3px 10px; display: inline-block; margin-bottom: 8px; background: #E5EEFF; color: var(--primary-blue); }
        .stat-pill.y { background: #FFF3D6; color: #856404; }
        .stat-pill.g { background: #d4edda; color: #155724; }
        .stat-pill.r { background: #fce8e8; color: #C41E24; }
        .stat-label { font-size: 0.78rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
        .stat-value { font-size: 2.6rem; font-weight: 900; line-height: 1; }
        .stat-sub   { font-size: 0.75rem; color: #94a3b8; margin-top: 6px; font-weight: 500; }

        /* ── PANEL CARDS ── */
        .panel-card { background: var(--bg-white); border-radius: 20px; border: 1px solid var(--border-light); box-shadow: 0 4px 15px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 24px; }
        .panel-header { background: var(--primary-gradient); color: white; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between; }
        .panel-header-title { font-size: 1rem; font-weight: 800; }
        .panel-header-sub   { font-size: 0.76rem; opacity: 0.75; margin-top: 2px; font-weight: 500; }
        .panel-header-badge { font-size: 0.7rem; background: rgba(255,255,255,0.15); border-radius: 20px; padding: 4px 14px; font-weight: 700; }
        .panel-body { padding: 26px; }

        /* ── SECTION TITLE ── */
        .section-title { font-size: 1.05rem; font-weight: 800; color: var(--primary-blue); position: relative; padding-bottom: 10px; margin-bottom: 0; }
        .section-title::after { content: ''; position: absolute; bottom: 0; left: 0; width: 32px; height: 3px; background: var(--secondary-yellow); border-radius: 2px; }

        /* ── CHARTS ── */
        .chart-wrap { position: relative; }
        .chart-wrap canvas { max-width: 100%; }

        /* ── STATUS BREAKDOWN ── */
        .breakdown-list { list-style: none; padding: 0; margin: 0; }
        .breakdown-item { display: flex; align-items: center; justify-content: space-between; padding: 13px 0; border-bottom: 1px solid var(--border-light); }
        .breakdown-item:last-child { border-bottom: none; }
        .status-dot { width: 12px; height: 12px; border-radius: 50%; display: inline-block; margin-right: 10px; flex-shrink: 0; }
        .breakdown-label { display: flex; align-items: center; font-size: 0.9rem; font-weight: 600; }
        .breakdown-right { text-align: right; }
        .breakdown-count { font-size: 1.2rem; font-weight: 900; color: var(--primary-blue); display: block; }
        .breakdown-pct   { font-size: 0.72rem; color: #94a3b8; font-weight: 500; }

        /* ── PROGRESS BARS (program rows) ── */
        .prog-row { display: flex; align-items: center; gap: 14px; padding: 11px 0; border-bottom: 1px solid var(--border-light); }
        .prog-row:last-child { border-bottom: none; }
        .prog-num  { font-size: 0.65rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; background: var(--bg-soft-blue); color: var(--primary-blue); border-radius: 20px; padding: 2px 10px; flex-shrink: 0; }
        .prog-name { font-size: 0.88rem; font-weight: 700; flex: 1; min-width: 120px; }
        .prog-bar-wrap { flex: 2; height: 8px; background: #E2E8F0; border-radius: 4px; overflow: hidden; }
        .prog-bar  { height: 100%; border-radius: 4px; background: var(--primary-gradient); }
        .prog-count { font-size: 0.88rem; font-weight: 800; color: var(--primary-blue); min-width: 28px; text-align: right; }

        /* ── BARANGAY TABLE ── */
        .table-scroll { max-height: 400px; overflow-y: auto; border-radius: 0 0 14px 14px; }
        .bgy-table { width: 100%; }
        .bgy-table th { font-size: 0.71rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.07em; color: #64748b; padding: 10px 14px; background: var(--bg-soft-blue); border-bottom: 2px solid var(--border-light); position: sticky; top: 0; z-index: 2; }
        .bgy-table td { padding: 11px 14px; font-size: 0.86rem; border-bottom: 1px solid #F1F5F9; vertical-align: middle; }
        .bgy-table tr:last-child td { border-bottom: none; }
        .bgy-table tr:hover td { background: #FAFBFF; }
        .badge-sm { font-size: 0.68rem; font-weight: 800; border-radius: 20px; padding: 3px 10px; letter-spacing: 0.04em; text-transform: uppercase; display: inline-block; }
        .badge-pending  { background: #FFF3D6; color: #856404; }
        .badge-approved { background: #d4edda; color: #155724; }
        .badge-rejected { background: #fce8e8; color: #C41E24; }
        .no-data { text-align: center; padding: 40px 0; color: #94a3b8; font-size: 0.88rem; }

        /* ── MAIN GROW ── */
        .main-content { flex: 1; }

        /* ── FOOTER ── */
        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,0.75); text-align: center; padding: 20px 0; font-size: 0.85rem; margin-top: 48px; }
        .footer-strip strong { color: white; }
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
                    <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.requirements*') ? 'active' : '' }}" href="{{ route('admin.requirements') }}">Applications</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.data.*') ? 'active' : '' }}" href="{{ route('admin.data.dashboard') }}">Data Management</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.detailed-analysis') }}">Analysis</a></li>
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
                        <div class="hero-badge">Analysis</div>
                        <h1>Detailed Analysis</h1>
                        <div class="hero-divider"></div>
                        <p>Application statistics, program breakdown, and barangay-level data for {{ $municipality->name }}.</p>
                    </div>
                    <div class="col-md-4 d-none d-md-flex justify-content-end">
                        <div class="muni-badge-lg">
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

        <!-- STAT CARDS -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="inner">
                        <span class="stat-pill">Total</span>
                        <div class="stat-label">Applications</div>
                        <div class="stat-value" style="color:var(--primary-blue);">{{ number_format($totalApplications) }}</div>
                        <div class="stat-sub">All time in {{ $municipality->name }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card yellow">
                    <div class="inner">
                        <span class="stat-pill y">Pending</span>
                        <div class="stat-label">For Review</div>
                        <div class="stat-value" style="color:#856404;">{{ number_format($pendingApplications) }}</div>
                        <div class="stat-sub">Awaiting action</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card green">
                    <div class="inner">
                        <span class="stat-pill g">Approved</span>
                        <div class="stat-label">Completed</div>
                        <div class="stat-value" style="color:#155724;">{{ number_format($approvedApplications) }}</div>
                        <div class="stat-sub">Successfully processed</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card red">
                    <div class="inner">
                        <span class="stat-pill r">Rejected</span>
                        <div class="stat-label">Declined</div>
                        <div class="stat-value" style="color:#C41E24;">{{ number_format($rejectedApplications) }}</div>
                        <div class="stat-sub">Did not qualify</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 1: Program Chart + Status Doughnut -->
        <div class="row g-4 mb-0">

            <!-- Applications by Program (bar chart) -->
            <div class="col-md-7">
                <div class="panel-card">
                    <div class="panel-header">
                        <div>
                            <div class="panel-header-title">Applications by Program</div>
                            <div class="panel-header-sub">Pending, Approved &amp; Rejected per program</div>
                        </div>
                        <span class="panel-header-badge">{{ $applicationsByProgram->count() }} programs</span>
                    </div>
                    <div class="panel-body">
                        <div class="chart-wrap" style="height:320px;">
                            <canvas id="programChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Doughnut + Breakdown -->
            <div class="col-md-5">
                <div class="panel-card">
                    <div class="panel-header">
                        <div>
                            <div class="panel-header-title">Status Distribution</div>
                            <div class="panel-header-sub">Overall application outcomes</div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="chart-wrap" style="height:200px;" class="mb-3">
                            <canvas id="statusChart"></canvas>
                        </div>
                        <ul class="breakdown-list mt-3">
                            <li class="breakdown-item">
                                <div class="breakdown-label"><span class="status-dot" style="background:#FDB913;"></span> Pending</div>
                                <div class="breakdown-right">
                                    <span class="breakdown-count" style="color:#856404;">{{ $pendingApplications }}</span>
                                    <span class="breakdown-pct">{{ $totalApplications > 0 ? round(($pendingApplications/$totalApplications)*100,1) : 0 }}%</span>
                                </div>
                            </li>
                            <li class="breakdown-item">
                                <div class="breakdown-label"><span class="status-dot" style="background:#28a745;"></span> Approved</div>
                                <div class="breakdown-right">
                                    <span class="breakdown-count" style="color:#155724;">{{ $approvedApplications }}</span>
                                    <span class="breakdown-pct">{{ $totalApplications > 0 ? round(($approvedApplications/$totalApplications)*100,1) : 0 }}%</span>
                                </div>
                            </li>
                            <li class="breakdown-item">
                                <div class="breakdown-label"><span class="status-dot" style="background:#C41E24;"></span> Rejected</div>
                                <div class="breakdown-right">
                                    <span class="breakdown-count" style="color:#C41E24;">{{ $rejectedApplications }}</span>
                                    <span class="breakdown-pct">{{ $totalApplications > 0 ? round(($rejectedApplications/$totalApplications)*100,1) : 0 }}%</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>

        <!-- ROW 2: Program Progress Bars -->
        <div class="row g-4 mb-0 mt-0">
            <div class="col-12">
                <div class="panel-card">
                    <div class="panel-header">
                        <div>
                            <div class="panel-header-title">Program Share Overview</div>
                            <div class="panel-header-sub">Relative volume of applications per program</div>
                        </div>
                    </div>
                    <div class="panel-body">
                        @php $maxProg = $applicationsByProgram->max('total') ?: 1; $pIdx = 1; @endphp
                        @forelse($applicationsByProgram as $program => $stats)
                        <div class="prog-row">
                            <span class="prog-num">{{ str_pad($pIdx++, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="prog-name">{{ str_replace('_', ' ', $program) }}</span>
                            <div class="prog-bar-wrap">
                                <div class="prog-bar" style="width:{{ ($stats['total']/$maxProg)*100 }}%"></div>
                            </div>
                            <span class="prog-count">{{ $stats['total'] }}</span>
                        </div>
                        @empty
                        <div class="no-data">No program data available.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 3: Barangay Chart + Table -->
        <div class="row g-4 mb-0 mt-0">

            <!-- Barangay Bar Chart -->
            <div class="col-md-7">
                <div class="panel-card">
                    <div class="panel-header">
                        <div>
                            <div class="panel-header-title">Barangay-level Statistics</div>
                            <div class="panel-header-sub">Applications per barangay (top 15)</div>
                        </div>
                        <span class="panel-header-badge">{{ count($barangayStats) }} barangays</span>
                    </div>
                    <div class="panel-body">
                        @if(count($barangayStats) > 0)
                        <div class="chart-wrap" style="height:360px;">
                            <canvas id="barangayChart"></canvas>
                        </div>
                        @else
                        <div class="no-data">No barangay data available.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Barangay Table -->
            <div class="col-md-5">
                <div class="panel-card" style="height:100%;">
                    <div class="panel-header">
                        <div>
                            <div class="panel-header-title">Barangay Data Table</div>
                            <div class="panel-header-sub">Population, households &amp; applications</div>
                        </div>
                    </div>
                    <div class="table-scroll">
                        <table class="bgy-table">
                            <thead>
                                <tr>
                                    <th>Barangay</th>
                                    <th>Total</th>
                                    <th>Pending</th>
                                    <th>Approved</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($barangayStats as $name => $stats)
                                <tr>
                                    <td><strong>{{ $name }}</strong><br><span style="font-size:0.73rem;color:#94a3b8;">Pop: {{ number_format($stats['population']) }}</span></td>
                                    <td><strong>{{ $stats['total'] }}</strong></td>
                                    <td><span class="badge-sm badge-pending">{{ $stats['pending'] }}</span></td>
                                    <td><span class="badge-sm badge-approved">{{ $stats['approved'] }}</span></td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="no-data">No data available.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>
    </div>

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    @include('components.admin-settings-modal')
    @include('components.admin-chat-modal')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ── SHARED DEFAULTS ──
        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.font.size   = 12;
        Chart.defaults.color       = '#64748b';

        // ── 1. APPLICATIONS BY PROGRAM (grouped bar) ──
        const progLabels = {!! json_encode($applicationsByProgram->keys()->map(fn($k) => str_replace('_', ' ', $k))) !!};
        const progPending  = {!! json_encode($applicationsByProgram->pluck('pending'))  !!};
        const progApproved = {!! json_encode($applicationsByProgram->pluck('approved')) !!};
        const progRejected = {!! json_encode($applicationsByProgram->pluck('rejected')) !!};

        new Chart(document.getElementById('programChart'), {
            type: 'bar',
            data: {
                labels: progLabels,
                datasets: [
                    {
                        label: 'Pending',
                        data: progPending,
                        backgroundColor: 'rgba(253,185,19,0.85)',
                        borderRadius: { topLeft: 6, topRight: 6 },
                        borderSkipped: false,
                        barPercentage: 0.65,
                    },
                    {
                        label: 'Approved',
                        data: progApproved,
                        backgroundColor: 'rgba(44,62,143,0.85)',
                        borderRadius: { topLeft: 6, topRight: 6 },
                        borderSkipped: false,
                        barPercentage: 0.65,
                    },
                    {
                        label: 'Rejected',
                        data: progRejected,
                        backgroundColor: 'rgba(196,30,36,0.80)',
                        borderRadius: { topLeft: 6, topRight: 6 },
                        borderSkipped: false,
                        barPercentage: 0.65,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { usePointStyle: true, pointStyle: 'circle', padding: 18, font: { weight: '600' } }
                    },
                    tooltip: {
                        backgroundColor: '#1E293B',
                        padding: 12,
                        cornerRadius: 10,
                        bodyFont: { size: 13 },
                        titleFont: { size: 13, weight: 'bold' }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, font: { size: 11 } },
                        grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    }
                }
            }
        });

        // ── 2. STATUS DOUGHNUT ──
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Approved', 'Rejected'],
                datasets: [{
                    data: [{{ $pendingApplications }}, {{ $approvedApplications }}, {{ $rejectedApplications }}],
                    backgroundColor: ['#FDB913', '#2C3E8F', '#C41E24'],
                    hoverBackgroundColor: ['#E5A500', '#1A2A5C', '#9B1217'],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1E293B',
                        padding: 10,
                        cornerRadius: 10,
                        callbacks: {
                            label: (ctx) => ` ${ctx.label}: ${ctx.parsed} (${ctx.dataset.data.reduce((a,b) => a+b,0) > 0 ? Math.round(ctx.parsed/ctx.dataset.data.reduce((a,b)=>a+b,0)*100) : 0}%)`
                        }
                    }
                }
            }
        });

        // ── 3. BARANGAY BAR CHART ──
        @php
            $bgyDisplay = collect($barangayStats)
                ->sortByDesc(fn($s) => $s['total'])
                ->take(15);
        @endphp

        @if(count($barangayStats) > 0)
        const bgyLabels   = {!! json_encode($bgyDisplay->keys()->values()) !!};
        const bgyTotal    = {!! json_encode($bgyDisplay->pluck('total')->values()) !!};
        const bgyPending  = {!! json_encode($bgyDisplay->pluck('pending')->values()) !!};
        const bgyApproved = {!! json_encode($bgyDisplay->pluck('approved')->values()) !!};

        new Chart(document.getElementById('barangayChart'), {
            type: 'bar',
            data: {
                labels: bgyLabels,
                datasets: [
                    {
                        label: 'Approved',
                        data: bgyApproved,
                        backgroundColor: 'rgba(44,62,143,0.85)',
                        borderRadius: { topLeft: 5, topRight: 5 },
                        borderSkipped: false,
                        barPercentage: 0.6,
                    },
                    {
                        label: 'Pending',
                        data: bgyPending,
                        backgroundColor: 'rgba(253,185,19,0.85)',
                        borderRadius: { topLeft: 5, topRight: 5 },
                        borderSkipped: false,
                        barPercentage: 0.6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',   // Horizontal bar — cleaner for many barangay names
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { usePointStyle: true, pointStyle: 'circle', padding: 16, font: { weight: '600' } }
                    },
                    tooltip: {
                        backgroundColor: '#1E293B',
                        padding: 12,
                        cornerRadius: 10
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        stacked: true,
                        ticks: { stepSize: 1, font: { size: 11 } },
                        grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false }
                    },
                    y: {
                        stacked: true,
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });
        @endif
    </script>
</body>
</html>
