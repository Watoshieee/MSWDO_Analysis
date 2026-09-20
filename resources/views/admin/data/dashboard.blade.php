<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Management – MSWDO Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
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

        /* NAVBAR */
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,0.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.55rem; color: white !important; display: flex; align-items: center; gap: 12px; }
        .navbar-toggler { order: -1; }
        .navbar-brand { order: 0; margin-left: auto !important; margin-right: 0 !important; }
        @media (min-width: 992px) {
            .navbar-toggler { order: 0; }
            .navbar-brand { order: 0; margin-left: 0 !important; margin-right: auto !important; }
        }
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; transition: all 0.25s; border-radius: 8px; padding: 10px 18px !important; font-size: 0.85rem; white-space: nowrap; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .user-info { color: white; display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.1); padding: 9px 22px; border-radius: 40px; font-size: 0.9rem; font-weight: 600; }
        .logout-btn { background: transparent; border: 2px solid rgba(255,255,255,0.8); color: white; border-radius: 30px; padding: 6px 18px; font-weight: 700; transition: all 0.3s; font-size: 0.88rem; cursor: pointer; }
        .logout-btn:hover { background: var(--secondary-yellow); color: var(--primary-blue); border-color: var(--secondary-yellow); }

        /* HERO */
        .hero-banner { background: var(--primary-gradient); color: white; padding: 44px 0 38px; position: relative; overflow: hidden; }
        .hero-banner::before { content: ''; position: absolute; top: -80px; right: -80px; width: 340px; height: 340px; border-radius: 50%; background: rgba(253,185,19,0.09); }
        .hero-banner::after  { content: ''; position: absolute; bottom: -60px; left: -40px; width: 230px; height: 230px; border-radius: 50%; background: rgba(255,255,255,0.05); }
        .hero-inner { position: relative; z-index: 2; }
        .hero-badge { display: inline-block; background: rgba(253,185,19,0.18); color: var(--secondary-yellow); border: 1px solid rgba(253,185,19,0.35); border-radius: 30px; padding: 4px 16px; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 10px; }
        .hero-banner h1 { font-size: 2rem; font-weight: 900; margin-bottom: 4px; }
        .hero-divider { width: 44px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; margin: 10px 0 8px; }
        .hero-banner p { opacity: 0.82; font-size: 0.85rem; margin: 0; }
        .muni-badge-lg { background: rgba(253,185,19,0.18); border: 1px solid rgba(253,185,19,0.35); color: var(--secondary-yellow); border-radius: 12px; padding: 14px 24px; text-align: center; }
        .muni-badge-lg .muni-name { font-size: 1.35rem; font-weight: 900; display: block; }
        .muni-badge-lg .muni-sub  { font-size: 0.72rem; opacity: 0.75; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; }

        /* STAT CARDS */
        .stat-card { background: #EEF2FF; border-radius: 18px; border: 1px solid #C7D6F5; box-shadow: 0 8px 36px rgba(44,62,143,0.18); height: 100%; position: relative; overflow: hidden; }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--primary-gradient); }
        .stat-card .inner { padding: 26px 28px; }
        .stat-pill  { font-size: 0.68rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; background: #D8E4FF; color: var(--primary-blue); border-radius: 20px; padding: 3px 10px; display: inline-block; margin-bottom: 10px; }
        .stat-label { font-size: 0.78rem; font-weight: 600; color: #6278b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px; }
        .stat-value { font-size: 2.6rem; font-weight: 900; color: var(--primary-blue); line-height: 1; }
        .stat-sub   { font-size: 0.75rem; color: #6278b8; margin-top: 8px; font-weight: 500; }

        /* SECTION HEADING */
        .section-heading { font-size: 1.05rem; font-weight: 800; color: var(--primary-blue); position: relative; padding-bottom: 10px; margin-bottom: 20px; }
        .section-heading::after { content: ''; position: absolute; bottom: 0; left: 0; width: 36px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; }

        /* MENU CARDS — Quick Actions style */
        .menu-card { display: flex; align-items: center; gap: 14px; padding: 16px 18px; border-radius: 14px; background: var(--primary-gradient); color: white; border: none; transition: all 0.25s ease; margin-bottom: 0; text-decoration: none; box-shadow: 0 3px 14px rgba(44,62,143,0.18); height: 100%; }
        .menu-card:hover { box-shadow: 0 10px 28px rgba(44,62,143,0.32); transform: translateY(-3px); color: white; }
        .menu-text  { flex: 1; }
        .menu-num   { font-size: 0.6rem; font-weight: 800; letter-spacing: 0.12em; text-transform: uppercase; color: rgba(253,185,19,0.9); display: block; margin-bottom: 4px; }
        .menu-title { font-size: 1rem; font-weight: 800; color: white; display: block; margin-bottom: 4px; line-height: 1.25; }
        .menu-desc  { font-size: 0.76rem; color: rgba(255,255,255,0.72); display: block; line-height: 1.5; }
        .menu-arrow { font-size: 1.4rem; color: rgba(255,255,255,0.55); flex-shrink: 0; transition: color 0.2s; }
        .menu-card:hover .menu-arrow { color: var(--secondary-yellow); }

        /* ALERT */
        .alert-success-c { border-radius: 12px; font-size: 0.88rem; padding: 12px 16px; margin-bottom: 16px; background: #d4edda; border-left: 4px solid #28a745; color: #155724; border: none; }

        /* MAIN GROW */
        .main-content { flex: 1; }

        /* YEAR BUTTONS */
        .year-btn:hover { transform: translateY(-2px); filter: brightness(0.92); box-shadow: 0 4px 12px rgba(44,62,143,0.15); }

        /* EXPORT BUTTONS */
        .export-btn { border-radius: 8px; font-weight: 700; font-size: 0.83rem; transition: all 0.2s ease; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; white-space: nowrap; line-height: 1.4; }
        .export-btn-csv { background: #FFFFFF; color: var(--primary-blue); border: 1.5px solid var(--primary-blue); padding: 7px 15px; box-shadow: 0 2px 6px rgba(44,62,143,0.08); }
        .export-btn-csv:hover { background: var(--primary-blue); color: #FFFFFF; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(44,62,143,0.18); }
        .export-btn-report { background: var(--primary-gradient); color: #FFFFFF; border: none; padding: 8.5px 16px; box-shadow: 0 3px 12px rgba(44,62,143,0.22); }
        .export-btn-report:hover { filter: brightness(1.08); transform: translateY(-1px); color: #FFFFFF; box-shadow: 0 6px 16px rgba(44,62,143,0.30); }
        .export-btn-options { background: #E2E8F0; color: #475569; border: none; padding: 8.5px 12px; }
        .export-btn-options:hover { background: #CBD5E1; color: #1E293B; transform: translateY(-1px); }

        /* FOOTER */
        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,0.75); text-align: center; padding: 20px 0; font-size: 0.85rem; margin-top: 48px; }
        .footer-strip strong { color: white; }
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
                    <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.requirements*') ? 'active' : '' }}" href="{{ route('admin.requirements') }}">Applications</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">Users Management</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.data.dashboard') }}">Data Management</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}" href="{{ route('admin.announcements.index') }}">Announcements</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.detailed-analysis') }}">Analysis</a></li>
                    <li class="nav-item"><a class="nav-link" href="/analysis/programs">Comparative Analysis</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    @auth
                    <button type="button" class="btn" onclick="openAdminNotifModal()"
                        style="background:rgba(255,255,255,0.1);color:white;border:none;border-radius:50%;width:40px;height:40px;font-size:1.1rem;display:flex;align-items:center;justify-content:center;padding:0;transition:all 0.3s;position:relative;"
                        title="Application Notifications">
                        <i class="bi bi-bell-fill"></i>
                        @if(isset($adminNotifCount) && $adminNotifCount > 0)
                            <span class="admin-bell-badge"
                                style="position:absolute;top:-4px;right:-4px;background:#dc3545;color:white;border-radius:50%;width:20px;height:20px;font-size:0.7rem;font-weight:800;display:flex;align-items:center;justify-content:center;border:2px solid #2C3E8F;">{{ $adminNotifCount > 9 ? '9+' : $adminNotifCount }}</span>
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
                        <div class="hero-badge">Data Management</div>
                        <h1>Manage Your Data</h1>
                        <div class="hero-divider"></div>
                        <p>Update municipality profiles and social welfare program data for {{ $municipality->name }}.</p>
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

        @include('components.admin-notification')

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:12px; font-weight:600; font-size:0.88rem;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:12px; font-weight:600; font-size:0.88rem;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- YEAR FILTERS & EXPORT ACTIONS -->
        <div style="margin: 8px 0 24px; display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:15px;">
            <div>
                <div class="section-heading" style="margin-bottom:0; padding-bottom:8px;">Dashboard Overview</div>
                <div style="font-size:0.85rem; color:#64748b;">Showing stats for selected year.</div>
            </div>
            
            <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:10px;">
                <!-- Year Filter Pills -->
                <div class="year-filter-buttons" style="display:flex;gap:6px;flex-wrap:wrap;">
                    @foreach($allYears as $year)
                    <a href="{{ route('admin.data.dashboard') }}?year={{ $year }}" class="year-btn" style="background:{{ request('year', $currentYear) == $year ? 'var(--primary-gradient)' : '#E2E8F0' }};color:{{ request('year', $currentYear) == $year ? 'white' : '#64748b' }};border:none;border-radius:8px;padding:8px 16px;font-size:0.85rem;font-weight:700;text-decoration:none;transition:all 0.2s;">{{ $year }}</a>
                    @endforeach
                </div>

                <!-- Export Action Buttons -->
                <div class="d-flex align-items-center gap-2">
                    <!-- Export CSV / Raw Data Button -->
                    <form method="POST" action="{{ route('admin.data.export.csv') }}" class="d-inline m-0">
                        @csrf
                        <input type="hidden" name="year" value="{{ request('year', $currentYear) }}">
                        <button type="submit" class="export-btn export-btn-csv" title="Export raw data CSV files, DSS/WSM, and graphs in a ZIP package for {{ $municipality->name }}">
                            <i class="bi bi-file-earmark-zip me-1"></i> Export CSV
                        </button>
                    </form>

                    <!-- Export Analysis Report Button -->
                    <form method="POST" action="{{ route('admin.data.export.report') }}" class="d-inline m-0">
                        @csrf
                        <input type="hidden" name="year" value="{{ request('year', $currentYear) }}">
                        <button type="submit" class="export-btn export-btn-report" title="Export complete Excel Analysis Report (.xlsx) with DSS, WSM, and Graphs for {{ $municipality->name }}">
                            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export Analysis Report
                        </button>
                    </form>

                    <!-- More Export Options (Modal Trigger) -->
                    <button type="button" class="export-btn export-btn-options" data-bs-toggle="modal" data-bs-target="#exportOptionsModal" title="More Export Options (All Years, Comparative)">
                        <i class="bi bi-sliders"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- STAT CARDS -->
        <div class="row g-3 mb-5">
            <div class="col-md-6">
                <div class="stat-card">
                    <div class="inner">
                        <span class="stat-pill">Population</span>
                        <div class="stat-label">Total Residents</div>
                        <div class="stat-value">{{ number_format($totalPopulation ?? 0) }}</div>
                        <div class="stat-sub">Total Population</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stat-card">
                    <div class="inner">
                        <span class="stat-pill">Beneficiaries</span>
                        <div class="stat-label">Program Beneficiaries</div>
                        <div class="stat-value">{{ number_format($beneficiaries) }}</div>
                        <div class="stat-sub">Across all programs</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MENU CARDS -->
        <p class="section-heading" id="manage-data-section">Manage Your Data</p>
        <div class="row g-4 mb-4">
            <div class="col-md-4" id="municipality-card">
                <a href="{{ route('admin.data.municipality') }}#return" class="menu-card">
                    <div class="menu-text">
                        <span class="menu-num">01 &mdash; Municipality</span>
                        <span class="menu-title">Municipality Profile</span>
                        <span class="menu-desc">Update population, households, age groups, and program beneficiary counts for {{ $municipality->name }}.</span>
                    </div>
                    <span class="menu-arrow">&rsaquo;</span>
                </a>
            </div>
            <div class="col-md-4" id="barangay-card">
                <a href="{{ route('admin.data.barangays') }}#return" class="menu-card">
                    <div class="menu-text">
                        <span class="menu-num">02 &mdash; Barangays</span>
                        <span class="menu-title">Barangay Data</span>
                        <span class="menu-desc">Manage barangay-level population, households, and program counts for {{ $municipality->name }} (same tools as super admin, scoped to your municipality).</span>
                    </div>
                    <span class="menu-arrow">&rsaquo;</span>
                </a>
            </div>
            <div class="col-md-4" id="programs-card">
                <a href="{{ route('admin.data.programs') }}#return" class="menu-card">
                    <div class="menu-text">
                        <span class="menu-num">03 &mdash; Programs</span>
                        <span class="menu-title">Social Programs</span>
                        <span class="menu-desc">Manage social welfare program beneficiaries, enrollment data, and yearly records.</span>
                    </div>
                    <span class="menu-arrow">&rsaquo;</span>
                </a>
            </div>
            <div class="col-md-4" id="yearly-card">
                <a href="{{ route('admin.data.yearly') }}#return" class="menu-card">
                    <div class="menu-text">
                        <span class="menu-num">04 &mdash; Yearly Summary</span>
                        <span class="menu-title">Municipality Yearly Data</span>
                        <span class="menu-desc">Add and manage yearly population, household, PWD, AICS, and Solo Parent summary records for {{ $municipality->name }}.</span>
                    </div>
                    <span class="menu-arrow">&rsaquo;</span>
                </a>
            </div>
        </div>

    </div>
    </div>

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script>
        // Scroll to the section when returning from subpages
        window.addEventListener('DOMContentLoaded', function() {
            if (window.location.hash === '#return') {
                const section = document.getElementById('manage-data-section');
                if (section) {
                    setTimeout(() => {
                        section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 100);
                }
            }
        });
    </script>
    <!-- EXPORT OPTIONS MODAL -->
    <div class="modal fade" id="exportOptionsModal" tabindex="-1" aria-labelledby="exportOptionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border:none; border-radius:18px; overflow:hidden; box-shadow:0 12px 48px rgba(44,62,143,0.22);">
                <div class="modal-header" style="background:var(--primary-gradient); color:white; padding:18px 24px;">
                    <div>
                        <div style="font-size:0.72rem; font-weight:800; letter-spacing:0.1em; text-transform:uppercase; color:var(--secondary-yellow); margin-bottom:2px;">
                            DATA MANAGEMENT &bull; EXPORT CENTER
                        </div>
                        <h5 class="modal-title" id="exportOptionsModalLabel" style="font-weight:900; font-size:1.25rem; margin:0;">
                            Export Data &amp; Analysis Reports
                        </h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="background:#F8FAFC; padding:24px;">
                    <!-- Municipality & Scope Info -->
                    <div style="background:#FFFFFF; border:1px solid #E2E8F0; border-radius:12px; padding:14px 18px; margin-bottom:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                        <div>
                            <span style="font-size:0.72rem; font-weight:700; text-transform:uppercase; color:#64748B; letter-spacing:0.06em; display:block;">Active Municipality</span>
                            <strong style="color:var(--primary-blue); font-size:1.1rem;">{{ $municipality->name }}</strong>
                        </div>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <label for="exportYearSelect" style="font-size:0.8rem; font-weight:700; color:#475569;">Target Year:</label>
                            <select id="exportYearSelect" class="form-select form-select-sm" style="width:auto; font-weight:700; border-radius:8px; border-color:#CBD5E1;" onchange="updateExportForms(this.value)">
                                <option value="{{ request('year', $currentYear) }}" selected>Selected Year ({{ request('year', $currentYear) }})</option>
                                <option value="all">All Available Years</option>
                                @foreach($allYears as $y)
                                    @if($y != request('year', $currentYear))
                                    <option value="{{ $y }}">Year {{ $y }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Export Option Cards -->
                    <div class="row g-3">
                        <!-- Option A: CSV / Raw Data -->
                        <div class="col-md-6">
                            <div style="background:#FFFFFF; border:1px solid #C7D6F5; border-radius:14px; padding:20px; height:100%; display:flex; flex-direction:column; justify-content:space-between; box-shadow:0 4px 16px rgba(44,62,143,0.06);">
                                <div>
                                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                                        <div style="width:38px; height:38px; border-radius:10px; background:#EEF2FF; color:var(--primary-blue); display:flex; align-items:center; justify-content:center; font-size:1.2rem;">
                                            <i class="bi bi-file-earmark-zip"></i>
                                        </div>
                                        <div>
                                            <span style="font-size:0.65rem; font-weight:800; letter-spacing:0.08em; text-transform:uppercase; color:var(--primary-blue);">Option A</span>
                                            <h6 style="font-weight:800; color:var(--text-dark); margin:0; font-size:0.95rem;">Export CSV / Raw Data</h6>
                                        </div>
                                    </div>
                                    <p style="font-size:0.78rem; color:#64748B; margin-bottom:12px; line-height:1.5;">
                                        Downloads a complete ZIP package containing UTF-8 CSV raw data tables, DSS &amp; WSM calculations, 7 high-res PNG visualization charts, and methodology README.
                                    </p>
                                    <ul style="font-size:0.73rem; color:#475569; padding-left:18px; margin-bottom:16px;">
                                        <li>01_Municipality_Yearly_Data.csv</li>
                                        <li>02_Barangay_Data.csv</li>
                                        <li>03_Social_Programs.csv</li>
                                        <li>04_DSS_WSM.csv</li>
                                        <li>05_Graphs/ (7 PNG charts)</li>
                                        <li>README.txt (WSM formulas &amp; weights)</li>
                                    </ul>
                                </div>
                                <form id="modalCsvForm" method="POST" action="{{ route('admin.data.export.csv') }}">
                                    @csrf
                                    <input type="hidden" name="year" id="modalCsvYear" value="{{ request('year', $currentYear) }}">
                                    <button type="submit" class="btn w-100" style="background:#FFFFFF; color:var(--primary-blue); border:2px solid var(--primary-blue); font-weight:800; font-size:0.85rem; border-radius:10px; padding:9px 16px;">
                                        <i class="bi bi-download me-1"></i> Download ZIP Package
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Option B: Analysis Report Excel -->
                        <div class="col-md-6">
                            <div style="background:#FFFFFF; border:1px solid #FCD34D; border-radius:14px; padding:20px; height:100%; display:flex; flex-direction:column; justify-content:space-between; box-shadow:0 4px 16px rgba(253,185,19,0.12);">
                                <div>
                                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                                        <div style="width:38px; height:38px; border-radius:10px; background:#FEF3C7; color:#B45309; display:flex; align-items:center; justify-content:center; font-size:1.2rem;">
                                            <i class="bi bi-file-earmark-spreadsheet"></i>
                                        </div>
                                        <div>
                                            <span style="font-size:0.65rem; font-weight:800; letter-spacing:0.08em; text-transform:uppercase; color:#B45309;">Option B</span>
                                            <h6 style="font-weight:800; color:var(--text-dark); margin:0; font-size:0.95rem;">Export Analysis Report</h6>
                                        </div>
                                    </div>
                                    <p style="font-size:0.78rem; color:#64748B; margin-bottom:12px; line-height:1.5;">
                                        Generates a professional Excel workbook (.xlsx) organized into 10 structured worksheets with formatted KPI cards, YoY analysis, DSS prevalence rates, WSM priority scores, and embedded charts.
                                    </p>
                                    <ul style="font-size:0.73rem; color:#475569; padding-left:18px; margin-bottom:16px;">
                                        <li>10 Structured Worksheets</li>
                                        <li>Executive Summary &amp; YoY Trends</li>
                                        <li>DSS &amp; Transparent WSM Calculations</li>
                                        <li>Embedded High-Resolution Charts</li>
                                        <li>Methodological Framework</li>
                                    </ul>
                                </div>
                                <form id="modalReportForm" method="POST" action="{{ route('admin.data.export.report') }}">
                                    @csrf
                                    <input type="hidden" name="year" id="modalReportYear" value="{{ request('year', $currentYear) }}">
                                    <button type="submit" class="btn w-100" style="background:var(--primary-gradient); color:white; border:none; font-weight:800; font-size:0.85rem; border-radius:10px; padding:10px 16px; box-shadow:0 4px 12px rgba(44,62,143,0.25);">
                                        <i class="bi bi-file-earmark-excel me-1"></i> Download Excel (.xlsx)
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Option C: Comparative Analysis Export -->
                        <div class="col-12">
                            <div style="background:#FFFFFF; border:1px solid #E2E8F0; border-radius:14px; padding:16px 20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px;">
                                <div>
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <span class="badge" style="background:#E0E7FF; color:var(--primary-blue); font-weight:800; font-size:0.68rem;">COMPARATIVE</span>
                                        <h6 style="font-weight:800; margin:0; font-size:0.92rem; color:var(--primary-blue);">Municipal Comparative Export</h6>
                                    </div>
                                    <p style="font-size:0.75rem; color:#64748B; margin:4px 0 0;">
                                        Compares demographic scale, beneficiary concentration, and calculated DSS/WSM indicators among <strong>Magdalena, Liliw, and Majayjay</strong>.
                                    </p>
                                </div>
                                <form method="POST" action="{{ route('admin.data.export.comparative') }}" class="m-0">
                                    @csrf
                                    <input type="hidden" name="year" id="modalCompYear" value="{{ request('year', $currentYear) }}">
                                    <button type="submit" class="btn btn-sm" style="background:#F1F5F9; color:#334155; border:1px solid #CBD5E1; font-weight:700; border-radius:8px; padding:7px 16px;">
                                        <i class="bi bi-bar-chart me-1"></i> Export Comparative CSV
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Note -->
                    <div style="margin-top:16px; font-size:0.72rem; color:#64748B; font-style:italic;">
                        <i class="bi bi-info-circle me-1"></i> The DSS/WSM result is a decision-support indicator calculated from the available MSWDO data and configured criteria. It does not replace professional assessment, field validation, or official MSWDO decision-making.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateExportForms(yearVal) {
            document.getElementById('modalCsvYear').value = yearVal;
            document.getElementById('modalReportYear').value = yearVal;
            const compYear = document.getElementById('modalCompYear');
            if (compYear) compYear.value = yearVal;
        }
    </script>

    @include('components.admin-notification-modal')
    @include('components.admin-settings-modal')
    @include('components.admin-chat-modal')
</body>
</html>



