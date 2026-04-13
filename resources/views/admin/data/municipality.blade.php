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
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; transition: all 0.25s; border-radius: 8px; padding: 10px 18px !important; font-size: 0.93rem; }
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
        .btn-update { background: var(--primary-gradient); color: white; border: none; border-radius: 12px; padding: 12px 24px; font-weight: 800; font-size: 0.95rem; cursor: pointer; transition: all 0.3s; margin-top: 20px; display: block; width: 100%; }
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
                <img src="/images/mswd-logo.png" alt="MSWD" style="width:36px;height:36px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.requirements') }}">Applications</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.data.dashboard') }}">Data Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.detailed-analysis') }}">Analysis</a></li>
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

    <!-- ── HERO ── -->
    <section class="hero-banner">
        <div class="container">
            <div class="hero-inner">
                <a href="{{ route('admin.data.dashboard') }}" class="back-link">&#8592; Data Management</a>
                <div class="hero-badge">Municipality Profile</div>
                <h1>{{ $municipality->name }}</h1>
                <div class="hero-divider"></div>
                <p>Update population, demographics, households, and program beneficiary data.</p>
            </div>
        </div>
    </section>

    <div class="main-content">
    <div class="container mt-4">

        @if(session('success'))
            <div class="alert-s">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert-e"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif

        <div id="toast" class="toast-msg" style="display:none;"></div>

        <!-- ── TAB NAV ── -->
        <div class="tab-nav">
            <button class="tab-btn active" onclick="switchTab('current')">Current Year</button>
            <button class="tab-btn" onclick="switchTab('yearly')">Yearly History</button>
            <button class="tab-btn" onclick="switchTab('barangay')">Barangay Data</button>
        </div>

        <!-- ════════════════════════════════════
             TAB 1 — CURRENT YEAR DATA
        ════════════════════════════════════ -->
        <div id="current-tab">
            <div class="panel-card">
                <div class="panel-header">
                    <div>
                        <div class="panel-header-title">Current Year Data — {{ $municipality->year ?? date('Y') }}</div>
                        <div class="panel-header-sub">Update the demographic and program beneficiary figures below</div>
                    </div>
                </div>
                <div class="panel-body">

                    <!-- Total Population Display -->
                    <div class="total-box">
                        <div>
                            <div class="total-box-label">Total Population</div>
                            <div class="total-box-value" id="totalPopulation">
                                {{ number_format($municipality->male_population + $municipality->female_population) }}
                            </div>
                        </div>
                        <div style="font-size:0.8rem;color:#64748b;text-align:right;">
                            Male: <strong>{{ number_format($municipality->male_population) }}</strong><br>
                            Female: <strong>{{ number_format($municipality->female_population) }}</strong>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.data.municipality.update') }}">
                        @csrf

                        <div class="section-title">Demographics</div>
                        <div class="row g-3 mb-0">
                            <div class="col-md-6">
                                <label class="f-label">Male Population</label>
                                <input type="number" name="male_population" class="f-input population-input"
                                       value="{{ $municipality->male_population }}" required min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="f-label">Female Population</label>
                                <input type="number" name="female_population" class="f-input population-input"
                                       value="{{ $municipality->female_population }}" required min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="f-label">Youth (0–19)</label>
                                <input type="number" name="population_0_19" class="f-input" value="{{ $municipality->population_0_19 }}" required min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="f-label">Adult (20–59)</label>
                                <input type="number" name="population_20_59" class="f-input" value="{{ $municipality->population_20_59 }}" required min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="f-label">Senior (60–100)</label>
                                <input type="number" name="population_60_100" class="f-input" value="{{ $municipality->population_60_100 }}" required min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="f-label">Total Households</label>
                                <input type="number" name="total_households" class="f-input" value="{{ $municipality->total_households }}" required min="0">
                            </div>
                            <div class="col-md-6">
                                <label class="f-label">Single Parents</label>
                                <input type="number" name="single_parent_count" class="f-input" value="{{ $municipality->single_parent_count }}" required min="0">
                            </div>
                        </div>

                        <div class="section-title mt-4">Program Beneficiaries</div>
                        <div class="row g-3 mb-0">
                            @php
                                $programs = [
                                    '4Ps'         => ['name' => 'total_4ps',         'val' => $total4ps],
                                    'PWD'         => ['name' => 'total_pwd',         'val' => $totalPwd],
                                    'Senior'      => ['name' => 'total_senior',      'val' => $totalSenior],
                                    'AICS'        => ['name' => 'total_aics',        'val' => $totalAics],
                                    'ESA'         => ['name' => 'total_esa',         'val' => $totalEsa],
                                    'SLP'         => ['name' => 'total_slp',         'val' => $totalSlp],
                                    'Solo Parent' => ['name' => 'total_solo_parent', 'val' => $totalSoloParent],
                                ];
                            @endphp
                            @foreach($programs as $label => $prog)
                            <div class="col-md-3 col-sm-4">
                                <div class="program-box">
                                    <span class="p-name">{{ $label }}</span>
                                    <input type="number" name="{{ $prog['name'] }}" class="f-input" value="{{ $prog['val'] }}" required min="0">
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-3">
                                <label class="f-label">Data Year</label>
                                <select name="year" class="f-input" required>
                                    @foreach(range(date('Y') - 2, date('Y') + 1) as $yearOption)
                                        <option value="{{ $yearOption }}" {{ ($municipality->year ?? date('Y')) == $yearOption ? 'selected' : '' }}>
                                            {{ $yearOption }}
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
                    <a href="{{ route('admin.yearly.compare') }}" style="font-size:0.78rem;color:rgba(255,255,255,0.80);font-weight:700;">Compare Years</a>
                </div>
                <div class="panel-body">
                    @if(empty($years))
                        <div style="text-align:center;padding:40px;color:#94a3b8;font-size:0.88rem;">No historical data recorded yet.</div>
                    @else
                        <div style="height:220px;position:relative;margin-bottom:22px;">
                            <canvas id="yearlyPopulationChart"></canvas>
                        </div>
                        <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:8px;">
                            @foreach($years as $year)
                                <a href="{{ route('admin.yearly.view', $year) }}" class="year-pill">{{ $year }}</a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- ════════════════════════════════════
             TAB 3 — BARANGAY DATA
        ════════════════════════════════════ -->
        <div id="barangay-tab" style="display:none;">
            <div class="panel-card">
                <div class="panel-header">
                    <div>
                        <div class="panel-header-title">Barangay Data Entry</div>
                        <div class="panel-header-sub">Select a year then fill in each barangay's data</div>
                    </div>
                </div>
                <div class="panel-body">
                    <!-- Year Selector -->
                    <div class="year-selector">
                        @foreach(range(date('Y') - 2, date('Y') + 1) as $yearOption)
                            <input type="radio" name="barangay_year" id="yr{{ $yearOption }}" class="yr-radio"
                                   value="{{ $yearOption }}" {{ $yearOption == date('Y') ? 'checked' : '' }}
                                   onchange="loadBarangayData({{ $yearOption }})">
                            <label for="yr{{ $yearOption }}" class="yr-label">{{ $yearOption }}</label>
                        @endforeach
                    </div>

                    <!-- Barangay Cards Container -->
                    <div id="barangay-data-container">
                        <div class="loading-spin">Select a year above to load barangay data...</div>
                    </div>
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
            ['current-tab','yearly-tab','barangay-tab'].forEach(id => {
                document.getElementById(id).style.display = 'none';
            });
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));

            const map = { current: 0, yearly: 1, barangay: 2 };
            document.getElementById(tabName + '-tab').style.display = 'block';
            document.querySelectorAll('.tab-btn')[map[tabName]].classList.add('active');

            if (tabName === 'yearly' && typeof yearlyChart === 'undefined' && document.getElementById('yearlyPopulationChart')) {
                initializeYearlyChart();
            }
            if (tabName === 'barangay') {
                const yr = document.querySelector('input[name="barangay_year"]:checked');
                if (yr) loadBarangayData(yr.value);
            }
        }

        // ── Load Barangay Data ──
        function loadBarangayData(year) {
            const container = document.getElementById('barangay-data-container');
            container.innerHTML = `<div class="loading-spin">Loading barangay data for ${year}...</div>`;

            const timeout = setTimeout(() => {
                container.innerHTML = `<div class="loading-spin">Request timed out. <button style="margin-left:8px;" class="btn-save-bgy" style="width:auto;padding:6px 16px;" onclick="loadBarangayData(${year})">Retry</button></div>`;
            }, 10000);

            fetch(`/api/barangays/{{ $municipality->name }}/${year}`)
                .then(r => { clearTimeout(timeout); if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
                .then(data => {
                    if (Array.isArray(data) && data.length > 0) renderBarangayCards(year, data);
                    else throw new Error('No data');
                })
                .catch(err => {
                    clearTimeout(timeout);
                    container.innerHTML = `<div class="loading-spin">Error: ${err.message}. <button onclick="loadBarangayData(${year})" style="margin-left:8px;padding:6px 16px;background:var(--primary-blue);color:white;border:none;border-radius:8px;cursor:pointer;font-weight:700;font-size:0.82rem;">Retry</button></div>`;
                });
        }

        // ── Render Barangay Cards ──
        function renderBarangayCards(year, barangays) {
            const container = document.getElementById('barangay-data-container');
            let html = `<p style="font-size:0.82rem;color:#64748b;margin-bottom:14px;">Editing <strong>${barangays.length} barangays</strong> for year <strong>${year}</strong>. Fill in data and click Save for each.</p><div class="bgy-grid">`;

            barangays.forEach(b => {
                html += `
                <div class="bgy-card">
                    <div class="bgy-card-name">${b.name}</div>
                    <div class="bgy-grid-2">
                        <div class="bgy-field"><label>Male</label><input type="number" class="male-input" data-barangay="${b.name}" value="${b.male_population || 0}" min="0"></div>
                        <div class="bgy-field"><label>Female</label><input type="number" class="female-input" data-barangay="${b.name}" value="${b.female_population || 0}" min="0"></div>
                    </div>
                    <div class="bgy-grid-2">
                        <div class="bgy-field"><label>Age 0–19</label><input type="number" id="age0_19_${b.name.replace(/\s+/g,'_')}" value="${b.population_0_19 || 0}" min="0"></div>
                        <div class="bgy-field"><label>Age 20–59</label><input type="number" id="age20_59_${b.name.replace(/\s+/g,'_')}" value="${b.population_20_59 || 0}" min="0"></div>
                    </div>
                    <div class="bgy-grid-2">
                        <div class="bgy-field"><label>Age 60–100</label><input type="number" id="age60_100_${b.name.replace(/\s+/g,'_')}" value="${b.population_60_100 || 0}" min="0"></div>
                        <div class="bgy-field"><label>Households</label><input type="number" id="households_${b.name.replace(/\s+/g,'_')}" value="${b.total_households || 0}" min="0"></div>
                    </div>
                    <div class="bgy-grid-2">
                        <div class="bgy-field"><label>Single Parents</label><input type="number" id="single_parents_${b.name.replace(/\s+/g,'_')}" value="${b.single_parent_count || 0}" min="0"></div>
                    </div>
                    <button class="btn-save-bgy" onclick="saveBarangayData('${b.name}', ${year}, this)">Save ${b.name}</button>
                </div>`;
            });

            html += '</div>';
            container.innerHTML = html;
        }

        // ── Save Barangay Data ──
        function saveBarangayData(barangayName, year, button) {
            const card = button.closest('.bgy-card');
            const data = {
                male_population:    card.querySelector('.male-input')?.value || 0,
                female_population:  card.querySelector('.female-input')?.value || 0,
                population_0_19:    card.querySelector('[id*="age0_19"]')?.value || 0,
                population_20_59:   card.querySelector('[id*="age20_59"]')?.value || 0,
                population_60_100:  card.querySelector('[id*="age60_100"]')?.value || 0,
                total_households:   card.querySelector('[id*="households"]')?.value || 0,
                single_parent_count:card.querySelector('[id*="single_parents"]')?.value || 0,
                year: year,
                barangay_name: barangayName
            };

            const orig = button.textContent;
            button.textContent = 'Saving…';
            button.disabled = true;

            fetch('/admin/data/barangays/find-or-create', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ name: barangayName, municipality: '{{ $municipality->name }}', year })
            })
            .then(r => r.json())
            .then(res => {
                if (!res.success || !res.barangay_id) throw new Error(res.message || 'Could not locate barangay');
                return fetch(`/admin/data/barangays/${res.barangay_id}/update`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify(data)
                });
            })
            .then(async r => {
                const txt = await r.text();
                try { return JSON.parse(txt); } catch(e) { throw new Error('Invalid response'); }
            })
            .then(res => {
                if (res.success) {
                    showToast(`Saved: ${barangayName} (${year})`, 'success');
                    button.textContent = '✓ Saved';
                    button.style.background = '#28a745';
                    setTimeout(() => { button.textContent = orig; button.style.background = ''; button.disabled = false; }, 2200);
                } else {
                    throw new Error(res.message || 'Save failed');
                }
            })
            .catch(err => {
                showToast('Error: ' + err.message, 'error');
                button.textContent = orig;
                button.disabled = false;
            });
        }

        // ── Population Auto-Calc ──
        document.querySelectorAll('.population-input').forEach(el => {
            el.addEventListener('input', () => {
                const m = parseInt(document.querySelector('input[name="male_population"]').value) || 0;
                const f = parseInt(document.querySelector('input[name="female_population"]').value) || 0;
                document.getElementById('totalPopulation').textContent = (m + f).toLocaleString();
            });
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

