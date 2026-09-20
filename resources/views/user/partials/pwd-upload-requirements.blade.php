@php
    $uploadPrefix = $uploadPrefix ?? 'mon';
    $uploadCols = $uploadCols ?? 'col-md-6';
    $hideProgress = $hideProgress ?? false;
    $totalR = count($pwdRequirements ?? []);
    $approvedR = ($uploadedFiles ?? collect())->where('status', 'approved')->count();
    $pctR = $totalR > 0 ? round(($approvedR / $totalR) * 100) : 0;
@endphp

@if(!$hideProgress && isset($application) && $application)
    <div style="margin-bottom:16px;">
        <div style="height:6px;background:#dbe4ff;border-radius:3px;overflow:hidden;">
            <div
                style="width:{{ $pctR }}%;height:100%;background:var(--secondary-yellow);border-radius:3px;transition:width .4s;">
            </div>
        </div>
        <div style="font-size:.72rem;color:#64748b;margin-top:4px;">{{ $approvedR }}/{{ $totalR }} approved — {{ $pctR }}%
            complete</div>
    </div>
@endif

<div class="row g-3">
    @foreach($pwdRequirements as $reqName)
        @php
            $uf = ($uploadedFiles ?? collect())->firstWhere('requirement_name', $reqName);
            $fStatus = $uf?->status ?? 'not_uploaded';
            $cls = match ($fStatus) {
                'approved' => 'approved',
                'rejected' => 'rejected',
                'in_review' => 'in_review',
                'pending' => 'pending',
                default => '',
            };
            $badge = match ($fStatus) {
                'approved' => '<span style="background:#d4edda;color:#155724;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;">Approved</span>',
                'rejected' => '<span style="background:#f8d7da;color:#721c24;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;">Rejected</span>',
                'in_review' => '<span style="background:#d1ecf1;color:#0c5460;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;">In Review</span>',
                'pending' => '<span style="background:#FFF3D6;color:#856404;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;">Pending</span>',
                default => '<span style="background:#e9ecef;color:#6c757d;border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;">Not uploaded</span>',
            };
        @endphp
        <div class="{{ $uploadCols }}">
            <div class="pwd-req {{ $cls }}" data-req-name="{{ $reqName }}">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:6px;">
                    <div style="flex:1;">
                        <div class="pwd-req-name">{{ $reqName }}</div>
                        @if($uf && $uf->uploaded_at)
                            <div style="font-size:.7rem;color:#94a3b8;margin-top:1px;">
                                {{ \Carbon\Carbon::parse($uf->uploaded_at)->format('M j, Y') }}</div>
                        @endif
                    </div>
                    <div style="display:flex;align-items:center;gap:5px;flex-shrink:0;">
                        {!! $badge !!}
                        @if($uf && $uf->file_path)
                            @php 
                                $ext = strtolower(pathinfo($uf->file_path, PATHINFO_EXTENSION)); 
                                $fileUrl = route('user.serve-file', $uf->id);
                            @endphp
                            @if(in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                                <img src="{{ $fileUrl }}"
                                    onclick="openFileModal('{{ $fileUrl }}', '{{ addslashes($reqName) }}', '{{ $ext }}')"
                                    class="pwd-thumb" alt="">
                            @endif
                        @endif
                    </div>
                </div>

                @if($uf && $uf->admin_remarks)
                    <div class="pwd-remark"><strong>Remark:</strong> {{ $uf->admin_remarks }}</div>
                @endif

                @if(!$uf || $fStatus === 'rejected')
                    <div class="pwd-upload-box">
                        <div style="font-size:.75rem;font-weight:600;color:#856404;margin-bottom:6px;">
                            {{ $fStatus === 'rejected' ? 'Re-upload Document' : 'Choose file to upload' }}
                        </div>
                        <form action="{{ route('user.pwd-upload-requirement') }}" method="POST"
                            enctype="multipart/form-data" class="js-pwd-ajax-upload"
                            data-req-name="{{ $reqName }}">
                            @csrf
                            <input type="hidden" name="requirement_name" value="{{ $reqName }}">
                            <div class="d-flex gap-2 align-items-center flex-wrap">
                                <div style="flex:1;min-width:0;">
                                    <input type="file" name="file" class="form-control form-control-sm"
                                        accept=".jpg,.jpeg,.png,.pdf" required>
                                    <div style="font-size:.64rem;color:#94a3b8;margin-top:3px;">Images: 5MB max · PDF: 25MB max</div>
                                </div>
                                <button type="submit" class="btn btn-sm btn-warning" style="font-weight:600;white-space:nowrap;">{{ $fStatus === 'rejected' ? 'Re-upload' : 'Upload' }}</button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>
