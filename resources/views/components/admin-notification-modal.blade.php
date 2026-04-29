{{--
    Admin Notification Bell Modal
    Variables expected:
        $adminNewApplications   – Application collection (new since last viewed)
        $adminNewAppointments   – Appointment collection (new pending Solo Parent bookings)
        $adminNotifCount        – int (badge count)
--}}

@php
    $hasNew      = isset($adminNewApplications) && $adminNewApplications->count() > 0;
    $hasNewAppts   = isset($adminNewAppointments) && $adminNewAppointments->count() > 0;
    $hasNewUploads = isset($adminNewUploads) && $adminNewUploads->count() > 0;
    $total         = ($hasNew ? $adminNewApplications->count() : 0) + ($hasNewAppts ? $adminNewAppointments->count() : 0) + ($hasNewUploads ? $adminNewUploads->count() : 0);
    $programLabels = [
        'Senior_Citizen_Pension' => 'Senior Citizen',
        'PWD_Assistance'         => 'PWD Assistance',
        'PWD_New'                => 'PWD (New)',
        'PWD_Renewal'            => 'PWD (Renewal)',
        'AICS_Medical'           => 'AICS Medical',
        'AICS_Burial'            => 'AICS Burial',
        '4Ps'                    => '4Ps',
        'SLP'                    => 'SLP',
        'ESA'                    => 'ESA',
        'Solo_Parent'            => 'Solo Parent',
        'solo_parent'            => 'Solo Parent',
    ];

    $fmtAdminTs = function ($ts) {
        if (!$ts) return null;
        try {
            $c = $ts instanceof \Carbon\CarbonInterface ? $ts : \Carbon\Carbon::parse($ts);
            $c = $c->copy()->setTimezone('Asia/Manila');
            return $c;
        } catch (\Exception $e) {
            return null;
        }
    };
@endphp

<div class="modal fade" id="adminNotifModal" tabindex="-1" aria-labelledby="adminNotifModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:20px;border:none;overflow:hidden;box-shadow:0 10px 40px rgba(0,0,0,0.15);">

            {{-- Header --}}
            <div class="modal-header" style="background:var(--primary-gradient,linear-gradient(135deg,#2C3E8F,#1A2A5C));color:white;border:none;padding:22px 28px;">
                <div>
                    <h5 class="modal-title" id="adminNotifModalLabel" style="font-weight:800;font-size:1.2rem;margin-bottom:3px;">
                        🔔 Application Notifications
                    </h5>
                    <p style="font-size:0.82rem;opacity:0.82;margin:0;">New applications from residents in your municipality</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body" style="padding:22px 28px;max-height:60vh;overflow-y:auto;background:#fafbfc;">
                @if($hasNew)
                    @foreach($adminNewApplications as $app)
                        @php
                            $label  = $programLabels[$app->program_type] ?? str_replace('_', ' ', $app->program_type);
                            $appDate = $fmtAdminTs($app->created_at ?? $app->application_date);
                        @endphp
                        <div class="admin-notif-card" style="background:white;border-radius:14px;padding:18px 20px;margin-bottom:12px;border:1px solid #e2e8f0;border-left:4px solid var(--secondary-yellow,#FDB913);box-shadow:0 2px 8px rgba(0,0,0,0.04);transition:all 0.2s;">
                            <div style="display:flex;gap:14px;align-items:flex-start;">
                                {{-- Icon --}}
                                <div style="width:46px;height:46px;border-radius:12px;background:#FFF8E1;border:1px solid #FFE082;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;">
                                    📋
                                </div>

                                {{-- Content --}}
                                <div style="flex:1;min-width:0;">
                                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;margin-bottom:6px;">
                                        <div>
                                            <h6 style="font-weight:800;font-size:0.93rem;color:#1e293b;margin:0 0 2px;">
                                                New Application
                                            </h6>
                                            <p style="font-size:0.74rem;color:#94a3b8;margin:0;">
                                                {{ $appDate ? $appDate->diffForHumans() : 'Recently' }}
                                                @if($appDate) <span style="color:#cbd5e1;">•</span> {{ $appDate->format('M d, Y h:i A') }} @endif
                                            </p>
                                        </div>
                                        <span style="padding:3px 10px;border-radius:12px;font-size:0.68rem;font-weight:800;text-transform:uppercase;letter-spacing:0.05em;white-space:nowrap;background:#FFF8E1;color:#856404;flex-shrink:0;">
                                            PENDING
                                        </span>
                                    </div>

                                    <div style="background:#f8fafc;border-radius:10px;padding:10px 14px;margin-bottom:8px;font-size:0.84rem;color:#475569;line-height:1.7;">
                                        <p style="margin:0;"><strong style="color:#1e293b;">Applicant:</strong>
                                            {{ $app->user->full_name ?? ($app->first_name . ' ' . $app->last_name) }}
                                        </p>
                                        <p style="margin:0;"><strong style="color:#1e293b;">Program:</strong> {{ $label }}</p>
                                        @if($app->barangay)
                                        <p style="margin:0;"><strong style="color:#1e293b;">Barangay:</strong> {{ $app->barangay }}</p>
                                        @endif
                                        <p style="margin:0;"><strong style="color:#1e293b;">Applied:</strong> {{ $appDate ? $appDate->format('M d, Y h:i A') : 'N/A' }}</p>
                                    </div>

                                    <a href="{{ route('admin.view-requirement', $app->id) }}"
                                       style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;padding:8px 16px;border-radius:10px;font-size:0.82rem;font-weight:700;text-decoration:none;transition:all 0.2s;">
                                        Review Application →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    @if(!$hasNewAppts && !$hasNewUploads)
                    <div style="text-align:center;padding:60px 20px;">
                        <div style="font-size:3.5rem;margin-bottom:14px;opacity:0.3;">🔔</div>
                        <h6 style="font-weight:700;color:#64748b;margin-bottom:6px;">No new notifications</h6>
                        <p style="font-size:0.85rem;color:#94a3b8;margin:0;">New applications and appointments will appear here.</p>
                    </div>
                    @endif
                @endif

                {{-- New Solo Parent Appointments Section --}}
                @if($hasNewAppts)
                    <div style="margin-top:{{ $hasNew ? '16px' : '0' }};padding-top:{{ $hasNew ? '16px' : '0' }};border-top:{{ $hasNew ? '1px solid #e2e8f0' : 'none' }};">
                        <h6 style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.08em;color:#6366f1;font-weight:700;margin-bottom:12px;">📅 New Solo Parent Appointments</h6>
                        @foreach($adminNewAppointments as $appt)
                        <div class="admin-notif-card" style="background:white;border-radius:14px;border:1.5px solid #e0e7ff;padding:16px 20px;margin-bottom:12px;transition:all 0.2s;">
                            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
                                <div style="flex:1;">
                                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                                        <span style="background:#e0e7ff;color:#3730a3;border-radius:20px;padding:3px 10px;font-size:0.72rem;font-weight:700;">Solo Parent Appointment</span>
                                        <span style="background:#fff8e1;color:#856404;border-radius:20px;padding:3px 10px;font-size:0.72rem;font-weight:700;">⏳ Pending</span>
                                    </div>
                                    <p style="margin:0;font-weight:700;color:#1e293b;font-size:0.9rem;">{{ $appt->user?->full_name ?? 'Unknown' }}</p>
                                    <p style="margin:0;font-size:0.8rem;color:#64748b;">{{ $appt->user?->email ?? '' }}</p>
                                    @php
                                        $apptCreated = $fmtAdminTs($appt->created_at);
                                    @endphp
                                    <p style="margin:2px 0 0;font-size:0.8rem;color:#64748b;">
                                        {{ $apptCreated ? $apptCreated->diffForHumans() : 'Recently' }}
                                        @if($apptCreated) <span style="color:#cbd5e1;">•</span> {{ $apptCreated->format('M d, Y h:i A') }} @endif
                                    </p>
                                    <p style="margin:2px 0 0;font-size:0.8rem;color:#64748b;">
                                        📅 {{ \Carbon\Carbon::parse($appt->appointment_date)->setTimezone('Asia/Manila')->format('F d, Y') }} at {{ \Carbon\Carbon::createFromFormat('H:i', $appt->appointment_time)->setTimezone('Asia/Manila')->format('h:i A') }}
                                        &nbsp;|&nbsp; {{ $appt->interview_type === 'online' ? 'Online' : 'Face-to-Face' }}
                                    </p>
                                </div>
                                <a href="{{ route('admin.requirements') }}"
                                   style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border:none;border-radius:10px;padding:8px 16px;font-size:0.78rem;font-weight:700;white-space:nowrap;text-decoration:none;">
                                    Review
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif

                {{-- New Solo Parent Document Uploads --}}
                @if($hasNewUploads)
                    <div style="margin-top:16px;padding-top:16px;border-top:1px solid #e2e8f0;">
                        <h6 style="font-size:.78rem;text-transform:uppercase;letter-spacing:.08em;color:#dc2626;font-weight:700;margin-bottom:12px;">&#128203; New Documents Uploaded</h6>
                        @foreach($adminNewUploads as $fm)
                        <div class="admin-notif-card" style="background:white;border-radius:14px;border:1.5px solid #fecaca;padding:16px 20px;margin-bottom:12px;transition:all 0.2s;">
                            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
                                <div style="flex:1;">
                                    <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                                        <span style="background:#fee2e2;color:#991b1b;border-radius:20px;padding:3px 10px;font-size:.72rem;font-weight:700;">Solo Parent Docs</span>
                                        <span style="background:#fff8e1;color:#856404;border-radius:20px;padding:3px 10px;font-size:.72rem;font-weight:700;">&#9203; Pending Review</span>
                                    </div>
                                    <p style="margin:0;font-weight:700;color:#1e293b;font-size:.9rem;">{{ $fm->application?->full_name ?? 'Unknown' }}</p>
                                    <p style="margin:0;font-size:.8rem;color:#64748b;">{{ $fm->application?->user?->email ?? '' }}</p>
                                    @php
                                        $latestUploadAt = $fmtAdminTs($fm->fileUploads->whereNotNull('file_path')->max('uploaded_at'));
                                    @endphp
                                    <p style="margin:2px 0 0;font-size:.8rem;color:#64748b;">
                                        {{ $latestUploadAt ? $latestUploadAt->diffForHumans() : 'Recently' }}
                                        @if($latestUploadAt) <span style="color:#cbd5e1;">•</span> {{ $latestUploadAt->format('M d, Y h:i A') }} @endif
                                    </p>
                                    <p style="margin:2px 0 0;font-size:.8rem;color:#64748b;">
                                        &#128196; {{ $fm->fileUploads->whereNotNull('file_path')->count() }} file(s) uploaded
                                        &nbsp;|&nbsp; {{ $fm->municipality }}
                                    </p>
                                </div>
                                <a href="{{ route('admin.view-requirement', $fm->application_id) }}"
                                   style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#dc2626,#b91c1c);color:white;border:none;border-radius:10px;padding:8px 16px;font-size:.78rem;font-weight:700;white-space:nowrap;text-decoration:none;">
                                    Review
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="modal-footer" style="background:#f8f9fa;border:none;padding:14px 28px;display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:0.8rem;color:#64748b;">
                    @if($hasNew) {{ $total }} new application{{ $total != 1 ? 's' : '' }} @endif
                </span>
                <div style="display:flex;gap:10px;">
                    <a href="{{ route('admin.requirements') }}"
                       style="background:var(--secondary-yellow,#FDB913);color:#1A2A5C;border:none;border-radius:10px;padding:9px 20px;font-weight:700;font-size:0.85rem;text-decoration:none;">
                        View All Applications
                    </a>
                    <button type="button" class="btn" data-bs-dismiss="modal"
                        style="background:#2C3E8F;color:white;border:none;border-radius:10px;padding:9px 20px;font-weight:700;font-size:0.85rem;">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .admin-notif-card:hover { box-shadow:0 4px 16px rgba(0,0,0,0.08); transform:translateY(-2px); }
</style>

<script>
document.getElementById('adminNotifModal')?.addEventListener('show.bs.modal', function () {
    fetch('{{ route('admin.mark-notifications-viewed') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? ''
        }
    }).then(r => r.json()).then(d => {
        if (d.success) {
            const badge = document.querySelector('.admin-bell-badge');
            if (badge) badge.style.display = 'none';
        }
    }).catch(() => {});
});
</script>
