<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Programs – MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --secondary-yellow: #FDB913;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --bg-light: #F8FAFC;
            --bg-white: #FFFFFF;
            --bg-soft-blue: #F0F5FF;
            --border-light: #E2E8F0;
            --text-dark: #1E293B;
        }
        body { background: var(--bg-light); font-family: 'Inter', sans-serif; color: var(--text-dark); display:flex; flex-direction:column; min-height:100vh; }
        a { text-decoration: none; }

        /* ── NAVBAR ── */
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,0.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.55rem; color: white !important; display:flex; align-items:center; gap:10px; }
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; transition: all 0.25s; border-radius: 8px; padding: 10px 18px !important; font-size: 0.95rem; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .user-info { color:white; display:flex; align-items:center; gap:12px; background:rgba(255,255,255,0.1); padding:9px 22px; border-radius:40px; font-size:0.92rem; font-weight:600; }
        .logout-btn { background:transparent; border:2px solid rgba(255,255,255,0.8); color:white; border-radius:30px; padding:6px 18px; font-weight:700; transition:all 0.3s; font-size:0.88rem; cursor:pointer; }
        .logout-btn:hover { background:var(--secondary-yellow); color:var(--primary-blue); border-color:var(--secondary-yellow); }

        /* ── HERO BANNER ── */
        .hero-banner { background: var(--primary-gradient); color: white; padding: 54px 0 46px; position: relative; overflow: hidden; }
        .hero-banner::before { content:''; position:absolute; top:-80px; right:-80px; width:320px; height:320px; border-radius:50%; background:rgba(253,185,19,0.10); }
        .hero-banner::after  { content:''; position:absolute; bottom:-60px; left:-40px; width:220px; height:220px; border-radius:50%; background:rgba(255,255,255,0.05); }
        .hero-inner { position:relative; z-index:2; }
        .hero-badge { display:inline-block; background:rgba(253,185,19,0.18); color:var(--secondary-yellow); border:1px solid rgba(253,185,19,0.35); border-radius:30px; padding:5px 18px; font-size:0.75rem; font-weight:800; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:16px; }
        .hero-banner h1 { font-size:2.4rem; font-weight:900; margin-bottom:8px; line-height:1.15; }
        .hero-divider { width:50px; height:4px; background:var(--secondary-yellow); border-radius:2px; margin:14px 0; }
        .hero-banner p { opacity:0.84; font-size:0.97rem; margin:0; max-width:600px; line-height:1.7; }

        /* ── PROGRAM CARDS ── */
        .program-card {
            background: var(--bg-white); border-radius: 18px;
            padding: 24px 22px 20px; border: 1px solid var(--border-light);
            transition: all 0.3s ease; height: 100%;
            position: relative; overflow: hidden; display:flex; flex-direction:column;
        }
        .program-card::before { content:''; position:absolute; top:0; left:0; right:0; height:4px; background:var(--primary-gradient); }
        .program-card.pc-yellow::before { background: linear-gradient(135deg,#FDB913,#E5A500); }
        .program-card.pc-green::before  { background: linear-gradient(135deg,#28a745,#1e7e34); }
        .program-card.pc-purple::before { background: linear-gradient(135deg,#6f42c1,#4a1f9e); }
        .program-card.pc-red::before    { background: linear-gradient(135deg,#C41E24,#8B0000); }
        .program-card.pc-teal::before   { background: linear-gradient(135deg,#17a2b8,#0d7b8a); }
        .program-card.pc-orange::before { background: linear-gradient(135deg,#fd7e14,#c9530a); }
        .program-card:hover:not(.locked) { transform: translateY(-5px); box-shadow: 0 14px 32px rgba(44,62,143,0.12); border-color: var(--primary-blue); }
        .program-card.locked { opacity:0.50; cursor:not-allowed; }
        .prog-num {
            font-size: 0.68rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--primary-blue); background: var(--bg-soft-blue);
            border-radius: 20px; padding: 3px 12px; display: inline-block; margin-bottom: 10px;
        }
        .prog-num.n-yellow { color:#856404; background:#FFF3D6; }
        .prog-num.n-green  { color:#155724; background:#d4edda; }
        .prog-num.n-purple { color:#6f42c1; background:#ede4ff; }
        .prog-num.n-red    { color:#C41E24; background:#FCE8E8; }
        .prog-num.n-teal   { color:#17a2b8; background:#d1f5f9; }
        .prog-num.n-orange { color:#fd7e14; background:#fff0e0; }
        .program-title { font-size: 1rem; font-weight: 800; color: var(--primary-blue); margin-bottom: 5px; }
        .program-desc  { font-size: 0.81rem; color: #64748b; line-height:1.55; margin-bottom: 16px; flex:1; }

        .apply-btn {
            background: var(--primary-gradient); color: white; border: none;
            border-radius: 10px; padding: 10px 20px; font-weight: 700; font-size: 0.84rem;
            width: 100%; transition: all 0.25s; cursor:pointer; letter-spacing:0.02em;
        }
        .apply-btn:hover:not(:disabled) { box-shadow: 0 6px 18px rgba(44,62,143,0.30); transform:translateY(-1px); }
        .apply-btn:disabled { background: #cbd5e1; color:#94a3b8; cursor: not-allowed; }

        /* ── ALERTS ── */
        .alert { border-radius: 12px; border: none; font-size: 0.9rem; }
        .alert-success-c { background: #d4edda; border-left: 5px solid #28a745; color: #155724; }
        .alert-warning-c { background: #FFF3D6; border-left: 5px solid var(--secondary-yellow); color: #856404; }

        /* ── SECTION HEADER ── */
        .section-header h4 { font-size:1.2rem; font-weight:800; color:var(--primary-blue); position:relative; padding-bottom:10px; margin-bottom:6px; }
        .section-header h4::after { content:''; position:absolute; bottom:0; left:0; width:38px; height:4px; background:var(--secondary-yellow); border-radius:2px; }
        .section-header p { font-size:0.85rem; color:#94a3b8; margin:10px 0 0; }

        /* ── FOOTER ── */
        .main-content { flex:1; }
        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,0.75); text-align: center; padding: 20px 0; font-size: 0.85rem; margin-top: 56px; }
        .footer-strip strong { color:white; }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/user/dashboard">
                <img src="/images/mswd-logo.png" alt="MSWD" style="width:36px;height:36px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/user/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/user/programs">Programs</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('user.my-requirements') }}">My Requirements</a></li>
                    <li class="nav-item"><a class="nav-link" href="/user/announcements">Announcements</a></li>
                    <li class="nav-item"><a class="nav-link" href="/analysis">Public Analysis</a></li>
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
            </div>
        </div>
    </nav>

    <!-- ===== HERO BANNER ===== -->
    <section class="hero-banner">
        <div class="container">
            <div class="hero-inner">
                <div class="hero-badge">Member Portal</div>
                <h1>Available Programs</h1>
                <div class="hero-divider"></div>
                <p>Choose a program below to view its requirements and submit your application for MSWDO assistance.</p>
            </div>
        </div>
    </section>

    <div class="main-content">
    <div class="container mt-4">

        @if(session('success'))
            <div class="alert alert-success-c alert-dismissible fade show mb-3">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @php
            $hasPendingOrApproved = \App\Models\Application::where('user_id', Auth::id())
                ->whereIn('status', ['pending', 'in_review'])->exists();
            $hasCompleted = \App\Models\Application::where('user_id', Auth::id())
                ->where('status', 'approved')->exists();
        @endphp

        @if($hasCompleted)
            <div class="alert alert-success-c alert-dismissible fade show mb-4">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong>Congratulations!</strong> Your application has been approved! Please proceed to the MSWDO Office to claim your benefits.
            </div>
        @elseif($hasPendingOrApproved)
            <div class="alert alert-warning-c alert-dismissible fade show mb-4">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong>Active Application.</strong> You cannot apply for another program until your current application is completed or rejected.
            </div>
        @endif

        @php
            $programs = [
                ['key' => '4Ps',                    'num' => '01', 'num_class' => '',          'card_class' => '',          'title' => 'Pantawid Pamilyang Pilipino',  'desc' => 'A government program providing conditional cash grants to the poorest families.'],
                ['key' => 'Senior_Citizen_Pension',  'num' => '02', 'num_class' => 'n-yellow',  'card_class' => 'pc-yellow', 'title' => 'Senior Citizen Pension',       'desc' => 'Monthly social pension assistance for indigent senior citizens aged 60 and above.'],
                ['key' => 'PWD_Assistance',          'num' => '03', 'num_class' => 'n-green',   'card_class' => 'pc-green',  'title' => 'PWD Assistance',               'desc' => 'Financial and social support services for persons with disability.'],
                ['key' => 'Solo_Parent',             'num' => '04', 'num_class' => 'n-purple',  'card_class' => 'pc-purple', 'title' => 'Solo Parent Support',          'desc' => 'Assistance and privileges for solo parents raising children independently.'],
                ['key' => 'AICS',                    'num' => '05', 'num_class' => 'n-red',     'card_class' => 'pc-red',    'title' => 'Assistance in Crisis (AICS)',  'desc' => 'Emergency financial aid for individuals and families in crisis situations.'],
                ['key' => 'SLP',                     'num' => '06', 'num_class' => 'n-teal',    'card_class' => 'pc-teal',   'title' => 'Sustainable Livelihood',       'desc' => 'Livelihood support to help poor families increase income and assets.'],
                ['key' => 'ESA',                     'num' => '07', 'num_class' => 'n-orange',  'card_class' => 'pc-orange', 'title' => 'Educational Assistance',       'desc' => 'Scholarship and educational support for qualified student beneficiaries.'],
            ];
        @endphp

        <div class="section-header mb-3">
            <h4>Choose a Program</h4>
            <p>Select a program to view its requirements and apply for assistance.</p>
        </div>

        <div class="row g-3 mb-4 justify-content-center">
            @foreach($programs as $p)
            <div class="col-md-4 col-sm-6">
                @if($hasPendingOrApproved)
                    <div class="program-card {{ $p['card_class'] }} locked">
                        <span class="prog-num {{ $p['num_class'] }}">{{ $p['num'] }}</span>
                        <div class="program-title">{{ $p['title'] }}</div>
                        <div class="program-desc">{{ $p['desc'] }}</div>
                        <button class="apply-btn" disabled>Currently Unavailable</button>
                    </div>
                @else
                    <a href="{{ route('user.apply', $p['key']) }}" style="display:block;height:100%;">
                        <div class="program-card {{ $p['card_class'] }}">
                            <span class="prog-num {{ $p['num_class'] }}">{{ $p['num'] }}</span>
                            <div class="program-title">{{ $p['title'] }}</div>
                            <div class="program-desc">{{ $p['desc'] }}</div>
                            <button class="apply-btn">Apply Now &rarr;</button>
                        </div>
                    </a>
                @endif
            </div>
            @endforeach
        </div>

    </div>
    </div>

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>