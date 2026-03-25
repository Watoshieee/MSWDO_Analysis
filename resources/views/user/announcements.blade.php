<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Announcements – MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --secondary-yellow: #FDB913;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --bg-light: #F8FAFC; --bg-white: #FFFFFF; --bg-soft-blue: #F0F5FF;
            --border-light: #E2E8F0; --text-dark: #1E293B;
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

        /* ── ANNOUNCEMENT CARDS ── */
        .ann-card {
            background: var(--bg-white); border-radius: 18px;
            border: 1px solid var(--border-light); margin-bottom: 16px;
            padding: 22px 24px; cursor: pointer;
            transition: all 0.25s ease; position:relative; overflow:hidden;
        }
        .ann-card::before {
            content:''; position:absolute; top:0; left:0; bottom:0;
            width: 5px; background: var(--primary-blue); border-radius: 18px 0 0 18px;
        }
        .ann-card.info::before    { background: #17a2b8; }
        .ann-card.warning::before { background: var(--secondary-yellow); }
        .ann-card.success::before { background: #28a745; }
        .ann-card.danger::before  { background: #dc3545; }

        .ann-card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(44,62,143,0.10); border-color: var(--primary-blue); }

        .ann-type-pill {
            font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em;
            padding: 3px 10px; border-radius: 20px; display: inline-block; margin-bottom: 8px;
        }
        .pill-info    { background: #e0f7fa; color: #006064; }
        .pill-warning { background: #FFF3D6; color: #856404; }
        .pill-success { background: #e8f5e9; color: #2e7d32; }
        .pill-danger  { background: #fce8e8; color: #b71c1c; }
        .pill-default { background: var(--bg-soft-blue); color: var(--primary-blue); }

        .ann-title { font-size: 1.05rem; font-weight: 700; color: var(--text-dark); margin-bottom: 6px; }
        .ann-preview { font-size: 0.86rem; color: #64748b; line-height: 1.6;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .ann-date { font-size: 0.76rem; color: #94a3b8; margin-top: 10px; }
        .ann-arrow { font-size: 1.1rem; color: #CBD5E1; font-style: normal; }

        /* ── MODAL ── */
        .modal-content { border-radius: 18px; overflow: hidden; border: none; }
        .modal-header  { background: var(--primary-gradient); color: white; border-radius: 0; border: none; padding: 20px 24px; }
        .modal-title   { font-weight: 700; font-size: 1.05rem; }
        .modal-body    { padding: 24px; font-size: 0.92rem; line-height: 1.7; color: #334155; }
        .modal-footer  { border: none; padding: 12px 24px 20px; }

        /* ── EMPTY ── */
        .empty-state { text-align:center; padding: 50px 0; }
        .empty-num { font-size: 4rem; font-weight: 800; color: #E2E8F0; line-height:1; }
        .empty-state p { color: #94a3b8; }

        /* ── FOOTER ── */
        .main-content { flex:1; }
        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,0.75); text-align: center; padding: 20px 0; font-size: 0.85rem; margin-top: 40px; }
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
                    <li class="nav-item"><a class="nav-link" href="/user/programs">Programs</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('user.my-requirements') }}">My Requirements</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/user/announcements">Announcements</a></li>
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
                <h1>Announcements</h1>
                <div class="hero-divider"></div>
                <p>Important updates, reminders, and notices from the Municipal Social Welfare &amp; Development Office.</p>
            </div>
        </div>
    </section>

    <div class="main-content">
    <div class="container mt-4">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" style="border-radius:12px;">
                {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(count($announcements) > 0)
            @foreach($announcements as $announcement)
                @php
                    $pillClass = 'pill-' . ($announcement->type ?? 'default');
                @endphp
                <div class="ann-card {{ $announcement->type ?? '' }}" onclick="showAnnouncement({{ $announcement->id }})">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <span class="ann-type-pill {{ $pillClass }}">{{ ucfirst($announcement->type ?? 'Notice') }}</span>
                            <div class="ann-title">{{ $announcement->title }}</div>
                            <div class="ann-preview">{{ Str::limit(strip_tags($announcement->content), 150) }}</div>
                            <div class="ann-date">{{ $announcement->created_at->format('F d, Y') }}</div>
                        </div>
                        <span class="ann-arrow">&rsaquo;</span>
                    </div>
                </div>
            @endforeach

            <div class="mt-4">{{ $announcements->links() }}</div>
        @else
            <div class="empty-state">
                <div class="empty-num">00</div>
                <h5 style="font-weight:700;color:var(--primary-blue);margin:12px 0 8px;">No announcements yet</h5>
                <p>Check back later for updates from MSWDO.</p>
            </div>
        @endif
    </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="announcementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Announcement</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const announcementsData = @json($announcements);

        function showAnnouncement(id) {
            const a = announcementsData.find(x => x.id === id);
            if (!a) return;

            const typeColors = { success: '#28a745', warning: '#FDB913', danger: '#dc3545', info: '#17a2b8' };
            const color = typeColors[a.type] || '#2C3E8F';
            const posted = new Date(a.created_at).toLocaleDateString('en-US', { year:'numeric', month:'long', day:'numeric' });

            document.getElementById('modalTitle').textContent = a.title;
            document.getElementById('modalBody').innerHTML = `
                <div style="margin-bottom:14px;">
                    <span style="background:${color};color:white;padding:3px 12px;border-radius:20px;font-size:0.72rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;">
                        ${(a.type || 'Notice').toUpperCase()}
                    </span>
                </div>
                <h5 style="font-weight:800;color:#1e293b;margin-bottom:14px;">${a.title}</h5>
                <div style="line-height:1.75;color:#334155;">${a.content}</div>
                <hr style="margin:18px 0;">
                <div style="font-size:0.78rem;color:#94a3b8;">Posted on: ${posted}</div>
            `;

            new bootstrap.Modal(document.getElementById('announcementModal')).show();
        }
    </script>
</body>
</html>