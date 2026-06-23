<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">
    <title>Choose Your Role · Borobhai.online</title>

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
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif !important;
            background: #0b1020;
            min-height: 100vh;
            color: #f1f5f9;
            display: flex; align-items: center; justify-content: center;
            padding: 28px 20px;
            position: relative;
            overflow-x: hidden;
        }
        /* dark glow orbs */
        body::before, body::after {
            content: ''; position: fixed; border-radius: 50%; filter: blur(80px); opacity: .25; pointer-events: none; z-index: 0;
        }
        body::before { width: 420px; height: 420px; background: #4f46e5; top: -140px; right: -100px; }
        body::after  { width: 380px; height: 380px; background: #6366f1; bottom: -120px; left: -90px; }

        .cr-box {
            width: 100%; max-width: 620px; position: relative; z-index: 2;
            animation: boxIn .55s ease;
        }
        @keyframes boxIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* brand */
        .cr-brand { display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 26px; }
        .cr-brand-icon {
            width: 46px; height: 46px; border-radius: 13px;
            background: linear-gradient(135deg, #4f46e5, #6366f1); color: #fff;
            display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
            box-shadow: 0 4px 16px rgba(79,70,229,.4);
        }
        .cr-brand-name { font-size: 1.35rem; font-weight: 800; letter-spacing: -.5px; }

        /* welcome header */
        .cr-head { text-align: center; margin-bottom: 28px; }
        .cr-welcome {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(99,102,241,.14); color: #c7d2fe;
            font-size: .82rem; font-weight: 600; padding: 6px 14px; border-radius: 20px;
            margin-bottom: 14px; border: 1px solid rgba(129,140,248,.25);
        }
        .cr-head h1 { font-size: 1.7rem; font-weight: 800; letter-spacing: -.5px; margin-bottom: 8px; }
        .cr-head h1 span { color: var(--bb-primary-light); }
        .cr-head p { font-size: .94rem; color: #94a3b8; line-height: 1.5; max-width: 440px; margin: 0 auto; }

        /* role cards */
        .cr-roles { display: flex; flex-direction: column; gap: 13px; margin-bottom: 24px; }
        .cr-role {
            display: flex; align-items: center; gap: 16px;
            background: rgba(255,255,255,.04);
            border: 1.5px solid rgba(255,255,255,.1);
            border-radius: 16px; padding: 18px 20px;
            cursor: pointer; transition: all .2s ease;
            position: relative;
        }
        .cr-role:hover { background: rgba(255,255,255,.07); border-color: rgba(129,140,248,.45); transform: translateY(-2px); }
        .cr-role.selected {
            border-color: var(--bb-primary-light);
            background: rgba(79,70,229,.14);
            box-shadow: 0 0 0 4px rgba(99,102,241,.16);
        }
        .cr-role-icon {
            width: 52px; height: 52px; border-radius: 14px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 1.4rem;
        }
        .cr-icon-student { background: rgba(79,70,229,.2); color: #a5b4fc; }
        .cr-icon-alumni  { background: rgba(217,119,6,.2);  color: #fcd34d; }
        .cr-icon-teacher { background: rgba(124,58,237,.22); color: #c4b5fd; }
        .cr-role-text { flex-grow: 1; min-width: 0; }
        .cr-role-text h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: 3px; }
        .cr-role-text p { font-size: .83rem; color: #94a3b8; line-height: 1.45; }
        .cr-role-check {
            width: 24px; height: 24px; border-radius: 50%; flex-shrink: 0;
            border: 2px solid rgba(255,255,255,.2);
            display: flex; align-items: center; justify-content: center;
            transition: all .2s ease;
        }
        .cr-role.selected .cr-role-check {
            background: var(--bb-primary-light); border-color: var(--bb-primary-light);
        }
        .cr-role-check i { font-size: .75rem; color: #0b1020; opacity: 0; transition: opacity .2s ease; }
        .cr-role.selected .cr-role-check i { opacity: 1; }

        .cr-error {
            color: #fca5a5; font-size: .85rem; font-weight: 500; text-align: center;
            margin-bottom: 16px; display: none; align-items: center; justify-content: center; gap: 6px;
        }
        .cr-error.show { display: flex; }

        .btn-continue {
            width: 100%; padding: 15px; border: none; border-radius: 13px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff; font-size: 1rem; font-weight: 700;
            cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;
            box-shadow: 0 4px 18px rgba(79,70,229,.4); transition: all .2s ease;
        }
        .btn-continue:hover { transform: translateY(-1px); box-shadow: 0 6px 24px rgba(79,70,229,.55); }
        .btn-continue:disabled { opacity: .55; cursor: default; transform: none; }

        .cr-foot { text-align: center; margin-top: 20px; font-size: .85rem; color: #64748b; }
        .cr-foot a { color: var(--bb-primary-light); text-decoration: none; font-weight: 600; }
        .cr-foot a:hover { text-decoration: underline; }

        @media (max-width: 540px) {
            .cr-role { padding: 15px 16px; gap: 13px; }
            .cr-role-icon { width: 46px; height: 46px; font-size: 1.2rem; }
            .cr-head h1 { font-size: 1.45rem; }
        }
    </style>
</head>
<body>

<div class="cr-box">

    <div class="cr-brand">
        <div class="cr-brand-icon"><i class="fa-solid fa-graduation-cap"></i></div>
        <span class="cr-brand-name">Borobhai.online</span>
    </div>

    <div class="cr-head">
        <div class="cr-welcome">
            <i class="fa-solid fa-circle-check"></i> Connected with {{ ucfirst($provider ?? 'your account') }}
        </div>
        <h1>Welcome, <span>{{ $name ?? 'there' }}</span>!</h1>
        <p>You're almost done. Choose how you want to use Borobhai.online to finish setting up your account.</p>
    </div>

    <form id="chooseRoleForm" method="POST" action="{{ route('social.storeRole') }}">
        @csrf
        <input type="hidden" name="role" id="selectedRole" value="">

        <div class="cr-roles">

            <div class="cr-role" data-role="student" onclick="selectRole('student')">
                <div class="cr-role-icon cr-icon-student"><i class="fa-solid fa-user-graduate"></i></div>
                <div class="cr-role-text">
                    <h3>Student</h3>
                    <p>Connect with seniors, find jobs, and apply to opportunities.</p>
                </div>
                <div class="cr-role-check"><i class="fa-solid fa-check"></i></div>
            </div>

            <div class="cr-role" data-role="alumni" onclick="selectRole('alumni')">
                <div class="cr-role-icon cr-icon-alumni"><i class="fa-solid fa-mortarboard"></i></div>
                <div class="cr-role-text">
                    <h3>Alumni / Graduate</h3>
                    <p>Share opportunities, post jobs, and mentor juniors.</p>
                </div>
                <div class="cr-role-check"><i class="fa-solid fa-check"></i></div>
            </div>

            <div class="cr-role" data-role="teacher" onclick="selectRole('teacher')">
                <div class="cr-role-icon cr-icon-teacher"><i class="fa-solid fa-chalkboard-user"></i></div>
                <div class="cr-role-text">
                    <h3>Teacher</h3>
                    <p>Share academic papers and resources with the community.</p>
                </div>
                <div class="cr-role-check"><i class="fa-solid fa-check"></i></div>
            </div>

        </div>

        <div class="cr-error" id="roleError">
            <i class="fa-solid fa-circle-exclamation"></i> Please select a role to continue
        </div>

        <button type="submit" id="continueBtn" class="btn-continue" disabled>
            <span id="btnText">Continue</span>
            <i class="fa-solid fa-arrow-right" id="btnArrow"></i>
            <div id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" style="width:1.1rem;height:1.1rem;"></div>
        </button>
    </form>

    <div class="cr-foot">
        Not you? <a href="{{ route('login') }}">Sign in with a different account</a>
    </div>

</div>

<script>
let chosenRole = '';

function selectRole(role) {
    chosenRole = role;
    document.getElementById('selectedRole').value = role;

    // সব card থেকে selected সরাও, শুধু এইটায় বসাও
    document.querySelectorAll('.cr-role').forEach(el => {
        el.classList.toggle('selected', el.getAttribute('data-role') === role);
    });

    // button active করো
    document.getElementById('continueBtn').disabled = false;

    // error থাকলে সরাও
    document.getElementById('roleError').classList.remove('show');
}

// submit — role না দিলে আটকাও (যদিও button disabled, তবু safety)
document.getElementById('chooseRoleForm').addEventListener('submit', function(e) {
    if (!chosenRole) {
        e.preventDefault();
        document.getElementById('roleError').classList.add('show');
        return;
    }
    // loading state
    const btn = document.getElementById('continueBtn');
    btn.disabled = true;
    document.getElementById('btnText').innerText = 'Setting up...';
    document.getElementById('btnArrow').classList.add('d-none');
    document.getElementById('btnSpinner').classList.remove('d-none');
    // normal form submit (POST) — controller account বানিয়ে redirect করবে
});
</script>
</body>
</html>