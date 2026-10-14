<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard &mdash; MSWDO {{ $municipality->name }}</title>
    <link rel="icon" type="image/jpeg" href="{{ $mswdoLogoUrl ?? '/images/logo_mswdo.jpg' }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-navy: {{ $adminPrimaryColor ?? '#1a2470' }};
            --primary-navy-dark: color-mix(in srgb, var(--primary-navy) 80%, black);
            --primary-gradient: linear-gradient(135deg, var(--primary-navy) 0%, #243088 100%);
            --accent-gold: {{ $adminSecondaryColor ?? '#f5b82e' }};
            --accent-gold-hover: #ffc94d;
            --accent-red: {{ $adminAccentColor ?? '#c41e24' }};
        }

        body {
            margin: 0;
            background: #f4f6fb;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #1b2559;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a {
            color: #1e2a78;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }

        /* Top banner */
        .top-banner {
            background: var(--primary-gradient);
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
            color: var(--accent-gold);
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
            color: var(--accent-gold);
            line-height: 1.2;
        }
        .muni-tag-label {
            font-size: 9.5px;
            letter-spacing: .14em;
            color: var(--accent-gold);
            font-weight: 600;
            margin-top: 2px;
        }

        /* Content wrap & grid */
        .wrap {
            max-width: 1280px;
            margin: 0 auto;
            padding: 16px 32px 28px;
            box-sizing: border-box;
            flex: 1;
            width: 100%;
        }
        .grid {
            display: grid;
            grid-template-columns: minmax(0, 8fr) minmax(0, 4fr);
            gap: 16px;
            align-items: stretch;
        }

        /* Card styles */
        .card {
            background: #fff;
            border: 1px solid #e1e6f2;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(23, 32, 102, .06);
            overflow: hidden;
        }
        .ch {
            background: var(--primary-gradient);
            color: #fff;
            border-radius: 11px 11px 0 0;
            padding: 0 14px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 14px;
            font-weight: 700;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .pill {
            background: rgba(255, 255, 255, .16);
            font-size: 11px;
            font-weight: 600;
            padding: 3px 9px;
            border-radius: 99px;
            color: #fff;
        }
        .btn-view-all {
            background: var(--accent-gold);
            color: #172066 !important;
            font-size: 12px;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: background 0.15s ease;
        }
        .btn-view-all:hover {
            background: var(--accent-gold-hover);
            text-decoration: none;
        }

        /* KPI cards */
        .kpis {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        .kpi {
            background: #f4f6fb;
            border: 1px solid #e1e6f2;
            border-radius: 10px;
            padding: 10px 12px;
        }
        .kpi.main {
            background: #1e2a78;
            border-color: #1e2a78;
            color: #fff;
        }
        .kpi small {
            display: block;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: #4d5878;
            margin-bottom: 2px;
        }
        .kpi.main small {
            color: #c9d0f2;
        }
        .kpi b {
            display: block;
            font-size: 26px;
            line-height: 1.15;
            font-weight: 800;
        }
        .kpi span {
            font-size: 11px;
            color: #4d5878;
        }
        .kpi.main span {
            color: #c9d0f2;
        }

        /* Two column section */
        .two {
            display: grid;
            grid-template-columns: minmax(0, 4fr) minmax(0, 8fr);
            gap: 18px;
            margin-top: 14px;
        }
        .h {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: #4d5878;
            margin: 0 0 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Progress bars per program */
        .pr {
            display: grid;
            grid-template-columns: 92px 1fr 22px;
            gap: 8px;
            align-items: center;
            font-size: 12px;
            padding: 6px 0;
            border-bottom: 1px solid #eef1f8;
        }
        .pr:last-child {
            border-bottom: none;
        }
        .pr span {
            font-weight: 600;
            color: #1b2559;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .bar {
            height: 7px;
            background: #e8ecf7;
            border-radius: 9px;
            overflow: hidden;
        }
        .bar i {
            display: block;
            height: 100%;
            background: #1e2a78;
            border-radius: 9px;
            transition: width 0.4s ease;
        }
        .pr b {
            font-weight: 700;
            text-align: right;
            color: #1b2559;
        }

        /* Recent applications table */
        table.dash-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        table.dash-table th {
            text-align: left;
            font-size: 10.5px;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: #4d5878;
            padding: 0 6px 6px;
            font-weight: 700;
        }
        table.dash-table td {
            padding: 7px 6px;
            border-top: 1px solid #eef1f8;
            color: #1b2559;
            vertical-align: middle;
        }
        .st {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            padding: 2px 8px;
            border-radius: 99px;
            display: inline-block;
        }
        .st.ap { background: #dcf5e6; color: #14663a; }
        .st.pe { background: #fff1cc; color: #7a5200; }
        .st.re { background: #fde2e2; color: #8f1d1d; }

        /* Aside column */
        .side {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .qrow {
            display: flex;
            justify-content: space-between;
            padding: 12px 14px;
        }
        .qa {
            position: relative;
        }
        .qa a, .qa button {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            border: 1px solid #d5dcee;
            background: #fff;
            color: #1e2a78;
            display: grid;
            place-items: center;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
            padding: 0;
        }
        .qa a:hover, .qa button:hover {
            background: #1e2a78;
            color: #fff;
            border-color: #1e2a78;
            text-decoration: none;
        }
        .qa a.pri, .qa button.pri {
            background: var(--accent-gold);
            border-color: var(--accent-gold);
            color: #172066;
        }
        .qa a.pri:hover, .qa button.pri:hover {
            background: var(--accent-gold-hover);
            border-color: var(--accent-gold-hover);
            color: #172066;
        }
        .qa a:focus-visible, .qa button:focus-visible {
            outline: 2px solid var(--accent-gold);
            outline-offset: 2px;
        }

        .card.qa-card {
            overflow: visible;
            position: relative;
            z-index: 50;
        }

        /* Tooltip */
        .tip {
            position: absolute;
            top: 48px;
            left: 0;
            width: 176px;
            background: #141b4d;
            color: #fff;
            border-radius: 8px;
            padding: 8px 10px;
            font-size: 11px;
            line-height: 1.35;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            z-index: 1050;
            box-shadow: 0 6px 16px rgba(20, 27, 77, .3);
            transition: opacity 0.15s ease, visibility 0.15s ease;
        }
        .tip b {
            display: block;
            font-size: 12px;
            color: var(--accent-gold);
            margin-bottom: 2px;
        }
        .qa:nth-child(n+5) .tip {
            left: auto;
            right: 0;
        }
        .qa:hover .tip, .qa:focus-within .tip {
            opacity: 1;
            visibility: visible;
        }

        /* Organization & Settings Links */
        .lk-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 10px 14px;
            color: #1b2559;
            transition: background 0.15s ease;
        }
        .lk-row + .lk-row {
            border-top: 1px solid #eef1f8;
        }
        .lk-row:hover {
            background: #f8fafc;
        }
        .lk-title {
            color: #1b2559;
            text-decoration: none;
            display: inline-block;
        }
        .lk-title:hover {
            color: var(--primary-navy);
            text-decoration: underline;
        }
        .lk-arrow {
            color: #4d5878;
            padding-top: 4px;
            display: inline-flex;
            text-decoration: none;
        }
        .chip-group {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
            margin-top: 4px;
        }
        .chip {
            display: inline-block;
            font-size: 10.5px;
            font-weight: 600;
            color: #1e2a78;
            background: #eef1fb;
            border-radius: 99px;
            padding: 2px 9px;
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.15s ease;
            cursor: pointer;
            line-height: 1.4;
        }
        button.chip {
            border: none;
        }
        .chip:hover {
            background: #1e2a78;
            color: #fff;
            text-decoration: none;
        }
        .ic {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: #e8ecf9;
            color: #1e2a78;
            display: grid;
            place-items: center;
            flex: none;
            margin-top: 2px;
        }
        .lk-row b {
            display: block;
            font-size: 13px;
            color: #1b2559;
        }
        .lk-row span.s {
            display: block;
            font-size: 11px;
            color: #4d5878;
            margin-top: 1px;
        }

        /* Municipality Snapshot */
        .snap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            padding: 12px 14px;
        }
        .snap > div {
            background: #f4f6fb;
            border: 1px solid #e1e6f2;
            border-radius: 8px;
            padding: 8px 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .snap-label {
            font-size: 11px;
            font-weight: 700;
            color: #4d5878;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .snap b {
            display: block;
            font-size: 18px;
            color: #1b2559;
            font-weight: 800;
            line-height: 1.2;
            margin: 3px 0 2px;
        }
        .snap-year {
            font-size: 10.5px;
            font-weight: 600;
            color: #64748b;
        }

        /* VMG Modal styles */
        .vmg-tab-btn {
            background: transparent;
            border: none;
            font-weight: 700;
            font-size: 0.88rem;
            color: #64748b;
            padding: 10px 18px;
            border-bottom: 2px solid transparent;
            cursor: pointer;
        }
        .vmg-tab-btn.active {
            color: #1a2470;
            border-bottom-color: #1a2470;
        }
        .vmg-field-label {
            font-size: 0.85rem;
            font-weight: 700;
            color: #1b2559;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .vmg-field-icon {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .vmg-textarea {
            width: 100%;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.88rem;
            line-height: 1.6;
            color: #1e293b;
            resize: vertical;
            outline: none;
            box-sizing: border-box;
        }
        .vmg-textarea:focus {
            border-color: #1a2470;
            box-shadow: 0 0 0 3px rgba(26, 36, 112, 0.1);
        }
        .vmg-save-btn {
            background: var(--primary-gradient);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 9px 22px;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .sg-input-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sg-input-num {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: #eef1fb;
            color: #1a2470;
            font-weight: 800;
            font-size: 0.78rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .sg-input {
            flex: 1;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.88rem;
            color: #1e293b;
            outline: none;
        }
        .sg-input:focus {
            border-color: #1a2470;
        }
        .sg-remove-btn {
            background: #fee2e2;
            color: #dc2626;
            border: none;
            border-radius: 8px;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
        }

        @media (max-width: 900px) {
            .grid, .two {
                grid-template-columns: 1fr;
            }
            .kpis {
                grid-template-columns: 1fr 1fr;
            }
            .wrap, .banner-inner {
                padding-left: 16px;
                padding-right: 16px;
            }
            .card.ov {
                overflow-x: auto;
            }
        }
    
        /* Dashboard-specific Chat Button Integrations (Recent Applications Table & QA) */
        .cx-dot {
            position: absolute;
            top: -3px;
            right: -3px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--accent-gold, #f5b82e);
            border: 2px solid #fff;
        }
        .qa button:hover .cx-dot, .qa a:hover .cx-dot { border-color: #1e2a78; }

        .cx-rowbtn {
            position: relative;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            border: 1px solid #d5dcee;
            background: #fff;
            color: #1e2a78;
            display: inline-grid;
            place-items: center;
            cursor: pointer;
            padding: 0;
            transition: all 0.15s ease;
        }
        .cx-rowbtn:hover { background: #1e2a78; border-color: #1e2a78; color: #fff; }
        .cx-rowbtn.has-unread::after {
            content: "";
            position: absolute;
            top: -3px;
            right: -3px;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: var(--accent-gold, #f5b82e);
            border: 2px solid #fff;
        }
        @media (max-width: 900px) {
            .card.ov th:last-child, .card.ov td:last-child {
                position: sticky;
                right: 0;
                background: #fff;
                box-shadow: -8px 0 6px -6px rgba(23, 32, 102, .18);
            }
        }

    </style>
</head>
<body>

    {{-- System Admin Navbar --}}
    @include('components.admin-navbar', ['active' => 'dashboard'])

    {{-- Top Hero Banner --}}
    <div class="top-banner">
        <div class="banner-inner">
            <div>
                <span class="banner-badge">ADMIN DASHBOARD</span>
                <h1 class="banner-title">Welcome back, {{ Auth::user()->full_name }}!</h1>
                <p class="banner-sub">Overview of applications and program activity in {{ $municipality->name }} &bull; {{ now()->format('l, F j, Y') }}</p>
            </div>
            <div class="muni-tag">
                <div class="muni-tag-name">{{ $municipality->name }}</div>
                <div class="muni-tag-label">MUNICIPALITY</div>
            </div>
        </div>
    </div>

    {{-- Main Container Wrap --}}
    <div class="wrap">

        @include('components.admin-notification')

        <div class="grid">

            {{-- LEFT COLUMN: Application Overview Card --}}
            <section class="card ov">
                <div class="ch">
                    <span>Application Overview</span>
                    <a class="btn-view-all" href="{{ route('admin.requirements') }}">
                        View All Applications
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                </div>
                <div style="padding: 14px">
                    {{-- KPI Stat Boxes --}}
                    <div class="kpis">
                        <div class="kpi main">
                            <small>Total applications</small>
                            <b>{{ number_format($totalApplications) }}</b>
                            <span>All time</span>
                        </div>
                        <div class="kpi">
                            <small>Pending</small>
                            <b>{{ number_format($pendingApplications) }}</b>
                            <span>Awaiting review</span>
                        </div>
                        <div class="kpi">
                            <small>Approved</small>
                            <b>{{ number_format($approvedApplications) }}</b>
                            <span>Processed</span>
                        </div>
                        <div class="kpi">
                            <small>Rejected</small>
                            <b>{{ number_format($rejectedApplications) }}</b>
                            <span>Did not qualify</span>
                        </div>
                    </div>

                    {{-- Two-column sub-layout: By program | Recent applications --}}
                    <div class="two">
                        {{-- By Program --}}
                        <div>
                            <p class="h">By program</p>
                            @php
                                $maxProgCount = (isset($programOverview) && $programOverview->count() > 0) ? ($programOverview->max('count') ?: 1) : 1;
                            @endphp

                            @if(isset($programOverview) && $programOverview->count() > 0)
                                @foreach($programOverview as $pItem)
                                    @php
                                        $barPct = $maxProgCount > 0 ? round(($pItem['count'] / $maxProgCount) * 100) : 0;
                                    @endphp
                                    <div class="pr">
                                        <span title="{{ $pItem['full_label'] }}">{{ $pItem['label'] }}</span>
                                        <div class="bar"><i style="width:{{ $barPct }}%"></i></div>
                                        <b>{{ $pItem['count'] }}</b>
                                    </div>
                                @endforeach
                            @else
                                <div style="font-size:12px;color:#8893a7;padding:12px 0;">No program data available.</div>
                            @endif
                        </div>

                        {{-- Recent Applications --}}
                        <div>
                            <p class="h">
                                <span>Recent applications</span>
                                <a href="{{ route('admin.requirements') }}" style="text-transform:none;letter-spacing:0;font-size:12px">View all</a>
                            </p>
                            <table class="dash-table">
                                <thead>
                                    <tr>
                                        <th>Applicant</th>
                                        <th>Program</th>
                                        <th>Barangay</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th style="text-align:right">Chat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentApplications as $app)
                                        @php
                                            $stClass = match (strtolower($app->status ?? '')) {
                                                'approved' => 'ap',
                                                'rejected' => 're',
                                                default => 'pe'
                                            };
                                            $appDate = $app->application_date ? (is_string($app->application_date) ? \Carbon\Carbon::parse($app->application_date)->format('M j') : $app->application_date->format('M j')) : '&mdash;';
                                            $shortProg = match ($app->program_type) {
                                                'PWD_Assistance' => 'PWD',
                                                'Solo_Parent' => 'Solo Parent',
                                                'AICS_Medical' => 'AICS Medical',
                                                'AICS_Burial' => 'AICS Burial',
                                                default => ucwords(str_replace('_', ' ', $app->program_type))
                                            };
                                        @endphp
                                        <tr>
                                            <td><b>{{ $app->full_name }}</b></td>
                                            <td>{{ $shortProg }}</td>
                                            <td>{{ $app->barangay ?: '&mdash;' }}</td>
                                            <td>{!! $appDate !!}</td>
                                            <td><span class="st {{ $stClass }}">{{ ucfirst($app->status ?? 'pending') }}</span></td>
                                            <td style="text-align:right">
                                                <button type="button" class="cx-rowbtn" data-open-chat data-user-id="{{ $app->user_id ?? '' }}" onclick="openChatWithApplicant({{ $app->user_id ?? 'null' }}, '{{ addslashes($app->full_name) }}', '{{ $shortProg }}', '{{ addslashes($app->barangay ?? '') }}', '{{ $app->status ?? 'pending' }}', '{{ route('admin.requirements') }}?search={{ urlencode($app->full_name) }}')" title="Message {{ $app->full_name }}" aria-label="Message {{ $app->full_name }}">
                                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" style="text-align:center;padding:16px;color:#8893a7;">No recent applications recorded.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            {{-- RIGHT COLUMN: Aside containing 3 cards --}}
            <aside class="side">

                {{-- 1. Quick Actions --}}
                <section class="card qa-card">
                    <div class="ch">
                        <span>Quick Actions</span>
                        <span class="pill">8 shortcuts</span>
                    </div>
                    <div class="qrow">
                        {{-- Add User --}}
                        <div class="qa">
                            <a href="{{ route('admin.users') }}#create" class="pri" aria-label="Add User">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </a>
                            <span class="tip" role="tooltip"><b>Add User</b>Register a new user or admin</span>
                        </div>

                        {{-- Users Management --}}
                        <div class="qa">
                            <a href="{{ route('admin.users') }}" aria-label="Users Management">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </a>
                            <span class="tip" role="tooltip"><b>Users Management</b>View registered users and admins</span>
                        </div>

                        {{-- Manage Applications --}}
                        <div class="qa">
                            <a href="{{ route('admin.requirements') }}" aria-label="Manage Applications">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="8" y1="13" x2="16" y2="13"></line>
                                    <line x1="8" y1="17" x2="16" y2="17"></line>
                                </svg>
                            </a>
                            <span class="tip" role="tooltip"><b>Manage Applications</b>Review and process applications</span>
                        </div>

                        {{-- Messages (Chat with applicants) --}}
                        <div class="qa">
                            <button type="button" onclick="openChatDrawer()" aria-label="Messages" aria-haspopup="dialog" title="Messages">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                                </svg>
                                <span class="cx-dot" id="cxQaDot" style="display:none;"></span>
                            </button>
                            <span class="tip" role="tooltip"><b>Messages</b>Chat with applicants</span>
                        </div>

                        {{-- View Analysis --}}
                        <div class="qa">
                            <a href="{{ route('admin.detailed-analysis') }}" aria-label="Analysis">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                    <line x1="12" y1="20" x2="12" y2="10"></line>
                                    <line x1="18" y1="20" x2="18" y2="4"></line>
                                    <line x1="6" y1="20" x2="6" y2="16"></line>
                                </svg>
                            </a>
                            <span class="tip" role="tooltip"><b>Analysis</b>By program, status and barangay</span>
                        </div>

                        {{-- Data Management --}}
                        <div class="qa">
                            <a href="{{ route('admin.data.dashboard') }}" aria-label="Data Management">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                    <path d="M3 5v14c0 1.7 4 3 9 3s9-1.3 9-3V5"></path>
                                    <path d="M3 12c0 1.7 4 3 9 3s9-1.3 9-3"></path>
                                </svg>
                            </a>
                            <span class="tip" role="tooltip"><b>Data Management</b>Municipality, barangay and program data</span>
                        </div>

                        {{-- Announcements --}}
                        <div class="qa">
                            <a href="{{ route('admin.announcements.index') }}" aria-label="Announcements">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 11v2a1 1 0 0 0 1 1h2l5 4V6L6 10H4a1 1 0 0 0-1 1z"></path>
                                    <path d="M15.5 8.5a5 5 0 0 1 0 7"></path>
                                </svg>
                            </a>
                            <span class="tip" role="tooltip"><b>Announcements</b>Post updates and notices</span>
                        </div>

                        {{-- Settings --}}
                        <div class="qa">
                            <a href="{{ route('admin.settings') }}" aria-label="Settings">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                    <line x1="4" y1="21" x2="4" y2="14"></line>
                                    <line x1="4" y1="10" x2="4" y2="3"></line>
                                    <line x1="12" y1="21" x2="12" y2="12"></line>
                                    <line x1="12" y1="8" x2="12" y2="3"></line>
                                    <line x1="20" y1="21" x2="20" y2="16"></line>
                                    <line x1="20" y1="12" x2="20" y2="3"></line>
                                    <line x1="1" y1="14" x2="7" y2="14"></line>
                                    <line x1="9" y1="8" x2="15" y2="8"></line>
                                    <line x1="17" y1="16" x2="23" y2="16"></line>
                                </svg>
                            </a>
                            <span class="tip" role="tooltip"><b>Settings</b>Municipality Configuration</span>
                        </div>
                    </div>
                </section>

                {{-- 2. Organization & Settings --}}
                <section class="card">
                    <div class="ch">
                        <span>Organization &amp; Settings</span>
                    </div>

                    {{-- Consolidated Row: Mission, Vision & Municipality Configuration --}}
                    <div class="lk-row">
                        <span class="ic">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                            </svg>
                        </span>
                        <div style="flex:1">
                            <a href="{{ route('admin.settings', ['tab' => 'vmg']) }}" class="lk-title">
                                <b>Mission, Vision &amp; Municipality Configuration</b>
                            </a>
                            <span class="s">Content management, programs, information and UI settings</span>
                            <div class="chip-group">
                                <a href="{{ route('admin.settings', ['tab' => 'vmg']) }}" class="chip" onclick="if(event.ctrlKey||event.metaKey)return;event.preventDefault();openVMGModal('vmg');" title="Quick edit Vision">Vision</a>
                                <a href="{{ route('admin.settings', ['tab' => 'vmg']) }}" class="chip" onclick="if(event.ctrlKey||event.metaKey)return;event.preventDefault();openVMGModal('vmg');" title="Quick edit Mission">Mission</a>
                                <a href="{{ route('admin.settings', ['tab' => 'vmg', 'sub' => 'sg']) }}" class="chip" id="dashChipStrategicGoals" title="Quick edit strategic goals" onclick="if(event.ctrlKey||event.metaKey)return;event.preventDefault();openVMGModal('sg');">
                                    {{ count($visionData['strategic_goals'] ?? []) ?: 4 }} strategic goals
                                </a>
                                <a href="{{ route('admin.settings', ['tab' => 'programs']) }}" class="chip" title="Manage Programs & Services">{{ $totalPrograms ?: 4 }} programs</a>
                                <a href="{{ route('admin.settings', ['tab' => 'muni']) }}" class="chip" title="Manage Municipality Information">Municipality info</a>
                                <a href="{{ route('admin.settings', ['tab' => 'theme']) }}" class="chip" title="Manage Branding & UI Colors">UI &amp; colors</a>
                            </div>
                        </div>
                        <a href="{{ route('admin.settings', ['tab' => 'vmg']) }}" aria-label="Open Organization & Settings" class="lk-arrow">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 6 15 12 9 18"></polyline>
                            </svg>
                        </a>
                    </div>
                </section>

                {{-- 3. Municipality Snapshot --}}
                <section class="card">
                    <div class="ch">
                        <span>Municipality Snapshot</span>
                        <span class="pill" style="font-weight: 700; letter-spacing: 0.02em;">Dashboard Year: {{ $dashboardYear ?? 2026 }}</span>
                    </div>
                    <div class="snap">
                        <div>
                            <div class="snap-label">Population</div>
                            <b>{{ number_format($snapshotPopulation ?? 0) }}</b>
                            <span class="snap-year">{{ $snapshotYear ?? 2024 }}</span>
                        </div>
                        <div>
                            <div class="snap-label">Households</div>
                            <b>{{ number_format($snapshotHouseholds ?? 0) }}</b>
                            <span class="snap-year">{{ $snapshotYear ?? 2024 }}</span>
                        </div>
                        <div>
                            <div class="snap-label">Barangays</div>
                            <b>{{ $totalBarangays }}</b>
                            <span class="snap-year">Current</span>
                        </div>
                        <div>
                            <div class="snap-label">Programs</div>
                            <b>{{ $totalPrograms }}</b>
                            <span class="snap-year">Current</span>
                        </div>
                    </div>
                </section>

            </aside>

        </div>
    </div>

    {{-- MODAL: Vision, Mission & Strategic Goals --}}
    <div class="modal fade" id="vmgEditModal" tabindex="-1" aria-labelledby="vmgEditModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;box-shadow:0 20px 60px rgba(0,0,0,.25);">
                {{-- Header --}}
                <div class="modal-header" style="background:var(--primary-gradient);border:none;padding:20px 28px;align-items:center;">
                    <div>
                        <div style="font-size:0.75rem;letter-spacing:0.1em;color:var(--accent-gold);font-weight:800;text-transform:uppercase;">Content Management</div>
                        <h5 class="modal-title" id="vmgEditModalLabel" style="color:white;font-weight:800;font-size:1.2rem;margin:2px 0 0;">
                            Mission, Vision &amp; Strategic Goals
                        </h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Tabs --}}
                <div style="background:#f8fafc;border-bottom:1px solid #e2e8f0;padding:0 28px;display:flex;gap:4px;">
                    <button type="button" class="vmg-tab-btn active" id="tab-btn-vmg" onclick="switchVMGTab('vmg')">
                        Vision, Mission &amp; Goals
                    </button>
                    <button type="button" class="vmg-tab-btn" id="tab-btn-sg" onclick="switchVMGTab('sg')">
                        Strategic Goals
                    </button>
                </div>

                {{-- Body --}}
                <div class="modal-body" style="padding:28px;background:#fff;">
                    {{-- Pane 1: Vision / Mission / Goals --}}
                    <div id="pane-vmg">
                        <div class="row g-4">
                            <div class="col-12">
                                <label class="vmg-field-label">
                                    <span class="vmg-field-icon" style="background:#2C3E8F;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </span>
                                    Vision
                                </label>
                                <textarea id="vmgVision" class="vmg-textarea" rows="4" placeholder="Enter the vision statement for {{ $municipality->name }}...">{{ $visionData['vision'] }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="vmg-field-label">
                                    <span class="vmg-field-icon" style="background:#FDB913;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#1A2A5C" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    </span>
                                    Mission
                                </label>
                                <textarea id="vmgMission" class="vmg-textarea" rows="4" placeholder="Enter the mission statement for {{ $municipality->name }}...">{{ $visionData['mission'] }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="vmg-field-label">
                                    <span class="vmg-field-icon" style="background:#6366f1;">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                                    </span>
                                    General Goals
                                </label>
                                <textarea id="vmgGoals" class="vmg-textarea" rows="4" placeholder="Enter the general goals for {{ $municipality->name }}...">{{ $visionData['goals'] }}</textarea>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3 mt-4">
                            <button type="button" onclick="saveVMG()" class="vmg-save-btn" id="vmgSaveBtn">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                Save Vision, Mission &amp; Goals
                            </button>
                            <span id="vmgSaveStatus" style="font-size:.82rem;font-weight:600;"></span>
                        </div>
                    </div>

                    {{-- Pane 2: Strategic Goals --}}
                    <div id="pane-sg" style="display:none;">
                        <div style="margin-bottom:16px;">
                            <div style="font-size:.82rem;color:#64748b;line-height:1.6;">
                                Manage the strategic goals for <strong>{{ $municipality->name }}</strong>. These goals guide your MSWDO programs and priorities.
                            </div>
                        </div>
                        <div id="sgGoalsList" style="display:flex;flex-direction:column;gap:10px;">
                            @php $existingGoals = $visionData['strategic_goals'] ?? []; @endphp
                            @if(count($existingGoals) > 0)
                                @foreach($existingGoals as $gi => $goal)
                                    <div class="sg-input-row" data-idx="{{ $gi }}">
                                        <span class="sg-input-num">{{ str_pad($gi + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                        <input type="text" class="sg-input" value="{{ $goal }}" placeholder="Enter strategic goal...">
                                        <button type="button" onclick="removeSGRow(this)" class="sg-remove-btn" title="Remove">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="sg-input-row" data-idx="0">
                                    <span class="sg-input-num">01</span>
                                    <input type="text" class="sg-input" value="" placeholder="Enter strategic goal...">
                                    <button type="button" onclick="removeSGRow(this)" class="sg-remove-btn" title="Remove">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addSGRow()" id="addSGBtn"
                                style="margin-top:12px;background:#f1f5f9;border:2px dashed #cbd5e1;border-radius:10px;color:#64748b;font-weight:700;font-size:.82rem;padding:10px 18px;cursor:pointer;width:100%;transition:all .2s;">
                            + Add Strategic Goal
                        </button>
                        <div class="d-flex align-items-center gap-3 mt-4">
                            <button type="button" onclick="saveSG()" class="vmg-save-btn" id="sgSaveBtn">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                Save Strategic Goals
                            </button>
                            <span id="sgSaveStatus" style="font-size:.82rem;font-weight:600;"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
        const MUNI_NAME = {!! json_encode(Auth::user()->municipality ?? ($municipality?->name ?? '')) !!};
        const SAVE_URL = {!! json_encode(route('admin.vision-mission.save')) !!};

        function switchVMGTab(tab) {
            document.getElementById('pane-vmg').style.display = tab === 'vmg' ? 'block' : 'none';
            document.getElementById('pane-sg').style.display  = tab === 'sg'  ? 'block' : 'none';
            document.getElementById('tab-btn-vmg').classList.toggle('active', tab === 'vmg');
            document.getElementById('tab-btn-sg').classList.toggle('active', tab === 'sg');
        }

        function openVMGModal(tab) {
            switchVMGTab(tab);
            const modalEl = document.getElementById('vmgEditModal');
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }

        async function saveVMG() {
            const btn = document.getElementById('vmgSaveBtn');
            const status = document.getElementById('vmgSaveStatus');
            btn.disabled = true;
            btn.textContent = 'Saving...';
            status.textContent = '';

            const payload = {
                _token: CSRF_TOKEN,
                municipality_name: MUNI_NAME,
                vision: document.getElementById('vmgVision').value,
                mission: document.getElementById('vmgMission').value,
                goals: document.getElementById('vmgGoals').value,
            };

            try {
                const res = await fetch(SAVE_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.success) {
                    status.style.color = '#16a34a';
                    status.textContent = 'Saved successfully!';
                    setTimeout(() => location.reload(), 1200);
                } else {
                    status.style.color = '#dc2626';
                    status.textContent = data.message || 'Error saving.';
                }
            } catch (e) {
                status.style.color = '#dc2626';
                status.textContent = 'Network error. Please try again.';
            }

            btn.disabled = false;
            btn.innerHTML = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Save Vision, Mission &amp; Goals`;
        }

        function renumberSG() {
            document.querySelectorAll('#sgGoalsList .sg-input-row').forEach((row, i) => {
                row.dataset.idx = i;
                row.querySelector('.sg-input-num').textContent = String(i + 1).padStart(2, '0');
            });
        }

        function addSGRow() {
            const list = document.getElementById('sgGoalsList');
            if (list.children.length >= 10) { alert('Maximum 10 strategic goals allowed.'); return; }
            const idx = list.children.length;
            const div = document.createElement('div');
            div.className = 'sg-input-row';
            div.dataset.idx = idx;
            div.innerHTML = `
                <span class="sg-input-num">${String(idx + 1).padStart(2, '0')}</span>
                <input type="text" class="sg-input" value="" placeholder="Enter strategic goal...">
                <button type="button" onclick="removeSGRow(this)" class="sg-remove-btn" title="Remove">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>`;
            list.appendChild(div);
            div.querySelector('.sg-input').focus();
        }

        function removeSGRow(btn) {
            const row = btn.closest('.sg-input-row');
            const list = document.getElementById('sgGoalsList');
            if (list.children.length <= 1) { row.querySelector('.sg-input').value = ''; return; }
            row.remove();
            renumberSG();
        }

        async function saveSG() {
            const btn = document.getElementById('sgSaveBtn');
            const status = document.getElementById('sgSaveStatus');
            btn.disabled = true;
            btn.textContent = 'Saving...';
            status.textContent = '';

            const goals = Array.from(document.querySelectorAll('#sgGoalsList .sg-input'))
                .map(i => i.value.trim())
                .filter(v => v !== '');

            const payload = {
                _token: CSRF_TOKEN,
                municipality_name: MUNI_NAME,
                strategic_goals: goals,
            };

            try {
                const res = await fetch(SAVE_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.success) {
                    status.style.color = '#16a34a';
                    status.textContent = 'Strategic goals saved!';
                    setTimeout(() => location.reload(), 1200);
                } else {
                    status.style.color = '#dc2626';
                    status.textContent = data.message || 'Error saving.';
                }
            } catch (e) {
                status.style.color = '#dc2626';
                status.textContent = 'Network error. Please try again.';
            }

            btn.disabled = false;
            btn.innerHTML = `<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg> Save Strategic Goals`;
        }
    </script>


</body>
</html>