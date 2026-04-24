<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $municipality->name }} — Barangay Analysis · MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
html, body { overscroll-behavior: none; margin: 0; padding: 0; }

        :root {
            --primary-blue: #2C3E8F;
            --primary-dark: #1A2A5C;
            --secondary-yellow: #FDB913;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --bg-light: #F4F7FB;
            --bg-white: #FFFFFF;
            --border-light: #E2E8F0;
            --text-dark: #1E293B;
            --text-muted: #64748b;
        }

        * { box-sizing: border-box; }
        body {
            background: var(--bg-light);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            display: flex; flex-direction: column; min-height: 100vh;
            margin: 0;
        }
        a { text-decoration: none; }

        /* ── NAVBAR ── */
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,0.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.5rem; color: white !important; display: flex; align-items: center; gap: 10px; letter-spacing: -.01em; }
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; border-radius: 8px; padding: 9px 16px !important; font-size: 0.9rem; transition: all 0.2s; }
        .nav-link:hover, .nav-link.active { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-back-btn {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.28);
            color: white !important; border-radius: 30px;
            padding: 8px 22px !important; font-weight: 700; font-size: 0.88rem;
            transition: all 0.22s; letter-spacing: 0.01em;
        }
        .nav-back-btn:hover { background: rgba(255,255,255,0.22); border-color: rgba(255,255,255,0.5); transform: translateX(-2px); }

        /* ── HERO ── */
        .hero {
            background: var(--primary-gradient);
            color: white; padding: 48px 0 44px;
            position: relative; overflow: hidden;
        }
        .hero::before {
            content: ''; position: absolute; top: -90px; right: -90px;
            width: 360px; height: 360px; border-radius: 50%;
            background: radial-gradient(circle, rgba(253,185,19,0.10) 0%, transparent 70%);
        }
        .hero::after {
            content: ''; position: absolute; bottom: -70px; left: -50px;
            width: 240px; height: 240px; border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .hero-inner { position: relative; z-index: 2; }
        .hero-badge {
            display: inline-block;
            background: rgba(253,185,19,0.18);
            color: var(--secondary-yellow);
            border: 1px solid rgba(253,185,19,0.35);
            border-radius: 30px; padding: 4px 16px;
            font-size: 0.7rem; font-weight: 800;
            letter-spacing: 0.12em; text-transform: uppercase; margin-bottom: 12px;
        }
        .hero h1 { font-size: 2.2rem; font-weight: 900; line-height: 1.15; margin-bottom: 4px; }
        .hero-divider { width: 46px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; margin: 12px 0 14px; }
        .hero p { opacity: 0.8; font-size: 0.92rem; margin: 0; }

        /* Breadcrumb */
        .breadcrumb { margin: 0; padding: 0; background: transparent; }
        .breadcrumb-item a { color: rgba(255,255,255,0.65); font-size: 0.82rem; }
        .breadcrumb-item a:hover { color: var(--secondary-yellow); }
        .breadcrumb-item.active { color: rgba(255,255,255,0.9); font-size: 0.82rem; }
        .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,0.4); }

        /* ── STAT CARDS ── */
        .stat-card {
            background: var(--bg-white);
            border-radius: 18px;
            border: 1px solid var(--border-light);
            overflow: hidden;
            position: relative;
        }
        .stat-card .accent-bar { height: 4px; }
        .acc-blue   { background: linear-gradient(90deg, #2C3E8F, #5578d9); }
        .acc-green  { background: linear-gradient(90deg, #16a34a, #22c55e); }
        .acc-yellow { background: linear-gradient(90deg, #FDB913, #E5A500); }
        .acc-red    { background: linear-gradient(90deg, #dc2626, #ef4444); }
        .acc-teal   { background: linear-gradient(90deg, #0891b2, #22d3ee); }

        .stat-card .sc-body { padding: 18px 20px 16px; }
        .stat-card .sc-label { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: var(--text-muted); margin-bottom: 6px; }
        .stat-card .sc-value { font-size: 1.9rem; font-weight: 900; color: var(--primary-blue); line-height: 1.1; margin-bottom: 3px; }
        .stat-card .sc-sub { font-size: 0.75rem; color: var(--text-muted); font-weight: 500; }

        /* ── SECTION HEADER ── */
        .section-hdr { margin-bottom: 20px; }
        .section-hdr h5 {
            font-size: 1rem; font-weight: 800; color: var(--primary-blue);
            position: relative; padding-bottom: 10px; margin-bottom: 4px;
        }
        .section-hdr h5::after {
            content: ''; position: absolute; bottom: 0; left: 0;
            width: 34px; height: 4px; background: var(--secondary-yellow); border-radius: 2px;
        }
        .section-hdr p { font-size: 0.8rem; color: var(--text-muted); margin: 8px 0 0; }

        /* ── CHART CARDS ── */
        .chart-card {
            background: var(--bg-white);
            border-radius: 18px;
            border: 1px solid var(--border-light);
            padding: 22px 22px 18px;
            height: 100%;
        }
        .chart-container { position: relative; height: 280px; }

        /* ── TABLE ── */
        .table-card {
            background: var(--bg-white);
            border-radius: 18px;
            border: 1px solid var(--border-light);
            overflow: hidden;
        }
        .table-card-header {
            padding: 18px 22px 16px;
            border-bottom: 1px solid var(--border-light);
        }
        .table-responsive { overflow-x: auto; }
        table.mswdo-table { width: 100%; border-collapse: collapse; }
        table.mswdo-table thead tr {
            background: var(--primary-gradient);
        }
        table.mswdo-table thead th {
            color: white; font-size: 0.72rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .08em;
            padding: 12px 16px; white-space: nowrap;
            border: none;
        }
        table.mswdo-table tbody tr {
            border-bottom: 1px solid var(--border-light);
            transition: background .18s;
            cursor: pointer;
        }
        table.mswdo-table tbody tr:last-child { border-bottom: none; }
        table.mswdo-table tbody tr:hover { background: #F0F5FF; }
        table.mswdo-table tbody td {
            padding: 11px 16px; font-size: 0.82rem; color: var(--text-dark);
            vertical-align: middle;
        }
        table.mswdo-table tbody td strong { font-weight: 700; color: var(--primary-blue); }

        .badge-approved {
            background: #d4edda; color: #155724;
            border-radius: 20px; padding: 3px 12px;
            font-size: 0.72rem; font-weight: 800; display: inline-block;
        }

        /* Age progress bar */
        .age-bar { height: 6px; border-radius: 4px; overflow: hidden; background: #e8edf6; display: flex; margin-bottom: 3px; }
        .age-bar .ab-youth  { background: #3b82f6; }
        .age-bar .ab-adult  { background: #22c55e; }
        .age-bar .ab-senior { background: #FDB913; }
        .age-label { font-size: 0.68rem; color: var(--text-muted); }

        /* ── PROGRAMS SECTION ── */
        .prog-benefit-card {
            background: var(--bg-white);
            border-radius: 14px; border: 1px solid var(--border-light);
            padding: 18px 20px;
        }
        .prog-benefit-card .pbc-type { font-size: 0.68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: var(--text-muted); margin-bottom: 6px; }
        .prog-benefit-card .pbc-count { font-size: 1.6rem; font-weight: 900; color: var(--primary-blue); line-height: 1.1; }
        .prog-benefit-card .pbc-sub { font-size: 0.72rem; color: var(--text-muted); margin-top: 2px; }
        .prog-benefit-card .pbc-bar { height: 3px; border-radius: 2px; background: var(--primary-gradient); margin-top: 12px; }

        /* ── MODAL ── */
        .modal-content { border-radius: 18px; border: none; overflow: hidden; }
        .modal-header { background: var(--primary-gradient); color: white; padding: 18px 24px; border: none; }
        .modal-title { font-weight: 800; font-size: 1.05rem; }
        .modal-header .btn-close { filter: brightness(0) invert(1); opacity: .8; }
        .modal-body { padding: 22px 24px; }
        .modal-stat { background: #F0F5FF; border-radius: 12px; padding: 14px 18px; margin-bottom: 10px; }
        .modal-stat .ms-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--text-muted); margin-bottom: 3px; }
        .modal-stat .ms-val { font-size: 1.1rem; font-weight: 800; color: var(--primary-blue); }

        /* ── FOOTER ── */
        .main-content { flex: 1; }
        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,0.7); text-align: center; padding: 18px 0; font-size: 0.82rem; margin-top: 52px; }
        .footer-strip strong { color: white; }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/analysis">
                <img src="{{ asset('images/mswd-logo.png') }}" alt="MSWD" style="width:32px;height:32px;object-fit:contain;" onerror="this.style.display='none'"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link nav-back-btn" href="/analysis/programs">&#8592; Back to Analysis</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="container">
            <div class="hero-inner">
                <nav aria-label="breadcrumb" class="mb-2">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/analysis">Public Analysis</a></li>
                        <li class="breadcrumb-item active">{{ $municipality->name }}</li>
                    </ol>
                </nav>
                <div class="hero-badge">Barangay Analysis</div>
                <h1>{{ $municipality->name }}</h1>
                <div class="hero-divider"></div>
                <p>Barangay-level demographic and social welfare data for {{ $municipality->name }}.</p>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="container mt-4">

            <!-- STAT CARDS -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="accent-bar acc-blue"></div>
                        <div class="sc-body">
                            <div class="sc-label">Total Population</div>
                            <div class="sc-value">{{ number_format($totalPopulation) }}</div>
                            <div class="sc-sub">From barangay data</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="accent-bar acc-green"></div>
                        <div class="sc-body">
                            <div class="sc-label">Total Households</div>
                            <div class="sc-value">{{ number_format($totalHouseholds) }}</div>
                            <div class="sc-sub">Registered households</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="accent-bar acc-yellow"></div>
                        <div class="sc-body">
                            <div class="sc-label">Total Barangays</div>
                            <div class="sc-value">{{ $barangays->count() }}</div>
                            <div class="sc-sub">In {{ $municipality->name }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="accent-bar acc-teal"></div>
                        <div class="sc-body">
                            <div class="sc-label">Approved Applications</div>
                            <div class="sc-value">{{ number_format($totalApprovedApps) }}</div>
                            <div class="sc-sub">Total approved</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHARTS ROW -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="chart-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="section-hdr" style="margin-bottom:0;">
                                <h5>Top 10 Barangays by Population</h5>
                                <p>Highest populated barangays in {{ $municipality->name }}</p>
                            </div>
                            <div class="year-filter-buttons" style="display:flex;gap:6px;flex-wrap:wrap;">
                                <button class="year-btn-top active" data-year="all" onclick="filterTopBarangays('all')" style="background:var(--primary-gradient);color:white;border:none;border-radius:8px;padding:6px 14px;font-size:0.75rem;font-weight:700;cursor:pointer;transition:all 0.2s;">All</button>
                                @foreach($availableYears as $year)
                                <button class="year-btn-top" data-year="{{ $year }}" onclick="filterTopBarangays('{{ $year }}')" style="background:#E2E8F0;color:#64748b;border:none;border-radius:8px;padding:6px 14px;font-size:0.75rem;font-weight:700;cursor:pointer;transition:all 0.2s;">{{ $year }}</button>
                                @endforeach
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="topBarangaysChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="section-hdr" style="margin-bottom:0;">
                                <h5>Program Beneficiaries by Type</h5>
                                <p>Distribution of beneficiaries across programs</p>
                            </div>
                            <div class="year-filter-buttons" style="display:flex;gap:6px;flex-wrap:wrap;">
                                <button class="year-btn" data-year="all" onclick="filterByYear('all')" style="background:#E2E8F0;color:#64748b;border:none;border-radius:8px;padding:6px 14px;font-size:0.75rem;font-weight:700;cursor:pointer;transition:all 0.2s;">All</button>
                                @foreach($availableYears as $year)
                                <button class="year-btn {{ $year == 2024 ? 'active' : '' }}" data-year="{{ $year }}" onclick="filterByYear('{{ $year }}')" style="background:{{ $year == 2024 ? 'var(--primary-gradient)' : '#E2E8F0' }};color:{{ $year == 2024 ? 'white' : '#64748b' }};border:none;border-radius:8px;padding:6px 14px;font-size:0.75rem;font-weight:700;cursor:pointer;transition:all 0.2s;">{{ $year }}</button>
                                @endforeach
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="programBeneficiariesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DETAILED TABLE -->
            <div class="table-card mb-4">
                <div class="table-card-header">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <div class="section-hdr" style="margin-bottom:0;">
                                <h5>Detailed Barangay Information</h5>
                                <p>Click any row to view full barangay details</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="year-filter-buttons" style="display:flex;gap:6px;flex-wrap:wrap;justify-content:center;">
                                <button class="year-btn-table active" data-year="all" onclick="filterTableByYear('all')" style="background:var(--primary-gradient);color:white;border:none;border-radius:8px;padding:6px 14px;font-size:0.75rem;font-weight:700;cursor:pointer;transition:all 0.2s;">All</button>
                                @foreach($availableYears as $year)
                                <button class="year-btn-table" data-year="{{ $year }}" onclick="filterTableByYear('{{ $year }}')" style="background:#E2E8F0;color:#64748b;border:none;border-radius:8px;padding:6px 14px;font-size:0.75rem;font-weight:700;cursor:pointer;transition:all 0.2s;">{{ $year }}</button>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="search-box">
                                <input type="text" id="barangaySearch" class="form-control" placeholder="🔍 Search barangay..." style="border-radius:10px;border:1.5px solid var(--border-light);padding:10px 16px;font-size:0.88rem;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                    <table class="mswdo-table" id="barangayTable">
                        <thead>
                            <tr>
                                <th style="cursor:pointer;" onclick="sortTable(0)">Barangay ↕</th>
                                <th style="cursor:pointer;" onclick="sortTable(1)">Population ↕</th>
                                <th style="cursor:pointer;" onclick="sortTable(2)">Households ↕</th>
                                <th style="cursor:pointer;" onclick="sortTable(3)">PWD ↕</th>
                                <th style="cursor:pointer;" onclick="sortTable(4)">AICS ↕</th>
                                <th style="cursor:pointer;" onclick="sortTable(5)">4PS ↕</th>
                                <th style="cursor:pointer;" onclick="sortTable(6)">Senior ↕</th>
                                <th style="cursor:pointer;" onclick="sortTable(7)">Single Parents ↕</th>
                                <th style="cursor:pointer;" onclick="sortTable(8)">Approved Apps ↕</th>
                            </tr>
                        </thead>
                        <tbody id="barangayTableBody">
                            @foreach($barangayData as $barangay => $data)
                            <tr onclick="showBarangayDetails('{{ $barangay }}')">
                                <td><strong>{{ $barangay }}</strong></td>
                                <td data-value="{{ $data['population'] }}">{{ number_format($data['population']) }}</td>
                                <td data-value="{{ $data['households'] }}">{{ number_format($data['households']) }}</td>
                                <td data-value="{{ $data['pwd'] }}">{{ number_format($data['pwd']) }}</td>
                                <td data-value="{{ $data['aics'] }}">{{ number_format($data['aics']) }}</td>
                                <td data-value="{{ $data['four_ps'] }}">{{ number_format($data['four_ps']) }}</td>
                                <td data-value="{{ $data['senior'] }}">{{ number_format($data['senior']) }}</td>
                                <td data-value="{{ $data['single_parents'] }}">{{ number_format($data['single_parents']) }}</td>
                                <td>
                                    <span class="badge-approved">{{ $data['approved_apps'] }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="table-footer" style="padding:14px 22px;border-top:1px solid var(--border-light);background:#F8FAFC;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span style="font-size:0.82rem;color:var(--text-muted);">Showing <strong id="visibleCount">{{ count($barangayData) }}</strong> of <strong>{{ count($barangayData) }}</strong> barangays</span>
                        <button onclick="resetTable()" class="btn btn-sm" style="background:var(--primary-gradient);color:white;border-radius:8px;padding:6px 16px;font-size:0.8rem;font-weight:700;">Reset Filters</button>
                    </div>
                </div>
            </div>

            <!-- SOCIAL WELFARE PROGRAMS -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="section-hdr" style="margin-bottom:0;">
                        <h5>Social Welfare Programs in {{ $municipality->name }}</h5>
                        <p>Program beneficiary breakdown by type</p>
                    </div>
                    <div class="year-filter-buttons" style="display:flex;gap:6px;flex-wrap:wrap;">
                        <button class="year-btn-programs" data-year="all" onclick="filterProgramsByYear('all')" style="background:#E2E8F0;color:#64748b;border:none;border-radius:8px;padding:6px 14px;font-size:0.75rem;font-weight:700;cursor:pointer;transition:all 0.2s;">All</button>
                        @foreach($programYears as $year)
                        <button class="year-btn-programs {{ $year == $defaultProgramYear ? 'active' : '' }}" data-year="{{ $year }}" onclick="filterProgramsByYear('{{ $year }}')" style="background:{{ $year == $defaultProgramYear ? 'var(--primary-gradient)' : '#E2E8F0' }};color:{{ $year == $defaultProgramYear ? 'white' : '#64748b' }};border:none;border-radius:8px;padding:6px 14px;font-size:0.75rem;font-weight:700;cursor:pointer;transition:all 0.2s;">{{ $year }}</button>
                        @endforeach
                    </div>
                </div>
                <div class="row g-3" id="programsContainer">
                    @php
                        $defaultPrograms = $programsByYear[$defaultProgramYear] ?? [];
                    @endphp
                    @forelse($defaultPrograms as $type => $count)
                    <div class="col-md-4 col-sm-6">
                        <div class="prog-benefit-card">
                            <div class="pbc-type">{{ str_replace('_', ' ', $type) }}</div>
                            <div class="pbc-count">{{ number_format($count) }}</div>
                            <div class="pbc-sub">Beneficiaries</div>
                            <div class="pbc-bar"></div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <p style="text-align:center;color:var(--text-muted);padding:20px;">No program data available for {{ $defaultProgramYear }}</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- BARANGAY DETAILS MODAL -->
    <div class="modal fade" id="barangayModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Barangay Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <!-- Dynamic content loaded here -->
                </div>
            </div>
        </div>
    </div>

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const BLUE = 'rgba(44,62,143,0.75)';
        const YELLOW = 'rgba(253,185,19,0.80)';
        const GREEN = 'rgba(22,163,74,0.75)';
        const PINK  = 'rgba(244,63,94,0.75)';
        const TEAL  = 'rgba(8,145,178,0.75)';

        const chartDefaults = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { labels: { font: { family: 'Inter', size: 11 }, color: '#64748b', boxWidth: 12 } }
            }
        };

        let programBeneficiariesChart;
        let topBarangaysChartObj;
        const dataByYear = {!! json_encode($dataByYear) !!};
        const allYearsData = {
            totalPWD: {{ $totalPWD_All }},
            totalAICS: {{ $totalAICS_All }},
            total4PS: {{ $total4PS_All }},
            totalSenior: {{ $totalSenior_All }},
            totalSingleParents: {{ $totalSingleParents_All }},
            barangayData: {!! json_encode($allBarangayData) !!}
        };

        document.addEventListener('DOMContentLoaded', function () {
            // Remove old static fallback and initialize with 'all' data
            const initialTopData = allYearsData.barangayData;
            const bNames = Object.keys(initialTopData);
            const top10 = bNames.map(name => ({ name, pop: initialTopData[name].population }))
                                .sort((a, b) => b.pop - a.pop)
                                .slice(0, 10);
            topBarangaysChartObj = new Chart(document.getElementById('topBarangaysChart'), {
                type: 'bar',
                data: {
                    labels: top10.map(d => d.name),
                    datasets: [{ label: 'Population', data: top10.map(d => d.pop), backgroundColor: BLUE, borderRadius: 4 }]
                },
                options: { ...chartDefaults, indexAxis: 'y', scales: { x: { beginAtZero: true, grid: { color: '#f1f5f9' } }, y: { grid: { display: false } } } }
            });

            /* -- Program Beneficiaries (Doughnut) -- */
            const ctx = document.getElementById('programBeneficiariesChart');
            
            // Default to 2024 data if available, otherwise use all years
            const defaultYear = dataByYear[2024] ? 2024 : 'all';
            let initialData;
            if (defaultYear === 2024 && dataByYear[2024]) {
                const yearData = dataByYear[2024];
                initialData = [yearData.totalPWD, yearData.totalAICS, yearData.total4PS, yearData.totalSenior, yearData.totalSingleParents];
            } else {
                initialData = [allYearsData.totalPWD, allYearsData.totalAICS, allYearsData.total4PS, allYearsData.totalSenior, allYearsData.totalSingleParents];
            }
            
            programBeneficiariesChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['PWD', 'AICS', '4PS', 'Senior', 'Solo Parent'],
                    datasets: [{ 
                        data: initialData, 
                        backgroundColor: [BLUE, GREEN, YELLOW, PINK, TEAL], 
                        borderWidth: 2, 
                        borderColor: '#fff' 
                    }]
                },
                options: { ...chartDefaults, cutout: '60%', plugins: { ...chartDefaults.plugins, legend: { ...chartDefaults.plugins.legend, position: 'bottom' } } }
            });
        });

        // ── Year filter for chart ──
        function filterByYear(year) {
            // Update button styles
            document.querySelectorAll('.year-btn').forEach(btn => {
                if (btn.dataset.year === year) {
                    btn.style.background = 'var(--primary-gradient)';
                    btn.style.color = 'white';
                    btn.classList.add('active');
                } else {
                    btn.style.background = '#E2E8F0';
                    btn.style.color = '#64748b';
                    btn.classList.remove('active');
                }
            });

            // Update chart data
            let data;
            if (year === 'all') {
                data = [allYearsData.totalPWD, allYearsData.totalAICS, allYearsData.total4PS, allYearsData.totalSenior, allYearsData.totalSingleParents];
            } else {
                const yearData = dataByYear[year];
                data = [yearData.totalPWD, yearData.totalAICS, yearData.total4PS, yearData.totalSenior, yearData.totalSingleParents];
            }

            programBeneficiariesChart.data.datasets[0].data = data;
            programBeneficiariesChart.update();
        }

        // ── Year filter for top barangays chart ──
        function filterTopBarangays(year) {
            // Update button styles
            document.querySelectorAll('.year-btn-top').forEach(btn => {
                if (btn.dataset.year === year) {
                    btn.style.background = 'var(--primary-gradient)';
                    btn.style.color = 'white';
                    btn.classList.add('active');
                } else {
                    btn.style.background = '#E2E8F0';
                    btn.style.color = '#64748b';
                    btn.classList.remove('active');
                }
            });

            // Update chart data
            const data = year === 'all' ? allYearsData.barangayData : dataByYear[year].barangayData;
            
            const barangayNames = Object.keys(data);
            const populations = barangayNames.map(name => data[name].population);
            
            const top10 = barangayNames.map((name, i) => ({ name, pop: populations[i] }))
                                       .sort((a, b) => b.pop - a.pop)
                                       .slice(0, 10);

            topBarangaysChartObj.data.labels = top10.map(d => d.name);
            topBarangaysChartObj.data.datasets[0].data = top10.map(d => d.pop);
            topBarangaysChartObj.update();
        }

        // ── Year filter for table ──
        function filterTableByYear(year) {
            // Update button styles
            document.querySelectorAll('.year-btn-table').forEach(btn => {
                if (btn.dataset.year === year) {
                    btn.style.background = 'var(--primary-gradient)';
                    btn.style.color = 'white';
                    btn.classList.add('active');
                } else {
                    btn.style.background = '#E2E8F0';
                    btn.style.color = '#64748b';
                    btn.classList.remove('active');
                }
            });

            // Update table data
            const tbody = document.getElementById('barangayTableBody');
            const data = year === 'all' ? allYearsData.barangayData : dataByYear[year].barangayData;
            
            tbody.innerHTML = '';
            Object.keys(data).forEach(barangayName => {
                const d = data[barangayName];
                const row = `
                    <tr onclick="showBarangayDetails('${barangayName}')">
                        <td><strong>${barangayName}</strong></td>
                        <td data-value="${d.population}">${d.population.toLocaleString()}</td>
                        <td data-value="${d.households}">${d.households.toLocaleString()}</td>
                        <td data-value="${d.pwd}">${d.pwd.toLocaleString()}</td>
                        <td data-value="${d.aics}">${d.aics.toLocaleString()}</td>
                        <td data-value="${d.four_ps}">${d.four_ps.toLocaleString()}</td>
                        <td data-value="${d.senior}">${d.senior.toLocaleString()}</td>
                        <td data-value="${d.single_parents}">${d.single_parents.toLocaleString()}</td>
                        <td><span class="badge-approved">${d.approved_apps}</span></td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });

            // Update visible count
            document.getElementById('visibleCount').textContent = Object.keys(data).length;
        }

        // ── Year filter for programs ──
        const programsByYear = {!! json_encode($programsByYear) !!};
        
        function filterProgramsByYear(year) {
            // Update button styles
            document.querySelectorAll('.year-btn-programs').forEach(btn => {
                if (btn.dataset.year === year) {
                    btn.style.background = 'var(--primary-gradient)';
                    btn.style.color = 'white';
                    btn.classList.add('active');
                } else {
                    btn.style.background = '#E2E8F0';
                    btn.style.color = '#64748b';
                    btn.classList.remove('active');
                }
            });

            // Update programs display
            const container = document.getElementById('programsContainer');
            const programs = programsByYear[year] || {};
            
            if (Object.keys(programs).length === 0) {
                const displayYear = year === 'all' ? 'all years' : year;
                container.innerHTML = '<div class="col-12"><p style="text-align:center;color:var(--text-muted);padding:20px;">No program data available for ' + displayYear + '</p></div>';
                return;
            }
            
            container.innerHTML = '';
            Object.keys(programs).forEach(type => {
                const count = programs[type];
                const card = `
                    <div class="col-md-4 col-sm-6">
                        <div class="prog-benefit-card">
                            <div class="pbc-type">${type.replace(/_/g, ' ')}</div>
                            <div class="pbc-count">${count.toLocaleString()}</div>
                            <div class="pbc-sub">Beneficiaries</div>
                            <div class="pbc-bar"></div>
                        </div>
                    </div>
                `;
                container.innerHTML += card;
            });
        }

        // ── Search functionality ──
        document.getElementById('barangaySearch').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#barangayTableBody tr');
            let visibleCount = 0;

            rows.forEach(row => {
                const barangayName = row.cells[0].textContent.toLowerCase();
                if (barangayName.includes(searchValue)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('visibleCount').textContent = visibleCount;
        });

        // ── Sort table functionality ──
        let sortDirection = {};
        function sortTable(columnIndex) {
            const table = document.getElementById('barangayTable');
            const tbody = document.getElementById('barangayTableBody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            // Toggle sort direction
            sortDirection[columnIndex] = sortDirection[columnIndex] === 'asc' ? 'desc' : 'asc';
            const direction = sortDirection[columnIndex];

            rows.sort((a, b) => {
                let aValue, bValue;
                
                if (columnIndex === 0) {
                    // Barangay name (text)
                    aValue = a.cells[columnIndex].textContent.trim();
                    bValue = b.cells[columnIndex].textContent.trim();
                    return direction === 'asc' 
                        ? aValue.localeCompare(bValue)
                        : bValue.localeCompare(aValue);
                } else {
                    // Numeric columns
                    aValue = parseInt(a.cells[columnIndex].dataset.value || a.cells[columnIndex].textContent.replace(/,/g, '')) || 0;
                    bValue = parseInt(b.cells[columnIndex].dataset.value || b.cells[columnIndex].textContent.replace(/,/g, '')) || 0;
                    return direction === 'asc' ? aValue - bValue : bValue - aValue;
                }
            });

            // Re-append sorted rows
            rows.forEach(row => tbody.appendChild(row));
        }

        // ── Reset table ──
        function resetTable() {
            document.getElementById('barangaySearch').value = '';
            const rows = document.querySelectorAll('#barangayTableBody tr');
            rows.forEach(row => row.style.display = '');
            document.getElementById('visibleCount').textContent = rows.length;
            sortDirection = {};
        }

        function showBarangayDetails(barangayName) {
            const data  = {!! json_encode($barangayData) !!}[barangayName];
            const modal = new bootstrap.Modal(document.getElementById('barangayModal'));
            document.getElementById('modalTitle').textContent = `${barangayName} — Detailed Analysis`;

            document.getElementById('modalBody').innerHTML = `
                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="fw-700 mb-2" style="font-weight:800;font-size:.8rem;text-transform:uppercase;letter-spacing:.08em;color:#64748b;">Demographics</p>
                        <div class="modal-stat"><div class="ms-label">Total Population</div><div class="ms-val">${data.population.toLocaleString()}</div></div>
                        <div class="modal-stat"><div class="ms-label">Households</div><div class="ms-val">${data.households.toLocaleString()}</div></div>
                        <div class="modal-stat"><div class="ms-label">Single Parents</div><div class="ms-val">${data.single_parents.toLocaleString()}</div></div>
                    </div>
                    <div class="col-md-6">
                        <p class="fw-700 mb-2" style="font-weight:800;font-size:.8rem;text-transform:uppercase;letter-spacing:.08em;color:#64748b;">Program Beneficiaries</p>
                        <div class="modal-stat"><div class="ms-label">PWD</div><div class="ms-val">${data.pwd.toLocaleString()}</div></div>
                        <div class="modal-stat"><div class="ms-label">AICS</div><div class="ms-val">${data.aics.toLocaleString()}</div></div>
                        <div class="modal-stat"><div class="ms-label">4PS</div><div class="ms-val">${data.four_ps.toLocaleString()}</div></div>
                        <div class="modal-stat"><div class="ms-label">Senior Citizen</div><div class="ms-val">${data.senior.toLocaleString()}</div></div>
                        <p class="fw-700 mb-2 mt-3" style="font-weight:800;font-size:.8rem;text-transform:uppercase;letter-spacing:.08em;color:#64748b;">Applications</p>
                        <div class="modal-stat"><div class="ms-label">Approved Applications</div><div class="ms-val">${data.approved_apps}</div></div>
                    </div>
                </div>
            `;
            modal.show();
        }
    </script>
</body>
</html>