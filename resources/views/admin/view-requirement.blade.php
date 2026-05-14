<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Review Requirements - {{ $application->full_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
html, body { overscroll-behavior: none; margin: 0; padding: 0; }

        :root {
            --primary-blue: #2C3E8F;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --secondary-yellow: #FDB913;
            --bg-light: #F8FAFC;
            --border-light: #E2E8F0;
        }
        body { background: var(--bg-light); font-family: 'Inter', sans-serif; min-height: 100vh; }

        /* NAVBAR */
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.4rem; color: white !important; display:flex; align-items:center; gap:10px; }
        .nav-link { color: rgba(255,255,255,.88) !important; font-weight:600; border-radius:8px; padding:10px 18px !important; font-size: .85rem; transition:all .25s; white-space: nowrap; }
        .nav-link:hover { background: rgba(255,255,255,.15); }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; }
        .user-info { color:white; background:rgba(255,255,255,.1); padding:9px 22px; border-radius:40px; font-size:.9rem; font-weight:600; }
        .logout-btn { background:transparent; border:2px solid rgba(255,255,255,.8); color:white; border-radius:30px; padding:6px 18px; font-weight:700; font-size:.88rem; cursor:pointer; transition:all .3s; }
        .logout-btn:hover { background: var(--secondary-yellow); color: var(--primary-blue); border-color: var(--secondary-yellow); }

        /* HERO */
        .page-hero { background: var(--primary-gradient); border-radius:18px; padding:28px 32px; color:white; margin-bottom:24px; }
        .page-hero h1 { font-size:1.5rem; font-weight:800; margin:0 0 4px; }
        .page-hero p  { margin:0; opacity:.82; font-size:.9rem; }

        /* CARDS */
        .info-card { background:white; border-radius:16px; border:1px solid var(--border-light); padding:24px 28px; box-shadow:0 2px 12px rgba(0,0,0,.04); margin-bottom:22px; }
        .info-card h5 { font-size:.8rem; font-weight:800; color:#94a3b8; text-transform:uppercase; letter-spacing:.08em; margin-bottom:14px; }

        /* REQUIREMENT REVIEW CARDS - Box Layout */
        .req-grid {
            display: flex;
            flex-direction: column;
            gap: 14px;
            margin-top: 20px;
        }
        
        .req-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
            padding: 14px;
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }
        
        .req-card:hover {
            box-shadow: 0 3px 10px rgba(44, 62, 143, 0.1);
        }
        
        .req-card.pending { border-left: 4px solid #fdb913; }
        .req-card.approved { border-left: 4px solid #28a745; }
        .req-card.rejected { border-left: 4px solid #dc3545; }
        
        .req-card-image {
            width: 110px;
            min-width: 110px;
            height: 110px;
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 8px;
            cursor: pointer;
            border: 1px solid #e2e8f0;
        }
        
        .req-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .req-card:hover .req-card-image img {
            transform: scale(1.05);
        }
        
        .req-file-icon {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            color: #94a3b8;
        }
        
        .req-file-ext {
            font-size: 0.65rem;
            font-weight: 700;
            color: #64748b;
            background: white;
            padding: 3px 8px;
            border-radius: 10px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        
        .req-card-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        
        .req-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #1e293b;
            line-height: 1.3;
            margin-bottom: 2px;
        }
        
        .req-date {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
        }
        
        .req-status-badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.72rem;
            font-weight: 700;
            display: inline-block;
            width: fit-content;
            margin-top: 2px;
        }
        
        .req-remarks {
            background: #fff3cd;
            border-left: 3px solid #fdb913;
            padding: 8px 10px;
            border-radius: 6px;
            font-size: 0.75rem;
            color: #856404;
            line-height: 1.4;
        }
        
        .req-actions {
            display: flex;
            gap: 8px;
            margin-top: 4px;
        }
        
        .req-btn {
            border: none;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: 700;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
        }
        
        .req-btn-approve {
            background: #d4edda;
            color: #155724;
        }
        
        .req-btn-approve:hover {
            background: #28a745;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        }
        
        .req-btn-decline {
            background: #f8d7da;
            color: #721c24;
        }
        
        .req-btn-decline:hover {
            background: #dc3545;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
        }
        
        .req-status-text {
            font-size: 0.8rem;
            font-weight: 700;
            padding: 8px 16px;
            border-radius: 6px;
        }
        
        .req-text-approved {
            color: #28a745;
            background: #f6fff6;
            border: 1px solid #d4edda;
        }
        
        .req-text-rejected {
            color: #856404;
            background: #fff3cd;
            border: 1px solid #ffc107;
        }
        
        @media (max-width: 768px) {
            .req-card {
                flex-direction: column;
            }
            .req-card-image {
                width: 100%;
                height: 150px;
            }
        }

        /* STATUS BADGES */
        .s-badge { padding:4px 13px; border-radius:20px; font-size:.74rem; font-weight:700; display:inline-block; }
        .s-approved { background:#d4edda; color:#155724; }
        .s-rejected  { background:#f8d7da; color:#721c24; }
        .s-pending   { background:#fff3cd; color:#856404; }
        .s-in_review { background:#d1ecf1; color:#0c5460; }

        /* FILE PREVIEW */
        .file-thumb { width:90px; height:90px; object-fit:cover; border-radius:10px; border:1px solid var(--border-light); cursor:pointer; transition:opacity .2s; }
        .file-thumb:hover { opacity:.85; }

        /* ACTION BUTTONS */
        .action-gap {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
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
        .btn-view { background: var(--primary-blue); color: white; border: none; border-radius: 8px; padding: 6px 16px; font-weight: 600; font-size: .78rem; cursor: pointer; transition: all .2s; }
        .btn-view:hover { background: #1A2A5C; transform: translateY(-1px); }

        /* MODAL */
        .modal-header { background: var(--primary-gradient); color:white; border-radius:14px 14px 0 0; padding:18px 24px; border: none; }
        .modal-title { font-weight:800; font-size:1rem; }
        .modal-content { border-radius:14px; border:none; }
        .modal-body { padding:24px; }
        .modal-footer { padding:16px 24px; border-top:1px solid var(--border-light); background: var(--bg-light); }
        .modal-header .btn-close { background-color: white; opacity: 0.8; }
        
        /* File viewer in modal */
        .file-viewer-container {
            text-align: center;
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .file-viewer-container img {
            max-width: 100%;
            max-height: 60vh;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }
        .file-viewer-container iframe {
            width: 100%;
            height: 60vh;
            border: none;
            border-radius: 8px;
        }
        .file-info-detail {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--border-light);
        }
        .file-info-detail p { margin: 5px 0; font-size: 0.85rem; }

        .footer-strip { background:var(--primary-gradient); color:rgba(255,255,255,.8); text-align:center; padding:18px; font-size:.84rem; margin-top:40px; }

        /* Navy loading overlay for approve/reject actions */
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
            border: 1px solid rgba(255,255,255,.15);
            padding: 20px 18px;
            text-align: center;
        }
        .ui-loading-spinner {
            width: 44px;
            height: 44px;
            margin: 0 auto 10px;
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,.25);
            border-top-color: #FDB913;
            animation: uiSpin .8s linear infinite;
        }
        .ui-loading-title { font-weight: 800; font-size: .98rem; letter-spacing: .01em; }
        .ui-loading-sub { margin-top: 4px; opacity: .85; font-size: .8rem; }
        @keyframes uiSpin { to { transform: rotate(360deg); } }
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
                <img src="{{ asset('images/mswd-logo.png') }}" alt="MSWD" style="width:34px;height:34px;object-fit:contain;"> MSWDO
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.requirements') }}">Applications</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">Users Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.data.dashboard') }}">Data Management</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.detailed-analysis') }}">Analysis</a></li>
                </ul>
                <div class="d-flex">
                    @auth
                    <div class="user-info gap-3 d-flex align-items-center">
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

    <div class="container mt-4 pb-5">

        @include('components.admin-notification')

        {{-- Hero --}}
        <div class="page-hero">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1> {{ $application->full_name }}</h1>
                    <p>{{ str_replace('_', ' ', $application->program_type) }} &mdash; Reviewing submitted documents</p>
                </div>
                <a href="{{ route('admin.requirements') }}" class="btn btn-light btn-sm fw-bold px-4" style="border-radius:30px;">
                    Back to List
                </a>
            </div>
        </div>

        {{-- Applicant Info --}}
        <div class="info-card mb-4">
            <h5>Applicant Information</h5>
            <div class="row g-3">
                <div class="col-md-3">
                    <div style="font-size:.75rem;color:#94a3b8;font-weight:700;text-transform:uppercase;margin-bottom:3px;">Full Name</div>
                    <div style="font-weight:700;color:#1e293b;">{{ $application->full_name }}</div>
                </div>
                <div class="col-md-3">
                    <div style="font-size:.75rem;color:#94a3b8;font-weight:700;text-transform:uppercase;margin-bottom:3px;">Program</div>
                    <div style="font-weight:600;">{{ str_replace('_', ' ', $application->program_type) }}</div>
                </div>
                <div class="col-md-2">
                    <div style="font-size:.75rem;color:#94a3b8;font-weight:700;text-transform:uppercase;margin-bottom:3px;">Barangay</div>
                    <div>{{ $application->barangay ?: '—' }}</div>
                </div>
                <div class="col-md-2">
                    <div style="font-size:.75rem;color:#94a3b8;font-weight:700;text-transform:uppercase;margin-bottom:3px;">Date Filed</div>
                    <div>{{ $application->application_date ? \Carbon\Carbon::parse($application->application_date)->format('M d, Y') : '—' }}</div>
                </div>
                <div class="col-md-2">
                    <div style="font-size:.75rem;color:#94a3b8;font-weight:700;text-transform:uppercase;margin-bottom:3px;">Status</div>
                    @php $st = $application->status ?? 'pending'; @endphp
                    <span class="s-badge s-{{ $st }}">{{ ucfirst($st) }}</span>
                </div>
            </div>
        </div>{{-- end Applicant Info info-card --}}

        {{-- No-Documents Warning Banner --}}
        @if(!$hasDocuments)
        <div style="background:#fff3cd;border:1px solid #ffc107;border-radius:14px;padding:18px 22px;margin-bottom:20px;display:flex;align-items:flex-start;gap:14px;">
            <span style="font-size:1.4rem;">⚠️</span>
            <div>
                <div style="font-weight:800;color:#856404;font-size:.95rem;margin-bottom:4px;">No Documents Submitted Yet</div>
                <div style="font-size:.85rem;color:#856404;line-height:1.6;">This applicant has not uploaded any required documents. Approval and ID actions are <strong>disabled</strong> until at least one document is submitted.</div>
            </div>
        </div>
        @endif

        {{-- ID Status / Mark Ready Section (Solo Parent only) --}}
        @if($application->program_type === 'Solo_Parent')
        <div class="info-card mb-4" style="border-left: 4px solid {{ $allApproved ? '#28a745' : '#fdb913' }};">
            <h5>Solo Parent ID Status</h5>
            @if($application->id_status === 'ready_for_pickup')
                <div style="color:#28a745;font-weight:700;">ID is marked as Ready for Pickup
                    @if($application->id_ready_at)
                        <span style="font-size:.78rem;color:#6c757d;font-weight:400;"> — {{ \Carbon\Carbon::parse($application->id_ready_at)->format('M d, Y h:i A') }}</span>
                    @endif
                </div>
            @elseif($allApproved)
                <div style="margin-bottom:10px;font-size:.88rem;color:#155724;">✔ All documents are approved. You may now mark the Solo Parent ID as ready for pickup.</div>
                <form action="{{ route('admin.applications.mark-id-ready', $application->id) }}" method="POST" class="d-inline" id="markIdReadyForm">
                    @csrf
                    <button type="submit" class="btn btn-success fw-bold" style="border-radius:8px;padding:8px 22px;"
                        onclick="return confirm('Mark this Solo Parent ID as ready for pickup and notify the applicant?')">
                        Mark ID as Ready for Pickup
                    </button>
                </form>
            @else
                <div style="color:#856404;font-size:.88rem;margin-bottom:10px;">
                    @if(!$hasDocuments)
                        No documents submitted. ID cannot be marked ready.
                    @else
                        Not all documents are approved yet. ID can only be marked ready once all documents are approved.
                    @endif
                </div>
                <button class="btn btn-secondary fw-bold" disabled style="border-radius:8px;padding:8px 22px;opacity:.55;cursor:not-allowed;">
                    Mark ID as Ready for Pickup
                </button>
            @endif
        </div>
        @endif

        {{-- Documents - Card Grid Layout --}}
        @if($fileMonitoring)
        <div style="font-size:1rem;font-weight:800;color:var(--primary-blue);margin-bottom:14px;">
            Submitted Documents ({{ $fileMonitoring->fileUploads->count() }})
        </div>

        <div class="req-grid">
            @forelse($fileMonitoring->fileUploads as $file)
            @php 
                $status = $file->status ?? 'pending';
                $ext = strtolower(pathinfo($file->file_path, PATHINFO_EXTENSION));
                $fileUrl = route('admin.serve-file', $file->id);
                $isImage = in_array($ext, ['jpg','jpeg','png','webp','gif']);
            @endphp
            
            <div class="req-card {{ $status }}">
                {{-- Image Preview --}}
                <div class="req-card-image" onclick="openFileModal('{{ $fileUrl }}', '{{ addslashes($file->requirement_name) }}', '{{ $ext }}', {{ $file->id }}, '{{ $status }}')" style="cursor:pointer;">
                    @if($isImage)
                        <img src="{{ $fileUrl }}" 
                             alt="{{ $file->requirement_name }}"
                             onerror="this.parentElement.innerHTML='<div class=\"req-file-icon\"><svg width=\"48\" height=\"48\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><path d=\"M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z\"></path><polyline points=\"13 2 13 9 20 9\"></polyline></svg><div class=\"req-file-ext\">{{ strtoupper($ext) }}</div></div>';">
                    @else
                        <div class="req-file-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path>
                                <polyline points="13 2 13 9 20 9"></polyline>
                            </svg>
                            <div class="req-file-ext">{{ strtoupper($ext) }}</div>
                        </div>
                    @endif
                </div>

                {{-- Card Content --}}
                <div class="req-card-content">
                    {{-- Requirement Title --}}
                    <div class="req-title">{{ $file->requirement_name }}</div>

                    {{-- Date Submitted --}}
                    <div class="req-date">
                        {{ $file->uploaded_at ? \Carbon\Carbon::parse($file->uploaded_at)->format('M d, Y h:i A') : 'N/A' }}
                    </div>

                    {{-- Status Badge --}}
                    <div class="req-status-badge s-{{ $status }}">
                        @if($status === 'approved')
                            Approved
                        @elseif($status === 'rejected')
                            Rejected
                        @else
                            Pending
                        @endif
                    </div>

                    {{-- Admin Remarks --}}
                    @if($file->admin_remarks)
                        <div class="req-remarks">
                            <strong>Admin Note:</strong> {{ $file->admin_remarks }}
                        </div>
                    @endif

                    {{-- Action Buttons --}}
                    <div class="req-actions">
                        @if($status === 'pending')
                            {{-- Approve Button --}}
                            <form action="{{ route('admin.update-file-status', $file->id) }}" method="POST" style="flex:1;" class="js-loading-submit" data-loading-title="Approving Requirement" data-loading-sub="Updating document status and notifying the applicant...">
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="req-btn req-btn-approve">
                                    Approve
                                </button>
                            </form>

                            {{-- Decline Button --}}
                            <button type="button" 
                                    class="req-btn req-btn-decline"
                                    data-bs-toggle="modal"
                                    data-bs-target="#declineModal"
                                    data-file-id="{{ $file->id }}"
                                    data-file-name="{{ $file->requirement_name }}">
                                Decline
                            </button>
                        @elseif($status === 'approved')
                            <div class="req-status-text req-text-approved">✔ Already Approved</div>
                        @elseif($status === 'rejected')
                            <div class="req-status-text req-text-rejected">Waiting for Re-upload</div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            @endforelse
        </div>
        
        @if($fileMonitoring->fileUploads->count() === 0)
            <div style="text-align:center;padding:40px;color:#94a3b8;">
                <p style="font-size:.9rem;">No documents submitted yet for this application.</p>
            </div>
        @endif
        @else
            <div style="text-align:center;padding:60px;color:#94a3b8;">
                <p style="font-size:.9rem;">No documents have been uploaded yet for this application.</p>
            </div>
        @endif

    </div>{{-- end .container --}}

    {{-- ===== FILE VIEWER MODAL ===== --}}
    <div class="modal fade" id="fileViewerModal" tabindex="-1" aria-labelledby="fileViewerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fileViewerModalLabel">Document Viewer</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="file-viewer-container" id="fileViewerContainer">
                        <div class="text-muted">Loading document...</div>
                    </div>
                    <div class="file-info-detail" id="fileInfoDetail">
                        <p><strong>Document Name:</strong> <span id="modalDocName" class="fw-bold" style="color: var(--primary-blue);">—</span></p>
                        <p><strong>File Type:</strong> <span id="modalFileType">—</span></p>
                        <p><strong>File Size:</strong> <span id="modalFileSize">Loading...</span></p>
                        <p><strong>Status:</strong> <span id="modalFileStatus" class="s-badge">—</span></p>
                    </div>
                </div>
                <div class="modal-footer" style="justify-content:space-between;">
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <a href="#" id="downloadFileBtn" class="btn btn-primary" download>Download File</a>
                    </div>
                    <div id="modalActionButtons" style="display:none;">
                        {{-- Approve form --}}
                        <form id="modalApproveForm" method="POST" class="d-inline js-loading-submit" data-loading-title="Approving Requirement" data-loading-sub="Updating document status and notifying the applicant..." style="display:none;">
                            @csrf
                            <input type="hidden" name="status" value="approved">
                            <input type="hidden" name="admin_remarks" value="">
                            <button type="submit" class="btn-action btn-approve">Approve</button>
                        </form>
                        {{-- Decline button --}}
                        <button type="button" id="modalDeclineBtn" class="btn-action btn-decline" style="display:none;">
                            Decline
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== DECLINE MODAL ===== --}}
    <div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:16px;border:none;overflow:hidden;">
                <div class="modal-header" style="background:var(--primary-gradient);color:white;border:none;padding:18px 24px;">
                    <h5 class="modal-title" id="declineModalLabel" style="font-weight:800;">Decline Document</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="declineForm" action="" method="POST" data-loading-title="Declining Requirement" data-loading-sub="Saving remarks and notifying the applicant...">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <div class="modal-body" style="padding:24px;">
                        <div style="margin-bottom:16px;">
                            <div style="font-size:.8rem;color:#64748b;margin-bottom:6px;">Document being declined:</div>
                            <div style="font-weight:700;color:#1e293b;" id="declineDocName">—</div>
                        </div>
                        <label class="form-label" style="font-size:0.85rem;font-weight:700;color:#1e293b;">
                            Rejection Reason <span style="color:#dc3545;">*</span>
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
                        <div id="remarksError" style="color:#dc3545;font-size:0.8rem;margin-top:6px;display:none;">
                            Please select a rejection reason.
                        </div>
                        <div style="font-size:.72rem;color:#94a3b8;margin-top:10px;background:#f8f9fa;padding:10px 12px;border-radius:8px;border-left:3px solid #6c757d;">
                            The applicant will see this message and can re-upload the corrected document.
                        </div>
                    </div>
                    <div class="modal-footer" style="border:none;padding:16px 24px 20px;gap:8px;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:8px;font-weight:700;font-size:0.85rem;padding:8px 20px;">
                            Cancel
                        </button>
                        <button type="submit" id="confirmDeclineBtn" class="btn"
                            style="background:linear-gradient(135deg,#dc3545,#c82333);color:white;border-radius:8px;font-weight:700;font-size:0.85rem;border:none;padding:8px 22px;">
                            Confirm Decline
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let fileViewerModal;
        let currentFileId = null;
        let currentFileName = null;
        let currentFileStatus = null;
        
        document.addEventListener('DOMContentLoaded', function() {
            fileViewerModal = new bootstrap.Modal(document.getElementById('fileViewerModal'));
            document.querySelectorAll('.js-loading-submit').forEach(form => {
                form.addEventListener('submit', function () {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.style.opacity = '.65';
                        submitBtn.style.cursor = 'not-allowed';
                    }
                    showLoading(
                        this.getAttribute('data-loading-title') || 'Processing Request',
                        this.getAttribute('data-loading-sub') || 'Please wait while we update records.'
                    );
                });
            });
        });

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
        
        function openFileModal(fileUrl, fileName, fileExt, fileId, fileStatus) {
            const container = document.getElementById('fileViewerContainer');
            const docNameSpan = document.getElementById('modalDocName');
            const fileTypeSpan = document.getElementById('modalFileType');
            const fileStatusSpan = document.getElementById('modalFileStatus');
            const downloadBtn = document.getElementById('downloadFileBtn');
            
            // Store current file info
            currentFileId = fileId;
            currentFileName = fileName;
            currentFileStatus = fileStatus;
            
            // Set download link — use a separate download URL (append ?dl=1 so the controller forces download)
            downloadBtn.href = fileUrl + '?dl=1';
            downloadBtn.setAttribute('download', fileName + '.' + fileExt);
            
            // Set document name and status
            docNameSpan.textContent = fileName;
            fileTypeSpan.textContent = fileExt.toUpperCase();
            
            // Set status badge
            fileStatusSpan.className = 's-badge s-' + fileStatus;
            const statusText = fileStatus === 'approved' ? 'Approved' : 
                              (fileStatus === 'rejected' ? 'Rejected' : 
                              (fileStatus === 'in_review' ? 'In Review' : 'Pending'));
            fileStatusSpan.textContent = statusText;
            
            // Show/hide action buttons based on status
            const approveForm = document.getElementById('modalApproveForm');
            const declineBtn = document.getElementById('modalDeclineBtn');
            const actionButtons = document.getElementById('modalActionButtons');
            
            if (fileStatus === 'approved') {
                // Already approved - hide buttons
                actionButtons.style.display = 'none';
            } else if (fileStatus === 'rejected') {
                // Rejected - hide buttons (waiting for re-upload)
                actionButtons.style.display = 'none';
            } else {
                // Pending or in_review - show buttons
                actionButtons.style.display = 'block';
                approveForm.style.display = 'inline';
                declineBtn.style.display = 'inline-block';
                approveForm.action = '/admin/requirements/' + fileId + '/status';
            }
            
            // Display file based on extension
            const ext = fileExt.toLowerCase();
            
            if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(ext)) {
                // Display image
                container.innerHTML = `<img src="${fileUrl}" alt="${fileName}" class="img-fluid rounded" style="max-height: 60vh;">`;
            } else if (ext === 'pdf') {
                // Display PDF using iframe
                container.innerHTML = `<iframe src="${fileUrl}" title="${fileName}" style="width:100%; height:60vh; border:none; border-radius:8px;"></iframe>`;
            } else {
                // For other file types, show message and download option
                container.innerHTML = `
                    <div class="text-center">
                        <div style="font-size: 1rem; margin-bottom: 20px; color:#475569; font-weight:700;">No Preview Available</div>
                        <h6>File cannot be previewed</h6>
                        <p class="text-muted">This file type (${ext.toUpperCase()}) cannot be displayed in the browser.</p>
                        <a href="${fileUrl}" class="btn btn-primary" download>Download File</a>
                    </div>
                `;
            }
            
            // Try to get file size
            fetch(fileUrl, { method: 'HEAD' })
                .then(response => {
                    const size = response.headers.get('Content-Length');
                    const fileSizeSpan = document.getElementById('modalFileSize');
                    if (size) {
                        const fileSizeBytes = parseInt(size);
                        const fileSizeFormatted = formatFileSize(fileSizeBytes);
                        fileSizeSpan.textContent = fileSizeFormatted;
                    } else {
                        fileSizeSpan.textContent = 'Unknown';
                    }
                })
                .catch(() => {
                    document.getElementById('modalFileSize').textContent = 'Unknown';
                });
            
            // Show modal
            fileViewerModal.show();
        }
        
        // Helper function to format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
        
        // Handle decline button click in modal
        document.getElementById('modalDeclineBtn').addEventListener('click', function() {
            // Close file viewer modal
            fileViewerModal.hide();
            
            // Open decline modal with current file info
            setTimeout(() => {
                const declineModalEl = document.getElementById('declineModal');
                const declineModal = new bootstrap.Modal(declineModalEl);
                
                document.getElementById('declineDocName').textContent = currentFileName;
                document.getElementById('declineForm').action = '/admin/requirements/' + currentFileId + '/status';
                document.getElementById('rejectionReason').value = '';
                document.getElementById('declineRemarks').value = '';
                document.getElementById('remarksError').style.display = 'none';
                
                declineModal.show();
            }, 300);
        });
        
        // Populate the decline modal with the correct file data
        const declineModal = document.getElementById('declineModal');
        declineModal.addEventListener('show.bs.modal', function (e) {
            const btn      = e.relatedTarget;
            const fileId   = btn.getAttribute('data-file-id');
            const fileName = btn.getAttribute('data-file-name');

            document.getElementById('declineDocName').textContent = fileName;
            document.getElementById('declineForm').action = '/admin/requirements/' + fileId + '/status';
            document.getElementById('rejectionReason').value = '';
            document.getElementById('declineRemarks').value = '';
            document.getElementById('remarksError').style.display = 'none';
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
                remarksLabel.innerHTML = 'Additional Comments <span style="color:#dc3545;">*</span>';
            } else {
                // Reset
                remarksField.value = '';
                remarksField.placeholder = 'Add any additional details or instructions...';
                remarksLabel.innerHTML = 'Additional Comments <span style="font-size:0.75rem;font-weight:400;color:#64748b;">(Optional)</span>';
            }
        });
        
        // Client-side validation: require rejection reason before submit
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
            
            // For non-Other reasons, use the reason as final remarks (comments are already auto-filled)
            var finalRemarks = comments || reason;
            document.getElementById('declineRemarks').value = finalRemarks;
            const declineBtn = document.getElementById('confirmDeclineBtn');
            if (declineBtn) {
                declineBtn.disabled = true;
                declineBtn.style.opacity = '.65';
                declineBtn.style.cursor = 'not-allowed';
            }
            showLoading(
                this.getAttribute('data-loading-title') || 'Declining Requirement',
                this.getAttribute('data-loading-sub') || 'Saving remarks and notifying the applicant...'
            );
        });
    </script>
</body>
</html>