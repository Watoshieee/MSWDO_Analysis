<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Settings &amp; Configuration &mdash; MSWDO Admin</title>
    <link rel="icon" type="image/jpeg" href="{{ $mswdoLogoUrl ?? '/images/logo_mswdo.jpg' }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-navy: {{ $primaryColor ?? '#1a2470' }};
            --primary-gradient: linear-gradient(135deg, var(--primary-navy) 0%, #243088 100%);
            --accent-gold: {{ $secondaryColor ?? '#f5b82e' }};
            --accent-gold-hover: #ffc94d;
            --accent-red: {{ $accentColor ?? '#c41e24' }};
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

        .btn-back-dash {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 99px;
            padding: 5px 14px;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .btn-back-dash:hover {
            background: rgba(255, 255, 255, 0.28);
            color: #fff;
            text-decoration: none;
        }

        /* Content wrap */
        .wrap {
            max-width: 1280px;
            margin: 0 auto;
            padding: 20px 32px 36px;
            box-sizing: border-box;
            flex: 1;
            width: 100%;
        }

        /* Nav pill tabs for settings */
        .settings-nav {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
            border-bottom: 2px solid #e1e6f2;
            padding-bottom: 10px;
            flex-wrap: wrap;
        }
        .settings-nav-btn {
            background: #fff;
            border: 1px solid #d5dcee;
            color: #4d5878;
            font-size: 13px;
            font-weight: 700;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .settings-nav-btn:hover {
            background: #f4f6fb;
            color: #1e2a78;
            border-color: #1e2a78;
        }
        .settings-nav-btn.active {
            background: #1e2a78;
            color: #fff;
            border-color: #1e2a78;
        }

        /* Cards */
        .card {
            background: #fff;
            border: 1px solid #e1e6f2;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(23, 32, 102, .06);
            overflow: hidden;
            margin-bottom: 20px;
        }
        .ch {
            background: var(--primary-gradient);
            color: #fff;
            border-radius: 11px 11px 0 0;
            padding: 0 16px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 14px;
            font-weight: 700;
        }
        .card-body {
            padding: 20px;
        }

        /* Form elements */
        .form-label {
            font-size: 12px;
            font-weight: 700;
            color: #4d5878;
            text-transform: uppercase;
            letter-spacing: .05em;
            margin-bottom: 6px;
            display: block;
        }
        .form-control, .form-textarea {
            width: 100%;
            border: 1.5px solid #d5dcee;
            border-radius: 8px;
            padding: 9px 12px;
            font-size: 13px;
            color: #1b2559;
            box-sizing: border-box;
            background: #fff;
            transition: border-color 0.15s ease;
            outline: none;
        }
        .form-control:focus, .form-textarea:focus {
            border-color: #1e2a78;
            box-shadow: 0 0 0 3px rgba(30, 42, 120, 0.1);
        }
        .form-textarea {
            resize: vertical;
            line-height: 1.6;
        }

        /* Buttons */
        .btn-pri {
            background: var(--accent-gold);
            color: #172066;
            font-size: 13px;
            font-weight: 700;
            padding: 9px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.15s ease;
        }
        .btn-pri:hover {
            background: var(--accent-gold-hover);
        }
        .btn-sec {
            background: #f4f6fb;
            color: #4d5878;
            border: 1px solid #d5dcee;
            font-size: 13px;
            font-weight: 600;
            padding: 9px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .btn-sec:hover {
            background: #e8ecf7;
            color: #1b2559;
        }

        /* Strategic Goals rows */
        .sg-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }
        .sg-num {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: #eef1fb;
            color: #1e2a78;
            font-weight: 800;
            font-size: 12px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }
        .sg-btn-remove {
            background: #fee2e2;
            color: #991b1b;
            border: none;
            border-radius: 8px;
            width: 32px;
            height: 32px;
            cursor: pointer;
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }

        /* Programs table */
        table.settings-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
        }
        table.settings-table th {
            text-align: left;
            font-size: 10.5px;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: #4d5878;
            padding: 8px 10px;
            border-bottom: 2px solid #e1e6f2;
            background: #f8fafc;
        }
        table.settings-table td {
            padding: 10px;
            border-top: 1px solid #eef1f8;
            vertical-align: middle;
        }
        .badge-prog-key {
            background: #eef1fb;
            color: #1e2a78;
            font-family: monospace;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 6px;
        }
        .btn-toggle-prog {
            border: none;
            border-radius: 99px;
            padding: 3px 12px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .btn-toggle-prog.active {
            background: #dcf5e6;
            color: #14663a;
        }
        .btn-toggle-prog.inactive {
            background: #fde2e2;
            color: #8f1d1d;
        }

        /* Color customizer */
        .color-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }
        .color-row input[type="color"] {
            width: 50px;
            height: 42px;
            border-radius: 8px;
            border: 1.5px solid #d5dcee;
            cursor: pointer;
            padding: 2px;
            background: #fff;
        }
        .color-row input[type="text"] {
            width: 130px;
            font-family: monospace;
            font-weight: 700;
            font-size: 13px;
        }
        .color-preview-box {
            background: #f8fafc;
            border: 1px solid #e1e6f2;
            border-radius: 10px;
            padding: 16px;
        }

        /* Toast notification */
        .toast-notify {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            background: #141b4d;
            color: #fff;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            gap: 10px;
            transition: opacity 0.3s ease;
        }

        @media (max-width: 900px) {
            .wrap, .banner-inner {
                padding-left: 16px;
                padding-right: 16px;
            }
        }
    </style>
</head>
<body>

    {{-- Admin Navbar --}}
    @include('components.admin-navbar', ['active' => 'settings'])

    {{-- Top Banner --}}
    <div class="top-banner">
        <div class="banner-inner">
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                    <a href="{{ route('admin.dashboard') }}" class="btn-back-dash" id="btnBackToDashboard">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Back to Dashboard
                    </a>
                    <span class="banner-badge">ADMIN SETTINGS</span>
                </div>
                <h1 class="banner-title">Settings &amp; Municipality Configuration</h1>
                <p class="banner-sub">Manage organization information, social programs, contact details, and UI theme for {{ $municipality->name ?? Auth::user()->municipality }}</p>
            </div>
            <div class="muni-tag">
                <div class="muni-tag-name">{{ $municipality->name ?? Auth::user()->municipality }}</div>
                <div class="muni-tag-label">MUNICIPALITY</div>
            </div>
        </div>
    </div>

    {{-- Main Container --}}
    <div class="wrap">

        {{-- Section Switcher Nav --}}
        <div class="settings-nav">
            <button type="button" class="settings-nav-btn active" onclick="showSettingsTab('vmg')" id="tabBtn-vmg">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                Mission, Vision &amp; Strategic Goals
            </button>
            <button type="button" class="settings-nav-btn" onclick="showSettingsTab('muni')" id="tabBtn-muni">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                Municipality Information &amp; Contacts
            </button>
            <button type="button" class="settings-nav-btn" onclick="showSettingsTab('programs')" id="tabBtn-programs">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                Programs &amp; Services
            </button>
            <button type="button" class="settings-nav-btn" onclick="showSettingsTab('theme')" id="tabBtn-theme">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="m4.93 4.93 4.24 4.24"></path><path d="m14.83 9.17 4.24-4.24"></path><path d="m14.83 14.83 4.24 4.24"></path><path d="m9.17 14.83-4.24 4.24"></path></svg>
                Branding &amp; UI Colors
            </button>
        </div>

        {{-- TAB 1: Mission, Vision & Strategic Goals --}}
        <div id="tabContent-vmg">
            <div class="card">
                <div class="ch">
                    <span>Mission, Vision &amp; Strategic Goals</span>
                    <span class="pill">Public Organization Profile</span>
                </div>
                <div class="card-body">
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Vision Statement</label>
                            <textarea id="settingVision" class="form-textarea" rows="5" placeholder="Enter vision statement for {{ $municipality->name ?? Auth::user()->municipality }}...">{{ $visionData['vision'] }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mission Statement</label>
                            <textarea id="settingMission" class="form-textarea" rows="5" placeholder="Enter mission statement for {{ $municipality->name ?? Auth::user()->municipality }}...">{{ $visionData['mission'] }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">General Goals</label>
                            <textarea id="settingGoals" class="form-textarea" rows="3" placeholder="Enter general goals for {{ $municipality->name ?? Auth::user()->municipality }}...">{{ $visionData['goals'] ?? '' }}</textarea>
                        </div>
                    </div>

                    <div id="settingsGoalsSection" class="mb-4">
                        <label class="form-label">Strategic Goals (Up to 10)</label>
                        <p style="font-size: 12px; color: #64748b; margin-top: -4px; margin-bottom: 12px;">These strategic goals display on the public analysis and citizen overview portals.</p>
                        <div id="settingsGoalsList">
                            @php $sGoals = $visionData['strategic_goals'] ?? []; @endphp
                            @if(count($sGoals) > 0)
                                @foreach($sGoals as $gi => $gVal)
                                    <div class="sg-row" data-idx="{{ $gi }}">
                                        <span class="sg-num">{{ str_pad($gi + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                        <input type="text" class="form-control sg-input-val" value="{{ $gVal }}" placeholder="Strategic goal statement...">
                                        <button type="button" onclick="removeGoalInput(this)" class="sg-btn-remove" title="Remove">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        </button>
                                    </div>
                                @endforeach
                            @else
                                <div class="sg-row" data-idx="0">
                                    <span class="sg-num">01</span>
                                    <input type="text" class="form-control sg-input-val" value="" placeholder="Strategic goal statement...">
                                    <button type="button" onclick="removeGoalInput(this)" class="sg-btn-remove" title="Remove">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <button type="button" onclick="addGoalInput()" class="btn-sec mt-2" style="font-size: 12px; padding: 6px 14px;">
                            + Add Strategic Goal
                        </button>
                    </div>

                    <div class="d-flex align-items-center gap-3 pt-3 border-top">
                        <button type="button" class="btn-pri" onclick="saveSettingsVMG()" id="btnSaveVMG">
                            Save Mission, Vision &amp; Goals
                        </button>
                        <span id="statusVMG" style="font-size: 13px; font-weight: 600;"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 2: Municipality Information & Contacts --}}
        <div id="tabContent-muni" style="display: none;">
            <div class="card">
                <div class="ch">
                    <span>Municipality Information &amp; Contact Details</span>
                    <span class="pill">Office Profile</span>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Office Display Name</label>
                            <input type="text" id="muniDisplayName" class="form-control" value="{{ $muniSettings->display_name ?? ('Municipality of ' . ($municipality->name ?? Auth::user()->municipality)) }}" placeholder="e.g. Municipality of Majayjay">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Email</label>
                            <input type="email" id="muniEmail" class="form-control" value="{{ $muniSettings->contact_email ?? '' }}" placeholder="mswdo@example.gov.ph">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Phone</label>
                            <input type="text" id="muniPhone" class="form-control" value="{{ $muniSettings->contact_phone ?? '' }}" placeholder="(049) 555-0123">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Office Hours</label>
                            <input type="text" id="muniHours" class="form-control" value="{{ $muniSettings->office_hours ?? 'Monday - Friday: 8:00 AM - 5:00 PM' }}" placeholder="Monday - Friday: 8:00 AM - 5:00 PM">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Office Address</label>
                            <textarea id="muniAddress" class="form-textarea" rows="3" placeholder="Municipal Hall, Poblacion...">{{ $muniSettings->office_address ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3 pt-3 border-top">
                        <button type="button" class="btn-pri" onclick="saveSettingsMuni()" id="btnSaveMuni">
                            Save Municipality Information
                        </button>
                        <span id="statusMuni" style="font-size: 13px; font-weight: 600;"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 3: Programs & Services Management --}}
        <div id="tabContent-programs" style="display: none;">
            <div class="card">
                <div class="ch">
                    <span>Programs &amp; Services Management</span>
                    <span class="pill">{{ $muniPrograms->count() }} Programs Configured</span>
                </div>
                <div class="card-body">
                    <p style="font-size: 13px; color: #4d5878; margin-top: 0; margin-bottom: 16px;">
                        Configure social welfare programs available in <strong>{{ $municipality->name ?? Auth::user()->municipality }}</strong>. You can toggle active availability and edit descriptions or display order.
                    </p>

                    @if($muniPrograms->count() > 0)
                        <div class="table-responsive">
                            <table class="settings-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">Order</th>
                                        <th>Program Label</th>
                                        <th>Key</th>
                                        <th>Description</th>
                                        <th style="text-align: center; width: 100px;">Status</th>
                                        <th style="text-align: right; width: 100px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($muniPrograms as $prog)
                                        <tr id="progRow-{{ $prog->id }}">
                                            <td><b>{{ str_pad($prog->display_order, 2, '0', STR_PAD_LEFT) }}</b></td>
                                            <td><strong>{{ $prog->program_label }}</strong></td>
                                            <td><span class="badge-prog-key">{{ $prog->program_key }}</span></td>
                                            <td style="color: #64748b; font-size: 12px; max-width: 320px;">
                                                {{ Str::limit($prog->description, 90) ?: '—' }}
                                            </td>
                                            <td style="text-align: center;">
                                                <button type="button" class="btn-toggle-prog {{ $prog->is_active ? 'active' : 'inactive' }}" id="progToggleBtn-{{ $prog->id }}" onclick="toggleSettingsProgram({{ $prog->id }})">
                                                    {{ $prog->is_active ? 'Active' : 'Inactive' }}
                                                </button>
                                            </td>
                                            <td style="text-align: right;">
                                                <button type="button" class="btn-sec" style="padding: 4px 12px; font-size: 11px; font-weight: 700;" data-id="{{ $prog->id }}" data-label="{{ $prog->program_label }}" data-desc="{{ $prog->description ?? '' }}" data-order="{{ $prog->display_order }}" onclick="openEditProgramModal(this.dataset.id, this.dataset.label, this.dataset.desc, this.dataset.order)">
                                                    Edit
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div style="text-align: center; padding: 32px; color: #8893a7;">
                            No program records configured for this municipality.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- TAB 4: Branding & UI Colors --}}
        <div id="tabContent-theme" style="display: none;">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card" style="height: 100%;">
                        <div class="ch">
                            <span>UI Colors &amp; Branding</span>
                            <span class="pill">Visual Theme</span>
                        </div>
                        <div class="card-body">
                            <p style="font-size: 13px; color: #4d5878; margin-top: 0; margin-bottom: 20px;">
                                Customize the branding colors applied to your MSWDO admin dashboard and components.
                            </p>

                            <label class="form-label">Primary Color (Navbars &amp; Card Headers)</label>
                            <div class="color-row">
                                <input type="color" id="primaryColorInput" value="{{ $primaryColor }}">
                                <input type="text" id="primaryColorText" class="form-control" value="{{ $primaryColor }}" readonly>
                            </div>

                            <label class="form-label">Secondary / Accent Gold (Buttons &amp; Highlights)</label>
                            <div class="color-row">
                                <input type="color" id="secondaryColorInput" value="{{ $secondaryColor }}">
                                <input type="text" id="secondaryColorText" class="form-control" value="{{ $secondaryColor }}" readonly>
                            </div>

                            <label class="form-label">Accent Color (Alerts &amp; Declines)</label>
                            <div class="color-row">
                                <input type="color" id="accentColorInput" value="{{ $accentColor }}">
                                <input type="text" id="accentColorText" class="form-control" value="{{ $accentColor }}" readonly>
                            </div>

                            <div class="d-flex gap-3 pt-3 border-top mt-4">
                                <button type="button" class="btn-pri" onclick="saveColors()">Save Theme Colors</button>
                                <button type="button" class="btn-sec" onclick="resetColors()">Reset to Default</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card" style="height: 100%;">
                        <div class="ch">
                            <span>Live Theme Preview</span>
                            <span class="pill">Preview</span>
                        </div>
                        <div class="card-body">
                            <p style="font-size: 13px; color: #4d5878; margin-top: 0; margin-bottom: 16px;">
                                Real-time preview of how your color choices will look across header and buttons:
                            </p>

                            <div class="color-preview-box">
                                <div id="previewHeader" style="background: var(--primary-gradient); color: #fff; padding: 12px 18px; border-radius: 8px; font-weight: 700; font-size: 14px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center;">
                                    <span>Sample Card Header</span>
                                    <span style="background: rgba(255,255,255,0.18); font-size: 11px; padding: 2px 8px; border-radius: 99px;">Badge</span>
                                </div>

                                <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                                    <button type="button" id="previewBtnPri" style="background: {{ $secondaryColor }}; color: #172066; font-weight: 700; font-size: 12px; padding: 8px 16px; border-radius: 8px; border: none; cursor: default;">
                                        Primary Button
                                    </button>
                                    <button type="button" id="previewBtnAccent" style="background: {{ $accentColor }}; color: #fff; font-weight: 700; font-size: 12px; padding: 8px 16px; border-radius: 8px; border: none; cursor: default;">
                                        Accent Action
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Edit Program Modal --}}
    <div class="modal fade" id="modalEditProgram" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: none; box-shadow: 0 16px 48px rgba(0,0,0,0.2);">
                <div class="modal-header" style="background: var(--primary-gradient); color: #fff; border: none; padding: 16px 22px;">
                    <h5 class="modal-title" style="font-weight: 800; font-size: 15px; margin: 0;">Edit Program Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" style="padding: 22px;">
                    <input type="hidden" id="editProgId">
                    <div class="mb-3">
                        <label class="form-label">Program Label</label>
                        <input type="text" class="form-control" id="editProgLabel" maxlength="200">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-textarea" id="editProgDesc" rows="3" maxlength="1000"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Display Order</label>
                        <input type="number" class="form-control" id="editProgOrder" min="0" max="999">
                    </div>
                </div>
                <div class="modal-footer" style="border: none; padding: 0 22px 18px;">
                    <button type="button" class="btn-sec" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-pri" onclick="saveProgramEdit()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        const MUNI_NAME = {!! json_encode(Auth::user()->municipality ?? ($municipality?->name ?? '')) !!};
        const PROG_BASE_URL = {!! json_encode(url("admin/settings/programs")) !!};

        // Tab switcher with URL state reflection
        function showSettingsTab(tab, sub = null) {
            ['vmg', 'muni', 'programs', 'theme'].forEach(t => {
                const el = document.getElementById('tabContent-' + t);
                const btn = document.getElementById('tabBtn-' + t);
                if (el) el.style.display = t === tab ? 'block' : 'none';
                if (btn) btn.classList.toggle('active', t === tab);
            });

            // Update URL without full page reload
            try {
                const url = new URL(window.location.href);
                url.searchParams.set('tab', tab);
                if (tab === 'vmg' && (sub === 'sg' || (sub === null && url.searchParams.get('sub') === 'sg'))) {
                    url.searchParams.set('sub', 'sg');
                } else {
                    url.searchParams.delete('sub');
                }
                window.history.replaceState(null, '', url.pathname + url.search);
            } catch (e) {}
        }

        // Toast feedback
        function showToast(msg, isSuccess = true) {
            const toast = document.createElement('div');
            toast.className = 'toast-notify';
            toast.style.background = isSuccess ? '#14663a' : '#8f1d1d';
            toast.innerHTML = (isSuccess ? '&#10003; ' : '&#9888; ') + msg;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 400);
            }, 3000);
        }

        // VMG Goals Add/Remove
        function addGoalInput() {
            const list = document.getElementById('settingsGoalsList');
            const idx = list.querySelectorAll('.sg-row').length;
            if (idx >= 10) { alert('Maximum 10 strategic goals allowed.'); return; }
            const div = document.createElement('div');
            div.className = 'sg-row';
            div.dataset.idx = idx;
            div.innerHTML = `
                <span class="sg-num">${String(idx + 1).padStart(2, '0')}</span>
                <input type="text" class="form-control sg-input-val" value="" placeholder="Strategic goal statement...">
                <button type="button" onclick="removeGoalInput(this)" class="sg-btn-remove" title="Remove">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>`;
            list.appendChild(div);
            div.querySelector('input').focus();
        }

        function removeGoalInput(btn) {
            const row = btn.closest('.sg-row');
            const list = document.getElementById('settingsGoalsList');
            if (list.querySelectorAll('.sg-row').length <= 1) {
                row.querySelector('input').value = '';
                return;
            }
            row.remove();
            list.querySelectorAll('.sg-row').forEach((r, i) => {
                r.querySelector('.sg-num').textContent = String(i + 1).padStart(2, '0');
            });
        }

        // Save VMG
        async function saveSettingsVMG() {
            const btn = document.getElementById('btnSaveVMG');
            const status = document.getElementById('statusVMG');
            btn.disabled = true;
            btn.textContent = 'Saving...';
            status.textContent = '';

            const goals = Array.from(document.querySelectorAll('.sg-input-val'))
                .map(i => i.value.trim())
                .filter(v => v !== '');

            const payload = {
                municipality_name: MUNI_NAME,
                vision: document.getElementById('settingVision').value,
                mission: document.getElementById('settingMission').value,
                goals: document.getElementById('settingGoals') ? document.getElementById('settingGoals').value : null,
                strategic_goals: goals
            };

            const urlParams = new URLSearchParams(window.location.search);
            const isSubSG = urlParams.get('sub') === 'sg';

            try {
                const res = await fetch('{{ route("admin.vision-mission.save") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.success) {
                    showToast('Mission, Vision & Strategic Goals saved!');
                    status.style.color = '#14663a';
                    status.textContent = 'Saved successfully.';
                    setTimeout(() => {
                        window.location.href = window.location.pathname + (isSubSG ? '?tab=vmg&sub=sg' : '?tab=vmg');
                    }, 800);
                } else {
                    showToast(data.message || 'Error saving.', false);
                }
            } catch (e) {
                showToast('Network error while saving.', false);
            }

            btn.disabled = false;
            btn.textContent = 'Save Mission, Vision & Goals';
        }

        // Save Municipality Info
        async function saveSettingsMuni() {
            const btn = document.getElementById('btnSaveMuni');
            const status = document.getElementById('statusMuni');
            btn.disabled = true;
            btn.textContent = 'Saving...';
            status.textContent = '';

            const payload = {
                display_name: document.getElementById('muniDisplayName').value,
                contact_email: document.getElementById('muniEmail').value,
                contact_phone: document.getElementById('muniPhone').value,
                office_hours: document.getElementById('muniHours').value,
                office_address: document.getElementById('muniAddress').value
            };

            try {
                const res = await fetch('{{ route("admin.settings.municipality") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.success) {
                    showToast('Municipality information saved!');
                    status.style.color = '#14663a';
                    status.textContent = 'Saved successfully.';
                    setTimeout(() => {
                        window.location.href = window.location.pathname + '?tab=muni';
                    }, 800);
                } else {
                    showToast(data.message || 'Error saving.', false);
                }
            } catch (e) {
                showToast('Network error while saving.', false);
            }

            btn.disabled = false;
            btn.textContent = 'Save Municipality Information';
        }

        // Toggle Program Status
        async function toggleSettingsProgram(progId) {
            try {
                const res = await fetch(PROG_BASE_URL + '/' + progId + '/toggle', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    const btn = document.getElementById('progToggleBtn-' + progId);
                    btn.className = 'btn-toggle-prog ' + (data.is_active ? 'active' : 'inactive');
                    btn.textContent = data.is_active ? 'Active' : 'Inactive';
                    showToast(data.message);
                } else {
                    showToast(data.message || 'Error toggling status.', false);
                }
            } catch (e) {
                showToast('Network error.', false);
            }
        }

        // Edit Program Modal
        let progModalInstance = null;
        function openEditProgramModal(id, label, desc, order) {
            document.getElementById('editProgId').value = id;
            document.getElementById('editProgLabel').value = label;
            document.getElementById('editProgDesc').value = desc;
            document.getElementById('editProgOrder').value = order;
            if (!progModalInstance) {
                progModalInstance = new bootstrap.Modal(document.getElementById('modalEditProgram'));
            }
            progModalInstance.show();
        }

        async function saveProgramEdit() {
            const id = document.getElementById('editProgId').value;
            const payload = {
                program_label: document.getElementById('editProgLabel').value,
                description: document.getElementById('editProgDesc').value,
                display_order: parseInt(document.getElementById('editProgOrder').value) || 0
            };

            try {
                const res = await fetch(PROG_BASE_URL + '/' + id + '/update', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.success) {
                    showToast(data.message);
                    if (progModalInstance) progModalInstance.hide();
                    setTimeout(() => {
                        window.location.href = window.location.pathname + '?tab=programs';
                    }, 800);
                } else {
                    showToast(data.message || 'Error saving program.', false);
                }
            } catch (e) {
                showToast('Network error.', false);
            }
        }

        // Theme colors live preview
        document.getElementById('primaryColorInput').addEventListener('input', e => {
            document.getElementById('primaryColorText').value = e.target.value;
            updatePreview();
        });
        document.getElementById('secondaryColorInput').addEventListener('input', e => {
            document.getElementById('secondaryColorText').value = e.target.value;
            updatePreview();
        });
        document.getElementById('accentColorInput').addEventListener('input', e => {
            document.getElementById('accentColorText').value = e.target.value;
            updatePreview();
        });

        function updatePreview() {
            const p = document.getElementById('primaryColorInput').value;
            const s = document.getElementById('secondaryColorInput').value;
            const a = document.getElementById('accentColorInput').value;
            document.getElementById('previewHeader').style.background = `linear-gradient(135deg, ${p} 0%, #243088 100%)`;
            document.getElementById('previewBtnPri').style.background = s;
            document.getElementById('previewBtnAccent').style.background = a;
        }

        async function saveColors() {
            const payload = {
                primary_color: document.getElementById('primaryColorInput').value,
                secondary_color: document.getElementById('secondaryColorInput').value,
                accent_color: document.getElementById('accentColorInput').value
            };
            try {
                const res = await fetch('{{ route("admin.settings.update") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.success) {
                    showToast(data.message);
                    setTimeout(() => {
                        window.location.href = window.location.pathname + '?tab=theme';
                    }, 800);
                } else {
                    showToast('Error saving colors.', false);
                }
            } catch (e) {
                showToast('Network error.', false);
            }
        }

        async function resetColors() {
            if (!confirm('Reset all theme colors to default values?')) return;
            try {
                const res = await fetch('{{ route("admin.settings.reset") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
                });
                const data = await res.json();
                if (data.success) {
                    showToast(data.message);
                    setTimeout(() => {
                        window.location.href = window.location.pathname + '?tab=theme';
                    }, 800);
                } else {
                    showToast('Error resetting colors.', false);
                }
            } catch (e) {
                showToast('Network error.', false);
            }
        }

        // Auto-switch tabs from URL parameters (?tab=muni|programs|theme|vmg, ?sub=sg)
        document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            const sub = urlParams.get('sub');
            if (tab && ['vmg', 'muni', 'programs', 'theme'].includes(tab)) {
                showSettingsTab(tab, sub);
            }
            if (sub === 'sg') {
                showSettingsTab('vmg', 'sg');
                const sgEl = document.getElementById('settingsGoalsSection');
                if (sgEl) {
                    setTimeout(() => sgEl.scrollIntoView({ behavior: 'smooth' }), 120);
                }
            }
        });
    </script>
</body>
</html>