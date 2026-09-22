<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Requirements – MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; }

        :root {
            --primary-blue: {{ $primaryColor ?? '#2C3E8F' }};
            --secondary-yellow: {{ $secondaryColor ?? '#FDB913' }};
            --primary-gradient: linear-gradient(135deg, {{ $primaryColor ?? '#2C3E8F' }} 0%, #1A2A5C 100%);
            --bg-light: #F8FAFC; --bg-white: #FFFFFF; --bg-soft-blue: #F0F5FF;
            --border-light: #E2E8F0; --text-dark: #1E293B;
            --secondary-yellow-light: #FFF3D6;
        }
        body { background: var(--bg-light); font-family: 'Inter', sans-serif; color: var(--text-dark); display:flex; flex-direction:column; min-height:100vh; }
        a { text-decoration: none; }

        /* ── NAVBAR ── */
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,0.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.55rem; color: white !important; display:flex; align-items:center; gap:10px; }
        .navbar-toggler { order: -1; }
        .navbar-brand { order: 0; margin-left: auto !important; margin-right: 0 !important; }
        @media (min-width: 992px) {
            .navbar-toggler { order: 0; }
            .navbar-brand { order: 0; margin-left: 0 !important; margin-right: auto !important; }
        }
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; transition: all 0.25s; border-radius: 8px; padding: 8px 14px !important; font-size: 0.88rem; white-space: nowrap; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .navbar-nav { flex-wrap: nowrap; }
        .user-info { color:white; display:flex; align-items:center; gap:12px; background:rgba(255,255,255,0.1); padding:9px 22px; border-radius:40px; font-size:0.92rem; font-weight:600; white-space: nowrap; }
        .logout-btn { background:transparent; border:2px solid rgba(255,255,255,0.8); color:white; border-radius:30px; padding:6px 18px; font-weight:700; transition:all 0.3s; font-size:0.88rem; cursor:pointer; }
        .logout-btn:hover { background:var(--secondary-yellow); color:var(--primary-blue); border-color:var(--secondary-yellow); }

        /* ── HERO BANNER ── */
        .hero-banner { background: var(--primary-gradient); color: white; padding: 54px 0 46px; position: relative; overflow: hidden; }
        .hero-banner::before { content:''; position:absolute; top:-80px; right:-80px; width:320px; height:320px; border-radius:50%; background:rgba(253,185,19,0.10); }
        .hero-banner::after  { content:''; position:absolute; bottom:-60px; left:-40px; width:220px; height:220px; border-radius:50%; background:rgba(255,255,255,0.05); }
        .hero-inner { position:relative; z-index:2; }
        .hero-badge { display:inline-block; background:rgba(253,185,19,0.18); color:var(--secondary-yellow); border:1px solid rgba(253,185,19,0.35); border-radius:30px; padding:5px 18px; font-size:0.75rem; font-weight:800; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:16px; }
        .hero-banner h1 { font-size:2.4rem; font-weight:900; margin-bottom:8px; line-height:1.15; }
        .hero-divider { width:50px; height:4px; background:var(--secondary-yellow); border-radius:2px; margin:14px 0; }
        .hero-banner p { opacity:0.84; font-size:0.97rem; margin:0; max-width:600px; line-height:1.7; }

        /* ── MAIN CONTENT ── */
        .main-content { flex:1; }

        /* ── APPLICATION CARD ── */
        .application-card {
            background: var(--bg-white); border-radius: 18px;
            border: 1px solid var(--border-light); margin-bottom: 24px;
            overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.04);
        }
        .app-card-header {
            padding: 18px 24px;
            display: flex; justify-content: space-between; align-items: center;
            border-bottom: 1px solid var(--border-light);
            background: var(--bg-soft-blue);
        }
        .app-type-tag {
            font-size: 0.72rem; font-weight: 700; letter-spacing: 0.08em;
            text-transform: uppercase; background: var(--primary-blue);
            color: white; border-radius: 20px; padding: 3px 12px;
        }
        .app-date { font-size: 0.82rem; color: #64748b; }
        .app-card-body { padding: 22px 24px; }

        /* Status badge */
        .status-badge { padding: 5px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; display: inline-block; }
        .status-approved  { background: #d4edda; color: #155724; }
        .status-rejected  { background: #f8d7da; color: #721c24; }
        .status-pending   { background: var(--secondary-yellow-light); color: #856404; }
        .status-in_review { background: #d1ecf1; color: #0c5460; }

        /* Progress */
        .progress-track { height: 8px; border-radius: 4px; background: #E2E8F0; overflow: hidden; margin-bottom: 6px; }
        .progress-fill  { height: 100%; background: var(--primary-gradient); border-radius: 4px; transition: width 0.4s ease; }

        /* Stat mini-row */
        .stat-mini { text-align: center; }
        .stat-mini .val { font-size: 1.4rem; font-weight: 800; color: var(--primary-blue); line-height:1; }
        .stat-mini .lbl { font-size: 0.72rem; color: #94a3b8; text-transform: uppercase; letter-spacing:0.06em; margin-top: 2px; }

        /* Requirement items */
        .req-item {
            padding: 10px 14px; border-radius: 10px;
            background: var(--bg-light); border-left: 4px solid var(--border-light);
            margin-bottom: 8px;
        }
        .req-item.approved   { border-left-color: #28a745; background: #f0fff8; }
        .req-item.rejected   { border-left-color: #dc3545; background: #fff5f5; }
        .req-item.in_review  { border-left-color: #17a2b8; background: #f0faff; }
        .req-item.pending    { border-left-color: var(--secondary-yellow); }
        .req-name { font-weight: 600; font-size: 0.88rem; color: var(--text-dark); line-height: 1.3; }
        .admin-remark { font-size: 0.78rem; color: #dc3545; margin-top: 5px; padding: 5px 8px; background: #fff5f5; border-radius: 6px; }

        /* File preview */
        .file-preview { width: 48px; height: 48px; object-fit: cover; border-radius: 8px; cursor: pointer; }
        .btn-view { background: var(--primary-blue); color: white; border: none; padding: 5px 14px; border-radius: 8px; font-size: 0.8rem; font-weight: 600; transition: all 0.25s; cursor: pointer; }
        .btn-view:hover { background: #1A2A5C; color: white; }

        /* Modal styles */
        .modal-content { border-radius: 20px; overflow: hidden; border: none; }
        .modal-header { background: var(--primary-gradient); color: white; border: none; padding: 20px 24px; }
        .modal-header .btn-close { background-color: white; opacity: 0.8; }
        .modal-title { font-weight: 800; font-size: 1.2rem; }
        .modal-body { padding: 24px; }
        .file-view-container {
            text-align: center;
            background: var(--bg-light);
            border-radius: 12px;
            padding: 24px;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .file-view-container img {
            max-width: 100%;
            max-height: 60vh;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }
        .file-view-container iframe {
            width: 100%;
            height: 60vh;
            border: none;
            border-radius: 8px;
        }
        .file-info {
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid var(--border-light);
        }
        .file-info p { margin: 5px 0; }
        .file-name { font-weight: 700; color: var(--primary-blue); word-break: break-all; }
        .modal-footer { background: var(--bg-light); border: none; padding: 16px 24px; }

        /* Empty state */
        .empty-state { text-align:center; padding: 50px 0; }
        .empty-num { font-size: 4rem; font-weight: 800; color: #E2E8F0; line-height:1; }
        .empty-state p { color: #94a3b8; margin-bottom: 18px; }

        /* Reupload */
        .reupload-form { background: #FFF3D6; border-radius: 8px; padding: 10px 12px; margin-top: 8px; }

        /* Footer */
        .footer-strip { background: var(--primary-gradient); color: white; text-align: center; padding: 18px; font-size: 0.85rem; margin-top: auto; }
        
        /* Scroll highlight animation */
        @keyframes highlightPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(253, 185, 19, 0); }
            50% { box-shadow: 0 0 0 8px rgba(253, 185, 19, 0.4); }
        }
        .scroll-highlight {
            animation: highlightPulse 1.5s ease-in-out 2;
            border: 2px solid var(--secondary-yellow) !important;
            background: var(--secondary-yellow-light) !important;
        }

        /* Loading overlay */
        .ui-loading-backdrop {
            position: fixed; inset: 0;
            background: rgba(15,23,42,0.55);
            backdrop-filter: blur(1.5px);
            z-index: 12050; display: none;
            align-items: center; justify-content: center;
        }
        .ui-loading-box {
            width: 100%; max-width: 340px; border-radius: 16px;
            background: linear-gradient(135deg,#2C3E8F,#1A2A5C);
            color: #fff; box-shadow: 0 16px 44px rgba(15,23,42,.35);
            border: 1px solid rgba(255,255,255,.15);
            padding: 20px 18px; text-align: center;
        }
        .ui-loading-spinner {
            width: 44px; height: 44px; margin: 0 auto 10px;
            border-radius: 50%; border: 3px solid rgba(255,255,255,.25);
            border-top-color: #FDB913; animation: uiSpin .8s linear infinite;
        }
        @keyframes uiSpin { to { transform: rotate(360deg); } }
        .ui-loading-title { font-weight: 800; font-size: .98rem; }
        .ui-loading-sub   { margin-top: 4px; opacity: .85; font-size: .8rem; }

        /* Toast notification */
        @keyframes slideInRight  { from { opacity:0; transform:translateX(60px); } to { opacity:1; transform:translateX(0); } }
        @keyframes slideOutRight { from { opacity:1; transform:translateX(0); } to { opacity:0; transform:translateX(60px); } }
        @keyframes flashTimerShrink { from { width:100%; } to { width:0%; } }
        #reqToast {
            position: fixed; top: 84px; right: 18px; z-index: 13000;
            max-width: 380px; background: linear-gradient(135deg,#2C3E8F,#1A2A5C);
            color: white; border: 1px solid rgba(255,255,255,.18);
            border-radius: 12px; padding: 12px 16px;
            box-shadow: 0 10px 28px rgba(26,42,92,.35);
            font-size: .88rem; font-weight: 600;
            display: none; align-items: center; gap: 10px; overflow: hidden;
        }
        #reqToast.show { display: flex; animation: slideInRight .35s cubic-bezier(.68,-.55,.265,1.55) forwards; }
        #reqToast.hide { animation: slideOutRight .3s ease forwards; }
        #reqToastTimer {
            position: absolute; bottom: 0; left: 0; height: 3px;
            background: rgba(253,185,19,.9); width: 100%;
            transform-origin: left; border-radius: 0 0 12px 12px;
        }

        /* Scrollbar — same as Programs (/user/programs) */
        html { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    
    
        html { background: #1A2A5C; }
        body { padding-bottom: 0 !important; }
</style>
</head>
<body>

    <!-- NAVBAR -->
    @include('components.user-navbar', ['active' => 'my-requirements'])

    <!-- ===== HERO BANNER ===== -->
    <section class="hero-banner">
        <div class="container">
            <div class="hero-inner">
                <div class="hero-badge">Member Portal</div>
                <h1>My Requirements</h1>
                <div class="hero-divider"></div>
                <p>Track the status of your submitted documents for each MSWDO program application.</p>
            </div>
        </div>
    </section>

    <div class="main-content">
    <div class="container mt-4">

        @include('components.admin-notification')


        @if(count($requirementsData) > 0)
            @foreach($requirementsData as $data)
                @php
                    $app            = $data['application'];
                    $overallStatus  = $data['overallStatus'];
                    $totalReq       = $data['totalRequirements'];
                    $uploaded       = $data['uploadedCount'];
                    $approved       = $data['approvedCount'];
                    $rejected       = $data['rejectedCount'];
                    $percentComplete = $totalReq > 0 ? ($approved / $totalReq) * 100 : 0;
                @endphp

                <div class="application-card" id="app-{{ $app->id }}" data-scroll-target>
                    <!-- Card header -->
                    <div class="app-card-header">
                        <div class="d-flex align-items-center gap-3">
                            <span class="app-type-tag">{{ str_replace('_', ' ', $app->program_type) }}</span>
                            <span class="app-date">Applied: {{ $app->application_date->format('M d, Y') }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="status-badge status-{{ $overallStatus }}">
                                {{ ucfirst(str_replace('_', ' ', $overallStatus)) }}
                            </span>
                            @if($overallStatus === 'rejected' && $app->admin_remarks)
                                <button type="button" class="btn btn-sm" 
                                    style="background:#dc3545;color:white;border:none;border-radius:8px;padding:5px 14px;font-size:0.8rem;font-weight:600;"
                                    onclick="showRemarksModal('{{ addslashes($app->admin_remarks) }}', '{{ str_replace('_', ' ', $app->program_type) }}')"
                                    title="View rejection reason">
                                    <span style="font-size:0.9rem;">💬</span> View Remarks
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="app-card-body">
                        <!-- Stats row -->
                        <div class="row g-3 mb-4">
                            <div class="col-3"><div class="stat-mini"><div class="val">{{ $totalReq }}</div><div class="lbl">Total</div></div></div>
                            <div class="col-3"><div class="stat-mini"><div class="val">{{ $uploaded }}</div><div class="lbl">Uploaded</div></div></div>
                            <div class="col-3"><div class="stat-mini"><div class="val">{{ $approved }}</div><div class="lbl">Approved</div></div></div>
                            <div class="col-3"><div class="stat-mini"><div class="val">{{ $rejected }}</div><div class="lbl">Rejected</div></div></div>
                        </div>

                        <!-- Progress bar -->
                        <div class="progress-track">
                            <div class="progress-fill" style="width: {{ $percentComplete }}%"></div>
                        </div>
                        <p class="text-muted" style="font-size:0.78rem; margin-bottom:20px;">
                            Overall Progress — <strong>{{ round($percentComplete) }}% complete</strong>
                        </p>

                        <h6 style="font-weight:700;color:var(--primary-blue);margin-bottom:14px;font-size:0.88rem;text-transform:uppercase;letter-spacing:0.06em;">Submitted Documents</h6>

                        <div class="row g-2">
                        @foreach($data['fileUploads'] as $file)
                            @php $status = $file->status; $hasFile = $file->file_path; @endphp
                            <div class="col-md-6">
                                <div class="req-item {{ $status }}" id="file-{{ $file->id }}" data-scroll-target>
                                    {{-- Top row: name + status badge + view button --}}
                                    <div class="d-flex align-items-start gap-2">
                                        <div style="flex:1;min-width:0;">
                                            <div class="req-name">{{ $file->requirement_name }}</div>
                                            @if($file->admin_remarks)
                                                <div class="admin-remark"><strong>Remark:</strong> {{ $file->admin_remarks }}</div>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center gap-1 flex-shrink-0">
                                            <span class="status-badge status-{{ $status }}" style="font-size:0.72rem;padding:3px 10px;">{{ ucfirst($status) }}</span>
                                            @if($hasFile)
                                                @php 
                                                    $ext = strtolower(pathinfo($file->file_path, PATHINFO_EXTENSION));
                                                    $fileUrl = route('user.serve-file', $file->id);
                                                @endphp
                                                <button onclick="openFileModal('{{ $fileUrl }}', '{{ addslashes($file->requirement_name) }}', '{{ $ext }}')" 
                                                        class="btn-view" style="padding:3px 10px;font-size:0.75rem;">👁 View</button>
                                            @endif
                                        </div>
                                    </div>

                                    @if($status == 'rejected')
                                        <div class="reupload-form">
                                            <p style="font-size:0.78rem;font-weight:600;margin-bottom:8px;color:#856404;">Re-upload Document</p>
                                            <form class="js-reupload"
                                                  action="{{ route('user.resubmit-requirement', $file->id) }}"
                                                  method="POST"
                                                  enctype="multipart/form-data"
                                                  data-file-id="{{ $file->id }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="d-flex gap-2 align-items-center flex-wrap">
                                                    <div style="flex:1;min-width:0;">
                                                        <input type="file" name="file" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf" required>
                                                        <small class="text-muted" style="font-size:0.7rem;">Images: 5MB, PDF: 25MB</small>
                                                    </div>
                                                    <button type="submit" class="btn btn-sm btn-warning" style="font-weight:600;white-space:nowrap;">Re-upload</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <div class="empty-num">00</div>
                <h5 style="font-weight:700;color:var(--primary-blue);margin:12px 0 8px;">No requirements submitted yet</h5>
                <p>Apply for a program first to view your document checklist here.</p>
                <a href="{{ route('user.programs') }}" style="background:var(--primary-gradient);color:white;border:none;border-radius:10px;padding:10px 24px;font-weight:600;font-size:0.9rem;">Browse Programs &rarr;</a>
            </div>
        @endif
        @include('components.user-notification-modal')

    </div>
    </div>

    <!-- ===== MODAL FOR FILE VIEWER ===== -->
    <div class="modal fade" id="fileViewerModal" tabindex="-1" aria-labelledby="fileViewerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fileViewerModalLabel">Document Viewer</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="file-view-container" id="fileViewerContainer">
                        <!-- Content will be loaded here dynamically -->
                        <div class="text-muted">Loading document...</div>
                    </div>
                    <div class="file-info" id="fileInfo">
                        <!-- File info will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="#" id="downloadFileBtn" class="btn btn-primary" download>Download File</a>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== MODAL FOR REJECTION REMARKS ===== -->
    <div class="modal fade" id="remarksModal" tabindex="-1" aria-labelledby="remarksModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background:linear-gradient(135deg,#dc3545,#a71d2a);color:white;border:none;">
                    <h5 class="modal-title" id="remarksModalLabel" style="font-weight:800;">
                        <span style="font-size:1.2rem;">❌</span> Application Rejected
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:24px;">
                    <div style="background:#fff5f5;border-left:4px solid #dc3545;border-radius:10px;padding:16px 18px;margin-bottom:16px;">
                        <div style="font-weight:700;color:#721c24;font-size:0.9rem;margin-bottom:8px;">
                            <span style="font-size:1rem;">📝</span> Program: <span id="remarksProgram"></span>
                        </div>
                    </div>
                    <div style="background:#f8f9fa;border-radius:10px;padding:16px 18px;">
                        <div style="font-weight:700;color:#495057;font-size:0.85rem;margin-bottom:10px;text-transform:uppercase;letter-spacing:0.05em;">
                            Reason for Rejection:
                        </div>
                        <div id="remarksContent" style="color:#212529;font-size:0.95rem;line-height:1.7;white-space:pre-wrap;">
                            <!-- Remarks will be loaded here -->
                        </div>
                    </div>
                    <div style="background:#fff3cd;border-left:4px solid #ffc107;border-radius:10px;padding:14px 16px;margin-top:16px;">
                        <div style="font-size:0.85rem;color:#856404;line-height:1.6;">
                            <strong>💡 What to do next:</strong><br>
                            Please review the rejection reason above and re-upload the required documents with the necessary corrections.
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="background:#f8f9fa;border:none;padding:16px 24px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:8px;font-weight:600;">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-strip"></div>

    {{-- Loading overlay --}}
    <div id="uiLoadingBackdrop" class="ui-loading-backdrop" aria-hidden="true">
        <div class="ui-loading-box">
            <div class="ui-loading-spinner"></div>
            <div class="ui-loading-title">Uploading Document</div>
            <div class="ui-loading-sub">Please wait while we process your file.</div>
        </div>
    </div>

    {{-- Toast notification --}}
    <div id="reqToast" role="alert">
        <span id="reqToastMsg" style="flex:1;line-height:1.4;"></span>
        <button onclick="dismissReqToast()" style="background:rgba(255,255,255,0.15);border:none;color:white;border-radius:6px;width:24px;height:24px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:1.1rem;line-height:1;padding:0;flex-shrink:0;">&times;</button>
        <div id="reqToastTimer" style="animation:flashTimerShrink 5s linear forwards;"></div>
    </div>
    
    @include('components.chat-modal')
    @include('components.chatbot-widget')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Prevent browser scroll restoration
        if ('scrollRestoration' in history) history.scrollRestoration = 'manual';

        let fileViewerModal;
        let remarksModal;
        let _toastTimer;

        document.addEventListener('DOMContentLoaded', function() {
            const fileViewerEl = document.getElementById('fileViewerModal');
            const remarksEl = document.getElementById('remarksModal');
            if (fileViewerEl) fileViewerModal = new bootstrap.Modal(fileViewerEl);
            if (remarksEl) remarksModal = new bootstrap.Modal(remarksEl);

            const restoreScroll = function() {
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            };
            fileViewerEl?.addEventListener('hidden.bs.modal', restoreScroll);
            remarksEl?.addEventListener('hidden.bs.modal', restoreScroll);
            document.getElementById('announcementsModal')?.addEventListener('hidden.bs.modal', restoreScroll);
            document.getElementById('chatModal')?.addEventListener('hidden.bs.modal', restoreScroll);

            // AJAX re-upload handler
            document.querySelectorAll('.js-reupload').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const scrollBefore = window.scrollY || window.pageYOffset;
                    const fileId = form.dataset.fileId;
                    const fileInput = form.querySelector('input[type="file"]');

                    if (!fileInput || !fileInput.files.length) return;

                    const formData = new FormData(form);
                    const submitBtn = form.querySelector('button[type="submit"]');

                    // Disable button + show loading
                    if (submitBtn) { submitBtn.disabled = true; submitBtn.style.opacity = '.65'; }
                    showReqLoading();

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(function(r) { return r.json(); })
                    .then(function(res) {
                        hideReqLoading();
                        if (!res.success) {
                            showReqToast(res.message || 'Upload failed.', true);
                            if (submitBtn) { submitBtn.disabled = false; submitBtn.style.opacity = ''; }
                            return;
                        }
                        // In-place DOM update
                        const card = document.getElementById('file-' + fileId);
                        if (card) {
                            updateReqItemDOM(card, res);
                        }
                        window.scrollTo(0, scrollBefore);
                        showReqToast('Document re-uploaded successfully! Waiting for admin review.');
                    })
                    .catch(function() {
                        hideReqLoading();
                        if (submitBtn) { submitBtn.disabled = false; submitBtn.style.opacity = ''; }
                        showReqToast('Upload failed. Please try again.', true);
                    });
                });
            });
        });

        function updateReqItemDOM(card, res) {
            // Update border class
            card.classList.remove('approved', 'rejected', 'pending', 'in_review');
            card.classList.add('pending');

            // Update status badge
            const badge = card.querySelector('.status-badge');
            if (badge) {
                badge.className = 'status-badge status-pending';
                badge.style.fontSize = '0.72rem';
                badge.style.padding = '3px 10px';
                badge.textContent = 'Pending';
            }

            // Remove admin remark
            const remark = card.querySelector('.admin-remark');
            if (remark) remark.remove();

            // Replace re-upload form with pending message
            const reuploadDiv = card.querySelector('.reupload-form');
            if (reuploadDiv) reuploadDiv.remove();

            // Update View button href if file URL changed
            const viewBtn = card.querySelector('.btn-view');
            if (viewBtn && res.file_url && res.file_ext) {
                viewBtn.setAttribute('onclick',
                    `openFileModal('${res.file_url}', '${escapeHtml(res.requirement_name)}', '${res.file_ext}')`);
            }
        }

        function showReqLoading() {
            const el = document.getElementById('uiLoadingBackdrop');
            if (el) { el.style.display = 'flex'; el.setAttribute('aria-hidden','false'); }
        }
        function hideReqLoading() {
            const el = document.getElementById('uiLoadingBackdrop');
            if (el) { el.style.display = 'none'; el.setAttribute('aria-hidden','true'); }
        }

        function showReqToast(message, isError) {
            clearTimeout(_toastTimer);
            const toast = document.getElementById('reqToast');
            const msg   = document.getElementById('reqToastMsg');
            const timer = document.getElementById('reqToastTimer');
            if (!toast) return;
            msg.textContent = message;
            toast.classList.remove('hide');
            // Reset timer bar animation
            timer.style.animation = 'none';
            void timer.offsetWidth; // reflow
            timer.style.animation = 'flashTimerShrink 5s linear forwards';
            toast.classList.add('show');
            _toastTimer = setTimeout(dismissReqToast, 5000);
        }
        function dismissReqToast() {
            clearTimeout(_toastTimer);
            const toast = document.getElementById('reqToast');
            if (!toast) return;
            toast.classList.add('hide');
            setTimeout(function() { toast.classList.remove('show','hide'); }, 320);
        }

        function showRemarksModal(remarks, programType) {
            document.getElementById('remarksProgram').textContent = programType;
            document.getElementById('remarksContent').textContent = remarks;
            remarksModal.show();
        }
        
        function openFileModal(fileUrl, fileName, fileExt) {
            const container = document.getElementById('fileViewerContainer');
            const fileInfo = document.getElementById('fileInfo');
            const downloadBtn = document.getElementById('downloadFileBtn');
            
            downloadBtn.href = fileUrl;
            downloadBtn.setAttribute('download', fileName + '.' + fileExt);
            
            const ext = fileExt.toLowerCase();
            
            if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(ext)) {
                container.innerHTML = `<img src="${fileUrl}" alt="${fileName}" class="img-fluid rounded">`;
            } else if (ext === 'pdf') {
                container.innerHTML = `<iframe src="${fileUrl}" title="${fileName}"></iframe>`;
            } else {
                container.innerHTML = `
                    <div class="text-center">
                        <div style="font-size: 4rem; margin-bottom: 20px;">📄</div>
                        <h6>File cannot be previewed</h6>
                        <p class="text-muted">This file type (${ext.toUpperCase()}) cannot be displayed in the browser.</p>
                        <a href="${fileUrl}" class="btn btn-primary" download>Download File</a>
                    </div>
                `;
            }
            
            fileInfo.innerHTML = `
                <p><strong>Document Name:</strong> <span class="file-name">${escapeHtml(fileName)}</span></p>
                <p><strong>File Type:</strong> ${ext.toUpperCase()}</p>
                <p><strong>File Size:</strong> <span id="fileSize">Loading...</span></p>
            `;
            
            fetch(fileUrl, { method: 'HEAD' })
                .then(response => {
                    const size = response.headers.get('Content-Length');
                    if (size) {
                        document.getElementById('fileSize').textContent = formatFileSize(parseInt(size));
                    } else {
                        document.getElementById('fileSize').textContent = 'Unknown';
                    }
                })
                .catch(() => { document.getElementById('fileSize').textContent = 'Unknown'; });
            
            fileViewerModal.show();
        }
        
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        document.getElementById('announcementsModal').addEventListener('show.bs.modal', function () {
            fetch('{{ route('user.mark-notifications-viewed') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      const badge = document.querySelector('.btn[data-bs-target="#announcementsModal"] span');
                      if (badge) badge.style.display = 'none';
                  }
              });
        });
        
        document.addEventListener('DOMContentLoaded', function() {
            const hash = window.location.hash;
            if (hash) {
                const targetElement = document.querySelector(hash);
                if (targetElement) {
                    setTimeout(() => {
                        const offsetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - 100;
                        window.scrollTo({ top: offsetPosition, behavior: 'smooth' });
                        targetElement.classList.add('scroll-highlight');
                        setTimeout(() => targetElement.classList.remove('scroll-highlight'), 3000);
                    }, 300);
                }
            }
        });
    </script>
</body>
</html>