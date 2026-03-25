<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – MSWDO Analysis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
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

        /* Decorative background blobs */
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

        /* ── CARD ── */
        .login-wrapper {
            width: 100%;
            max-width: 920px;
            display: flex;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0,0,0,0.4);
            position: relative; z-index: 1;
        }

        /* ── LEFT SIDE – Branding Panel ── */
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
        .brand-logo .logo-icon {
            width: 56px; height: 56px; border-radius: 16px;
            background: rgba(253,185,19,0.18);
            border: 2px solid rgba(253,185,19,0.35);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.7rem; color: #FDB913;
        }
        .brand-logo .logo-text {
            font-size: 1.3rem; font-weight: 800; line-height: 1.2;
        }
        .brand-logo .logo-sub {
            font-size: 0.78rem; opacity: 0.7; font-weight: 400; margin-top: 2px;
        }
        .brand-panel h2 {
            font-size: 1.85rem; font-weight: 800; line-height: 1.25;
            margin-bottom: 14px;
        }
        .brand-panel h2 span { color: #FDB913; }
        .brand-divider {
            width: 48px; height: 4px;
            background: #FDB913; border-radius: 2px;
            margin-bottom: 18px;
        }
        .brand-panel p {
            font-size: 0.93rem; opacity: 0.80; line-height: 1.7; margin-bottom: 32px;
        }
        .brand-features { list-style: none; padding: 0; margin: 0; }
        .brand-features li {
            display: flex; align-items: center; gap: 10px;
            font-size: 0.88rem; opacity: 0.85; margin-bottom: 11px;
        }
        .brand-features li i {
            color: #FDB913; font-size: 1rem; flex-shrink: 0;
        }
        .brand-back {
            display: inline-flex; align-items: center; gap: 6px;
            color: rgba(255,255,255,0.75); font-size: 0.85rem;
            text-decoration: none; margin-top: 36px;
            transition: color 0.2s ease;
        }
        .brand-back:hover { color: #FDB913; }

        /* ── RIGHT SIDE – Form Panel ── */
        .form-panel {
            flex: 1;
            background: white;
            padding: 54px 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .form-panel h3 {
            font-size: 1.55rem; font-weight: 800;
            color: #1e293b; margin-bottom: 5px;
        }
        .form-panel .form-sub {
            font-size: 0.88rem; color: #64748b; margin-bottom: 32px;
        }

        /* Alerts */
        .alert { border-radius: 10px; border: none; font-size: 0.88rem; padding: 12px 16px; }
        .alert-success { background: #e8f5e9; color: #1e7e34; }
        .alert-danger  { background: #fce8e8; color: #C41E24; }

        /* Form inputs */
        .form-label {
            font-weight: 600; font-size: 0.88rem;
            color: #334155; margin-bottom: 6px;
        }
        .input-wrapper { position: relative; }
        .input-wrapper .icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8; font-size: 1rem; pointer-events: none;
        }
        .form-control {
            border-radius: 12px;
            border: 2px solid #E2E8F0;
            padding: 11px 14px 11px 42px;
            font-size: 0.92rem; font-family: 'Inter', sans-serif;
            transition: all 0.25s ease;
            background: #F8FAFC;
        }
        .form-control:focus {
            border-color: #2C3E8F;
            background: white;
            box-shadow: 0 0 0 4px rgba(44,62,143,0.08);
            outline: none;
        }
        .form-control::placeholder { color: #CBD5E1; }

        /* Toggle password */
        .toggle-pw {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            color: #94a3b8; cursor: pointer; font-size: 1rem;
            background: none; border: none; padding: 0;
            transition: color 0.2s;
        }
        .toggle-pw:hover { color: #2C3E8F; }

        /* Remember me */
        .form-check-input:checked {
            background-color: #2C3E8F;
            border-color: #2C3E8F;
        }
        .form-check-label { font-size: 0.88rem; color: #475569; }

        /* Submit button */
        .btn-submit {
            background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            border: none; color: white;
            padding: 13px; border-radius: 12px;
            font-size: 0.95rem; font-weight: 700;
            width: 100%; letter-spacing: 0.01em;
            transition: all 0.3s ease;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(44,62,143,0.28);
            color: white;
        }
        .btn-submit:active { transform: translateY(0); }

        /* Footer links */
        .form-footer {
            text-align: center; margin-top: 22px;
            font-size: 0.86rem; color: #64748b;
        }
        .form-footer a {
            color: #2C3E8F; font-weight: 600; text-decoration: none;
            transition: color 0.2s;
        }
        .form-footer a:hover { color: #FDB913; text-decoration: underline; }
        .form-footer .divider {
            display: flex; align-items: center; gap: 10px;
            color: #CBD5E1; margin: 18px 0;
            font-size: 0.8rem;
        }
        .form-footer .divider::before,
        .form-footer .divider::after {
            content: ''; flex: 1;
            height: 1px; background: #E2E8F0;
        }

        /* Responsive */
        @media (max-width: 700px) {
            .brand-panel { display: none; }
            .login-wrapper { max-width: 440px; }
            .form-panel { padding: 40px 28px; }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <!-- ── LEFT: BRANDING ── -->
    <div class="brand-panel">
        <div class="brand-logo">
            <img src="/images/mswd-logo.png" alt="MSWD Logo" style="width:64px;height:64px;object-fit:contain;margin-bottom:4px;">
            <div>
                <div class="logo-text">MSWDO</div>
                <div class="logo-sub">Municipal Social Welfare & Development</div>
            </div>
        </div>

        <h2>Welcome to the<br><span>Analysis System</span></h2>
        <div class="brand-divider"></div>
        <p>
            Access population data, demographic insights, and program reports for
            Magdalena, Liliw, and Majayjay — all in one place.
        </p>

        <ul class="brand-features">
            <li><i class="bi bi-bar-chart-fill"></i> Comparative Analysis Charts</li>
            <li><i class="bi bi-people-fill"></i> Demographic Data & Maps</li>
            <li><i class="bi bi-shield-check-fill"></i> Secure Staff Access</li>
            <li><i class="bi bi-heart-fill"></i> MSWDO Program Overviews</li>
        </ul>

        <a href="/analysis" class="brand-back">
            <i class="bi bi-arrow-left"></i> Back to Programs
        </a>
    </div>

    <!-- ── RIGHT: FORM ── -->
    <div class="form-panel">
        <h3>Sign In</h3>
        <p class="form-sub">Enter your credentials to access the dashboard.</p>

        @if(session('success'))
            <div class="alert alert-success mb-3">
                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mb-3">
                <i class="bi bi-exclamation-circle me-1"></i> {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mb-3">
                <i class="bi bi-exclamation-circle me-1"></i>
                @foreach($errors->all() as $error)
                    <span>{{ $error }}</span><br>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label class="form-label">Username or Email</label>
                <div class="input-wrapper">
                    <i class="bi bi-person icon"></i>
                    <input
                        type="text"
                        name="login"
                        class="form-control"
                        placeholder="Enter username or email"
                        value="{{ old('login') }}"
                        required autofocus
                        autocomplete="username">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-wrapper">
                    <i class="bi bi-lock icon"></i>
                    <input
                        type="password"
                        name="password"
                        id="passwordInput"
                        class="form-control"
                        placeholder="Enter password"
                        required
                        autocomplete="current-password">
                    <button type="button" class="toggle-pw" onclick="togglePassword()" id="toggleIcon">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>
                <a href="{{ route('password.request') }}" style="font-size:0.85rem;color:#2C3E8F;font-weight:600;text-decoration:none;">
                    Forgot password?
                </a>
            </div>

            <button type="submit" class="btn-submit">
                <i class="bi bi-box-arrow-in-right"></i> Sign In
            </button>
        </form>

        <div class="form-footer">
            <div class="divider">or</div>
            Don't have an account?
            <a href="{{ route('register') }}">Create one here</a>
        </div>
    </div>

</div>

<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>

</body>
</html>