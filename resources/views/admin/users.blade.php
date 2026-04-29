<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Management – {{ $municipality->name }} | MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @include('components.admin-colors')
    <style>
        :root {
            --primary-blue: {{ $adminPrimaryColor ?? '#2C3E8F' }};
            --secondary-yellow: {{ $adminSecondaryColor ?? '#FDB913' }};
            --accent-red: {{ $adminAccentColor ?? '#C41E24' }};
            --primary-gradient: linear-gradient(135deg, var(--primary-blue) 0%, color-mix(in srgb, var(--primary-blue) 80%, black) 100%);
            --bg-light: #F8FAFC;
            --border-light: #E2E8F0;
            --text-dark: #1E293B;
        }

        body { background: var(--bg-light); font-family: 'Inter', sans-serif; color: var(--text-dark); min-height: 100vh; margin: 0; }
        a { text-decoration: none; }

        /* NAVBAR */
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,0.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.55rem; color: white !important; display:flex; align-items:center; gap:12px; }
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; transition: all 0.25s; border-radius: 8px; padding: 10px 18px !important; font-size: 0.85rem; white-space: nowrap; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .user-info { color:white; display:flex; align-items:center; gap:12px; background:rgba(255,255,255,0.1); padding:9px 22px; border-radius:40px; font-size:0.9rem; font-weight:600; }
        .logout-btn { background:transparent; border:2px solid rgba(255,255,255,0.8); color:white; border-radius:30px; padding:6px 18px; font-weight:700; transition:all 0.3s; font-size:0.88rem; cursor:pointer; }
        .logout-btn:hover { background:var(--secondary-yellow); color:var(--primary-blue); border-color:var(--secondary-yellow); }

        /* HERO */
        .hero-banner { background: var(--primary-gradient); color: white; padding: 40px 0 32px; position: relative; overflow: hidden; }
        .hero-banner::before { content:''; position:absolute; top:-80px; right:-80px; width:300px; height:300px; border-radius:50%; background:rgba(253,185,19,0.09); }
        .hero-badge { display:inline-block; background:rgba(253,185,19,0.18); color:var(--secondary-yellow); border:1px solid rgba(253,185,19,0.35); border-radius:30px; padding:4px 16px; font-size:0.72rem; font-weight:800; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:10px; }
        .hero-banner h1 { font-size:1.85rem; font-weight:900; margin-bottom:4px; }
        .hero-divider { width:40px; height:4px; background:var(--secondary-yellow); border-radius:2px; margin:10px 0 8px; }

        /* STAT CARDS */
        .stat-card { background: white; border-radius: 16px; border: 1px solid var(--border-light); padding: 22px 24px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); position: relative; overflow: hidden; }
        .stat-card::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:var(--primary-gradient); }
        .stat-label { font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8; margin-bottom: 6px; }
        .stat-value { font-size: 2.4rem; font-weight: 900; color: var(--primary-blue); line-height: 1; }
        .stat-sub { font-size: 0.75rem; color: #94a3b8; margin-top: 6px; font-weight: 500; }

        /* SEARCH + FILTER BAR */
        .filter-bar { background: white; border-radius: 16px; border: 1px solid var(--border-light); padding: 18px 22px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); }
        .search-input { border: 2px solid #e2e8f0; border-radius: 10px; padding: 10px 16px 10px 40px; font-size: 0.88rem; font-family: 'Inter', sans-serif; width: 100%; transition: border-color .2s; background: #f8fafc; }
        .search-input:focus { outline: none; border-color: var(--primary-blue); background: white; box-shadow: 0 0 0 4px rgba(44,62,143,.08); }
        .search-wrap { position: relative; }
        .search-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; pointer-events: none; }

        /* TABLE */
        .table-card { background: white; border-radius: 18px; border: 1px solid var(--border-light); box-shadow: 0 4px 15px rgba(0,0,0,0.06); overflow: hidden; }
        .table-header { background: var(--primary-gradient); color: white; padding: 16px 22px; font-size: 0.85rem; font-weight: 700; display: flex; align-items: center; justify-content: space-between; }
        .table-header-badge { font-size: 0.7rem; background: rgba(255,255,255,0.15); border-radius: 20px; padding: 3px 12px; font-weight: 700; }
        .users-table { width: 100%; border-collapse: collapse; }
        .users-table th { font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.07em; color: #64748b; padding: 12px 16px 12px; border-bottom: 2px solid #e2e8f0; background: #f8fafc; white-space: nowrap; }
        .users-table td { padding: 14px 16px; font-size: 0.88rem; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .users-table tbody tr:last-child td { border-bottom: none; }

        /* AVATAR */
        .user-avatar { width: 38px; height: 38px; border-radius: 12px; background: var(--primary-gradient); display: flex; align-items: center; justify-content: center; font-size: 0.82rem; font-weight: 900; color: white; flex-shrink: 0; }

        /* BADGES */
        .badge-pill { display: inline-block; font-size: 0.68rem; font-weight: 800; letter-spacing: 0.06em; text-transform: uppercase; border-radius: 20px; padding: 3px 11px; }
        .badge-active { background: #d4edda; color: #155724; }
        .badge-pending { background: #FFF3D6; color: #856404; }
        .badge-approved { background: #d4edda; color: #155724; }
        .badge-rejected { background: #fce8e8; color: #721c24; }
        .badge-zero { background: #f1f5f9; color: #94a3b8; }

        /* MINI STAT PILLS */
        .mini-pill { display: inline-flex; align-items: center; gap: 5px; font-size: 0.72rem; font-weight: 700; padding: 3px 9px; border-radius: 20px; }
        .mp-total { background: #e5eeff; color: #2C3E8F; }
        .mp-pending { background: #FFF3D6; color: #856404; }
        .mp-approved { background: #d4edda; color: #155724; }
        .mp-rejected { background: #fce8e8; color: #721c24; }

        /* EMPTY STATE */
        .empty-state { text-align: center; padding: 64px 24px; color: #94a3b8; }
        .empty-state svg { opacity: .35; margin-bottom: 16px; }
        .empty-state h5 { font-weight: 800; color: #475569; font-size: 1rem; margin-bottom: 6px; }

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
                <img src="{{ asset('images/mswd-logo.png') }}" alt="MSWD" style="width:36px;height:36px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.requirements') }}">Applications</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.users') }}">Users Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.data.dashboard') }}">Data Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.announcements.index') }}">Announcements</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.detailed-analysis') }}">Analysis</a></li>
                    <li class="nav-item"><a class="nav-link" href="/analysis/programs">Comparative Analysis</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    @auth
                    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#adminNotifModal"
                        style="background:rgba(255,255,255,0.1);color:white;border:none;border-radius:50%;width:40px;height:40px;font-size:1.1rem;display:flex;align-items:center;justify-content:center;padding:0;position:relative;"
                        title="Notifications">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zm.995-14.901a1 1 0 1 0-1.99 0A5.002 5.002 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901z"/></svg>
                        @if(isset($adminNotifCount) && $adminNotifCount > 0)
                        <span style="position:absolute;top:-4px;right:-4px;background:#dc3545;color:white;border-radius:50%;width:20px;height:20px;font-size:0.7rem;font-weight:800;display:flex;align-items:center;justify-content:center;border:2px solid #2C3E8F;">{{ $adminNotifCount > 9 ? '9+' : $adminNotifCount }}</span>
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

    <!-- HERO -->
    <section class="hero-banner">
        <div class="container" style="position:relative;z-index:1;">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="hero-badge">User Management</div>
                    <h1>{{ $municipality->name }} — Registered Users</h1>
                    <div class="hero-divider"></div>
                    <p style="opacity:.82;font-size: .85rem;margin:0;">View all residents registered in the {{ $municipality->name }} MSWDO portal.</p>
                </div>
                <div class="col-md-4 d-none d-md-flex justify-content-end">
                    <div style="background:rgba(253,185,19,0.18);border:1px solid rgba(253,185,19,0.35);color:var(--secondary-yellow);border-radius:12px;padding:14px 24px;text-align:center;">
                        <span style="font-size:2rem;font-weight:900;display:block;">{{ $users->count() }}</span>
                        <span style="font-size:.72rem;opacity:.75;font-weight:600;text-transform:uppercase;letter-spacing:.08em;">Registered Users</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container mt-4">

        @php
            $topNotice = session('success') ?: session('error');
        @endphp
        @if($topNotice)
            <div style="position:fixed;top:84px;right:18px;z-index:1080;max-width:420px;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border:1px solid rgba(255,255,255,.18);border-radius:12px;padding:12px 16px;box-shadow:0 10px 28px rgba(26,42,92,.35);font-size:.84rem;font-weight:700;">
                {{ $topNotice }}
            </div>
        @endif

        <!-- STAT CARDS -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-label">Total Users</div>
                    <div class="stat-value">{{ $users->count() }}</div>
                    <div class="stat-sub">Registered in {{ $municipality->name }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-label">With Applications</div>
                    <div class="stat-value">{{ $users->where('total_apps', '>', 0)->count() }}</div>
                    <div class="stat-sub">Have submitted at least 1</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-label">Pending Reviews</div>
                    <div class="stat-value" style="color:#856404;">{{ $users->sum('pending_apps') }}</div>
                    <div class="stat-sub">Across all users</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-label">Total Approved</div>
                    <div class="stat-value" style="color:#15803d;">{{ $users->sum('approved_apps') }}</div>
                    <div class="stat-sub">Successfully processed</div>
                </div>
            </div>
        </div>

        <!-- SEARCH & FILTER BAR -->
        <div class="filter-bar mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="search-wrap">
                        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        <input type="text" id="userSearch" class="search-input" placeholder="Search by name, email or barangay…" oninput="filterUsers()">
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="appFilter" class="search-input" style="padding-left:14px;cursor:pointer;" onchange="filterUsers()">
                        <option value="">All users</option>
                        <option value="with">With applications</option>
                        <option value="without">No applications yet</option>
                    </select>
                </div>
                <div class="col-md-3 text-end">
                    <span id="userCount" style="font-size:.82rem;font-weight:700;color:#64748b;">{{ $users->count() }} users</span>
                </div>
            </div>
        </div>

        <!-- USERS TABLE -->
        <div class="table-card mb-5">
            <div class="table-header">
                <span>Registered Users</span>
                <span class="table-header-badge">{{ $municipality->name }}</span>
            </div>
            <div style="overflow-x:auto;">
                @if($users->count() > 0)
                <table class="users-table" id="usersTable">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Barangay</th>
                            <th>Gender</th>
                            <th>Applications</th>
                            <th>Joined</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        @foreach($users as $i => $user)
                        <tr class="user-row"
                            data-name="{{ strtolower($user->full_name) }}"
                            data-email="{{ strtolower($user->email) }}"
                            data-barangay="{{ strtolower($user->barangay ?? '') }}"
                            data-apps="{{ $user->total_apps }}">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar">{{ strtoupper(substr($user->full_name, 0, 2)) }}</div>
                                    <div>
                                        <div style="font-weight:700;font-size:.9rem;color:#1e293b;">{{ $user->full_name }}</div>
                                        @if($user->date_of_birth)
                                        <div style="font-size:.73rem;color:#94a3b8;margin-top:1px;">
                                            {{ \Carbon\Carbon::parse($user->date_of_birth)->age }} yrs old
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="color:#475569;font-size:.85rem;">{{ $user->email }}</td>
                            <td style="color:#475569;font-size:.85rem;">{{ $user->phone_number ?: '—' }}</td>
                            <td style="font-size:.85rem;font-weight:600;">{{ $user->barangay ?: '—' }}</td>
                            <td>
                                @if($user->gender)
                                    <span class="badge-pill" style="background:{{ $user->gender === 'Male' ? '#e5eeff' : '#fce8f5' }};color:{{ $user->gender === 'Male' ? '#2C3E8F' : '#9d174d' }};">
                                        {{ $user->gender }}
                                    </span>
                                @else
                                    <span style="color:#cbd5e1;font-size:.82rem;">—</span>
                                @endif
                            </td>
                            <td>
                                @if($user->total_apps > 0)
                                <div class="d-flex flex-wrap gap-1">
                                    <span class="mini-pill mp-total">{{ $user->total_apps }} total</span>
                                    @if($user->pending_apps > 0)
                                        <span class="mini-pill mp-pending">{{ $user->pending_apps }} pending</span>
                                    @endif
                                    @if($user->approved_apps > 0)
                                        <span class="mini-pill mp-approved">{{ $user->approved_apps }} approved</span>
                                    @endif
                                    @if($user->rejected_apps > 0)
                                        <span class="mini-pill mp-rejected">{{ $user->rejected_apps }} rejected</span>
                                    @endif
                                </div>
                                @else
                                    <span style="color:#cbd5e1;font-size:.82rem;font-style:italic;">No applications yet</span>
                                @endif
                            </td>
                            <td style="font-size:.82rem;color:#64748b;white-space:nowrap;">
                                {{ $user->created_at ? $user->created_at->format('M d, Y') : '—' }}
                            </td>
                            <td>
                                @if($user->email_verified_at)
                                    <span class="badge-pill badge-active">Verified</span>
                                @else
                                    <span class="badge-pill badge-pending">Unverified</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                    </svg>
                    <h5>No users registered yet</h5>
                    <p style="font-size:.85rem;margin:0;">No residents have registered in the {{ $municipality->name }} MSWDO portal yet.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- No results message (hidden by default) -->
        <div id="noResults" class="empty-state" style="display:none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0015.803 15.803z"/>
            </svg>
            <h5>No users found</h5>
            <p style="font-size:.85rem;margin:0;">Try a different search term or filter.</p>
        </div>

    </div>

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    @include('components.admin-notification-modal')
    @include('components.admin-chat-modal')
    @include('components.admin-settings-modal')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    let _searchDebounce;

    function buildUserRow(user) {
        const dob = user.date_of_birth ? new Date(user.date_of_birth) : null;
        const age = dob ? Math.floor((new Date() - dob) / (365.25 * 24 * 60 * 60 * 1000)) : null;
        const genderBadge = user.gender
            ? `<span class="badge-pill" style="background:${user.gender === 'Male' ? '#e5eeff' : '#fce8f5'};color:${user.gender === 'Male' ? '#2C3E8F' : '#9d174d'};">${user.gender}</span>`
            : '<span style="color:#cbd5e1;font-size:.82rem;">—</span>';

        let appsPills = '';
        if (user.total_apps > 0) {
            appsPills = `<div class="d-flex flex-wrap gap-1">`;
            appsPills += `<span class="mini-pill mp-total">${user.total_apps} total</span>`;
            if (user.pending_apps > 0) appsPills += `<span class="mini-pill mp-pending">${user.pending_apps} pending</span>`;
            if (user.approved_apps > 0) appsPills += `<span class="mini-pill mp-approved">${user.approved_apps} approved</span>`;
            if (user.rejected_apps > 0) appsPills += `<span class="mini-pill mp-rejected">${user.rejected_apps} rejected</span>`;
            appsPills += `</div>`;
        } else {
            appsPills = '<span style="color:#cbd5e1;font-size:.82rem;font-style:italic;">No applications yet</span>';
        }

        const statusBadge = user.email_verified_at
            ? '<span class="badge-pill badge-active">Verified</span>'
            : '<span class="badge-pill badge-pending">Unverified</span>';

        const initials = (user.full_name || '').substring(0, 2).toUpperCase();
        const joined = user.created_at ? new Date(user.created_at).toLocaleDateString('en-US', {month:'short', day:'2-digit', year:'numeric'}) : '—';

        return `
            <tr class="user-row">
                <td>
                    <div class="d-flex align-items-center gap-3">
                        <div class="user-avatar">${initials}</div>
                        <div>
                            <div style="font-weight:700;font-size:.9rem;color:#1e293b;">${user.full_name || '—'}</div>
                            ${age ? `<div style="font-size:.73rem;color:#94a3b8;margin-top:1px;">${age} yrs old</div>` : ''}
                        </div>
                    </div>
                </td>
                <td style="color:#475569;font-size:.85rem;">${user.email || '—'}</td>
                <td style="color:#475569;font-size:.85rem;">${user.phone_number || '—'}</td>
                <td style="font-size:.85rem;font-weight:600;">${user.barangay || '—'}</td>
                <td>${genderBadge}</td>
                <td>${appsPills}</td>
                <td style="font-size:.82rem;color:#64748b;white-space:nowrap;">${joined}</td>
                <td>${statusBadge}</td>
            </tr>
        `;
    }

    function filterUsers() {
        clearTimeout(_searchDebounce);
        _searchDebounce = setTimeout(fetchUsersAjax, 220);
    }

    async function fetchUsersAjax() {
        const q = document.getElementById('userSearch').value.trim();
        const appFilter = document.getElementById('appFilter').value;
        const tbody = document.getElementById('usersTableBody');

        const params = new URLSearchParams();
        if (q) params.set('q', q);
        if (appFilter) params.set('app_filter', appFilter);

        try {
            const res = await fetch(`{{ route('admin.users.search') }}?${params.toString()}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            });
            const data = await res.json();
            const users = data.users || [];

            if (!users.length) {
                tbody.innerHTML = '';
                document.getElementById('noResults').style.display = 'block';
            } else {
                tbody.innerHTML = users.map(buildUserRow).join('');
                document.getElementById('noResults').style.display = 'none';
            }

            const count = data.count || 0;
            document.getElementById('userCount').textContent = count + ' user' + (count !== 1 ? 's' : '');
        } catch (e) {
            // keep current table if request fails
        }
    }
    </script>
</body>
</html>
