@php
    $uploadPrefix = $uploadPrefix ?? 'mta';
    $uploadCols = $uploadCols ?? 'col-md-6';
    $mtaRequirementGroups = $mtaRequirementGroups ?? [];
    $mtaRequirements = $mtaRequirements ?? collect($mtaRequirementGroups)->pluck('documents')->flatten()->all();
    $totalR = count($mtaRequirements);
    $uploadedCount = ($uploadedFiles ?? collect())->filter(fn ($f) => !empty($f->file_path))->count();
    $pctR = $totalR > 0 ? round(($uploadedCount / $totalR) * 100) : 0;
@endphp

<div style="margin-bottom:16px;">
    <div style="height:6px;background:#dbe4ff;border-radius:3px;overflow:hidden;">
        <div style="width:{{ $pctR }}%;height:100%;background:var(--secondary-yellow);border-radius:3px;transition:width .4s;" id="mta-upload-progress"></div>
    </div>
    <div style="font-size:.72rem;color:#64748b;margin-top:4px;" id="mta-upload-progress-text">
        {{ $uploadedCount }}/{{ $totalR }} uploaded — {{ $pctR }}% complete
    </div>
</div>

@foreach($mtaRequirementGroups as $group)
    <div class="mta-cat-block">
        <div class="mta-cat-title" style="display:flex;align-items:center;gap:8px;">
            <span class="badge" style="background:var(--primary-gradient);color:white;font-size:.8rem;padding:5px 10px;border-radius:6px;">{{ $loop->iteration }}</span>
            <span>{{ $group['category'] }}</span>
        </div>
        <div class="row g-3">
            @foreach($group['documents'] as $reqName)
                @php
                    $uf = ($uploadedFiles ?? collect())->firstWhere('requirement_name', $reqName);
                    $isUploaded = $uf && !empty($uf->file_path);
                    $cls = $isUploaded ? 'uploaded' : '';
                @endphp
                <div class="{{ $uploadCols }}">
                    <div class="pwd-req {{ $cls }}" data-req-name="{{ $reqName }}">
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:6px;">
                            <div style="flex:1;">
                                <div class="pwd-req-name">
                                    {{ $reqName }}
                                    <span class="badge bg-danger ms-1" style="font-size:.65rem;font-weight:700;vertical-align:middle;">REQUIRED</span>
                                </div>
                                <div class="mta-uploaded-date" style="font-size:.7rem;color:#94a3b8;margin-top:1px;{{ $isUploaded && $uf->uploaded_at ? '' : 'display:none;' }}">
                                    {{ $isUploaded && $uf->uploaded_at ? 'Uploaded on ' . \Carbon\Carbon::parse($uf->uploaded_at)->format('M j, Y') : '' }}
                                </div>
                            </div>
                            <div style="display:flex;align-items:center;gap:5px;flex-shrink:0;">
                                <span class="mta-status-badge badge" style="background:{{ $isUploaded ? '#d4edda' : '#e9ecef' }};color:{{ $isUploaded ? '#155724' : '#6c757d' }};border-radius:20px;padding:3px 10px;font-size:.72rem;font-weight:700;">
                                    {{ $isUploaded ? 'Uploaded' : 'Not uploaded' }}
                                </span>
                                @if($isUploaded)
                                    @php
                                        $ext = strtolower(pathinfo($uf->file_path, PATHINFO_EXTENSION));
                                        $fileUrl = route('user.serve-file', $uf->id);
                                    @endphp
                                    @if(in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                                        <img src="{{ $fileUrl }}"
                                            onclick="openFileModal('{{ $fileUrl }}', '{{ addslashes($reqName) }}', '{{ $ext }}')"
                                            class="pwd-thumb" alt="Preview">
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="openFileModal('{{ $fileUrl }}', '{{ addslashes($reqName) }}', '{{ $ext }}')" style="font-size:.72rem;font-weight:600;padding:2px 8px;">View</button>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <div class="pwd-upload-box">
                            <div style="font-size:.75rem;font-weight:600;color:#856404;margin-bottom:6px;">
                                {{ $isUploaded ? 'Replace document' : 'Choose file to upload' }}
                            </div>
                            <form action="{{ route('user.mta-upload-requirement') }}" method="POST"
                                enctype="multipart/form-data" class="js-mta-ajax-upload"
                                data-req-name="{{ $reqName }}">
                                @csrf
                                <input type="hidden" name="requirement_name" value="{{ $reqName }}">
                                <div class="d-flex gap-2 align-items-center flex-wrap">
                                    <div style="flex:1;min-width:0;">
                                        <input type="file" name="file" class="form-control form-control-sm"
                                            accept=".jpg,.jpeg,.png,.pdf" required>
                                        <div style="font-size:.64rem;color:#94a3b8;margin-top:3px;">Images: 5MB max · PDF: 25MB max</div>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-warning" style="font-weight:600;white-space:nowrap;">{{ $isUploaded ? 'Replace' : 'Upload' }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endforeach
