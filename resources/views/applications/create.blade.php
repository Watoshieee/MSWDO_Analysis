<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $programName }} – Required Documents | MSWDO</title>
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
        .hero-divider { width:44px; height:4px; background:var(--secondary-yellow); border-radius:2px; margin:10px 0; }
        .hero-banner p { opacity:0.84; font-size:0.93rem; margin:0; }

        /* ── STEPPER ── */
        .stepper-wrap { background:var(--bg-white); border:1px solid var(--border-light); border-radius:18px; padding:22px 28px; margin-bottom:24px; }
        .stepper { display:flex; align-items:center; }
        .step-item { display:flex; align-items:center; }
        .step-circle { width:40px; height:40px; border-radius:50%; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:0.9rem; border:2px solid var(--border-light); background:var(--bg-light); color:#94a3b8; }
        .step-circle.active { background:var(--primary-blue); border-color:var(--primary-blue); color:white; box-shadow:0 0 0 4px rgba(44,62,143,0.15); }
        .step-label { margin-left:10px; }
        .step-label .step-num  { font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#94a3b8; }
        .step-label .step-name { font-size:0.85rem; font-weight:700; color:#94a3b8; }
        .step-label .step-name.active-text { color:var(--primary-blue); }
        .step-connector { flex:1; height:2px; background:var(--border-light); margin:0 14px; }

        /* ── INFO BANNER ── */
        .info-banner { background:var(--bg-soft-blue); border:1px solid rgba(44,62,143,0.12); border-left:4px solid var(--primary-blue); border-radius:14px; padding:14px 18px; margin-bottom:22px; }
        .info-banner p { margin:0; font-size:0.85rem; color:var(--primary-blue); font-weight:500; line-height:1.6; }
        .info-banner strong { font-weight:800; }

        /* ── SECTION HEADER ── */
        .section-header-bar { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
        .section-header-bar h5 { font-size:1.05rem; font-weight:800; color:var(--primary-blue); margin:0; position:relative; padding-bottom:8px; }
        .section-header-bar h5::after { content:''; position:absolute; bottom:0; left:0; width:32px; height:3px; background:var(--secondary-yellow); border-radius:2px; }
        .doc-count-pill { font-size:0.72rem; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; background:var(--bg-soft-blue); color:var(--primary-blue); border-radius:20px; padding:4px 14px; }

        /* ── REQUIREMENT CARDS ── */
        .req-card { background:var(--bg-white); border:1px solid var(--border-light); border-radius:14px; margin-bottom:12px; overflow:hidden; transition:all 0.25s; position:relative; }
        .req-card::before { content:''; position:absolute; left:0; top:0; bottom:0; width:5px; background:var(--border-light); }
        .req-card.has-file::before  { background:var(--primary-blue); }
        .req-card:hover { box-shadow:0 6px 20px rgba(44,62,143,0.08); }
        .req-inner { padding:18px 20px 18px 26px; display:flex; align-items:center; gap:20px; flex-wrap:wrap; }

        .req-num { font-size:0.68rem; font-weight:800; letter-spacing:0.08em; text-transform:uppercase; color:var(--primary-blue); background:var(--bg-soft-blue); border-radius:20px; padding:2px 10px; margin-bottom:5px; display:inline-block; }
        .req-num.ready { color:#155724; background:#d4edda; }
        .req-name { font-size:0.95rem; font-weight:700; color:var(--text-dark); }
        .req-required { font-size:0.75rem; color:#94a3b8; margin-top:2px; font-weight:500; }

        /* upload zone */
        .upload-zone { border:2px dashed var(--border-light); border-radius:12px; padding:16px 20px; text-align:center; cursor:pointer; transition:all 0.25s; min-width:200px; }
        .upload-zone:hover { border-color:var(--primary-blue); background:var(--bg-soft-blue); }
        .upload-zone.has-file-selected { border-color:#28a745; background:#f0fff0; }
        .uz-icon { width:36px; height:36px; border-radius:10px; background:var(--bg-soft-blue); color:var(--primary-blue); display:flex; align-items:center; justify-content:center; font-size:1rem; font-weight:900; margin:0 auto 6px; }
        .uz-label { font-size:0.8rem; font-weight:700; color:var(--primary-blue); }
        .uz-sub   { font-size:0.71rem; color:#94a3b8; }
        .uz-selected { font-size:0.8rem; font-weight:700; color:#155724; word-break:break-all; }

        /* status indicator */
        .status-dot { width:8px; height:8px; border-radius:50%; display:inline-block; margin-right:6px; }
        .dot-empty { background:#cbd5e1; }
        .dot-ready { background:#28a745; }
        .req-status-text { font-size:0.8rem; font-weight:600; color:#94a3b8; }
        .req-status-text.ready-text { color:#28a745; }

        /* ── ALERTS ── */
        .alert-styled { border-radius:12px; border:none; font-size:0.89rem; padding:12px 16px; margin-bottom:16px; }
        .alert-success-c { background:#d4edda; border-left:5px solid #28a745; color:#155724; }
        .alert-danger-c  { background:#fce8e8; border-left:5px solid #dc3545; color:#721c24; }

        /* ── SUBMIT BAR ── */
        .submit-bar { background:var(--bg-white); border:1px solid var(--border-light); border-radius:18px; padding:18px 24px; margin-top:28px; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; }
        .submit-bar-left { font-size:0.85rem; color:#64748b; font-weight:500; }
        .submit-bar-left strong { color:var(--primary-blue); font-weight:800; font-size:1rem; }
        .btn-back-prog { background:transparent; border:2px solid var(--border-light); color:var(--text-dark); border-radius:10px; padding:11px 22px; font-weight:700; font-size:0.88rem; transition:all 0.2s; cursor:pointer; }
        .btn-back-prog:hover { border-color:var(--primary-blue); color:var(--primary-blue); }
        .btn-submit-docs { background:var(--primary-gradient); color:white; border:none; border-radius:10px; padding:12px 28px; font-weight:800; font-size:0.92rem; cursor:pointer; transition:all 0.25s; letter-spacing:0.02em; }
        .btn-submit-docs:hover:not(:disabled) { box-shadow:0 8px 22px rgba(44,62,143,0.30); transform:translateY(-1px); }
        .btn-submit-docs:disabled { background:#cbd5e1; color:#94a3b8; cursor:not-allowed; }

        /* ── FOOTER ── */
        .main-content { flex:1; }
        .footer-strip { background: var(--primary-gradient); color: rgba(255,255,255,0.75); text-align: center; padding: 20px 0; font-size: 0.85rem; margin-top: 56px; }
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

    <!-- HERO -->
    <section class="hero-banner">
        <div class="container">
            <div class="hero-inner">
                <div class="hero-badge">Apply for Assistance</div>
                <h1>{{ $programName }}</h1>
                <div class="hero-divider"></div>
                <p>Upload the required documents listed below to submit your application for review.</p>
            </div>
        </div>
    </section>

    <div class="main-content">
    <div class="container mt-4">

        <!-- STEPPER -->
        <div class="stepper-wrap">
            <div class="stepper">
                <div class="step-item">
                    <div class="step-circle active">01</div>
                    <div class="step-label">
                        <div class="step-num">Step 01</div>
                        <div class="step-name active-text">Upload Documents</div>
                    </div>
                </div>
                <div class="step-connector"></div>
                <div class="step-item">
                    <div class="step-circle">02</div>
                    <div class="step-label">
                        <div class="step-num">Step 02</div>
                        <div class="step-name">Submit Application</div>
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

        <!-- INFO -->
        <div class="info-banner">
            <p>
                <strong>How this works:</strong> Upload each required document using the areas below. 
                Once you've selected all your files, click <strong>Submit Application</strong>. 
                Your documents will be uploaded and sent for review by the MSWDO team.
            </p>
        </div>

        @if(session('success'))
            <div class="alert-styled alert-success-c">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert-styled alert-danger-c">{{ session('error') }}</div>
        @endif

        <!-- DOCUMENT LIST -->
        <div class="section-header-bar">
            <h5>Required Documents</h5>
            <span class="doc-count-pill">{{ count($requirements) }} documents</span>
        </div>

        @foreach($requirements as $index => $req)
        <div class="req-card" id="req-card-{{ $index }}">
            <div class="req-inner">
                <!-- Number + name -->
                <div style="flex:1; min-width:200px;">
                    <span class="req-num" id="req-num-{{ $index }}">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                    <div class="req-name">{{ $req->requirement_name }}</div>
                    <div class="req-required">Required document</div>
                </div>

                <!-- Upload zone -->
                <div class="upload-zone" id="zone-{{ $index }}"
                     onclick="document.getElementById('file-{{ $index }}').click()">
                    <div class="uz-icon" id="zone-icon-{{ $index }}">+</div>
                    <div class="uz-label">Select File</div>
                    <div class="uz-sub" id="zone-sub-{{ $index }}">JPG, PNG or PDF &bull; Max 5MB</div>
                    <div id="zone-filename-{{ $index }}" class="uz-selected mt-1" style="display:none;"></div>
                </div>

                <input type="file" id="file-{{ $index }}" class="d-none"
                       accept=".jpg,.jpeg,.png,.pdf"
                       data-requirement="{{ $req->requirement_name }}"
                       data-index="{{ $index }}">

                <!-- Status indicator -->
                <div style="min-width:100px; text-align:right;">
                    <span class="status-dot dot-empty" id="dot-{{ $index }}"></span>
                    <span class="req-status-text" id="status-text-{{ $index }}">Not selected</span>
                </div>
            </div>
        </div>
        @endforeach

        <!-- SUBMIT BAR -->
        <div class="submit-bar">
            <div class="submit-bar-left">
                <strong id="ready-count">0</strong> of {{ count($requirements) }} documents selected
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('user.programs') }}" class="btn-back-prog">&#8592; Back to Programs</a>
                <button class="btn-submit-docs" id="submit-btn" onclick="submitAllRequirements()" disabled>
                    Submit Application &#8594;
                </button>
            </div>
        </div>

    </div>
    </div>

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    <script>
        let uploadedFiles = {};
        const totalDocs = {{ count($requirements) }};

        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function () {
                const index       = this.dataset.index;
                const requirement = this.dataset.requirement;
                const file        = this.files[0];

                if (!file) return;

                // Validate size
                if (file.size > 5 * 1024 * 1024) {
                    alert('File size must be less than 5MB.');
                    this.value = '';
                    return;
                }

                // Validate type
                if (!['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'].includes(file.type)) {
                    alert('Only JPG, PNG, and PDF files are allowed.');
                    this.value = '';
                    return;
                }

                uploadedFiles[requirement] = { file, index, name: file.name };

                // Update upload zone
                const zone = document.getElementById(`zone-${index}`);
                zone.classList.add('has-file-selected');
                document.getElementById(`zone-icon-${index}`).textContent = '✓';
                document.getElementById(`zone-icon-${index}`).style.background = '#d4edda';
                document.getElementById(`zone-icon-${index}`).style.color     = '#155724';
                document.getElementById(`zone-sub-${index}`).style.display    = 'none';
                const fnEl = document.getElementById(`zone-filename-${index}`);
                fnEl.style.display    = 'block';
                fnEl.textContent      = file.name;

                // Update number badge
                const numEl = document.getElementById(`req-num-${index}`);
                numEl.classList.add('ready');
                numEl.textContent = '✓';

                // Update card
                document.getElementById(`req-card-${index}`).classList.add('has-file');

                // Update status dot
                document.getElementById(`dot-${index}`).classList.replace('dot-empty', 'dot-ready');
                const stEl = document.getElementById(`status-text-${index}`);
                stEl.textContent = 'Ready';
                stEl.classList.add('ready-text');

                // Update counter
                const ready = Object.keys(uploadedFiles).length;
                document.getElementById('ready-count').textContent = ready;

                // Enable submit if at least 1
                document.getElementById('submit-btn').disabled = ready === 0;
            });
        });

        function submitAllRequirements() {
            const requirements = Object.keys(uploadedFiles);
            if (requirements.length === 0) {
                alert('Please select at least one document before submitting.');
                return;
            }

            const formData = new FormData();
            formData.append('program_type', '{{ $programType }}');

            for (const [req, data] of Object.entries(uploadedFiles)) {
                formData.append(`requirements[${req}]`, data.file);
                formData.append('requirement_names[]', req);
            }

            const btn = document.getElementById('submit-btn');
            btn.innerHTML = 'Submitting...';
            btn.disabled  = true;

            fetch('{{ route('applications.upload-batch') }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '{{ route('user.my-requirements') }}';
                } else {
                    alert('Error: ' + data.message);
                    btn.innerHTML = 'Submit Application &#8594;';
                    btn.disabled  = false;
                }
            })
            .catch(() => {
                alert('A network error occurred. Please try again.');
                btn.innerHTML = 'Submit Application &#8594;';
                btn.disabled  = false;
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>