<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications – MSWDO Admin</title>
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
            --secondary-yellow-light: #FFF3D6;
            --accent-red-light: #FCE8E8;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--bg-light); font-family: 'Inter', sans-serif; color: var(--text-dark); min-height: 100vh; display: flex; flex-direction: column; }
        a { text-decoration: none; }

        /* ===== NAVBAR ===== */
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,0.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.55rem; color: white !important; display: flex; align-items: center; gap: 12px; }
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; transition: all 0.25s; border-radius: 8px; padding: 10px 18px !important; font-size: 0.95rem; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .user-info { color: white; display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.1); padding: 9px 22px; border-radius: 40px; font-size: 0.92rem; font-weight: 500; }
        .logout-btn { background: transparent; border: 2px solid rgba(255,255,255,0.8); color: white; border-radius: 30px; padding: 6px 18px; font-weight: 700; transition: all 0.3s; font-size: 0.88rem; cursor: pointer; }
        .logout-btn:hover { background: var(--secondary-yellow); color: var(--primary-blue); border-color: var(--secondary-yellow); }

        /* ===== PAGE HERO ===== */
        .page-hero { background: var(--primary-gradient); border-radius: 20px; padding: 32px 36px; margin-bottom: 28px; color: white; position: relative; overflow: hidden; }
        .page-hero::before { content: ''; position: absolute; top: -60px; right: -60px; width: 220px; height: 220px; border-radius: 50%; background: rgba(253,185,19,0.09); pointer-events: none; }
        .page-hero::after  { content: ''; position: absolute; bottom: -40px; left: -40px; width: 160px; height: 160px; border-radius: 50%; background: rgba(255,255,255,0.04); pointer-events: none; }
        .page-hero h1 { font-size: 1.75rem; font-weight: 800; margin-bottom: 4px; position: relative; z-index: 1; }
        .page-hero p  { opacity: 0.82; margin: 0; font-size: 0.92rem; position: relative; z-index: 1; }
        .muni-badge { background: rgba(253,185,19,0.18); border: 1px solid rgba(253,185,19,0.35); color: #FDB913; border-radius: 30px; padding: 7px 20px; font-size: 0.85rem; font-weight: 700; position: relative; z-index: 1; display: inline-block; }

        /* ===== PANEL CARD ===== */
        .panel-card { background: var(--bg-white); border-radius: 18px; border: 1px solid var(--border-light); box-shadow: 0 4px 15px rgba(0,0,0,0.03); overflow: hidden; }
        .panel-header { background: var(--primary-gradient); color: white; padding: 18px 26px; font-size: 1rem; font-weight: 700; display: flex; justify-content: space-between; align-items: center; }
        .panel-body { padding: 26px; }

        /* ===== TABLE ===== */
        .table thead th { background: var(--bg-soft-blue); color: var(--primary-blue); font-weight: 700; font-size: 0.775rem; border: none; padding: 13px 15px; text-transform: uppercase; letter-spacing: 0.05em; }
        .table tbody td { padding: 14px 15px; font-size: 0.875rem; vertical-align: middle; border-color: #F1F5F9; color: var(--text-dark); }
        .table tbody tr:hover { background: #F8FAFC; }

        /* ===== BADGES / PILLS ===== */
        .prog-tag  { background: var(--bg-soft-blue); color: var(--primary-blue); padding: 4px 12px; border-radius: 20px; font-size: 0.76rem; font-weight: 700; white-space: nowrap; }
        .files-tag { background: #F1F5F9; color: #475569; padding: 4px 12px; border-radius: 20px; font-size: 0.76rem; font-weight: 600; }
        .status-pill { padding: 4px 14px; border-radius: 20px; font-size: 0.76rem; font-weight: 700; display: inline-block; }
        .status-pending   { background: var(--secondary-yellow-light); color: #856404; }
        .status-in_review { background: #E0F0FF; color: #0056b3; }
        .status-approved  { background: #d4edda; color: #155724; }
        .status-rejected  { background: var(--accent-red-light); color: #C41E24; }

        /* ===== ACTION BUTTON ===== */
        .btn-view { background: var(--primary-gradient); color: white; border: none; border-radius: 8px; padding: 7px 16px; font-weight: 700; font-size: 0.82rem; transition: all 0.25s; cursor: pointer; }
        .btn-view:hover { box-shadow: 0 4px 12px rgba(44,62,143,0.32); color: white; opacity: 0.92; }

        /* ===== EMPTY STATE ===== */
        .empty-state { text-align: center; padding: 56px 24px; }
        .empty-num  { font-size: 5rem; font-weight: 800; color: #E2E8F0; line-height: 1; }
        .empty-state p { color: #94a3b8; margin-top: 10px; font-size: 0.95rem; }

        /* ===== ALERTS ===== */
        .alert { border-radius: 12px; border: none; font-size: 0.9rem; }
        .alert-success { background: #d4edda; color: #155724; }
        .alert-danger   { background: var(--accent-red-light); color: #C41E24; }

        /* ===== MAIN CONTENT GROW ===== */
        .main-content { flex: 1; padding-bottom: 0; }

        /* ===== FOOTER ===== */
        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,0.9); text-align: center; padding: 20px; font-size: 0.85rem; font-weight: 500; letter-spacing: 0.02em; margin-top: 40px; }
    </style>
</head>
<body>

    <!-- ===== NAVBAR ===== -->
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
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.requirements') }}">Applications</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.data.dashboard') }}">Data Management</a></li>
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

    <!-- ===== MAIN CONTENT ===== -->
    <div class="main-content">
        <div class="container mt-4">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3">
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

            <!-- Hero -->
            <div class="page-hero mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1>Applications Management</h1>
                        <p>Review, approve, or reject submitted program applications.</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <span class="muni-badge">{{ $municipality }}</span>
                    </div>
                </div>
            </div>

            <!-- Applications Table -->
            <div class="panel-card">
                <div class="panel-header">
                    <span>Submitted Applications</span>
                    @if(isset($fileMonitorings))
                        <span style="font-size:0.8rem;font-weight:600;background:rgba(255,255,255,0.15);padding:4px 12px;border-radius:20px;">
                            {{ $fileMonitorings->total() }} Total
                        </span>
                    @endif
                </div>
                <div class="panel-body">
                    @if(isset($fileMonitorings) && $fileMonitorings->count() > 0)
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Applicant</th>
                                        <th>Program</th>
                                        <th>Date Submitted</th>
                                        <th>Status</th>
                                        <th>Files</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fileMonitorings as $fm)
                                    <tr>
                                        <td style="color:#94a3b8;font-weight:600;font-size:0.8rem;">{{ $fm->id }}</td>
                                        <td>
                                            <strong>{{ $fm->application->full_name ?? 'N/A' }}</strong>
                                            <div style="font-size:0.77rem;color:#94a3b8;margin-top:1px;">{{ $fm->application->barangay ?? '—' }}</div>
                                        </td>
                                        <td>
                                            <span class="prog-tag">{{ str_replace('_', ' ', $fm->application->program_type ?? 'N/A') }}</span>
                                        </td>
                                        <td style="font-size:0.84rem;color:#64748b;">
                                            {{ $fm->created_at ? $fm->created_at->format('M d, Y') : 'N/A' }}
                                        </td>
                                        <td>
                                            @php
                                                $s   = $fm->overall_status ?? 'pending';
                                                $cls = $s === 'approved' ? 'approved' : ($s === 'rejected' ? 'rejected' : ($s === 'in_review' ? 'in_review' : 'pending'));
                                            @endphp
                                            <span class="status-pill status-{{ $cls }}">
                                                {{ ucfirst(str_replace('_', ' ', $s)) }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $total    = $fm->fileUploads->count();
                                                $uploaded = $fm->fileUploads->where('file_path', '!=', null)->count();
                                            @endphp
                                            <span class="files-tag">{{ $uploaded }}/{{ $total }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.view-requirement', $fm->id) }}" class="btn-view">View &rarr;</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">{{ $fileMonitorings->links() }}</div>
                    @else
                        <div class="empty-state">
                            <div class="empty-num">00</div>
                            <p>No applications submitted yet for <strong>{{ $municipality }}</strong>.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ===== FOOTER ===== -->
    <div class="footer-strip">
        MSWDO &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>