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
    <title>{{ $job->title }} · {{ $job->company }} — Borobhai.com</title>
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
    </style>
</head>
<body>

<nav class="jp-nav">
    <div class="jp-wrap d-flex align-items-center justify-content-between" style="margin:0 auto;">
        <a href="{{ route('home') }}" class="jp-brand">Borobhai.com</a>
        <a href="{{ route('home') }}" class="jp-back"><i class="bi bi-arrow-left"></i> Back to Feed</a>
    </div>
</nav>

@php
    $expired = $job->is_expired;
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
        <p class="jp-text">{{ $job->description }}</p>
    </div>

    {{-- REQUIREMENTS --}}
    @if($job->requirements)
    <div class="jp-section">
        <h2 class="jp-sec-title"><i class="bi bi-check2-square"></i> Requirements</h2>
        <p class="jp-text">{{ $job->requirements }}</p>
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
                <small>Alumni · posted {{ $job->created_at->diffForHumans() }}</small>
            </div>
        </div>
    </div>

    {{-- APPLY --}}
    <div class="jp-apply">
        <div class="jp-apply-card">
            <div class="jp-apply-info">
                <strong>Ready to apply?</strong>
                @if($expired)
                    Applications are closed for this position.
                @else
                    Apply via {{ $job->apply_type === 'email' ? 'email' : 'the application link' }}.
                @endif
            </div>
            @if($expired)
                <button class="jp-apply-btn disabled" onclick="deadlineWarn()"><i class="bi bi-lock-fill"></i> Applications Closed</button>
            @else
                @php
                    $applyHref = $job->apply_type === 'email'
                        ? 'mailto:'.$job->apply_value.'?subject='.urlencode('Application for '.$job->title)
                        : (str_starts_with($job->apply_value, 'http') ? $job->apply_value : 'https://'.$job->apply_value);
                @endphp
                <a href="{{ $applyHref }}" target="_blank" class="jp-apply-btn"><i class="bi bi-send-fill"></i> Apply Now</a>
            @endif
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deadlineWarn() {
    const Toast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:3000, timerProgressBar:true });
    Toast.fire({ icon:'warning', title:'Deadline over — applications are closed for this job.' });
}
</script>

</body>
</html>