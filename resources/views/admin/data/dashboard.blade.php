<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Management – MSWDO Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --secondary-yellow: #FDB913;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --secondary-gradient: linear-gradient(135deg, #FDB913 0%, #E5A500 100%);
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
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; transition: all 0.25s; border-radius: 8px; padding: 10px 18px !important; font-size: 0.93rem; }
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
        .hero-banner p { opacity: 0.82; font-size: 0.93rem; margin: 0; }
        .muni-badge-lg { background: rgba(253,185,19,0.18); border: 1px solid rgba(253,185,19,0.35); color: var(--secondary-yellow); border-radius: 12px; padding: 14px 24px; text-align: center; }
        .muni-badge-lg .muni-name { font-size: 1.35rem; font-weight: 900; display: block; }
        .muni-badge-lg .muni-sub  { font-size: 0.72rem; opacity: 0.75; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; }

        /* STAT CARDS */
        .stat-card { background: var(--bg-white); border-radius: 18px; border: 1px solid var(--border-light); box-shadow: 0 4px 15px rgba(0,0,0,0.03); height: 100%; transition: all 0.3s; position: relative; overflow: hidden; }
        .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--primary-gradient); }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(44,62,143,0.10); }
        .stat-card .inner { padding: 26px 28px; }
        .stat-pill  { font-size: 0.68rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; background: #E5EEFF; color: var(--primary-blue); border-radius: 20px; padding: 3px 10px; display: inline-block; margin-bottom: 10px; }
        .stat-label { font-size: 0.78rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px; }
        .stat-value { font-size: 2.6rem; font-weight: 900; color: var(--primary-blue); line-height: 1; }
        .stat-sub   { font-size: 0.75rem; color: #94a3b8; margin-top: 8px; font-weight: 500; }

        /* SECTION HEADING */
        .section-heading { font-size: 1.05rem; font-weight: 800; color: var(--primary-blue); position: relative; padding-bottom: 10px; margin-bottom: 20px; }
        .section-heading::after { content: ''; position: absolute; bottom: 0; left: 0; width: 36px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; }

        /* MENU CARDS */
        .menu-card { background: var(--bg-white); border-radius: 18px; padding: 28px 26px; border: 1px solid var(--border-light); box-shadow: 0 4px 15px rgba(0,0,0,0.03); transition: all 0.3s ease; height: 100%; position: relative; overflow: hidden; display: block; color: inherit; }
        .menu-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--primary-gradient); transition: height 0.3s; }
        .menu-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(44,62,143,0.12); border-color: var(--primary-blue); color: inherit; }
        .menu-card:hover::before { height: 6px; }
        .menu-num   { font-size: 0.68rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; color: var(--primary-blue); background: var(--bg-soft-blue); border-radius: 20px; padding: 3px 10px; display: inline-block; margin-bottom: 14px; }
        .menu-title { font-size: 1.1rem; font-weight: 800; color: var(--primary-blue); margin-bottom: 8px; }
        .menu-desc  { font-size: 0.83rem; color: #64748b; line-height: 1.65; }
        .menu-arrow { font-size: 1.2rem; margin-top: 16px; color: var(--primary-blue); opacity: 0.35; display: block; transition: all 0.25s; }
        .menu-card:hover .menu-arrow { opacity: 1; transform: translateX(4px); }

        /* ALERT */
        .alert-success-c { border-radius: 12px; font-size: 0.88rem; padding: 12px 16px; margin-bottom: 16px; background: #d4edda; border-left: 4px solid #28a745; color: #155724; border: none; }

        /* MAIN GROW */
        .main-content { flex: 1; }

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
                <img src="/images/mswd-logo.png" alt="MSWD" style="width:36px;height:36px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.requirements*') ? 'active' : '' }}" href="{{ route('admin.requirements') }}">Applications</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.data.dashboard') }}">Data Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.detailed-analysis') }}">Analysis</a></li>
                    <li class="nav-item"><a class="nav-link" href="/analysis">Public View</a></li>
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
                        <div class="hero-badge">Data Management</div>
                        <h1>Manage Your Data</h1>
                        <div class="hero-divider"></div>
                        <p>Update municipality profiles, barangay records, and social welfare program data for {{ $municipality->name }}.</p>
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

        @if(session('success'))
            <div class="alert-success-c">{{ session('success') }}</div>
        @endif

        <!-- STAT CARDS -->
        <div class="row g-3 mb-5">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="inner">
                        <span class="stat-pill">Population</span>
                        <div class="stat-label">Total Residents</div>
                        <div class="stat-value">{{ number_format($municipality->male_population + $municipality->female_population) }}</div>
                        <div class="stat-sub">Male &amp; female combined</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="inner">
                        <span class="stat-pill">Barangays</span>
                        <div class="stat-label">Total Barangays</div>
                        <div class="stat-value">{{ number_format($barangays) }}</div>
                        <div class="stat-sub">In {{ $municipality->name }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
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
        <p class="section-heading">Manage Your Data</p>
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <a href="{{ route('admin.data.municipality') }}" class="menu-card">
                    <span class="menu-num">01 — Municipality</span>
                    <div class="menu-title">Municipality Profile</div>
                    <div class="menu-desc">Update population, households, age groups, and program beneficiary counts for {{ $municipality->name }}.</div>
                    <span class="menu-arrow">&#8594;</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.data.barangays') }}" class="menu-card">
                    <span class="menu-num">02 — Barangays</span>
                    <div class="menu-title">Barangay Records</div>
                    <div class="menu-desc">Manage population, household, and demographic data for all {{ $barangays }} barangays in {{ $municipality->name }}.</div>
                    <span class="menu-arrow">&#8594;</span>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.data.programs') }}" class="menu-card">
                    <span class="menu-num">03 — Programs</span>
                    <div class="menu-title">Social Programs</div>
                    <div class="menu-desc">Manage social welfare program beneficiaries, enrollment data, and yearly records.</div>
                    <span class="menu-arrow">&#8594;</span>
                </a>
            </div>
        </div>

    </div>
    </div>

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>