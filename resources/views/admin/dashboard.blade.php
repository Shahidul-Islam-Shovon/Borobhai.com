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

    <div class="nav-link-custom" data-target="posts-tab">
        <i class="fa-solid fa-newspaper"></i> Reported Contents
        @if($reports->whereNotIn('type',['job'])->count() > 0)
            <span class="badge bg-danger ms-auto pending-reports-count" style="font-size:0.6rem;">
                {{ $reports->whereNotIn('type',['job'])->count() }}
            </span>
        @endif
    </div>

    <div class="nav-link-custom" data-target="jobs-tab">
        <i class="fa-solid fa-briefcase"></i> Reported Jobs
        @if($reports->where('type','job')->count() > 0)
            <span class="badge bg-warning text-dark ms-auto" style="font-size:0.6rem;">
                {{ $reports->where('type','job')->count() }}
            </span>
        @endif
    </div>

    <div class="nav-link-custom" data-target="history-tab">
        <i class="fa-solid fa-clock-rotate-left"></i> Completed Reports
        @php $historyUnseenCount = $completedReports->where('history_seen', false)->count(); @endphp
        <span class="badge bg-info ms-auto" id="history-unseen-badge" style="font-size:0.6rem; {{ $historyUnseenCount > 0 ? '' : 'display:none;' }}">{{ $historyUnseenCount }}</span>
    </div>

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
                <img src="{{ asset('storage/'.auth()->user()->profile_picture) }}" class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 38px; height: 38px; font-size: 0.85rem;">
            </div>
            <div class="d-none d-sm-block">
                <div class="fw-bold text-dark" style="font-size: 0.82rem; line-height: 1.2;">{{ auth()->user()->name }}</div>
                <div class="text-muted mt-0.5" style="font-size: 0.68rem; font-weight: 600;">
                   @if(auth()->user()->isSuperAdmin())
                        <span class="badge bg-dark text-white border" style="font-size: 0.65rem; padding: 4px 8px; border-radius: 4px; font-weight: 700;">Super Admin</span>
                    @else
                        <span class="badge bg-secondary text-white border" style="font-size: 0.65rem; padding: 4px 8px; border-radius: 4px; font-weight: 700;">Admin</span>
                    @endif                   
                </div>
                
            </div>
        </div>
        <a href="{{ route('admin.dashboard.report.download') }}"
                class="btn btn-sm btn-primary" style="border-radius:8px;font-weight:600;">
                    <i class="fa-solid fa-file-arrow-down me-1"></i> Generate Full Report
                </a>
    </div>
    

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="counter-card">
                <div><p class="text-muted small fw-bold mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">TOTAL USERS</p><h4 class="fw-bold mb-0" style="font-size: 1.4rem;" data-counter="total_users">{{ $counters['total_users'] }}</h4></div>
                <div class="counter-icon text-primary" style="background: rgba(59,130,246,0.08);"><i class="fa-solid fa-users"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="counter-card">
                <div><p class="text-muted small fw-bold mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">STUDENTS</p><h4 class="fw-bold mb-0" style="font-size: 1.4rem;" data-counter="total_students">{{ $counters['total_students'] }}</h4></div>
                <div class="counter-icon text-success" style="background: rgba(16,185,129,0.08);"><i class="fa-solid fa-user-graduate"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="counter-card">
                <div><p class="text-muted small fw-bold mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">ALUMNI</p><h4 class="fw-bold mb-0" style="font-size: 1.4rem;" data-counter="total_alumni">{{ $counters['total_alumni'] }}</h4></div>
                <div class="counter-icon text-warning" style="background: rgba(245,158,11,0.08);"><i class="fa-solid fa-briefcase"></i></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="counter-card">
                <div><p class="text-muted small fw-bold mb-1" style="font-size: 0.68rem; letter-spacing: 0.5px;">CIRCULARS</p><h4 class="fw-bold mb-0" style="font-size: 1.4rem;" id="count-circulars" data-counter="total_circulars">{{ $counters['total_circulars'] }}</h4></div>
                <div class="counter-icon text-info" style="background: rgba(6,182,212,0.08);"><i class="fa-solid fa-scroll"></i></div>
            </div>
        </div>
    </div>

    {{-- Analytics Tab --}}
    <div id="analytics-tab" class="tab-content-panel active">
        <div class="card p-4 shadow-sm border-0" style="border-radius: 16px; background: #fff;">
            <h6 class="fw-bold mb-4" style="font-size: 0.88rem;"><i class="fa-solid fa-chart-line text-primary me-2"></i> Registration & Engagement Metrics</h6>
            <div style="position: relative; height: 380px; width: 100%; padding: 10px;">
                <canvas id="adminTrendChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Users Tab --}}
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
                                                <span class="mt-2 badge text-white d-inline-flex align-items-center gap-1" style="font-size: 10px; padding: 4px 8px; border-radius: 6px; font-weight: 700; background-color: #991b1b !important;">
                                                    <i class="fa-solid fa-crown" style="font-size: 8px; color: #fcd34d;"></i> Super Admin
                                                </span>
                                            @elseif($user->role === 'admin')
                                                <span class="mt-2 badge text-white d-inline-flex align-items-center gap-1" style="font-size: 10px; padding: 4px 8px; border-radius: 6px; font-weight: 600; background-color: #1e293b !important;">
                                                    <i class="fa-solid fa-user-shield" style="font-size: 8px;"></i> Admin
                                                </span>
                                            @elseif($user->role === 'alumni')
                                                <span class="mt-2 badge text-dark d-inline-flex align-items-center gap-1" style="font-size: 10px; padding: 4px 8px; border-radius: 6px; font-weight: 600; background-color: #e0f2fe !important; color: #0369a1 !important;">
                                                    <i class="fa-solid fa-user-graduate" style="font-size: 8px;"></i> Alumni
                                                </span>
                                            @elseif($user->role === 'teacher')
                                                <span class="mt-2 badge text-dark d-inline-flex align-items-center gap-1" style="font-size: 10px; padding: 4px 8px; border-radius: 6px; font-weight: 600; background-color: #f3e8ff !important; color: #7c3aed !important;">
                                                    <i class="fa-solid fa-chalkboard-user" style="font-size: 8px;"></i> Teacher
                                                </span>
                                            @else
                                                <span class="mt-2 badge text-dark d-inline-flex align-items-center gap-1" style="font-size: 10px; padding: 4px 8px; border-radius: 6px; font-weight: 600; background-color: #fef3c7 !important; color: #b45309 !important;">
                                                    <i class="fa-solid fa-user" style="font-size: 8px;"></i> Student
                                                </span>
                                            @endif

                                            @if($user->status === 'active' || empty($user->status))
                                                <span class="mt-2 status-badge active user-status-badge-{{ $user->id }}"><i class="fa-solid fa-circle text-success" style="font-size: 5px;"></i> Active</span>
                                            @elseif($user->status === 'suspended_temp')
                                                <span class="mt-2 status-badge temp-suspended user-status-badge-{{ $user->id }}"><i class="fa-solid fa-clock"></i> 7-Day Suspended</span>
                                            @elseif($user->status === 'suspended_perm')
                                                <span class="mt-2 status-badge perm-suspended user-status-badge-{{ $user->id }}"><i class="fa-solid fa-ban"></i> Permanently Banned</span>
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
                                            if ($user->role !== 'admin') { $canSuspend = true; }
                                            elseif ($user->role === 'admin' && !$user->isSuperAdmin() && $currentUser->isSuperAdmin()) { $canSuspend = true; }
                                        }
                                    @endphp
                                    @if($canSuspend)
                                        <div class="d-flex justify-content-end align-items-center gap-1" id="suspend-btn-{{ $user->id }}">
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

    {{-- Reported Contents Tab --}}
<div id="posts-tab" class="tab-content-panel">
    <div class="card-table-wrapper">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="fw-bold mb-0" style="font-size:0.88rem;">
                <i class="fa-solid fa-flag text-danger me-2"></i>
                Reported Contents
                <span class="badge bg-danger ms-2 pending-reports-count" style="font-size:0.7rem;">
                    {{ $reports->whereNotIn('type', ['job'])->count() }}
                </span>
            </h6>
        </div>

        @php $contentReports = $reports->whereNotIn('type', ['job']); @endphp

        @if($contentReports->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="fa-solid fa-shield-check fa-2x mb-3 text-success"></i>
                <p class="fw-semibold">No pending reports. All clear!</p>
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle w-100" id="reportedTable">
                <thead>
                    <tr>
                        <th style="width:4%">#</th>
                        <th style="width:8%">Type</th>
                        <th style="width:24%">Reported Content</th>
                        <th style="width:16%">Reported User</th>
                        <th style="width:11%">Reason</th>
                        <th style="width:14%">Appeal</th>
                        <th style="width:23%;text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($contentReports as $report)
                    @include('admin.partials.active-report-row', ['report' => $report, 'groupedByUser' => $groupedByUser])
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

    {{-- Reported Jobs Tab --}}
<div id="jobs-tab" class="tab-content-panel">
    <div class="card-table-wrapper">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="fw-bold mb-0" style="font-size:0.88rem;">
                <i class="fa-solid fa-briefcase text-warning me-2"></i>
                Reported Jobs
                <span class="badge bg-warning text-dark ms-2" style="font-size:0.7rem;">
                    {{ $reports->where('type','job')->count() }}
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
            <table class="table table-hover align-middle w-100" id="jobsTable">
                <thead>
                    <tr>
                        <th style="width:4%">#</th>
                        <th style="width:26%">Reported Job</th>
                        <th style="width:18%">Posted By</th>
                        <th style="width:12%">Reason</th>
                        <th style="width:14%">Appeal</th>
                        <th style="width:26%;text-align:right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobReports as $report)
                        @include('admin.partials.active-report-row', ['report' => $report, 'groupedByUser' => $groupedByUser])
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

    {{-- History Tab --}}
    <div id="history-tab" class="tab-content-panel">
        <div class="card-table-wrapper">
            <h6 class="fw-bold mb-4" style="font-size:0.88rem;">
                <i class="fa-solid fa-clock-rotate-left text-secondary me-2"></i>
                Completed Complaint History
            </h6>
            @if($completedReports->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-inbox fa-2x mb-3"></i>
                    <p class="fw-semibold">No completed reports yet.</p>
                </div>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="historyTable">
                    <thead>
                        <tr>
                            <th>#</th><th>Type</th><th>Content</th><th>Reason</th>
                            <th>Final Outcome</th><th>Resolved At</th><th style="text-align:right;">Appeal</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($completedReports as $r)
                        @include('admin.partials.history-row', ['r' => $r])
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

</div>

<script>
// ============================================================
// TAB NAVIGATION
// ============================================================
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
        setTimeout(() => { window.scrollTo(0, parseInt(savedScrollPos)); localStorage.removeItem("admin_scroll_pos"); }, 60);
    }
});

function saveDashboardState() {
    localStorage.setItem("admin_scroll_pos", window.scrollY);
    const activeTabEl = document.querySelector('.nav-link-custom.active');
    if (activeTabEl) localStorage.setItem('admin_active_tab', activeTabEl.getAttribute('data-target'));
}

document.querySelectorAll('.nav-link-custom').forEach(link => {
    link.addEventListener('click', function() {
        const targetTabId = this.getAttribute('data-target');
        const currentActiveContent = document.querySelector('.tab-content-panel.active');
        if (currentActiveContent && currentActiveContent.id === targetTabId) return;
        document.querySelectorAll('.nav-link-custom').forEach(l => l.classList.remove('active'));
        this.classList.add('active');
        if (currentActiveContent) {
            currentActiveContent.style.opacity = '0';
            currentActiveContent.style.transform = 'scale(0.995) translateY(4px)';
            setTimeout(() => {
                currentActiveContent.classList.remove('active');
                const nextActiveContent = document.getElementById(targetTabId);
                nextActiveContent.classList.add('active');

                if (targetTabId === 'history-tab' && $.fn.DataTable.isDataTable('#historyTable')) {
                    setTimeout(() => { $('#historyTable').DataTable().columns.adjust().draw(false); }, 200);
                }
                                
                if (targetTabId === 'history-tab' && nextActiveContent.dataset.needsAdjust === '1') {
                    setTimeout(() => {
                        if ($.fn.DataTable.isDataTable('#historyTable')) {
                            $('#historyTable').DataTable().columns.adjust().draw(false);
                        }
                        nextActiveContent.dataset.needsAdjust = '0';
                    }, 250);
                }
                setTimeout(() => {
                    nextActiveContent.style.opacity = '1';
                    nextActiveContent.style.transform = 'scale(1) translateY(0)';
                }, 20);
                localStorage.setItem('admin_active_tab', targetTabId);
            }, 180);
        }
    });
});

// ============================================================
// DATATABLES
// ============================================================
$(document).ready(function () {
    if ($('#usersTable').length && !$.fn.DataTable.isDataTable('#usersTable')) {
        $('#usersTable').DataTable({ pageLength: 10, responsive: true, order: [[0, 'asc']], stateSave: true, stateDuration: 60, columnDefs: [{ orderable: false, targets: [-1] }] });
    }
    if ($('#reportedTable').length && !$.fn.DataTable.isDataTable('#reportedTable')) {
        $('#reportedTable').DataTable({ pageLength: 10, order: [], columnDefs: [{ orderable: false, targets: [0, 4, 5, 6] }], language: { search: 'Search reports:', emptyTable: 'No pending reports — all clear!', zeroRecords: 'No matching reports found.' } });
    }
    if ($('#jobsTable').length && !$.fn.DataTable.isDataTable('#jobsTable')) {
        $('#jobsTable').DataTable({ pageLength: 10, order: [], columnDefs: [{ orderable: false, targets: [0, 4, 5] }], language: { search: 'Search jobs:' } });
    }
    
    if ($('#historyTable').length && !$.fn.DataTable.isDataTable('#historyTable')) {
    $('#historyTable').DataTable({
        pageLength: 10,
        order: [[5, 'desc']],
        columnDefs: [{ orderable: false, targets: [0, 4, 6] }],
        language: { search: 'Search history:' }
    });

}
});

// ============================================================
// TOAST
// ============================================================
const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true });

// ============================================================
// ANALYTICS CHART
// ============================================================
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
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'top', labels: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '500' } } } },
                scales: { y: { beginAtZero: true, ticks: { font: { size: 10 } } }, x: { grid: { display: false }, ticks: { font: { size: 10 } } } }
            }
        });
    }).catch(err => console.error('Chart Load Error:', err));
});

// ============================================================
// LOGOUT
// ============================================================
function confirmLogout() {
    Swal.fire({ title: 'Sign Out?', text: "Are you sure you want to log out of the admin panel?", icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b', confirmButtonText: 'Yes, Sign Out' })
    .then((result) => {
        if (result.isConfirmed) {
            Swal.fire({ icon: 'success', title: 'Logging Out...', showConfirmButton: false, timer: 1500, timerProgressBar: true });
            setTimeout(() => { document.getElementById('logout-form').submit(); }, 1300);
        }
    });
}

// ============================================================
// ROLE CHANGE
// ============================================================
function confirmRoleChange(userId, selectElement) {
    const previousRole = selectElement.getAttribute('data-previous');
    const newRole = selectElement.value;
    Swal.fire({ title: 'Change User Role?', text: `Change role from ${previousRole.toUpperCase()} to ${newRole.toUpperCase()}?`, icon: 'warning', showCancelButton: true, confirmButtonColor: '#2563eb', cancelButtonColor: '#64748b', confirmButtonText: 'Yes, Update Role' })
    .then((result) => { result.isConfirmed ? executeRoleChange(userId, newRole) : (selectElement.value = previousRole); });
}

function executeRoleChange(userId, newRole) {
    fetch(`/admin/users/${userId}/change-role`, { method: "POST", headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify({ role: newRole }) })
    .then(async response => { const data = await response.json(); if (!response.ok) throw new Error(data.message || 'Failed'); return data; })
    .then(data => {
        saveDashboardState();
        if (data.success) {
            Swal.fire({ icon: 'success', title: 'Role Updated', text: data.message, timer: 1250, showConfirmButton: false }).then(() => { window.location.reload(); });
        }
    })
    .catch(error => { Swal.fire({ icon: 'error', title: 'Action Denied', text: error.message }); });
}

// ============================================================
// AUTHORITY CHANGE
// ============================================================
function executeAuthorityChange(userId, type) {
    let actionText = '';
    if (type === 'super') actionText = 'Do you want to promote this user to Super Admin?';
    else if (type === 'admin') actionText = 'Do you want to make this user a Regular Admin?';
    else actionText = 'Do you want to demote this user to a normal member?';
    Swal.fire({ title: 'Update Admin Privileges?', text: actionText, icon: 'warning', showCancelButton: true, confirmButtonColor: '#0f172a', cancelButtonColor: '#64748b', confirmButtonText: 'Yes, Update Privileges' })
    .then((result) => {
        if (result.isConfirmed) {
            fetch("{{ route('admin.manage.authority') }}", { method: "POST", headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }, body: JSON.stringify({ user_id: userId, type: type }) })
            .then(async response => { const data = await response.json(); if (!response.ok) throw new Error(data.message || 'Something went wrong'); return data; })
            .then(data => { if (data.success) { Swal.fire({ icon: 'success', title: 'Privileges Updated', text: data.message, timer: 1500, showConfirmButton: false }).then(() => { window.location.reload(); }); } })
            .catch(error => { Swal.fire({ icon: 'error', title: 'Action Denied', text: error.message }); });
        }
    });
}

// ============================================================
// ADMIN REPORT ACTIONS (warn / delete-content / dismiss)
// ============================================================
function adminAction(action, reportId) {
    const config = {
        warn:             { title: 'Send Warning?',   text: 'A warning notification will be sent to the user. The report will remain active.', icon: 'warning', confirmText: 'Yes, Warn User', color: '#d97706' },
        'delete-content': { title: 'Delete Content?', text: 'This will permanently delete the reported content.', icon: 'warning', confirmText: 'Yes, Delete', color: '#dc2626' },
        dismiss:          { title: 'Dismiss Report?', text: 'This report will be marked as resolved with no violation found.', icon: 'info', confirmText: 'Dismiss', color: '#16a34a' },
    };
    const c = config[action];
    if (!c) return;

    Swal.fire({ title: c.title, text: c.text, icon: c.icon, showCancelButton: true, confirmButtonColor: c.color, confirmButtonText: c.confirmText })
    .then(result => {
        if (!result.isConfirmed) return;

        let note = null;
        if (action === 'warn' || action === 'delete-content') {
            note = prompt(
                action === 'warn' ? 'Write a note for the user explaining the warning:' : 'Write a reason for deleting this (the user will see this):',
                ''
            );
            if (note === null) return;
        }

        const methodMap = { warn: 'POST', 'delete-content': 'DELETE', dismiss: 'POST' };
        const urlMap = {
            warn: `/admin/reports/${reportId}/warn`,
            'delete-content': `/admin/reports/${reportId}/delete-content`,
            dismiss: `/admin/reports/${reportId}/dismiss`,
        };

        fetch(urlMap[action], {
            method: methodMap[action],
            headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ note: note })
        })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                Toast.fire({ icon: 'success', title: d.message });
                if (action !== 'warn') {
                    removeReportRow(reportId);
                    addHistoryRowLive(d.history_row);
                }
                refreshStatsCards();
            } else {
                Toast.fire({ icon: 'error', title: d.message || 'Something went wrong.' });
            }
        })
        .catch(() => Toast.fire({ icon: 'error', title: 'Network error.' }));
    });
}

// ✅ একটা নির্দিষ্ট রিপোর্ট Read মার্ক করা — persist করে, tab/reload এ প্রভাবিত হয় না

function removeReportRow(reportId) {
    const row = document.getElementById('report-row-' + reportId);
    if (!row) return;

    const reportedTableEl = document.getElementById('reportedTable');
    const jobsTableEl     = document.getElementById('jobsTable');

    if (reportedTableEl && $.contains(reportedTableEl, row) && $.fn.DataTable.isDataTable('#reportedTable')) {
        $('#reportedTable').DataTable().row(row).remove().draw(false);
    } else if (jobsTableEl && $.contains(jobsTableEl, row) && $.fn.DataTable.isDataTable('#jobsTable')) {
        $('#jobsTable').DataTable().row(row).remove().draw(false);
    } else {
        row.remove();
    }

    document.querySelectorAll('.pending-reports-count').forEach(b => {
        b.textContent = Math.max(0, (parseInt(b.textContent) || 0) - 1);
    });
}

// ============================================================
// STATS LIVE REFRESH
// ============================================================
function refreshStatsCards() {
    fetch('/admin/dashboard/analytics-data', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(d => {
        if (d.counters) {
            Object.entries(d.counters).forEach(([key, val]) => {
                const el = document.querySelector(`[data-counter="${key}"]`);
                if (el) el.textContent = val;
            });
        }
    })
    .catch(() => {});
}


function bumpHistoryBadge(delta) {
    const badge = document.getElementById('history-unseen-badge');
    if (!badge) return;
    let count = Math.max(0, (parseInt(badge.textContent) || 0) + delta);
    badge.textContent = count;
    badge.style.display = count > 0 ? '' : 'none';
}

// ============================================================
// LIVE HISTORY COUNT (in-memory, no reload needed)
// ============================================================
let historyUnseenCount = parseInt(document.getElementById('history-unseen-badge')?.textContent || '0');

function setHistoryBadge(count) {
    const badge = document.getElementById('history-unseen-badge');
    if (!badge) return;
    historyUnseenCount = Math.max(0, count);
    badge.textContent = historyUnseenCount;
    badge.style.display = historyUnseenCount > 0 ? '' : 'none';
}

function addHistoryRowLive(html) {
    if (!html) return;
    const tbody = document.querySelector('#historyTable tbody');
    if (!tbody) return;

    const wrapper = document.createElement('table');
    wrapper.innerHTML = `<tbody>${html.trim()}</tbody>`;
    const trNode = wrapper.querySelector('tr');
    if (!trNode) return;

    if ($.fn.DataTable.isDataTable('#historyTable')) {
        const table = $('#historyTable').DataTable();
        table.row.add(trNode);
        table.order([5, 'desc']).draw();   // ✅ প্রতিবার নতুন রো যোগের পর Resolved At অনুযায়ী আবার সর্ট — তাই সবার আগে বসবে

        const historyTabEl = document.getElementById('history-tab');
        if (historyTabEl && historyTabEl.classList.contains('active')) {
            table.columns.adjust();
        }
    } else {
        tbody.insertBefore(trNode, tbody.firstChild);   // ✅ DataTable init না থাকলেও সবার আগে বসবে
    }

    document.querySelector('#history-tab .text-center.py-5')?.remove();
    setHistoryBadge(historyUnseenCount + 1);
}


function markAsRead(reportId, el) {
    fetch(`/admin/reports/${reportId}/mark-seen`, {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => r.json())
    .then(d => {
        if (!d.success) return;

        const row = document.getElementById('report-row-' + reportId)
                 || document.querySelector(`tr[data-report-id="${reportId}"]`);
        if (!row) return;

        const wasHistoryRow = row.hasAttribute('data-report-id');
        row.classList.remove('table-warning', 'table-info');
        row.querySelectorAll('.badge').forEach(b => {
            if (b.textContent.trim() === 'NEW') { b.textContent = '•'; b.className = ''; }
        });

        if (el) {
            if (el.tagName === 'A') {
                const li = el.closest('li');
                const divider = li?.nextElementSibling;
                li?.remove();
                if (divider && divider.querySelector('hr')) divider.remove();
            } else if (el.tagName === 'BUTTON') {
                const span = document.createElement('span');
                span.className = 'text-muted';
                span.style.fontSize = '0.68rem';
                span.innerHTML = '<i class="fa-solid fa-check"></i> Read';
                el.replaceWith(span);
            }
        }

        if (wasHistoryRow) setHistoryBadge(historyUnseenCount - 1); // ✅ লাইভ কাউন্ট -1
    })
    .catch(() => {});
}


// ============================================================
// SUSPEND
// ============================================================
function suspendFromReport(userId, action) {
    let text = '', confirmText = '', color = '';
    if (action === 'temp')   { text = 'Suspend this user for 7 days?';    confirmText = 'Yes, Suspend 7 Days';  color = '#d97706'; }
    else if (action === 'perm')   { text = 'Permanently ban this user?';       confirmText = 'Yes, Ban Permanently'; color = '#dc2626'; }
    else if (action === 'active') { text = 'Restore access for this user?';    confirmText = 'Yes, Restore Access'; color = '#16a34a'; }
    Swal.fire({ title: 'Update Suspension?', text: text, icon: 'warning', showCancelButton: true, confirmButtonColor: color, cancelButtonColor: '#64748b', confirmButtonText: confirmText })
    .then(result => {
        if (!result.isConfirmed) return;
        fetch(`/admin/reports/suspend/${userId}`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ action: action })
        })
        .then(r => r.json())
        .then(d => {
            if (!d.success) { Swal.fire({ icon: 'error', title: 'Failed', text: d.message }); return; }
            Toast.fire({ icon: 'success', title: d.message });

            document.querySelectorAll(`.user-status-badge-${userId}`).forEach(badge => {
                if (action === 'active') {
                    badge.className = `status-badge active user-status-badge-${userId}`;
                    badge.innerHTML = '<i class="fa-solid fa-circle" style="font-size:5px;"></i> Active';
                } else if (action === 'temp') {
                    badge.className = `status-badge temp-suspended user-status-badge-${userId}`;
                    badge.innerHTML = '<i class="fa-solid fa-clock"></i> 7d Suspended';
                } else {
                    badge.className = `status-badge perm-suspended user-status-badge-${userId}`;
                    badge.innerHTML = '<i class="fa-solid fa-ban"></i> Permanent Banned';
                }
            });

            document.querySelectorAll(`[id="suspend-btn-${userId}"]`).forEach(wrap => {
                if (action === 'active') {
                    wrap.innerHTML = `
                        <button onclick="suspendFromReport(${userId}, 'temp')" class="btn-action-pill temp-ban"><i class="fa-solid fa-clock"></i> 7d Ban</button>
                        <button onclick="suspendFromReport(${userId}, 'perm')" class="btn-action-pill perm-ban"><i class="fa-solid fa-ban"></i> Perm Ban</button>`;
                } else {
                    const otherAction = action === 'temp' ? 'perm' : 'temp';
                    const otherLabel  = action === 'temp' ? '<i class="fa-solid fa-ban"></i> Perm Ban' : '<i class="fa-solid fa-clock"></i> 7d Ban';
                    const otherClass  = action === 'temp' ? 'perm-ban' : 'temp-ban';
                    wrap.innerHTML = `
                        <button onclick="suspendFromReport(${userId}, 'active')" class="btn-action-pill activate"><i class="fa-solid fa-circle-check"></i> Remove Suspension</button>
                        <button onclick="suspendFromReport(${userId}, '${otherAction}')" class="btn-action-pill ${otherClass}">${otherLabel}</button>`;
                }
            });
        })
        .catch(() => Swal.fire({ icon: 'error', title: 'Network Error' }));
    });
}

// ============================================================
// COMPLETE REPORT → History (live)
// ============================================================
function completeReport(reportId) {
    Swal.fire({ title: 'Mark as Completed?', text: 'This complaint will move to history.', icon: 'info', showCancelButton: true, confirmButtonColor: '#16a34a', confirmButtonText: 'Yes, Complete' })
    .then(result => {
        if (!result.isConfirmed) return;
        fetch(`/admin/reports/${reportId}/complete`, { method: 'POST', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                Toast.fire({ icon: 'success', title: 'Moved to history!' });
                removeReportRow(reportId);
                addHistoryRowLive(d.history_row);
            } else {
                Swal.fire({ icon: 'error', title: 'Failed', text: d.message });
            }
        });
    });
}

function markReviewed(reportId) {
    Swal.fire({
        title: 'Review Appeal?',
        text: 'Report will be marked as reviewed and dismissed. This action cannot be undone',
        icon: 'question', showCancelButton: true,
        confirmButtonColor: '#16a34a', confirmButtonText: 'Yes, Mark Reviewed'
    }).then(result => {
        if (!result.isConfirmed) return;
        fetch(`/admin/reports/${reportId}/mark-reviewed`, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(r => r.json())
        .then(d => {
            Toast.fire({ icon: d.success ? 'success' : 'error', title: d.message });
            if (d.success) {
                removeReportRow(reportId);
                addHistoryRowLive(d.history_row);
            }
        });
    });
}

// ============================================================
// LIVE POLLING — প্রতি 15 সেকেন্ডে
// ============================================================
let lastPollTime = new Date().toISOString();
let pollingActive = true;

function injectRow(tableId, html, reportId) {
    // duplicate check
    if (document.getElementById('report-row-' + reportId)) return false;

    const wrapper = document.createElement('table');
    wrapper.innerHTML = `<tbody>${html}</tbody>`;
    const trNode = wrapper.querySelector('tr');
    if (!trNode) return false;

    if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().row.add(trNode).draw(false);
    } else {
        const tbody = document.querySelector(tableId + ' tbody');
        if (tbody) tbody.appendChild(trNode);
    }
    return true;
}

function pollReports() {
    if (!pollingActive) return;

    fetch(`/admin/reports/poll?since=${encodeURIComponent(lastPollTime)}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => {
        if (!r.ok) throw new Error('Poll failed');
        return r.json();
    })
    .then(d => {
        if (!d.success) return;
        lastPollTime = d.server_time;

        let hasNewContent = false;
        let hasNewJob     = false;

        // ✅ নতুন content reports inject
        (d.content_rows || []).forEach(item => {
            const added = injectRow('#reportedTable', item.html, item.id);
            if (added) {
                hasNewContent = true;
                // empty state সরাও
                document.querySelector('#posts-tab .text-center.py-5')?.remove();
            }
        });

        // ✅ নতুন job reports inject
        (d.job_rows || []).forEach(item => {
            const added = injectRow('#jobsTable', item.html, item.id);
            if (added) {
                hasNewJob = true;
                document.querySelector('#jobs-tab .text-center.py-5')?.remove();
            }
        });

        // ✅ নতুন/updated history rows
        (d.history_rows || []).forEach(item => {
            document.querySelectorAll(`[data-report-id="${item.id}"]`).forEach(row => {
                if ($.fn.DataTable.isDataTable('#historyTable')) {
                    $('#historyTable').DataTable().row(row).remove();
                } else {
                    row.remove();
                }
            });
            addHistoryRowLive(item.html);
        });

        // ✅ Badge/count live update
        updateLiveCounts(d.pending_content, d.pending_jobs, d.history_unseen);

        // ✅ Toast — নতুন report এলে
        if (hasNewContent || hasNewJob) {
            Toast.fire({
                icon: 'warning',
                title: `🚨 New report${(hasNewContent && hasNewJob) ? 's' : ''} received! Please Reload The Page`
            });
        }
    })
    .catch(() => {}); // silent fail — network error হলে পরের poll এ আবার try করবে
}

function updateLiveCounts(pendingContent, pendingJobs, historyUnseen) {
    // ✅ Reported Contents badge (sidebar + tab header)
    document.querySelectorAll('.pending-reports-count').forEach(el => {
        el.textContent = pendingContent;
        el.style.display = pendingContent > 0 ? '' : 'none';
    });

    // ✅ Reported Jobs badge (sidebar)
    const jobsNavEl = document.querySelector('[data-target="jobs-tab"]');
    if (jobsNavEl) {
        let jobBadge = jobsNavEl.querySelector('.badge');
        if (!jobBadge) {
            jobBadge = document.createElement('span');
            jobBadge.className = 'badge bg-warning text-dark ms-auto';
            jobBadge.style.fontSize = '0.6rem';
            jobsNavEl.appendChild(jobBadge);
        }
        jobBadge.textContent = pendingJobs;
        jobBadge.style.display = pendingJobs > 0 ? '' : 'none';
    }

    // ✅ History unseen badge
    setHistoryBadge(historyUnseen);
}

// Page visible হলেই poll করো (background tab এ করবে না)
document.addEventListener('visibilitychange', () => {
    pollingActive = !document.hidden;
    if (pollingActive) pollReports(); // tab এ ফিরলে সাথে সাথে poll
});

// শুরু করো
pollReports();
setInterval(pollReports, 15000);


</script>
</body>
</html>