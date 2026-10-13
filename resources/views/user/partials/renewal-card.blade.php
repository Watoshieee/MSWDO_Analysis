@php
    $pType = $programType ?? 'PWD';
    $pLabel = $pType === 'PWD' ? 'PWD' : 'Solo Parent';
    $minD = $minDate ?? \Carbon\Carbon::tomorrow('Asia/Manila')->format('Y-m-d');
    $maxD = $maxDate ?? \Carbon\Carbon::now('Asia/Manila')->addDays(60)->format('Y-m-d');
    $hasActiveRenewal = isset($renewalRequest) && $renewalRequest && in_array($renewalRequest->status, ['pending', 'approved']) && (!$renewalRequest->appointment_id || $renewalRequest->appointment);
@endphp

{{-- Flash notices --}}
@if(session('renewal_success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:12px;font-size:.9rem;font-weight:600;background:#d4edda;border-color:#c3e6cb;color:#155724;box-shadow:0 4px 12px rgba(21,87,36,.12);margin-bottom:20px;">
        <span style="margin-right:8px;">✅</span> {{ session('renewal_success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('renewal_error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:12px;font-size:.9rem;font-weight:600;background:#f8d7da;border-color:#f5c6cb;color:#721c24;box-shadow:0 4px 12px rgba(114,28,36,.12);margin-bottom:20px;">
        <span style="margin-right:8px;">⚠️</span> {{ session('renewal_error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
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
                <div class="col-md-4">
                    <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;padding:14px 16px;">
                        <small style="color:#64748B;font-weight:700;font-size:.72rem;letter-spacing:.5px;text-transform:uppercase;">Scheduled Date</small>
                        <div style="color:#1E293B;font-weight:800;font-size:1rem;margin-top:4px;">
                            {{ $renewalRequest->appointment ? $renewalRequest->appointment->formatted_date : 'N/A' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;padding:14px 16px;">
                        <small style="color:#64748B;font-weight:700;font-size:.72rem;letter-spacing:.5px;text-transform:uppercase;">Scheduled Time</small>
                        <div style="color:#1E293B;font-weight:800;font-size:1rem;margin-top:4px;">
                            {{ $renewalRequest->appointment ? $renewalRequest->appointment->formatted_time : 'N/A' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
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
                        ℹ️ Your renewal appointment is currently pending MSWDO admin approval. Once approved, you will receive an email and notification.
                    </span>
                    <form method="POST" action="{{ route('user.renewals.cancel', $renewalRequest->id) }}" onsubmit="return confirm('Are you sure you want to cancel this renewal request? This will release your appointment slot.');">
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

{{-- Past rejected or cancelled renewal notice --}}
@elseif(isset($renewalRequest) && $renewalRequest && in_array($renewalRequest->status, ['rejected', 'cancelled']))
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

{{-- Renewal Submission Form (shown when no active pending/approved renewal) --}}
@if(!$hasActiveRenewal)
    <div id="renewalFormContainer" class="card mb-4" style="border:none;border-radius:16px;box-shadow:0 4px 20px rgba(44,62,143,.08);overflow:hidden;border:1.5px solid #c7d6f5;">
        <div style="background:linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);color:white;padding:18px 24px;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:40px;height:40px;border-radius:10px;background:rgba(253,185,19,.2);display:flex;align-items:center;justify-content:center;font-size:1.3rem;">
                    🪪
                </div>
                <div>
                    <h5 style="margin:0;font-weight:800;font-size:1.15rem;letter-spacing:-0.2px;">Request {{ $pLabel }} ID Renewal</h5>
                    <small style="opacity:.85;font-size:.82rem;">Upload your existing ID and choose an appointment slot for validation</small>
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
                    <li>Appointments are available on <strong>weekdays (Monday–Friday)</strong> between <strong>8:00 AM–12:00 PM</strong> and <strong>1:00 PM–5:00 PM</strong>.</li>
                    <li>Maximum of <strong>5 appointments per hour</strong> are accepted to prevent office overcrowding.</li>
                    <li>Your selected appointment will be confirmed once the MSWDO admin reviews and approves your uploaded ID.</li>
                </ul>
            </div>

            <form id="renewalSubmitForm" method="POST" action="{{ route('user.renewals.store') }}" enctype="multipart/form-data">
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

                    {{-- Appointment Date Selection --}}
                    <div class="col-md-6">
                        <label class="form-label" style="font-weight:700;font-size:.88rem;color:#1E293B;">
                            Select Appointment Date <span style="color:#dc2626;">*</span>
                        </label>
                        <input type="date" name="appointment_date" id="renewalApptDate" class="form-control" style="border-radius:10px;border:1.5px solid #C7D6F5;padding:10px 14px;font-size:.9rem;" min="{{ $minD }}" max="{{ $maxD }}" required onchange="fetchRenewalSlots(this.value)">
                        <div class="form-text" style="font-size:.78rem;color:#64748b;">
                            Weekdays only (Mon–Fri). Holidays and full capacity days are automatically blocked.
                        </div>

                        {{-- Appointment Time Slot Picker --}}
                        <div style="margin-top:16px;">
                            <label class="form-label" style="font-weight:700;font-size:.88rem;color:#1E293B;">
                                Select Appointment Time Slot <span style="color:#dc2626;">*</span>
                            </label>
                            <input type="hidden" name="appointment_time" id="renewalSelectedTime" required>
                            
                            <div id="renewalSlotLoading" style="display:none;color:#3b82f6;font-size:.85rem;font-weight:600;padding:8px 0;">
                                <span class="spinner-border spinner-border-sm me-1"></span> Checking available time slots&hellip;
                            </div>

                            <div id="renewalSlotContainer" style="display:none;margin-top:6px;">
                                <div id="renewalSlotSummary" style="font-size:.8rem;font-weight:700;margin-bottom:8px;color:#15803d;"></div>
                                <div id="renewalSlotGrid" style="display:grid;grid-template-columns:repeat(auto-fill, minmax(95px, 1fr));gap:6px;max-height:165px;overflow-y:auto;padding:4px;border:1px solid #E2E8F0;border-radius:8px;background:#F8FAFC;">
                                </div>
                            </div>

                            <div id="renewalSlotPrompt" style="color:#94a3b8;font-size:.84rem;padding:8px 0;">
                                👈 Select a date above to view available time slots.
                            </div>
                        </div>
                    </div>

                    {{-- User Notes --}}
                    <div class="col-12">
                        <label class="form-label" style="font-weight:700;font-size:.88rem;color:#1E293B;">
                            Additional Notes / Instructions <span style="font-weight:400;color:#64748b;font-size:.8rem;">(Optional)</span>
                        </label>
                        <textarea name="user_notes" rows="2" class="form-control" placeholder="E.g., Renewing due to expired card, replacement, or updated information..." style="border-radius:10px;border:1.5px solid #C7D6F5;font-size:.88rem;resize:none;"></textarea>
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

        function fetchRenewalSlots(date) {
            const promptEl = document.getElementById('renewalSlotPrompt');
            const loadingEl = document.getElementById('renewalSlotLoading');
            const containerEl = document.getElementById('renewalSlotContainer');
            const gridEl = document.getElementById('renewalSlotGrid');
            const summaryEl = document.getElementById('renewalSlotSummary');
            const selectedTimeInput = document.getElementById('renewalSelectedTime');

            selectedTimeInput.value = '';

            if (!date) {
                promptEl.style.display = 'block';
                loadingEl.style.display = 'none';
                containerEl.style.display = 'none';
                return;
            }

            // Check weekend client-side
            const d = new Date(date + 'T00:00:00');
            const day = d.getDay();
            if (day === 0 || day === 6) {
                promptEl.style.display = 'block';
                promptEl.textContent = '❌ Weekends are not available. Please choose Monday through Friday.';
                promptEl.style.color = '#dc2626';
                loadingEl.style.display = 'none';
                containerEl.style.display = 'none';
                return;
            }

            promptEl.style.display = 'none';
            loadingEl.style.display = 'block';
            containerEl.style.display = 'none';
            gridEl.innerHTML = '';

            fetch('/user/appointments/slots?date=' + encodeURIComponent(date), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                loadingEl.style.display = 'none';

                if (data.error) {
                    promptEl.style.display = 'block';
                    promptEl.textContent = '⚠️ ' + data.error;
                    promptEl.style.color = '#dc2626';
                    return;
                }

                if (!Array.isArray(data) || !data.length) {
                    promptEl.style.display = 'block';
                    promptEl.textContent = 'No slots available on this date.';
                    promptEl.style.color = '#dc2626';
                    return;
                }

                const availableSlots = data.filter(s => !s.full);
                summaryEl.textContent = availableSlots.length + ' available slot(s) for ' + date;
                containerEl.style.display = 'block';

                data.forEach(s => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = s.label;
                    btn.style.padding = '6px 8px';
                    btn.style.fontSize = '.76rem';
                    btn.style.fontWeight = '700';
                    btn.style.borderRadius = '6px';
                    btn.style.border = '1px solid #cbd5e1';
                    btn.style.cursor = s.full ? 'not-allowed' : 'pointer';

                    if (s.full) {
                        btn.disabled = true;
                        btn.style.background = '#f1f5f9';
                        btn.style.color = '#94a3b8';
                        btn.title = 'Fully booked or hour capacity reached (max 5/hr)';
                    } else {
                        btn.style.background = 'white';
                        btn.style.color = '#1e293b';
                        btn.onclick = () => {
                            // deselect all
                            gridEl.querySelectorAll('button').forEach(b => {
                                if (!b.disabled) {
                                    b.style.background = 'white';
                                    b.style.color = '#1e293b';
                                    b.style.borderColor = '#cbd5e1';
                                }
                            });
                            // select this
                            btn.style.background = '#2C3E8F';
                            btn.style.color = 'white';
                            btn.style.borderColor = '#2C3E8F';
                            selectedTimeInput.value = s.time;
                        };
                    }
                    gridEl.appendChild(btn);
                });
            })
            .catch(err => {
                loadingEl.style.display = 'none';
                promptEl.style.display = 'block';
                promptEl.textContent = 'Error loading slots. Please try another date.';
                promptEl.style.color = '#dc2626';
            });
        }

        // Form submit validation
        document.getElementById('renewalSubmitForm')?.addEventListener('submit', function(e) {
            const time = document.getElementById('renewalSelectedTime').value;
            const file = document.getElementById('renewalIdFileInput').files[0];
            if (!time) {
                e.preventDefault();
                alert('Please select an appointment time slot.');
                return;
            }
            if (!file) {
                e.preventDefault();
                alert('Please upload your ID file.');
                return;
            }
            const btn = document.getElementById('renewalSubmitBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Submitting Request...';
        });
    </script>
@endif
