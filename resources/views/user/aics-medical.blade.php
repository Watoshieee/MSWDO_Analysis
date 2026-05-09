<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AICS Medical Assistance - MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
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
        <div style="display:flex;align-items:center;gap:12px;">
            {{-- Notification Bell --}}
            <button type="button" data-bs-toggle="modal" data-bs-target="#announcementsModal"
                style="background:rgba(255,255,255,0.12);color:white;border:2px solid rgba(255,255,255,0.3);border-radius:50%;width:40px;height:40px;font-size:1.05rem;display:flex;align-items:center;justify-content:center;padding:0;cursor:pointer;transition:all 0.3s;position:relative;"
                title="Notifications">
                <i class="bi bi-bell-fill"></i>
                @if(isset($notificationCount) && $notificationCount > 0)
                <span class="bell-badge" style="position:absolute;top:-4px;right:-4px;background:#dc3545;color:white;border-radius:50%;width:20px;height:20px;font-size:0.7rem;font-weight:800;display:flex;align-items:center;justify-content:center;border:2px solid #2C3E8F;">
                    {{ $notificationCount > 9 ? '9+' : $notificationCount }}
                </span>
                @endif
            </button>
            <a href="{{ route('user.aics-category') }}" class="back-btn">&#8592; Back to AICS Categories</a>
        </div>
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

    @php
        $topNotice = session('upload_success') ?: session('appt_success') ?: session('error') ?: session('appt_error');
    @endphp
    @if($topNotice)
    <div style="position:fixed;top:84px;right:18px;z-index:1080;max-width:420px;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border:1px solid rgba(255,255,255,.18);border-radius:12px;padding:12px 16px;box-shadow:0 10px 28px rgba(26,42,92,.35);font-size:.84rem;font-weight:700;">
        {{ $topNotice }}
    </div>
    @endif

    {{-- ── ACTIVE APPOINTMENT CARD ── --}}
    @if(isset($appointment) && $appointment && in_array($appointment->status, ['pending','confirmed']))
    <div style="background:white;border-radius:20px;border:1px solid #c7d2fe;box-shadow:0 4px 20px rgba(44,62,143,.08);overflow:hidden;margin-bottom:24px;">
        <div style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;padding:18px 26px;display:flex;align-items:center;gap:14px;">
            <div style="width:42px;height:42px;background:rgba(253,185,19,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;">📅</div>
            <div style="flex:1;">
                <div style="font-weight:800;font-size:1rem;">Your Appointment</div>
                <div style="opacity:.8;font-size:.8rem;margin-top:2px;">AICS Medical Assistance</div>
            </div>
            {!! $appointment->status_badge !!}
        </div>
        <div style="padding:20px 26px;">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:16px;">
                <div style="background:#f0f4ff;border-radius:10px;padding:12px 14px;">
                    <div style="font-size:.72rem;color:#64748b;font-weight:600;margin-bottom:3px;">DATE</div>
                    <div style="font-weight:800;color:#1e293b;font-size:.9rem;">{{ $appointment->formatted_date }}</div>
                </div>
                <div style="background:#f0f4ff;border-radius:10px;padding:12px 14px;">
                    <div style="font-size:.72rem;color:#64748b;font-weight:600;margin-bottom:3px;">TIME</div>
                    <div style="font-weight:800;color:#1e293b;font-size:.9rem;">{{ $appointment->formatted_time }}</div>
                </div>
                <div style="background:#f0f4ff;border-radius:10px;padding:12px 14px;">
                    <div style="font-size:.72rem;color:#64748b;font-weight:600;margin-bottom:3px;">TYPE</div>
                    <div style="font-weight:800;color:#1e293b;font-size:.9rem;">{{ $appointment->interview_label }}</div>
                </div>
            </div>
            @if($appointment->admin_notes)
            <div style="background:#FFF3D6;border-left:3px solid #FDB913;border-radius:8px;padding:10px 14px;font-size:.84rem;color:#856404;margin-bottom:14px;">
                <strong>Admin Note:</strong> {{ $appointment->admin_notes }}
            </div>
            @endif
            @if($appointment->cancellation_status === 'pending')
                <div style="background:#fff3cd;border-left:3px solid #ffc107;border-radius:8px;padding:10px 14px;font-size:.84rem;color:#856404;margin-bottom:14px;">
                    <strong>⏳ Cancellation Pending:</strong> Your cancellation request is waiting for admin approval.
                </div>
            @endif
            <form method="POST" action="{{ route('user.appointments.cancel', $appointment->id) }}" id="cancelForm" style="display:inline-block;margin-right:10px;">
                @csrf
                <input type="hidden" name="cancel_reason" id="cancelReasonInput">
                @if($appointment->cancellation_status !== 'pending')
                    <button type="button" onclick="showCancelModal()" style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;border-radius:8px;padding:8px 18px;font-size:.8rem;font-weight:700;cursor:pointer;">
                        🚫 Cancel Appointment
                    </button>
                @endif
            </form>
            @if($appointment->reschedule_status === 'pending')
                <span style="background:#e0f2fe;color:#0c4a6e;border:1px solid #7dd3fc;border-radius:8px;padding:8px 18px;font-size:.8rem;font-weight:700;">
                    🔄 Reschedule Pending Approval
                </span>
            @else
                <button type="button" onclick="showRescheduleModal()" style="background:#e0e7ff;color:#3730a3;border:1px solid #a5b4fc;border-radius:8px;padding:8px 18px;font-size:.8rem;font-weight:700;cursor:pointer;">
                    🔄 Request Reschedule
                </button>
            @endif
        </div>
    </div>
    @else

    {{-- ── BOOKING FORM ── --}}
    <div style="background:white;border-radius:20px;border:1px solid #c7d2fe;box-shadow:0 4px 20px rgba(44,62,143,.08);overflow:hidden;margin-bottom:24px;">
        <div style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;padding:20px 26px;display:flex;align-items:center;gap:14px;">
            <div style="width:42px;height:42px;background:rgba(253,185,19,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;">📅</div>
            <div>
                <div style="font-weight:800;font-size:1rem;">Schedule an Appointment</div>
                <div style="opacity:.8;font-size:.8rem;margin-top:2px;">Book your AICS Medical interview slot with the MSWDO</div>
            </div>
        </div>
        <div style="padding:24px 26px;">
            <div style="background:#eef2ff;border-radius:10px;padding:12px 16px;font-size:.83rem;color:#4338ca;font-weight:600;margin-bottom:20px;">
                ℹ️ Office hours: <strong>Monday – Friday, 8:00 AM – 5:00 PM</strong> (lunch 12:00–1:00 PM excluded) &bull; Max 5 appointments per time slot.
            </div>
            @if(isset($appointment) && $appointment && $appointment->status === 'rejected')
            <div style="background:#fee2e2;border-left:4px solid #dc3545;border-radius:12px;padding:14px 18px;font-size:.85rem;color:#991b1b;font-weight:600;margin-bottom:16px;">
                ❌ Your previous appointment was <strong>rejected</strong>. You may book a new slot below.
                @if($appointment->admin_notes)<br>Admin reason: {{ $appointment->admin_notes }}@endif
            </div>
            @endif
            <form id="aicsMedApptForm" method="POST" action="{{ route('user.appointments.store') }}">
                @csrf
                <input type="hidden" name="program_type" value="AICS_Medical">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label style="font-size:.8rem;font-weight:700;color:#374151;display:block;margin-bottom:6px;">📆 Select Date <span style="color:red">*</span></label>
                        <input type="date" id="aicsMedDate" name="appointment_date"
                               min="{{ $minDate }}" max="{{ $maxDate }}"
                               class="form-control"
                               style="border-radius:10px;border:1.5px solid #c7d2fe;font-weight:600;font-size:.88rem;"
                               required>
                        <div style="font-size:.7rem;color:#94a3b8;margin-top:4px;">Weekdays only (Mon–Fri)</div>
                    </div>
                    <div class="col-md-4">
                        <label style="font-size:.8rem;font-weight:700;color:#374151;display:block;margin-bottom:6px;">⏰ Select Time Slot <span style="color:red">*</span></label>
                        <select id="aicsMedTime" name="appointment_time" class="form-control"
                                style="border-radius:10px;border:1.5px solid #c7d2fe;font-weight:600;font-size:.88rem;" required disabled>
                            <option value="">Select date first</option>
                        </select>
                        <div id="aicsMedSlotMsg" style="font-size:.7rem;color:#94a3b8;margin-top:4px;"></div>
                    </div>
                    <div class="col-md-4">
                        <label style="font-size:.8rem;font-weight:700;color:#374151;display:block;margin-bottom:6px;">💬 Interview Type <span style="color:red">*</span></label>
                        <select name="interview_type" class="form-control"
                                style="border-radius:10px;border:1.5px solid #c7d2fe;font-weight:600;font-size:.88rem;" required>
                            <option value="face_to_face">🏢 Face-to-Face</option>
                            <option value="online">📱 Online (via phone call)</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label style="font-size:.8rem;font-weight:700;color:#374151;display:block;margin-bottom:6px;">📝 Additional Notes (optional)</label>
                        <textarea name="user_notes" rows="2" class="form-control"
                                  placeholder="Any concerns or special requests…"
                                  style="border-radius:10px;border:1.5px solid #c7d2fe;font-size:.85rem;"
                                  maxlength="500"></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border:none;border-radius:12px;padding:12px 32px;font-weight:800;font-size:.92rem;cursor:pointer;display:inline-flex;align-items:center;gap:8px;">
                            📅 Book Appointment
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- ═══ REQUIREMENTS — visible only once appointment is VALIDATED (Eligibility Assessment passed) ═══ --}}
    @if(isset($appointment) && $appointment && $appointment->status === 'validated')

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

    @else
    {{-- ── Waiting / no-appointment notice ─────────────────────────────────── --}}
    <div style="background:white;border-radius:20px;border:1.5px dashed #c7d2fe;padding:40px 32px;text-align:center;margin-bottom:24px;">
        <div style="font-size:3rem;margin-bottom:14px;">
            @if(!isset($appointment) || !$appointment)
                📅
            @elseif($appointment->status === 'pending')
                ⏳
            @elseif($appointment->status === 'confirmed')
                🔍
            @else
                ❌
            @endif
        </div>
        @if(!isset($appointment) || !$appointment)
            <div style="font-weight:800;font-size:1.05rem;color:#1e293b;margin-bottom:8px;">Book an Appointment First</div>
            <div style="color:#64748b;font-size:.88rem;max-width:460px;margin:0 auto;">
                You need to schedule and complete an interview with the MSWDO before you can submit your requirements. Use the form above to book your slot.
            </div>
        @elseif($appointment->status === 'pending')
            <div style="font-weight:800;font-size:1.05rem;color:#1e293b;margin-bottom:8px;">Waiting for Appointment Confirmation</div>
            <div style="color:#64748b;font-size:.88rem;max-width:460px;margin:0 auto;">
                Your appointment on <strong>{{ $appointment->formatted_date }}</strong> at <strong>{{ $appointment->formatted_time }}</strong> is pending admin confirmation.
            </div>
        @elseif($appointment->status === 'confirmed')
            <div style="font-weight:800;font-size:1.05rem;color:#1e293b;margin-bottom:8px;">🔍 Appointment Confirmed — Awaiting Eligibility Assessment</div>
            <div style="color:#64748b;font-size:.88rem;max-width:460px;margin:0 auto;">
                Your appointment on <strong>{{ $appointment->formatted_date }}</strong> at <strong>{{ $appointment->formatted_time }}</strong> has been confirmed.
                The MSWDO will conduct your eligibility assessment. Requirements will be unlocked once you pass.
            </div>
        @elseif($appointment->status === 'rejected')
            <div style="font-weight:800;font-size:1.05rem;color:#991b1b;margin-bottom:8px;">Appointment Rejected</div>
            <div style="color:#64748b;font-size:.88rem;max-width:460px;margin:0 auto;">
                Your appointment was rejected. Please book a new slot above to continue your application.
                @if($appointment->admin_notes)<br><span style="color:#dc3545;font-size:.82rem;margin-top:6px;display:block;">Reason: {{ $appointment->admin_notes }}</span>@endif
            </div>
        @elseif($appointment->status === 'cancelled')
            <div style="font-weight:800;font-size:1.05rem;color:#475569;margin-bottom:8px;">Appointment Cancelled</div>
            <div style="color:#64748b;font-size:.88rem;max-width:460px;margin:0 auto;">
                You cancelled your appointment. Book a new slot above to continue.
            </div>
        @endif
    </div>
    @endif
    <div class="text-center pb-4">
        <a href="{{ route('user.dashboard') }}" class="back-btn d-inline-flex" style="font-size:.9rem;padding:11px 26px;">&#8592; Return to Dashboard</a>
    </div>
</div>

<div class="footer-strip">
    <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
</div>

@include('components.user-notification-modal')
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
document.getElementById('aicsMedBatch')?.addEventListener('submit', function(e) {
    const inputs = this.querySelectorAll('.batch-file-input');
    const hasFile = Array.from(inputs).some(i => i.files.length > 0);
    if (!hasFile) { e.preventDefault(); alert('Please select at least one file before uploading.'); return; }
    inputs.forEach(i => { if (!i.files.length) i.disabled = true; });
    const btn = document.getElementById('aicsMedBatch-btn');
    if (btn) { btn.textContent = 'Uploading...'; btn.disabled = true; }
});

// ── Appointment slot loader ──────────────────────────────────────────────────
const aicsMedDateEl = document.getElementById('aicsMedDate');
const aicsMedTimeEl = document.getElementById('aicsMedTime');
const aicsMedMsgEl  = document.getElementById('aicsMedSlotMsg');
if (aicsMedDateEl) {
    aicsMedDateEl.addEventListener('change', function () {
        const d = this.value;
        if (!d) return;
        const day = new Date(d + 'T00:00:00').getDay();
        if (day === 0 || day === 6) {
            aicsMedMsgEl.textContent = '⚠️ Weekends are not available.';
            aicsMedTimeEl.innerHTML = '<option value="">Not available</option>';
            aicsMedTimeEl.disabled = true;
            return;
        }
        aicsMedMsgEl.textContent = 'Loading available slots…';
        aicsMedTimeEl.disabled = true;
        fetch(`/user/appointments/slots?date=${d}`, {headers:{'Accept':'application/json'}})
        .then(r => r.json())
        .then(slots => {
            const available = slots.filter(s => !s.full);
            if (!available.length) {
                aicsMedTimeEl.innerHTML = '<option value="">No available slots</option>';
                aicsMedMsgEl.textContent = '⚠️ No slots available for this date.';
                return;
            }
            aicsMedTimeEl.innerHTML = '<option value="">-- Select time --</option>' +
                available.map(s => `<option value="${s.time}">${s.label} (${s.remaining} left)</option>`).join('');
            aicsMedTimeEl.disabled = false;
            aicsMedMsgEl.textContent = `✅ ${available.length} slot(s) available`;
        })
        .catch(() => { aicsMedMsgEl.textContent = 'Failed to load slots. Try again.'; });
    });
}
</script>
</body>
</html>


{{-- Cancel Modal --}}
<div id="cancelModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;max-width:500px;width:90%;padding:28px;box-shadow:0 10px 40px rgba(0,0,0,.3);">
        <h4 style="font-weight:800;color:#1e293b;margin-bottom:16px;">Cancel Appointment</h4>
        <p style="font-size:.9rem;color:#64748b;margin-bottom:20px;">Please provide a reason for cancelling your appointment:</p>
        <textarea id="cancelReasonText" rows="4" style="width:100%;border:1.5px solid #e2e8f0;border-radius:10px;padding:12px;font-size:.88rem;font-family:inherit;" placeholder="e.g., May emergency po sa family" required></textarea>
        <div style="display:flex;gap:10px;margin-top:20px;justify-content:flex-end;">
            <button onclick="hideCancelModal()" style="background:#e2e8f0;color:#64748b;border:none;border-radius:8px;padding:10px 20px;font-weight:700;cursor:pointer;">Cancel</button>
            <button onclick="submitCancel()" style="background:#dc3545;color:white;border:none;border-radius:8px;padding:10px 20px;font-weight:700;cursor:pointer;">Confirm Cancel</button>
        </div>
    </div>
</div>

{{-- Reschedule Modal --}}
<div id="rescheduleModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;overflow-y:auto;">
    <div style="background:white;border-radius:16px;max-width:600px;width:90%;padding:28px;box-shadow:0 10px 40px rgba(0,0,0,.3);margin:20px;">
        <h4 style="font-weight:800;color:#1e293b;margin-bottom:16px;">Request Reschedule</h4>
        <form method="POST" action="{{ route('user.appointments.reschedule', $appointment->id ?? 0) }}" id="rescheduleForm">
            @csrf
            <div style="margin-bottom:16px;">
                <label style="font-size:.85rem;font-weight:700;color:#374151;display:block;margin-bottom:6px;">New Date <span style="color:red">*</span></label>
                <input type="date" name="reschedule_date" id="rescheduleDate" min="{{ $minDate ?? '' }}" max="{{ $maxDate ?? '' }}" required style="width:100%;border:1.5px solid #c7d2fe;border-radius:10px;padding:10px;font-size:.88rem;">
            </div>
            <div style="margin-bottom:16px;">
                <label style="font-size:.85rem;font-weight:700;color:#374151;display:block;margin-bottom:6px;">New Time <span style="color:red">*</span></label>
                <select name="reschedule_time" id="rescheduleTime" required disabled style="width:100%;border:1.5px solid #c7d2fe;border-radius:10px;padding:10px;font-size:.88rem;">
                    <option value="">Select date first</option>
                </select>
                <div id="rescheduleSlotMsg" style="font-size:.75rem;color:#94a3b8;margin-top:4px;"></div>
            </div>
            <div style="margin-bottom:20px;">
                <label style="font-size:.85rem;font-weight:700;color:#374151;display:block;margin-bottom:6px;">Reason for Reschedule <span style="color:red">*</span></label>
                <textarea name="reschedule_reason" rows="3" required style="width:100%;border:1.5px solid #c7d2fe;border-radius:10px;padding:12px;font-size:.88rem;font-family:inherit;" placeholder="e.g., May conflict sa schedule"></textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="hideRescheduleModal()" style="background:#e2e8f0;color:#64748b;border:none;border-radius:8px;padding:10px 20px;font-weight:700;cursor:pointer;">Cancel</button>
                <button type="submit" style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border:none;border-radius:8px;padding:10px 20px;font-weight:700;cursor:pointer;">Submit Request</button>
            </div>
        </form>
    </div>
</div>

<script>
function showCancelModal() {
    document.getElementById('cancelModal').style.display = 'flex';
}
function hideCancelModal() {
    document.getElementById('cancelModal').style.display = 'none';
}
function submitCancel() {
    const reason = document.getElementById('cancelReasonText').value.trim();
    if (!reason) {
        alert('Please provide a reason for cancellation.');
        return;
    }
    document.getElementById('cancelReasonInput').value = reason;
    document.getElementById('cancelForm').submit();
}

function showRescheduleModal() {
    document.getElementById('rescheduleModal').style.display = 'flex';
}
function hideRescheduleModal() {
    document.getElementById('rescheduleModal').style.display = 'none';
}

// Load slots for reschedule
const rescheduleDate = document.getElementById('rescheduleDate');
const rescheduleTime = document.getElementById('rescheduleTime');
const rescheduleSlotMsg = document.getElementById('rescheduleSlotMsg');

if (rescheduleDate) {
    rescheduleDate.addEventListener('change', function() {
        const date = this.value;
        if (!date) return;

        const d = new Date(date + 'T00:00:00');
        if (d.getDay() === 0 || d.getDay() === 6) {
            rescheduleTime.innerHTML = '<option value="">Weekdays only</option>';
            rescheduleTime.disabled = true;
            rescheduleSlotMsg.textContent = 'Please select a weekday (Mon–Fri).';
            rescheduleSlotMsg.style.color = '#dc3545';
            return;
        }

        rescheduleTime.disabled = true;
        rescheduleTime.innerHTML = '<option value="">Loading slots…</option>';
        rescheduleSlotMsg.textContent = '';

        fetch(`/user/appointments/slots?date=${date}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(slots => {
            rescheduleTime.innerHTML = '<option value="">Choose a time</option>';
            let available = 0;
            slots.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.time;
                if (s.full) {
                    opt.textContent = `${s.label}  — FULL`;
                    opt.disabled = true;
                    opt.style.color = '#9ca3af';
                } else {
                    opt.textContent = `${s.label}  (${s.remaining} slot${s.remaining !== 1 ? 's' : ''} left)`;
                    available++;
                }
                rescheduleTime.appendChild(opt);
            });
            rescheduleTime.disabled = false;
            rescheduleSlotMsg.textContent = available > 0
                ? `${available} time slot${available > 1 ? 's' : ''} available`
                : 'No slots available on this date. Please pick another day.';
            rescheduleSlotMsg.style.color = available > 0 ? '#16a34a' : '#dc3545';
        })
        .catch(() => {
            rescheduleTime.innerHTML = '<option value="">Error loading slots</option>';
            rescheduleSlotMsg.textContent = 'Failed to load slots. Please try again.';
        });
    });
}
</script>
