<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">
    <title>Create Account · Borobhai.online</title>

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
        .auth-right::before {
            content: '';
            position: absolute;
            width: 480px; height: 480px; border-radius: 50%;
            background: radial-gradient(circle, rgba(79,70,229,.18) 0%, transparent 70%);
            top: 50%; left: 50%; transform: translate(-50%,-50%);
            pointer-events: none;
        }
        .auth-form-box {
            width: 100%; max-width: 430px;
            position: relative; z-index: 2;
            animation: formIn .55s ease;
        }
        @keyframes formIn { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }

        .auth-form-head { margin-bottom: 24px; }
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

        .auth-social { display: flex; flex-direction: column; gap: 11px; margin-bottom: 20px; }
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

        .auth-divider { display: flex; align-items: center; gap: 14px; margin: 4px 0 20px; color: #64748b; font-size: .78rem; font-weight: 500; }
        .auth-divider::before, .auth-divider::after { content: ''; flex: 1; height: 1px; background: rgba(255,255,255,.08); }

        .form-label { display: block; font-size: .84rem; font-weight: 600; color: #cbd5e1; margin-bottom: 8px; }
        .input-wrap { position: relative; display: flex; align-items: center; }
        .input-icon { position: absolute; left: 16px; color: #64748b; font-size: 1rem; z-index: 2; transition: color .2s ease; }
        .form-input, .form-select {
            width: 100%; padding: 14px 16px 14px 46px; font-size: .94rem;
            background: rgba(255,255,255,.04);
            border: 1.5px solid rgba(255,255,255,.1);
            border-radius: 12px; color: #f1f5f9;
            transition: all .2s ease;
        }
        .form-input::placeholder { color: #475569; }
        .form-select {
            cursor: pointer; appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat; background-position: right 16px center; background-size: 12px 12px;
        }
        /* dropdown option গুলো dark theme এ readable */
        .form-select option { background: #1a1f33; color: #f1f5f9; }
        .form-select option[disabled] { color: #64748b; }
        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: var(--bb-primary-light);
            background: rgba(255,255,255,.06);
            box-shadow: 0 0 0 4px rgba(99,102,241,.18);
        }
        .input-wrap:focus-within .input-icon { color: var(--bb-primary-light); }
        .toggle-eye { position: absolute; right: 16px; color: #64748b; cursor: pointer; z-index: 2; transition: color .2s ease; }
        .toggle-eye:hover { color: #cbd5e1; }

        .error-text { color: #f87171; font-size: .79rem; font-weight: 500; margin-top: 6px; display: none; align-items: center; gap: 5px; }

        /* role hint (dark) */
        .role-hint {
            font-size: .8rem; color: #c7d2fe; margin-top: 8px; padding: 10px 13px;
            background: rgba(79,70,229,.14); border-radius: 10px; border-left: 3px solid var(--bb-primary-light);
            display: none; line-height: 1.45;
        }
        .role-hint.show { display: block; animation: hintIn .3s ease; }
        @keyframes hintIn { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: translateY(0); } }

        .btn-submit {
            width: 100%; padding: 14px; border: none; border-radius: 12px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff; font-size: .96rem; font-weight: 700;
            cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;
            box-shadow: 0 4px 18px rgba(79,70,229,.4); transition: all .2s ease;
        }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 24px rgba(79,70,229,.55); }
        .btn-submit:disabled { opacity: .7; cursor: default; transform: none; }

        .auth-foot { text-align: center; margin-top: 22px; font-size: .9rem; color: #94a3b8; }
        .auth-foot a { color: var(--bb-primary-light); text-decoration: none; font-weight: 700; }
        .auth-foot a:hover { text-decoration: underline; }

        .auth-alert {
            border-radius: 12px; font-size: .87rem; padding: 12px 16px; line-height: 1.45;
            margin-bottom: 18px; display: none; align-items: flex-start; gap: 8px;
        }
        .auth-alert.show { display: flex; }
        .auth-alert.err { background: rgba(220,38,38,.12); color: #fca5a5; border: 1px solid rgba(248,113,113,.3); }
        .auth-alert.ok  { background: rgba(5,150,105,.12); color: #6ee7b7; border: 1px solid rgba(110,231,183,.3); }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .auth-wrap { flex-direction: column; }
            .auth-left { flex: none; padding: 22px 28px; min-height: auto; }
            .auth-hero { display: none; }
            .auth-features { display: none; }
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
            <span class="auth-brand-name">Borobhai.online</span>
        </div>

        <div class="auth-hero">
            <h1>Join your<br>community today.</h1>
            <p>Create your account, choose your role, and start connecting with seniors, alumni, and opportunities that matter.</p>
        </div>

        <div class="auth-features">
            <div class="auth-feature">
                <div class="auth-feature-icon"><i class="fa-solid fa-user-graduate"></i></div>
                <div class="auth-feature-text">
                    <h4>Students &amp; Alumni</h4>
                    <p>One platform for juniors, graduates, and teachers.</p>
                </div>
            </div>
            <div class="auth-feature">
                <div class="auth-feature-icon"><i class="fa-solid fa-rocket"></i></div>
                <div class="auth-feature-text">
                    <h4>Grow Your Career</h4>
                    <p>Apply to jobs and build your professional profile.</p>
                </div>
            </div>
            <div class="auth-feature">
                <div class="auth-feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <div class="auth-feature-text">
                    <h4>Safe &amp; Verified</h4>
                    <p>A trusted network built for your institution.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== RIGHT: Register Form ===== --}}
    <div class="auth-right">
        <div class="auth-form-box">

            <div class="auth-form-head">
                <div class="mobile-brand">
                    <div class="mobile-brand-icon"><i class="fa-solid fa-graduation-cap"></i></div>
                    <span class="mobile-brand-name">Borobhai.online</span>
                </div>
                <h2>Create account</h2>
                <p>Join Borobhai.online and set up your professional role.</p>
            </div>

            <div id="globalRegAlert" class="auth-alert err" role="alert"></div>

            {{-- Social signup (role চুজ করার পর active) --}}
            <div class="auth-social">
                <a href="#" id="googleSignup" class="btn-social" onclick="return socialSignup(event, 'google')">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google"> Sign up with Google
                </a>
              
            </div>

            <div class="auth-divider">or sign up with email</div>

            <form id="ajaxRegisterForm" method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Role --}}
                <div style="margin-bottom:16px;">
                    <label class="form-label">Register As</label>
                    <div class="input-wrap">
                        <i class="fa-solid fa-users-gear input-icon"></i>
                        <select name="role" id="role" class="form-select" required onchange="onRoleChange()">
                            <option value="" disabled selected>Select your account type</option>
                            <option value="student">Student</option>
                            <option value="alumni">Alumni / Graduate</option>
                            <option value="teacher">Teacher</option>
                        </select>
                    </div>
                    <div class="role-hint" id="roleHint"></div>
                    <div class="error-text" id="roleError"></div>
                </div>

                {{-- Name --}}
                <div style="margin-bottom:16px;">
                    <label class="form-label">Full Name</label>
                    <div class="input-wrap">
                        <i class="fa-regular fa-user input-icon"></i>
                        <input type="text" id="name" name="name" class="form-input" placeholder="John Doe" required>
                    </div>
                    <div class="error-text" id="nameError"></div>
                </div>

                {{-- Email --}}
                <div style="margin-bottom:16px;">
                    <label class="form-label">Email Address</label>
                    <div class="input-wrap">
                        <i class="fa-regular fa-envelope input-icon"></i>
                        <input type="email" id="email" name="email" class="form-input" placeholder="name@example.com" required>
                    </div>
                    <div class="error-text" id="emailError"></div>
                </div>

                {{-- Password --}}
                <div style="margin-bottom:16px;">
                    <label class="form-label">Password</label>
                    <div class="input-wrap">
                        <i class="fa-regular fa-lock-keyhole input-icon"></i>
                        <input type="password" id="password" name="password" class="form-input" required autocomplete="new-password" style="padding-right:44px;">
                        <span class="toggle-eye" onclick="togglePass('password', 'eyeIcon1')"><i class="fa-regular fa-eye" id="eyeIcon1"></i></span>
                    </div>
                    <div class="error-text" id="passwordError"></div>
                </div>

                {{-- Confirm Password --}}
                <div style="margin-bottom:22px;">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-wrap">
                        <i class="fa-regular fa-shield-check input-icon"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required autocomplete="new-password" style="padding-right:44px;">
                        <span class="toggle-eye" onclick="togglePass('password_confirmation', 'eyeIcon2')"><i class="fa-regular fa-eye" id="eyeIcon2"></i></span>
                    </div>
                </div>

                <div style="display:flex;align-items:flex-start;gap:9px;margin-bottom:20px;">
                <input type="checkbox" id="agreeTerms" name="terms" value="1" required
                    style="width:16px;height:16px;margin-top:3px;cursor:pointer;accent-color:#6366f1;flex-shrink:0;">
                <label for="agreeTerms" style="font-size:.82rem;color:#94a3b8;line-height:1.5;cursor:pointer;">
                    <p style="text-align:center;font-size:.76rem;color:#64748b;margin:-6px 0 16px;line-height:1.5;">
                        By continuing, you agree to our
                        <a href="{{ route('terms') }}" target="_blank" style="color:#818cf8;text-decoration:none;font-weight:600;">Terms</a> &amp;
                        <a href="{{ route('privacy') }}" target="_blank" style="color:#818cf8;text-decoration:none;font-weight:600;">Privacy</a>.
                    </p>
                </label>
            </div>

                <button type="submit" id="regBtn" class="btn-submit">
                    <span id="btnText">Create Account</span>
                    <div id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" style="width:1.1rem;height:1.1rem;"></div>
                </button>
            </form>

            <div class="auth-foot">Already have an account? <a href="{{ route('login') }}">Sign in</a></div>

        </div>
    </div>

</div>

<script>
// ===== role চুজ করলে ছোট বর্ণনা =====
function onRoleChange() {
    const role = document.getElementById('role').value;
    const hint = document.getElementById('roleHint');
    const hints = {
        student: 'Connect with seniors, find jobs, and apply to opportunities.',
        alumni:  'Share opportunities, post jobs, and mentor juniors.',
        teacher: 'Share academic papers and resources. You can view posts and jobs, but cannot post jobs.'
    };
    if (hints[role]) {
        hint.textContent = hints[role];
        hint.classList.add('show');
    } else {
        hint.classList.remove('show');
    }
    // role select হলে আগের error সরাও
    const re = document.getElementById('roleError');
    if (re) { re.style.display = 'none'; re.innerHTML = ''; }
}

// ===== Google/Facebook signup — role চুজ করা থাকলে সেটা নিয়ে redirect =====
function socialSignup(e, provider) {
    e.preventDefault();
    const role = document.getElementById('role').value;
    if (!role) {
        const re = document.getElementById('roleError');
        re.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Please select your account type first';
        re.style.display = 'flex';
        document.getElementById('role').focus();
        return false;
    }

    // ✅ Terms & Privacy টিক না দিলে Google/FB এও আটকাও
    const agree = document.getElementById('agreeTerms');
    if (!agree || !agree.checked) {
        const alert = document.getElementById('globalRegAlert');
        alert.className = 'auth-alert err show';
        alert.innerHTML = '<i class="fa-solid fa-triangle-exclamation" style="margin-top:2px;"></i><span>Please agree to the Terms &amp; Privacy Policy first.</span>';
        const agree = document.getElementById('agreeTerms');
    if (!agree || !agree.checked) {
        const alert = document.getElementById('globalRegAlert');
        alert.className = 'auth-alert err show';
        alert.innerHTML = '<i class="fa-solid fa-triangle-exclamation" style="margin-top:2px;"></i><span>Please agree to the Terms &amp; Privacy Policy first.</span>';

        // checkbox এর container টা ক্ষণিকের জন্য হাইলাইট (focus নয়, তাই লাফ দেবে না)
        const box = agree.closest('div');
        if (box) {
            box.style.transition = 'background .3s ease';
            box.style.background = 'rgba(248,113,113,.12)';
            box.style.borderRadius = '8px';
            setTimeout(() => { box.style.background = 'transparent'; }, 1200);
        }
        return false;
    }
        return false;
    }

    window.location.href = `/auth/${provider}/redirect?role=${role}`;
    return false;
}

// ===== চোখ টগল =====
function togglePass(inputId, eyeId) {
    const input = document.getElementById(inputId);
    const eyeIcon = document.getElementById(eyeId);
    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.setAttribute('type', type);
    if (type === 'text') {
        eyeIcon.classList.remove('fa-eye'); eyeIcon.classList.add('fa-eye-slash'); eyeIcon.style.color = '#4f46e5';
    } else {
        eyeIcon.classList.remove('fa-eye-slash'); eyeIcon.classList.add('fa-eye'); eyeIcon.style.color = '#94a3b8';
    }
}

// ===== AJAX register (logic অপরিবর্তিত — শুধু UI সিলেক্টর আপডেট) =====
document.getElementById('ajaxRegisterForm').addEventListener('submit', function(e) {
    e.preventDefault();

    // ✅ Terms & Privacy টিক না দিলে আটকাও
    const agree = document.getElementById('agreeTerms');
    if (!agree || !agree.checked) {
        const alert = document.getElementById('globalRegAlert');
        alert.className = 'auth-alert err show';
        alert.innerHTML = '<i class="fa-solid fa-triangle-exclamation" style="margin-top:2px;"></i><span>Please agree to the Terms &amp; Privacy Policy to continue.</span>';
        agree?.focus();
        return; // submit থামাও
    }

    const form = this;
    const regBtn = document.getElementById('regBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    const globalRegAlert = document.getElementById('globalRegAlert');

    document.querySelectorAll('.error-text').forEach(el => { el.style.display = 'none'; el.innerHTML = ''; });
    if (globalRegAlert) { globalRegAlert.className = 'auth-alert err'; globalRegAlert.innerHTML = ''; }

    regBtn.disabled = true;
    btnText.innerText = 'Creating Profile...';
    btnSpinner.classList.remove('d-none');

    fetch(form.action, {
        method: "POST",
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: new FormData(form)
    })
    .then(response => response.json().then(data => ({ status: response.status, body: data })))
    .then(res => {
        regBtn.disabled = false;
        btnText.innerText = 'Create Account';
        btnSpinner.classList.add('d-none');

        if (res.status === 200 && res.body.success) {
            globalRegAlert.className = 'auth-alert ok show';
            globalRegAlert.innerHTML = `<i class="fa-solid fa-circle-check" style="margin-top:2px;"></i><span>${res.body.message}</span>`;
            setTimeout(() => { window.location.href = res.body.redirect; }, 900);
        } else if (res.status === 422) {
            const errors = res.body.errors;
            if (errors.role)     { const el=document.getElementById('roleError');     el.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${errors.role[0]}`;     el.style.display='flex'; }
            if (errors.name)     { const el=document.getElementById('nameError');     el.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${errors.name[0]}`;     el.style.display='flex'; }
            if (errors.email)    { const el=document.getElementById('emailError');    el.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${errors.email[0]}`;    el.style.display='flex'; }
            if (errors.password) { const el=document.getElementById('passwordError'); el.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${errors.password[0]}`; el.style.display='flex'; }
        }
    })
    .catch(error => {
        regBtn.disabled = false; btnText.innerText = 'Create Account'; btnSpinner.classList.add('d-none');
        globalRegAlert.className = 'auth-alert err show';
        globalRegAlert.innerHTML = `<i class="fa-solid fa-circle-xmark" style="margin-top:2px;"></i><span>Connection error. Please try again.</span>`;
    });
});
</script>
</body>
</html>