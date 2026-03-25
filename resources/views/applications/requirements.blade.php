<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Upload Requirements – MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --secondary-yellow: #FDB913;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --bg-light: #F8FAFC;
            --bg-white: #FFFFFF;
            --bg-soft-blue: #F0F5FF;
            --border-light: #E2E8F0;
            --text-dark: #1E293B;
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
        .hero-banner { background: var(--primary-gradient); color: white; padding: 44px 0 38px; position: relative; overflow: hidden; }
        .hero-banner::before { content:''; position:absolute; top:-80px; right:-80px; width:320px; height:320px; border-radius:50%; background:rgba(253,185,19,0.10); }
        .hero-banner::after  { content:''; position:absolute; bottom:-60px; left:-40px; width:220px; height:220px; border-radius:50%; background:rgba(255,255,255,0.05); }
        .hero-inner { position:relative; z-index:2; }
        .hero-badge { display:inline-block; background:rgba(253,185,19,0.18); color:var(--secondary-yellow); border:1px solid rgba(253,185,19,0.35); border-radius:30px; padding:4px 16px; font-size:0.72rem; font-weight:800; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:12px; }
        .hero-banner h1 { font-size:2rem; font-weight:900; margin-bottom:4px; line-height:1.15; }
        .hero-divider { width:44px; height:4px; background:var(--secondary-yellow); border-radius:2px; margin:10px 0 10px; }
        .hero-banner p { opacity:0.84; font-size:0.93rem; margin:0; }
        .hero-meta { display:flex; gap:20px; margin-top:16px; flex-wrap:wrap; }
        .hero-meta-item { font-size:0.82rem; font-weight:600; opacity:0.9; display:flex; align-items:center; gap:6px; }
        .hero-meta-item::before { content:''; width:7px; height:7px; border-radius:50%; background:var(--secondary-yellow); display:inline-block; flex-shrink:0; }

        /* ── PROGRESS STEPPER ── */
        .stepper-wrap { background:var(--bg-white); border:1px solid var(--border-light); border-radius:18px; padding:22px 28px; margin-bottom:24px; }
        .stepper { display:flex; align-items:center; }
        .step-item { display:flex; align-items:center; flex:1; position:relative; }
        .step-item:last-child { flex:0; }
        .step-circle {
            width:40px; height:40px; border-radius:50%; flex-shrink:0;
            display:flex; align-items:center; justify-content:center;
            font-weight:800; font-size:0.9rem;
            border:2px solid var(--border-light); background:var(--bg-light); color:#94a3b8;
            position:relative; z-index:1;
        }
        .step-circle.completed { background:#28a745; border-color:#28a745; color:white; }
        .step-circle.active    { background:var(--primary-blue); border-color:var(--primary-blue); color:white; box-shadow:0 0 0 4px rgba(44,62,143,0.15); }
        .step-label { margin-left:10px; }
        .step-label .step-num { font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#94a3b8; }
        .step-label .step-name { font-size:0.85rem; font-weight:700; color:var(--text-dark); }
        .step-label .step-name.active-text { color:var(--primary-blue); }
        .step-label .step-name.done-text   { color:#28a745; }
        .step-connector { flex:1; height:2px; background:var(--border-light); margin:0 14px; }
        .step-connector.done { background:#28a745; }

        /* ── PROGRESS BAR CARD ── */
        .progress-card { background:var(--bg-white); border:1px solid var(--border-light); border-radius:18px; padding:20px 24px; margin-bottom:24px; }
        .progress-stats { display:flex; gap:6px; margin-bottom:14px; }
        .prog-stat { flex:1; border-radius:14px; padding:14px 16px; text-align:center; }
        .prog-stat .stat-num  { font-size:1.6rem; font-weight:900; line-height:1; }
        .prog-stat .stat-lbl  { font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; margin-top:4px; opacity:0.75; }
        .prog-stat.total   { background:var(--bg-soft-blue); color:var(--primary-blue); }
        .prog-stat.uploaded{ background:#d4edda; color:#155724; }
        .prog-stat.approved{ background:#FFF3D6; color:#856404; }
        .bar-wrap { height:10px; background:#e2e8f0; border-radius:10px; overflow:hidden; }
        .bar-fill { height:100%; background: linear-gradient(90deg, var(--primary-blue), var(--secondary-yellow)); border-radius:10px; transition:width 0.6s ease; }
        .bar-label { font-size:0.78rem; color:#64748b; margin-top:6px; font-weight:600; }

        /* ── ALERT ── */
        .alert-styled { border-radius:12px; border:none; font-size:0.89rem; padding:12px 16px; }
        .alert-success-c { background:#d4edda; border-left:5px solid #28a745; color:#155724; }
        .alert-danger-c  { background:#fce8e8; border-left:5px solid #dc3545; color:#721c24; }

        /* ── SECTION HEADER ── */
        .section-header-bar { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
        .section-header-bar h5 { font-size:1.05rem; font-weight:800; color:var(--primary-blue); margin:0; position:relative; padding-bottom:8px; }
        .section-header-bar h5::after { content:''; position:absolute; bottom:0; left:0; width:32px; height:3px; background:var(--secondary-yellow); border-radius:2px; }
        .doc-count-pill { font-size:0.72rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; background:var(--bg-soft-blue); color:var(--primary-blue); border-radius:20px; padding:4px 14px; }

        /* ── REQUIREMENT ROWS ── */
        .req-card {
            background:var(--bg-white); border:1px solid var(--border-light);
            border-radius:14px; margin-bottom:12px; overflow:hidden;
            transition:all 0.25s; position:relative;
        }
        .req-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:5px; background:var(--border-light); }
        .req-card.req-approved::before { background:#28a745; }
        .req-card.req-rejected::before { background:#dc3545; }
        .req-card.req-pending::before  { background:var(--secondary-yellow); }
        .req-card:hover { box-shadow:0 6px 20px rgba(44,62,143,0.08); }
        .req-inner { padding:18px 20px 18px 26px; display:flex; align-items:center; gap:20px; flex-wrap:wrap; }

        /* Left: number + name */
        .req-num { font-size:0.68rem; font-weight:800; letter-spacing:0.08em; text-transform:uppercase;
                   color:var(--primary-blue); background:var(--bg-soft-blue);
                   border-radius:20px; padding:2px 10px; margin-bottom:5px; display:inline-block; }
        .req-num.num-approved { color:#155724; background:#d4edda; }
        .req-num.num-rejected { color:#721c24; background:#fce8e8; }
        .req-num.num-pending  { color:#856404; background:#FFF3D6; }
        .req-name { font-size:0.95rem; font-weight:700; color:var(--text-dark); }
        .req-remarks { font-size:0.78rem; color:#dc3545; margin-top:4px; font-style:italic; }

        /* Status pill */
        .status-pill { font-size:0.68rem; font-weight:700; letter-spacing:0.07em; text-transform:uppercase;
                       border-radius:20px; padding:4px 12px; white-space:nowrap; }
        .pill-approved { background:#d4edda; color:#155724; }
        .pill-rejected { background:#fce8e8; color:#721c24; }
        .pill-pending  { background:#FFF3D6; color:#856404; }
        .pill-empty    { background:var(--bg-light); color:#94a3b8; border:1px solid var(--border-light); }

        /* Upload zone */
        .upload-zone {
            border:2px dashed var(--border-light); border-radius:12px; padding:18px 20px;
            text-align:center; cursor:pointer; transition:all 0.25s; min-width:180px;
        }
        .upload-zone:hover { border-color:var(--primary-blue); background:var(--bg-soft-blue); }
        .upload-zone .uz-label { font-size:0.8rem; font-weight:700; color:var(--primary-blue); margin:6px 0 2px; }
        .upload-zone .uz-sub   { font-size:0.72rem; color:#94a3b8; }
        .upload-icon-box { width:38px; height:38px; border-radius:12px; background:var(--bg-soft-blue);
                           color:var(--primary-blue); display:flex; align-items:center; justify-content:center;
                           font-size:1.1rem; font-weight:900; margin:0 auto 4px; }

        /* File preview */
        .file-thumb { width:70px; height:70px; object-fit:cover; border-radius:10px;
                      border:2px solid var(--border-light); cursor:pointer; }
        .file-pdf-box { width:70px; height:70px; border-radius:10px; background:#fce8e8; border:2px solid #f5c6c6;
                        display:flex; align-items:center; justify-content:center; cursor:pointer;
                        font-size:0.6rem; font-weight:800; color:#dc3545; text-align:center; line-height:1.3; }
        .file-actions { display:flex; flex-direction:column; gap:6px; }
        .btn-view { background:var(--primary-gradient); color:white; border:none; border-radius:8px;
                    padding:6px 16px; font-size:0.78rem; font-weight:700; cursor:pointer; transition:all 0.2s; }
        .btn-view:hover { opacity:0.9; color:white; }
        .btn-remove { background:transparent; border:1.5px solid #dc3545; color:#dc3545; border-radius:8px;
                      padding:5px 16px; font-size:0.78rem; font-weight:700; cursor:pointer; transition:all 0.2s; }
        .btn-remove:hover { background:#fce8e8; }

        /* ── FOOTER ── */
        .main-content { flex:1; }
        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,0.75); text-align: center; padding: 20px 0; font-size: 0.85rem; margin-top: 48px; }
        .footer-strip strong { color:white; }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('user.dashboard') }}">
                <img src="/images/mswd-logo.png" alt="MSWD" style="width:36px;height:36px;object-fit:contain;"> MSWDO
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('user.dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('user.programs') }}">Programs</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('user.my-requirements') }}">My Requirements</a></li>
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

    <!-- HERO BANNER -->
    <section class="hero-banner">
        <div class="container">
            <div class="hero-inner">
                <div class="hero-badge">Document Upload</div>
                <h1>{{ str_replace('_', ' ', $application->program_type) }}</h1>
                <div class="hero-divider"></div>
                <p>Please upload all the required documents for your application.</p>
                <div class="hero-meta">
                    <span class="hero-meta-item">{{ $application->full_name }}</span>
                    <span class="hero-meta-item">Brgy. {{ $application->barangay }}</span>
                    <span class="hero-meta-item">Application #{{ $application->id }}</span>
                </div>
            </div>
        </div>
    </section>

    <div class="main-content">
    <div class="container mt-4">

        <!-- PROGRESS STEPPER -->
        <div class="stepper-wrap">
            <div class="stepper">
                <div class="step-item">
                    <div class="step-circle completed">&#10003;</div>
                    <div class="step-label">
                        <div class="step-num">Step 01</div>
                        <div class="step-name done-text">Application Form</div>
                    </div>
                </div>
                <div class="step-connector done"></div>
                <div class="step-item">
                    <div class="step-circle active">02</div>
                    <div class="step-label">
                        <div class="step-num">Step 02</div>
                        <div class="step-name active-text">Upload Documents</div>
                    </div>
                </div>
                <div class="step-connector"></div>
                <div class="step-item">
                    <div class="step-circle">03</div>
                    <div class="step-label">
                        <div class="step-num">Step 03</div>
                        <div class="step-name">Under Review</div>
                    </div>
                </div>
            </div>
        </div>

        @php
            $totalRequirements    = $requirements->count();
            $uploadedRequirements = $fileUploads->where('file_path', '!=', null)->count();
            $approvedRequirements = $fileUploads->where('status', 'approved')->count();
            $percentComplete      = $totalRequirements > 0 ? ($approvedRequirements / $totalRequirements) * 100 : 0;
        @endphp

        <!-- PROGRESS CARD -->
        <div class="progress-card">
            <div class="progress-stats">
                <div class="prog-stat total">
                    <div class="stat-num">{{ $totalRequirements }}</div>
                    <div class="stat-lbl">Required</div>
                </div>
                <div class="prog-stat uploaded">
                    <div class="stat-num">{{ $uploadedRequirements }}</div>
                    <div class="stat-lbl">Uploaded</div>
                </div>
                <div class="prog-stat approved">
                    <div class="stat-num">{{ $approvedRequirements }}</div>
                    <div class="stat-lbl">Approved</div>
                </div>
            </div>
            <div class="bar-wrap">
                <div class="bar-fill" style="width: {{ $percentComplete }}%"></div>
            </div>
            <div class="bar-label">{{ round($percentComplete) }}% of documents approved</div>
        </div>

        @if(session('success'))
            <div class="alert-styled alert-success-c mb-3">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-styled alert-danger-c mb-3">{{ session('error') }}</div>
        @endif

        <!-- REQUIREMENT LIST -->
        <div class="section-header-bar">
            <h5>Required Documents</h5>
            <span class="doc-count-pill">{{ $totalRequirements }} documents</span>
        </div>

        @foreach($requirements as $req)
            @php
                $fileUpload  = $fileUploads->where('requirement_name', $req->requirement_name)->first();
                $status      = $fileUpload ? $fileUpload->status : 'empty';
                $cardClass   = $status === 'approved' ? 'req-approved' : ($status === 'rejected' ? 'req-rejected' : ($fileUpload ? 'req-pending' : ''));
                $numClass    = $status === 'approved' ? 'num-approved' : ($status === 'rejected' ? 'num-rejected' : ($fileUpload ? 'num-pending' : ''));
                $pillClass   = $status === 'approved' ? 'pill-approved' : ($status === 'rejected' ? 'pill-rejected' : ($fileUpload ? 'pill-pending' : 'pill-empty'));
                $pillLabel   = $status === 'approved' ? 'Approved' : ($status === 'rejected' ? 'Needs Re-upload' : ($fileUpload ? 'Under Review' : 'Not Uploaded'));
                $hasFile     = $fileUpload && $fileUpload->file_path;
                $ext         = $hasFile ? pathinfo($fileUpload->file_path, PATHINFO_EXTENSION) : null;
            @endphp

            <div class="req-card {{ $cardClass }}">
                <div class="req-inner">
                    <!-- Name + status -->
                    <div style="flex:1; min-width:180px;">
                        <span class="req-num {{ $numClass }}">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="req-name">{{ $req->requirement_name }}</div>
                        @if($fileUpload && $fileUpload->remarks)
                            <div class="req-remarks">Note: {{ $fileUpload->remarks }}</div>
                        @endif
                        <span class="status-pill {{ $pillClass }} mt-2 d-inline-block">{{ $pillLabel }}</span>
                    </div>

                    <!-- File or upload zone -->
                    <div>
                        @if($hasFile)
                            <div class="d-flex align-items-center gap-3">
                                @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                                    <img src="{{ asset('storage/' . $fileUpload->file_path) }}"
                                         class="file-thumb"
                                         onclick="window.open('{{ asset('storage/' . $fileUpload->file_path) }}')">
                                @else
                                    <div class="file-pdf-box" onclick="window.open('{{ asset('storage/' . $fileUpload->file_path) }}')">
                                        PDF<br>Doc
                                    </div>
                                @endif
                                <div class="file-actions">
                                    <a href="{{ asset('storage/' . $fileUpload->file_path) }}" target="_blank" class="btn-view">View File</a>
                                    @if($status === 'rejected')
                                        <button type="button" class="btn-remove"
                                            onclick="deleteFile('{{ $req->requirement_name }}')">Remove</button>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="upload-zone" onclick="document.getElementById('file-{{ $loop->index }}').click()">
                                <div class="upload-icon-box">+</div>
                                <div class="uz-label">Upload File</div>
                                <div class="uz-sub">JPG, PNG or PDF &bull; Max 5MB</div>
                            </div>
                            <form action="{{ route('applications.requirement.upload', $application->id) }}"
                                  method="POST" enctype="multipart/form-data"
                                  id="form-{{ $loop->index }}" style="display:none;">
                                @csrf
                                <input type="hidden" name="requirement_name" value="{{ $req->requirement_name }}">
                                <input type="file" name="file" id="file-{{ $loop->index }}"
                                       accept=".jpg,.jpeg,.png,.pdf"
                                       onchange="document.getElementById('form-{{ $loop->index }}').submit()">
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Bottom nav -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <a href="{{ route('user.programs') }}" style="background:transparent; border:2px solid var(--border-light); color:var(--text-dark); border-radius:10px; padding:10px 22px; font-weight:700; font-size:0.88rem; transition:all 0.2s;">
                &#8592; Back to Programs
            </a>
            <a href="{{ route('user.my-requirements') }}" style="background:var(--primary-gradient); color:white; border:none; border-radius:10px; padding:10px 22px; font-weight:700; font-size:0.88rem; transition:all 0.2s;">
                View My Requirements &#8594;
            </a>
        </div>

    </div>
    </div>

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    <script>
        function deleteFile(requirementName) {
            if (confirm('Remove this file? You will need to upload a new one.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('applications.requirement.delete', $application->id) }}';
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="requirement_name" value="${requirementName}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>