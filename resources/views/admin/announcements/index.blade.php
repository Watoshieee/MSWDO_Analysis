<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Announcements – MSWDO Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @include('components.admin-colors')
    <style>
        html, body { overscroll-behavior: none; margin: 0; padding: 0; }
        :root {
            --bg-light: #F8FAFC;
            --bg-soft-blue: #F0F5FF;
            --border-light: #E2E8F0;
            --text-dark: #1E293B;
            --text-mid: #475569;
            --text-light: #94a3b8;
        }
        * { box-sizing: border-box; }
        body { background: var(--bg-light); font-family: 'Inter', sans-serif; color: var(--text-dark); display: flex; flex-direction: column; min-height: 100vh; }
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
        .hero-banner { background: var(--primary-gradient); color: white; padding: 44px 0 38px; position: relative; overflow: hidden; }
        .hero-banner::before { content: ''; position: absolute; top: -80px; right: -80px; width: 340px; height: 340px; border-radius: 50%; background: rgba(253,185,19,0.09); }
        .hero-banner::after  { content: ''; position: absolute; bottom: -60px; left: -40px; width: 230px; height: 230px; border-radius: 50%; background: rgba(255,255,255,0.05); }
        .hero-inner { position: relative; z-index: 2; }
        .hero-badge { display: inline-block; background: rgba(253,185,19,0.18); color: var(--secondary-yellow); border: 1px solid rgba(253,185,19,0.35); border-radius: 30px; padding: 4px 16px; font-size: 0.72rem; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 10px; }
        .hero-banner h1 { font-size: 2rem; font-weight: 900; margin-bottom: 4px; }
        .hero-divider { width: 44px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; margin: 10px 0 8px; }
        .hero-banner p { opacity: 0.82; font-size: 0.85rem; margin: 0; }
        .muni-badge-lg { background: rgba(253,185,19,0.18); border: 1px solid rgba(253,185,19,0.35); color: var(--secondary-yellow); border-radius: 12px; padding: 14px 24px; text-align: center; }
        .muni-badge-lg .muni-name { font-size: 1.35rem; font-weight: 900; display: block; }
        .muni-badge-lg .muni-sub  { font-size: 0.72rem; opacity: 0.75; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; }

        /* ── MAIN ── */
        .main-content { flex: 1; }
        .section-heading { font-size: 1.05rem; font-weight: 800; color: var(--primary-blue); position: relative; padding-bottom: 10px; margin-bottom: 20px; }
        .section-heading::after { content: ''; position: absolute; bottom: 0; left: 0; width: 36px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; }

        /* ── FORM CARD ── */
        .form-card { background: white; border-radius: 18px; border: 1px solid var(--border-light); box-shadow: 0 4px 16px rgba(0,0,0,0.05); overflow: hidden; }
        .form-card-header { background: var(--primary-gradient); color: white; padding: 16px 22px; font-size: 0.85rem; font-weight: 700; }
        .form-card-body { padding: 24px; }
        .form-label { font-size: 0.8rem; font-weight: 700; color: var(--text-mid); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 6px; }
        .form-control, .form-select {
            border: 1.5px solid var(--border-light); border-radius: 10px;
            font-size: 0.88rem; padding: 10px 14px;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue); box-shadow: 0 0 0 3px rgba(44,62,143,0.1); outline: none;
        }
        textarea.form-control { resize: vertical; min-height: 110px; }
        .btn-post { background: var(--primary-gradient); color: white; border: none; border-radius: 10px; padding: 12px 0; font-weight: 800; font-size: 0.9rem; width: 100%; cursor: pointer; transition: all 0.25s; }
        .btn-post:hover { box-shadow: 0 6px 20px rgba(44,62,143,0.32); transform: translateY(-1px); }

        /* ── LIST CARD ── */
        .list-card { background: white; border-radius: 18px; border: 1px solid var(--border-light); box-shadow: 0 4px 16px rgba(0,0,0,0.05); overflow: hidden; }
        .list-card-header { background: var(--primary-gradient); color: white; padding: 16px 22px; display: flex; align-items: center; justify-content: space-between; }
        .list-card-header span { font-size: 0.85rem; font-weight: 700; }
        .list-card-badge { font-size: 0.7rem; background: rgba(255,255,255,0.15); border-radius: 20px; padding: 3px 12px; font-weight: 700; }

        /* ── ANN TABLE ── */
        .ann-table { width: 100%; border-collapse: collapse; }
        .ann-table th { font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.07em; color: #64748b; padding: 12px 16px; border-bottom: 2px solid var(--border-light); text-align: left; background: #f8fafc; }
        .ann-table td { padding: 14px 16px; font-size: 0.86rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .ann-table tr:last-child td { border-bottom: none; }
        .ann-table tr:hover td { background: #f8fafc; }

        .ann-title { font-weight: 700; color: var(--primary-blue); font-size: 0.9rem; }
        .ann-preview { font-size: 0.8rem; color: var(--text-light); margin-top: 2px; max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .ann-meta-text { font-size: 0.76rem; color: var(--text-light); margin-top: 3px; }

        /* ── TYPE BADGE ── */
        .type-badge { display: inline-block; font-size: 0.68rem; font-weight: 800; letter-spacing: 0.5px; padding: 3px 10px; border-radius: 20px; text-transform: uppercase; }
        .type-general        { background: #E5EEFF; color: var(--primary-blue); }
        .type-event          { background: #FFF3D6; color: #856404; }
        .type-emergency      { background: #FCE8E8; color: #7f1d1d; }
        .type-program_update { background: #d4edda; color: #155724; }

        /* ── STATUS ── */
        .badge-active   { background: #d4edda; color: #155724; font-size: 0.68rem; font-weight: 800; padding: 3px 10px; border-radius: 20px; }
        .badge-inactive { background: #f1f5f9; color: #94a3b8; font-size: 0.68rem; font-weight: 800; padding: 3px 10px; border-radius: 20px; }

        /* ── MUNI TAG ── */
        .muni-tag { background: #E5EEFF; color: var(--primary-blue); font-size: 0.7rem; font-weight: 700; padding: 2px 9px; border-radius: 20px; display: inline-block; }
        .muni-all { background: #FFF3D6; color: #856404; }

        /* ── ACTION BUTTONS ── */
        .btn-act { font-size: 0.76rem; font-weight: 700; padding: 5px 12px; border-radius: 8px; border: none; cursor: pointer; transition: opacity 0.15s; }
        .btn-act:hover { opacity: 0.8; }
        .btn-deact  { background: #FFF3D6; color: #856404; }
        .btn-activ  { background: #d4edda; color: #155724; }
        .btn-del    { background: #FCE8E8; color: #7f1d1d; }

        /* ── ALERTS ── */
        .alert-success-c { border-radius: 12px; font-size: 0.88rem; padding: 12px 18px; margin-bottom: 20px; background: #d4edda; border-left: 4px solid #28a745; color: #155724; }
        .alert-error-c   { border-radius: 12px; font-size: 0.88rem; padding: 12px 18px; margin-bottom: 20px; background: #FCE8E8; border-left: 4px solid #C41E24; color: #7f1d1d; }

        /* ── EMPTY ── */
        .empty-state { text-align: center; padding: 40px 0; color: var(--text-light); font-size: 0.9rem; }

        /* ── FOOTER ── */
        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,0.75); text-align: center; padding: 20px 0; font-size: 0.85rem; margin-top: 48px; }
        .footer-strip strong { color: white; }
    </style>
</head>
<body>

    {{-- ── NAVBAR ── --}}
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
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.requirements*') ? 'active' : '' }}" href="{{ route('admin.requirements') }}">Applications</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">Users Management</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.data.*') ? 'active' : '' }}" href="{{ route('admin.data.dashboard') }}">Data Management</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.announcements.index') }}">Announcements</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.detailed*') ? 'active' : '' }}" href="{{ route('admin.detailed-analysis') }}">Analysis</a></li>
                    <li class="nav-item"><a class="nav-link" href="/analysis/programs">Comparative Analysis</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    @auth
                    <button type="button" class="btn" onclick="openAdminNotifModal()"
                        style="background:rgba(255,255,255,0.1);color:white;border:none;border-radius:50%;width:40px;height:40px;font-size:1.1rem;display:flex;align-items:center;justify-content:center;padding:0;transition:all 0.3s;position:relative;"
                        title="Application Notifications">
                        <i class="bi bi-bell-fill"></i>
                        @if(isset($adminNotifCount) && $adminNotifCount > 0)
                        <span class="admin-bell-badge" style="position:absolute;top:-4px;right:-4px;background:#dc3545;color:white;border-radius:50%;width:20px;height:20px;font-size:0.7rem;font-weight:800;display:flex;align-items:center;justify-content:center;border:2px solid #2C3E8F;">{{ $adminNotifCount > 9 ? '9+' : $adminNotifCount }}</span>
                        @endif
                    </button>
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

    {{-- ── HERO BANNER ── --}}
    <section class="hero-banner">
        <div class="container">
            <div class="hero-inner">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="hero-badge">Admin Panel</div>
                        <h1>Announcements</h1>
                        <div class="hero-divider"></div>
                        <p>Post and manage announcements for residents in your municipality.</p>
                    </div>
                    <div class="col-md-4 d-none d-md-flex justify-content-end">
                        <div class="muni-badge-lg">
                            <span class="muni-name">{{ Auth::user()->municipality ?? 'All' }}</span>
                            <span class="muni-sub">Municipality</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── MAIN CONTENT ── --}}
    <div class="main-content">
    <div class="container mt-4 mb-5">

        @include('components.admin-notification')
        @if($errors->any())
            <div class="alert-error-c">
                @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
            </div>
        @endif

        <div class="row g-4">

            {{-- ── CREATE FORM ── --}}
            <div class="col-lg-4">
                <div class="form-card">
                    <div class="form-card-header">Post New Announcement</div>
                    <div class="form-card-body">
                        <form method="POST" action="{{ route('admin.announcements.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Title <span style="font-weight:400;text-transform:none;font-size:0.78rem;">(optional)</span></label>
                                <input type="text" name="title" class="form-control" placeholder="e.g. Schedule change for 4Ps" value="{{ old('title') }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Content <span style="color:#C41E24;">*</span></label>
                                <textarea name="content" class="form-control" placeholder="Write your announcement here..." required>{{ old('content') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Type <span style="color:#C41E24;">*</span></label>
                                <select name="type" class="form-select" required>
                                    <option value="">Select type</option>
                                    @foreach(\App\Models\Announcement::TYPES as $val => $label)
                                        <option value="{{ $val }}" {{ old('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Program <span style="color:#C41E24;">*</span></label>
                                <select name="program_type" class="form-select" required>
                                    <option value="">Select program</option>
                                    @foreach(\App\Models\Announcement::PROGRAMS as $val => $label)
                                        <option value="{{ $val }}" {{ old('program_type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Municipality <span style="color:#C41E24;">*</span></label>
                                @if($admin->role === 'admin')
                                    <input type="hidden" name="municipality" value="{{ $admin->municipality }}">
                                    <input type="text" class="form-control" value="{{ $admin->municipality }}" disabled style="background:#f8fafc;color:#64748b;">
                                @else
                                    <select name="municipality" class="form-select" required>
                                        <option value="">Select municipality</option>
                                        <option value="all" {{ old('municipality') === 'all' ? 'selected' : '' }}>All Municipalities</option>
                                        @foreach($municipalities as $muni)
                                            <option value="{{ $muni }}" {{ old('municipality') === $muni ? 'selected' : '' }}>{{ $muni }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            <button type="submit" class="btn-post">Post Announcement</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ── ANNOUNCEMENTS LIST ── --}}
            <div class="col-lg-8">
                <div class="list-card">
                    <div class="list-card-header">
                        <span>Posted Announcements</span>
                        <span class="list-card-badge">{{ $announcements->count() }} total</span>
                    </div>

                    @if($announcements->isEmpty())
                        <div class="empty-state">
                            <p>No announcements have been posted yet.</p>
                        </div>
                    @else
                        <div style="overflow-x:auto;">
                            <table class="ann-table">
                                <thead>
                                    <tr>
                                        <th>Announcement</th>
                                        <th>Type</th>
                                        <th>Program</th>
                                        <th>Municipality</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($announcements as $ann)
                                    <tr>
                                        <td>
                                            @if($ann->title)
                                                <div class="ann-title">{{ $ann->title }}</div>
                                            @endif
                                            <div class="ann-preview">{{ $ann->content }}</div>
                                            <div class="ann-meta-text">
                                                {{ $ann->created_at->format('M d, Y') }}
                                                @if($ann->creator) &bull; {{ $ann->creator->full_name ?? $ann->creator->username }} @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="type-badge type-{{ $ann->type }}">{{ $ann->typeLabel() }}</span>
                                        </td>
                                        <td style="font-size:0.82rem;">
                                            @if(!$ann->program_type || $ann->program_type === 'all') All Programs
                                            @else {{ $ann->program_type }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(!$ann->municipality || $ann->municipality === 'all')
                                                <span class="muni-tag muni-all">All</span>
                                            @else
                                                <span class="muni-tag">{{ $ann->municipality }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($ann->is_active)
                                                <span class="badge-active">Active</span>
                                            @else
                                                <span class="badge-inactive">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1 flex-wrap">
                                                @if($ann->is_active)
                                                    <form method="POST" action="{{ route('admin.announcements.deactivate', $ann) }}">
                                                        @csrf @method('PATCH')
                                                        <button class="btn-act btn-deact">Deactivate</button>
                                                    </form>
                                                @else
                                                    <form method="POST" action="{{ route('admin.announcements.activate', $ann) }}">
                                                        @csrf @method('PATCH')
                                                        <button class="btn-act btn-activ">Activate</button>
                                                    </form>
                                                @endif
                                                <form method="POST" action="{{ route('admin.announcements.destroy', $ann) }}"
                                                    onsubmit="return confirm('Delete this announcement permanently?');">
                                                    @csrf @method('DELETE')
                                                    <button class="btn-act btn-del">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>{{-- end row --}}
    </div>
    </div>{{-- end main-content --}}

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    @include('components.admin-notification-modal')
    @include('components.admin-chat-modal')
    @include('components.admin-settings-modal')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
