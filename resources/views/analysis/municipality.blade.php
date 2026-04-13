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
            transition: transform .28s, box-shadow .28s;
            position: relative;
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 14px 32px rgba(44,62,143,0.11); }
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
            padding: 18px 20px; transition: transform .2s, box-shadow .2s;
        }
        .prog-benefit-card:hover { transform: translateY(-3px); box-shadow: 0 10px 24px rgba(44,62,143,0.09); }
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
                <img src="/images/mswd-logo.png" alt="MSWD" style="width:32px;height:32px;object-fit:contain;" onerror="this.style.display='none'"> MSWDO
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
                            <div class="sc-value">{{ number_format($municipality->male_population + $municipality->female_population) }}</div>
                            <div class="sc-sub">{{ $barangays->count() }} Barangays</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="accent-bar acc-green"></div>
                        <div class="sc-body">
                            <div class="sc-label">Total Households</div>
                            <div class="sc-value">{{ number_format($municipality->total_households) }}</div>
                            <div class="sc-sub">Registered households</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="accent-bar acc-yellow"></div>
                        <div class="sc-body">
                            <div class="sc-label">Single Parents</div>
                            <div class="sc-value">{{ number_format($municipality->single_parent_count) }}</div>
                            <div class="sc-sub">Solo parent households</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card">
                        <div class="accent-bar acc-teal"></div>
                        <div class="sc-body">
                            <div class="sc-label">Approved Applications</div>
                            <div class="sc-value">{{ number_format($barangays->sum('total_approved_applications')) }}</div>
                            <div class="sc-sub">Total approved</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHARTS ROW 1 -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="chart-card">
                        <div class="section-hdr">
                            <h5>Top 10 Barangays by Population</h5>
                            <p>Highest populated barangays in {{ $municipality->name }}</p>
                        </div>
                        <div class="chart-container">
                            <canvas id="topBarangaysChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-card">
                        <div class="section-hdr">
                            <h5>Population Distribution by Age Group</h5>
                            <p>Proportion across youth, adult, and senior age brackets</p>
                        </div>
                        <div class="chart-container">
                            <canvas id="ageDistributionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHARTS ROW 2 -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="chart-card">
                        <div class="section-hdr">
                            <h5>Gender Distribution per Barangay</h5>
                            <p>Male vs. Female breakdown across top barangays</p>
                        </div>
                        <div class="chart-container">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="chart-card">
                        <div class="section-hdr">
                            <h5>Single Parents per Barangay</h5>
                            <p>Trend of solo-parent households across barangays</p>
                        </div>
                        <div class="chart-container">
                            <canvas id="singleParentsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DETAILED TABLE -->
            <div class="table-card mb-4">
                <div class="table-card-header">
                    <div class="section-hdr" style="margin-bottom:0;">
                        <h5>Detailed Barangay Information</h5>
                        <p>Click any row to view full barangay details</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="mswdo-table">
                        <thead>
                            <tr>
                                <th>Barangay</th>
                                <th>Population</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Households</th>
                                <th>Single Parents</th>
                                <th>Approved Apps</th>
                                <th style="min-width:180px;">Age Distribution</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barangayData as $barangay => $data)
                            <tr onclick="showBarangayDetails('{{ $barangay }}')">
                                <td><strong>{{ $barangay }}</strong></td>
                                <td>{{ number_format($data['population']) }}</td>
                                <td>{{ number_format($data['male']) }}</td>
                                <td>{{ number_format($data['female']) }}</td>
                                <td>{{ number_format($data['households']) }}</td>
                                <td>{{ number_format($data['single_parents']) }}</td>
                                <td>
                                    <span class="badge-approved">{{ $data['approved_apps'] }}</span>
                                </td>
                                <td>
                                    @php
                                        $total = $data['age_0_19'] + $data['age_20_59'] + $data['age_60_100'];
                                        $pct0_19   = $total > 0 ? ($data['age_0_19']   / $total) * 100 : 0;
                                        $pct20_59  = $total > 0 ? ($data['age_20_59']  / $total) * 100 : 0;
                                        $pct60_100 = $total > 0 ? ($data['age_60_100'] / $total) * 100 : 0;
                                    @endphp
                                    <div class="age-bar">
                                        <div class="ab-youth"  style="width:{{ $pct0_19 }}%" title="0-19: {{ number_format($data['age_0_19']) }}"></div>
                                        <div class="ab-adult"  style="width:{{ $pct20_59 }}%" title="20-59: {{ number_format($data['age_20_59']) }}"></div>
                                        <div class="ab-senior" style="width:{{ $pct60_100 }}%" title="60+: {{ number_format($data['age_60_100']) }}"></div>
                                    </div>
                                    <div class="age-label">
                                        0-19: {{ number_format($data['age_0_19']) }} &nbsp;·&nbsp;
                                        20-59: {{ number_format($data['age_20_59']) }} &nbsp;·&nbsp;
                                        60+: {{ number_format($data['age_60_100']) }}
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- SOCIAL WELFARE PROGRAMS -->
            @if($programs->count() > 0)
            <div class="mb-4">
                <div class="section-hdr">
                    <h5>Social Welfare Programs in {{ $municipality->name }}</h5>
                    <p>Program beneficiary breakdown by type</p>
                </div>
                <div class="row g-3">
                    @foreach($programs->groupBy('program_type') as $type => $programsByType)
                    <div class="col-md-4 col-sm-6">
                        <div class="prog-benefit-card">
                            <div class="pbc-type">{{ str_replace('_', ' ', $type) }}</div>
                            <div class="pbc-count">{{ number_format($programsByType->sum('beneficiary_count')) }}</div>
                            <div class="pbc-sub">Beneficiaries</div>
                            <div class="pbc-bar"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

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

        document.addEventListener('DOMContentLoaded', function () {
            const barangayNames  = {!! json_encode(array_keys($barangayData)) !!};
            const populations    = {!! json_encode(array_column($barangayData, 'population')) !!};
            const males          = {!! json_encode(array_column($barangayData, 'male')) !!};
            const females        = {!! json_encode(array_column($barangayData, 'female')) !!};
            const singleParents  = {!! json_encode(array_column($barangayData, 'single_parents')) !!};
            const age0_19        = {!! json_encode(array_column($barangayData, 'age_0_19')) !!};
            const age20_59       = {!! json_encode(array_column($barangayData, 'age_20_59')) !!};
            const age60_100      = {!! json_encode(array_column($barangayData, 'age_60_100')) !!};

            /* -- Top 10 Barangays by Population (horizontal bar) -- */
            const top10 = barangayNames.slice(0, 10).map((name, i) => ({ name, pop: populations[i] }));
            new Chart(document.getElementById('topBarangaysChart'), {
                type: 'bar',
                data: {
                    labels: top10.map(d => d.name),
                    datasets: [{ label: 'Population', data: top10.map(d => d.pop), backgroundColor: BLUE, borderRadius: 4 }]
                },
                options: { ...chartDefaults, indexAxis: 'y', scales: { x: { beginAtZero: true, grid: { color: '#f1f5f9' } }, y: { grid: { display: false } } } }
            });

            /* -- Age Distribution (Doughnut) -- */
            const totalAge0_19   = age0_19.reduce((a, b) => a + b, 0);
            const totalAge20_59  = age20_59.reduce((a, b) => a + b, 0);
            const totalAge60_100 = age60_100.reduce((a, b) => a + b, 0);
            new Chart(document.getElementById('ageDistributionChart'), {
                type: 'doughnut',
                data: {
                    labels: ['0–19 years', '20–59 years', '60+ years'],
                    datasets: [{ data: [totalAge0_19, totalAge20_59, totalAge60_100], backgroundColor: [BLUE, GREEN, YELLOW], borderWidth: 2, borderColor: '#fff' }]
                },
                options: { ...chartDefaults, cutout: '60%', plugins: { ...chartDefaults.plugins, legend: { ...chartDefaults.plugins.legend, position: 'bottom' } } }
            });

            /* -- Gender Distribution (grouped bar) -- */
            new Chart(document.getElementById('genderChart'), {
                type: 'bar',
                data: {
                    labels: barangayNames.slice(0, 8),
                    datasets: [
                        { label: 'Male',   data: males.slice(0, 8),   backgroundColor: BLUE, borderRadius: 4 },
                        { label: 'Female', data: females.slice(0, 8), backgroundColor: PINK, borderRadius: 4 }
                    ]
                },
                options: { ...chartDefaults, scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } } }
            });

            /* -- Single Parents (smooth area line) -- */
            new Chart(document.getElementById('singleParentsChart'), {
                type: 'line',
                data: {
                    labels: barangayNames.slice(0, 8),
                    datasets: [{
                        label: 'Single Parents',
                        data: singleParents.slice(0, 8),
                        borderColor: 'rgba(253,185,19,1)',
                        backgroundColor: 'rgba(253,185,19,0.10)',
                        tension: 0.4, fill: true,
                        pointBackgroundColor: 'rgba(253,185,19,1)', pointRadius: 5
                    }]
                },
                options: { ...chartDefaults, scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } } }
            });
        });

        function showBarangayDetails(barangayName) {
            const data  = {!! json_encode($barangayData) !!}[barangayName];
            const modal = new bootstrap.Modal(document.getElementById('barangayModal'));
            document.getElementById('modalTitle').textContent = `${barangayName} — Detailed Analysis`;

            const pct = (v, t) => t > 0 ? ((v / t) * 100).toFixed(1) + '%' : '—';

            document.getElementById('modalBody').innerHTML = `
                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="fw-700 mb-2" style="font-weight:800;font-size:.8rem;text-transform:uppercase;letter-spacing:.08em;color:#64748b;">Demographics</p>
                        <div class="modal-stat"><div class="ms-label">Total Population</div><div class="ms-val">${data.population.toLocaleString()}</div></div>
                        <div class="modal-stat"><div class="ms-label">Male</div><div class="ms-val">${data.male.toLocaleString()} <span style="font-size:.8rem;font-weight:600;color:#64748b;">(${pct(data.male,data.population)})</span></div></div>
                        <div class="modal-stat"><div class="ms-label">Female</div><div class="ms-val">${data.female.toLocaleString()} <span style="font-size:.8rem;font-weight:600;color:#64748b;">(${pct(data.female,data.population)})</span></div></div>
                        <div class="modal-stat"><div class="ms-label">Households</div><div class="ms-val">${data.households.toLocaleString()}</div></div>
                        <div class="modal-stat"><div class="ms-label">Single Parents</div><div class="ms-val">${data.single_parents.toLocaleString()}</div></div>
                    </div>
                    <div class="col-md-6">
                        <p class="fw-700 mb-2" style="font-weight:800;font-size:.8rem;text-transform:uppercase;letter-spacing:.08em;color:#64748b;">Age Distribution</p>
                        <div class="modal-stat"><div class="ms-label">0–19 years (Youth)</div><div class="ms-val">${data.age_0_19.toLocaleString()} <span style="font-size:.8rem;font-weight:600;color:#64748b;">(${pct(data.age_0_19,data.population)})</span></div></div>
                        <div class="modal-stat"><div class="ms-label">20–59 years (Adult)</div><div class="ms-val">${data.age_20_59.toLocaleString()} <span style="font-size:.8rem;font-weight:600;color:#64748b;">(${pct(data.age_20_59,data.population)})</span></div></div>
                        <div class="modal-stat"><div class="ms-label">60+ years (Senior)</div><div class="ms-val">${data.age_60_100.toLocaleString()} <span style="font-size:.8rem;font-weight:600;color:#64748b;">(${pct(data.age_60_100,data.population)})</span></div></div>
                        <p class="fw-700 mb-2 mt-3" style="font-weight:800;font-size:.8rem;text-transform:uppercase;letter-spacing:.08em;color:#64748b;">Applications</p>
                        <div class="modal-stat"><div class="ms-label">Approved Applications</div><div class="ms-val">${data.approved_apps} <span style="font-size:.8rem;font-weight:600;color:#64748b;">(${pct(data.approved_apps,data.households)} of households)</span></div></div>
                    </div>
                </div>
            `;
            modal.show();
        }
    </script>
</body>
</html>