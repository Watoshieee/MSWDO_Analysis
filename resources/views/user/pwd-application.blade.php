<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title data-en="PWD Application Guide - MSWDO" data-tl="Gabay sa PWD Application - MSWDO">PWD Application Guide - MSWDO</title>
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

        .top-bar{background:var(--primary-gradient);padding:14px 0;box-shadow:0 4px 20px rgba(44,62,143,.2);}
        .top-bar-inner{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;}
        .brand{display:flex;align-items:center;gap:12px;color:white;font-weight:800;font-size:1.45rem;}
        .brand img{width:34px;height:34px;object-fit:contain;}
        .back-btn{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);border:2px solid rgba(255,255,255,.4);color:white;border-radius:30px;padding:8px 22px;font-weight:700;font-size:.88rem;cursor:pointer;transition:all .3s;text-decoration:none;}
        .back-btn:hover{background:var(--secondary-yellow);color:var(--primary-blue);border-color:var(--secondary-yellow);}

        .hero-banner{background:var(--primary-gradient);color:white;padding:36px 0 32px;position:relative;overflow:hidden;}
        .hero-banner::before{content:'';position:absolute;top:-90px;right:-90px;width:360px;height:360px;border-radius:50%;background:rgba(253,185,19,.10);}
        .hero-inner{position:relative;z-index:2;}
        .hero-badge{display:inline-block;background:rgba(253,185,19,.18);color:var(--secondary-yellow);border:1px solid rgba(253,185,19,.35);border-radius:30px;padding:5px 18px;font-size:.75rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;margin-bottom:12px;}
        .hero-banner h1{font-size:2rem;font-weight:900;margin-bottom:6px;line-height:1.15;}
        .hero-divider{width:50px;height:4px;background:var(--secondary-yellow);border-radius:2px;margin:12px 0;}
        .hero-banner p.hero-sub{opacity:.85;font-size:.92rem;margin:0;max-width:900px;line-height:1.65;}
        .hero-banner.compact{padding:24px 0 20px;}

        .lang-toggle{display:inline-flex;border-radius:30px;overflow:hidden;border:2px solid rgba(255,255,255,.4);}
        .lang-btn{background:transparent;border:none;color:rgba(255,255,255,.7);font-weight:700;font-size:.82rem;padding:8px 20px;cursor:pointer;transition:all .2s;letter-spacing:.05em;}
        .lang-btn.active{background:var(--secondary-yellow);color:var(--primary-blue);}
        .lang-btn:hover:not(.active){background:rgba(255,255,255,.15);color:white;}

        /* Page shells — both views full width */
        #pwd-wizard-view,#pwd-monitor-view{width:100%;}

        .wizard-card{background:white;border-radius:20px;border:1px solid var(--border-light);box-shadow:0 8px 32px rgba(44,62,143,.08);overflow:hidden;margin-bottom:20px;}
        .wizard-top{background:var(--primary-gradient);color:white;padding:20px 32px 16px;}
        .wizard-top h2{font-size:1.3rem;font-weight:900;margin:0 0 4px;}
        .wizard-top p{margin:0;font-size:.86rem;opacity:.82;}
        .wizard-progress-track{height:6px;background:rgba(255,255,255,.18);border-radius:3px;margin-top:14px;overflow:hidden;}
        .wizard-progress-fill{height:100%;background:var(--secondary-yellow);border-radius:3px;transition:width .4s ease;width:20%;}

        .wizard-steps{display:flex;align-items:flex-start;justify-content:stretch;gap:8px;padding:18px 32px 14px;overflow-x:auto;scrollbar-width:none;-ms-overflow-style:none;background:#fafbfc;border-bottom:1px solid var(--border-light);}
        .wizard-steps::-webkit-scrollbar{display:none;}
        .wiz-step{flex:1;min-width:100px;display:flex;flex-direction:column;align-items:center;gap:8px;cursor:pointer;border:none;background:transparent;padding:0 6px;transition:opacity .2s;}
        .wiz-step:disabled{cursor:default;opacity:.55;}
        .wiz-step-circle{width:40px;height:40px;border-radius:50%;border:2px solid var(--border-light);background:white;color:#94a3b8;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.88rem;transition:all .3s;flex-shrink:0;}
        .wiz-step.active .wiz-step-circle{background:var(--primary-gradient);border-color:var(--primary-blue);color:white;box-shadow:0 4px 14px rgba(44,62,143,.28);}
        .wiz-step.done .wiz-step-circle{background:#dcfce7;border-color:#22c55e;color:#15803d;}
        .wiz-step-label{font-size:.72rem;font-weight:700;color:#64748b;text-align:center;line-height:1.3;max-width:120px;}
        .wiz-step.active .wiz-step-label{color:var(--primary-blue);}
        .wiz-step.done .wiz-step-label{color:#15803d;}

        .wizard-body{padding:28px 32px 24px;min-height:380px;}
        .wizard-panel{display:none;animation:fadeSlideIn .35s ease;}
        .wizard-panel.active{display:block;}
        @keyframes fadeSlideIn{from{opacity:0;transform:translateY(10px);}to{opacity:1;transform:translateY(0);}}

        .wizard-nav{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:16px 32px 22px;border-top:1px solid var(--border-light);background:#fafbfc;}
        .wiz-btn-prev,.wiz-btn-next{display:inline-flex;align-items:center;gap:8px;border-radius:12px;padding:11px 24px;font-weight:700;font-size:.88rem;cursor:pointer;transition:all .25s;border:none;}
        .wiz-btn-prev{background:white;color:var(--primary-blue);border:2px solid var(--border-light);}
        .wiz-btn-prev:hover:not(:disabled){border-color:var(--primary-blue);background:var(--primary-blue-light);}
        .wiz-btn-prev:disabled{opacity:.4;cursor:not-allowed;}
        .wiz-btn-next{background:var(--primary-gradient);color:white;box-shadow:0 4px 14px rgba(44,62,143,.25);}
        .wiz-btn-next:hover{transform:translateY(-1px);box-shadow:0 6px 18px rgba(44,62,143,.35);}
        .wiz-step-counter{font-size:.78rem;color:#64748b;font-weight:600;}

        /* Content blocks */
        .step-item{display:flex;gap:18px;margin-bottom:24px;align-items:flex-start;}
        .step-item:last-child{margin-bottom:0;}
        .step-num{width:40px;height:40px;min-width:40px;border-radius:50%;background:var(--primary-gradient);color:white;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1rem;box-shadow:0 4px 14px rgba(44,62,143,.28);}
        .step-content{flex:1;}
        .step-title{font-weight:700;color:var(--primary-blue);font-size:.97rem;margin-bottom:4px;}
        .step-desc{font-size:.87rem;color:#475569;line-height:1.65;}
        .step-note{background:var(--secondary-yellow-light);border-left:3px solid var(--secondary-yellow);border-radius:8px;padding:10px 14px;font-size:.82rem;color:#856404;margin-top:10px;}
        .step-link{color:var(--primary-blue);font-weight:600;font-size:.84rem;word-break:break-all;}
        .connector{margin-left:19px;border-left:2px dashed var(--border-light);height:16px;}

        .info-card{background:var(--primary-blue-light);border:1px solid rgba(44,62,143,.12);border-radius:14px;padding:18px 22px;margin-bottom:16px;}
        .info-card.yellow{background:var(--secondary-yellow-light);border-color:rgba(253,185,19,.3);}
        .info-card .ic-title{font-weight:700;color:var(--primary-blue);font-size:.88rem;margin-bottom:6px;}
        .info-card .ic-body{font-size:.85rem;color:#475569;line-height:1.65;}

        .btn-yellow{display:inline-flex;align-items:center;gap:10px;background:var(--secondary-gradient);color:var(--primary-blue);border:none;border-radius:12px;padding:13px 28px;font-weight:800;font-size:.92rem;cursor:pointer;transition:all .3s;text-decoration:none;justify-content:center;}
        .btn-yellow:hover{transform:translateY(-2px);box-shadow:0 8px 22px rgba(253,185,19,.45);color:var(--primary-blue);}
        .btn-blue{display:inline-flex;align-items:center;gap:8px;background:var(--primary-gradient);color:white;border:none;border-radius:12px;padding:13px 28px;font-weight:700;font-size:.92rem;cursor:pointer;transition:all .3s;text-decoration:none;justify-content:center;}
        .btn-blue:hover{opacity:.9;transform:translateY(-2px);box-shadow:0 8px 22px rgba(44,62,143,.35);color:white;}

        .cta-strip{background:var(--primary-gradient);border-radius:16px;padding:22px 30px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;}
        .cta-strip .cta-text-main{color:white;font-weight:800;font-size:1rem;margin-bottom:3px;}
        .cta-strip .cta-text-sub{color:rgba(255,255,255,.75);font-size:.85rem;}

        .overview-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-top:8px;}
        .overview-tile{background:var(--primary-blue-light);border-radius:14px;padding:20px 18px;border:1px solid rgba(44,62,143,.1);text-align:center;}
        .overview-tile .ot-num{font-size:2rem;font-weight:900;color:var(--primary-blue);line-height:1;}
        .overview-tile .ot-label{font-size:.8rem;font-weight:700;color:#475569;margin-top:8px;line-height:1.4;}

        /* Wizard multi-column layouts */
        .wiz-2col{display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;}
        .wiz-3col{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;}
        .guide-steps-grid{display:grid;grid-template-columns:1fr;gap:0;}
        .resources-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
        .verify-grid{display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;}
        .panel-heading{font-weight:800;color:var(--primary-blue);font-size:1.1rem;margin-bottom:6px;}
        .panel-sub{font-size:.86rem;color:#64748b;margin-bottom:22px;line-height:1.6;max-width:none;}

        .pwd-req{padding:12px 16px;border-radius:12px;background:#f8fafc;border-left:4px solid #dee2e6;height:100%;}
        .pwd-req.approved{border-left-color:#28a745;background:#f0fff8;}
        .pwd-req.rejected{border-left-color:#dc3545;background:#fff5f5;}
        .pwd-req.in_review{border-left-color:#17a2b8;background:#f0faff;}
        .pwd-req.pending{border-left-color:#FDB913;background:#fffbea;}
        .pwd-req-name{font-weight:600;font-size:.85rem;color:#1e293b;}
        .pwd-thumb{width:36px;height:36px;object-fit:cover;border-radius:6px;cursor:pointer;vertical-align:middle;}
        .pwd-view{background:#2C3E8F;color:white;padding:3px 10px;border-radius:6px;font-size:.75rem;font-weight:600;text-decoration:none;border:none;cursor:pointer;}
        .pwd-view:hover{background:#1A2A5C;color:white;}
        .pwd-upload-box{background:#FFF3D6;border-radius:8px;padding:10px 12px;margin-top:8px;}
        .pwd-remark{font-size:.78rem;color:#dc3545;margin-top:5px;padding:5px 8px;background:#fff0f0;border-radius:6px;}

        .toast-notice{position:fixed;top:84px;right:18px;z-index:1081;max-width:420px;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border:1px solid rgba(255,255,255,.18);border-radius:12px;padding:12px 16px;box-shadow:0 10px 28px rgba(26,42,92,.35);font-size:.84rem;font-weight:700;}

        /* Monitor dashboard */
        .monitor-card{background:white;border-radius:20px;border:1px solid var(--border-light);box-shadow:0 8px 32px rgba(44,62,143,.08);overflow:hidden;margin-bottom:20px;}
        .monitor-header{background:var(--primary-gradient);color:white;padding:22px 32px 20px;}
        .monitor-header h2{font-size:1.35rem;font-weight:900;margin:0 0 4px;}
        .monitor-header p{margin:0;font-size:.86rem;opacity:.82;}
        .monitor-header-meta{margin:8px 0 0;font-size:.75rem;opacity:.75;}
        .monitor-body{padding:0;}
        .monitor-stats-bar{display:grid;grid-template-columns:repeat(4,1fr);gap:0;background:rgba(44,62,143,.04);border-bottom:1px solid var(--border-light);}
        .monitor-stat{padding:18px 20px;text-align:center;border-right:1px solid var(--border-light);}
        .monitor-stat:last-child{border-right:none;}
        .monitor-stat .sv{font-size:1.75rem;font-weight:900;color:var(--primary-blue);line-height:1;}
        .monitor-stat .sl{font-size:.68rem;color:#64748b;font-weight:700;text-transform:uppercase;margin-top:5px;letter-spacing:.05em;}
        .monitor-layout{display:grid;grid-template-columns:minmax(280px,340px) 1fr;gap:0;align-items:stretch;min-height:420px;}
        .monitor-aside{padding:24px 24px 28px;border-right:1px solid var(--border-light);background:#fafbfc;}
        .monitor-main{padding:24px 32px 32px;background:white;}
        .status-pill{display:inline-flex;align-items:center;gap:6px;border-radius:20px;padding:6px 16px;font-size:.76rem;font-weight:800;text-transform:uppercase;letter-spacing:.04em;white-space:nowrap;}
        .status-pill.pending{background:#FFF3D6;color:#856404;}
        .status-pill.approved{background:#dcfce7;color:#15803d;}
        .status-pill.rejected{background:#fee2e2;color:#b91c1c;}
        .status-pill.processing{background:#dbeafe;color:#1d4ed8;}
        .status-pill.ready{background:var(--secondary-yellow-light);color:#92400e;border:1px solid rgba(253,185,19,.4);}
        .timeline{display:flex;flex-direction:column;gap:0;margin:0 0 20px;}
        .tl-item{display:flex;gap:12px;position:relative;padding-bottom:16px;}
        .tl-item:last-child{padding-bottom:0;}
        .tl-item:not(:last-child)::before{content:'';position:absolute;left:14px;top:30px;bottom:0;width:2px;background:var(--border-light);}
        .tl-item.done:not(:last-child)::before{background:#86efac;}
        .tl-item.active:not(:last-child)::before{background:linear-gradient(to bottom,#86efac,var(--border-light));}
        .tl-dot{width:30px;height:30px;border-radius:50%;border:2px solid var(--border-light);background:white;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:800;color:#94a3b8;flex-shrink:0;z-index:1;}
        .tl-item.done .tl-dot{background:#dcfce7;border-color:#22c55e;color:#15803d;}
        .tl-item.active .tl-dot{background:var(--primary-gradient);border-color:var(--primary-blue);color:white;box-shadow:0 4px 12px rgba(44,62,143,.25);}
        .tl-content{flex:1;padding-top:3px;min-width:0;}
        .tl-title{font-weight:700;font-size:.84rem;color:var(--text-dark);line-height:1.3;}
        .tl-item.active .tl-title{color:var(--primary-blue);}
        .tl-desc{font-size:.74rem;color:#64748b;margin-top:2px;line-height:1.45;}
        .aside-label{font-size:.72rem;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;margin-bottom:12px;}
        .monitor-actions{display:flex;flex-direction:column;gap:8px;}
        .mon-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;border-radius:10px;padding:10px 16px;font-weight:700;font-size:.82rem;cursor:pointer;transition:all .25s;border:none;text-decoration:none;width:100%;}
        .mon-btn-outline{background:white;color:var(--primary-blue);border:2px solid var(--border-light);}
        .mon-btn-outline:hover{border-color:var(--primary-blue);background:var(--primary-blue-light);}
        .mon-btn-yellow{background:var(--secondary-gradient);color:var(--primary-blue);}
        .mon-btn-yellow:hover{transform:translateY(-1px);box-shadow:0 6px 16px rgba(253,185,19,.35);}
        .section-label{font-weight:800;color:var(--primary-blue);font-size:1rem;margin-bottom:4px;}
        .section-hint{font-size:.82rem;color:#64748b;margin-bottom:18px;}
        .monitor-progress-card{background:var(--primary-blue-light);border:1px solid rgba(44,62,143,.1);border-radius:12px;padding:14px 16px;margin-bottom:20px;}
        .monitor-progress-card .mp-label{font-size:.72rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin-bottom:8px;}
        .monitor-progress-card .mp-bar{height:8px;background:#dbe4ff;border-radius:4px;overflow:hidden;}
        .monitor-progress-card .mp-fill{height:100%;background:var(--secondary-yellow);border-radius:4px;}
        .monitor-progress-card .mp-text{font-size:.78rem;color:var(--primary-blue);font-weight:700;margin-top:6px;}
        .wiz-btn-finish{display:none;background:var(--secondary-gradient);color:var(--primary-blue);box-shadow:0 4px 14px rgba(253,185,19,.35);}
        .wiz-btn-finish:hover{transform:translateY(-1px);box-shadow:0 6px 18px rgba(253,185,19,.45);}

        .footer-strip{background:var(--primary-gradient);color:rgba(255,255,255,.85);text-align:center;padding:18px;font-size:.85rem;margin-top:auto;}
        .footer-strip strong{color:white;}

        @media(min-width:992px){
            .guide-steps-grid{grid-template-columns:1fr 1fr;column-gap:32px;}
            .guide-steps-grid .connector{display:none;}
        }
        @media(max-width:991px){
            .monitor-layout{grid-template-columns:1fr;}
            .monitor-aside{border-right:none;border-bottom:1px solid var(--border-light);}
            .monitor-stats-bar{grid-template-columns:repeat(2,1fr);}
            .monitor-stat:nth-child(2){border-right:none;}
            .monitor-stat:nth-child(1),.monitor-stat:nth-child(2){border-bottom:1px solid var(--border-light);}
            .monitor-actions{flex-direction:row;flex-wrap:wrap;}
            .mon-btn{width:auto;flex:1;min-width:140px;}
            .wiz-2col,.wiz-3col,.resources-grid,.verify-grid{grid-template-columns:1fr;}
            .overview-grid{grid-template-columns:repeat(2,1fr);}
        }
        @media(min-width:768px) and (max-width:991px){
            .overview-grid{grid-template-columns:repeat(3,1fr);}
        }
        @media(max-width:576px){
            .wizard-body{padding:20px 16px 16px;}
            .wizard-nav{padding:14px 16px 20px;flex-wrap:wrap;}
            .wizard-top,.wizard-steps{padding-left:16px;padding-right:16px;}
            .wiz-btn-prev,.wiz-btn-next,.wiz-btn-finish{flex:1;justify-content:center;}
            .hero-banner h1{font-size:1.55rem;}
            .monitor-header,.monitor-main,.monitor-aside{padding-left:16px;padding-right:16px;}
            .monitor-actions{flex-direction:column;}
            .mon-btn{width:100%;}
            .overview-grid{grid-template-columns:1fr;}
        }
    </style>
</head>
<body>

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
                        <button class="lang-btn" data-lang="tl" onclick="setLang('tl')">TL</button>
                    </div>
                    <a href="{{ route('user.dashboard') }}" class="back-btn">&#8592; <span data-en="Back to Programs" data-tl="Bumalik sa Programs">Back to Programs</span></a>
                </div>
            </div>
        </div>
    </div>

    <section class="hero-banner compact" id="hero-banner">
        <div class="container">
            <div class="hero-inner">
                <div class="hero-badge" data-en="PWD Services" data-tl="Mga Serbisyo para sa PWD">PWD Services</div>
                <h1 id="hero-title" data-en="PWD Application Wizard" data-tl="Wizard ng PWD Application">PWD Application Wizard</h1>
                <div class="hero-divider"></div>
                <p class="hero-sub" id="hero-sub" data-en="Follow each step below to apply for your PWD ID, submit requirements online, and verify your membership — all in one guided flow." data-tl="Sundin ang bawat hakbang sa ibaba para mag-apply ng PWD ID, magsumite ng mga kinakailangan online, at i-verify ang iyong membership — lahat sa isang gabay na daloy.">
                    Follow each step below to apply for your PWD ID, submit requirements online, and verify your membership — all in one guided flow.
                </p>
            </div>
        </div>
    </section>

    @php
        $topNotice = session('upload_success') ?: session('error');
        $totalR = count($pwdRequirements ?? []);
        $approvedR = ($uploadedFiles ?? collect())->where('status','approved')->count();
        $pendingR = ($uploadedFiles ?? collect())->where('status','pending')->count();
        $rejectedR = ($uploadedFiles ?? collect())->where('status','rejected')->count();
        $inReviewR = ($uploadedFiles ?? collect())->where('status','in_review')->count();
        $uploadedCount = ($uploadedFiles ?? collect())->count();
        $pctR = $totalR > 0 ? round(($approvedR / $totalR) * 100) : 0;
        $wizardSteps = !empty($isPwdBeneficiary)
            ? ['overview', 'guide', 'resources', 'verify']
            : ['overview', 'guide', 'resources', 'upload', 'verify'];

        $hasApplication = isset($application) && $application;

        if ($hasApplication) {
            $appStatus = $application->status ?? null;
            $idStatus = $application->id_status ?? 'not_started';
        } else {
            $appStatus = null;
            $idStatus = 'not_started';
        }

        $tlSubmitted = $hasApplication;
        $tlUploaded = $uploadedCount > 0;
        $tlReview = $tlUploaded && in_array($appStatus, ['pending', null]) && ($pendingR + $inReviewR) > 0;
        $tlApproved = $appStatus === 'approved';
        $tlProcessing = in_array($idStatus, ['processing']) || ($hasApplication && !empty($application->completed_at));
        $tlReady = in_array($idStatus, ['ready_for_pickup', 'claimed', 'released']) || ($hasApplication && !empty($application->id_ready_at));

        if ($tlReady) { $activeTimeline = 5; }
        elseif ($tlProcessing) { $activeTimeline = 4; }
        elseif ($tlApproved) { $activeTimeline = 3; }
        elseif ($tlReview || $tlUploaded) { $activeTimeline = 2; }
        elseif ($tlSubmitted) { $activeTimeline = 1; }
        else { $activeTimeline = 0; }

        $overallLabel = match(true) {
            !empty($isPwdBeneficiary) => 'PWD Beneficiary',
            $tlReady => 'ID Ready for Pickup',
            $tlProcessing => 'ID Processing',
            $appStatus === 'approved' => 'Requirements Approved',
            $appStatus === 'rejected' => 'Application Rejected',
            $rejectedR > 0 => 'Action Required',
            $uploadedCount > 0 => 'Under Review',
            $hasApplication => 'Awaiting Documents',
            default => 'Not Started',
        };
        $overallPillClass = match(true) {
            !empty($isPwdBeneficiary) => 'approved',
            $tlReady => 'ready',
            $tlProcessing => 'processing',
            $appStatus === 'approved' => 'approved',
            $appStatus === 'rejected' || $rejectedR > 0 => 'rejected',
            $uploadedCount > 0 => 'pending',
            default => 'pending',
        };
    @endphp

    @if(!empty($isPwdBeneficiary))
    <div class="toast-notice" style="top:84px;">
        PWD beneficiary na ang account na ito. Re-application is disabled.
    </div>
    @endif

    @if($topNotice)
    <div class="toast-notice" style="top:{{ !empty($isPwdBeneficiary) ? '150px' : '84px' }};">
        {{ $topNotice }}
    </div>
    @endif

    <div class="flex-grow-1 py-3 pb-4">
        <div class="container-fluid px-3 px-lg-4">

                {{-- ═══ MONITOR VIEW (shown after wizard or when application exists) ═══ --}}
                <div id="pwd-monitor-view" style="display:none;">
                    <div class="monitor-card">
                        <div class="monitor-header">
                            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap;">
                                <div>
                                    <h2 data-en="Your PWD Application" data-tl="Iyong PWD Application">Your PWD Application</h2>
                                    <p data-en="Track your application progress, upload documents, and stay updated." data-tl="Subaybayan ang progress ng iyong aplikasyon, mag-upload ng dokumento, at manatiling updated.">Track your application progress, upload documents, and stay updated.</p>
                                </div>
                                <span class="status-pill {{ $overallPillClass }}">{{ $overallLabel }}</span>
                            </div>
                            @if($hasApplication && $application->application_date)
                            <p class="monitor-header-meta">
                                Applied: {{ \Carbon\Carbon::parse($application->application_date)->format('M j, Y') }}
                                @if($application->municipality) · {{ $application->municipality }} @endif
                            </p>
                            @endif
                        </div>

                        @if(!empty($isPwdBeneficiary))
                        <div class="monitor-body" style="padding:24px 32px;">
                            <div class="info-card mb-0">
                                <div class="ic-title">PWD Beneficiary Status</div>
                                <div class="ic-body">Naka-register ka na bilang PWD beneficiary. Re-application is disabled. Para sa updates o concerns, bumisita sa MSWDO office.</div>
                            </div>
                        </div>
                        @else

                        <div class="monitor-stats-bar">
                            <div class="monitor-stat"><div class="sv">{{ $approvedR }}</div><div class="sl" data-en="Approved" data-tl="Approved">Approved</div></div>
                            <div class="monitor-stat"><div class="sv">{{ $pendingR + $inReviewR }}</div><div class="sl" data-en="In Review" data-tl="In Review">In Review</div></div>
                            <div class="monitor-stat"><div class="sv">{{ $rejectedR }}</div><div class="sl" data-en="Rejected" data-tl="Rejected">Rejected</div></div>
                            <div class="monitor-stat"><div class="sv">{{ max(0, $totalR - $uploadedCount) }}</div><div class="sl" data-en="Missing" data-tl="Kulang">Missing</div></div>
                        </div>

                        <div class="monitor-body">
                            <div class="monitor-layout">
                                <aside class="monitor-aside">
                                    <div class="monitor-progress-card">
                                        <div class="mp-label" data-en="Overall Progress" data-tl="Kabuuang Progress">Overall Progress</div>
                                        <div class="mp-bar"><div class="mp-fill" style="width:{{ $pctR }}%;"></div></div>
                                        <div class="mp-text">{{ $approvedR }}/{{ $totalR }} approved — {{ $pctR }}%</div>
                                    </div>

                                    <div class="aside-label" data-en="Application Timeline" data-tl="Timeline ng Aplikasyon">Application Timeline</div>
                                    <div class="timeline">
                                        @php
                                            $timelineItems = [
                                                ['done' => $tlSubmitted, 'active' => $activeTimeline === 1, 'en' => 'Application Started', 'tl' => 'Nagsimula ang Aplikasyon', 'descEn' => 'Registered in the system.', 'descTl' => 'Narehistro na sa system.'],
                                                ['done' => $tlUploaded, 'active' => $activeTimeline === 2, 'en' => 'Documents Submitted', 'tl' => 'Na-submit ang mga Dokumento', 'descEn' => 'Documents uploaded for review.', 'descTl' => 'Na-upload na ang mga dokumento.'],
                                                ['done' => $tlApproved, 'active' => $activeTimeline === 3, 'en' => 'Requirements Approved', 'tl' => 'Naaprubahan ang Requirements', 'descEn' => 'Admin approved your requirements.', 'descTl' => 'Naaprubahan na ng admin.'],
                                                ['done' => $tlProcessing, 'active' => $activeTimeline === 4, 'en' => 'PWD ID Processing', 'tl' => 'Pinoproseso ang PWD ID', 'descEn' => 'Physical ID being processed.', 'descTl' => 'Pinoproseso ang pisikal na ID.'],
                                                ['done' => $tlReady, 'active' => $activeTimeline === 5, 'en' => 'Ready for Pickup', 'tl' => 'Handa nang Kunin', 'descEn' => 'Visit MSWDO to claim your ID.', 'descTl' => 'Bumisita sa MSWDO para kunin.'],
                                            ];
                                        @endphp
                                        @foreach($timelineItems as $i => $tl)
                                        <div class="tl-item {{ $tl['done'] ? 'done' : '' }} {{ $tl['active'] ? 'active' : '' }}">
                                            <div class="tl-dot">{{ $tl['done'] ? '✓' : ($i + 1) }}</div>
                                            <div class="tl-content">
                                                <div class="tl-title" data-en="{{ $tl['en'] }}" data-tl="{{ $tl['tl'] }}">{{ $tl['en'] }}</div>
                                                <div class="tl-desc" data-en="{{ $tl['descEn'] }}" data-tl="{{ $tl['descTl'] }}">{{ $tl['descEn'] }}</div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    @if($appStatus === 'rejected' && ($application->admin_remarks ?? null))
                                    <div class="info-card yellow mb-3" style="margin-bottom:16px!important;">
                                        <div class="ic-title" data-en="Admin Remarks" data-tl="Mga Paalala ng Admin">Admin Remarks</div>
                                        <div class="ic-body" style="font-size:.82rem;">{{ $application->admin_remarks }}</div>
                                    </div>
                                    @endif

                                    <div class="aside-label" data-en="Quick Actions" data-tl="Mabilis na Aksyon">Quick Actions</div>
                                    <div class="monitor-actions">
                                        <button type="button" class="mon-btn mon-btn-outline" onclick="showWizardView()">
                                            <span data-en="View Application Guide" data-tl="Tingnan ang Gabay">View Application Guide</span>
                                        </button>
                                        <a href="https://pwd.doh.gov.ph/tbl_pwd_id_verificationlist.php" target="_blank" class="mon-btn mon-btn-outline">
                                            <span data-en="Open PWD Verifier" data-tl="Buksan ang PWD Verifier">Open PWD Verifier</span>
                                        </a>
                                        <a href="https://pwd.doh.gov.ph/downloads/PRPWD-APPLICATION_FORM.pdf" target="_blank" class="mon-btn mon-btn-yellow">
                                            <span data-en="Download PRPWD Form" data-tl="I-download ang PRPWD Form">Download PRPWD Form</span>
                                        </a>
                                    </div>
                                </aside>

                                <div class="monitor-main">
                                    <div class="section-label" data-en="Your Uploaded Requirements" data-tl="Iyong mga Na-upload na Requirements">Your Uploaded Requirements</div>
                                    <div class="section-hint" data-en="Upload or replace documents below. The admin will review each submission." data-tl="Mag-upload o magpalit ng mga dokumento sa ibaba. Rerepasuhin ng admin ang bawat submission.">Upload or replace documents below. The admin will review each submission.</div>

                                    @include('user.partials.pwd-upload-requirements', ['uploadPrefix' => 'mon', 'uploadCols' => 'col-md-6 col-lg-6 col-xl-3', 'hideProgress' => true])
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="text-center py-2">
                        <a href="{{ route('user.dashboard') }}" class="back-btn d-inline-flex" style="font-size:.92rem;padding:12px 28px;background:var(--primary-gradient);">
                            <span data-en="Return to Dashboard" data-tl="Bumalik sa Dashboard">Return to Dashboard</span>
                        </a>
                    </div>
                </div>

                {{-- ═══ WIZARD VIEW ═══ --}}
                <div id="pwd-wizard-view">
                <div class="wizard-card">
                    <div class="wizard-top">
                        <h2 id="wizard-step-title" data-en="Step 1 — Overview" data-tl="Hakbang 1 — Pangkalahatang-ideya">Step 1 — Overview</h2>
                        <p id="wizard-step-desc" data-en="Learn what you need and how this wizard works." data-tl="Alamin ang mga kailangan mo at kung paano gumagana ang wizard na ito.">Learn what you need and how this wizard works.</p>
                        <div class="wizard-progress-track">
                            <div class="wizard-progress-fill" id="wizard-progress-fill"></div>
                        </div>
                    </div>

                    <div class="wizard-steps" id="wizard-steps-nav" role="tablist">
                        <button type="button" class="wiz-step active" data-step="0" data-en="Overview" data-tl="Pangkalahatan">
                            <span class="wiz-step-circle">1</span>
                            <span class="wiz-step-label" data-en="Overview" data-tl="Pangkalahatan">Overview</span>
                        </button>
                        <button type="button" class="wiz-step" data-step="1" data-en="How to Apply" data-tl="Paano Mag-apply">
                            <span class="wiz-step-circle">2</span>
                            <span class="wiz-step-label" data-en="How to Apply" data-tl="Paano Mag-apply">How to Apply</span>
                        </button>
                        <button type="button" class="wiz-step" data-step="2" data-en="Form & Office" data-tl="Form at Opisina">
                            <span class="wiz-step-circle">3</span>
                            <span class="wiz-step-label" data-en="Form & Office" data-tl="Form at Opisina">Form & Office</span>
                        </button>
                        @if(empty($isPwdBeneficiary))
                        <button type="button" class="wiz-step" data-step="3" data-en="Upload" data-tl="Mag-upload">
                            <span class="wiz-step-circle">4</span>
                            <span class="wiz-step-label" data-en="Upload Docs" data-tl="Mag-upload">Upload Docs</span>
                        </button>
                        <button type="button" class="wiz-step" data-step="4" data-en="Verify" data-tl="I-verify">
                            <span class="wiz-step-circle">5</span>
                            <span class="wiz-step-label" data-en="Verify" data-tl="I-verify">Verify</span>
                        </button>
                        @else
                        <button type="button" class="wiz-step" data-step="3" data-en="Verify" data-tl="I-verify">
                            <span class="wiz-step-circle">4</span>
                            <span class="wiz-step-label" data-en="Verify" data-tl="I-verify">Verify</span>
                        </button>
                        @endif
                    </div>

                    <div class="wizard-body">

                        {{-- STEP 1: OVERVIEW --}}
                        <div class="wizard-panel active" data-panel="overview">
                            <div class="panel-heading" data-en="Welcome to the PWD Application Process" data-tl="Maligayang Pagdating sa Proseso ng PWD Application">Welcome to the PWD Application Process</div>
                            <div class="panel-sub" data-en="This wizard walks you through everything — from downloading the official form to uploading your documents and verifying your PWD membership." data-tl="Gagabayan ka ng wizard na ito sa lahat — mula sa pag-download ng opisyal na form hanggang sa pag-upload ng mga dokumento at pag-verify ng iyong PWD membership.">
                                This wizard walks you through everything — from downloading the official form to uploading your documents and verifying your PWD membership.
                            </div>

                            <div class="overview-grid mb-4">
                                <div class="overview-tile">
                                    <div class="ot-num">5</div>
                                    <div class="ot-label" data-en="Official steps to get your PWD ID" data-tl="Opisyal na hakbang para makuha ang PWD ID">Official steps to get your PWD ID</div>
                                </div>
                                <div class="overview-tile">
                                    <div class="ot-num">4</div>
                                    <div class="ot-label" data-en="Documents you can upload online" data-tl="Mga dokumentong maaaring i-upload online">Documents you can upload online</div>
                                </div>
                                <div class="overview-tile">
                                    <div class="ot-num">1</div>
                                    <div class="ot-label" data-en="Official DOH verification portal" data-tl="Opisyal na DOH verification portal">Official DOH verification portal</div>
                                </div>
                            </div>

                            <div class="wiz-2col">
                                <div class="info-card yellow mb-0">
                                    <div class="ic-title" data-en="What you'll need" data-tl="Mga kailangan mo">What you'll need</div>
                                    <div class="ic-body">
                                        <ul style="margin:0;padding-left:18px;line-height:2;">
                                            <li data-en="Completed PRPWD Application Form" data-tl="Nakumpletong PRPWD Application Form">Completed PRPWD Application Form</li>
                                            <li data-en="Certificate of Disability (original + 1 photocopy)" data-tl="Sertipiko ng Kapansanan (orihinal + 1 photocopy)">Certificate of Disability (original + 1 photocopy)</li>
                                            <li data-en="Two (2) recent 1×1 ID pictures" data-tl="Dalawang (2) bagong 1×1 ID na larawan">Two (2) recent 1×1 ID pictures</li>
                                            <li data-en="Valid government-issued ID" data-tl="Valid na ID na inilabas ng gobyerno">Valid government-issued ID</li>
                                        </ul>
                                    </div>
                                </div>

                                <div>
                                    @if(!empty($isPwdBeneficiary))
                                    <div class="info-card mb-0">
                                        <div class="ic-title">PWD Beneficiary Status</div>
                                        <div class="ic-body">Naka-register ka na bilang PWD beneficiary. Maaari mo pa ring basahin ang gabay, ngunit naka-disable ang re-application at upload.</div>
                                    </div>
                                    @elseif(isset($application) && $application)
                                    <div class="info-card mb-0">
                                        <div class="ic-title" data-en="Your upload progress" data-tl="Progress ng iyong upload">Your upload progress</div>
                                        <div class="ic-body">{{ $approvedR }}/{{ $totalR }} approved — {{ $pctR }}% complete</div>
                                        <div style="height:8px;background:#dbe4ff;border-radius:4px;margin-top:10px;overflow:hidden;">
                                            <div style="width:{{ $pctR }}%;height:100%;background:var(--primary-blue);border-radius:4px;"></div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="info-card mb-0">
                                        <div class="ic-title" data-en="Getting Started" data-tl="Pagsisimula">Getting Started</div>
                                        <div class="ic-body" data-en="Use the Next button below to begin. You can upload documents and track your application progress at any time." data-tl="Gamitin ang Next button sa ibaba para magsimula. Maaari kang mag-upload ng dokumento at subaybayan ang progress anumang oras.">Use the Next button below to begin. You can upload documents and track your application progress at any time.</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- STEP 2: HOW TO APPLY --}}
                        <div class="wizard-panel" data-panel="guide">
                            <div class="panel-heading" data-en="How to Avail a PWD ID" data-tl="Paano Makuha ang PWD ID">How to Avail a PWD ID</div>
                            <div class="panel-sub" data-en="Follow these 5 official steps to complete your PWD ID application." data-tl="Sundin ang 5 opisyal na hakbang na ito para makumpleto ang iyong PWD ID application.">Follow these 5 official steps to complete your PWD ID application.</div>

                            <div class="guide-steps-grid">
                            <div class="step-item">
                                <div class="step-num">1</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Visit the Official PWD Website" data-tl="Bisitahin ang Opisyal na PWD Website">Visit the Official PWD Website</div>
                                    <div class="step-desc" data-en="Go to the DOH PWD portal to access all official forms and information." data-tl="Pumunta sa DOH PWD portal para ma-access ang lahat ng opisyal na forms at impormasyon.">Go to the DOH PWD portal to access all official forms and information.</div>
                                    <a href="https://pwd.doh.gov.ph" target="_blank" class="step-link mt-2 d-block">https://pwd.doh.gov.ph</a>
                                </div>
                            </div>
                            <div class="connector"></div>
                            <div class="step-item">
                                <div class="step-num">2</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Download the Application Form" data-tl="I-download ang Application Form">Download the Application Form</div>
                                    <div class="step-desc" data-en="Go to the Downloads section and download the official PRPWD Application Form. You may fill it out digitally or print it." data-tl="Pumunta sa Downloads section at i-download ang opisyal na PRPWD Application Form. Maaari mo itong punan nang digital o i-print.">Go to the Downloads section and download the official PRPWD Application Form. You may fill it out digitally or print it.</div>
                                </div>
                            </div>
                            <div class="connector"></div>
                            <div class="step-item">
                                <div class="step-num">3</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Fill Out & Submit with Certificate of Disability" data-tl="Punan at Isumite kasama ang Sertipiko ng Kapansanan">Fill Out & Submit with Certificate of Disability</div>
                                    <div class="step-desc" data-en="Complete all required fields, then upload the completed form along with your Certificate of Disability to the official website." data-tl="Kumpletuhin ang lahat ng kinakailangang fields, pagkatapos ay i-upload ang nakumpletong form kasama ang iyong Sertipiko ng Kapansanan sa opisyal na website.">Complete all required fields, then upload the completed form along with your Certificate of Disability to the official website.</div>
                                    <div class="step-note">
                                        <strong>Note:</strong>
                                        <span data-en="You may also personally submit your documents at the MSWDO Office (Municipal Hall, Ground Floor) for faster processing." data-tl="Maaari ka ring personal na isumite ang iyong mga dokumento sa opisina ng MSWDO (Municipal Hall, Ground Floor) para mas mabilis na maproseso.">You may also personally submit your documents at the MSWDO Office (Municipal Hall, Ground Floor) for faster processing.</span>
                                    </div>
                                </div>
                            </div>
                            <div class="connector"></div>
                            <div class="step-item">
                                <div class="step-num">4</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Submit Two (2) 1×1 ID Pictures to MSWDO" data-tl="Isumite ang Dalawang (2) 1×1 ID na Larawan sa MSWDO">Submit Two (2) 1×1 ID Pictures to MSWDO</div>
                                    <div class="step-desc" data-en="Once your application is approved online, bring two (2) recent 1×1 ID pictures to the MSWDO office." data-tl="Kapag naaprubahan na ang iyong aplikasyon online, magdala ng dalawang (2) bagong 1×1 ID na larawan sa opisina ng MSWDO.">Once your application is approved online, bring two (2) recent 1×1 ID pictures to the MSWDO office.</div>
                                </div>
                            </div>
                            <div class="connector"></div>
                            <div class="step-item">
                                <div class="step-num">5</div>
                                <div class="step-content">
                                    <div class="step-title" data-en="Wait for Your Physical PWD ID" data-tl="Hintayin ang Iyong Pisikal na PWD ID">Wait for Your Physical PWD ID</div>
                                    <div class="step-desc" data-en="The MSWDO will process your physical PWD ID. You will be notified once it is ready for release." data-tl="Ipoproseso ng MSWDO ang iyong pisikal na PWD ID. Maabisuhan ka kapag handa na itong makuha.">The MSWDO will process your physical PWD ID. You will be notified once it is ready for release.</div>
                                </div>
                            </div>
                            </div>
                        </div>

                        {{-- STEP 3: FORM & OFFICE --}}
                        <div class="wizard-panel" data-panel="resources">
                            <div class="panel-heading" data-en="Download Form & MSWDO Office" data-tl="I-download ang Form at Opisina ng MSWDO">Download Form & MSWDO Office</div>
                            <div class="panel-sub" data-en="Get the official PRPWD form and know where to submit your requirements in person." data-tl="Kunin ang opisyal na PRPWD form at alamin kung saan isusumite ang mga kinakailangan nang personal.">Get the official PRPWD form and know where to submit your requirements in person.</div>

                            <div class="info-card mb-4">
                                <div class="ic-title" data-en="What you will download" data-tl="Ano ang iyong ma-download">What you will download</div>
                                <div class="ic-body" data-en="The official Persons with Disability (PWD) ID application form (PRPWD form) — recognized by all LGUs in the Philippines." data-tl="Ang opisyal na Application Form para sa Persons with Disability (PWD) ID (PRPWD form) — kinikilala ng lahat ng LGU sa Pilipinas.">The official Persons with Disability (PWD) ID application form (PRPWD form) — recognized by all LGUs in the Philippines.</div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6 col-lg-4">
                                    <a href="https://pwd.doh.gov.ph/downloads/PRPWD-APPLICATION_FORM.pdf" target="_blank" class="btn-yellow w-100">
                                        <span data-en="Download PRPWD Form (PDF)" data-tl="I-download ang PRPWD Form (PDF)">Download PRPWD Form (PDF)</span>
                                    </a>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <a href="https://pwd.doh.gov.ph" target="_blank" class="btn-blue w-100">
                                        <span data-en="Visit Official PWD Portal" data-tl="Bisitahin ang Opisyal na PWD Portal">Visit Official PWD Portal</span>
                                    </a>
                                </div>
                            </div>

                            <div class="resources-grid">
                            <div>
                            <div class="info-card">
                                <div class="ic-title" data-en="MSWDO Office Location" data-tl="Lokasyon ng Opisina ng MSWDO">MSWDO Office Location</div>
                                <div class="ic-body" data-en="Municipal Social Welfare and Development Office, Municipal Hall, Ground Floor" data-tl="Municipal Social Welfare and Development Office, Municipal Hall, Ground Floor">Municipal Social Welfare and Development Office, Municipal Hall, Ground Floor</div>
                            </div>
                            <div class="info-card">
                                <div class="ic-title" data-en="Office Hours" data-tl="Oras ng Opisina">Office Hours</div>
                                <div class="ic-body" data-en="Monday – Friday, 8:00 AM – 5:00 PM (Closed on Holidays)" data-tl="Lunes – Biyernes, 8:00 AM – 5:00 PM (Sarado sa mga Pista Opisyal)">Monday – Friday, 8:00 AM – 5:00 PM <small style="color:#94a3b8;">(Closed on Holidays)</small></div>
                            </div>
                            </div>
                            <div class="info-card yellow mb-0">
                                <div class="ic-title" data-en="What to Bring" data-tl="Mga Dapat Dalhin">What to Bring</div>
                                <div class="ic-body">
                                    <ul style="margin:0;padding-left:18px;line-height:2;">
                                        <li data-en="Completed PRPWD Application Form" data-tl="Nakumpletong PRPWD Application Form">Completed PRPWD Application Form</li>
                                        <li data-en="Certificate of Disability (original + 1 photocopy)" data-tl="Sertipiko ng Kapansanan (orihinal + 1 photocopy)">Certificate of Disability (original + 1 photocopy)</li>
                                        <li data-en="Two (2) recent 1×1 ID pictures" data-tl="Dalawang (2) bagong 1×1 ID na larawan">Two (2) recent 1×1 ID pictures</li>
                                        <li data-en="Valid government-issued ID" data-tl="Valid na ID na inilabas ng gobyerno">Valid government-issued ID</li>
                                    </ul>
                                </div>
                            </div>
                            </div>
                        </div>

                        @if(empty($isPwdBeneficiary))
                        {{-- STEP 4: UPLOAD --}}
                        <div class="wizard-panel" data-panel="upload">
                            <div class="panel-heading">Submit Your PWD Requirements Online</div>
                            <div class="panel-sub">Upload digital copies of your documents. The admin will review each one.</div>
                            @include('user.partials.pwd-upload-requirements', ['uploadPrefix' => 'wiz', 'uploadCols' => 'col-md-6 col-lg-6 col-xl-3'])
                        </div>
                        @endif

                        {{-- STEP 5 (or 4 for beneficiaries): VERIFY --}}
                        <div class="wizard-panel" data-panel="verify">
                            <div class="panel-heading" data-en="Verify PWD Membership" data-tl="I-verify ang PWD Membership">Verify PWD Membership</div>
                            <div class="panel-sub" data-en="Check your PWD registration status using the official DOH verification portal." data-tl="Suriin ang iyong katayuan ng PWD registration gamit ang opisyal na DOH verification portal.">Check your PWD registration status using the official DOH verification portal.</div>

                            <div class="verify-grid">
                            <div>
                                <div class="step-item">
                                    <div class="step-num">1</div>
                                    <div class="step-content">
                                        <div class="step-title" data-en="Visit the Verification Page" data-tl="Bisitahin ang Verification Page">Visit the Verification Page</div>
                                        <div class="step-desc" data-en="Open the official PWD ID verification portal using the button below." data-tl="Buksan ang opisyal na PWD ID verification portal gamit ang button sa ibaba.">Open the official PWD ID verification portal using the button below.</div>
                                        <a href="https://pwd.doh.gov.ph/tbl_pwd_id_verificationlist.php" target="_blank" class="step-link mt-2 d-block">pwd.doh.gov.ph/tbl_pwd_id_verificationlist.php</a>
                                    </div>
                                </div>
                                <div class="connector"></div>
                                <div class="step-item">
                                    <div class="step-num">2</div>
                                    <div class="step-content">
                                        <div class="step-title" data-en="Locate the Verification Section" data-tl="Hanapin ang Verification Section">Locate the Verification Section</div>
                                        <div class="step-desc" data-en="On the verification page, find the search box where you can enter your PWD details." data-tl="Sa verification page, hanapin ang search box kung saan maaari kang maglagay ng iyong PWD details.">On the verification page, find the search box where you can enter your PWD details.</div>
                                    </div>
                                </div>
                                <div class="connector"></div>
                                <div class="step-item">
                                    <div class="step-num">3</div>
                                    <div class="step-content">
                                        <div class="step-title" data-en="Enter PWD ID Number or Full Name" data-tl="Ilagay ang PWD ID Number o Buong Pangalan">Enter PWD ID Number or Full Name</div>
                                        <div class="step-desc" data-en="Type your 16-digit PWD ID number (XXXX-XXXX-XXXX-XXXX) or your full registered name." data-tl="I-type ang iyong 16-digit na PWD ID number (XXXX-XXXX-XXXX-XXXX) o ang iyong buong nakarehistrong pangalan.">Type your 16-digit PWD ID number (<strong>XXXX-XXXX-XXXX-XXXX</strong>) or your full registered name.</div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="info-card mb-3">
                                    <div class="ic-title" data-en="Search by PWD ID Number" data-tl="Maghanap gamit ang PWD ID Number">Search by PWD ID Number</div>
                                    <div class="ic-body" data-en="Enter your complete 16-digit PWD ID number." data-tl="Ilagay ang inyong kumpletong 16-digit na PWD ID number.">Enter your complete 16-digit PWD ID number.</div>
                                </div>
                                <div class="info-card mb-3">
                                    <div class="ic-title" data-en="Search by Full Name" data-tl="Maghanap gamit ang Buong Pangalan">Search by Full Name</div>
                                    <div class="ic-body" data-en="Enter the full registered name exactly as written on the application form." data-tl="Ilagay ang buong nakarehistrong pangalan nang eksakto tulad ng nakasulat sa application form.">Enter the full registered name exactly as written on the application form.</div>
                                </div>
                                <div class="info-card yellow mb-3">
                                        <div class="ic-title" data-en="Privacy & Security" data-tl="Privacy at Seguridad">Privacy & Security</div>
                                        <div class="ic-body" data-en="The verification portal is hosted by the Department of Health (DOH). MSWDO does not receive or store any information you enter there." data-tl="Ang verification portal ay ino-host ng Department of Health (DOH). Hindi tinatanggap o iniimbak ng MSWDO ang anumang impormasyon na iyong inilalagay doon.">The verification portal is hosted by the Department of Health (DOH). MSWDO does not receive or store any information you enter there.</div>
                                    </div>

                                <div class="cta-strip">
                                    <div>
                                        <div class="cta-text-main" data-en="Ready to verify your PWD membership?" data-tl="Handa na bang i-verify ang iyong PWD membership?">Ready to verify your PWD membership?</div>
                                        <div class="cta-text-sub" data-en="Opens the official DOH portal in a new tab." data-tl="Magbubukas ng opisyal na DOH portal sa bagong tab.">Opens the official DOH portal in a new tab.</div>
                                    </div>
                                    <a href="https://pwd.doh.gov.ph/tbl_pwd_id_verificationlist.php" target="_blank"
                                       style="background:var(--secondary-yellow);color:var(--primary-blue);border-radius:12px;padding:13px 30px;font-weight:800;font-size:.92rem;text-decoration:none;display:inline-flex;align-items:center;gap:8px;white-space:nowrap;">
                                        <span data-en="Open Verifier" data-tl="Buksan ang Verifier">Open Verifier</span>
                                    </a>
                                </div>
                            </div>
                            </div>
                        </div>

                    </div>

                    <div class="wizard-nav">
                        <button type="button" class="wiz-btn-prev" id="wiz-btn-prev" disabled onclick="wizardPrev()">
                            &#8592; <span data-en="Previous" data-tl="Nakaraan">Previous</span>
                        </button>
                        <span class="wiz-step-counter" id="wiz-step-counter">Step 1 of {{ count($wizardSteps) }}</span>
                        <button type="button" class="wiz-btn-next" id="wiz-btn-next" onclick="wizardNext()">
                            <span data-en="Next" data-tl="Susunod">Next</span> &#8594;
                        </button>
                        <button type="button" class="wiz-btn-next wiz-btn-finish" id="wiz-btn-finish" onclick="finishWizard()">
                            <span data-en="Finish & Track Application" data-tl="Tapusin at Subaybayan">Finish & Track Application</span> ✓
                        </button>
                    </div>
                </div>

                <div class="text-center py-2" id="wizard-bottom-nav">
                    <a href="{{ route('user.dashboard') }}" class="back-btn d-inline-flex" style="font-size:.92rem;padding:12px 28px;background:var(--primary-gradient);">
                        <span data-en="Return to Dashboard" data-tl="Bumalik sa Dashboard">Return to Dashboard</span>
                    </a>
                </div>
                </div>{{-- /pwd-wizard-view --}}

            </div>
        </div>
    </div>

    <div class="footer-strip">
        <strong>MSWDO</strong> &mdash; Municipal Social Welfare &amp; Development Office &copy; {{ date('Y') }}
    </div>

    <div class="modal fade" id="fileViewerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:20px;overflow:hidden;border:none;">
                <div class="modal-header" style="background:var(--primary-gradient);color:white;border:none;padding:20px 24px;">
                    <h5 class="modal-title" id="fileViewerModalLabel" style="font-weight:800;font-size:1.2rem;">Document Viewer</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:24px;">
                    <div id="fileViewerContainer" style="text-align:center;background:var(--bg-light);border-radius:12px;padding:24px;min-height:400px;display:flex;align-items:center;justify-content:center;">
                        <div class="text-muted">Loading document...</div>
                    </div>
                    <div id="fileInfo" style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border-light);"></div>
                </div>
                <div class="modal-footer" style="background:var(--bg-light);border:none;padding:16px 24px;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:8px;font-weight:600;">Close</button>
                    <a href="#" id="downloadFileBtn" class="btn btn-primary" download style="background:var(--primary-blue);border:none;border-radius:8px;font-weight:600;">Download File</a>
                </div>
            </div>
        </div>
    </div>

    @include('components.chat-modal')
    @include('components.chatbot-widget')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const WIZARD_STEPS = @json($wizardSteps);
        const HAS_APPLICATION = @json($hasApplication);
        const IS_BENEFICIARY = @json(!empty($isPwdBeneficiary));
        const UPLOAD_NOTICE = @json((bool) session('upload_success'));
        const WIZARD_META = {
            overview: {
                titleEn: 'Step 1 — Overview',
                titleTl: 'Hakbang 1 — Pangkalahatang-ideya',
                descEn: 'Learn what you need and how this wizard works.',
                descTl: 'Alamin ang mga kailangan mo at kung paano gumagana ang wizard na ito.'
            },
            guide: {
                titleEn: 'Step 2 — How to Apply',
                titleTl: 'Hakbang 2 — Paano Mag-apply',
                descEn: 'Follow the official 5-step PWD ID application process.',
                descTl: 'Sundin ang opisyal na 5-hakbang na proseso ng PWD ID application.'
            },
            resources: {
                titleEn: 'Step 3 — Form & Office',
                titleTl: 'Hakbang 3 — Form at Opisina',
                descEn: 'Download the PRPWD form and find the MSWDO office details.',
                descTl: 'I-download ang PRPWD form at hanapin ang detalye ng opisina ng MSWDO.'
            },
            upload: {
                titleEn: 'Step 4 — Upload Documents',
                titleTl: 'Hakbang 4 — Mag-upload ng Dokumento',
                descEn: 'Submit your PWD requirements online for admin review.',
                descTl: 'Isumite ang iyong mga kinakailangan sa PWD online para sa review ng admin.'
            },
            verify: {
                titleEn: WIZARD_STEPS.length === 4 ? 'Step 4 — Verify Membership' : 'Step 5 — Verify Membership',
                titleTl: WIZARD_STEPS.length === 4 ? 'Hakbang 4 — I-verify ang Membership' : 'Hakbang 5 — I-verify ang Membership',
                descEn: 'Check your PWD registration using the official DOH portal.',
                descTl: 'Suriin ang iyong PWD registration gamit ang opisyal na DOH portal.'
            }
        };

        let currentStep = 0;
        let currentLang = 'en';
        let fileViewerModal;

        function goToStep(index, save = true) {
            if (index < 0 || index >= WIZARD_STEPS.length) return;
            currentStep = index;
            const key = WIZARD_STEPS[index];
            const meta = WIZARD_META[key];

            document.querySelectorAll('.wizard-panel').forEach(p => {
                p.classList.toggle('active', p.dataset.panel === key);
            });

            document.querySelectorAll('.wiz-step').forEach((btn, i) => {
                btn.classList.remove('active', 'done');
                if (i < index) btn.classList.add('done');
                if (i === index) btn.classList.add('active');
                const circle = btn.querySelector('.wiz-step-circle');
                circle.textContent = i < index ? '✓' : (i + 1);
            });

            const titleEl = document.getElementById('wizard-step-title');
            const descEl = document.getElementById('wizard-step-desc');
            titleEl.dataset.en = meta.titleEn;
            titleEl.dataset.tl = meta.titleTl;
            descEl.dataset.en = meta.descEn;
            descEl.dataset.tl = meta.descTl;
            titleEl.textContent = currentLang === 'tl' ? meta.titleTl : meta.titleEn;
            descEl.textContent = currentLang === 'tl' ? meta.descTl : meta.descEn;

            const pct = ((index + 1) / WIZARD_STEPS.length) * 100;
            document.getElementById('wizard-progress-fill').style.width = pct + '%';

            document.getElementById('wiz-btn-prev').disabled = index === 0;
            const nextBtn = document.getElementById('wiz-btn-next');
            const finishBtn = document.getElementById('wiz-btn-finish');
            const isLast = index === WIZARD_STEPS.length - 1;
            nextBtn.style.display = isLast ? 'none' : 'inline-flex';
            finishBtn.style.display = isLast ? 'inline-flex' : 'none';

            const counterTpl = currentLang === 'tl'
                ? `Hakbang ${index + 1} ng ${WIZARD_STEPS.length}`
                : `Step ${index + 1} of ${WIZARD_STEPS.length}`;
            document.getElementById('wiz-step-counter').textContent = counterTpl;

            if (save) sessionStorage.setItem('pwdWizardStep', String(index));
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function wizardNext() { goToStep(currentStep + 1); }
        function wizardPrev() { goToStep(currentStep - 1); }

        function shouldShowMonitor() {
            if (IS_BENEFICIARY) return true;
            if (sessionStorage.getItem('pwdWizardComplete') === '1') return true;
            if (HAS_APPLICATION) return true;
            if (UPLOAD_NOTICE) return true;
            return false;
        }

        function showMonitorView() {
            document.getElementById('pwd-wizard-view').style.display = 'none';
            document.getElementById('pwd-monitor-view').style.display = 'block';
            const heroTitle = document.getElementById('hero-title');
            const heroSub = document.getElementById('hero-sub');
            heroTitle.dataset.en = 'PWD Application Tracker';
            heroTitle.dataset.tl = 'Tracker ng PWD Application';
            heroSub.dataset.en = 'Monitor your application status, upload documents, and track admin review progress.';
            heroSub.dataset.tl = 'Subaybayan ang status ng iyong aplikasyon, mag-upload ng dokumento, at i-track ang progress ng review ng admin.';
            heroTitle.textContent = currentLang === 'tl' ? heroTitle.dataset.tl : heroTitle.dataset.en;
            heroSub.textContent = currentLang === 'tl' ? heroSub.dataset.tl : heroSub.dataset.en;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function showWizardView() {
            document.getElementById('pwd-monitor-view').style.display = 'none';
            document.getElementById('pwd-wizard-view').style.display = 'block';
            const heroTitle = document.getElementById('hero-title');
            const heroSub = document.getElementById('hero-sub');
            heroTitle.dataset.en = 'PWD Application Wizard';
            heroTitle.dataset.tl = 'Wizard ng PWD Application';
            heroSub.dataset.en = 'Follow each step below to apply for your PWD ID, submit requirements online, and verify your membership — all in one guided flow.';
            heroSub.dataset.tl = 'Sundin ang bawat hakbang sa ibaba para mag-apply ng PWD ID, magsumite ng mga kinakailangan online, at i-verify ang iyong membership — lahat sa isang gabay na daloy.';
            heroTitle.textContent = currentLang === 'tl' ? heroTitle.dataset.tl : heroTitle.dataset.en;
            heroSub.textContent = currentLang === 'tl' ? heroSub.dataset.tl : heroSub.dataset.en;
            goToStep(currentStep, false);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function finishWizard() {
            sessionStorage.setItem('pwdWizardComplete', '1');
            sessionStorage.removeItem('pwdWizardStep');
            showMonitorView();
        }

        document.addEventListener('DOMContentLoaded', function() {
            fileViewerModal = new bootstrap.Modal(document.getElementById('fileViewerModal'));

            document.querySelectorAll('.wiz-step').forEach(btn => {
                btn.addEventListener('click', () => {
                    const step = parseInt(btn.dataset.step, 10);
                    if (step <= currentStep) goToStep(step);
                });
            });

            if (shouldShowMonitor()) {
                showMonitorView();
            } else {
                const saved = sessionStorage.getItem('pwdWizardStep');
                if (saved !== null) {
                    const idx = parseInt(saved, 10);
                    if (!isNaN(idx) && idx >= 0 && idx < WIZARD_STEPS.length) {
                        goToStep(idx, false);
                    }
                } else {
                    goToStep(0, false);
                }
            }
        });

        function openFileModal(fileUrl, fileName, fileExt) {
            const container = document.getElementById('fileViewerContainer');
            const fileInfo = document.getElementById('fileInfo');
            const downloadBtn = document.getElementById('downloadFileBtn');

            downloadBtn.href = fileUrl;
            downloadBtn.setAttribute('download', fileName + '.' + fileExt);

            const ext = fileExt.toLowerCase();

            if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'].includes(ext)) {
                container.innerHTML = `<img src="${fileUrl}" alt="${fileName}" style="max-width:100%;max-height:60vh;border-radius:8px;box-shadow:0 4px 16px rgba(0,0,0,0.1);">`;
            } else if (ext === 'pdf') {
                container.innerHTML = `<iframe src="${fileUrl}" title="${fileName}" style="width:100%;height:60vh;border:none;border-radius:8px;"></iframe>`;
            } else {
                container.innerHTML = `
                    <div class="text-center">
                        <h6>File cannot be previewed</h6>
                        <p class="text-muted">This file type (${ext.toUpperCase()}) cannot be displayed in the browser.</p>
                        <a href="${fileUrl}" class="btn btn-primary" download>Download File</a>
                    </div>`;
            }

            fileInfo.innerHTML = `
                <p><strong>Document Name:</strong> <span style="font-weight:700;color:var(--primary-blue);word-break:break-all;">${escapeHtml(fileName)}</span></p>
                <p><strong>File Type:</strong> ${ext.toUpperCase()}</p>`;

            fileViewerModal.show();
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function validateFileSize(input) {
            const file = input.files[0];
            if (!file) return;

            const isImage = ['image/jpeg', 'image/jpg', 'image/png'].includes(file.type);
            const maxSize = isImage ? 5 * 1024 * 1024 : 25 * 1024 * 1024;
            const maxSizeLabel = isImage ? '5MB' : '25MB';

            if (file.size > maxSize) {
                alert(`File size must be less than ${maxSizeLabel} for ${isImage ? 'images' : 'PDF files'}.`);
                input.value = '';
                return false;
            }
            return true;
        }

        const _IS_PWD_BENEFICIARY = @json(!empty($isPwdBeneficiary));
        const _CSRF2 = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
        const _UPLOAD_URL2 = '{{ route("user.pwd-upload-requirement") }}';

        function onFileChosen(prefix) {
            if (_IS_PWD_BENEFICIARY) return;
            const scope = prefix === 'mon'
                ? document.getElementById('pwd-monitor-view')
                : document.getElementById('pwd-wizard-view');
            const selected = [...scope.querySelectorAll('.req-file')].filter(i => i.files[0]);
            const wrap = document.getElementById('inline-upload-wrap-' + prefix);
            const lbl = document.getElementById('inline-label-' + prefix);
            if (selected.length > 0) {
                lbl.textContent = `${selected.length} file${selected.length > 1 ? 's' : ''} selected — ready to upload`;
                wrap.style.display = 'block';
            } else {
                wrap.style.display = 'none';
            }
        }

        async function uploadAll(prefix) {
            if (_IS_PWD_BENEFICIARY) {
                alert('Beneficiary ka na ng PWD program. Re-application is disabled.');
                return;
            }
            const scope = prefix === 'mon'
                ? document.getElementById('pwd-monitor-view')
                : document.getElementById('pwd-wizard-view');
            const inputs = [...scope.querySelectorAll('.req-file')].filter(i => i.files[0]);
            if (!inputs.length) return;

            const btn = document.getElementById('inline-btn-' + prefix);
            const bar = document.getElementById('inline-bar-' + prefix);
            const status = document.getElementById('inline-status-' + prefix);
            const lbl = document.getElementById('inline-label-' + prefix);
            btn.disabled = true;
            btn.style.opacity = '.6';

            sessionStorage.setItem('pwdWizardComplete', '1');
            sessionStorage.removeItem('pwdWizardStep');

            let done = 0;
            for (const inp of inputs) {
                status.textContent = `Uploading ${done + 1} of ${inputs.length}: "${inp.dataset.req}"…`;
                const fd = new FormData();
                fd.append('_token', _CSRF2);
                fd.append('requirement_name', inp.dataset.req);
                fd.append('file', inp.files[0]);
                try {
                    await fetch(_UPLOAD_URL2, {
                        method: 'POST',
                        body: fd,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    done++;
                    bar.style.width = Math.round((done / inputs.length) * 100) + '%';
                } catch (e) {
                    status.textContent = `Failed: "${inp.dataset.req}"`;
                }
            }
            lbl.textContent = `${done} of ${inputs.length} uploaded — refreshing…`;
            setTimeout(() => location.reload(), 900);
        }

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

            const key = WIZARD_STEPS[currentStep];
            const meta = WIZARD_META[key];
            document.getElementById('wizard-step-title').textContent = lang === 'tl' ? meta.titleTl : meta.titleEn;
            document.getElementById('wizard-step-desc').textContent = lang === 'tl' ? meta.descTl : meta.descEn;
            document.getElementById('wiz-step-counter').textContent = lang === 'tl'
                ? `Hakbang ${currentStep + 1} ng ${WIZARD_STEPS.length}`
                : `Step ${currentStep + 1} of ${WIZARD_STEPS.length}`;
        }
    </script>
</body>
</html>
