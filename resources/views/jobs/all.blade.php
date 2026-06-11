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
    <title>All Jobs — Borobhai.com</title>
    <style>
        :root {
            --bb-primary:#4f46e5; --bb-primary-soft:#eef2ff; --bb-ink:#1e1f24;
            --bb-muted:#6b7280; --bb-line:#eceef1; --bb-bg:#f3f4f8;
        }
        * { font-family:'Inter',-apple-system,sans-serif; }
        body { background:var(--bb-bg); color:var(--bb-ink); margin:0; }
        .jp-nav { background:#fff; box-shadow:0 1px 3px rgba(16,24,40,.06); padding:12px 0; position:sticky; top:0; z-index:100; }
        .jp-brand { font-weight:800; color:var(--bb-primary); font-size:21px; text-decoration:none; letter-spacing:-.5px; }
        .jp-back { color:var(--bb-muted); text-decoration:none; font-weight:600; font-size:14px; display:inline-flex; align-items:center; gap:6px; }
        .jp-back:hover { color:var(--bb-primary); }
        .ja-wrap { max-width:980px; margin:26px auto; padding:0 16px; }
        .ja-head { margin-bottom:20px; }
        .ja-head h1 { font-size:26px; font-weight:800; letter-spacing:-.5px; margin:0 0 3px; }
        .ja-head p { color:var(--bb-muted); font-size:14px; margin:0; }
        .ja-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:16px; }
        @media (max-width:680px){ .ja-grid { grid-template-columns:1fr; } }
        .ja-card { background:#fff; border-radius:16px; box-shadow:0 1px 3px rgba(16,24,40,.06); padding:18px; border:1px solid var(--bb-line); transition:box-shadow .2s, transform .2s; display:flex; flex-direction:column; }
        .ja-card:hover { box-shadow:0 8px 24px rgba(79,70,229,.10); transform:translateY(-2px); }
        .ja-top { display:flex; gap:12px; align-items:flex-start; }
        .ja-logo { width:48px; height:48px; border-radius:12px; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:21px; font-weight:800; background:var(--bb-primary-soft); color:var(--bb-primary); }
        .ja-title { font-size:16px; font-weight:800; color:var(--bb-ink); text-decoration:none; line-height:1.25; letter-spacing:-.2px; }
        .ja-title:hover { color:var(--bb-primary); }
        .ja-company { font-size:12.5px; color:var(--bb-muted); margin:2px 0 0; }
        .ja-meta { display:flex; flex-wrap:wrap; gap:6px; margin:12px 0; }
        .ja-pill { font-size:11.5px; font-weight:600; padding:4px 10px; border-radius:7px; background:var(--bb-bg); color:#4b5563; display:inline-flex; align-items:center; gap:4px; }
        .ja-pill i { font-size:10px; }
        .ja-expiring { color:#ea580c !important; }
        .ja-expired { color:#dc2626 !important; }
        .ja-foot { margin-top:auto; padding-top:12px; }
        .ja-view { display:block; text-align:center; background:var(--bb-primary-soft); color:var(--bb-primary); border-radius:9px; padding:9px; font-size:13px; font-weight:700; text-decoration:none; transition:all .15s; }
        .ja-view:hover { background:var(--bb-primary); color:#fff; }
        .ja-empty { background:#fff; border-radius:16px; padding:50px 20px; text-align:center; color:var(--bb-muted); }
    </style>
</head>
<body>

<nav class="jp-nav">
    <div class="ja-wrap d-flex align-items-center justify-content-between" style="margin:0 auto;">
        <a href="{{ route('home') }}" class="jp-brand">Borobhai.com</a>
        <a href="{{ route('home') }}" class="jp-back"><i class="bi bi-arrow-left"></i> Back to Feed</a>
    </div>
</nav>

<div class="ja-wrap">
    <div class="ja-head">
        <h1>Job Opportunities</h1>
        <p>{{ $jobs->total() }} {{ Str::plural('opening', $jobs->total()) }} shared by alumni · internships and part-time roles first</p>
    </div>

    @if($jobs->count())
        <div class="ja-grid">
            @foreach($jobs as $job)
                @php
                    $jt = strtolower($job->job_type);
                    $logoColor = str_contains($jt,'intern') ? 'background:#fff7ed;color:#ea580c;'
                               : (str_contains($jt,'part') ? 'background:#eff6ff;color:#2563eb;' : '');
                @endphp
                <div class="ja-card">
                    <div class="ja-top">
                        <div class="ja-logo" style="{{ $logoColor }}">{{ strtoupper(substr($job->company,0,1)) }}</div>
                        <div class="flex-grow-1" style="min-width:0;">
                            <a href="{{ route('jobs.show', $job->id) }}" class="ja-title">{{ $job->title }}</a>
                            <p class="ja-company">{{ $job->company }}@if($job->location) · {{ $job->location }}@endif</p>
                        </div>
                    </div>
                    <div class="ja-meta">
                        <span class="ja-pill"><i class="bi bi-briefcase"></i> {{ $job->job_type }}</span>
                        @if($job->salary)<span class="ja-pill"><i class="bi bi-cash-stack"></i> {{ $job->salary }}</span>@endif
                        @if($job->is_expired)
                            <span class="ja-pill ja-expired"><i class="bi bi-x-circle"></i> Deadline over</span>
                        @elseif($job->is_expiring_soon)
                            <span class="ja-pill ja-expiring"><i class="bi bi-alarm"></i> Expiring soon</span>
                        @elseif($job->deadline)
                            <span class="ja-pill"><i class="bi bi-calendar-event"></i> {{ $job->deadline->format('d M') }}</span>
                        @endif
                    </div>
                    <div class="ja-foot">
                        <a href="{{ route('jobs.show', $job->id) }}" class="ja-view">View Details & Apply</a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $jobs->links() }}
        </div>
    @else
        <div class="ja-empty">
            <i class="bi bi-briefcase fs-1 d-block mb-2"></i>
            <h5 class="fw-bold">No jobs available right now</h5>
            <p class="mb-0">Check back soon — alumni post new opportunities regularly.</p>
        </div>
    @endif
</div>

</body>
</html>