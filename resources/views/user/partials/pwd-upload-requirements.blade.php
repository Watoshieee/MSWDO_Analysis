@php
    $uploadPrefix = $uploadPrefix ?? 'mon';
    $uploadCols = $uploadCols ?? 'col-md-6';
    $hideProgress = $hideProgress ?? false;
    $totalR    = count($pwdRequirements ?? []);
    $approvedR = ($uploadedFiles ?? collect())->where('status','approved')->count();
    $pctR      = $totalR > 0 ? round(($approvedR / $totalR) * 100) : 0;
@endphp

@if(!$hideProgress && isset($application) && $application)
<div style="margin-bottom:16px;">
    <div style="height:6px;background:#dbe4ff;border-radius:3px;overflow:hidden;">
        <div style="width:{{ $pctR }}%;height:100%;background:var(--secondary-yellow);border-radius:3px;transition:width .4s;"></div>
    </div>
    <div style="font-size:.72rem;color:#64748b;margin-top:4px;">{{ $approvedR }}/{{ $totalR }} approved — {{ $pctR }}% complete</div>
</div>
@endif

<div class="row g-3">
@foreach($pwdRequirements as $reqName)
@php
    $uf      = ($uploadedFiles ?? collect())->firstWhere('requirement_name', $reqName);
    $fStatus = $uf?->status ?? 'not_uploaded';
    $cls     = match($fStatus) {
        'approved'  => 'approved',
        'rejected'  => 'rejected',
        'in_review' => 'in_review',
        'pending'   => 'pending',
        default     => '',
    };
    $badge = match($fStatus) {
        'approved'  => '<span style="background:#d4edda;color:#155724;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;">Approved</span>',
        'rejected'  => '<span style="background:#f8d7da;color:#721c24;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;">Rejected</span>',
        'in_review' => '<span style="background:#d1ecf1;color:#0c5460;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;">In Review</span>',
        'pending'   => '<span style="background:#FFF3D6;color:#856404;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;">Pending</span>',
        default     => '<span style="background:#e9ecef;color:#6c757d;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;">Not uploaded</span>',
    };
@endphp
<div class="{{ $uploadCols }}">
    <div class="pwd-req {{ $cls }}">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:6px;">
            <div style="flex:1;">
                <div class="pwd-req-name">{{ $reqName }}</div>
                @if($uf && $uf->uploaded_at)
                <div style="font-size:.7rem;color:#94a3b8;margin-top:1px;">{{ \Carbon\Carbon::parse($uf->uploaded_at)->format('M j, Y') }}</div>
                @endif
            </div>
            <div style="display:flex;align-items:center;gap:5px;flex-shrink:0;">
                {!! $badge !!}
                @if($uf && $uf->file_path)
                @php $ext = strtolower(pathinfo($uf->file_path, PATHINFO_EXTENSION)); @endphp
                @if(in_array($ext,['jpg','jpeg','png','webp']))
                <img src="{{ asset('storage/'.$uf->file_path) }}"
                     onclick="openFileModal('{{ asset('storage/'.$uf->file_path) }}', '{{ addslashes($reqName) }}', '{{ $ext }}')"
                     class="pwd-thumb" alt="">
                @endif
                <button type="button" onclick="openFileModal('{{ asset('storage/'.$uf->file_path) }}', '{{ addslashes($reqName) }}', '{{ $ext }}')" class="pwd-view">View</button>
                @endif
            </div>
        </div>

        @if($uf && $uf->admin_remarks)
        <div class="pwd-remark"><strong>Note:</strong> {{ $uf->admin_remarks }}</div>
        @endif

        @if(!$uf || $fStatus === 'rejected')
        <div class="pwd-upload-box">
            <div style="font-size:.75rem;font-weight:600;color:#856404;margin-bottom:6px;">
                {{ $fStatus === 'rejected' ? 'Choose replacement file' : 'Choose file to upload' }}
            </div>
            <input type="file"
                   class="req-file form-control form-control-sm"
                   data-req="{{ $reqName }}"
                   accept=".jpg,.jpeg,.png,.pdf"
                                               onchange="validateFileSize(this); onFileChosen('{{ $uploadPrefix }}');">
            <div style="font-size:.64rem;color:#94a3b8;margin-top:3px;">Images: 5MB max · PDF: 25MB max</div>
        </div>
        @endif
    </div>
</div>
@endforeach
</div>

<div id="inline-upload-wrap-{{ $uploadPrefix }}" style="display:none;margin-top:18px;background:#EEF2FF;border:1.5px solid #A0B6E8;border-radius:12px;padding:14px 18px;">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;">
        <div style="flex:1;min-width:0;">
            <div id="inline-label-{{ $uploadPrefix }}" style="font-weight:800;color:#2C3E8F;font-size:.88rem;">0 files selected</div>
            <div style="margin-top:6px;height:5px;background:#dbe4ff;border-radius:3px;overflow:hidden;">
                <div id="inline-bar-{{ $uploadPrefix }}" style="height:100%;width:0%;background:#2C3E8F;border-radius:3px;transition:width .3s;"></div>
            </div>
            <div id="inline-status-{{ $uploadPrefix }}" style="font-size:.72rem;color:#64748b;margin-top:3px;"></div>
        </div>
        <button type="button" id="inline-btn-{{ $uploadPrefix }}" onclick="uploadAll('{{ $uploadPrefix }}')"
                style="background:#F6C90E;color:#1a2e8a;border:none;border-radius:9px;padding:9px 24px;font-weight:900;font-size:.85rem;cursor:pointer;white-space:nowrap;flex-shrink:0;box-shadow:0 3px 10px rgba(246,201,14,.35);">
            Upload All Selected
        </button>
    </div>
</div>

<div style="font-size:.8rem;color:#6c757d;margin-top:12px;">
    Track all your uploaded documents in
    <a href="{{ route('user.my-requirements') }}" style="color:#2C3E8F;font-weight:600;">My Requirements</a>
</div>
