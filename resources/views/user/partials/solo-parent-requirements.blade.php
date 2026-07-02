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
    @php $uploaded = $uploadedByName->get($req); @endphp
    <div class="{{ $uploadCols }}">
        <div class="solo-req-row">
            <div class="solo-req-info">
                <div class="solo-req-name">{{ $req }}</div>
                @if($uploaded && $uploaded->admin_remarks)
                <div class="solo-req-remark">Remark: {{ $uploaded->admin_remarks }}</div>
                @endif
            </div>
            @if($uploaded && in_array($uploaded->status, ['pending','approved']))
                <span class="sp-badge uploaded">Uploaded</span>
            @elseif($uploaded && $uploaded->status === 'rejected')
                <form action="{{ route('applications.requirement.upload', $soloParentApplication->id) }}" method="POST" enctype="multipart/form-data" data-upload-type="single" class="solo-upload-form">
                    @csrf
                    <input type="hidden" name="requirement_name" value="{{ $req }}">
                    <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required class="solo-file-input">
                    <button type="submit" class="sp-btn sp-btn-sm">Resubmit</button>
                </form>
            @else
                <form action="{{ route('applications.requirement.upload', $soloParentApplication->id) }}" method="POST" enctype="multipart/form-data" data-upload-type="single" class="solo-upload-form">
                    @csrf
                    <input type="hidden" name="requirement_name" value="{{ $req }}">
                    <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required class="solo-file-input">
                    <button type="submit" class="sp-btn sp-btn-sm sp-btn-primary">Upload</button>
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
