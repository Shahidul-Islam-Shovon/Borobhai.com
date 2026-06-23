<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User Portal') - Borobhai.online</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" type="text/css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" type="text/css">
    
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'Inter', sans-serif !important; 
            background: #f8fafc !important; 
        }
        .dashboard-wrapper { display: flex; min-height: 100vh; width: 100%; }
        
        /* Deep Luxury Dark Indigo Sidebar */
        #sidebar {
            width: 270px; min-width: 270px;
            background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 100%); 
            color: #ffffff;
            box-shadow: 5px 0 25px rgba(15, 23, 42, 0.08);
            display: flex;
            flex-direction: column;
        }
        
        /* Centered Header Box */
        #sidebar .sidebar-header {
            padding: 26px 24px 20px 24px; 
            background: #020617;
            border-bottom: 1px solid #1e1b4b;
            text-align: center; /* Forces everything to center */
        }
        
        #sidebar .brand-title {
            font-size: 1.35rem; 
            font-weight: 700; 
            letter-spacing: -0.5px;
            display: inline-block;
            margin-bottom: 2px;
        }
        
        /* Premium Centered Badge Design */
        .role-badge-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
        }
        .role-badge {
            font-size: 0.78rem; 
            font-weight: 600; 
            letter-spacing: 0.3px; 
            padding: 5px 14px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        
        #sidebar ul.components { padding: 20px 0; list-style: none; flex-grow: 1; }
        #sidebar ul p {
            color: #818cf8; padding: 10px 25px; font-size: 0.75rem;
            text-transform: uppercase; font-weight: 700; letter-spacing: 1.2px; margin: 0;
        }
        #sidebar ul li a {
            padding: 14px 25px; font-size: 0.95rem; display: block;
            color: #94a3b8; text-decoration: none; transition: all 0.25s ease; font-weight: 500;
        }
        #sidebar ul li a:hover { color: #ffffff; background: rgba(99, 102, 241, 0.1); padding-left: 30px; }
        #sidebar ul li.active > a {
            color: #38bdf8; background: #1e1b4b; border-left: 4px solid #38bdf8; font-weight: 600;
        }
        #sidebar ul li a i { margin-right: 12px; width: 20px; text-align: center; font-size: 1.05rem; }
        
        .main-body { flex-grow: 1; background: #f1f5f9; display: flex; flex-direction: column; min-width: 0; }
        
        .top-navbar {
            background: rgba(255, 255, 255, 0.95); padding: 20px 45px; border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px -10px rgba(15, 23, 42, 0.05);
            display: flex; align-items: center; justify-content: space-between;
            position: relative; z-index: 10; backdrop-filter: blur(8px);
        }
        
        .btn-premium-logout {
            background: #ffffff; color: #ef4444; border: 1.5px solid #fee2e2;
            padding: 9px 22px; font-size: 0.85rem; font-weight: 600; border-radius: 10px;
            transition: all 0.25s ease; display: flex; align-items: center; gap: 8px; cursor: pointer;
        }
        .btn-premium-logout:hover {
            background: #fef2f2; border-color: #f87171; color: #dc2626;
            transform: translateY(-2px); box-shadow: 0 4px 12px rgba(239, 68, 68, 0.12);
        }
        
        .content-container { padding: 40px 45px; flex-grow: 1; }

        /* Fullscreen Logout Screen Overlay */
        #logout-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: #0f172a; z-index: 99999; display: flex;
            flex-direction: column; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none; transition: opacity 0.4s ease; color: #ffffff;
        }
        #logout-overlay.show { opacity: 1; pointer-events: auto; }
        .spinner-luxury {
            width: 50px; height: 50px; border: 4px solid rgba(56, 189, 248, 0.1);
            border-radius: 50%; border-top-color: #38bdf8;
            animation: spin 0.8s cubic-bezier(0.4, 0, 0.2, 1) infinite; margin-bottom: 20px;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Custom Pure JS Modal (Bootstrap JS-Independent) */
        .custom-popup-backdrop {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px);
            z-index: 9999; display: flex; align-items: center; justify-content: center;
            opacity: 0; pointer-events: none; transition: all 0.3s ease;
        }
        .custom-popup-backdrop.open { opacity: 1; pointer-events: auto; }
        .modal-premium-content {
            background: #ffffff; border-radius: 20px; max-width: 380px; width: 90%;
            padding: 30px 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: scale(0.9); transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-align: center;
        }
        .custom-popup-backdrop.open .modal-premium-content { transform: scale(1); }
    </style>
    @yield('custom_style')
</head>
<body>

<div id="logout-overlay">
    <div class="spinner-luxury"></div>
    <h5 class="fw-bold mb-1" style="letter-spacing: -0.2px;">Signing Out Securely...</h5>
    <p class="text-muted small">Please wait a moment.</p>
</div>

<div class="custom-popup-backdrop" id="logoutCustomModal">
    <div class="modal-premium-content">
        <div class="text-danger mb-3">
            <i class="fa-solid fa-circle-question" style="font-size: 3.5rem;"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2" style="font-size: 1.25rem;">Are you sure want to logout?</h5>
        <p class="text-muted small mb-4">You will need to enter your credentials again to access your dashboard workspace.</p>
        
        <div class="d-flex gap-3 justify-content-center">
            <button type="button" onclick="closeLogoutModal()" class="btn btn-light w-50 fw-semibold text-secondary py-2.5 rounded-3 border" style="font-size:0.9rem;">Cancel</button>
            <button type="button" onclick="confirmAndExecuteLogout()" class="btn btn-danger w-50 fw-semibold py-2.5 rounded-3" style="font-size:0.9rem; background:#ef4444; border:none; color:white;">Yes, Logout</button>
        </div>
    </div>
</div>

<div class="dashboard-wrapper">
    <nav id="sidebar">
        <div class="sidebar-header">
            <span class="brand-title"><i class="fa-solid fa-graduation-cap text-info me-2"></i>Borobhai.online</span>
            
            <div class="role-badge-container">
                @if(Auth::user()->role === 'student')
                    <div class="role-badge" style="background: rgba(56, 189, 248, 0.12); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.2);">
                        <i class="fa-solid fa-user" style="font-size: 0.75rem;"></i> Student Dashboard
                    </div>
                @elseif(Auth::user()->role === 'alumni')
                    <div class="role-badge" style="background: rgba(16, 185, 129, 0.12); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2);;">
                        <i class="fa-solid fa-user-graduate" style="font-size: 0.75rem;"></i> Alumni Dashboard
                    </div>
                @elseif(Auth::user()->role === 'admin')
                    <div class="role-badge" style="background: rgba(244, 63, 94, 0.12); color: #f43f5e; border: 1px solid rgba(244, 63, 94, 0.2);">
                        <i class="fa-solid fa-shield-halved" style="font-size: 0.75rem;"></i> Admin Panel
                    </div>
                @endif
            </div>
        </div>
        
        <ul class="list-unstyled components">
            <p>Workspace</p>
            <li class="active">
                <a href="#"><i class="fa-solid fa-chart-pie"></i> Dashboard Overview</a>
            </li>
            
            @if(Auth::user()->role === 'student')
                <p>Student Features</p>
                <li><a href="#"><i class="fa-solid fa-briefcase"></i> Browse Job Posts</a></li>
                <li><a href="#"><i class="fa-solid fa-user-tie"></i> Find a Mentor (Bhai)</a></li>
                <li><a href="#"><i class="fa-solid fa-file-invoice"></i> My Applications</a></li>
            @elseif(Auth::user()->role === 'alumni')
                <p>Alumni Features</p>
                <li><a href="#"><i class="fa-solid fa-file-circle-plus"></i> Post a New Job</a></li>
                <li><a href="#"><i class="fa-solid fa-users-rectangle"></i> Track Applicants</a></li>
                <li><a href="#"><i class="fa-solid fa-comments"></i> Student Queries</a></li>
            @endif

            <p>Personal</p>
            <li><a href="#"><i class="fa-solid fa-user-gear"></i> Profile Settings</a></li>
        </ul>
    </nav>

    <div class="main-body">
        <div class="top-navbar">
            <div class="fw-semibold text-secondary" style="font-size: 0.95rem;">
                Welcome Back, <span class="text-dark fw-bold">{{ Auth::user()->name }}</span> 
                <span class="badge ms-2 px-2.5 py-1.5" style="font-weight: 600; font-size: 0.75rem; background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe;">
                    {{ ucfirst(Auth::user()->role) }}
                </span>
            </div>
            <div>
                <form id="logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="button" onclick="openLogoutModal()" class="btn-premium-logout">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <div class="content-container">
            @if(session('warning'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-5 py-3.5 px-4 small fw-medium" id="globalWarningAlert" role="alert" style="background: #fef2f2; color: #991b1b; border-left: 6px solid #ef4444 !important;">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid fa-circle-exclamation me-3 text-danger fs-4"></i>  
                        <div>
                            <strong class="d-block mb-0.5" style="font-size:0.95rem; font-weight:700;">Access Denied</strong>
                            <span class="text-secondary small">{{ session('warning') }}</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" onclick="dismissWarningAlert()" aria-label="Close" style="top:18px;"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/bundle.min.js"></script>
<script>
    function dismissWarningAlert() {
        const alertBox = document.getElementById('globalWarningAlert');
        if(alertBox) {
            alertBox.style.transition = "opacity 0.3s ease, transform 0.3s ease";
            alertBox.style.opacity = "0";
            alertBox.style.transform = "translateY(-10px)";
            setTimeout(() => { alertBox.remove(); }, 300);
        }
    }

    function openLogoutModal() {
        document.getElementById('logoutCustomModal').classList.add('open');
    }

    function closeLogoutModal() {
        document.getElementById('logoutCustomModal').classList.remove('open');
    }

    function confirmAndExecuteLogout() {
        closeLogoutModal();
        document.getElementById('logout-overlay').classList.add('show');
        setTimeout(() => { 
            document.getElementById('logout-form').submit(); 
        }, 1100);
    }
</script>
@yield('custom_script')
</body>
</html>