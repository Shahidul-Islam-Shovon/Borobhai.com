@extends('Layout.user')

@section('title', 'Student Portal Dashboard')

@section('custom_style')
<style>
    .student-grid-system {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 30px !important;
        margin-bottom: 40px !important;
    }
    .student-premium-card {
        background: #ffffff !important;
        border-radius: 20px !important;
        padding: 28px 30px !important;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.02), 0 4px 12px -2px rgba(15, 23, 42, 0.02) !important;
        border: 1px solid #f1f5f9 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .student-premium-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 35px -10px rgba(99, 102, 241, 0.12) !important;
    }
    .s-label {
        color: #64748b; text-transform: uppercase; font-size: 0.75rem; font-weight: 700; display: block; margin-bottom: 8px; letter-spacing: 0.5px;
    }
    .s-value {
        font-size: 2.2rem; font-weight: 700; color: #0f172a; line-height: 1; letter-spacing: -1px;
    }
    .s-icon {
        font-size: 1.6rem; padding: 16px; border-radius: 16px; display: flex; align-items: center; justify-content: center; transition: all 0.3s;
    }
    
    /* Neon Glow Badges */
    .card-indigo { border-left: 6px solid #6366f1 !important; }
    .card-indigo .s-icon { background: #e0e7ff; color: #6366f1; }
    .card-cyan { border-left: 6px solid #06b6d4 !important; }
    .card-cyan .s-icon { background: #ecfeff; color: #06b6d4; }
    .card-emerald { border-left: 6px solid #10b981 !important; }
    .card-emerald .s-icon { background: #dcfce7; color: #10b981; }

    /* Premium Wave Banner */
    .student-welcome-banner {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border-radius: 20px; padding: 35px 40px; color: #ffffff; margin-bottom: 40px;
        box-shadow: 0 12px 30px -5px rgba(79, 70, 229, 0.25);
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;
    }
    .btn-student-action {
        background: rgba(255, 255, 255, 0.15); color: #ffffff; font-weight: 600; padding: 12px 26px;
        border-radius: 12px; text-decoration: none; backdrop-filter: blur(4px); transition: all 0.2s;
        border: 1px solid rgba(255, 255, 255, 0.25); display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-student-action:hover { background: #ffffff; color: #4f46e5; transform: scale(1.03); }
</style>
@endsection

@section('content')
<div class="student-welcome-banner">
    <div>
        <h3 class="fw-bold mb-2">Build Your Dream Career, {{ Auth::user()->name }}! 🚀</h3>
        <p class="mb-0 text-white-50 small">Connect with your university alumni (Borobhais) and secure top industry recommendations.</p>
    </div>
    <div>
        <a href="#" class="btn-student-action"><i class="fa-solid fa-magnifying-glass"></i> Explore Jobs Now</a>
    </div>
</div>

<div class="student-grid-system">
    <div class="student-premium-card card-indigo">
        <div>
            <span class="s-label">My Applications</span>
            <span class="s-value">12</span>
        </div>
        <div class="s-icon"><i class="fa-solid fa-file-invoice"></i></div>
    </div>
    
    <div class="student-premium-card card-cyan">
        <div>
            <span class="s-label">Live Circulars</span>
            <span class="s-value">45</span>
        </div>
        <div class="s-icon"><i class="fa-solid fa-briefcase"></i></div>
    </div>
    
    <div class="student-premium-card card-emerald">
        <div>
            <span class="s-label">Alumni Mentors</span>
            <span class="s-value">8</span>
        </div>
        <div class="s-icon"><i class="fa-solid fa-user-graduate"></i></div>
    </div>
</div>
@endsection