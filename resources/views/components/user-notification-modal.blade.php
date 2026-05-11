{{--
    Shared User Notification Bell Modal
    Variables expected:
        $documentNotifications   – FileUpload collection (approved/rejected docs)
        $rejectedApplications    – Application collection (status = rejected)
        $newAnnouncements        – Announcement collection (new since last viewed)
        $notificationCount       – int (badge count)
--}}

@php
    $hasDocNotifs    = isset($documentNotifications) && $documentNotifications->count() > 0;
    $hasAppRejects   = isset($rejectedApplications)  && $rejectedApplications->count() > 0;
    $hasAnnounce     = isset($newAnnouncements)       && $newAnnouncements->count() > 0;
    $hasValidatedAppt = isset($validatedAppointment)  && $validatedAppointment !== null;
    $hasIdReady      = isset($idReadyApplication)     && $idReadyApplication !== null;
    $hasApprovedSoloParent = isset($approvedSoloParentAppointment) && $approvedSoloParentAppointment !== null;
    $hasSoloParentReqValidated = isset($soloParentRequirementsValidated) && $soloParentRequirementsValidated !== null;
    $hasPwdValidated = isset($pwdValidatedApplication) && $pwdValidatedApplication !== null;
    $hasPwdIdReady   = isset($pwdIdReadyApplication) && $pwdIdReadyApplication !== null;
    $hasAicsConfirmed = isset($confirmedAicsAppointments) && $confirmedAicsAppointments->count() > 0;
    $hasAicsValidated = isset($aicsValidatedApplications) && $aicsValidatedApplications->count() > 0;
    $hasAicsReady = isset($aicsReadyApplications) && $aicsReadyApplications->count() > 0;
    $hasAny          = $hasDocNotifs || $hasAppRejects || $hasAnnounce || $hasApprovedSoloParent || $hasValidatedAppt || $hasSoloParentReqValidated || $hasIdReady || $hasPwdValidated || $hasPwdIdReady || $hasAicsConfirmed || $hasAicsValidated || $hasAicsReady;

    $fmtTs = function ($ts) {
        if (!$ts) return null;
        try {
            $c = $ts instanceof \Carbon\CarbonInterface ? $ts : \Carbon\Carbon::parse($ts);
            $c = $c->copy()->setTimezone('Asia/Manila');
            return $c->format('M d, Y h:i A');
        } catch (\Exception $e) {
            return null;
        }
    };

    // Build combined list for "All" tab
    $allItems = collect();

    if ($hasAppRejects) {
        foreach ($rejectedApplications as $app) {
            $allItems->push(['kind' => 'app_rejected', 'data' => $app, 'ts' => $app->created_at ?? $app->application_date]);
        }
    }
    if ($hasDocNotifs) {
        foreach ($documentNotifications as $doc) {
            $allItems->push(['kind' => 'doc_status', 'data' => $doc, 'ts' => $doc->verified_at ?? $doc->uploaded_at]);
        }
    }
    if ($hasAnnounce) {
        foreach ($newAnnouncements as $ann) {
            $allItems->push(['kind' => 'announcement', 'data' => $ann, 'ts' => $ann->created_at]);
        }
    }

    if ($hasIdReady) {
        $allItems->push([
            'kind' => 'solo_parent_id_ready',
            'data' => $idReadyApplication,
            'ts'   => $idReadyApplication->id_ready_at ?? $idReadyApplication->updated_at ?? $idReadyApplication->application_date,
        ]);
    }

    if ($hasApprovedSoloParent) {
        $allItems->push([
            'kind' => 'solo_parent_approved',
            'data' => $approvedSoloParentAppointment,
            'ts'   => $approvedSoloParentAppointment->updated_at ?? $approvedSoloParentAppointment->appointment_date,
        ]);
    }

    if ($hasSoloParentReqValidated) {
        $allItems->push([
            'kind' => 'solo_parent_requirements_validated',
            'data' => $soloParentRequirementsValidated,
            'ts'   => $soloParentRequirementsValidated->completed_at ?? $soloParentRequirementsValidated->application_date,
        ]);
    }

    if ($hasPwdValidated) {
        $allItems->push([
            'kind' => 'pwd_validated',
            'data' => $pwdValidatedApplication,
            'ts'   => $pwdValidatedApplication->completed_at ?? $pwdValidatedApplication->application_date,
        ]);
    }

    if ($hasPwdIdReady) {
        $allItems->push([
            'kind' => 'pwd_id_ready',
            'data' => $pwdIdReadyApplication,
            'ts'   => $pwdIdReadyApplication->id_ready_at ?? $pwdIdReadyApplication->application_date,
        ]);
    }

    if ($hasValidatedAppt) {
        $allItems->push([
            'kind' => 'solo_parent_eligible',
            'data' => $validatedAppointment,
            'ts'   => $validatedAppointment->validated_at ?? $validatedAppointment->updated_at,
        ]);
    }

    if ($hasAicsConfirmed) {
        foreach ($confirmedAicsAppointments as $aicsAppt) {
            $allItems->push([
                'kind' => 'aics_confirmed',
                'data' => $aicsAppt,
                'ts'   => $aicsAppt->updated_at ?? $aicsAppt->appointment_date,
            ]);
        }
    }

    if ($hasAicsValidated) {
        foreach ($aicsValidatedApplications as $aicsApp) {
            $allItems->push([
                'kind' => 'aics_validated',
                'data' => $aicsApp,
                'ts'   => $aicsApp->completed_at ?? $aicsApp->application_date,
            ]);
        }
    }

    if ($hasAicsReady) {
        foreach ($aicsReadyApplications as $aicsApp) {
            $allItems->push([
                'kind' => 'aics_ready',
                'data' => $aicsApp,
                'ts'   => $aicsApp->id_ready_at ?? $aicsApp->application_date,
            ]);
        }
    }

    $allItems = $allItems->sortByDesc('ts');

    $annCount   = isset($newAnnouncementCount) ? $newAnnouncementCount : (isset($newAnnouncements) ? $newAnnouncements->count() : 0);
    $totalCount = $allItems->count();
@endphp

<div class="modal fade" id="announcementsModal" tabindex="-1" aria-labelledby="announcementsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:20px;border:none;overflow:hidden;box-shadow:0 10px 40px rgba(0,0,0,0.15);">

            {{-- Header --}}
            <div class="modal-header" style="background:var(--primary-gradient,linear-gradient(135deg,#2C3E8F,#1A2A5C));color:white;border:none;padding:22px 28px;">
                <div>
                    <h5 class="modal-title" id="announcementsModalLabel" style="font-weight:800;font-size:1.2rem;margin-bottom:3px;">
                        Notifications
                    </h5>
                    <p style="font-size:0.82rem;opacity:0.82;margin:0;">Application updates &amp; new announcements</p>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            {{-- Filter tabs --}}
            @if($hasAny)
            <div style="background:#f8f9fa;border-bottom:1px solid #e2e8f0;padding:14px 28px;">
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    <button class="nfb active" data-filter="all" onclick="filterNotif('all')">
                        All <span class="nfc">({{ $totalCount }})</span>
                    </button>
                    @if($hasAnnounce)
                    <button class="nfb" data-filter="announcement" onclick="filterNotif('announcement')" style="border-color:#2C3E8F;">
                        Announcements <span class="nfc" style="background:#E5EEFF;color:#2C3E8F;">{{ $annCount }}</span>
                    </button>
                    @endif
                    @if($hasDocNotifs || $hasAppRejects)
                    <button class="nfb" data-filter="rejected" onclick="filterNotif('rejected')">
                        Rejected <span class="nfc"></span>
                    </button>
                    <button class="nfb" data-filter="approved" onclick="filterNotif('approved')">
                        Approved <span class="nfc"></span>
                    </button>
                    @endif
                </div>
            </div>
            @endif

            {{-- Body --}}
            <div class="modal-body" style="padding:22px 28px;max-height:60vh;overflow-y:auto;background:#fafbfc;">
                @if($hasAny)
                    <div id="notifList">
                    @foreach($allItems as $item)
                        @php
                            $kind     = $item['kind'];
                            $d        = $item['data'];
                            $isReject = ($kind === 'app_rejected') || ($kind === 'doc_status' && $d->status === 'rejected');
                            $isApprove= ($kind === 'doc_status' && $d->status === 'approved');
                            $isAnn    = ($kind === 'announcement');
                            $isOther  = in_array($kind, ['solo_parent_id_ready','solo_parent_eligible','solo_parent_approved','solo_parent_requirements_validated','aics_confirmed','aics_validated','aics_ready','pwd_validated','pwd_id_ready'], true);
                            $fClass   = $isAnn ? 'announcement' : ($isReject ? 'rejected' : 'approved');
                            $tsHuman  = $item['ts']
                                ? \Carbon\Carbon::parse($item['ts'])->setTimezone('Asia/Manila')->diffForHumans()
                                : 'Recently';
                            $tsExact  = $fmtTs($item['ts']);
                        @endphp

                        <div class="notif-card" data-filter="{{ $fClass }}"
                            style="background:white;border-radius:14px;padding:18px 20px;margin-bottom:12px;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,0.04);transition:all 0.2s;">
                                <div style="min-width:0;">
                                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;margin-bottom:6px;">
                                        <div>
                                            <h6 style="font-weight:800;font-size:0.92rem;color:#1e293b;margin:0 0 2px;">
                                                @if($kind === 'solo_parent_id_ready')
                                                    Your Solo Parent ID is Ready
                                                @elseif($kind === 'solo_parent_requirements_validated')
                                                    Solo Parent Requirements Validated
                                                @elseif($kind === 'solo_parent_approved')
                                                    Solo Parent Appointment Approved
                                                @elseif($kind === 'pwd_id_ready')
                                                    Your PWD ID is Ready
                                                @elseif($kind === 'pwd_validated')
                                                    PWD Requirements Validated
                                                @elseif($kind === 'solo_parent_eligible')
                                                    Eligible for Solo Parent ID
                                                @elseif($kind === 'aics_confirmed')
                                                    Appointment Confirmed
                                                @elseif($kind === 'aics_validated')
                                                    AICS Requirements Validated
                                                @elseif($kind === 'aics_ready')
                                                    AICS Grant Ready for Pickup
                                                @elseif($isAnn)
                                                    {{ $d->title ?: 'New Announcement' }}
                                                @elseif($kind === 'app_rejected') Application Declined
                                                @elseif($isReject) Document Rejected
                                                @else Document Approved
                                                @endif
                                            </h6>
                                            <p style="font-size:0.74rem;color:#94a3b8;margin:0;">
                                                {{ $tsHuman }}@if($tsExact) <span style="color:#cbd5e1;">•</span> {{ $tsExact }}@endif
                                            </p>
                                        </div>
                                        <span style="padding:3px 10px;border-radius:12px;font-size:0.68rem;font-weight:800;text-transform:uppercase;letter-spacing:0.05em;white-space:nowrap;flex-shrink:0;
                                            {{ $isAnn ? 'background:#E5EEFF;color:#2C3E8F;' : ($isReject ? 'background:#fee2e2;color:#991b1b;' : 'background:#dcfce7;color:#166534;') }}">
                                            @if($kind === 'solo_parent_id_ready')
                                                READY
                                            @elseif($kind === 'solo_parent_requirements_validated')
                                                VALIDATED
                                            @elseif($kind === 'solo_parent_approved')
                                                APPROVED
                                            @elseif($kind === 'pwd_id_ready')
                                                READY
                                            @elseif($kind === 'pwd_validated')
                                                VALIDATED
                                            @elseif($kind === 'solo_parent_eligible')
                                                ELIGIBLE
                                            @elseif($kind === 'aics_confirmed')
                                                CONFIRMED
                                            @elseif($kind === 'aics_validated')
                                                VALIDATED
                                            @elseif($kind === 'aics_ready')
                                                READY
                                            @else
                                                {{ $isAnn ? strtoupper($d->type) : ($isReject ? 'Action Required' : 'Completed') }}
                                            @endif
                                        </span>
                                    </div>

                                    {{-- Details block --}}
                                    <div style="background:#f8fafc;border-radius:10px;padding:10px 14px;margin-bottom:8px;font-size:0.84rem;color:#475569;line-height:1.6;">
                                        @if($kind === 'solo_parent_id_ready')
                                            <p style="margin:0;">Please pick up your Solo Parent ID at the <strong>{{ $d->municipality }} MSWDO Office</strong>.</p>
                                        @elseif($kind === 'solo_parent_requirements_validated')
                                            <p style="margin:0;">Your Solo Parent requirements are fully validated. Please wait for the ID ready notice.</p>
                                        @elseif($kind === 'solo_parent_approved')
                                            <p style="margin:0;">Your Solo Parent appointment has been approved. Please wait for your eligibility result from MSWDO.</p>
                                        @elseif($kind === 'pwd_id_ready')
                                            <p style="margin:0;">Your PWD ID is ready for pick-up at the <strong>{{ $d->municipality }} MSWDO Office</strong>.</p>
                                        @elseif($kind === 'pwd_validated')
                                            <p style="margin:0;">Your PWD requirements are validated. Please wait for a follow-up notice when your ID is ready for release.</p>
                                        @elseif($kind === 'solo_parent_eligible')
                                            <p style="margin:0;">Congratulations! You passed the eligibility assessment. Please submit your documents to complete your application.</p>
                                        @elseif($kind === 'aics_confirmed')
                                            @php
                                                $aicsLabel = match($d->program_type) {
                                                    'AICS_Medical' => 'AICS Medical Assistance',
                                                    'AICS_Burial'  => 'AICS Burial Assistance',
                                                    default        => str_replace('_', ' ', $d->program_type),
                                                };
                                                $aicsDate = $d->appointment_date ? $d->appointment_date->format('M d, Y') : null;
                                            @endphp
                                            <p style="margin:0;">
                                                Your <strong>{{ $aicsLabel }}</strong> appointment
                                                @if($aicsDate) on <strong>{{ $aicsDate }}</strong>@endif
                                                @if($d->formatted_time) at <strong>{{ $d->formatted_time }}</strong>@endif
                                                has been confirmed.
                                            </p>
                                        @elseif($kind === 'aics_validated')
                                            @php
                                                $aicsLabel = match($d->program_type) {
                                                    'AICS_Medical' => 'AICS Medical Assistance',
                                                    'AICS_Burial'  => 'AICS Burial Assistance',
                                                    default        => str_replace('_', ' ', $d->program_type),
                                                };
                                            @endphp
                                            <p style="margin:0;">Your <strong>{{ $aicsLabel }}</strong> requirements are validated. Please wait for grant release notice.</p>
                                        @elseif($kind === 'aics_ready')
                                            @php
                                                $aicsLabel = match($d->program_type) {
                                                    'AICS_Medical' => 'AICS Medical Assistance',
                                                    'AICS_Burial'  => 'AICS Burial Assistance',
                                                    default        => str_replace('_', ' ', $d->program_type),
                                                };
                                            @endphp
                                            <p style="margin:0;">Your <strong>{{ $aicsLabel }}</strong> grant is ready for pickup at MSWDO.</p>
                                        @elseif($isAnn)
                                            <p style="margin:0;">{{ Str::limit($d->content, 120) }}</p>
                                            @if($d->municipality && $d->municipality !== 'all')
                                                <p style="margin:4px 0 0;font-size:0.76rem;color:#94a3b8;">{{ $d->municipality }}</p>
                                            @endif
                                        @elseif($kind === 'app_rejected')
                                            <p style="margin:0 0 4px;"><strong style="color:#1e293b;">Program:</strong> {{ str_replace('_', ' ', $d->program_type) }}</p>
                                            <p style="margin:0;"><strong style="color:#1e293b;">Applied:</strong> {{ optional($d->application_date)->format('M d, Y') ?? 'N/A' }}</p>
                                        @else
                                            <p style="margin:0 0 4px;"><strong style="color:#1e293b;">Document:</strong> {{ $d->requirement_name }}</p>
                                            @if($d->fileMonitoring && $d->fileMonitoring->application)
                                                <p style="margin:0;"><strong style="color:#1e293b;">Program:</strong> {{ str_replace('_', ' ', $d->fileMonitoring->application->program_type) }}</p>
                                            @endif
                                        @endif
                                    </div>

                                    {{-- Rejection reason --}}
                                    @if(!$isAnn && $isReject)
                                        @php
                                            $remark = null;
                                            if ($kind === 'app_rejected' && $d->fileMonitoring) {
                                                $rf = $d->fileMonitoring->fileUploads->where('status','rejected')->first();
                                                $remark = $rf ? $rf->admin_remarks : null;
                                            } elseif ($kind === 'doc_status') {
                                                $remark = $d->admin_remarks ?? null;
                                            }
                                        @endphp
                                        @if($remark)
                                        <div style="background:#fef2f2;border-left:3px solid #dc3545;border-radius:8px;padding:10px 13px;margin-bottom:8px;font-size:0.8rem;color:#991b1b;">
                                            <strong style="display:block;margin-bottom:3px;font-size:0.72rem;text-transform:uppercase;letter-spacing:0.05em;">Reason:</strong>
                                            {{ $remark }}
                                        </div>
                                        @endif
                                    @endif

                                    {{-- Action button --}}
                                    @if($kind === 'solo_parent_approved')
                                        <a href="{{ route('user.solo-parent-application') }}" style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;padding:8px 16px;border-radius:10px;font-size:0.82rem;font-weight:700;text-decoration:none;transition:all 0.2s;">
                                            View appointment
                                        </a>
                                    @elseif($kind === 'solo_parent_eligible')
                                        <a href="{{ route('user.solo-parent-application') }}" style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;padding:8px 16px;border-radius:10px;font-size:0.82rem;font-weight:700;text-decoration:none;transition:all 0.2s;">
                                            Submit requirements
                                        </a>
                                    @elseif($kind === 'aics_confirmed')
                                        @php
                                            $aicsRoute = $d->program_type === 'AICS_Medical'
                                                ? route('user.aics-medical')
                                                : route('user.aics-burial');
                                        @endphp
                                        <a href="{{ $aicsRoute }}" style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#28a745,#155724);color:white;padding:8px 16px;border-radius:10px;font-size:0.82rem;font-weight:700;text-decoration:none;transition:all 0.2s;">
                                            Submit requirements
                                        </a>
                                    @elseif($kind === 'aics_validated' || $kind === 'aics_ready')
                                        @php
                                            $aicsRoute = $d->program_type === 'AICS_Medical'
                                                ? route('user.aics-medical')
                                                : route('user.aics-burial');
                                        @endphp
                                        <a href="{{ $aicsRoute }}" style="display:inline-flex;align-items:center;gap:6px;background:#E5EEFF;color:#2C3E8F;padding:8px 16px;border-radius:10px;font-size:0.82rem;font-weight:700;text-decoration:none;transition:all 0.2s;">
                                            View AICS status
                                        </a>
                                    @elseif($isAnn)
                                        <a href="{{ route('user.announcements') }}" style="display:inline-flex;align-items:center;gap:6px;background:#E5EEFF;color:#2C3E8F;padding:8px 16px;border-radius:10px;font-size:0.82rem;font-weight:700;text-decoration:none;transition:all 0.2s;">
                                            View all announcements
                                        </a>
                                    @elseif($isReject)
                                        @php $targetId = ($kind === 'app_rejected') ? 'app-'.$d->id : 'file-'.$d->id; @endphp
                                        <a href="{{ route('user.my-requirements') }}#{{ $targetId }}" style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;padding:8px 16px;border-radius:10px;font-size:0.82rem;font-weight:700;text-decoration:none;transition:all 0.2s;">
                                            Re-upload documents
                                        </a>
                                    @endif
                                </div>
                        </div>
                    @endforeach
                    </div>
                    @else
                    <div style="text-align:center;padding:60px 20px;">
                        <h6 style="font-weight:700;color:#64748b;margin-bottom:6px;">No notifications yet</h6>
                        <p style="font-size:0.85rem;color:#94a3b8;margin:0;">Application updates and new announcements will appear here.</p>
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="modal-footer" style="background:#f8f9fa;border:none;padding:14px 28px;display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:0.8rem;color:#64748b;">
                    @if($hasAny) {{ $totalCount }} notification{{ $totalCount != 1 ? 's' : '' }} @endif
                </span>
                <button type="button" class="btn" data-bs-dismiss="modal"
                    style="background:#2C3E8F;color:white;border:none;border-radius:10px;padding:9px 22px;font-weight:700;font-size:0.85rem;">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .nfb { padding:6px 14px;border-radius:20px;border:1.5px solid #cbd5e1;background:white;color:#64748b;font-size:0.82rem;font-weight:700;cursor:pointer;transition:all 0.2s; }
    .nfb:hover { background:#f1f5f9; border-color:#94a3b8; }
    .nfb.active { background:#2C3E8F !important; color:white !important; border-color:#2C3E8F !important; }
    .nfc { display:inline-block; background:#f1f5f9; color:#64748b; border-radius:20px; padding:1px 7px; font-size:0.72rem; margin-left:2px; }
    .notif-card:hover { box-shadow:0 4px 16px rgba(0,0,0,0.08); transform:translateY(-2px); }
    .notif-card.hidden { display:none !important; }
</style>

<script>
function filterNotif(f) {
    document.querySelectorAll('.nfb').forEach(b => b.classList.remove('active'));
    document.querySelector(`.nfb[data-filter="${f}"]`)?.classList.add('active');
    document.querySelectorAll('.notif-card').forEach(c => {
        c.classList.toggle('hidden', f !== 'all' && c.dataset.filter !== f);
    });
}
// Mark viewed when modal opens
document.getElementById('announcementsModal')?.addEventListener('show.bs.modal', function () {
    fetch('{{ route('user.mark-notifications-viewed') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content }
    }).then(r => r.json()).then(d => {
        if (d.success) {
            const badge = document.querySelector('.bell-badge');
            if (badge) badge.style.display = 'none';
        }
    }).catch(() => {});
});
</script>
