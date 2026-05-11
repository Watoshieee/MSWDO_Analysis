<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $municipality->name }} Profile – MSWDO</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        .tab-btn { flex: 1; padding: 10px 16px; border: none; background: transparent; border-radius: 10px; font-weight: 700; font-size: 0.85rem; transition: all 0.25s; cursor: pointer; color: #64748b; font-family: 'Inter', sans-serif; }
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
        .alert-s  { border-radius: 12px; font-size: 0.88rem; padding: 12px 16px; margin-bottom: 16px; background: #d4edda; border-left: 4px solid #28a745; color: #155724; }
        .alert-e  { border-radius: 12px; font-size: 0.88rem; padding: 12px 16px; margin-bottom: 16px; background: #fce8e8; border-left: 4px solid #C41E24; color: #721c24; }
        .toast-msg { position: fixed; top: 22px; right: 22px; padding: 12px 22px; border-radius: 12px; color: white; font-weight: 600; z-index: 9999; font-size: 0.88rem; animation: slideIn 0.3s ease; box-shadow: 0 6px 20px rgba(0,0,0,0.18); }
        .toast-success { background: #28a745; }
        .toast-error   { background: #C41E24; }
        @keyframes slideIn { from { transform: translateX(120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

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

        @include('components.admin-notification')
        @if($errors->any())
            <div class="alert-e"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div id="toast" class="toast-msg" style="display:none;"></div>

        <!-- ── TAB NAV ── -->
        <div class="tab-nav">
            <button class="tab-btn active" onclick="switchTab('current')">Current Year</button>
            <button class="tab-btn" onclick="switchTab('yearly')">Yearly History</button>
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
                                        <option value="{{ $yearOption }}" {{ ($municipality->year ?? date('Y')) == $yearOption ? 'selected' : '' }}>
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



    </div>
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
            toast.textContent = message;
            toast.className = `toast-msg toast-${type}`;
            toast.style.display = 'block';
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
    </script>
@include('components.admin-settings-modal')
@include('components.admin-chat-modal')
</body>
</html>

