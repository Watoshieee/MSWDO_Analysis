<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password – MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #1A2A5C 0%, #2C3E8F 55%, #3D5AA0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: fixed; top: -120px; right: -120px;
            width: 420px; height: 420px; border-radius: 50%;
            background: rgba(253,185,19,0.08);
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed; bottom: -100px; left: -80px;
            width: 350px; height: 350px; border-radius: 50%;
            background: rgba(255,255,255,0.04);
            pointer-events: none;
        }

        .login-wrapper {
            width: 100%;
            max-width: 880px;
            display: flex;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0,0,0,0.4);
            position: relative; z-index: 1;
        }

        /* LEFT – Branding Panel */
        .brand-panel {
            flex: 1;
            background: linear-gradient(160deg, rgba(255,255,255,0.10) 0%, rgba(255,255,255,0.04) 100%);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255,255,255,0.12);
            padding: 54px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: white;
        }
        .brand-logo {
            display: flex; align-items: center; gap: 14px;
            margin-bottom: 38px;
        }
        .brand-logo .logo-text { font-size: 1.3rem; font-weight: 800; line-height: 1.2; }
        .brand-logo .logo-sub  { font-size: 0.78rem; opacity: 0.7; font-weight: 400; margin-top: 2px; }
        .brand-panel h2 {
            font-size: 1.75rem; font-weight: 800; line-height: 1.25;
            margin-bottom: 12px;
        }
        .brand-panel h2 span { color: #FDB913; }
        .brand-divider {
            width: 48px; height: 4px;
            background: #FDB913; border-radius: 2px;
            margin-bottom: 18px;
        }
        .brand-panel p {
            font-size: 0.92rem; opacity: 0.80; line-height: 1.75; margin-bottom: 28px;
        }
        .tip-list { list-style: none; padding: 0; margin: 0; }
        .tip-list li {
            display: flex; align-items: flex-start; gap: 12px;
            font-size: 0.87rem; opacity: 0.85; margin-bottom: 12px;
        }
        .tip-dot {
            flex-shrink: 0;
            width: 8px; height: 8px; border-radius: 50%;
            background: #FDB913; margin-top: 6px;
        }
        .brand-back {
            display: inline-flex; align-items: center; gap: 6px;
            color: rgba(255,255,255,0.70); font-size: 0.85rem;
            text-decoration: none; margin-top: 34px;
            transition: color 0.2s ease;
        }
        .brand-back:hover { color: #FDB913; }

        /* RIGHT – Form Panel */
        .form-panel {
            flex: 1;
            background: white;
            padding: 54px 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .form-panel h3 {
            font-size: 1.5rem; font-weight: 800;
            color: #1e293b; margin-bottom: 5px;
        }
        .form-panel .form-sub {
            font-size: 0.88rem; color: #64748b; margin-bottom: 28px;
        }

        /* Alerts */
        .alert { border-radius: 10px; border: none; font-size: 0.88rem; padding: 12px 16px; }
        .alert-success { background: #e8f5e9; color: #1e7e34; }
        .alert-danger  { background: #fce8e8; color: #C41E24; }

        /* Labels */
        .form-label { font-weight: 600; font-size: 0.88rem; color: #334155; margin-bottom: 6px; }

        /* Inputs */
        .input-wrapper { position: relative; }
        .form-control {
            border-radius: 12px;
            border: 2px solid #E2E8F0;
            padding: 11px 42px 11px 16px;
            font-size: 0.92rem; font-family: 'Inter', sans-serif;
            transition: all 0.25s ease;
            background: #F8FAFC;
            width: 100%;
        }
        .form-control.no-icon { padding-right: 16px; }
        .form-control:focus {
            border-color: #2C3E8F;
            background: white;
            box-shadow: 0 0 0 4px rgba(44,62,143,0.08);
            outline: none;
        }
        .form-control::placeholder { color: #CBD5E1; }
        .form-control:disabled { opacity: 0.65; cursor: not-allowed; }

        /* Toggle password button */
        .toggle-pw {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none; padding: 0;
            color: #94a3b8; cursor: pointer; font-size: 0.85rem;
            font-family: 'Inter', sans-serif; font-weight: 600;
            transition: color 0.2s;
        }
        .toggle-pw:hover { color: #2C3E8F; }

        /* Hint text */
        .field-hint { font-size: 0.76rem; color: #94a3b8; margin-top: 5px; }

        /* Strength bar */
        .strength-bar {
            height: 4px; border-radius: 2px;
            background: #E2E8F0; margin-top: 8px; overflow: hidden;
        }
        .strength-fill {
            height: 100%; border-radius: 2px;
            transition: width 0.3s ease, background 0.3s ease;
            width: 0%;
        }
        .strength-label { font-size: 0.72rem; color: #94a3b8; margin-top: 4px; }

        /* Submit button */
        .btn-submit {
            background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            border: none; color: white;
            padding: 13px; border-radius: 12px;
            font-size: 0.95rem; font-weight: 700;
            width: 100%; letter-spacing: 0.01em;
            transition: all 0.3s ease;
            cursor: pointer; margin-top: 8px;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(44,62,143,0.28);
        }
        .btn-submit:active { transform: translateY(0); }

        .form-footer {
            text-align: center; margin-top: 18px;
            font-size: 0.86rem; color: #64748b;
        }
        .form-footer a {
            color: #2C3E8F; font-weight: 600; text-decoration: none;
            transition: color 0.2s;
        }
        .form-footer a:hover { color: #FDB913; text-decoration: underline; }

        @media (max-width: 700px) {
            .brand-panel { display: none; }
            .login-wrapper { max-width: 440px; }
            .form-panel { padding: 40px 28px; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <!-- LEFT: Branding -->
    <div class="brand-panel">
        <div class="brand-logo">
            <img src="/images/mswd-logo.png" alt="MSWD Logo" style="width:56px;height:56px;object-fit:contain;">
            <div>
                <div class="logo-text">MSWDO</div>
                <div class="logo-sub">Municipal Social Welfare &amp; Development</div>
            </div>
        </div>

        <h2>Create a New<br><span>Password</span></h2>
        <div class="brand-divider"></div>
        <p>
            Choose a strong password to keep your MSWDO account secure.
            Make sure it's something only you would know.
        </p>

        <ul class="tip-list">
            <li><span class="tip-dot"></span><span>At least 8 characters long.</span></li>
            <li><span class="tip-dot"></span><span>Include at least one uppercase letter (A–Z).</span></li>
            <li><span class="tip-dot"></span><span>Include at least one number (0–9).</span></li>
            <li><span class="tip-dot"></span><span>Include a special character (e.g. @, #, !).</span></li>
            <li><span class="tip-dot"></span><span>Do not reuse your previous password.</span></li>
        </ul>

        <a href="{{ route('login') }}" class="brand-back">
            &#8592; Back to Login
        </a>
    </div>

    <!-- RIGHT: Form -->
    <div class="form-panel">
        <h3>Reset Password</h3>
        <p class="form-sub">Enter your new password below to regain access.</p>

        @if($errors->any())
            <div class="alert alert-danger mb-3">
                @foreach($errors->all() as $error)
                    <p class="mb-0">{{ $error }}</p>
                @endforeach
            </div>
        @endif
        @if(session('status'))
            <div class="alert alert-success mb-3">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            {{-- Email (auto-filled & read-only) --}}
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control no-icon"
                    value="{{ $email ?? request('email') ?? old('email') }}"
                    placeholder="your@email.com"
                    required
                    readonly
                    style="background:#F0F5FF; color:#475569;">
                <p class="field-hint">This is the email linked to your account.</p>
            </div>

            {{-- New Password --}}
            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <div class="input-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Create a strong password"
                        required
                        oninput="checkStrength(this.value)">
                    <button type="button" class="toggle-pw" onclick="togglePw('password', 'eye1')">
                        <span id="eye1">Show</span>
                    </button>
                </div>
                <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                <p class="strength-label" id="strengthLabel"></p>
            </div>

            {{-- Confirm Password --}}
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <div class="input-wrapper">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Re-enter the same password"
                        required>
                    <button type="button" class="toggle-pw" onclick="togglePw('password_confirmation', 'eye2')">
                        <span id="eye2">Show</span>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit">Reset Password</button>
        </form>

        <div class="form-footer">
            Remember your password? <a href="{{ route('login') }}">Sign in here</a>
        </div>
    </div>

</div>

<script>
function togglePw(inputId, labelId) {
    const input = document.getElementById(inputId);
    const label = document.getElementById(labelId);
    if (input.type === 'password') {
        input.type = 'text';
        label.textContent = 'Hide';
    } else {
        input.type = 'password';
        label.textContent = 'Show';
    }
}

function checkStrength(val) {
    const fill  = document.getElementById('strengthFill');
    const label = document.getElementById('strengthLabel');
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const levels = [
        { width: '0%',   color: '#E2E8F0', text: '' },
        { width: '25%',  color: '#ef4444', text: 'Weak' },
        { width: '50%',  color: '#f59e0b', text: 'Fair' },
        { width: '75%',  color: '#3b82f6', text: 'Good' },
        { width: '100%', color: '#22c55e', text: 'Strong' },
    ];

    const lvl = val.length === 0 ? levels[0] : levels[score] || levels[1];
    fill.style.width = lvl.width;
    fill.style.background = lvl.color;
    label.textContent = lvl.text;
    label.style.color  = lvl.color;
}
</script>

</body>
</html>