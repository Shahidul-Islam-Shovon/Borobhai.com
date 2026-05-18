<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Borobhai.com</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif !important;
            background: radial-gradient(circle at 10% 20%, rgb(242, 245, 251) 0%, rgb(237, 242, 249) 90%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; padding: 30px 20px;
        }
        .register-container {
            width: 100%; max-width: 500px; background: #ffffff; border-radius: 24px; padding: 40px 35px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.04); border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .brand-section { text-align: center; margin-bottom: 30px; }
        .brand-logo-icon {
            font-size: 2rem; color: #2563eb; background: rgba(37, 99, 235, 0.08);
            width: 55px; height: 55px; display: inline-flex; align-items: center; justify-content: center; border-radius: 16px; margin-bottom: 12px;
        }
        .brand-title { font-size: 1.5rem; font-weight: 800; color: #0f172a; }
        .brand-subtitle { font-size: 0.85rem; color: #64748b; margin-top: 4px; }
        .form-label { font-size: 0.85rem; font-weight: 600; color: #334155; margin-bottom: 6px; }
        .input-group-custom { position: relative; display: flex; align-items: center; }
        .input-icon { position: absolute; left: 16px; color: #94a3b8; font-size: 1.05rem; z-index: 10; }
        .form-control-custom, .form-select-custom {
            width: 100%; padding: 12px 45px 12px 45px; font-size: 0.95rem;
            background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; color: #0f172a; transition: all 0.25s ease;
        }
        .form-select-custom { padding-left: 45px; cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 16px center; background-size: 12px 12px; }
        .form-control-custom:focus, .form-select-custom:focus { background-color: #ffffff; border-color: #2563eb; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); outline: none; }
        .btn-premium-submit {
            background: #2563eb; color: #ffffff; font-weight: 600; padding: 14px; border-radius: 12px;
            width: 100%; border: none; cursor: pointer; transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .btn-premium-submit:hover { background: #1d4ed8; transform: translateY(-1px); }
        .error-text { color: #ef4444; font-size: 0.8rem; font-weight: 500; margin-top: 5px; display: none; align-items: center; gap: 5px; }
        .footer-link-text { text-align: center; margin-top: 20px; font-size: 0.88rem; color: #64748b; }
        .footer-link-text a { color: #2563eb; text-decoration: none; font-weight: 600; }
        .toggle-password-eye { position: absolute; right: 16px; color: #94a3b8; cursor: pointer; z-index: 10; }
    </style>
</head>
<body>

<div class="register-container">
    <div class="brand-section">
        <div class="brand-logo-icon"><i class="fa-solid fa-user-plus"></i></div>
        <div class="brand-title">Create Account</div>
        <div class="brand-subtitle">Join Borobhai.com and setup your professional role</div>
    </div>

    <div id="globalRegAlert" class="alert d-none text-center fw-medium mb-4" role="alert" style="border-radius: 12px; font-size: 0.9rem;"></div>

    <form id="ajaxRegisterForm" method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Register As</label>
            <div class="input-group-custom">
                <select name="role" id="role" class="form-select-custom" required>
                    <option value="" disabled selected>Select your account type</option>
                    <option value="student">Student</option>
                    <option value="alumni">Alumni / Graduate</option>
                </select>
                <i class="fa-solid fa-users-gear input-icon"></i>
            </div>
            <div class="error-text" id="roleError"></div>
        </div>

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <div class="input-group-custom">
                <input type="text" id="name" name="name" class="form-control-custom" placeholder="John Doe" required style="padding-left: 45px;">
                <i class="fa-regular fa-user input-icon"></i>
            </div>
            <div class="error-text" id="nameError"></div>
        </div>

        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <div class="input-group-custom">
                <input type="email" id="email" name="email" class="form-control-custom" placeholder="name@example.com" required style="padding-left: 45px;">
                <i class="fa-regular fa-envelope input-icon"></i>
            </div>
            <div class="error-text" id="emailError"></div>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group-custom">
                <input type="password" id="password" name="password" class="form-control-custom" required autocomplete="new-password" style="padding-left: 45px; padding-right: 45px;">
                <i class="fa-regular fa-lock-keyhole input-icon"></i>
                <span class="toggle-password-eye" onclick="togglePass('password', 'eyeIcon1')">
                    <i class="fa-regular fa-eye" id="eyeIcon1"></i>
                </span>
            </div>
            <div class="error-text" id="passwordError"></div>
        </div>

        <div class="mb-4">
            <label class="form-label">Confirm Password</label>
            <div class="input-group-custom">
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control-custom" required autocomplete="new-password" style="padding-left: 45px; padding-right: 45px;">
                <i class="fa-regular fa-shield-check input-icon"></i>
                <span class="toggle-password-eye" onclick="togglePass('password_confirmation', 'eyeIcon2')">
                    <i class="fa-regular fa-eye" id="eyeIcon2"></i>
                </span>
            </div>
        </div>

        <button type="submit" id="regBtn" class="btn-premium-submit">
            <span id="btnText">Create Account</span>
            <div id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" style="width: 1.1rem; height: 1.1rem;"></div>
        </button>
    </form>

    <div class="footer-link-text">
        Already have an account? <a href="{{ route('login') }}">Sign In</a>
    </div>
</div>

<script>
// 🔥 চোখ অ্যাক্টিভ করার রিয়েল-টাইম ফাংশন
function togglePass(inputId, eyeId) {
    const input = document.getElementById(inputId);
    const eyeIcon = document.getElementById(eyeId);
    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.setAttribute('type', type);
    
    if (type === 'text') {
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
        eyeIcon.style.color = '#2563eb';
    } else {
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
        eyeIcon.style.color = '#94a3b8';
    }
}

document.getElementById('ajaxRegisterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const regBtn = document.getElementById('regBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    const globalRegAlert = document.getElementById('globalRegAlert');
    
    document.querySelectorAll('.error-text').forEach(el => { el.style.display = 'none'; el.innerHTML = ''; });
    if(globalRegAlert) globalRegAlert.classList.add('d-none');

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
            globalRegAlert.className = "alert alert-success d-block text-center fw-medium mb-4";
            globalRegAlert.innerHTML = `<i class="fa-solid fa-circle-check me-2"></i> ${res.body.message}`;
            setTimeout(() => { window.location.href = res.body.redirect; }, 1000);
        } else if (res.status === 422) {
            const errors = res.body.errors;
            if (errors.role) { document.getElementById('roleError').innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${errors.role[0]}`; document.getElementById('roleError').style.display = 'flex'; }
            if (errors.name) { document.getElementById('nameError').innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${errors.name[0]}`; document.getElementById('nameError').style.display = 'flex'; }
            if (errors.email) { document.getElementById('emailError').innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${errors.email[0]}`; document.getElementById('emailError').style.display = 'flex'; }
            if (errors.password) { document.getElementById('passwordError').innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${errors.password[0]}`; document.getElementById('passwordError').style.display = 'flex'; }
        }
    })
    .catch(error => {
        regBtn.disabled = false; btnText.innerText = 'Create Account'; btnSpinner.classList.add('d-none');
        globalRegAlert.className = "alert alert-danger d-block text-center mb-4";
        globalRegAlert.innerHTML = `<i class="fa-solid fa-circle-xmark me-2"></i> Connection error.`;
    });
});
</script>
</body>
</html>