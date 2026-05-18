<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - Borobhai.com</title>
    
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
            margin: 0; padding: 20px;
        }
        .forgot-container {
            width: 100%; max-width: 450px;
            background: #ffffff; border-radius: 24px; padding: 45px 40px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.04);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .brand-section { text-align: center; margin-bottom: 25px; }
        .brand-logo-icon {
            font-size: 2rem; color: #2563eb; background: rgba(37, 99, 235, 0.08);
            width: 60px; height: 60px; display: inline-flex; align-items: center; justify-content: center; border-radius: 18px; margin-bottom: 12px;
        }
        .brand-title { font-size: 1.5rem; font-weight: 800; color: #0f172a; }
        .info-text { font-size: 0.88rem; color: #64748b; line-height: 1.6; text-align: center; margin-bottom: 25px; }
        .form-label { font-size: 0.85rem; font-weight: 600; color: #334155; margin-bottom: 8px; }
        .input-group-custom { position: relative; display: flex; align-items: center; }
        .input-icon { position: absolute; left: 16px; color: #94a3b8; font-size: 1.05rem; }
        .form-control-custom {
            width: 100%; padding: 13px 16px 13px 45px; font-size: 0.95rem;
            background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;
            transition: all 0.25s ease;
        }
        .form-control-custom:focus {
            background-color: #ffffff; border-color: #2563eb; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); outline: none;
        }
        .btn-premium-submit {
            background: #2563eb; color: #ffffff; font-weight: 600; padding: 14px; border-radius: 12px;
            width: 100%; border: none; cursor: pointer; transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .btn-premium-submit:hover { background: #1d4ed8; transform: translateY(-1px); }
        .error-text { color: #ef4444; font-size: 0.8rem; font-weight: 500; margin-top: 6px; display: none; align-items: center; gap: 5px; }
        .back-link { text-align: center; margin-top: 25px; font-size: 0.88rem; }
        .back-link a { color: #2563eb; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>

<div class="forgot-container">
    <div class="brand-section">
        <div class="brand-logo-icon"><i class="fa-solid fa-key-skeleton"></i></div>
        <div class="brand-title">Reset Password</div>
    </div>
    
    <p class="info-text">
        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
    </p>

    <div id="globalAlert" class="alert d-none text-center fw-medium mb-4" role="alert" style="border-radius: 12px; font-size: 0.9rem;"></div>

    <form id="ajaxForgotForm" method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label class="form-label">Email Address</label>
            <div class="input-group-custom">
                <input type="email" id="email" name="email" class="form-control-custom" placeholder="name@example.com" required>
                <i class="fa-regular fa-envelope input-icon"></i>
            </div>
            <div class="error-text" id="emailError"></div>
        </div>

        <button type="submit" id="submitBtn" class="btn-premium-submit">
            <span id="btnText">Email Password Reset Link</span>
            <div id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" style="width: 1.1rem; height: 1.1rem;"></div>
        </button>
    </form>

    <div class="back-link">
        <a href="{{ route('login') }}"><i class="fa-solid fa-arrow-left me-2"></i> Back to Sign In</a>
    </div>
</div>

<script>
document.getElementById('ajaxForgotForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');
    const globalAlert = document.getElementById('globalAlert');

    document.querySelectorAll('.error-text').forEach(el => { el.style.display = 'none'; el.innerHTML = ''; });
    globalAlert.classList.add('d-none');

    submitBtn.disabled = true;
    btnText.innerText = 'Sending Link...';
    btnSpinner.classList.remove('d-none');

    const formData = new FormData(form);

    fetch(form.action, {
        method: "POST",
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: formData
    })
    .then(response => response.json().then(data => ({ status: response.status, body: data })))
    .then(res => {
        submitBtn.disabled = false;
        btnText.innerText = 'Email Password Reset Link';
        btnSpinner.classList.add('d-none');

        if (res.status === 200 && res.body.success) {
            globalAlert.className = "alert alert-success d-block text-center fw-medium mb-4";
            globalAlert.innerHTML = `<i class="fa-solid fa-circle-check me-2"></i> ${res.body.message}`;
            form.reset(); // ফর্ম ক্লিয়ার করা
        } else if (res.status === 422) {
            const errors = res.body.errors;
            if (errors.email) {
                const emailErr = document.getElementById('emailError');
                emailErr.innerHTML = `<i class="fa-solid fa-circle-exclamation"></i> ${errors.email[0]}`;
                emailErr.style.display = 'flex';
            }
        }
    })
    .catch(error => {
        submitBtn.disabled = false;
        btnText.innerText = 'Email Password Reset Link';
        btnSpinner.classList.add('d-none');
        globalAlert.className = "alert alert-danger d-block text-center mb-4";
        globalAlert.innerHTML = `<i class="fa-solid fa-circle-xmark me-2"></i> Error sending request.`;
    });
});
</script>
</body>
</html>