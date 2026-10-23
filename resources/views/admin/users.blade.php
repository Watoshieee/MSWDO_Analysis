<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Management – {{ $municipality->name }} | MSWDO</title>
    <link rel="icon" type="image/jpeg" href="{{ $mswdoLogoUrl ?? '/images/logo_mswdo.jpg' }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @include('components.admin-colors')
    <style>
        html,
        body {
            overscroll-behavior: none;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary-blue: {{ $adminPrimaryColor ?? '#2C3E8F' }};
            --secondary-yellow: {{ $adminSecondaryColor ?? '#FDB913' }};
            --accent-red: {{ $adminAccentColor ?? '#C41E24' }};
            --primary-gradient: linear-gradient(135deg, var(--primary-blue) 0%, color-mix(in srgb, var(--primary-blue) 80%, black) 100%);
            --bg-light: #F8FAFC;
            --border-light: #E2E8F0;
            --text-dark: #1E293B;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body { background: var(--bg-light); font-family: 'Inter', sans-serif; color: var(--text-dark); min-height: 100vh; display: flex; flex-direction: column; }
        a { text-decoration: none; }

        /* NAVBAR */
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,0.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.55rem; color: white !important; display:flex; align-items:center; gap:12px; }
        .navbar-toggler { order: -1; }
        .navbar-brand { order: 0; margin-left: auto !important; margin-right: 0 !important; }
        @media (min-width: 992px) {
            .navbar-toggler { order: 0; }
            .navbar-brand { order: 0; margin-left: 0 !important; margin-right: auto !important; }
        }
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; transition: all 0.25s; border-radius: 8px; padding: 10px 18px !important; font-size: 0.85rem; white-space: nowrap; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .user-info { color:white; display:flex; align-items:center; gap:12px; background:rgba(255,255,255,0.1); padding:9px 22px; border-radius:40px; font-size:0.9rem; font-weight:600; }
        .logout-btn { background:transparent; border:2px solid rgba(255,255,255,0.8); color:white; border-radius:30px; padding:6px 18px; font-weight:700; transition:all 0.3s; font-size:0.88rem; cursor:pointer; }
        .logout-btn:hover { background:var(--secondary-yellow); color:var(--primary-blue); border-color:var(--secondary-yellow); }

        /* ── Top banner (Standardized with Dashboard) ── */
        .top-banner {
            background: linear-gradient(135deg, var(--primary-navy, {{ $adminPrimaryColor ?? '#2C3E8F' }}) 0%, #243088 100%) !important;
            color: #fff;
            box-shadow: 0 4px 20px rgba(26, 36, 112, 0.15);
        }
        .banner-inner {
            max-width: 1280px;
            margin: 0 auto;
            padding: 18px 32px 20px;
            box-sizing: border-box;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }
        .banner-badge {
            display: inline-block;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: .1em;
            color: var(--accent-gold, #f5b82e);
            border: 1px solid rgba(245, 184, 46, .5);
            border-radius: 99px;
            padding: 3px 10px;
            text-transform: uppercase;
        }
        .banner-title {
            margin: 6px 0 2px;
            font-size: 24px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.01em;
            line-height: 1.25;
        }
        .banner-sub {
            margin: 0;
            font-size: 12.5px;
            color: #d3d9f5;
        }
        .muni-tag {
            border: 1px solid rgba(245, 184, 46, .45);
            background: rgba(245, 184, 46, .1);
            border-radius: 10px;
            padding: 8px 18px;
            text-align: center;
        }
        .muni-tag-name {
            font-size: 17px;
            font-weight: 800;
            color: var(--accent-gold, #f5b82e);
            line-height: 1.2;
        }
        .muni-tag-label {
            font-size: 9.5px;
            letter-spacing: .14em;
            color: var(--accent-gold, #f5b82e);
            font-weight: 600;
            margin-top: 2px;
            text-transform: uppercase;
        }
        @media (max-width: 768px) {
            .banner-inner {
                padding-left: 16px;
                padding-right: 16px;
            }
        }

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
        .btn-view-id {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
        }
        .btn-view-id:hover { opacity: 0.92; }
        .btn-approve-acct {
            background: #16a34a;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 6px 11px;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.15s ease;
        }
        .btn-approve-acct:hover { background: #15803d; }
        .btn-decline-acct {
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 6px 11px;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.15s ease;
        }
        .btn-decline-acct:hover { background: #b02a37; }
        /* ── VIEW ID MODAL STYLES ── */
        .id-modal-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(4px);
            z-index: 10050;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: fadeInModal .2s ease-out;
        }
        @keyframes fadeInModal {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .id-modal-backdrop.show { display: flex !important; }
        .id-modal {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 820px;
            max-height: 92vh;
            overflow: hidden;
            box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.35), 0 0 0 1px rgba(226, 232, 240, 0.6);
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 10051;
            animation: slideUpModal .25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        @keyframes slideUpModal {
            from { transform: translateY(18px) scale(0.98); opacity: 0; }
            to { transform: translateY(0) scale(1); opacity: 1; }
        }
        .id-modal-header {
            background: var(--primary-gradient);
            color: white;
            padding: 20px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }
        .id-modal-header-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .id-modal-icon-badge {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: var(--secondary-yellow);
            flex-shrink: 0;
        }
        .id-modal-close-btn {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.2rem;
            transition: all 0.2s;
            line-height: 1;
        }
        .id-modal-close-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: scale(1.05);
        }

        .id-modal-body {
            padding: 24px;
            overflow-y: auto;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        /* User Details Card Grid */
        .user-details-card {
            background: #F8FAFC;
            border: 1px solid var(--border-light);
            border-radius: 14px;
            padding: 16px 18px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px 16px;
        }
        @media (max-width: 768px) {
            .user-details-card {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media (max-width: 480px) {
            .user-details-card {
                grid-template-columns: 1fr;
            }
        }
        .user-detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
            min-width: 0;
        }
        .user-detail-label {
            font-size: 0.68rem;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .user-detail-value {
            font-size: 0.88rem;
            font-weight: 700;
            color: #1e293b;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* File Info Bar */
        .id-file-info-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            background: #f1f5f9;
            border: 1px solid var(--border-light);
            border-radius: 11px;
            gap: 12px;
            flex-wrap: wrap;
        }
        .id-file-info-left {
            display: flex;
            align-items: center;
            gap: 8px;
            min-width: 0;
            max-width: calc(100% - 170px);
        }
        .id-file-name-text {
            font-size: 0.82rem;
            font-weight: 600;
            color: #334155;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .id-file-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }
        .btn-file-quick {
            font-size: 0.76rem;
            font-weight: 600;
            padding: 5px 11px;
            border-radius: 8px;
            border: 1px solid var(--border-light);
            background: white;
            color: #334155;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s;
            text-decoration: none;
            cursor: pointer;
        }
        .btn-file-quick:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        /* ID Preview Area */
        .id-preview-wrap {
            border: 1.5px dashed #cbd5e1;
            border-radius: 14px;
            background: #f8fafc;
            min-height: 280px;
            max-height: 440px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        .id-preview-img {
            max-height: 420px;
            width: auto;
            max-width: 100%;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.2s;
        }
        .id-preview-frame {
            width: 100%;
            height: 420px;
            border: none;
            border-radius: 8px;
            background: white;
        }
        .id-preview-empty {
            text-align: center;
            padding: 40px 20px;
            color: #94a3b8;
        }
        .id-preview-empty i {
            font-size: 2.5rem;
            color: #cbd5e1;
            margin-bottom: 10px;
            display: block;
        }

        /* Footer & Actions */
        .id-modal-footer {
            padding: 16px 24px;
            background: #f8fafc;
            border-top: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }
        .id-modal-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            margin-left: auto;
        }
        .btn-modal-close {
            background: white;
            color: #475569;
            border: 1.5px solid var(--border-light);
            border-radius: 10px;
            padding: 9px 18px;
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-modal-close:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #1e293b;
        }
        .btn-approve-id {
            background: #16a34a;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 9px 20px;
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(22, 163, 74, 0.2);
        }
        .btn-approve-id:hover {
            background: #15803d;
            box-shadow: 0 4px 14px rgba(22, 163, 74, 0.3);
            transform: translateY(-1px);
        }
        .btn-decline-id {
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 9px 20px;
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.2);
        }
        .btn-decline-id:hover {
            background: #b02a37;
            box-shadow: 0 4px 14px rgba(220, 53, 69, 0.3);
            transform: translateY(-1px);
        }
        .btn-modal-cancel {
            background: #e2e8f0;
            color: #475569;
            border: none;
            border-radius: 10px;
            padding: 9px 18px;
            font-size: 0.84rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-modal-cancel:hover {
            background: #cbd5e1;
            color: #1e293b;
        }
        .btn-view-id {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 6px 13px;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            box-shadow: 0 2px 6px rgba(44, 62, 143, 0.15);
        }
        .btn-view-id:hover {
            opacity: 0.94;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(44, 62, 143, 0.25);
            color: white;
        }
        .confirm-modal { max-width: 480px; }
        .confirm-modal-body { padding: 24px 22px 22px; text-align: center; }
        .confirm-icon {
            width: 56px; height: 56px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px; font-size: 1.6rem;
        }
        .confirm-icon.approve { background: #dcfce7; color: #16a34a; }
        .confirm-icon.decline { background: #fee2e2; color: #dc3545; }
        .confirm-icon.success { background: #dcfce7; color: #16a34a; }
        .confirm-icon.error { background: #fee2e2; color: #dc3545; }
        .confirm-title { font-size: 1.05rem; font-weight: 800; color: #1e293b; margin-bottom: 8px; }
        .confirm-message { font-size: 0.88rem; color: #64748b; line-height: 1.65; margin-bottom: 0; }
        .confirm-modal-actions {
            display: flex; gap: 10px; justify-content: center;
            margin-top: 22px; flex-wrap: wrap;
        }
        .decline-reason-list { display: none; }

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
        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,0.9); text-align: center; padding: 20px; font-size: 0.85rem; margin-top: 40px; }

        /* ── Navy loading overlay for async/admin actions ───────────────────── */
        .ui-loading-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(2px);
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
            padding: 24px 20px;
            text-align: center;
            animation: idModalSlideUp 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .ui-loading-spinner {
            width: 44px;
            height: 44px;
            margin: 0 auto 12px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, .25);
            border-top-color: #FDB913;
            animation: uiSpin .8s linear infinite;
        }

        .ui-loading-title {
            font-weight: 800;
            font-size: 1rem;
            letter-spacing: .01em;
            color: #ffffff;
        }

        .ui-loading-sub {
            margin-top: 6px;
            opacity: .88;
            font-size: .82rem;
            color: #e2e8f0;
            line-height: 1.4;
        }

        @keyframes uiSpin {
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    @include('components.admin-navbar')

    {{-- Top Hero Banner (Standardized with Dashboard) --}}
    <div class="top-banner">
        <div class="banner-inner">
            <div>
                <span class="banner-badge">USERS MANAGEMENT</span>
                <h1 class="banner-title">Users Management</h1>
                <p class="banner-sub">View all residents registered in the {{ $municipality->name }} MSWDO portal.</p>
            </div>
            <div class="muni-tag">
                <div class="muni-tag-name">{{ $municipality->name }}</div>
                <div class="muni-tag-label">MUNICIPALITY</div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div style="flex:1;">
        <div class="container mt-4">

            @include('components.admin-notification')


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
                    <div class="stat-label">Pending Accounts</div>
                    <div class="stat-value" style="color:#d97706;">{{ $users->where('status', 'pending')->count() }}</div>
                    <div class="stat-sub">Awaiting account approval</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-label">Active Users</div>
                    <div class="stat-value" style="color:#16a34a;">{{ $users->where('status', 'active')->count() }}</div>
                    <div class="stat-sub">Approved and verified</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-card">
                    <div class="stat-label">Pending ID Review</div>
                    <div class="stat-value">{{ $users->where('id_verification_status', 'pending')->count() }}</div>
                    <div class="stat-sub">Awaiting valid ID approval</div>
                </div>
            </div>
        </div>

        <!-- SEARCH & FILTER BAR -->
        <div class="filter-bar mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-md-4">
                    <div class="search-wrap">
                        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        <input type="text" id="userSearch" class="search-input" placeholder="Search by name, email or barangay…" oninput="filterUsers()">
                    </div>
                </div>
                <div class="col-md-2">
                    <select id="statusFilter" class="search-input" style="padding-left:14px;cursor:pointer;" onchange="filterUsers()">
                        <option value="pending">Pending approval</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="appFilter" class="search-input" style="padding-left:14px;cursor:pointer;" onchange="filterUsers()">
                        <option value="with">With applications</option>
                        <option value="without">No applications yet</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select id="idFilter" class="search-input" style="padding-left:14px;cursor:pointer;" onchange="filterUsers()">
                        <option value="approved">Approved ID</option>
                        <option value="pending">Pending ID review</option>
                    </select>
                </div>
                <div class="col-md-2 text-end">
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
                            <th>Account Status</th>
                            <th>ID Verification</th>
                            <th>Actions</th>
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
                                @if($user->status === 'active')
                                    <span class="badge-pill badge-active">Active</span>
                                @elseif($user->status === 'pending')
                                    <span class="badge-pill badge-pending">Pending</span>
                                @elseif($user->status === 'inactive')
                                    <span class="badge-pill badge-rejected">Inactive</span>
                                @else
                                    <span class="badge-pill badge-pending">{{ ucfirst($user->status ?? 'Pending') }}</span>
                                @endif
                            </td>
                            <td>
                                @if($user->id_verification_status === 'pending')
                                    <span class="badge-pill badge-pending">Pending Review</span>
                                @elseif($user->id_verification_status === 'approved')
                                    <span class="badge-pill badge-approved">Approved</span>
                                @elseif($user->id_verification_status === 'rejected')
                                    <span class="badge-pill badge-rejected">Declined</span>
                                @else
                                    <span class="badge-pill badge-zero">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-1 flex-wrap">
                                    @if($user->status === 'pending')
                                        <button type="button" class="btn-approve-acct"
                                            data-user-id="{{ $user->id }}"
                                            data-full-name="{{ $user->full_name }}"
                                            title="Approve Account">
                                            Approve
                                        </button>
                                        <button type="button" class="btn-decline-acct"
                                            data-user-id="{{ $user->id }}"
                                            data-full-name="{{ $user->full_name }}"
                                            title="Decline Account">
                                            Decline
                                        </button>
                                    @endif
                                    @php
                                        $idStatus = $user->id_verification_status;
                                        $showViewId = $user->valid_id_path && in_array($idStatus, ['pending', 'rejected']);
                                    @endphp
                                    @if($showViewId)
                                        <button type="button" class="btn-view-id"
                                            data-user-id="{{ $user->id }}"
                                            data-full-name="{{ $user->full_name }}"
                                            data-municipality="{{ $user->municipality }}"
                                            data-barangay="{{ $user->barangay ?? '—' }}"
                                            data-id-type="{{ $user->valid_id_type ?? 'Government-Issued ID' }}"
                                            data-created-at="{{ optional($user->created_at)->format('M d, Y') ?? '—' }}"
                                            data-status="{{ $user->id_verification_status ?? 'pending' }}"
                                            data-filename="{{ $user->valid_id_filename ?? '' }}"
                                            data-file-url="{{ route('admin.users.valid-id', $user->id) }}"
                                            title="View Valid ID">
                                            <i class="bi bi-eye"></i> View ID
                                        </button>
                                    @endif
                                    @if($user->status !== 'pending' && !$showViewId)
                                        <span style="color:#cbd5e1;font-size:.82rem;">—</span>
                                    @endif
                                </div>
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
    </div>

    <div class="footer-strip"></div>

    @include('components.admin-notification-modal')
    @include('components.admin-chat-modal')
    @include('components.admin-settings-modal')

    <div id="idModalBackdrop" class="id-modal-backdrop" aria-hidden="true">
        <div class="id-modal" role="dialog" aria-modal="true" aria-labelledby="idModalTitle">
            <!-- Modal Header -->
            <div class="id-modal-header">
                <div class="id-modal-header-title">
                    <div class="id-modal-icon-badge">
                        <i class="bi bi-person-vcard-fill"></i>
                    </div>
                    <div>
                        <div id="idModalTitle" style="font-size:1.1rem;font-weight:800;letter-spacing:-0.01em;">Valid ID Review</div>
                        <div id="idModalUserSubtitle" style="font-size:.82rem;opacity:.9;margin-top:2px;display:flex;align-items:center;gap:8px;">
                            <span id="idModalUserName">Resident</span>
                            <span id="idModalStatusBadge" class="badge-pill badge-pending" style="font-size:0.65rem;padding:2px 8px;">Pending Review</span>
                        </div>
                    </div>
                </div>
                <button type="button" id="closeIdModalBtn" class="id-modal-close-btn" title="Close Modal">&times;</button>
            </div>

            <!-- Modal Body -->
            <div class="id-modal-body">
                <!-- User Details Section -->
                <div class="user-details-card">
                    <div class="user-detail-item">
                        <span class="user-detail-label">
                            <i class="bi bi-person-fill text-primary"></i> Full Name
                        </span>
                        <span class="user-detail-value" id="modalDetailName">—</span>
                    </div>
                    <div class="user-detail-item">
                        <span class="user-detail-label">
                            <i class="bi bi-geo-alt-fill text-danger"></i> Location
                        </span>
                        <span class="user-detail-value" id="modalDetailLocation">—</span>
                    </div>
                    <div class="user-detail-item">
                        <span class="user-detail-label">
                            <i class="bi bi-card-heading text-primary"></i> Valid ID Type
                        </span>
                        <span class="user-detail-value" id="modalDetailIdType" style="color:var(--primary-blue);">—</span>
                    </div>
                    <div class="user-detail-item">
                        <span class="user-detail-label">
                            <i class="bi bi-calendar-event text-secondary"></i> Registered On
                        </span>
                        <span class="user-detail-value" id="modalDetailRegisteredAt">—</span>
                    </div>
                </div>

                <!-- File Info Bar -->
                <div id="idFileInfoBar" class="id-file-info-bar">
                    <div class="id-file-info-left">
                        <i class="bi bi-file-earmark-image fs-5 text-primary" id="modalFileIcon"></i>
                        <span class="id-file-name-text" id="modalFileNameText" title="Filename">valid_id.jpg</span>
                        <span id="modalFileTypeBadge" class="badge bg-light text-secondary border" style="font-size:0.68rem;font-weight:700;">IMAGE</span>
                    </div>
                    <div class="id-file-actions">
                        <a href="#" id="modalOpenExternalBtn" target="_blank" class="btn-file-quick" title="Open in new tab">
                            <i class="bi bi-box-arrow-up-right"></i> Open
                        </a>
                        <a href="#" id="modalDownloadBtn" download class="btn-file-quick" title="Download file">
                            <i class="bi bi-download"></i> Download
                        </a>
                    </div>
                </div>

                <!-- Preview Area -->
                <div id="idPreviewContainer" class="id-preview-wrap">
                    <!-- Dynamic preview inserted by JS (img, iframe, or empty state) -->
                </div>

                <!-- Decline Reason Section (when declining) -->
                <div id="declineReasonWrap" style="display:none;background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:16px;">
                    <label style="font-size:.82rem;font-weight:800;color:#991b1b;display:flex;align-items:center;gap:6px;margin-bottom:8px;">
                        <i class="bi bi-exclamation-triangle-fill"></i> Reason for Declining Valid ID <span style="color:#dc3545;">*</span>
                    </label>
                    <select id="decline_reason_select" name="decline_reason"
                        style="width:100%;border:1.5px solid #fca5a5;border-radius:10px;padding:10px 14px;font-size:0.88rem;font-family:'Inter',sans-serif;color:#334155;background:#fff;outline:none;cursor:pointer;transition:border-color .2s;"
                        onchange="handleDeclineReasonChange()">
                        <option value="" disabled selected hidden>Select a reason…</option>
                        @foreach(\App\Models\User::ID_DECLINE_REASONS as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <textarea id="decline_reason_custom"
                        placeholder="Please specify the reason in detail…"
                        style="display:none;width:100%;margin-top:10px;border:1.5px solid #fca5a5;border-radius:10px;padding:10px 14px;font-size:0.88rem;font-family:'Inter',sans-serif;color:#334155;background:#fff;outline:none;resize:vertical;min-height:80px;transition:border-color .2s;"
                        oninput="this.style.borderColor=this.value.trim()?'#16a34a':'#fca5a5';"></textarea>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="id-modal-footer">
                <div class="text-muted" style="font-size:0.80rem;">
                    <i class="bi bi-shield-check text-success me-1"></i>
                    <span id="idModalVerificationNote">Admin document verification</span>
                </div>
                <div class="id-modal-actions">
                    <button type="button" class="btn-modal-cancel" id="cancelDeclineBtn" style="display:none;">Cancel</button>
                    <button type="button" class="btn-decline-id" id="declineToggleBtn"><i class="bi bi-x-circle me-1"></i> Decline</button>
                    <button type="button" class="btn-decline-id" id="confirmDeclineBtn" style="display:none;"><i class="bi bi-send-x me-1"></i> Confirm Decline</button>
                    <button type="button" class="btn-approve-id" id="approveIdBtn"><i class="bi bi-check-circle me-1"></i> Approve ID</button>
                    <button type="button" class="btn-modal-close" id="modalCloseActionBtn"><i class="bi bi-x-lg me-1"></i> Close</button>
                </div>
            </div>
        </div>
    </div>

    <div id="confirmActionBackdrop" class="id-modal-backdrop" aria-hidden="true" style="z-index:10060;">
        <div class="id-modal confirm-modal" role="dialog" aria-modal="true">
            <div class="id-modal-header">
                <div id="confirmActionTitle" style="font-size:1.05rem;font-weight:800;">Confirm Action</div>
                <button type="button" id="closeConfirmActionBtn" class="id-modal-close-btn" title="Close">&times;</button>
            </div>
            <div class="confirm-modal-body">
                <div id="confirmActionIcon" class="confirm-icon approve">✓</div>
                <div id="confirmActionHeading" class="confirm-title">Are you sure?</div>
                <p id="confirmActionMessage" class="confirm-message"></p>
                <div class="confirm-modal-actions">
                    <button type="button" class="btn-modal-cancel" id="cancelConfirmActionBtn">Cancel</button>
                    <button type="button" class="btn-approve-id" id="proceedConfirmActionBtn" style="display:none;">Approve</button>
                    <button type="button" class="btn-decline-id" id="proceedDeclineConfirmBtn" style="display:none;">Confirm Decline</button>
                </div>
            </div>
        </div>
    </div>

    <div id="resultActionBackdrop" class="id-modal-backdrop" aria-hidden="true" style="z-index:10070;">
        <div class="id-modal confirm-modal" role="dialog" aria-modal="true">
            <div class="id-modal-header">
                <div id="resultActionTitle" style="font-size:1.05rem;font-weight:800;">Notification</div>
                <button type="button" id="closeResultActionBtn" class="id-modal-close-btn" title="Close">&times;</button>
            </div>
            <div class="confirm-modal-body">
                <div id="resultActionIcon" class="confirm-icon success">✓</div>
                <div id="resultActionHeading" class="confirm-title">Success</div>
                <p id="resultActionMessage" class="confirm-message"></p>
                <div class="confirm-modal-actions">
                    <button type="button" class="btn-approve-id" id="okResultActionBtn">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- UI Loading Overlay -->
    <div id="uiLoadingBackdrop" class="ui-loading-backdrop" aria-hidden="true">
        <div class="ui-loading-box" role="status" aria-live="polite">
            <div class="ui-loading-spinner"></div>
            <div class="ui-loading-title">Processing Request</div>
            <div class="ui-loading-sub">Please wait while we update records.</div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    let _searchDebounce;
    let currentIdUserId = null;
    let _isProcessingIdAction = false;

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

    const approveIdUrlTemplate = @json(route('admin.users.approve-id', ['id' => '__ID__']));
    const declineIdUrlTemplate = @json(route('admin.users.decline-id', ['id' => '__ID__']));
    const approveAccountUrlTemplate = @json(route('admin.users.approve-account', ['id' => '__ID__']));
    const declineAccountUrlTemplate = @json(route('admin.users.decline-account', ['id' => '__ID__']));
    const validIdUrlTemplate = @json(route('admin.users.valid-id', ['id' => '__ID__']));
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

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

        let statusBadge = '<span class="badge-pill badge-pending">Pending</span>';
        if (user.status === 'active') {
            statusBadge = '<span class="badge-pill badge-active">Active</span>';
        } else if (user.status === 'inactive') {
            statusBadge = '<span class="badge-pill badge-rejected">Inactive</span>';
        } else if (user.status === 'pending') {
            statusBadge = '<span class="badge-pill badge-pending">Pending</span>';
        }

        let idBadge = '<span class="badge-pill badge-zero">N/A</span>';
        if (user.id_verification_status === 'pending') idBadge = '<span class="badge-pill badge-pending">Pending Review</span>';
        else if (user.id_verification_status === 'approved') idBadge = '<span class="badge-pill badge-approved">Approved</span>';
        else if (user.id_verification_status === 'rejected') idBadge = '<span class="badge-pill badge-rejected">Declined</span>';

        let actionBtns = [];
        if (user.status === 'pending') {
            actionBtns.push(`<button type="button" class="btn-approve-acct"
                data-user-id="${user.id}"
                data-full-name="${escapeHtml(user.full_name || '')}">Approve</button>`);
            actionBtns.push(`<button type="button" class="btn-decline-acct"
                data-user-id="${user.id}"
                data-full-name="${escapeHtml(user.full_name || '')}">Decline</button>`);
        }
        const idStatus = user.id_verification_status;
        const showViewId = user.valid_id_path && (idStatus === 'pending' || idStatus === 'rejected');
        if (showViewId) {
            const fileUrl = validIdUrlTemplate.replace('__ID__', user.id);
            const joinedFormatted = user.created_at_formatted || (user.created_at ? new Date(user.created_at).toLocaleDateString('en-US', {month:'short', day:'2-digit', year:'numeric'}) : '—');
            actionBtns.push(`<button type="button" class="btn-view-id"
                data-user-id="${user.id}"
                data-full-name="${escapeHtml(user.full_name || '')}"
                data-municipality="${escapeHtml(user.municipality || '')}"
                data-barangay="${escapeHtml(user.barangay || '—')}"
                data-id-type="${escapeHtml(user.valid_id_type || 'Government-Issued ID')}"
                data-created-at="${escapeHtml(joinedFormatted)}"
                data-status="${escapeHtml(user.id_verification_status || 'pending')}"
                data-filename="${escapeHtml(user.valid_id_filename || '')}"
                data-file-url="${fileUrl}"
                title="View Valid ID"><i class="bi bi-eye"></i> View ID</button>`);
        }

        const actionCell = actionBtns.length
            ? `<div class="d-flex align-items-center gap-1 flex-wrap">${actionBtns.join('')}</div>`
            : '<span style="color:#cbd5e1;font-size:.82rem;">—</span>';

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
                <td>${idBadge}</td>
                <td>${actionCell}</td>
            </tr>
        `;
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');
    }

    function openIdModalFromButton(btn) {
        const userId = btn.dataset.userId;
        const fullName = btn.dataset.fullName || 'Registered User';
        const municipality = btn.dataset.municipality || '';
        const barangay = btn.dataset.barangay || '';
        const idType = btn.dataset.idType || 'Government-Issued ID';
        const createdAt = btn.dataset.createdAt || '—';
        const status = btn.dataset.status || 'pending';
        const filename = btn.dataset.filename || '';
        const fileUrl = btn.dataset.fileUrl || validIdUrlTemplate.replace('__ID__', userId);

        currentIdUserId = userId;

        // User Header Name
        const nameHeader = document.getElementById('idModalUserName');
        if (nameHeader) nameHeader.textContent = fullName;

        // Header Status Badge
        const statusBadgeEl = document.getElementById('idModalStatusBadge');
        if (statusBadgeEl) {
            if (status === 'approved') {
                statusBadgeEl.className = 'badge-pill badge-approved';
                statusBadgeEl.textContent = 'Approved';
            } else if (status === 'rejected') {
                statusBadgeEl.className = 'badge-pill badge-rejected';
                statusBadgeEl.textContent = 'Declined';
            } else {
                statusBadgeEl.className = 'badge-pill badge-pending';
                statusBadgeEl.textContent = 'Pending Review';
            }
        }

        // Details grid: Full Name
        const detailNameEl = document.getElementById('modalDetailName');
        if (detailNameEl) detailNameEl.textContent = fullName;

        // Location: Barangay, Municipality
        const detailLocationEl = document.getElementById('modalDetailLocation');
        if (detailLocationEl) {
            if (barangay && barangay !== '—' && municipality) {
                detailLocationEl.textContent = `Brgy. ${barangay}, ${municipality}`;
            } else if (municipality) {
                detailLocationEl.textContent = municipality;
            } else {
                detailLocationEl.textContent = barangay || '—';
            }
        }

        // Valid ID Type
        const detailIdTypeEl = document.getElementById('modalDetailIdType');
        if (detailIdTypeEl) detailIdTypeEl.textContent = idType;

        // Registration Date
        const detailRegisteredEl = document.getElementById('modalDetailRegisteredAt');
        if (detailRegisteredEl) detailRegisteredEl.textContent = createdAt;

        // Verification note & action buttons based on verification status
        const noteEl = document.getElementById('idModalVerificationNote');
        const approveBtn = document.getElementById('approveIdBtn');
        const declineBtn = document.getElementById('declineToggleBtn');

        if (status === 'pending') {
            if (approveBtn) approveBtn.style.display = 'inline-flex';
            if (declineBtn) declineBtn.style.display = 'inline-flex';
            if (noteEl) noteEl.innerHTML = '<i class="bi bi-clock-history text-warning me-1"></i> Awaiting admin verification';
        } else if (status === 'approved') {
            if (approveBtn) approveBtn.style.display = 'none';
            if (declineBtn) declineBtn.style.display = 'none';
            if (noteEl) noteEl.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i> Document verified & approved';
        } else {
            if (approveBtn) approveBtn.style.display = 'none';
            if (declineBtn) declineBtn.style.display = 'none';
            if (noteEl) noteEl.innerHTML = '<i class="bi bi-x-circle-fill text-danger me-1"></i> Document declined';
        }

        // Reset decline controls
        cancelDeclineForm();

        // File info bar & preview
        const fileInfoBar = document.getElementById('idFileInfoBar');
        const fileNameEl = document.getElementById('modalFileNameText');
        const fileTypeBadge = document.getElementById('modalFileTypeBadge');
        const fileIcon = document.getElementById('modalFileIcon');
        const openBtn = document.getElementById('modalOpenExternalBtn');
        const downloadBtn = document.getElementById('modalDownloadBtn');
        const previewContainer = document.getElementById('idPreviewContainer');

        const cleanFilename = filename || (fileUrl ? fileUrl.split('/').pop() : 'valid_id_document');
        const lowerName = cleanFilename.toLowerCase();
        const isPdf = lowerName.endsWith('.pdf');

        if (fileUrl) {
            if (fileInfoBar) fileInfoBar.style.display = 'flex';
            if (fileNameEl) {
                fileNameEl.textContent = cleanFilename;
                fileNameEl.title = cleanFilename;
            }
            if (openBtn) openBtn.href = fileUrl;
            if (downloadBtn) {
                downloadBtn.href = fileUrl;
                downloadBtn.setAttribute('download', cleanFilename);
            }

            if (isPdf) {
                if (fileIcon) fileIcon.className = 'bi bi-file-earmark-pdf-fill fs-5 text-danger';
                if (fileTypeBadge) {
                    fileTypeBadge.textContent = 'PDF';
                    fileTypeBadge.className = 'badge bg-danger-subtle text-danger border';
                }
                previewContainer.innerHTML = `
                    <iframe src="${fileUrl}" class="id-preview-frame" title="Valid ID Preview - ${escapeHtml(cleanFilename)}"></iframe>
                `;
            } else {
                if (fileIcon) fileIcon.className = 'bi bi-file-earmark-image-fill fs-5 text-primary';
                if (fileTypeBadge) {
                    fileTypeBadge.textContent = 'IMAGE';
                    fileTypeBadge.className = 'badge bg-light text-primary border';
                }
                previewContainer.innerHTML = `
                    <img src="${fileUrl}" alt="Valid ID - ${escapeHtml(fullName)}" class="id-preview-img" onerror="this.onerror=null;this.parentElement.innerHTML='<div class=\\'id-preview-empty\\'><i class=\\'bi bi-exclamation-triangle text-warning fs-1\\'></i><div style=\\'font-weight:700;color:#1e293b;margin-top:8px;\\'>Unable to load image preview</div><p class=\\'text-muted small mb-2\\'>The file could not be rendered directly. You may open or download it using the buttons above.</p><a href=\\'${fileUrl}\\' target=\\'_blank\\' class=\\'btn-file-quick\\'><i class=\\'bi bi-box-arrow-up-right\\'></i> Open in New Tab</a></div>';">
                `;
            }
        } else {
            if (fileInfoBar) fileInfoBar.style.display = 'none';
            previewContainer.innerHTML = `
                <div class="id-preview-empty">
                    <i class="bi bi-file-earmark-x text-muted fs-1"></i>
                    <div style="font-weight:700;color:#1e293b;margin-top:8px;">No Valid ID Uploaded</div>
                    <p class="text-muted small mb-0">This resident does not have an uploaded valid ID document on file.</p>
                </div>
            `;
        }

        const backdrop = document.getElementById('idModalBackdrop');
        backdrop.classList.add('show');
        backdrop.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }

    function closeIdModal() {
        if (_isProcessingIdAction) return;
        const backdrop = document.getElementById('idModalBackdrop');
        if (backdrop) {
            backdrop.classList.remove('show');
            backdrop.setAttribute('aria-hidden', 'true');
        }
        document.body.style.overflow = 'auto';
        const container = document.getElementById('idPreviewContainer');
        if (container) container.innerHTML = '';
        currentIdUserId = null;
        cancelDeclineForm();
    }

    function toggleDeclineForm() {
        if (_isProcessingIdAction) return;
        document.getElementById('declineReasonWrap').style.display = 'block';
        document.getElementById('confirmDeclineBtn').style.display = 'inline-flex';
        document.getElementById('cancelDeclineBtn').style.display = 'inline-flex';
        document.getElementById('declineToggleBtn').style.display = 'none';
        document.getElementById('approveIdBtn').style.display = 'none';
    }

    function cancelDeclineForm() {
        if (_isProcessingIdAction) return;
        const wrap = document.getElementById('declineReasonWrap');
        if (wrap) wrap.style.display = 'none';
        const confirmBtn = document.getElementById('confirmDeclineBtn');
        if (confirmBtn) confirmBtn.style.display = 'none';
        const cancelBtn = document.getElementById('cancelDeclineBtn');
        if (cancelBtn) cancelBtn.style.display = 'none';
        const declineBtn = document.getElementById('declineToggleBtn');
        if (declineBtn && currentIdUserId) declineBtn.style.display = 'inline-flex';
        const approveBtn = document.getElementById('approveIdBtn');
        if (approveBtn && currentIdUserId) approveBtn.style.display = 'inline-flex';
        const sel = document.getElementById('decline_reason_select');
        if (sel) {
            sel.value = '';
            sel.style.borderColor = '#fca5a5';
        }
        const custom = document.getElementById('decline_reason_custom');
        if (custom) {
            custom.style.display = 'none';
            custom.value = '';
        }
    }

    let pendingConfirmAction = null;

    function showConfirmModal({ title, heading, message, type, action }) {
        pendingConfirmAction = action;
        document.getElementById('confirmActionTitle').textContent = title;
        document.getElementById('confirmActionHeading').textContent = heading;
        document.getElementById('confirmActionMessage').textContent = message;

        const icon = document.getElementById('confirmActionIcon');
        icon.className = 'confirm-icon ' + (type === 'decline' ? 'decline' : 'approve');
        icon.textContent = type === 'decline' ? '✕' : '✓';

        document.getElementById('proceedConfirmActionBtn').style.display = type === 'approve' ? 'inline-block' : 'none';
        document.getElementById('proceedDeclineConfirmBtn').style.display = type === 'decline' ? 'inline-block' : 'none';

        const backdrop = document.getElementById('confirmActionBackdrop');
        backdrop.classList.add('show');
        backdrop.setAttribute('aria-hidden', 'false');
    }

    function closeConfirmModal() {
        const backdrop = document.getElementById('confirmActionBackdrop');
        backdrop.classList.remove('show');
        backdrop.setAttribute('aria-hidden', 'true');
        pendingConfirmAction = null;
    }

    function showResultModal({ title, heading, message, type }) {
        document.getElementById('resultActionTitle').textContent = title;
        document.getElementById('resultActionHeading').textContent = heading;
        document.getElementById('resultActionMessage').textContent = message;

        const icon = document.getElementById('resultActionIcon');
        icon.className = 'confirm-icon ' + (type === 'error' ? 'error' : 'success');
        icon.textContent = type === 'error' ? '✕' : '✓';

        const okBtn = document.getElementById('okResultActionBtn');
        okBtn.className = type === 'error' ? 'btn-decline-id' : 'btn-approve-id';

        const backdrop = document.getElementById('resultActionBackdrop');
        backdrop.classList.add('show');
        backdrop.setAttribute('aria-hidden', 'false');
    }

    function closeResultModal() {
        const backdrop = document.getElementById('resultActionBackdrop');
        backdrop.classList.remove('show');
        backdrop.setAttribute('aria-hidden', 'true');
    }

    function requestApproveId() {
        if (!currentIdUserId) return;
        showConfirmModal({
            title: 'Approve Valid ID',
            heading: 'Approve this valid ID?',
            message: 'This will activate the user account and allow them to login to the portal.',
            type: 'approve',
            action: 'approve_id',
        });
    }

    function handleDeclineReasonChange() {
        const sel = document.getElementById('decline_reason_select');
        const custom = document.getElementById('decline_reason_custom');
        custom.style.display = sel.value === 'other' ? 'block' : 'none';
        if (sel.value !== 'other') custom.value = '';
        sel.style.borderColor = sel.value ? '#16a34a' : '#e2e8f0';
    }

    function getSelectedDeclineReason() {
        const sel = document.getElementById('decline_reason_select');
        if (!sel.value) return '';
        if (sel.value === 'other') {
            const custom = document.getElementById('decline_reason_custom').value.trim();
            return custom ? 'other:' + custom : '';
        }
        return sel.value;
    }

    function getDeclineReasonLabel() {
        const sel = document.getElementById('decline_reason_select');
        if (!sel.value) return '';
        if (sel.value === 'other') {
            const custom = document.getElementById('decline_reason_custom').value.trim();
            return custom || 'Other reason';
        }
        return sel.options[sel.selectedIndex].text;
    }

    function requestDeclineId() {
        if (!currentIdUserId) return;
        const reason = getSelectedDeclineReason();
        if (!reason) {
            showResultModal({
                title: 'Missing Information',
                heading: 'Reason required',
                message: 'Please select a reason for declining the valid ID before continuing.',
                type: 'error',
            });
            return;
        }
        const reasonLabel = getDeclineReasonLabel();
        showConfirmModal({
            title: 'Decline Valid ID',
            heading: 'Decline this valid ID?',
            message: 'The user will be removed from the system and notified by email. Reason: ' + reasonLabel,
            type: 'decline',
            action: 'decline_id',
        });
    }

    async function submitApproveId() {
        if (_isProcessingIdAction || !currentIdUserId) return;
        _isProcessingIdAction = true;

        const approveBtn = document.getElementById('approveIdBtn');
        const declineBtn = document.getElementById('declineToggleBtn');
        const modalCloseBtn = document.getElementById('modalCloseActionBtn');
        const topCloseBtn = document.getElementById('closeIdModalBtn');

        if (approveBtn) {
            approveBtn.disabled = true;
            approveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Approving...';
        }
        if (declineBtn) declineBtn.disabled = true;
        if (modalCloseBtn) modalCloseBtn.disabled = true;
        if (topCloseBtn) topCloseBtn.disabled = true;

        showLoading('Approving Valid ID', 'Activating user account and sending approval notification...');

        try {
            const res = await fetch(approveIdUrlTemplate.replace('__ID__', currentIdUserId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            let data = {};
            try { data = await res.json(); } catch (e) {}

            hideLoading();

            if (!res.ok) {
                showResultModal({
                    title: 'Approval Failed',
                    heading: 'Could not approve',
                    message: data.message || 'Failed to approve valid ID. Please try again.',
                    type: 'error',
                });
                return;
            }

            _isProcessingIdAction = false;
            closeIdModal();
            fetchUsersAjax();
            showResultModal({
                title: 'Valid ID Approved',
                heading: 'Successfully approved',
                message: data.message || 'The user account has been activated. An approval email was sent to the user.',
                type: 'success',
            });
        } catch (err) {
            hideLoading();
            showResultModal({
                title: 'Approval Failed',
                heading: 'Network error',
                message: 'An unexpected network error occurred. Please check your connection and try again.',
                type: 'error',
            });
        } finally {
            _isProcessingIdAction = false;
            if (approveBtn) {
                approveBtn.disabled = false;
                approveBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Approve ID';
            }
            if (declineBtn) declineBtn.disabled = false;
            if (modalCloseBtn) modalCloseBtn.disabled = false;
            if (topCloseBtn) topCloseBtn.disabled = false;
        }
    }

    async function submitDeclineId() {
        if (_isProcessingIdAction || !currentIdUserId) return;
        const reason = getSelectedDeclineReason();
        if (!reason) return;

        _isProcessingIdAction = true;

        const confirmDeclineBtn = document.getElementById('confirmDeclineBtn');
        const declineToggleBtn = document.getElementById('declineToggleBtn');
        const cancelDeclineBtn = document.getElementById('cancelDeclineBtn');
        const approveBtn = document.getElementById('approveIdBtn');
        const modalCloseBtn = document.getElementById('modalCloseActionBtn');
        const topCloseBtn = document.getElementById('closeIdModalBtn');

        if (confirmDeclineBtn) {
            confirmDeclineBtn.disabled = true;
            confirmDeclineBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Declining...';
        }
        if (declineToggleBtn) declineToggleBtn.disabled = true;
        if (cancelDeclineBtn) cancelDeclineBtn.disabled = true;
        if (approveBtn) approveBtn.disabled = true;
        if (modalCloseBtn) modalCloseBtn.disabled = true;
        if (topCloseBtn) topCloseBtn.disabled = true;

        showLoading('Declining Valid ID', 'Processing rejection, cleaning uploaded files, and notifying user...');

        try {
            const res = await fetch(declineIdUrlTemplate.replace('__ID__', currentIdUserId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ decline_reason: reason }),
            });

            let data = {};
            try { data = await res.json(); } catch (e) {}

            hideLoading();

            if (!res.ok) {
                showResultModal({
                    title: 'Decline Failed',
                    heading: 'Could not decline',
                    message: data.message || 'Failed to decline valid ID. Please try again.',
                    type: 'error',
                });
                return;
            }

            _isProcessingIdAction = false;
            closeIdModal();
            fetchUsersAjax();
            showResultModal({
                title: 'Valid ID Declined',
                heading: 'Successfully declined',
                message: data.message || 'The user has been notified by email and removed from the system.',
                type: 'success',
            });
        } catch (err) {
            hideLoading();
            showResultModal({
                title: 'Decline Failed',
                heading: 'Network error',
                message: 'An unexpected network error occurred. Please check your connection and try again.',
                type: 'error',
            });
        } finally {
            _isProcessingIdAction = false;
            if (confirmDeclineBtn) {
                confirmDeclineBtn.disabled = false;
                confirmDeclineBtn.innerHTML = '<i class="bi bi-send-x me-1"></i> Confirm Decline';
            }
            if (declineToggleBtn) declineToggleBtn.disabled = false;
            if (cancelDeclineBtn) cancelDeclineBtn.disabled = false;
            if (approveBtn) approveBtn.disabled = false;
            if (modalCloseBtn) modalCloseBtn.disabled = false;
            if (topCloseBtn) topCloseBtn.disabled = false;
        }
    }

    function requestApproveAccount(userId, fullName) {
        showConfirmModal({
            title: 'Approve User Account',
            heading: 'Approve this account?',
            message: `Are you sure you want to approve the account for ${fullName}? This will activate the account and allow them to sign in.`,
            type: 'approve',
            action: () => submitApproveAccount(userId, fullName),
        });
    }

    function requestDeclineAccount(userId, fullName) {
        showConfirmModal({
            title: 'Decline User Account',
            heading: 'Decline this account?',
            message: `Are you sure you want to decline the account for ${fullName}? The account will be marked as inactive and cannot sign in.`,
            type: 'decline',
            action: () => submitDeclineAccount(userId, fullName),
        });
    }

    async function submitApproveAccount(userId, fullName) {
        try {
            const res = await fetch(approveAccountUrlTemplate.replace('__ID__', userId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            let data = {};
            try { data = await res.json(); } catch (e) {}

            if (!res.ok) {
                showResultModal({
                    title: 'Approval Failed',
                    heading: 'Could not approve',
                    message: data.message || 'Failed to approve account. Please try again.',
                    type: 'error',
                });
                return;
            }

            fetchUsersAjax();
            showResultModal({
                title: 'Account Approved',
                heading: 'Successfully Approved',
                message: data.message || `Account for ${fullName} has been approved and activated.`,
                type: 'success',
            });
        } catch (e) {
            showResultModal({
                title: 'Action Failed',
                heading: 'Network error',
                message: 'Failed to connect to server. Please try again.',
                type: 'error',
            });
        }
    }

    async function submitDeclineAccount(userId, fullName) {
        try {
            const res = await fetch(declineAccountUrlTemplate.replace('__ID__', userId), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            let data = {};
            try { data = await res.json(); } catch (e) {}

            if (!res.ok) {
                showResultModal({
                    title: 'Decline Failed',
                    heading: 'Could not decline',
                    message: data.message || 'Failed to decline account. Please try again.',
                    type: 'error',
                });
                return;
            }

            fetchUsersAjax();
            showResultModal({
                title: 'Account Declined',
                heading: 'Successfully Declined',
                message: data.message || `Account for ${fullName} has been marked as inactive.`,
                type: 'success',
            });
        } catch (e) {
            showResultModal({
                title: 'Action Failed',
                heading: 'Network error',
                message: 'Failed to connect to server. Please try again.',
                type: 'error',
            });
        }
    }

    document.addEventListener('change', function(e) {
        if (e.target && e.target.name === 'decline_reason') {
            document.querySelectorAll('.decline-reason-option').forEach(el => el.classList.remove('selected'));
            const option = e.target.closest('.decline-reason-option');
            if (option) option.classList.add('selected');
        }
    });

    document.addEventListener('click', function(e) {
        const approveAcctBtn = e.target.closest('.btn-approve-acct');
        if (approveAcctBtn) {
            e.preventDefault();
            e.stopPropagation();
            requestApproveAccount(approveAcctBtn.dataset.userId, approveAcctBtn.dataset.fullName);
            return;
        }

        const declineAcctBtn = e.target.closest('.btn-decline-acct');
        if (declineAcctBtn) {
            e.preventDefault();
            e.stopPropagation();
            requestDeclineAccount(declineAcctBtn.dataset.userId, declineAcctBtn.dataset.fullName);
            return;
        }

        const viewBtn = e.target.closest('.btn-view-id');
        if (viewBtn) {
            e.preventDefault();
            e.stopPropagation();
            openIdModalFromButton(viewBtn);
            return;
        }

        if (e.target.closest('#closeIdModalBtn') || e.target.closest('#modalCloseActionBtn')) {
            closeIdModal();
            return;
        }

        if (e.target.closest('#declineToggleBtn')) {
            toggleDeclineForm();
            return;
        }

        if (e.target.closest('#cancelDeclineBtn')) {
            cancelDeclineForm();
            return;
        }

        if (e.target.closest('#confirmDeclineBtn')) {
            requestDeclineId();
            return;
        }

        if (e.target.closest('#approveIdBtn')) {
            requestApproveId();
            return;
        }

        if (e.target.closest('#closeConfirmActionBtn') || e.target.closest('#cancelConfirmActionBtn')) {
            closeConfirmModal();
            cancelDeclineForm();
            return;
        }

        if (e.target.closest('#proceedConfirmActionBtn')) {
            if (_isProcessingIdAction) return;
            const action = pendingConfirmAction;
            closeConfirmModal();
            if (typeof action === 'function') {
                action();
            } else if (action === 'approve_id') {
                submitApproveId();
            }
            return;
        }

        if (e.target.closest('#proceedDeclineConfirmBtn')) {
            if (_isProcessingIdAction) return;
            const action = pendingConfirmAction;
            closeConfirmModal();
            if (typeof action === 'function') {
                action();
            } else if (action === 'decline_id') {
                submitDeclineId();
            }
            return;
        }

        if (e.target.closest('#closeResultActionBtn') || e.target.closest('#okResultActionBtn')) {
            closeResultModal();
            return;
        }

        if (e.target.id === 'confirmActionBackdrop') {
            closeConfirmModal();
            return;
        }

        if (e.target.id === 'resultActionBackdrop') {
            closeResultModal();
            return;
        }

        if (e.target.id === 'idModalBackdrop') {
            closeIdModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key !== 'Escape') return;
        if (_isProcessingIdAction) return;
        if (document.getElementById('resultActionBackdrop').classList.contains('show')) {
            closeResultModal();
        } else if (document.getElementById('confirmActionBackdrop').classList.contains('show')) {
            closeConfirmModal();
        } else if (document.getElementById('idModalBackdrop').classList.contains('show')) {
            closeIdModal();
        }
    });

    function filterUsers() {
        clearTimeout(_searchDebounce);
        _searchDebounce = setTimeout(fetchUsersAjax, 220);
    }

    async function fetchUsersAjax() {
        const q = document.getElementById('userSearch').value.trim();
        const appFilter = document.getElementById('appFilter').value;
        const idFilter = document.getElementById('idFilter').value;
        const statusFilter = document.getElementById('statusFilter') ? document.getElementById('statusFilter').value : '';
        const tbody = document.getElementById('usersTableBody');

        const params = new URLSearchParams();
        if (q) params.set('q', q);
        if (appFilter) params.set('app_filter', appFilter);
        if (idFilter) params.set('id_filter', idFilter);
        if (statusFilter) params.set('status_filter', statusFilter);

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
