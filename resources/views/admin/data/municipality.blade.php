<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $municipality->name }} Profile – MSWDO</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; transition: all 0.25s; border-radius: 8px; padding: 10px 18px !important; font-size: 0.85rem; white-space: nowrap; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .user-info { color: white; display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.1); padding: 9px 22px; border-radius: 40px; font-size: 0.9rem; font-weight: 600; }
        .logout-btn { background: transparent; border: 2px solid rgba(255,255,255,0.8); color: white; border-radius: 30px; padding: 6px 18px; font-weight: 700; transition: all 0.3s; font-size: 0.88rem; cursor: pointer; }
        .logout-btn:hover { background: var(--secondary-yellow); color: var(--primary-blue); border-color: var(--secondary-yellow); }

        /* ── HERO ── */
        .hero-banner { background: var(--primary-gradient); color: white; padding: 36px 0 30px; position: relative; overflow: hidden; }
        .hero-banner::before { content: ''; position: absolute; top: -60px; right: -60px; width: 260px; height: 260px; border-radius: 50%; background: rgba(253,185,19,0.09); }
        .hero-inner { position: relative; z-index: 2; }
        .hero-badge { display: inline-block; background: rgba(253,185,19,0.18); color: var(--secondary-yellow); border: 1px solid rgba(253,185,19,0.35); border-radius: 30px; padding: 4px 16px; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 8px; }
        .hero-banner h1 { font-size: 1.75rem; font-weight: 900; margin-bottom: 4px; }
        .hero-divider { width: 40px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; margin: 8px 0 6px; }
        .hero-banner p { opacity: 0.82; font-size: 0.9rem; margin: 0; }
        .back-link { display: inline-flex; align-items: center; gap: 8px; color: rgba(255,255,255,0.75); font-size: 0.82rem; font-weight: 600; border: 1px solid rgba(255,255,255,0.25); border-radius: 20px; padding: 5px 14px; transition: all 0.25s; margin-bottom: 12px; }
        .back-link:hover { color: white; background: rgba(255,255,255,0.15); }

        /* ── TAB NAV ── */
        .tab-nav { display: flex; gap: 6px; background: var(--bg-white); border: 1px solid var(--border-light); border-radius: 14px; padding: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); margin-bottom: 24px; max-width: 480px; }
        .tab-btn { flex: 1; padding: 8px 16px; border: none; background: transparent; border-radius: 10px; font-weight: 700; font-size: 0.82rem; transition: all 0.25s; cursor: pointer; color: #64748b; font-family: 'Inter', sans-serif; white-space: nowrap; }
        .tab-btn:hover { background: var(--bg-soft-blue); color: var(--primary-blue); }
        .tab-btn.active { background: var(--primary-gradient); color: white; box-shadow: 0 4px 12px rgba(44,62,143,0.25); }

        /* ── PANEL CARD ── */
        .panel-card { background: var(--bg-white); border-radius: 20px; border: 1px solid var(--border-light); box-shadow: 0 4px 15px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 24px; }
        .panel-header { background: var(--primary-gradient); color: white; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between; }
        .panel-header-title { font-size: 1rem; font-weight: 800; }
        .panel-header-sub { font-size: 0.75rem; opacity: 0.75; margin-top: 2px; }
        .panel-body { padding: 26px; }

        /* ── TOTAL POP BOX ── */
        .total-box { background: var(--bg-soft-blue); border-radius: 14px; padding: 18px 24px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; border: 1px solid rgba(44,62,143,0.12); }
        .total-box-label { font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
        .total-box-value { font-size: 2.4rem; font-weight: 900; color: var(--primary-blue); line-height: 1; }

        /* ── FORM ELEMENTS ── */
        .section-title { font-size: 0.78rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--primary-blue); margin: 20px 0 12px; padding-bottom: 8px; border-bottom: 2px solid var(--secondary-yellow); display: inline-block; }
        .f-label { font-size: 0.75rem; font-weight: 700; color: var(--primary-blue); text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 5px; display: block; }
        .f-input { border: 1.5px solid var(--border-light); border-radius: 10px; padding: 9px 13px; font-size: 0.9rem; font-family: 'Inter', sans-serif; transition: all 0.25s; width: 100%; background: white; }
        .f-input:focus { border-color: var(--primary-blue); box-shadow: 0 0 0 3px rgba(44,62,143,0.08); outline: none; }
        .program-box { background: var(--bg-soft-blue); border: 1px solid var(--border-light); border-radius: 12px; padding: 13px 14px; }
        .program-box .p-name { font-size: 0.72rem; font-weight: 800; color: var(--primary-blue); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 6px; display: block; }
        .btn-update { background: var(--primary-gradient); color: white; border: none; border-radius: 12px; padding: 12px 24px; font-weight: 800; font-size: 0.85rem; cursor: pointer; transition: all 0.3s; margin-top: 20px; display: block; width: 100%; }
        .btn-update:hover { box-shadow: 0 8px 24px rgba(44,62,143,0.28); transform: translateY(-1px); }

        /* ── YEARLY HISTORY ── */
        .year-pill { display: inline-block; background: var(--bg-soft-blue); color: var(--primary-blue); border: 1px solid rgba(44,62,143,0.16); border-radius: 20px; padding: 6px 16px; font-size: 0.82rem; font-weight: 700; cursor: pointer; transition: all 0.2s; text-decoration: none; }
        .year-pill:hover { background: var(--primary-blue); color: white; }

        /* ── BARANGAY GRID ── */
        .year-selector { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 18px; }
        .yr-radio { display: none; }
        .yr-label { display: inline-flex; align-items: center; background: var(--bg-soft-blue); border: 2px solid transparent; color: var(--primary-blue); border-radius: 20px; padding: 6px 18px; font-size: 0.82rem; font-weight: 700; cursor: pointer; transition: all 0.2s; }
        .yr-radio:checked + .yr-label { background: var(--primary-blue); color: white; border-color: var(--primary-blue); }
        .bgy-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 14px; max-height: 580px; overflow-y: auto; padding-right: 4px; }
        .bgy-card { background: var(--bg-soft-blue); border: 1.5px solid var(--border-light); border-radius: 14px; padding: 16px; transition: all 0.2s; }
        .bgy-card:hover { border-color: var(--primary-blue); box-shadow: 0 4px 14px rgba(44,62,143,0.10); }
        .bgy-card-name { font-size: 0.88rem; font-weight: 800; color: var(--primary-blue); margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid var(--border-light); }
        .bgy-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 10px; }
        .bgy-field label { font-size: 0.65rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em; display: block; margin-bottom: 3px; }
        .bgy-field input { border: 1.5px solid var(--border-light); border-radius: 8px; padding: 6px 10px; font-size: 0.88rem; font-family: 'Inter', sans-serif; width: 100%; background: white; }
        .bgy-field input:focus { border-color: var(--primary-blue); outline: none; }
        .btn-save-bgy { width: 100%; background: var(--primary-gradient); color: white; border: none; border-radius: 9px; padding: 9px; font-size: 0.83rem; font-weight: 800; cursor: pointer; transition: all 0.2s; margin-top: 8px; }
        .btn-save-bgy:hover { box-shadow: 0 5px 14px rgba(44,62,143,0.25); transform: translateY(-1px); }

        /* ── ALERTS & TOASTS ── */
        .alert-e  { border-radius: 12px; font-size: 0.88rem; padding: 12px 16px; margin-bottom: 16px; background: #fce8e8; border-left: 4px solid #C41E24; color: #721c24; }
        .toast-msg { position: fixed; top: 22px; right: 22px; padding: 14px 24px; border-radius: 12px; color: white; font-weight: 600; z-index: 9999; font-size: 0.88rem; animation: slideIn 0.3s ease; box-shadow: 0 6px 20px rgba(0,0,0,0.18); display: flex; align-items: center; gap: 10px; overflow: hidden; }
        .toast-success { background: var(--primary-blue); }
        .toast-error   { background: #C41E24; }
        .toast-timer { height: 3px; width: 100%; background: var(--secondary-yellow); position: absolute; bottom: 0; left: 0; border-radius: 0 0 12px 12px; animation: timerShrink 3.5s linear; }
        @keyframes slideIn { from { transform: translateX(120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes timerShrink { from { width: 100%; } to { width: 0%; } }

        /* ── INFO ROW ── */
        .loading-spin { text-align: center; padding: 36px; color: #94a3b8; font-size: 0.88rem; }

        /* ── MAIN / FOOTER ── */
        .main-content { flex: 1; }
        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,0.75); text-align: center; padding: 20px 0; font-size: 0.85rem; margin-top: 48px; }
        .footer-strip strong { color: white; }

        @media (max-width: 768px) {
            .bgy-grid { grid-template-columns: 1fr; }
            .tab-nav { max-width: 100%; }
        }
    </style>
</head>
<body>

    <!-- ── NAVBAR ── -->
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
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.requirements') }}">Applications</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">Users Management</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.data.dashboard') }}">Data Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.detailed-analysis') }}">Analysis</a></li>
                    <li class="nav-item"><a class="nav-link" href="/analysis/programs">Comparative Analysis</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}" href="{{ route('admin.announcements.index') }}">Announcements</a></li>
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

    <!-- ── HERO ── -->
    <section class="hero-banner">
        <div class="container">
            <div class="hero-inner">
                <a href="{{ route('admin.data.dashboard') }}#return" class="back-link">&#8592; Data Management</a>
                <div class="hero-badge">Municipality Profile</div>
                <h1>{{ $municipality->name }}</h1>
                <div class="hero-divider"></div>
                <p>Update population, demographics, and household data for this municipality.</p>
            </div>
        </div>
    </section>

    <div class="main-content">
    <div class="container mt-4">

        @if($errors->any())
            <div class="alert-e"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div id="toast" class="toast-msg" style="display:none;"></div>

        <!-- ── TAB NAV ── -->
        <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 1px;">
            <div class="tab-nav">
                <button class="tab-btn active" onclick="switchTab('current')">Current Year</button>
                <button class="tab-btn" onclick="switchTab('yearly')">Yearly History</button>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-sm" onclick="openCsvModal('import')" style="background:var(--primary-blue);color:white;border:none;border-radius:8px;padding:8px 16px;font-weight:700;font-size:0.85rem;">
                    <i class="bi bi-upload"></i> Import CSV
                </button>
                <button class="btn btn-sm" onclick="openCsvModal('export')" style="background:var(--secondary-yellow);color:#333;border:none;border-radius:8px;padding:8px 16px;font-weight:700;font-size:0.85rem;">
                    <i class="bi bi-download"></i> Export CSV
                </button>
            </div>
        </div>

        <!-- ════════════════════════════════════
             TAB 1 — CURRENT YEAR DATA
        ════════════════════════════════════ -->
        <div id="current-tab">
            <div class="panel-card">
                <div class="panel-header">
                    <div>
                        <div class="panel-header-title">Current Year Data — {{ $municipality->year ?? date('Y') }}</div>
                        <div class="panel-header-sub">Update the demographic and household data below</div>
                    </div>
                </div>
                <div class="panel-body">

                    <form method="POST" action="{{ route('admin.data.municipality.update') }}">
                        @csrf

                        <div class="section-title">Demographics</div>
                        <div class="row g-3 mb-0">
                            <div class="col-md-6">
                                <label class="f-label">Total Population</label>
                                <input type="number" name="total_population" class="f-input"
                                       value="{{ $currentTotalPopulation }}"
                                       required min="0" placeholder="e.g. 45000">
                                <small class="text-muted" style="font-size:.75rem;">💡 Saved value for {{ $municipality->year ?? date('Y') }}</small>
                            </div>
                            <div class="col-md-6">
                                <label class="f-label">Total Households</label>
                                <input type="number" name="total_households" class="f-input" value="{{ $currentTotalHouseholds }}" required min="0">
                                <small class="text-muted" style="font-size:.75rem;">💡 Saved value for {{ $municipality->year ?? date('Y') }}</small>
                            </div>
                        </div>

                        <div class="section-title mt-4" style="font-size:.72rem;font-weight:800;color:var(--primary-blue);text-transform:uppercase;letter-spacing:.08em;margin-top:1.5rem;margin-bottom:.5rem;">Gender &amp; Age Breakdown</div>
                        <div class="row g-3 mb-0">
                            <div class="col-md-6">
                                <label class="f-label">Male Population</label>
                                <input type="number" name="male_population" class="f-input"
                                       value="{{ $currentSummary->male_population ?? $municipality->male_population ?? 0 }}"
                                       min="0" placeholder="0">
                            </div>
                            <div class="col-md-6">
                                <label class="f-label">Female Population</label>
                                <input type="number" name="female_population" class="f-input"
                                       value="{{ $currentSummary->female_population ?? $municipality->female_population ?? 0 }}"
                                       min="0" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="f-label">Age 0–19</label>
                                <input type="number" name="population_0_19" class="f-input"
                                       value="{{ $currentSummary->population_0_19 ?? $municipality->population_0_19 ?? 0 }}"
                                       min="0" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="f-label">Age 20–59</label>
                                <input type="number" name="population_20_59" class="f-input"
                                       value="{{ $currentSummary->population_20_59 ?? $municipality->population_20_59 ?? 0 }}"
                                       min="0" placeholder="0">
                            </div>
                            <div class="col-md-4">
                                <label class="f-label">Age 60+</label>
                                <input type="number" name="population_60_100" class="f-input"
                                       value="{{ $currentSummary->population_60_100 ?? $municipality->population_60_100 ?? 0 }}"
                                       min="0" placeholder="0">
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-3">
                                <label class="f-label">Data Year</label>
                                <select name="year" class="f-input" required id="yearSelect">
                                    @php
                                        $dropdownYears = array_unique(array_merge(
                                            $years,
                                            range(date('Y') - 1, date('Y') + 2)
                                        ));
                                        rsort($dropdownYears);
                                    @endphp
                                    @foreach($dropdownYears as $yearOption)
                                        <option value="{{ $yearOption }}" {{ $currentYear == $yearOption ? 'selected' : '' }}>
                                            {{ $yearOption }}{{ in_array($yearOption, $years) && $allSummaries->where('year', $yearOption)->isNotEmpty() ? ' ✓' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn-update">Save Municipality Data</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- ════════════════════════════════════
             TAB 2 — YEARLY HISTORY
        ════════════════════════════════════ -->
        <div id="yearly-tab" style="display:none;">
            <div class="panel-card">
                <div class="panel-header">
                    <div>
                        <div class="panel-header-title">Historical Population Data</div>
                        <div class="panel-header-sub">Population trends across recorded years</div>
                    </div>
                    <a href="{{ route('admin.data.yearly') }}" style="font-size:0.78rem;color:rgba(255,255,255,0.80);font-weight:700;">Manage Yearly Records &rarr;</a>
                </div>
                <div class="panel-body">
                    @if(empty($years))
                        <div style="text-align:center;padding:40px;color:#94a3b8;font-size:0.88rem;">No historical data recorded yet.</div>
                    @else
                        <div style="height:220px;position:relative;margin-bottom:22px;">
                            <canvas id="yearlyPopulationChart"></canvas>
                        </div>
                        <table style="width:100%;border-collapse:collapse;font-size:0.85rem;margin-top:8px;">
                            <thead>
                                <tr style="background:#f8fafc;">
                                    <th style="padding:10px 14px;text-align:left;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Year</th>
                                    <th style="padding:10px 14px;text-align:right;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Population</th>
                                    <th style="padding:10px 14px;text-align:right;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Male</th>
                                    <th style="padding:10px 14px;text-align:right;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Female</th>
                                    <th style="padding:10px 14px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Age 0-19</th>
                                    <th style="padding:10px 14px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Age 20-59</th>
                                    <th style="padding:10px 14px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Age 60+</th>
                                    <th style="padding:10px 14px;text-align:right;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Households</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allSummaries as $summary)
                                <tr style="border-bottom:1px solid #E2E8F0;{{ $summary->year == ($municipality->year ?? date('Y')) ? 'background:#EEF2FF;' : '' }}">
                                    <td style="padding:11px 14px;font-weight:700;color:#334155;">
                                        {{ $summary->year }}
                                        @if($summary->year == ($municipality->year ?? date('Y')))
                                            <span style="background:#2C3E8F;color:white;font-size:0.65rem;padding:2px 8px;border-radius:20px;margin-left:6px;">Current</span>
                                        @endif
                                    </td>
                                    <td style="padding:11px 14px;text-align:right;color:#334155;">{{ number_format($summary->total_population) }}</td>
                                    <td style="padding:11px 14px;text-align:right;color:#334155;">{{ number_format($summary->male_population ?? 0) }}</td>
                                    <td style="padding:11px 14px;text-align:right;color:#334155;">{{ number_format($summary->female_population ?? 0) }}</td>
                                    <td style="padding:11px 14px;text-align:center;color:#64748b;font-size:0.82rem;">{{ number_format($summary->population_0_19 ?? 0) }}</td>
                                    <td style="padding:11px 14px;text-align:center;color:#64748b;font-size:0.82rem;">{{ number_format($summary->population_20_59 ?? 0) }}</td>
                                    <td style="padding:11px 14px;text-align:center;color:#64748b;font-size:0.82rem;">{{ number_format($summary->population_60_100 ?? 0) }}</td>
                                    <td style="padding:11px 14px;text-align:right;color:#334155;">{{ number_format($summary->total_households) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Import History -->
        <div class="panel-card">
            <div class="panel-header">
                <div>
                    <div class="panel-header-title"><i class="bi bi-clock-history"></i> Recent Import History</div>
                    <div class="panel-header-sub">Last 5 CSV import operations</div>
                </div>
                <button class="btn btn-sm" onclick="openArchivedModal()" style="background:rgba(255,255,255,0.2);color:white;border:1px solid rgba(255,255,255,0.4);border-radius:8px;padding:6px 16px;font-weight:700;font-size:0.82rem;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    Archived
                </button>
            </div>
            <div class="panel-body" style="padding:0;">
                @if($importLogs->isEmpty())
                    <div style="text-align:center;padding:40px;color:#94a3b8;font-size:0.88rem;">
                        <i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.5;"></i>
                        No import history yet
                    </div>
                @else
                    <div class="table-responsive">
                        <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
                            <thead>
                                <tr style="background:#f8fafc;">
                                    <th style="padding:12px 16px;text-align:left;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Date/Time</th>
                                    <th style="padding:12px 16px;text-align:left;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">File Name</th>
                                    <th style="padding:12px 16px;text-align:left;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Type</th>
                                    <th style="padding:12px 16px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Total</th>
                                    <th style="padding:12px 16px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Success</th>
                                    <th style="padding:12px 16px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Failed</th>
                                    <th style="padding:12px 16px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Status</th>
                                    <th style="padding:12px 16px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($importLogs as $log)
                                <tr style="border-bottom:1px solid #E2E8F0;">
                                    <td style="padding:12px 16px;color:#334155;font-size:0.82rem;">{{ $log->created_at->format('M d, Y H:i') }}</td>
                                    <td style="padding:12px 16px;color:#334155;font-size:0.82rem;"><i class="bi bi-file-earmark-text" style="color:#64748b;margin-right:6px;"></i>{{ $log->file_name }}</td>
                                    <td style="padding:12px 16px;"><span style="background:#e0f2fe;color:#0369a1;padding:4px 10px;border-radius:12px;font-size:0.72rem;font-weight:700;text-transform:uppercase;">{{ str_replace('_', ' ', $log->file_type) }}</span></td>
                                    <td style="padding:12px 16px;text-align:center;color:#334155;font-weight:600;">{{ $log->total_rows }}</td>
                                    <td style="padding:12px 16px;text-align:center;color:#15803d;font-weight:700;">{{ $log->successful_rows }}</td>
                                    <td style="padding:12px 16px;text-align:center;color:#dc2626;font-weight:700;">{{ $log->failed_rows }}</td>
                                    <td style="padding:12px 16px;text-align:center;">
                                        @if($log->status == 'completed')
                                            <span style="background:#d4edda;color:#155724;padding:4px 10px;border-radius:12px;font-size:0.72rem;font-weight:700;text-transform:uppercase;">{{ $log->status }}</span>
                                        @elseif($log->status == 'failed')
                                            <span style="background:#f8d7da;color:#721c24;padding:4px 10px;border-radius:12px;font-size:0.72rem;font-weight:700;text-transform:uppercase;">{{ $log->status }}</span>
                                        @else
                                            <span style="background:#fff3cd;color:#856404;padding:4px 10px;border-radius:12px;font-size:0.72rem;font-weight:700;text-transform:uppercase;">{{ $log->status }}</span>
                                        @endif
                                    </td>
                                    <td style="padding:12px 16px;text-align:center;">
                                        <button class="btn btn-sm" onclick="archiveLog({{ $log->id }})" style="background:#fce8e8;color:#C41E24;border:none;border-radius:20px;padding:4px 14px;font-size:0.76rem;font-weight:800;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#C41E24';this.style.color='white'" onmouseout="this.style.background='#fce8e8';this.style.color='#C41E24'">
                                            <i class="bi bi-archive"></i> Archive
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
    </div>

    <!-- CSV Import/Export Modal -->
    <div class="modal fade" id="csvModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius:16px;border:none;">
                <div class="modal-header" style="background:var(--primary-gradient);color:white;border-radius:16px 16px 0 0;">
                    <h5 class="modal-title" id="csvModalTitle"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding:2rem;">
                    <!-- Import Section -->
                    <div id="importSection" style="display:none;">
                        <form action="{{ route('admin.csv.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" style="font-weight:700;color:var(--primary-blue);font-size:0.85rem;">Select Data Type</label>
                                <select name="import_type" class="form-select" required style="border:1.5px solid var(--border-light);border-radius:10px;">
                                    <option value="">Choose data type...</option>
                                    <option value="municipality_data">Municipality Data</option>
                                    <option value="barangay_data">Barangay Data</option>
                                    <option value="program_data">Program Data</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-weight:700;color:var(--primary-blue);font-size:0.85rem;">Year (Optional)</label>
                                <select name="year" class="form-select" style="border:1.5px solid var(--border-light);border-radius:10px;">
                                    <option value="">All years in CSV file</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}">{{ $year }} only</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Leave as "All years" to import all data from CSV, or select a specific year to filter</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-weight:700;color:var(--primary-blue);font-size:0.85rem;">Upload CSV File</label>
                                <input type="file" name="csv_file" class="form-control" accept=".csv" required style="border:1.5px solid var(--border-light);border-radius:10px;">
                                <small class="text-muted">Maximum file size: 10MB</small>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary" id="importBtn" style="background:var(--primary-gradient);border:none;border-radius:10px;padding:12px;font-weight:700;">
                                    <i class="bi bi-upload"></i> Import Data
                                </button>
                            </div>
                        </form>
                        <div class="mt-4 pt-3" style="border-top:1px solid #e9ecef;">
                            <h6 style="font-weight:700;font-size:0.85rem;color:var(--primary-blue);"><i class="bi bi-file-earmark-arrow-down"></i> Download Templates</h6>
                            <div class="d-flex gap-2 flex-wrap mt-2">
                                <a href="{{ route('admin.csv.template', 'municipality_data') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download"></i> Municipality
                                </a>
                                <a href="{{ route('admin.csv.template', 'barangay_data') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download"></i> Barangay
                                </a>
                                <a href="{{ route('admin.csv.template', 'program_data') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-download"></i> Program
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Export Section -->
                    <div id="exportSection" style="display:none;">
                        <form action="{{ route('admin.csv.export') }}" method="POST" id="exportForm">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" style="font-weight:700;color:var(--primary-blue);font-size:0.85rem;">Select Data Type</label>
                                <select name="export_type" class="form-select" required style="border:1.5px solid var(--border-light);border-radius:10px;">
                                    <option value="">Choose data type...</option>
                                    <option value="municipality_data">Municipality Data</option>
                                    <option value="barangay_data">Barangay Data</option>
                                    <option value="program_data">Program Data</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-weight:700;color:var(--primary-blue);font-size:0.85rem;">Filter by Year (Optional)</label>
                                <select name="year" class="form-select" style="border:1.5px solid var(--border-light);border-radius:10px;">
                                    <option value="">All Years</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="alert" style="background:#e0f2fe;border-left:4px solid #0369a1;color:#0c4a6e;font-size:0.85rem;">
                                <i class="bi bi-info-circle"></i> <strong>Municipality:</strong> {{ $municipality->name }}
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn" style="background:var(--secondary-yellow);color:#333;border:none;border-radius:10px;padding:12px;font-weight:700;">
                                    <i class="bi bi-download"></i> Export to CSV
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Archive Confirmation Modal -->
    <div class="modal fade" id="archiveModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header" style="background:var(--primary-gradient);color:white;border-radius:16px 16px 0 0;">
                <h5 class="modal-title" style="font-weight:800;"><i class="bi bi-archive"></i> Confirm Archive</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:2rem;">
                <div style="text-align:center;padding:20px;">
                    <div style="font-size:3rem;color:#C41E24;margin-bottom:16px;"></div>
                    <div style="font-size:1.1rem;font-weight:700;color:var(--primary-blue);margin-bottom:8px;">Archive this import log?</div>
                    <div style="font-size:0.88rem;color:#64748b;">This will remove the log from the list. This action cannot be undone.</div>
                </div>
            </div>
            <div class="modal-footer border-0" style="padding:0 2rem 2rem;gap:8px;">
                <button type="button" class="btn" data-bs-dismiss="modal" style="background:var(--bg-light);border:1.5px solid var(--border-light);color:#64748b;border-radius:10px;padding:10px;font-weight:700;font-size:0.88rem;flex:1;">Cancel</button>
                <button type="button" onclick="confirmArchive()" style="background:#C41E24;color:white;border:none;border-radius:10px;padding:10px;font-weight:800;font-size:0.85rem;flex:1;">Archive</button>
            </div>
        </div></div>
    </div>

    <!-- Archived Logs Modal -->
    <div class="modal fade" id="archivedModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl"><div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header" style="background:var(--primary-gradient);color:white;border-radius:16px 16px 0 0;">
                <h5 class="modal-title" style="font-weight:800;"><i class="bi bi-archive"></i> Archived Import History</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:0;">
                <div class="table-responsive">
                    <table style="width:100%;border-collapse:collapse;font-size:0.85rem;">
                        <thead>
                            <tr style="background:#f8fafc;">
                                <th style="padding:12px 16px;text-align:left;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Date/Time</th>
                                <th style="padding:12px 16px;text-align:left;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">File Name</th>
                                <th style="padding:12px 16px;text-align:left;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Type</th>
                                <th style="padding:12px 16px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Total</th>
                                <th style="padding:12px 16px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Success</th>
                                <th style="padding:12px 16px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Failed</th>
                                <th style="padding:12px 16px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Status</th>
                                <th style="padding:12px 16px;text-align:center;color:#2C3E8F;font-size:0.75rem;text-transform:uppercase;letter-spacing:.06em;border-bottom:2px solid #E2E8F0;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="archivedLogsBody">
                            <tr>
                                <td colspan="8" style="text-align:center;padding:40px;color:#94a3b8;font-size:0.88rem;">
                                    <i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.5;"></i>
                                    No archived logs
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div></div>
    </div>

    <!-- Restore Confirmation Modal -->
    <div class="modal fade" id="restoreConfirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header" style="background:var(--primary-gradient);color:white;border-radius:16px 16px 0 0;">
                <h5 class="modal-title" style="font-weight:800;"><i class="bi bi-arrow-counterclockwise"></i> Confirm Restore</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:2rem;">
                <div style="text-align:center;padding:20px;">
                    <div style="font-size:3rem;color:#15803d;margin-bottom:16px;"></div>
                    <div style="font-size:1.1rem;font-weight:700;color:var(--primary-blue);margin-bottom:8px;">Restore this import log?</div>
                    <div style="font-size:0.88rem;color:#64748b;">This will move the log back to the active list.</div>
                </div>
            </div>
            <div class="modal-footer border-0" style="padding:0 2rem 2rem;gap:8px;">
                <button type="button" class="btn" data-bs-dismiss="modal" style="background:var(--bg-light);border:1.5px solid var(--border-light);color:#64748b;border-radius:10px;padding:10px;font-weight:700;font-size:0.88rem;flex:1;">Cancel</button>
                <button type="button" onclick="confirmRestore()" class="btn" style="background:#15803d;color:white;border:none;border-radius:10px;padding:10px;font-weight:800;font-size:0.85rem;flex:1;">Restore</button>
            </div>
        </div></div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered"><div class="modal-content" style="border-radius:16px;border:none;">
            <div class="modal-header" style="background:var(--primary-gradient);color:white;border-radius:16px 16px 0 0;">
                <h5 class="modal-title" style="font-weight:800;"><i class="bi bi-trash"></i> Confirm Delete</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding:2rem;">
                <div style="text-align:center;padding:20px;">
                    <div style="font-size:3rem;color:#C41E24;margin-bottom:16px;"></div>
                    <div style="font-size:1.1rem;font-weight:700;color:var(--primary-blue);margin-bottom:8px;">Permanently delete this log?</div>
                    <div style="font-size:0.88rem;color:#64748b;">This action cannot be undone. The log will be permanently removed.</div>
                </div>
            </div>
            <div class="modal-footer border-0" style="padding:0 2rem 2rem;gap:8px;">
                <button type="button" class="btn" data-bs-dismiss="modal" style="background:var(--bg-light);border:1.5px solid var(--border-light);color:#64748b;border-radius:10px;padding:10px;font-weight:700;font-size:0.88rem;flex:1;">Cancel</button>
                <button type="button" onclick="confirmDelete()" class="btn" style="background:#C41E24;color:white;border:none;border-radius:10px;padding:10px;font-weight:800;font-size:0.85rem;flex:1;">Delete</button>
            </div>
        </div></div>
    </div>

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // ── Toast ──
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.innerHTML = `<div class="toast-timer"></div><span>${message}</span>`;
            toast.className = `toast-msg toast-${type}`;
            toast.style.display = 'flex';
            setTimeout(() => { toast.style.display = 'none'; }, 3500);
        }

        // ── Tab Switch ──
        function switchTab(tabName) {
            ['current-tab','yearly-tab'].forEach(id => {
                document.getElementById(id).style.display = 'none';
            });
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));

            const map = { current: 0, yearly: 1 };
            document.getElementById(tabName + '-tab').style.display = 'block';
            document.querySelectorAll('.tab-btn')[map[tabName]].classList.add('active');

            if (tabName === 'yearly' && typeof yearlyChart === 'undefined' && document.getElementById('yearlyPopulationChart')) {
                initializeYearlyChart();
            }
        }



        // ── Year Switcher ──
        document.getElementById('yearSelect').addEventListener('change', function() {
            const yr = parseInt(this.value);
            window.location.href = '{{ route("admin.data.municipality") }}?year=' + yr;
        });


        // ── Yearly Chart ──
        let yearlyChart;
        function initializeYearlyChart() {
            const yrs  = {!! json_encode(array_keys($yearlyData ?? [])) !!};
            const pops = {!! json_encode(array_column($yearlyData ?? [], 'total_population')) !!};
            if (!yrs.length) return;
            Chart.defaults.font.family = "'Inter', sans-serif";
            yearlyChart = new Chart(document.getElementById('yearlyPopulationChart'), {
                type: 'line',
                data: {
                    labels: yrs,
                    datasets: [{
                        label: 'Population',
                        data: pops,
                        borderColor: '#2C3E8F',
                        backgroundColor: 'rgba(44,62,143,0.08)',
                        tension: 0.35,
                        fill: true,
                        pointBackgroundColor: '#FDB913',
                        pointBorderColor: '#2C3E8F',
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { backgroundColor: '#1E293B', padding: 12, cornerRadius: 10 }
                    },
                    scales: {
                        y: { beginAtZero: false, grid: { color: 'rgba(0,0,0,0.04)' } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // CSV Modal
        function openCsvModal(type) {
            const modal = new bootstrap.Modal(document.getElementById('csvModal'));
            const title = document.getElementById('csvModalTitle');
            const importSection = document.getElementById('importSection');
            const exportSection = document.getElementById('exportSection');
            
            if (type === 'import') {
                title.innerHTML = '<i class="bi bi-upload"></i> Import CSV Data';
                importSection.style.display = 'block';
                exportSection.style.display = 'none';
            } else {
                title.innerHTML = '<i class="bi bi-download"></i> Export CSV Data';
                importSection.style.display = 'none';
                exportSection.style.display = 'block';
            }
            
            modal.show();
        }

        // Handle import form submission
        document.getElementById('importForm').addEventListener('submit', function() {
            const btn = document.getElementById('importBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Importing...';
        });

        // Show notification on page load if exists
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif

        // Archive log
        let archiveLogId = null;

        function archiveLog(id) {
            archiveLogId = id;
            new bootstrap.Modal(document.getElementById('archiveModal')).show();
        }

        function confirmArchive() {
            if (archiveLogId) {
                // Save scroll position before submitting
                sessionStorage.setItem('scrollPosition', window.scrollY);
                
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/admin/csv/import-log/' + archiveLogId + '/archive';
                const csrf = document.createElement('input');
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Restore scroll position after page load
        window.addEventListener('load', function() {
            const scrollPosition = sessionStorage.getItem('scrollPosition');
            if (scrollPosition) {
                window.scrollTo(0, parseInt(scrollPosition));
                sessionStorage.removeItem('scrollPosition');
            }
        });

        function openArchivedModal() {
            const modal = new bootstrap.Modal(document.getElementById('archivedModal'));
            modal.show();
            
            const tbody = document.getElementById('archivedLogsBody');
            tbody.innerHTML = `<tr><td colspan="8" style="text-align:center;padding:40px;color:#94a3b8;font-size:0.88rem;"><span class="spinner-border spinner-border-sm me-2"></span>Loading...</td></tr>`;
            
            fetch('{{ route("admin.csv.import-log.archived") }}')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.logs.length > 0) {
                        let html = '';
                        data.logs.forEach(log => {
                            const date = new Date(log.created_at).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' });
                            const statusBg = log.status === 'completed' ? '#d4edda' : (log.status === 'failed' ? '#f8d7da' : '#fff3cd');
                            const statusColor = log.status === 'completed' ? '#155724' : (log.status === 'failed' ? '#721c24' : '#856404');
                            
                            html += `<tr style="border-bottom:1px solid #E2E8F0;">
                                <td style="padding:12px 16px;color:#334155;font-size:0.82rem;">${date}</td>
                                <td style="padding:12px 16px;color:#334155;font-size:0.82rem;"><i class="bi bi-file-earmark-text" style="color:#64748b;margin-right:6px;"></i>${log.file_name}</td>
                                <td style="padding:12px 16px;"><span style="background:#e0f2fe;color:#0369a1;padding:4px 10px;border-radius:12px;font-size:0.72rem;font-weight:700;text-transform:uppercase;">${log.file_type.replace(/_/g, ' ')}</span></td>
                                <td style="padding:12px 16px;text-align:center;color:#334155;font-weight:600;">${log.total_rows}</td>
                                <td style="padding:12px 16px;text-align:center;color:#15803d;font-weight:700;">${log.successful_rows}</td>
                                <td style="padding:12px 16px;text-align:center;color:#dc2626;font-weight:700;">${log.failed_rows}</td>
                                <td style="padding:12px 16px;text-align:center;"><span style="background:${statusBg};color:${statusColor};padding:4px 10px;border-radius:12px;font-size:0.72rem;font-weight:700;text-transform:uppercase;">${log.status}</span></td>
                                <td style="padding:12px 16px;text-align:center;">
                                    <div style="display:flex;gap:4px;justify-content:center;">
                                        <button onclick="restoreArchivedLog(${log.id})" class="btn btn-sm" style="background:#d4edda;color:#155724;border:none;border-radius:6px;padding:3px 8px;font-size:0.72rem;font-weight:700;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#155724';this.style.color='white'" onmouseout="this.style.background='#d4edda';this.style.color='#155724'">
                                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                                        </button>
                                        <button onclick="deleteArchivedLog(${log.id})" class="btn btn-sm" style="background:#f8d7da;color:#721c24;border:none;border-radius:6px;padding:3px 8px;font-size:0.72rem;font-weight:700;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#721c24';this.style.color='white'" onmouseout="this.style.background='#f8d7da';this.style.color='#721c24'">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>`;
                        });
                        tbody.innerHTML = html;
                    } else {
                        tbody.innerHTML = `<tr><td colspan="8" style="text-align:center;padding:40px;color:#94a3b8;font-size:0.88rem;"><i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:12px;opacity:0.5;"></i>No archived logs</td></tr>`;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    tbody.innerHTML = `<tr><td colspan="8" style="text-align:center;padding:40px;color:#dc2626;font-size:0.88rem;">Error loading archived logs</td></tr>`;
                });
        }

        let pendingRestoreId = null;
        let pendingDeleteId = null;

        function restoreArchivedLog(id) {
            pendingRestoreId = id;
            const modal = new bootstrap.Modal(document.getElementById('restoreConfirmModal'));
            modal.show();
        }

        function confirmRestore() {
            if (!pendingRestoreId) return;
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('restoreConfirmModal'));
            modal.hide();
            
            fetch(`/admin/csv/import-log/${pendingRestoreId}/restore`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Import log restored successfully', 'success');
                    // Close archived modal and remove all backdrops
                    const archivedModal = bootstrap.Modal.getInstance(document.getElementById('archivedModal'));
                    if (archivedModal) {
                        archivedModal.hide();
                    }
                    // Remove all modal backdrops and restore body
                    setTimeout(() => {
                        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                        // Reload to refresh the list
                        location.reload();
                    }, 300);
                } else {
                    showToast(data.message || 'Failed to restore log', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error restoring log', 'error');
            })
            .finally(() => {
                pendingRestoreId = null;
            });
        }

        function deleteArchivedLog(id) {
            pendingDeleteId = id;
            const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            modal.show();
        }

        function confirmDelete() {
            if (!pendingDeleteId) return;
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));
            modal.hide();
            
            fetch(`/admin/csv/import-log/${pendingDeleteId}/force-delete`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('Import log permanently deleted', 'success');
                    // Close archived modal and remove all backdrops
                    const archivedModal = bootstrap.Modal.getInstance(document.getElementById('archivedModal'));
                    if (archivedModal) {
                        archivedModal.hide();
                    }
                    // Remove all modal backdrops and restore body
                    setTimeout(() => {
                        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                        document.body.classList.remove('modal-open');
                        document.body.style.overflow = '';
                        document.body.style.paddingRight = '';
                        // Reload to refresh the list
                        location.reload();
                    }, 300);
                } else {
                    showToast(data.message || 'Failed to delete log', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error deleting log', 'error');
            })
            .finally(() => {
                pendingDeleteId = null;
            });
        }
    </script>
@include('components.admin-settings-modal')
@include('components.admin-chat-modal')
</body>
</html>

