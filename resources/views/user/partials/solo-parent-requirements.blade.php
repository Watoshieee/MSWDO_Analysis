@if((isset($appointment) && $appointment && $appointment->status === 'validated' && isset($soloParentApplication) && $soloParentApplication) || (isset($soloParentApplication) && $soloParentApplication && $soloParentApplication->category_code))
@php
    $fm = $soloParentApplication->fileMonitoring;
    $uploads = $fm ? $fm->fileUploads : collect();
    $overallStatus = $fm ? $fm->overall_status : 'pending';

    $catCode = $soloParentApplication->category_code;
    $categoryTitle = $catCode ? (\App\Services\SoloParentCategoryService::getCategories()[$catCode] ?? $catCode) : null;
    $categorized = \App\Services\SoloParentCategoryService::getCategorizedRequirementsForApplication($soloParentApplication);
    $requirementGroups = $categorized['primary'] ?? [];
    $benefitGroups = $categorized['benefit'] ?? [];

    $uploadedByName = $uploads->keyBy('requirement_name');
    $reqPrefix = $reqPrefix ?? 'req';
    $uploadAction = route('applications.requirement.upload', $soloParentApplication->id);

    // Count total required and total uploaded
    $totalPrimary = count($requirementGroups);
    $totalBenefit = count($benefitGroups);
    $totalRequirements = $totalPrimary + $totalBenefit;

    $uploadedCount = 0;
    foreach ($requirementGroups as $g) {
        foreach ($g['options'] as $opt) {
            if ($u = $uploadedByName->get($opt['requirement_name'])) {
                if (in_array($u->status, ['approved', 'pending', 'in_review'])) {
                    $uploadedCount++;
                    break;
                }
            }
        }
    }
    foreach ($benefitGroups as $g) {
        foreach ($g['options'] as $opt) {
            if ($u = $uploadedByName->get($opt['requirement_name'])) {
                if (in_array($u->status, ['approved', 'pending', 'in_review'])) {
                    $uploadedCount++;
                    break;
                }
            }
        }
    }
    $initialPct = $totalRequirements > 0 ? round(($uploadedCount / $totalRequirements) * 100) : 0;
@endphp

<div class="sp-eligible-banner mb-3">
    <div class="sp-eligible-title">Congratulations! You passed the eligibility assessment.</div>
    <div class="sp-eligible-sub">Please upload all required documents below to complete your Solo Parent ID application.</div>
</div>

@if($categoryTitle)
<div style="font-size:0.9rem;font-weight:700;color:var(--navy);margin-bottom:14px;background:#eef3ff;border:1px solid #c7d2fe;border-radius:10px;padding:10px 14px;">
    <strong>Assigned Category:</strong> Category {{ $catCode }} &mdash; {{ $categoryTitle }}
</div>
@endif

@if($overallStatus === 'approved')
<div class="sp-status-msg approved mb-3">All your documents have been approved! Your Solo Parent ID is being processed.</div>
@elseif($overallStatus === 'rejected')
<div class="sp-status-msg rejected mb-3">Some documents need attention. Please resubmit the declined documents below.</div>
@elseif($overallStatus === 'in_review')
<div class="sp-status-msg review mb-3">Your documents are currently under review. We will notify you of the results.</div>
@endif

{{-- REDESIGNED UPLOAD ALL INTERFACE --}}
<div class="up" id="upContainer-{{ $reqPrefix }}" data-upload-url="{{ $uploadAction }}" data-total="{{ $totalRequirements }}">

    {{-- 1. Drag & Drop Bulk Zone --}}
    <label class="up-drop" id="bulkDropZone-{{ $reqPrefix }}" for="bulkFileInput-{{ $reqPrefix }}">
        <svg class="i" style="width:42px;height:42px;color:var(--navy);"><use href="#i-cloud"/></svg>
        <b>Drag and drop all your documents here</b>
        <small>Files fill the empty rows in order. You can replace any file afterwards.</small>
        <span class="up-pick">Select multiple files</span>
        <input type="file" multiple accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf" class="sr" id="bulkFileInput-{{ $reqPrefix }}">
    </label>

    <div id="bulkErrorMsg-{{ $reqPrefix }}" class="sp-status-msg rejected" style="display:none;margin:4px 0 0;" role="alert"></div>

    {{-- 2. Summary Progress Bar and Upload All Button --}}
    <div class="up-bar">
        <div class="up-sum">
            <p class="up-count" id="upCount-{{ $reqPrefix }}">
                {{ $uploadedCount }} of {{ $totalRequirements }} documents added
                @if($uploadedCount > 0) · {{ $uploadedCount }} uploaded @endif
            </p>
            <div class="up-meter">
                <i id="upMeterFill-{{ $reqPrefix }}" style="width: {{ $initialPct }}%;"></i>
            </div>
        </div>
        <div class="up-btns">
            <button type="button" class="btn btn-navy" id="uploadAllBtn-{{ $reqPrefix }}" onclick="triggerUploadAll('{{ $reqPrefix }}')" disabled>
                <svg class="i i-sm"><use href="#i-cloud"/></svg>
                <span class="up-btn-text">Upload all</span>
            </button>
        </div>
    </div>

    {{-- 3. Document Requirements List --}}
    <ul class="up-list" id="upList-{{ $reqPrefix }}">
        @foreach($requirementGroups as $index => $group)
            @php
                $isOr = !empty($group['is_or_group']);
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

                $state = match($status) {
                    'approved', 'pending', 'in_review' => 'uploaded',
                    'rejected' => 'error',
                    default => 'empty',
                };
                $pillClass = match($status) {
                    'approved' => 'ok',
                    'pending', 'in_review' => 'work',
                    'rejected' => 'bad',
                    default => '',
                };
                $pillText = match($status) {
                    'approved' => 'Approved',
                    'pending' => 'Pending Review',
                    'in_review' => 'In Review',
                    'rejected' => 'Action Needed',
                    default => 'Not added',
                };

                $ext = $hasFile ? strtolower(pathinfo($primaryUpload->file_path, PATHINFO_EXTENSION)) : '';
                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']);
                $fileUrl = $hasFile ? route('user.serve-file', $primaryUpload->id) : '';
            @endphp
            <li class="up-row" data-s="{{ $state }}" data-status="{{ $status }}" data-req-name="{{ $activeReqName }}" id="row-{{ $reqPrefix }}-pri-{{ $index }}">
                {{-- Thumbnail --}}
                <div class="up-th">
                    @if($hasFile && $isImage)
                        <img src="{{ $fileUrl }}" alt="" onclick="openSpFileModal('{{ $fileUrl }}', '{{ addslashes($primaryUpload->requirement_name) }}', '{{ $ext }}')">
                    @elseif($status === 'approved' || $status === 'pending' || $status === 'in_review')
                        <svg class="i"><use href="#i-check"/></svg>
                    @else
                        <svg class="i"><use href="#i-file"/></svg>
                    @endif
                </div>

                {{-- Main Info --}}
                <div class="up-main">
                    <div class="up-name">
                        <span>{{ $group['group_title'] }}</span>
                        <span class="up-req">REQUIRED</span>
                    </div>

                    @if($isOr && !$primaryUpload)
                        <div class="mt-1 mb-1">
                            <select class="form-select form-select-sm up-or-select" style="max-width:340px;font-size:13px;" onchange="updateRowReqName(this)">
                                @foreach($group['options'] as $opt)
                                    <option value="{{ $opt['requirement_name'] }}" {{ $opt['requirement_name'] === $activeReqName ? 'selected' : '' }}>
                                        {{ $opt['requirement_name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @elseif($isOr && $primaryUpload)
                        <span class="text-muted small fw-normal d-block" style="font-size:12.5px;">Submitted: {{ $primaryUpload->requirement_name }}</span>
                    @endif

                    <span class="up-meta">
                        @if($primaryUpload)
                            {{ $primaryUpload->file_name ?? 'Document uploaded' }} · {{ \Carbon\Carbon::parse($primaryUpload->uploaded_at ?? $primaryUpload->updated_at)->format('M d, Y') }}
                        @else
                            JPG, PNG or PDF · max 5MB
                        @endif
                    </span>

                    @if($primaryUpload && $primaryUpload->admin_remarks)
                        <p class="up-err"><strong>Remark:</strong> {{ $primaryUpload->admin_remarks }}</p>
                    @endif

                    <div class="up-prog" style="display:none;"><i style="width: 0%;"></i></div>
                    <p class="up-err inline-err" style="display:none;"></p>
                </div>

                {{-- Action buttons --}}
                <div class="up-act">
                    <span class="up-st {{ $pillClass }}">{{ $pillText }}</span>

                    @if($hasFile)
                        <button type="button" class="sbtn" onclick="openSpFileModal('{{ $fileUrl }}', '{{ addslashes($primaryUpload->requirement_name) }}', '{{ $ext }}')">
                            View
                        </button>
                    @endif

                    {{-- Staged specific upload & remove buttons --}}
                    <button type="button" class="sbtn pri up-go" style="display:none;" onclick="uploadSingleRow(this)">Upload</button>
                    <button type="button" class="sbtn ico up-remove" style="display:none;" onclick="clearRowFile(this)" aria-label="Remove file">
                        <svg class="i i-sm"><use href="#i-x"/></svg>
                    </button>

                    {{-- File picker label button --}}
                    <label class="sbtn {{ $state === 'empty' || $state === 'error' ? 'pri' : '' }} chooser-btn">
                        <span class="chooser-label">
                            @if($state === 'uploaded') Replace
                            @elseif($state === 'error') Re-upload
                            @else Choose file
                            @endif
                        </span>
                        <input type="file" accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf" class="sr row-file-input" onchange="handleRowFileSelect(this)">
                    </label>
                </div>
            </li>
        @endforeach

        {{-- Benefit Track Requirements if present --}}
        @if(!empty($benefitGroups))
            <li class="pt-3 pb-1" style="list-style:none;">
                <div style="font-size:0.92rem;font-weight:800;color:var(--navy);display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
                    <span class="badge bg-success" style="font-size:0.75rem;background:#15803d !important;padding:4px 8px;">BENEFIT TRACK</span>
                    <span>SOLO PARENT AVAILING SUBSIDY &amp; DISCOUNT &mdash; CODE 1,2</span>
                </div>
                <div style="font-size:0.8rem;color:var(--muted);margin-top:2px;line-height:1.45;">
                    Additional requirements for Solo Parent 10% discount and monthly cash subsidy under RA 11861.
                </div>
            </li>

            @foreach($benefitGroups as $bIndex => $group)
                @php
                    $isOr = !empty($group['is_or_group']);
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

                    $state = match($status) {
                        'approved', 'pending', 'in_review' => 'uploaded',
                        'rejected' => 'error',
                        default => 'empty',
                    };
                    $pillClass = match($status) {
                        'approved' => 'ok',
                        'pending', 'in_review' => 'work',
                        'rejected' => 'bad',
                        default => '',
                    };
                    $pillText = match($status) {
                        'approved' => 'Approved',
                        'pending' => 'Pending Review',
                        'in_review' => 'In Review',
                        'rejected' => 'Action Needed',
                        default => 'Not added',
                    };

                    $ext = $hasFile ? strtolower(pathinfo($primaryUpload->file_path, PATHINFO_EXTENSION)) : '';
                    $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']);
                    $fileUrl = $hasFile ? route('user.serve-file', $primaryUpload->id) : '';
                @endphp
                <li class="up-row" data-s="{{ $state }}" data-status="{{ $status }}" data-req-name="{{ $activeReqName }}" id="row-{{ $reqPrefix }}-ben-{{ $bIndex }}">
                    {{-- Thumbnail --}}
                    <div class="up-th">
                        @if($hasFile && $isImage)
                            <img src="{{ $fileUrl }}" alt="" onclick="openSpFileModal('{{ $fileUrl }}', '{{ addslashes($primaryUpload->requirement_name) }}', '{{ $ext }}')">
                        @elseif($status === 'approved' || $status === 'pending' || $status === 'in_review')
                            <svg class="i"><use href="#i-check"/></svg>
                        @else
                            <svg class="i"><use href="#i-file"/></svg>
                        @endif
                    </div>

                    {{-- Main Info --}}
                    <div class="up-main">
                        <div class="up-name">
                            <span>{{ $group['group_title'] }}</span>
                            <span class="up-req">REQUIRED</span>
                        </div>

                        @if($isOr && !$primaryUpload)
                            <div class="mt-1 mb-1">
                                <select class="form-select form-select-sm up-or-select" style="max-width:340px;font-size:13px;" onchange="updateRowReqName(this)">
                                    @foreach($group['options'] as $opt)
                                        <option value="{{ $opt['requirement_name'] }}" {{ $opt['requirement_name'] === $activeReqName ? 'selected' : '' }}>
                                            {{ $opt['requirement_name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @elseif($isOr && $primaryUpload)
                            <span class="text-muted small fw-normal d-block" style="font-size:12.5px;">Submitted: {{ $primaryUpload->requirement_name }}</span>
                        @endif

                        <span class="up-meta">
                            @if($primaryUpload)
                                {{ $primaryUpload->file_name ?? 'Document uploaded' }} · {{ \Carbon\Carbon::parse($primaryUpload->uploaded_at ?? $primaryUpload->updated_at)->format('M d, Y') }}
                            @else
                                JPG, PNG or PDF · max 5MB
                            @endif
                        </span>

                        @if($primaryUpload && $primaryUpload->admin_remarks)
                            <p class="up-err"><strong>Remark:</strong> {{ $primaryUpload->admin_remarks }}</p>
                        @endif

                        <div class="up-prog" style="display:none;"><i style="width: 0%;"></i></div>
                        <p class="up-err inline-err" style="display:none;"></p>
                    </div>

                    {{-- Action buttons --}}
                    <div class="up-act">
                        <span class="up-st {{ $pillClass }}">{{ $pillText }}</span>

                        @if($hasFile)
                            <button type="button" class="sbtn" onclick="openSpFileModal('{{ $fileUrl }}', '{{ addslashes($primaryUpload->requirement_name) }}', '{{ $ext }}')">
                                View
                            </button>
                        @endif

                        {{-- Staged specific upload & remove buttons --}}
                        <button type="button" class="sbtn pri up-go" style="display:none;" onclick="uploadSingleRow(this)">Upload</button>
                        <button type="button" class="sbtn ico up-remove" style="display:none;" onclick="clearRowFile(this)" aria-label="Remove file">
                            <svg class="i i-sm"><use href="#i-x"/></svg>
                        </button>

                        {{-- File picker label button --}}
                        <label class="sbtn {{ $state === 'empty' || $state === 'error' ? 'pri' : '' }} chooser-btn">
                            <span class="chooser-label">
                                @if($state === 'uploaded') Replace
                                @elseif($state === 'error') Re-upload
                                @else Choose file
                                @endif
                            </span>
                            <input type="file" accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf" class="sr row-file-input" onchange="handleRowFileSelect(this)">
                        </label>
                    </div>
                </li>
            @endforeach
        @endif
    </ul>

    {{-- 4. Tip Box --}}
    <p class="up-tip mt-2">
        <b>Tip: </b>Upload clear, readable scanned copies or photos. Accepted formats: PDF, JPG, PNG. Max size: 5MB per file.
    </p>

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
