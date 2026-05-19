<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Premium Admin Control Center - Borobhai.com</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        
        /* DataTables Custom Styling to make it Premium */
        .card-table-wrapper { background: #fff; border-radius: 20px; padding: 25px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.02); }
        .table th { background: #f8fafc !important; color: #64748b !important; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; border-bottom: 1px solid #e2e8f0 !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current { background: #3b82f6 !important; color: #fff !important; border-color: #3b82f6 !important; border-radius: 8px; }
        .form-control-sm { border-radius: 8px !important; padding: 0.4rem 0.8rem !important; border: 1px solid #cbd5e1; }
        
        .badge-active { background-color: #d1fae5; color: #065f46; font-weight: 600; padding: 6px 12px; border-radius: 8px; font-size: 0.8rem; }
        .badge-suspended { background-color: #fee2e2; color: #991b1b; font-weight: 600; padding: 6px 12px; border-radius: 8px; font-size: 0.8rem; }
        
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
    <div class="d-flex justify-content-between align-items-center mb-5">
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
                <th>User Details</th>
                <th>Role (Assign Admin)</th>
                <th>Joined Date</th>
                <th class="text-center">Status</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            {{-- @foreach লুপটি এখানে সঠিকভাবে শুরু করা হলো --}}


            @foreach($users as $user)
    <tr id="user-row-{{ $user->id }}">
        <td>
            <div class="d-flex align-items-center gap-3">
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center fw-bold text-secondary" style="width: 42px; height: 42px;">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <div class="fw-semibold">
                        {{ $user->name }} 
                        @if(auth()->id() == $user->id) 
                            <span class="badge bg-primary ms-1" style="font-size: 10px;">You</span> 
                        @endif
                    </div>
                    <div class="text-muted small">{{ $user->email }}</div>
                </div>
            </div>
        </td>
        <td>
            {{--  বাগ ফিক্স: লগইন থাকা এডমিন নিজের রোল চেঞ্জ করতে পারবে না --}}
            @if(auth()->id() == $user->id)
                <span class="badge bg-dark px-3 py-2" style="border-radius: 8px;">Admin</span>
            @else
                <select onchange="changeUserRole({{ $user->id }}, this.value)" class="form-select form-select-sm" style="border-radius: 8px; width: 110px;">
                    <option value="student" {{ $user->role == 'student' ? 'selected' : '' }}>Student</option>
                    <option value="alumni" {{ $user->role == 'alumni' ? 'selected' : '' }}>Alumni</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            @endif
        </td>
        <td>{{ $user->created_at->format('Y-m-d') }}</td>
        <td class="text-center">
            <span id="status-badge-{{ $user->id }}" class="{{ $user->status === 'active' ? 'badge-active' : 'badge-suspended' }}">
                @if($user->status === 'active') Active 
                @elseif($user->status === 'suspended_temp') Temp Suspended
                @else Perm Suspended @endif
            </span>
        </td>

       <td class="text-end">
    {{-- বাগ ফিক্স: এডমিন নিজেকে নিজে সাসপেন্ড করা ব্লক --}}
    @if(auth()->id() == $user->id)
        <button class="btn btn-light btn-sm border disabled" style="border-radius: 8px;" disabled>
            <i class="fa-solid fa-lock text-muted"></i> Restricted
        </button>
    @else
        <div class="dropdown d-inline-block">
            <button class="btn btn-light btn-sm border dropdown-toggle" type="button" data-bs-toggle="dropdown" style="border-radius: 8px;">
                <i class="fa-solid fa-user-shield me-1"></i> Manage Status
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                {{-- 🔄 টগল লজিক: ইউজার যদি অলরেডি সাসপেন্ডেড থাকে, তবে রিমুভ অপশন দেখাবে --}}
                @if($user->status === 'suspended_temp' || $user->status === 'suspended_perm')
                    <li>
                        <a class="dropdown-item text-success fw-bold" href="javascript:void(0)" onclick="manageSuspension({{ $user->id }}, 'active')">
                            <i class="fa-solid fa-key me-2"></i> Remove Suspension
                        </a>
                    </li>
                @else
                    {{-- ইউজার একটিভ থাকলে সাধারণ সাসপেনশন অপশনগুলো দেখাবে --}}
                    <li><a class="dropdown-item text-warning fw-medium" href="javascript:void(0)" onclick="manageSuspension({{ $user->id }}, 'temp')"><i class="fa-solid fa-clock me-2"></i> Suspend: 7 Days</a></li>
                    <li><a class="dropdown-item text-danger fw-medium" href="javascript:void(0)" onclick="manageSuspension({{ $user->id }}, 'perm')"><i class="fa-solid fa-ban me-2"></i> Suspend: Permanently</a></li>
                @endif
            </ul>
        </div>
    @endif
</td>

    </tr>
@endforeach

            
            {{-- লুপ এখানে শেষ হলো --}}
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
                            <th>Author</th>
                            <th>Post Excerpt</th>
                            <th>Published At</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($posts as $post)
                        <tr id="post-row-{{ $post->id }}">
                            <td><span class="fw-medium">{{ $post->user->name ?? 'Unknown' }}</span></td>
                            <td><span class="text-muted d-inline-block text-truncate" style="max-width: 350px;">{{ $post->content }}</span></td>
                            <td>{{ $post->created_at->format('Y-m-d') }}</td>
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
                            <th>Posted By</th>
                            <th>Job Profile & Company</th>
                            <th>Deadline</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($circulars as $circular)
                        <tr id="circular-row-{{ $circular->id }}">
                            <td><span class="fw-medium text-dark">{{ $circular->user->name ?? 'Alumni' }}</span></td>
                            <td><div><strong class="text-primary">{{ $circular->title }}</strong></div><span class="text-muted small"><i class="fa-solid fa-building me-1"></i> {{ $circular->company }}</span></td>
                            <td><span class="badge bg-warning-subtle text-dark fw-semibold">{{ $circular->deadline }}</span></td>
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

// --- ১. এডমিন অন্য কাউকে এডমিন বা অন্য রোল বানানোর ফাংশন ---
function changeUserRole(userId, newRole) {
    Swal.fire({
        title: 'Change User Role?',
        text: `Are you sure you want to change this user role to ${newRole.toUpperCase()}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3b82f6',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Update Role!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/users/${userId}/change-role`, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ role: newRole })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    Toast.fire({ icon: 'success', title: data.message });
                } else {
                    Toast.fire({ icon: 'error', title: data.message });
                }
            });
        }
    });
}

// --- ২. সাময়িক এবং পারমানেন্ট সাসপেনশন হ্যান্ডেলিং এজাক্স ---
function manageSuspension(userId, actionType) {
    let confirmText = "Activate this account / Remove suspension?";
    if(actionType === 'temp') confirmText = "Suspend this user for exactly 7 Days?";
    if(actionType === 'perm') confirmText = "Permanently suspend this user? They will never be able to login!";

    Swal.fire({
        title: 'Confirm Action',
        text: confirmText,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: actionType === 'active' ? '#10b981' : '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Execute!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/users/${userId}/suspension`, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ action: actionType })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // ⚡ ড্রপডাউন এবং স্ট্যাটাস ব্যাজ লাইভ সিঙ্ক করার জন্য ইনস্ট্যান্ট রিলোড
                        window.location.reload();
                    });
                } else {
                    Toast.fire({ icon: 'error', title: data.message });
                }
            });
        }
    });
}

// --- ১. নো-লোড ক্যাশড ট্যাব ইঞ্জিন ---
document.querySelectorAll('.nav-link-custom').forEach(link => {
    link.addEventListener('click', function() {
        document.querySelectorAll('.nav-link-custom').forEach(l => l.classList.remove('active'));
        document.querySelectorAll('.tab-content-panel').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        document.getElementById(this.getAttribute('data-target')).classList.add('active');
    });
});

// --- ২. JQUERY DATATABLES INITIALIZATION (বড় ডেটাবেজ ম্যানেজমেন্ট) ---
$(document).ready(function() {
    $('#usersTable').DataTable({ "pageLength": 10, "responsive": true, "order": [[2, "desc"]] });
    $('#postsTable').DataTable({ "pageLength": 10, "responsive": true, "order": [[2, "desc"]] });
    $('#circularsTable').DataTable({ "pageLength": 10, "responsive": true, "order": [[2, "desc"]] });
});

// --- ৩. সুইট-অ্যালার্ট টোস্ট ইঞ্জিন (প্রিমিয়াম নোটিফিকেশন) ---
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true
});

// --- ৪. প্রিমিয়াম সাইন-আউট কনফার্মেশন অ্যালার্ট ---
function confirmLogout() {
    Swal.fire({
        title: 'Are you sure?',
        text: "You will be safely logged out of the admin panel!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Sign Out!',
        background: '#fff',
        border: '1px solid #e2e8f0'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Logging out...',
                text: 'Please wait a moment.',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });
            document.getElementById('logout-form').submit();
        }
    });
}

// --- ৫. ইউজার স্ট্যাটাস পরিবর্তন এজাক্স (সুইট অ্যালার্ট সহ) ---
function toggleUserStatus(userId) {
    fetch(`/admin/users/${userId}/toggle-status`, {
        method: "POST",
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            Toast.fire({ icon: 'success', title: data.message });
            const badge = document.getElementById(`status-badge-${userId}`);
            badge.innerText = data.new_status.charAt(0).toUpperCase() + data.new_status.slice(1);
            badge.className = data.new_status === 'active' ? 'badge-active' : 'badge-suspended';
        } else {
            Toast.fire({ icon: 'error', title: data.message });
        }
    }).catch(() => Toast.fire({ icon: 'error', title: 'Connection error.' }));
}

// --- 👑 ৬. চার্ট কেটে যাওয়া ফিক্সড এবং প্রিমিয়াম লুক ইঞ্জিন ---
document.addEventListener("DOMContentLoaded", function() {
    fetch('/admin/dashboard/analytics-data', { method: 'GET' })
    .then(res => res.json())
    .then(data => {
        const ctx = document.getElementById('adminTrendChart').getContext('2d');
        
        // চার্টের লাইনগুলোর জন্য প্রিমিয়াম গ্রাডিয়েন্ট এফেক্ট তৈরি
        const blueGradient = ctx.createLinearGradient(0, 0, 0, 400);
        blueGradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
        blueGradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: 'New Registrations',
                        data: data.users,
                        borderColor: '#3b82f6',
                        backgroundColor: blueGradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#3b82f6',
                        pointHoverRadius: 7,
                        tension: 0.35,
                        fill: true
                    },
                    {
                        label: 'Engagement (Posts)',
                        data: data.posts,
                        borderColor: '#10b981',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        pointBackgroundColor: '#10b981',
                        tension: 0.35,
                        borderDash: [6, 4]
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: { left: 10, right: 25, top: 10, bottom: 20 } // টেক্সট কাটার মেইন সলিউশন
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: { boxWidth: 12, font: { family: 'Plus Jakarta Sans', size: 12, weight: 500 } }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { padding: 10, font: { family: 'Plus Jakarta Sans', size: 11 } },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { padding: 10, font: { family: 'Plus Jakarta Sans', size: 11 } },
                        grid: { display: false }
                    }
                }
            }
        });
    });
});

// --- ৭. পোস্ট ও সার্কুলার ডিলিট মডারেশন এজাক্স ---
function deletePost(postId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This post will be permanently deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/posts/${postId}/delete`, {
                method: "DELETE",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    Toast.fire({ icon: 'success', title: data.message });
                    $('#postsTable').DataTable().row(`#post-row-${postId}`).remove().draw(false);
                }
            });
        }
    });
}

function deleteCircular(circularId) {
    Swal.fire({
        title: 'Delete Circular?',
        text: "This circular will be removed from job board!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Remove!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/circulars/${circularId}/delete`, {
                method: "DELETE",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    Toast.fire({ icon: 'success', title: data.message });
                    $('#circularsTable').DataTable().row(`#circular-row-${circularId}`).remove().draw(false);
                    const counter = document.getElementById('count-circulars');
                    if(counter) counter.innerText = parseInt(counter.innerText) - 1;
                }
            });
        }
    });
}
</script>
</body>
</html>