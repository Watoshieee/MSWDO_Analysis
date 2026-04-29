<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Applications � MSWDO Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @include('components.admin-colors')
    <style>
html, body { overscroll-behavior: none; margin: 0; padding: 0; }

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
        .navbar-toggler { order: -1; }
        .navbar-brand { order: 0; margin-left: auto !important; margin-right: 0 !important; }
        @media (min-width: 992px) {
            .navbar-toggler { order: 0; }
            .navbar-brand { order: 0; margin-left: 0 !important; margin-right: auto !important; }
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.88) !important;
            font-weight: 600;
            transition: all 0.25s;
            border-radius: 8px;
            padding: 10px 18px !important;
            font-size: 0.95rem;
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
            font-size: 0.95rem;
        }

        .footer-strip {
            background: var(--primary-gradient);
            color: rgba(255, 255, 255, 0.9);
            text-align: center;
            padding: 20px;
            font-size: 0.85rem;
            margin-top: 40px;
        }
    </style>
</head>

<body>

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
                    <li class="nav-item"><a class="nav-link active"
                            href="{{ route('admin.requirements') }}">Applications</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.data.dashboard') }}">Data
                            Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.detailed-analysis') }}">Analysis</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/analysis/programs">Comparative Analysis</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.announcements*') ? 'active' : '' }}" href="{{ route('admin.announcements.index') }}">Announcements</a></li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    @auth
                    <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#adminNotifModal"
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

    <div style="flex:1;">
        <div class="container mt-4">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3"
                    style="border-radius:12px;border:none;background:#d4edda;color:#155724;">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                            onmouseover="this.style.background='rgba(255,255,255,0.22)'" onmouseout="this.style.background='rgba(255,255,255,0.12)'">
                            &#128193; Archived ({{ isset($archivedApplications) ? $archivedApplications->count() : 0 }})
                        </button>
                        <span style="font-size:0.8rem;font-weight:600;background:rgba(255,255,255,0.15);padding:4px 12px;border-radius:20px;">
                            {{ $applications->total() }} Total
                        </span>
                    </div>
                </div>
                <div class="p-0">
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
                                            <td style="font-size:.85rem;color:#475569;">{{ $app->barangay ?: '�' }}</td>
                                            <td style="font-size:.82rem;color:#64748b;">
                                                {{ $app->application_date ? \Carbon\Carbon::parse($app->application_date)->format('M j, Y') : '�' }}
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
                                                    {{-- APPROVE - hide if already approved --}}
                                                    @if($app->status !== 'approved' && $app->status !== 'rejected')
                                                        <form method="POST"
                                                            action="{{ route('admin.applications.status', $app->id) }}"
                                                            style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="status" value="approved">
                                                            <button type="submit" class="btn-action btn-approve"
                                                                onclick="return confirm('Approve this application?')">&#10003; Approve</button>
                                                        </form>
                                                    @endif

                                                    {{-- DECLINE - hide if already rejected --}}
                                                    @if($app->status !== 'rejected' && $app->status !== 'approved')
                                                        <button type="button" class="btn-action btn-decline" data-bs-toggle="modal"
                                                            data-bs-target="#declineModal" data-id="{{ $app->id }}"
                                                            data-name="{{ $app->full_name }}">&#10007; Decline</button>
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
                        <div class="p-3">{{ $applications->links() }}</div>
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
                    <span>&#128197; Solo Parent Appointments</span>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <button type="button" onclick="openArchivedAppts()"
                            style="background:rgba(255,255,255,0.12);border:1.5px solid rgba(255,255,255,0.3);color:white;border-radius:20px;padding:5px 16px;font-size:0.8rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:6px;transition:all .2s;"
                            onmouseover="this.style.background='rgba(255,255,255,0.22)'" onmouseout="this.style.background='rgba(255,255,255,0.12)'">
                            &#128193; Archived (<span id="archivedApptCount">0</span>)
                        </button>
                        <span id="apptCount" style="font-size:0.8rem;font-weight:600;background:rgba(255,255,255,0.15);padding:4px 12px;border-radius:20px;">Loading&hellip;</span>
                    </div>
                </div>
                <div class="p-3" id="apptTableWrap">
                    <div style="text-align:center;padding:30px;color:#94a3b8;font-size:.9rem;">Loading appointments&hellip;</div>
                </div>
            </div>

        </div>
    </div>


            {{-- Reject modal --}}
            <div id="rejectModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
                <div style="background:white;border-radius:16px;padding:28px;max-width:440px;width:90%;box-shadow:0 8px 40px rgba(0,0,0,.2);">
                    <h5 style="font-weight:800;margin-bottom:14px;color:#1e293b;">❌ Reject Appointment</h5>
                    <p style="font-size:.85rem;color:#64748b;margin-bottom:14px;">Provide a reason for rejection. This will be sent to the user via email.</p>
                    <textarea id="rejectNotes" rows="3" class="form-control" placeholder="Reason for rejection…" style="border-radius:10px;margin-bottom:16px;"></textarea>
                    <div style="display:flex;gap:10px;">
                        <button onclick="submitReject()" style="background:linear-gradient(135deg,#dc3545,#b91c1c);color:white;border:none;border-radius:10px;padding:10px 24px;font-weight:700;cursor:pointer;flex:1;">Send Rejection</button>
                        <button onclick="closeRejectModal()" style="background:#f1f5f9;color:#374151;border:none;border-radius:10px;padding:10px 20px;font-weight:600;cursor:pointer;">Cancel</button>
                    </div>
                </div>
            </div>

            <script>
            var rejectTargetId = null;
            var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

            function loadAppointments() {
                fetch('/admin/appointments', {headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}})
                .then(r => r.json())
                .then(data => {
                    document.getElementById('apptCount').textContent = data.length + ' Total';
                    if (!data.length) {
                        document.getElementById('apptTableWrap').innerHTML = '<div style="text-align:center;padding:30px;color:#94a3b8;font-size:.9rem;">No appointments yet.</div>';
                        return;
                    }

                    let rows = data.map(a => {
                        let actions = '';
                        if (a.status === 'pending') {
                            actions = `
                                <button onclick="confirmAppt(${a.id})" style="background:#d4edda;color:#155724;border:none;border-radius:8px;padding:5px 14px;font-size:.78rem;font-weight:700;cursor:pointer;margin-right:6px;">✅ Confirm</button>
                                <button onclick="openRejectModal(${a.id})" style="background:#f8d7da;color:#721c24;border:none;border-radius:8px;padding:5px 14px;font-size:.78rem;font-weight:700;cursor:pointer;">❌ Reject</button>
                            `;
                        } else if (a.status === 'confirmed') {
                            actions = `
                                <button onclick="validateAppt(${a.id})" style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border:none;border-radius:8px;padding:5px 14px;font-size:.78rem;font-weight:700;cursor:pointer;margin-right:6px;">🏆 Validate</button>
                                <button onclick="openRejectModal(${a.id})" style="background:#f8d7da;color:#721c24;border:none;border-radius:8px;padding:5px 14px;font-size:.78rem;font-weight:700;cursor:pointer;">❌ Reject</button>
                            `;
                        } else if (a.status === 'validated') {
                            if (a.id_status === 'ready_for_pickup') {
                                actions = `<span style="background:#d4edda;color:#155724;border-radius:8px;padding:5px 14px;font-size:.78rem;font-weight:700;">🎫 ID Ready</span>`;
                            } else {
                                actions = `<button onclick="markIdReady(${a.solo_parent_app_id},'${encodeURIComponent(a.user_name)}')" style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border:none;border-radius:8px;padding:5px 14px;font-size:.78rem;font-weight:700;cursor:pointer;">🎫 ID Ready</button>`;
                            }
                        } else {
                            actions = '<span style="color:#94a3b8;font-size:.78rem;">—</span>';
                        }
                        return `<tr>
                            <td style="font-weight:700;">${a.user_name}<br><small style="color:#94a3b8;font-size:.72rem;">${a.user_email}</small></td>
                            <td>${a.date}<br><small style="color:#64748b;">${a.day}</small></td>
                            <td>${a.time}</td>
                            <td>${a.interview_type}</td>
                            <td>${a.status_badge}</td>
                            <td>${a.user_notes ? `<span title="${a.user_notes}" style="cursor:help;">📝</span>` : '—'}</td>
                            <td><div style="display:flex;flex-wrap:wrap;gap:5px;align-items:center;">${actions}<button onclick="archiveAppt(${a.id},'${encodeURIComponent(a.user_name)}')" style="background:#f8faff;color:#64748b;border:1.5px solid #e2e8f0;border-radius:8px;padding:4px 12px;font-size:.76rem;font-weight:700;cursor:pointer;">&#128193; Archive</button></div></td>
                        </tr>`;
                    }).join('');

                    document.getElementById('apptTableWrap').innerHTML = `
                        <div class="table-responsive">
                        <table class="table mb-0" style="font-size:.85rem;">
                            <thead><tr>
                                <th>Applicant</th><th>Date</th><th>Time</th><th>Type</th><th>Status</th><th>Notes</th><th>Actions</th>
                            </tr></thead>
                            <tbody>${rows}</tbody>
                        </table>
                        </div>`;
                })
                .catch(e => {
                    document.getElementById('apptTableWrap').innerHTML = '<div style="padding:20px;color:#dc3545;">Failed to load appointments.</div>';
                });
            }

            function confirmAppt(id) {
                if (!confirm('Confirm this appointment? An email will be sent to the user.')) return;
                fetch(`/admin/appointments/${id}/confirm`, {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json'},
                    body: JSON.stringify({admin_notes: ''})
                }).then(r => r.json()).then(d => {
                    alert(d.message);
                    loadAppointments();
                }).catch(() => alert('Error confirming appointment.'));
            }

            function validateAppt(id) {
                if (!confirm('Validate this appointment?\n\nThis will:\n- Mark the applicant as eligible for Solo Parent ID\n- Create their requirements submission form\n- Send them an email notification')) return;
                fetch(`/admin/appointments/${id}/validate`, {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json'},
                    body: JSON.stringify({})
                }).then(r => r.json()).then(d => {
                    if (d.success) {
                        alert('✅ ' + d.message);
                        loadAppointments();
                    } else {
                        alert('⚠️ ' + (d.message || 'Error validating appointment.'));
                    }
                }).catch(() => alert('Error validating appointment.'));
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
                fetch(`/admin/appointments/${rejectTargetId}/reject`, {
                    method: 'POST',
                    headers: {'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json'},
                    body: JSON.stringify({admin_notes: notes})
                }).then(r => r.json()).then(d => {
                    closeRejectModal();
                    alert(d.message);
                    loadAppointments();
                }).catch(() => alert('Error rejecting appointment.'));
            }

            // Load on page ready
            document.addEventListener('DOMContentLoaded', loadAppointments);
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
                            <option value="Does not meet eligibility requirements">Does not meet eligibility requirements</option>
                            <option value="Invalid or expired documents">Invalid or expired documents</option>
                            <option value="Incorrect information provided">Incorrect information provided</option>
                            <option value="Duplicate application">Duplicate application</option>
                            <option value="Other">Other (specify below)</option>
                        </select>
                        <label class="form-label" style="font-size:0.85rem;font-weight:700;color:#1e293b;" for="declineRemarks">
                            Additional Comments <span style="font-size:0.75rem;font-weight:400;color:#64748b;">(Optional)</span>
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
        document.getElementById('rejectionReason').addEventListener('change', function() {
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
    function archiveApp(id, name) {
        if (!confirm('Archive the application of "' + name + '"?\n\nThis will move it to the archive. You can restore it later.')) return;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/applications/' + id + '/archive';
        form.innerHTML = '<input type="hidden" name="_token" value="' + document.querySelector('meta[name=csrf-token]').content + '">' +
                         '<input type="hidden" name="_method" value="DELETE">';
        document.body.appendChild(form);
        form.submit();
    }

    function deleteAppDirect(id, name) {
        if (!confirm('PERMANENTLY DELETE the application of "' + name + '"?\n\n⚠ This action CANNOT be undone. The record will be gone forever.')) return;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/applications/' + id + '/direct-delete';
        form.innerHTML = '<input type="hidden" name="_token" value="' + document.querySelector('meta[name=csrf-token]').content + '">' +
                         '<input type="hidden" name="_method" value="DELETE">';
        document.body.appendChild(form);
        form.submit();
    }
    </script>

    {{-- ═══════════════════ ARCHIVE MODAL ═══════════════════════════ --}}
    <div id="archiveModal"
         style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:10000;align-items:center;justify-content:center;"
         onclick="if(event.target===this) this.style.display='none'">
        <div style="background:white;width:90%;max-width:860px;border-radius:18px;max-height:82vh;display:flex;flex-direction:column;box-shadow:0 8px 48px rgba(44,62,143,.22);animation:fadeInScale .25s ease;">
            {{-- Modal header --}}
            <div style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);padding:18px 26px;border-radius:18px 18px 0 0;display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <div style="color:white;font-weight:800;font-size:1rem;">&#128193; Archived Applications</div>
                    <div style="color:rgba(255,255,255,.7);font-size:.8rem;margin-top:2px;">
                        {{ isset($archivedApplications) ? $archivedApplications->count() : 0 }} archived record(s) &mdash; restore or permanently delete
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
                                    <form method="POST" action="{{ route('admin.applications.restore', $app->id) }}" style="display:inline;">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn-action btn-approve"
                                            onclick="return confirm('Restore this application?')" style="font-size:.76rem;">
                                            &#8593; Restore
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.applications.force-delete', $app->id) }}" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-action btn-decline"
                                            onclick="return confirm('Permanently delete this application? This cannot be undone.')" style="font-size:.76rem;">
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
        from { transform: scale(.95); opacity: 0; }
        to   { transform: scale(1);   opacity: 1; }
    }
    </style>


    <script>
    function markIdReady(appId, encodedName) {
        const name = decodeURIComponent(encodedName);
        if (!confirm('Mark Solo Parent ID of "' + name + '" as ready for pickup?\n\nThis will send a pickup notification email to the user.')) return;
        fetch('/admin/applications/' + appId + '/mark-id-ready', {
            method: 'POST',
            headers: {'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json'},
        }).then(r => r.json()).then(d => {
            alert(d.message);
            loadAppointments();
        }).catch(() => alert('Failed to mark ID as ready.'));
    }

    function archiveAppt(id, name) {
        const displayName = decodeURIComponent(name);
        if (!confirm('Archive the appointment of "' + displayName + '"?\n\nThis will move it to the archive.')) return;
        fetch('/admin/appointments/' + id + '/archive', {
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json'},
        }).then(r => r.json()).then(d => {
            alert(d.message);
            loadAppointments();
            loadArchivedAppts();
        }).catch(() => alert('Archive failed.'));
    }

    function openArchivedAppts() {
        document.getElementById('apptArchiveModal').style.display = 'flex';
        loadArchivedAppts();
    }

    function loadArchivedAppts() {
        fetch('/admin/appointments/archived', {headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}})
        .then(r => r.json()).then(data => {
            document.getElementById('archivedApptCount').textContent = data.length;
            const wrap = document.getElementById('archivedApptBody');
            if (!data.length) {
                wrap.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:30px;color:#94a3b8;">No archived appointments.</td></tr>';
                return;
            }
            wrap.innerHTML = data.map(a => `<tr style="background:#f8faff;">
                <td><div style="font-weight:700;color:#475569;">${a.user_name}</div><div style="font-size:.72rem;color:#94a3b8;">${a.user_email}</div></td>
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
            </tr>`).join('');
        });
    }

    function restoreAppt(id) {
        if (!confirm('Restore this appointment?')) return;
        fetch('/admin/appointments/' + id + '/restore', {
            method: 'PATCH',
            headers: {'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json'},
        }).then(r => r.json()).then(d => { alert(d.message); loadArchivedAppts(); loadAppointments(); });
    }

    function forceDeleteAppt(id) {
        if (!confirm('Permanently delete this appointment? This CANNOT be undone.')) return;
        fetch('/admin/appointments/' + id + '/force-delete', {
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': csrfToken, 'Content-Type': 'application/json', 'Accept': 'application/json'},
        }).then(r => r.json()).then(d => { alert(d.message); loadArchivedAppts(); });
    }
    </script>

    {{-- ═══════════════ ARCHIVED APPOINTMENTS MODAL ═══════════════════════════ --}}
    <div id="apptArchiveModal"
         style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:10001;align-items:center;justify-content:center;"
         onclick="if(event.target===this) this.style.display='none'">
        <div style="background:white;width:90%;max-width:900px;border-radius:18px;max-height:82vh;display:flex;flex-direction:column;box-shadow:0 8px 48px rgba(44,62,143,.22);animation:fadeInScale .25s ease;">
            <div style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);padding:18px 26px;border-radius:18px 18px 0 0;display:flex;align-items:center;justify-content:space-between;">
                <div>
                    <div style="color:white;font-weight:800;font-size:1rem;">&#128193; Archived Appointments</div>
                    <div style="color:rgba(255,255,255,.7);font-size:.8rem;margin-top:2px;">Restore or permanently delete archived appointments</div>
                </div>
                <button onclick="document.getElementById('apptArchiveModal').style.display='none'"
                    style="background:rgba(255,255,255,.15);border:none;color:white;border-radius:50%;width:34px;height:34px;font-size:1.1rem;cursor:pointer;line-height:1;">&times;</button>
            </div>
            <div style="overflow-y:auto;padding:0;">
                <table class="table mb-0" style="font-size:.85rem;">
                    <thead>
                        <tr>
                            <th>Applicant</th><th>Date</th><th>Time</th><th>Type</th><th>Status</th><th>Archived On</th><th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="archivedApptBody">
                        <tr><td colspan="7" style="text-align:center;padding:30px;color:#94a3b8;">Loading&hellip;</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>