<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title data-en="{{ $aicsPageTitle }}" data-tl="{{ $aicsPageTitle }}">{{ $aicsPageTitle }}</title>
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

        .top-bar{background:var(--primary-gradient);padding:14px 0;box-shadow:0 4px 20px rgba(44,62,143,.22);}
        .top-bar-inner{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;}
        .brand{display:flex;align-items:center;gap:12px;color:white;font-weight:800;font-size:1.45rem;}
        .brand img{width:34px;height:34px;object-fit:contain;}
        .back-btn{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:2px solid rgba(255,255,255,.4);color:white;border-radius:30px;padding:8px 22px;font-weight:700;font-size:.88rem;cursor:pointer;transition:all .3s;text-decoration:none;}
        .back-btn:hover{background:var(--secondary-yellow);color:var(--primary-blue);border-color:var(--secondary-yellow);}

        .hero-banner{background:var(--primary-gradient);color:white;padding:24px 0 20px;position:relative;overflow:hidden;}
        .hero-banner::before{content:'';position:absolute;top:-90px;right:-90px;width:360px;height:360px;border-radius:50%;background:rgba(253,185,19,.10);}
        .hero-inner{position:relative;z-index:2;}
        .hero-badge{display:inline-block;background:rgba(253,185,19,.18);color:var(--secondary-yellow);border:1px solid rgba(253,185,19,.35);border-radius:30px;padding:5px 18px;font-size:.75rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;margin-bottom:12px;}
        .hero-banner h1{font-size:2rem;font-weight:900;margin-bottom:6px;line-height:1.15;}
        .hero-divider{width:50px;height:4px;background:var(--secondary-yellow);border-radius:2px;margin:12px 0;}
        .hero-banner p.hero-sub{opacity:.85;font-size:.92rem;margin:0;max-width:900px;line-height:1.65;}

        .lang-toggle{display:inline-flex;border-radius:30px;overflow:hidden;border:2px solid rgba(255,255,255,.4);}
        .lang-btn{background:transparent;border:none;color:rgba(255,255,255,.7);font-weight:700;font-size:.82rem;padding:8px 20px;cursor:pointer;transition:all .2s;}
        .lang-btn.active{background:var(--secondary-yellow);color:var(--primary-blue);}

        #aics-wizard-view,#aics-monitor-view{width:100%;}
        .wizard-card,.monitor-card{background:white;border-radius:20px;border:1px solid var(--border-light);box-shadow:0 8px 32px rgba(44,62,143,.08);overflow:hidden;margin-bottom:20px;}
        .wizard-top,.monitor-header{background:var(--primary-gradient);color:white;padding:20px 32px 16px;}
        .wizard-top h2,.monitor-header h2{font-size:1.3rem;font-weight:900;margin:0 0 4px;}
        .wizard-top p,.monitor-header p{margin:0;font-size:.86rem;opacity:.82;}
        .wizard-progress-track{height:6px;background:rgba(255,255,255,.18);border-radius:3px;margin-top:14px;overflow:hidden;}
        .wizard-progress-fill{height:100%;background:var(--secondary-yellow);border-radius:3px;transition:width .4s ease;width:16%;}

        .wizard-steps{display:flex;align-items:flex-start;justify-content:stretch;gap:8px;padding:18px 32px 14px;overflow-x:auto;background:#fafbfc;border-bottom:1px solid var(--border-light);}
        .wiz-step{flex:1;min-width:90px;display:flex;flex-direction:column;align-items:center;gap:8px;cursor:pointer;border:none;background:transparent;padding:0 6px;}
        .wiz-step-circle{width:40px;height:40px;border-radius:50%;border:2px solid var(--border-light);background:white;color:#94a3b8;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.88rem;transition:all .3s;}
        .wiz-step.active .wiz-step-circle{background:var(--primary-gradient);border-color:var(--primary-blue);color:white;box-shadow:0 4px 14px rgba(44,62,143,.28);}
        .wiz-step.done .wiz-step-circle{background:#dcfce7;border-color:#22c55e;color:#15803d;}
        .wiz-step-label{font-size:.68rem;font-weight:700;color:#64748b;text-align:center;line-height:1.3;max-width:100px;}
        .wiz-step.active .wiz-step-label{color:var(--primary-blue);}

        .wizard-body{padding:28px 32px 24px;min-height:360px;}
        .wizard-panel{display:none;animation:fadeIn .35s ease;}
        .wizard-panel.active{display:block;}
        @keyframes fadeIn{from{opacity:0;transform:translateY(8px);}to{opacity:1;transform:translateY(0);}}
        .wizard-nav{display:flex;align-items:center;justify-content:space-between;padding:16px 32px 22px;border-top:1px solid var(--border-light);background:#fafbfc;}
        .wiz-btn-prev,.wiz-btn-next,.wiz-btn-finish{display:inline-flex;align-items:center;gap:8px;border-radius:12px;padding:11px 24px;font-weight:700;font-size:.88rem;cursor:pointer;border:none;transition:all .25s;}
        .wiz-btn-prev{background:white;color:var(--primary-blue);border:2px solid var(--border-light);}
        .wiz-btn-prev:disabled{opacity:.4;cursor:not-allowed;}
        .wiz-btn-next{background:var(--primary-gradient);color:white;}
        .wiz-btn-finish{display:none;background:var(--secondary-gradient);color:var(--primary-blue);}
        .wiz-step-counter{font-size:.78rem;color:#64748b;font-weight:600;}

        .panel-heading{font-weight:800;color:var(--primary-blue);font-size:1.1rem;margin-bottom:6px;}
        .panel-sub{font-size:.86rem;color:#64748b;margin-bottom:22px;line-height:1.6;}
        .overview-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px;}
        .overview-tile{background:var(--primary-blue-light);border-radius:14px;padding:20px;text-align:center;border:1px solid rgba(44,62,143,.1);}
        .overview-tile .ot-num{font-size:2rem;font-weight:900;color:var(--primary-blue);}
        .overview-tile .ot-label{font-size:.78rem;font-weight:700;color:#475569;margin-top:8px;}
        .wiz-2col{display:grid;grid-template-columns:1fr 1fr;gap:24px;}
        .guide-steps-grid{display:grid;grid-template-columns:1fr;gap:0;}
        .resources-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}

        .step-item{display:flex;gap:18px;margin-bottom:20px;align-items:flex-start;}
        .step-num{width:40px;height:40px;min-width:40px;border-radius:50%;background:var(--primary-gradient);color:white;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1rem;}
        .step-title{font-weight:700;color:var(--primary-blue);font-size:.95rem;margin-bottom:4px;}
        .step-desc{font-size:.87rem;color:#475569;line-height:1.65;}
        .connector{margin-left:19px;border-left:2px dashed var(--border-light);height:14px;}

        .info-card{background:var(--primary-blue-light);border:1px solid rgba(44,62,143,.12);border-radius:14px;padding:18px 22px;margin-bottom:16px;}
        .info-card.yellow{background:var(--secondary-yellow-light);border-color:rgba(253,185,19,.3);}
        .info-card .ic-title{font-weight:700;color:var(--primary-blue);font-size:.88rem;margin-bottom:6px;}
        .info-card .ic-body{font-size:.85rem;color:#475569;line-height:1.65;}

        /* AICS Booking */
        .aics-booking-card{background:white;border-radius:16px;border:1px solid #c7d2fe;box-shadow:0 4px 16px rgba(44,62,143,.06);overflow:hidden;margin-bottom:0;}
        .aics-booking-header{background:var(--primary-gradient);color:white;padding:18px 24px;display:flex;align-items:center;gap:14px;}
        .aics-booking-icon{width:42px;height:42px;background:rgba(253,185,19,.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;}
        .aics-booking-title{font-weight:800;font-size:1rem;}
        .aics-booking-sub{opacity:.8;font-size:.8rem;margin-top:2px;}
        .aics-booking-body{padding:22px 24px;color:#1e293b;font-size:.9rem;line-height:1.7;}
        .aics-appt-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:16px;}
        .aics-appt-tile{background:#f0f4ff;border-radius:10px;padding:12px 14px;}
        .aics-appt-label{font-size:.7rem;color:#64748b;font-weight:600;margin-bottom:3px;}
        .aics-appt-val{font-weight:800;font-size:.9rem;}
        .aics-appt-actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:4px;}
        .aics-office-hours{background:#eef2ff;border-radius:10px;padding:12px 16px;font-size:.83rem;color:#4338ca;font-weight:600;margin-bottom:18px;}
        .aics-label{font-size:.8rem;font-weight:700;color:#374151;display:block;margin-bottom:6px;}
        .aics-input{border-radius:10px!important;border:1.5px solid #c7d2fe!important;font-weight:600;font-size:.88rem;}
        .aics-hint{font-size:.7rem;color:#94a3b8;margin-top:4px;}
        .aics-btn{display:inline-flex;align-items:center;gap:6px;border-radius:10px;padding:10px 20px;font-weight:700;font-size:.84rem;cursor:pointer;border:none;transition:all .2s;}
        .aics-btn-primary{background:var(--primary-gradient);color:white;}
        .aics-btn-danger{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;}
        .aics-btn-outline{background:#e0e7ff;color:#3730a3;border:1px solid #a5b4fc;}
        .aics-btn-wait{background:#e0f2fe;color:#0c4a6e;border:1px solid #7dd3fc;}
        .aics-btn-yellow{background:var(--secondary-gradient);color:var(--primary-blue);}
        .aics-btn-sm{padding:6px 14px;font-size:.78rem;}
        .aics-note{border-radius:10px;padding:12px 16px;font-size:.84rem;margin-bottom:14px;}
        .aics-note.yellow{background:#FFF3D6;border-left:3px solid #FDB913;color:#856404;}
        .aics-note.reminder{background:linear-gradient(135deg,#fff3cd,#ffeeba);border:2px solid #FDB913;color:#856404;font-weight:700;}
        .aics-note.rejected{background:#fee2e2;border-left:4px solid #dc3545;color:#991b1b;font-weight:600;}

        /* AICS Requirements */
        .aics-req-note{background:var(--secondary-yellow-light);border-left:4px solid var(--secondary-yellow);border-radius:10px;padding:11px 16px;margin-bottom:18px;font-size:.84rem;color:#856404;font-weight:600;}
        .aics-req-card{background:white;border:1px solid var(--border-light);border-radius:14px;padding:0;overflow:hidden;height:100%;}
        .aics-req-card.rejected-border{border-left:4px solid #dc3545;}
        .aics-req-head{display:flex;align-items:flex-start;justify-content:space-between;gap:8px;padding:14px 16px 10px;}
        .aics-req-name{font-weight:700;color:var(--text-dark);font-size:.9rem;}
        .aics-req-date{font-size:.7rem;color:#94a3b8;margin-top:2px;}
        .aics-req-badges{display:flex;align-items:center;gap:5px;flex-shrink:0;flex-wrap:wrap;justify-content:flex-end;}
        .aics-badge{border-radius:20px;padding:2px 9px;font-size:.72rem;font-weight:700;}
        .aics-badge.approved{background:#d4edda;color:#155724;}
        .aics-badge.rejected{background:#f8d7da;color:#721c24;}
        .aics-badge.pending{background:#FFF3D6;color:#856404;}
        .aics-badge.none{background:#e9ecef;color:#6c757d;}
        .aics-badge.review{background:#d1ecf1;color:#0c5460;}
        .aics-upload-area{background:#FFFBF0;border:1px dashed var(--secondary-yellow);border-radius:10px;padding:12px 14px;margin:0 12px 12px;}
        .aics-upload-label{font-size:.78rem;font-weight:700;color:#856404;margin-bottom:7px;}
        .aics-size-hint{font-size:.67rem;color:#94a3b8;margin-top:3px;}
        .aics-view-link{display:inline-block;background:var(--primary-gradient);color:white;border-radius:8px;padding:3px 10px;font-size:.74rem;font-weight:700;margin-left:4px;}
        .aics-thumb{width:44px;height:44px;object-fit:cover;border-radius:8px;border:1px solid var(--border-light);cursor:pointer;}
        .aics-remark{background:var(--secondary-yellow-light);border-left:3px solid var(--secondary-yellow);border-radius:8px;padding:8px 12px;font-size:.78rem;color:#856404;margin:0 12px 10px;}
        .aics-status-msg{border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:.86rem;font-weight:700;}
        .aics-status-msg.approved{background:#dbeafe;border-left:4px solid #2C3E8F;color:#1e3a8a;}
        .aics-status-msg.rejected{background:#f8d7da;border-left:4px solid #dc3545;color:#721c24;}
        .aics-status-msg.review{background:#e8f4fd;border-left:4px solid #2196F3;color:#0d47a1;}
        .aics-upload-all-bar{background:#f0f4ff;border:1.5px solid #c7d2fe;border-radius:14px;padding:16px 20px;margin-top:16px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;}
        .aics-upload-all-title{font-weight:700;color:#1e3a8a;font-size:.9rem;}
        .aics-upload-all-hint{font-size:.8rem;color:#64748b;}
        .aics-req-divider{margin:22px 0 18px;border-color:#e2e8f0;}
        .aics-individual-title{font-weight:700;color:#1e293b;font-size:.9rem;margin-bottom:12px;}
        .aics-track-link{font-size:.8rem;color:#6c757d;margin-top:16px;}
        .aics-track-link a{color:var(--primary-blue);font-weight:600;}
        .aics-waiting-box{text-align:center;padding:32px 20px;background:#f8fafc;border-radius:14px;border:1px dashed #c7d2fe;}
        .aics-waiting-title{font-weight:700;color:#1e293b;margin-bottom:8px;}
        .aics-waiting-text{font-size:.87rem;color:#64748b;line-height:1.7;}

        /* Monitor */
        .monitor-body{padding:0;}
        .monitor-stats-bar{display:grid;grid-template-columns:repeat(4,1fr);border-bottom:1px solid var(--border-light);background:rgba(44,62,143,.04);}
        .monitor-stat{padding:18px;text-align:center;border-right:1px solid var(--border-light);}
        .monitor-stat:last-child{border-right:none;}
        .monitor-stat .sv{font-size:1.6rem;font-weight:900;color:var(--primary-blue);}
        .monitor-stat .sl{font-size:.66rem;color:#64748b;font-weight:700;text-transform:uppercase;margin-top:4px;}
        .monitor-layout{display:grid;grid-template-columns:minmax(280px,320px) 1fr;gap:0;}
        .monitor-aside{padding:24px;border-right:1px solid var(--border-light);background:#fafbfc;}
        .monitor-main{padding:24px 32px 32px;}
        .status-pill{display:inline-flex;border-radius:20px;padding:6px 16px;font-size:.76rem;font-weight:800;text-transform:uppercase;}
        .status-pill.pending{background:#FFF3D6;color:#856404;}
        .status-pill.confirmed{background:#dbeafe;color:#1d4ed8;}
        .status-pill.validated{background:#dcfce7;color:#15803d;}
        .status-pill.approved{background:#dcfce7;color:#15803d;}
        .timeline{display:flex;flex-direction:column;gap:0;margin:16px 0;}
        .tl-item{display:flex;gap:12px;position:relative;padding-bottom:14px;}
        .tl-item:not(:last-child)::before{content:'';position:absolute;left:14px;top:30px;bottom:0;width:2px;background:var(--border-light);}
        .tl-item.done:not(:last-child)::before{background:#86efac;}
        .tl-dot{width:30px;height:30px;border-radius:50%;border:2px solid var(--border-light);background:white;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:800;color:#94a3b8;flex-shrink:0;z-index:1;}
        .tl-item.done .tl-dot{background:#dcfce7;border-color:#22c55e;color:#15803d;}
        .tl-item.active .tl-dot{background:var(--primary-gradient);border-color:var(--primary-blue);color:white;}
        .tl-title{font-weight:700;font-size:.84rem;}
        .tl-item.active .tl-title{color:var(--primary-blue);}
        .tl-desc{font-size:.74rem;color:#64748b;margin-top:2px;}
        .aside-label{font-size:.72rem;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;margin-bottom:10px;}
        .mon-btn{display:flex;align-items:center;justify-content:center;gap:8px;border-radius:10px;padding:10px 16px;font-weight:700;font-size:.82rem;cursor:pointer;border:none;text-decoration:none;width:100%;margin-bottom:8px;}
        .mon-btn-outline{background:white;color:var(--primary-blue);border:2px solid var(--border-light);}
        .mon-btn-yellow{background:var(--secondary-gradient);color:var(--primary-blue);}

        .toast-notice{position:fixed;top:84px;right:18px;z-index:1081;max-width:420px;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border-radius:12px;padding:12px 16px;box-shadow:0 10px 28px rgba(26,42,92,.35);font-size:.84rem;font-weight:700;}
        .footer-strip{background:var(--primary-gradient);color:rgba(255,255,255,.85);text-align:center;padding:18px;font-size:.85rem;margin-top:auto;}
        .footer-strip strong{color:white;}

        @media(min-width:992px){.guide-steps-grid{grid-template-columns:1fr 1fr;column-gap:28px;}.guide-steps-grid .connector{display:none;}}
        @media(max-width:991px){
            .monitor-layout,.wiz-2col,.resources-grid{grid-template-columns:1fr;}
            .monitor-aside{border-right:none;border-bottom:1px solid var(--border-light);}
            .monitor-stats-bar{grid-template-columns:repeat(2,1fr);}
            .overview-grid{grid-template-columns:repeat(2,1fr);}
            .aics-appt-grid{grid-template-columns:1fr;}
        }
        @media(max-width:576px){
            .wizard-body,.wizard-top,.wizard-steps,.wizard-nav,.monitor-main,.monitor-aside{padding-left:16px;padding-right:16px;}
            .overview-grid{grid-template-columns:1fr;}
        }
        @keyframes slideInRight{from{transform:translateX(400px);opacity:0;}to{transform:translateX(0);opacity:1;}}
        @keyframes slideOutRight{from{transform:translateX(0);opacity:1;}to{transform:translateX(400px);opacity:0;}}
        @keyframes flashTimerShrink{from{width:100%;}to{width:0%;}}
    </style>
</head>
<body>

<div class="top-bar">
    <div class="container-fluid px-3 px-lg-4">
        <div class="top-bar-inner">
            <div class="brand"><img src="{{ asset('images/mswd-logo.png') }}" alt="MSWD"><span>MSWDO</span></div>
            <div class="d-flex align-items-center gap-3">
                <div class="lang-toggle">
                    <button class="lang-btn active" data-lang="en" onclick="setLang('en')">EN</button>
                    <button class="lang-btn" data-lang="tl" onclick="setLang('tl')">TL</button>
                </div>
                <a href="{{ $aicsBackRoute }}" class="back-btn">&#8592; <span data-en="Back to AICS Categories" data-tl="Bumalik sa AICS Categories">Back to AICS Categories</span></a>
            </div>
        </div>
    </div>
</div>

<section class="hero-banner" id="hero-banner">
    <div class="container-fluid px-3 px-lg-4">
        <div class="hero-inner">
            <div class="hero-badge">{{ $aicsProgramLabel }}</div>
            <h1 id="hero-title" data-en="{{ $aicsTitleEn }}" data-tl="{{ $aicsTitleTl }}">{{ $aicsTitleEn }}</h1>
            <div class="hero-divider"></div>
            <p class="hero-sub" id="hero-sub"
               data-en="Book your interview, follow each step, and track your {{ $aicsProgramLabel }} application — all in one guided flow."
               data-tl="Mag-book ng panayam, sundin ang bawat hakbang, at subaybayan ang iyong {{ $aicsProgramLabel }} application — lahat sa isang gabay na daloy.">
                Book your interview, follow each step, and track your {{ $aicsProgramLabel }} application — all in one guided flow.
            </p>
        </div>
    </div>
</section>

@php
    $topNotice = session('appt_success') ?: session('appt_error');
    $hasAppointment = isset($appointment) && $appointment && in_array($appointment->status ?? '', ['pending','confirmed','validated']);
    $apptStatus = $appointment->status ?? null;
    $isValidated = $apptStatus === 'validated';
    $isConfirmed = $apptStatus === 'confirmed';
    $isPending = $apptStatus === 'pending';

    $fm = ($application ?? null)?->fileMonitoring;
    if (!$fm && isset($application) && $application) {
        $fm = \App\Models\FileMonitoring::where('application_id', $application->id)->first();
    }
    $overallStatus = $fm ? $fm->overall_status : null;
    $uploadedCount = $uploadedFiles->count();
    $approvedCount = $uploadedFiles->where('status', 'approved')->count();
    $totalReqs = count($requirements);

    $wizardSteps = ['overview', 'book', 'guide', 'office', 'requirements', 'finish'];

    $tlBooked = $hasAppointment;
    $tlConfirmed = in_array($apptStatus, ['confirmed', 'validated']);
    $tlValidated = $isValidated;
    $tlDocuments = $uploadedCount > 0;
    $tlApproved = $overallStatus === 'approved';
    $tlAssistance = $application && in_array($application->id_status ?? '', ['ready_for_pickup', 'released']);

    if ($tlAssistance) {
        $activeTimeline = 6;
    } elseif ($tlApproved) {
        $activeTimeline = 5;
    } elseif ($tlDocuments) {
        $activeTimeline = 4;
    } elseif ($tlValidated) {
        $activeTimeline = 3;
    } elseif ($tlConfirmed) {
        $activeTimeline = 2;
    } elseif ($tlBooked) {
        $activeTimeline = 1;
    } else {
        $activeTimeline = 0;
    }

    $overallLabel = match(true) {
        $application && ($application->id_status ?? null) === 'released' => 'Assistance Released',
        $tlAssistance => 'Assistance Ready for Pickup',
        $overallStatus === 'approved' => 'Requirements Approved',
        $isValidated => 'Eligible — Upload Documents',
        $isConfirmed => 'Appointment Confirmed',
        $isPending => 'Awaiting Confirmation',
        default => 'Not Started',
    };
    $overallPillClass = match(true) {
        $application && ($application->id_status ?? null) === 'released' => 'approved',
        $tlAssistance => 'validated',
        $overallStatus === 'approved' => 'approved',
        $isValidated => 'validated',
        $isConfirmed => 'confirmed',
        $isPending => 'pending',
        default => 'pending',
    };

    $guideStepCount = count($aicsGuideSteps ?? []);
@endphp

@if($topNotice)
<div id="flashNotification" class="toast-notice" style="animation:slideInRight .4s ease;">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
        <span>{{ $topNotice }}</span>
        <button onclick="closeFlashNotification()" style="background:transparent;border:none;color:rgba(255,255,255,.7);font-size:1.2rem;cursor:pointer;">&times;</button>
    </div>
    <div id="flashTimer" style="position:absolute;bottom:0;left:0;height:3px;background:rgba(253,185,19,.9);width:100%;"></div>
</div>
@endif

<div class="flex-grow-1 py-3 pb-4">
    <div class="container-fluid px-3 px-lg-4">

        {{-- MONITOR VIEW --}}
        <div id="aics-monitor-view" style="display:none;">
            <div class="monitor-card">
                <div class="monitor-header">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;">
                        <div>
                            <h2 data-en="Your {{ $aicsProgramLabel }} Application" data-tl="Iyong {{ $aicsProgramLabel }} Application">Your {{ $aicsProgramLabel }} Application</h2>
                            <p data-en="Track your appointment, upload documents, and monitor your assistance application." data-tl="Subaybayan ang appointment, mag-upload ng dokumento, at i-monitor ang aplikasyon para sa tulong.">Track your appointment, upload documents, and monitor your assistance application.</p>
                        </div>
                        <span class="status-pill {{ $overallPillClass }}">{{ $overallLabel }}</span>
                    </div>
                </div>

                <div class="monitor-stats-bar">
                    <div class="monitor-stat"><div class="sv">{{ $hasAppointment ? '✓' : '—' }}</div><div class="sl" data-en="Booked" data-tl="Naka-book">Booked</div></div>
                    <div class="monitor-stat"><div class="sv">{{ $isValidated || $isConfirmed ? '✓' : '—' }}</div><div class="sl" data-en="Confirmed" data-tl="Na-confirm">Confirmed</div></div>
                    <div class="monitor-stat"><div class="sv">{{ $uploadedCount }}</div><div class="sl" data-en="Uploaded" data-tl="Na-upload">Uploaded</div></div>
                    <div class="monitor-stat"><div class="sv">{{ $approvedCount }}</div><div class="sl" data-en="Approved" data-tl="Approved">Approved</div></div>
                </div>

                <div class="monitor-body">
                    <div class="monitor-layout">
                        <aside class="monitor-aside">
                            <div class="aside-label" data-en="Application Timeline" data-tl="Timeline ng Aplikasyon">Application Timeline</div>
                            <div class="timeline">
                                @php
                                    $timelineItems = [
                                        ['done'=>$tlBooked,'active'=>$activeTimeline===1,'en'=>'Appointment Booked','tl'=>'Naka-book ang Appointment','descEn'=>'Your interview slot is scheduled.','descTl'=>'Naka-schedule na ang iyong panayam.'],
                                        ['done'=>$tlConfirmed,'active'=>$activeTimeline===2,'en'=>'Appointment Confirmed','tl'=>'Na-confirm ang Appointment','descEn'=>'MSWDO confirmed your appointment.','descTl'=>'Na-confirm ng MSWDO ang iyong appointment.'],
                                        ['done'=>$tlValidated,'active'=>$activeTimeline===3,'en'=>'Eligibility Validated','tl'=>'Na-validate ang Eligibility','descEn'=>'You passed the eligibility assessment.','descTl'=>'Nakapasa ka sa eligibility assessment.'],
                                        ['done'=>$tlDocuments,'active'=>$activeTimeline===4,'en'=>'Documents Submitted','tl'=>'Na-submit ang Dokumento','descEn'=>'Upload required documents online.','descTl'=>'I-upload ang mga kinakailangang dokumento.'],
                                        ['done'=>$tlApproved,'active'=>$activeTimeline===5,'en'=>'Requirements Approved','tl'=>'Naaprubahan ang Requirements','descEn'=>'Admin approved your documents.','descTl'=>'Naaprubahan ng admin ang mga dokumento.'],
                                        ['done'=>$tlAssistance,'active'=>$activeTimeline===6,'en'=>'Assistance Released','tl'=>'Na-release ang Tulong','descEn'=>'Your financial assistance grant is ready or has been released.','descTl'=>'Handa na o na-release na ang iyong tulong pinansyal.'],
                                    ];
                                @endphp
                                @foreach($timelineItems as $i => $tl)
                                <div class="tl-item {{ $tl['done']?'done':'' }} {{ $tl['active']?'active':'' }}">
                                    <div class="tl-dot">{{ $tl['done']?'✓':($i+1) }}</div>
                                    <div>
                                        <div class="tl-title" data-en="{{ $tl['en'] }}" data-tl="{{ $tl['tl'] }}">{{ $tl['en'] }}</div>
                                        <div class="tl-desc" data-en="{{ $tl['descEn'] }}" data-tl="{{ $tl['descTl'] }}">{{ $tl['descEn'] }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="aside-label" data-en="Quick Actions" data-tl="Mabilis na Aksyon">Quick Actions</div>
                            <button type="button" class="mon-btn mon-btn-outline" onclick="showWizardView()">
                                <span data-en="View Application Guide" data-tl="Tingnan ang Gabay">View Application Guide</span>
                            </button>
                        </aside>

                        <div class="monitor-main">
                            <div class="panel-heading" data-en="Your Appointment" data-tl="Iyong Appointment">Your Appointment</div>
                            <div class="panel-sub" data-en="Manage your interview booking below." data-tl="Pamahalaan ang iyong booking sa panayam sa ibaba.">Manage your interview booking below.</div>
                            @include('user.partials.aics-booking', ['bookingPrefix' => 'mon'])

                            @if($isValidated)
                            <div class="panel-heading mt-4" data-en="Required Documents" data-tl="Mga Kinakailangang Dokumento">Required Documents</div>
                            <div class="panel-sub" data-en="Upload your documents for admin review." data-tl="I-upload ang iyong mga dokumento para sa review ng admin.">Upload your documents for admin review.</div>
                            @include('user.partials.aics-requirements', [
                                'reqPrefix' => 'mon',
                                'uploadCols' => 'col-md-6 col-lg-6',
                                'aicsBatchRoute' => $aicsBatchRoute,
                                'aicsSingleRoute' => $aicsSingleRoute,
                            ])
                            @elseif($isConfirmed)
                            <div class="mt-4">
                                @include('user.partials.aics-requirements', [
                                    'reqPrefix' => 'mon',
                                    'uploadCols' => 'col-md-6 col-lg-6',
                                    'aicsBatchRoute' => $aicsBatchRoute,
                                    'aicsSingleRoute' => $aicsSingleRoute,
                                ])
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center py-2">
                <a href="{{ route('user.dashboard') }}" class="back-btn d-inline-flex" style="background:var(--primary-gradient);padding:12px 28px;">
                    <span data-en="Return to Dashboard" data-tl="Bumalik sa Dashboard">Return to Dashboard</span>
                </a>
            </div>
        </div>

        {{-- WIZARD VIEW --}}
        <div id="aics-wizard-view">
            <div class="wizard-card">
                <div class="wizard-top">
                    <h2 id="wizard-step-title">Step 1 — Overview</h2>
                    <p id="wizard-step-desc" data-en="Learn how the {{ $aicsProgramLabel }} application works." data-tl="Alamin kung paano gumagana ang {{ $aicsProgramLabel }} application.">Learn how the {{ $aicsProgramLabel }} application works.</p>
                    <div class="wizard-progress-track"><div class="wizard-progress-fill" id="wizard-progress-fill"></div></div>
                </div>

                <div class="wizard-steps" id="wizard-steps-nav">
                    @foreach($wizardSteps as $i => $step)
                    @php
                        $labels = [
                            'overview' => ['en'=>'Overview','tl'=>'Pangkalahatan'],
                            'book' => ['en'=>'Book Appointment','tl'=>'Mag-book'],
                            'guide' => ['en'=>'How to Apply','tl'=>'Paano Mag-apply'],
                            'office' => ['en'=>'Office Info','tl'=>'Opisina'],
                            'requirements' => ['en'=>'Documents','tl'=>'Dokumento'],
                            'finish' => ['en'=>'Finish','tl'=>'Tapos'],
                        ];
                    @endphp
                    <button type="button" class="wiz-step {{ $i===0?'active':'' }}" data-step="{{ $i }}">
                        <span class="wiz-step-circle">{{ $i+1 }}</span>
                        <span class="wiz-step-label" data-en="{{ $labels[$step]['en'] }}" data-tl="{{ $labels[$step]['tl'] }}">{{ $labels[$step]['en'] }}</span>
                    </button>
                    @endforeach
                </div>

                <div class="wizard-body">
                    {{-- OVERVIEW --}}
                    <div class="wizard-panel active" data-panel="overview">
                        <div class="panel-heading" data-en="Welcome to {{ $aicsProgramLabel }}" data-tl="Maligayang Pagdating sa {{ $aicsProgramLabel }}">Welcome to {{ $aicsProgramLabel }}</div>
                        <div class="panel-sub" data-en="This wizard guides you from booking an interview to receiving your financial assistance." data-tl="Gagabayan ka ng wizard na ito mula sa pag-book ng panayam hanggang sa pagtanggap ng tulong pinansyal.">This wizard guides you from booking an interview to receiving your financial assistance.</div>
                        <div class="overview-grid">
                            <div class="overview-tile"><div class="ot-num">1</div><div class="ot-label" data-en="Book an interview appointment" data-tl="Mag-book ng appointment sa panayam">Book an interview appointment</div></div>
                            <div class="overview-tile"><div class="ot-num">{{ $guideStepCount }}</div><div class="ot-label" data-en="Steps to complete your application" data-tl="Hakbang para makumpleto ang aplikasyon">Steps to complete your application</div></div>
                            <div class="overview-tile"><div class="ot-num">{{ $totalReqs }}</div><div class="ot-label" data-en="Documents to upload after validation" data-tl="Dokumentong i-upload pagkatapos ma-validate">Documents to upload after validation</div></div>
                        </div>
                        <div class="wiz-2col mt-3">
                            <div class="info-card yellow mb-0">
                                <div class="ic-title" data-en="Important: Book First" data-tl="Mahalaga: Mag-book Muna">Important: Book First</div>
                                <div class="ic-body" data-en="You must schedule an appointment before proceeding. Choose face-to-face or online interview." data-tl="Kailangan mong mag-schedule ng appointment bago magpatuloy. Pumili ng harapan o online na panayam.">You must schedule an appointment before proceeding. Choose face-to-face or online interview.</div>
                            </div>
                            <div class="info-card mb-0">
                                <div class="ic-title" data-en="After the Interview" data-tl="Pagkatapos ng Panayam">After the Interview</div>
                                <div class="ic-body" data-en="If eligible, you'll receive a requirements list via email and can upload documents here." data-tl="Kung karapat-dapat, makakatanggap ka ng listahan ng requirements sa email at maaari kang mag-upload dito.">If eligible, you'll receive a requirements list via email and can upload documents here.</div>
                            </div>
                        </div>
                    </div>

                    {{-- BOOK --}}
                    <div class="wizard-panel" data-panel="book">
                        <div class="panel-heading" data-en="Book Your Appointment" data-tl="Mag-book ng Appointment">Book Your Appointment</div>
                        <div class="panel-sub" data-en="Schedule a face-to-face or online interview with the MSWDO. This is the first required step." data-tl="Mag-schedule ng harapan o online na panayam sa MSWDO. Ito ang unang kinakailangang hakbang.">Schedule a face-to-face or online interview with the MSWDO. This is the first required step.</div>
                        @include('user.partials.aics-booking', ['bookingPrefix' => 'wiz'])
                    </div>

                    {{-- GUIDE --}}
                    <div class="wizard-panel" data-panel="guide">
                        <div class="panel-heading" data-en="How to Apply for {{ $aicsProgramLabel }}" data-tl="Paano Mag-apply ng {{ $aicsProgramLabel }}">How to Apply for {{ $aicsProgramLabel }}</div>
                        <div class="panel-sub" data-en="Follow these {{ $guideStepCount }} official steps to complete your application." data-tl="Sundin ang {{ $guideStepCount }} opisyal na hakbang na ito.">Follow these {{ $guideStepCount }} official steps to complete your application.</div>
                        <div class="guide-steps-grid">
                            @foreach($aicsGuideSteps as $i => $gs)
                            <div class="step-item">
                                <div class="step-num">{{ $i+1 }}</div>
                                <div>
                                    <div class="step-title" data-en="{{ $gs['en'] }}" data-tl="{{ $gs['tl'] }}">{{ $gs['en'] }}</div>
                                    <div class="step-desc" data-en="{{ $gs['descEn'] }}" data-tl="{{ $gs['descTl'] }}">{{ $gs['descEn'] }}</div>
                                </div>
                            </div>
                            @if($i < count($aicsGuideSteps)-1)<div class="connector"></div>@endif
                            @endforeach
                        </div>
                    </div>

                    {{-- OFFICE --}}
                    <div class="wizard-panel" data-panel="office">
                        <div class="panel-heading" data-en="MSWDO Office Details" data-tl="Detalye ng Opisina ng MSWDO">MSWDO Office Details</div>
                        <div class="panel-sub" data-en="Where to attend your interview and submit requirements." data-tl="Saan dadaluhan ang panayam at isusumite ang mga kinakailangan.">Where to attend your interview and submit requirements.</div>
                        <div class="resources-grid">
                            <div>
                                <div class="info-card"><div class="ic-title" data-en="Location" data-tl="Lokasyon">Location</div><div class="ic-body" data-en="Municipal Social Welfare and Development Office, Municipal Hall, Ground Floor" data-tl="Municipal Social Welfare and Development Office, Municipal Hall, Ground Floor">Municipal Social Welfare and Development Office, Municipal Hall, Ground Floor</div></div>
                                <div class="info-card"><div class="ic-title" data-en="Office Hours" data-tl="Oras ng Opisina">Office Hours</div><div class="ic-body">Monday – Friday, 8:00 AM – 5:00 PM <small style="color:#94a3b8;">(Closed on Holidays)</small></div></div>
                            </div>
                            <div class="info-card yellow mb-0">
                                <div class="ic-title" data-en="Interview Options" data-tl="Mga Opsyon sa Panayam">Interview Options</div>
                                <div class="ic-body"><ul style="margin:0;padding-left:18px;line-height:2;"><li data-en="Face-to-face at the MSWDO office" data-tl="Harapan sa opisina ng MSWDO">Face-to-face at the MSWDO office</li><li data-en="Online interview (if you cannot visit in person)" data-tl="Online na panayam">Online interview (if you cannot visit in person)</li></ul></div>
                            </div>
                        </div>
                    </div>

                    {{-- REQUIREMENTS --}}
                    <div class="wizard-panel" data-panel="requirements">
                        <div class="panel-heading" data-en="Required Documents" data-tl="Mga Kinakailangang Dokumento">Required Documents</div>
                        <div class="panel-sub" data-en="Available after your interview and eligibility validation." data-tl="Available pagkatapos ng panayam at eligibility validation.">Available after your interview and eligibility validation.</div>
                        @include('user.partials.aics-requirements', [
                            'reqPrefix' => 'wiz',
                            'uploadCols' => 'col-md-6 col-lg-4',
                            'aicsBatchRoute' => $aicsBatchRoute,
                            'aicsSingleRoute' => $aicsSingleRoute,
                        ])
                    </div>

                    {{-- FINISH --}}
                    <div class="wizard-panel" data-panel="finish">
                        <div class="panel-heading" data-en="You're All Set!" data-tl="Handa Ka Na!">You're All Set!</div>
                        <div class="panel-sub" data-en="You've completed the application guide. Use the tracker to monitor your appointment and upload documents." data-tl="Natapos mo na ang gabay. Gamitin ang tracker para subaybayan ang appointment at mag-upload ng dokumento.">You've completed the application guide. Use the tracker to monitor your appointment and upload documents.</div>
                        <div class="info-card">
                            <div class="ic-title" data-en="What happens next?" data-tl="Ano ang susunod?">What happens next?</div>
                            <div class="ic-body">
                                <ul style="margin:0;padding-left:18px;line-height:2.2;">
                                    <li data-en="Wait for your appointment confirmation via email" data-tl="Hintayin ang kumpirmasyon ng appointment sa email">Wait for your appointment confirmation via email</li>
                                    <li data-en="Attend your scheduled interview" data-tl="Dumalo sa nakatakdang panayam">Attend your scheduled interview</li>
                                    <li data-en="Upload documents once validated as eligible" data-tl="Mag-upload ng dokumento kapag na-validate na">Upload documents once validated as eligible</li>
                                    <li data-en="Track everything in your Application Tracker" data-tl="Subaybayan ang lahat sa Application Tracker">Track everything in your Application Tracker</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wizard-nav">
                    <button type="button" class="wiz-btn-prev" id="wiz-btn-prev" disabled onclick="wizardPrev()">&#8592; <span data-en="Previous" data-tl="Nakaraan">Previous</span></button>
                    <span class="wiz-step-counter" id="wiz-step-counter">Step 1 of {{ count($wizardSteps) }}</span>
                    <button type="button" class="wiz-btn-next" id="wiz-btn-next" onclick="wizardNext()"><span data-en="Next" data-tl="Susunod">Next</span> &#8594;</button>
                    <button type="button" class="wiz-btn-finish" id="wiz-btn-finish" onclick="finishWizard()"><span data-en="Finish & Track Application" data-tl="Tapusin at Subaybayan">Finish & Track Application</span> ✓</button>
                </div>
            </div>

            <div class="text-center py-2">
                <a href="{{ route('user.dashboard') }}" class="back-btn d-inline-flex" style="background:var(--primary-gradient);padding:12px 28px;">
                    <span data-en="Return to Dashboard" data-tl="Bumalik sa Dashboard">Return to Dashboard</span>
                </a>
            </div>
        </div>

    </div>
</div>

<div class="footer-strip"><strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}</div>

{{-- Cancel Modal --}}
<div id="cancelModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;max-width:500px;width:90%;padding:28px;">
        <h4 style="font-weight:800;margin-bottom:16px;" data-en="Cancel Appointment" data-tl="Kanselahin ang Appointment">Cancel Appointment</h4>
        <p style="font-size:.9rem;color:#64748b;margin-bottom:20px;" data-en="Please provide a reason for cancelling your appointment:" data-tl="Mangyaring magbigay ng dahilan sa pagkansela ng appointment:">Please provide a reason for cancelling your appointment:</p>
        <textarea id="cancelReasonText" rows="4" style="width:100%;border:1.5px solid #e2e8f0;border-radius:10px;padding:12px;font-size:.88rem;" placeholder="e.g., May emergency po sa family" required></textarea>
        <div style="display:flex;gap:10px;margin-top:20px;justify-content:flex-end;">
            <button onclick="hideCancelModal()" class="aics-btn" style="background:#e2e8f0;color:#64748b;" data-en="Cancel" data-tl="Kanselahin">Cancel</button>
            <button onclick="submitCancel()" class="aics-btn aics-btn-danger" data-en="Confirm Cancel" data-tl="Kumpirmahin ang Kanselasyon">Confirm Cancel</button>
        </div>
    </div>
</div>

{{-- Reschedule Modal --}}
<div id="rescheduleModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;overflow-y:auto;">
    <div style="background:white;border-radius:16px;max-width:600px;width:90%;padding:28px;margin:20px;">
        <h4 style="font-weight:800;margin-bottom:16px;" data-en="Request Reschedule" data-tl="Humiling ng Reschedule">Request Reschedule</h4>
        <form method="POST" action="{{ route('user.appointments.reschedule', $appointment->id ?? 0) }}" id="rescheduleForm">
            @csrf
            <div style="margin-bottom:16px;">
                <label class="aics-label">New Date <span class="text-danger">*</span></label>
                <input type="date" name="reschedule_date" id="rescheduleDate" min="{{ $minDate ?? '' }}" max="{{ $maxDate ?? '' }}" required class="form-control aics-input">
            </div>
            <div style="margin-bottom:16px;">
                <label class="aics-label">New Time <span class="text-danger">*</span></label>
                <select name="reschedule_time" id="rescheduleTime" required disabled class="form-control aics-input"><option value="">Select date first</option></select>
                <div id="rescheduleSlotMsg" class="aics-hint"></div>
            </div>
            <div style="margin-bottom:20px;">
                <label class="aics-label">Reason for Reschedule <span class="text-danger">*</span></label>
                <textarea name="reschedule_reason" rows="3" required class="form-control aics-input" placeholder="e.g., May conflict sa schedule"></textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button type="button" onclick="hideRescheduleModal()" class="aics-btn" style="background:#e2e8f0;color:#64748b;">Cancel</button>
                <button type="submit" class="aics-btn aics-btn-primary">Submit Request</button>
            </div>
        </form>
    </div>
</div>

@include('components.user-notification-modal')
@include('components.chat-modal')
@include('components.chatbot-widget')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const AICS_STORAGE_KEY = @json($aicsStorageKey);
const WIZARD_STEPS = @json($wizardSteps);
const HAS_APPOINTMENT = @json($hasAppointment);
const APPT_NOTICE = @json((bool) session('appt_success'));
const AICS_TITLE_EN = @json($aicsTitleEn);
const AICS_TITLE_TL = @json($aicsTitleTl);
const AICS_PROGRAM_LABEL = @json($aicsProgramLabel);

const WIZARD_META = {
    overview: { titleEn:'Step 1 — Overview', titleTl:'Hakbang 1 — Pangkalahatan', descEn:'Learn how the ' + AICS_PROGRAM_LABEL + ' application works.', descTl:'Alamin kung paano gumagana ang ' + AICS_PROGRAM_LABEL + ' application.' },
    book: { titleEn:'Step 2 — Book Appointment', titleTl:'Hakbang 2 — Mag-book ng Appointment', descEn:'Schedule your interview with the MSWDO.', descTl:'Mag-schedule ng panayam sa MSWDO.' },
    guide: { titleEn:'Step 3 — How to Apply', titleTl:'Hakbang 3 — Paano Mag-apply', descEn:'Follow the official application process.', descTl:'Sundin ang opisyal na proseso ng aplikasyon.' },
    office: { titleEn:'Step 4 — Office Info', titleTl:'Hakbang 4 — Opisina', descEn:'MSWDO office location and interview options.', descTl:'Lokasyon ng opisina at mga opsyon sa panayam.' },
    requirements: { titleEn:'Step 5 — Documents', titleTl:'Hakbang 5 — Dokumento', descEn:'Upload requirements after eligibility validation.', descTl:'Mag-upload pagkatapos ma-validate.' },
    finish: { titleEn:'Step 6 — Finish', titleTl:'Hakbang 6 — Tapos', descEn:'Complete the guide and start tracking.', descTl:'Tapusin ang gabay at magsimulang mag-track.' },
};

let currentStep = 0, currentLang = 'en';

function goToStep(index, save = true) {
    if (index < 0 || index >= WIZARD_STEPS.length) return;
    currentStep = index;
    const key = WIZARD_STEPS[index];
    const meta = WIZARD_META[key] || WIZARD_META.overview;
    document.querySelectorAll('.wizard-panel').forEach(p => p.classList.toggle('active', p.dataset.panel === key));
    document.querySelectorAll('.wiz-step').forEach((btn, i) => {
        btn.classList.remove('active','done');
        if (i < index) btn.classList.add('done');
        if (i === index) btn.classList.add('active');
        btn.querySelector('.wiz-step-circle').textContent = i < index ? '✓' : (i+1);
    });
    const titleEl = document.getElementById('wizard-step-title');
    const descEl = document.getElementById('wizard-step-desc');
    titleEl.textContent = currentLang === 'tl' ? meta.titleTl : meta.titleEn;
    descEl.textContent = currentLang === 'tl' ? meta.descTl : meta.descEn;
    document.getElementById('wizard-progress-fill').style.width = ((index+1)/WIZARD_STEPS.length*100)+'%';
    document.getElementById('wiz-btn-prev').disabled = index === 0;
    const isLast = index === WIZARD_STEPS.length - 1;
    document.getElementById('wiz-btn-next').style.display = isLast ? 'none' : 'inline-flex';
    document.getElementById('wiz-btn-finish').style.display = isLast ? 'inline-flex' : 'none';
    document.getElementById('wiz-step-counter').textContent = currentLang === 'tl' ? `Hakbang ${index+1} ng ${WIZARD_STEPS.length}` : `Step ${index+1} of ${WIZARD_STEPS.length}`;
    if (save) sessionStorage.setItem(AICS_STORAGE_KEY + 'WizardStep', String(index));
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
function wizardNext() { goToStep(currentStep + 1); }
function wizardPrev() { goToStep(currentStep - 1); }

function shouldShowMonitor() {
    if (sessionStorage.getItem(AICS_STORAGE_KEY + 'WizardComplete') === '1') return true;
    if (HAS_APPOINTMENT) return true;
    if (APPT_NOTICE) return true;
    return false;
}
function showMonitorView() {
    document.getElementById('aics-wizard-view').style.display = 'none';
    document.getElementById('aics-monitor-view').style.display = 'block';
    document.getElementById('hero-title').textContent = currentLang === 'tl' ? ('Tracker ng ' + AICS_PROGRAM_LABEL) : (AICS_PROGRAM_LABEL + ' Tracker');
    document.getElementById('hero-sub').textContent = currentLang === 'tl'
        ? 'Subaybayan ang appointment, mag-upload ng dokumento, at i-monitor ang progress ng aplikasyon.'
        : 'Track your appointment, upload documents, and monitor your assistance application progress.';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
function showWizardView() {
    document.getElementById('aics-monitor-view').style.display = 'none';
    document.getElementById('aics-wizard-view').style.display = 'block';
    document.getElementById('hero-title').textContent = currentLang === 'tl' ? AICS_TITLE_TL : AICS_TITLE_EN;
    document.getElementById('hero-sub').textContent = currentLang === 'tl'
        ? ('Mag-book ng panayam, sundin ang bawat hakbang, at subaybayan ang iyong ' + AICS_PROGRAM_LABEL + ' application.')
        : ('Book your interview, follow each step, and track your ' + AICS_PROGRAM_LABEL + ' application.');
    goToStep(currentStep, false);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
function finishWizard() {
    sessionStorage.setItem(AICS_STORAGE_KEY + 'WizardComplete', '1');
    sessionStorage.removeItem(AICS_STORAGE_KEY + 'WizardStep');
    showMonitorView();
}

function initApptSlotLoader(prefix) {
    const dateInput = document.getElementById('apptDate-' + prefix);
    const timeSelect = document.getElementById('apptTime-' + prefix);
    const slotMsg = document.getElementById('slotMsg-' + prefix);
    if (!dateInput || !timeSelect) return;
    dateInput.addEventListener('change', function() {
        const date = this.value;
        if (!date) return;
        const d = new Date(date + 'T00:00:00');
        if (d.getDay() === 0 || d.getDay() === 6) {
            timeSelect.innerHTML = '<option value="">Weekdays only</option>';
            timeSelect.disabled = true;
            if (slotMsg) { slotMsg.textContent = 'Please select a weekday (Mon–Fri).'; slotMsg.style.color = '#dc3545'; }
            return;
        }
        timeSelect.disabled = true;
        timeSelect.innerHTML = '<option value="">Loading slots…</option>';
        if (slotMsg) slotMsg.textContent = '';
        fetch(`/user/appointments/slots?date=${date}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(slots => {
            timeSelect.innerHTML = '<option value="">Choose a time</option>';
            let available = 0;
            slots.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.time;
                opt.textContent = s.full ? `${s.label} — FULL` : `${s.label} (${s.remaining} slot${s.remaining !== 1 ? 's' : ''} left)`;
                if (s.full) opt.disabled = true;
                else available++;
                timeSelect.appendChild(opt);
            });
            timeSelect.disabled = false;
            if (slotMsg) {
                slotMsg.textContent = available > 0 ? `${available} time slot${available > 1 ? 's' : ''} available` : 'No slots available. Pick another day.';
                slotMsg.style.color = available > 0 ? '#16a34a' : '#dc3545';
            }
        })
        .catch(() => { timeSelect.innerHTML = '<option value="">Error loading slots</option>'; });
    });
}
['mon', 'wiz'].forEach(initApptSlotLoader);

let activeCancelPrefix = 'mon';
function showCancelModal(prefix) {
    activeCancelPrefix = prefix || 'mon';
    document.getElementById('cancelModal').style.display = 'flex';
}
function hideCancelModal() { document.getElementById('cancelModal').style.display = 'none'; }
function submitCancel() {
    const reason = document.getElementById('cancelReasonText').value.trim();
    if (!reason) { alert('Please provide a reason for cancellation.'); return; }
    document.getElementById('cancelReasonInput-' + activeCancelPrefix).value = reason;
    document.getElementById('cancelForm-' + activeCancelPrefix).submit();
}
function showRescheduleModal() { document.getElementById('rescheduleModal').style.display = 'flex'; }
function hideRescheduleModal() { document.getElementById('rescheduleModal').style.display = 'none'; }

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
            rescheduleSlotMsg.textContent = 'Please select a weekday (Mon–Fri).';
            return;
        }
        rescheduleTime.disabled = true;
        rescheduleTime.innerHTML = '<option value="">Loading slots…</option>';
        fetch(`/user/appointments/slots?date=${date}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(slots => {
            rescheduleTime.innerHTML = '<option value="">Choose a time</option>';
            slots.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.time;
                opt.textContent = s.full ? `${s.label} — FULL` : `${s.label} (${s.remaining} left)`;
                if (s.full) opt.disabled = true;
                rescheduleTime.appendChild(opt);
            });
            rescheduleTime.disabled = false;
        });
    });
}

function validateAicsFile(input) {
    const file = input.files[0];
    if (!file) return;
    const isImage = ['image/jpeg','image/jpg','image/png'].includes(file.type);
    const maxSize = isImage ? 5 * 1024 * 1024 : 25 * 1024 * 1024;
    if (file.size > maxSize) {
        alert('File size must be less than ' + (isImage ? '5MB' : '25MB') + ' for ' + (isImage ? 'images' : 'PDF files') + '.');
        input.value = '';
        return false;
    }
    return true;
}

['mon', 'wiz'].forEach(prefix => {
    const batchForm = document.getElementById('aicsBatch-' + prefix);
    if (!batchForm) return;
    batchForm.addEventListener('submit', function(e) {
        const inputs = this.querySelectorAll('.batch-file-input');
        const hasFile = Array.from(inputs).some(i => i.files.length > 0);
        if (!hasFile) {
            e.preventDefault();
            alert('Please select at least one file before uploading.');
            return;
        }
        inputs.forEach(i => { if (!i.files.length) i.disabled = true; });
        const btn = document.getElementById('aicsBatchBtn-' + prefix);
        if (btn) { btn.textContent = 'Uploading...'; btn.disabled = true; }
    });
});

function closeFlashNotification() {
    const n = document.getElementById('flashNotification');
    if (n) { n.style.animation = 'slideOutRight .4s ease forwards'; setTimeout(() => n.remove(), 400); }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.wiz-step').forEach(btn => {
        btn.addEventListener('click', () => { const s = parseInt(btn.dataset.step,10); if (s <= currentStep) goToStep(s); });
    });
    const timer = document.getElementById('flashTimer');
    if (timer) { timer.style.animation = 'flashTimerShrink 5s linear forwards'; setTimeout(closeFlashNotification, 5000); }

    if (shouldShowMonitor()) showMonitorView();
    else {
        const saved = sessionStorage.getItem(AICS_STORAGE_KEY + 'WizardStep');
        if (APPT_NOTICE && WIZARD_STEPS.includes('book')) goToStep(WIZARD_STEPS.indexOf('book'), false);
        else if (saved !== null) { const idx = parseInt(saved,10); if (!isNaN(idx) && idx < WIZARD_STEPS.length) goToStep(idx, false); }
        else goToStep(0, false);
    }
});

function setLang(lang) {
    currentLang = lang;
    document.querySelectorAll('.lang-btn').forEach(b => b.classList.toggle('active', b.dataset.lang === lang));
    document.querySelectorAll('[data-en]').forEach(el => { const t = lang === 'tl' ? (el.dataset.tl || el.dataset.en) : el.dataset.en; if (t) el.textContent = t; });
    document.querySelectorAll('li[data-en]').forEach(li => { const t = lang === 'tl' ? (li.dataset.tl || li.dataset.en) : li.dataset.en; if (t) li.textContent = t; });
    const t = document.querySelector('title'); if (t) t.textContent = lang === 'tl' ? t.dataset.tl : t.dataset.en;
    const key = WIZARD_STEPS[currentStep];
    const meta = WIZARD_META[key] || WIZARD_META.overview;
    const titleEl = document.getElementById('wizard-step-title');
    const descEl = document.getElementById('wizard-step-desc');
    if (titleEl && document.getElementById('aics-wizard-view').style.display !== 'none') {
        titleEl.textContent = lang === 'tl' ? meta.titleTl : meta.titleEn;
        descEl.textContent = lang === 'tl' ? meta.descTl : meta.descEn;
    }
}
</script>
</body>
</html>
