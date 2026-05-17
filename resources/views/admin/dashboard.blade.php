@extends('Layout.admin')

@section('title', 'Admin Dashboard')

@section('content')
<style>
    .dashboard-header-block { display: flex; align-items: center; justify-content: space-between; margin-bottom: 35px; }
    .header-title-main { font-size: 2rem; font-weight: 700; color: #0f172a; letter-spacing: -0.5px; margin-bottom: 4px; }
    .header-sub-main { color: #64748b; font-size: 0.9rem; }
    
    .date-badge-premium { 
        background: #ffffff; border: 1px solid #e2e8f0; padding: 10px 16px; 
        border-radius: 8px; font-weight: 600; color: #475569; font-size: 0.85rem; box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    
    .stats-card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px; margin-bottom: 35px; }
    .card-luxury-box {
        background: #ffffff; border-radius: 16px; padding: 24px; border: 1px solid #e2e8f0;
        display: flex; align-items: center; justify-content: space-between; transition: all 0.3s ease;
    }
    .card-luxury-box:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.03); }
    
    .box-number { font-size: 2rem; font-weight: 700; color: #0f172a; margin-top: 4px; }
    .box-title-text { color: #64748b; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .box-icon-container { font-size: 1.5rem; padding: 14px; border-radius: 12px; }

    .dashboard-splits { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
    @media (max-width: 992px) { .dashboard-splits { grid-template-columns: 1fr; } }
    .split-card { background: #ffffff; padding: 24px; border-radius: 16px; border: 1px solid #e2e8f0; }
    
    .chart-header-flex { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
    .live-dot-badge { background: #edf5ff; color: #2563eb; font-size: 0.75rem; font-weight: 700; padding: 5px 12px; border-radius: 6px; }

    .control-btn-stack { display: flex; flex-direction: column; gap: 12px; margin-top: 20px; }
    .btn-action-core {
        width: 100%; padding: 14px; border-radius: 10px; font-weight: 600; font-size: 0.9rem;
        display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; border: none; transition: 0.2s;
    }
    .btn-core-dark { background: #0f172a; color: white; }
    .btn-core-dark:hover { background: #1e293b; }
    .btn-core-outline { background: white; color: #0f172a; border: 1px solid #e2e8f0; }
    .btn-core-outline:hover { background: #f8fafc; }
</style>

<div class="dashboard-header-block">
    <div>
        <h2 class="header-title-main">System Dashboard</h2>
        <p class="header-sub-main">Real-time architecture and platform data metrics</p>
    </div>
    <div class="date-badge-premium">
        <i class="fa-solid fa-calendar-day me-2 text-danger"></i> Sunday, May 17, 2026
    </div>
</div>

<div class="stats-card-grid">
    <div class="card-luxury-box" style="border-left: 5px solid #2563eb;">
        <div><span class="box-title-text">Total Users</span><div class="box-number">1,245</div></div>
        <div class="box-icon-container" style="background:#eff6ff; color:#2563eb;"><i class="fa-solid fa-users"></i></div>
    </div>
    <div class="card-luxury-box" style="border-left: 5px solid #10b981;">
        <div><span class="box-title-text">Alumni Network</span><div class="box-number">482</div></div>
        <div class="box-icon-container" style="background:#ecfdf5; color:#10b981;"><i class="fa-solid fa-user-graduate"></i></div>
    </div>
    <div class="card-luxury-box" style="border-left: 5px solid #06b6d4;">
        <div><span class="box-title-text">Live Vacancies</span><div class="box-number">87</div></div>
        <div class="box-icon-container" style="background:#ecfeff; color:#06b6d4;"><i class="fa-solid fa-briefcase"></i></div>
    </div>
    <div class="card-luxury-box" style="border-left: 5px solid #f43f5e;">
        <div><span class="box-title-text">Pending Reviews</span><div class="box-number">14</div></div>
        <div class="box-icon-container" style="background:#fff1f2; color:#f43f5e;"><i class="fa-solid fa-clock-rotate-left"></i></div>
    </div>
</div>

<div class="dashboard-splits">
    <div class="split-card">
        <div class="chart-header-flex">
            <div>
                <h5 style="font-weight: 700; color: #0f172a; margin-bottom: 2px;">User Metrics Stream</h5>
                <p style="color: #64748b; font-size: 0.8rem;">Platform registration analysis</p>
            </div>
            <span class="live-dot-badge">Live Tracking</span>
        </div>
        <div style="position: relative; height:260px; width:100%;">
            <canvas id="metricsChartContainer"></canvas>
        </div>
    </div>
    
    <div class="split-card" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <h5 style="font-weight: 700; color: #0f172a; margin-bottom: 2px;">Administrative Core</h5>
            <p style="color: #64748b; font-size: 0.8rem;">Fast control triggers</p>
        </div>
        <div class="control-btn-stack">
            <button class="btn-action-core btn-core-dark"><i class="fa-solid fa-user-shield"></i> Verify Profiles</button>
            <button class="btn-action-core btn-core-outline"><i class="fa-solid fa-paper-plane"></i> System Broadcast</button>
            <button class="btn-action-core btn-core-outline" style="background: #f8fafc;"><i class="fa-solid fa-database"></i> Database Backup</button>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('metricsChartContainer').getContext('2d');
        
        const gradient = ctx.createLinearGradient(0, 0, 0, 250);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.15)');
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Users',
                    data: [150, 320, 510, 740, 980, 1245],
                    borderColor: '#2563eb',
                    borderWidth: 2.5,
                    fill: true,
                    backgroundColor: gradient,
                    tension: 0.35,
                    pointBackgroundColor: '#2563eb',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8', font: { family: 'Inter' } } },
                    x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { family: 'Inter' } } }
                }
            }
        });
    });
</script>
@endsection