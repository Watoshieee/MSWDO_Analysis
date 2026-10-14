@php
    $pType = $programType ?? 'PWD';
    $pLabel = $pType === 'PWD' ? 'PWD' : 'Solo Parent';
    $noAppointment = $noAppointment ?? false; // PWD renewal: no appointment needed
    $minD = $minDate ?? \Carbon\Carbon::tomorrow('Asia/Manila')->format('Y-m-d');
    $maxD = $maxDate ?? \Carbon\Carbon::now('Asia/Manila')->addDays(60)->format('Y-m-d');
    $linkedAppt = (isset($renewalRequest) && $renewalRequest) ? ($renewalRequest->appointment ?? null) : null;
    if (!$linkedAppt && isset($appointment) && $appointment) {
        $linkedAppt = $appointment;
    }
    // For no-appointment mode: active renewal means status is pending/approved (no appointment required)
    if ($noAppointment) {
        $hasActiveRenewal = isset($renewalRequest) && $renewalRequest && in_array($renewalRequest->status, ['pending', 'approved']);
        $hasRejectedRenewalWithAppt = false; // No appointment to preserve
    } else {
        $hasActiveRenewal = isset($renewalRequest) && $renewalRequest && in_array($renewalRequest->status, ['pending', 'approved']) && (!$renewalRequest->appointment_id || $linkedAppt);
        $hasRejectedRenewalWithAppt = isset($renewalRequest) && $renewalRequest && $renewalRequest->status === 'rejected' && $linkedAppt && !in_array($linkedAppt->status, ['rejected', 'cancelled']);
    }
@endphp

{{-- Toast styles (fixed top-right) --}}
@if(session('renewal_success') || session('renewal_error') || $errors->any())
<style>
.rc-toast {
    position: fixed;
    top: 88px;
    right: 24px;
    z-index: 99999;
    max-width: 440px;
    width: calc(100% - 48px);
    border-radius: 14px;
    padding: 14px 18px 17px;
    box-shadow: 0 8px 28px rgba(0,0,0,.22);
    display: flex;
    align-items: flex-start;
    gap: 12px;
    animation: rcSlideIn .35s cubic-bezier(.22,1,.36,1) both;
    overflow: hidden;
}
.rc-toast.ok  { background: #1a2470; color: #fff; border: 1px solid rgba(255,255,255,.15); }
.rc-toast.err { background: #7f1d1d; color: #fff; border: 1px solid rgba(255,255,255,.15); }
.rc-toast-bar {
    position: absolute;
    bottom: 0; left: 0;
    height: 3px;
    background: #f5b82e;
    width: 100%;
    transform-origin: left;
    animation: rcBar 5s linear forwards;
}
.rc-toast-close {
    background: transparent;
    border: none;
    color: rgba(255,255,255,.7);
    font-size: 1.2rem;
    cursor: pointer;
    line-height: 1;
    margin-left: auto;
    flex-shrink: 0;
    padding: 0;
}
@keyframes rcSlideIn {
    from { opacity:0; transform: translateX(32px); }
    to   { opacity:1; transform: translateX(0); }
}
@keyframes rcBar {
    from { transform: scaleX(1); }
    to   { transform: scaleX(0); }
}
</style>

@if(session('renewal_success'))
    <div class="rc-toast ok" id="rcToast" role="status" aria-live="polite">
        <span style="font-size:1.3rem;flex-shrink:0;">✅</span>
        <span style="font-size:.88rem;font-weight:600;line-height:1.45;">{{ session('renewal_success') }}</span>
        <button class="rc-toast-close" onclick="document.getElementById('rcToast').remove()" aria-label="Close">&times;</button>
        <div class="rc-toast-bar"></div>
    </div>
    <script>setTimeout(()=>{ const t=document.getElementById('rcToast'); if(t) t.remove(); }, 5200);</script>
@endif

@if(session('renewal_error'))
    <div class="rc-toast err" id="rcToastErr" role="alert">
        <span style="font-size:1.3rem;flex-shrink:0;">⚠️</span>
        <span style="font-size:.88rem;font-weight:600;line-height:1.45;">{{ session('renewal_error') }}</span>
        <button class="rc-toast-close" onclick="document.getElementById('rcToastErr').remove()" aria-label="Close">&times;</button>
        <div class="rc-toast-bar"></div>
    </div>
    <script>setTimeout(()=>{ const t=document.getElementById('rcToastErr'); if(t) t.remove(); }, 5200);</script>
@endif

@if($errors->any())
    <div class="rc-toast err" id="rcToastValid" role="alert">
        <span style="font-size:1.3rem;flex-shrink:0;">⚠️</span>
        <div style="font-size:.88rem;font-weight:600;line-height:1.5;">
            <strong style="display:block;margin-bottom:2px;">Please correct the following:</strong>
            <ul style="margin:0;padding-left:16px;">
                @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
            </ul>
        </div>
        <button class="rc-toast-close" onclick="document.getElementById('rcToastValid').remove()" aria-label="Close">&times;</button>
        <div class="rc-toast-bar"></div>
    </div>
    <script>setTimeout(()=>{ const t=document.getElementById('rcToastValid'); if(t) t.remove(); }, 7000);</script>
@endif
@endif

{{-- Active Renewal Status Card --}}
@if($hasActiveRenewal)
    <div class="card mb-4" style="border:none;border-radius:16px;box-shadow:0 4px 20px rgba(44,62,143,.08);overflow:hidden;border:1.5px solid #c7d6f5;">
        <div style="background:linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);color:white;padding:18px 24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:40px;height:40px;border-radius:10px;background:rgba(253,185,19,.2);display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                    🔄
                </div>
                <div>
                    <h5 style="margin:0;font-weight:800;font-size:1.15rem;letter-spacing:-0.2px;">{{ $pLabel }} ID Renewal Request</h5>
                    <small style="opacity:.85;font-size:.82rem;">Reference ID #{{ $renewalRequest->id }} · Submitted {{ $renewalRequest->created_at->format('M d, Y h:i A') }}</small>
                </div>
            </div>
            <div>
                {!! $renewalRequest->status_badge !!}
            </div>
        </div>

        <div class="card-body" style="padding:24px;">
            <div class="row g-3">
                @if(!$noAppointment)
                <div class="col-md-4">
                    <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;padding:14px 16px;">
                        <small style="color:#64748B;font-weight:700;font-size:.72rem;letter-spacing:.5px;text-transform:uppercase;">Scheduled Date</small>
                        <div style="color:#1E293B;font-weight:800;font-size:1rem;margin-top:4px;">
                            {{ $linkedAppt ? $linkedAppt->formatted_date : 'N/A' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;padding:14px 16px;">
                        <small style="color:#64748B;font-weight:700;font-size:.72rem;letter-spacing:.5px;text-transform:uppercase;">Scheduled Time</small>
                        <div style="color:#1E293B;font-weight:800;font-size:1rem;margin-top:4px;">
                            {{ $linkedAppt ? $linkedAppt->formatted_time : 'N/A' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                @else
                <div class="col-md-12">
                @endif
                    <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;padding:14px 16px;">
                        <small style="color:#64748B;font-weight:700;font-size:.72rem;letter-spacing:.5px;text-transform:uppercase;">Uploaded ID File</small>
                        <div style="margin-top:4px;">
                            <a href="{{ route('user.renewals.id-file', $renewalRequest->id) }}" target="_blank" style="color:#2C3E8F;font-weight:700;font-size:.9rem;display:inline-flex;align-items:center;gap:6px;text-decoration:none;">
                                📄 View Uploaded ID
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @if($renewalRequest->user_notes)
                <div style="margin-top:16px;background:#f8faff;border-left:4px solid #2C3E8F;border-radius:0 8px 8px 0;padding:12px 16px;font-size:.88rem;color:#334155;">
                    <strong>Your Note:</strong> {{ $renewalRequest->user_notes }}
                </div>
            @endif

            @if($renewalRequest->admin_remarks)
                <div style="margin-top:16px;background:#fff8e6;border-left:4px solid #FDB913;border-radius:0 8px 8px 0;padding:12px 16px;font-size:.88rem;color:#856404;">
                    <strong>Admin Remarks:</strong> {{ $renewalRequest->admin_remarks }}
                </div>
            @endif

            @if($renewalRequest->status === 'pending')
                <div style="margin-top:20px;padding-top:16px;border-top:1px solid #E2E8F0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                    <span style="color:#64748B;font-size:.85rem;">
                        @if($noAppointment)
                            ℹ️ Your old PWD ID is currently being reviewed by the MSWDO admin. You will be notified once it is approved or rejected.
                        @else
                            ℹ️ Your renewal appointment is currently pending MSWDO admin approval. Once approved, you will receive an email and notification.
                        @endif
                    </span>
                    <form method="POST" action="{{ route('user.renewals.cancel', $renewalRequest->id) }}"
                          class="gcm-form"
                          data-gcm-title="Cancel renewal request?"
                          data-gcm-message="Are you sure you want to cancel this renewal request? This action cannot be undone."
                          data-gcm-confirm="Cancel Request"
                          data-gcm-cancel="Keep It"
                          data-gcm-class="danger">
                        @csrf
                        <input type="hidden" name="cancel_reason" value="Applicant requested cancellation">
                        <button type="submit" style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;border-radius:8px;padding:7px 18px;font-weight:700;font-size:.82rem;cursor:pointer;">
                            Cancel Request
                        </button>
                    </form>
                </div>
            @elseif($renewalRequest->status === 'approved')
                @if($renewalRequest->appointment && $renewalRequest->appointment->status === 'validated')
                    <div style="margin-top:20px;padding:14px 18px;border-radius:10px;background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;font-size:.88rem;font-weight:600;">
                        🎉 <strong>Renewal Validated & Completed:</strong> Your in-person verification is complete. Your renewed ID has been processed.
                    </div>
                @else
                    <div style="margin-top:20px;padding:14px 18px;border-radius:10px;background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;font-size:.88rem;font-weight:600;">
                        🎉 <strong>Appointment Confirmed:</strong> Your renewal appointment is approved! Please bring your existing ID card, supporting documents, and appointment slip on your scheduled date.
                    </div>
                @endif
            @endif
        </div>
    </div>

{{-- Rejected Renewal with Preserved Appointment --}}
@elseif($hasRejectedRenewalWithAppt)
    {{-- Rejection Notice Alert --}}
    <div class="alert alert-danger mb-4" style="border-radius:14px;border:1.5px solid #fca5a5;background:#fef2f2;color:#991b1b;padding:18px 22px;box-shadow:0 4px 14px rgba(220,38,38,.08);">
        <div style="display:flex;align-items:flex-start;gap:14px;">
            <div style="font-size:1.6rem;line-height:1;">⚠️</div>
            <div style="flex:1;">
                <div style="font-weight:800;font-size:1.02rem;color:#991b1b;margin-bottom:4px;">
                    Your Valid ID was rejected. Please upload a new Valid ID. Your existing appointment has been preserved.
                </div>
                @if($renewalRequest->admin_remarks)
                    <div style="margin-top:8px;padding:10px 14px;background:#fff;border-left:4px solid #dc2626;border-radius:0 8px 8px 0;font-size:.88rem;color:#7f1d1d;">
                        <strong>Reason for Rejection:</strong> {{ $renewalRequest->admin_remarks }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Preserved Appointment & Re-upload Card --}}
    <div class="card mb-4" style="border:none;border-radius:16px;box-shadow:0 4px 20px rgba(44,62,143,.08);overflow:hidden;border:1.5px solid #c7d6f5;">
        <div style="background:linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);color:white;padding:18px 24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:40px;height:40px;border-radius:10px;background:rgba(253,185,19,.2);display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                    🔄
                </div>
                <div>
                    <h5 style="margin:0;font-weight:800;font-size:1.15rem;letter-spacing:-0.2px;">{{ $pLabel }} ID Renewal — Preserved Appointment</h5>
                    <small style="opacity:.85;font-size:.82rem;">Reference ID #{{ $renewalRequest->id }} · Appointment Preserved</small>
                </div>
            </div>
            <div>
                {!! $renewalRequest->status_badge !!}
            </div>
        </div>

        <div class="card-body" style="padding:24px;">
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;padding:14px 16px;">
                        <small style="color:#64748B;font-weight:700;font-size:.72rem;letter-spacing:.5px;text-transform:uppercase;">Preserved Date</small>
                        <div style="color:#1E293B;font-weight:800;font-size:1rem;margin-top:4px;">
                            {{ $linkedAppt ? $linkedAppt->formatted_date : 'N/A' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;padding:14px 16px;">
                        <small style="color:#64748B;font-weight:700;font-size:.72rem;letter-spacing:.5px;text-transform:uppercase;">Preserved Time</small>
                        <div style="color:#1E293B;font-weight:800;font-size:1rem;margin-top:4px;">
                            {{ $linkedAppt ? $linkedAppt->formatted_time : 'N/A' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;padding:14px 16px;">
                        <small style="color:#64748B;font-weight:700;font-size:.72rem;letter-spacing:.5px;text-transform:uppercase;">Rejected Document</small>
                        <div style="margin-top:4px;">
                            <a href="{{ route('user.renewals.id-file', $renewalRequest->id) }}" target="_blank" style="color:#dc2626;font-weight:700;font-size:.9rem;display:inline-flex;align-items:center;gap:6px;text-decoration:none;">
                                📄 View Previous ID
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Re-upload Form --}}
            <form id="reuploadRenewalForm" method="POST" action="{{ route('user.renewals.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="program_type" value="{{ $pType }}">

                <div class="row g-4">
                    <div class="col-12">
                        <label class="form-label" style="font-weight:700;font-size:.88rem;color:#1E293B;">
                            Upload Replacement Valid ID <span style="color:#dc2626;">*</span>
                        </label>
                        <div class="upload-zone" style="border:2px dashed #C7D6F5;border-radius:12px;padding:24px;text-align:center;background:#F8FAFC;cursor:pointer;transition:border-color .2s;" onclick="document.getElementById('reuploadIdFileInput').click()">
                            <input type="file" name="id_file" id="reuploadIdFileInput" class="d-none" accept=".jpg,.jpeg,.png,.pdf" onchange="handleReuploadFileSelect(this)" required>
                            <div id="reuploadFilePlaceholder">
                                <div style="font-size:2rem;margin-bottom:8px;">📎</div>
                                <div style="font-weight:700;color:#2C3E8F;font-size:.92rem;">Click to select replacement Valid ID file</div>
                                <small style="color:#64748B;font-size:.8rem;">Accepted formats: JPG, JPEG, PNG, PDF (Max 5MB)</small>
                            </div>
                            <div id="reuploadFilePreview" style="display:none;">
                                <div style="font-size:2rem;margin-bottom:8px;">📄</div>
                                <div id="reuploadFileName" style="font-weight:700;color:#1E293B;font-size:.92rem;"></div>
                                <small id="reuploadFileSize" style="color:#64748B;font-size:.8rem;"></small>
                                <div style="margin-top:8px;">
                                    <span style="color:#2C3E8F;font-weight:700;font-size:.82rem;text-decoration:underline;">Click to choose a different file</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- User Notes --}}
                    <div class="col-12">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                            <label class="form-label" style="font-weight:700;font-size:.88rem;color:#1E293B;margin-bottom:0;">
                                Additional Notes / Instructions <span style="font-weight:400;color:#64748b;font-size:.8rem;">(Optional)</span>
                            </label>
                            <span id="charCount-reupload" style="font-size:.78rem;color:#64748b;font-weight:600;">0 / 200 characters</span>
                        </div>
                        <textarea name="user_notes" id="userNotes-reupload" rows="2" class="form-control"
                            maxlength="200"
                            placeholder="Optional notes regarding the replacement ID (max 200 characters)..."
                            style="border-radius:10px;border:1.5px solid #C7D6F5;font-size:.88rem;resize:vertical;"
                            oninput="updateReuploadCharCount(this)">{{ old('user_notes') }}</textarea>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" id="reuploadSubmitBtn" style="background:linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);color:white;border:none;border-radius:10px;padding:12px 32px;font-weight:800;font-size:.92rem;cursor:pointer;box-shadow:0 4px 14px rgba(44,62,143,.25);transition:opacity .2s;">
                            Upload Replacement Valid ID
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

{{-- Past rejected or cancelled renewal notice (only for appointment-based renewals like Solo Parent) --}}
@elseif(isset($renewalRequest) && $renewalRequest && in_array($renewalRequest->status, ['rejected', 'cancelled']) && !$noAppointment)
    <div class="alert alert-warning mb-4" style="border-radius:14px;border:1.5px solid #fde68a;background:#fffbeb;color:#92400e;padding:16px 20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
            <div>
                <strong>Notice:</strong> Your previous renewal request on {{ $renewalRequest->created_at->format('M d, Y') }} was
                <span class="badge {{ $renewalRequest->status === 'rejected' ? 'bg-danger' : 'bg-secondary' }}">{{ ucfirst($renewalRequest->status) }}</span>.
                @if($renewalRequest->admin_remarks)
                    <div style="margin-top:4px;font-size:.85rem;color:#78350f;">Reason / Remarks: {{ $renewalRequest->admin_remarks }}</div>
                @endif
            </div>
            <button type="button" onclick="document.getElementById('renewalFormContainer').scrollIntoView({behavior:'smooth'})" style="background:#FDB913;color:#1A2A5C;border:none;border-radius:8px;padding:6px 16px;font-weight:700;font-size:.82rem;cursor:pointer;">
                Submit New Renewal Request
            </button>
        </div>
    </div>
@endif

{{-- PWD Renewal: Show rejection reason before the resubmission form (noAppointment mode) --}}
@if($noAppointment && isset($renewalRequest) && $renewalRequest && $renewalRequest->status === 'rejected')
    <div style="border-radius:14px;border:1.5px solid #fca5a5;background:#fef2f2;color:#991b1b;padding:16px 20px;margin-bottom:16px;">
        <div style="font-weight:700;font-size:.95rem;margin-bottom:4px;">Your PWD renewal ID was not accepted.</div>
        @if($renewalRequest->admin_remarks)
            <div style="font-size:.88rem;margin-top:6px;padding:10px 14px;background:white;border-left:4px solid #dc2626;border-radius:0 8px 8px 0;color:#7f1d1d;">
                <strong>Reason:</strong> {{ $renewalRequest->admin_remarks }}
            </div>
        @endif
        <div style="margin-top:8px;font-size:.85rem;color:#7f1d1d;">Please upload a new, clear copy of your valid PWD ID below.</div>
    </div>
@endif

{{-- Renewal Submission Form (shown when no active pending/approved renewal) --}}
@if(!$hasActiveRenewal && !$hasRejectedRenewalWithAppt)
    <div id="renewalFormContainer" class="card mb-4" style="border:none;border-radius:16px;box-shadow:0 4px 20px rgba(44,62,143,.08);overflow:hidden;border:1.5px solid #c7d6f5;">
        <div style="background:linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);color:white;padding:18px 24px;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:40px;height:40px;border-radius:10px;background:rgba(253,185,19,.2);display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                    🪪
                </div>
                <div>
                    <h5 style="margin:0;font-weight:800;font-size:1.15rem;letter-spacing:-0.2px;">Request {{ $pLabel }} ID Renewal</h5>
                    <small style="opacity:.85;font-size:.82rem;">
                        @if($noAppointment)
                            Upload your existing ID for admin review. No appointment needed.
                        @else
                            Upload your existing ID and choose an appointment slot for validation
                        @endif
                    </small>
                </div>
            </div>
            <span style="background:rgba(253,185,19,.2);color:#FDB913;border-radius:20px;padding:4px 12px;font-size:.75rem;font-weight:700;">
                Renewal Service
            </span>
        </div>

        <div class="card-body" style="padding:26px;">
            <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:12px 18px;margin-bottom:22px;color:#1e40af;font-size:.85rem;">
                📋 <strong>Renewal Requirements:</strong>
                <ul style="margin:6px 0 0 18px;padding:0;color:#1e3a8a;">
                    <li>Upload a clear photo or PDF scan of your current or expired <strong>{{ $pLabel }} ID</strong> (Max 5MB).</li>
                    @if($noAppointment)
                        <li>Your uploaded ID will be reviewed by the MSWDO admin. Once <strong>approved</strong>, you can proceed to submit your requirements — same as a new application.</li>
                        <li>No appointment booking is required for PWD ID renewal.</li>
                    @else
                        <li>Appointments are available on <strong>weekdays (Monday–Friday)</strong> between <strong>8:00 AM–12:00 PM</strong> and <strong>1:00 PM–5:00 PM</strong>.</li>
                        <li>Maximum of <strong>5 appointments per hour</strong> are accepted to prevent office overcrowding.</li>
                        <li>Your selected appointment will be confirmed once the MSWDO admin reviews and approves your uploaded ID.</li>
                    @endif
                </ul>
            </div>

            <form id="renewalSubmitForm" method="POST" action="{{ route('user.renewals.store') }}" enctype="multipart/form-data" {!! $noAppointment ? 'data-no-appointment="1"' : '' !!}>
                @csrf
                <input type="hidden" name="program_type" value="{{ $pType }}">

                <div class="row g-4">
                    {{-- ID Document Upload --}}
                    <div class="col-md-6">
                        <label class="form-label" style="font-weight:700;font-size:.88rem;color:#1E293B;">
                            Upload Current / Expired {{ $pLabel }} ID <span style="color:#dc2626;">*</span>
                        </label>
                        <div style="border:2px dashed #93c5fd;background:#f8faff;border-radius:12px;padding:20px;text-align:center;position:relative;cursor:pointer;transition:border-color .2s;" id="renewalDropZone">
                            <input type="file" name="id_file" id="renewalIdFileInput" accept=".jpg,.jpeg,.png,.pdf" required style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;" onchange="handleRenewalFileSelect(this)">
                            <div id="renewalFilePlaceholder">
                                <div style="font-size:2rem;color:#3b82f6;margin-bottom:6px;">📤</div>
                                <div style="font-weight:700;color:#1e3a8a;font-size:.9rem;">Click or drag file here</div>
                                <div style="color:#64748b;font-size:.78rem;margin-top:4px;">Supports JPG, PNG, or PDF up to 5MB</div>
                            </div>
                            <div id="renewalFilePreview" style="display:none;text-align:left;background:white;padding:10px 14px;border-radius:8px;border:1px solid #bfdbfe;">
                                <div style="display:flex;align-items:center;justify-content:space-between;">
                                    <div style="display:flex;align-items:center;gap:10px;overflow:hidden;">
                                        <span style="font-size:1.4rem;">📄</span>
                                        <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                            <div id="renewalFileName" style="font-weight:700;font-size:.85rem;color:#1e293b;"></div>
                                            <div id="renewalFileSize" style="font-size:.75rem;color:#64748b;"></div>
                                        </div>
                                    </div>
                                    <span style="color:#16a34a;font-weight:700;font-size:.8rem;">Selected</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Appointment Date Selection (hidden for no-appointment programs like PWD) --}}
                    @if(!$noAppointment)
                    <div class="col-md-6">
                        <label class="form-label" style="font-weight:700;font-size:.88rem;color:#1E293B;">
                            Select Appointment Date <span style="color:#dc2626;">*</span>
                        </label>
                        <input type="date" name="appointment_date" id="apptDate-renew" class="form-control" style="border-radius:10px;border:1.5px solid #C7D6F5;padding:10px 14px;font-size:.9rem;" min="{{ $minD }}" max="{{ $maxD }}" required>
                        <div class="form-text" style="font-size:.78rem;color:#64748b;">
                            Weekdays only (Mon–Fri). Holidays and full capacity days are automatically blocked.
                        </div>

                        {{-- Appointment Time Slot Picker (Clock-Style Selector) --}}
                        <div style="margin-top:16px;">
                            <label class="form-label" style="font-weight:700;font-size:.88rem;color:#1E293B;">
                                Select Appointment Time Slot <span style="color:#dc2626;">*</span>
                            </label>
                            <input type="hidden" name="appointment_time" id="apptTime-renew">

                            <button type="button" id="timePickerBtn-renew" class="sp-timepicker-btn" onclick="openClockModal('renew')" disabled>
                                <span class="sp-timepicker-icon">🕐</span>
                                <span id="timePickerLabel-renew">Select date first</span>
                            </button>
                            <div id="slotMsg-renew" class="sp-hint" style="font-size:.78rem;margin-top:6px;min-height:18px;"></div>
                        </div>
                    </div>
                    @endif

                    {{-- User Notes with 200-Character Limit & Live Counter --}}
                    <div class="col-12">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                            <label class="form-label" style="font-weight:700;font-size:.88rem;color:#1E293B;margin-bottom:0;">
                                Additional Notes / Instructions <span style="font-weight:400;color:#64748b;font-size:.8rem;">(Optional)</span>
                            </label>
                            <span id="charCount-renew" style="font-size:.78rem;color:#64748b;font-weight:600;">0 / 200 characters</span>
                        </div>
                        <textarea name="user_notes" id="userNotes-renew" rows="3" class="form-control"
                            maxlength="200"
                            placeholder="E.g., Renewing due to expired card, replacement, or updated information (max 200 characters)..."
                            style="border-radius:10px;border:1.5px solid #C7D6F5;font-size:.88rem;resize:vertical;"
                            oninput="updateRenewalCharCount(this)"
                            onpaste="setTimeout(() => updateRenewalCharCount(this), 0)">{{ old('user_notes') }}</textarea>
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:4px;">
                            <span id="charCountError-renew" style="font-size:.78rem;color:#dc2626;font-weight:600;display:none;">Additional Notes must not exceed 200 characters.</span>
                            <span style="font-size:.75rem;color:#94a3b8;">Maximum 200 characters</span>
                        </div>
                    </div>

                    <div class="col-12 text-end">
                        <button type="submit" id="renewalSubmitBtn" style="background:linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);color:white;border:none;border-radius:10px;padding:12px 32px;font-weight:800;font-size:.92rem;cursor:pointer;box-shadow:0 4px 14px rgba(44,62,143,.25);transition:opacity .2s;">
                            Submit Renewal Request
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif

    <script>
        function handleRenewalFileSelect(input) {
            const file = input.files[0];
            const placeholder = document.getElementById('renewalFilePlaceholder');
            const preview = document.getElementById('renewalFilePreview');
            const nameEl = document.getElementById('renewalFileName');
            const sizeEl = document.getElementById('renewalFileSize');

            if (!file) {
                placeholder.style.display = 'block';
                preview.style.display = 'none';
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                alert('File is too large. Please select a document under 5MB.');
                input.value = '';
                placeholder.style.display = 'block';
                preview.style.display = 'none';
                return;
            }

            nameEl.textContent = file.name;
            sizeEl.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            placeholder.style.display = 'none';
            preview.style.display = 'block';
        }

        function updateRenewalCharCount(textarea) {
            if (!textarea) return;
            const maxChars = 200;
            if (textarea.value.length > maxChars) {
                textarea.value = textarea.value.slice(0, maxChars);
            }
            const count = textarea.value.length;
            const countEl = document.getElementById('charCount-renew');
            const errEl = document.getElementById('charCountError-renew');

            if (countEl) {
                countEl.textContent = count + ' / ' + maxChars + ' characters';
                countEl.style.color = (count >= maxChars) ? '#dc2626' : '#64748b';
            }
            if (errEl) {
                if (count >= maxChars) {
                    errEl.textContent = 'Additional Notes must not exceed 200 characters.';
                    errEl.style.display = 'block';
                    textarea.style.borderColor = '#dc2626';
                } else {
                    errEl.style.display = 'none';
                    textarea.style.borderColor = '';
                }
            }
        }

        // Initialize clock slot loader for renewal form
        function initRenewalControls() {
            if (typeof initApptSlotLoader === 'function') {
                initApptSlotLoader('renew');
            }
            const notesEl = document.getElementById('userNotes-renew');
            if (notesEl) {
                updateRenewalCharCount(notesEl);
            }
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initRenewalControls);
        } else {
            initRenewalControls();
        }

        // Re-upload form helpers
        function handleReuploadFileSelect(input) {
            const file = input.files[0];
            const placeholder = document.getElementById('reuploadFilePlaceholder');
            const preview = document.getElementById('reuploadFilePreview');
            const nameEl = document.getElementById('reuploadFileName');
            const sizeEl = document.getElementById('reuploadFileSize');

            if (!file) {
                if (placeholder) placeholder.style.display = 'block';
                if (preview) preview.style.display = 'none';
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                alert('File is too large. Please select a document under 5MB.');
                input.value = '';
                if (placeholder) placeholder.style.display = 'block';
                if (preview) preview.style.display = 'none';
                return;
            }

            if (nameEl) nameEl.textContent = file.name;
            if (sizeEl) sizeEl.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
            if (placeholder) placeholder.style.display = 'none';
            if (preview) preview.style.display = 'block';
        }

        function updateReuploadCharCount(textarea) {
            if (!textarea) return;
            const maxChars = 200;
            if (textarea.value.length > maxChars) {
                textarea.value = textarea.value.slice(0, maxChars);
            }
            const count = textarea.value.length;
            const countEl = document.getElementById('charCount-reupload');
            if (countEl) {
                countEl.textContent = count + ' / ' + maxChars + ' characters';
                countEl.style.color = (count >= maxChars) ? '#dc2626' : '#64748b';
            }
        }

        document.getElementById('reuploadRenewalForm')?.addEventListener('submit', function(e) {
            const file = document.getElementById('reuploadIdFileInput')?.files[0];
            const notes = document.getElementById('userNotes-reupload')?.value;

            if (!file) {
                e.preventDefault();
                alert('Please upload your replacement Valid ID file.');
                return;
            }
            if (notes && notes.length > 200) {
                e.preventDefault();
                alert('Additional Notes must not exceed 200 characters.');
                return;
            }
            const btn = document.getElementById('reuploadSubmitBtn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Uploading Replacement ID...';
            }
        });

        // Form submit validation
        document.getElementById('renewalSubmitForm')?.addEventListener('submit', function(e) {
            const noAppt = this.dataset.noAppointment === '1';
            const time = document.getElementById('apptTime-renew')?.value;
            const file = document.getElementById('renewalIdFileInput')?.files[0];
            const notes = document.getElementById('userNotes-renew')?.value;

            if (!noAppt && !time) {
                e.preventDefault();
                alert('Please select an appointment time slot.');
                return;
            }
            if (!file) {
                e.preventDefault();
                alert('Please upload your ID file.');
                return;
            }
            if (notes && notes.length > 200) {
                e.preventDefault();
                alert('Additional Notes must not exceed 200 characters.');
                return;
            }
            const btn = document.getElementById('renewalSubmitBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Submitting Request...';
        });

    // Wire .gcm-form to global custom confirm modal
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.gcm-form').forEach(function (form) {
            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                if (typeof uiConfirm !== 'function') { form.submit(); return; }
                const ok = await uiConfirm({
                    title:        form.dataset.gcmTitle   || 'Confirm Action',
                    message:      form.dataset.gcmMessage || 'Are you sure?',
                    confirmText:  form.dataset.gcmConfirm || 'Confirm',
                    cancelText:   form.dataset.gcmCancel  || 'Cancel',
                    confirmClass: form.dataset.gcmClass   || 'default',
                });
                if (ok) {
                    const btn = form.querySelector('[type="submit"]');
                    if (btn) btn.disabled = true;
                    form.submit();
                }
            });
        });
    });
    </script>

@if(empty($hasClockModal))
    {{-- Self-contained fallback clock modal & styles for pages without parent clock modal --}}
    <style>
        .sp-timepicker-btn {
            width: 100%;
            min-height: 50px;
            border: 1.5px solid #c6d1ee;
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 15px;
            font-weight: 700;
            color: #1a2470;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.2s;
        }
        .sp-timepicker-btn:hover:not(:disabled) { border-color: #1a2470; background: #f8faff; }
        .sp-timepicker-btn.has-value { background: #eef3ff; border-color: #1a2470; }
        .sp-timepicker-btn:disabled { background: #f1f5f9; color: #94a3b8; border-color: #cbd5e1; cursor: not-allowed; }
    </style>

@endif
