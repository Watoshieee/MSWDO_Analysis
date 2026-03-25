<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Data – {{ $municipality->name }} – MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root { --primary-blue:#2C3E8F; --secondary-yellow:#FDB913; --primary-gradient:linear-gradient(135deg,#2C3E8F 0%,#1A2A5C 100%); --bg-light:#F8FAFC; --bg-white:#FFFFFF; --bg-soft-blue:#F0F5FF; --border-light:#E2E8F0; --text-dark:#1E293B; }
        * { box-sizing:border-box; }
        body { background:var(--bg-light); font-family:'Inter',sans-serif; color:var(--text-dark); display:flex; flex-direction:column; min-height:100vh; margin:0; }
        a { text-decoration:none; }
        .navbar { background:var(--primary-gradient) !important; box-shadow:0 4px 24px rgba(44,62,143,0.18); padding:14px 0; }
        .navbar-brand { font-weight:800; font-size:1.55rem; color:white !important; display:flex; align-items:center; gap:12px; }
        .nav-link { color:rgba(255,255,255,0.88) !important; font-weight:600; transition:all 0.25s; border-radius:8px; padding:10px 18px !important; font-size:0.93rem; }
        .nav-link:hover { background:rgba(255,255,255,0.15); color:white !important; }
        .nav-link.active { background:var(--secondary-yellow); color:var(--primary-blue) !important; font-weight:700; }
        .user-info { color:white; display:flex; align-items:center; gap:12px; background:rgba(255,255,255,0.1); padding:9px 22px; border-radius:40px; font-size:0.9rem; font-weight:600; }
        .logout-btn { background:transparent; border:2px solid rgba(255,255,255,0.8); color:white; border-radius:30px; padding:6px 18px; font-weight:700; transition:all 0.3s; font-size:0.88rem; cursor:pointer; }
        .logout-btn:hover { background:var(--secondary-yellow); color:var(--primary-blue); border-color:var(--secondary-yellow); }
        .hero-banner { background:var(--primary-gradient); color:white; padding:36px 0 30px; position:relative; overflow:hidden; }
        .hero-banner::before { content:''; position:absolute; top:-60px; right:-60px; width:260px; height:260px; border-radius:50%; background:rgba(253,185,19,0.09); }
        .hero-inner { position:relative; z-index:2; }
        .hero-badge { display:inline-block; background:rgba(253,185,19,0.18); color:var(--secondary-yellow); border:1px solid rgba(253,185,19,0.35); border-radius:30px; padding:4px 16px; font-size:0.72rem; font-weight:800; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:8px; }
        .hero-banner h1 { font-size:1.75rem; font-weight:900; margin-bottom:4px; }
        .hero-divider { width:40px; height:4px; background:var(--secondary-yellow); border-radius:2px; margin:8px 0 6px; }
        .hero-banner p { opacity:0.82; font-size:0.9rem; margin:0; }
        .back-link { display:inline-flex; align-items:center; gap:8px; color:rgba(255,255,255,0.75); font-size:0.82rem; font-weight:600; border:1px solid rgba(255,255,255,0.25); border-radius:20px; padding:5px 14px; transition:all 0.25s; margin-bottom:12px; }
        .back-link:hover { color:white; background:rgba(255,255,255,0.15); }
        .panel-card { background:var(--bg-white); border-radius:20px; border:1px solid var(--border-light); box-shadow:0 4px 15px rgba(0,0,0,0.04); overflow:hidden; }
        .panel-header { background:var(--primary-gradient); color:white; padding:18px 24px; display:flex; align-items:center; justify-content:space-between; }
        .panel-header-title { font-size:1rem; font-weight:800; }
        .panel-header-sub { font-size:0.75rem; opacity:0.75; margin-top:2px; }
        .panel-header-badge { font-size:0.7rem; background:rgba(255,255,255,0.15); border-radius:20px; padding:4px 14px; font-weight:700; }
        .bgy-table { width:100%; }
        .bgy-table th { font-size:0.71rem; font-weight:800; text-transform:uppercase; letter-spacing:0.07em; color:#64748b; padding:12px 16px; background:var(--bg-soft-blue); border-bottom:2px solid var(--border-light); }
        .bgy-table td { padding:13px 16px; font-size:0.87rem; border-bottom:1px solid #F1F5F9; vertical-align:middle; }
        .bgy-table tr:last-child td { border-bottom:none; }
        .bgy-table tr:hover td { background:#FAFBFF; }
        .btn-edit-styled { background:var(--secondary-yellow); color:var(--primary-blue); border:none; border-radius:20px; padding:5px 16px; font-size:0.78rem; font-weight:800; cursor:pointer; transition:all 0.2s; }
        .btn-edit-styled:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(253,185,19,0.35); }
        .modal-content { border:none; border-radius:16px; overflow:hidden; }
        .modal-hdr { background:var(--primary-gradient); color:white; padding:18px 22px; border-radius:0; }
        .f-label { font-size:0.76rem; font-weight:700; color:var(--primary-blue); text-transform:uppercase; letter-spacing:0.04em; margin-bottom:4px; display:block; }
        .f-input { border:1.5px solid var(--border-light); border-radius:10px; padding:9px 13px; font-size:0.9rem; font-family:'Inter',sans-serif; transition:all 0.25s; width:100%; }
        .f-input:focus { border-color:var(--primary-blue); box-shadow:0 0 0 3px rgba(44,62,143,0.08); outline:none; }
        .btn-submit { background:var(--primary-gradient); color:white; border:none; border-radius:10px; padding:11px; font-weight:800; font-size:0.93rem; cursor:pointer; transition:all 0.3s; width:100%; }
        .btn-submit:hover { box-shadow:0 8px 24px rgba(44,62,143,0.28); transform:translateY(-1px); }
        .btn-cncl { background:var(--bg-light); border:1.5px solid var(--border-light); color:#64748b; border-radius:10px; padding:10px; font-weight:700; font-size:0.88rem; cursor:pointer; width:100%; transition:all 0.2s; }
        .btn-cncl:hover { border-color:#94a3b8; }
        .alert-s { border-radius:12px; font-size:0.88rem; padding:12px 16px; margin-bottom:16px; background:#d4edda; border-left:4px solid #28a745; color:#155724; }
        .main-content { flex:1; }
        .footer-strip { background:var(--primary-gradient); color:rgba(255,255,255,0.75); text-align:center; padding:20px 0; font-size:0.85rem; margin-top:48px; }
        .footer-strip strong { color:white; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/admin/dashboard"><img src="/images/mswd-logo.png" alt="MSWD" style="width:36px;height:36px;object-fit:contain;"> MSWDO</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.requirements') }}">Applications</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.data.dashboard') }}">Data Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.detailed-analysis') }}">Analysis</a></li>
                    <li class="nav-item"><a class="nav-link" href="/analysis">Public View</a></li>
                </ul>
                <div class="d-flex">
                    @auth
                    <div class="user-info">
                        <span>{{ Auth::user()->full_name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">@csrf<button type="submit" class="logout-btn">Logout</button></form>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <section class="hero-banner">
        <div class="container"><div class="hero-inner">
            <a href="{{ route('admin.data.dashboard') }}" class="back-link">&#8592; Data Management</a>
            <div class="hero-badge">Barangays</div>
            <h1>Barangay Data Management</h1>
            <div class="hero-divider"></div>
            <p>Edit population, household, and demographic records for {{ $municipality->name }}.</p>
        </div></div>
    </section>

    <div class="main-content">
    <div class="container mt-4">
        @if(session('success'))<div class="alert-s">{{ session('success') }}</div>@endif

        <div class="panel-card mb-4">
            <div class="panel-header">
                <div><div class="panel-header-title">Barangay Records</div><div class="panel-header-sub">Click Edit to update a barangay's data</div></div>
                <span class="panel-header-badge">{{ $barangays->total() }} barangays</span>
            </div>
            <div style="overflow-x:auto;">
                <table class="bgy-table">
                    <thead><tr><th>Barangay</th><th>Population</th><th>Households</th><th>Single Parents</th><th>Year</th><th>Action</th></tr></thead>
                    <tbody>
                        @foreach($barangays as $barangay)
                        <tr>
                            <td><strong>{{ $barangay->name }}</strong></td>
                            <td>{{ number_format($barangay->male_population + $barangay->female_population) }}</td>
                            <td>{{ number_format($barangay->total_households) }}</td>
                            <td>{{ $barangay->single_parent_count }}</td>
                            <td>{{ $barangay->year ?? 'N/A' }}</td>
                            <td><button class="btn-edit-styled" data-bs-toggle="modal" data-bs-target="#editModal{{ $barangay->id }}">Edit</button></td>
                        </tr>
                        <div class="modal fade" id="editModal{{ $barangay->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg"><div class="modal-content">
                                <div class="modal-hdr d-flex align-items-center justify-content-between">
                                    <span style="font-weight:800;">Edit: {{ $barangay->name }}</span>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('admin.data.barangays.update', $barangay->id) }}">@csrf
                                    <div class="modal-body p-4">
                                        <div class="row g-3">
                                            <div class="col-md-6"><label class="f-label">Male Population</label><input type="number" name="male_population" class="f-input" value="{{ $barangay->male_population }}" required min="0"></div>
                                            <div class="col-md-6"><label class="f-label">Female Population</label><input type="number" name="female_population" class="f-input" value="{{ $barangay->female_population }}" required min="0"></div>
                                            <div class="col-md-4"><label class="f-label">Age 0–19</label><input type="number" name="population_0_19" class="f-input" value="{{ $barangay->population_0_19 }}" required min="0"></div>
                                            <div class="col-md-4"><label class="f-label">Age 20–59</label><input type="number" name="population_20_59" class="f-input" value="{{ $barangay->population_20_59 }}" required min="0"></div>
                                            <div class="col-md-4"><label class="f-label">Age 60–100</label><input type="number" name="population_60_100" class="f-input" value="{{ $barangay->population_60_100 }}" required min="0"></div>
                                            <div class="col-md-6"><label class="f-label">Total Households</label><input type="number" name="total_households" class="f-input" value="{{ $barangay->total_households }}" required min="0"></div>
                                            <div class="col-md-6"><label class="f-label">Single Parents</label><input type="number" name="single_parent_count" class="f-input" value="{{ $barangay->single_parent_count }}" required min="0"></div>
                                            <div class="col-md-6"><label class="f-label">Year</label>
                                                <select name="year" class="f-input" required>
                                                    @foreach(range(date('Y') - 2, date('Y') + 1) as $yearOption)
                                                        <option value="{{ $yearOption }}" {{ $barangay->year == $yearOption ? 'selected' : '' }}>{{ $yearOption }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 px-4 pb-4 gap-2">
                                        <button type="button" class="btn-cncl" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn-submit">Save Changes</button>
                                    </div>
                                </form>
                            </div></div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 d-flex justify-content-center">{{ $barangays->links() }}</div>
        </div>
    </div>
    </div>

    <div class="footer-strip"><strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>