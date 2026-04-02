<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Municipalities – MSWDO Super Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--primary-blue:#2C3E8F;--primary-blue-light:#E5EEFF;--secondary-yellow:#FDB913;--primary-gradient:linear-gradient(135deg,#2C3E8F 0%,#1A2A5C 100%);--secondary-gradient:linear-gradient(135deg,#FDB913 0%,#E5A500 100%);--bg-light:#F8FAFC;--border-light:#E2E8F0;}
        *,body{font-family:'Inter','Segoe UI',sans-serif;}
        body{background:var(--bg-light);display:flex;flex-direction:column;min-height:100vh;}
        .navbar{background:var(--primary-gradient)!important;box-shadow:0 4px 24px rgba(44,62,143,.18);padding:14px 0;}
        .navbar-brand{font-weight:800;font-size:1.55rem;color:white!important;display:flex;align-items:center;gap:10px;}
        .nav-link{color:rgba(255,255,255,.88)!important;font-weight:600;transition:all .25s;border-radius:8px;padding:10px 18px!important;font-size:.95rem;}
        .nav-link:hover{background:rgba(255,255,255,.15);color:white!important;}
        .nav-link.active{background:var(--secondary-yellow);color:var(--primary-blue)!important;font-weight:700;}
        .user-info{color:white;display:flex;align-items:center;gap:12px;background:rgba(255,255,255,.1);padding:9px 22px;border-radius:40px;font-size:.92rem;font-weight:500;}
        .logout-btn{background:transparent;border:2px solid rgba(255,255,255,.8);color:white;border-radius:30px;padding:6px 18px;font-weight:700;transition:all .3s;font-size:.88rem;cursor:pointer;}
        .logout-btn:hover{background:var(--secondary-yellow);color:var(--primary-blue);border-color:var(--secondary-yellow);}
        .hero-banner{background:var(--primary-gradient);color:white;padding:52px 0 42px;position:relative;overflow:hidden;}
        .hero-banner::before{content:'';position:absolute;top:-70px;right:-70px;width:320px;height:320px;border-radius:50%;background:rgba(253,185,19,.10);}
        .hero-badge{display:inline-block;background:rgba(253,185,19,.18);color:var(--secondary-yellow);border:1px solid rgba(253,185,19,.35);border-radius:30px;padding:5px 18px;font-size:.78rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;margin-bottom:18px;}
        .hero-banner h1{font-size:2.4rem;font-weight:800;margin-bottom:10px;}
        .hero-divider{width:55px;height:4px;background:var(--secondary-yellow);border-radius:2px;margin:14px 0;}
        .hero-banner p{font-size:1rem;opacity:.87;}
        .back-link{display:inline-flex;align-items:center;gap:6px;color:rgba(255,255,255,.75);font-size:.85rem;font-weight:600;text-decoration:none;margin-bottom:14px;transition:color .2s;}
        .back-link:hover{color:var(--secondary-yellow);}
        .main-content{flex:1;}
        .panel-card{background:white;border-radius:20px;box-shadow:0 4px 15px rgba(0,0,0,.03);border:1px solid var(--border-light);overflow:hidden;}
        .panel-header{background:var(--primary-gradient);color:white;padding:20px 28px;display:flex;align-items:center;justify-content:space-between;}
        .panel-header h5{font-weight:700;margin:0;font-size:1.05rem;}
        .count-badge{background:rgba(253,185,19,.25);color:var(--secondary-yellow);border:1px solid rgba(253,185,19,.4);border-radius:20px;padding:3px 12px;font-size:.78rem;font-weight:700;}
        table.premium-table{width:100%;border-collapse:collapse;}
        .premium-table thead th{background:var(--bg-light);color:var(--primary-blue);font-size:.78rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;padding:12px 20px;border-bottom:2px solid var(--border-light);}
        .premium-table tbody td{padding:14px 20px;font-size:.88rem;border-bottom:1px solid var(--border-light);vertical-align:middle;color:#334155;}
        .premium-table tbody tr:last-child td{border-bottom:none;}
        .premium-table tbody tr:hover{background:var(--primary-blue-light);}
        .btn-add{background:var(--secondary-gradient);color:var(--primary-blue);border:none;border-radius:30px;padding:10px 28px;font-weight:800;font-size:.9rem;cursor:pointer;transition:all .3s;text-decoration:none;display:inline-block;}
        .btn-add:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(253,185,19,.4);color:var(--primary-blue);}
        .btn-archive-view{background:transparent;border:2px solid var(--primary-blue);color:var(--primary-blue);border-radius:30px;padding:9px 22px;font-weight:700;transition:all .3s;cursor:pointer;font-size:.88rem;}
        .btn-archive-view:hover{background:var(--primary-blue);color:white;}
        .btn-action-view{display:inline-flex;align-items:center;background:var(--primary-blue-light);color:var(--primary-blue);border:1px solid rgba(44,62,143,.2);border-radius:8px;padding:5px 12px;font-size:.78rem;font-weight:700;cursor:pointer;transition:all .25s;text-decoration:none;}
        .btn-action-view:hover{background:var(--primary-blue);color:white;}
        .btn-action-edit{display:inline-flex;align-items:center;background:var(--secondary-gradient);color:var(--primary-blue);border:none;border-radius:8px;padding:5px 12px;font-size:.78rem;font-weight:700;cursor:pointer;transition:all .25s;text-decoration:none;}
        .btn-action-edit:hover{transform:translateY(-1px);box-shadow:0 4px 12px rgba(253,185,19,.4);color:var(--primary-blue);}
        .btn-action-archive{display:inline-flex;align-items:center;background:rgba(196,30,36,.08);color:#C41E24;border:1px solid rgba(196,30,36,.2);border-radius:8px;padding:5px 12px;font-size:.78rem;font-weight:700;cursor:pointer;transition:all .25s;}
        .btn-action-archive:hover{background:#C41E24;color:white;}
        .btn-restore{background:linear-gradient(135deg,#10B981,#059669);color:white;border:none;border-radius:8px;padding:5px 14px;font-size:.8rem;font-weight:700;cursor:pointer;transition:all .25s;}
        .btn-restore:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(16,185,129,.4);}
        .btn-perm-delete{background:rgba(196,30,36,.1);color:#C41E24;border:1px solid rgba(196,30,36,.2);border-radius:8px;padding:5px 14px;font-size:.8rem;font-weight:700;cursor:pointer;transition:all .25s;}
        .btn-perm-delete:hover{background:#C41E24;color:white;}
        .modal-header{background:var(--primary-gradient);color:white;border-radius:16px 16px 0 0;}
        .modal-title{font-weight:800;}
        .btn-close{filter:invert(1);}
        .modal-content{border-radius:16px;border:none;box-shadow:0 20px 60px rgba(44,62,143,.2);}
        .footer-strip{background:var(--primary-gradient);color:rgba(255,255,255,.75);text-align:center;padding:18px 0;font-size:.85rem;margin-top:auto;}
        .footer-strip strong{color:white;}
        .alert-success{background:#E5EEFF;color:var(--primary-blue);border:none;border-left:4px solid var(--primary-blue);border-radius:12px;}
        .alert-danger{background:#fef2f2;color:#991b1b;border:none;border-left:4px solid #C41E24;border-radius:12px;}
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="{{ route('superadmin.dashboard') }}">
            <img src="/images/mswd-logo.png" alt="MSWD" style="width:34px;height:34px;object-fit:contain;"> MSWDO
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.dashboard') }}">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.users') }}">User Management</a></li>
                <li class="nav-item"><a class="nav-link active" href="{{ route('superadmin.municipalities.index') }}">Municipalities</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.data.dashboard') }}">Data Management</a></li>
                <li class="nav-item"><a class="nav-link" href="/analysis/programs">Public View</a></li>
            </ul>
            <div class="d-flex">
                <div class="user-info">
                    <span>{{ Auth::user()->full_name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<section class="hero-banner">
    <div class="container" style="position:relative;z-index:2;">
        <a href="{{ route('superadmin.dashboard') }}" class="back-link">&#8592; Back to Dashboard</a>
        <div class="hero-badge">Super Admin</div>
        <h1>Municipalities Management</h1>
        <div class="hero-divider"></div>
        <p>Add, edit, archive, and manage all municipalities in the MSWDO system.</p>
    </div>
</section>

<div class="main-content">
    <div class="container py-5">
        @if(session('success'))
            <div class="alert alert-success mb-4">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mb-4">{{ session('error') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <button class="btn-archive-view" data-bs-toggle="modal" data-bs-target="#archivedMuniModal">
                🗂 Archived Municipalities (<span id="archivedMuniCount">...</span>)
            </button>
            <a href="{{ route('superadmin.municipalities.create') }}" class="btn-add">+ Add Municipality</a>
        </div>

        <div class="panel-card">
            <div class="panel-header">
                <h5>All Active Municipalities</h5>
                <span class="count-badge">{{ $municipalities->total() }} total</span>
            </div>
            <div class="table-responsive">
                <table class="premium-table">
                    <thead>
                        <tr>
                            <th>Municipality</th>
                            <th>Total Population</th>
                            <th>Households</th>
                            <th>Single Parents</th>
                            <th>Year</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($municipalities as $municipality)
                        <tr>
                            <td style="font-weight:700;color:var(--primary-blue);">{{ $municipality->name }}</td>
                            <td>{{ number_format($municipality->male_population + $municipality->female_population) }}</td>
                            <td>{{ number_format($municipality->total_households) }}</td>
                            <td>{{ number_format($municipality->single_parent_count) }}</td>
                            <td>{{ $municipality->year ?? date('Y') }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('superadmin.municipalities.barangays', $municipality->id) }}" class="btn-action-view">Barangays</a>
                                    <a href="{{ route('superadmin.municipalities.edit', $municipality->id) }}" class="btn-action-edit">Edit</a>
                                    <button class="btn-action-archive"
                                        onclick="archiveMunicipality({{ $municipality->id }}, '{{ addslashes($municipality->name) }}')">
                                        Archive
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 d-flex justify-content-center">{{ $municipalities->links() }}</div>
        </div>
    </div>
</div>

<footer class="footer-strip">
    <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
</footer>

<!-- ========== ARCHIVED MUNICIPALITIES MODAL ========== -->
<div class="modal fade" id="archivedMuniModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">🗂 Archived Municipalities</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive" style="max-height:480px;">
                    <table class="premium-table" style="margin-bottom:0;">
                        <thead style="position:sticky;top:0;z-index:1;">
                            <tr>
                                <th>Municipality</th>
                                <th>Year</th>
                                <th>Archived Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="archivedMuniList">
                            <tr><td colspan="4" class="text-center py-4">
                                <div class="spinner-border text-primary" role="status"></div>
                            </td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ── Load archived count on page load ──────────────────────────────
    fetch('/superadmin/municipalities/archived', { headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(data => { document.getElementById('archivedMuniCount').textContent = data.length; })
        .catch(() => { document.getElementById('archivedMuniCount').textContent = '?'; });

    // ── Load archived municipalities into modal ────────────────────────
    function loadArchivedMunicipalities() {
        const tbody = document.getElementById('archivedMuniList');
        tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></td></tr>';

        fetch('/superadmin/municipalities/archived', { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                document.getElementById('archivedMuniCount').textContent = data.length;
                if (!data.length) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center py-5 text-muted"><div style="font-size:2rem;opacity:.3;">🗂</div><p class="mt-2 mb-0">No archived municipalities.</p></td></tr>';
                    return;
                }
                tbody.innerHTML = data.map(m => {
                    const date = m.deleted_at ? new Date(m.deleted_at).toLocaleString('en-PH', {dateStyle:'medium',timeStyle:'short'}) : 'N/A';
                    return `<tr>
                        <td style="font-weight:700;color:var(--primary-blue);">${esc(m.name)}</td>
                        <td>${m.year || 'N/A'}</td>
                        <td>${date}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <button class="btn-restore" onclick="restoreMunicipality(${m.id}, '${esc(m.name)}')">Restore</button>
                                <button class="btn-perm-delete" onclick="permDeleteMunicipality(${m.id}, '${esc(m.name)}')">Delete Forever</button>
                            </div>
                        </td>
                    </tr>`;
                }).join('');
            })
            .catch(() => {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-danger">Failed to load archived municipalities.</td></tr>';
            });
    }

    // ── Archive ───────────────────────────────────────────────────────
    function archiveMunicipality(id, name) {
        if (!confirm(`Archive "${name}"?\n\nThis municipality will be hidden but can be restored later from the archive.`)) return;

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/superadmin/municipalities/' + id;
        const csrf  = document.createElement('input'); csrf.name = '_token';  csrf.value = CSRF;
        const meth  = document.createElement('input'); meth.name = '_method'; meth.value = 'DELETE';
        form.appendChild(csrf); form.appendChild(meth);
        document.body.appendChild(form);
        form.submit();
    }

    // ── Restore ───────────────────────────────────────────────────────
    function restoreMunicipality(id, name) {
        if (!confirm(`Restore "${name}"? It will become active again.`)) return;

        fetch('/superadmin/municipalities/' + id + '/restore', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }
        })
        .then(r => r.json())
        .then(data => { if (data.success) { loadArchivedMunicipalities(); location.reload(); } else { alert(data.message); } })
        .catch(() => alert('Network error.'));
    }

    // ── Permanent Delete ──────────────────────────────────────────────
    function permDeleteMunicipality(id, name) {
        if (!confirm(`⚠️ PERMANENTLY DELETE "${name}"?\n\nThis will remove it and all its barangay data from the database forever.\n\nThis CANNOT be undone!`)) return;

        fetch('/superadmin/municipalities/' + id + '/force-delete', {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' }
        })
        .then(r => r.json())
        .then(data => { if (data.success) { loadArchivedMunicipalities(); } else { alert(data.message); } })
        .catch(() => alert('Network error.'));
    }

    function esc(str) {
        if (!str) return '';
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML.replace(/'/g, "\\'");
    }

    document.getElementById('archivedMuniModal').addEventListener('show.bs.modal', loadArchivedMunicipalities);
</script>
</body>
</html>