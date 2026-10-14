<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Applications - MSWDO Admin</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo_mswdo.jpg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
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
            white-space: nowrap;
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

        /* ==========================================================================
           STANDARDIZED ACTION BUTTON DESIGN SYSTEM (MSWDO)
           ========================================================================== */
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            height: 32px;
            min-height: 32px;
            padding: 0 12px;
            font-family: 'Inter', sans-serif;
            font-size: 0.78rem;
            font-weight: 700;
            line-height: 1;
            white-space: nowrap;
            border-radius: 8px;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none !important;
            transition: all 0.18s ease;
            user-select: none;
            vertical-align: middle;
            box-sizing: border-box;
        }

        .btn-action:focus-visible {
            outline: 2px solid var(--primary-blue);
            outline-offset: 2px;
        }

        /* 1. Primary Action (Primary Blue / Gradient) - Key progression steps like Mark ID Ready, Mark Eligible */
        .btn-action-primary,
        .btn-action.btn-view,
        .btn-action.btn-primary-action {
            background: var(--primary-gradient);
            color: #ffffff !important;
            border-color: transparent;
            box-shadow: 0 2px 6px rgba(44, 62, 143, 0.18);
        }
        .btn-action-primary:hover,
        .btn-action.btn-view:hover,
        .btn-action.btn-primary-action:hover {
            background: linear-gradient(135deg, #233275 0%, #131f45 100%);
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(44, 62, 143, 0.3);
            transform: translateY(-1px);
        }
        .btn-action-primary:active,
        .btn-action.btn-view:active,
        .btn-action.btn-primary-action:active {
            transform: translateY(0);
        }

        /* 2. Success / Approve Action - Green */
        .btn-action-success,
        .btn-action.btn-approve,
        .btn-action.btn-success-action {
            background: #d4edda;
            color: #155724 !important;
            border-color: #c3e6cb;
        }
        .btn-action-success:hover,
        .btn-action.btn-approve:hover,
        .btn-action.btn-success-action:hover {
            background: #28a745;
            color: #ffffff !important;
            border-color: #28a745;
            box-shadow: 0 3px 10px rgba(40, 167, 69, 0.25);
            transform: translateY(-1px);
        }
        .btn-action-success:active,
        .btn-action.btn-approve:active,
        .btn-action.btn-success-action:active {
            transform: translateY(0);
        }

        /* 3. Danger / Reject / Cancel Action - Red */
        .btn-action-danger,
        .btn-action.btn-decline,
        .btn-action.btn-danger-action {
            background: #FCE8E8;
            color: #C41E24 !important;
            border-color: #f5c2c7;
        }
        .btn-action-danger:hover,
        .btn-action.btn-decline:hover,
        .btn-action.btn-danger-action:hover {
            background: #C41E24;
            color: #ffffff !important;
            border-color: #C41E24;
            box-shadow: 0 3px 10px rgba(196, 30, 36, 0.25);
            transform: translateY(-1px);
        }
        .btn-action-danger:active,
        .btn-action.btn-decline:active,
        .btn-action.btn-danger-action:active {
            transform: translateY(0);
        }

        /* 4. Secondary / Info Action - Soft Blue / Indigo (View Documents, View Notes, Reschedule) */
        .btn-action-secondary,
        .btn-action.btn-secondary-action,
        .btn-action.btn-view-action {
            background: #eef3ff;
            color: #1e3a8a !important;
            border-color: #c7d6f5;
        }
        .btn-action-secondary:hover,
        .btn-action.btn-secondary-action:hover,
        .btn-action.btn-view-action:hover {
            background: #2C3E8F;
            color: #ffffff !important;
            border-color: #2C3E8F;
            box-shadow: 0 3px 10px rgba(44, 62, 143, 0.22);
            transform: translateY(-1px);
        }
        .btn-action-secondary:active,
        .btn-action.btn-secondary-action:active,
        .btn-action.btn-view-action:active {
            transform: translateY(0);
        }

        /* 5. Warning / Attention Action - Amber / Gold (View Uploaded ID) */
        .btn-action-warning,
        .btn-action.btn-warning-action {
            background: #fef3c7;
            color: #92400e !important;
            border-color: #fcd34d;
        }
        .btn-action-warning:hover,
        .btn-action.btn-warning-action:hover {
            background: #f59e0b;
            color: #ffffff !important;
            border-color: #f59e0b;
            box-shadow: 0 3px 10px rgba(245, 158, 11, 0.25);
            transform: translateY(-1px);
        }
        .btn-action-warning:active,
        .btn-action.btn-warning-action:active {
            transform: translateY(0);
        }

        /* 6. Neutral Action - Light Slate (Archive) */
        .btn-action-neutral,
        .btn-action.btn-neutral-action {
            background: #f8faff;
            color: #64748b !important;
            border-color: #e2e8f0;
        }
        .btn-action-neutral:hover,
        .btn-action.btn-neutral-action:hover {
            background: #e2e8f0;
            color: #1e293b !important;
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }
        .btn-action-neutral:active,
        .btn-action.btn-neutral-action:active {
            transform: translateY(0);
        }

        /* 7. Disabled Action */
        .btn-action:disabled,
        .btn-action[disabled],
        .btn-action.btn-disabled-action {
            background: #f1f5f9 !important;
            color: #94a3b8 !important;
            border-color: #e2e8f0 !important;
            cursor: not-allowed !important;
            box-shadow: none !important;
            transform: none !important;
            opacity: 0.75;
        }

        /* Status Badges in Action Column (matching button height and geometry) */
        .status-badge-soft {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 32px;
            min-height: 32px;
            padding: 0 12px;
            font-size: 0.76rem;
            font-weight: 700;
            border-radius: 8px;
            white-space: nowrap;
            line-height: 1;
            box-sizing: border-box;
        }
        .status-badge-soft.status-success {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
        .status-badge-soft.status-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .status-badge-soft.status-warning {
            background: #fff7ed;
            color: #9a3412;
            border: 1px solid #ffedd5;
        }
        .status-badge-soft.status-info {
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        /* Standardized action gap container */
        .action-gap {
            display: flex;
            align-items: center;
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

        /* Client-side toast — matches components/admin-notification (navy + yellow timer + close) */
        .ui-toast {
            position: fixed;
            top: 84px;
            right: 18px;
            z-index: 1090;
            max-width: 420px;
            min-width: 260px;
            padding: 14px 18px;
            border-radius: 12px;
            background: linear-gradient(135deg, #2C3E8F, #1A2A5C);
            color: #fff;
            box-shadow: 0 10px 28px rgba(26, 42, 92, .35);
            border: 1px solid rgba(255, 255, 255, .18);
            display: none;
            flex-direction: column;
            overflow: hidden;
            font-size: .84rem;
            font-weight: 700;
        }

        .ui-toast.show {
            display: flex;
        }

        .ui-toast-row {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .ui-toast-text {
            flex: 1;
            white-space: pre-line;
            line-height: 1.45;
        }

        .ui-toast-close {
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: #fff;
            border-radius: 6px;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.1rem;
            line-height: 1;
            padding: 0;
            transition: background .2s;
        }

        .ui-toast-close:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .ui-toast-timer {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            width: 100%;
            background: rgba(253, 185, 19, 0.9);
            transform-origin: left;
            animation: uiToastTimerShrink 5s linear forwards;
        }

        @keyframes uiToastTimerShrink {
            from { width: 100%; }
            to { width: 0%; }
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

    @include('components.admin-navbar')

    <div style="flex:1;">
        <div class="container mt-4">

            @include('components.admin-notification')

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
                    <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                        <a href="{{ route('admin.solo-parent-requirements.index') }}"
                            style="background:rgba(253,185,19,0.2);border:1.5px solid rgba(253,185,19,0.5);color:#FDB913;border-radius:20px;padding:5px 16px;font-size:0.8rem;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px;text-decoration:none;transition:all .2s;"
                            onmouseover="this.style.background='rgba(253,185,19,0.35)'"
                            onmouseout="this.style.background='rgba(253,185,19,0.2)'">
                            ⚙️ Solo Parent Requirements
                        </a>
                        <button type="button" onclick="document.getElementById('archiveModal').style.display='flex'"
                            style="background:rgba(255,255,255,0.12);border:1.5px solid rgba(255,255,255,0.3);color:white;border-radius:20px;padding:5px 16px;font-size:0.8rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;transition:all .2s;"
                            onmouseover="this.style.background='rgba(255,255,255,0.22)'"
                            onmouseout="this.style.background='rgba(255,255,255,0.12)'">
                            Archived ({{ isset($archivedApplications) ? $archivedApplications->count() : 0 }})
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
                            <button type="submit" class="btn-action btn-action-primary" style="height:36px;padding:0 16px;">Filter</button>
                            <a href="{{ route('admin.requirements') }}" class="btn-action btn-action-neutral"
                                style="height:36px;padding:0 16px;">Reset</a>
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
                                                    <span class="status-pill status-approved">Approved</span>
                                                @elseif($app->status === 'rejected')
                                                    <span class="status-pill status-rejected">Rejected</span>
                                                @else
                                                    <span class="status-pill status-pending">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="action-gap">
                                                    @if($app->status === 'approved' && $app->program_type === 'Solo_Parent')
                                                        @if($app->id_status === 'released')
                                                            <span class="status-badge-soft status-success">Released</span>
                                                        @elseif($app->id_status === 'ready_for_pickup')
                                                            <span class="status-badge-soft status-success">ID Ready</span>
                                                        @elseif($app->id_status === 'processing')
                                                            <form method="POST" action="{{ route('admin.applications.mark-id-ready', $app->id) }}" style="display:inline;">
                                                                @csrf
                                                                <button type="submit" class="btn-action btn-action-primary js-confirm-submit"
                                                                    data-confirm-title="Mark Solo Parent ID ready?"
                                                                    data-confirm-message="Notify user that their Solo Parent ID is ready for pick-up?"
                                                                    data-confirm-ok="Mark ID Ready">
                                                                    Mark ID Ready
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif

                                                    @if($app->status === 'approved' && in_array($app->program_type, ['PWD_Assistance', 'PWD_New', 'PWD_Renewal'], true))
                                                        @if($app->id_status === 'ready_for_pickup')
                                                            <span class="status-badge-soft status-success">ID Ready</span>
                                                        @elseif($app->id_status === 'processing')
                                                            <form method="POST" action="{{ route('admin.applications.mark-id-ready', $app->id) }}" style="display:inline;">
                                                                @csrf
                                                                <button type="submit" class="btn-action btn-action-primary js-confirm-submit"
                                                                    data-confirm-title="Mark PWD ID ready?"
                                                                    data-confirm-message="Notify user that PWD ID is ready for pick-up?"
                                                                    data-confirm-ok="Mark ID Ready">
                                                                    Mark ID Ready
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form method="POST" action="{{ route('admin.applications.validate-pwd', $app->id) }}" style="display:inline;">
                                                                @csrf
                                                                <button type="submit" class="btn-action btn-approve js-confirm-submit"
                                                                    data-confirm-title="Validate requirements?"
                                                                    data-confirm-message="Mark this approved PWD application as validated and notify the user?"
                                                                    data-confirm-ok="Validate">
                                                                    Validate
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif

                                                    @if($app->status === 'approved' && in_array($app->program_type, ['AICS_Medical', 'AICS_Burial'], true))
                                                        @if($app->id_status === 'released')
                                                            <span class="status-badge-soft status-success">Released</span>
                                                        @elseif($app->id_status === 'ready_for_pickup')
                                                            <span class="status-badge-soft status-success">Claim Ready</span>
                                                            <form method="POST" action="{{ route('admin.applications.mark-released', $app->id) }}" style="display:inline;">
                                                                @csrf
                                                                <button type="submit" class="btn-action btn-approve js-confirm-submit"
                                                                    data-confirm-title="Mark as Released?"
                                                                    data-confirm-message="Confirm that the AICS grant has been physically released to the applicant? This will send a confirmation email."
                                                                    data-confirm-ok="Mark Released">
                                                                    Mark Released
                                                                </button>
                                                            </form>
                                                        @elseif($app->id_status === 'processing')
                                                            <form method="POST" action="{{ route('admin.applications.mark-id-ready', $app->id) }}" style="display:inline;">
                                                                @csrf
                                                                <button type="submit" class="btn-action btn-action-primary js-confirm-submit"
                                                                    data-confirm-title="Mark claim ready?"
                                                                    data-confirm-message="Notify user that AICS grant is ready for pickup?"
                                                                    data-confirm-ok="Mark Claim Ready">
                                                                    Mark Claim Ready
                                                                </button>
                                                            </form>
                                                        @else
                                                            <form method="POST" action="{{ route('admin.applications.validate-aics', $app->id) }}" style="display:inline;">
                                                                @csrf
                                                                <button type="submit" class="btn-action btn-action-primary js-confirm-submit"
                                                                    data-confirm-title="Validate AICS requirements?"
                                                                    data-confirm-message="Mark this approved AICS application as validated and notify the user?"
                                                                    data-confirm-ok="Validate">
                                                                    Validate
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif

                                                    {{-- VIEW DOCUMENTS --}}
                                                    <a href="{{ route('admin.view-requirement', $app->id) }}" class="btn-action btn-action-secondary">View Documents</a>

                                                    {{-- ARCHIVE --}}
                                                    <button type="button" class="btn-action btn-action-neutral" onclick="archiveApp({{ $app->id }}, '{{ addslashes($app->full_name) }}')">Archive</button>
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
                            <div class="empty-num">0</div>
                            <p>No applications submitted yet for <strong>{{ $municipality }}</strong>.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="panel-card" style="margin-top:28px;">
                <div class="panel-header" style="display:flex;align-items:center;justify-content:space-between;">
                    <span>Appointments</span>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <button type="button" onclick="openArchivedAppts()"
                            style="background:rgba(255,255,255,0.12);border:1.5px solid rgba(255,255,255,0.3);color:white;border-radius:20px;padding:5px 16px;font-size:0.8rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;transition:all .2s;"
                            onmouseover="this.style.background='rgba(255,255,255,0.22)'"
                            onmouseout="this.style.background='rgba(255,255,255,0.12)'">
                            Archived (<span id="archivedApptCount">0</span>)
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
                        <option value="Solo_Parent_Renewal">Solo Parent Renewal</option>
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


    {{-- Notes modal --}}
    <div id="notesModal"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;"
        onclick="if(event.target===this) this.style.display='none'">
        <div style="background:white;border-radius:16px;padding:28px;max-width:480px;width:90%;box-shadow:0 8px 40px rgba(0,0,0,.2);">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                <h5 style="font-weight:800;color:#1e293b;margin:0;">Appointment Notes</h5>
                <button onclick="document.getElementById('notesModal').style.display='none'" style="background:rgba(44,62,143,.08);border:none;color:#1e293b;border-radius:8px;width:32px;height:32px;font-size:1.1rem;cursor:pointer;line-height:1;">&times;</button>
            </div>
            <p id="notesModalApplicant" style="font-size:.8rem;color:#64748b;margin-bottom:12px;"></p>
            <div id="notesModalBody" style="background:#f8faff;border:1px solid #c7d6f5;border-radius:10px;padding:14px 16px;font-size:.88rem;color:#1e293b;line-height:1.6;white-space:pre-wrap;word-break:break-word;min-height:60px;max-height:50vh;overflow-y:auto;"></div>
            <div style="text-align:right;margin-top:16px;">
                <button type="button" onclick="document.getElementById('notesModal').style.display='none'" class="btn-action btn-action-neutral" style="height:36px;padding:0 22px;">Close</button>
            </div>
        </div>
    </div>

    {{-- Reject modal --}}
    <div id="rejectModal"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
        <div
            style="background:white;border-radius:16px;padding:28px;max-width:440px;width:90%;box-shadow:0 8px 40px rgba(0,0,0,.2);">
            <h5 style="font-weight:800;margin-bottom:14px;color:#1e293b;"> Reject Appointment</h5>
            <p style="font-size:.85rem;color:#64748b;margin-bottom:14px;">Provide a reason for rejection. This will be
                sent to the user via email.</p>
            <textarea id="rejectNotes" rows="3" class="form-control" placeholder="Reason for rejection…"
                style="border-radius:10px;margin-bottom:16px;"></textarea>
            <div style="display:flex;gap:10px;">
                <button type="button" onclick="submitReject()" class="btn-action btn-decline" style="height:38px;padding:0 22px;flex:1;">Send Rejection</button>
                <button type="button" onclick="closeRejectModal()" class="btn-action btn-action-neutral" style="height:38px;padding:0 20px;">Cancel</button>
            </div>
        </div>
    </div>


    {{-- Validate Solo Parent Eligibility & Assign Category Modal --}}
    <div id="validateSoloParentModal"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(2px);">
        <div style="background:white;border-radius:18px;padding:26px 28px;max-width:560px;width:92%;box-shadow:0 12px 48px rgba(0,0,0,.25);max-height:90vh;display:flex;flex-direction:column;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                <h5 style="font-weight:800;margin:0;color:#1e293b;font-size:1.15rem;">Validate Solo Parent Eligibility</h5>
                <button type="button" onclick="closeValidateSoloParentModal()" style="background:none;border:none;font-size:1.4rem;color:#94a3b8;cursor:pointer;line-height:1;">&times;</button>
            </div>

            <div style="font-size:.88rem;color:#475569;margin-bottom:14px;background:#f1f5f9;padding:10px 14px;border-radius:10px;">
                <strong>Applicant:</strong> <span id="vspApplicantName" style="color:#2C3E8F;font-weight:700;"></span>
            </div>

            <div style="margin-bottom:14px;">
                <label style="font-size:.78rem;font-weight:700;color:#334155;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:6px;">
                    Solo Parent Category <span style="color:#dc3545;">*</span>
                </label>
                <select id="vspCategorySelect" class="form-select" style="border-radius:10px;font-weight:600;font-size:.88rem;border:1.5px solid #cbd5e1;padding:9px 12px;" onchange="updateVspPreview()">
                    @foreach(\App\Services\SoloParentCategoryService::getCategories() as $code => $title)
                        <option value="{{ $code }}">{{ $title }}</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom:14px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;padding:12px 14px;">
                <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;margin:0;">
                    <input type="checkbox" id="vspAvailBenefit" value="CODE_1_2" style="margin-top:3px;accent-color:#2C3E8F;width:17px;height:17px;cursor:pointer;" onchange="updateVspPreview()">
                    <div>
                        <span style="font-size:.86rem;font-weight:700;color:#1e293b;display:block;">
                            Applicant is availing of Solo Parent Subsidy &amp; Discount (Code 1,2)
                        </span>
                        <span style="font-size:.78rem;color:#64748b;display:block;margin-top:2px;line-height:1.4;">
                            Separate benefit track under RA 11861. Adds the 4 proof of income requirements without altering the applicant's primary category.
                        </span>
                    </div>
                </label>
            </div>

            <div style="flex:1;overflow-y:auto;background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:14px;margin-bottom:18px;max-height:280px;">
                <div style="font-size:.75rem;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:8px;">
                    Requirements That Will Be Assigned (<span id="vspReqCount">0</span>):
                </div>
                <div id="vspReqPreviewList" style="display:flex;flex-direction:column;gap:8px;"></div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="closeValidateSoloParentModal()" class="btn-action btn-action-neutral" style="height:38px;padding:0 20px;">Cancel</button>
                <button type="button" onclick="submitValidateSoloParent()" id="vspSubmitBtn" class="btn-action btn-action-primary" style="height:38px;padding:0 22px;">Validate Eligibility</button>
            </div>
        </div>
    </div>
    <script>
        function openNotesModal(encodedName, encodedNotes) {
            document.getElementById('notesModalApplicant').textContent = 'Applicant: ' + decodeURIComponent(encodedName);
            const body = document.getElementById('notesModalBody');
            const decoded = decodeURIComponent(encodedNotes || '').trim();
            if (decoded) {
                body.textContent = decoded;
                body.style.color = '#1e293b';
                body.style.fontStyle = 'normal';
            } else {
                body.textContent = 'No notes available.';
                body.style.color = '#94a3b8';
                body.style.fontStyle = 'italic';
            }
            document.getElementById('notesModal').style.display = 'flex';
        }

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
                if (program) {
                    if (program === 'solo_parent_renewal') {
                        const isRen = normalizeText(a.program_type) === 'solo_parent_renewal' || !!a.renewal;
                        if (!isRen) return false;
                    } else if (normalizeText(a.program_type) !== program) {
                        return false;
                    }
                }
                return true;
            });
            renderAppointments(filtered);
        }

        function loadAppointments() {
            fetch('/admin/appointments', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => {
                    if (!r.ok) throw new Error('HTTP ' + r.status);
                    return r.json();
                })
                .then(data => {
                    _allAppointments = Array.isArray(data) ? data : [];
                    applyAppointmentFilters();
                })
                .catch(e => {
                    console.error('Failed to load appointments:', e);
                    document.getElementById('apptTableWrap').innerHTML = '<div style="padding:20px;color:#dc3545;font-weight:600;">Failed to load appointments: ' + (e.message || 'Server error') + '</div>';
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
                    'Solo_Parent_Renewal': { label: 'Solo Parent Renewal', color: '#7c3aed', bg: '#f5f3ff' },
                    'AICS_Medical': { label: 'AICS Medical', color: '#0891b2', bg: '#e0f2fe' },
                    'AICS_Burial': { label: 'AICS Burial', color: '#7c3aed', bg: '#f5f3ff' },
                };
                const prog = progLabels[a.program_type] || { label: a.program_type.replace('_', ' '), color: '#64748b', bg: '#f1f5f9' };
                const progBadge = `<span style="background:${prog.bg};color:${prog.color};border-radius:20px;padding:2px 10px;font-size:.72rem;font-weight:700;white-space:nowrap;">${prog.label}</span>`;

                let actions = '';
                const isSoloParent = a.program_type === 'Solo_Parent';
                const isRenewal = a.program_type === 'Solo_Parent_Renewal' || !!a.renewal;
                const isAics = ['AICS_Medical', 'AICS_Burial'].includes(a.program_type);
                const hasPendingReschedule = a.reschedule_status === 'pending';
                const hasPendingCancellation = a.cancellation_status === 'pending';

                // If there's a pending cancellation request, show cancellation approve/reject buttons
                if (hasPendingCancellation) {
                    actions = `
                        <button onclick="approveCancellation(${a.id})" class="btn-action btn-approve">Approve Cancellation</button>
                        <button onclick="rejectCancellation(${a.id})" class="btn-action btn-decline">Reject Cancellation</button>`;
                }
                // If there's a pending reschedule request, only show reschedule approve/reject buttons
                else if (hasPendingReschedule) {
                    actions = `
                        <button onclick="approveReschedule(${a.id})" class="btn-action btn-approve">Approve Reschedule</button>
                        <button onclick="rejectReschedule(${a.id})" class="btn-action btn-decline">Reject Reschedule</button>`;
                } else {
                    // Normal appointment status buttons
                    if (a.status === 'pending') {
                        if (isRenewal && a.renewal) {
                            if (a.renewal.status === 'pending') {
                                actions = `
                                    <button onclick="approveRenewalId(${a.renewal.id})" class="btn-action btn-approve">Approve ID</button>
                                    <button onclick="openRejectRenewalIdModal(${a.renewal.id})" class="btn-action btn-decline">Reject ID</button>
                                    <button disabled title="ID must be approved first before approving appointment" class="btn-action btn-disabled-action">Approve Appointment</button>
                                `;
                            } else if (a.renewal.status === 'approved') {
                                actions = `
                                    <span class="status-badge-soft status-success">ID Approved</span>
                                    <button onclick="confirmAppt(${a.id})" class="btn-action btn-approve">Approve Appointment</button>
                                    <button onclick="openRejectModal(${a.id})" class="btn-action btn-decline">Reject Appointment</button>
                                `;
                            } else if (a.renewal.status === 'rejected') {
                                actions = `<span class="status-badge-soft status-danger">ID Rejected</span>`;
                            }
                        } else {
                            actions = `
                                <button onclick="confirmAppt(${a.id})" class="btn-action btn-approve">Approve Appointment</button>
                                <button onclick="openRejectModal(${a.id})" class="btn-action btn-decline">Reject Appointment</button>
                            `;
                        }
                    } else if (a.status === 'confirmed' || a.status === 'approved') {
                        if ((isSoloParent || isRenewal) && a.status === 'confirmed') {
                            actions = `
                                    <button onclick="validateAppt(${a.id})" class="btn-action btn-action-primary">Mark Eligible</button>
                                    <button onclick="openRejectModal(${a.id})" class="btn-action btn-decline">Reject Appointment</button>
                                `;
                        } else if (isAics && a.status === 'confirmed') {
                            actions = `
                                    <button onclick="validateAppt(${a.id})" class="btn-action btn-action-primary">Validate Interview</button>
                                    <button onclick="openRejectModal(${a.id})" class="btn-action btn-decline">Reject Appointment</button>
                                `;
                        }
                    } else if (a.status === 'validated') {
                        if (isAics) {
                            if (a.id_status === 'released') {
                                actions = `<span class="status-badge-soft status-success">Released</span>`;
                            } else if (a.id_status === 'ready_for_pickup') {
                                actions = `<span class="status-badge-soft status-success">Claim Ready</span>
                                           <button onclick="markReleased(${a.aics_app_id},'${encodeURIComponent(a.user_name)}')" class="btn-action btn-approve">Mark Released</button>`;
                            } else {
                                actions = `<span class="status-badge-soft status-info">Validated</span>`;
                            }
                        } else {
                            actions = `<span class="status-badge-soft status-info">Validated</span>`;
                        }
                    } else {
                        actions = '<span style="color:#94a3b8;font-size:.78rem;">—</span>';
                    }
                }

                // Prepend View Uploaded ID button if renewal request is attached
                if (a.renewal && a.renewal.id_file_url) {
                    actions = `<button onclick="viewRenewalId('${a.renewal.id_file_url}', '${encodeURIComponent(a.user_name)}', '${a.renewal.program_type}', ${a.renewal.id}, '${a.renewal.status}', '${encodeURIComponent(a.renewal.admin_remarks || '')}')" class="btn-action btn-action-warning">View Uploaded ID</button>` + actions;
                }

                // Build notes data for modal
                let notesModalContent = '';
                let hasNotes = false;

                if (a.cancellation_status === 'pending' && a.cancel_reason) {
                    notesModalContent = a.cancel_reason;
                    hasNotes = true;
                } else if (a.reschedule_status === 'pending' && a.reschedule_request_reason) {
                    notesModalContent = (a.reschedule_request_date || '') + ' ' + (a.reschedule_request_time || '') + (a.reschedule_request_reason ? '\n' + a.reschedule_request_reason : '') + (a.reschedule_admin_notes ? '\nAdmin: ' + a.reschedule_admin_notes : '');
                    hasNotes = true;
                } else if (a.status === 'cancelled' && a.cancel_reason) {
                    notesModalContent = 'Cancelled: ' + a.cancel_reason;
                    hasNotes = true;
                } else if ((a.user_notes || '').trim()) {
                    notesModalContent = a.user_notes.trim();
                    hasNotes = true;
                }

                const notesCell = hasNotes
                    ? `<button onclick="openNotesModal('${encodeURIComponent(a.user_name)}', '${encodeURIComponent(notesModalContent)}')" class="btn-action btn-action-secondary">View Notes</button>`
                    : '<span style="color:#94a3b8;font-size:.78rem;">—</span>';

                // Reschedule button: hide for applications marked Eligible (status === 'validated')
                if (a.status !== 'validated') {
                    actions += `<button onclick="showAdminRescheduleModal(${a.id}, '${encodeURIComponent(a.user_name)}')" class="btn-action btn-action-secondary">Reschedule</button>`;
                }

                return `<tr>
                        <td style="font-weight:700;">${a.user_name}<br><small style="color:#94a3b8;font-size:.72rem;">${a.user_email}</small></td>
                        <td>${progBadge}</td>
                        <td>${a.date}<br><small style="color:#64748b;">${a.day}</small></td>
                        <td>${a.time}</td>
                        <td>${a.interview_type}</td>
                        <td>${a.status_badge}</td>
                        <td>${notesCell}</td>
                        <td><div class="action-gap">${actions}<button onclick="archiveAppt(${a.id},'${encodeURIComponent(a.user_name)}')" class="btn-action btn-action-neutral">Archive</button></div></td>
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

        const _vspCategoryMap = @json(\App\Services\SoloParentCategoryService::getCategoryPreviewMap());
        const _vspBenefitData = @json(\App\Services\SoloParentCategoryService::getBenefitPreviewList());
        let _vspTargetId = null;

        function openValidateSoloParentModal(id, appt) {
            _vspTargetId = id;
            document.getElementById('vspApplicantName').textContent = appt ? (appt.user_name || 'Applicant #' + id) : 'Applicant #' + id;
            document.getElementById('vspCategorySelect').value = 'A1';
            const _vspBenefitEl = document.getElementById('vspAvailBenefit');
            if (_vspBenefitEl) _vspBenefitEl.checked = false;
            updateVspPreview();
            document.getElementById('validateSoloParentModal').style.display = 'flex';
        }

        function closeValidateSoloParentModal() {
            document.getElementById('validateSoloParentModal').style.display = 'none';
            _vspTargetId = null;
        }

        function updateVspPreview() {
            const cat = document.getElementById('vspCategorySelect').value;
            const data = _vspCategoryMap[cat];
            const isBenefit = document.getElementById('vspAvailBenefit')?.checked;
            const listEl = document.getElementById('vspReqPreviewList');
            const countEl = document.getElementById('vspReqCount');
            listEl.innerHTML = '';

            const catReqs = (data && data.requirements) ? data.requirements : [];
            const benefitReqs = (isBenefit && _vspBenefitData && _vspBenefitData.requirements) ? _vspBenefitData.requirements : [];
            const totalCount = catReqs.length + benefitReqs.length;

            countEl.textContent = totalCount;

            if (totalCount === 0) {
                listEl.innerHTML = '<div style="color:#94a3b8;font-size:.82rem;">No requirements configured.</div>';
                return;
            }

            if (isBenefit && catReqs.length > 0) {
                const head = document.createElement('div');
                head.style.cssText = 'font-size:.75rem;font-weight:800;color:#2C3E8F;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2px;';
                head.textContent = `Category ${cat} Requirements (${catReqs.length}):`;
                listEl.appendChild(head);
            }

            catReqs.forEach((req, idx) => {
                const item = document.createElement('div');
                item.style.cssText = 'background:white;border:1px solid #e2e8f0;border-radius:8px;padding:9px 12px;font-size:.82rem;line-height:1.4;';

                if (req.is_or_group) {
                    let orHtml = `<div style="font-weight:700;color:#2C3E8F;margin-bottom:4px;">${idx + 1}. ${req.title} <span style="background:#e0f2fe;color:#0369a1;font-size:.7rem;padding:2px 8px;border-radius:10px;margin-left:6px;">Submit 1 of ${req.options.length}</span></div>`;
                    orHtml += '<div style="padding-left:12px;display:flex;flex-direction:column;gap:3px;margin-top:4px;">';
                    req.options.forEach((opt, oIdx) => {
                        const orBadge = oIdx > 0 ? '<span style="color:#d97706;font-weight:700;font-size:.72rem;margin-right:4px;">OR</span> ' : '';
                        orHtml += `<div style="color:#475569;">${orBadge}• ${opt}</div>`;
                    });
                    orHtml += '</div>';
                    item.innerHTML = orHtml;
                } else {
                    item.innerHTML = `<div style="color:#1e293b;"><strong style="color:#2C3E8F;">${idx + 1}.</strong> ${req.title}</div>`;
                }

                listEl.appendChild(item);
            });

            if (isBenefit && benefitReqs.length > 0) {
                const bHead = document.createElement('div');
                bHead.style.cssText = 'font-size:.75rem;font-weight:800;color:#15803d;text-transform:uppercase;letter-spacing:.04em;margin-top:10px;margin-bottom:2px;';
                bHead.textContent = `Code 1,2 Benefit Requirements (${benefitReqs.length}):`;
                listEl.appendChild(bHead);

                benefitReqs.forEach((bReq, bIdx) => {
                    const bItem = document.createElement('div');
                    bItem.style.cssText = 'background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:9px 12px;font-size:.82rem;line-height:1.4;';
                    bItem.innerHTML = `<div style="color:#14532d;"><strong style="color:#15803d;">+${bIdx + 1}.</strong> ${bReq.title}</div>`;
                    listEl.appendChild(bItem);
                });
            }
        }

        function submitValidateSoloParent() {
            if (!_vspTargetId) return;
            const cat = document.getElementById('vspCategorySelect').value;
            const isAvailing = document.getElementById('vspAvailBenefit')?.checked;
            const benefitCode = isAvailing ? 'CODE_1_2' : null;
            const apptId = _vspTargetId;
            closeValidateSoloParentModal();
            showLoading('Validating Eligibility', 'Assigning category requirements and notifying applicant...');

            fetch(`/admin/appointments/${apptId}/validate`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ category_code: cat, benefit_code: benefitCode })
            }).then(r => r.json()).then(d => {
                if (d.success) {
                    uiToast(d.message || 'Validated and requirements assigned.');
                    loadAppointments();
                } else {
                    uiToast(d.message || 'Error validating appointment.', 'Error');
                }
            }).catch(() => uiToast('Error validating appointment.', 'Error'))
              .finally(() => hideLoading());
        }

        function validateAppt(id) {
            const appt = _allAppointments.find(a => a.id === id);
            const isSoloParent = appt && ['Solo_Parent', 'Solo_Parent_Renewal'].includes(appt.program_type);

            if (isSoloParent) {
                openValidateSoloParentModal(id, appt);
                return;
            }

            const programLabel = appt
                ? (appt.program_type === 'AICS_Burial' ? 'AICS Burial Assistance'
                    : appt.program_type === 'AICS_Medical' ? 'AICS Medical Assistance'
                    : 'Solo Parent ID')
                : 'program';

            uiConfirm(
                'Validate interview?',
                `Mark this appointment as validated?\n\nThis confirms the interview is done and will:\n- Allow the user to upload their ${programLabel} requirements\n- Send them an email and bell notification`,
                { okText: 'Validate', cancelText: 'Cancel' }
            ).then(ok => {
                if (!ok) return;
                showLoading('Validating', 'Preparing requirements form and sending notification...');
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
            if (!notes) { uiToast('Please enter a reason for rejection.', 'Error'); return; }
            const targetId = rejectTargetId;
            closeRejectModal();
            showLoading('Rejecting Appointment', 'Sending rejection notification...');
            fetch(`/admin/appointments/${targetId}/reject`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ admin_notes: notes })
            }).then(r => r.json()).then(d => {
                uiToast(d.message || 'Appointment rejected.');
                loadAppointments();
            }).catch(() => uiToast('Error rejecting appointment.', 'Error'))
                .finally(() => hideLoading());
        }

        function approveReschedule(id) {
            showLoading('Approving Reschedule', 'Updating appointment details...');
            fetch(`/admin/appointments/${id}/reschedule/approve`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ admin_notes: '' })
            }).then(r => r.json()).then(d => {
                uiToast(d.message || 'Reschedule approved.');
                loadAppointments();
            }).catch(() => uiToast('Error approving reschedule.', 'Error'))
                .finally(() => hideLoading());
        }

        function rejectReschedule(id) {
            openAdminReasonModal({
                title: 'Reject Reschedule Request',
                subtitle: 'Appointment Reschedule Review',
                description: 'Please provide a reason for rejecting this reschedule request.',
                onSubmit: function(reason) {
                    showLoading('Rejecting Reschedule', 'Updating request status...');
                    fetch(`/admin/appointments/${id}/reschedule/reject`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ admin_notes: reason })
                    }).then(r => r.json()).then(d => {
                        uiToast(d.message || 'Reschedule rejected.');
                        closeAdminReasonModal();
                        loadAppointments();
                    }).catch(() => uiToast('Error rejecting reschedule.', 'Error'))
                        .finally(() => hideLoading());
                }
            });
        }

        function approveCancellation(id) {
            uiConfirm(
                'Approve Cancellation?',
                'Approve this cancellation request? The appointment will be cancelled.',
                { okText: 'Approve', cancelText: 'Cancel' }
            ).then(ok => {
                if (!ok) return;
                showLoading('Approving Cancellation', 'Cancelling appointment...');
                fetch(`/admin/appointments/${id}/cancellation/approve`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({})
                }).then(r => r.json()).then(d => {
                    uiToast(d.message || 'Cancellation approved.');
                    loadAppointments();
                }).catch(() => uiToast('Error approving cancellation.', 'Error'))
                    .finally(() => hideLoading());
            });
        }

        function rejectCancellation(id) {
            openAdminReasonModal({
                title: 'Reject Cancellation Request',
                subtitle: 'Appointment Cancellation Review',
                description: 'Please provide a reason for rejecting this cancellation request.',
                onSubmit: function(reason) {
                    showLoading('Rejecting Cancellation', 'Updating request status...');
                    fetch(`/admin/appointments/${id}/cancellation/reject`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ admin_notes: reason })
                    }).then(r => r.json()).then(d => {
                        uiToast(d.message || 'Cancellation rejected.');
                        closeAdminReasonModal();
                        loadAppointments();
                    }).catch(() => uiToast('Error rejecting cancellation.', 'Error'))
                        .finally(() => hideLoading());
                }
            });
        }


        // ── Reusable Admin Reason Input Modal ──
        var _adminReasonCallback = null;

        function openAdminReasonModal(opts) {
            const title = opts.title || 'Rejection Reason';
            const subtitle = opts.subtitle || '';
            const desc = opts.description || 'Please provide a reason.';
            _adminReasonCallback = opts.onSubmit || null;

            document.getElementById('adminReasonModalTitle').textContent = title;
            document.getElementById('adminReasonModalSubtitle').textContent = subtitle;
            document.getElementById('adminReasonModalDesc').textContent = desc;
            document.getElementById('adminReasonTextarea').value = '';
            document.getElementById('adminReasonError').style.display = 'none';
            document.getElementById('adminReasonSubmitBtn').disabled = false;
            document.getElementById('adminReasonSubmitBtn').textContent = 'Confirm Rejection';
            document.getElementById('adminReasonModal').style.display = 'flex';
            setTimeout(() => document.getElementById('adminReasonTextarea').focus(), 100);
        }

        function closeAdminReasonModal() {
            document.getElementById('adminReasonModal').style.display = 'none';
            _adminReasonCallback = null;
        }

        function submitAdminReason() {
            const reason = document.getElementById('adminReasonTextarea').value.trim();
            const errorDiv = document.getElementById('adminReasonError');
            if (!reason) {
                errorDiv.textContent = 'Please provide a reason for rejection.';
                errorDiv.style.display = 'block';
                document.getElementById('adminReasonTextarea').focus();
                return;
            }
            errorDiv.style.display = 'none';
            const btn = document.getElementById('adminReasonSubmitBtn');
            btn.disabled = true;
            btn.textContent = 'Submitting...';
            if (_adminReasonCallback) {
                _adminReasonCallback(reason);
            }
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

    <div class="footer-strip"></div>

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
                        <button type="button" class="btn-action btn-action-neutral" data-bs-dismiss="modal" style="height:38px;padding:0 20px;">
                            Cancel
                        </button>
                        <button type="submit" id="confirmDeclineBtn" class="btn-action btn-decline" style="height:38px;padding:0 22px;">
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
                    <div style="color:white;font-weight:800;font-size:1rem;">Archived Applications</div>
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
                                                    Restore
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
                                                    Delete
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

        const _markReleasedInFlight = {};
        function markReleased(appId, encodedName) {
            if (_markReleasedInFlight[appId]) return;
            const name = decodeURIComponent(encodedName);
            uiConfirm(
                'Mark as Released?',
                'Confirm that the AICS grant for \"' + name + '\" has been physically released to the applicant?\n\nThis will send a confirmation email to the user.',
                { okText: 'Mark Released', cancelText: 'Cancel' }
            ).then(ok => {
                if (!ok) return;
                _markReleasedInFlight[appId] = true;
                showLoading('Marking as Released', 'Finalizing grant release and notifying user...');
                fetch('/admin/applications/' + appId + '/mark-released', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                }).then(r => r.json()).then(d => {
                    uiToast(d.message || 'Grant marked as released.');
                    loadAppointments();
                }).finally(() => {
                    _markReleasedInFlight[appId] = false;
                    hideLoading();
                }).catch(() => uiToast('Failed to mark as released.', 'Error'));
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
                    <div class="action-gap">
                        <button onclick="restoreAppt(${a.id})" class="btn-action btn-approve">Restore</button>
                        <button onclick="forceDeleteAppt(${a.id})" class="btn-action btn-decline">Delete</button>
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
                    <div style="color:white;font-weight:800;font-size:1rem;">Archived Appointments</div>
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
    <div id="uiToast" class="ui-toast" role="status" aria-live="polite" aria-atomic="true">
        <div class="ui-toast-row">
            <span id="uiToastText" class="ui-toast-text"></span>
            <button type="button" class="ui-toast-close" id="uiToastClose" onclick="dismissUiToast()" aria-label="Close">&times;</button>
        </div>
        <div id="uiToastTimer" class="ui-toast-timer" aria-hidden="true"></div>
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

        // Toast aligned with components/admin-notification (navy, yellow timer, close, 5s)
        let __uiToastTimer = null;
        function dismissUiToast() {
            const toast = document.getElementById('uiToast');
            if (!toast || !toast.classList.contains('show')) return;
            if (__uiToastTimer) {
                clearTimeout(__uiToastTimer);
                __uiToastTimer = null;
            }
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100px)';
            setTimeout(() => {
                toast.classList.remove('show');
                toast.style.opacity = '';
                toast.style.transform = '';
                toast.style.transition = '';
            }, 400);
        }

        function uiToast(message, title = 'MSWDO Admin') {
            const toast = document.getElementById('uiToast');
            const textEl = document.getElementById('uiToastText');
            const bar = document.getElementById('uiToastTimer');
            if (!toast || !textEl || !bar) return;

            let body = (message || '').trim();
            if (title && title !== 'MSWDO Admin' && title !== 'Error') {
                body = title + (body ? '\n\n' + body : '');
            }

            textEl.textContent = body;

            bar.style.animation = 'none';
            void bar.offsetHeight;
            bar.style.animation = 'uiToastTimerShrink 5s linear forwards';

            if (__uiToastTimer) clearTimeout(__uiToastTimer);

            toast.style.transition = 'all 0.4s cubic-bezier(0.68,-0.55,0.265,1.55)';
            toast.classList.add('show');
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100px)';
            setTimeout(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateX(0)';
            }, 100);

            __uiToastTimer = setTimeout(() => dismissUiToast(), 5000);
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

        // Admin direct reschedule
        function showAdminRescheduleModal(apptId, encodedName) {
            document.getElementById('adminRescheduleApptId').value = apptId;
            document.getElementById('adminRescheduleUserName').textContent = decodeURIComponent(encodedName);
            document.getElementById('adminRescheduleDate').value = '';
            document.getElementById('adminRescheduleTime').innerHTML = '<option value="">Select date first</option>';
            document.getElementById('adminRescheduleTime').disabled = true;
            document.getElementById('adminRescheduleNotes').value = '';
            document.getElementById('adminRescheduleModal').style.display = 'flex';
        }

        function hideAdminRescheduleModal() {
            document.getElementById('adminRescheduleModal').style.display = 'none';
        }

        function submitAdminReschedule() {
            const apptId = document.getElementById('adminRescheduleApptId').value;
            const date = document.getElementById('adminRescheduleDate').value;
            const time = document.getElementById('adminRescheduleTime').value;
            const notes = document.getElementById('adminRescheduleNotes').value;

            if (!date || !time) {
                alert('Please select both date and time.');
                return;
            }

            showLoading('Rescheduling Appointment', 'Updating appointment details...');
            fetch(`/admin/appointments/${apptId}/admin-reschedule`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ new_date: date, new_time: time, admin_notes: notes })
            }).then(r => r.json()).then(d => {
                if (d.success) {
                    uiToast(d.message || 'Appointment rescheduled.');
                    hideAdminRescheduleModal();
                    loadAppointments();
                } else {
                    uiToast(d.message || 'Error rescheduling.', 'Error');
                }
            }).catch(() => uiToast('Error rescheduling appointment.', 'Error'))
                .finally(() => hideLoading());
        }

        function viewRenewalId(url, name, prog, renewalId, renewalStatus, adminRemarks) {
            var _lastRenewalRemarks = decodeURIComponent(adminRemarks || '');
            const displayName = decodeURIComponent(name);
            document.getElementById('renewalIdModalApplicant').textContent = displayName + ' (' + (prog === 'PWD' ? 'PWD' : 'Solo Parent') + ' ID)';
            document.getElementById('renewalIdDownloadLink').href = url;
            const container = document.getElementById('renewalIdViewerContainer');
            container.innerHTML = '<div style="padding:40px;color:#64748b;text-align:center;"><div class="spinner-border spinner-border-sm me-2"></div>Loading ID document...</div>';

            const actionsContainer = document.getElementById('renewalIdModalActions');
            if (actionsContainer) {
                if (renewalStatus === 'pending' && renewalId) {
                    actionsContainer.innerHTML = `
                        <button type="button" onclick="approveRenewalId(${renewalId})" class="btn-action btn-approve">Approve ID</button>
                        <button type="button" onclick="openRejectRenewalIdModal(${renewalId})" class="btn-action btn-decline">Reject ID</button>
                    `;
                } else if (renewalStatus === 'approved') {
                    actionsContainer.innerHTML = '<span class="status-badge-soft status-success">ID Approved</span>';
                } else if (renewalStatus === 'rejected') {
                    actionsContainer.innerHTML = '<span class="status-badge-soft status-danger">ID Rejected</span>';
                    // Show rejection reason if available from appointment data
                    if (typeof _lastRenewalRemarks !== 'undefined' && _lastRenewalRemarks) {
                        const remarkDiv = document.createElement('div');
                        remarkDiv.style.cssText = 'margin-top:8px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:10px 14px;font-size:.82rem;color:#991b1b;';
                        remarkDiv.textContent = 'Rejection reason: ' + _lastRenewalRemarks;
                        actionsContainer.parentElement.appendChild(remarkDiv);
                    }
                } else {
                    actionsContainer.innerHTML = '';
                }
            }

            document.getElementById('renewalIdModal').style.display = 'flex';

            const isPdf = url.toLowerCase().includes('.pdf');
            if (isPdf) {
                container.innerHTML = `<iframe src="${url}" style="width:100%;height:65vh;border:none;border-radius:8px;"></iframe>`;
            } else {
                container.innerHTML = `<img src="${url}" alt="Renewal ID" style="max-width:100%;max-height:65vh;object-fit:contain;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.1);" onerror="this.onerror=null;this.parentElement.innerHTML='<iframe src=\'' + url + '\' style=\'width:100%;height:65vh;border:none;\'></iframe>';">`;
            }
        }

        function closeRenewalIdModal() {
            document.getElementById('renewalIdModal').style.display = 'none';
            document.getElementById('renewalIdViewerContainer').innerHTML = '';
        }

        function approveRenewalId(renewalId) {
            if (!confirm("Are you sure you want to approve this renewal applicant's ID? Once approved, you can confirm their appointment.")) return;
            fetch('/admin/renewals/' + renewalId + '/approve', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ admin_remarks: 'Renewal ID approved by admin' })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    uiToast(data.message || 'Renewal ID approved.');
                    closeRenewalIdModal();
                    loadAppointments();
                } else {
                    alert(data.message || 'Failed to approve renewal ID.');
                }
            })
            .catch(e => {
                alert('Server error approving renewal ID.');
            });
        }

        var _rejectRenewalTargetId = null;

        function openRejectRenewalIdModal(renewalId) {
            _rejectRenewalTargetId = renewalId;
            // Try to get applicant name from the renewal ID modal title
            const nameEl = document.getElementById('renewalIdModalApplicant');
            document.getElementById('rejectRenewalApplicantName').textContent = nameEl ? nameEl.textContent : 'Applicant';
            document.getElementById('rejectRenewalReasonSelect').value = '';
            document.getElementById('rejectRenewalNotes').value = '';
            document.getElementById('rejectRenewalNotes').placeholder = 'Add details or instructions for the applicant...';
            document.getElementById('rejectRenewalCommentsLabel').innerHTML = 'Additional Comments <span style="font-size:.75rem;font-weight:400;color:#64748b;">(Optional)</span>';
            document.getElementById('rejectRenewalError').style.display = 'none';
            document.getElementById('rejectRenewalSubmitBtn').disabled = false;
            document.getElementById('rejectRenewalSubmitBtn').textContent = 'Confirm Rejection';
            document.getElementById('rejectRenewalIdModal').style.display = 'flex';
        }

        function closeRejectRenewalIdModal() {
            document.getElementById('rejectRenewalIdModal').style.display = 'none';
            _rejectRenewalTargetId = null;
        }

        function onRejectRenewalReasonChange() {
            const reason = document.getElementById('rejectRenewalReasonSelect').value;
            const notesField = document.getElementById('rejectRenewalNotes');
            const label = document.getElementById('rejectRenewalCommentsLabel');
            document.getElementById('rejectRenewalError').style.display = 'none';

            if (reason && reason !== 'Other') {
                notesField.value = reason;
                notesField.placeholder = 'The rejection reason has been auto-filled. You can add more details.';
                label.innerHTML = 'Additional Comments <span style="font-size:.75rem;font-weight:400;color:#64748b;">(Optional)</span>';
            } else if (reason === 'Other') {
                notesField.value = '';
                notesField.placeholder = 'Please specify the reason for rejection...';
                label.innerHTML = 'Additional Comments <span style="color:#C41E24;">*</span>';
            } else {
                notesField.value = '';
                notesField.placeholder = 'Add details or instructions for the applicant...';
                label.innerHTML = 'Additional Comments <span style="font-size:.75rem;font-weight:400;color:#64748b;">(Optional)</span>';
            }
        }

        function submitRejectRenewalId() {
            const reason = document.getElementById('rejectRenewalReasonSelect').value;
            const notes = document.getElementById('rejectRenewalNotes').value.trim();
            const errorDiv = document.getElementById('rejectRenewalError');

            if (!reason) {
                errorDiv.textContent = 'Please select a rejection reason.';
                errorDiv.style.display = 'block';
                document.getElementById('rejectRenewalReasonSelect').focus();
                return;
            }
            if (reason === 'Other' && !notes) {
                errorDiv.textContent = 'Please specify the reason in Additional Comments when selecting "Other".';
                errorDiv.style.display = 'block';
                document.getElementById('rejectRenewalNotes').focus();
                return;
            }
            errorDiv.style.display = 'none';

            const finalRemarks = notes || reason;
            const btn = document.getElementById('rejectRenewalSubmitBtn');
            btn.disabled = true;
            btn.textContent = 'Submitting...';

            fetch('/admin/renewals/' + _rejectRenewalTargetId + '/reject', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ admin_remarks: finalRemarks })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    uiToast(data.message || 'Renewal ID rejected.');
                    closeRejectRenewalIdModal();
                    closeRenewalIdModal();
                    loadAppointments();
                } else {
                    errorDiv.textContent = data.message || 'Failed to reject renewal ID.';
                    errorDiv.style.display = 'block';
                    btn.disabled = false;
                    btn.textContent = 'Confirm Rejection';
                }
            })
            .catch(e => {
                errorDiv.textContent = 'Server error rejecting renewal ID. Please try again.';
                errorDiv.style.display = 'block';
                btn.disabled = false;
                btn.textContent = 'Confirm Rejection';
            });
        }

        // Load slots for admin reschedule
        document.addEventListener('DOMContentLoaded', function() {
            const adminRescheduleDate = document.getElementById('adminRescheduleDate');
            const adminRescheduleTime = document.getElementById('adminRescheduleTime');
            
            if (adminRescheduleDate) {
                adminRescheduleDate.addEventListener('change', function() {
                    const date = this.value;
                    console.log('Admin reschedule date changed:', date);
                    if (!date) return;

                    const d = new Date(date);
                    console.log('Day of week:', d.getDay());
                    if (d.getDay() === 0 || d.getDay() === 6) {
                        adminRescheduleTime.innerHTML = '<option value="">Weekdays only</option>';
                        adminRescheduleTime.disabled = true;
                        return;
                    }

                    adminRescheduleTime.disabled = true;
                    adminRescheduleTime.innerHTML = '<option value="">Loading slots…</option>';

                    const url = '/admin/appointments/slots?date=' + date;
                    console.log('Fetching slots from:', url);

                    fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        console.log('Response ok:', response.ok);
                        if (!response.ok) {
                            return response.text().then(text => {
                                console.error('Error response:', text);
                                throw new Error('Network response was not ok: ' + response.status);
                            });
                        }
                        return response.json();
                    })
                    .then(slots => {
                        console.log('Slots received:', slots);
                        if (!Array.isArray(slots)) {
                            console.error('Invalid response format:', slots);
                            throw new Error('Invalid response format');
                        }
                        adminRescheduleTime.innerHTML = '<option value="">Choose a time</option>';
                        slots.forEach(s => {
                            const opt = document.createElement('option');
                            opt.value = s.time;
                            if (s.full) {
                                opt.textContent = s.label + '  — FULL';
                                opt.disabled = true;
                            } else {
                                opt.textContent = s.label + '  (' + s.remaining + ' slot' + (s.remaining !== 1 ? 's' : '') + ' left)';
                            }
                            adminRescheduleTime.appendChild(opt);
                        });
                        adminRescheduleTime.disabled = false;
                        console.log('Slots loaded successfully');
                    })
                    .catch(err => {
                        console.error('Error loading slots:', err);
                        adminRescheduleTime.innerHTML = '<option value="">Error loading slots</option>';
                        adminRescheduleTime.disabled = true;
                    });
                });
            }
        });
    </script>

    {{-- Admin Reschedule Modal --}}
    <div id="adminRescheduleModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;overflow-y:auto;">
        <div style="background:white;border-radius:16px;max-width:600px;width:90%;padding:28px;box-shadow:0 10px 40px rgba(0,0,0,.3);margin:20px;">
            <h4 style="font-weight:800;color:#1e293b;margin-bottom:16px;"> Reschedule Appointment</h4>
            <p style="font-size:.9rem;color:#64748b;margin-bottom:20px;">Reschedule appointment for <strong id="adminRescheduleUserName"></strong></p>
            <input type="hidden" id="adminRescheduleApptId">
            <div style="margin-bottom:16px;">
                <label style="font-size:.85rem;font-weight:700;color:#374151;display:block;margin-bottom:6px;"> New Date <span style="color:red">*</span></label>
                <input type="date" id="adminRescheduleDate" required style="width:100%;border:1.5px solid #c7d2fe;border-radius:10px;padding:10px;font-size:.88rem;">
            </div>
            <div style="margin-bottom:16px;">
                <label style="font-size:.85rem;font-weight:700;color:#374151;display:block;margin-bottom:6px;"> New Time <span style="color:red">*</span></label>
                <select id="adminRescheduleTime" required disabled style="width:100%;border:1.5px solid #c7d2fe;border-radius:10px;padding:10px;font-size:.88rem;">
                    <option value="">Select date first</option>
                </select>
            </div>
            <div style="margin-bottom:20px;">
                <label style="font-size:.85rem;font-weight:700;color:#374151;display:block;margin-bottom:6px;"> Admin Notes </label>
                <textarea id="adminRescheduleNotes" rows="2" style="width:100%;border:1.5px solid #c7d2fe;border-radius:10px;padding:12px;font-size:.88rem;font-family:inherit;" placeholder="Reason for rescheduling..."></textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="hideAdminRescheduleModal()" class="btn-action btn-action-neutral" style="height:38px;padding:0 20px;">Cancel</button>
                <button type="button" onclick="submitAdminReschedule()" class="btn-action btn-action-primary" style="height:38px;padding:0 22px;">Confirm Reschedule</button>
            </div>
        </div>
    </div>

    <!-- RENEWAL ID PREVIEW MODAL -->
    <div id="renewalIdModal" style="display:none;position:fixed;inset:0;background:rgba(15,23,42,0.65);z-index:9999;align-items:center;justify-content:center;backdrop-filter:blur(4px);padding:16px;">
        <div style="background:white;border-radius:16px;width:100%;max-width:850px;max-height:90vh;display:flex;flex-direction:column;box-shadow:0 20px 40px rgba(0,0,0,0.25);overflow:hidden;">
            <div style="background:linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);color:white;padding:16px 22px;display:flex;align-items:center;justify-content:space-between;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="font-size:1.3rem;">🪪</span>
                    <div>
                        <h6 style="margin:0;font-weight:800;font-size:1rem;" id="renewalIdModalApplicant">Applicant Uploaded ID</h6>
                        <small style="opacity:.85;font-size:.78rem;">MSWDO ID Renewal Review</small>
                    </div>
                </div>
                <button type="button" onclick="closeRenewalIdModal()" style="background:transparent;border:none;color:white;font-size:1.4rem;line-height:1;cursor:pointer;">&times;</button>
            </div>
            <div id="renewalIdViewerContainer" style="padding:16px;overflow-y:auto;display:flex;align-items:center;justify-content:center;min-height:300px;background:#f8fafc;">
            </div>
            <div style="padding:12px 20px;border-top:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between;background:white;">
                <a id="renewalIdDownloadLink" href="#" target="_blank" download style="color:#2C3E8F;font-weight:700;font-size:.85rem;text-decoration:none;">
                    📥 Open / Download Original File
                </a>
                <div style="display:flex;align-items:center;gap:8px;">
                    <div id="renewalIdModalActions" style="display:flex;align-items:center;gap:8px;"></div>
                    <button type="button" onclick="closeRenewalIdModal()" class="btn-action btn-action-neutral">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Reject Renewal ID Modal --}}
    <div id="rejectRenewalIdModal"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:10000;align-items:center;justify-content:center;backdrop-filter:blur(2px);"
        onclick="if(event.target===this) closeRejectRenewalIdModal()">
        <div style="background:white;border-radius:16px;padding:0;max-width:480px;width:92%;box-shadow:0 12px 48px rgba(0,0,0,.25);overflow:hidden;">
            <div style="background:linear-gradient(135deg,#C41E24 0%,#8B1419 100%);color:white;padding:18px 24px;display:flex;align-items:center;gap:10px;">
                <span style="font-size:1.2rem;">⚠️</span>
                <div>
                    <h5 style="font-weight:800;margin:0;font-size:1rem;">Reject Renewal ID</h5>
                    <small style="opacity:.85;font-size:.78rem;">MSWDO ID Renewal Review</small>
                </div>
            </div>
            <div style="padding:20px 24px;">
                <div style="font-size:.88rem;color:#475569;margin-bottom:14px;background:#fef2f2;padding:10px 14px;border-radius:10px;border:1px solid #fecaca;">
                    <strong>Applicant:</strong> <span id="rejectRenewalApplicantName" style="color:#991b1b;font-weight:700;"></span>
                    <div style="margin-top:4px;font-size:.82rem;color:#1e40af;">The applicant will be notified to re-upload their Valid ID. Their existing appointment date and time will be preserved.</div>
                </div>
                <label style="font-size:.85rem;font-weight:700;color:#1e293b;display:block;margin-bottom:6px;">
                    Rejection Reason <span style="color:#C41E24;">*</span>
                </label>
                <select id="rejectRenewalReasonSelect" style="width:100%;border:1.5px solid #fca5a5;border-radius:10px;padding:10px 12px;font-size:.88rem;font-weight:600;margin-bottom:12px;color:#1e293b;background:white;" onchange="onRejectRenewalReasonChange()">
                    <option value="">-- Select a reason --</option>
                    <option value="Blurry or unreadable ID photo">Blurry or unreadable ID photo</option>
                    <option value="ID does not match applicant name">ID does not match applicant name</option>
                    <option value="Expired ID document">Expired ID document</option>
                    <option value="Wrong type of ID uploaded">Wrong type of ID uploaded</option>
                    <option value="Incomplete or cropped ID image">Incomplete or cropped ID image</option>
                    <option value="Other">Other (specify below)</option>
                </select>
                <label style="font-size:.85rem;font-weight:700;color:#1e293b;display:block;margin-bottom:6px;" id="rejectRenewalCommentsLabel">
                    Additional Comments <span style="font-size:.75rem;font-weight:400;color:#64748b;">(Optional)</span>
                </label>
                <textarea id="rejectRenewalNotes" rows="3" placeholder="Add details or instructions for the applicant..."
                    style="width:100%;border:1.5px solid #fca5a5;border-radius:10px;padding:12px;font-size:.88rem;resize:none;"></textarea>
                <div id="rejectRenewalError" style="color:#C41E24;font-size:.8rem;margin-top:6px;display:none;"></div>
            </div>
            <div style="padding:0 24px 20px;display:flex;gap:10px;">
                <button type="button" onclick="submitRejectRenewalId()" id="rejectRenewalSubmitBtn" class="btn-action btn-decline" style="height:38px;padding:0 22px;flex:1;">
                    Confirm Rejection
                </button>
                <button type="button" onclick="closeRejectRenewalIdModal()" class="btn-action btn-action-neutral" style="height:38px;padding:0 20px;">
                    Cancel
                </button>
            </div>
        </div>
    </div>


    {{-- Reusable Admin Reason Input Modal --}}
    <div id="adminReasonModal"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:10000;align-items:center;justify-content:center;backdrop-filter:blur(2px);"
        onclick="if(event.target===this) closeAdminReasonModal()">
        <div style="background:white;border-radius:16px;padding:0;max-width:460px;width:92%;box-shadow:0 12px 48px rgba(0,0,0,.25);overflow:hidden;">
            <div style="background:linear-gradient(135deg,#C41E24 0%,#8B1419 100%);color:white;padding:18px 24px;display:flex;align-items:center;gap:10px;">
                <span style="font-size:1.2rem;">&#9888;&#65039;</span>
                <div>
                    <h5 id="adminReasonModalTitle" style="font-weight:800;margin:0;font-size:1rem;">Rejection Reason</h5>
                    <small id="adminReasonModalSubtitle" style="opacity:.85;font-size:.78rem;"></small>
                </div>
            </div>
            <div style="padding:20px 24px;">
                <p id="adminReasonModalDesc" style="font-size:.88rem;color:#475569;margin-bottom:14px;line-height:1.5;"></p>
                <label style="font-size:.85rem;font-weight:700;color:#1e293b;display:block;margin-bottom:6px;">
                    Reason <span style="color:#C41E24;">*</span>
                </label>
                <textarea id="adminReasonTextarea" rows="3" placeholder="Enter the reason for rejection..."
                    style="width:100%;border:1.5px solid #fca5a5;border-radius:10px;padding:12px;font-size:.88rem;resize:none;box-sizing:border-box;"></textarea>
                <div id="adminReasonError" style="color:#C41E24;font-size:.8rem;margin-top:6px;display:none;"></div>
            </div>
            <div style="padding:0 24px 20px;display:flex;gap:10px;">
                <button type="button" onclick="submitAdminReason()" id="adminReasonSubmitBtn" class="btn-action btn-decline" style="height:38px;padding:0 22px;flex:1;">
                    Confirm Rejection
                </button>
                <button type="button" onclick="closeAdminReasonModal()" class="btn-action btn-action-neutral" style="height:38px;padding:0 20px;">
                    Cancel
                </button>
            </div>
        </div>
    </div>

</body>

</html>