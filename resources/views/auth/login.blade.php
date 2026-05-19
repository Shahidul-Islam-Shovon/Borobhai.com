<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Borobhai.com</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif !important;
            background: radial-gradient(circle at 10% 20%, rgb(242, 245, 251) 0%, rgb(237, 242, 249) 90%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }
        .login-container {
            width: 100%;
            max-width: 450px;
            background: #ffffff;
            border-radius: 24px;
            padding: 45px 40px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.04), 0 1px 3px rgba(15, 23, 42, 0.02);
            border: 1px solid rgba(226, 232, 240, 0.8);
            position: relative;
        }
        .brand-section { text-align: center; margin-bottom: 35px; }
        .brand-logo-icon {
            font-size: 2.2rem; color: #2563eb; background: rgba(37, 99, 235, 0.08);
            width: 65px; height: 65px; display: inline-flex; align-items: center; justify-content: center;
            border-radius: 18px; margin-bottom: 15px;
        }
        .brand-title { font-size: 1.6rem; font-weight: 800; color: #0f172a; letter-spacing: -0.5px; }
        .brand-subtitle { font-size: 0.88rem; color: #64748b; margin-top: 5px; }
        .form-label { font-size: 0.85rem; font-weight: 600; color: #334155; margin-bottom: 8px; }
        .input-group-custom { position: relative; display: flex; align-items: center; }
        .input-icon { position: absolute; left: 16px; color: #94a3b8; font-size: 1.05rem; z-index: 10; }
        .form-control-custom {
            width: 100%; padding: 13px 16px 13px 45px; font-size: 0.95rem;
            background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; color: #0f172a;
        }
        .form-control-custom:focus { background-color: #ffffff; border-color: #2563eb; outline: none; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); }
        .btn-premium-submit {
            background: #2563eb; color: #ffffff; font-weight: 600; font-size: 0.98rem; padding: 14px;
            border-radius: 12px; width: 100%; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }
        .btn-premium-submit:hover { background: #1d4ed8; transform: translateY(-1px); }
        .error-text { color: #ef4444; font-size: 0.8rem; font-weight: 500; margin-top: 6px; display: none; align-items: center; gap: 5px; }
        .footer-link-text { text-align: center; margin-top: 25px; font-size: 0.88rem; color: #64748b; }
        .footer-link-text a { color: #2563eb; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>

<div class="login-container">
    <div class="brand-section">
        <div class="brand-logo-icon"><i class="fa-solid fa-graduation-cap"></i></div>
        <div class="brand-title">Borobhai.com</div>
        <div class="brand-subtitle">Welcome back! Please sign in to your account.</div>
    </div>

    {{-- 🛡️ গ্লোবাল অ্যালার্ট বক্স --}}
    <div id="globalAlert" class="alert {{ session('suspended_error') ? 'alert-danger d-block' : 'd-none' }} text-center fw-medium mb-4" role="alert" 
         style="border-radius: 12px; font-size: 0.9rem; padding: 12px; {{ session('suspended_error') ? 'background-color: #fef2f2; color: #dc2626; border: 1px solid #fca5a5;' : '' }}">
        @if(session('suspended_error'))
            <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('suspended_error') }}
        @endif
    </div>

    <form id="ajaxLoginForm" method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <div class="input-group-custom">
                <input type="email" id="email" name="email" class="form-control-custom" placeholder="name@example.com" required autocomplete="username">
                <i class="fa-regular fa-envelope input-icon"></i>
            </div>
            <div class="error-text" id="emailError"></div>
        </div>

        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label mb-0">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size: 0.82rem; color: #2563eb; text-decoration: none; font-weight: 500;">Forgot Password?</a>
                @endif
            </div>
            <div class="input-group-custom">
                <input type="password" id="password" name="password" class="form-control-custom" required autocomplete="current-password" style="padding-right: 45px;">
                <i class="fa-regular fa-lock-keyhole input-icon"></i>
                <span id="togglePassword" style="position: absolute; right: 16px; color: #94a3b8; cursor: pointer; z-index: 10;">
                    <i class="fa-regular fa-eye" id="eyeIcon"></i>
                </span>
            </div>
            <div class="error-text" id="passwordError"></div>
        </div>

        <div class="form-check mb-4 text-start">
            <input class="form-check-input" type="checkbox" name="remember" id="remember_me" style="cursor: pointer;">
            <label class="form-check-label text-secondary" style="font-size: 0.85rem; cursor: pointer;" for="remember_me">Keep me logged in</label>
        </div>

        <button type="submit" id="loginBtn" class="btn-premium-submit">
            <span id="btnText">Sign In</span>
            <div id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" style="width: 1.1rem; height: 1.1rem;"></div>
        </button>
    </form>

    <div class="footer-link-text">Don't have an account? <a href="{{ route('register') }}">Create Account</a></div>
</div>

<script>
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');
const eyeIcon = document.getElementById('eyeIcon');

togglePassword.addEventListener('click', function () {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    eyeIcon.classList.toggle('fa-eye');
    eyeIcon.classList.toggle('fa-eye-slash');
});

document.getElementById('ajaxLoginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const loginBtn = document.getElementById('loginBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    const globalAlert = document.getElementById('globalAlert');

    // আগের এরর ক্লিয়ার করা
    document.querySelectorAll('.error-text').forEach(el => { el.style.display = 'none'; el.innerHTML = ''; });
    globalAlert.className = "alert d-none text-center fw-medium mb-4";
    globalAlert.removeAttribute('style');

    loginBtn.disabled = true;
    btnText.innerText = 'Verifying Credentials...';
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

        // 👑 চেক ১: স্ট্যাটাস ২০০ হলেই কেবল সাকসেস দেখাবে
        if (res.status === 200) {
            globalAlert.className = "alert alert-success d-block text-center fw-medium mb-4";
            globalAlert.style.backgroundColor = "#ecfdf5";
            globalAlert.style.color = "#059669";
            globalAlert.innerHTML = `<i class="fa-solid fa-circle-check me-2"></i> Logged in successfully!`;
            
            setTimeout(() => {
                window.location.href = (res.body && res.body.redirect) ? res.body.redirect : window.location.origin;
            }, 800);

        // 👑 চেক ২: স্ট্যাটাস ৪২২ হলে এরর দেখাবে (সাসপেন্ডসহ সব ভ্যালিডেশন এরর)
        } else if (res.status === 422 && res.body && res.body.errors) {
            const errors = res.body.errors;
            
            if (errors.email) {
                // যদি মেসেজে Suspended বা Access Denied লেখা থাকে তবে বড় লাল বক্সে দেখাবে
                if (errors.email[0].includes('Suspended') || errors.email[0].includes('Denied') || errors.email[0].includes('Access')) {
                    globalAlert.className = "alert alert-danger d-block text-center fw-medium mb-4";
                    globalAlert.style.backgroundColor = "#fef2f2";
                    globalAlert.style.color = "#dc2626";
                    globalAlert.style.border = "1px solid #fca5a5";
                    globalAlert.style.borderRadius = "12px";
                    globalAlert.style.padding = "12px";
                    globalAlert.style.fontSize = "0.9rem";
                    globalAlert.innerHTML = `<i class="fa-solid fa-triangle-exclamation me-2"></i> ${errors.email[0]}`;
                } else {
                    // নরমাল ভুল ইমেইলের এরর ফিল্ডের নিচে দেখাবে
                    const emailErr = document.getElementById('emailError');
                    emailErr.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${errors.email[0]}`;
                    emailErr.style.display = 'flex';
                }
            }
            
            if (errors.password) {
                const passErr = document.getElementById('passwordError');
                passErr.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${errors.password[0]}`;
                passErr.style.display = 'flex';
            }
        } else {
            // অন্য কোনো আননোন সার্ভার এরর হলে
            globalAlert.className = "alert alert-danger d-block text-center mb-4";
            globalAlert.innerHTML = `<i class="fa-solid fa-circle-xmark me-2"></i> Error occurred. Status: ${res.status}`;
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