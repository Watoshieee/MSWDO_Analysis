<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title data-en="Solo Parent ID Guide  MSWDO" data-tl="Gabay sa Solo Parent ID - MSWDO">Solo Parent ID Guide  MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
html, body { overscroll-behavior: none; margin: 0; padding: 0; }

        :root {
            --primary-blue:#2C3E8F; --primary-blue-light:#E5EEFF;
            --secondary-yellow:#FDB913; --secondary-yellow-light:#FFF3D6;
            --primary-gradient:linear-gradient(135deg,#2C3E8F 0%,#1A2A5C 100%);
            --secondary-gradient:linear-gradient(135deg,#FDB913 0%,#E5A500 100%);
            --bg-light:#F8FAFC; --border-light:#E2E8F0; --text-dark:#1E293B;
        }
        *,body{font-family:'Inter','Segoe UI',sans-serif;}
        body{background:var(--bg-light);color:var(--text-dark);display:flex;flex-direction:column;min-height:100vh;margin:0;}
        a{text-decoration:none;}

        /* TOP BAR */
        .top-bar{background:var(--primary-gradient);padding:14px 0;box-shadow:0 4px 20px rgba(44,62,143,.22);}
        .top-bar-inner{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;}
        .brand{display:flex;align-items:center;gap:12px;color:white;font-weight:800;font-size:1.45rem;}
        .brand img{width:34px;height:34px;object-fit:contain;}
        .back-btn{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:2px solid rgba(255,255,255,.4);color:white;border-radius:30px;padding:8px 22px;font-weight:700;font-size:.88rem;cursor:pointer;transition:all .3s;text-decoration:none;}
        .back-btn:hover{background:var(--secondary-yellow);color:var(--primary-blue);border-color:var(--secondary-yellow);}

        /* HERO */
        .hero-banner{background:var(--primary-gradient);color:white;padding:56px 0 44px;position:relative;overflow:hidden;}
        .hero-banner::before{content:'';position:absolute;top:-90px;right:-90px;width:360px;height:360px;border-radius:50%;background:rgba(253,185,19,.10);}
        .hero-banner::after{content:'';position:absolute;bottom:-60px;left:-50px;width:240px;height:240px;border-radius:50%;background:rgba(255,255,255,.04);}
        .hero-inner{position:relative;z-index:2;}
        .hero-badge{display:inline-block;background:rgba(253,185,19,.18);color:var(--secondary-yellow);border:1px solid rgba(253,185,19,.35);border-radius:30px;padding:5px 18px;font-size:.75rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;margin-bottom:16px;}
        .hero-banner h1{font-size:2.5rem;font-weight:900;margin-bottom:8px;line-height:1.12;}
        .hero-divider{width:50px;height:4px;background:var(--secondary-yellow);border-radius:2px;margin:16px 0;}
        .hero-banner p.hero-sub{opacity:.85;font-size:.97rem;margin:0;max-width:600px;line-height:1.7;}

        /* Language toggle */
        .lang-toggle{display:inline-flex;border-radius:30px;overflow:hidden;border:2px solid rgba(255,255,255,.4);}
        .lang-btn{background:transparent;border:none;color:rgba(255,255,255,.7);font-weight:700;font-size:.82rem;padding:8px 20px;cursor:pointer;transition:all .2s;letter-spacing:.05em;}
        .lang-btn.active{background:var(--secondary-yellow);color:var(--primary-blue);}
        .lang-btn:hover:not(.active){background:rgba(255,255,255,.15);color:white;}

        /* SECTION CARD */
        .section-card{background:white;border-radius:20px;border:1px solid var(--border-light);box-shadow:0 4px 16px rgba(0,0,0,.04);overflow:hidden;margin-bottom:28px;}
        .section-header{background:var(--primary-gradient);color:white;padding:20px 26px;display:flex;align-items:center;gap:16px;}
        .sec-icon{width:44px;height:44px;background:rgba(253,185,19,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;}
        .section-header h4{font-weight:800;margin:0;font-size:1.05rem;}
        .section-header p{margin:0;opacity:.82;font-size:.84rem;margin-top:2px;}
        .section-body{padding:28px;}

        /* Steps */
        .step-item{display:flex;gap:18px;margin-bottom:24px;align-items:flex-start;}
        .step-item:last-child{margin-bottom:0;}
        .step-num{width:40px;height:40px;min-width:40px;border-radius:50%;background:var(--primary-gradient);color:white;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1rem;box-shadow:0 4px 14px rgba(44,62,143,.28);}
        .step-content{flex:1;}
        .step-title{font-weight:700;color:var(--primary-blue);font-size:.97rem;margin-bottom:4px;}
        .step-desc{font-size:.87rem;color:#475569;line-height:1.65;}
        .step-note{background:var(--secondary-yellow-light);border-left:3px solid var(--secondary-yellow);border-radius:8px;padding:10px 14px;font-size:.82rem;color:#856404;margin-top:10px;}
        .connector{margin-left:19px;border-left:2px dashed var(--border-light);height:16px;}

        /* Info cards */
        .info-card{background:var(--primary-blue-light);border:1px solid rgba(44,62,143,.12);border-radius:14px;padding:18px 22px;margin-bottom:16px;}
        .info-card.yellow{background:var(--secondary-yellow-light);border-color:rgba(253,185,19,.3);}
        .info-card.placeholder{background:#f8f9fa;border:2px dashed #dee2e6;text-align:center;}
        .info-card .ic-title{font-weight:700;color:var(--primary-blue);font-size:.88rem;margin-bottom:6px;}
        .info-card .ic-body{font-size:.85rem;color:#475569;line-height:1.65;}

        /* Action buttons */
        .btn-yellow{display:inline-flex;align-items:center;gap:10px;background:var(--secondary-gradient);color:var(--primary-blue);border:none;border-radius:12px;padding:13px 28px;font-weight:800;font-size:.92rem;cursor:pointer;transition:all .3s;text-decoration:none;justify-content:center;}
        .btn-yellow:hover{transform:translateY(-2px);box-shadow:0 8px 22px rgba(253,185,19,.45);color:var(--primary-blue);}
        .btn-purple{display:inline-flex;align-items:center;gap:8px;background:var(--primary-gradient);color:white;border:none;border-radius:12px;padding:13px 28px;font-weight:700;font-size:.92rem;cursor:pointer;transition:all .3s;text-decoration:none;justify-content:center;}
        .btn-purple:hover{opacity:.9;transform:translateY(-2px);box-shadow:0 8px 22px rgba(44,62,143,.35);color:white;}

        /* CTA strip */
        .cta-strip{background:var(--primary-gradient);border-radius:16px;padding:22px 30px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;}
        .cta-strip .cta-text-main{color:white;font-weight:800;font-size:1rem;margin-bottom:3px;}
        .cta-strip .cta-text-sub{color:rgba(255,255,255,.75);font-size:.85rem;}

        /* Footer */
        .footer-strip{background:var(--primary-gradient);color:rgba(255,255,255,.85);text-align:center;padding:18px;font-size:.85rem;margin-top:auto;}
        .footer-strip strong{color:white;}
    </style>
</head>
<body>

    <!-- TOP BAR -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-inner">
                <div class="brand">
                    <img src="{{ asset('images/mswd-logo.png') }}" alt="MSWD">
                    <span>MSWDO</span>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="lang-toggle">
                        <button class="lang-btn active" data-lang="en" onclick="setLang('en')">EN</button>
                        <button class="lang-btn"        data-lang="tl" onclick="setLang('tl')">TL</button>
                    </div>
                    <a href="{{ route('user.dashboard') }}" class="back-btn">
                         <span data-en="Back to Dashboard" data-tl="Bumalik sa Dashboard">Back to Dashboard</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- HERO -->
    <section class="hero-banner">
        <div class="container">
            <div class="hero-inner">
                <div class="hero-badge" data-en="Solo Parent Services" data-tl="Mga Serbisyo para sa Solo Parent">Solo Parent Services</div>
                <h1 data-en="Solo Parent ID Application Guide" data-tl="Gabay sa Pag-apply ng Solo Parent ID">Solo Parent ID Application Guide</h1>
                <div class="hero-divider"></div>
                <p class="hero-sub"
                   data-en="A complete, step-by-step guide to applying for a Solo Parent ID through the MSWDO  from scheduling an appointment to receiving your ID."
                   data-tl="Isang kumpletong hakbang-hakbang na gabay sa pag-apply ng Solo Parent ID sa MSWDO  mula sa pag-schedule ng appointment hanggang sa pagtanggap ng iyong ID.">
                    A complete, step-by-step guide to applying for a Solo Parent ID through the MSWDO  from scheduling an appointment to receiving your ID.
                </p>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════════════
         APPOINTMENT BOOKING SECTION
    ════════════════════════════════════════════════════════════════════════ --}}
    <div class="container" style="padding-top:32px;">

        {{-- Flash messages --}}
        @php
            $topNotice = session('appt_success') ?: session('appt_error');
        @endphp
        @if($topNotice)
        <div style="position:fixed;top:{{ !empty($isSoloParentBeneficiary) ? '150px' : '84px' }};right:18px;z-index:1081;max-width:420px;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border:1px solid rgba(255,255,255,.18);border-radius:12px;padding:12px 16px;box-shadow:0 10px 28px rgba(26,42,92,.35);font-size:.84rem;font-weight:700;">
            {{ $topNotice }}
        </div>
        @endif

        @if(!empty($isSoloParentBeneficiary))
        <div style="position:fixed;top:84px;right:18px;z-index:1080;max-width:420px;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border:1px solid rgba(255,255,255,.18);border-radius:12px;padding:12px 16px;box-shadow:0 10px 28px rgba(26,42,92,.35);font-size:.84rem;font-weight:700;">
            Solo Parent beneficiary na ang account na ito. Re-application is disabled.
        </div>
        @endif

        {{-- ── ACTIVE APPOINTMENT CARD ── --}}
        @if(!empty($isSoloParentBeneficiary))
        <div style="background:white;border-radius:20px;border:1px solid #c7d6f5;box-shadow:0 4px 20px rgba(44,62,143,.12);overflow:hidden;margin-bottom:24px;">
            <div style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;padding:18px 26px;display:flex;align-items:center;gap:14px;">
                <div style="width:42px;height:42px;background:rgba(255,255,255,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;">🪪</div>
                <div style="font-weight:800;font-size:1rem;">Solo Parent Beneficiary</div>
            </div>
            <div style="padding:20px 26px;color:#1e293b;font-size:.9rem;line-height:1.7;">
                May na-release na / beneficiary na ang iyong Solo Parent application, kaya disabled na ang new appointment at re-application.
            </div>
        </div>
        @elseif($appointment && in_array($appointment->status, ['pending','confirmed']))
        <div style="background:white;border-radius:20px;border:1px solid #c7d2fe;box-shadow:0 4px 20px rgba(44,62,143,.08);overflow:hidden;margin-bottom:24px;">
            <div style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;padding:18px 26px;display:flex;align-items:center;gap:14px;">
                <div style="width:42px;height:42px;background:rgba(253,185,19,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;">📅</div>
                <div style="flex:1;">
                    <div style="font-weight:800;font-size:1rem;">Your Appointment</div>
                    <div style="opacity:.8;font-size:.8rem;margin-top:2px;">Solo Parent ID Application</div>
                </div>
                {!! $appointment->status_badge !!}
            </div>
            <div style="padding:20px 26px;">
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:16px;">
                    <div style="background:#f0f4ff;border-radius:10px;padding:12px 14px;">
                        <div style="font-size:.72rem;color:#64748b;font-weight:600;margin-bottom:3px;">DATE</div>
                        <div style="font-weight:800;color:#1e293b;font-size:.9rem;">{{ $appointment->formatted_date }}</div>
                    </div>
                    <div style="background:#f0f4ff;border-radius:10px;padding:12px 14px;">
                        <div style="font-size:.72rem;color:#64748b;font-weight:600;margin-bottom:3px;">TIME</div>
                        <div style="font-weight:800;color:#1e293b;font-size:.9rem;">{{ $appointment->formatted_time }}</div>
                    </div>
                    <div style="background:#f0f4ff;border-radius:10px;padding:12px 14px;">
                        <div style="font-size:.72rem;color:#64748b;font-weight:600;margin-bottom:3px;">TYPE</div>
                        <div style="font-weight:800;color:#1e293b;font-size:.9rem;">{{ $appointment->interview_label }}</div>
                    </div>
                </div>
                @if($appointment->admin_notes)
                <div style="background:#FFF3D6;border-left:3px solid #FDB913;border-radius:8px;padding:10px 14px;font-size:.84rem;color:#856404;margin-bottom:14px;">
                    <strong>Admin Note:</strong> {{ $appointment->admin_notes }}
                </div>
                @endif
                @if($appointment->appointment_date->isTomorrow())
                <div style="background:linear-gradient(135deg,#fff3cd,#ffeeba);border:2px solid #FDB913;border-radius:10px;padding:12px 16px;font-size:.86rem;color:#856404;font-weight:700;margin-bottom:14px;">
                    ⏰ <strong>Reminder:</strong> Your appointment is TOMORROW! Please be ready.
                </div>
                @endif
                <form method="POST" action="{{ route('user.appointments.cancel', $appointment->id) }}" id="cancelForm" style="display:inline-block;margin-right:10px;">
                    @csrf
                    <input type="hidden" name="cancel_reason" id="cancelReasonInput">
                    <button type="button" onclick="showCancelModal()" style="background:#fee2e2;color:#991b1b;border:1px solid:#fca5a5;border-radius:8px;padding:8px 18px;font-size:.8rem;font-weight:700;cursor:pointer;">
                        Cancel Appointment
                    </button>
                </form>
                @if($appointment->reschedule_status === 'pending')
                    <span style="background:#e0f2fe;color:#0c4a6e;border:1px solid #7dd3fc;border-radius:8px;padding:8px 18px;font-size:.8rem;font-weight:700;">
                        Waiting for Approval
                    </span>
                @else
                    <button type="button" onclick="showRescheduleModal()" style="background:#e0e7ff;color:#3730a3;border:1px solid #a5b4fc;border-radius:8px;padding:8px 18px;font-size:.8rem;font-weight:700;cursor:pointer;">
                        Request Reschedule
                    </button>
                @endif
            </div>
        </div>
        @else

        {{-- ── BOOKING FORM ── --}}
        <div style="background:white;border-radius:20px;border:1px solid #c7d2fe;box-shadow:0 4px 20px rgba(44,62,143,.08);overflow:hidden;margin-bottom:24px;" id="bookingCard">
            <div style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;padding:20px 26px;display:flex;align-items:center;gap:14px;">
                <div style="width:42px;height:42px;background:rgba(253,185,19,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;">📅</div>
                <div>
                    <div style="font-weight:800;font-size:1rem;">Schedule an Appointment</div>
                    <div style="opacity:.8;font-size:.8rem;margin-top:2px;">Step 1 &mdash; Book your interview slot with the MSWDO</div>
                </div>
            </div>
            <div style="padding:24px 26px;">
                <div style="background:#eef2ff;border-radius:10px;padding:12px 16px;font-size:.83rem;color:#4338ca;font-weight:600;margin-bottom:20px;">
                    ℹ️ Office hours: <strong>Monday – Friday, 8:00 AM – 5:00 PM</strong> (lunch 12:00–1:00 PM excluded) &bull; Max 5 appointments per time slot.
                </div>

                <form id="apptForm" method="POST" action="{{ route('user.appointments.store') }}">
                    @csrf
                    <div class="row g-3">
                        {{-- Date picker --}}
                        <div class="col-md-4">
                            <label style="font-size:.8rem;font-weight:700;color:#374151;display:block;margin-bottom:6px;">📆 Select Date <span style="color:red">*</span></label>
                            <input type="date" id="apptDate" name="appointment_date"
                                   min="{{ $minDate }}" max="{{ $maxDate }}"
                                   class="form-control"
                                   style="border-radius:10px;border:1.5px solid #c7d2fe;font-weight:600;font-size:.88rem;"
                                   required>
                            <div style="font-size:.7rem;color:#94a3b8;margin-top:4px;">Weekdays only (Mon–Fri)</div>
                        </div>

                        {{-- Time slot --}}
                        <div class="col-md-4">
                            <label style="font-size:.8rem;font-weight:700;color:#374151;display:block;margin-bottom:6px;">⏰ Select Time Slot <span style="color:red">*</span></label>
                            <select id="apptTime" name="appointment_time" class="form-control"
                                    style="border-radius:10px;border:1.5px solid #c7d2fe;font-weight:600;font-size:.88rem;" required disabled>
                                <option value="">Select date first</option>
                            </select>
                            <div id="slotMsg" style="font-size:.7rem;color:#94a3b8;margin-top:4px;"></div>
                        </div>

                        {{-- Interview type --}}
                        <div class="col-md-4">
                            <label style="font-size:.8rem;font-weight:700;color:#374151;display:block;margin-bottom:6px;">💬 Interview Type <span style="color:red">*</span></label>
                            <select name="interview_type" class="form-control"
                                    style="border-radius:10px;border:1.5px solid #c7d2fe;font-weight:600;font-size:.88rem;" required>
                                <option value="face_to_face">🏢 Face-to-Face</option>
                                <option value="online">📱 Online (via phone call)</option>
                            </select>
                        </div>

                        {{-- Notes --}}
                        <div class="col-12">
                            <label style="font-size:.8rem;font-weight:700;color:#374151;display:block;margin-bottom:6px;">📝 Additional Notes (optional)</label>
                            <textarea name="user_notes" rows="2" class="form-control"
                                      placeholder="Any concerns or special requests…"
                                      style="border-radius:10px;border:1.5px solid #c7d2fe;font-size:.85rem;"
                                      maxlength="500"></textarea>
                        </div>

                        {{-- Submit --}}
                        <div class="col-12">
                            <button type="submit" id="apptSubmitBtn"
                                    style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border:none;border-radius:12px;padding:12px 32px;font-weight:800;font-size:.92rem;cursor:pointer;display:inline-flex;align-items:center;gap:8px;transition:opacity .2s;">
                                📅 Book Appointment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($appointment && $appointment->status === 'rejected')
        <div style="background:#fee2e2;border-left:4px solid #dc3545;border-radius:12px;padding:14px 18px;font-size:.85rem;color:#991b1b;font-weight:600;margin-bottom:16px;">
            ❌ Your previous appointment was <strong>rejected</strong>. You may book a new slot above.
            @if($appointment->admin_notes)<br>Admin reason: {{ $appointment->admin_notes }}@endif
        </div>
        @endif

        @endif {{-- end active appointment check --}}
    </div>

    {{-- AJAX: load time slots when date changes --}}
    <script>
    (function(){
        const dateInput  = document.getElementById('apptDate');
        const timeSelect = document.getElementById('apptTime');
        const slotMsg    = document.getElementById('slotMsg');
        if (!dateInput) return;

        dateInput.addEventListener('change', function(){
            const date = this.value;
            if (!date) return;

            // Disable weekend selection via JS
            const d = new Date(date + 'T00:00:00');
            if (d.getDay() === 0 || d.getDay() === 6) {
                timeSelect.innerHTML = '<option value="">Weekdays only</option>';
                timeSelect.disabled = true;
                slotMsg.textContent = '⚠️ Please select a weekday (Mon–Fri).';
                slotMsg.style.color = '#dc3545';
                return;
            }

            timeSelect.disabled = true;
            timeSelect.innerHTML = '<option value="">Loading slots…</option>';
            slotMsg.textContent  = '';

            fetch(`/user/appointments/slots?date=${date}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(slots => {
                timeSelect.innerHTML = '<option value="">Choose a time</option>';
                let available = 0;
                slots.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.time;
                    if (s.full) {
                        opt.textContent = `${s.label}  — FULL`;
                        opt.disabled = true;
                        opt.style.color = '#9ca3af';
                    } else {
                        opt.textContent = `${s.label}  (${s.remaining} slot${s.remaining !== 1 ? 's' : ''} left)`;
                        available++;
                    }
                    timeSelect.appendChild(opt);
                });
                timeSelect.disabled = false;
                slotMsg.textContent = available > 0
                    ? `✅ ${available} time slot${available > 1 ? 's' : ''} available`
                    : '⚠️ No slots available on this date. Please pick another day.';
                slotMsg.style.color = available > 0 ? '#16a34a' : '#dc3545';
            })
            .catch(() => {
                timeSelect.innerHTML = '<option value="">Error loading slots</option>';
                slotMsg.textContent = 'Failed to load slots. Please try again.';
            });
        });
    })();
    </script>
    <!-- MAIN CONTENT -->
    <div class="flex-grow-1 py-5">
        <div class="container">
            <div class="row g-4">

                <!-- LEFT: APPLICATION STEPS -->
                <div class="col-lg-7">

                    <div class="section-card">
                        <div class="section-header">
                            <div>
                                <h4 data-en="How to Apply for a Solo Parent ID" data-tl="Paano Mag-apply ng Solo Parent ID">How to Apply for a Solo Parent ID</h4>
                                <p data-en="Follow these 7 steps to complete your application" data-tl="Sundin ang 7 hakbang na ito para makumpleto ang iyong aplikasyon">Follow these 7 steps to complete your application</p>
                            </div>
                        </div>
                        <div class="section-body">

                            <!-- Step 1 -->
                            <div class="step-item">
                                <div class="step-num">1</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Schedule an Appointment" data-tl="Mag-schedule ng Appointment">Schedule an Appointment</div>
                                    <div class="step-desc" data-en="Apply for an appointment for a face-to-face or online interview with the MSWDO." data-tl="Mag-apply ng appointment para sa harapan o online na panayam sa MSWDO.">
                                        Apply for an appointment for a face-to-face or online interview with the MSWDO.
                                    </div>
                                </div>
                            </div>
                            <div class="connector"></div>

                            <!-- Step 2 -->
                            <div class="step-item">
                                <div class="step-num">2</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Wait for Confirmation" data-tl="Hintayin ang Kumpirmasyon">Wait for Confirmation</div>
                                    <div class="step-desc" data-en="The admin will review your request and send a confirmation notification through the website and your registered email." data-tl="Susuriin ng admin ang iyong kahilingan at magpapadala ng kumpirmasyon sa pamamagitan ng website at iyong nakarehistrong email.">
                                        The admin will review your request and send a confirmation notification through the website and your registered email.
                                    </div>
                                    <div class="step-note">
                                         <span data-en="Make sure your registered email is active and accessible." data-tl="Siguraduhing aktibo at naa-access ang iyong nakarehistrong email.">Make sure your registered email is active and accessible.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="connector"></div>

                            <!-- Step 3 -->
                            <div class="step-item">
                                <div class="step-num">3</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Attend the Interview" data-tl="Dumalo sa Panayam">Attend the Interview</div>
                                    <div class="step-desc" data-en="Go to the MSWDO office for a face-to-face interview if you are nearby. Otherwise, attend the scheduled online interview." data-tl="Pumunta sa opisina ng MSWDO para sa harapang panayam kung malapit ka. Kung hindi, dumalo sa nakatakdang online na panayam.">
                                        Go to the MSWDO office for a face-to-face interview if you are nearby. Otherwise, attend the scheduled online interview.
                                    </div>
                                    <div class="step-note">
                                        <span data-en="MSWDO Office - Municipal Hall, Ground Floor, Monday-Friday 8:00 AM�5:00 PM." data-tl="Opisina ng MSWDO - Municipal Hall, Ground Floor, Lunes-Biyernes 8:00 AM-5:00 PM.">MSWDO Office - Municipal Hall, Ground Floor, Monday�Friday 8:00 AM�5:00 PM.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="connector"></div>

                            <!-- Step 4 -->
                            <div class="step-item">
                                <div class="step-num">4</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Eligibility Notification" data-tl="Abiso sa Pagiging Karapat-dapat">Eligibility Notification</div>
                                    <div class="step-desc" data-en="You will receive a message via your registered email informing you if you are eligible for the Solo Parent program." data-tl="Makatanggap ka ng mensahe sa iyong nakarehistrong email na nagpapaalam kung ikaw ay karapat-dapat para sa programang Solo Parent.">
                                        You will receive a message via your registered email informing you if you are eligible for the program.
                                    </div>
                                </div>
                            </div>
                            <div class="connector"></div>

                            <!-- Step 5 -->
                            <div class="step-item">
                                <div class="step-num">5</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Requirements Submission" data-tl="Pagsusumite ng mga Kinakailangan">Requirements Submission</div>
                                    <div class="step-desc" data-en="If approved, a list of required documents will be sent to you via email. Prepare all the necessary documents before proceeding." data-tl="Kung naaprubahan, isang listahan ng mga kinakailangang dokumento ang ipapadala sa iyo sa pamamagitan ng email. Ihanda ang lahat ng kinakailangang dokumento bago magpatuloy.">
                                        If approved, a list of required documents will be sent to you via email. Prepare all the necessary documents before proceeding.
                                    </div>
                                </div>
                            </div>
                            <div class="connector"></div>

                            <!-- Step 6 -->
                            <div class="step-item">
                                <div class="step-num">6</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Submit Documents" data-tl="Isumite ang mga Dokumento">Submit Documents</div>
                                    <div class="step-desc" data-en="Prepare and submit the hard copies of your required documents to the MSWDO office." data-tl="Ihanda at isumite ang mga pisikal na kopya ng iyong mga kinakailangang dokumento sa opisina ng MSWDO.">
                                        Prepare and submit the hard copies of your required documents to the MSWDO office.
                                    </div>
                                    <div class="step-note">
                                         <span data-en="Bring original copies and at least one photocopy of each document for verification." data-tl="Magdala ng mga orihinal na kopya at hindi bababa sa isang photocopy ng bawat dokumento para sa pag-verify.">Bring original copies and at least one photocopy of each document for verification.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="connector"></div>

                            <!-- Step 7 -->
                            <div class="step-item">
                                <div class="step-num">7</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="ID Processing and Release" data-tl="Pagpoproseso at Paglalabas ng ID">ID Processing and Release</div>
                                    <div class="step-desc" data-en="Your Solo Parent ID will be processed by the MSWDO. You will be notified once it is ready for release." data-tl="Ipoproseso ng MSWDO ang iyong Solo Parent ID. Maabisuhan ka kapag handa na itong makuha.">
                                        Your Solo Parent ID will be processed by the MSWDO. You will be notified once it is ready for release.
                                    </div>
                                    <div class="step-note">
                                        <span data-en="You will receive a notification via email and the website when your ID is ready for pickup." data-tl="Makakatanggap ka ng abiso sa pamamagitan ng email at website kapag handa na ang iyong ID para makuha.">You will receive a notification via email and the website when your ID is ready for pickup.</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- RIGHT: SIDEBAR -->
                <div class="col-lg-5">

                    <!-- Requirements Section -->
                    @if(isset($appointment) && $appointment && $appointment->status === 'validated' && isset($soloParentApplication) && $soloParentApplication)
                    {{-- ✅ VALIDATED: Show real requirements + upload form --}}
                    <div class="section-card">
                        <div class="section-header" style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);">
                            <div>
                                <h4>Required Documents</h4>
                                <p>You are eligible! Please upload the following documents.</p>
                            </div>
                        </div>
                        <div class="section-body">

                            {{-- Eligibility Banner --}}
                            <div style="background:linear-gradient(135deg,#e0e7ff,#c7d2fe);border-radius:14px;border:1.5px solid #a5b4fc;padding:18px 22px;margin-bottom:22px;display:flex;align-items:center;gap:14px;">
                                <span style="font-size:1.8rem;">🎉</span>
                                <div>
                                    <div style="font-weight:800;color:#1e3a8a;font-size:1rem;">Congratulations! You passed the eligibility assessment.</div>
                                    <div style="font-size:.85rem;color:#3730a3;margin-top:2px;">Please upload all required documents below to complete your Solo Parent ID application.</div>
                                </div>
                            </div>

                            {{-- Overall Status --}}
                            @php
                                $fm = $soloParentApplication->fileMonitoring;
                                $uploads = $fm ? $fm->fileUploads : collect();
                                $overallStatus = $fm ? $fm->overall_status : 'pending';
                            @endphp
                            @if($overallStatus === 'approved')
                            <div style="background:#dbeafe;border-left:4px solid #2C3E8F;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-size:.87rem;color:#1e3a8a;font-weight:700;">
                                ✅ All your documents have been approved! Your Solo Parent ID is being processed.
                            </div>
                            @elseif($overallStatus === 'rejected')
                            <div style="background:#f8d7da;border-left:4px solid #dc3545;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-size:.87rem;color:#721c24;font-weight:700;">
                                ❌ Some documents need attention. Please resubmit the declined documents below.
                            </div>
                            @elseif($overallStatus === 'in_review')
                            <div style="background:#e8f4fd;border-left:4px solid #2196F3;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-size:.87rem;color:#0d47a1;font-weight:700;">
                                🔍 Your documents are currently under review. We will notify you of the results.
                            </div>
                            @endif

                            {{-- Requirements List + Uploaded Files --}}
                            @php
                                $soloReqs = [
                                    'PSA Birth Certificate of Child/Children',
                                    'Barangay Certificate (stating you are a solo parent)',
                                    'Valid Government-Issued ID',
                                    'CENOMAR or PSA Marriage Certificate',
                                    'Death Certificate of Spouse (if widowed) / Police Report (if abandoned)',
                                    '2x2 ID Photo (recent, white background)',
                                ];
                                $uploadedByName = $uploads->keyBy('requirement_name');
                            @endphp

                            <div style="margin-bottom:20px;">
                            @foreach($soloReqs as $req)
                                @php
                                    $uploaded = $uploadedByName->get($req);
                                @endphp
                                <div class="solo-req-row" style="background:white;border:1.5px solid #e2e8f0;border-radius:12px;padding:14px 18px;margin-bottom:10px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
                                    <div style="display:flex;align-items:center;gap:10px;flex:1;">
                                        @if($uploaded && $uploaded->status === 'approved')
                                            <span style="color:#28a745;font-size:1.1rem;">✅</span>
                                        @elseif($uploaded && $uploaded->status === 'rejected')
                                            <span style="color:#dc3545;font-size:1.1rem;">❌</span>
                                        @elseif($uploaded)
                                            <span style="color:#ffc107;font-size:1.1rem;">⏳</span>
                                        @else
                                            <span style="color:#ced4da;font-size:1.1rem;">📄</span>
                                        @endif
                                        <div>
                                            <div style="font-weight:700;font-size:.88rem;color:#1e293b;">{{ $req }}</div>
                                            @if($uploaded && $uploaded->admin_remarks)
                                            <div style="font-size:.78rem;color:#dc3545;margin-top:2px;">Remark: {{ $uploaded->admin_remarks }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    @if($uploaded && in_array($uploaded->status, ['pending','approved']))
                                        <span style="background:#e2e8f0;color:#64748b;border-radius:20px;padding:3px 12px;font-size:.75rem;font-weight:700;">Uploaded</span>
                                    @elseif($uploaded && $uploaded->status === 'rejected')
                                        {{-- Re-upload form --}}
                                        <form action="{{ route('applications.requirement.upload', $soloParentApplication->id) }}" method="POST" enctype="multipart/form-data" data-upload-type="single" style="display:flex;align-items:center;gap:8px;">
                                            @csrf
                                            <input type="hidden" name="requirement_name" value="{{ $req }}">
                                            <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required style="font-size:.78rem;max-width:180px;">
                                            <button type="submit" style="background:#2C3E8F;color:white;border:none;border-radius:8px;padding:5px 14px;font-size:.78rem;font-weight:700;cursor:pointer;">🔄 Resubmit</button>
                                        </form>
                                    @else
                                        {{-- Upload form --}}
                                        <form action="{{ route('applications.requirement.upload', $soloParentApplication->id) }}" method="POST" enctype="multipart/form-data" data-upload-type="single" style="display:flex;align-items:center;gap:8px;">
                                            @csrf
                                            <input type="hidden" name="requirement_name" value="{{ $req }}">
                                            <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" required style="font-size:.78rem;max-width:180px;">
                                            <button type="submit" style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border:none;border-radius:8px;padding:5px 14px;font-size:.78rem;font-weight:700;cursor:pointer;">📤 Upload</button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                            </div>

                            {{-- Upload All Button --}}
                            <div id="uploadAllBar" style="background:#f0f4ff;border:1.5px solid #c7d2fe;border-radius:14px;padding:16px 20px;margin-bottom:18px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                                <div>
                                    <div style="font-weight:700;color:#1e3a8a;font-size:.9rem;">📦 Upload All Selected Files</div>
                                    <div id="uploadAllStatus" style="font-size:.8rem;color:#64748b;margin-top:2px;">Select files in the rows above, then click Upload All to submit them all at once.</div>
                                </div>
                                <button type="button" id="uploadAllBtn" onclick="uploadAllFiles()"
                                    style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border:none;border-radius:10px;padding:10px 24px;font-size:.88rem;font-weight:700;cursor:pointer;white-space:nowrap;">
                                    📤 Upload All
                                </button>
                            </div>

                            <script>
                            function uploadAllFiles() {
                                // Collect all file inputs inside individual upload forms (not resubmit forms)
                                const rows = document.querySelectorAll('.solo-req-row');
                                const toUpload = [];
                                rows.forEach(row => {
                                    const input = row.querySelector('input[type="file"][name="file"]');
                                    const reqInput = row.querySelector('input[name="requirement_name"]');
                                    const form = row.querySelector('form[data-upload-type="single"]');
                                    if (input && input.files.length > 0 && reqInput && form) {
                                        toUpload.push({ file: input.files[0], reqName: reqInput.value, action: form.action });
                                    }
                                });

                                if (toUpload.length === 0) {
                                    alert('⚠️ Please select at least one file in the rows below before clicking Upload All.');
                                    return;
                                }

                                const btn = document.getElementById('uploadAllBtn');
                                const status = document.getElementById('uploadAllStatus');
                                btn.disabled = true;
                                btn.textContent = '⏳ Uploading...';

                                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
                                let done = 0, failed = 0;

                                const uploadNext = (index) => {
                                    if (index >= toUpload.length) {
                                        btn.disabled = false;
                                        btn.textContent = '📤 Upload All';
                                        if (failed === 0) {
                                            status.textContent = '✅ ' + done + ' file(s) uploaded successfully! Refreshing...';
                                            setTimeout(() => location.reload(), 1200);
                                        } else {
                                            status.textContent = '✅ ' + done + ' uploaded, ❌ ' + failed + ' failed. Check individual rows.';
                                        }
                                        return;
                                    }

                                    const { file, reqName, action } = toUpload[index];
                                    status.textContent = '⏳ Uploading ' + (index + 1) + ' of ' + toUpload.length + ': ' + file.name;

                                    const fd = new FormData();
                                    fd.append('_token', csrfToken);
                                    fd.append('requirement_name', reqName);
                                    fd.append('file', file);

                                    fetch(action, { method: 'POST', body: fd })
                                        .then(r => { if (r.ok || r.redirected) { done++; } else { failed++; } })
                                        .catch(() => { failed++; })
                                        .finally(() => uploadNext(index + 1));
                                };

                                uploadNext(0);
                            }
                            </script>

                            <div style="background:#fff8e1;border-left:4px solid #FDB913;border-radius:8px;padding:12px 16px;font-size:.83rem;color:#856404;line-height:1.6;">
                                ⚠️ <strong>Tip:</strong> Upload clear, readable scanned copies or photos. Accepted formats: PDF, JPG, PNG. Max size: 5MB per file.
                            </div>
                        </div>
                    </div>

                    @elseif(isset($appointment) && $appointment && $appointment->status === 'confirmed')
                    {{-- ⏳ CONFIRMED but not yet validated --}}
                    <div class="section-card">
                        <div class="section-header">
                            <div class="sec-icon">⏳</div>
                            <div>
                                <h4>Required Documents</h4>
                                <p>Your appointment has been confirmed. Waiting for eligibility assessment.</p>
                            </div>
                        </div>
                        <div class="section-body">
                            <div style="text-align:center;padding:32px 20px;">
                                <div style="font-size:2.5rem;margin-bottom:12px;">🔍</div>
                                <div style="font-weight:700;color:#1e293b;margin-bottom:8px;">Eligibility Review In Progress</div>
                                <div style="font-size:.87rem;color:#64748b;line-height:1.7;">Your appointment has been confirmed. The MSWDO officer will review your eligibility during your interview. Once validated, the requirements list will appear here and you will be notified by email.</div>
                            </div>
                        </div>
                    </div>

                    @else
                    {{-- 📋 DEFAULT: Coming Soon placeholder --}}
                    <div class="section-card">
                        <div class="section-header">
                            <div>
                                <h4 data-en="Required Documents" data-tl="Mga Kinakailangang Dokumento">Required Documents</h4>
                                <p data-en="Documents needed for your Solo Parent ID application" data-tl="Mga dokumentong kailangan para sa iyong aplikasyon">Documents needed for your Solo Parent ID application</p>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="info-card placeholder" style="padding:32px 22px;">
                                <div style="font-weight:700;color:#6c757d;font-size:.95rem;margin-bottom:8px;"
                                     data-en="Requirements List Coming Soon"
                                     data-tl="Listahan ng mga Kinakailangan — Malapit na">
                                    Requirements List Coming Soon
                                </div>
                                <div style="font-size:.85rem;color:#94a3b8;line-height:1.7;"
                                     data-en="The list of required documents is being finalized. Once available, it will be displayed here and sent to eligible applicants via email after the interview."
                                     data-tl="Ang listahan ng mga kinakailangang dokumento ay pinaplano pa. Kapag available na, ipapakita ito dito at ipapadala sa mga karapat-dapat na aplikante sa pamamagitan ng email pagkatapos ng panayam.">
                                    The list of required documents is being finalized. Once available, it will be displayed here and sent to eligible applicants via email after the interview.
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- MSWDO Office Details -->
                    <div class="section-card">
                        <div class="section-header">
                            <div>
                                <h4 data-en="MSWDO Office Details" data-tl="Detalye ng Opisina ng MSWDO">MSWDO Office Details</h4>
                                <p data-en="Where to personally submit your requirements" data-tl="Saan personal na magsumite ng mga kinakailangan">Where to personally submit your requirements</p>
                            </div>
                        </div>
                        <div class="section-body">
                            <div class="info-card">
                                <div class="ic-title"><span data-en="Location" data-tl="Lokasyon">Location</span></div>
                                <div class="ic-body" data-en="Municipal Social Welfare and Development Office, Municipal Hall, Ground Floor" data-tl="Municipal Social Welfare and Development Office, Municipal Hall, Ground Floor">
                                    Municipal Social Welfare and Development Office, Municipal Hall, Ground Floor
                                </div>
                            </div>
                            <div class="info-card">
                                <div class="ic-title"><span data-en="Office Hours" data-tl="Oras ng Opisina">Office Hours</span></div>
                                <div class="ic-body" data-en="Monday - Friday - 8:00 AM - 5:00 PM (Closed on Holidays)" data-tl="Lunes - Biyernes - 8:00 AM - 5:00 PM (Sarado sa mga Pista Opisyal)">
                                    Monday - Friday - 8:00 AM - 5:00 PM <br><small style="color:#94a3b8;">(Closed on Holidays)</small>
                                </div>
                            </div>
                            <div class="info-card yellow">
                                <div class="ic-title"><span data-en="Interview Options" data-tl="Mga Opsyon sa Panayam">Interview Options</span></div>
                                <div class="ic-body">
                                    <ul style="margin:0;padding-left:18px;line-height:2;">
                                        <li data-en="Face-to-face at the MSWDO office" data-tl="Harapan sa opisina ng MSWDO">Face-to-face at the MSWDO office</li>
                                        <li data-en="Online interview (if you cannot visit in person)" data-tl="Online na panayam (kung hindi ka makabisita)">Online interview (if you cannot visit in person)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>


           

        </div>
    </div>

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    {{-- Cancel Modal --}}
    <div id="cancelModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
        <div style="background:white;border-radius:16px;max-width:500px;width:90%;padding:28px;box-shadow:0 10px 40px rgba(0,0,0,.3);">
            <h4 style="font-weight:800;color:#1e293b;margin-bottom:16px;">🚫 Cancel Appointment</h4>
            <p style="font-size:.9rem;color:#64748b;margin-bottom:20px;">Please provide a reason for cancelling your appointment:</p>
            <textarea id="cancelReasonText" rows="4" style="width:100%;border:1.5px solid #e2e8f0;border-radius:10px;padding:12px;font-size:.88rem;font-family:inherit;" placeholder="e.g., May emergency po sa family" required></textarea>
            <div style="display:flex;gap:10px;margin-top:20px;justify-content:flex-end;">
                <button onclick="hideCancelModal()" style="background:#e2e8f0;color:#64748b;border:none;border-radius:8px;padding:10px 20px;font-weight:700;cursor:pointer;">
Cancel</button>
                <button onclick="submitCancel()" style="background:#dc3545;color:white;border:none;border-radius:8px;padding:10px 20px;font-weight:700;cursor:pointer;">🚫 Confirm Cancel</button>
            </div>
        </div>
    </div>

    {{-- Reschedule Modal --}}
    <div id="rescheduleModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;overflow-y:auto;">
        <div style="background:white;border-radius:16px;max-width:600px;width:90%;padding:28px;box-shadow:0 10px 40px rgba(0,0,0,.3);margin:20px;">
            <h4 style="font-weight:800;color:#1e293b;margin-bottom:16px;">🔄 Request Reschedule</h4>
            <form method="POST" action="{{ route('user.appointments.reschedule', $appointment->id ?? 0) }}" id="rescheduleForm">
                @csrf
                <div style="margin-bottom:16px;">
                    <label style="font-size:.85rem;font-weight:700;color:#374151;display:block;margin-bottom:6px;">📆 New Date <span style="color:red">*</span></label>
                    <input type="date" name="reschedule_date" id="rescheduleDate" min="{{ $minDate ?? '' }}" max="{{ $maxDate ?? '' }}" required style="width:100%;border:1.5px solid #c7d2fe;border-radius:10px;padding:10px;font-size:.88rem;">
                </div>
                <div style="margin-bottom:16px;">
                    <label style="font-size:.85rem;font-weight:700;color:#374151;display:block;margin-bottom:6px;">⏰ New Time <span style="color:red">*</span></label>
                    <select name="reschedule_time" id="rescheduleTime" required disabled style="width:100%;border:1.5px solid #c7d2fe;border-radius:10px;padding:10px;font-size:.88rem;">
                        <option value="">Select date first</option>
                    </select>
                    <div id="rescheduleSlotMsg" style="font-size:.75rem;color:#94a3b8;margin-top:4px;"></div>
                </div>
                <div style="margin-bottom:20px;">
                    <label style="font-size:.85rem;font-weight:700;color:#374151;display:block;margin-bottom:6px;">📝 Reason for Reschedule <span style="color:red">*</span></label>
                    <textarea name="reschedule_reason" rows="3" required style="width:100%;border:1.5px solid #c7d2fe;border-radius:10px;padding:12px;font-size:.88rem;font-family:inherit;" placeholder="e.g., May conflict sa schedule"></textarea>
                </div>
                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button type="button" onclick="hideRescheduleModal()" style="background:#e2e8f0;color:#64748b;border:none;border-radius:8px;padding:10px 20px;font-weight:700;cursor:pointer;">Cancel</button>
                    <button type="submit" style="background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border:none;border-radius:8px;padding:10px 20px;font-weight:700;cursor:pointer;">🔄 Submit Request</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function showCancelModal() {
        document.getElementById('cancelModal').style.display = 'flex';
    }
    function hideCancelModal() {
        document.getElementById('cancelModal').style.display = 'none';
    }
    function submitCancel() {
        const reason = document.getElementById('cancelReasonText').value.trim();
        if (!reason) {
            alert('Please provide a reason for cancellation.');
            return;
        }
        document.getElementById('cancelReasonInput').value = reason;
        document.getElementById('cancelForm').submit();
    }

    function showRescheduleModal() {
        document.getElementById('rescheduleModal').style.display = 'flex';
    }
    function hideRescheduleModal() {
        document.getElementById('rescheduleModal').style.display = 'none';
    }

    // Load slots for reschedule
    const rescheduleDate = document.getElementById('rescheduleDate');
    const rescheduleTime = document.getElementById('rescheduleTime');
    const rescheduleSlotMsg = document.getElementById('rescheduleSlotMsg');
    
    if (rescheduleDate) {
        rescheduleDate.addEventListener('change', function() {
            const date = this.value;
            if (!date) return;

            const d = new Date(date + 'T00:00:00');
            if (d.getDay() === 0 || d.getDay() === 6) {
                rescheduleTime.innerHTML = '<option value="">Weekdays only</option>';
                rescheduleTime.disabled = true;
                rescheduleSlotMsg.textContent = '⚠️ Please select a weekday (Mon–Fri).';
                rescheduleSlotMsg.style.color = '#dc3545';
                return;
            }

            rescheduleTime.disabled = true;
            rescheduleTime.innerHTML = '<option value="">Loading slots…</option>';
            rescheduleSlotMsg.textContent = '';

            fetch(`/user/appointments/slots?date=${date}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(slots => {
                rescheduleTime.innerHTML = '<option value="">Choose a time</option>';
                let available = 0;
                slots.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.time;
                    if (s.full) {
                        opt.textContent = `${s.label}  — FULL`;
                        opt.disabled = true;
                        opt.style.color = '#9ca3af';
                    } else {
                        opt.textContent = `${s.label}  (${s.remaining} slot${s.remaining !== 1 ? 's' : ''} left)`;
                        available++;
                    }
                    rescheduleTime.appendChild(opt);
                });
                rescheduleTime.disabled = false;
                rescheduleSlotMsg.textContent = available > 0
                    ? `✅ ${available} time slot${available > 1 ? 's' : ''} available`
                    : '⚠️ No slots available on this date. Please pick another day.';
                rescheduleSlotMsg.style.color = available > 0 ? '#16a34a' : '#dc3545';
            })
            .catch(() => {
                rescheduleTime.innerHTML = '<option value="">Error loading slots</option>';
                rescheduleSlotMsg.textContent = 'Failed to load slots. Please try again.';
            });
        });
    }
    </script>

    @include('components.chat-modal')
    @include('components.chatbot-widget')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentLang = 'en';
        function setLang(lang) {
            currentLang = lang;
            document.querySelectorAll('.lang-btn').forEach(b => {
                b.classList.toggle('active', b.dataset.lang === lang);
            });
            document.querySelectorAll('[data-en]').forEach(el => {
                const text = lang === 'tl' ? (el.dataset.tl || el.dataset.en) : el.dataset.en;
                if (text) el.textContent = text;
            });
            document.querySelectorAll('li[data-en]').forEach(li => {
                const text = lang === 'tl' ? (li.dataset.tl || li.dataset.en) : li.dataset.en;
                if (text) li.textContent = text;
            });
            const t = document.querySelector('title');
            if (t) t.textContent = lang === 'tl' ? t.dataset.tl : t.dataset.en;
        }
    </script>
</body>
</html>
