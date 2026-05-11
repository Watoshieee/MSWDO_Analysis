<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Applications – MSWDO Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @include('components.admin-colors')
    <style>
        html, body { overscroll-behavior: none; margin: 0; padding: 0; }
        :root { --bg-light: #F8FAFC; --bg-soft-blue: #F0F5FF; --border-light: #E2E8F0; --text-dark: #1E293B; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--bg-light); font-family: 'Inter', sans-serif; color: var(--text-dark); min-height: 100vh; display: flex; flex-direction: column; }
        a { text-decoration: none; }
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,0.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.55rem; color: white !important; display: flex; align-items: center; gap: 12px; }
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; transition: all 0.25s; border-radius: 8px; padding: 10px 18px !important; font-size: 0.95rem; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .user-info { color: white; display: flex; align-items: center; gap: 12px; background: rgba(255,255,255,0.1); padding: 9px 22px; border-radius: 40px; font-size: 0.92rem; font-weight: 500; }
        .logout-btn { background: transparent; border: 2px solid rgba(255,255,255,0.8); color: white; border-radius: 30px; padding: 6px 18px; font-weight: 700; transition: all 0.3s; font-size: 0.88rem; cursor: pointer; }
        .logout-btn:hover { background: var(--secondary-yellow); color: var(--primary-blue); border-color: var(--secondary-yellow); }
        .page-hero { background: var(--primary-gradient); border-radius: 20px; padding: 32px 36px; margin-bottom: 28px; color: white; position: relative; overflow: hidden; }
        .page-hero h1 { font-size: 1.75rem; font-weight: 800; margin-bottom: 4px; }
        .page-hero p { opacity: 0.82; margin: 0; font-size: 0.92rem; }
        .muni-badge { background: rgba(253,185,19,0.18); border: 1px solid rgba(253,185,19,0.35); color: #FDB913; border-radius: 30px; padding: 7px 20px; font-size: 0.85rem; font-weight: 700; display: inline-block; }
        .stat-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .stat-card { background: #fff; border-radius: 14px; padding: 20px 22px; border: 1px solid var(--border-light); box-shadow: 0 2px 8px rgba(0,0,0,0.03); }
        .stat-card .label { font-size: 0.78rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.04em; }
        .stat-card .value { font-size: 1.8rem; font-weight: 800; margin-top: 4px; }
        .stat-total .value { color: var(--primary-blue); }
        .stat-pending .value { color: #856404; }
        .stat-approved .value { color: #155724; }
        .stat-rejected .value { color: #C41E24; }
        .filter-bar { background: #fff; border-radius: 14px; padding: 18px 22px; border: 1px solid var(--border-light); margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 12px; align-items: end; }
        .filter-bar .form-group { display: flex; flex-direction: column; gap: 4px; }
        .filter-bar label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; }
        .filter-bar select, .filter-bar input { border: 1.5px solid #C7D6F5; border-radius: 8px; padding: 7px 12px; font-size: 0.85rem; font-family: 'Inter', sans-serif; }
        .filter-bar .btn-filter { background: var(--primary-gradient); color: white; border: none; border-radius: 8px; padding: 8px 20px; font-weight: 700; font-size: 0.85rem; cursor: pointer; align-self: end; }
        .filter-bar .btn-reset { background: #f1f5f9; color: #64748b; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 8px 16px; font-weight: 600; font-size: 0.85rem; cursor: pointer; align-self: end; }
        .panel-card { background: #fff; border-radius: 18px; border: 1px solid var(--border-light); box-shadow: 0 4px 15px rgba(0,0,0,0.03); overflow: hidden; }
        .panel-header { background: var(--primary-gradient); color: white; padding: 18px 26px; font-size: 1rem; font-weight: 700; display: flex; justify-content: space-between; align-items: center; }
        .table thead th { background: var(--bg-soft-blue); color: var(--primary-blue); font-weight: 700; font-size: 0.775rem; border: none; padding: 13px 15px; text-transform: uppercase; letter-spacing: 0.05em; }
        .table tbody td { padding: 14px 15px; font-size: 0.875rem; vertical-align: middle; border-color: #F1F5F9; }
        .table tbody tr:hover { background: #F8FAFC; }
        .prog-tag { background: var(--bg-soft-blue); color: var(--primary-blue); padding: 3px 10px; border-radius: 20px; font-size: 0.76rem; font-weight: 700; }
        .status-pill { padding: 4px 14px; border-radius: 20px; font-size: 0.76rem; font-weight: 700; display: inline-block; }
        .status-pending { background: #FFF3D6; color: #856404; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-rejected { background: #FCE8E8; color: #C41E24; }
        .btn-action { border: none; border-radius: 8px; padding: 5px 13px; font-weight: 700; font-size: 0.78rem; transition: all 0.2s; cursor: pointer; display: inline-block; text-decoration: none; }
        .btn-approve { background: #d4edda; color: #155724; }
        .btn-approve:hover { background: #28a745; color: white; }
        .btn-decline { background: #FCE8E8; color: #C41E24; }
        .btn-decline:hover { background: #C41E24; color: white; }
        .btn-view { background: var(--primary-gradient); color: white; }
        .btn-view:hover { opacity: 0.88; color: white; box-shadow: 0 4px 12px rgba(44,62,143,0.3); }
        .action-gap { display: flex; gap: 6px; flex-wrap: wrap; }
        .empty-state { text-align: center; padding: 60px 24px; }
        .empty-num { font-size: 5rem; font-weight: 800; color: #E2E8F0; line-height: 1; }
        .empty-state p { color: #94a3b8; margin-top: 10px; font-size: 0.95rem; }
        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,0.9); text-align: center; padding: 20px; font-size: 0.85rem; margin-top: 40px; }
        .file-preview-grid { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 8px; }
        .file-thumb { width: 60px; height: 60px; border-radius: 8px; border: 1.5px solid #e2e8f0; object-fit: cover; cursor: pointer; transition: transform 0.2s; }
        .file-thumb:hover { transform: scale(1.1); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .pdf-thumb { width: 60px; height: 60px; border-radius: 8px; border: 1.5px solid #e2e8f0; display: flex; align-items: center; justify-content: center; background: #FFF3D6; color: #856404; font-weight: 800; font-size: 0.65rem; cursor: pointer; transition: transform 0.2s; }
        .pdf-thumb:hover { transform: scale(1.1); }
        .remarks-text { font-size: 0.78rem; color: #C41E24; font-style: italic; margin-top: 4px; max-width: 200px; }
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
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.applications') }}">Applications</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.requirements') }}">Requirements</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.data.dashboard') }}">Data Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.detailed-analysis') }}">Analysis</a></li>
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

            @include('components.admin-notification')

            {{-- PAGE HERO --}}
            <div class="page-hero mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1>📋 Applications Management</h1>
                        <p>Review, approve, or reject submitted program applications from mobile users.</p>
                    </div>
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <span class="muni-badge">📍 {{ $municipality->name ?? 'Municipality' }}</span>
                    </div>
                </div>
            </div>

            {{-- STATS ROW --}}
            @php
                $total = $applications->total();
                $pending = \App\Models\Application::where('municipality', Auth::user()->municipality)->where('status', 'pending')->count();
                $approved = \App\Models\Application::where('municipality', Auth::user()->municipality)->where('status', 'approved')->count();
                $rejected = \App\Models\Application::where('municipality', Auth::user()->municipality)->where('status', 'rejected')->count();
            @endphp
            <div class="stat-row">
                <div class="stat-card stat-total">
                    <div class="label">Total Applications</div>
                    <div class="value">{{ $total }}</div>
                </div>
                <div class="stat-card stat-pending">
                    <div class="label">Pending</div>
                    <div class="value">{{ $pending }}</div>
                </div>
                <div class="stat-card stat-approved">
                    <div class="label">Approved</div>
                    <div class="value">{{ $approved }}</div>
                </div>
                <div class="stat-card stat-rejected">
                    <div class="label">Rejected</div>
                    <div class="value">{{ $rejected }}</div>
                </div>
            </div>

            {{-- FILTER BAR --}}
            <form method="GET" action="{{ route('admin.applications') }}">
                <div class="filter-bar">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Program</label>
                        <select name="program_type">
                            <option value="">All Programs</option>
                            @foreach($programTypes as $pt)
                                <option value="{{ $pt }}" {{ request('program_type') == $pt ? 'selected' : '' }}>{{ str_replace('_', ' ', $pt) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Barangay</label>
                        <select name="barangay">
                            <option value="">All Barangays</option>
                            @foreach($barangays as $b)
                                <option value="{{ $b }}" {{ request('barangay') == $b ? 'selected' : '' }}>{{ $b }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Search Name</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Applicant name...">
                    </div>
                    <button type="submit" class="btn-filter">🔍 Filter</button>
                    <a href="{{ route('admin.applications') }}" class="btn-reset">Reset</a>
                </div>
            </form>

            {{-- APPLICATIONS TABLE --}}
            <div class="panel-card">
                <div class="panel-header">
                    <span>Submitted Applications</span>
                    <span style="font-size:0.8rem;font-weight:600;background:rgba(255,255,255,0.15);padding:4px 12px;border-radius:20px;">
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
                                        <th>Files</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applications as $app)
                                        @php
                                            $fm = $app->fileMonitoring;
                                            $files = $fm ? $fm->fileUploads : collect();
                                        @endphp
                                        <tr>
                                            <td>
                                                <div style="font-weight:700;color:#1e293b;">{{ $app->full_name }}</div>
                                                <div style="font-size:.72rem;color:#94a3b8;">{{ $app->contact_number }}</div>
                                            </td>
                                            <td><span class="prog-tag">{{ str_replace('_', ' ', $app->program_type) }}</span></td>
                                            <td style="font-size:.85rem;color:#475569;">{{ $app->barangay ?: '—' }}</td>
                                            <td style="font-size:.82rem;color:#64748b;">
                                                {{ $app->application_date ? \Carbon\Carbon::parse($app->application_date)->format('M j, Y') : '—' }}
                                            </td>
                                            <td>
                                                @if($files->count() > 0)
                                                    <div class="file-preview-grid">
                                                        @foreach($files as $fu)
                                                            @php
                                                                $ext = strtolower(pathinfo($fu->file_name, PATHINFO_EXTENSION));
                                                                $url = asset('storage/' . $fu->file_path);
                                                            @endphp
                                                            @if(in_array($ext, ['jpg','jpeg','png']))
                                                                <img src="{{ $url }}" class="file-thumb" alt="{{ $fu->requirement_name }}"
                                                                     onclick="openPreview('{{ $url }}', 'image', '{{ $fu->requirement_name }}')" title="{{ $fu->requirement_name }}">
                                                            @elseif($ext === 'pdf')
                                                                <div class="pdf-thumb" onclick="openPreview('{{ $url }}', 'pdf', '{{ $fu->requirement_name }}')" title="{{ $fu->requirement_name }}">
                                                                    PDF<br>📄
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span style="font-size:.78rem;color:#94a3b8;">No files</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($app->status === 'approved')
                                                    <span class="status-pill status-approved">✔ Approved</span>
                                                @elseif($app->status === 'rejected')
                                                    <span class="status-pill status-rejected">✖ Rejected</span>
                                                    @if($app->admin_remarks)
                                                        <div class="remarks-text">{{ Str::limit($app->admin_remarks, 60) }}</div>
                                                    @endif
                                                @else
                                                    <span class="status-pill status-pending">⏳ Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="action-gap">
                                                    @if($app->status !== 'approved')
                                                        <form method="POST" action="{{ route('admin.applications.status', $app->id) }}" style="display:inline;">
                                                            @csrf
                                                            <input type="hidden" name="status" value="approved">
                                                            <button type="submit" class="btn-action btn-approve" onclick="return confirm('Approve this application?')">✔ Approve</button>
                                                        </form>
                                                    @endif
                                                    @if($app->status !== 'rejected')
                                                        <button type="button" class="btn-action btn-decline" data-bs-toggle="modal"
                                                            data-bs-target="#declineModal" data-id="{{ $app->id }}" data-name="{{ $app->full_name }}">✖ Reject</button>
                                                    @endif
                                                    <a href="{{ route('admin.view-requirement', $app->id) }}" class="btn-action btn-view">👁 View</a>
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
                            <p>No applications found matching your filters.</p>
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

    {{-- FILE PREVIEW MODAL --}}
    <div class="modal fade" id="filePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px;border:none;overflow:hidden;">
                <div class="modal-header" style="background:var(--primary-gradient);color:white;border:none;padding:16px 24px;">
                    <h5 class="modal-title" style="font-weight:700;" id="previewTitle">File Preview</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0" style="min-height:400px;display:flex;align-items:center;justify-content:center;background:#f8fafc;">
                    <img id="previewImage" src="" style="max-width:100%;max-height:70vh;display:none;">
                    <iframe id="previewPdf" src="" style="width:100%;height:70vh;border:none;display:none;"></iframe>
                </div>
            </div>
        </div>
    </div>

    {{-- DECLINE MODAL --}}
    <div class="modal fade" id="declineModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px;border:none;overflow:hidden;">
                <div class="modal-header" style="background:var(--primary-gradient);color:white;border:none;padding:18px 24px;">
                    <h5 class="modal-title" style="font-weight:800;">Reject Application</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="declineForm" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <div class="modal-body" style="padding:24px;">
                        <p style="color:#475569;font-size:0.9rem;margin-bottom:14px;">
                            You are rejecting the application of <strong id="declineApplicantName"></strong>.
                            Please provide a reason so the applicant can re-submit.
                        </p>
                        <label class="form-label" style="font-size:0.85rem;font-weight:700;color:#1e293b;">
                            Reason for Rejection <span style="color:#C41E24;">*</span>
                        </label>
                        <textarea name="admin_remarks" id="declineRemarks" class="form-control" rows="4"
                            placeholder="e.g. Missing required documents, blurry photo, incomplete information..."
                            style="border-radius:10px;border:1.5px solid #C7D6F5;font-size:0.88rem;resize:none;" required></textarea>
                        <div id="remarksError" style="color:#C41E24;font-size:0.8rem;margin-top:6px;display:none;">
                            Please provide a reason before rejecting.
                        </div>
                    </div>
                    <div class="modal-footer" style="border:none;padding:16px 24px 20px;gap:8px;">
                        <button type="button" class="btn" data-bs-dismiss="modal"
                            style="border-radius:8px;font-weight:700;font-size:0.85rem;border:1.5px solid #C7D6F5;padding:8px 20px;">Cancel</button>
                        <button type="submit" class="btn"
                            style="background:linear-gradient(135deg,#C41E24,#8B0000);color:white;border-radius:8px;font-weight:700;font-size:0.85rem;border:none;padding:8px 22px;">
                            Confirm Rejection
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Decline modal wiring
        document.getElementById('declineModal').addEventListener('show.bs.modal', function(event) {
            var btn = event.relatedTarget;
            document.getElementById('declineApplicantName').textContent = btn.getAttribute('data-name');
            document.getElementById('declineRemarks').value = '';
            document.getElementById('remarksError').style.display = 'none';
            document.getElementById('declineForm').action = '/admin/applications/' + btn.getAttribute('data-id') + '/status';
        });

        document.getElementById('declineForm').addEventListener('submit', function(e) {
            var remarks = document.getElementById('declineRemarks').value.trim();
            if (!remarks) {
                e.preventDefault();
                document.getElementById('remarksError').style.display = 'block';
                document.getElementById('declineRemarks').focus();
            }
        });

        // File preview modal
        function openPreview(url, type, name) {
            document.getElementById('previewTitle').textContent = name || 'File Preview';
            var img = document.getElementById('previewImage');
            var pdf = document.getElementById('previewPdf');
            if (type === 'image') {
                img.src = url; img.style.display = 'block';
                pdf.src = ''; pdf.style.display = 'none';
            } else {
                pdf.src = url; pdf.style.display = 'block';
                img.src = ''; img.style.display = 'none';
            }
            new bootstrap.Modal(document.getElementById('filePreviewModal')).show();
        }
    </script>
</body>
</html>
