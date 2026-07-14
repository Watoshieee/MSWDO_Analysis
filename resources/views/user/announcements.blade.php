<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Announcements – MSWDO Member Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
html, body { overscroll-behavior: none; margin: 0; padding: 0; }
        :root {
            --primary-blue: {{ $primaryColor ?? '#2C3E8F' }};
            --primary-dark: #1A2A5C;
            --secondary-yellow: {{ $secondaryColor ?? '#FDB913' }};
            --secondary-yellow-light: #FFF3D6;
            --primary-gradient: linear-gradient(135deg, {{ $primaryColor ?? '#2C3E8F' }} 0%, #1A2A5C 100%);
            --bg-light: #F4F7FB;
            --border-light: #E2E8F0;
            --text-dark: #1E293B;
            --text-muted: #64748b;
        }
        * { box-sizing: border-box; }
        body { background: #e2e8f0; font-family: 'Inter', sans-serif; color: var(--text-dark); display: flex; flex-direction: column; min-height: 100vh; margin: 0; }
        a { text-decoration: none; }

        /* ── NAVBAR ── */
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,0.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.5rem; color: white !important; display: flex; align-items: center; gap: 10px; }
        .navbar-toggler { order: -1; }
        .navbar-brand { order: 0; margin-left: auto !important; margin-right: 0 !important; }
        @media (min-width: 992px) {
            .navbar-toggler { order: 0; }
            .navbar-brand { order: 0; margin-left: 0 !important; margin-right: auto !important; }
        }
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; border-radius: 8px; padding: 10px 18px !important; font-size: 0.92rem; transition: all 0.2s; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .user-info { color: white; display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.1); padding: 9px 22px; border-radius: 40px; font-size: 0.9rem; font-weight: 600; }
        .logout-btn { background: transparent; border: 2px solid rgba(255,255,255,0.7); color: white; border-radius: 30px; padding: 6px 18px; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.25s; }
        .logout-btn:hover { background: var(--secondary-yellow); color: var(--primary-blue); border-color: var(--secondary-yellow); }

        /* ── HERO ── */
        .hero {
            background: var(--primary-gradient);
            color: white; padding: 56px 0 52px;
            position: relative; overflow: hidden;
        }
        .hero::before {
            content: ''; position: absolute; top: -100px; right: -100px;
            width: 400px; height: 400px; border-radius: 50%;
            background: radial-gradient(circle, rgba(253,185,19,0.12) 0%, transparent 70%);
        }
        .hero::after {
            content: ''; position: absolute; bottom: -80px; left: -60px;
            width: 280px; height: 280px; border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .hero-inner { position: relative; z-index: 2; }
        .hero-badge {
            display: inline-block; background: rgba(253,185,19,0.18);
            color: var(--secondary-yellow); border: 1px solid rgba(253,185,19,0.35);
            border-radius: 30px; padding: 5px 18px;
            font-size: 0.72rem; font-weight: 800; letter-spacing: 0.12em;
            text-transform: uppercase; margin-bottom: 14px;
        }
        .hero h1 { font-size: 2.5rem; font-weight: 900; line-height: 1.15; margin-bottom: 6px; }
        .hero-divider { width: 50px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; margin: 14px 0 16px; }
        .hero p { opacity: 0.85; font-size: 0.97rem; max-width: 520px; line-height: 1.7; margin-bottom: 0; }

        /* ── FILTER BAR (sticky) ── */
        .filter-bar {
            background: #f8fafc; border-bottom: 1px solid #cbd5e1;
            padding: 14px 0; position: sticky; top: 0; z-index: 100;
            box-shadow: 0 2px 12px rgba(44,62,143,0.06);
        }
        .filter-inner { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .filter-select {
            border: 1.5px solid #cbd5e1; border-radius: 10px;
            font-size: 0.85rem; padding: 7px 14px;
            font-family: 'Inter', sans-serif; color: var(--text-dark);
            background: white; cursor: pointer; transition: border-color 0.2s;
            min-width: 150px;
        }
        .filter-select:focus { border-color: var(--primary-blue); outline: none; }
        .filter-label { font-size: 0.8rem; font-weight: 700; color: var(--text-muted); white-space: nowrap; }
        .btn-filter {
            background: var(--primary-gradient); color: white; border: none;
            padding: 8px 20px; border-radius: 10px; font-size: 0.84rem;
            font-weight: 700; cursor: pointer; transition: all 0.2s;
        }
        .btn-filter:hover { box-shadow: 0 4px 14px rgba(44,62,143,0.3); transform: translateY(-1px); }
        .btn-reset {
            background: white; color: var(--text-muted); border: 1.5px solid #cbd5e1;
            padding: 7px 16px; border-radius: 10px; font-size: 0.84rem;
            font-weight: 600; cursor: pointer; transition: all 0.2s; text-decoration: none;
        }
        .btn-reset:hover { border-color: var(--primary-blue); color: var(--primary-blue); }
        .result-count { margin-left: auto; font-size: 0.78rem; color: var(--text-muted); font-weight: 600; white-space: nowrap; }

        /* ── MAIN ── */
        .main-content { flex: 1; }

        /* ── ANNOUNCEMENT CARD ── */
        .ann-card {
            background: #f8fafc;
            border-radius: 20px;
            border: 1px solid #cbd5e1;
            overflow: hidden;
            margin-bottom: 16px;
        }

        /* Color accent strip */
        .ann-card .accent { height: 5px; width: 100%; }
        .acc-general        { background: linear-gradient(90deg, #2C3E8F, #5578d9); }
        .acc-event          { background: linear-gradient(90deg, #FDB913, #E5A500); }
        .acc-emergency      { background: linear-gradient(90deg, #dc2626, #ef4444); }
        .acc-program_update { background: linear-gradient(90deg, #16a34a, #22c55e); }

        .ann-body { padding: 20px 22px 18px; }

        .ann-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; margin-bottom: 10px; }
        .ann-title { font-size: 1rem; font-weight: 800; color: var(--primary-blue); line-height: 1.3; }
        .ann-title.no-title { font-style: italic; font-weight: 400; color: var(--text-muted); font-size: 0.88rem; }

        .type-badge {
            display: inline-block; font-size: 0.68rem; font-weight: 800;
            letter-spacing: 0.5px; padding: 3px 11px; border-radius: 20px;
            text-transform: uppercase; white-space: nowrap; flex-shrink: 0;
        }
        .badge-general        { background: #E5EEFF; color: #2C3E8F; }
        .badge-event          { background: var(--secondary-yellow-light); color: #856404; }
        .badge-emergency      { background: #fee2e2; color: #991b1b; }
        .badge-program_update { background: #d4edda; color: #155724; }

        .ann-content { font-size: 0.88rem; color: var(--text-dark); line-height: 1.75; margin-bottom: 14px; }

        .ann-footer {
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 8px;
            padding-top: 12px; border-top: 1px solid #e2e8f0;
        }
        .ann-meta { font-size: 0.76rem; color: var(--text-muted); font-weight: 500; }
        .prog-tag {
            display: inline-block; font-size: 0.7rem; font-weight: 700;
            padding: 3px 10px; border-radius: 20px;
            background: #E5EEFF; color: var(--primary-blue);
        }
        .prog-tag.all { background: var(--secondary-yellow-light); color: #856404; }

        /* ── EMPTY STATE ── */
        .empty-state { text-align: center; padding: 60px 0; }
        .empty-num { font-size: 4rem; font-weight: 900; color: #E2E8F0; line-height: 1; display: block; margin-bottom: 12px; }
        .empty-state h5 { font-weight: 700; color: var(--primary-blue); margin-bottom: 8px; }
        .empty-state p { color: var(--text-muted); font-size: 0.88rem; }

        /* ── ALERTS ── */
        .alert-styled { border-radius: 12px; border: none; font-size: 0.88rem; padding: 12px 16px; }

        /* ── PAGINATION ── */
        .pagination .page-link { color: var(--primary-blue); border-color: var(--border-light); }
        .pagination .page-item.active .page-link { background: var(--primary-blue); border-color: var(--primary-blue); }

        /* ── FOOTER ── */
        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,0.7); text-align: center; padding: 20px 0; font-size: 0.83rem; }
        .footer-strip strong { color: white; }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/user/dashboard">
                <img src="{{ asset('images/mswd-logo.png') }}" alt="MSWD" style="width:36px;height:36px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/user/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/user/programs">Programs</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('user.profile') }}">User Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('user.my-requirements') }}">My Requirements</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('user.announcements') }}">Announcements</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#announcementsModal"
                        style="background:rgba(255,255,255,0.1);color:white;border:none;border-radius:50%;width:40px;height:40px;font-weight:700;font-size:1.1rem;display:flex;align-items:center;justify-content:center;padding:0;transition:all 0.3s;position:relative;"
                        title="Notifications">
                        <i class="bi bi-bell-fill"></i>
                        @if(isset($notificationCount) && $notificationCount > 0)
                        <span class="bell-badge" style="position:absolute;top:-4px;right:-4px;background:#dc3545;color:white;border-radius:50%;width:20px;height:20px;font-size:0.7rem;font-weight:800;display:flex;align-items:center;justify-content:center;border:2px solid #2C3E8F;">{{ $notificationCount > 9 ? '9+' : $notificationCount }}</span>
                        @endif
                    </button>
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

    <!-- HERO -->
    <section class="hero">
        <div class="container">
            <div class="hero-inner">
                <div class="hero-badge">Member Portal</div>
                <h1>Announcements</h1>
                <div class="hero-divider"></div>
                <p>Stay updated with the latest notices, events, and program updates from MSWDO relevant to your municipality and enrolled programs.</p>
            </div>
        </div>
    </section>

    <!-- FILTER BAR -->
    <div class="filter-bar">
        <div class="container">
            <form method="GET" action="{{ route('user.announcements') }}" class="filter-inner">
                <span class="filter-label">Filter by:</span>

                <select name="type" class="filter-select">
                    <option value="">All Types</option>
                    @foreach($types as $val => $label)
                        <option value="{{ $val }}" {{ request('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>

                <select name="program" class="filter-select">
                    <option value="">All Programs</option>
                    @foreach($programs as $val => $label)
                        @if($val !== 'all')
                            <option value="{{ $val }}" {{ request('program') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endif
                    @endforeach
                </select>

                <button type="submit" class="btn-filter">Apply</button>

                @if(request('type') || request('program'))
                    <a href="{{ route('user.announcements') }}" class="btn-reset">Clear</a>
                @endif

                @if(method_exists($announcements, 'total'))
                    <div class="result-count">{{ $announcements->total() }} announcement{{ $announcements->total() != 1 ? 's' : '' }}</div>
                @endif
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="container" style="padding-top:28px; padding-bottom:48px;">

            @if(is_object($announcements) && (method_exists($announcements, 'isEmpty') ? $announcements->isEmpty() : $announcements->count() === 0))
                <div class="empty-state">
                    <span class="empty-num">00</span>
                    <h5>No Announcements Yet</h5>
                    <p>There are no announcements for your municipality or programs at this time.<br>Check back later for updates.</p>
                </div>
            @else
                @foreach($announcements as $ann)
                <div class="ann-card">
                    <div class="accent acc-{{ $ann->type }}"></div>
                    <div class="ann-body">
                        <div class="ann-header">
                            @if($ann->title)
                                <div class="ann-title">{{ $ann->title }}</div>
                            @else
                                <div class="ann-title no-title">No title</div>
                            @endif
                            <span class="type-badge badge-{{ $ann->type }}">{{ $ann->typeLabel() }}</span>
                        </div>

                        <div class="ann-content">{{ $ann->content }}</div>

                        <div class="ann-footer">
                            <div class="ann-meta">
                                Posted {{ $ann->created_at->format('F d, Y') }}
                                @if($ann->municipality && $ann->municipality !== 'all')
                                    &bull; {{ $ann->municipality }}
                                @endif
                            </div>
                            @if($ann->program_type && $ann->program_type !== 'all')
                                <span class="prog-tag">{{ $ann->program_type }}</span>
                            @else
                                <span class="prog-tag all">All Programs</span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach

                {{-- Pagination --}}
                @if(method_exists($announcements, 'links') && $announcements->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $announcements->links() }}
                    </div>
                @endif
            @endif

        </div>
    </div>

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    @include('components.user-notification-modal')
    @include('components.chat-modal')
    @include('components.chatbot-widget')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mark notifications as viewed when modal is opened
        document.getElementById('announcementsModal').addEventListener('show.bs.modal', function () {
            fetch('{{ route('user.mark-notifications-viewed') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    const badge = document.querySelector('.btn[data-bs-target="#announcementsModal"] span');
                    if (badge) badge.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>