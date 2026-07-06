<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Account – MSWDO Member Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --primary-dark: #1A2A5C;
            --secondary-yellow: #FDB913;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            --success: #16a34a;
            --danger: #dc3545;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0; padding: 0;
            font-family: 'Inter', sans-serif;
            background: var(--primary-gradient);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 32px 16px;
        }

        /* ── CARD ── */
        .auth-card {
            display: flex;
            width: 100%; max-width: 960px;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 32px 80px rgba(0,0,0,0.28);
            background: white;
        }

        /* ── LEFT PANEL ── */
        .left-panel {
            background: var(--primary-gradient);
            color: white;
            padding: 44px 36px;
            width: 320px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }
        .left-panel::before {
            content: '';
            position: absolute; bottom: -80px; right: -80px;
            width: 260px; height: 260px; border-radius: 50%;
            background: rgba(253,185,19,0.10);
        }
        .left-panel::after {
            content: '';
            position: absolute; top: -60px; left: -40px;
            width: 200px; height: 200px; border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }
        .brand { display: flex; align-items: center; gap: 10px; margin-bottom: 32px; position: relative; z-index:1; }
        .brand img { width: 42px; height: 42px; object-fit: contain; }
        .brand-name { font-size: 1.35rem; font-weight: 800; }
        .brand-sub  { font-size: 0.72rem; opacity: 0.75; font-weight: 500; }
        .panel-title { font-size: 1.4rem; font-weight: 900; line-height: 1.25; margin-bottom: 8px; position: relative; z-index:1; }
        .panel-title span { color: var(--secondary-yellow); }
        .panel-divider { width: 40px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; margin: 12px 0 16px; position: relative; z-index:1; }
        .panel-desc { font-size: 0.85rem; opacity: 0.82; line-height: 1.7; margin-bottom: 24px; position: relative; z-index:1; }
        .panel-bullets { list-style: none; padding: 0; margin: 0; position: relative; z-index:1; }
        .panel-bullets li { font-size: 0.8rem; opacity: 0.85; margin-bottom: 10px; display: flex; align-items: center; gap: 10px; }
        .panel-bullets li::before { content: ''; width: 7px; height: 7px; border-radius: 50%; background: var(--secondary-yellow); flex-shrink: 0; }
        .back-link { margin-top: auto; font-size: 0.8rem; color: rgba(255,255,255,0.75); display: inline-flex; align-items: center; gap: 6px; text-decoration: none; position: relative; z-index:1; transition: color 0.2s; }
        .back-link:hover { color: white; }

        /* ── RIGHT PANEL ── */
        .right-panel { flex: 1; padding: 36px 32px 32px; overflow-y: auto; background: #fff; }
        .form-title    { font-size: 1.55rem; font-weight: 900; color: #1E293B; margin-bottom: 2px; }
        .form-subtitle { font-size: 0.86rem; color: #64748b; margin-bottom: 20px; }
        .form-subtitle a { color: var(--primary-blue); font-weight: 700; text-decoration: none; }
        .form-subtitle a:hover { text-decoration: underline; }

        /* ── GRID ── */
        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

        /* ── LABELS & INPUTS ── */
        .field-label { font-size: 0.8rem; font-weight: 700; color: #374151; margin-bottom: 5px; display: flex; align-items: center; gap: 4px; }
        .field-label .req { color: #dc3545; }
        .field-wrap  { position: relative; margin-bottom: 4px; }

        .form-input {
            width: 100%; font-family: 'Inter', sans-serif;
            border: 1.5px solid #E2E8F0; border-radius: 11px;
            padding: 10px 13px; font-size: 0.88rem; color: #1E293B;
            transition: border-color 0.2s, box-shadow 0.2s; outline: none;
            background: #fff; appearance: none;
        }
        .form-input:focus { border-color: var(--primary-blue); box-shadow: 0 0 0 3px rgba(44,62,143,0.10); }
        .form-input.valid   { border-color: var(--success); }
        .form-input.invalid { border-color: var(--danger); }
        .form-input.valid:focus   { box-shadow: 0 0 0 3px rgba(22,163,74,0.10); }
        .form-input.invalid:focus { box-shadow: 0 0 0 3px rgba(220,53,69,0.10); }

        /* ── FIELD MESSAGE ── */
        .field-msg { font-size: 0.73rem; margin-top: 3px; min-height: 16px; display: flex; align-items: center; gap: 4px; }
        .field-msg.ok  { color: var(--success); }
        .field-msg.err { color: var(--danger); }
        .field-msg.hint{ color: #94a3b8; }

        /* ── PASSWORD TOGGLE ── */
        .pw-wrap { position: relative; }
        .pw-toggle {
            position: absolute; right: 11px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; font-size: 1.05rem;
            color: #94a3b8; padding: 0; opacity: 0.6; transition: opacity .2s;
        }
        .pw-toggle:hover { opacity: 1; }
        .pw-wrap .form-input { padding-right: 38px; }

        /* ── PASSWORD STRENGTH ── */
        .pw-strength { margin-top: 7px; }
        .pw-bars { display: flex; gap: 4px; margin-bottom: 5px; }
        .pw-bar { flex: 1; height: 4px; border-radius: 2px; background: #e2e8f0; transition: background .3s; }
        .pw-bar.weak   { background: #ef4444; }
        .pw-bar.fair   { background: #f59e0b; }
        .pw-bar.good   { background: #3b82f6; }
        .pw-bar.strong { background: var(--success); }
        .pw-criteria { display: grid; grid-template-columns: 1fr 1fr; gap: 2px 12px; }
        .pw-crit { font-size: 0.68rem; color: #94a3b8; display: flex; align-items: center; gap: 5px; }
        .pw-crit .dot { width: 6px; height: 6px; border-radius: 50%; background: #e2e8f0; flex-shrink: 0; transition: background .2s; }
        .pw-crit.met .dot { background: var(--success); }
        .pw-crit.met { color: var(--success); }

        /* ── AGE BADGE ── */
        .age-pill { display: inline-flex; align-items: center; gap: 8px; margin-top: 5px; background: #F0F5FF; border-radius: 20px; padding: 4px 12px; }
        .age-pill .age-val  { font-weight: 800; color: var(--primary-blue); font-size: 0.83rem; }
        .age-pill .age-grp  { font-size: 0.69rem; font-weight: 700; border-radius: 20px; padding: 2px 9px; }
        .ag-adult  { background: #cce5ff; color: #004085; }
        .ag-senior { background: #fce8e8; color: #721c24; }
        .age-err { color: var(--danger); font-size: 0.75rem; font-weight: 600; }

        /* ── DIVIDER ── */
        .section-divider { height: 1px; background: #F1F5F9; margin: 10px 0 16px; }

        /* ── INFO BOX ── */
        .info-box { background: #F0F5FF; border: 1px solid rgba(44,62,143,0.12); border-left: 4px solid var(--primary-blue); border-radius: 11px; padding: 10px 14px; margin-bottom: 18px; font-size: 0.79rem; color: var(--primary-blue); line-height: 1.65; }

        /* ── ALERT ── */
        .alert-styled { border-radius: 11px; font-size: 0.83rem; padding: 11px 15px; margin-bottom: 14px; }
        .alert-danger-c { background: #fce8e8; border-left: 4px solid var(--danger); color: #721c24; }

        /* ── SUBMIT BUTTON ── */
        .btn-register {
            width: 100%; background: var(--primary-gradient); color: white;
            border: none; border-radius: 11px; padding: 13px;
            font-weight: 800; font-size: 0.93rem; cursor: pointer;
            transition: all 0.3s; letter-spacing: 0.02em; margin-top: 6px;
        }
        .btn-register:hover { box-shadow: 0 10px 28px rgba(44,62,143,0.30); transform: translateY(-1px); }
        .btn-register:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

        .login-row { text-align: center; margin-top: 14px; font-size: 0.84rem; color: #64748b; }
        .login-row a { color: var(--primary-blue); font-weight: 700; text-decoration: none; }
        .login-row a:hover { text-decoration: underline; }

        .section-label { font-size: 0.72rem; font-weight: 800; color: #94a3b8; letter-spacing: .08em; text-transform: uppercase; margin: 14px 0 10px; }

        @media (max-width: 720px) {
            .left-panel { display: none; }
            .right-panel { padding: 28px 20px; }
            .field-row { grid-template-columns: 1fr; }
        }

        /* ── MODAL STYLES ── */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.6);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-y: auto;
        }
        .modal-content {
            background: white;
            border-radius: 16px;
            max-width: 700px;
            width: 100%;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            position: relative;
        }
        .modal-header {
            background: var(--primary-gradient);
            color: white;
            padding: 20px 24px;
            border-radius: 16px 16px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 800;
        }
        .modal-close {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }
        .modal-close:hover {
            background: rgba(255,255,255,0.3);
        }
        .modal-body {
            padding: 24px;
            line-height: 1.7;
            color: #334155;
        }
        .modal-body h4 {
            color: var(--primary-blue);
            font-size: 1.1rem;
            font-weight: 700;
            margin: 20px 0 10px;
        }
        .modal-body h4:first-child {
            margin-top: 0;
        }
        .modal-body p {
            margin: 10px 0;
            font-size: 0.9rem;
        }
        .modal-body ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .modal-body li {
            margin: 6px 0;
            font-size: 0.9rem;
        }
        .modal-body strong {
            color: var(--primary-blue);
        }
    </style>
</head>
<body>
    <div class="auth-card">

        <!-- ── LEFT PANEL ── -->
        <div class="left-panel">
            <div class="brand">
                <img src="{{ asset('images/mswd-logo.png') }}" alt="MSWDO">
                <div>
                    <div class="brand-name">MSWDO</div>
                    <div class="brand-sub">Municipal Social Welfare &amp; Development</div>
                </div>
            </div>

            <div class="panel-title">Join the<br><span>Member Portal</span></div>
            <div class="panel-divider"></div>
            <p class="panel-desc">Create your free account to access MSWDO programs, submit applications, and track your requirements online.</p>

            <ul class="panel-bullets">
                <li>Apply for social welfare programs</li>
                <li>Upload and track your documents</li>
                <li>Receive announcements &amp; updates</li>
                <li>View your application status anytime</li>
            </ul>

            <a href="/analysis" class="back-link">&#8592; Back to Public Analysis</a>
        </div>

        <!-- ── RIGHT PANEL ── -->
        <div class="right-panel">
            <div class="form-title">Create Account</div>
            <p class="form-subtitle">Fill in all details below to register. <a href="{{ route('login') }}">Already have an account?</a></p>

            @if($errors->any())
                <div class="alert-styled alert-danger-c">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if(session('error'))
                <div class="alert-styled alert-danger-c">{{ session('error') }}</div>
            @endif
<div class="info-box">
                Registration is for <strong>community members</strong> who wish to apply for MSWDO assistance programs.
                A verification code will be sent to your email after registration. Your uploaded valid ID will also be reviewed by an admin before you can login.
            </div>

            <form method="POST" action="{{ route('register') }}" id="registerForm" enctype="multipart/form-data" novalidate>
                @csrf

                <!-- Personal Info -->
                <div class="section-label">Personal Information</div>
                <div class="field-row">
                    <div>
                        <label class="field-label" for="first_name">First Name <span class="req">*</span></label>
                        <div class="field-wrap">
                            <input type="text" id="first_name" name="first_name"
                                   class="form-input @error('first_name') invalid @enderror"
                                   value="{{ old('first_name') }}" placeholder="Juan"
                                   autocomplete="given-name" oninput="validateFirstName(); buildFullName();">
                        </div>
                        <div id="msg_first_name" class="field-msg hint">Letters only, min 2 chars.</div>
                        @error('first_name')<div class="field-msg err">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="field-label" for="middle_name">Middle Name</label>
                        <div class="field-wrap">
                            <input type="text" id="middle_name" name="middle_name"
                                   class="form-input @error('middle_name') invalid @enderror"
                                   value="{{ old('middle_name') }}" placeholder="Santos (optional)"
                                   autocomplete="additional-name" oninput="validateMiddleName(); buildFullName();">
                        </div>
                        <div id="msg_middle_name" class="field-msg hint">Optional</div>
                        @error('middle_name')<div class="field-msg err">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="field-row" style="margin-top:8px;">
                    <div>
                        <label class="field-label" for="last_name">Last Name <span class="req">*</span></label>
                        <div class="field-wrap">
                            <input type="text" id="last_name" name="last_name"
                                   class="form-input @error('last_name') invalid @enderror"
                                   value="{{ old('last_name') }}" placeholder="Dela Cruz"
                                   autocomplete="family-name" oninput="validateLastName(); buildFullName();">
                        </div>
                        <div id="msg_last_name" class="field-msg hint">Letters only, min 2 chars.</div>
                        @error('last_name')<div class="field-msg err">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="field-label">Full Name</label>
                        <div class="field-wrap">
                            <input type="text" id="fullname_display" class="form-input" readonly style="background: #f8fafc; cursor: default;" placeholder="Auto-generated from name fields">
                        </div>
                        <div class="field-msg hint">Automatically generated</div>
                    </div>
                </div>

                <div class="field-row" style="margin-top:8px;">
                    <div>
                        <label class="field-label" for="username">Username <span class="req">*</span></label>
                        <div class="field-wrap">
                            <input type="text" id="username" name="username"
                                   class="form-input @error('username') invalid @enderror"
                                   value="{{ old('username') }}" placeholder="juandc123"
                                   autocomplete="username" oninput="validateUsername()">
                        </div>
                        <div id="msg_username" class="field-msg hint">Letters, numbers, underscores · 4–20 chars.</div>
                        @error('username')<div class="field-msg err">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="field-label" for="email">Email Address <span class="req">*</span></label>
                        <div class="field-wrap">
                            <input type="email" id="email" name="email"
                                   class="form-input @error('email') invalid @enderror"
                                   value="{{ old('email') }}" placeholder="juan@example.com"
                                   autocomplete="email" oninput="validateEmail()">
                        </div>
                        <div id="msg_email" class="field-msg hint">Must be a valid email address.</div>
                        @error('email')<div class="field-msg err">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="field-row" style="margin-top:8px;">
                    <div>
                        <label class="field-label" for="mobile_number">Mobile Number <span class="req">*</span></label>
                        <div class="field-wrap" style="position: relative;">
                            <span style="position: absolute; left: 13px; top: 50%; transform: translateY(-50%); font-size: 0.88rem; color: #64748b; font-weight: 600; pointer-events: none;">+63</span>
                            <input type="tel" id="mobile_number" name="mobile_number"
                                   class="form-input @error('mobile_number') invalid @enderror"
                                   value="{{ old('mobile_number') }}" placeholder="9XXXXXXXXX"
                                   autocomplete="tel" oninput="validateMobile()"
                                   style="padding-left: 45px;" maxlength="10">
                        </div>
                        <div id="msg_mobile" class="field-msg hint">Enter 10 digits (9XXXXXXXXX)</div>
                        @error('mobile_number')<div class="field-msg err">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="field-label" for="gender">Gender <span class="req">*</span></label>
                        <div class="field-wrap">
                            <select id="gender" name="gender"
                                    class="form-input @error('gender') invalid @enderror">
                                <option value="">Select Gender</option>
                                <option value="Male"   {{ old('gender') == 'Male'   ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other"  {{ old('gender') == 'Other'  ? 'selected' : '' }}>Other / Prefer not to say</option>
                            </select>
                        </div>
                        <div id="msg_gender" class="field-msg hint">Select your gender.</div>
                        @error('gender')<div class="field-msg err">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="field-row" style="margin-top:8px;">
                    <div>
                        <label class="field-label" for="birthdate">Date of Birth <span class="req">*</span></label>
                        <div class="field-wrap">
                            <input type="date" id="birthdate" name="birthdate"
                                   class="form-input @error('birthdate') invalid @enderror"
                                   value="{{ old('birthdate') }}"
                                   min="{{ now()->subYears(150)->format('Y-m-d') }}"
                                   max="{{ now()->subYears(18)->format('Y-m-d') }}"
                                   onchange="calculateAge()">
                        </div>
                        <div id="age-display"></div>
                        @error('birthdate')<div class="field-msg err">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="field-label" for="valid_id">Valid ID <span class="req">*</span></label>
                        <div class="field-wrap">
                            <input type="file" id="valid_id" name="valid_id" accept=".jpg,.jpeg,.png,.pdf"
                                   class="form-input @error('valid_id') invalid @enderror"
                                   onchange="validateValidId()">
                        </div>
                        <div id="msg_valid_id" class="field-msg hint">Accepted formats: JPG, PNG, PDF. Max size: 5 MB.</div>
                        @error('valid_id')<div class="field-msg err">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="field-row" style="margin-top:8px;">
                    <div>
                        <label class="field-label" for="municipality">Municipality <span class="req">*</span></label>
                        <div class="field-wrap">
                            <select id="municipality" name="municipality"
                                    class="form-input @error('municipality') invalid @enderror"
                                    onchange="populateBarangays()">
                                <option value="">Select Municipality</option>
                                @foreach($municipalities as $muni)
                                    <option value="{{ $muni->name }}" {{ old('municipality') == $muni->name ? 'selected' : '' }}>
                                        {{ $muni->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div id="msg_municipality" class="field-msg hint"></div>
                        @error('municipality')<div class="field-msg err">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="field-label" for="barangay">Barangay <span class="req">*</span></label>
                        <div class="field-wrap">
                            <select id="barangay" name="barangay"
                                    class="form-input @error('barangay') invalid @enderror"
                                    disabled>
                                <option value="">Select Municipality first</option>
                            </select>
                        </div>
                        <div id="msg_barangay" class="field-msg hint">Select your municipality first.</div>
                        @error('barangay')<div class="field-msg err">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Hidden full_name field -->
                <input type="hidden" id="full_name" name="full_name" value="{{ old('full_name') }}">

                <div class="section-divider"></div>
                <div class="section-label">Valid ID Verification</div>
                <div class="info-box">
                    Upload a clear photo or scan of your valid government-issued ID (e.g., PhilSys, Driver's License, Passport, UMID, Voter's ID, Student ID). This will be reviewed by an MSWDO admin before your account can be activated.
                </div>

                <div class="section-divider"></div>
                <div class="section-label">Terms & Conditions and Privacy Policy</div>
                <div class="info-box" style="margin-bottom: 14px;">
                    By creating an account, you agree to our Terms & Conditions and Privacy Policy.
                </div>
                
                <div style="margin-bottom: 16px;">
                    <label style="display: flex; align-items: flex-start; gap: 10px; cursor: pointer; font-size: 0.85rem; color: #334155;">
                        <input type="checkbox" id="agree_terms" name="agree_terms" required
                               style="margin-top: 3px; width: 18px; height: 18px; cursor: pointer; accent-color: var(--primary-blue);">
                        <span>
                            I have read and agree to the 
                            <a href="#" onclick="showTerms(event)" style="color: var(--primary-blue); font-weight: 700; text-decoration: none;">Terms & Conditions</a> 
                            and 
                            <a href="#" onclick="showPrivacy(event)" style="color: var(--primary-blue); font-weight: 700; text-decoration: none;">Privacy Policy</a>
                            <span style="color: var(--danger); font-weight: 700;">*</span>
                        </span>
                    </label>
                    <div id="msg_terms" class="field-msg hint" style="margin-left: 28px;">Required to proceed with registration.</div>
                </div>

                <div class="section-divider"></div>
                <div class="section-label">Account Information</div>
                <div class="info-box">
                    <strong>Note:</strong> A temporary password will be automatically generated and sent to your email. You will be required to change it after email verification.
                </div>

                <button type="submit" class="btn-register" id="submitBtn"
>
                    Create My Account &#8594;
                </button>
            </form>

            <div class="login-row">
                Already have an account? <a href="{{ route('login') }}">Sign in here</a>
            </div>
        </div>
    </div>

    <!-- Terms & Conditions Modal -->
    <div id="termsModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>📜 Terms and Conditions</h3>
                <button class="modal-close" onclick="closeModal('termsModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p><strong>MSWDO Application System</strong></p>

                <h4>1. Acceptance of Terms</h4>
                <p>By accessing and using the MSWDO Application System ("the System"), you agree to comply with these Terms and Conditions. If you do not agree, please do not use the System.</p>

                <h4>2. Purpose of the System</h4>
                <p>The System is designed to allow users to:</p>
                <ul>
                    <li>View available MSWDO programs</li>
                    <li>Submit applications and required documents</li>
                    <li>Track the status of their applications</li>
                </ul>

                <h4>3. User Responsibilities</h4>
                <p>Users agree to:</p>
                <ul>
                    <li>Provide accurate and complete information</li>
                    <li>Upload valid and authentic documents only</li>
                    <li>Keep login credentials secure</li>
                    <li>Not misuse or attempt to exploit the system</li>
                </ul>
                <p>Failure to comply may result in:</p>
                <ul>
                    <li>Rejection of application</li>
                    <li>Suspension or restriction of access</li>
                </ul>

                <h4>4. Application Process</h4>
                <ul>
                    <li>Submission of an application does not guarantee approval</li>
                    <li>Applications are subject to review and validation by MSWDO personnel</li>
                    <li>Users may be required to re-upload or provide additional documents</li>
                </ul>

                <h4>5. Approval and Rejection</h4>
                <ul>
                    <li>Applications may be approved or rejected based on eligibility</li>
                    <li>If rejected, a reason will be provided</li>
                    <li>Users may reapply or resubmit as allowed</li>
                </ul>

                <h4>6. System Availability</h4>
                <p>The System may be temporarily unavailable due to:</p>
                <ul>
                    <li>Maintenance</li>
                    <li>Updates</li>
                    <li>Technical issues</li>
                </ul>
                <p>MSWDO is not liable for delays caused by such interruptions.</p>

                <h4>7. Limitation of Liability</h4>
                <p>MSWDO shall not be held liable for:</p>
                <ul>
                    <li>Incorrect information submitted by users</li>
                    <li>Delays in processing due to incomplete requirements</li>
                    <li>Technical issues beyond control</li>
                </ul>

                <h4>8. Modifications</h4>
                <p>MSWDO reserves the right to modify these Terms at any time. Continued use of the System constitutes acceptance of updated terms.</p>
            </div>
        </div>
    </div>

    <!-- Privacy Policy Modal -->
    <div id="privacyModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>🔐 Privacy Policy</h3>
                <button class="modal-close" onclick="closeModal('privacyModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p><strong>MSWDO Application System</strong></p>

                <h4>1. Introduction</h4>
                <p>This Privacy Policy explains how your personal data is collected, used, and protected in accordance with the Data Privacy Act of 2012.</p>

                <h4>2. Information We Collect</h4>
                <p>We may collect the following:</p>
                <ul>
                    <li>Personal information (Name, Address, Contact Number, Email)</li>
                    <li>Application details</li>
                    <li>Uploaded documents (PDF, images)</li>
                    <li>System usage data (logs, timestamps)</li>
                </ul>

                <h4>3. Purpose of Data Collection</h4>
                <p>Your data is used to:</p>
                <ul>
                    <li>Process your application</li>
                    <li>Verify your identity and eligibility</li>
                    <li>Communicate updates and results</li>
                    <li>Improve system performance and services</li>
                </ul>

                <h4>4. Data Sharing</h4>
                <p>Your information may be shared only with:</p>
                <ul>
                    <li>Authorized MSWDO personnel</li>
                    <li>Government agencies when required by law</li>
                </ul>
                <p>We do <strong>not sell or share your data for commercial purposes</strong>.</p>

                <h4>5. Data Protection</h4>
                <p>We implement reasonable security measures to protect your data, including:</p>
                <ul>
                    <li>Secure authentication (login system)</li>
                    <li>Restricted access to authorized personnel</li>
                    <li>Data storage protection</li>
                </ul>

                <h4>6. Data Retention</h4>
                <p>Your data will be stored only as long as necessary for:</p>
                <ul>
                    <li>Application processing</li>
                    <li>Legal and administrative requirements</li>
                </ul>

                <h4>7. Your Rights (Under Philippine Law)</h4>
                <p>Under the Data Privacy Act of 2012, you have the right to:</p>
                <ul>
                    <li>Access your personal data</li>
                    <li>Request correction of inaccurate data</li>
                    <li>Request deletion (subject to legal limitations)</li>
                    <li>Withdraw consent where applicable</li>
                </ul>

                <h4>8. Contact Information</h4>
                <p>For concerns regarding your data, you may contact:</p>
                <p><strong>MSWDO Office</strong><br>
                Email: mswdo@example.com<br>
                Contact Number: (049) XXX-XXXX</p>
                <p>You may also contact the National Privacy Commission for complaints.</p>

                <h4>9. Updates to Privacy Policy</h4>
                <p>This policy may be updated from time to time. Continued use of the System indicates your acceptance of any changes.</p>
            </div>
        </div>
    </div>

    <script>
    // ── Barangay data from server ──
    const barangayData = @json($barangays);

    // ── Populate barangay dropdown ──
    function populateBarangays() {
        const muni = document.getElementById('municipality').value;
        const brgySelect = document.getElementById('barangay');
        const msg = document.getElementById('msg_barangay');
        brgySelect.innerHTML = '';

        if (!muni || !barangayData[muni]) {
            brgySelect.innerHTML = '<option value="">Select municipality first</option>';
            brgySelect.disabled = true;
            msg.textContent = 'Select your municipality first.';
            msg.className = 'field-msg hint';
            return;
        }

        brgySelect.innerHTML = '<option value="">Select Barangay</option>';
        barangayData[muni].forEach(b => {
            const opt = document.createElement('option');
            opt.value = b;
            opt.textContent = b;
            // Restore old value if any
            if (b === '{{ old('barangay') }}') opt.selected = true;
            brgySelect.appendChild(opt);
        });
        brgySelect.disabled = false;
        msg.textContent = `${barangayData[muni].length} barangays available.`;
        msg.className = 'field-msg ok';
    }

    // Run on page load if municipality was pre-selected (after server-side error)
    document.addEventListener('DOMContentLoaded', () => {
        populateBarangays();
        const oldBar = '{{ old('barangay') }}';
        if (oldBar) {
            const opt = [...document.getElementById('barangay').options].find(o => o.value === oldBar);
            if (opt) opt.selected = true;
        }
        if (document.getElementById('birthdate').value) calculateAge();
        // Build full name on page load if fields have values
        buildFullName();
    });

    // ── Field Validators ──
    function setMsg(id, type, text) {
        const el = document.getElementById(id);
        if (!el) return;
        el.textContent = text;
        el.className = 'field-msg ' + type;
    }
    function markInput(id, state) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('valid','invalid');
        if (state) el.classList.add(state);
    }

    function buildFullName() {
        const first = document.getElementById('first_name').value.trim();
        const middle = document.getElementById('middle_name').value.trim();
        const last = document.getElementById('last_name').value.trim();
        const fullName = [first, middle, last].filter(Boolean).join(' ');
        document.getElementById('full_name').value = fullName;
        document.getElementById('fullname_display').value = fullName;
    }

    function validateFirstName() {
        const v = document.getElementById('first_name').value.trim();
        if (!v) { setMsg('msg_first_name','hint','Letters only, min 2 chars.'); markInput('first_name',''); return; }
        if (v.length < 2) { setMsg('msg_first_name','err','Too short — minimum 2 characters.'); markInput('first_name','invalid'); return; }
        if (!/^[a-zA-ZÀ-ÿ\s'\-\.]+$/.test(v)) { setMsg('msg_first_name','err','Only letters, spaces, hyphens, and apostrophes allowed.'); markInput('first_name','invalid'); return; }
        setMsg('msg_first_name','ok','✓ Looks good!'); markInput('first_name','valid');
    }

    function validateMiddleName() {
        const v = document.getElementById('middle_name').value.trim();
        if (!v) { setMsg('msg_middle_name','hint','Optional'); markInput('middle_name',''); return; }
        if (!/^[a-zA-ZÀ-ÿ\s'\-\.]+$/.test(v)) { setMsg('msg_middle_name','err','Only letters, spaces, hyphens, and apostrophes allowed.'); markInput('middle_name','invalid'); return; }
        setMsg('msg_middle_name','ok','✓ Valid'); markInput('middle_name','valid');
    }

    function validateLastName() {
        const v = document.getElementById('last_name').value.trim();
        if (!v) { setMsg('msg_last_name','hint','Letters only, min 2 chars.'); markInput('last_name',''); return; }
        if (v.length < 2) { setMsg('msg_last_name','err','Too short — minimum 2 characters.'); markInput('last_name','invalid'); return; }
        if (!/^[a-zA-ZÀ-ÿ\s'\-\.]+$/.test(v)) { setMsg('msg_last_name','err','Only letters, spaces, hyphens, and apostrophes allowed.'); markInput('last_name','invalid'); return; }
        setMsg('msg_last_name','ok','✓ Looks good!'); markInput('last_name','valid');
    }

    function validateUsername() {
        const v = document.getElementById('username').value.trim();
        if (!v) { setMsg('msg_username','hint','Letters, numbers, underscores · 4–20 chars.'); markInput('username',''); return; }
        if (v.length < 4) { setMsg('msg_username','err','Too short — minimum 4 characters.'); markInput('username','invalid'); return; }
        if (v.length > 20) { setMsg('msg_username','err','Too long — maximum 20 characters.'); markInput('username','invalid'); return; }
        if (!/^[a-zA-Z0-9_]+$/.test(v)) { setMsg('msg_username','err','Only letters, numbers, and underscores — no spaces.'); markInput('username','invalid'); return; }
        
        // Check username availability via AJAX
        setMsg('msg_username','hint','Checking availability...');
        fetch('/check-username', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ username: v })
        })
        .then(response => response.json())
        .then(data => {
            if (data.available) {
                setMsg('msg_username','ok','✓ Username is available!');
                markInput('username','valid');
            } else {
                setMsg('msg_username','err','✗ This username is already taken.');
                markInput('username','invalid');
            }
        })
        .catch(error => {
            console.error('Error checking username:', error);
            setMsg('msg_username','hint','Could not verify availability. Please try again.');
            markInput('username','');
        });
    }

    function validateEmail() {
        const v = document.getElementById('email').value.trim();
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
        if (!v) { setMsg('msg_email','hint','Must be a valid email address.'); markInput('email',''); return; }
        if (!re.test(v)) { setMsg('msg_email','err','Enter a valid email address.'); markInput('email','invalid'); return; }
        setMsg('msg_email','ok','✓ Valid email format.'); markInput('email','valid');
    }

    function validateMobile() {
        const v = document.getElementById('mobile_number').value.trim();
        const cleaned = v.replace(/\D/g, '');
        
        if (!v) { 
            setMsg('msg_mobile','hint','Enter 10 digits (9XXXXXXXXX)'); 
            markInput('mobile_number',''); 
            return; 
        }
        
        // Must be exactly 10 digits and start with 9
        if (cleaned.length !== 10 || !cleaned.startsWith('9')) {
            setMsg('msg_mobile','err','Must be 10 digits starting with 9 (e.g., 9171234567)');
            markInput('mobile_number','invalid'); 
            return;
        }
        
        // Check for 5 or more consecutive repeated digits
        if (/(\d)\1{4,}/.test(cleaned)) {
            setMsg('msg_mobile','err','Cannot contain 5 or more repeated digits in a row');
            markInput('mobile_number','invalid'); 
            return;
        }
        
        setMsg('msg_mobile','ok','✓ Valid Philippine number (+63' + cleaned + ')'); 
        markInput('mobile_number','valid');
    }

    function validateValidId() {
        const input = document.getElementById('valid_id');
        const file = input.files[0];
        if (!file) {
            setMsg('msg_valid_id', 'hint', 'Accepted formats: JPG, PNG, PDF. Max size: 5 MB.');
            markInput('valid_id', '');
            return;
        }
        const allowed = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
        if (!allowed.includes(file.type)) {
            setMsg('msg_valid_id', 'err', 'Only JPG, PNG, or PDF files are allowed.');
            markInput('valid_id', 'invalid');
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            setMsg('msg_valid_id', 'err', 'File must not exceed 5 MB.');
            markInput('valid_id', 'invalid');
            return;
        }
        setMsg('msg_valid_id', 'ok', '✓ ' + file.name + ' selected');
        markInput('valid_id', 'valid');
    }

    // ── Age calculator ──
    function calculateAge() {
        const val = document.getElementById('birthdate').value;
        const display = document.getElementById('age-display');
        const input = document.getElementById('birthdate');
        if (!val) { display.innerHTML = ''; markInput('birthdate',''); return; }

        const today = new Date();
        const birth = new Date(val);
        if (birth > today) {
            display.innerHTML = '<div class="age-err">Birth date cannot be in the future.</div>';
            markInput('birthdate','invalid'); return;
        }

        let age = today.getFullYear() - birth.getFullYear();
        const m = today.getMonth() - birth.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;

        if (age < 18) {
            display.innerHTML = '<div class="age-err">⚠ You must be at least 18 years old to register.</div>';
            markInput('birthdate','invalid'); return;
        }

        const grpClass = age >= 60 ? 'ag-senior' : 'ag-adult';
        const grpLabel = age >= 60 ? 'Senior Citizen' : 'Adult';
        display.innerHTML = `<div class="age-pill"><span class="age-val">${age} yrs old</span><span class="age-grp ${grpClass}">${grpLabel}</span></div>`;
        markInput('birthdate','valid');
    }

    // ── Password strength ──
    function checkPassword() {
        const v = document.getElementById('password').value;
        const wrap = document.getElementById('pw_strength_wrap');
        wrap.style.display = v.length ? 'block' : 'none';

        const cLen   = v.length >= 8;
        const cUpper = /[A-Z]/.test(v);
        const cLower = /[a-z]/.test(v);
        const cNum   = /\d/.test(v);
        const cSym   = /[@$!%*?&#_\-\.]/.test(v);

        setCrit('c_len',   cLen);
        setCrit('c_upper', cUpper);
        setCrit('c_lower', cLower);
        setCrit('c_num',   cNum);
        setCrit('c_sym',   cSym);

        const score = [cLen, cUpper, cLower, cNum, cSym].filter(Boolean).length;
        const bars = ['s1','s2','s3','s4'];
        const levels = ['','weak','fair','good','strong'];
        const cls = score <= 1 ? 'weak' : score === 2 ? 'fair' : score === 3 ? 'good' : score === 4 ? 'good' : 'strong';

        bars.forEach((b,i) => {
            const el = document.getElementById(b);
            el.className = 'pw-bar';
            if (i < score) el.classList.add(cls);
        });

        markInput('password', (score >= 5 && cLen) ? 'valid' : v.length ? 'invalid' : '');
        checkConfirm();
    }

    function setCrit(id, met) {
        const el = document.getElementById(id);
        if (met) el.classList.add('met'); else el.classList.remove('met');
    }

    function checkConfirm() {
        const pw  = document.getElementById('password').value;
        const cpw = document.getElementById('password_confirmation').value;
        const msg = document.getElementById('msg_confirm');
        const inp = document.getElementById('password_confirmation');
        if (!cpw) { msg.textContent = 'Must match the password above.'; msg.className = 'field-msg hint'; inp.classList.remove('valid','invalid'); return; }
        if (pw === cpw) { msg.textContent = '✓ Passwords match!'; msg.className = 'field-msg ok'; inp.classList.add('valid'); inp.classList.remove('invalid'); }
        else            { msg.textContent = '✗ Passwords do not match.'; msg.className = 'field-msg err'; inp.classList.add('invalid'); inp.classList.remove('valid'); }
    }

    // ── Password visibility toggle ──
    function togglePw(fieldId, btn) {
        const input = document.getElementById(fieldId);
        if (input.type === 'password') { input.type = 'text'; btn.style.opacity = '1'; }
        else { input.type = 'password'; btn.style.opacity = '0.6'; }
    }

    // ── Prevent submit if obvious client errors ──
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        buildFullName();
        validateFirstName();
        validateLastName();
        validateUsername();
        validateEmail();
        validateMobile();
        validateValidId();

        const hasInvalid = document.querySelectorAll('.form-input.invalid').length > 0;
        const agreeTerms = document.getElementById('agree_terms').checked;
        const msgTerms = document.getElementById('msg_terms');
        const validIdInput = document.getElementById('valid_id');

        if (!validIdInput.files.length) {
            setMsg('msg_valid_id', 'err', 'Please upload your valid ID.');
            markInput('valid_id', 'invalid');
            e.preventDefault();
            validIdInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        if (!agreeTerms) {
            msgTerms.textContent = '✗ You must agree to the Terms & Conditions and Privacy Policy.';
            msgTerms.className = 'field-msg err';
            e.preventDefault();
            document.getElementById('agree_terms').scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        } else {
            msgTerms.textContent = '✓ Agreed';
            msgTerms.className = 'field-msg ok';
        }

        if (hasInvalid) {
            e.preventDefault();
            // Scroll to first error
            const first = document.querySelector('.form-input.invalid');
            if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    // ── Terms & Privacy Modal Functions ──
    function showTerms(e) {
        e.preventDefault();
        const modal = document.getElementById('termsModal');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function showPrivacy(e) {
        e.preventDefault();
        const modal = document.getElementById('privacyModal');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const termsModal = document.getElementById('termsModal');
        const privacyModal = document.getElementById('privacyModal');
        if (event.target === termsModal) {
            closeModal('termsModal');
        }
        if (event.target === privacyModal) {
            closeModal('privacyModal');
        }
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
