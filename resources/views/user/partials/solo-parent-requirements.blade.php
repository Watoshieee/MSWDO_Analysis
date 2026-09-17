@if(isset($appointment) && $appointment && $appointment->status === 'validated' && isset($soloParentApplication) && $soloParentApplication)
@php
    $fm = $soloParentApplication->fileMonitoring;
    $uploads = $fm ? $fm->fileUploads : collect();
    $overallStatus = $fm ? $fm->overall_status : 'pending';
    $soloReqs = [
        'PSA Birth Certificate of Child/Children',
        'Barangay Certificate (stating you are a solo parent)',
        'Valid Government-Issued ID',
        'CENOMAR or PSA Marriage Certificate',
        'Death Certificate of Spouse (if widowed) / Police Report (if abandoned)',
        '2x2 ID Photo (recent, white background)',
    ];
    $uploadedByName = $uploads->keyBy('requirement_name');
    $uploadCols = $uploadCols ?? 'col-lg-6';
    $reqPrefix = $reqPrefix ?? 'req';
@endphp

<style>
    /* ── My-Requirements card style reused in Solo Parent ── */
    .sp-req-item {
        padding: 10px 14px; border-radius: 10px;
        background: #F8FAFC; border-left: 4px solid #E2E8F0;
        margin-bottom: 0; height: 100%;
    }
    .sp-req-item.approved  { border-left-color: #28a745; background: #f0fff8; }
    .sp-req-item.rejected  { border-left-color: #dc3545; background: #fff5f5; }
    .sp-req-item.in_review { border-left-color: #17a2b8; background: #f0faff; }
    .sp-req-item.pending   { border-left-color: #FDB913; }
    .sp-req-item.not_uploaded { border-left-color: #E2E8F0; }

    .sp-req-name  { font-weight: 600; font-size: 0.88rem; color: #1E293B; line-height: 1.3; }
    .sp-req-remark { font-size: 0.78rem; color: #dc3545; margin-top: 5px; padding: 5px 8px; background: #fff5f5; border-radius: 6px; }

    .sp-status-badge { padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 700; display: inline-block; }
    .sp-status-approved  { background: #d4edda; color: #155724; }
    .sp-status-rejected  { background: #f8d7da; color: #721c24; }
    .sp-status-pending   { background: #FFF3D6; color: #856404; }
    .sp-status-in_review { background: #d1ecf1; color: #0c5460; }

    .sp-btn-view { background: #2C3E8F; color: white; border: none; padding: 3px 10px; border-radius: 8px; font-size: 0.75rem; font-weight: 600; cursor: pointer; transition: all 0.25s; }
    .sp-btn-view:hover { background: #1A2A5C; color: white; }

    .sp-reupload-form { background: #FFF3D6; border-radius: 8px; padding: 10px 12px; margin-top: 8px; }
</style>

<div class="sp-eligible-banner">
    <div class="sp-eligible-title">Congratulations! You passed the eligibility assessment.</div>
    <div class="sp-eligible-sub">Please upload all required documents below to complete your Solo Parent ID application.</div>
</div>

@if($overallStatus === 'approved')
<div class="sp-status-msg approved">All your documents have been approved! Your Solo Parent ID is being processed.</div>
@elseif($overallStatus === 'rejected')
<div class="sp-status-msg rejected">Some documents need attention. Please resubmit the declined documents below.</div>
@elseif($overallStatus === 'in_review')
<div class="sp-status-msg review">Your documents are currently under review. We will notify you of the results.</div>
@endif

<div id="req-scope-{{ $reqPrefix }}">
<div class="row g-3 mb-3">
@foreach($soloReqs as $req)
    @php
        $uploaded = $uploadedByName->get($req);
        $status   = $uploaded ? $uploaded->status : 'not_uploaded';
        $hasFile  = $uploaded && $uploaded->file_path;
    @endphp
    <div class="{{ $uploadCols }}">
        <div class="sp-req-item {{ $status }}" data-req-name="{{ $req }}">

            {{-- Top row: name + badge + view --}}
            <div class="d-flex align-items-start gap-2">
                <div style="flex:1;min-width:0;">
                    <div class="sp-req-name">{{ $req }}</div>
                    @if($uploaded && $uploaded->admin_remarks)
                        <div class="sp-req-remark"><strong>Remark:</strong> {{ $uploaded->admin_remarks }}</div>
                    @endif
                </div>
                <div class="d-flex align-items-center gap-1 flex-shrink-0">
                    @if($uploaded)
                        <span class="sp-status-badge sp-status-{{ $status }}">{{ ucfirst($status) }}</span>
                    @endif
                    @if($hasFile)
                        @php
                            $ext     = strtolower(pathinfo($uploaded->file_path, PATHINFO_EXTENSION));
                            $fileUrl = route('user.serve-file', $uploaded->id);
                        @endphp
                        @if(in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                            <img src="{{ $fileUrl }}"
                                 onclick="openSpFileModal('{{ $fileUrl }}', '{{ addslashes($req) }}', '{{ $ext }}')"
                                 style="width:36px;height:36px;object-fit:cover;border-radius:6px;cursor:pointer;vertical-align:middle;" alt="">
                        @endif
                    @endif
                </div>
            </div>

            {{-- Upload / Re-upload form --}}
            @if($status === 'rejected')
                <div class="sp-reupload-form">
                    <p style="font-size:0.78rem;font-weight:600;margin-bottom:8px;color:#856404;">Re-upload Document</p>
                    <form action="{{ route('applications.requirement.upload', $soloParentApplication->id) }}"
                          method="POST" enctype="multipart/form-data"
                          data-upload-type="single" class="solo-upload-form js-ajax-upload">
                        @csrf
                        <input type="hidden" name="requirement_name" value="{{ $req }}">
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            <div style="flex:1;min-width:0;">
                                <input type="file" name="file" class="form-control form-control-sm"
                                       accept=".jpg,.jpeg,.png,.pdf" required>
                                <small class="text-muted" style="font-size:0.7rem;">Images: 5MB, PDF: 25MB</small>
                            </div>
                            <button type="submit" class="btn btn-sm btn-warning" style="font-weight:600;white-space:nowrap;">Re-upload</button>
                        </div>
                    </form>
                </div>
            @elseif($status === 'not_uploaded')
                <form action="{{ route('applications.requirement.upload', $soloParentApplication->id) }}"
                      method="POST" enctype="multipart/form-data"
                      data-upload-type="single" class="solo-upload-form js-ajax-upload"
                      style="margin-top:8px;">
                    @csrf
                    <input type="hidden" name="requirement_name" value="{{ $req }}">
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <div style="flex:1;min-width:0;">
                            <input type="file" name="file" class="form-control form-control-sm"
                                   accept=".jpg,.jpeg,.png,.pdf" required>
                            <small class="text-muted" style="font-size:0.7rem;">Images: 5MB, PDF: 5MB</small>
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary" style="font-weight:600;white-space:nowrap;background:#2C3E8F;border:none;">Upload</button>
                    </div>
                </form>
            @endif

        </div>
    </div>
@endforeach
</div>

<div id="uploadAllBar-{{ $reqPrefix }}" class="sp-upload-all-bar">
    <div>
        <div class="sp-upload-all-title">Upload All Selected Files</div>
        <div id="uploadAllStatus-{{ $reqPrefix }}" class="sp-upload-all-hint">Select files in the rows above, then click Upload All to submit them all at once.</div>
    </div>
    <button type="button" id="uploadAllBtn-{{ $reqPrefix }}" onclick="uploadAllFiles('{{ $reqPrefix }}')" class="sp-btn sp-btn-primary">Upload All</button>
</div>

<div class="sp-tip">
    <strong>Tip:</strong> Upload clear, readable scanned copies or photos. Accepted formats: PDF, JPG, PNG. Max size: 5MB per file.
</div>
</div>

@elseif(isset($appointment) && $appointment && $appointment->status === 'confirmed')
<div class="sp-waiting-box">
    <div class="sp-waiting-title">Eligibility Review In Progress</div>
    <div class="sp-waiting-text">Your appointment has been confirmed. The MSWDO officer will review your eligibility during your interview. Once validated, the requirements list will appear here and you will be notified by email.</div>
</div>

@else
<div class="info-card placeholder" style="padding:28px 22px;text-align:center;">
    <div class="ic-title" style="color:#6c757d;" data-en="Requirements List Coming Soon" data-tl="Listahan ng mga Kinakailangan — Malapit na">Requirements List Coming Soon</div>
    <div class="ic-body" style="color:#94a3b8;" data-en="The list of required documents will appear here after your interview and eligibility assessment. Book an appointment first to get started." data-tl="Ang listahan ng mga kinakailangang dokumento ay lalabas dito pagkatapos ng iyong panayam at eligibility assessment. Mag-book muna ng appointment para magsimula.">
        The list of required documents will appear here after your interview and eligibility assessment. Book an appointment first to get started.
    </div>
</div>
@endif
