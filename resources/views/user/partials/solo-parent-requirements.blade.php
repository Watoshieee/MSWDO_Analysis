@if(isset($appointment) && $appointment && $appointment->status === 'validated' && isset($soloParentApplication) && $soloParentApplication)
@php
    $fm = $soloParentApplication->fileMonitoring;
    $uploads = $fm ? $fm->fileUploads : collect();
    $overallStatus = $fm ? $fm->overall_status : 'pending';

    $catCode = $soloParentApplication->category_code;
    $categoryTitle = $catCode ? (\App\Services\SoloParentCategoryService::getCategories()[$catCode] ?? $catCode) : null;
    $categorized = \App\Services\SoloParentCategoryService::getCategorizedRequirementsForApplication($soloParentApplication);
    $requirementGroups = $categorized['primary'];
    $benefitGroups = $categorized['benefit'];

    $uploadedByName = $uploads->keyBy('requirement_name');
    $uploadCols = $uploadCols ?? 'col-lg-6';
    $reqPrefix = $reqPrefix ?? 'req';
@endphp

<style>
    .sp-eligible-banner { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; }
    .sp-eligible-title  { font-weight: 700; font-size: 0.95rem; color: #15803d; }
    .sp-eligible-sub    { font-size: 0.82rem; color: #4b5563; margin-top: 3px; }

    .sp-status-msg { border-radius: 10px; padding: 12px 18px; margin-bottom: 16px; font-size: 0.85rem; font-weight: 600; }
    .sp-status-msg.approved { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .sp-status-msg.rejected { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    .sp-status-msg.review   { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }

    .sp-req-item { background: white; border: 1px solid #E2E8F0; border-radius: 12px; padding: 14px 16px; border-left: 4px solid #CBD5E1; transition: all 0.2s; }
    .sp-req-item.approved { border-left-color: #28a745; }
    .sp-req-item.rejected { border-left-color: #dc3545; }
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

    .sp-upload-all-bar { display: none; background: #EEF2FF; border: 1.5px solid #C7D2FE; border-radius: 12px; padding: 14px 18px; margin-top: 8px; margin-bottom: 16px; align-items: center; justify-content: space-between; gap: 12px; }
    .sp-upload-all-title { font-weight: 700; font-size: 0.92rem; color: #1E293B; }
    .sp-upload-all-hint  { font-size: 0.78rem; color: #64748B; margin-top: 2px; }

    .sp-tip { font-size: 0.78rem; color: #64748b; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 8px; padding: 10px 14px; margin-top: 4px; }
    .sp-waiting-box { background: white; border: 1px solid #E2E8F0; border-radius: 14px; padding: 24px; text-align: center; }
    .sp-waiting-title { font-weight: 700; font-size: 1rem; color: #1E293B; margin-bottom: 6px; }
    .sp-waiting-text  { font-size: 0.85rem; color: #64748B; max-width: 520px; margin: 0 auto; line-height: 1.5; }
</style>

<div class="sp-eligible-banner">
    <div class="sp-eligible-title">Congratulations! You passed the eligibility assessment.</div>
    <div class="sp-eligible-sub">Please upload all required documents below to complete your Solo Parent ID application.</div>
</div>

@if($categoryTitle)
<div style="font-size:0.85rem;font-weight:600;color:#1E293B;margin-bottom:14px;">
    <strong>Assigned Category:</strong> Category {{ $catCode }} &mdash; {{ $categoryTitle }}
</div>
@endif

@if($overallStatus === 'approved')
<div class="sp-status-msg approved">All your documents have been approved! Your Solo Parent ID is being processed.</div>
@elseif($overallStatus === 'rejected')
<div class="sp-status-msg rejected">Some documents need attention. Please resubmit the declined documents below.</div>
@elseif($overallStatus === 'in_review')
<div class="sp-status-msg review">Your documents are currently under review. We will notify you of the results.</div>
@endif

<div id="req-scope-{{ $reqPrefix }}">
<div class="row g-3 mb-3">
@foreach($requirementGroups as $group)
    @php
        $isOr = $group['is_or_group'];
        $groupUploads = collect();
        foreach($group['options'] as $opt) {
            if ($u = $uploadedByName->get($opt['requirement_name'])) {
                $groupUploads->push($u);
            }
        }
        $primaryUpload = $groupUploads->first();
        $status = $primaryUpload ? $primaryUpload->status : 'not_uploaded';
        $hasFile = $primaryUpload && $primaryUpload->file_path;
        $activeReqName = $primaryUpload ? $primaryUpload->requirement_name : $group['options'][0]['requirement_name'];
    @endphp
    <div class="{{ $uploadCols }}">
        <div class="sp-req-item {{ $status }}" data-req-name="{{ $activeReqName }}">

            {{-- Top row: name + badge + view --}}
            <div class="d-flex align-items-start gap-2">
                <div style="flex:1;min-width:0;">
                    <div class="sp-req-name">
                        {{ $group['group_title'] }}
                        @if($isOr && $primaryUpload)
                            <span class="text-muted small fw-normal d-block">Submitted: {{ $primaryUpload->requirement_name }}</span>
                        @endif
                    </div>
                    @if($primaryUpload && $primaryUpload->admin_remarks)
                        <div class="sp-req-remark"><strong>Remark:</strong> {{ $primaryUpload->admin_remarks }}</div>
                    @endif
                </div>
                <div class="d-flex align-items-center gap-1 flex-shrink-0">
                    @if($primaryUpload)
                        <span class="sp-status-badge sp-status-{{ $status }}">{{ ucfirst($status) }}</span>
                    @endif
                    @if($hasFile)
                        @php
                            $ext     = strtolower(pathinfo($primaryUpload->file_path, PATHINFO_EXTENSION));
                            $fileUrl = route('user.serve-file', $primaryUpload->id);
                        @endphp
                        @if(in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                            <img src="{{ $fileUrl }}"
                                 onclick="openSpFileModal('{{ $fileUrl }}', '{{ addslashes($primaryUpload->requirement_name) }}', '{{ $ext }}')"
                                 style="width:36px;height:36px;object-fit:cover;border-radius:6px;cursor:pointer;vertical-align:middle;" alt="">
                        @else
                            <button type="button" class="sp-btn-view"
                                    onclick="openSpFileModal('{{ $fileUrl }}', '{{ addslashes($primaryUpload->requirement_name) }}', '{{ $ext }}')">
                                View
                            </button>
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
                        <input type="hidden" name="requirement_name" value="{{ $primaryUpload->requirement_name }}">
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
                @if($isOr)
                    <form action="{{ route('applications.requirement.upload', $soloParentApplication->id) }}"
                          method="POST" enctype="multipart/form-data"
                          data-upload-type="single" class="solo-upload-form js-ajax-upload"
                          style="margin-top:8px;">
                        @csrf
                        <div class="mb-1" style="font-size:0.75rem;color:#64748B;">Select one option:</div>
                        <select name="requirement_name" class="form-select form-select-sm mb-2" required>
                            @foreach($group['options'] as $opt)
                                <option value="{{ $opt['requirement_name'] }}">{{ $opt['requirement_name'] }}</option>
                            @endforeach
                        </select>
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            <div style="flex:1;min-width:0;">
                                <input type="file" name="file" class="form-control form-control-sm"
                                       accept=".jpg,.jpeg,.png,.pdf" required>
                                <small class="text-muted" style="font-size:0.7rem;">Images: 5MB, PDF: 25MB</small>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary" style="font-weight:600;white-space:nowrap;background:#2C3E8F;border:none;">Upload</button>
                        </div>
                    </form>
                @else
                    <form action="{{ route('applications.requirement.upload', $soloParentApplication->id) }}"
                          method="POST" enctype="multipart/form-data"
                          data-upload-type="single" class="solo-upload-form js-ajax-upload"
                          style="margin-top:8px;">
                        @csrf
                        <input type="hidden" name="requirement_name" value="{{ $activeReqName }}">
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            <div style="flex:1;min-width:0;">
                                <input type="file" name="file" class="form-control form-control-sm"
                                       accept=".jpg,.jpeg,.png,.pdf" required>
                                <small class="text-muted" style="font-size:0.7rem;">Images: 5MB, PDF: 25MB</small>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary" style="font-weight:600;white-space:nowrap;background:#2C3E8F;border:none;">Upload</button>
                        </div>
                    </form>
                @endif
            @endif

        </div>
    </div>
@endforeach
</div>

@if(!empty($benefitGroups))
<div class="mt-4 mb-3 pt-3 border-top">
    <div style="font-size:0.92rem;font-weight:800;color:#1E293B;margin-bottom:4px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
        <span class="badge bg-success" style="font-size:0.75rem;background:#15803d !important;padding:4px 8px;">BENEFIT TRACK</span>
        <span>SOLO PARENT AVAILING SUBSIDY &amp; DISCOUNT &mdash; CODE 1,2</span>
    </div>
    <div style="font-size:0.8rem;color:#64748B;margin-bottom:14px;line-height:1.45;">
        Additional requirements for Solo Parent 10% discount and monthly cash subsidy under RA 11861.
    </div>

    <div class="row g-3 mb-3">
    @foreach($benefitGroups as $group)
        @php
            $isOr = $group['is_or_group'];
            $groupUploads = collect();
            foreach($group['options'] as $opt) {
                if ($u = $uploadedByName->get($opt['requirement_name'])) {
                    $groupUploads->push($u);
                }
            }
            $primaryUpload = $groupUploads->first();
            $status = $primaryUpload ? $primaryUpload->status : 'not_uploaded';
            $hasFile = $primaryUpload && $primaryUpload->file_path;
            $activeReqName = $primaryUpload ? $primaryUpload->requirement_name : $group['options'][0]['requirement_name'];
        @endphp
        <div class="{{ $uploadCols }}">
            <div class="sp-req-item {{ $status }}" data-req-name="{{ $activeReqName }}">

                {{-- Top row: name + badge + view --}}
                <div class="d-flex align-items-start gap-2">
                    <div style="flex:1;min-width:0;">
                        <div class="sp-req-name">
                            {{ $group['group_title'] }}
                            @if($isOr && $primaryUpload)
                                <span class="text-muted small fw-normal d-block">Submitted: {{ $primaryUpload->requirement_name }}</span>
                            @endif
                        </div>
                        @if($primaryUpload && $primaryUpload->admin_remarks)
                            <div class="sp-req-remark"><strong>Remark:</strong> {{ $primaryUpload->admin_remarks }}</div>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-1 flex-shrink-0">
                        @if($primaryUpload)
                            <span class="sp-status-badge sp-status-{{ $status }}">{{ ucfirst($status) }}</span>
                        @endif
                        @if($hasFile)
                            @php
                                $ext     = strtolower(pathinfo($primaryUpload->file_path, PATHINFO_EXTENSION));
                                $fileUrl = route('user.serve-file', $primaryUpload->id);
                            @endphp
                            @if(in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                                <img src="{{ $fileUrl }}"
                                     onclick="openSpFileModal('{{ $fileUrl }}', '{{ addslashes($primaryUpload->requirement_name) }}', '{{ $ext }}')"
                                     style="width:36px;height:36px;object-fit:cover;border-radius:6px;cursor:pointer;vertical-align:middle;" alt="">
                            @else
                                <button type="button" class="sp-btn-view"
                                        onclick="openSpFileModal('{{ $fileUrl }}', '{{ addslashes($primaryUpload->requirement_name) }}', '{{ $ext }}')">
                                    View
                                </button>
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
                            <input type="hidden" name="requirement_name" value="{{ $primaryUpload->requirement_name }}">
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
                        <input type="hidden" name="requirement_name" value="{{ $activeReqName }}">
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            <div style="flex:1;min-width:0;">
                                <input type="file" name="file" class="form-control form-control-sm"
                                       accept=".jpg,.jpeg,.png,.pdf" required>
                                <small class="text-muted" style="font-size:0.7rem;">Images: 5MB, PDF: 25MB</small>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary" style="font-weight:600;white-space:nowrap;background:#2C3E8F;border:none;">Upload</button>
                        </div>
                    </form>
                @endif

            </div>
        </div>
    @endforeach
    </div>
</div>
@endif
<div id="uploadAllBar-{{ $reqPrefix }}" class="sp-upload-all-bar">
    <div>
        <div class="sp-upload-all-title">Upload All Selected Files</div>
        <div id="uploadAllStatus-{{ $reqPrefix }}" class="sp-upload-all-hint">Select files in the rows above, then click Upload All to submit them all at once.</div>
    </div>
    <button type="button" id="uploadAllBtn-{{ $reqPrefix }}" onclick="uploadAllFiles('{{ $reqPrefix }}')" class="btn btn-sm btn-primary" style="font-weight:600;white-space:nowrap;background:#2C3E8F;border:none;">Upload All</button>
</div>

<div class="sp-tip">
    <strong>Tip:</strong> Upload clear, readable scanned copies or photos. Accepted formats: PDF, JPG, PNG. Max size: 5MB for photos, 25MB for PDF.
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
