<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications – MSWDO Admin</title>
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
                <img src="/images/mswd-logo.png" alt="MSWD" style="width:36px;height:36px;object-fit:contain;"> MSWDO
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
                </ul>
                <div class="d-flex">
                    @auth
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
                    <span
                        style="font-size:0.8rem;font-weight:600;background:rgba(255,255,255,0.15);padding:4px 12px;border-radius:20px;">
                        {{ $applications->total() }} Total
                    </span>
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
                                                    {{-- APPROVE --}}
                                                    @if($app->status !== 'approved')
                                                        <form method="POST"
                                                            action="{{ route('admin.applications.status', $app->id) }}"
                                                            style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="status" value="approved">
                                                            <button type="submit" class="btn-action btn-approve"
                                                                onclick="return confirm('Approve this application?')">✔
                                                                Approve</button>
                                                        </form>
                                                    @endif

                                                    {{-- DECLINE --}}
                                                    @if($app->status !== 'rejected')
                                                        <button type="button" class="btn-action btn-decline" data-bs-toggle="modal"
                                                            data-bs-target="#declineModal" data-id="{{ $app->id }}"
                                                            data-name="{{ $app->full_name }}">✖ Decline</button>
                                                    @endif

                                                    {{-- VIEW --}}
                                                    <a href="{{ route('admin.view-requirement', $app->id) }}"
                                                        class="btn-action btn-view"> View</a>
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

        </div>
    </div>

    <div class="footer-strip">
        MSWDO &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    @include('components.admin-settings-modal')
    @include('components.admin-chat-modal')

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
                            Please provide a reason so the applicant understands why.
                        </p>
                        <label class="form-label" style="font-size:0.85rem;font-weight:700;color:#1e293b;">
                            Reason for Decline <span style="color:#C41E24;">*</span>
                        </label>
                        <textarea name="admin_remarks" id="declineRemarks" class="form-control" rows="4"
                            placeholder="e.g. Missing required documents, incomplete information..."
                            style="border-radius:10px;border:1.5px solid #C7D6F5;font-size:0.88rem;resize:none;"
                            required></textarea>
                        <div id="remarksError" style="color:#C41E24;font-size:0.8rem;margin-top:6px;display:none;">
                            Please provide a reason before declining.
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
            document.getElementById('declineRemarks').value = '';
            document.getElementById('remarksError').style.display = 'none';
            document.getElementById('declineForm').action = '/admin/applications/' + id + '/status';
        });

        // Client-side guard: require remarks before submit
        document.getElementById('declineForm').addEventListener('submit', function (e) {
            var remarks = document.getElementById('declineRemarks').value.trim();
            if (!remarks) {
                e.preventDefault();
                document.getElementById('remarksError').style.display = 'block';
                document.getElementById('declineRemarks').focus();
            }
        });
    </script>
</body>

</html>