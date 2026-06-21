<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">
    <title>Sign In · Borobhai.com</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bb-primary: #4f46e5;
            --bb-primary-dark: #4338ca;
            --bb-primary-light: #818cf8;
            --bb-ink: #0f172a;
            --bb-muted: #64748b;
            --bb-line: #e2e8f0;
            --bb-bg: #f8fafc;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif !important;
            background: var(--bb-bg);
            min-height: 100vh;
            color: var(--bb-ink);
            overflow-x: hidden;
        }

        .auth-wrap { display: flex; min-height: 100vh; }

        /* ===== LEFT BRANDING PANEL ===== */
        .auth-left {
            flex: 1.05;
            position: relative;
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 45%, #7c73f0 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 56px 54px;
            overflow: hidden;
        }
        /* subtle moving glow orbs */
        .auth-left::before, .auth-left::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: .45;
        }
        .auth-left::before {
            width: 360px; height: 360px;
            background: #a78bfa;
            top: -120px; right: -80px;
            animation: floatOrb 9s ease-in-out infinite;
        }
        .auth-left::after {
            width: 300px; height: 300px;
            background: #4338ca;
            bottom: -100px; left: -60px;
            animation: floatOrb 11s ease-in-out infinite reverse;
        }
        @keyframes floatOrb {
            0%,100% { transform: translate(0,0) scale(1); }
            50%     { transform: translate(20px,30px) scale(1.08); }
        }

        .auth-brand {
            display: flex; align-items: center; gap: 12px;
            position: relative; z-index: 2;
        }
        .auth-brand-icon {
            width: 48px; height: 48px; border-radius: 14px;
            background: rgba(255,255,255,.16);
            backdrop-filter: blur(8px);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            border: 1px solid rgba(255,255,255,.2);
        }
        .auth-brand-name { font-size: 1.4rem; font-weight: 800; letter-spacing: -.5px; }

        .auth-hero { position: relative; z-index: 2; }
        .auth-hero h1 {
            font-size: 2.5rem; font-weight: 800; line-height: 1.15;
            letter-spacing: -1px; margin-bottom: 18px;
        }
        .auth-hero p {
            font-size: 1.02rem; line-height: 1.6; color: rgba(255,255,255,.82);
            max-width: 420px;
        }

        /* floating feature cards */
        .auth-features { position: relative; z-index: 2; display: flex; flex-direction: column; gap: 14px; }
        .auth-feature {
            display: flex; align-items: center; gap: 14px;
            background: rgba(255,255,255,.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,.16);
            border-radius: 16px;
            padding: 15px 18px;
            opacity: 0;
            transform: translateY(16px);
            animation: cardRise .6s ease forwards;
        }
        .auth-feature:nth-child(1) { animation-delay: .25s; }
        .auth-feature:nth-child(2) { animation-delay: .45s; }
        .auth-feature:nth-child(3) { animation-delay: .65s; }
        @keyframes cardRise { to { opacity: 1; transform: translateY(0); } }
        .auth-feature-icon {
            width: 42px; height: 42px; border-radius: 11px; flex-shrink: 0;
            background: rgba(255,255,255,.18);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.15rem;
        }
        .auth-feature-text h4 { font-size: .95rem; font-weight: 700; margin-bottom: 2px; }
        .auth-feature-text p { font-size: .8rem; color: rgba(255,255,255,.72); line-height: 1.4; }

        /* ===== RIGHT FORM PANEL (PREMIUM DARK) ===== */
        .auth-right {
            flex: 1;
            display: flex; align-items: center; justify-content: center;
            padding: 40px 24px;
            background: #0b1020;
            position: relative;
            overflow: hidden;
        }
        /* subtle dark glow behind form */
        .auth-right::before {
            content: '';
            position: absolute;
            width: 480px; height: 480px; border-radius: 50%;
            background: radial-gradient(circle, rgba(79,70,229,.18) 0%, transparent 70%);
            top: 50%; left: 50%; transform: translate(-50%,-50%);
            pointer-events: none;
        }
        .auth-form-box {
            width: 100%; max-width: 410px;
            position: relative; z-index: 2;
            animation: formIn .55s ease;
        }
        @keyframes formIn { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }

        .auth-form-head { margin-bottom: 28px; }
        .auth-form-head .mobile-brand {
            display: none; align-items: center; gap: 10px; margin-bottom: 22px;
        }
        .auth-form-head .mobile-brand-icon {
            width: 42px; height: 42px; border-radius: 12px;
            background: var(--bb-primary); color: #fff;
            display: flex; align-items: center; justify-content: center; font-size: 1.3rem;
        }
        .auth-form-head .mobile-brand-name { font-size: 1.25rem; font-weight: 800; color: #fff; }
        .auth-form-head h2 { font-size: 1.7rem; font-weight: 800; letter-spacing: -.5px; margin-bottom: 6px; color: #fff; }
        .auth-form-head p { font-size: .92rem; color: #94a3b8; }

        /* social buttons (dark) */
        .auth-social { display: flex; flex-direction: column; gap: 11px; margin-bottom: 22px; }
        .btn-social {
            width: 100%; padding: 12px; border-radius: 12px;
            border: 1.5px solid rgba(255,255,255,.1); background: rgba(255,255,255,.04);
            font-size: .92rem; font-weight: 600; color: #e2e8f0;
            cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;
            transition: all .2s ease; text-decoration: none;
        }
        .btn-social:hover { background: rgba(255,255,255,.08); border-color: rgba(255,255,255,.2); transform: translateY(-1px); }
        .btn-social img { width: 19px; height: 19px; }
        .btn-social.fb-btn { color: #60a5fa; }
        .btn-social.fb-btn i { font-size: 19px; }

        .auth-divider { display: flex; align-items: center; gap: 14px; margin: 4px 0 22px; color: #64748b; font-size: .78rem; font-weight: 500; }
        .auth-divider::before, .auth-divider::after { content: ''; flex: 1; height: 1px; background: rgba(255,255,255,.08); }

        /* form fields (dark + pro) */
        .form-label { display: block; font-size: .84rem; font-weight: 600; color: #cbd5e1; margin-bottom: 8px; }
        .input-wrap { position: relative; display: flex; align-items: center; }
        .input-icon { position: absolute; left: 16px; color: #64748b; font-size: 1rem; z-index: 2; transition: color .2s ease; }
        .form-input {
            width: 100%; padding: 14px 16px 14px 46px; font-size: .94rem;
            background: rgba(255,255,255,.04);
            border: 1.5px solid rgba(255,255,255,.1);
            border-radius: 12px; color: #f1f5f9;
            transition: all .2s ease;
        }
        .form-input::placeholder { color: #475569; }
        .form-input:focus {
            outline: none;
            border-color: var(--bb-primary-light);
            background: rgba(255,255,255,.06);
            box-shadow: 0 0 0 4px rgba(99,102,241,.18);
        }
        .input-wrap:focus-within .input-icon { color: var(--bb-primary-light); }
        .toggle-eye { position: absolute; right: 16px; color: #64748b; cursor: pointer; z-index: 2; transition: color .2s ease; }
        .toggle-eye:hover { color: #cbd5e1; }

        .error-text { color: #f87171; font-size: .79rem; font-weight: 500; margin-top: 6px; display: none; align-items: center; gap: 5px; }

        .form-row-between { display: flex; justify-content: space-between; align-items: center; }
        .forgot-link { font-size: .82rem; color: var(--bb-primary-light); text-decoration: none; font-weight: 600; }
        .forgot-link:hover { text-decoration: underline; }

        .check-row { display: flex; align-items: center; gap: 8px; margin: 18px 0 22px; }
        .check-row input { width: 16px; height: 16px; cursor: pointer; accent-color: var(--bb-primary); }
        .check-row label { font-size: .85rem; color: #94a3b8; cursor: pointer; }

        .btn-submit {
            width: 100%; padding: 14px; border: none; border-radius: 12px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff; font-size: .96rem; font-weight: 700;
            cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;
            box-shadow: 0 4px 18px rgba(79,70,229,.4); transition: all .2s ease;
        }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 24px rgba(79,70,229,.55); }
        .btn-submit:disabled { opacity: .7; cursor: default; transform: none; }

        .auth-foot { text-align: center; margin-top: 24px; font-size: .9rem; color: #94a3b8; }
        .auth-foot a { color: var(--bb-primary-light); text-decoration: none; font-weight: 700; }
        .auth-foot a:hover { text-decoration: underline; }

        /* alert box (dark) */
        .auth-alert {
            border-radius: 12px; font-size: .87rem; padding: 12px 16px; line-height: 1.45;
            margin-bottom: 20px; display: none; align-items: flex-start; gap: 8px;
        }
        .auth-alert.show { display: flex; }
        .auth-alert.err { background: rgba(220,38,38,.12); color: #fca5a5; border: 1px solid rgba(248,113,113,.3); }
        .auth-alert.ok  { background: rgba(5,150,105,.12); color: #6ee7b7; border: 1px solid rgba(110,231,183,.3); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .auth-wrap { flex-direction: column; }
            .auth-left {
                flex: none; padding: 30px 28px;
                min-height: auto;
            }
            .auth-hero { display: none; }
            .auth-features { display: none; }
            .auth-left { padding: 22px 28px; }
            .auth-form-head .mobile-brand { display: none; }
        }
        @media (max-width: 600px) {
            .auth-left { display: none; }
            .auth-right { padding: 32px 20px; min-height: 100vh; }
            .auth-form-head .mobile-brand { display: flex; }
        }
    </style>
</head>
<body>

<div class="auth-wrap">

    {{-- ===== LEFT: Branding Panel ===== --}}
    <div class="auth-left">
        <div class="auth-brand">
            <div class="auth-brand-icon"><i class="fa-solid fa-graduation-cap"></i></div>
            <span class="auth-brand-name">Borobhai.com</span>
        </div>

        <div class="auth-hero">
            <h1>Welcome back to<br>your network.</h1>
            <p>Reconnect with seniors and alumni, discover opportunities, and grow your professional journey — all in one place.</p>
        </div>

        <div class="auth-features">
            <div class="auth-feature">
                <div class="auth-feature-icon"><i class="fa-solid fa-people-arrows"></i></div>
                <div class="auth-feature-text">
                    <h4>Connect with Alumni</h4>
                    <p>Build meaningful connections with seniors and graduates.</p>
                </div>
            </div>
            <div class="auth-feature">
                <div class="auth-feature-icon"><i class="fa-solid fa-briefcase"></i></div>
                <div class="auth-feature-text">
                    <h4>Find Opportunities</h4>
                    <p>Explore jobs and internships shared by your community.</p>
                </div>
            </div>
            <div class="auth-feature">
                <div class="auth-feature-icon"><i class="fa-solid fa-file-lines"></i></div>
                <div class="auth-feature-text">
                    <h4>Share Knowledge</h4>
                    <p>Publish research, theses, and resources with everyone.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== RIGHT: Login Form ===== --}}
    <div class="auth-right">
        <div class="auth-form-box">

            <div class="auth-form-head">
                <div class="mobile-brand">
                    <div class="mobile-brand-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                    <span class="mobile-brand-name">Borobhai.com</span>
                </div>
                <h2>Sign in</h2>
                <p>Welcome back! Please enter your details.</p>
            </div>

            {{-- Global alert (session error / suspended) --}}
            @php
                $hasError = session('suspended_error') || session('error');
                $errorMessage = session('suspended_error') ?? session('error');
            @endphp
            <div id="globalAlert" class="auth-alert err {{ $hasError ? 'show' : '' }}" role="alert">
                @if($hasError)
                    <i class="fa-solid fa-triangle-exclamation" style="margin-top:2px;"></i>
                    <span>{!! $errorMessage !!}</span>
                @endif
            </div>

            {{-- Social login --}}
            <div class="auth-social">
                <a href="#" class="btn-social" onclick="return socialLogin(event, 'google')">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google"> Continue with Google
                </a>
                <a href="#" class="btn-social fb-btn" onclick="return socialLogin(event, 'facebook')">
                    <i class="fa-brands fa-facebook"></i> Continue with Facebook
                </a>
            </div>

            <div class="auth-divider">or sign in with email</div>

            <form id="ajaxLoginForm" method="POST" action="{{ route('login') }}">
                @csrf

                <div style="margin-bottom:18px;">
                    <label class="form-label">Email Address</label>
                    <div class="input-wrap">
                        <i class="fa-regular fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email" class="form-input" placeholder="name@example.com" required autocomplete="username">
                    </div>
                    <div class="error-text" id="emailError"></div>
                </div>

                <div style="margin-bottom:4px;">
                    <div class="form-row-between" style="margin-bottom:7px;">
                        <label class="form-label" style="margin-bottom:0;">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                        @endif
                    </div>
                    <div class="input-wrap">
                        <i class="fa-regular fa-lock-keyhole input-icon"></i>
                        <input type="password" id="password" name="password" class="form-input" required autocomplete="current-password" style="padding-right:44px;">
                        <span class="toggle-eye" id="togglePassword"><i class="fa-regular fa-eye" id="eyeIcon"></i></span>
                    </div>
                    <div class="error-text" id="passwordError"></div>
                </div>

                <div class="check-row">
                    <input type="checkbox" name="remember" id="remember_me">
                    <label for="remember_me">Keep me logged in</label>
                </div>

                <button type="submit" id="loginBtn" class="btn-submit">
                    <span id="btnText">Sign In</span>
                    <div id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" style="width:1.1rem;height:1.1rem;"></div>
                </button>
            </form>

            <div class="auth-foot">Don't have an account? <a href="{{ route('register') }}">Create one</a></div>

        </div>
    </div>

</div>

<script>
// ===== Social login → backend redirect =====
// login page এ role select নেই — তাই role ছাড়াই redirect।
// নতুন user হলে backend নিজেই Choose Role পেজে পাঠাবে (Option B)।
function socialLogin(e, provider) {
    e.preventDefault();
    window.location.href = `/auth/${provider}/redirect`;
    return false;
}

// ===== Password toggle =====
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');
const eyeIcon = document.getElementById('eyeIcon');
togglePassword.addEventListener('click', function () {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    eyeIcon.classList.toggle('fa-eye');
    eyeIcon.classList.toggle('fa-eye-slash');
    eyeIcon.style.color = type === 'text' ? '#4f46e5' : '#94a3b8';
});

// ===== AJAX login (logic অপরিবর্তিত — শুধু UI সিলেক্টর আপডেট) =====
document.getElementById('ajaxLoginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const loginBtn = document.getElementById('loginBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    const globalAlert = document.getElementById('globalAlert');

    document.querySelectorAll('.error-text').forEach(el => { el.style.display = 'none'; el.innerHTML = ''; });
    globalAlert.className = 'auth-alert err';
    globalAlert.innerHTML = '';

    loginBtn.disabled = true;
    btnText.innerText = 'Verifying...';
    btnSpinner.classList.remove('d-none');

    fetch(form.action, {
        method: "POST",
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: new FormData(form)
    })
    .then(async response => {
        const isJson = response.headers.get('content-type')?.includes('application/json');
        const data = isJson ? await response.json() : null;
        return { status: response.status, body: data };
    })
    .then(res => {
        loginBtn.disabled = false;
        btnText.innerText = 'Sign In';
        btnSpinner.classList.add('d-none');

        if (res.status === 200) {
            globalAlert.className = 'auth-alert ok show';
            globalAlert.innerHTML = `<i class="fa-solid fa-circle-check" style="margin-top:2px;"></i><span>Logged in successfully!</span>`;
            setTimeout(() => {
                window.location.href = (res.body && res.body.redirect) ? res.body.redirect : window.location.origin;
            }, 700);

        } else if (res.status === 422 && res.body && res.body.errors) {
            const errors = res.body.errors;
            if (errors.email) {
                const msg = errors.email[0];
                if (msg.includes('Suspended') || msg.includes('Denied') || msg.includes('Access') || msg.includes('Blocked')) {
                    globalAlert.className = 'auth-alert err show';
                    globalAlert.innerHTML = `<i class="fa-solid fa-triangle-exclamation" style="margin-top:2px;"></i><span>${msg}</span>`;
                } else {
                    const emailErr = document.getElementById('emailError');
                    emailErr.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${msg}`;
                    emailErr.style.display = 'flex';
                }
            }
            if (errors.password) {
                const passErr = document.getElementById('passwordError');
                passErr.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${errors.password[0]}`;
                passErr.style.display = 'flex';
            }
        } else {
            globalAlert.className = 'auth-alert err show';
            globalAlert.innerHTML = `<i class="fa-solid fa-circle-xmark" style="margin-top:2px;"></i><span>Error occurred. Status: ${res.status}</span>`;
        }
    })
    .catch(error => {
        loginBtn.disabled = false;
        btnText.innerText = 'Sign In';
        btnSpinner.classList.add('d-none');
        console.error('Error:', error);
    });
});
</script>
</body>
</html>