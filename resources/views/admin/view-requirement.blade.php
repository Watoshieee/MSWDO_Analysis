<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Review Requirements – {{ $application->full_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
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
        .nav-link { color: rgba(255,255,255,.88) !important; font-weight:600; border-radius:8px; padding:10px 18px !important; font-size:.95rem; transition:all .25s; }
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

        /* DOCUMENT CARDS */
        .doc-card { background:white; border-radius:14px; border:1px solid var(--border-light); padding:20px 22px; margin-bottom:16px; box-shadow:0 2px 10px rgba(0,0,0,.04); border-left:4px solid #cbd5e1; transition:border-color .2s; }
        .doc-card.approved { border-left-color:#28a745; background:#f6fff6; }
        .doc-card.rejected { border-left-color:#dc3545; background:#fff6f6; }
        .doc-card.pending  { border-left-color:#fdb913; }

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
        .btn-approve { background:#28a745; color:white; border:none; border-radius:8px; padding:8px 20px; font-weight:700; font-size:.83rem; cursor:pointer; transition:all .2s; }
        .btn-approve:hover { background:#218838; box-shadow:0 4px 12px rgba(40,167,69,.35); }
        .btn-decline { background:#dc3545; color:white; border:none; border-radius:8px; padding:8px 20px; font-weight:700; font-size:.83rem; cursor:pointer; transition:all .2s; margin-left:8px; }
        .btn-decline:hover { background:#c82333; box-shadow:0 4px 12px rgba(220,53,69,.35); }
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
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/admin/dashboard">
                <img src="/images/mswd-logo.png" alt="MSWD" style="width:34px;height:34px;object-fit:contain;"> MSWDO
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('admin.requirements') }}">Applications</a></li>
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

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" style="border-radius:12px;">
                ✅ {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3" style="border-radius:12px;">
                ❌ {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Hero --}}
        <div class="page-hero">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1> {{ $application->full_name }}</h1>
                    <p>{{ str_replace('_', ' ', $application->program_type) }} &mdash; Reviewing submitted documents</p>
                </div>
                <a href="{{ route('admin.requirements') }}" class="btn btn-light btn-sm fw-bold px-4" style="border-radius:30px;">
                    ← Back to List
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
        </div>

        {{-- Documents --}}
        @if($fileMonitoring)
        <div style="font-size:1rem;font-weight:800;color:var(--primary-blue);margin-bottom:14px;">
             Submitted Documents ({{ $fileMonitoring->fileUploads->count() }})
        </div>

        @forelse($fileMonitoring->fileUploads as $file)
        @php $status = $file->status ?? 'pending'; @endphp
        <div class="doc-card {{ $status }}">
            <div class="row align-items-center g-3">

                {{-- Document Name + Status --}}
                <div class="col-md-4">
                    <div style="font-weight:700;color:#1e293b;margin-bottom:6px;">{{ $file->requirement_name }}</div>
                    <span class="s-badge s-{{ $status }}">
                        {{ $status === 'approved' ? ' Approved' : ($status === 'rejected' ? ' Rejected' : ($status === 'in_review' ? ' In Review' : ' Pending')) }}
                    </span>
                    @if($file->admin_remarks)
                        <div style="margin-top:8px;padding:8px 12px;background:#fff3cd;border-radius:8px;font-size:.78rem;color:#856404;border-left:3px solid #fdb913;">
                            <strong>Admin Note:</strong> {{ $file->admin_remarks }}
                        </div>
                    @endif
                    @if($file->uploaded_at)
                        <div style="font-size:.7rem;color:#94a3b8;margin-top:6px;">Uploaded {{ \Carbon\Carbon::parse($file->uploaded_at)->format('M d, Y h:i A') }}</div>
                    @endif
                </div>

                {{-- File Preview --}}
                <div class="col-md-4 text-center">
                    @if($file->file_path)
                        @php $ext = strtolower(pathinfo($file->file_path, PATHINFO_EXTENSION)); @endphp
                        @if(in_array($ext, ['jpg','jpeg','png','webp']))
                            <img src="{{ asset('storage/'.$file->file_path) }}"
                                 class="file-thumb"
                                 onclick="openFileModal('{{ asset('storage/'.$file->file_path) }}', '{{ $file->requirement_name }}', '{{ $ext }}')"
                                 title="Click to view full size">
                        @elseif($ext === 'pdf')
                            <div style="font-size:2.8rem;line-height:1;margin-bottom:6px;">📄 PDF</div>
                        @else
                            <div style="font-size:2.8rem;line-height:1;margin-bottom:6px;">📎</div>
                        @endif
                        <div class="mt-2">
                            <button onclick="openFileModal('{{ asset('storage/'.$file->file_path) }}', '{{ $file->requirement_name }}', '{{ $ext }}')"
                                    class="btn-view">
                                👁 View Document
                            </button>
                        </div>
                    @else
                        <div style="color:#94a3b8;font-size:.85rem;"> No file uploaded</div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="col-md-4 text-end">
                    @if($file->file_path)
                        @if($status === 'approved')
                            <div style="color:#28a745;font-weight:700;font-size:.88rem;"> Already Approved</div>
                        @else
                            {{-- Approve form --}}
                            <form action="{{ route('admin.update-file-status', $file->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="status" value="approved">
                                <input type="hidden" name="admin_remarks" value="">
                                <button type="submit" class="btn-approve"> Approve</button>
                            </form>

                            {{-- Decline triggers modal --}}
                            <button type="button" class="btn-decline"
                                data-bs-toggle="modal"
                                data-bs-target="#declineModal"
                                data-file-id="{{ $file->id }}"
                                data-file-name="{{ $file->requirement_name }}">
                                ❌ Decline
                            </button>
                        @endif
                    @else
                        <span style="font-size:.8rem;color:#94a3b8;">No file to review</span>
                    @endif
                </div>
            </div>
        </div>
        @empty
            <div style="text-align:center;padding:40px;color:#94a3b8;">
                <p style="font-size:.9rem;">No documents submitted yet for this application.</p>
            </div>
        @endforelse
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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="#" id="downloadFileBtn" class="btn btn-primary" download>Download File</a>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== DECLINE MODAL ===== --}}
    <div class="modal fade" id="declineModal" tabindex="-1" aria-labelledby="declineModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="declineModalLabel"> Decline Document</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="declineForm" action="" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="rejected">
                    <div class="modal-body">
                        <div style="margin-bottom:16px;">
                            <div style="font-size:.8rem;color:#64748b;margin-bottom:6px;">Document being declined:</div>
                            <div style="font-weight:700;color:#1e293b;" id="declineDocName">—</div>
                        </div>
                        <div>
                            <label for="admin_remarks" style="font-weight:700;font-size:.87rem;margin-bottom:6px;display:block;">
                                Reason for Declining <span style="color:#dc3545;">*</span>
                            </label>
                            <textarea name="admin_remarks" id="admin_remarks" rows="4"
                                class="form-control"
                                placeholder="Enter the reason for declining this document. This will be shown to the applicant..."
                                style="border-radius:10px;font-size:.875rem;"
                                required></textarea>
                            <div style="font-size:.72rem;color:#94a3b8;margin-top:6px;">
                                The applicant will see this message and can re-upload the corrected document.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:8px;">Cancel</button>
                        <button type="submit" class="btn-decline" style="margin-left:0;" id="confirmDeclineBtn">
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
        
        document.addEventListener('DOMContentLoaded', function() {
            fileViewerModal = new bootstrap.Modal(document.getElementById('fileViewerModal'));
        });
        
        function openFileModal(fileUrl, fileName, fileExt) {
            const container = document.getElementById('fileViewerContainer');
            const docNameSpan = document.getElementById('modalDocName');
            const fileTypeSpan = document.getElementById('modalFileType');
            const downloadBtn = document.getElementById('downloadFileBtn');
            
            // Set download link
            downloadBtn.href = fileUrl;
            downloadBtn.setAttribute('download', fileName + '.' + fileExt);
            
            // Set document name
            docNameSpan.textContent = fileName;
            fileTypeSpan.textContent = fileExt.toUpperCase();
            
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
                        <div style="font-size: 4rem; margin-bottom: 20px;">📄</div>
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
        
        // Populate the decline modal with the correct file data
        const declineModal = document.getElementById('declineModal');
        declineModal.addEventListener('show.bs.modal', function (e) {
            const btn      = e.relatedTarget;
            const fileId   = btn.getAttribute('data-file-id');
            const fileName = btn.getAttribute('data-file-name');

            document.getElementById('declineDocName').textContent = fileName;
            document.getElementById('declineForm').action = '/admin/requirements/' + fileId + '/status';
            document.getElementById('admin_remarks').value = '';
        });
    </script>
</body>
</html>