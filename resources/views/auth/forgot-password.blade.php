<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password – MSWDO</title>
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
            max-width: 860px;
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
        .steps-list { list-style: none; padding: 0; margin: 0; }
        .steps-list li {
            display: flex; align-items: flex-start; gap: 12px;
            font-size: 0.87rem; opacity: 0.85; margin-bottom: 14px;
        }
        .step-num {
            flex-shrink: 0;
            width: 24px; height: 24px; border-radius: 50%;
            background: rgba(253,185,19,0.2);
            border: 1px solid rgba(253,185,19,0.45);
            color: #FDB913; font-size: 0.75rem; font-weight: 800;
            display: flex; align-items: center; justify-content: center;
        }
        .brand-back {
            display: inline-flex; align-items: center; gap: 6px;
            color: rgba(255,255,255,0.70); font-size: 0.85rem;
            text-decoration: none; margin-top: 34px;
            transition: color 0.2s ease;
        }
        .brand-back:hover { color: #FDB913; }
        .back-arrow { font-size: 1rem; }

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

        /* Inputs */
        .form-label { font-weight: 600; font-size: 0.88rem; color: #334155; margin-bottom: 6px; }
        .form-control {
            border-radius: 12px;
            border: 2px solid #E2E8F0;
            padding: 12px 16px;
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

        /* Button */
        .btn-submit {
            background: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
            border: none; color: white;
            padding: 13px; border-radius: 12px;
            font-size: 0.95rem; font-weight: 700;
            width: 100%; letter-spacing: 0.01em;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(44,62,143,0.28);
        }
        .btn-submit:active { transform: translateY(0); }

        .form-footer {
            text-align: center; margin-top: 20px;
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

        <h2>Reset Your<br><span>Password</span></h2>
        <div class="brand-divider"></div>
        <p>
            Enter the email address linked to your account and we'll
            send you a secure password reset link right away.
        </p>

        <ul class="steps-list">
            <li>
                <span class="step-num">1</span>
                <span>Enter your registered email address below.</span>
            </li>
            <li>
                <span class="step-num">2</span>
                <span>Check your inbox for the reset link we send.</span>
            </li>
            <li>
                <span class="step-num">3</span>
                <span>Click the link and create a new secure password.</span>
            </li>
        </ul>

        <a href="{{ route('login') }}" class="brand-back">
            <span class="back-arrow">&#8592;</span> Back to Login
        </a>
    </div>

    <!-- RIGHT: Form -->
    <div class="form-panel">
        <h3>Forgot Password?</h3>
        <p class="form-sub">We'll email you a reset link to get back in.</p>

        @if(session('status'))
            <div class="alert alert-success mb-3">{{ session('status') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mb-3">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mb-3">
                @foreach($errors->all() as $error)
                    <p class="mb-0">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="form-label">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    placeholder="you@example.com"
                    value="{{ old('email') }}"
                    required autofocus>
            </div>

            <button type="submit" class="btn-submit">Send Reset Link</button>
        </form>

        <div class="form-footer">
            Remember your password? <a href="{{ route('login') }}">Sign in here</a>
        </div>
    </div>

</div>

</body>
</html>