<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MSWDO Comparative Analysis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            /* Blue as MAIN color */
            --primary-blue: #2C3E8F;
            --primary-blue-light: #E5EEFF;
            --primary-blue-soft: #5D7BB9;
            
            /* Yellow as SECONDARY */
            --secondary-yellow: #FDB913;
            --secondary-yellow-light: #FFF3D6;
            
            /* Red as MINIMAL accent */
            --accent-red: #C41E24;
            --accent-red-light: #FCE8E8;
            
            /* Gradients */
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --secondary-gradient: linear-gradient(135deg, #FDB913 0%, #E5A500 100%);
            
            /* Backgrounds */
            --bg-light: #F8FAFC;
            --bg-white: #FFFFFF;
            --bg-soft-blue: #F0F5FF;
        }

        body {
            background: var(--bg-light);
            font-family: 'Inter', 'Segoe UI', sans-serif;
        }


        /* Navbar */
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44, 62, 143, 0.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.55rem; color: white !important; display:flex; align-items:center; gap:10px; }
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; transition: all 0.25s; border-radius: 8px; padding: 10px 18px !important; font-size: 0.95rem; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .user-info { color: white; display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.1); padding: 9px 22px; border-radius: 40px; font-size:0.92rem; font-weight:500; }
        .logout-btn { background: transparent; border: 2px solid rgba(255,255,255,0.8); color: white; border-radius: 30px; padding: 6px 18px; font-weight: 700; transition: all 0.3s; font-size:0.88rem; cursor:pointer; }
        .logout-btn:hover { background: var(--secondary-yellow); color: var(--primary-blue); border-color: var(--secondary-yellow); }
        .btn-login { background: white; color: var(--primary-blue); border: 2px solid white; border-radius: 30px; padding: 8px 25px; font-weight: 700; text-decoration: none; transition: all 0.3s; }
        .btn-login:hover { background: var(--secondary-yellow); color: var(--primary-blue); border-color: var(--secondary-yellow); transform: translateY(-2px); }
        .btn-register { background: transparent; border: 2px solid white; color: white; border-radius: 30px; padding: 8px 25px; font-weight: 700; text-decoration: none; transition: all 0.3s; }
        .btn-register:hover { background: var(--secondary-yellow); color: var(--primary-blue); transform: translateY(-2px); }


        /* Page Header */
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-blue);
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .page-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 80px;
            height: 4px;
            background: var(--primary-gradient);
            border-radius: 2px;
        }

        .page-title i {
            color: var(--secondary-yellow);
        }

        /* Stat Cards — white with top accent bar */
        .stat-card {
            background: white;
            border-radius: 18px;
            border: 1px solid #E2E8F0;
            overflow: hidden;
            transition: transform .28s, box-shadow .28s;
            height: 100%;
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 14px 32px rgba(44,62,143,0.11); }
        .accent-bar { height: 4px; }
        .acc-blue   { background: linear-gradient(90deg,#2C3E8F,#5578d9); }
        .acc-green  { background: linear-gradient(90deg,#16a34a,#22c55e); }
        .acc-yellow { background: linear-gradient(90deg,#FDB913,#E5A500); }
        .acc-teal   { background: linear-gradient(90deg,#0891b2,#22d3ee); }
        .acc-red    { background: linear-gradient(90deg,#dc2626,#ef4444); }
        .sc-body  { padding: 18px 20px 16px; }
        .sc-label { font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing:.1em; color: #64748b; margin-bottom: 6px; }
        .sc-value { font-size: 1.9rem; font-weight: 900; color: var(--primary-blue); line-height:1.1; margin-bottom:3px; }
        .sc-sub   { font-size: 0.75rem; color: #64748b; font-weight:500; }

        /* Section header (yellow-underline style) */
        .sec-title { font-size:1rem; font-weight:800; color:var(--primary-blue); position:relative; padding-bottom:10px; margin-bottom:4px; }
        .sec-title::after { content:''; position:absolute; bottom:0; left:0; width:34px; height:4px; background:var(--secondary-yellow); border-radius:2px; }
        .sec-sub { font-size:0.8rem; color:#64748b; margin:8px 0 0; }

        /* Chart Cards */
        .chart-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border: 1px solid #E2E8F0;
            position: relative;
            overflow: hidden;
        }

        .chart-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
        }

        .chart-card .card-header {
            background: transparent;
            border: none;
            padding: 0 0 15px 0;
        }

        .chart-card .card-header h5 {
            color: var(--primary-blue);
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chart-card .card-header h5 i {
            color: var(--secondary-yellow);
        }

        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
        }

        /* Municipality beneficiary cards — white with accent bar */
        .program-card {
            background: white;
            border-radius: 18px;
            border: 1px solid #E2E8F0;
            overflow: hidden;
            height: 100%;
            transition: transform .28s, box-shadow .28s;
        }
        .program-card:hover { transform: translateY(-4px); box-shadow: 0 14px 32px rgba(44,62,143,0.11); }
        .program-card .pc-body { padding: 20px 22px 18px; }
        .program-card .pc-muni { font-size:0.7rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#64748b; margin-bottom:6px; }
        .program-card .pc-value { font-size:2rem; font-weight:900; color:var(--primary-blue); line-height:1.1; margin-bottom:2px; }
        .program-card .pc-label { font-size:0.75rem; color:#64748b; font-weight:500; }
        .program-card .pc-active { font-size:0.72rem; color:#64748b; margin-top:6px; }

        .legend-item {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 4px;
            margin-right: 5px;
        }

        /* Municipality Cards */
        .municipality-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border: 1px solid #E2E8F0;
            height: 100%;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .municipality-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(44, 62, 143, 0.1);
            border-color: var(--primary-blue-soft);
        }

        .municipality-card .card-header {
            background: var(--primary-gradient);
            color: white;
            padding: 15px 20px;
            border: none;
        }

        .municipality-card .card-header h5 {
            margin: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .municipality-card .card-header h5 i {
            color: var(--secondary-yellow);
        }

        .municipality-card .card-body {
            padding: 20px;
        }

        .municipality-card .table {
            margin-bottom: 0;
            font-size: 0.95rem;
        }

        .municipality-card .table td {
            padding: 8px 0;
            border: none;
        }

        .badge.bg-warning {
            background: var(--secondary-yellow) !important;
            color: var(--primary-blue);
            padding: 5px 10px;
            border-radius: 20px;
        }

        .badge.bg-success {
            background: #28a745 !important;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .badge.bg-danger {
            background: var(--accent-red) !important;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .btn-view {
            background: var(--primary-blue-light);
            color: var(--primary-blue);
            border: 1px solid var(--primary-blue-soft);
            border-radius: 30px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-view:hover {
            background: var(--primary-gradient);
            color: white;
            transform: translateY(-2px);
        }

        .program-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px dashed #E2E8F0;
        }

        .program-item:last-child {
            border-bottom: none;
        }

        .program-name {
            color: #475569;
            font-size: 0.9rem;
        }

        .program-count {
            font-weight: 600;
            color: var(--primary-blue);
            background: var(--bg-soft-blue);
            padding: 2px 10px;
            border-radius: 20px;
        }

        /* Alerts */
        .alert-success {
            background: var(--bg-soft-blue);
            color: var(--primary-blue);
            border-left: 5px solid var(--primary-blue);
            border-radius: 12px;
        }

        .alert-info {
            background: var(--secondary-yellow-light);
            color: var(--primary-blue);
            border-left: 5px solid var(--secondary-yellow);
            border-radius: 12px;
        }

        .alert-warning {
            background: var(--accent-red-light);
            color: var(--accent-red);
            border-left: 5px solid var(--accent-red);
            border-radius: 12px;
        }

        @media (max-width: 768px) {
            .page-title { font-size: 1.5rem; }
            .chart-container { height: 300px; }
            .stat-card h2 { font-size: 2rem; }
        }

        .btn-group .btn-light {
            background-color: rgba(255, 255, 255, 0.2);
            border-color: transparent;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-group .btn-light:hover { background-color: rgba(255, 255, 255, 0.3); color: white; }
        .btn-group .btn-light.active { background-color: var(--secondary-yellow); color: var(--primary-blue); border-color: transparent; }
        .btn-group .btn-light i { margin-right: 5px; }

        /* ===== HERO BANNER ===== */
        .hero-banner {
            background: var(--primary-gradient);
            color: white;
            padding: 58px 0 48px;
            position: relative;
            overflow: hidden;
        }
        .hero-banner::before {
            content: ''; position: absolute; top: -70px; right: -70px;
            width: 320px; height: 320px; border-radius: 50%;
            background: rgba(253,185,19,0.1);
        }
        .hero-banner::after {
            content: ''; position: absolute; bottom: -80px; left: -50px;
            width: 250px; height: 250px; border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }
        .hero-banner .hero-badge {
            display: inline-block;
            background: rgba(253,185,19,0.18);
            color: var(--secondary-yellow);
            border: 1px solid rgba(253,185,19,0.35);
            border-radius: 30px; padding: 5px 18px;
            font-size: 0.78rem; font-weight: 700;
            letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 18px;
        }
        .hero-banner h1 { font-size: 2.6rem; font-weight: 800; line-height: 1.2; margin-bottom: 14px; }
        .hero-divider { width: 55px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; margin: 16px 0; }
        .hero-banner p { font-size: 1.02rem; opacity: 0.87; max-width: 700px; line-height: 1.75; }
        .hero-stat-pill {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2);
            border-radius: 30px; padding: 8px 20px;
            font-size: 0.9rem; font-weight: 600; margin-right: 10px; margin-top: 10px;
        }
        .hero-stat-pill span { color: var(--secondary-yellow); font-size: 1.05rem; font-weight: 800; }

        /* ===== FOOTER ===== */
        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,0.75); text-align: center; padding: 18px 0; font-size: 0.85rem; margin-top: 60px; }
        .footer-strip strong { color: white; }
    </style>
</head>
<body>
    <!-- Navigation Bar — role-aware -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/analysis">
                <img src="/images/mswd-logo.png" alt="MSWD" style="width:36px;height:36px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">

                {{-- ── SUPER ADMIN NAV ── --}}
                @auth
                @if(Auth::user()->isSuperAdmin())
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.users') }}">User Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.municipalities.index') }}">Municipalities</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.data.dashboard') }}">Data Management</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/analysis">Public View</a></li>
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
                {{-- ── ADMIN NAV ── --}}
                @elseif(Auth::user()->isAdmin())
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.requirements') }}">Applications</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.data.dashboard') }}">Data Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.detailed-analysis') }}">Analysis</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/analysis/programs">Public View</a></li>
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
                @else
                {{-- ── USER / GUEST PUBLIC NAV ── --}}
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/analysis">Programs</a></li>
                    <li class="nav-item"><a class="nav-link" href="/analysis/demographic">Demographic</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/analysis/programs">Analysis</a></li>
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
                @endif
                @else
                {{-- ── GUEST NAV ── --}}
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/analysis">Programs</a></li>
                    <li class="nav-item"><a class="nav-link" href="/analysis/demographic">Demographic</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/analysis/programs">Analysis</a></li>
                </ul>
                <div class="d-flex">
                    <a href="{{ route('login') }}" class="btn-login me-2">Login</a>
                    <a href="{{ route('register') }}" class="btn-register">Register</a>
                </div>
                @endauth

            </div>
        </div>
    </nav>

    <!-- ===== HERO BANNER ===== -->
    <section class="hero-banner">
        <div class="container" style="position:relative;z-index:2;">
            <div class="hero-badge">Comparative Analysis</div>
            <h1>Magdalena, Liliw &amp; Majayjay</h1>
            <div class="hero-divider"></div>
            <p>A side-by-side comparison of population demographics, social welfare program beneficiaries, and household statistics across the three municipalities.</p>
            <div class="mt-3">
                <span class="hero-stat-pill">Population &amp; Households</span>
                <span class="hero-stat-pill">Program Beneficiaries</span>
                <span class="hero-stat-pill">Trends &amp; Insights</span>
            </div>
        </div>
    </section>

    <div class="container mt-4">
        <!-- Session Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div style="margin:8px 0 28px;">
            <div class="sec-title" style="font-size:1.4rem;">Comparative Analysis: Magdalena, Liliw &amp; Majayjay</div>
            <p class="sec-sub">Side-by-side population, household, and social welfare statistics across the three municipalities.</p>
        </div>

        <!-- Summary Stats (existing) -->
<div class="row g-3 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="accent-bar acc-blue"></div>
            <div class="sc-body">
                <div class="sc-label">Total Population</div>
                <div class="sc-value">{{ number_format(array_sum(array_column($comparisonData, 'total_population'))) }}</div>
                <div class="sc-sub">3 Municipalities</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="accent-bar acc-green"></div>
            <div class="sc-body">
                <div class="sc-label">Total Households</div>
                <div class="sc-value">{{ number_format(array_sum(array_column($comparisonData, 'households'))) }}</div>
                <div class="sc-sub">Registered households</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="accent-bar acc-yellow"></div>
            <div class="sc-body">
                <div class="sc-label">Single Parents</div>
                <div class="sc-value">{{ number_format(array_sum(array_column($comparisonData, 'single_parents'))) }}</div>
                <div class="sc-sub">Solo parent households</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="accent-bar acc-teal"></div>
            <div class="sc-body">
                <div class="sc-label">Approved Applications</div>
                <div class="sc-value">{{ number_format(array_sum(array_column($comparisonData, 'approved_apps'))) }}</div>
                <div class="sc-sub">Total approved</div>
            </div>
        </div>
    </div>
</div>

<!-- Comparative Analysis Card with Toggle Buttons -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card stat-card" style="background:white; border:1px solid #E2E8F0;">
            <div class="card-header text-white d-flex justify-content-between align-items-center" style="background:linear-gradient(135deg,#2C3E8F 0%,#1A2A5C 100%); border:none; padding:16px 20px;">
                <h5 class="mb-0" style="font-weight:700; font-size:0.97rem;">Comparative Analysis: Population, Households &amp; Single Parents</h5>
                <div class="btn-group" role="group" aria-label="Chart type toggle">
                    <button type="button" class="btn btn-light btn-sm active" id="barBtn" onclick="showBarChart()">Bar Graph</button>
                    <button type="button" class="btn btn-light btn-sm" id="lineBtn" onclick="showLineChart()">Line Graph</button>
                </div>
            </div>
            <div class="card-body">
                <!-- Bar Chart Container -->
                <div id="barChartContainer" class="chart-container" style="position: relative; height: 400px;">
                    <canvas id="comparativeBarChart"></canvas>
                </div>
                <!-- Line Chart Container (initially hidden) -->
                <div id="lineChartContainer" class="chart-container" style="position: relative; height: 400px; display: none;">
                    <canvas id="comparativeLineChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>



        <!-- Program Beneficiaries Overview Cards -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="chart-card">
                    <div class="card-header" style="border-bottom:1px solid #E2E8F0; padding:18px 22px 16px; background:white;">
                        <div class="sec-title">Program Beneficiaries Overview</div>
                        <div class="sec-sub">Total beneficiaries per municipality</div>
                    </div>
                    <div class="card-body" style="padding:18px 22px;">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="program-card">
                                    <div class="accent-bar acc-green"></div>
                                    <div class="pc-body">
                                        <div class="pc-muni">Magdalena</div>
                                        <div class="pc-value">{{ number_format($municipalityProgramTotals['Magdalena']) }}</div>
                                        <div class="pc-label">Total Beneficiaries</div>
                                        <div class="pc-active">{{ $comparisonData['Magdalena']['programs']->count() }} active programs</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="program-card">
                                    <div class="accent-bar acc-yellow"></div>
                                    <div class="pc-body">
                                        <div class="pc-muni">Liliw</div>
                                        <div class="pc-value">{{ number_format($municipalityProgramTotals['Liliw']) }}</div>
                                        <div class="pc-label">Total Beneficiaries</div>
                                        <div class="pc-active">{{ $comparisonData['Liliw']['programs']->count() }} active programs</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="program-card">
                                    <div class="accent-bar acc-blue"></div>
                                    <div class="pc-body">
                                        <div class="pc-muni">Majayjay</div>
                                        <div class="pc-value">{{ number_format($municipalityProgramTotals['Majayjay']) }}</div>
                                        <div class="pc-label">Total Beneficiaries</div>
                                        <div class="pc-active">{{ $comparisonData['Majayjay']['programs']->count() }} active programs</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Program Beneficiaries Comparison Graph -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="chart-card">
                    <div class="card-header" style="border-bottom:1px solid #E2E8F0; padding:18px 22px 16px; background:white;">
                        <div class="sec-title">Program Beneficiaries Comparison</div>
                        <div class="sec-sub">Breakdown by municipality and program type</div>
                    </div>
                    <div class="card-body">
                        <!-- Graph Legend -->
                        <div class="text-center mb-3">
                            <span class="me-3">
                                <span class="legend-item" style="background-color: #28a745;"></span>
                                Magdalena
                            </span>
                            <span class="me-3">
                                <span class="legend-item" style="background-color: #FDB913;"></span>
                                Liliw
                            </span>
                            <span class="me-3">
                                <span class="legend-item" style="background-color: #2C3E8F;"></span>
                                Majayjay
                            </span>
                        </div>
                        
                        <!-- Chart Container -->
                        <div class="chart-container">
                            <canvas id="programComparisonChart"></canvas>
                        </div>
                        
                        <!-- Graph Insights -->
                        <div class="row g-3 mt-2">
                            <div class="col-md-4">
                                <div class="alert alert-success">
                                    <strong>Highest Total:</strong>
                                    @php $highestTotal = array_keys($municipalityProgramTotals, max($municipalityProgramTotals))[0]; @endphp
                                    {{ $highestTotal }} ({{ number_format(max($municipalityProgramTotals)) }} beneficiaries)
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-info">
                                    <strong>Most Programs:</strong>
                                    @php
                                        $mostPrograms = 'Magdalena'; $maxCount = $comparisonData['Magdalena']['programs']->count();
                                        if($comparisonData['Liliw']['programs']->count() > $maxCount){ $mostPrograms='Liliw'; $maxCount=$comparisonData['Liliw']['programs']->count(); }
                                        if($comparisonData['Majayjay']['programs']->count() > $maxCount){ $mostPrograms='Majayjay'; $maxCount=$comparisonData['Majayjay']['programs']->count(); }
                                    @endphp
                                    {{ $mostPrograms }} ({{ $maxCount }} programs)
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-warning">
                                    <strong>Total Across All:</strong>
                                    {{ number_format(array_sum($municipalityProgramTotals)) }} beneficiaries
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="chart-card">
                    <div class="card-header" style="border-bottom:1px solid #E2E8F0; padding:18px 22px 16px; background:white;">
                        <div class="sec-title">Population Distribution by Municipality</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="populationChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-card">
                    <div class="card-header" style="border-bottom:1px solid #E2E8F0; padding:18px 22px 16px; background:white;">
                        <div class="sec-title">Gender Distribution</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="chart-card">
                    <div class="card-header" style="border-bottom:1px solid #E2E8F0; padding:18px 22px 16px; background:white;">
                        <div class="sec-title">Age Group Distribution</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="ageChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-card">
                    <div class="card-header" style="border-bottom:1px solid #E2E8F0; padding:18px 22px 16px; background:white;">
                        <div class="sec-title">Application Status</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="applicationChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Municipality Details -->
        <div class="row mt-4 g-3">
            @foreach($comparisonData as $municipality => $data)
            <div class="col-md-4 mb-4">
                <div class="municipality-card">
                    <div class="card-header">
                        <h5>{{ $municipality }}</h5>
                    </div>
                    <div class="card-body">
                        <!-- Quick Stats -->
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <h6 class="text-muted">Population</h6>
                                <p class="h4" style="color: var(--primary-blue);">{{ number_format($data['total_population']) }}</p>
                            </div>
                            <div class="col-6">
                                <h6 class="text-muted">Households</h6>
                                <p class="h4" style="color: var(--primary-blue);">{{ number_format($data['households']) }}</p>
                            </div>
                        </div>

                        <!-- Demographics -->
                        <h6 class="mt-3" style="color: var(--primary-blue); font-weight:700;">Demographics</h6>
                        <table class="table table-sm">
                            <tr>
                                <td>Male:</td>
                                <td class="text-end">{{ number_format($data['male']) }}</td>
                                <td class="text-end"><span class="badge" style="background: var(--bg-soft-blue); color: var(--primary-blue);">{{ $data['total_population'] > 0 ? round(($data['male']/$data['total_population'])*100,1) : 0 }}%</span></td>
                            </tr>
                            <tr>
                                <td>Female:</td>
                                <td class="text-end">{{ number_format($data['female']) }}</td>
                                <td class="text-end"><span class="badge" style="background: var(--bg-soft-blue); color: var(--primary-blue);">{{ $data['total_population'] > 0 ? round(($data['female']/$data['total_population'])*100,1) : 0 }}%</span></td>
                            </tr>
                            <tr>
                                <td>Single Parents:</td>
                                <td class="text-end">{{ number_format($data['single_parents']) }}</td>
                                <td></td>
                            </tr>
                        </table>

                        <!-- Age Distribution -->
                        <h6 class="mt-3" style="color: var(--primary-blue); font-weight:700;">Age Groups</h6>
                        <table class="table table-sm">
                            @foreach($data['age_groups'] as $group => $count)
                            <tr>
                                <td>{{ $group }}:</td>
                                <td class="text-end">{{ number_format($count) }}</td>
                                <td class="text-end"><span class="badge" style="background: var(--bg-soft-blue); color: var(--primary-blue);">{{ $data['total_population'] > 0 ? round(($count/$data['total_population'])*100,1) : 0 }}%</span></td>
                            </tr>
                            @endforeach
                        </table>

                        <!-- Applications -->
                        <h6 class="mt-3" style="color: var(--primary-blue); font-weight:700;">Applications</h6>
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <span class="badge bg-warning">Pending</span>
                                <p class="mt-1">{{ $data['pending_apps'] }}</p>
                            </div>
                            <div class="col-4">
                                <span class="badge bg-success">Approved</span>
                                <p class="mt-1">{{ $data['approved_apps'] }}</p>
                            </div>
                            <div class="col-4">
                                <span class="badge bg-danger">Rejected</span>
                                <p class="mt-1">{{ $data['rejected_apps'] }}</p>
                            </div>
                        </div>

                        <!-- Programs -->
                        <h6 class="mt-3" style="color: var(--primary-blue); font-weight:700;">Program Beneficiaries</h6>
                        <div class="program-list">
                            @forelse($data['programs'] as $program => $count)
                            <div class="program-item">
                                <span class="program-name">{{ str_replace('_', ' ', $program) }}:</span>
                                <span class="program-count">{{ number_format($count) }}</span>
                            </div>
                            @empty
                            <p class="text-muted text-center">No program data</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-0 pb-3 text-center">
                        <a href="/analysis/municipality/{{ $municipality }}" class="btn-view">View Barangay Details &rarr;</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Program Comparison Chart
        const programLabels = {!! json_encode(array_keys($programComparison ?? [])) !!};
        const magdalenaData = {!! json_encode(array_column($programComparison ?? [], 'magdalena')) !!};
        const liliwData = {!! json_encode(array_column($programComparison ?? [], 'liliw')) !!};
        const majayjayData = {!! json_encode(array_column($programComparison ?? [], 'majayjay')) !!};
        
        const formattedLabels = programLabels.map(label => label.replace(/_/g, ' '));
        
        new Chart(document.getElementById('programComparisonChart'), {
            type: 'bar',
            data: {
                labels: formattedLabels,
                datasets: [
                    {
                        label: 'Magdalena',
                        data: magdalenaData,
                        backgroundColor: '#28a745',
                        borderRadius: 6,
                        barPercentage: 0.7
                    },
                    {
                        label: 'Liliw',
                        data: liliwData,
                        backgroundColor: '#FDB913',
                        borderRadius: 6,
                        barPercentage: 0.7
                    },
                    {
                        label: 'Majayjay',
                        data: majayjayData,
                        backgroundColor: '#2C3E8F',
                        borderRadius: 6,
                        barPercentage: 0.7
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.raw.toLocaleString() + ' beneficiaries';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' },
                        title: { display: true, text: 'Number of Beneficiaries' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        // Population Chart
        new Chart(document.getElementById('populationChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($comparisonData)) !!},
                datasets: [{
                    label: 'Population',
                    data: {!! json_encode(array_column($comparisonData, 'total_population')) !!},
                    backgroundColor: '#2C3E8F',
                    borderRadius: 6,
                    barPercentage: 0.7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Gender Chart
        new Chart(document.getElementById('genderChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($comparisonData)) !!},
                datasets: [
                    {
                        label: 'Male',
                        data: {!! json_encode(array_column($comparisonData, 'male')) !!},
                        backgroundColor: '#2C3E8F',
                        borderRadius: 6,
                        barPercentage: 0.7
                    },
                    {
                        label: 'Female',
                        data: {!! json_encode(array_column($comparisonData, 'female')) !!},
                        backgroundColor: '#FDB913',
                        borderRadius: 6,
                        barPercentage: 0.7
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { usePointStyle: true } }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Age Chart - UPDATED with correct column names
        new Chart(document.getElementById('ageChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode(array_keys($comparisonData)) !!},
                datasets: [
                    {
                        label: '0-19 years',
                        data: {!! json_encode(array_column($comparisonData, 'population_0_19')) !!},
                        borderColor: '#2C3E8F',
                        backgroundColor: 'rgba(44, 62, 143, 0.1)',
                        tension: 0.1,
                        fill: true
                    },
                    {
                        label: '20-59 years',
                        data: {!! json_encode(array_column($comparisonData, 'population_20_59')) !!},
                        borderColor: '#FDB913',
                        backgroundColor: 'rgba(253, 185, 19, 0.1)',
                        tension: 0.1,
                        fill: true
                    },
                    {
                        label: '60-100 years',
                        data: {!! json_encode(array_column($comparisonData, 'population_60_100')) !!},
                        borderColor: '#C41E24',
                        backgroundColor: 'rgba(196, 30, 36, 0.1)',
                        tension: 0.1,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 10
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Application Chart
        new Chart(document.getElementById('applicationChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($comparisonData)) !!},
                datasets: [
                    {
                        label: 'Pending',
                        data: {!! json_encode(array_column($comparisonData, 'pending_apps')) !!},
                        backgroundColor: '#FDB913',
                        borderRadius: 6,
                        barPercentage: 0.7
                    },
                    {
                        label: 'Approved',
                        data: {!! json_encode(array_column($comparisonData, 'approved_apps')) !!},
                        backgroundColor: '#28a745',
                        borderRadius: 6,
                        barPercentage: 0.7
                    },
                    {
                        label: 'Rejected',
                        data: {!! json_encode(array_column($comparisonData, 'rejected_apps')) !!},
                        backgroundColor: '#C41E24',
                        borderRadius: 6,
                        barPercentage: 0.7
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { usePointStyle: true } }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // ===== COMPARATIVE CHARTS (Bar & Line) =====
        // Data for both charts
        const barChartData = {
            labels: {!! json_encode(array_keys($comparisonData)) !!},
            datasets: [
                {
                    label: 'Population',
                    data: {!! json_encode(array_column($comparisonData, 'total_population')) !!},
                    backgroundColor: '#2C3E8F',
                    borderRadius: 6,
                    barPercentage: 0.7,
                    categoryPercentage: 0.8
                },
                {
                    label: 'Households',
                    data: {!! json_encode(array_column($comparisonData, 'households')) !!},
                    backgroundColor: '#FDB913',
                    borderRadius: 6,
                    barPercentage: 0.7,
                    categoryPercentage: 0.8
                },
                {
                    label: 'Single Parents',
                    data: {!! json_encode(array_column($comparisonData, 'single_parents')) !!},
                    backgroundColor: '#C41E24',
                    borderRadius: 6,
                    barPercentage: 0.7,
                    categoryPercentage: 0.8
                }
            ]
        };

        const lineChartData = {
            labels: {!! json_encode(array_keys($comparisonData)) !!},
            datasets: [
                {
                    label: 'Population',
                    data: {!! json_encode(array_column($comparisonData, 'total_population')) !!},
                    borderColor: '#2C3E8F',
                    backgroundColor: 'rgba(44, 62, 143, 0.1)',
                    borderWidth: 3,
                    tension: 0.1,
                    fill: false,
                    pointBackgroundColor: '#2C3E8F',
                    pointBorderColor: 'white',
                    pointRadius: 6,
                    pointHoverRadius: 8
                },
                {
                    label: 'Households',
                    data: {!! json_encode(array_column($comparisonData, 'households')) !!},
                    borderColor: '#FDB913',
                    backgroundColor: 'rgba(253, 185, 19, 0.1)',
                    borderWidth: 3,
                    tension: 0.1,
                    fill: false,
                    pointBackgroundColor: '#FDB913',
                    pointBorderColor: 'white',
                    pointRadius: 6,
                    pointHoverRadius: 8
                },
                {
                    label: 'Single Parents',
                    data: {!! json_encode(array_column($comparisonData, 'single_parents')) !!},
                    borderColor: '#C41E24',
                    backgroundColor: 'rgba(196, 30, 36, 0.1)',
                    borderWidth: 3,
                    tension: 0.1,
                    fill: false,
                    pointBackgroundColor: '#C41E24',
                    pointBorderColor: 'white',
                    pointRadius: 6,
                    pointHoverRadius: 8
                }
            ]
        };

        // Chart options (shared)
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        boxWidth: 10
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.raw.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        };

        // Initialize Bar Chart
        window.barChart = new Chart(document.getElementById('comparativeBarChart'), {
            type: 'bar',
            data: barChartData,
            options: chartOptions
        });

        // Initialize Line Chart
        window.lineChart = new Chart(document.getElementById('comparativeLineChart'), {
            type: 'line',
            data: lineChartData,
            options: chartOptions
        });
    });

    // Toggle Functions (outside DOMContentLoaded para accessible globally)
    function showBarChart() {
        document.getElementById('barChartContainer').style.display = 'block';
        document.getElementById('lineChartContainer').style.display = 'none';
        
        document.getElementById('barBtn').classList.add('active');
        document.getElementById('lineBtn').classList.remove('active');
    }

    function showLineChart() {
        document.getElementById('barChartContainer').style.display = 'none';
        document.getElementById('lineChartContainer').style.display = 'block';
        
        document.getElementById('lineBtn').classList.add('active');
        document.getElementById('barBtn').classList.remove('active');
    }
</script>

    <!-- Footer -->
    <div class="footer-strip">
        <i class="bi bi-heart-fill me-1" style="color:#FDB913;"></i>
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>


    {{-- ── User back-button (non-admin authenticated users only) ── --}}
    @auth
    @if(!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin())
    <style>
        .back-dashboard-btn {
            position: fixed; bottom: 32px; left: 32px; z-index: 9999;
            display: flex; align-items: center; gap: 10px;
            background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            color: white; border: none; border-radius: 50px;
            padding: 13px 22px 13px 18px;
            font-family: 'Inter', sans-serif; font-weight: 700; font-size: 0.88rem;
            box-shadow: 0 8px 28px rgba(44,62,143,0.35);
            cursor: pointer; text-decoration: none;
            transition: all 0.3s ease;
            animation: slideInUp 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }
        .back-dashboard-btn:hover { transform: translateY(-4px); box-shadow: 0 14px 36px rgba(44,62,143,0.45); color: white; }
        .back-dashboard-btn .btn-dot { width: 8px; height: 8px; border-radius: 50%; background: #FDB913; flex-shrink: 0; box-shadow: 0 0 0 3px rgba(253,185,19,0.25); }
        .back-dashboard-btn .btn-label { letter-spacing: 0.02em; }
        .back-dashboard-btn .btn-arrow { width: 26px; height: 26px; border-radius: 50%; background: rgba(253,185,19,0.22); color: #FDB913; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 900; flex-shrink: 0; transition: transform 0.25s ease; }
        .back-dashboard-btn:hover .btn-arrow { transform: translateX(-3px); }
        @keyframes slideInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    </style>
    <a href="{{ route('user.dashboard') }}" class="back-dashboard-btn" title="Return to your dashboard">
        <span class="btn-arrow">&#8592;</span>
        <span class="btn-dot"></span>
        <span class="btn-label">My Dashboard</span>
    </a>
    @endif
    @endauth

    {{-- ── Back button: super-admin ── --}}
    @auth
    @if(Auth::user()->isSuperAdmin())
    <style>
        .admin-back-btn {
            position: fixed; bottom: 32px; left: 32px; z-index: 9999;
            display: flex; align-items: center; gap: 10px;
            background: linear-gradient(135deg, #FDB913 0%, #E5A500 100%);
            color: #1A2A5C; border: none; border-radius: 50px;
            padding: 13px 22px 13px 18px;
            font-family: 'Inter', sans-serif; font-weight: 800; font-size: 0.88rem;
            box-shadow: 0 8px 28px rgba(253,185,19,0.45);
            cursor: pointer; text-decoration: none;
            transition: all 0.3s ease;
            animation: adminSlideIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }
        .admin-back-btn:hover { transform: translateY(-4px); box-shadow: 0 14px 36px rgba(253,185,19,0.55); color: #1A2A5C; }
        .admin-back-btn .abtn-dot { width: 8px; height: 8px; border-radius: 50%; background: #1A2A5C; flex-shrink: 0; box-shadow: 0 0 0 3px rgba(26,42,92,0.18); }
        .admin-back-btn .abtn-label { letter-spacing: 0.02em; }
        .admin-back-btn .abtn-arrow { width: 26px; height: 26px; border-radius: 50%; background: rgba(26,42,92,0.12); color: #1A2A5C; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 900; flex-shrink: 0; transition: transform 0.25s ease; }
        .admin-back-btn:hover .abtn-arrow { transform: translateX(-3px); }
        @keyframes adminSlideIn { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    </style>
    <a href="{{ route('superadmin.dashboard') }}" class="admin-back-btn" title="Return to Super Admin Dashboard">
        <span class="abtn-arrow">&#8592;</span>
        <span class="abtn-dot"></span>
        <span class="abtn-label">Super Admin Dashboard</span>
    </a>
    {{-- ── Back button: admin ── --}}
    @elseif(Auth::user()->isAdmin())
    <style>
        .admin-back-btn {
            position: fixed; bottom: 32px; left: 32px; z-index: 9999;
            display: flex; align-items: center; gap: 10px;
            background: linear-gradient(135deg, #FDB913 0%, #E5A500 100%);
            color: #1A2A5C; border: none; border-radius: 50px;
            padding: 13px 22px 13px 18px;
            font-family: 'Inter', sans-serif; font-weight: 800; font-size: 0.88rem;
            box-shadow: 0 8px 28px rgba(253,185,19,0.45);
            cursor: pointer; text-decoration: none;
            transition: all 0.3s ease;
            animation: adminSlideIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }
        .admin-back-btn:hover { transform: translateY(-4px); box-shadow: 0 14px 36px rgba(253,185,19,0.55); color: #1A2A5C; }
        .admin-back-btn .abtn-dot { width: 8px; height: 8px; border-radius: 50%; background: #1A2A5C; flex-shrink: 0; box-shadow: 0 0 0 3px rgba(26,42,92,0.18); }
        .admin-back-btn .abtn-label { letter-spacing: 0.02em; }
        .admin-back-btn .abtn-arrow { width: 26px; height: 26px; border-radius: 50%; background: rgba(26,42,92,0.12); color: #1A2A5C; display: flex; align-items: center; justify-content: center; font-size: 1rem; font-weight: 900; flex-shrink: 0; transition: transform 0.25s ease; }
        .admin-back-btn:hover .abtn-arrow { transform: translateX(-3px); }
    </style>
    <a href="{{ route('admin.dashboard') }}" class="admin-back-btn" title="Return to Admin Dashboard">
        <span class="abtn-arrow">&#8592;</span>
        <span class="abtn-dot"></span>
        <span class="abtn-label">Admin Dashboard</span>
    </a>
    @endif
    @endauth

</body>
</html>