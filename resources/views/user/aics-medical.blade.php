<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AICS Medical Assistance - MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
    html,body{margin:0;padding:0;overscroll-behavior:none;}
    :root{--blue:#2C3E8F;--blue-dark:#1A2A5C;--yellow:#FDB913;--yellow-light:#FFF3D6;--grad:linear-gradient(135deg,#2C3E8F,#1A2A5C);--border:#E2E8F0;--bg:#F8FAFC;--dark:#1E293B;}
    *{font-family:'Inter','Segoe UI',sans-serif;}
    body{background:var(--bg);color:var(--dark);display:flex;flex-direction:column;min-height:100vh;}
    a{text-decoration:none;}
    .top-bar{background:var(--grad);padding:14px 0;box-shadow:0 4px 20px rgba(44,62,143,.2);}
    .top-inner{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;}
    .brand{display:flex;align-items:center;gap:12px;color:white;font-weight:800;font-size:1.4rem;}
    .brand img{width:32px;height:32px;object-fit:contain;}
    .back-btn{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:2px solid rgba(255,255,255,.4);color:white;border-radius:30px;padding:8px 22px;font-weight:700;font-size:.88rem;cursor:pointer;transition:all .3s;}
    .back-btn:hover{background:var(--yellow);color:var(--blue);border-color:var(--yellow);}
    .hero{background:var(--grad);color:white;padding:44px 0 32px;position:relative;overflow:hidden;}
    .hero::before{content:'';position:absolute;top:-80px;right:-80px;width:320px;height:320px;border-radius:50%;background:rgba(253,185,19,.1);}
    .hero-inner{position:relative;z-index:2;}
    .hero-badge{display:inline-block;background:rgba(253,185,19,.18);color:var(--yellow);border:1px solid rgba(253,185,19,.35);border-radius:30px;padding:5px 18px;font-size:.75rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;margin-bottom:14px;}
    .hero h1{font-size:2rem;font-weight:900;margin-bottom:6px;}
    .divider{width:48px;height:4px;background:var(--yellow);border-radius:2px;margin:12px 0;}
    .hero p{opacity:.85;font-size:.93rem;max-width:580px;line-height:1.7;}
    .sec-card{background:white;border-radius:20px;border:1px solid var(--border);box-shadow:0 4px 16px rgba(0,0,0,.04);overflow:hidden;margin-bottom:24px;}
    .sec-head{background:var(--grad);color:white;padding:18px 24px;display:flex;align-items:center;gap:14px;flex-wrap:wrap;}
    .sec-icon{width:42px;height:42px;background:rgba(253,185,19,.2);border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;}
    .sec-head h4{font-weight:800;margin:0;font-size:1rem;}
    .sec-head p{margin:0;opacity:.82;font-size:.82rem;margin-top:1px;}
    .sec-body{padding:22px 24px;}
    .note{background:var(--yellow-light);border-left:4px solid var(--yellow);border-radius:10px;padding:11px 16px;margin-bottom:18px;font-size:.84rem;color:#856404;font-weight:600;}
    /* Req cards */
    .req-card{background:white;border:1px solid var(--border);border-radius:14px;padding:0;overflow:hidden;height:100%;}
    .req-card-head{display:flex;align-items:flex-start;justify-content:space-between;gap:8px;padding:14px 16px 10px;}
    .req-name{font-weight:700;color:var(--dark);font-size:.9rem;}
    .req-date{font-size:.7rem;color:#94a3b8;margin-top:2px;}
    .upload-area{background:#FFFBF0;border:1px dashed var(--yellow);border-radius:10px;padding:12px 14px;margin:0 12px 12px;}
    .upload-label{font-size:.78rem;font-weight:700;color:#856404;margin-bottom:7px;}
    .size-hint{font-size:.67rem;color:#94a3b8;margin-top:3px;}
    .view-link{display:inline-block;background:var(--grad);color:white;border-radius:8px;padding:3px 10px;font-size:.74rem;font-weight:700;margin-left:4px;}
    .thumb{width:44px;height:44px;object-fit:cover;border-radius:8px;border:1px solid var(--border);cursor:pointer;}
    .remark{background:var(--yellow-light);border-left:3px solid var(--yellow);border-radius:8px;padding:8px 12px;font-size:.78rem;color:#856404;margin:0 12px 10px;}
    .badge-approved{background:#d4edda;color:#155724;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;}
    .badge-rejected{background:#f8d7da;color:#721c24;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;}
    .badge-pending{background:#FFF3D6;color:#856404;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;}
    .badge-none{background:#e9ecef;color:#6c757d;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;}
    .badge-review{background:#d1ecf1;color:#0c5460;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;}
    .upload-all-btn{background:var(--grad);color:white;border:none;border-radius:12px;padding:13px 32px;font-weight:800;font-size:.95rem;cursor:pointer;display:inline-flex;align-items:center;gap:9px;transition:all .3s;}
    .upload-all-btn:hover{opacity:.88;transform:translateY(-1px);}
    .footer-strip{background:var(--grad);color:rgba(255,255,255,.8);text-align:center;padding:18px;font-size:.84rem;margin-top:auto;}
    .footer-strip strong{color:white;}
    </style>

</head>
<body>

<div class="top-bar">
    <div class="container top-inner">
        <a class="brand" href="{{ route('user.dashboard') }}">
            <img src="{{ asset('images/mswd-logo.png') }}" alt="MSWD"> MSWDO
        </a>
        <a href="{{ route('user.aics-category') }}" class="back-btn">&#8592; Back to AICS Categories</a>
    </div>
</div>

<div class="hero">
    <div class="container hero-inner">
        <div class="hero-badge">AICS - Medical Assistance</div>
        <h1>&#128196; Submit Your Medical Assistance Requirements</h1>
        <div class="divider"></div>
        <p>Upload digital copies of your documents below. The admin will review each one individually.</p>
    </div>
</div>

<div class="container py-4" style="flex:1;">

    @if(session('upload_success'))
    <div style="background:#d4edda;border-left:4px solid #28a745;border-radius:12px;padding:12px 18px;margin-bottom:16px;font-size:.88rem;color:#155724;font-weight:600;">
        &#10003; {{ session('upload_success') }}
    </div>
    @endif
    @if(session('error'))
    <div style="background:#f8d7da;border-left:4px solid #dc3545;border-radius:12px;padding:12px 18px;margin-bottom:16px;font-size:.88rem;color:#721c24;font-weight:600;">
        &#10007; {{ session('error') }}
    </div>
    @endif

    <div class="note">
        &#128204; <strong>Note:</strong> Prepare <strong>2 copies</strong> of every requirement.
        Upload clear, readable scans or photos. <strong>Images: 5MB max &bull; PDF: 25MB max</strong>
    </div>

    <div class="sec-card">
        <div class="sec-head">
            <div class="sec-icon">&#128196;</div>
            <div>
                <h4>Submit Your Requirements</h4>
                <p>Select files below and click <strong>Upload All</strong>, or upload each document individually.</p>
            </div>
        </div>
        <div class="sec-body">
            <form id="aicsMedBatch" action="{{ route('user.aics-medical-upload-batch') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                @foreach($requirements as $reqName)
                @php
                    $uf      = $uploadedFiles->firstWhere('requirement_name', $reqName);
                    $fStatus = $uf?->status ?? 'not_uploaded';
                @endphp
                <div class="col-md-6">
                    <div class="req-card">
                        <div class="req-card-head">
                            <div>
                                <div class="req-name">{{ $reqName }}</div>
                                @if($uf && $uf->uploaded_at)
                                <div class="req-date">Uploaded {{ \Carbon\Carbon::parse($uf->uploaded_at)->format('M j, Y') }}</div>
                                @endif
                            </div>
                            <div style="display:flex;align-items:center;gap:5px;flex-shrink:0;">
                                @if($fStatus==='approved') <span class="badge-approved">&#10003; Approved</span>
                                @elseif($fStatus==='rejected') <span class="badge-rejected">&#10007; Rejected</span>
                                @elseif($fStatus==='pending') <span class="badge-pending">&#9203; Pending</span>
                                @elseif($fStatus==='in_review') <span class="badge-review">&#128269; In Review</span>
                                @else <span class="badge-none">&#128280; Not uploaded</span>
                                @endif
                                @if($uf && $uf->file_path)
                                @php $ext=strtolower(pathinfo($uf->file_path,PATHINFO_EXTENSION)); @endphp
                                @if(in_array($ext,['jpg','jpeg','png','webp']))
                                <img src="{{ asset('storage/'.$uf->file_path) }}" onclick="window.open('{{ asset('storage/'.$uf->file_path) }}')" class="thumb">
                                @endif
                                <a href="{{ asset('storage/'.$uf->file_path) }}" target="_blank" class="view-link">View</a>
                                @endif
                            </div>
                        </div>

                        @if($uf && $uf->admin_remarks)
                        <div class="remark"><strong>Note:</strong> {{ $uf->admin_remarks }}</div>
                        @endif
                        @if($fStatus !== 'approved')
                        <div class="upload-area">
                            <div class="upload-label">
                                &#128193; {{ $fStatus==='rejected' ? 'Re-upload document' : 'Choose file to upload' }}
                            </div>
                            <input type="file" name="files[{{ $reqName }}]"
                                class="form-control form-control-sm batch-file-input"
                                accept=".jpg,.jpeg,.png,.pdf"
                                onchange="validateAicsFile(this)"
                                data-req="{{ $reqName }}">
                            <div class="size-hint">Images: 5MB max &bull; PDF: 25MB max</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
                </div>
                <div style="margin-top:20px;display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
                    <button type="submit" id="aicsMedBatch-btn" class="upload-all-btn">
                        &#128228; Upload All Selected Files
                    </button>
                    <span style="font-size:.78rem;color:#64748b;">Only files you select will be uploaded</span>
                </div>
            </form>
            @php $hasIndividual = $uploadedFiles->whereIn('status',['rejected','pending'])->count() > 0
                || $uploadedFiles->count() < count($requirements); @endphp
            @if($uploadedFiles->count() > 0)
            <hr style="margin:22px 0 18px;border-color:#e2e8f0;">
            <div style="font-weight:700;color:#1e293b;font-size:.9rem;margin-bottom:12px;">&#128260; Upload / Re-upload Individually</div>
            <div class="row g-3">
            @foreach($requirements as $reqName)
            @php
                $uf2      = $uploadedFiles->firstWhere('requirement_name', $reqName);
                $fStatus2 = $uf2?->status ?? 'not_uploaded';
            @endphp
            @if(!$uf2 || $fStatus2 === 'rejected')
            <div class="col-md-6">
                <div class="req-card" style="border-left:4px solid {{ $fStatus2==='rejected'?'#dc3545':'#cbd5e1' }};">
                    <div class="req-card-head">
                        <div class="req-name">{{ $reqName }}</div>
                        @if($fStatus2==='rejected')<span class="badge-rejected">&#10007; Rejected</span>
                        @else<span class="badge-none">&#128280; Not uploaded</span>@endif
                    </div>
                    @if($uf2 && $uf2->admin_remarks)
                    <div class="remark"><strong>Note:</strong> {{ $uf2->admin_remarks }}</div>
                    @endif
                    <div class="upload-area">
                        <div class="upload-label">&#128193; {{ $fStatus2==='rejected'?'Re-upload document':'Upload document' }}</div>
                        <form action="{{ route('user.aics-medical-upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="requirement_name" value="{{ $reqName }}">
                            <div class="row g-1 align-items-center mt-1">
                                <div class="col-8">
                                    <input type="file" name="file" class="form-control form-control-sm"
                                        accept=".jpg,.jpeg,.png,.pdf" onchange="validateAicsFile(this)" required>
                                    <div class="size-hint">Images: 5MB &bull; PDF: 25MB</div>
                                </div>
                                <div class="col-4">
                                    <button type="submit" class="btn btn-warning btn-sm w-100" style="font-weight:700;font-size:.8rem;">
                                        {{ $fStatus2==='rejected'?'Re-upload':'Upload' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
            </div>
            @endif

            <div style="font-size:.8rem;color:#6c757d;margin-top:16px;">
                &#128203; Track all your uploaded documents in
                <a href="{{ route('user.my-requirements') }}" style="color:var(--blue);font-weight:600;">My Requirements &#8594;</a>
            </div>
        </div>
    </div>

    <div class="text-center pb-4">
        <a href="{{ route('user.dashboard') }}" class="back-btn d-inline-flex" style="font-size:.9rem;padding:11px 26px;">&#8592; Return to Dashboard</a>
    </div>
</div>

<div class="footer-strip">
    <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
</div>

@include('components.chat-modal')
@include('components.chatbot-widget')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function validateAicsFile(input) {
    const file = input.files[0]; if (!file) return;
    const isImage = ['image/jpeg','image/jpg','image/png'].includes(file.type);
    const maxSize = isImage ? 5*1024*1024 : 25*1024*1024;
    if (file.size > maxSize) {
        alert('File size must be less than ' + (isImage?'5MB':'25MB') + ' for ' + (isImage?'images':'PDF files') + '.');
        input.value=''; return false;
    }
    return true;
}
document.getElementById('aicsMedBatch').addEventListener('submit', function(e) {
    const inputs = this.querySelectorAll('.batch-file-input');
    const hasFile = Array.from(inputs).some(i => i.files.length > 0);
    if (!hasFile) { e.preventDefault(); alert('Please select at least one file before uploading.'); return; }
    inputs.forEach(i => { if (!i.files.length) i.disabled = true; });
    const btn = document.getElementById('aicsMedBatch-btn');
    btn.textContent = 'Uploading...'; btn.disabled = true;
});
</script>
</body>
</html>
