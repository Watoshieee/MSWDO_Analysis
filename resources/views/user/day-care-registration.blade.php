<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title data-en="Registration for Day Care Services - MSWDO" data-tl="Rehistrasyon para sa Day Care Services - MSWDO">Registration for Day Care Services - MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        html, body { overscroll-behavior: none; margin: 0; padding: 0; }

        :root {
            --primary-blue: {{ $primaryColor ?? '#2C3E8F' }};
            --primary-blue-light: #E5EEFF;
            --secondary-yellow: {{ $secondaryColor ?? '#FDB913' }};
            --secondary-yellow-light: #FFF3D6;
            --primary-gradient: linear-gradient(135deg, {{ $primaryColor ?? '#2C3E8F' }} 0%, #1A2A5C 100%);
            --secondary-gradient: linear-gradient(135deg, {{ $secondaryColor ?? '#FDB913' }} 0%, #E5A500 100%);
            --bg-light: #F8FAFC;
            --border-light: #E2E8F0;
            --text-dark: #1E293B;
        }

        *, body { font-family: 'Inter', 'Segoe UI', sans-serif; }

        body {
            background: var(--bg-light);
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        a { text-decoration: none; }

        .top-bar {
            background: var(--primary-gradient);
            padding: 14px 0;
            box-shadow: 0 4px 20px rgba(44, 62, 143, .2);
        }

        .top-bar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            font-weight: 800;
            font-size: 1.45rem;
        }

        .brand img { width: 34px; height: 34px; object-fit: contain; }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, .12);
            border: 2px solid rgba(255, 255, 255, .4);
            color: white;
            border-radius: 30px;
            padding: 8px 22px;
            font-weight: 700;
            font-size: .88rem;
            cursor: pointer;
            transition: all .3s;
            text-decoration: none;
        }

        .back-btn:hover {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
            border-color: var(--secondary-yellow);
        }

        .hero-banner {
            background: var(--primary-gradient);
            color: white;
            padding: 26px 0 22px;
            position: relative;
            overflow: hidden;
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            top: -90px;
            right: -90px;
            width: 360px;
            height: 360px;
            border-radius: 50%;
            background: rgba(253, 185, 19, .10);
        }

        .hero-inner { position: relative; z-index: 2; }

        .hero-badge {
            display: inline-block;
            background: rgba(253, 185, 19, .18);
            color: var(--secondary-yellow);
            border: 1px solid rgba(253, 185, 19, .35);
            border-radius: 30px;
            padding: 5px 18px;
            font-size: .75rem;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .hero-banner h1 {
            font-size: 2rem;
            font-weight: 900;
            margin-bottom: 6px;
            line-height: 1.15;
        }

        .hero-divider {
            width: 50px;
            height: 4px;
            background: var(--secondary-yellow);
            border-radius: 2px;
            margin: 12px 0;
        }

        .hero-banner p.hero-sub {
            opacity: .85;
            font-size: .92rem;
            margin: 0;
            max-width: 900px;
            line-height: 1.65;
        }

        .lang-toggle {
            display: inline-flex;
            border-radius: 30px;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, .4);
        }

        .lang-btn {
            background: transparent;
            border: none;
            color: rgba(255, 255, 255, .7);
            font-weight: 700;
            font-size: .82rem;
            padding: 8px 20px;
            cursor: pointer;
            transition: all .2s;
            letter-spacing: .05em;
        }

        .lang-btn.active {
            background: var(--secondary-yellow);
            color: var(--primary-blue);
        }

        .lang-btn:hover:not(.active) {
            background: rgba(255, 255, 255, .15);
            color: white;
        }

        .form-card {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--border-light);
            box-shadow: 0 8px 32px rgba(44, 62, 143, .08);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .form-card-top {
            background: var(--primary-gradient);
            color: white;
            padding: 20px 32px 18px;
        }

        .form-card-top h2 { font-size: 1.25rem; font-weight: 800; margin: 0 0 4px; }
        .form-card-top p { margin: 0; font-size: .85rem; opacity: .85; }

        .form-card-body { padding: 28px 32px; }

        .section-title {
            font-size: 1.02rem;
            font-weight: 800;
            color: var(--primary-blue);
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--border-light);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-badge {
            background: var(--primary-blue-light);
            color: var(--primary-blue);
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
            font-weight: 800;
        }

        .form-label {
            font-size: .84rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 6px;
        }

        .req-star { color: #dc2626; margin-left: 2px; }

        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid var(--border-light);
            padding: 10px 14px;
            font-size: .9rem;
            transition: all .2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(44, 62, 143, .12);
        }

        .form-control[readonly] {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: 700;
            cursor: not-allowed;
        }

        /* PWD-Style Requirement Box */
        .pwd-req {
            padding: 12px 16px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid var(--border-light);
            border-left: 4px solid #dee2e6;
            margin-bottom: 16px;
            transition: all .2s;
        }

        .pwd-req.uploaded {
            border-left-color: #28a745;
            background: #f0fff8;
            border-top-color: #bbf7d0;
            border-right-color: #bbf7d0;
            border-bottom-color: #bbf7d0;
        }

        .pwd-req-name {
            font-weight: 700;
            font-size: .92rem;
            color: #1e293b;
            line-height: 1.35;
        }

        .pwd-thumb {
            width: 36px;
            height: 36px;
            object-fit: cover;
            border-radius: 6px;
            cursor: pointer;
            vertical-align: middle;
            border: 1px solid #cbd5e1;
        }

        .pwd-upload-box {
            background: #FFF3D6;
            border-radius: 10px;
            padding: 12px 14px;
            margin-top: 10px;
            border: 1px dashed rgba(253, 185, 19, .6);
        }

        .btn-submit-main {
            background: var(--secondary-gradient);
            color: var(--primary-blue);
            border: none;
            border-radius: 12px;
            padding: 13px 34px;
            font-weight: 800;
            font-size: .96rem;
            cursor: pointer;
            transition: all .25s;
            box-shadow: 0 4px 14px rgba(253, 185, 19, .35);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-submit-main:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(253, 185, 19, .48);
        }

        .btn-submit-main:disabled {
            opacity: .5;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .toast-notice {
            position: fixed;
            top: 84px;
            right: 18px;
            z-index: 1080;
            max-width: 420px;
            background: linear-gradient(135deg,#2C3E8F,#1A2A5C);
            color: white;
            border-radius: 12px;
            padding: 12px 16px;
            box-shadow: 0 10px 28px rgba(26,42,92,.35);
            font-size: .84rem;
            font-weight: 700;
        }

        .ui-loading-backdrop {
            position: fixed; inset: 0;
            background: rgba(15,23,42,0.55);
            backdrop-filter: blur(1.5px);
            z-index: 12050; display: none;
            align-items: center; justify-content: center;
        }

        .ui-loading-box {
            width: 100%; max-width: 340px; border-radius: 16px;
            background: linear-gradient(135deg,#2C3E8F,#1A2A5C);
            color: #fff; box-shadow: 0 16px 44px rgba(15,23,42,.35);
            border: 1px solid rgba(255,255,255,.15);
            padding: 20px 18px; text-align: center;
        }

        .ui-loading-spinner {
            width: 44px; height: 44px; margin: 0 auto 10px;
            border-radius: 50%; border: 3px solid rgba(255,255,255,.25);
            border-top-color: #FDB913; animation: uiSpin .8s linear infinite;
        }

        @keyframes uiSpin { to { transform: rotate(360deg); } }
        .ui-loading-title { font-weight: 800; font-size: .98rem; }
        .ui-loading-sub { margin-top: 4px; opacity: .85; font-size: .8rem; }
        @keyframes slideInRight  { from { transform: translateX(400px); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideOutRight { from { transform: translateX(0); opacity: 1; } to { transform: translateX(400px); opacity: 0; } }
        @keyframes flashTimerShrink { from { width: 100%; } to { width: 0%; } }

        .footer-strip {
            background: var(--primary-gradient);
            color: rgba(255, 255, 255, .85);
            text-align: center;
            padding: 18px;
            font-size: .85rem;
            margin-top: auto;
        }

        .summary-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #bbf7d0;
            box-shadow: 0 6px 20px rgba(34, 197, 94, .08);
            padding: 24px;
            margin-bottom: 24px;
        }

        .summary-item {
            margin-bottom: 12px;
        }

        .summary-label {
            font-size: .78rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .summary-val {
            font-size: .95rem;
            font-weight: 700;
            color: #1e293b;
        }

        html { background: #1A2A5C; }
        body { padding-bottom: 0 !important; }

        @media (max-width: 576px) {
            .hero-banner h1 { font-size: 1.45rem; }
            .form-card-body { padding: 20px 16px; }
            .form-card-top { padding: 16px; }
            .btn-submit-main { width: 100%; justify-content: center; }
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
                    <a href="{{ route('user.programs') }}" class="back-btn">&#8592; <span data-en="Back to Programs" data-tl="Bumalik sa Programs">Back to Programs</span></a>
                </div>
            </div>
        </div>
    </div>

    <section class="hero-banner">
        <div class="container">
            <div class="hero-inner">
                <div class="hero-badge" data-en="Liliw MSWDO Service" data-tl="Serbisyo ng Liliw MSWDO">Liliw MSWDO Service</div>
                <h1 data-en="Registration for Day Care Services" data-tl="Rehistrasyon para sa Day Care Services">Registration for Day Care Services</h1>
                <div class="hero-divider"></div>
                <p class="hero-sub"
                    data-en="Register your child for early childhood care and development at the Child Development Center in Liliw. Fill in all required information and upload the required documents below."
                    data-tl="I-rehistro ang iyong anak para sa early childhood care and development sa Child Development Center sa Liliw. Punan ang lahat ng kinakailangang impormasyon at i-upload ang mga kailangang dokumento sa ibaba.">
                    Register your child for early childhood care and development at the Child Development Center in Liliw. Fill in all required information and upload the required documents below.
                </p>
            </div>
        </div>
    </section>

    @if(session('upload_success') || session('success') || session('error'))
        <div class="toast-notice">
            {{ session('upload_success') ?: (session('success') ?: session('error')) }}
        </div>
    @endif

    <div class="flex-grow-1 py-3 pb-4">
        <div class="container">

            @php
                $isSubmitted = !empty($application) && ($application->status ?? '') === 'submitted' && empty($isNew);
                $formData = $application->form_data ?? [];
            @endphp

            @if($isSubmitted)
                <!-- SUBMITTED SUMMARY VIEW -->
                <div class="summary-card">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge" style="background:#22c55e;color:#fff;font-size:.85rem;padding:6px 12px;border-radius:20px;">
                                &#10003; <span data-en="Registration Submitted" data-tl="Naisumite na ang Rehistrasyon">Registration Submitted</span>
                            </span>
                            <span class="text-muted" style="font-size:.82rem;">
                                {{ !empty($application->application_date) ? \Carbon\Carbon::parse($application->application_date)->format('M d, Y h:i A') : '' }}
                            </span>
                        </div>
                        <a href="{{ route('user.day-care-registration', ['new' => 1]) }}" class="btn btn-sm btn-outline-primary" style="font-weight:700;border-radius:8px;">
                            + <span data-en="Register Another Child" data-tl="Magrehistro ng Isa Pang Bata">Register Another Child</span>
                        </a>
                    </div>

                    <h4 style="font-weight:800;color:var(--primary-blue);margin-bottom:18px;">
                        <span data-en="Day Care Registration Summary" data-tl="Buod ng Rehistrasyon sa Day Care">Day Care Registration Summary</span>
                    </h4>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6 summary-item">
                            <div class="summary-label" data-en="Child Development Center" data-tl="Child Development Center">Child Development Center</div>
                            <div class="summary-val">{{ $formData['child_development_center'] ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6 summary-item">
                            <div class="summary-label" data-en="Child Full Name" data-tl="Buong Pangalan ng Bata">Child Full Name</div>
                            <div class="summary-val">{{ $formData['child_full_name'] ?? ($application->full_name ?? 'N/A') }}</div>
                        </div>
                        <div class="col-md-3 summary-item">
                            <div class="summary-label" data-en="Birthday" data-tl="Araw ng Kapanganakan">Birthday</div>
                            <div class="summary-val">
                                {{ !empty($formData['birthday']) ? \Carbon\Carbon::parse($formData['birthday'])->format('M d, Y') : 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-3 summary-item">
                            <div class="summary-label" data-en="Calculated Age" data-tl="Kinalkulang Edad">Calculated Age</div>
                            <div class="summary-val">
                                {{ isset($formData['age']) ? $formData['age'] . ' years old' : ($application->age . ' years old') }}
                            </div>
                        </div>
                        <div class="col-md-6 summary-item">
                            <div class="summary-label" data-en="Father's Name" data-tl="Pangalan ng Ama">Father's Name</div>
                            <div class="summary-val">{{ $formData['father_name'] ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6 summary-item">
                            <div class="summary-label" data-en="Mother's Name" data-tl="Pangalan ng Ina">Mother's Name</div>
                            <div class="summary-val">{{ $formData['mother_name'] ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6 summary-item">
                            <div class="summary-label" data-en="Address" data-tl="Tirahan">Address</div>
                            <div class="summary-val">{{ $formData['address'] ?? 'N/A' }}</div>
                        </div>
                    </div>

                    <h5 style="font-weight:700;color:var(--primary-blue);margin-bottom:12px;font-size:1rem;">
                        <span data-en="Uploaded Documents" data-tl="Mga Na-upload na Dokumento">Uploaded Documents</span>
                    </h5>
                    <div class="row g-3">
                        @foreach($dayCareRequirements as $reqName)
                            @php
                                $uf = ($uploadedFiles ?? collect())->firstWhere('requirement_name', $reqName);
                                $isUp = $uf && !empty($uf->file_path);
                            @endphp
                            <div class="col-md-6">
                                <div class="pwd-req uploaded" style="margin-bottom:0;">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <div class="pwd-req-name">{{ $reqName }}</div>
                                            <div style="font-size:.72rem;color:#64748b;">
                                                {{ $isUp && $uf->uploaded_at ? 'Uploaded on ' . \Carbon\Carbon::parse($uf->uploaded_at)->format('M d, Y') : 'Uploaded' }}
                                            </div>
                                        </div>
                                        @if($isUp)
                                            @php
                                                $ext = strtolower(pathinfo($uf->file_path, PATHINFO_EXTENSION));
                                                $fileUrl = route('user.serve-file', $uf->id);
                                            @endphp
                                            @if(in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                                                <img src="{{ $fileUrl }}" onclick="openFileModal('{{ $fileUrl }}', '{{ addslashes($reqName) }}', '{{ $ext }}')" class="pwd-thumb" alt="Preview">
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="openFileModal('{{ $fileUrl }}', '{{ addslashes($reqName) }}', '{{ $ext }}')" style="font-size:.72rem;font-weight:600;padding:2px 8px;">View</button>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!$isSubmitted)
                <!-- REGISTRATION FORM -->
                <div class="form-card">
                    <div class="form-card-top">
                        <h2 data-en="Day Care Enrollment Form" data-tl="Form sa Pagpapatala sa Day Care">Day Care Enrollment Form</h2>
                        <p data-en="Please complete all required fields and upload the two required documents." data-tl="Mangyaring kumpletuhin ang lahat ng kinakailangang impormasyon at i-upload ang dalawang dokumento.">Please complete all required fields and upload the two required documents.</p>
                    </div>

                    <div class="form-card-body">
                        <form action="{{ route('user.day-care-register') }}" method="POST" enctype="multipart/form-data" id="dayCareRegisterForm">
                            @csrf

                            <!-- SECTION 1: NAME OF CHILD DEVELOPMENT CENTER -->
                            <div class="section-title">
                                <span class="section-badge">1</span>
                                <span data-en="Child Development Center" data-tl="Child Development Center">Child Development Center</span>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="child_development_center">
                                    <span data-en="Name of Child Development Center" data-tl="Pangalan ng Child Development Center">Name of Child Development Center</span>
                                    <span class="req-star">*</span>
                                </label>
                                <input type="text"
                                    class="form-control @error('child_development_center') is-invalid @enderror"
                                    id="child_development_center"
                                    name="child_development_center"
                                    value="{{ old('child_development_center', $formData['child_development_center'] ?? '') }}"
                                    placeholder="e.g., Liliw Central Child Development Center"
                                    required>
                                @error('child_development_center')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- SECTION 2: CHILD INFORMATION -->
                            <div class="section-title">
                                <span class="section-badge">2</span>
                                <span data-en="Child Information" data-tl="Impormasyon ng Bata">Child Information</span>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="child_last_name">
                                        <span data-en="Last Name" data-tl="Apelyido">Last Name</span>
                                        <span class="req-star">*</span>
                                    </label>
                                    <input type="text"
                                        class="form-control @error('child_last_name') is-invalid @enderror"
                                        id="child_last_name"
                                        name="child_last_name"
                                        value="{{ old('child_last_name', $formData['child_last_name'] ?? '') }}"
                                        placeholder="Last Name"
                                        required>
                                    @error('child_last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="child_first_name">
                                        <span data-en="First Name" data-tl="Pangalan">First Name</span>
                                        <span class="req-star">*</span>
                                    </label>
                                    <input type="text"
                                        class="form-control @error('child_first_name') is-invalid @enderror"
                                        id="child_first_name"
                                        name="child_first_name"
                                        value="{{ old('child_first_name', $formData['child_first_name'] ?? '') }}"
                                        placeholder="First Name"
                                        required>
                                    @error('child_first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="child_middle_name">
                                        <span data-en="Middle Name" data-tl="Gitnang Pangalan">Middle Name</span>
                                        <span class="req-star">*</span>
                                    </label>
                                    <input type="text"
                                        class="form-control @error('child_middle_name') is-invalid @enderror"
                                        id="child_middle_name"
                                        name="child_middle_name"
                                        value="{{ old('child_middle_name', $formData['child_middle_name'] ?? '') }}"
                                        placeholder="Middle Name"
                                        required>
                                    @error('child_middle_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="child_birthday">
                                        <span data-en="Birthday" data-tl="Araw ng Kapanganakan">Birthday</span>
                                        <span class="req-star">*</span>
                                    </label>
                                    <input type="date"
                                        class="form-control @error('birthday') is-invalid @enderror"
                                        id="child_birthday"
                                        name="birthday"
                                        value="{{ old('birthday', $formData['birthday'] ?? '') }}"
                                        max="{{ date('Y-m-d') }}"
                                        onchange="updateChildAge()"
                                        oninput="updateChildAge()"
                                        required>
                                    @error('birthday')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="child_age_display">
                                        <span data-en="Age" data-tl="Edad">Age</span>
                                        <span class="badge bg-secondary ms-1" style="font-size:.65rem;" data-en="Auto-calculated" data-tl="Kusang kinakalkula">Auto-calculated</span>
                                        <span class="req-star">*</span>
                                    </label>
                                    <input type="text"
                                        class="form-control"
                                        id="child_age_display"
                                        name="age_display"
                                        value="{{ old('age_display', isset($formData['age']) ? $formData['age'] . ' years old' : '') }}"
                                        placeholder="Select Birthday above to auto-calculate"
                                        readonly
                                        required>
                                    <input type="hidden" id="child_age" name="age" value="{{ old('age', $formData['age'] ?? '') }}">
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="father_name">
                                        <span data-en="Name of Father" data-tl="Pangalan ng Ama">Name of Father</span>
                                        <span class="req-star">*</span>
                                    </label>
                                    <input type="text"
                                        class="form-control @error('father_name') is-invalid @enderror"
                                        id="father_name"
                                        name="father_name"
                                        value="{{ old('father_name', $formData['father_name'] ?? '') }}"
                                        placeholder="Full Name of Father"
                                        required>
                                    @error('father_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="mother_name">
                                        <span data-en="Name of Mother" data-tl="Pangalan ng Ina">Name of Mother</span>
                                        <span class="req-star">*</span>
                                    </label>
                                    <input type="text"
                                        class="form-control @error('mother_name') is-invalid @enderror"
                                        id="mother_name"
                                        name="mother_name"
                                        value="{{ old('mother_name', $formData['mother_name'] ?? '') }}"
                                        placeholder="Full Name of Mother"
                                        required>
                                    @error('mother_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="address">
                                    <span data-en="Address" data-tl="Tirahan">Address</span>
                                    <span class="req-star">*</span>
                                </label>
                                <textarea
                                    class="form-control @error('address') is-invalid @enderror"
                                    id="address"
                                    name="address"
                                    rows="2"
                                    placeholder="House No., Street, Barangay, Liliw, Laguna"
                                    required>{{ old('address', $formData['address'] ?? ($user->barangay ? $user->barangay . ', Liliw, Laguna' : 'Liliw, Laguna')) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- SECTION 3: REQUIRED DOCUMENTS -->
                            <div class="section-title">
                                <span class="section-badge">3</span>
                                <span data-en="Required Documents" data-tl="Mga Kinakailangang Dokumento">Required Documents</span>
                            </div>

                            <p class="text-muted" style="font-size:.85rem;margin-bottom:14px;"
                                data-en="Both documents are required before submitting. You may upload them directly below. Once uploaded, each document is marked as uploaded immediately."
                                data-tl="Kailangan ang parehong dokumento bago mag-submit. Maaari mo itong i-upload sa ibaba. Kapag nai-upload, agad itong mamarkahan bilang uploaded.">
                                Both documents are required before submitting. You may upload them directly below. Once uploaded, each document is marked as uploaded immediately.
                            </p>

                            @php
                                $totalReq = count($dayCareRequirements);
                                $upCount = ($uploadedFiles ?? collect())->filter(fn ($f) => !empty($f->file_path))->count();
                                $pct = $totalReq > 0 ? round(($upCount / $totalReq) * 100) : 0;
                            @endphp

                            <div style="margin-bottom:18px;">
                                <div style="height:6px;background:#dbe4ff;border-radius:3px;overflow:hidden;">
                                    <div style="width:{{ $pct }}%;height:100%;background:var(--secondary-yellow);border-radius:3px;transition:width .4s;" id="daycare-upload-progress"></div>
                                </div>
                                <div style="font-size:.74rem;color:#64748b;margin-top:4px;" id="daycare-upload-progress-text">
                                    {{ $upCount }}/{{ $totalReq }} uploaded — {{ $pct }}% complete
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                @foreach($dayCareRequirements as $reqName)
                                    @php
                                        $uf = ($uploadedFiles ?? collect())->firstWhere('requirement_name', $reqName);
                                        $isUploaded = $uf && !empty($uf->file_path);
                                        $cls = $isUploaded ? 'uploaded' : '';
                                    @endphp
                                    <div class="col-md-6">
                                        <div class="pwd-req {{ $cls }}" data-req-name="{{ $reqName }}">
                                            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:6px;">
                                                <div style="flex:1;">
                                                    <div class="pwd-req-name">
                                                        {{ $reqName }}
                                                        <span class="badge bg-danger ms-1" style="font-size:.64rem;font-weight:700;vertical-align:middle;">REQUIRED</span>
                                                    </div>
                                                    <div class="daycare-uploaded-date" style="font-size:.7rem;color:#94a3b8;margin-top:2px;{{ $isUploaded && $uf->uploaded_at ? '' : 'display:none;' }}">
                                                        {{ $isUploaded && $uf->uploaded_at ? 'Uploaded on ' . \Carbon\Carbon::parse($uf->uploaded_at)->format('M j, Y') : '' }}
                                                    </div>
                                                </div>
                                                <div style="display:flex;align-items:center;gap:6px;flex-shrink:0;">
                                                    <span class="daycare-status-badge badge" style="background:{{ $isUploaded ? '#d4edda' : '#e2e8f0' }};color:{{ $isUploaded ? '#155724' : '#64748b' }};border-radius:20px;padding:3px 10px;font-size:.74rem;font-weight:700;">
                                                        {{ $isUploaded ? 'Uploaded' : 'Not uploaded' }}
                                                    </span>
                                                    <div class="pwd-thumb-wrap">
                                                        @if($isUploaded)
                                                            @php
                                                                $ext = strtolower(pathinfo($uf->file_path, PATHINFO_EXTENSION));
                                                                $fileUrl = route('user.serve-file', $uf->id);
                                                            @endphp
                                                            @if(in_array($ext, ['jpg', 'jpeg', 'png', 'webp']))
                                                                <img src="{{ $fileUrl }}" onclick="openFileModal('{{ $fileUrl }}', '{{ addslashes($reqName) }}', '{{ $ext }}')" class="pwd-thumb" alt="Preview">
                                                            @else
                                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="openFileModal('{{ $fileUrl }}', '{{ addslashes($reqName) }}', '{{ $ext }}')" style="font-size:.72rem;font-weight:600;padding:2px 8px;">View</button>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="pwd-upload-box">
                                                <div style="font-size:.75rem;font-weight:600;color:#856404;margin-bottom:6px;">
                                                    {{ $isUploaded ? 'Replace document' : 'Choose file to upload' }}
                                                </div>
                                                <div class="js-daycare-upload-form" data-req-name="{{ $reqName }}">
                                                    <div class="d-flex gap-2 align-items-center flex-wrap">
                                                        <div style="flex:1;min-width:0;">
                                                            <input type="file" name="{{ $reqName === 'Birth Certificate of the Child' ? 'birth_certificate' : 'immunization_book' }}"
                                                                class="form-control form-control-sm js-daycare-file-input"
                                                                data-req-name="{{ $reqName }}"
                                                                accept=".jpg,.jpeg,.png,.pdf">
                                                            <div style="font-size:.64rem;color:#94a3b8;margin-top:3px;">Images: 5MB max · PDF: 25MB max</div>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-warning js-daycare-ajax-btn" data-req-name="{{ $reqName }}" style="font-weight:700;white-space:nowrap;">
                                                            {{ $isUploaded ? 'Replace' : 'Upload' }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- SUBMIT SECTION -->
                            <div class="text-end pt-3 border-top">
                                <button type="submit" class="btn-submit-main" id="btnSubmitDayCare" {{ empty($allDayCareUploaded) ? 'disabled' : '' }}>
                                    <span data-en="Submit Registration" data-tl="I-submit ang Rehistrasyon">Submit Registration</span> &#8594;
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <div class="text-center py-2">
                <a href="{{ route('user.programs') }}" class="back-btn d-inline-flex" style="font-size:.92rem;padding:12px 28px;background:var(--primary-gradient);">
                    &#8592; <span data-en="Return to Programs" data-tl="Bumalik sa Programs">Return to Programs</span>
                </a>
            </div>
        </div>
    </div>

    <div class="footer-strip">
        <div class="container">
            <strong>Municipal Social Welfare and Development Office (MSWDO) — Liliw, Laguna</strong>
        </div>
    </div>

    <!-- UI LOADING OVERLAY -->
    <div id="uiLoadingBackdrop" class="ui-loading-backdrop" aria-hidden="true">
        <div class="ui-loading-box">
            <div class="ui-loading-spinner"></div>
            <div class="ui-loading-title">Uploading Document</div>
            <div class="ui-loading-sub">Please wait while we process your file.</div>
        </div>
    </div>

    <!-- DOCUMENT VIEWER MODAL -->
    <div class="modal fade" id="fileViewerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:20px;overflow:hidden;border:none;">
                <div class="modal-header" style="background:var(--primary-gradient);color:white;border:none;padding:20px 24px;">
                    <h5 class="modal-title" id="fileViewerModalLabel" style="font-weight:800;font-size:1.2rem;">Document Viewer</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="padding:24px;">
                    <div id="fileViewerContainer" style="text-align:center;background:var(--bg-light);border-radius:12px;padding:24px;min-height:400px;display:flex;align-items:center;justify-content:center;"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentLang = 'en';
        let allUploaded = @json(!empty($allDayCareUploaded));
        const totalReq = {{ count($dayCareRequirements ?? []) }};
        const uploadUrl = @json(route('user.day-care-upload-requirement'));

        function setLang(lang) {
            currentLang = lang;
            document.querySelectorAll('.lang-btn').forEach(btn => {
                btn.classList.toggle('active', btn.dataset.lang === lang);
            });
            document.querySelectorAll('[data-en][data-tl]').forEach(el => {
                if (el.querySelector('input,button,select,textarea')) return;
                el.textContent = lang === 'tl' ? el.dataset.tl : el.dataset.en;
            });
        }

        // Automatic Age Calculation from Birthday
        function updateChildAge() {
            const bdayInput = document.getElementById('child_birthday');
            const ageDisplay = document.getElementById('child_age_display');
            const ageHidden = document.getElementById('child_age');
            if (!bdayInput || !ageDisplay || !ageHidden) return;

            const val = bdayInput.value;
            if (!val) {
                ageDisplay.value = '';
                ageHidden.value = '';
                return;
            }

            const birthDate = new Date(val);
            const today = new Date();

            if (isNaN(birthDate.getTime())) {
                ageDisplay.value = '';
                ageHidden.value = '';
                return;
            }

            if (birthDate > today) {
                ageDisplay.value = 'Invalid (future date)';
                ageHidden.value = '';
                showDayCareNotification('Birthday cannot be in the future.');
                bdayInput.value = '';
                return;
            }

            let years = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                years--;
            }

            if (years <= 0) {
                let months = (today.getFullYear() - birthDate.getFullYear()) * 12 + (today.getMonth() - birthDate.getMonth());
                if (today.getDate() < birthDate.getDate()) months--;
                months = Math.max(0, months);
                ageDisplay.value = months + ' month' + (months === 1 ? '' : 's') + ' old';
                ageHidden.value = '0';
            } else {
                ageDisplay.value = years + ' year' + (years === 1 ? '' : 's') + ' old';
                ageHidden.value = String(years);
            }

            checkFormValidity();
        }

        function showReqLoading() {
            const el = document.getElementById('uiLoadingBackdrop');
            if (el) { el.style.display = 'flex'; el.setAttribute('aria-hidden','false'); }
        }

        function hideReqLoading() {
            const el = document.getElementById('uiLoadingBackdrop');
            if (el) { el.style.display = 'none'; el.setAttribute('aria-hidden','true'); }
        }

        function showDayCareNotification(message) {
            const existing = document.getElementById('dayCareNotif');
            if (existing) existing.remove();
            const notif = document.createElement('div');
            notif.id = 'dayCareNotif';
            notif.style.cssText = 'position:fixed;top:84px;right:18px;z-index:1081;max-width:420px;background:linear-gradient(135deg,#2C3E8F,#1A2A5C);color:white;border-radius:12px;padding:12px 16px;box-shadow:0 10px 28px rgba(26,42,92,.35);font-size:.84rem;font-weight:700;animation:slideInRight .4s ease;';
            notif.innerHTML = `
                <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
                    <span>${message}</span>
                    <button onclick="this.closest('#dayCareNotif').remove()" style="background:transparent;border:none;color:rgba(255,255,255,.7);font-size:1.2rem;cursor:pointer;line-height:1;">&times;</button>
                </div>
                <div style="position:absolute;bottom:0;left:0;height:3px;background:rgba(253,185,19,.9);width:100%;border-radius:0 0 12px 12px;animation:flashTimerShrink 5s linear forwards;"></div>
            `;
            document.body.appendChild(notif);
            setTimeout(() => { if (notif.parentNode) { notif.style.animation = 'slideOutRight .4s ease forwards'; setTimeout(() => notif.remove(), 400); } }, 5000);
        }

        function markRowUploaded(row, data) {
            row.classList.add('uploaded');
            const badge = row.querySelector('.daycare-status-badge');
            if (badge) {
                badge.style.background = '#d4edda';
                badge.style.color = '#155724';
                badge.textContent = 'Uploaded';
            }
            const dateEl = row.querySelector('.daycare-uploaded-date');
            if (dateEl && data.uploaded_at) {
                dateEl.style.display = '';
                dateEl.textContent = 'Uploaded on ' + data.uploaded_at;
            }
            const btn = row.querySelector('.js-daycare-ajax-btn');
            if (btn) btn.textContent = 'Replace';
            const hint = row.querySelector('.pwd-upload-box > div');
            if (hint) hint.textContent = 'Replace document';

            const thumbWrap = row.querySelector('.pwd-thumb-wrap');
            if (thumbWrap && data.file_url) {
                const ext = (data.file_ext || '').toLowerCase();
                if (['jpg','jpeg','png','webp'].includes(ext)) {
                    thumbWrap.innerHTML = `<img src="${data.file_url}" onclick="openFileModal('${data.file_url}', '${data.requirement_name}', '${ext}')" class="pwd-thumb" alt="Preview">`;
                } else {
                    thumbWrap.innerHTML = `<button type="button" class="btn btn-sm btn-outline-secondary" onclick="openFileModal('${data.file_url}', '${data.requirement_name}', '${ext}')" style="font-size:.72rem;font-weight:600;padding:2px 8px;">View</button>`;
                }
            }
        }

        // AJAX single-file upload
        document.querySelectorAll('.js-daycare-ajax-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const reqName = this.dataset.reqName;
                const row = document.querySelector(`.pwd-req[data-req-name="${reqName}"]`);
                if (!row) return;

                const fileInput = row.querySelector('.js-daycare-file-input');
                if (!fileInput || !fileInput.files.length) {
                    showDayCareNotification('Please select a file to upload for "' + reqName + '".');
                    return;
                }

                const file = fileInput.files[0];
                const isImage = ['image/jpeg','image/jpg','image/png'].includes(file.type);
                const isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');

                if (!isImage && !isPdf) {
                    showDayCareNotification('Invalid file type. Only JPG, PNG, and PDF files are allowed.');
                    return;
                }

                const maxSize = isImage ? 5 * 1024 * 1024 : 25 * 1024 * 1024;
                if (file.size > maxSize) {
                    showDayCareNotification('"' + file.name + '" exceeds the ' + (isImage ? '5MB' : '25MB') + ' limit.');
                    return;
                }

                btn.disabled = true;
                showReqLoading();

                const fd = new FormData();
                fd.append('requirement_name', reqName);
                fd.append('file', file);
                fd.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                fetch(uploadUrl, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    body: fd
                })
                .then(r => r.json().then(data => ({ ok: r.ok, data })))
                .then(result => {
                    hideReqLoading();
                    btn.disabled = false;
                    if (result.ok && result.data && result.data.success) {
                        markRowUploaded(row, result.data);
                        fileInput.value = '';
                        allUploaded = !!result.data.all_uploaded;
                        const progress = document.getElementById('daycare-upload-progress');
                        const progressText = document.getElementById('daycare-upload-progress-text');
                        const pct = result.data.total_required ? Math.round((result.data.uploaded_count / result.data.total_required) * 100) : 0;
                        if (progress) progress.style.width = pct + '%';
                        if (progressText) progressText.textContent = `${result.data.uploaded_count}/${result.data.total_required} uploaded — ${pct}% complete`;
                        checkFormValidity();
                        showDayCareNotification(result.data.message || 'File uploaded successfully.');
                    } else {
                        showDayCareNotification((result.data && result.data.message) || 'Upload failed. Please try again.');
                    }
                })
                .catch(() => {
                    hideReqLoading();
                    btn.disabled = false;
                    showDayCareNotification('Upload failed. Please try again.');
                });
            });
        });

        function checkFormValidity() {
            const submitBtn = document.getElementById('btnSubmitDayCare');
            if (!submitBtn) return;

            const cdc = document.getElementById('child_development_center')?.value.trim();
            const fn = document.getElementById('child_first_name')?.value.trim();
            const mn = document.getElementById('child_middle_name')?.value.trim();
            const ln = document.getElementById('child_last_name')?.value.trim();
            const bday = document.getElementById('child_birthday')?.value;
            const fat = document.getElementById('father_name')?.value.trim();
            const mot = document.getElementById('mother_name')?.value.trim();
            const addr = document.getElementById('address')?.value.trim();

            const textFieldsFilled = !!(cdc && fn && mn && ln && bday && fat && mot && addr);

            // Check if both documents are uploaded via AJAX OR selected in input
            const uploadedRows = document.querySelectorAll('.pwd-req.uploaded').length;
            const hasBirthCert = document.querySelector('.pwd-req[data-req-name="Birth Certificate of the Child"].uploaded') ||
                document.querySelector('input[name="birth_certificate"]')?.files.length > 0;
            const hasBabybook = document.querySelector('.pwd-req[data-req-name="Babybook / Immunization Book"].uploaded') ||
                document.querySelector('input[name="immunization_book"]')?.files.length > 0;

            const docsReady = (uploadedRows >= totalReq) || (hasBirthCert && hasBabybook);

            submitBtn.disabled = !(textFieldsFilled && docsReady);
        }

        // Add listeners to all inputs to update button state
        document.querySelectorAll('#dayCareRegisterForm input, #dayCareRegisterForm textarea').forEach(el => {
            el.addEventListener('input', checkFormValidity);
            el.addEventListener('change', checkFormValidity);
        });

        // Form submit validation
        const formEl = document.getElementById('dayCareRegisterForm');
        if (formEl) {
            formEl.addEventListener('submit', function (e) {
                const cdc = document.getElementById('child_development_center')?.value.trim();
                const fn = document.getElementById('child_first_name')?.value.trim();
                const mn = document.getElementById('child_middle_name')?.value.trim();
                const ln = document.getElementById('child_last_name')?.value.trim();
                const bday = document.getElementById('child_birthday')?.value;
                const fat = document.getElementById('father_name')?.value.trim();
                const mot = document.getElementById('mother_name')?.value.trim();
                const addr = document.getElementById('address')?.value.trim();

                if (!cdc || !fn || !mn || !ln || !bday || !fat || !mot || !addr) {
                    e.preventDefault();
                    showDayCareNotification('Please complete all required text fields.');
                    return;
                }

                const uploadedRows = document.querySelectorAll('.pwd-req.uploaded').length;
                const hasBirthCert = document.querySelector('.pwd-req[data-req-name="Birth Certificate of the Child"].uploaded') ||
                    document.querySelector('input[name="birth_certificate"]')?.files.length > 0;
                const hasBabybook = document.querySelector('.pwd-req[data-req-name="Babybook / Immunization Book"].uploaded') ||
                    document.querySelector('input[name="immunization_book"]')?.files.length > 0;

                if (!((uploadedRows >= totalReq) || (hasBirthCert && hasBabybook))) {
                    e.preventDefault();
                    showDayCareNotification('Please upload both the Birth Certificate of the Child and Babybook / Immunization Book.');
                    return;
                }
            });
        }

        function openFileModal(url, title, ext) {
            const label = document.getElementById('fileViewerModalLabel');
            const container = document.getElementById('fileViewerContainer');
            if (label) label.textContent = title || 'Document Viewer';
            if (container) {
                const lower = (ext || '').toLowerCase();
                if (['jpg','jpeg','png','webp','gif'].includes(lower)) {
                    container.innerHTML = `<img src="${url}" style="max-width:100%;max-height:70vh;border-radius:8px;">`;
                } else {
                    container.innerHTML = `<iframe src="${url}" style="width:100%;height:70vh;border:0;border-radius:8px;"></iframe>`;
                }
            }
            const modal = new bootstrap.Modal(document.getElementById('fileViewerModal'));
            modal.show();
        }

        // Initialize age if birthday was already filled
        if (document.getElementById('child_birthday')?.value) {
            updateChildAge();
        }
        checkFormValidity();
    </script>
</body>
</html>
