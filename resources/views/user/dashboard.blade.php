<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard – MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --primary-blue-light: #E5EEFF;
            --primary-blue-soft: #5D7BB9;
            --secondary-yellow: #FDB913;
            --secondary-yellow-light: #FFF3D6;
            --accent-green: #28a745;
            --accent-red: #C41E24;
            --accent-red-light: #FCE8E8;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --secondary-gradient: linear-gradient(135deg, #FDB913 0%, #E5A500 100%);
            --bg-light: #F8FAFC;
            --bg-soft-blue: #F0F5FF;
            --border-light: #E2E8F0;
            --text-dark: #1E293B;
            --text-mid: #475569;
            --text-light: #94a3b8;
        }
        * { box-sizing: border-box; }
        body { background: var(--bg-light); font-family: 'Inter', sans-serif; color: var(--text-dark); margin: 0; }
        a { text-decoration: none; color: inherit; }

        /* ── NAVBAR ── */
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,0.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.55rem; color: white !important; display: flex; align-items: center; gap: 10px; }
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; transition: all 0.25s; border-radius: 8px; padding: 10px 18px !important; font-size: 0.95rem; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .user-info { color: white; display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.1); padding: 9px 22px; border-radius: 40px; font-size: 0.92rem; font-weight: 600; }
        .logout-btn { background: transparent; border: 2px solid rgba(255,255,255,0.8); color: white; border-radius: 30px; padding: 6px 18px; font-weight: 700; transition: all 0.3s; font-size: 0.88rem; cursor: pointer; }
        .logout-btn:hover { background: var(--secondary-yellow); color: var(--primary-blue); border-color: var(--secondary-yellow); }

        /* ── HERO BANNER ── */
        .hero-banner {
            background: var(--primary-gradient);
            color: white; padding: 56px 0 50px;
            position: relative; overflow: hidden;
        }
        .hero-banner::before { content: ''; position: absolute; top: -90px; right: -90px; width: 360px; height: 360px; border-radius: 50%; background: rgba(253,185,19,0.10); }
        .hero-banner::after  { content: ''; position: absolute; bottom: -60px; left: -50px; width: 240px; height: 240px; border-radius: 50%; background: rgba(255,255,255,0.04); }
        .hero-inner { position: relative; z-index: 2; }
        .hero-badge {
            display: inline-block; background: rgba(253,185,19,0.18);
            color: var(--secondary-yellow); border: 1px solid rgba(253,185,19,0.35);
            border-radius: 30px; padding: 5px 18px; font-size: 0.75rem; font-weight: 800;
            letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 18px;
        }
        .hero-banner h1 { font-size: 2.5rem; font-weight: 900; margin-bottom: 6px; line-height: 1.15; }
        .hero-banner h1 em { color: var(--secondary-yellow); font-style: normal; }
        .hero-divider { width: 50px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; margin: 16px 0; }
        .hero-banner p { opacity: 0.84; font-size: 0.97rem; margin: 0; max-width: 580px; line-height: 1.7; }
        .hero-pills { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 24px; }
        .hero-pill {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.22);
            border-radius: 30px; padding: 9px 20px; font-size: 0.87rem; font-weight: 600; color: white;
        }
        .hero-pill .pill-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--secondary-yellow); flex-shrink: 0; }

        /* ── STAT CARDS - NO HOVER ANIMATION ── */
.stat-card {
    background: white; 
    border-radius: 18px;
    border: 1px solid var(--border-light);
    box-shadow: 0 4px 16px rgba(0,0,0,0.04);
    height: 100%; 
    position: relative; 
    overflow: hidden;
}
.stat-card::before { 
    content: ''; 
    position: absolute; 
    top: 0; 
    left: 0; 
    right: 0; 
    height: 5px; 
    background: var(--primary-gradient); 
}
.stat-card.s-yellow::before { 
    background: var(--secondary-gradient); 
}
.stat-card.s-green::before { 
    background: linear-gradient(135deg,#28a745,#1e7e34); 
}
.stat-card.s-red::before { 
    background: linear-gradient(135deg,#C41E24,#8B0000); 
}
.stat-card .card-body { 
    padding: 26px 28px; 
}
.stat-label { 
    font-size: 0.72rem; 
    font-weight: 800; 
    color: var(--text-light); 
    text-transform: uppercase; 
    letter-spacing: 0.09em; 
    margin-bottom: 8px; 
}
.stat-value { 
    font-size: 3rem; 
    font-weight: 900; 
    color: var(--primary-blue); 
    line-height: 1; 
    margin-bottom: 4px; 
}
.stat-value.v-yellow { 
    color: #856404; 
}
.stat-value.v-green { 
    color: #155724; 
}
.stat-value.v-red { 
    color: var(--accent-red); 
}
.stat-desc { 
    font-size: 0.78rem; 
    color: var(--text-light); 
    font-weight: 500; 
}

        /* ── SECTION HEADER ── */
        .section-header { margin-bottom: 22px; }
        .section-header h4 { font-size: 1.2rem; font-weight: 800; color: var(--primary-blue); position: relative; padding-bottom: 10px; margin: 0; }
        .section-header h4::after { content: ''; position: absolute; bottom: 0; left: 0; width: 38px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; }
        .section-header p { font-size: 0.85rem; color: var(--text-light); margin: 10px 0 0; }

        /* ── PROGRAM CARDS ── */
        .program-card {
            background: white; border-radius: 18px;
            padding: 24px 22px 20px; border: 1px solid var(--border-light);
            transition: all 0.3s ease; height: 100%;
            position: relative; overflow: hidden;
            display: flex; flex-direction: column;
        }
        .program-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px; background: var(--primary-gradient); }
        .program-card.pc-yellow::before { background: var(--secondary-gradient); }
        .program-card.pc-green::before  { background: linear-gradient(135deg,#28a745,#1e7e34); }
        .program-card.pc-purple::before { background: linear-gradient(135deg,#6f42c1,#4a1f9e); }
        .program-card.pc-red::before    { background: linear-gradient(135deg,#C41E24,#8B0000); }
        .program-card.pc-teal::before   { background: linear-gradient(135deg,#17a2b8,#0d7b8a); }
        .program-card.pc-orange::before { background: linear-gradient(135deg,#fd7e14,#c9530a); }
        .program-card:hover:not(.locked) { transform: translateY(-5px); box-shadow: 0 14px 30px rgba(44,62,143,0.12); border-color: var(--primary-blue-soft); }
        .program-card.locked { opacity: 0.48; cursor: not-allowed; }
        .prog-num {
            font-size: 0.68rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--primary-blue); background: var(--bg-soft-blue);
            border-radius: 20px; padding: 3px 12px; display: inline-block; margin-bottom: 12px;
        }
        .prog-num.n-yellow { color: #856404; background: var(--secondary-yellow-light); }
        .prog-num.n-green  { color: #155724; background: #d4edda; }
        .prog-num.n-purple { color: #6f42c1; background: #ede4ff; }
        .prog-num.n-red    { color: var(--accent-red); background: var(--accent-red-light); }
        .prog-num.n-teal   { color: #17a2b8; background: #d1f5f9; }
        .prog-num.n-orange { color: #fd7e14; background: #fff0e0; }
        .prog-title { font-size: 1rem; font-weight: 800; color: var(--primary-blue); margin-bottom: 6px; }
        .prog-sub   { font-size: 0.79rem; color: var(--text-mid); line-height: 1.55; margin-bottom: 16px; flex: 1; }
        .apply-btn {
            background: var(--primary-gradient); color: white; border: none;
            border-radius: 10px; padding: 10px 16px; font-weight: 700; font-size: 0.83rem;
            width: 100%; transition: all 0.25s; cursor: pointer; letter-spacing: 0.02em;
        }
        .apply-btn:hover:not(:disabled) { box-shadow: 0 6px 20px rgba(44,62,143,0.32); color: white; transform: translateY(-1px); }
        .apply-btn:disabled { background: #cbd5e1; color: #94a3b8; cursor: not-allowed; }

        /* ── PANEL CARDS ── */
        .panel-card { background: white; border-radius: 18px; border: 1px solid var(--border-light); box-shadow: 0 4px 16px rgba(0,0,0,0.04); overflow: hidden; height: 100%; }
        .panel-header { background: var(--primary-gradient); color: white; padding: 18px 26px; font-size: 0.95rem; font-weight: 800; letter-spacing: 0.02em; display: flex; align-items: center; justify-content: space-between; }
        .panel-header .ph-badge { background: rgba(253,185,19,0.25); color: var(--secondary-yellow); border-radius: 20px; padding: 3px 12px; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; }
        .panel-body { padding: 22px 26px; }
        .table thead th { background: var(--bg-soft-blue); color: var(--primary-blue); font-weight: 800; font-size: 0.72rem; border: none; padding: 10px 14px; text-transform: uppercase; letter-spacing: 0.06em; }
        .table tbody td { padding: 13px 14px; font-size: 0.87rem; vertical-align: middle; border-color: #F1F5F9; }
        .table tbody tr:hover { background: #FAFBFF; }

        /* Status badges */
        .status-badge { padding: 5px 14px; border-radius: 20px; font-size: 0.72rem; font-weight: 800; display: inline-block; letter-spacing: 0.03em; }
        .status-pending   { background: var(--secondary-yellow-light); color: #856404; }
        .status-in_review { background: #E0F0FF; color: #0056b3; }
        .status-approved  { background: #d4edda; color: #155724; }
        .status-rejected  { background: var(--accent-red-light); color: #721c24; }

        /* Announcements */
        .ann-item { padding: 14px 18px; border-radius: 12px; background: var(--bg-soft-blue); border-left: 4px solid var(--secondary-yellow); margin-bottom: 12px; transition: background 0.2s; }
        .ann-item:hover { background: #E5EEFF; }
        .ann-t { font-weight: 800; color: var(--primary-blue); font-size: 0.88rem; margin-bottom: 4px; }
        .ann-d { font-size: 0.72rem; color: var(--text-light); margin-bottom: 5px; font-weight: 600; letter-spacing: 0.03em; text-transform: uppercase; }
        .ann-p { font-size: 0.82rem; color: var(--text-mid); margin: 0; line-height: 1.5; }

        /* Quick links */
        .quick-link {
            display: flex; align-items: center; gap: 16px;
            padding: 14px 18px; border-radius: 14px; border: 1px solid var(--border-light);
            background: white; transition: all 0.25s; margin-bottom: 10px; color: var(--text-dark);
        }
        .quick-link:hover { background: var(--bg-soft-blue); border-color: var(--primary-blue-soft); padding-left: 22px; }
        .ql-num { width: 36px; height: 36px; border-radius: 10px; background: var(--bg-soft-blue); color: var(--primary-blue); font-weight: 900; font-size: 0.8rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; letter-spacing: 0.05em; }
        .ql-text strong { display: block; font-size: 0.9rem; font-weight: 700; color: var(--primary-blue); }
        .ql-text span   { font-size: 0.76rem; color: var(--text-light); }
        .ql-arrow { margin-left: auto; color: var(--text-light); font-size: 1.2rem; font-weight: 300; }

        /* Alerts */
        .dash-alert { border-radius: 14px; border: none; padding: 16px 20px; font-size: 0.9rem; margin-bottom: 16px; border-left: 5px solid transparent; display: flex; align-items: flex-start; gap: 14px; }
        .dash-alert-label { font-weight: 800; font-size: 0.68rem; letter-spacing: 0.1em; text-transform: uppercase; display: block; margin-bottom: 3px; }
        .alert-success-c { background: #d4edda; border-left-color: var(--accent-green); color: #155724; }
        .alert-warning-c { background: var(--secondary-yellow-light); border-left-color: var(--secondary-yellow); color: #856404; }
        .alert-danger-c  { background: var(--accent-red-light); border-left-color: var(--accent-red); color: #721c24; }
        .alert-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 1rem; flex-shrink: 0; }

        /* Empty state */
        .empty-state { text-align: center; padding: 36px 0; }
        .empty-num { font-size: 4rem; font-weight: 900; color: var(--border-light); line-height: 1; display: block; margin-bottom: 10px; }
        .empty-state p { color: var(--text-light); margin-bottom: 18px; font-size: 0.88rem; }
        .btn-browse { background: var(--primary-gradient); color: white; border: none; border-radius: 10px; padding: 10px 24px; font-weight: 700; font-size: 0.88rem; display: inline-block; cursor: pointer; transition: all 0.25s; }
        .btn-browse:hover { color: white; box-shadow: 0 6px 20px rgba(44,62,143,0.3); transform: translateY(-1px); }

        /* Footer */
        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,0.7); text-align: center; padding: 20px 0; font-size: 0.84rem; margin-top: 60px; }
        .footer-strip strong { color: white; }

        /* Session alerts */
        .session-alert { border-radius: 14px; border: none; font-size: 0.9rem; }
    </style>
</head>
<body>

    <!-- ===== NAVBAR ===== -->
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
                    <li class="nav-item"><a class="nav-link active" href="/user/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/user/programs">Programs</a></li>
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
                <h1>Welcome back, <em>{{ Auth::user()->full_name }}</em>!</h1>
                <div class="hero-divider"></div>
                <p>Apply for MSWDO social welfare programs, track your application status, and stay updated with the latest announcements from our office.</p>
                <div class="hero-pills">
                    <span class="hero-pill"><span class="pill-dot"></span>{{ date('F d, Y') }}</span>
                    @php $totalApplications = $totalApplications ?? 0; @endphp
                    <span class="hero-pill"><span class="pill-dot"></span>{{ $totalApplications }} {{ $totalApplications == 1 ? 'Application' : 'Applications' }} Submitted</span>
                </div>
            </div>
        </div>
    </section>

    <div class="container mt-4">

        {{-- Session Alerts --}}
        @if(session('success'))
            <div class="dash-alert alert-success-c alert-dismissible fade show mb-3">
                <div class="alert-icon" style="background:#c3e6cb;color:#155724;">✓</div>
                <div><span class="dash-alert-label">Success</span>{{ session('success') }}</div>
                <button class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="dash-alert alert-danger-c alert-dismissible fade show mb-3">
                <div class="alert-icon" style="background:#f5c6cb;color:#721c24;">!</div>
                <div><span class="dash-alert-label">Error</span>{{ session('error') }}</div>
                <button class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Stat Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <div class="card-body">
                        <div class="stat-label">Total Applications</div>
                        <div class="stat-value">{{ $totalApplications ?? 0 }}</div>
                        <div class="stat-desc">All time submissions</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card s-yellow">
                    <div class="card-body">
                        <div class="stat-label">Under Review</div>
                        <div class="stat-value v-yellow">{{ $pendingCount ?? 0 }}</div>
                        <div class="stat-desc">Awaiting decision</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card s-green">
                    <div class="card-body">
                        <div class="stat-label">Approved</div>
                        <div class="stat-value v-green">{{ $approvedCount ?? 0 }}</div>
                        <div class="stat-desc">Successfully completed</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card s-red">
                    <div class="card-body">
                        <div class="stat-label">Rejected</div>
                        <div class="stat-value v-red">{{ $rejectedCount ?? 0 }}</div>
                        <div class="stat-desc">Unsuccessful applications</div>
                    </div>
                </div>
            </div>
        </div>



        {{-- Programs --}}
        <div class="section-header mb-3">
            <h4>Available Programs</h4>
            <p>Choose a program to view requirements and submit your application.</p>
        </div>

        @php
            $programs = [
                ['key' => 'Senior_Citizen_Pension',  'num' => '01', 'num_class' => 'n-yellow',  'card_class' => 'pc-yellow', 'title' => 'Senior Citizen Pension',     'sub' => 'Monthly social pension for senior citizens 60 years and above.'],
                ['key' => 'PWD_Assistance',          'num' => '02', 'num_class' => 'n-green',   'card_class' => 'pc-green',  'title' => 'PWD Assistance',             'sub' => 'Support services for persons with disability.',             'url' => route('user.pwd-application')],
                ['key' => 'Solo_Parent',             'num' => '03', 'num_class' => 'n-purple',  'card_class' => 'pc-purple', 'title' => 'Solo Parent Support',        'sub' => 'Assistance for solo parents raising children alone.',       'url' => route('user.solo-parent-application')],
                ['key' => 'AICS',                    'num' => '04', 'num_class' => 'n-red',     'card_class' => 'pc-red',    'title' => 'Assistance in Crisis',       'sub' => 'Emergency financial aid for families in crisis situations.', 'url' => route('user.aics-category')],
            ];
        @endphp

        <div class="row g-3 mb-4 justify-content-center">
            @foreach($programs as $p)
            <div class="col-md-4 col-sm-6">
                <a href="{{ $p['url'] ?? route('user.apply', $p['key']) }}" style="display:block;height:100%;">
                    <div class="program-card {{ $p['card_class'] }}">
                        <span class="prog-num {{ $p['num_class'] }}">{{ $p['num'] }}</span>
                        <div class="prog-title">{{ $p['title'] }}</div>
                        <div class="prog-sub">{{ $p['sub'] }}</div>
                        <button class="apply-btn">Apply Now &rarr;</button>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        {{-- Bottom panels --}}
        <div class="row g-4 mb-2">

            {{-- Recent Applications --}}
            <div class="col-lg-7">
                <div class="panel-card">
                    <div class="panel-header">
                        Recent Applications
                        <span class="ph-badge">History</span>
                    </div>
                    <div class="panel-body">
                        @if(isset($recentApplications) && count($recentApplications) > 0)
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Program</th>
                                            <th>Barangay</th>
                                            <th>Date Applied</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentApplications as $app)
                                        <tr>
                                            <td><strong>{{ str_replace('_', ' ', $app->program_type) }}</strong></td>
                                            <td>{{ $app->barangay }}</td>
                                            <td>{{ optional($app->created_at ?? $app->application_date)->format('M d, Y') ?? 'N/A' }}</td>
                                            <td>
                                                <span class="status-badge status-{{ $app->status }}">
                                                    {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('user.my-requirements') }}" style="color:var(--primary-blue);font-weight:700;font-size:0.88rem;">
                                    View All Applications &rarr;
                                </a>
                            </div>
                        @else
                            <div class="empty-state">
                                <span class="empty-num">00</span>
                                <p>No applications submitted yet.</p>
                                <a href="{{ route('user.programs') }}" class="btn-browse">Browse Programs &rarr;</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right column --}}
            <div class="col-lg-5">
                <div class="row g-4">

                    {{-- Quick Links --}}
                    <div class="col-12">
                        <div class="panel-card">
                            <div class="panel-header">
                                Quick Actions
                                <span class="ph-badge">Shortcuts</span>
                            </div>
                            <div class="panel-body">
                                <a href="/user/programs" class="quick-link">
                                    <div class="ql-num">01</div>
                                    <div class="ql-text">
                                        <strong>Browse Programs</strong>
                                        <span>View all available MSWDO programs</span>
                                    </div>
                                    <span class="ql-arrow">&rsaquo;</span>
                                </a>
                                <a href="{{ route('user.my-requirements') }}" class="quick-link">
                                    <div class="ql-num" style="background:#d4edda;color:#155724;">02</div>
                                    <div class="ql-text">
                                        <strong>My Requirements</strong>
                                        <span>Upload and manage your documents</span>
                                    </div>
                                    <span class="ql-arrow">&rsaquo;</span>
                                </a>
                                <a href="{{ route('user.pwd-application') }}" class="quick-link" style="background:linear-gradient(135deg,#f0f5ff,#e8f0fe);border:1px solid rgba(44,62,143,.15);">
                                    <div class="ql-num" style="background:var(--primary-blue);color:white;">03</div>
                                    <div class="ql-text">
                                        <strong style="color:var(--primary-blue);">PWD Application (Apply)</strong>
                                        <span>Guide, forms &amp; verification tool</span>
                                    </div>
                                    <span class="ql-arrow" style="color:var(--primary-blue);">&rsaquo;</span>
                                </a>
                                <a href="{{ route('user.solo-parent-application') }}" class="quick-link" style="background:linear-gradient(135deg,#f0f5ff,#e8f0fe);border:1px solid rgba(44,62,143,.15);">
                                    <div class="ql-num" style="background:var(--primary-blue);color:white;">04</div>
                                    <div class="ql-text">
                                        <strong style="color:var(--primary-blue);">Solo Parent ID (Apply)</strong>
                                        <span>Step-by-step application guide</span>
                                    </div>
                                    <span class="ql-arrow" style="color:var(--primary-blue);">&rsaquo;</span>
                                </a>
                                <a href="/user/announcements" class="quick-link">
                                    <div class="ql-num" style="background:var(--secondary-yellow-light);color:#856404;">05</div>
                                    <div class="ql-text">
                                        <strong>Announcements</strong>
                                        <span>Stay updated with MSWDO news</span>
                                    </div>
                                    <span class="ql-arrow">&rsaquo;</span>
                                </a>
                                <a href="/analysis" class="quick-link">
                                    <div class="ql-num" style="background:#d1f5f9;color:#17a2b8;">05</div>
                                    <div class="ql-text">
                                        <strong>Public Analysis</strong>
                                        <span>View demographic &amp; program data</span>
                                    </div>
                                    <span class="ql-arrow">&rsaquo;</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Announcements --}}
                    @if(isset($announcements) && count($announcements) > 0)
                    <div class="col-12">
                        <div class="panel-card">
                            <div class="panel-header">
                                Latest Announcements
                                <span class="ph-badge">News</span>
                            </div>
                            <div class="panel-body">
                                @foreach($announcements->take(3) as $announcement)
                                <div class="ann-item">
                                    <div class="ann-t">{{ $announcement->title }}</div>
                                    <div class="ann-d">{{ $announcement->created_at->format('F d, Y') }}</div>
                                    <p class="ann-p">{{ Str::limit($announcement->content, 90) }}</p>
                                </div>
                                @endforeach
                                <div class="text-center mt-2">
                                    <a href="{{ route('user.announcements') }}" style="color:var(--primary-blue);font-weight:700;font-size:0.88rem;">
                                        View All &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

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