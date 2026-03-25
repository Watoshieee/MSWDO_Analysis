<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account – MSWDO Member Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #2C3E8F;
            --primary-dark: #1A2A5C;
            --secondary-yellow: #FDB913;
            --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
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
            width: 100%; max-width: 920px;
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
            width: 340px;
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
        .brand { display: flex; align-items: center; gap: 10px; margin-bottom: 36px; position: relative; z-index:1; }
        .brand img { width: 42px; height: 42px; object-fit: contain; }
        .brand-name { font-size: 1.35rem; font-weight: 800; }
        .brand-sub  { font-size: 0.72rem; opacity: 0.75; font-weight: 500; }
        .panel-title { font-size: 1.45rem; font-weight: 900; line-height: 1.25; margin-bottom: 8px; position: relative; z-index:1; }
        .panel-title span { color: var(--secondary-yellow); }
        .panel-divider { width: 40px; height: 4px; background: var(--secondary-yellow); border-radius: 2px; margin: 14px 0 18px; position: relative; z-index:1; }
        .panel-desc { font-size: 0.87rem; opacity: 0.82; line-height: 1.7; margin-bottom: 28px; position: relative; z-index:1; }
        .panel-bullets { list-style: none; padding: 0; margin: 0; position: relative; z-index:1; }
        .panel-bullets li { font-size: 0.82rem; opacity: 0.85; margin-bottom: 10px; display: flex; align-items: center; gap: 10px; }
        .panel-bullets li::before { content: ''; width: 7px; height: 7px; border-radius: 50%; background: var(--secondary-yellow); flex-shrink: 0; }
        .back-link { margin-top: auto; font-size: 0.82rem; color: rgba(255,255,255,0.75); display: inline-flex; align-items: center; gap: 6px; text-decoration: none; position: relative; z-index:1; transition: color 0.2s; }
        .back-link:hover { color: white; }

        /* ── RIGHT PANEL ── */
        .right-panel { flex: 1; padding: 40px 36px 36px; overflow-y: auto; background: #fff; }
        .form-title      { font-size: 1.6rem; font-weight: 900; color: #1E293B; margin-bottom: 4px; }
        .form-subtitle   { font-size: 0.87rem; color: #64748b; margin-bottom: 24px; }
        .form-subtitle a { color: var(--primary-blue); font-weight: 700; text-decoration: none; }
        .form-subtitle a:hover { text-decoration: underline; }

        /* field row */
        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

        /* labels & inputs */
        .field-label { font-size: 0.82rem; font-weight: 700; color: #374151; margin-bottom: 6px; display: block; }
        .field-wrap  { position: relative; margin-bottom: 16px; }
        .form-input {
            width: 100%; font-family: 'Inter', sans-serif;
            border: 1.5px solid #E2E8F0; border-radius: 12px;
            padding: 11px 14px; font-size: 0.9rem; color: #1E293B;
            transition: all 0.25s; outline: none; background: #fff;
            appearance: none;
        }
        .form-input:focus { border-color: var(--primary-blue); box-shadow: 0 0 0 3px rgba(44,62,143,0.10); }
        .form-input.is-invalid { border-color: #dc3545; }
        .form-input.is-invalid:focus { box-shadow: 0 0 0 3px rgba(220,53,69,0.10); }
        .field-hint { font-size: 0.73rem; color: #94a3b8; margin-top: 4px; }
        .invalid-feedback { font-size: 0.78rem; color: #dc3545; margin-top: 4px; display: block; }

        /* age badge */
        .age-pill { display: inline-flex; align-items: center; gap: 8px; margin-top: 6px; background: #F0F5FF; border-radius: 20px; padding: 5px 14px; }
        .age-pill .age-val  { font-weight: 800; color: var(--primary-blue); font-size: 0.85rem; }
        .age-pill .age-grp  { font-size: 0.7rem; font-weight: 700; border-radius: 20px; padding: 2px 10px; }
        .ag-child  { background: #d4edda; color: #155724; }
        .ag-teen   { background: #fff3cd; color: #856404; }
        .ag-adult  { background: #cce5ff; color: #004085; }
        .ag-senior { background: #fce8e8; color: #721c24; }

        /* password toggle */
        .pw-wrap { position: relative; }
        .pw-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; font-size: 1rem; color: #94a3b8; padding: 0; }

        /* divider */
        .section-divider { height: 1px; background: #F1F5F9; margin: 6px 0 18px; }

        /* info box */
        .info-box { background: #F0F5FF; border: 1px solid rgba(44,62,143,0.12); border-left: 4px solid var(--primary-blue); border-radius: 12px; padding: 12px 16px; margin-bottom: 20px; font-size: 0.8rem; color: var(--primary-blue); line-height: 1.7; }

        /* alert */
        .alert-styled { border-radius: 12px; font-size: 0.85rem; padding: 12px 16px; margin-bottom: 16px; }
        .alert-danger-c  { background: #fce8e8; border-left: 4px solid #dc3545; color: #721c24; }

        /* submit button */
        .btn-register {
            width: 100%; background: var(--primary-gradient); color: white;
            border: none; border-radius: 12px; padding: 14px;
            font-weight: 800; font-size: 0.95rem; cursor: pointer;
            transition: all 0.3s; letter-spacing: 0.02em;
            margin-top: 4px;
        }
        .btn-register:hover { box-shadow: 0 10px 28px rgba(44,62,143,0.30); transform: translateY(-1px); }

        .login-row { text-align: center; margin-top: 16px; font-size: 0.85rem; color: #64748b; }
        .login-row a { color: var(--primary-blue); font-weight: 700; text-decoration: none; }
        .login-row a:hover { text-decoration: underline; }

        /* ── RESPONSIVE ── */
        @media (max-width: 720px) {
            .left-panel { display: none; }
            .right-panel { padding: 32px 22px; }
            .field-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="auth-card">

        <!-- ── LEFT PANEL ── -->
        <div class="left-panel">
            <div class="brand">
                <img src="/images/mswd-logo.png" alt="MSWDO">
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
            <p class="form-subtitle">Fill in the details below to register as a member. <a href="{{ route('login') }}">Already have an account?</a></p>

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
                A verification code will be sent to your email after registration.
            </div>

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <!-- Personal Info -->
                <div class="field-row">
                    <div class="field-wrap">
                        <label class="field-label" for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" class="form-input @error('full_name') is-invalid @enderror"
                               value="{{ old('full_name') }}" placeholder="Juan Dela Cruz" required>
                        @error('full_name')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="field-wrap">
                        <label class="field-label" for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-input @error('username') is-invalid @enderror"
                               value="{{ old('username') }}" placeholder="juandc" required>
                        @error('username')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-wrap">
                        <label class="field-label" for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-input @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="juan@example.com" required>
                        @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="field-wrap">
                        <label class="field-label" for="mobile_number">Mobile Number</label>
                        <input type="tel" id="mobile_number" name="mobile_number" class="form-input @error('mobile_number') is-invalid @enderror"
                               value="{{ old('mobile_number') }}" placeholder="09XXXXXXXXX">
                        @error('mobile_number')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="field-row">
                    <div class="field-wrap">
                        <label class="field-label" for="birthdate">Date of Birth</label>
                        <input type="date" id="birthdate" name="birthdate" class="form-input @error('birthdate') is-invalid @enderror"
                               value="{{ old('birthdate') }}" onchange="calculateAge()" required>
                        @error('birthdate')<span class="invalid-feedback">{{ $message }}</span>@enderror
                        <div id="age-display"></div>
                    </div>
                    <div class="field-wrap">
                        <label class="field-label" for="municipality">Municipality</label>
                        <select id="municipality" name="municipality" class="form-input @error('municipality') is-invalid @enderror">
                            <option value="">Select Municipality</option>
                            @foreach($municipalities as $muni)
                                <option value="{{ $muni->name }}" {{ old('municipality') == $muni->name ? 'selected' : '' }}>
                                    {{ $muni->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('municipality')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="section-divider"></div>

                <div class="field-row">
                    <div class="field-wrap">
                        <label class="field-label" for="password">Password</label>
                        <div class="pw-wrap">
                            <input type="password" id="password" name="password" class="form-input @error('password') is-invalid @enderror"
                                   placeholder="Min. 8 characters" required>
                            <button type="button" class="pw-toggle" onclick="togglePw('password', this)">&#128065;</button>
                        </div>
                        @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="field-wrap">
                        <label class="field-label" for="password_confirmation">Confirm Password</label>
                        <div class="pw-wrap">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="form-input" placeholder="Re-enter password" required>
                            <button type="button" class="pw-toggle" onclick="togglePw('password_confirmation', this)">&#128065;</button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-register">Create My Account &#8594;</button>
            </form>

            <div class="login-row">
                Already have an account? <a href="{{ route('login') }}">Sign in here</a>
            </div>
        </div>
    </div>

    <script>
        function togglePw(fieldId, btn) {
            const input = document.getElementById(fieldId);
            if (input.type === 'password') {
                input.type = 'text';
                btn.style.opacity = '1';
            } else {
                input.type = 'password';
                btn.style.opacity = '0.5';
            }
        }

        function calculateAge() {
            const val = document.getElementById('birthdate').value;
            const display = document.getElementById('age-display');
            if (!val) { display.innerHTML = ''; return; }

            const today = new Date();
            const birth = new Date(val);
            if (birth > today) {
                display.innerHTML = '<span style="color:#dc3545;font-size:0.78rem;font-weight:600;">Birth date cannot be in the future.</span>';
                return;
            }

            let age = today.getFullYear() - birth.getFullYear();
            const m = today.getMonth() - birth.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;

            let grpClass = 'ag-adult', grpLabel = 'Adult';
            if (age >= 60)       { grpClass = 'ag-senior'; grpLabel = 'Senior Citizen'; }
            else if (age >= 18)  { grpClass = 'ag-adult';  grpLabel = 'Adult'; }
            else if (age >= 13)  { grpClass = 'ag-teen';   grpLabel = 'Teenager'; }
            else                 { grpClass = 'ag-child';  grpLabel = 'Child'; }

            display.innerHTML = `
                <div class="age-pill">
                    <span class="age-val">${age} yrs old</span>
                    <span class="age-grp ${grpClass}">${grpLabel}</span>
                </div>`;
        }

        document.addEventListener('DOMContentLoaded', () => {
            if (document.getElementById('birthdate').value) calculateAge();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>