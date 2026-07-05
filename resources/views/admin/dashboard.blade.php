<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Control Center - Borobhai.online</title>
    
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        .sidebar { width: 260px; height: 100vh; background: #0f172a; position: fixed; top: 0; left: 0; padding: 25px 15px; color: #fff; z-index: 100; box-shadow: 4px 0 24px rgba(15, 23, 42, 0.08); }
        .main-content { margin-left: 260px; padding: 40px; min-height: 100vh; transition: all 0.3s ease; }
        .sidebar-brand { font-size: 1.35rem; font-weight: 800; color: #3b82f6; margin-bottom: 35px; text-align: center; letter-spacing: -0.5px; }
        .nav-link-custom { display: flex; align-items: center; gap: 12px; padding: 11px 16px; color: #94a3b8; font-size: 0.82rem; font-weight: 500; border-radius: 10px; text-decoration: none; margin-bottom: 6px; cursor: pointer; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); }
        .nav-link-custom:hover { background: #1e293b; color: #f1f5f9; transform: translateX(2px); }
        .nav-link-custom.active { background: #1e293b; color: #fff; border-left: 4px solid #3b82f6; font-weight: 600; }
        .counter-card { background: #fff; border-radius: 16px; padding: 22px; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .counter-card:hover { transform: translateY(-3px); box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.04); border-color: #cbd5e1; }
        .counter-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
        .card-table-wrapper { background: #fff; border-radius: 16px; padding: 22px; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px rgba(15, 23, 42, 0.015); }
        .table { font-size: 0.76rem !important; } 
        .table th { background: #f8fafc !important; color: #64748b !important; font-weight: 700; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0 !important; padding: 10px 12px !important; }
        .table td { padding: 10px 12px !important; vertical-align: middle; color: #334155; border-bottom: 1px solid #f1f5f9 !important; }
        .table tr:hover td { background-color: #f8fafc; }
        
        .status-badge { display: inline-flex; align-items: center; gap: 4px; font-size: 0.65rem; font-weight: 700; padding: 3px 8px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.3px; }
        .status-badge.active { background-color: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
        .status-badge.temp-suspended { background-color: #fff7ed; color: #ea580c; border: 1px solid #fed7aa; }
        .status-badge.perm-suspended { background-color: #fef2f2; color: #dc2626; border: 1px solid #fca5a5; }

        .btn-action-pill { display: inline-flex; align-items: center; gap: 5px; padding: 5px 10px; font-size: 0.72rem; font-weight: 600; border-radius: 6px; border: 1px solid #e2e8f0; background: #fff; cursor: pointer; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); text-decoration: none; }
        .btn-action-pill:hover { transform: translateY(-1px); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        .btn-action-pill.temp-ban { color: #d97706; }
        .btn-action-pill.temp-ban:hover { background: #fff7ed; border-color: #fdba74; }
        .btn-action-pill.perm-ban { color: #dc2626; }
        .btn-action-pill.perm-ban:hover { background: #fef2f2; border-color: #fca5a5; }
        .btn-action-pill.activate { color: #16a34a; background: #f0fdf4; border-color: #86efac; }
        .btn-action-pill.activate:hover { background: #bbf7d0; }
        .tab-content-panel { display: none; opacity: 0; transform: scale(0.995) translateY(4px); transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1); }
        .tab-content-panel.active { display: block; opacity: 1; transform: scale(1) translateY(0); }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand"><i class="fa-solid fa-graduation-cap me-2"></i>Borobhai Admin</div>
    <div class="nav-link-custom active" data-target="analytics-tab"><i class="fa-solid fa-chart-pie"></i>Analytics</div>
    <div class="nav-link-custom" data-target="users-tab"><i class="fa-solid fa-users"></i> User Management</div>
    <div class="nav-link-custom" data-target="posts-tab"><i class="fa-solid fa-newspaper"></i>Reported Contents</div>
    <div class="nav-link-custom" data-target="jobs-tab"><i class="fa-solid fa-briefcase"></i> Reported Jobs</div>
    
    <div style="position: absolute; bottom: 30px; width: calc(100% - 30px);">
        <div class="d-flex align-items-center gap-5">
                    <img src="{{ auth()->user()->profile_picture ? asset('storage/'.auth()->user()->profile_picture) : asset('default-avatar.png') }}" 
                        alt="Profile" class="rounded-full" style="width: 60px; height: 60px; object-fit: cover;">
                    
                    
                    <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-warning" style="font-size: 0.8rem; text-decoration: none;">
                        Edit Profile
                    </a>
                    <span class="mb-4"></span>
                </div>
        <form id="logout-form" method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="button" onclick="confirmLogout()" class="w-100 btn btn-danger btn-sm" style="margin-top:10px; border-radius: 10px; padding: 9px; font-size: 0.78rem; font-weight: 600;">
                <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Sign Out
            </button>
        </form>
    </div>
</div>

<div class="main-content">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>
                Welcome back, {{ auth()->user()->name }} 
                @if(auth()->user()->isSuperAdmin())
                    <span class="badge bg-dark text-white border ms-1" style="font-size: 0.65rem; padding: 4px 8px; vertical-align: middle; border-radius: 4px; font-weight: 700;">Super Admin</span>
                @else
                    <span class="badge bg-secondary text-white border ms-1" style="font-size: 0.65rem; padding: 4px 8px; vertical-align: middle; border-radius: 4px; font-weight: 700;">Admin</span>
                @endif
            </h2>
        </div>
        
        <div class="d-flex align-items-center gap-3 bg-white px-3 py-2 border rounded-4 shadow-sm" style="border-radius: 12px;">
            <div>
                
                 <img  src="{{ asset('storage/'.auth()->user()->profile_picture) }}" class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; font-size: 0.85rem;">           

            </div>
            <div class="d-none d-sm-block">
                <div class="fw-bold text-dark" style="font-size: 0.82rem; line-height: 1.2;">
                    {{ auth()->user()->name }}
                </div>
                <div class="text-muted mt-0.5" style="font-size: 0.68rem; font-weight: 600;">
                   @if(auth()->user()->isSuperAdmin())
                        <span class="badge bg-dark text-white border" style="font-size: 0.65rem; padding: 4px 8px; border-radius: 4px; font-weight: 700;">Super Admin</span>
                    @else
                        <span class="badge bg-secondary text-white border" style="font-size: 0.65rem; padding: 4px 8px; border-radius: 4px; font-weight: 700;">Admin</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="counter-card">
                 <div><p class="text-muted small fw-bold mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">TOTAL USERS</p><h4 class="fw-bold mb-0" style="font-size: 1.4rem;">{{ $counters['total_users'] }}</h4></div>
                <div class="counter-icon text-primary" style="background: rgba(59,130,246,0.08);"><i class="fa-solid fa-users"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="counter-card">
                <div><p class="text-muted small fw-bold mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">STUDENTS</p><h4 class="fw-bold mb-0" style="font-size: 1.4rem;">{{ $counters['total_students'] }}</h4></div>
                <div class="counter-icon text-success" style="background: rgba(16,185,129,0.08);"><i class="fa-solid fa-user-graduate"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="counter-card">
                <div><p class="text-muted small fw-bold mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">ALUMNI</p><h4 class="fw-bold mb-0" style="font-size: 1.4rem;">{{ $counters['total_alumni'] }}</h4></div>
                <div class="counter-icon text-warning" style="background: rgba(245,158,11,0.08);"><i class="fa-solid fa-briefcase"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="counter-card">
                <div><p class="text-muted small fw-bold mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">CIRCULARS</p><h4 class="fw-bold mb-0" style="font-size: 1.4rem;" id="count-circulars">{{ $counters['total_circulars'] }}</h4></div>
                <div class="counter-icon text-info" style="background: rgba(6,182,212,0.08);"><i class="fa-solid fa-scroll"></i></div>
            </div>
        </div>
    </div>

    <div id="analytics-tab" class="tab-content-panel active">
        <div class="card p-4 shadow-sm border-0" style="border-radius: 16px; background: #fff;">
            <h6 class="fw-bold mb-4" style="font-size: 0.88rem;"><i class="fa-solid fa-chart-line text-primary me-2"></i> Registration & Engagement Metrics</h6>
            <div style="position: relative; height: 380px; width: 100%; padding: 10px;">
                <canvas id="adminTrendChart"></canvas>
            </div>
        </div>
    </div>

    <div id="users-tab" class="tab-content-panel">
        <div class="card-table-wrapper">
            <h6 class="fw-bold mb-4" style="font-size: 0.88rem;"><i class="fa-solid fa-users-gear text-primary me-2"></i> Member Control Panel</h6>
            <div class="table-responsive">
                 <table id="usersTable" class="table table-hover align-middle w-100">
                    <thead>
                        <tr>
                            <th style="width: 8%;">ID</th>
                            <th style="width: 32%;">User Info</th>
                            <th style="width: 25%;">Email</th>
                            <th style="width: 15%;">User Role</th>
                            <th style="width: 20%; text-align: right;">Admin Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($users as $user)
                        <tr id="user-row-{{ $user->id }}">
                            <td class="fw-bold text-secondary">#{{ $user->id }}</td>
                            
                            <td>
    <div class="d-flex align-items-center gap-3">
        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center fw-bold text-secondary" style="width: 38px; height: 38px; min-width: 38px; font-size: 0.85rem; border: 1px solid #e2e8f0;">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        
        <div class="d-flex flex-column justify-content-center">
            <div class="fw-bold text-dark d-flex align-items-center gap-2" style="font-size: 0.82rem; line-height: 1.2;">
                 {{ $user->name }} 
                @if(auth()->id() == $user->id) 
                     <span class="badge bg-primary text-white" style="font-size: 8px; padding: 2px 4px; border-radius: 4px; font-weight: 700;">Logged In</span> 
                @endif
            </div>

            <div class="d-flex align-items-center gap-2 mt-1.5" style="line-height: 1; flex-wrap: wrap;">
    
    @if($user->is_super_admin)
        {{-- 👑 ১. সুপার এডমিন হলে শুধুই সুপার এডমিন ব্যাজ দেখাবে (কাহিনী শেষ) --}}
        <span class="mt-2 badge text-white d-inline-flex align-items-center gap-1" style="font-size: 10px; padding: 4px 8px; border-radius: 6px; font-weight: 700; background-color: #991b1b !important;">
            <i class="fa-solid fa-crown" style="font-size: 8px; color: #fcd34d;"></i> Super Admin
        </span>
    @elseif($user->role === 'admin')
        {{-- 🛡️ ২. রেগুলার এডমিন ব্যাজ --}}
        <span class="mt-2 badge text-white d-inline-flex align-items-center gap-1" style="font-size: 10px; padding: 4px 8px; border-radius: 6px; font-weight: 600; background-color: #1e293b !important;">
            <i class="fa-solid fa-user-shield" style="font-size: 8px;"></i> Admin
        </span>
    @elseif($user->role === 'alumni')
        {{-- 🎓 ৩. অ্যালুমনাই ব্যাজ --}}
        <span class="mt-2 badge text-dark d-inline-flex align-items-center gap-1" style="font-size: 10px; padding: 4px 8px; border-radius: 6px; font-weight: 600; background-color: #e0f2fe !important; color: #0369a1 !important;">
            <i class="fa-solid fa-user-graduate" style="font-size: 8px;"></i> Alumni
        </span>
        @elseif($user->role === 'teacher')
            <span class="mt-2 badge text-dark d-inline-flex align-items-center gap-1" style="font-size: 10px; padding: 4px 8px; border-radius: 6px; font-weight: 600; background-color: #f3e8ff !important; color: #7c3aed !important;">
                <i class="fa-solid fa-chalkboard-user" style="font-size: 8px;"></i> Teacher
            </span>
    @else
        {{-- 🧑‍🎓 ৪. রেগুলার স্টুডেন্ট ব্যাজ --}}
        <span class="mt-2 badge text-dark d-inline-flex align-items-center gap-1" style="font-size: 10px; padding: 4px 8px; border-radius: 6px; font-weight: 600; background-color: #fef3c7 !important; color: #b45309 !important;">
            <i class="fa-solid fa-user" style="font-size: 8px;"></i> Student
        </span>
    @endif

    @if($user->status === 'active' || empty($user->status))
        <span class="mt-2 status-badge active"><i class="fa-solid fa-circle text-success" style="font-size: 5px;"></i> Active</span>
    @elseif($user->status === 'suspended_temp')
        <span class="mt-2 status-badge temp-suspended"><i class="fa-solid fa-clock"></i> 7-Day Suspended</span>
    @elseif($user->status === 'suspended_perm')
        <span class="mt-2 status-badge perm-suspended"><i class="fa-solid fa-ban"></i> Permanently Banned</span>
    @endif

</div>

            <div class="mt-2 pt-1 d-flex flex-column gap-1" style="border-top: 1px dashed #f1f5f9;">
                <div class="text-muted" style="font-size: 0.68rem; font-weight: 500; letter-spacing: 0.2px;">
                    <i class="fa-regular fa-calendar-check me-1 text-secondary"></i> Joined: {{ $user->created_at->format('d M Y') }}
                </div>
                
                @if($user->status === 'suspended_temp' && $user->suspended_until)
                    <div class="text-danger fw-semibold d-flex align-items-center" style="font-size: 0.65rem; letter-spacing: 0.1px;">
                        <i class="fa-solid fa-hourglass-half me-1"></i> Blocked until: {{ \Carbon\Carbon::parse($user->suspended_until)->format('d M Y (g:i a)') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</td>

                            <td class="text-secondary" style="font-size: 0.74rem;">{{ $user->email }}</td>

                            <td>
                                @if(auth()->id() == $user->id)
                                    <span class="badge bg-light text-dark border px-2 py-1" style="border-radius: 4px; font-size: 0.75rem; font-weight: 600;">
                                        <i class="fa-solid fa-user-lock me-1"></i> Self Role Locked
                                    </span>
                                
                                @elseif($user->email === env('CHIEF_SUPER_ADMIN_EMAIL', 'shahidul.webdev@gmail.com'))
                                    <span class="badge bg-danger text-white px-2 py-1" style="border-radius: 4px; font-size: 0.75rem; font-weight: 600;">
                                        <i class="fa-solid fa-shield-halved me-1"></i> Main System Admin
                                    </span>

                                @else
                                    <div class="d-flex align-items-center gap-2">
                                        <select data-previous="{{ $user->role }}" 
                                                onchange="confirmRoleChange({{ $user->id }}, this)" 
                                                class="form-select form-select-sm role-select-dropdown" 
                                                style="max-width: 120px;"
                                                {{ (in_array($user->status, ['suspended_temp', 'suspended_perm']) || ($user->role === 'admin' && !auth()->user()->isSuperAdmin())) ? 'disabled' : '' }}>
                                            
                                            <option value="" disabled {{ !in_array($user->role, ['student', 'alumni', 'admin', 'teacher']) ? 'selected' : '' }}>Select Role</option>
                                            <option value="student" {{ $user->role === 'student' ? 'selected' : '' }} {{ $user->role === 'student' ? 'disabled' : '' }}>Student</option>
                                            <option value="alumni" {{ $user->role === 'alumni' ? 'selected' : '' }} {{ $user->role === 'alumni' ? 'disabled' : '' }}>Alumni</option>

                                            <option value="teacher" {{ $user->role === 'teacher' ? 'selected' : '' }} {{ $user->role === 'teacher' ? 'disabled' : '' }}>Teacher</option>

                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }} {{ $user->role === 'admin' ? 'disabled' : '' }}>Admin</option>
                                        </select>

                                        @if(auth()->user()->isSuperAdmin() && !in_array($user->status, ['suspended_temp', 'suspended_perm']))
                                            <div class="d-flex gap-1">
                                                @if($user->role !== 'admin')
                                                    <button onclick="executeAuthorityChange({{ $user->id }}, 'admin')" class="btn btn-xs btn-outline-primary py-0.5 px-1.5" style="font-size: 9px; font-weight: 700; border-radius: 4px;" title="Make Admin">+ Admin</button>
                                                    <button onclick="executeAuthorityChange({{ $user->id }}, 'super')" class="btn btn-xs btn-dark py-0.5 px-1.5" style="font-size: 9px; font-weight: 700; border-radius: 4px; background-color: #1e293b; color: #fff;" title="Make Super Admin">+ Super</button>
                                                @elseif($user->role === 'admin' && !$user->isSuperAdmin())
                                                    <button onclick="executeAuthorityChange({{ $user->id }}, 'super')" class="btn btn-xs btn-dark py-0.5 px-1.5" style="font-size: 9px; font-weight: 700; border-radius: 4px; background-color: #1e293b; color: #fff;" title="Promote to Super Admin">+ Super</button>
                                                    <button onclick="executeAuthorityChange({{ $user->id }}, 'student')" class="btn btn-xs btn-outline-danger py-0.5 px-1.5" style="font-size: 9px; font-weight: 700; border-radius: 4px;" title="Demote to Regular User">Make Normal</button>
                                                @elseif($user->isSuperAdmin())
                                                    <button onclick="executeAuthorityChange({{ $user->id }}, 'admin')" class="btn btn-xs btn-outline-warning py-0.5 px-1.5" style="font-size: 9px; font-weight: 700; border-radius: 4px;" title="Demote to Normal Admin">Only Admin</button>
                                                    <button onclick="executeAuthorityChange({{ $user->id }}, 'alumni')" class="btn btn-xs btn-outline-danger py-0.5 px-1.5" style="font-size: 9px; font-weight: 700; border-radius: 4px;" title="Demote to Regular Alumni">Normal User</button>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </td>

                            <td style="text-align: right;">
                                @if(auth()->id() == $user->id)
                                    <span class="text-muted" style="font-size: 0.72rem; font-weight: 500;"><i class="fa-solid fa-shield-halved me-1"></i> Protected</span>
                                @elseif($user->isSuperAdmin() && !auth()->user()->isSuperAdmin())
                                    <span class="text-muted" style="font-size: 0.72rem; font-weight: 500;"><i class="fa-solid fa-lock me-1"></i> Vaulted</span>
                                @else
                                    @php
                                        $currentUser = auth()->user();
                                        $canSuspend = false;

                                        if ($user->email !== env('CHIEF_SUPER_ADMIN_EMAIL', 'shahidul.webdev@gmail.com')) {
                                            if ($user->role !== 'admin') {
                                                $canSuspend = true;
                                            } 
                                            elseif ($user->role === 'admin' && !$user->isSuperAdmin() && $currentUser->isSuperAdmin()) {
                                                $canSuspend = true;
                                            }
                                        }
                                    @endphp

                                    @if($canSuspend)
                                        <div class="d-flex justify-content-end align-items-center gap-1">
                                            @if($user->status === 'active' || empty($user->status))
                                                <button onclick="suspendFromReport({{ $user->id }}, 'temp')" class="btn-action-pill temp-ban" title="Suspend 7 Days">
                                                    <i class="fa-solid fa-clock"></i> Suspend 7 Days
                                                </button>
                                                <button onclick="suspendFromReport({{ $user->id }}, 'perm')" class="btn-action-pill perm-ban" title="Suspend Permanently">
                                                    <i class="fa-solid fa-ban"></i> Suspend Permanently
                                                </button>
                                            @elseif($user->status === 'suspended_temp')
                                                <button onclick="suspendFromReport({{ $user->id }}, 'active')" class="btn-action-pill activate" title="Remove Suspension">
                                                    <i class="fa-solid fa-circle-check"></i> Remove Suspension
                                                </button>
                                                <button onclick="suspendFromReport({{ $user->id }}, 'perm')" class="btn-action-pill perm-ban" title="Upgrade to Permanent Ban">
                                                    <i class="fa-solid fa-ban"></i> Suspend Permanently
                                                </button>
                                            @elseif($user->status === 'suspended_perm')
                                                <button onclick="suspendFromReport({{ $user->id }}, 'active')" class="btn-action-pill activate" title="Remove Suspension">
                                                    <i class="fa-solid fa-circle-check"></i> Remove Suspension
                                                </button>
                                                <button onclick="suspendFromReport({{ $user->id }}, 'temp')" class="btn-action-pill temp-ban" title="Downgrade to 7 Days Suspension">
                                                    <i class="fa-solid fa-clock"></i> Suspend 7 Days
                                                </button>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted" style="font-size: 0.72rem; font-weight: 500;"><i class="fa-solid fa-user-shield me-1"></i> Restricted</span>
                                    @endif
                                @endif
                            </td>                          
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> 
        </div>
    </div>

    {{-- updated posts tab start --}}
        <div id="posts-tab" class="tab-content-panel">
        <div class="card-table-wrapper">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="fw-bold mb-0" style="font-size:0.88rem;">
                    <i class="fa-solid fa-flag text-danger me-2"></i>
                    Reported Content
                    <span class="badge bg-danger ms-2" style="font-size:0.7rem;">{{ $counters['pending_reports'] }}</span>
                </h6>
            </div>

            @if($reports->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-shield-check fa-2x mb-3 text-success"></i>
                    <p class="fw-semibold">No pending reports. All clear!</p>
                </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100">
                    <thead>
                        <tr>
                            <th style="width:8%">Type</th>
                            <th style="width:30%">Reported Content</th>
                            <th style="width:20%">Reported User</th>
                            <th style="width:15%">Reason</th>
                            <th style="width:27%; text-align:right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    
                    @foreach($reports as $report)
                    <tr id="report-row-{{ $report->id }}">
                        <td>
                            @php $typeColor = match($report->type) { 'post'=>'primary', 'job'=>'warning', 'user'=>'danger', default=>'secondary' }; @endphp
                            <span class="badge bg-{{ $typeColor }}-subtle text-{{ $typeColor }} border border-{{ $typeColor }}-subtle" style="font-size:0.65rem;font-weight:700;text-transform:uppercase;">
                                {{ $report->type }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark" style="font-size:0.78rem;">
                                {{ $report->targetTitle }}
                            </div>

                            {{-- টেবিলের View Content --}}
                            @if($report->targetLink)
                            <a href="{{ $report->targetLink }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-primary" style="font-size:0.68rem;">
                                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>View Content
                            </a>
                            @endif

                        </td>
                        <td>
                            @if($report->targetUser)
                            <div class="fw-semibold" style="font-size:0.78rem;">{{ $report->targetUser->name }}</div>
                            <div class="text-muted" style="font-size:0.68rem;">{{ $report->targetUser->email }}</div>
                            @else
                            <span class="text-muted" style="font-size:0.72rem;">User deleted</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted" style="font-size:0.72rem;">{{ ucfirst($report->reason) }}</span>
                            @if($report->details)
                            <div class="text-muted" style="font-size:0.65rem;" title="{{ $report->details }}">
                                {{ \Illuminate\Support\Str::limit($report->details, 30) }}
                            </div>
                            @endif
                        </td>
                        <td style="text-align:right;">
                            <div class="d-flex justify-content-end gap-1 flex-wrap">

                                {{-- Action বাটনের View --}}
                                @if($report->targetLink)
                                <a href="{{ $report->targetLink }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn-action-pill" style="color:#2563eb;">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>
                                @endif

                                {{-- Warn --}}
                                @if($report->targetUser)
                                <button onclick="adminAction('warn', {{ $report->id }})" class="btn-action-pill temp-ban" title="Send Warning">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Warn
                                </button>
                                {{-- Suspend --}}
                                <button onclick="suspendFromReport({{ $report->targetUser->id }}, 'temp')" class="btn-action-pill temp-ban" title="Suspend 7 Days">
                                    <i class="fa-solid fa-clock"></i> 7d Ban
                                </button>
                                <button onclick="suspendFromReport({{ $report->targetUser->id }}, 'perm')" class="btn-action-pill perm-ban" title="Permanent Ban">
                                    <i class="fa-solid fa-ban"></i> Perm Ban
                                </button>
                                @endif

                                {{-- Delete content --}}
                                @if($report->type !== 'user')
                                <button onclick="adminAction('delete-content', {{ $report->id }})" class="btn-action-pill perm-ban" title="Delete Content">
                                    <i class="fa-regular fa-trash-can"></i> Delete
                                </button>
                                @endif

                                {{-- Dismiss --}}
                                <button onclick="adminAction('dismiss', {{ $report->id }})" class="btn-action-pill activate" title="Dismiss Report">
                                    <i class="fa-solid fa-check"></i> Dismiss
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
    {{-- updated posts tab end --}}

    {{-- updated reported job section start --}}
        <div id="jobs-tab" class="tab-content-panel">
        <div class="card-table-wrapper">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h6 class="fw-bold mb-0" style="font-size:0.88rem;">
                    <i class="fa-solid fa-briefcase text-warning me-2"></i>
                    Reported Jobs
                    <span class="badge bg-warning text-dark ms-2" style="font-size:0.7rem;">
                        {{ $reports->where('type', 'job')->count() }}
                    </span>
                </h6>
            </div>

            @php $jobReports = $reports->where('type', 'job'); @endphp

            @if($jobReports->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-shield-check fa-2x mb-3 text-success"></i>
                    <p class="fw-semibold">No reported jobs. All clear!</p>
                </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100">
                    <thead>
                        <tr>
                            <th style="width:30%">Reported Job</th>
                            <th style="width:20%">Posted By</th>
                            <th style="width:15%">Reason</th>
                            <th style="width:35%; text-align:right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($jobReports as $report)
                    <tr id="report-row-{{ $report->id }}">
                        <td>
                            <div class="fw-semibold text-dark" style="font-size:0.78rem;">
                                {{ $report->targetTitle }}
                            </div>
                            @if($report->targetLink)
                            <a href="{{ $report->targetLink }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-primary" style="font-size:0.68rem;">
                                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>View Job
                            </a>
                            @else
                            <span class="text-muted" style="font-size:0.68rem;">[Job removed]</span>
                            @endif
                        </td>
                        <td>
                            @if($report->targetUser)
                            <div class="fw-semibold" style="font-size:0.78rem;">
                                {{ $report->targetUser->name }}
                            </div>
                            <div class="text-muted" style="font-size:0.68rem;">
                                {{ $report->targetUser->email }}
                            </div>
                            @else
                            <span class="text-muted" style="font-size:0.72rem;">User deleted</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted" style="font-size:0.72rem;">
                                {{ ucfirst($report->reason) }}
                            </span>
                            @if($report->details)
                            <div class="text-muted" style="font-size:0.65rem;">
                                {{ \Illuminate\Support\Str::limit($report->details, 30) }}
                            </div>
                            @endif
                        </td>
                        <td style="text-align:right;">
                            <div class="d-flex justify-content-end gap-1 flex-wrap">

                                {{-- View Job --}}
                                @if($report->targetLink)
                                <a href="{{ $report->targetLink }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="btn-action-pill" style="color:#2563eb;">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>
                                @endif

                                {{-- Warn user --}}
                                @if($report->targetUser)
                                <button onclick="adminAction('warn', {{ $report->id }})"
                                        class="btn-action-pill temp-ban">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Warn
                                </button>

                                {{-- Suspend --}}
                                <button onclick="suspendFromReport({{ $report->targetUser->id }}, 'temp')"
                                        class="btn-action-pill temp-ban">
                                    <i class="fa-solid fa-clock"></i> 7d Ban
                                </button>
                                <button onclick="suspendFromReport({{ $report->targetUser->id }}, 'perm')"
                                        class="btn-action-pill perm-ban">
                                    <i class="fa-solid fa-ban"></i> Perm Ban
                                </button>
                                @endif

                                {{-- Delete Job --}}
                                <button onclick="adminAction('delete-content', {{ $report->id }})"
                                        class="btn-action-pill perm-ban">
                                    <i class="fa-regular fa-trash-can"></i> Delete Job
                                </button>

                                {{-- Dismiss --}}
                                <button onclick="adminAction('dismiss', {{ $report->id }})"
                                        class="btn-action-pill activate">
                                    <i class="fa-solid fa-check"></i> Dismiss
                                </button>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
    {{-- updated reported job section end --}}
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const activeTab = localStorage.getItem('admin_active_tab') || 'analytics-tab';
    
    document.querySelectorAll('.nav-link-custom').forEach(l => l.classList.remove('active'));
    document.querySelectorAll('.tab-content-panel').forEach(t => t.classList.remove('active'));
    
    const tabBtn = document.querySelector(`[data-target="${activeTab}"]`);
    if (tabBtn) tabBtn.classList.add('active');
    
    const tabContent = document.getElementById(activeTab);
    if (tabContent) {
        tabContent.classList.add('active');
        tabContent.style.opacity = '1';
         tabContent.style.transform = 'scale(1) translateY(0)';
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
        const targetTabId = this.getAttribute('data-target');
        const currentActiveContent = document.querySelector('.tab-content-panel.active');
        
        if(currentActiveContent && currentActiveContent.id === targetTabId) return;

        document.querySelectorAll('.nav-link-custom').forEach(l => l.classList.remove('active'));
        this.classList.add('active');
        
        if(currentActiveContent) {
            currentActiveContent.style.opacity =  '0';
            currentActiveContent.style.transform = 'scale(0.995) translateY(4px)';
            
            setTimeout(() => {
                currentActiveContent.classList.remove('active');
                const nextActiveContent = document.getElementById(targetTabId);
                nextActiveContent.classList.add('active');
         
                setTimeout(() => {
                    nextActiveContent.style.opacity = '1';
                    nextActiveContent.style.transform = 'scale(1) translateY(0)';
                }, 20);
                
                localStorage.setItem('admin_active_tab', targetTabId);
            }, 180);
        }
    });
});

// ✅ আগের DataTable init replace করো
$(document).ready(function () {
    $('#usersTable').DataTable({
        pageLength: 10,
        responsive: true,
        order: [[0, 'asc']],
        stateSave: true,      // ✅ reload এর পর same page/search ধরে রাখবে
        stateDuration: 60     // 60 সেকেন্ড state ধরে রাখবে
    });
    $('#circularsTable').DataTable({
        pageLength: 10,
        responsive: true,
        order: [[0, 'desc']],
        stateSave: true,
        stateDuration: 60
    });
});

const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });

function confirmRoleChange(userId, selectElement) {
    const previousRole = selectElement.getAttribute('data-previous');
    const newRole = selectElement.value;
    Swal.fire({
        title: 'Change User Role?',
        text: `Are you sure you want to change this user's role from ${previousRole.toUpperCase()} to ${newRole.toUpperCase()}?`,
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
        if (!response.ok) throw new Error(data.message || 'Failed to update role');
        return data;
    })
    .then(data => {
        saveDashboardState();
        if (data.success) {
            document.querySelectorAll('.role-select-dropdown').forEach(el => {
                el.onchange = null;
                el.removeAttribute('onchange');
            });
            
            Swal.fire({ icon: 'success', title: 'Role Updated', text: data.message, timer: 1250, showConfirmButton: false })
            .then(() => { window.location.reload(); });
        }
    })
    .catch(error => {
        Swal.fire({ icon: 'error', title: 'Action Denied', text: error.message });
    });
}

function executeAuthorityChange(userId, type) {
    let actionText = '';
    if (type === 'super') actionText = 'Do you want to promote this user to Super Admin?';
    else if (type === 'admin') actionText = 'Do you want to make this user a Regular Admin?';
    else actionText = 'Do you want to demote this user to a normal member?';
     
    Swal.fire({
        title: 'Update Admin Privileges?',
        text: actionText,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0f172a',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Update Privileges'
    }).then((result) => {
        if (result.isConfirmed) {
             fetch("{{ route('admin.manage.authority') }}", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ user_id: userId, type: type })
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) throw new Error(data.message || 'Something went wrong');
                return data;
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({ icon: 'success', title: 'Privileges Updated', text: data.message, timer: 1500, showConfirmButton: false })
                    .then(() => { window.location.reload(); });
                 }
            })
            .catch(error => {
                Swal.fire({ icon: 'error', title: 'Action Denied', text: error.message });
            });
        }
    });
}


function suspendFromReport(userId, action) {
    let text = '', confirmText = '', color = '';

    if (action === 'temp') {
        text = 'Suspend this user for 7 days?';
        confirmText = 'Yes, Suspend 7 Days';
        color = '#d97706';
    } else if (action === 'perm') {
        text = 'Permanently ban this user?';
        confirmText = 'Yes, Ban Permanently';
        color = '#dc2626';
    } else if (action === 'active') {
        text = 'Restore access for this user?';
        confirmText = 'Yes, Restore Access';
        color = '#16a34a';
    }

    Swal.fire({
        title: 'Suspend User?', text: text, icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: color,
        cancelButtonColor: '#64748b',
        confirmButtonText: confirmText
    }).then(result => {
        if (!result.isConfirmed) return;

        fetch(`/admin/reports/suspend/${userId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ action: action })
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                Toast.fire({ icon: 'success', title: d.message });
            } else {
                Swal.fire({ icon: 'error', title: 'Failed', text: d.message });
            }
        })
        .catch(() => Swal.fire({ icon: 'error', title: 'Network Error' }));
    });
}

document.addEventListener("DOMContentLoaded", function() {
    fetch('/admin/dashboard/analytics-data', { method: 'GET' })
    .then(res => res.json())
    .then(data => {
        const chartCanvas = document.getElementById('adminTrendChart');
        if (!chartCanvas) return;

        const ctx = chartCanvas.getContext('2d');
        const blueGradient = ctx.createLinearGradient(0, 0, 0, 350);
        blueGradient.addColorStop(0, 'rgba(59, 130, 246, 0.15)');
        blueGradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

          new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels || [],
                datasets: [
                    { label: 'New Registrations', data: data.users || [], borderColor: '#3b82f6', backgroundColor: blueGradient, borderWidth: 2.5, fill: true, tension: 0.35 },
                    { label: 'Engagement (Posts)', data: data.posts || [], borderColor: '#10b981', backgroundColor: 'transparent', borderWidth: 1.8, tension: 0.35, borderDash: [6, 4] }
                ]
            },
            options: {
                 responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'top', labels: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '500' } } } },
                scales: {
                    y: { beginAtZero: true, ticks: { font: { size: 10 } } },
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } }
                }
            }
        });
    }).catch(err => console.error('Chart Load Error:', err));
});

function confirmLogout() {
    Swal.fire({ 
        title: 'Sign Out?', 
        text: "Are you sure you want to log out of the admin panel?", 
        icon: 'warning', 
        showCancelButton: true, 
        confirmButtonColor: '#ef4444', 
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, Sign Out' 
    }).then((result) => { 
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: 'Logging Out...',
                text: 'You have been successfully logged out.',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true
            });

            setTimeout(() => {
                document.getElementById('logout-form').submit();
            }, 1300);
        } 
    });
}

function deletePost(postId) {
    Swal.fire({ title: 'Delete Post?', text: "Are you sure you want to permanently delete this post?", icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, Delete Post' }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/posts/${postId}/delete`, { method: "DELETE", headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } })
            .then(res => res.json()).then(data => { if(data.success) { Toast.fire({ icon: 'success', title: 'Post Deleted' }); $('#postsTable').DataTable().row(`#post-row-${postId}`).remove().draw(false); } });
        }
    });
}

function deleteCircular(circularId) {
    Swal.fire({ title: 'Delete Job Listing?', text: 'Are you sure you want to delete this job circular?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Yes, Delete Circular' }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/admin/circulars/${circularId}/delete`, { method: "DELETE", headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } })
            .then(res => res.json()).then(data => { 
                if(data.success) { 
                    Toast.fire({ icon: 'success', title: 'Job Circular Deleted' }); 
                    $('#circularsTable').DataTable().row(`#circular-row-${circularId}`).remove().draw(false); 
                    const counter = document.getElementById('count-circulars');
                    if(counter) counter.innerText = parseInt(counter.innerText) - 1;
                } 
             });
        }
    });
}

function adminAction(action, reportId) {
    const config = {
        warn:           { title: 'Send Warning?',   text: 'A warning notification will be sent to the user.', icon: 'warning', confirmText: 'Yes, Warn User', color: '#d97706' },
        dismiss:        { title: 'Dismiss Report?', text: 'This report will be marked as resolved.',           icon: 'info',    confirmText: 'Dismiss',         color: '#16a34a' },
        'delete-content':{ title: 'Delete Content?', text: 'This will permanently delete the reported content.',icon: 'warning', confirmText: 'Yes, Delete',    color: '#dc2626' },
    };
    const c = config[action];

    Swal.fire({
        title: c.title, text: c.text, icon: c.icon,
        showCancelButton: true, confirmButtonColor: c.color,
        confirmButtonText: c.confirmText
    })
    .then(result => {
        if (!result.isConfirmed) return;

        // ✅ FIX: route name অনুযায়ী URL ও method
        let url, method;
        if (action === 'warn') {
            url    = `/admin/reports/${reportId}/warn`;
            method = 'POST';
        } else if (action === 'dismiss') {
            url    = `/admin/reports/${reportId}/dismiss`;
            method = 'POST';
        } else if (action === 'delete-content') {
            url    = `/admin/reports/${reportId}/delete-content`;
            method = 'DELETE';
        }

        fetch(url, {
            method: method,
            headers: {
                'Accept':       'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                Toast.fire({ icon: 'success', title: d.message });
                document.getElementById('report-row-' + reportId)?.remove();
            } else {
                Swal.fire({ icon: 'error', title: 'Failed', text: d.message });
            }
        })
        .catch(() => {
            Swal.fire({ icon: 'error', title: 'Network Error', text: 'Please try again.' });
        });
    });
}

// ✅ Report section এর জন্য আলাদা function
function suspendFromReport(userId, action) {
    let text = '', confirmText = '', color = '';

    if (action === 'temp') {
        text = 'Suspend this user for 7 days?';
        confirmText = 'Yes, Suspend 7 Days'; color = '#d97706';
    } else if (action === 'perm') {
        text = 'Permanently ban this user?';
        confirmText = 'Yes, Ban Permanently'; color = '#dc2626';
    } else if (action === 'active') {
        text = 'Restore access for this user?';
        confirmText = 'Yes, Restore'; color = '#16a34a';
    }

    Swal.fire({
        title: 'Suspend User?', text: text, icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: color,
        cancelButtonColor: '#64748b',
        confirmButtonText: confirmText
    }).then(result => {
        if (!result.isConfirmed) return;

        fetch(`/admin/reports/suspend/${userId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ action: action })
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                Toast.fire({ icon: 'success', title: d.message });
            } else {
                Swal.fire({ icon: 'error', title: 'Failed', text: d.message });
            }
        })
        .catch(() => Swal.fire({ icon: 'error', title: 'Network Error' }));
    });
}

</script>
</body>
</html>