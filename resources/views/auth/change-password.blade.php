<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Change Password – MSWDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
html, body { overscroll-behavior: none; margin: 0; padding: 0; }
:root {
    --primary-blue: #2C3E8F;
    --secondary-yellow: #FDB913;
    --primary-gradient: linear-gradient(135deg, #2C3E8F 0%, #1A2A5C 100%);
    --success: #16a34a;
    --danger: #dc3545;
}
body {
    font-family: 'Inter', sans-serif;
    background: var(--primary-gradient);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 32px 16px;
}
.auth-card {
    background: white;
    border-radius: 24px;
    box-shadow: 0 32px 80px rgba(0,0,0,0.28);
    max-width: 500px;
    width: 100%;
    padding: 40px;
}
.form-title { font-size: 1.8rem; font-weight: 900; color: #1E293B; margin-bottom: 8px; text-align: center; }
.form-subtitle { font-size: 0.9rem; color: #64748b; margin-bottom: 24px; text-align: center; }
.field-label { font-size: 0.8rem; font-weight: 700; color: #374151; margin-bottom: 5px; display: block; }
.field-label .req { color: #dc3545; }
.form-input {
    width: 100%;
    border: 1.5px solid #E2E8F0;
    border-radius: 11px;
    padding: 10px 13px;
    font-size: 0.88rem;
    transition: border-color 0.2s, box-shadow 0.2s;
    outline: none;
}
.form-input:focus { border-color: var(--primary-blue); box-shadow: 0 0 0 3px rgba(44,62,143,0.10); }
.form-input.valid { border-color: var(--success); }
.form-input.invalid { border-color: var(--danger); }
.field-msg { font-size: 0.73rem; margin-top: 3px; min-height: 16px; }
.field-msg.ok { color: var(--success); }
.field-msg.err { color: var(--danger); }
.field-msg.hint { color: #94a3b8; }
.pw-wrap { position: relative; }
.pw-toggle {
    position: absolute;
    right: 11px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    color: #94a3b8;
    opacity: 0.6;
    display: flex;
    align-items: center;
    padding: 0;
}
.pw-toggle:hover { opacity: 1; color: #2C3E8F; }
.pw-wrap .form-input { padding-right: 38px; }
/* Hide browser built-in password reveal button */
input[type="password"]::-ms-reveal,
input[type="password"]::-ms-clear { display: none; }
input::-webkit-credentials-auto-fill-button { display: none; }
.pw-strength { margin-top: 7px; }
.pw-bars { display: flex; gap: 4px; margin-bottom: 5px; }
.pw-bar { flex: 1; height: 4px; border-radius: 2px; background: #e2e8f0; transition: background .3s; }
.pw-bar.weak { background: #ef4444; }
.pw-bar.fair { background: #f59e0b; }
.pw-bar.good { background: #3b82f6; }
.pw-bar.strong { background: var(--success); }
.pw-criteria { display: grid; grid-template-columns: 1fr 1fr; gap: 2px 12px; }
.pw-crit { font-size: 0.68rem; color: #94a3b8; display: flex; align-items: center; gap: 5px; transition: color 0.3s; }
.pw-crit .dot { width: 6px; height: 6px; border-radius: 50%; background: #e2e8f0; flex-shrink: 0; transition: background 0.3s; }
.pw-crit.met .dot { background: var(--success); }
.pw-crit.met { color: var(--success); }
.pw-crit.unmet .dot { background: var(--danger); }
.pw-crit.unmet { color: var(--danger); }
.btn-submit {
    width: 100%;
    background: var(--primary-gradient);
    color: white;
    border: none;
    border-radius: 11px;
    padding: 13px;
    font-weight: 800;
    font-size: 0.93rem;
    cursor: pointer;
    transition: all 0.3s;
    margin-top: 20px;
}
.btn-submit:hover { box-shadow: 0 10px 28px rgba(44,62,143,0.30); transform: translateY(-1px); }
.alert-styled { border-radius: 11px; font-size: 0.83rem; padding: 11px 15px; margin-bottom: 14px; }
.alert-success-c { background: #d4edda; border-left: 4px solid var(--success); color: #155724; }
.alert-danger-c { background: #fce8e8; border-left: 4px solid var(--danger); color: #721c24; }
.info-box { background: #F0F5FF; border: 1px solid rgba(44,62,143,0.12); border-left: 4px solid var(--primary-blue); border-radius: 11px; padding: 12px 16px; margin-bottom: 20px; font-size: 0.82rem; color: var(--primary-blue); line-height: 1.65; }
    </style>
</head>
<body>
    <div class="auth-card">
        <div class="form-title">Set New Password</div>
        <p class="form-subtitle">Create a strong password for your account</p>

        @if(session('success'))
            <div class="alert-styled alert-success-c">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert-styled alert-danger-c">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="info-box">
            <strong>Important:</strong> Your temporary password has been sent to your email. Please create a new secure password below.
            After changing your password, your uploaded valid ID will still need admin approval before you can login.
        </div>

        <form method="POST" action="{{ route('password.change.submit') }}" id="changePasswordForm">
            @csrf

            <div style="margin-bottom: 16px;">
                <label class="field-label" for="password">New Password <span class="req">*</span></label>
                <div class="pw-wrap">
                    <input type="password" id="password" name="password" class="form-input" placeholder="Min. 8 characters" oninput="checkPassword()">
                    <button type="button" class="pw-toggle" onclick="togglePw('password', this)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                        </svg>
                    </button>
                </div>
                <div class="pw-strength" id="pw_strength_wrap" style="display:none;">
                    <div class="pw-bars">
                        <div class="pw-bar" id="s1"></div>
                        <div class="pw-bar" id="s2"></div>
                        <div class="pw-bar" id="s3"></div>
                        <div class="pw-bar" id="s4"></div>
                    </div>
                    <div class="pw-criteria">
                        <div class="pw-crit" id="c_len"><div class="dot"></div>8+ characters</div>
                        <div class="pw-crit" id="c_upper"><div class="dot"></div>Uppercase (A–Z)</div>
                        <div class="pw-crit" id="c_lower"><div class="dot"></div>Lowercase (a–z)</div>
                        <div class="pw-crit" id="c_num"><div class="dot"></div>Number (0–9)</div>
                        <div class="pw-crit" id="c_sym"><div class="dot"></div>Special char (!@#...)</div>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label class="field-label" for="password_confirmation">Confirm Password <span class="req">*</span></label>
                <div class="pw-wrap">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Re-enter password" oninput="checkConfirm()">
                    <button type="button" class="pw-toggle" onclick="togglePw('password_confirmation', this)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                        </svg>
                    </button>
                </div>
                <div id="msg_confirm" class="field-msg hint">Must match the password above.</div>
            </div>

            <button type="submit" class="btn-submit">Change Password &#8594;</button>
        </form>
    </div>

    <script>
    function checkPassword() {
        const v = document.getElementById('password').value;
        const wrap = document.getElementById('pw_strength_wrap');
        wrap.style.display = v.length ? 'block' : 'none';

        const cLen = v.length >= 8;
        const cUpper = /[A-Z]/.test(v);
        const cLower = /[a-z]/.test(v);
        const cNum = /\d/.test(v);
        const cSym = /[@$!%*?&#_\-\.]/.test(v);

        setCrit('c_len', cLen);
        setCrit('c_upper', cUpper);
        setCrit('c_lower', cLower);
        setCrit('c_num', cNum);
        setCrit('c_sym', cSym);

        const score = [cLen, cUpper, cLower, cNum, cSym].filter(Boolean).length;
        const cls = score <= 1 ? 'weak' : score === 2 ? 'fair' : score === 3 ? 'good' : score === 4 ? 'good' : 'strong';

        ['s1','s2','s3','s4'].forEach((b,i) => {
            const el = document.getElementById(b);
            el.className = 'pw-bar';
            if (i < score) el.classList.add(cls);
        });

        const inp = document.getElementById('password');
        inp.classList.remove('valid','invalid');
        if (score >= 5 && cLen) inp.classList.add('valid');
        else if (v.length) inp.classList.add('invalid');

        checkConfirm();
        updateSubmitButton();
    }

    function setCrit(id, met) {
        const el = document.getElementById(id);
        if (met) el.classList.add('met'); else el.classList.remove('met');
    }

    function checkConfirm() {
        const pw = document.getElementById('password').value;
        const cpw = document.getElementById('password_confirmation').value;
        const msg = document.getElementById('msg_confirm');
        const inp = document.getElementById('password_confirmation');
        
        if (!cpw) {
            msg.textContent = 'Must match the password above.';
            msg.className = 'field-msg hint';
            inp.classList.remove('valid','invalid');
            updateSubmitButton();
            return;
        }
        
        if (pw === cpw) {
            msg.textContent = '✓ Passwords match!';
            msg.className = 'field-msg ok';
            inp.classList.add('valid');
            inp.classList.remove('invalid');
        } else {
            msg.textContent = '✗ Passwords do not match.';
            msg.className = 'field-msg err';
            inp.classList.add('invalid');
            inp.classList.remove('valid');
        }
        updateSubmitButton();
    }

    function updateSubmitButton() {
        const pw = document.getElementById('password').value;
        const cpw = document.getElementById('password_confirmation').value;
        const btn = document.querySelector('.btn-submit');
        
        const cLen = pw.length >= 8;
        const cUpper = /[A-Z]/.test(pw);
        const cLower = /[a-z]/.test(pw);
        const cNum = /\d/.test(pw);
        const cSym = /[@$!%*?&#_\-\.]/.test(pw);
        const allMet = cLen && cUpper && cLower && cNum && cSym;
        const passwordsMatch = pw === cpw && cpw.length > 0;
        
        // Always keep button enabled
        btn.disabled = false;
        btn.style.opacity = '1';
        btn.style.cursor = 'pointer';
    }

    function togglePw(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const svg = btn.querySelector('svg');
        if (input.type === 'password') {
            input.type = 'text';
            btn.style.opacity = '1';
            svg.innerHTML = '<path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/><path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/><path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709z"/><path fill-rule="evenodd" d="M13.646 14.354l-12-12 .708-.708 12 12-.708.708z"/>';
        } else {
            input.type = 'password';
            btn.style.opacity = '0.6';
            svg.innerHTML = '<path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>';
        }
    }

    // Initialize button state on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateSubmitButton();
    });

    // Prevent form submission if requirements not met
    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        const pw = document.getElementById('password').value;
        const cpw = document.getElementById('password_confirmation').value;
        
        const cLen = pw.length >= 8;
        const cUpper = /[A-Z]/.test(pw);
        const cLower = /[a-z]/.test(pw);
        const cNum = /\d/.test(pw);
        const cSym = /[@$!%*?&#_\-\.]/.test(pw);
        const allMet = cLen && cUpper && cLower && cNum && cSym;
        const passwordsMatch = pw === cpw && cpw.length > 0;
        
        if (!allMet || !passwordsMatch) {
            e.preventDefault();
            
            // Highlight unmet requirements in red
            highlightUnmet('c_len', cLen);
            highlightUnmet('c_upper', cUpper);
            highlightUnmet('c_lower', cLower);
            highlightUnmet('c_num', cNum);
            highlightUnmet('c_sym', cSym);
            
            // Show password strength if hidden
            if (pw.length) {
                document.getElementById('pw_strength_wrap').style.display = 'block';
            }
            
            return false;
        }
    });
    
    function highlightUnmet(id, isMet) {
        const el = document.getElementById(id);
        if (!isMet) {
            el.classList.add('unmet');
            setTimeout(() => el.classList.remove('unmet'), 3000);
        }
    }
    </script>
</body>
</html>
