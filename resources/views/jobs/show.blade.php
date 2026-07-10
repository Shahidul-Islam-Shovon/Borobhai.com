<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">
    <title>{{ $job->title }} · {{ $job->company }} — Borobhai.online</title>
    <style>
        :root {
            --bb-primary:#4f46e5; --bb-primary-dark:#4338ca; --bb-primary-soft:#eef2ff;
            --bb-ink:#1e1f24; --bb-muted:#6b7280; --bb-line:#eceef1; --bb-bg:#f3f4f8; --bb-card:#fff;
        }
        * { font-family:'Inter',-apple-system,sans-serif; }
        body { background:var(--bb-bg); color:var(--bb-ink); margin:0; }
        .jp-nav { background:#fff; box-shadow:0 1px 3px rgba(16,24,40,.06); padding:12px 0; position:sticky; top:0; z-index:100; }
        .jp-brand { font-weight:800; color:var(--bb-primary); font-size:21px; text-decoration:none; letter-spacing:-.5px; }
        .jp-back { color:var(--bb-muted); text-decoration:none; font-weight:600; font-size:14px; display:inline-flex; align-items:center; gap:6px; transition:color .15s; }
        .jp-back:hover { color:var(--bb-primary); }

        .jp-wrap { max-width:880px; margin:24px auto; padding:0 16px; }

        /* Hero */
        .jp-hero { background:#fff; border-radius:20px; box-shadow:0 1px 3px rgba(16,24,40,.06); overflow:hidden; }
        .jp-hero-band { height:90px; background:linear-gradient(120deg,#4f46e5,#7c73f0 60%,#a78bfa); }
        .jp-hero-body { padding:0 28px 26px; margin-top:-38px; }
        .jp-logo {
            width:78px; height:78px; border-radius:18px; background:#fff; box-shadow:0 4px 14px rgba(16,24,40,.12);
            display:flex; align-items:center; justify-content:center; font-size:34px; font-weight:800;
            color:var(--bb-primary); border:3px solid #fff;
        }
        .jp-title { font-size:27px; font-weight:800; letter-spacing:-.6px; margin:16px 0 4px; line-height:1.15; }
        .jp-company { font-size:15.5px; color:var(--bb-muted); font-weight:600; margin:0 0 14px; }
        .jp-company i { color:var(--bb-primary); }

        .jp-meta-row { display:flex; flex-wrap:wrap; gap:8px; }
        .jp-chip {
            display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:600;
            padding:7px 13px; border-radius:10px; background:var(--bb-bg); color:#374151;
        }
        .jp-chip i { font-size:13px; color:var(--bb-primary); }
        .jp-chip-type { background:var(--bb-primary-soft); color:var(--bb-primary); }

        /* Expiry banners */
        .jp-banner { margin:16px 0 0; padding:12px 16px; border-radius:12px; font-size:13.5px; font-weight:600; display:flex; align-items:center; gap:8px; }
        .jp-banner-warn { background:#fff7ed; color:#c2410c; border:1px solid #fed7aa; }
        .jp-banner-dead { background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; }

        /* Body sections */
        .jp-section { background:#fff; border-radius:18px; box-shadow:0 1px 3px rgba(16,24,40,.06); padding:24px 28px; margin-top:18px; }
        .jp-sec-title { font-size:17px; font-weight:800; letter-spacing:-.3px; margin:0 0 12px; display:flex; align-items:center; gap:9px; }
        .jp-sec-title i { color:var(--bb-primary); }
        .jp-text { font-size:14.5px; line-height:1.7; color:#374151; white-space:pre-line; margin:0; }
        .jp-list { margin:0; padding:0; list-style:none; }
        .jp-list li { font-size:14.5px; line-height:1.6; color:#374151; padding:7px 0 7px 28px; position:relative; border-bottom:1px solid #f3f4f6; }
        .jp-list li:last-child { border-bottom:none; }
        .jp-list li::before { content:'\F26A'; font-family:'bootstrap-icons'; position:absolute; left:2px; top:7px; color:var(--bb-primary); font-size:13px; }

        .jp-skills { display:flex; flex-wrap:wrap; gap:8px; }
        .jp-skill { font-size:13px; font-weight:600; padding:6px 13px; border-radius:20px; background:var(--bb-primary-soft); color:var(--bb-primary); }

        /* Apply card */
        .jp-apply { position:sticky; bottom:0; }
        .jp-apply-card { background:#fff; border-radius:18px; box-shadow:0 -2px 20px rgba(16,24,40,.08); padding:18px 24px; margin-top:18px; display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; }
        .jp-apply-info { font-size:13px; color:var(--bb-muted); }
        .jp-apply-info strong { color:var(--bb-ink); font-size:15px; display:block; }
        .jp-apply-btn {
            background:linear-gradient(135deg,#4f46e5,#7c73f0); color:#fff; border:none;
            border-radius:12px; padding:13px 30px; font-size:15px; font-weight:700; cursor:pointer;
            text-decoration:none; display:inline-flex; align-items:center; gap:8px; transition:all .15s;
            box-shadow:0 4px 14px rgba(79,70,229,.35);
        }
        .jp-apply-btn:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(79,70,229,.45); color:#fff; }
        .jp-apply-btn.disabled { background:#d1d5db; color:#6b7280; box-shadow:none; cursor:not-allowed; pointer-events:auto; }

        .jp-poster { display:flex; align-items:center; gap:10px; margin-top:6px; }
        .jp-poster-av { width:34px; height:34px; border-radius:50%; object-fit:cover; background:linear-gradient(135deg,#4f46e5,#7c73f0); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; }
        .jp-poster-name { font-size:13px; font-weight:600; }
        .jp-poster-name small { color:var(--bb-muted); font-weight:400; display:block; font-size:11.5px; }

        @media (max-width:576px){
            .jp-title { font-size:22px; }
            .jp-section, .jp-hero-body { padding-left:18px; padding-right:18px; }
            .jp-apply-card { flex-direction:column; align-items:stretch; }
            .jp-apply-btn { justify-content:center; }
        }

        /* Apply modal form */
        .bb-job-label { display:block; font-size:12.5px; font-weight:600; color:#374151; margin-bottom:5px; }
        .bb-job-input { width:100%; border:1.5px solid #e4e6eb; border-radius:10px; padding:9px 12px; font-size:13.5px; outline:none; transition:border-color .15s, box-shadow .15s; background:#fff; }
        .bb-job-input:focus { border-color:#4f46e5; box-shadow:0 0 0 3px rgba(79,70,229,.12); }
        textarea.bb-job-input { resize:vertical; }
        .apply-dialog { height:calc(100vh - 3.5rem); }
        .apply-content { max-height:100%; display:flex; flex-direction:column; overflow:hidden; }
        .apply-form { display:flex; flex-direction:column; min-height:0; flex:1 1 auto; overflow:hidden; }
        .apply-body { overflow-y:auto; flex:1 1 auto; min-height:0; }
        .apply-footer { flex:0 0 auto; }
        @media (max-width:576px){ .apply-dialog { height:calc(100vh - 1rem); } }

        /* ===== Leaving Site Modal (premium) ===== */
        .leave-modal-content { border:none; border-radius:22px; overflow:hidden; box-shadow:0 24px 60px rgba(16,24,40,.28); }
        .leave-hero { background:linear-gradient(135deg,#4f46e5,#7c73f0 55%,#a78bfa); padding:30px 28px 26px; text-align:center; position:relative; }
        .leave-hero-icon {
            width:70px; height:70px; border-radius:20px; background:rgba(255,255,255,.18);
            display:flex; align-items:center; justify-content:center; margin:0 auto 14px;
            font-size:32px; color:#fff; backdrop-filter:blur(4px); border:1.5px solid rgba(255,255,255,.3);
            animation:leavePulse 2s ease-in-out infinite;
        }
        @keyframes leavePulse { 0%,100%{ transform:translateY(0);} 50%{ transform:translateY(-5px);} }
        .leave-hero h4 { color:#fff; font-weight:800; font-size:21px; margin:0 0 5px; letter-spacing:-.4px; }
        .leave-hero p { color:rgba(255,255,255,.88); font-size:13.5px; margin:0; }
        .leave-body { padding:24px 28px 14px; }
        .leave-dest {
            display:flex; align-items:center; gap:12px; background:var(--bb-bg); border-radius:14px;
            padding:14px 16px; margin-bottom:16px; border:1px solid var(--bb-line);
        }
        .leave-dest-logo {
            width:46px; height:46px; border-radius:12px; flex-shrink:0; background:var(--bb-primary-soft);
            color:var(--bb-primary); display:flex; align-items:center; justify-content:center; font-size:20px; font-weight:800;
        }
        .leave-dest-info { min-width:0; flex-grow:1; }
        .leave-dest-label { font-size:11px; color:var(--bb-muted); font-weight:600; text-transform:uppercase; letter-spacing:.4px; margin:0; }
        .leave-dest-url { font-size:13.5px; font-weight:700; color:var(--bb-ink); margin:2px 0 0; word-break:break-all; line-height:1.3; }
        .leave-copy-btn {
            flex-shrink:0; border:1.5px solid var(--bb-line); background:#fff; color:var(--bb-muted);
            width:38px; height:38px; border-radius:10px; cursor:pointer; transition:all .15s; font-size:15px;
        }
        .leave-copy-btn:hover { border-color:var(--bb-primary); color:var(--bb-primary); }
        .leave-steps { margin:0 0 16px; padding:0; list-style:none; }
        .leave-steps li { display:flex; align-items:flex-start; gap:10px; font-size:13px; color:#4b5563; padding:5px 0; }
        .leave-steps li i { color:#16a34a; font-size:15px; margin-top:1px; flex-shrink:0; }
        .leave-safety { font-size:11.5px; color:var(--bb-muted); background:#fffbeb; border:1px solid #fde68a; border-radius:10px; padding:9px 12px; display:flex; align-items:flex-start; gap:8px; line-height:1.5; }
        .leave-safety i { color:#d97706; flex-shrink:0; margin-top:1px; }
        .leave-foot { padding:6px 28px 24px; display:flex; gap:10px; }
        .leave-btn-cancel { flex:0 0 auto; border:1.5px solid var(--bb-line); background:#fff; color:#4b5563; border-radius:12px; padding:12px 20px; font-size:14px; font-weight:600; cursor:pointer; transition:all .15s; }
        .leave-btn-cancel:hover { background:var(--bb-bg); }
        .leave-btn-go { flex:1; border:none; border-radius:12px; padding:12px 20px; font-size:14.5px; font-weight:700; cursor:pointer; color:#fff; background:linear-gradient(135deg,#4f46e5,#7c73f0); box-shadow:0 4px 14px rgba(79,70,229,.35); transition:all .15s; display:inline-flex; align-items:center; justify-content:center; gap:7px; }
        .leave-btn-go:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(79,70,229,.45); }
        .modal-backdrop.leave-blur { backdrop-filter:blur(3px); }

        /* ===== Post-leave confirm banner (in-page, real-life style) ===== */
        .confirm-banner {
            background:linear-gradient(135deg,#eef2ff,#f5f3ff); border:1.5px solid #ddd6fe;
            border-radius:18px; padding:20px 24px; margin-top:18px; display:none;
            animation:bannerIn .35s ease;
        }
        .confirm-banner.show { display:block; }
        @keyframes bannerIn { from{ opacity:0; transform:translateY(8px);} to{ opacity:1; transform:translateY(0);} }
        .confirm-banner-top { display:flex; align-items:center; gap:12px; margin-bottom:6px; }
        .confirm-banner-icon { width:42px; height:42px; border-radius:12px; background:#fff; color:var(--bb-primary); display:flex; align-items:center; justify-content:center; font-size:20px; flex-shrink:0; box-shadow:0 2px 8px rgba(79,70,229,.15); }
        .confirm-banner-title { font-size:15.5px; font-weight:800; color:var(--bb-ink); margin:0; }
        .confirm-banner-sub { font-size:12.5px; color:var(--bb-muted); margin:1px 0 0; }
        .confirm-banner-actions { display:flex; gap:10px; margin-top:14px; flex-wrap:wrap; }
        .cb-yes { flex:1; min-width:160px; border:none; border-radius:11px; padding:11px 18px; font-size:14px; font-weight:700; cursor:pointer; color:#fff; background:linear-gradient(135deg,#16a34a,#22c55e); box-shadow:0 4px 12px rgba(34,197,94,.3); transition:all .15s; display:inline-flex; align-items:center; justify-content:center; gap:7px; }
        .cb-yes:hover { transform:translateY(-1px); box-shadow:0 6px 16px rgba(34,197,94,.4); }
        .cb-reopen { border:1.5px solid var(--bb-primary); background:#fff; color:var(--bb-primary); border-radius:11px; padding:11px 18px; font-size:14px; font-weight:600; cursor:pointer; transition:all .15s; }
        .cb-reopen:hover { background:var(--bb-primary-soft); }
        .cb-cancel { border:1.5px solid var(--bb-line); background:#fff; color:#6b7280; border-radius:11px; padding:11px 16px; font-size:14px; font-weight:600; cursor:pointer; transition:all .15s; }
        .cb-cancel:hover { background:var(--bb-bg); }
    </style>
</head>
<body>

@if($isAdminReviewMode ?? false)
    <nav style="background:#0f172a;padding:.7rem 1.2rem;position:sticky;top:0;z-index:100;box-shadow:0 2px 8px rgba(0,0,0,.15);">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2 text-white">
                <i class="bi bi-shield-lock-fill text-warning"></i>
                <span class="fw-bold" style="font-size:14px;">
                    Admin Review Mode — Viewing Job: {{ $job->title }}
                </span>
            </div>
            <a href="{{ route('admin.dashboard') }}"
               style="background:#1e293b;color:#fff;border-radius:8px;padding:7px 14px;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </nav>
@else
    @include('partials.inner-navbar')
@endif

@php
    $expired = $job->is_expired;
    $isOwner = auth()->id() === $job->user_id;
    $expiringSoon = $job->is_expiring_soon;
@endphp

<div class="jp-wrap">

    {{-- HERO --}}
    <div class="jp-hero">
        <div class="jp-hero-band"></div>
        <div class="jp-hero-body">
            <div class="jp-logo">{{ strtoupper(substr($job->company, 0, 1)) }}</div>
            <h1 class="jp-title">{{ $job->title }}</h1>
            <p class="jp-company"><i class="bi bi-building"></i> {{ $job->company }}@if($job->location) &nbsp;·&nbsp; <i class="bi bi-geo-alt"></i> {{ $job->location }}@endif</p>

            <div class="jp-meta-row">
                <span class="jp-chip jp-chip-type"><i class="bi bi-briefcase-fill"></i> {{ $job->job_type }}</span>
                @if($job->salary)<span class="jp-chip"><i class="bi bi-cash-stack"></i> {{ $job->salary }}</span>@endif
                @if($job->experience)<span class="jp-chip"><i class="bi bi-bar-chart-line"></i> {{ $job->experience }}</span>@endif
                @if($job->category)<span class="jp-chip"><i class="bi bi-tag"></i> {{ $job->category }}</span>@endif
                @if($job->deadline)<span class="jp-chip"><i class="bi bi-calendar-event"></i> Apply by {{ $job->deadline->format('d M Y') }}</span>@endif
            </div>

            @if($expired)
                <div class="jp-banner jp-banner-dead"><i class="bi bi-x-circle-fill"></i> The application deadline has passed. This job is no longer accepting applicants.</div>
            @elseif($expiringSoon)
                <div class="jp-banner jp-banner-warn"><i class="bi bi-alarm-fill"></i> Expiring soon — apply before {{ $job->deadline->format('d M Y') }}.</div>
            @endif
        </div>
    </div>

    {{-- DESCRIPTION --}}
    <div class="jp-section">
        <h2 class="jp-sec-title"><i class="bi bi-file-text"></i> Job Description</h2>
        {!! bb_format_text($job->description) !!}
    </div>

    {{-- REQUIREMENTS --}}
    @if($job->requirements)
    <div class="jp-section">
        <h2 class="jp-sec-title"><i class="bi bi-check2-square"></i> Requirements</h2>
        {!! bb_format_text($job->requirements) !!}
    </div>
    @endif

    {{-- SKILLS --}}
    @if(count($job->skills_array))
    <div class="jp-section">
        <h2 class="jp-sec-title"><i class="bi bi-stars"></i> Skills</h2>
        <div class="jp-skills">
            @foreach($job->skills_array as $skill)
                <span class="jp-skill">{{ $skill }}</span>
            @endforeach
        </div>
    </div>
    @endif

    {{-- POSTED BY --}}
    <div class="jp-section">
        <h2 class="jp-sec-title"><i class="bi bi-person-badge"></i> Posted by</h2>
        <div class="jp-poster">
            <div class="jp-poster-av">
                @if($job->user->profile_picture)
                    <img src="{{ asset('storage/'.$job->user->profile_picture) }}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
                @else
                    {{ strtoupper(substr($job->user->name, 0, 1)) }}
                @endif
            </div>
            <div class="jp-poster-name">
                {{ $job->user->name }}
               <small>{{ ucfirst($job->user->role) }} · posted {{ $job->created_at->diffForHumans() }}</small>
            </div>
        </div>
    </div>

    {{-- APPLY --}}
    <div class="jp-apply">
        @if($isAdminReviewMode ?? false)
        <div class="jp-apply-card">
            <div class="jp-apply-info">
                <strong><i class="bi bi-shield-lock-fill text-warning"></i> Admin Review Mode</strong>
                Apply and interaction options are hidden while reviewing this job.
            </div>
        </div>
        @else
        <div class="jp-apply-card" id="applyCard">
            @if($isOwner)
                <div class="jp-apply-info">
                    <strong><i class="bi bi-person-badge"></i> This is your job posting</strong>
                    Manage and review applicants here.
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('jobs.applicants', $job) }}" class="jp-apply-btn"><i class="bi bi-people-fill"></i> View Applicants</a>
                    <button type="button" class="jp-apply-btn" style="background:#fff;color:var(--bb-primary);border:1.5px solid var(--bb-primary);box-shadow:none;"
                            onclick="downloadJobReport('{{ $job->getRouteKey() }}')">
                        <i class="bi bi-file-earmark-arrow-down"></i> Download this Job Report
                    </button>
                </div>
            @elseif($expired)
                <div class="jp-apply-info">
                    <strong>Applications closed</strong>
                    The deadline for this position has passed.
                </div>
                <button class="jp-apply-btn disabled" onclick="deadlineWarn()"><i class="bi bi-lock-fill"></i> Applications Closed</button>
            @elseif($hasApplied)
                <div class="jp-apply-info">
                    <strong style="color:#16a34a;"><i class="bi bi-check-circle-fill"></i> Application submitted</strong>
                    You have already applied to this job.
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('jobs.myApplications') }}" class="jp-apply-btn" style="background:#eef2ff;color:var(--bb-primary);box-shadow:none;"><i class="bi bi-clock-history"></i> View in History</a>
                    @if(($myAppMethod ?? 'inapp') === 'inapp' && in_array($myAppStatus, ['pending', 'reviewed']))
                        <button class="jp-apply-btn" style="background:#fef2f2;color:#dc2626;box-shadow:none;" onclick="withdrawApplication('{{ $job->getRouteKey() }}')"><i class="bi bi-x-circle"></i> Withdraw</button>
                    @endif
                </div>
            @else
                <div class="jp-apply-info">
                    <strong>Ready to apply?</strong>
                    Apply directly on Borobhai, or via the company.
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="jp-apply-btn" onclick="openApplyModal()"><i class="bi bi-send-fill"></i> Apply on Borobhai</button>
                    @php
                        $applyHref = $job->apply_type === 'email'
                            ? 'mailto:'.$job->apply_value.'?subject='.urlencode('Application for '.$job->title)
                            : (str_starts_with($job->apply_value, 'http') ? $job->apply_value : 'https://'.$job->apply_value);
                    @endphp
                    <button class="jp-apply-btn" style="background:#fff;color:var(--bb-primary);border:1.5px solid var(--bb-primary);box-shadow:none;"
                            data-job-id="{{ $job->getRouteKey() }}"
                            data-apply-type="{{ $job->apply_type }}"
                            data-apply-href="{{ $applyHref }}"
                            onclick="applyExternalBtn(this)">
                        <i class="bi bi-box-arrow-up-right"></i> Apply via Company
                    </button>                                      
                </div>
            @endif
        @endif
    </div>

        @if(!$isOwner && !$expired && !$hasApplied)
        {{-- Continue ক্লিকের পর দেখানো confirm banner (real-life style) --}}
        <div class="confirm-banner" id="confirmBanner">
            <div class="confirm-banner-top">
                <div class="confirm-banner-icon"><i class="bi bi-send-check"></i></div>
                <div>
                    <p class="confirm-banner-title">Did you finish applying on the company site?</p>
                    <p class="confirm-banner-sub">Mark as applied to save in your Job History — only confirm if you actually submitted your application.</p>
                </div>
            </div>
            <div class="confirm-banner-actions">
                <button class="cb-yes" onclick="markAsApplied()"><i class="bi bi-check-circle-fill"></i> Yes, I submitted</button>
                <button class="cb-reopen" onclick="reopenExternal()"><i class="bi bi-box-arrow-up-right"></i> Open again</button>
                <button class="cb-cancel" onclick="cancelConfirm()">Not now</button>
            </div>
        </div>
        @endif
    </div>

    @if(!$isOwner && !$expired && !$hasApplied)
    {{-- ==================== APPLY MODAL ==================== --}}
    <div class="modal fade" id="applyModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered apply-dialog">
            <div class="modal-content border-0 shadow-lg rounded-4 apply-content">
                <div class="modal-header border-bottom">
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Apply for this position</h5>
                        <small class="text-muted">{{ $job->title }} · {{ $job->company }}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="applyForm" class="apply-form" enctype="multipart/form-data">
                    <div class="modal-body p-4 apply-body">
                        <input type="hidden" name="apply_method" value="inapp">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="bb-job-label">Full Name *</label>
                                <input type="text" name="applicant_name" id="ap_name" class="bb-job-input" value="{{ Auth::user()->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="bb-job-label">Email *</label>
                                <input type="email" name="applicant_email" id="ap_email" class="bb-job-input" value="{{ Auth::user()->email }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="bb-job-label">Phone</label>
                                <input type="text" name="phone" id="ap_phone" class="bb-job-input" value="{{ Auth::user()->phone ?? '' }}" placeholder="01XXXXXXXXX" required>
                            </div>
                            <div class="col-md-6">
                                <label class="bb-job-label">Resume / CV <span class="text-muted" style="font-weight:400;">(PDF/Word, max 5MB)</span></label>
                                <input type="file" name="resume" id="ap_resume" class="bb-job-input" accept=".pdf,.doc,.docx" required>
                            </div>
                            <div class="col-12">
                                <label class="bb-job-label">Cover Note (If Need) <span class="text-muted" style="font-weight:400;">(why you're a good fit)</span></label>
                                <textarea name="cover_note" id="ap_cover" class="bb-job-input" rows="4" placeholder="Briefly introduce yourself and explain why you're interested..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer apply-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="jp-apply-btn" id="applySubmitBtn" onclick="submitApply()" style="padding:10px 24px;"><i class="bi bi-send-fill me-1"></i> Submit Application</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- ==================== LEAVING SITE MODAL (External Apply) ==================== --}}
    @if(!$isOwner && !$expired && !$hasApplied)
    <div class="modal fade" id="leaveModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content leave-modal-content">
                <div class="leave-hero">
                    <div class="leave-hero-icon"><i class="bi bi-box-arrow-up-right"></i></div>
                    <h4>You're leaving Borobhai</h4>
                    <p>You'll continue your application on the company's site</p>
                </div>
                <div class="leave-body">
                    <div class="leave-dest">
                        <div class="leave-dest-logo">{{ strtoupper(substr($job->company, 0, 1)) }}</div>
                        <div class="leave-dest-info">
                            <p class="leave-dest-label">{{ $job->apply_type === 'email' ? 'Apply via email' : 'Destination' }}</p>
                            <p class="leave-dest-url" id="leaveDestUrl">—</p>
                        </div>
                        <button class="leave-copy-btn" onclick="copyApplyLink()" title="Copy link"><i class="bi bi-clipboard"></i></button>
                    </div>

                    <ul class="leave-steps">
                        <li><i class="bi bi-1-circle-fill"></i> Complete your application on the company's official page</li>
                        <li><i class="bi bi-2-circle-fill"></i> We'll save this in your <strong>Job History</strong> as applied</li>
                        <li><i class="bi bi-3-circle-fill"></i> Come back anytime to track or withdraw</li>
                    </ul>

                    <div class="leave-safety">
                        <i class="bi bi-shield-exclamation"></i>
                        <span>You're being redirected to an external website. Borobhai isn't responsible for content or data you share there. Never pay money to apply for a job.</span>
                    </div>
                </div>
                <div class="leave-foot">
                    <button class="leave-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button class="leave-btn-go" id="leaveGoBtn"><i class="bi bi-box-arrow-up-right"></i> Continue to Company Site</button>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deadlineWarn() {
    const Toast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:3000, timerProgressBar:true });
    Toast.fire({ icon:'warning', title:'Deadline over — applications are closed for this job.' });
}

const APPLY_CSRF = document.querySelector('meta[name="csrf-token"]').content;

// back button (bfcache) থেকে এলে stale state এড়াতে reload
window.addEventListener('pageshow', function (e) {
    // external apply চলাকালীন reload করব না — নাহলে confirm banner চলে যায়
    if (window._externalApplyActive) return;
    if (e.persisted) location.reload();
});
const applyToast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:2400, timerProgressBar:true });
let applyModalObj = null;
document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('applyModal');
    if (el) applyModalObj = bootstrap.Modal.getOrCreateInstance(el);
});

function openApplyModal(){ applyModalObj?.show(); }

// in-app apply submit
// in-app apply submit
function submitApply(){
    const form   = document.getElementById('applyForm');
    const btn    = document.getElementById('applySubmitBtn');
    const nameEl   = document.getElementById('ap_name');
    const emailEl  = document.getElementById('ap_email');
    const phoneEl  = document.getElementById('ap_phone');
    const resumeEl = document.getElementById('ap_resume');

    const name   = nameEl.value.trim();
    const email  = emailEl.value.trim();
    const phone  = phoneEl.value.trim();
    const hasResume = resumeEl.files && resumeEl.files.length > 0;

    // সব required field reset
    [nameEl, emailEl, phoneEl, resumeEl].forEach(el => el.style.borderColor = '');

    // ── client-side validation ──
    const missing = [];
    if (!name)      { missing.push('Full Name');  nameEl.style.borderColor   = '#dc2626'; }
    if (!email)     { missing.push('Email');       emailEl.style.borderColor  = '#dc2626'; }
    if (!phone)     { missing.push('Phone');       phoneEl.style.borderColor  = '#dc2626'; }
    if (!hasResume) { missing.push('Resume/CV');   resumeEl.style.borderColor = '#dc2626'; }

    if (missing.length) {
        applyToast.fire({ icon:'error', title: 'Please fill: ' + missing.join(', ') });
        // প্রথম খালি field এ focus
        const firstEmpty = [nameEl, emailEl, phoneEl].find(el => !el.value.trim()) || (!hasResume ? resumeEl : null);
        if (firstEmpty) firstEmpty.focus();
        return;
    }

    // email format হালকা যাচাই
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        emailEl.style.borderColor = '#dc2626';
        applyToast.fire({ icon:'error', title:'Please enter a valid email address' });
        emailEl.focus();
        return;
    }

    // resume file size/type হালকা যাচাই (backend ও করবে)
    if (hasResume) {
        const f = resumeEl.files[0];
        const okType = /\.(pdf|doc|docx)$/i.test(f.name);
        if (!okType) {
            resumeEl.style.borderColor = '#dc2626';
            applyToast.fire({ icon:'error', title:'Resume must be PDF or Word (.pdf/.doc/.docx)' });
            return;
        }
        if (f.size > 5 * 1024 * 1024) {
            resumeEl.style.borderColor = '#dc2626';
            applyToast.fire({ icon:'error', title:'Resume cannot exceed 5MB' });
            return;
        }
    }

    btn.disabled = true; const orig = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Submitting...';
    const fd = new FormData(form);

    fetch("{{ route('jobs.apply', $job) }}", {
        method:'POST', headers:{'X-CSRF-TOKEN':APPLY_CSRF,'Accept':'application/json'}, body:fd
    })
    .then(r=>r.json())
    .then(d=>{
        btn.disabled=false; btn.innerHTML=orig;
        if(!d.success){
            let msg = d.message || 'Could not apply.';
            if (d.errors) msg = Object.values(d.errors).flat().join('\n');
            Swal.fire({icon:'error', title:'Failed', text:msg});
            return;
        }
        applyModalObj?.hide();
        Swal.fire({
            icon:'success', title:'Application Submitted!',
            text:'Your application has been sent. Track it in your Job History.',
            confirmButtonColor:'#4f46e5'
        }).then(()=>location.reload());
    })
    .catch(()=>{ btn.disabled=false; btn.innerHTML=orig; Swal.fire({icon:'error',title:'Network error'}); });
}

// ===== EXTERNAL APPLY — premium "leaving site" flow =====
let leaveModalObj = null;
let pendingExternal = { jobId:null, applyType:null, applyHref:null };

document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('leaveModal');
    if (el) leaveModalObj = bootstrap.Modal.getOrCreateInstance(el);

    // "Continue to Company Site" বাটন
    const goBtn = document.getElementById('leaveGoBtn');
    if (goBtn) goBtn.addEventListener('click', confirmExternalLeave);
});

// "Apply via Company" ক্লিক → আগে সুন্দর leaving modal দেখাও
function applyExternalBtn(btn){
    pendingExternal = {
        jobId:    btn.dataset.jobId,
        applyType:btn.dataset.applyType,
        applyHref:btn.dataset.applyHref
    };

    // লিংক/মেইল আছে কিনা যাচাই
    if (!pendingExternal.applyHref || pendingExternal.applyHref === 'mailto:' || pendingExternal.applyHref.trim() === '') {
        Swal.fire({
            icon:'warning',
            title:'No apply link provided',
            text:'এই job এ প্রতিষ্ঠানের apply লিংক/ইমেইল দেওয়া নেই। Apply on Borobhai ব্যবহার করুন।'
        });
        return;
    }

    // destination সুন্দর করে দেখাই
    const destEl = document.getElementById('leaveDestUrl');
    if (destEl) {
        if (pendingExternal.applyType === 'email') {
            destEl.textContent = pendingExternal.applyHref.replace('mailto:', '').split('?')[0];
        } else {
            try {
                const u = new URL(pendingExternal.applyHref);
                destEl.textContent = u.hostname + (u.pathname !== '/' ? u.pathname : '');
            } catch(e) { destEl.textContent = pendingExternal.applyHref; }
        }
    }

    leaveModalObj?.show();
}

// লিংক কপি
function copyApplyLink(){
    const href = pendingExternal.applyType === 'email'
        ? pendingExternal.applyHref.replace('mailto:', '').split('?')[0]
        : pendingExternal.applyHref;
    navigator.clipboard?.writeText(href).then(()=>{
        applyToast.fire({ icon:'success', title:'Link copied!' });
    }).catch(()=>{
        applyToast.fire({ icon:'info', title:href });
    });
}

// "Continue" — external এ পাঠাই + in-page confirm banner দেখাই (auto-detect নেই)
function confirmExternalLeave(){
    const { applyType, applyHref } = pendingExternal;
    leaveModalObj?.hide();

    // external apply চলছে — pageshow reload বন্ধ রাখি
    window._externalApplyActive = true;

    openExternalTarget(applyType, applyHref);

    const banner = document.getElementById('confirmBanner');
    if (banner) {
        banner.classList.add('show');
        setTimeout(()=> banner.scrollIntoView({ behavior:'smooth', block:'center' }), 300);
    }
}

// OPEN external link/email in new tab (Gmail web for mailto)
function openExternalTarget(applyType, applyHref){
    if (applyType === 'email') {
        var raw     = applyHref.replace(/^mailto:/i, '');
        var email   = raw.split('?')[0];
        var subject = '';
        var m = raw.match(/[?&]subject=([^&]*)/i);
        if (m) subject = decodeURIComponent(m[1].replace(/\+/g, ' '));

        var gmailUrl = 'https://mail.google.com/mail/?view=cm&fs=1&to='
                     + encodeURIComponent(email)
                     + '&su=' + encodeURIComponent(subject);

        window.open(gmailUrl, '_blank');   // ← noopener বাদ, fallback বাদ
    } else {
        window.open(applyHref, '_blank');
    }
}



// "Open again" — আবার external সাইট/মেইল খোলে
function reopenExternal(){
    openExternalTarget(pendingExternal.applyType, pendingExternal.applyHref);
}

// "Not now" — banner লুকাই, কিছুই track হয় না
function cancelConfirm(){
    const banner = document.getElementById('confirmBanner');
    if (banner) banner.classList.remove('show');
    applyToast.fire({ icon:'info', title:'No problem — you can apply anytime' });
}

// "Yes, I submitted" — এখন track হয়
function markAsApplied(){
    if (!pendingExternal.jobId) return;
    const yesBtn = document.querySelector('.cb-yes');
    if (yesBtn) { yesBtn.disabled = true; yesBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...'; }
    trackExternalApply(pendingExternal.jobId, pendingExternal.applyType);
}

// আসল track — শুধু user "Yes, I applied" বললে
function trackExternalApply(jobId, applyType){
    fetch(`/jobs/${jobId}/apply`, {
        method:'POST',
        headers:{
            'X-CSRF-TOKEN': APPLY_CSRF,
            'Accept':'application/json',
            'Content-Type':'application/json'
        },
        body: JSON.stringify({ apply_method:'external' })
    })
    .then(r=>r.json())
    .then(d=>{
        if (d.success) {
            applyToast.fire({ icon:'success', title:'Application tracked! ✓' });
            setTimeout(()=>location.reload(), 1400);
        } else {
            applyToast.fire({ icon:'info', title: d.message || 'Could not track' });
            setTimeout(()=>location.reload(), 1400);
        }
    })
    .catch(()=>{
        applyToast.fire({ icon:'error', title:'Network error' });
    });
}

// ===== external apply state =====
let pendingExternal2 = null; // (reserved)

// withdraw
function withdrawApplication(jobId){
    Swal.fire({
        title:'Withdraw application?', icon:'warning', showCancelButton:true,
        confirmButtonColor:'#ef4444', confirmButtonText:'Withdraw'
    }).then(r=>{
        if(!r.isConfirmed) return;
        fetch(`/jobs/${jobId}/withdraw`, {
            method:'POST', headers:{'X-CSRF-TOKEN':APPLY_CSRF,'Accept':'application/json'}
        })
        .then(r=>r.json())
        .then(d=>{
            if(!d.success){
                Swal.fire({ icon:'info', title:'Cannot withdraw', text: d.message || 'This application cannot be withdrawn.' });
                return;
            }
            applyToast.fire({icon:'info', title:'Application withdrawn'});
            setTimeout(()=>location.reload(), 1000);
        })
        .catch(()=>{ Swal.fire({icon:'error', title:'Network error'}); });
    });
}

// Job Report PDF download — loading overlay সহ
function downloadJobReport(jobHash){
    var overlay = document.getElementById('reportLoadingOverlay');
    if (overlay) overlay.style.display = 'flex';

    var url = '/jobs/' + jobHash + '/report';

    fetch(url, { headers: { 'Accept': 'application/pdf' } })
    .then(function(r){
        if (!r.ok) throw new Error('Failed');
        return r.blob();
    })
    .then(function(blob){
        // blob থেকে download trigger
        var a = document.createElement('a');
        var objUrl = window.URL.createObjectURL(blob);
        a.href = objUrl;

        // filename — response এ না পেলে default
        a.download = 'borobhai-job-report.pdf';
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(objUrl);

        if (overlay) overlay.style.display = 'none';
        Swal.fire({ icon:'success', title:'Report downloaded!', timer:1600, showConfirmButton:false });
    })
    .catch(function(){
        if (overlay) overlay.style.display = 'none';
        Swal.fire({ icon:'error', title:'Could not generate report', text:'Please try again.' });
    });
}

</script>

<div id="reportLoadingOverlay" style="display:none;position:fixed;inset:0;z-index:99999;
     background:rgba(255,255,255,.55);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px);
     align-items:center;justify-content:center;flex-direction:column;">
    <div style="width:64px;height:64px;border:5px solid #e5e7eb;border-top-color:#4f46e5;
                border-radius:50%;animation:reportSpin .8s linear infinite;"></div>
    <div style="margin-top:20px;font-size:17px;font-weight:700;color:#4f46e5;font-family:'Inter',sans-serif;">
        Download Your Data, Please Wait
    </div>
    <div style="margin-top:6px;font-size:12.5px;color:#6b7280;font-family:'Inter',sans-serif;">
        Generating job report PDF…
    </div>
</div>
<style>
@keyframes reportSpin { to { transform:rotate(360deg); } }
</style>


</body>
</html>