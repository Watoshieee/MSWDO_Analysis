<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AICS Burial Assistance – MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
html, body { overscroll-behavior: none; margin: 0; padding: 0; }

        :root {
            --primary-blue:#2C3E8F; --primary-blue-light:#E5EEFF;
            --secondary-yellow:#FDB913; --secondary-yellow-light:#FFF3D6;
            --primary-gradient:linear-gradient(135deg,#2C3E8F 0%,#1A2A5C 100%);
            --bg-light:#F8FAFC; --border-light:#E2E8F0; --text-dark:#1E293B;
        }
        *,body { font-family:'Inter','Segoe UI',sans-serif; }
        body { background:var(--bg-light); color:var(--text-dark); display:flex; flex-direction:column; min-height:100vh; margin:0; }
        a { text-decoration:none; }

        .top-bar { background:var(--primary-gradient); padding:14px 0; box-shadow:0 4px 20px rgba(44,62,143,.2); }
        .top-bar-inner { display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; }
        .brand { display:flex; align-items:center; gap:12px; color:white; font-weight:800; font-size:1.45rem; }
        .brand img { width:34px; height:34px; object-fit:contain; }
        .back-btn { display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,.12); border:2px solid rgba(255,255,255,.4); color:white; border-radius:30px; padding:8px 22px; font-weight:700; font-size:.88rem; cursor:pointer; transition:all .3s; }
        .back-btn:hover { background:var(--secondary-yellow); color:var(--primary-blue); border-color:var(--secondary-yellow); }

        .hero-banner { background:var(--primary-gradient); color:white; padding:48px 0 36px; position:relative; overflow:hidden; }
        .hero-banner::before { content:''; position:absolute; top:-90px; right:-90px; width:360px; height:360px; border-radius:50%; background:rgba(253,185,19,.10); }
        .hero-inner { position:relative; z-index:2; }
        .hero-badge { display:inline-block; background:rgba(253,185,19,.18); color:var(--secondary-yellow); border:1px solid rgba(253,185,19,.35); border-radius:30px; padding:5px 18px; font-size:.75rem; font-weight:800; letter-spacing:.1em; text-transform:uppercase; margin-bottom:16px; }
        .hero-banner h1 { font-size:2.2rem; font-weight:900; margin-bottom:8px; }
        .hero-divider { width:50px; height:4px; background:var(--secondary-yellow); border-radius:2px; margin:14px 0; }
        .hero-banner p { opacity:.85; font-size:.95rem; max-width:600px; line-height:1.7; }

        .section-card { background:white; border-radius:20px; border:1px solid var(--border-light); box-shadow:0 4px 16px rgba(0,0,0,.04); overflow:hidden; margin-bottom:28px; }
        .section-header { background:var(--primary-gradient); color:white; padding:20px 26px; display:flex; align-items:center; gap:16px; flex-wrap:wrap; }
        .sec-icon { width:44px; height:44px; background:rgba(253,185,19,.2); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.3rem; flex-shrink:0; }
        .section-header h4 { font-weight:800; margin:0; font-size:1.05rem; }
        .section-header p { margin:0; opacity:.82; font-size:.84rem; margin-top:2px; }
        .section-body { padding:24px 26px; }

        .note-banner { background:var(--secondary-yellow-light); border-left:4px solid var(--secondary-yellow); border-radius:10px; padding:12px 18px; margin-bottom:20px; font-size:.86rem; color:#856404; font-weight:600; }

        .req-row { background:white; border:1px solid var(--border-light); border-radius:14px; padding:16px 18px; margin-bottom:12px; border-left:4px solid #cbd5e1; }
        .req-row.approved { border-left-color:#28a745; background:#f6fff6; }
        .req-row.rejected  { border-left-color:#dc3545; background:#fff6f6; }
        .req-row.pending   { border-left-color:var(--secondary-yellow); }
        .req-name { font-weight:700; color:var(--text-dark); font-size:.93rem; }
        .req-date { font-size:.7rem; color:#94a3b8; margin-top:2px; }
        .req-remark { background:var(--secondary-yellow-light); border-left:3px solid var(--secondary-yellow); border-radius:8px; padding:8px 12px; font-size:.78rem; color:#856404; margin-top:10px; }
        .upload-box { background:#FFFBF0; border:1px dashed #FDB913; border-radius:10px; padding:12px 14px; margin-top:12px; }
        .pwd-thumb { width:48px; height:48px; object-fit:cover; border-radius:8px; border:1px solid var(--border-light); cursor:pointer; }
        .pwd-view { display:inline-block; background:var(--primary-gradient); color:white; border-radius:8px; padding:3px 10px; font-size:.74rem; font-weight:700; margin-left:4px; }

        .footer-strip { background:var(--primary-gradient); color:rgba(255,255,255,.8); text-align:center; padding:18px; font-size:.84rem; margin-top:auto; }
        .footer-strip strong { color:white; }
    </style>
</head>
<body>

<div class="top-bar">
    <div class="container top-bar-inner">
        <a class="brand" href="{{ route('user.dashboard') }}">
            <img src="/images/mswd-logo.png" alt="MSWD"> MSWDO
        </a>
        <a href="{{ route('user.aics-category') }}" class="back-btn">← Back to AICS Categories</a>
    </div>
</div>

<div class="hero-banner">
    <div class="container hero-inner">
        <div class="hero-badge">AICS — Burial Assistance</div>
        <h1>🕯️ Submit Your Burial Assistance Requirements</h1>
        <div class="hero-divider"></div>
        <p>Upload digital copies of your documents below. The admin will review each one individually.</p>
    </div>
</div>

<div class="container py-4" style="flex:1;">

    @if(session('upload_success'))
    <div style="background:#d4edda;border-left:4px solid #28a745;border-radius:12px;padding:12px 18px;margin-bottom:16px;font-size:.88rem;color:#155724;font-weight:600;">
        ✅ {{ session('upload_success') }}
    </div>
    @endif

    <div class="note-banner">
        ⚠️ <strong>Note:</strong> Prepare <strong>2 copies</strong> of every requirement. Upload clear, readable scans or photos (JPG/PNG/PDF, max 5MB each).
    </div>

    <!-- UPLOAD CARD -->
    <div class="section-card">
        <div class="section-header" style="flex-direction:column;align-items:flex-start;gap:6px;">
            <div style="display:flex;align-items:center;gap:14px;width:100%;">
                <div class="sec-icon">📤</div>
                <div style="flex:1;">
                    <h4>Submit Your AICS Burial Requirements Online</h4>
                    <p>Upload digital copies of your documents. The admin will review each one.</p>
                </div>
                @if($application)
                <span style="background:rgba(255,255,255,.15);color:white;border-radius:20px;padding:3px 14px;font-size:.78rem;font-weight:700;">App #{{ $application->id }}</span>
                @endif
            </div>
            @if($application)
            @php
                $totalR    = count($requirements);
                $approvedR = $uploadedFiles->where('status','approved')->count();
                $pctR      = $totalR > 0 ? round(($approvedR / $totalR) * 100) : 0;
            @endphp
            <div style="width:100%;margin-top:6px;">
                <div style="height:5px;background:rgba(255,255,255,.2);border-radius:3px;overflow:hidden;">
                    <div style="width:{{ $pctR }}%;height:100%;background:var(--secondary-yellow);border-radius:3px;"></div>
                </div>
                <div style="font-size:.72rem;color:rgba(255,255,255,.8);margin-top:4px;">{{ $approvedR }}/{{ $totalR }} approved &mdash; {{ $pctR }}% complete</div>
            </div>
            @endif
        </div>

        <div class="section-body">
            <div class="row g-3">
            @foreach($requirements as $reqName)
            @php
                $uf      = $uploadedFiles->firstWhere('requirement_name', $reqName);
                $fStatus = $uf?->status ?? 'not_uploaded';
                $cls     = match($fStatus) { 'approved'=>'approved','rejected'=>'rejected','pending'=>'pending', default=>'' };
                $badge   = match($fStatus) {
                    'approved'  => '<span style="background:#d4edda;color:#155724;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;">✅ Approved</span>',
                    'rejected'  => '<span style="background:#f8d7da;color:#721c24;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;">❌ Rejected</span>',
                    'in_review' => '<span style="background:#d1ecf1;color:#0c5460;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;">🔍 In Review</span>',
                    'pending'   => '<span style="background:#FFF3D6;color:#856404;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;">🕐 Pending</span>',
                    default     => '<span style="background:#e9ecef;color:#6c757d;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;">📎 Not uploaded</span>',
                };
            @endphp
            <div class="col-md-6">
                <div class="req-row {{ $cls }}">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:6px;">
                        <div style="flex:1;">
                            <div class="req-name">{{ $reqName }}</div>
                            @if($uf && $uf->uploaded_at)
                            <div class="req-date">{{ \Carbon\Carbon::parse($uf->uploaded_at)->format('M j, Y') }}</div>
                            @endif
                        </div>
                        <div style="display:flex;align-items:center;gap:5px;flex-shrink:0;">
                            {!! $badge !!}
                            @if($uf && $uf->file_path)
                            @php $ext = strtolower(pathinfo($uf->file_path, PATHINFO_EXTENSION)); @endphp
                            @if(in_array($ext,['jpg','jpeg','png','webp']))
                            <img src="{{ asset('storage/'.$uf->file_path) }}" onclick="window.open('{{ asset('storage/'.$uf->file_path) }}')" class="pwd-thumb">
                            @endif
                            <a href="{{ asset('storage/'.$uf->file_path) }}" target="_blank" class="pwd-view">View</a>
                            @endif
                        </div>
                    </div>

                    @if($uf && $uf->admin_remarks)
                    <div class="req-remark"><strong>Note:</strong> {{ $uf->admin_remarks }}</div>
                    @endif

                    @if(!$uf || $fStatus === 'rejected')
                    <div class="upload-box">
                        <div style="font-size:.78rem;font-weight:600;color:#856404;margin-bottom:7px;">
                            {{ $fStatus === 'rejected' ? '🔄 Re-upload document' : '📤 Upload document' }}
                        </div>
                        <form action="{{ route('user.aics-burial-upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="requirement_name" value="{{ $reqName }}">
                            <div class="row g-1 align-items-center">
                                <div class="col-8">
                                    <input type="file" name="file" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf" required>
                                    <div style="font-size:.67rem;color:#94a3b8;margin-top:2px;">Max 5MB · JPG PNG PDF</div>
                                </div>
                                <div class="col-4">
                                    <button type="submit" class="btn btn-warning btn-sm w-100" style="font-weight:700;font-size:.8rem;">
                                        {{ $fStatus === 'rejected' ? 'Re-upload' : 'Upload' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
            </div>

            <div style="font-size:.8rem;color:#6c757d;margin-top:14px;">
                💡 Track all your uploaded documents in
                <a href="{{ route('user.my-requirements') }}" style="color:var(--primary-blue);font-weight:600;">My Requirements →</a>
            </div>
        </div>
    </div>

    <div class="text-center pb-4">
        <a href="{{ route('user.dashboard') }}" class="back-btn d-inline-flex" style="font-size:.9rem;padding:11px 26px;">← Return to Dashboard</a>
    </div>

</div>

<div class="footer-strip">
    <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
