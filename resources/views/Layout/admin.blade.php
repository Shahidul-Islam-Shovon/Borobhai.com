<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    <title>@yield('title', 'Admin Panel') - Borobhai.com</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/css/admin-custom.css', 'resources/js/app.js'])
    
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif !important; background: #f8fafc; color: #1e293b; }
        
        .dashboard-wrapper { display: flex; min-height: 100vh; width: 100%; }
        
        /* Premium Admin Sidebar */
        #sidebar {
            width: 270px; min-width: 270px;
            background: #0f172a; color: #ffffff; 
            display: flex; flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        #sidebar .sidebar-header {
            padding: 30px 24px; background: #020617; text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .admin-brand-name { 
            font-size: 1.5rem; font-weight: 800; color: #ffffff; 
            letter-spacing: -0.5px; display: block; margin-bottom: 6px;
        }
        .sidebar-admin-title {
            color: #f43f5e; font-size: 0.85rem; font-weight: 700;
            display: inline-flex; align-items: center; gap: 6px;
            letter-spacing: 0.5px; background: rgba(244, 63, 94, 0.1);
            padding: 4px 12px; border-radius: 6px;
        }

        #sidebar ul.components { padding: 20px 0; list-style: none; flex-grow: 1; }
        #sidebar ul p { color: #475569; padding: 12px 25px; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; letter-spacing: 1.2px; }
        
        #sidebar ul li a { 
            padding: 14px 25px; font-size: 0.95rem; display: flex; align-items: center;
            color: #94a3b8; text-decoration: none; transition: all 0.3s ease; font-weight: 500; 
        }
        #sidebar ul li a i { margin-right: 14px; width: 20px; text-align: center; font-size: 1.1rem; transition: transform 0.3s; }
        #sidebar ul li a:hover { color: #ffffff; background: rgba(255, 255, 255, 0.03); padding-left: 30px; }
        #sidebar ul li a:hover i { transform: scale(1.15); color: #f43f5e; }
        
        #sidebar ul li.active > a { 
            color: #f43f5e; background: rgba(244, 63, 94, 0.06); 
            border-left: 4px solid #f43f5e; font-weight: 600; 
        }
        #sidebar ul li.active > a i { color: #f43f5e; }
        
        /* Top Navbar */
        .main-body { flex-grow: 1; display: flex; flex-direction: column; min-width: 0; }
        .top-navbar {
            background: #ffffff; padding: 18px 40px; border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: space-between;
        }
        
        .welcome-text-container {
            display: flex; align-items: center; gap: 10px; font-size: 0.95rem; color: #475569; font-weight: 500;
        }
        .welcome-text-container .admin-name { color: #0f172a; font-weight: 700; }
        
        .admin-status-badge {
            background-color: #fef2f2 !important; color: #ef4444 !important;
            font-size: 0.78rem; font-weight: 700; padding: 3px 10px; border-radius: 6px;
            border: 1px solid #fee2e2; display: inline-block; line-height: 1.2;
        }
        
        .btn-logout-red {
            background: #ffffff; color: #ef4444; border: 1px solid #fee2e2;
            padding: 8px 18px; font-size: 0.85rem; font-weight: 600; border-radius: 8px;
            transition: all 0.25s ease; display: flex; align-items: center; gap: 8px; cursor: pointer; text-decoration: none;
        }
        .btn-logout-red:hover { background: #fef2f2; color: #dc2626; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1); }
        .content-container { padding: 40px; flex-grow: 1; }

        /* স্মুথ ট্রানজিশনাল অ্যালার্ট ডিজাইন */
        .custom-alert {
            padding: 16px 20px; border-radius: 12px; margin-bottom: 30px; 
            display: flex; align-items: center; gap: 14px;
            opacity: 1; transform: translateY(0);
            transition: opacity 0.4s ease, transform 0.4s ease, margin 0.4s ease, padding 0.4s ease, height 0.4s ease;
            overflow: hidden;
        }
        .custom-alert.fade-out {
            opacity: 0; transform: translateY(-15px); margin-bottom: 0; padding-top: 0; padding-bottom: 0; height: 0; border: none;
        }
        
        .alert-danger-premium { background: #fff5f5; border: 1px solid #fed7d7; }
        .alert-success-premium { background: #f0fdf4; border: 1px solid #bbf7d0; }
        
        .custom-alert-icon-danger { color: #e53e3e; font-size: 1.3rem; }
        .custom-alert-icon-success { color: #16a34a; font-size: 1.3rem; }
        
        .custom-alert-close { 
            margin-left: auto; cursor: pointer; color: #a0aec0; font-size: 1.5rem; 
            background: none; border: none; line-height: 1; transition: color 0.2s;
        }
        .custom-alert-close:hover { color: #e53e3e; }
        .close-success:hover { color: #16a34a !important; }

        /* পিওর সিএসএস লাক্সারি মোডাল (অ্যানিমেশন সহ) */
        .custom-modal-bg {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0); backdrop-filter: blur(0px);
            z-index: 9999; display: none; align-items: center; justify-content: center;
            transition: all 0.3s ease;
        }
        .custom-modal-bg.show {
            background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px);
        }
        .modal-box {
            background: white; padding: 32px; border-radius: 16px; max-width: 380px; text-align: center;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15); 
            opacity: 0; transform: scale(0.9);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .custom-modal-bg.show .modal-box {
            opacity: 1; transform: scale(1);
        }
        .modal-buttons { display: flex; gap: 12px; margin-top: 24px; }
        .m-btn { flex: 1; padding: 11px; border-radius: 8px; font-weight: 600; font-size: 0.9rem; cursor: pointer; border: none; transition: all 0.2s; }
        .m-btn-cancel { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
        .m-btn-cancel:hover { background: #e2e8f0; }
        .m-btn-confirm { background: #ef4444; color: white; }
        .m-btn-confirm:hover { background: #dc2626; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2); }
    </style>
</head>
<body>

<div class="custom-modal-bg" id="logoutModal">
    <div class="modal-box" id="modalActualBox">
        <div id="logoutModalContent">
            <div style="color: #ef4444; margin-bottom: 15px;"><i class="fa-solid fa-circle-question fa-3x"></i></div>
            <h4 style="font-weight: 700; color: #0f172a; margin-bottom: 8px;">Are you sure want to logout?</h4>
            <p style="color: #64748b; font-size: 0.88rem;">Logging out will end your current administrative session.</p>
            <div class="modal-buttons">
                <button onclick="closeLogoutModal()" class="m-btn m-btn-cancel">Cancel</button>
                <button onclick="executeLogout()" class="m-btn m-btn-confirm">Yes, Logout</button>
            </div>
        </div>
    </div>
</div>

<div class="dashboard-wrapper">
    <nav id="sidebar">
        <div class="sidebar-header">
            <span class="admin-brand-name">
                <i class="fa-solid fa-graduation-cap text-info me-1"></i> Borobhai.com
            </span>
            <div class="sidebar-admin-title">
                <i class="fa-solid fa-shield-halved"></i> Admin Panel
            </div>
        </div>
        <ul class="components">
            <p>Core</p>
            <li class="active"><a href="#"><i class="fa-solid fa-gauge-high"></i> Dashboard</a></li>
            <p>Management</p>
            <li><a href="#"><i class="fa-solid fa-user-check"></i> User Verification</a></li>
            <li><a href="#"><i class="fa-solid fa-briefcase"></i> Job Approvals</a></li>
            <li><a href="#"><i class="fa-solid fa-flag"></i> Reported Posts</a></li>
            <p>Settings</p>
            <li><a href="#"><i class="fa-solid fa-gears"></i> System Settings</a></li>
        </ul>
    </nav>

    <div class="main-body">
        <div class="top-navbar">
            <div class="welcome-text-container">
                Welcome Back, <span class="admin-name">System Administrator</span>
                <span class="admin-status-badge">Admin</span>
            </div>
            
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="button" onclick="openLogoutModal()" class="btn-logout-red">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        </div>

        <div class="content-container">
            @if(session('success') || session('status'))
                <div class="custom-alert alert-success-premium" id="successAlert">
                    <i class="fa-solid fa-circle-check custom-alert-icon-success"></i>
                    <div>
                        <strong style="display:block; color:#1c8454; font-size:0.95rem;">Success!</strong>
                        <span style="color:#146c43; font-size:0.85rem;">{{ session('success') ?? session('status') }}</span>
                    </div>
                    <button class="custom-alert-close close-success" onclick="hideSuccessAlert()">&times;</button>
                </div>
            @endif

            @if(session('warning'))
                <div class="custom-alert alert-danger-premium" id="warningAlert">
                    <i class="fa-solid fa-circle-exclamation custom-alert-icon-danger"></i>
                    <div>
                        <strong style="display:block; color:#9b2c2c; font-size:0.95rem;">Access Denied</strong>
                        <span style="color:#c53030; font-size:0.85rem;">{{ session('warning') }}</span>
                    </div>
                    <button class="custom-alert-close" onclick="hideAlert()">&times;</button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script>
    // মোডাল পপআপ এবং ব্যাকড্রপ অ্যানিমেশন
    function openLogoutModal() { 
        const modal = document.getElementById('logoutModal');
        modal.style.display = 'flex';
        setTimeout(() => { modal.classList.add('show'); }, 10);
    }
    
    function closeLogoutModal() { 
        const modal = document.getElementById('logoutModal');
        modal.classList.remove('show');
        setTimeout(() => { modal.style.display = 'none'; }, 300);
    }
    
    // প্রিমিয়াম টাইমিং মেকানিজম (রিডাইরেক্টের ঠিক আগে সাক্সেস অ্যানিমেশন দেখাবে)
    function executeLogout() { 
        const modalContent = document.getElementById('logoutModalContent');
        
        // মোডালের ভেতরের টেক্সট পরিবর্তন করে সুন্দর সাক্সেস লোডার আনা
        modalContent.innerHTML = `
            <div style="color: #16a34a; margin-bottom: 15px;">
                <i class="fa-solid fa-circle-check fa-4x animate-bounce"></i>
            </div>
            <h4 style="font-weight: 700; color: #0f172a; margin-bottom: 8px;">Logout Successful!</h4>
            <p style="color: #64748b; font-size: 0.88rem; margin-bottom: 10px;">Securing your administrator session...</p>
            <div style="display: flex; justify-content: center; margin-top: 15px;">
                <i class="fa-solid fa-spinner fa-spin text-muted" style="font-size: 1.5rem;"></i>
            </div>
        `;
        
        // ১.২ সেকেন্ড ট্রানজিশনাল টাইম নিয়ে তারপর সাবমিট হবে (ঠাস করে চলে যাবে না)
        setTimeout(() => {
            document.getElementById('logout-form').submit(); 
        }, 1200);
    }

    // অ্যালার্ট বন্ধ করার স্মুথ ট্রানজিশন ফাংশন
    function hideAlert() { 
        const alert = document.getElementById('warningAlert');
        if(alert) {
            alert.classList.add('fade-out');
            setTimeout(() => { alert.remove(); }, 400); 
        }
    }
    
    function hideSuccessAlert() { 
        const alert = document.getElementById('successAlert');
        if(alert) {
            alert.classList.add('fade-out');
            setTimeout(() => { alert.remove(); }, 400); 
        }
    }
</script>
</body>
</html>