<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borobhai.online - Connect, Learn & Grow</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif !important;
            background-color: #f8fafc;
            color: #0f172a;
            overflow-x: hidden;
        }
        /* নেভবার স্টাইল */
        .premium-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            padding: 18px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .nav-brand {
            font-size: 1.4rem;
            font-weight: 800;
            color: #0f172a;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav-brand i { color: #2563eb; }
        .btn-nav-login {
            color: #475569;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            padding: 10px 20px;
            transition: color 0.2s;
        }
        .btn-nav-login:hover { color: #2563eb; }
        .btn-nav-register {
            background: #2563eb;
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            padding: 10px 24px;
            border-radius: 12px;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }
        .btn-nav-register:hover {
            background: #1d4ed8;
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.25);
            transform: translateY(-1px);
        }

        /* হিরো সেকশন */
        .hero-section {
            padding: 100px 0 80px 0;
            background: radial-gradient(circle at 90% 10%, rgba(37, 99, 235, 0.04) 0%, transparent 40%),
                        radial-gradient(circle at 10% 90%, rgba(2, 132, 199, 0.04) 0%, transparent 40%);
        }
        .hero-badge {
            background: rgba(37, 99, 235, 0.06);
            color: #2563eb;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 8px 16px;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 25px;
            border: 1px solid rgba(37, 99, 235, 0.1);
        }
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -1.5px;
            color: #0f172a;
            margin-bottom: 25px;
        }
        .hero-title span {
            color: #2563eb;
            background: linear-gradient(to right, #2563eb, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-subtitle {
            font-size: 1.15rem;
            color: #475569;
            line-height: 1.7;
            margin-bottom: 40px;
            max-width: 540px;
        }
        .hero-cta-group {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* ফিচার কার্ডস */
        .feature-section { padding: 60px 0 100px 0; }
        .feature-card {
            background: #ffffff;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 20px;
            padding: 35px;
            transition: all 0.3s ease;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.04);
            border-color: rgba(37, 99, 235, 0.15);
        }
        .feature-icon {
            width: 50px;
            height: 50px;
            background: rgba(37, 99, 235, 0.06);
            color: #2563eb;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin-bottom: 22px;
        }
        .feature-card h5 { font-weight: 700; color: #0f172a; margin-bottom: 12px; }
        .feature-card p { color: #64748b; font-size: 0.92rem; line-height: 1.6; margin-bottom: 0; }

        /* ডাইনামিক ইমেজ বাবলস (UI ডেকোরেশন) */
        .hero-image-wrapper {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .main-hero-svg {
            width: 100%;
            max-width: 480px;
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body>

    <nav class="premium-nav">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="{{ url('/') }}" class="nav-brand">
                <i class="fa-solid fa-graduation-cap"></i> Borobhai.online
            </a>
            
            <div class="d-flex align-items-center gap-2">
                @if (Route::has('login'))
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="btn-nav-register">Go to Dashboard</a>
                        @elseif(Auth::user()->role === 'student')
                            <a href="{{ route('student.dashboard') }}" class="btn-nav-register">Go to Dashboard</a>
                        @else
                            <a href="{{ route('alumni.dashboard') }}" class="btn-nav-register">Go to Dashboard</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn-nav-login">Sign In</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn-nav-register">Get Started</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 col-12 text-center text-lg-start">
                    <div class="hero-badge">
                        <i class="fa-solid fa-bolt"></i> Next-Gen Student & Alumni Network
                    </div>
                    <h1 class="hero-title">
                        Bridging the Gap Between <span>Students</span> & <span>Alumni</span>
                    </h1>
                    <p class="hero-subtitle">
                        Borobhai.online is a premium community platform designed for real-time mentorship, career guidelines, and secure data sharing across professional networks.
                    </p>
                    
                    <div class="hero-cta-group justify-content-center justify-content-lg-start">
                        @auth
                            @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="btn-nav-register" style="padding: 14px 32px; font-size: 1rem;">Open Dashboard <i class="fa-solid fa-arrow-right ms-2"></i></a>
                            @elseif(Auth::user()->role === 'student')
                                <a href="{{ route('student.dashboard') }}" class="btn-nav-register" style="padding: 14px 32px; font-size: 1rem;">Open Dashboard <i class="fa-solid fa-arrow-right ms-2"></i></a>
                            @else
                                <a href="{{ route('alumni.dashboard') }}" class="btn-nav-register" style="padding: 14px 32px; font-size: 1rem;">Open Dashboard <i class="fa-solid fa-arrow-right ms-2"></i></a>
                            @endif
                        @else
                            <a href="{{ route('register') }}" class="btn-nav-register" style="padding: 14px 32px; font-size: 1rem;">Create Free Account</a>
                            <a href="{{ route('login') }}" class="btn-nav-login fw-bold" style="font-size: 1rem;">Explore Features <i class="fa-solid fa-chevron-right ms-1" style="font-size: 0.8rem;"></i></a>
                        @endauth
                    </div>
                </div>
                
                <div class="col-lg-6 col-12 hero-image-wrapper">
                    <svg class="main-hero-svg" viewBox="0 0 500 500" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="250" cy="250" r="200" fill="#2563eb" fill-opacity="0.03"/>
                        <rect x="120" y="150" width="260" height="200" rx="24" fill="#ffffff" stroke="#e2e8f0" stroke-width="2"/>
                        <circle cx="250" cy="210" r="35" fill="#2563eb" fill-opacity="0.1"/>
                        <path d="M230 210C230 198.954 238.954 190 250 190C261.046 190 270 198.954 270 210C270 221.046 261.046 230 250 230C238.954 230 230 221.046 230 210Z" fill="#2563eb"/>
                        <path d="M200 290C200 267.909 217.909 250 240 250H260C282.091 250 300 267.909 300 290V310H200V290Z" fill="#2563eb" fill-opacity="0.4"/>
                        <rect x="150" y="380" width="200" height="12" rx="6" fill="#e2e8f0"/>
                    </svg>
                </div>
            </div>
        </div>
    </section>

    <section class="feature-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4 col-12">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fa-solid fa-user-graduate"></i>
                        </div>
                        <h5>Alumni Mentorship</h5>
                        <p>Get directly connected with seniors and alumni working in top global companies to get real-world industrial guidelines.</p>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fa-solid fa-bolt-lightning"></i>
                        </div>
                        <h5>Blazing Fast AJAX UI</h5>
                        <p>Experience completely seamless transitions with zero-refresh operations powered by an advance async background architecture.</p>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h5>Role-Based Protection</h5>
                        <p>Strict identity isolation ensures students, graduates, and administrators safely access their allocated ecosystems securely.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>
</html>