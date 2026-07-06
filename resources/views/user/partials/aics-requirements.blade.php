@php
    $reqPrefix = $reqPrefix ?? 'req';
    $uploadCols = $uploadCols ?? 'col-md-6';
    $isValidated = isset($appointment) && $appointment && $appointment->status === 'validated';
@endphp

@if($isValidated)
<div id="req-scope-{{ $reqPrefix }}">
<div class="aics-req-note">
    <strong>Note:</strong> Prepare <strong>2 copies</strong> of every requirement.
    Upload clear, readable scans or photos. <strong>Images: 5MB max · PDF: 25MB max</strong>
</div>

@php
    $overallStatus = null;
    if (isset($application) && $application) {
        $fm = \App\Models\FileMonitoring::where('application_id', $application->id)->first();
        $overallStatus = $fm?->overall_status;
    }
@endphp
@if($overallStatus === 'approved')
<div class="aics-status-msg approved">All your documents have been approved! Your assistance is being processed.</div>
@elseif($overallStatus === 'rejected')
<div class="aics-status-msg rejected">Some documents need attention. Please resubmit the declined documents below.</div>
@elseif($overallStatus === 'in_review')
<div class="aics-status-msg review">Your documents are currently under review. We will notify you of the results.</div>
@endif

<form id="aicsBatch-{{ $reqPrefix }}" action="{{ $aicsBatchRoute }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
    @foreach($requirements as $reqName)
    @php
        $uf = $uploadedFiles->firstWhere('requirement_name', $reqName);
        $fStatus = $uf?->status ?? 'not_uploaded';
    @endphp
    <div class="{{ $uploadCols }}">
        <div class="aics-req-card">
            <div class="aics-req-head">
                <div>
                    <div class="aics-req-name">{{ $reqName }}</div>
                    @if($uf && $uf->uploaded_at)
                    <div class="aics-req-date">Uploaded {{ \Carbon\Carbon::parse($uf->uploaded_at)->format('M j, Y') }}</div>
                    @endif
                </div>
                <div class="aics-req-badges">
                    @if($fStatus==='approved') <span class="aics-badge approved">✓ Approved</span>
                    @elseif($fStatus==='rejected') <span class="aics-badge rejected">✗ Rejected</span>
                    @elseif($fStatus==='pending') <span class="aics-badge pending">⏳ Pending</span>
                    @elseif($fStatus==='in_review') <span class="aics-badge review">🔍 In Review</span>
                    @else <span class="aics-badge none">○ Not uploaded</span>
                    @endif
                    @if($uf && $uf->file_path)
                    @php $ext = strtolower(pathinfo($uf->file_path, PATHINFO_EXTENSION)); @endphp
                    @if(in_array($ext, ['jpg','jpeg','png','webp']))
                    <img src="{{ asset('storage/'.$uf->file_path) }}" onclick="window.open('{{ asset('storage/'.$uf->file_path) }}')" class="aics-thumb">
                    @endif
                    <a href="{{ asset('storage/'.$uf->file_path) }}" target="_blank" class="aics-view-link">View</a>
                    @endif
                </div>
            </div>
            @if($uf && $uf->admin_remarks)
            <div class="aics-remark"><strong>Note:</strong> {{ $uf->admin_remarks }}</div>
            @endif
            @if($fStatus !== 'approved')
            <div class="aics-upload-area">
                <div class="aics-upload-label">{{ $fStatus==='rejected' ? 'Re-upload document' : 'Choose file to upload' }}</div>
                <input type="file" name="files[{{ $reqName }}]"
                    class="form-control form-control-sm batch-file-input"
                    accept=".jpg,.jpeg,.png,.pdf"
                    onchange="validateAicsFile(this)"
                    data-req="{{ $reqName }}">
                <div class="aics-size-hint">Images: 5MB max · PDF: 25MB max</div>
            </div>
            @endif
        </div>
    </div>
    @endforeach
    </div>
    <div class="aics-upload-all-bar">
        <div>
            <div class="aics-upload-all-title">Upload All Selected Files</div>
            <div class="aics-upload-all-hint">Only files you select will be uploaded</div>
        </div>
        <button type="submit" id="aicsBatchBtn-{{ $reqPrefix }}" class="aics-btn aics-btn-primary">Upload All Selected Files</button>
    </div>
</form>

@if($uploadedFiles->count() > 0)
<hr class="aics-req-divider">
<div class="aics-individual-title">Upload / Re-upload Individually</div>
<div class="row g-3">
@foreach($requirements as $reqName)
@php
    $uf2 = $uploadedFiles->firstWhere('requirement_name', $reqName);
    $fStatus2 = $uf2?->status ?? 'not_uploaded';
@endphp
@if(!$uf2 || $fStatus2 === 'rejected')
<div class="{{ $uploadCols }}">
    <div class="aics-req-card {{ $fStatus2==='rejected' ? 'rejected-border' : '' }}">
        <div class="aics-req-head">
            <div class="aics-req-name">{{ $reqName }}</div>
            @if($fStatus2==='rejected')<span class="aics-badge rejected">✗ Rejected</span>
            @else<span class="aics-badge none">○ Not uploaded</span>@endif
        </div>
        @if($uf2 && $uf2->admin_remarks)
        <div class="aics-remark"><strong>Note:</strong> {{ $uf2->admin_remarks }}</div>
        @endif
        <div class="aics-upload-area">
            <div class="aics-upload-label">{{ $fStatus2==='rejected'?'Re-upload document':'Upload document' }}</div>
            <form action="{{ $aicsSingleRoute }}" method="POST" enctype="multipart/form-data" class="aics-single-form">
                @csrf
                <input type="hidden" name="requirement_name" value="{{ $reqName }}">
                <div class="row g-1 align-items-center mt-1">
                    <div class="col-8">
                        <input type="file" name="file" class="form-control form-control-sm"
                            accept=".jpg,.jpeg,.png,.pdf" onchange="validateAicsFile(this)" required>
                        <div class="aics-size-hint">Images: 5MB · PDF: 25MB</div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="aics-btn aics-btn-sm aics-btn-yellow w-100">
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

<div class="aics-track-link">
    Track all your uploaded documents in
    <a href="{{ route('user.my-requirements') }}">My Requirements →</a>
</div>
</div>

@elseif(isset($appointment) && $appointment && $appointment->status === 'confirmed')
<div class="aics-waiting-box">
    <div class="aics-waiting-title">Eligibility Review In Progress</div>
    <div class="aics-waiting-text">Your appointment has been confirmed. The MSWDO officer will review your eligibility during your interview. Once validated, the requirements list will appear here and you will be notified by email.</div>
</div>

@else
<div class="aics-waiting-box">
    @if(!isset($appointment) || !$appointment)
        <div class="aics-waiting-title" data-en="Requirements List Coming Soon" data-tl="Listahan ng mga Kinakailangan — Malapit na">Requirements List Coming Soon</div>
        <div class="aics-waiting-text" data-en="Book an appointment and complete your interview first. The list of required documents will appear here after eligibility validation." data-tl="Mag-book muna ng appointment at kumpletuhin ang panayam. Lalabas ang listahan ng dokumento pagkatapos ma-validate ang eligibility.">Book an appointment and complete your interview first. The list of required documents will appear here after eligibility validation.</div>
    @elseif($appointment->status === 'pending')
        <div class="aics-waiting-title">Waiting for Appointment Confirmation</div>
        <div class="aics-waiting-text">Your appointment on <strong>{{ $appointment->formatted_date }}</strong> at <strong>{{ $appointment->formatted_time }}</strong> is pending admin confirmation.</div>
    @endif
</div>
@endif
