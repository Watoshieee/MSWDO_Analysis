<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Applications - MSWDO Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @include('components.admin-colors')
    <style>
        html,
        body {
            overscroll-behavior: none;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg-light: #F8FAFC;
            --bg-soft-blue: #F0F5FF;
            --border-light: #E2E8F0;
            --text-dark: #1E293B;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: var(--bg-light);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a {
            text-decoration: none;
        }

        .navbar {
            background: var(--primary-gradient) !important;
            box-shadow: 0 4px 24px rgba(44, 62, 143, 0.18);
            padding: 14px 0;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.55rem;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .navbar-toggler {
            order: -1;
        }

        .navbar-brand {
            order: 0;
            margin-left: auto !important;
            margin-right: 0 !important;
        }

        @media (min-width: 992px) {
            .navbar-toggler {
                order: 0;
            }

            .navbar-brand {
                order: 0;
                margin-left: 0 !important;
                margin-right: auto !important;
            }
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.88) !important;
            font-weight: 600;
            transition: all 0.25s;
            border-radius: 8px;
            padding: 10px 18px !important;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white !important;
        }

        .nav-link.active {
            background: var(--secondary-yellow);
            color: var(--primary-blue) !important;
            font-weight: 700;
        }

        .user-info {
            color: white;
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.1);
            padding: 9px 22px;
            border-radius: 40px;
            font-size: 0.92rem;
            font-weight: 500;
        }

        .logout-btn {
            background: transparent;
            border: 2px solid rgba(255, 255, 255, 0.8);
            color: white;
            border-radius: 30px;
            padding: 6px 18px;
            font-weight: 700;
            transition: all 0.3s;
            font-size: 0.88rem;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            border-color: var(--secondary-yellow);
        }

        .page-hero {
            background: var(--primary-gradient);
            border-radius: 20px;
            padding: 32px 36px;
            margin-bottom: 28px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .page-hero h1 {
            font-size: 1.75rem;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .page-hero p {
            opacity: 0.82;
            margin: 0;
            font-size: 0.92rem;
        }

        .muni-badge {
            background: rgba(253, 185, 19, 0.18);
            border: 1px solid rgba(253, 185, 19, 0.35);
            color: #FDB913;
            border-radius: 30px;
            padding: 7px 20px;
            font-size: 0.85rem;
            font-weight: 700;
            display: inline-block;
        }

        .panel-card {
            background: #fff;
            border-radius: 18px;
            border: 1px solid var(--border-light);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .panel-header {
            background: var(--primary-gradient);
            color: white;
            padding: 18px 26px;
            font-size: 1rem;
            font-weight: 700;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table thead th {
            background: var(--bg-soft-blue);
            color: var(--primary-blue);
            font-weight: 700;
            font-size: 0.775rem;
            border: none;
            padding: 13px 15px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .table tbody td {
            padding: 14px 15px;
            font-size: 0.875rem;
            vertical-align: middle;
            border-color: #F1F5F9;
        }

        .table tbody tr:hover {
            background: #F8FAFC;
        }

        .prog-tag {
            background: var(--bg-soft-blue);
            color: var(--primary-blue);
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.76rem;
            font-weight: 700;
        }

        .status-pill {
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 0.76rem;
            font-weight: 700;
            display: inline-block;
        }

        .status-pending {
            background: #FFF3D6;
            color: #856404;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background: #FCE8E8;
            color: #C41E24;
        }

        .prog-mini {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .prog-mini .bar {
            width: 60px;
            height: 6px;
            background: #e2e8f0;
            border-radius: 6px;
            overflow: hidden;
        }

        .prog-mini .fill {
            height: 100%;
            background: var(--primary-gradient);
            border-radius: 6px;
        }

        .prog-mini .pct {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--primary-blue);
            min-width: 28px;
        }

        .btn-action {
            border: none;
            border-radius: 8px;
            padding: 5px 13px;
            font-weight: 700;
            font-size: 0.78rem;
            transition: all 0.2s;
            cursor: pointer;
            display: inline-block;
            text-decoration: none;
        }

        .btn-approve {
            background: #d4edda;
            color: #155724;
        }

        .btn-approve:hover {
            background: #28a745;
            color: white;
        }

        .btn-decline {
            background: #FCE8E8;
            color: #C41E24;
        }

        .btn-decline:hover {
            background: #C41E24;
            color: white;
        }

        .btn-view {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-view:hover {
            opacity: 0.88;
            color: white;
            box-shadow: 0 4px 12px rgba(44, 62, 143, 0.3);
        }

        .action-gap {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .empty-state {
            text-align: center;
            padding: 60px 24px;
        }

        .empty-num {
            font-size: 5rem;
            font-weight: 800;
            color: #E2E8F0;
            line-height: 1;
        }

        .empty-state p {
            color: #94a3b8;
            margin-top: 10px;
            font-size: 0.85rem;
        }

        .footer-strip {
            background: var(--primary-gradient);
            color: rgba(255, 255, 255, 0.9);
            text-align: center;
            padding: 20px;
            font-size: 0.85rem;
            margin-top: 40px;
        }

        /* ── Minimal in-page UI dialogs (avoid browser confirm/alert) ───────── */
        .ui-modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(2, 6, 23, 0.55);
            z-index: 11000;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .ui-modal {
            width: 100%;
            max-width: 520px;
            border-radius: 18px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 20px 70px rgba(44, 62, 143, 0.25);
            transform: translateY(8px);
            opacity: 0;
            transition: all .18s ease;
        }

        .ui-modal-backdrop.show .ui-modal {
            transform: translateY(0);
            opacity: 1;
        }

        .ui-modal-header {
            background: var(--primary-gradient);
            color: #fff;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .ui-modal-title {
            font-weight: 800;
            font-size: 0.98rem;
            margin: 0;
            line-height: 1.2;
        }

        .ui-modal-close {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.14);
            border: 1px solid rgba(255, 255, 255, 0.22);
            color: #fff;
            cursor: pointer;
            font-size: 1.25rem;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .ui-modal-body {
            padding: 18px 20px 12px;
            color: #334155;
            font-size: 0.92rem;
        }

        .ui-modal-body p {
            margin: 0;
            line-height: 1.55;
        }

        .ui-modal-footer {
            padding: 12px 20px 18px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .ui-btn {
            border: none;
            border-radius: 12px;
            padding: 10px 16px;
            font-weight: 800;
            font-size: 0.86rem;
            cursor: pointer;
            transition: all .15s ease;
        }

        .ui-btn:active {
            transform: translateY(1px);
        }

        .ui-btn-secondary {
            background: #f1f5f9;
            color: #334155;
        }

        .ui-btn-secondary:hover {
            background: #e2e8f0;
        }

        .ui-btn-primary {
            background: var(--primary-gradient);
            color: #fff;
        }

        .ui-btn-primary:hover {
            opacity: 0.92;
        }

        .ui-toast {
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 12000;
            min-width: 260px;
            max-width: 360px;
            padding: 12px 14px;
            border-radius: 14px;
            background: #0f172a;
            color: #fff;
            box-shadow: 0 14px 40px rgba(2, 6, 23, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.12);
            display: none;
        }

        .ui-toast.show {
            display: block;
        }

        .ui-toast small {
            display: block;
            opacity: 0.75;
            font-weight: 600;
            margin-top: 2px;
        }

        /* ── Navy loading overlay for async/admin actions ───────────────────── */
        .ui-loading-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(1.5px);
            z-index: 12050;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .ui-loading-box {
            width: 100%;
            max-width: 360px;
            border-radius: 16px;
            background: linear-gradient(135deg, #2C3E8F, #1A2A5C);
            color: #fff;
            box-shadow: 0 16px 44px rgba(15, 23, 42, .35);
            border: 1px solid rgba(255, 255, 255, .15);
            padding: 20px 18px;
            text-align: center;
        }

        .ui-loading-spinner {
            width: 44px;
            height: 44px;
            margin: 0 auto 10px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, .25);
            border-top-color: #FDB913;
            animation: uiSpin .8s linear infinite;
        }

        .ui-loading-title {
            font-weight: 800;
            font-size: .98rem;
            letter-spacing: .01em;
        }

        .ui-loading-sub {
            margin-top: 4px;
            opacity: .85;
            font-size: .8rem;
        }

        @keyframes uiSpin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div id="uiLoadingBackdrop" class="ui-loading-backdrop" aria-hidden="true">
        <div class="ui-loading-box" role="status" aria-live="polite">
            <div class="ui-loading-spinner"></div>
            <div class="ui-loading-title">Processing Request</div>
            <div class="ui-loading-sub">Please wait while we update records.</div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/admin/dashboard">
                <img src="{{ asset('images/mswd-logo.png') }}" alt="MSWD"
                    style="width:36px;height:36px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active"
                            href="{{ route('admin.requirements') }}">Applications</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}"
                            href="{{ route('admin.users') }}">Users Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.data.dashboard') }}">Data
                            Management</a></li>
                    <li class="nav-item"><a
                            class="nav-link {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}"
                            href="{{ route('admin.announcements.index') }}">Announcements</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.detailed-analysis') }}">Analysis</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/analysis/programs">Comparative Analysis</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    @auth
                        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#adminNotifModal"
                            style="background:rgba(255,255,255,0.1);color:white;border:none;border-radius:50%;width:40px;height:40px;font-size:1.1rem;display:flex;align-items:center;justify-content:center;padding:0;transition:all 0.3s;position:relative;"
                            title="Application Notifications">
                            <i class="bi bi-bell-fill"></i>
                            @if(isset($adminNotifCount) && $adminNotifCount > 0)
                                <span class="admin-bell-badge"
                                    style="position:absolute;top:-4px;right:-4px;background:#dc3545;color:white;border-radius:50%;width:20px;height:20px;font-size:0.7rem;font-weight:800;display:flex;align-items:center;justify-content:center;border:2px solid #2C3E8F;">{{ $adminNotifCount > 9 ? '9+' : $adminNotifCount }}</span>
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

    <div style="flex:1;">
        <div class="container mt-4">

            @php
                $topNotice = session('success') ?: session('error');
            @endphp
            @if($topNotice)
                <div
                    style="position:fixed;top:84px;right:18px;z-index:1080;max-width:420px;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border:1px solid rgba(255,255,255,.18);border-radius:12px;padding:12px 16px;box-shadow:0 10px 28px rgba(26,42,92,.35);font-size:.84rem;font-weight:700;">
                    {{ $topNotice }}
                </div>
            @endif

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

            <div class="panel-card">
                <div class="panel-header">
                    <span>Submitted Applications</span>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <button type="button" onclick="document.getElementById('archiveModal').style.display='flex'"
                            style="background:rgba(255,255,255,0.12);border:1.5px solid rgba(255,255,255,0.3);color:white;border-radius:20px;padding:5px 16px;font-size:0.8rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;transition:all .2s;"
                            onmouseover="this.style.background='rgba(255,255,255,0.22)'"
                            onmouseout="this.style.background='rgba(255,255,255,0.12)'">
                            &#128193; Archived ({{ isset($archivedApplications) ? $archivedApplications->count() : 0 }})
                        </button>
                        <span
                            style="font-size:0.8rem;font-weight:600;background:rgba(255,255,255,0.15);padding:4px 12px;border-radius:20px;">
                            {{ $applications->total() }} Total
                        </span>
                    </div>
                </div>
                <div class="p-0">
                    <form method="GET" action="{{ route('admin.requirements') }}"
                        style="padding:14px 16px;border-bottom:1px solid #e2e8f0;background:#f8faff;">
                        <div
                            style="display:grid;grid-template-columns:2fr 1fr 1fr auto auto;gap:10px;align-items:center;">
                            <input type="text" name="app_search" value="{{ request('app_search') }}"
                                placeholder="Search applicant, contact, barangay..." class="form-control"
                                style="font-size:.84rem;border-radius:10px;border:1px solid #c7d6f5;">
                            <select name="app_status" class="form-select"
                                style="font-size:.84rem;border-radius:10px;border:1px solid #c7d6f5;">
                                <option value="">All status</option>
                                <option value="pending" {{ request('app_status') === 'pending' ? 'selected' : '' }}>
                                    Pending</option>
                                <option value="approved" {{ request('app_status') === 'approved' ? 'selected' : '' }}>
                                    Approved</option>
                                <option value="rejected" {{ request('app_status') === 'rejected' ? 'selected' : '' }}>
                                    Rejected</option>
                            </select>
                            <select name="app_program" class="form-select"
                                style="font-size:.84rem;border-radius:10px;border:1px solid #c7d6f5;">
                                <option value="">All programs</option>
                                <option value="PWD_Assistance" {{ request('app_program') === 'PWD_Assistance' ? 'selected' : '' }}>PWD Assistance</option>
                                <option value="PWD_New" {{ request('app_program') === 'PWD_New' ? 'selected' : '' }}>PWD
                                    New</option>
                                <option value="PWD_Renewal" {{ request('app_program') === 'PWD_Renewal' ? 'selected' : '' }}>PWD Renewal</option>
                                <option value="Solo_Parent" {{ request('app_program') === 'Solo_Parent' ? 'selected' : '' }}>Solo Parent</option>
                                <option value="AICS_Medical" {{ request('app_program') === 'AICS_Medical' ? 'selected' : '' }}>AICS Medical</option>
                                <option value="AICS_Burial" {{ request('app_program') === 'AICS_Burial' ? 'selected' : '' }}>AICS Burial</option>
                            </select>
                            <button type="submit" class="btn-action btn-view" style="padding:8px 14px;">Filter</button>
                            <a href="{{ route('admin.requirements') }}" class="btn-action"
                                style="padding:8px 14px;background:#eef2ff;color:#1e3a8a;border:1px solid #c7d2fe;">Reset</a>
                        </div>
                    </form>
                    @if($applications->count() > 0)
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th>Applicant</th>
                                        <th>Program</th>
                                        <th>Barangay</th>
                                        <th>Date Filed</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applications as $app)
                                        <tr>
                                            <td>
                                                <div style="font-weight:700;color:#1e293b;">{{ $app->full_name }}</div>
                                                <div style="font-size:.72rem;color:#94a3b8;">{{ $app->contact_number }}</div>
                                            </td>
                                            <td><span class="prog-tag">{{ str_replace('_', ' ', $app->program_type) }}</span>
                                            </td>
                                            <td style="font-size:.85rem;color:#475569;">{{ $app->barangay ?: '—' }}</td>
                                            <td style="font-size:.82rem;color:#64748b;">
                                                {{ $app->application_date ? \Carbon\Carbon::parse($app->application_date)->format('M j, Y') : '—' }}
                                            </td>
                                            <td>
                                                @if($app->status === 'approved')
                                                    <span class="status-pill status-approved"> Approved</span>
                                                @elseif($app->status === 'rejected')
                                                    <span class="status-pill status-rejected"> Rejected</span>
                                                @else
                                                    <span class="status-pill status-pending"> Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="action-gap">
                                                    @if($app->status === 'approved' && in_array($app->program_type, ['PWD_Assistance', 'PWD_New', 'PWD_Renewal'], true))
                                                        @if($app->id_status === 'ready_for_pickup')
                                                            <span class="btn-action"
                                                                style="background:#dcfce7;color:#166534;border:1px solid #86efac;font-weight:800;">
                                                                🎫 ID Ready
                                                            </span>
                                                        @elseif($app->id_status === 'processing')
                                                            <form method="POST"
                                                                action="{{ route('admin.applications.mark-id-ready', $app->id) }}"
                                                                style="display:inline;">
                                                                @csrf
                                                                <button type="submit" class="btn-action btn-approve js-confirm-submit"
                                                                    data-confirm-title="Mark PWD ID ready?"
                                                                    data-confirm-message="Notify user that PWD ID is ready for pick-up?"
                                                                    data-confirm-ok="ID Ready">
                                                                    🎫 ID Ready
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form method="POST"
                                                                action="{{ route('admin.applications.validate-pwd', $app->id) }}"
                                                                style="display:inline;">
                                                                @csrf
                                                                <button type="submit" class="btn-action btn-approve js-confirm-submit"
                                                                    data-confirm-title="Validate requirements?"
                                                                    data-confirm-message="Mark this approved PWD application as validated and notify the user?"
                                                                    data-confirm-ok="Validate">
                                                                    🏆 Validate
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif

                                                    @if($app->status === 'approved' && in_array($app->program_type, ['AICS_Medical', 'AICS_Burial'], true))
                                                        @if($app->id_status === 'ready_for_pickup')
                                                            <span class="btn-action"
                                                                style="background:#dcfce7;color:#166534;border:1px solid #86efac;font-weight:800;">
                                                                🎁 Claim Ready
                                                            </span>
                                                        @elseif($app->id_status === 'processing')
                                                            <form method="POST"
                                                                action="{{ route('admin.applications.mark-id-ready', $app->id) }}"
                                                                style="display:inline;">
                                                                @csrf
                                                                <button type="submit" class="btn-action btn-view js-confirm-submit"
                                                                    data-confirm-title="Mark claim ready?"
                                                                    data-confirm-message="Notify user that AICS grant is ready for pickup?"
                                                                    data-confirm-ok="Ready">
                                                                    🎁 Claim Ready
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form method="POST"
                                                                action="{{ route('admin.applications.validate-aics', $app->id) }}"
                                                                style="display:inline;">
                                                                @csrf
                                                                <button type="submit" class="btn-action btn-view js-confirm-submit"
                                                                    data-confirm-title="Validate AICS requirements?"
                                                                    data-confirm-message="Mark this approved AICS application as validated and notify the user?"
                                                                    data-confirm-ok="Validate">
                                                                    ✅ Validate
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif

                                                    {{-- VIEW - always show --}}
                                                    <a href="{{ route('admin.view-requirement', $app->id) }}"
                                                        class="btn-action btn-view"> View</a>

                                                    {{-- DELETE / ARCHIVE --}}
                                                    <button type="button" class="btn-action"
                                                        style="background:#f8faff;color:#64748b;border:1.5px solid #e2e8f0;font-size:.76rem;"
                                                        onclick="archiveApp({{ $app->id }}, '{{ addslashes($app->full_name) }}')">
                                                        &#128193; Archive
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3">{{ $applications->appends(request()->query())->links() }}</div>
                    @else
                        <div class="empty-state">
                            <div class="empty-num">00</div>
                            <p>No applications submitted yet for <strong>{{ $municipality }}</strong>.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="panel-card" style="margin-top:28px;">
                <div class="panel-header" style="display:flex;align-items:center;justify-content:space-between;">
                    <span>&#128197; Appointments</span>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <button type="button" onclick="openArchivedAppts()"
                            style="background:rgba(255,255,255,0.12);border:1.5px solid rgba(255,255,255,0.3);color:white;border-radius:20px;padding:5px 16px;font-size:0.8rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;transition:all .2s;"
                            onmouseover="this.style.background='rgba(255,255,255,0.22)'"
                            onmouseout="this.style.background='rgba(255,255,255,0.12)'">
                            &#128193; Archived (<span id="archivedApptCount">0</span>)
                        </button>
                        <span id="apptCount"
                            style="font-size:0.8rem;font-weight:600;background:rgba(255,255,255,0.15);padding:4px 12px;border-radius:20px;">Loading&hellip;</span>
                    </div>
                </div>
                <div
                    style="padding:14px 16px;border-bottom:1px solid #e2e8f0;background:#f8faff;display:grid;grid-template-columns:2fr 1fr 1fr;gap:10px;">
                    <input id="apptSearchInput" type="text" placeholder="Search applicant, email, notes..."
                        class="form-control" style="font-size:.84rem;border-radius:10px;border:1px solid #c7d6f5;">
                    <select id="apptStatusFilter" class="form-select"
                        style="font-size:.84rem;border-radius:10px;border:1px solid #c7d6f5;">
                        <option value="">All status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="validated">Validated</option>
                        <option value="rejected">Rejected</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                    <select id="apptProgramFilter" class="form-select"
                        style="font-size:.84rem;border-radius:10px;border:1px solid #c7d6f5;">
                        <option value="">All programs</option>
                        <option value="Solo_Parent">Solo Parent</option>
                        <option value="AICS_Medical">AICS Medical</option>
                        <option value="AICS_Burial">AICS Burial</option>
                    </select>
                </div>
                <div class="p-3" id="apptTableWrap">
                    <div style="text-align:center;padding:30px;color:#94a3b8;font-size:.9rem;">Loading
                        appointments&hellip;</div>
                </div>
            </div>

        </div>
    </div>


    {{-- Reject modal --}}
    <div id="rejectModal"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
        <div
            style="background:white;border-radius:16px;padding:28px;max-width:440px;width:90%;box-shadow:0 8px 40px rgba(0,0,0,.2);">
            <h5 style="font-weight:800;margin-bottom:14px;color:#1e293b;">❌ Reject Appointment</h5>
            <p style="font-size:.85rem;color:#64748b;margin-bottom:14px;">Provide a reason for rejection. This will be
                sent to the user via email.</p>
            <textarea id="rejectNotes" rows="3" class="form-control" placeholder="Reason for rejection…"
                style="border-radius:10px;margin-bottom:16px;"></textarea>
            <div style="display:flex;gap:10px;">
                <button onclick="submitReject()"
                    style="background:linear-gradient(135deg,#dc3545,#b91c1c);color:white;border:none;border-radius:10px;padding:10px 24px;font-weight:700;cursor:pointer;flex:1;">Send
                    Rejection</button>
                <button onclick="closeRejectModal()"
                    style="background:#f1f5f9;color:#374151;border:none;border-radius:10px;padding:10px 20px;font-weight:600;cursor:pointer;">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        var rejectTargetId = null;
        var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        var _allAppointments = [];
        var _apptFilterTimer = null;

        function normalizeText(v) {
            return String(v || '').toLowerCase();
        }

        function applyAppointmentFilters() {
            const term = normalizeText(document.getElementById('apptSearchInput')?.value);
            const status = normalizeText(document.getElementById('apptStatusFilter')?.value);
            const program = normalizeText(document.getElementById('apptProgramFilter')?.value);
            const filtered = _allAppointments.filter(a => {
                const hay = [
                    a.user_name,
                    a.user_email,
                    a.user_notes,
                    a.program_type,
                    a.status,
                    a.date,
                    a.time,
                ].map(normalizeText).join(' ');
                if (term && !hay.includes(term)) return false;
                if (status && normalizeText(a.status) !== status) return false;
                if (program && normalizeText(a.program_type) !== program) return false;
                return true;
            });
            renderAppointments(filtered);
        }

        function loadAppointments() {
            fetch('/admin/appointments', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(data => {
                    _allAppointments = Array.isArray(data) ? data : [];
                    applyAppointmentFilters();
                })
                .catch(e => {
                    document.getElementById('apptTableWrap').innerHTML = '<div style="padding:20px;color:#dc3545;">Failed to load appointments.</div>';
                });
        }

        function renderAppointments(data) {
            document.getElementById('apptCount').textContent = data.length + ' Total';
            if (!data.length) {
                document.getElementById('apptTableWrap').innerHTML = '<div style="text-align:center;padding:30px;color:#94a3b8;font-size:.9rem;">No matching appointments found.</div>';
                return;
            }

            let rows = data.map(a => {
                const progLabels = {
                    'Solo_Parent': { label: 'Solo Parent', color: '#4f46e5', bg: '#ede9fe' },
                    'AICS_Medical': { label: 'AICS Medical', color: '#0891b2', bg: '#e0f2fe' },
                    'AICS_Burial': { label: 'AICS Burial', color: '#7c3aed', bg: '#f5f3ff' },
                };
                const prog = progLabels[a.program_type] || { label: a.program_type.replace('_', ' '), color: '#64748b', bg: '#f1f5f9' };
                const progBadge = `<span style="background:${prog.bg};color:${prog.color};border-radius:20px;padding:2px 10px;font-size:.72rem;font-weight:700;white-space:nowrap;">${prog.label}</span>`;

                let actions = '';
                const isSoloParent = a.program_type === 'Solo_Parent';

                if (a.status === 'pending') {
                    actions = `
                            <button onclick="confirmAppt(${a.id})" style="background:#d4edda;color:#155724;border:none;border-radius:8px;padding:5px 14px;font-size:.78rem;font-weight:700;cursor:pointer;margin-right:6px;">✅ Approve</button>
                            <button onclick="openRejectModal(${a.id})" style="background:#f8d7da;color:#721c24;border:none;border-radius:8px;padding:5px 14px;font-size:.78rem;font-weight:700;cursor:pointer;">❌ Reject</button>
                        `;
                } else if (a.status === 'confirmed') {
                    if (isSoloParent) {
                        actions = `
                                <button onclick="validateAppt(${a.id})" style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border:none;border-radius:8px;padding:5px 14px;font-size:.78rem;font-weight:700;cursor:pointer;margin-right:6px;">🏆 Eligible</button>
                                <button onclick="openRejectModal(${a.id})" style="background:#f8d7da;color:#721c24;border:none;border-radius:8px;padding:5px 14px;font-size:.78rem;font-weight:700;cursor:pointer;">❌ Reject</button>
                            `;
                    } else {
                        actions = `<span style="background:#d4edda;color:#155724;border-radius:8px;padding:5px 14px;font-size:.78rem;font-weight:700;">✅ Approved</span>`;
                    }
                } else if (a.status === 'validated') {
                    if (a.id_status === 'ready_for_pickup') {
                        actions = `<span style="background:#d4edda;color:#155724;border-radius:8px;padding:5px 14px;font-size:.78rem;font-weight:700;">🎫 ID Ready</span>`;
                    } else if (a.id_status === 'processing') {
                        actions = `<button onclick="markIdReady(${a.solo_parent_app_id},'${encodeURIComponent(a.user_name)}')" style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border:none;border-radius:8px;padding:5px 14px;font-size:.78rem;font-weight:700;cursor:pointer;">🎫 ID Ready</button>`;
                    } else {
                        actions = `<span style="background:#fff7ed;color:#9a3412;border-radius:8px;padding:5px 14px;font-size:.78rem;font-weight:700;">⏳ Waiting Requirements Validation</span>`;
                    }
                } else {
                    actions = '<span style="color:#94a3b8;font-size:.78rem;">—</span>';
                }
                const noteText = (a.user_notes || '').trim();
                const notesCell = noteText
                    ? `<div style="max-width:230px;white-space:normal;line-height:1.35;background:#eef3ff;border:1px solid #c7d6f5;color:#1e3a8a;border-radius:10px;padding:6px 9px;font-size:.76rem;font-weight:600;" title="${noteText}">${noteText}</div>`
                    : '<span style="color:#94a3b8;font-size:.78rem;">—</span>';

                return `<tr>
                        <td style="font-weight:700;">${a.user_name}<br><small style="color:#94a3b8;font-size:.72rem;">${a.user_email}</small></td>
                        <td>${progBadge}</td>
                        <td>${a.date}<br><small style="color:#64748b;">${a.day}</small></td>
                        <td>${a.time}</td>
                        <td>${a.interview_type}</td>
                        <td>${a.status_badge}</td>
                        <td>${notesCell}</td>
                        <td><div style="display:flex;flex-wrap:wrap;gap:5px;align-items:center;">${actions}<button onclick="archiveAppt(${a.id},'${encodeURIComponent(a.user_name)}')" style="background:#f8faff;color:#64748b;border:1.5px solid #e2e8f0;border-radius:8px;padding:4px 12px;font-size:.76rem;font-weight:700;cursor:pointer;">&#128193; Archive</button></div></td>
                    </tr>`;
            }).join('');

            document.getElementById('apptTableWrap').innerHTML = `
                    <div class="table-responsive">
                    <table class="table mb-0" style="font-size:.85rem;">
                        <thead><tr>
                            <th>Applicant</th><th>Program</th><th>Date</th><th>Time</th><th>Type</th><th>Status</th><th>Notes</th><th>Actions</th>
                        </tr></thead>
                        <tbody>${rows}</tbody>
                    </table>
                    </div>`;
        }

        function confirmAppt(id) {
            uiConfirm(
                'Approve appointment?',
                'Approve this appointment? An email and bell notification will be sent to the user.',
                { okText: 'Approve', cancelText: 'Cancel' }
            ).then(ok => {
                if (!ok) return;
                showLoading('Approving Appointment', 'Sending notification and updating status...');
                fetch(`/admin/appointments/${id}/confirm`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ admin_notes: '' })
                }).then(r => r.json()).then(d => {
                    uiToast(d.message || 'Confirmed.');
                    loadAppointments();
                }).catch(() => uiToast('Error confirming appointment.', 'Error'))
                    .finally(() => hideLoading());
            });
        }

        function validateAppt(id) {
            uiConfirm(
                'Mark as eligible?',
                'Validate this appointment?\n\nThis will:\n- Mark the applicant as eligible for Solo Parent ID\n- Create their requirements submission form\n- Send them an email notification',
                { okText: 'Eligible', cancelText: 'Cancel' }
            ).then(ok => {
                if (!ok) return;
                showLoading('Marking Eligible', 'Preparing requirements and sending notification...');
                fetch(`/admin/appointments/${id}/validate`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({})
                }).then(r => r.json()).then(d => {
                    if (d.success) {
                        uiToast(d.message || 'Validated.');
                        loadAppointments();
                    } else {
                        uiToast(d.message || 'Error validating appointment.', 'Error');
                    }
                }).catch(() => uiToast('Error validating appointment.', 'Error'))
                    .finally(() => hideLoading());
            });
        }

        function openRejectModal(id) {
            rejectTargetId = id;
            document.getElementById('rejectNotes').value = '';
            document.getElementById('rejectModal').style.display = 'flex';
        }
        function closeRejectModal() {
            document.getElementById('rejectModal').style.display = 'none';
            rejectTargetId = null;
        }
        function submitReject() {
            const notes = document.getElementById('rejectNotes').value.trim();
            if (!notes) { alert('Please enter a reason for rejection.'); return; }
            showLoading('Rejecting Appointment', 'Sending rejection notification...');
            fetch(`/admin/appointments/${rejectTargetId}/reject`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ admin_notes: notes })
            }).then(r => r.json()).then(d => {
                closeRejectModal();
                alert(d.message);
                loadAppointments();
            }).catch(() => alert('Error rejecting appointment.'))
                .finally(() => hideLoading());
        }

        // Load on page ready
        document.addEventListener('DOMContentLoaded', function () {
            loadAppointments();
            const wireFilter = () => {
                if (_apptFilterTimer) clearTimeout(_apptFilterTimer);
                _apptFilterTimer = setTimeout(applyAppointmentFilters, 180);
            };
            document.getElementById('apptSearchInput')?.addEventListener('input', wireFilter);
            document.getElementById('apptStatusFilter')?.addEventListener('change', applyAppointmentFilters);
            document.getElementById('apptProgramFilter')?.addEventListener('change', applyAppointmentFilters);
        });
    </script>

    <div class="footer-strip">
        MSWDO &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    @include('components.admin-settings-modal')
    @include('components.admin-chat-modal')

    @include('components.admin-notification-modal')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DECLINE MODAL -->
    <div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px;border:none;overflow:hidden;">
                <div class="modal-header"
                    style="background:var(--primary-gradient);color:white;border:none;padding:18px 24px;">
                    <h5 class="modal-title" id="declineModalLabel" style="font-weight:800;">Decline Application</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="declineForm" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <div class="modal-body" style="padding:24px;">
                        <p style="color:#475569;font-size:0.9rem;margin-bottom:14px;">
                            You are declining the application of <strong id="declineApplicantName"></strong>.
                            Please select a reason and add additional comments if necessary.
                        </p>
                        <label class="form-label" style="font-size:0.85rem;font-weight:700;color:#1e293b;">
                            Rejection Reason <span style="color:#C41E24;">*</span>
                        </label>
                        <select id="rejectionReason" class="form-select mb-3"
                            style="border-radius:10px;border:1.5px solid #C7D6F5;font-size:0.88rem;">
                            <option value="">-- Select a reason --</option>
                            <option value="Incomplete Documents">Incomplete Documents</option>
                            <option value="Does not meet eligibility requirements">Does not meet eligibility
                                requirements</option>
                            <option value="Invalid or expired documents">Invalid or expired documents</option>
                            <option value="Incorrect information provided">Incorrect information provided</option>
                            <option value="Duplicate application">Duplicate application</option>
                            <option value="Other">Other (specify below)</option>
                        </select>
                        <label class="form-label" style="font-size:0.85rem;font-weight:700;color:#1e293b;"
                            for="declineRemarks">
                            Additional Comments <span
                                style="font-size:0.75rem;font-weight:400;color:#64748b;">(Optional)</span>
                        </label>
                        <textarea name="admin_remarks" id="declineRemarks" class="form-control" rows="3"
                            placeholder="Add any additional details or instructions..."
                            style="border-radius:10px;border:1.5px solid #C7D6F5;font-size:0.88rem;resize:none;"></textarea>
                        <div id="remarksError" style="color:#C41E24;font-size:0.8rem;margin-top:6px;display:none;">
                            Please select a rejection reason.
                        </div>
                    </div>
                    <div class="modal-footer" style="border:none;padding:16px 24px 20px;gap:8px;">
                        <button type="button" class="btn" data-bs-dismiss="modal"
                            style="border-radius:8px;font-weight:700;font-size:0.85rem;border:1.5px solid #C7D6F5;padding:8px 20px;">
                            Cancel
                        </button>
                        <button type="submit" id="confirmDeclineBtn" class="btn"
                            style="background:linear-gradient(135deg,#C41E24,#8B0000);color:white;border-radius:8px;font-weight:700;font-size:0.85rem;border:none;padding:8px 22px;">
                            Confirm Decline
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Wire decline modal to the correct application
        document.getElementById('declineModal').addEventListener('show.bs.modal', function (event) {
            var btn = event.relatedTarget;
            var id = btn.getAttribute('data-id');
            var name = btn.getAttribute('data-name');
            document.getElementById('declineApplicantName').textContent = name;
            document.getElementById('rejectionReason').value = '';
            document.getElementById('declineRemarks').value = '';
            document.getElementById('remarksError').style.display = 'none';
            document.getElementById('declineForm').action = '/admin/applications/' + id + '/status';
        });

        // Auto-fill rejection reason into additional comments
        document.getElementById('rejectionReason').addEventListener('change', function () {
            const reason = this.value;
            const remarksField = document.getElementById('declineRemarks');
            const remarksLabel = document.querySelector('label[for="declineRemarks"]');

            if (reason && reason !== 'Other') {
                // Auto-fill with selected reason
                remarksField.value = reason;
                remarksField.placeholder = 'The rejection reason has been auto-filled. You can add more details if needed.';
                // Make it optional
                remarksLabel.innerHTML = 'Additional Comments <span style="font-size:0.75rem;font-weight:400;color:#64748b;">(Optional)</span>';
            } else if (reason === 'Other') {
                // Clear and make required
                remarksField.value = '';
                remarksField.placeholder = 'Please specify the reason for rejection...';
                remarksLabel.innerHTML = 'Additional Comments <span style="color:#C41E24;">*</span>';
            } else {
                // Reset
                remarksField.value = '';
                remarksField.placeholder = 'Add any additional details or instructions...';
                remarksLabel.innerHTML = 'Additional Comments <span style="font-size:0.75rem;font-weight:400;color:#64748b;">(Optional)</span>';
            }
        });

        // Client-side guard: require rejection reason before submit
        document.getElementById('declineForm').addEventListener('submit', function (e) {
            var reason = document.getElementById('rejectionReason').value;
            var comments = document.getElementById('declineRemarks').value.trim();
            var errorDiv = document.getElementById('remarksError');

            if (!reason) {
                e.preventDefault();
                errorDiv.textContent = 'Please select a rejection reason.';
                errorDiv.style.display = 'block';
                document.getElementById('rejectionReason').focus();
                return;
            }

            // If "Other" is selected, require additional comments
            if (reason === 'Other' && !comments) {
                e.preventDefault();
                errorDiv.textContent = 'Please specify the reason in Additional Comments when selecting "Other".';
                errorDiv.style.display = 'block';
                document.getElementById('declineRemarks').focus();
                return;
            }

            // For non-Other reasons, use the comments as final remarks (already auto-filled with reason)
            var finalRemarks = comments || reason;
            document.getElementById('declineRemarks').value = finalRemarks;
        });
    </script>

    <script>
        async function archiveApp(id, name) {
            const ok = await uiConfirm(
                'Archive application?',
                'Archive the application of \"' + name + '\"?\\n\\nThis will move it to the archive. You can restore it later.',
                { okText: 'Archive', cancelText: 'Cancel' }
            );
            if (!ok) return;
            showLoading('Archiving Application', 'Moving record to archive...');
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/admin/applications/' + id + '/archive';
            form.innerHTML = '<input type="hidden" name="_token" value="' + document.querySelector('meta[name=csrf-token]').content + '">' +
                '<input type="hidden" name="_method" value="DELETE">';
            document.body.appendChild(form);
            form.submit();
        }

        function deleteAppDirect(id, name) {
            uiConfirm(
                'Permanently delete?',
                'PERMANENTLY DELETE the application of \"' + name + '\"?\n\n⚠ This action CANNOT be undone. The record will be gone forever.',
                { okText: 'Delete', cancelText: 'Cancel' }
            ).then(ok => {
                if (!ok) return;
                showLoading('Deleting Application', 'Removing record permanently...');
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/admin/applications/' + id + '/direct-delete';
                form.innerHTML = '<input type="hidden" name="_token" value="' + document.querySelector('meta[name=csrf-token]').content + '">' +
                    '<input type="hidden" name="_method" value="DELETE">';
                document.body.appendChild(form);
                form.submit();
            });
        }
    </script>

    {{-- ═══════════════════ ARCHIVE MODAL ═══════════════════════════ --}}
    <div id="archiveModal"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:10000;align-items:center;justify-content:center;"
        onclick="if(event.target===this) this.style.display='none'">
        <div
            style="background:white;width:90%;max-width:860px;border-radius:18px;max-height:82vh;display:flex;flex-direction:column;box-shadow:0 8px 48px rgba(44,62,143,.22);animation:fadeInScale .25s ease;">
            {{-- Modal header --}}
            <div
                style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);padding:18px 26px;border-radius:18px 18px 0 0;display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <div style="color:white;font-weight:800;font-size:1rem;">&#128193; Archived Applications</div>
                    <div style="color:rgba(255,255,255,.7);font-size:.8rem;margin-top:2px;">
                        {{ isset($archivedApplications) ? $archivedApplications->count() : 0 }} archived record(s)
                        &mdash; restore or permanently delete
                    </div>
                </div>
                <button onclick="document.getElementById('archiveModal').style.display='none'"
                    style="background:rgba(255,255,255,.15);border:none;color:white;border-radius:50%;width:34px;height:34px;font-size:1.1rem;cursor:pointer;line-height:1;">
                    &times;
                </button>
            </div>
            {{-- Modal body --}}
            <div style="overflow-y:auto;padding:0;">
                @if(isset($archivedApplications) && $archivedApplications->count() > 0)
                    <table class="table mb-0" style="font-size:.85rem;">
                        <thead>
                            <tr>
                                <th>Applicant</th>
                                <th>Program</th>
                                <th>Barangay</th>
                                <th>Date Filed</th>
                                <th>Archived On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($archivedApplications as $app)
                                <tr style="background:#f8faff;">
                                    <td>
                                        <div style="font-weight:700;color:#475569;">{{ $app->full_name }}</div>
                                        <div style="font-size:.72rem;color:#94a3b8;">{{ $app->contact_number }}</div>
                                    </td>
                                    <td>
                                        <span class="prog-tag" style="background:#f1f5f9;color:#64748b;">
                                            {{ str_replace('_', ' ', $app->program_type) }}
                                        </span>
                                    </td>
                                    <td style="color:#94a3b8;">{{ $app->barangay ?: '—' }}</td>
                                    <td style="color:#94a3b8;font-size:.8rem;">
                                        {{ $app->application_date ? \Carbon\Carbon::parse($app->application_date)->format('M j, Y') : '—' }}
                                    </td>
                                    <td style="color:#94a3b8;font-size:.8rem;">
                                        {{ $app->deleted_at ? \Carbon\Carbon::parse($app->deleted_at)->format('M j, Y') : '—' }}
                                    </td>
                                    <td>
                                        <div class="action-gap">
                                            <form method="POST" action="{{ route('admin.applications.restore', $app->id) }}"
                                                style="display:inline;">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn-action btn-approve js-confirm-submit"
                                                    data-confirm-title="Restore application?"
                                                    data-confirm-message="Restore this application?" data-confirm-ok="Restore"
                                                    style="font-size:.76rem;">
                                                    &#8593; Restore
                                                </button>
                                            </form>
                                            <form method="POST"
                                                action="{{ route('admin.applications.force-delete', $app->id) }}"
                                                style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-action btn-decline js-confirm-submit"
                                                    data-confirm-title="Permanently delete?"
                                                    data-confirm-message="Permanently delete this application? This cannot be undone."
                                                    data-confirm-ok="Delete" style="font-size:.76rem;">
                                                    &#10006; Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="text-align:center;padding:50px 24px;">
                        <div style="font-size:2.5rem;">&#128193;</div>
                        <p style="color:#94a3b8;margin-top:10px;">No archived applications yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeInScale {
            from {
                transform: scale(.95);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>


    <script>
        const _markReadyInFlight = {};
        function markIdReady(appId, encodedName) {
            if (_markReadyInFlight[appId]) return;
            const name = decodeURIComponent(encodedName);
            uiConfirm(
                'Mark ID ready?',
                'Mark Solo Parent ID of \"' + name + '\" as ready for pickup?\\n\\nThis will send a pickup notification email to the user.',
                { okText: 'Mark Ready', cancelText: 'Cancel' }
            ).then(ok => {
                if (!ok) return;
                _markReadyInFlight[appId] = true;
                showLoading('Marking ID Ready', 'Finalizing release status and notifying user...');
                fetch('/admin/applications/' + appId + '/mark-id-ready', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                }).then(r => r.json()).then(d => {
                    uiToast(d.message || 'Updated.');
                    loadAppointments();
                }).finally(() => {
                    _markReadyInFlight[appId] = false;
                    hideLoading();
                }).catch(() => uiToast('Failed to mark ID as ready.', 'Error'));
            });
        }

        function archiveAppt(id, name) {
            const displayName = decodeURIComponent(name);
            uiConfirm(
                'Archive appointment?',
                'Archive the appointment of \"' + displayName + '\"?\\n\\nThis will move it to the archive.',
                { okText: 'Archive', cancelText: 'Cancel' }
            ).then(ok => {
                if (!ok) return;
                showLoading('Archiving Appointment', 'Moving appointment to archive...');
                fetch('/admin/appointments/' + id + '/archive', {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                }).then(r => r.json()).then(d => {
                    uiToast(d.message || 'Archived.');
                    loadAppointments();
                    loadArchivedAppts();
                }).catch(() => uiToast('Archive failed.', 'Error'))
                    .finally(() => hideLoading());
            });
        }

        function openArchivedAppts() {
            document.getElementById('apptArchiveModal').style.display = 'flex';
            loadArchivedAppts();
        }

        function loadArchivedAppts() {
            fetch('/admin/appointments/archived', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => r.json()).then(data => {
                    document.getElementById('archivedApptCount').textContent = data.length;
                    const wrap = document.getElementById('archivedApptBody');
                    if (!data.length) {
                        wrap.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:30px;color:#94a3b8;">No archived appointments.</td></tr>';
                        return;
                    }
                    const progLabels = {
                        'Solo_Parent': { label: 'Solo Parent', color: '#4f46e5', bg: '#ede9fe' },
                        'AICS_Medical': { label: 'AICS Medical', color: '#0891b2', bg: '#e0f2fe' },
                        'AICS_Burial': { label: 'AICS Burial', color: '#7c3aed', bg: '#f5f3ff' },
                    };
                    wrap.innerHTML = data.map(a => {
                        const prog = progLabels[a.program_type] || { label: (a.program_type || '').replace('_', ' '), color: '#64748b', bg: '#f1f5f9' };
                        const progBadge = `<span style="background:${prog.bg};color:${prog.color};border-radius:20px;padding:2px 9px;font-size:.7rem;font-weight:700;">${prog.label}</span>`;
                        return `<tr style="background:#f8faff;">
                <td><div style="font-weight:700;color:#475569;">${a.user_name}</div><div style="font-size:.72rem;color:#94a3b8;">${a.user_email}</div></td>
                <td>${progBadge}</td>
                <td style="font-size:.82rem;color:#94a3b8;">${a.date}<br><small>${a.day}</small></td>
                <td style="font-size:.82rem;">${a.time}</td>
                <td style="font-size:.82rem;">${a.interview_type}</td>
                <td>${a.status_badge}</td>
                <td style="font-size:.82rem;color:#94a3b8;">${a.archived_at}</td>
                <td>
                    <div style="display:flex;gap:5px;">
                        <button onclick="restoreAppt(${a.id})" style="background:#d4edda;color:#155724;border:none;border-radius:8px;padding:4px 12px;font-size:.76rem;font-weight:700;cursor:pointer;">&#8593; Restore</button>
                        <button onclick="forceDeleteAppt(${a.id})" style="background:#f8d7da;color:#721c24;border:none;border-radius:8px;padding:4px 12px;font-size:.76rem;font-weight:700;cursor:pointer;">&#10006; Delete</button>
                    </div>
                </td>
            </tr>`;
                    }).join('');
                });
        }

        function restoreAppt(id) {
            uiConfirm('Restore appointment?', 'Restore this appointment?', { okText: 'Restore', cancelText: 'Cancel' })
                .then(ok => {
                    if (!ok) return;
                    showLoading('Restoring Appointment', 'Please wait...');
                    fetch('/admin/appointments/' + id + '/restore', {
                        method: 'PATCH',
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    }).then(r => r.json()).then(d => { uiToast(d.message || 'Restored.'); loadArchivedAppts(); loadAppointments(); })
                        .catch(() => uiToast('Restore failed.', 'Error'))
                        .finally(() => hideLoading());
                });
        }

        function forceDeleteAppt(id) {
            uiConfirm(
                'Permanently delete?',
                'Permanently delete this appointment? This CANNOT be undone.',
                { okText: 'Delete', cancelText: 'Cancel' }
            ).then(ok => {
                if (!ok) return;
                showLoading('Deleting Appointment', 'Removing archived appointment...');
                fetch('/admin/appointments/' + id + '/force-delete', {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                }).then(r => r.json()).then(d => { uiToast(d.message || 'Deleted.'); loadArchivedAppts(); })
                    .catch(() => uiToast('Delete failed.', 'Error'))
                    .finally(() => hideLoading());
            });
        }
    </script>

    {{-- ═══════════════ ARCHIVED APPOINTMENTS MODAL ═══════════════════════════ --}}
    <div id="apptArchiveModal"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:10001;align-items:center;justify-content:center;"
        onclick="if(event.target===this) this.style.display='none'">
        <div
            style="background:white;width:90%;max-width:900px;border-radius:18px;max-height:82vh;display:flex;flex-direction:column;box-shadow:0 8px 48px rgba(44,62,143,.22);animation:fadeInScale .25s ease;">
            <div
                style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);padding:18px 26px;border-radius:18px 18px 0 0;display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <div style="color:white;font-weight:800;font-size:1rem;">&#128193; Archived Appointments</div>
                    <div style="color:rgba(255,255,255,.7);font-size:.8rem;margin-top:2px;">Restore or permanently
                        delete archived appointments</div>
                </div>
                <button onclick="document.getElementById('apptArchiveModal').style.display='none'"
                    style="background:rgba(255,255,255,.15);border:none;color:white;border-radius:50%;width:34px;height:34px;font-size:1.1rem;cursor:pointer;line-height:1;">&times;</button>
            </div>
            <div style="overflow-y:auto;padding:0;">
                <table class="table mb-0" style="font-size:.85rem;">
                    <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Program</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Archived On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="archivedApptBody">
                        <tr>
                            <td colspan="8" style="text-align:center;padding:30px;color:#94a3b8;">Loading&hellip;</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ═══════════════ CUSTOM CONFIRM / TOAST UI ═══════════════════════════ --}}
    <div id="uiConfirmBackdrop" class="ui-modal-backdrop" aria-hidden="true">
        <div class="ui-modal" role="dialog" aria-modal="true" aria-labelledby="uiConfirmTitle">
            <div class="ui-modal-header">
                <h6 id="uiConfirmTitle" class="ui-modal-title">Confirm</h6>
                <button type="button" class="ui-modal-close" onclick="uiConfirmClose(false)"
                    aria-label="Close">&times;</button>
            </div>
            <div class="ui-modal-body">
                <p id="uiConfirmMessage"></p>
            </div>
            <div class="ui-modal-footer">
                <button type="button" id="uiConfirmCancel" class="ui-btn ui-btn-secondary"
                    onclick="uiConfirmClose(false)">Cancel</button>
                <button type="button" id="uiConfirmOk" class="ui-btn ui-btn-primary"
                    onclick="uiConfirmClose(true)">OK</button>
            </div>
        </div>
    </div>
    <div id="uiToast" class="ui-toast" role="status" aria-live="polite">
        <div id="uiToastTitle" style="font-weight:900;font-size:.88rem;"></div>
        <small id="uiToastMsg"></small>
    </div>

    <script>
        // Promise-based confirm dialog to replace browser confirm()
        let __uiConfirmResolve = null;
        function uiConfirm(title, message, opts = {}) {
            const backdrop = document.getElementById('uiConfirmBackdrop');
            const t = document.getElementById('uiConfirmTitle');
            const m = document.getElementById('uiConfirmMessage');
            const ok = document.getElementById('uiConfirmOk');
            const cancel = document.getElementById('uiConfirmCancel');

            t.textContent = title || 'Confirm';
            m.textContent = (message || '').replace(/\\n/g, '\n');
            ok.textContent = opts.okText || 'OK';
            cancel.textContent = opts.cancelText || 'Cancel';

            backdrop.style.display = 'flex';
            backdrop.classList.add('show');
            backdrop.setAttribute('aria-hidden', 'false');

            ok.focus({ preventScroll: true });

            return new Promise(resolve => {
                __uiConfirmResolve = resolve;
            });
        }

        function uiConfirmClose(result) {
            const backdrop = document.getElementById('uiConfirmBackdrop');
            backdrop.classList.remove('show');
            backdrop.style.display = 'none';
            backdrop.setAttribute('aria-hidden', 'true');
            if (typeof __uiConfirmResolve === 'function') {
                __uiConfirmResolve(!!result);
            }
            __uiConfirmResolve = null;
        }

        // Simple toast for success/error messages (replaces alert())
        let __uiToastTimer = null;
        function uiToast(message, title = 'MSWDO Admin') {
            const toast = document.getElementById('uiToast');
            const t = document.getElementById('uiToastTitle');
            const m = document.getElementById('uiToastMsg');
            t.textContent = title;
            m.textContent = message || '';
            toast.classList.add('show');
            if (__uiToastTimer) clearTimeout(__uiToastTimer);
            __uiToastTimer = setTimeout(() => toast.classList.remove('show'), 3200);
        }

        function showLoading(title = 'Processing Request', subtitle = 'Please wait while we update records.') {
            const backdrop = document.getElementById('uiLoadingBackdrop');
            if (!backdrop) return;
            const titleEl = backdrop.querySelector('.ui-loading-title');
            const subEl = backdrop.querySelector('.ui-loading-sub');
            if (titleEl) titleEl.textContent = title;
            if (subEl) subEl.textContent = subtitle;
            backdrop.style.display = 'flex';
            backdrop.setAttribute('aria-hidden', 'false');
        }

        function hideLoading() {
            const backdrop = document.getElementById('uiLoadingBackdrop');
            if (!backdrop) return;
            backdrop.style.display = 'none';
            backdrop.setAttribute('aria-hidden', 'true');
        }

        function resolveLoadingCopy(title, okText, btnText) {
            const hay = `${title || ''} ${okText || ''} ${btnText || ''}`.toLowerCase();
            if (hay.includes('validate aics')) return ['Validating AICS Requirements', 'Checking files and notifying the applicant...'];
            if (hay.includes('validate') && hay.includes('pwd')) return ['Validating PWD Requirements', 'Updating status and sending notification...'];
            if (hay.includes('mark pwd id ready') || (hay.includes('id ready') && hay.includes('pwd'))) {
                return ['Marking PWD ID Ready', 'Finalizing release status and notifying the user...'];
            }
            if (hay.includes('claim ready')) return ['Marking Claim Ready', 'Finalizing grant release and notifying the user...'];
            if (hay.includes('id ready')) return ['Marking ID Ready', 'Finalizing release status and notifying the user...'];
            if (hay.includes('restore')) return ['Restoring Record', 'Bringing archived record back to active list...'];
            if (hay.includes('permanently delete') || hay.includes('delete')) return ['Deleting Record', 'Removing record permanently...'];
            if (hay.includes('archive')) return ['Archiving Record', 'Moving record to archive...'];
            if (hay.includes('approve')) return ['Approving Request', 'Applying approval and sending updates...'];
            if (hay.includes('eligible')) return ['Marking Eligible', 'Preparing requirements and notifying user...'];
            return ['Processing Request', 'Applying changes...'];
        }

        // Close dialog when clicking backdrop
        document.getElementById('uiConfirmBackdrop')?.addEventListener('click', function (e) {
            if (e.target === this) uiConfirmClose(false);
        });

        // Replace remaining inline confirms on form-submit buttons
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.js-confirm-submit');
            if (!btn) return;
            // If button already handled elsewhere, ignore.
            if (btn.dataset.__confirmWired === '1') return;
            e.preventDefault();
            const form = btn.closest('form');
            if (!form) return;
            const title = btn.getAttribute('data-confirm-title') || 'Confirm';
            const message = btn.getAttribute('data-confirm-message') || 'Continue?';
            const okText = btn.getAttribute('data-confirm-ok') || 'OK';
            const cancelText = btn.getAttribute('data-confirm-cancel') || 'Cancel';
            const btnText = (btn.textContent || '').trim();
            uiConfirm(title, message, { okText, cancelText }).then(ok => {
                if (!ok) return;
                btn.disabled = true;
                btn.style.opacity = '.65';
                btn.style.cursor = 'not-allowed';
                const [loadingTitle, loadingSub] = resolveLoadingCopy(title, okText, btnText);
                showLoading(loadingTitle, loadingSub);
                form.submit();
            });
        }, true);
        // ESC closes dialog
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const backdrop = document.getElementById('uiConfirmBackdrop');
                if (backdrop && backdrop.style.display === 'flex') uiConfirmClose(false);
            }
        });
    </script>

</body>

</html>