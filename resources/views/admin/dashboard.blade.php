<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Premium Admin Control Center - Borobhai.com</title>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; margin: 0; padding: 0; }
        .sidebar { width: 260px; height: 100vh; background: #0f172a; position: fixed; top: 0; left: 0; padding: 25px 15px; color: #fff; z-index: 100; }
        .main-content { margin-left: 260px; padding: 40px; min-height: 100vh; }
        .sidebar-brand { font-size: 1.4rem; font-weight: 800; color: #3b82f6; margin-bottom: 35px; text-align: center; }
        .nav-link-custom { display: flex; align-items: center; gap: 12px; padding: 12px 18px; color: #94a3b8; font-weight: 500; border-radius: 12px; text-decoration: none; margin-bottom: 8px; cursor: pointer; transition: all 0.2s; }
        .nav-link-custom:hover, .nav-link-custom.active { background: #1e293b; color: #fff; }
        .nav-link-custom.active { border-left: 4px solid #3b82f6; }
        
        .counter-card { background: #fff; border-radius: 20px; padding: 25px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; }
        .counter-icon { width: 55px; height: 55px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        
        /* 📋 ফাইনাল ক্লিন ও ছোট ফন্ট টেবিল UI */
        .card-table-wrapper { background: #fff; border-radius: 20px; padding: 25px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.02); }
        .table { font-size: 0.82rem !important; } 
        .table th { background: #f8fafc !important; color: #64748b !important; font-weight: 600; font-size: 0.78rem; text-transform: uppercase; border-bottom: 1px solid #e2e8f0 !important; padding: 12px 14px !important; }
        .table td { padding: 12px 14px !important; vertical-align: middle; color: #334155; }
        
        /* ড্রপডাউন অপ্টিমাইজেশন */
        .form-select-sm-custom { padding: 4px 8px !important; font-size: 0.78rem !important; border-radius: 6px; font-weight: 500; min-width: 105px; height: auto !important; }

        .badge-active { background-color: #d1fae5; color: #065f46; font-weight: 600; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 4px; }
        .badge-suspended { background-color: #fee2e2; color: #991b1b; font-weight: 600; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 4px; }
        .badge-pending { background-color: #fef3c7; color: #92400e; font-weight: 600; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 4px; }
        
        /* সিস্টেম অ্যাকশন প্রফেশনাল বাটন স্টাইল */
        .btn-action-pill { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 0.75rem; font-weight: 600; border-radius: 6px; border: 1px solid #e2e8f0; background: #fff; cursor: pointer; transition: all 0.2s; text-decoration: none; }
        .btn-action-pill.temp-ban { color: #d97706; }
        .btn-action-pill.temp-ban:hover { background: #fff7ed; border-color: #fdba74; }
        .btn-action-pill.perm-ban { color: #dc2626; }
        .btn-action-pill.perm-ban:hover { background: #fef2f2; border-color: #fca5a5; }
        .btn-action-pill.activate { color: #16a34a; background: #f0fdf4; border-color: #86efac; }
        .btn-action-pill.activate:hover { background: #bbf7d0; }

        /* ডাইনামিক স্ট্যাটাস টেক্সট লেবেল */
        .text-suspended-label { font-weight: 700; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.3px; }
        .text-suspended-label.temp { color: #d97706; }
        .text-suspended-label.perm { color: #dc2626; }

        .tab-content-panel { display: none; }
        .tab-content-panel.active { display: block; animation: fadeIn 0.3s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand"><i class="fa-solid fa-graduation-cap me-2"></i>Borobhai Admin</div>
    <div class="nav-link-custom active" data-target="analytics-tab"><i class="fa-solid fa-chart-pie"></i> Analytics Dashboard</div>
    <div class="nav-link-custom" data-target="users-tab"><i class="fa-solid fa-users"></i> User Control Panel</div>
    <div class="nav-link-custom" data-target="posts-tab"><i class="fa-solid fa-newspaper"></i> Post Moderation</div>
    <div class="nav-link-custom" data-target="jobs-tab"><i class="fa-solid fa-briefcase"></i> Job Portal Audits</div>
    
    <div style="position: absolute; bottom: 30px; width: calc(100% - 30px);">
        <form id="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="button" onclick="confirmLogout()" class="w-100 btn btn-danger btn-sm" style="border-radius: 10px; padding: 10px;">
                <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Sign Out
            </button>
        </form>
    </div>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-extrabold h3 mb-1 text-slate-900">Welcome back, Chief Admin!</h1>
            <p class="text-muted small mb-0">Borobhai.com control center is completely active.</p>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="counter-card">
                <div><p class="text-muted small fw-bold mb-1">TOTAL USERS</p><h3 class="fw-bold mb-0">{{ $counters['total_users'] }}</h3></div>
                <div class="counter-icon text-primary" style="background: rgba(59,130,246,0.1);"><i class="fa-solid fa-users"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="counter-card">
                <div><p class="text-muted small fw-bold mb-1">STUDENTS</p><h3 class="fw-bold mb-0">{{ $counters['total_students'] }}</h3></div>
                <div class="counter-icon text-success" style="background: rgba(16,185,129,0.1);"><i class="fa-solid fa-user-graduate"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="counter-card">
                <div><p class="text-muted small fw-bold mb-1">ALUMNI</p><h3 class="fw-bold mb-0">{{ $counters['total_alumni'] }}</h3></div>
                <div class="counter-icon text-warning" style="background: rgba(245,158,11,0.1);"><i class="fa-solid fa-briefcase"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="counter-card">
                <div><p class="text-muted small fw-bold mb-1">CIRCULARS</p><h3 class="fw-bold mb-0" id="count-circulars">{{ $counters['total_circulars'] }}</h3></div>
                <div class="counter-icon text-info" style="background: rgba(6,182,212,0.1);"><i class="fa-solid fa-scroll"></i></div>
            </div>
        </div>
    </div>

    <div id="analytics-tab" class="tab-content-panel active">
        <div class="card p-4 shadow-sm border-0" style="border-radius: 20px; background: #fff;">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-chart-line text-primary me-2"></i> Registration & Engagement Metrics</h5>
            <div style="position: relative; height: 380px; width: 100%; padding: 10px;">
                <canvas id="adminTrendChart"></canvas>
            </div>
        </div>
    </div>

    <div id="users-tab" class="tab-content-panel">
        <div class="card-table-wrapper">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-users-gear text-primary me-2"></i> Member Control Panel</h5>
            <div class="table-responsive">
                <table id="usersTable" class="table table-hover align-middle w-100">
                    <thead>
                        <tr>
                            <th style="width: 8%;">UID</th>
                            <th style="width: 27%;">User Details</th>
                            <th style="width: 22%;">Email Address</th>
                            <th style="width: 15%;">Account Role</th>
                            <th style="width: 10%; text-align: center;">Status</th>
                            <th style="width: 18%; text-align: right;">System Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr id="user-row-{{ $user->id }}">
                            <td class="fw-bold text-secondary">#{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center fw-bold text-secondary" style="width: 35px; height: 35px; min-width: 35px; font-size: 0.82rem;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.82rem;">
                                            {{ $user->name }} 
                                            @if(auth()->id() == $user->id) 
                                                <span class="badge bg-primary ms-1" style="font-size: 9px; padding: 2px 4px;">You</span> 
                                            @endif
                                        </div>
                                        <div class="text-muted mt-0.5" style="font-size: 0.72rem; font-weight: 500;">
                                            Joined: {{ $user->created_at->format('d M Y') }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-secondary" style="font-size: 0.78rem;">{{ $user->email }}</td>
                            <td>
                                @if(auth()->id() == $user->id)
                                    <span class="badge bg-dark px-2 py-1" style="border-radius: 4px; font-size: 0.75rem;">Admin</span>
                                @else
                                    <select data-previous="{{ $user->role }}" onchange="confirmRoleChange({{ $user->id }}, this)" class="form-select form-select-sm form-select-sm-custom">
                                        <option value="student" {{ $user->role == 'student' ? 'selected' : '' }}>Student</option>
                                        <option value="alumni" {{ $user->role == 'alumni' ? 'selected' : '' }}>Alumni</option>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="{{ $user->status === 'active' ? 'badge-active' : ($user->status === 'suspended_temp' ? 'badge-pending' : 'badge-suspended') }}">
                                    @if($user->status === 'active') <i class="fa-solid fa-circle-check"></i> Active 
                                    @elseif($user->status === 'suspended_temp')  Tempurary Suspended 
                                    @else Blocked Permanently 
                                    @endif
                                </span>
                            </td>
                            <td style="text-align: right;">
                                @if(auth()->id() == $user->id)
                                    <span class="text-muted style-disabled" style="font-size: 0.75rem;"><i class="fa-solid fa-lock"></i> Secured</span>
                                @else
                                    <div class="d-flex justify-content-end align-items-center gap-1">
                                        @if($user->status === 'active')
                                            <button onclick="manageSuspension({{ $user->id }}, 'temp')" class="btn-action-pill temp-ban" title="Suspend 7 Days">
                                                 Suspend 7 Days
                                            </button>
                                            <button onclick="manageSuspension({{ $user->id }}, 'perm')" class="btn-action-pill perm-ban" title="Suspend Permanently">
                                                Suspend Permanently
                                            </button>
                                        @else
                                            @if($user->status === 'suspended_temp')
                                                <span class="text-suspended-label temp me-2">
                                            @else
                                                <span class="text-suspended-label perm me-2">
                                            @endif
                                            
                                            <button onclick="manageSuspension({{ $user->id }}, 'active')" class="btn-action-pill activate" title="Remove Suspension">
                                                <i class="fa-solid fa-circle-check"></i> Remove Suspension
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> 
        </div>
    </div>

    <div id="posts-tab" class="tab-content-panel">
        <div class="card-table-wrapper">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-shield-halved text-danger me-2"></i> Social Feed Moderation Logs</h5>
            <div class="table-responsive">
                <table id="postsTable" class="table table-hover align-middle w-100">
                    <thead>
                        <tr>
                            <th style="width: 15%;">ID</th>
                            <th style="width: 25%;">Author</th>
                            <th style="width: 45%;">Post Excerpt</th>
                            <th style="width: 15%; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                        <tr id="post-row-{{ $post->id }}">
                            <td>#{{ $post->id }}</td>
                            <td><span class="fw-medium">{{ $post->user->name ?? 'Unknown' }}</span></td>
                            <td><span class="text-muted d-inline-block text-truncate" style="max-width: 450px;">{{ $post->content }}</span></td>
                            <td class="text-end">
                                <button onclick="deletePost({{ $post->id }})" class="btn btn-sm btn-light text-danger border px-3" style="border-radius: 8px;">
                                    <i class="fa-regular fa-trash-can me-1"></i> Terminate
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="jobs-tab" class="tab-content-panel">
        <div class="card-table-wrapper">
            <h5 class="fw-bold mb-4"><i class="fa-solid fa-briefcase text-warning me-2"></i> Corporate Job Circular Audits</h5>
            <div class="table-responsive">
                <table id="circularsTable" class="table table-hover align-middle w-100">
                    <thead>
                        <tr>
                            <th style="width: 15%;">ID</th>
                            <th style="width: 25%;">Posted By</th>
                            <th style="width: 45%;">Job Profile & Company</th>
                            <th style="width: 15%; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($circulars as $circular)
                        <tr id="circular-row-{{ $circular->id }}">
                            <td>#{{ $circular->id }}</td>
                            <td><span class="fw-medium text-dark">{{ $circular->user->name ?? 'Alumni' }}</span></td>
                            <td><div><strong class="text-primary">{{ $circular->title }}</strong></div><span class="text-muted small"><i class="fa-solid fa-building me-1"></i> {{ $circular->company }}</span></td>
                            <td class="text-end">
                                <button onclick="deleteCircular({{ $circular->id }})" class="btn btn-sm btn-light text-danger border px-3" style="border-radius: 8px;">
                                    <i class="fa-solid fa-ban me-1"></i> Remove
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// ==========================================
// ১. ট্যাব মেমোরি এবং স্ক্রোল রিস্টোর ইঞ্জিন 
// ==========================================
document.addEventListener("DOMContentLoaded", function() {
    const activeTab = localStorage.getItem('admin_active_tab');
    if (activeTab) {
        document.querySelectorAll('.nav-link-custom').forEach(l => l.classList.remove('active'));
        document.querySelectorAll('.tab-content-panel').forEach(t => t.classList.remove('active'));
        
        const tabBtn = document.querySelector(`[data-target="${activeTab}"]`);
        if (tabBtn) tabBtn.classList.add('active');
        const tabContent = document.getElementById(activeTab);
        if (tabContent) tabContent.classList.add('active');
    }

    const savedScrollPos = localStorage.getItem("admin_scroll_pos");
    if (savedScrollPos) {
        setTimeout(() => {
            window.scrollTo(0, parseInt(savedScrollPos));
            localStorage.removeItem("admin_scroll_pos"); 
        }, 60);
    }
});

function saveDashboardState() {
    localStorage.setItem("admin_scroll_pos", window.scrollY);
    const activeTabEl = document.querySelector('.nav-link-custom.active');
    if (activeTabEl) {
        localStorage.setItem('admin_active_tab', activeTabEl.getAttribute('data-target'));
    }
}

document.querySelectorAll('.nav-link-custom').forEach(link => {
    link.addEventListener('click', function() {
        document.querySelectorAll('.nav-link-custom').forEach(l => l.classList.remove('active'));
        document.querySelectorAll('.tab-content-panel').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        document.getElementById(this.getAttribute('data-target')).classList.add('active');
        localStorage.setItem('admin_active_tab', this.getAttribute('data-target'));
    });
});

// ==========================================
// ২. JQUERY DATATABLES INITIALIZATION
// ==========================================
$(document).ready(function() {
    $('#usersTable').DataTable({ "pageLength": 10, "responsive": true, "order": [[0, "asc"]] });
    $('#postsTable').DataTable({ "pageLength": 10, "responsive": true, "order": [[0, "desc"]] });
    $('#circularsTable').DataTable({ "pageLength": 10, "responsive": true, "order": [[0, "desc"]] });
});

const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });

// ==========================================
// ৩. রোল পরিবর্তন করার কনফার্মেশন মেথড
// ==========================================
function confirmRoleChange(userId, selectElement) {
    const previousRole = selectElement.getAttribute('data-previous');
    const newRole = selectElement.value;

    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to change this user's role from ${previousRole.toUpperCase()} to ${newRole.toUpperCase()}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Update Role'
    }).then((result) => {
        if (result.isConfirmed) {
            executeRoleChange(userId, newRole);
        } else {
            selectElement.value = previousRole;
        }
    });
}

function executeRoleChange(userId, newRole) {
    fetch(`/admin/users/${userId}/change-role`, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ role: newRole })
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) throw new Error(data.message || 'Server error');
        return data;
    })
    .then(data => {
        saveDashboardState();
        if (data.success) {
            Swal.fire({ icon: 'success', title: data.message, timer: 1250, showConfirmButton: false })
            .then(() => { window.location.reload(); });
        }
    })
    .catch(error => {
        Swal.fire({ icon: 'error', title: 'Error', text: error.message });
    });
}

// ==========================================
// ৪. সাসপেনশন হ্যান্ডেলিং
// ==========================================
function manageSuspension(userId, action) {
    let textBody = "";
    let confirmText = "Confirm";
    let confirmColor = '#2563eb';

    if (action === 'temp') {
        textBody = "Are you Sure ? You Want to Suspend This User For 7 Days ?";
        confirmText = "Yes, Suspend";
        confirmColor = '#d97706';
    } else if (action === 'perm') {
        textBody = "Are you Sure ? You Want to Suspend This User For Permanently ?";
        confirmText = "Yes, Suspend Permanently";
        confirmColor = '#dc2626';
    } else if (action === 'active') {
        textBody = "Are You Sure you want To Remove Suspension of this User ?";
        confirmText = "Yes, Remove Suspension";
        confirmColor = '#16a34a';
    }

    Swal.fire({
        title: 'Are you sure?',
        text: textBody,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: confirmColor,
        cancelButtonColor: '#64748b',
        confirmButtonText: confirmText
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/users/${userId}/suspension`, { 
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ action: action })
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) throw new Error(data.message || 'Error updating status');
                return data;
            })
            .then(data => {
                saveDashboardState();
                if (data.success) {
                    Swal.fire({ icon: 'success', title: data.message, timer: 1250, showConfirmButton: false })
                    .then(() => { window.location.reload(); });
                }
            })
            .catch(error => {
                Swal.fire({ icon: 'error', title: 'Error', text: error.message });
            });
        }
    });
}

// ==========================================
// 📊 ৫. চার্ট রেন্ডারিং ইঞ্জিন
// ==========================================
document.addEventListener("DOMContentLoaded", function() {
    fetch('/admin/dashboard/analytics-data', { method: 'GET' })
    .then(res => res.json())
    .then(data => {
        const chartCanvas = document.getElementById('adminTrendChart');
        if (!chartCanvas) return;

        const ctx = chartCanvas.getContext('2d');
        const blueGradient = ctx.createLinearGradient(0, 0, 0, 350);
        blueGradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
        blueGradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels || [],
                datasets: [
                    { label: 'New Registrations', data: data.users || [], borderColor: '#3b82f6', backgroundColor: blueGradient, borderWidth: 3, fill: true, tension: 0.35 },
                    { label: 'Engagement (Posts)', data: data.posts || [], borderColor: '#10b981', backgroundColor: 'transparent', borderWidth: 2, tension: 0.35, borderDash: [6, 4] }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top', labels: { font: { family: 'Plus Jakarta Sans', size: 12 } } } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                }
            }
        });
    }).catch(err => console.error('Chart Load Error:', err));
});

function confirmLogout() {
    Swal.fire({ 
        title: 'Are you sure?', 
        text: "You will be logged out of the session!", 
        icon: 'warning', 
        showCancelButton: true, 
        confirmButtonColor: '#ef4444', 
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Sign Out!' 
    }).then((result) => { 
        if (result.isConfirmed) {
            // ১. ওয়ান-টাইম সাকসেস মেসেজ টোস্ট (অপশনাল কিন্তু প্রিমিয়াম লুক দেয়)
            Swal.fire({
                icon: 'success',
                title: 'Signing Out...',
                text: 'You have been logged out successfully.',
                showConfirmButton: false,
                timer: 1500, // ১.৫ সেকেন্ডের প্রিমিয়াম ডিলে
                timerProgressBar: true
            });

            // ২. মেসেজটি শেষ হওয়ার পর ফর্ম সাবমিট হবে
            setTimeout(() => {
                document.getElementById('logout-form').submit();
            }, 1300);
        } 
    });
}

function deletePost(postId) {
    Swal.fire({ title: 'Are you sure?', text: "Permanently delete this post?", icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, Delete!' }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/posts/${postId}/delete`, { method: "DELETE", headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } })
            .then(res => res.json()).then(data => { if(data.success) { Toast.fire({ icon: 'success', title: data.message }); $('#postsTable').DataTable().row(`#post-row-${postId}`).remove().draw(false); } });
        }
    });
}

function deleteCircular(circularId) {
    Swal.fire({ title: 'Delete Circular?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, Remove!' }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/circulars/${circularId}/delete`, { method: "DELETE", headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } })
            .then(res => res.json()).then(data => { if(data.success) { Toast.fire({ icon: 'success', title: data.message }); $('#circularsTable').DataTable().row(`#circular-row-${circularId}`).remove().draw(false); } });
        }
    });
}
</script>
</body>
</html>