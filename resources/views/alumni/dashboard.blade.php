@extends('Layout.user')

@section('title', 'Alumni Portal Dashboard')

@section('custom_style')
<style>
    /* Premium Grid System for Alumni */
    .alumni-grid-system {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 30px !important;
        margin-bottom: 50px !important;
    }
    
    .alumni-premium-card {
        background: #ffffff !important;
        border-radius: 16px !important;
        padding: 26px 28px !important;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.02) !important;
        border: 1px solid #e2e8f0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        transition: all 0.25s ease;
    }
    
    .alumni-premium-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px -5px rgba(30, 27, 75, 0.05) !important;
    }
    
    .a-label {
        color: #64748b;
        text-transform: uppercase;
        font-size: 0.75rem;
        font-weight: 700;
        display: block;
        margin-bottom: 8px;
        letter-spacing: 0.5px;
    }
    
    .a-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1e1b4b;
        line-height: 1;
    }
    
    .a-icon {
        font-size: 1.7rem;
        padding: 14px;
        border-radius: 12px;
        background: #eff6ff;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Action Banner Design */
    .action-banner {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        border-radius: 16px;
        padding: 35px 40px;
        color: #ffffff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        box-shadow: 0 10px 25px -5px rgba(30, 27, 75, 0.1);
    }

    .btn-alumni-action {
        background: #ffffff;
        color: #1e1b4b;
        font-weight: 600;
        padding: 12px 28px;
        border-radius: 10px;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 2px solid transparent;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-alumni-action:hover {
        background: transparent;
        color: #ffffff;
        border-color: #ffffff;
        transform: scale(1.03);
    }
</style>
@endsection

@section('content')
<div class="mb-5">
    <h2 class="fw-bold text-dark mb-2" style="font-size: 2rem; letter-spacing: -0.6px;">Alumni Dashboard</h2>
    <p class="text-muted small mb-0" style="font-size: 0.95rem;">Manage your job circulars, guide current students, and build community networks.</p>
</div>

<div class="alumni-grid-system">
    <div class="alumni-premium-card" style="border-left: 6px solid #2563eb !important;">
        <div>
            <span class="a-label">My Job Posts</span>
            <span class="a-value">4</span>
        </div>
        <div class="a-icon"><i class="fa-solid fa-file-circle-plus"></i></div>
    </div>
    
    <div class="alumni-premium-card" style="border-left: 6px solid #7c3aed !important;">
        <div>
            <span class="a-label">Total Applicants</span>
            <span class="a-value">38</span>
        </div>
        <div class="a-icon" style="background:#f5f3ff; color:#7c3aed;"><i class="fa-solid fa-users-rectangle"></i></div>
    </div>
    
    <div class="alumni-premium-card" style="border-left: 6px solid #db2777 !important;">
        <div>
            <span class="a-label">Student Queries</span>
            <span class="a-value">7</span>
        </div>
        <div class="a-icon" style="background:#fdf2f8; color:#db2777;"><i class="fa-solid fa-comments"></i></div>
    </div>
</div>

<div class="action-banner mt-4">
    <div>
        <h4 class="fw-bold mb-2"><i class="fa-solid fa-rocket me-2 text-info"></i> Want to Hire Fresh Talent?</h4>
        <p class="mb-0 text-white-50 small">Create a new job opportunity and filter out the best candidates from your university.</p>
    </div>
    <div>
        <a href="#" class="btn-alumni-action">
            <i class="fa-solid fa-plus-circle"></i> Post a New Job Circular
        </a>
    </div>
</div>
@endsection