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
    $hasAny          = $hasDocNotifs || $hasAppRejects || $hasAnnounce || $hasValidatedAppt || $hasIdReady;

    // Build combined list for "All" tab
    $allItems = collect();

    if ($hasAppRejects) {
        foreach ($rejectedApplications as $app) {
            $allItems->push(['kind' => 'app_rejected', 'data' => $app, 'ts' => $app->application_date]);
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
    $allItems = $allItems->sortByDesc('ts');

    $annCount   = isset($newAnnouncements) ? $newAnnouncements->count() : 0;
    $totalCount = $allItems->count();
@endphp

<div class="modal fade" id="announcementsModal" tabindex="-1" aria-labelledby="announcementsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:20px;border:none;overflow:hidden;box-shadow:0 10px 40px rgba(0,0,0,0.15);">

            {{-- Header --}}
            <div class="modal-header" style="background:var(--primary-gradient,linear-gradient(135deg,#2C3E8F,#1A2A5C));color:white;border:none;padding:22px 28px;">
                <div>
                    <h5 class="modal-title" id="announcementsModalLabel" style="font-weight:800;font-size:1.2rem;margin-bottom:3px;">
                        🔔 Notifications
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
                        📢 Announcements <span class="nfc" style="background:#E5EEFF;color:#2C3E8F;">{{ $annCount }}</span>
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
                            $fClass   = $isAnn ? 'announcement' : ($isReject ? 'rejected' : 'approved');
                        @endphp

                        <div class="notif-card" data-filter="{{ $fClass }}"
                            style="background:white;border-radius:14px;padding:18px 20px;margin-bottom:12px;border:1px solid #e2e8f0;box-shadow:0 2px 8px rgba(0,0,0,0.04);transition:all 0.2s;">
                            <div style="display:flex;gap:14px;align-items:flex-start;">

                                {{-- Icon --}}
                                @if($isAnn)
                                    @php $typeColors = ['general'=>'#2C3E8F','event'=>'#856404','emergency'=>'#991b1b','program_update'=>'#155724']; $tc = $typeColors[$d->type] ?? '#2C3E8F'; @endphp
                                    <div style="width:46px;height:46px;border-radius:12px;background:#E5EEFF;border:1px solid #c7d6f5;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;">
                                        📢
                                    </div>
                                @elseif($kind === 'app_rejected')
                                    <div style="width:46px;height:46px;border-radius:12px;background:#fee2e2;border:1px solid #fca5a5;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;">⚠️</div>
                                @elseif($isReject)
                                    <div style="width:46px;height:46px;border-radius:12px;background:#fee2e2;border:1px solid #fca5a5;display:flex;align-items:center;justify-content:center;color:#dc3545;font-size:1.3rem;font-weight:900;flex-shrink:0;">✕</div>
                                @else
                                    <div style="width:46px;height:46px;border-radius:12px;background:#dcfce7;border:1px solid #86efac;display:flex;align-items:center;justify-content:center;color:#16a34a;font-size:1.3rem;font-weight:900;flex-shrink:0;">✓</div>
                                @endif

                                {{-- Content --}}
                                <div style="flex:1;min-width:0;">
                                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:10px;margin-bottom:6px;">
                                        <div>
                                            <h6 style="font-weight:800;font-size:0.92rem;color:#1e293b;margin:0 0 2px;">
                                                @if($isAnn)
                                                    {{ $d->title ?: 'New Announcement' }}
                                                @elseif($kind === 'app_rejected') Application Declined
                                                @elseif($isReject) Document Rejected
                                                @else Document Approved
                                                @endif
                                            </h6>
                                            <p style="font-size:0.74rem;color:#94a3b8;margin:0;">{{ optional($item['ts'])->diffForHumans() ?? 'Recently' }}</p>
                                        </div>
                                        <span style="padding:3px 10px;border-radius:12px;font-size:0.68rem;font-weight:800;text-transform:uppercase;letter-spacing:0.05em;white-space:nowrap;flex-shrink:0;
                                            {{ $isAnn ? 'background:#E5EEFF;color:#2C3E8F;' : ($isReject ? 'background:#fee2e2;color:#991b1b;' : 'background:#dcfce7;color:#166534;') }}">
                                            {{ $isAnn ? strtoupper($d->type) : ($isReject ? 'Action Required' : 'Completed') }}
                                        </span>
                                    </div>

                                    {{-- Details block --}}
                                    <div style="background:#f8fafc;border-radius:10px;padding:10px 14px;margin-bottom:8px;font-size:0.84rem;color:#475569;line-height:1.6;">
                                        @if($isAnn)
                                            <p style="margin:0;">{{ Str::limit($d->content, 120) }}</p>
                                            @if($d->municipality && $d->municipality !== 'all')
                                                <p style="margin:4px 0 0;font-size:0.76rem;color:#94a3b8;">📍 {{ $d->municipality }}</p>
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
                                    @if($isAnn)
                                        <a href="{{ route('user.announcements') }}" style="display:inline-flex;align-items:center;gap:6px;background:#E5EEFF;color:#2C3E8F;padding:8px 16px;border-radius:10px;font-size:0.82rem;font-weight:700;text-decoration:none;transition:all 0.2s;">
                                            View All Announcements →
                                        </a>
                                    @elseif($isReject)
                                        @php $targetId = ($kind === 'app_rejected') ? 'app-'.$d->id : 'file-'.$d->id; @endphp
                                        <a href="{{ route('user.my-requirements') }}#{{ $targetId }}" style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;padding:8px 16px;border-radius:10px;font-size:0.82rem;font-weight:700;text-decoration:none;transition:all 0.2s;">
                                            📤 Re-upload Documents
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                @if($hasIdReady)
                    {{-- 🎫 Solo Parent ID Ready for Pick-Up --}}
                    <div style="background:white;border:1.5px solid #86efac;border-radius:14px;padding:16px 20px;margin-bottom:12px;transition:all 0.2s;">
                        <div style="display:flex;align-items:flex-start;gap:12px;">
                            <div style="width:46px;height:46px;min-width:46px;border-radius:12px;background:linear-gradient(135deg,#16a34a,#15803d);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:white;">🎫</div>
                            <div style="flex:1;">
                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                                    <span style="background:#dcfce7;color:#166534;border-radius:20px;padding:2px 10px;font-size:.72rem;font-weight:700;">Solo Parent ID</span>
                                    <span style="background:#d4edda;color:#155724;border-radius:20px;padding:2px 10px;font-size:.72rem;font-weight:700;">✅ Ready</span>
                                </div>
                                <div style="font-weight:800;color:#1e293b;font-size:.92rem;">Your Solo Parent ID is Ready for Pick-Up!</div>
                                <div style="font-size:.82rem;color:#64748b;margin-top:4px;line-height:1.6;">
                                    Your Solo Parent ID has been processed and is now available at the
                                    <strong>{{ $idReadyApplication->municipality }} MSWDO Office</strong>.
                                    Please bring a valid ID when claiming.
                                </div>
                                <div style="font-size:.78rem;color:#94a3b8;margin-top:4px;">
                                    📅 Office Hours: Monday – Friday, 8:00 AM – 5:00 PM
                                    &nbsp;|&nbsp;
                                    Ready since: {{ optional($idReadyApplication->id_ready_at)->diffForHumans() ?? 'Recently' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

    @if($hasValidatedAppt)
                    {{-- ✔️ Validated Solo Parent Appointment notification --}}
                    <div style="background:white;border:1.5px solid #cce5ff;border-radius:14px;padding:16px 20px;margin-bottom:12px;transition:all 0.2s;">
                        <div style="display:flex;align-items:flex-start;gap:12px;">
                            <div style="width:40px;height:40px;min-width:40px;border-radius:50%;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:white;">
                                🏆
                            </div>
                            <div style="flex:1;">
                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                                    <span style="background:#cce5ff;color:#004085;border-radius:20px;padding:2px 10px;font-size:.72rem;font-weight:700;">Solo Parent ID</span>
                                    <span style="background:#d4edda;color:#155724;border-radius:20px;padding:2px 10px;font-size:.72rem;font-weight:700;">✅ Eligible</span>
                                </div>
                                <div style="font-weight:700;color:#1e293b;font-size:.9rem;">You are Eligible for Solo Parent ID!</div>
                                <div style="font-size:.82rem;color:#64748b;margin-top:3px;line-height:1.5;">Congratulations! You passed the eligibility assessment. Please submit your required documents to complete your application.</div>
                                <a href="{{ route('user.solo-parent-application') }}" style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border-radius:8px;padding:7px 16px;font-size:.78rem;font-weight:700;margin-top:8px;text-decoration:none;">📁 Submit Requirements</a>
                            </div>
                        </div>
                    </div>
                    @endif

                    @else
                    <div style="text-align:center;padding:60px 20px;">
                        <div style="font-size:3.5rem;margin-bottom:14px;opacity:0.3;">🔔</div>
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
