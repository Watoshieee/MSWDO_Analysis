<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Requirements – MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --secondary-yellow: #FDB913;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --bg-light: #F8FAFC; --bg-white: #FFFFFF; --bg-soft-blue: #F0F5FF;
            --border-light: #E2E8F0; --text-dark: #1E293B;
            --secondary-yellow-light: #FFF3D6;
        }
        body { background: var(--bg-light); font-family: 'Inter', sans-serif; color: var(--text-dark); display:flex; flex-direction:column; min-height:100vh; }
        a { text-decoration: none; }

        /* ── NAVBAR ── */
        .navbar { background: var(--primary-gradient) !important; box-shadow: 0 4px 24px rgba(44,62,143,0.18); padding: 14px 0; }
        .navbar-brand { font-weight: 800; font-size: 1.55rem; color: white !important; display:flex; align-items:center; gap:10px; }
        .nav-link { color: rgba(255,255,255,0.88) !important; font-weight: 600; transition: all 0.25s; border-radius: 8px; padding: 10px 18px !important; font-size: 0.95rem; }
        .nav-link:hover { background: rgba(255,255,255,0.15); color: white !important; }
        .nav-link.active { background: var(--secondary-yellow); color: var(--primary-blue) !important; font-weight: 700; }
        .user-info { color:white; display:flex; align-items:center; gap:12px; background:rgba(255,255,255,0.1); padding:9px 22px; border-radius:40px; font-size:0.92rem; font-weight:600; }
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
            padding: 14px 16px; border-radius: 12px;
            background: var(--bg-light); border-left: 4px solid var(--border-light);
            margin-bottom: 10px;
        }
        .req-item.approved   { border-left-color: #28a745; background: #f0fff8; }
        .req-item.rejected   { border-left-color: #dc3545; background: #fff5f5; }
        .req-item.in_review  { border-left-color: #17a2b8; background: #f0faff; }
        .req-item.pending    { border-left-color: var(--secondary-yellow); }
        .req-name { font-weight: 600; font-size: 0.9rem; color: var(--text-dark); }
        .admin-remark { font-size: 0.82rem; color: #dc3545; margin-top: 6px; padding: 6px 10px; background: #fff5f5; border-radius: 8px; }

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

        /* Alerts */
        .alert { border-radius: 12px; border: none; font-size: 0.9rem; }
        .alert-success-c { background: #d4edda; border-left: 5px solid #28a745; color: #155724; }
        .alert-warning-c { background: var(--secondary-yellow-light); border-left: 5px solid var(--secondary-yellow); color: #856404; }

        /* Empty state */
        .empty-state { text-align:center; padding: 50px 0; }
        .empty-num { font-size: 4rem; font-weight: 800; color: #E2E8F0; line-height:1; }
        .empty-state p { color: #94a3b8; margin-bottom: 18px; }

        /* Reupload */
        .reupload-form { background: #FFF3D6; border-radius: 10px; padding: 14px 16px; margin-top: 12px; }

        /* Footer */
        .footer-strip { background: var(--primary-gradient); color: white; text-align: center; padding: 18px; font-size: 0.85rem; margin-top: 40px; }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/user/dashboard">
                <img src="/images/mswd-logo.png" alt="MSWD" style="width:34px;height:34px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/user/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/user/programs">Programs</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('user.my-requirements') }}">My Requirements</a></li>
                    <li class="nav-item"><a class="nav-link" href="/user/announcements">Announcements</a></li>
                    <li class="nav-item"><a class="nav-link" href="/analysis">Public Analysis</a></li>
                </ul>
                <div class="d-flex">
                    <div class="user-info">
                        <span>{{ Auth::user()->full_name }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="logout-btn">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

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

        @if(session('success'))
            <div class="alert alert-success-c alert-dismissible fade show mb-3">
                {{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3">
                {{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif



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

                <div class="application-card">
                    <!-- Card header -->
                    <div class="app-card-header">
                        <div class="d-flex align-items-center gap-3">
                            <span class="app-type-tag">{{ str_replace('_', ' ', $app->program_type) }}</span>
                            <span class="app-date">Applied: {{ $app->application_date->format('M d, Y') }}</span>
                        </div>
                        <span class="status-badge status-{{ $overallStatus }}">
                            {{ ucfirst(str_replace('_', ' ', $overallStatus)) }}
                        </span>
                    </div>

                    <div class="app-card-body">
                        <!-- Stats row -->
                        <div class="row g-3 mb-4">
                            <div class="col-3"><div class="stat-mini"><div class="val">{{ $totalReq }}</div><div class="lbl">Total</div></div></div>
                            <div class="col-3"><div class="stat-mini"><div class="val">{{ $uploaded }}</div><div class="lbl">Uploaded</div></div></div>
                            <div class="col-3"><div class="stat-mini"><div class="val" style="color:#28a745;">{{ $approved }}</div><div class="lbl">Approved</div></div></div>
                            <div class="col-3"><div class="stat-mini"><div class="val" style="color:#dc3545;">{{ $rejected }}</div><div class="lbl">Rejected</div></div></div>
                        </div>

                        <!-- Progress bar -->
                        <div class="progress-track">
                            <div class="progress-fill" style="width: {{ $percentComplete }}%"></div>
                        </div>
                        <p class="text-muted" style="font-size:0.78rem; margin-bottom:20px;">
                            Overall Progress — <strong>{{ round($percentComplete) }}% complete</strong>
                        </p>

                        <h6 style="font-weight:700;color:var(--primary-blue);margin-bottom:14px;font-size:0.88rem;text-transform:uppercase;letter-spacing:0.06em;">Submitted Documents</h6>

                        @foreach($data['fileUploads'] as $file)
                            @php $status = $file->status; $hasFile = $file->file_path; @endphp
                            <div class="req-item {{ $status }}">
                                <div class="row align-items-center g-2">
                                    <div class="col-md-5">
                                        <div class="req-name">{{ $file->requirement_name }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="status-badge status-{{ $status }}">{{ ucfirst($status) }}</span>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        @if($hasFile)
                                            @php $ext = pathinfo($file->file_path, PATHINFO_EXTENSION); @endphp
                                            @if(in_array($ext, ['jpg','jpeg','png']))
                                                <img src="{{ asset('storage/' . $file->file_path) }}" class="file-preview" onclick="openFileModal('{{ asset('storage/' . $file->file_path) }}', '{{ $file->requirement_name }}', '{{ $ext }}')">
                                            @else
                                                <span style="font-size:1.6rem;color:#dc3545;cursor:pointer;" onclick="openFileModal('{{ asset('storage/' . $file->file_path) }}', '{{ $file->requirement_name }}', '{{ $ext }}')">📄 PDF</span>
                                            @endif
                                            <button onclick="openFileModal('{{ asset('storage/' . $file->file_path) }}', '{{ $file->requirement_name }}', '{{ $ext }}')" class="btn-view ms-2">View</button>
                                        @else
                                            <span style="font-size:0.82rem;color:#94a3b8;">Not uploaded</span>
                                        @endif
                                    </div>
                                </div>

                                @if($file->admin_remarks)
                                    <div class="admin-remark">
                                        <strong>Remark:</strong> {{ $file->admin_remarks }}
                                    </div>
                                @endif

                                @if($status == 'rejected')
                                    <div class="reupload-form">
                                        <p style="font-size:0.83rem;font-weight:600;margin-bottom:10px;color:#856404;">Re-upload Document</p>
                                        <form action="{{ route('user.resubmit-requirement', $file->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf @method('PUT')
                                            <div class="row g-2 align-items-center">
                                                <div class="col-md-8">
                                                    <input type="file" name="file" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf" required>
                                                    <small class="text-muted">Max 5MB. JPG, PNG, PDF</small>
                                                </div>
                                                <div class="col-md-4">
                                                    <button type="submit" class="btn btn-sm btn-warning w-100" style="font-weight:600;">Re-upload</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @endforeach
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

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global variable for modal instance
        let fileViewerModal;
        
        // Initialize modal when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            fileViewerModal = new bootstrap.Modal(document.getElementById('fileViewerModal'));
        });
        
        // Function to open modal and display file
        function openFileModal(fileUrl, fileName, fileExt) {
            const container = document.getElementById('fileViewerContainer');
            const fileInfo = document.getElementById('fileInfo');
            const downloadBtn = document.getElementById('downloadFileBtn');
            
            // Set download link
            downloadBtn.href = fileUrl;
            downloadBtn.setAttribute('download', fileName + '.' + fileExt);
            
            // Display file based on extension
            const ext = fileExt.toLowerCase();
            
            if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(ext)) {
                // Display image
                container.innerHTML = `<img src="${fileUrl}" alt="${fileName}" class="img-fluid rounded">`;
            } else if (ext === 'pdf') {
                // Display PDF using iframe
                container.innerHTML = `<iframe src="${fileUrl}" title="${fileName}"></iframe>`;
            } else {
                // For other file types, show message and download option
                container.innerHTML = `
                    <div class="text-center">
                        <div style="font-size: 4rem; margin-bottom: 20px;">📄</div>
                        <h6>File cannot be previewed</h6>
                        <p class="text-muted">This file type (${ext.toUpperCase()}) cannot be displayed in the browser.</p>
                        <a href="${fileUrl}" class="btn btn-primary" download>Download File</a>
                    </div>
                `;
            }
            
            // Update file info
            fileInfo.innerHTML = `
                <p><strong>Document Name:</strong> <span class="file-name">${escapeHtml(fileName)}</span></p>
                <p><strong>File Type:</strong> ${ext.toUpperCase()}</p>
                <p><strong>File Size:</strong> <span id="fileSize">Loading...</span></p>
            `;
            
            // Try to get file size
            fetch(fileUrl, { method: 'HEAD' })
                .then(response => {
                    const size = response.headers.get('Content-Length');
                    if (size) {
                        const fileSizeBytes = parseInt(size);
                        const fileSizeFormatted = formatFileSize(fileSizeBytes);
                        document.getElementById('fileSize').textContent = fileSizeFormatted;
                    } else {
                        document.getElementById('fileSize').textContent = 'Unknown';
                    }
                })
                .catch(() => {
                    document.getElementById('fileSize').textContent = 'Unknown';
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
        
        // Helper function to escape HTML to prevent XSS
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>