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
    <title>My Job Applications — Borobhai.online</title>
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

        .ma-wrap { max-width:860px; margin:26px auto; padding:0 16px; }
        .ma-head h1 { font-size:25px; font-weight:800; letter-spacing:-.5px; margin:0 0 3px; }
        .ma-head p { color:var(--bb-muted); font-size:14px; margin:0 0 20px; }

        /* Stats */
        .ma-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:22px; }
        @media (max-width:600px){ .ma-stats { grid-template-columns:repeat(2,1fr); } }
        .ma-stat { background:#fff; border-radius:14px; padding:16px; box-shadow:0 1px 3px rgba(16,24,40,.06); border:1px solid var(--bb-line); }
        .ma-stat-num { font-size:26px; font-weight:800; letter-spacing:-1px; line-height:1; }
        .ma-stat-label { font-size:12px; color:var(--bb-muted); margin-top:5px; font-weight:500; }
        .ma-stat.total .ma-stat-num { color:var(--bb-primary); }
        .ma-stat.pending .ma-stat-num { color:#2563eb; }
        .ma-stat.short .ma-stat-num { color:#16a34a; }
        .ma-stat.reject .ma-stat-num { color:#dc2626; }

        /* Filter pills */
        .ma-filters { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:18px; }
        .ma-filter { font-size:13px; font-weight:600; padding:7px 15px; border-radius:20px; border:1.5px solid var(--bb-line); background:#fff; color:#4b5563; text-decoration:none; transition:all .15s; }
        .ma-filter:hover { border-color:var(--bb-primary); color:var(--bb-primary); }
        .ma-filter.active { background:var(--bb-primary); color:#fff; border-color:var(--bb-primary); }

        /* Application card */
        .ma-card { background:#fff; border-radius:16px; box-shadow:0 1px 3px rgba(16,24,40,.06); border:1px solid var(--bb-line); padding:18px; margin-bottom:14px; transition:box-shadow .2s; }
        .ma-card:hover { box-shadow:0 6px 20px rgba(79,70,229,.08); }
        .ma-card-top { display:flex; gap:13px; align-items:flex-start; }
        .ma-logo { width:50px; height:50px; border-radius:12px; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:22px; font-weight:800; background:var(--bb-primary-soft); color:var(--bb-primary); }
        .ma-info { flex-grow:1; min-width:0; }
        .ma-title { font-size:16px; font-weight:800; color:var(--bb-ink); text-decoration:none; letter-spacing:-.2px; }
        .ma-title:hover { color:var(--bb-primary); }
        .ma-company { font-size:13px; color:var(--bb-muted); margin:2px 0 0; }
        .ma-status { font-size:12px; font-weight:700; padding:5px 12px; border-radius:20px; display:inline-flex; align-items:center; gap:5px; white-space:nowrap; }
        .ma-meta { display:flex; flex-wrap:wrap; gap:14px; margin-top:12px; padding-top:12px; border-top:1px solid var(--bb-line); font-size:12.5px; color:var(--bb-muted); }
        .ma-meta span { display:inline-flex; align-items:center; gap:5px; }
        .ma-meta i { font-size:12px; }
        .ma-actions { margin-top:12px; display:flex; gap:8px; flex-wrap:wrap; }
        .ma-btn { font-size:12.5px; font-weight:600; padding:7px 14px; border-radius:9px; border:none; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:5px; transition:all .15s; }
        .ma-btn-view { background:var(--bb-primary-soft); color:var(--bb-primary); }
        .ma-btn-view:hover { background:var(--bb-primary); color:#fff; }
        .ma-btn-withdraw { background:#fef2f2; color:#dc2626; }
        .ma-btn-withdraw:hover { background:#dc2626; color:#fff; }

        .ma-method { font-size:10.5px; font-weight:700; padding:2px 8px; border-radius:5px; text-transform:uppercase; letter-spacing:.3px; }
        .ma-method-inapp { background:#eef2ff; color:#4f46e5; }
        .ma-method-external { background:#fff7ed; color:#ea580c; }

        /* External applied badge (status নয়, শুধু applied) */
        .ma-ext-applied { font-size:12px; font-weight:700; padding:5px 12px; border-radius:20px; display:inline-flex; align-items:center; gap:5px; white-space:nowrap; background:#eef2ff; color:#4f46e5; }

        .ma-empty { background:#fff; border-radius:16px; padding:50px 20px; text-align:center; color:var(--bb-muted); border:1px solid var(--bb-line); }
        .ma-empty i { font-size:42px; color:#d1d5db; }

        /* Search bar */
        .ma-search { display:flex; gap:8px; margin-bottom:14px; }
        .ma-search-box { flex-grow:1; position:relative; }
        .ma-search-box i { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--bb-muted); font-size:15px; }
        .ma-search-input { width:100%; border:1.5px solid var(--bb-line); border-radius:11px; padding:11px 14px 11px 40px; font-size:13.5px; outline:none; transition:border-color .15s, box-shadow .15s; background:#fff; }
        .ma-search-input:focus { border-color:var(--bb-primary); box-shadow:0 0 0 3px rgba(79,70,229,.1); }
        .ma-search-btn { border:none; background:var(--bb-primary); color:#fff; border-radius:11px; padding:11px 20px; font-size:13.5px; font-weight:600; cursor:pointer; transition:background .15s; }
        .ma-search-btn:hover { background:#4338ca; }
        .ma-search-clear { border:1.5px solid var(--bb-line); background:#fff; color:var(--bb-muted); border-radius:11px; padding:11px 16px; font-size:13.5px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; }
        .ma-search-clear:hover { color:var(--bb-primary); border-color:var(--bb-primary); }

        /* Pagination */
        .ma-pagination { display:flex; justify-content:center; margin-top:20px; }
        .ma-pagination nav > div:first-child { display:none; } /* mobile prev/next text hide */
        .ma-pagination .pagination { display:flex; gap:5px; list-style:none; padding:0; margin:0; flex-wrap:wrap; }
        .ma-pagination .page-item .page-link { border:1.5px solid var(--bb-line); border-radius:9px; padding:7px 13px; font-size:13px; font-weight:600; color:#4b5563; text-decoration:none; background:#fff; display:inline-block; transition:all .15s; }
        .ma-pagination .page-item .page-link:hover { border-color:var(--bb-primary); color:var(--bb-primary); }
        .ma-pagination .page-item.active .page-link { background:var(--bb-primary); color:#fff; border-color:var(--bb-primary); }
        .ma-pagination .page-item.disabled .page-link { opacity:.45; pointer-events:none; }
    </style>
</head>
<body>

@include('partials.inner-navbar')

<div class="ma-wrap">
    <div class="ma-head">
        <h1>My Job Applications</h1>
        <p>Track every job you've applied to and its current status</p>
    </div>

    {{-- STATS --}}
    <div class="ma-stats">
        <div class="ma-stat total">
            <div class="ma-stat-num">{{ $stats['total'] }}</div>
            <div class="ma-stat-label">Total Applied</div>
        </div>
        <div class="ma-stat pending">
            <div class="ma-stat-num">{{ $stats['pending'] }}</div>
            <div class="ma-stat-label">In Progress</div>
        </div>
        <div class="ma-stat short">
            <div class="ma-stat-num">{{ $stats['shortlisted'] }}</div>
            <div class="ma-stat-label">Shortlisted</div>
        </div>
        <div class="ma-stat reject">
            <div class="ma-stat-num">{{ $stats['rejected'] }}</div>
            <div class="ma-stat-label">Not Selected</div>
        </div>
    </div>

    {{-- SEARCH --}}
    <form method="GET" action="{{ route('jobs.myApplications') }}" class="ma-search">
        @if($filter)<input type="hidden" name="filter" value="{{ $filter }}">@endif
        <div class="ma-search-box">
            <i class="bi bi-search"></i>
            <input type="text" name="q" class="ma-search-input" value="{{ $search ?? '' }}" placeholder="Search by job title or company...">
        </div>
        <button type="submit" class="ma-search-btn">Search</button>
        @if(!empty($search))
            <a href="{{ route('jobs.myApplications', $filter ? ['filter' => $filter] : []) }}" class="ma-search-clear" title="Clear search"><i class="bi bi-x-lg"></i></a>
        @endif
    </form>

    {{-- FILTERS --}}
    <div class="ma-filters">
        <a href="{{ route('jobs.myApplications', !empty($search) ? ['q' => $search] : []) }}" class="ma-filter {{ !$filter ? 'active' : '' }}">All</a>
        <a href="{{ route('jobs.myApplications', array_filter(['filter' => 'pending', 'q' => $search ?? null])) }}" class="ma-filter {{ $filter === 'pending' ? 'active' : '' }}">Pending</a>
        <a href="{{ route('jobs.myApplications', array_filter(['filter' => 'reviewed', 'q' => $search ?? null])) }}" class="ma-filter {{ $filter === 'reviewed' ? 'active' : '' }}">Under Review</a>
        <a href="{{ route('jobs.myApplications', array_filter(['filter' => 'shortlisted', 'q' => $search ?? null])) }}" class="ma-filter {{ $filter === 'shortlisted' ? 'active' : '' }}">Shortlisted</a>
        <a href="{{ route('jobs.myApplications', array_filter(['filter' => 'rejected', 'q' => $search ?? null])) }}" class="ma-filter {{ $filter === 'rejected' ? 'active' : '' }}">Not Selected</a>
    </div>

    {{-- APPLICATIONS --}}
    @forelse($applications as $app)
        @php $meta = $app->status_meta; @endphp
        <div class="ma-card" id="appCard-{{ $app->id }}">
            <div class="ma-card-top">
                <div class="ma-logo">{{ strtoupper(substr(optional($app->jobPost)->company ?? '?', 0, 1)) }}</div>
                <div class="ma-info">
                    @if($app->jobPost)
                        @if($app->jobPost->trashed())
                            <span class="ma-title" style="color:var(--bb-ink);">{{ $app->jobPost->title }}</span>
                            <p class="ma-company">{{ $app->jobPost->company }}@if($app->jobPost->location) · {{ $app->jobPost->location }}@endif <span style="color:#9ca3af;font-size:11.5px;">· <i class="bi bi-archive"></i> Archived</span></p>
                        @else
                            <a href="{{ route('jobs.show', $app->jobPost->id) }}" class="ma-title">{{ $app->jobPost->title }}</a>
                            <p class="ma-company">{{ $app->jobPost->company }}@if($app->jobPost->location) · {{ $app->jobPost->location }}@endif</p>
                        @endif
                    @else
                        <span class="ma-title" style="color:var(--bb-muted);">Job no longer available</span>
                        <p class="ma-company">This job posting has been removed</p>
                    @endif
                </div>
                @if($app->apply_method === 'external')
                    {{-- External: প্রতিষ্ঠান নিজের সিস্টেমে handle করে, তাই Borobhai status নেই — শুধু Applied --}}
                    <span class="ma-ext-applied" title="Applied on the company's own site — managed by them">
                        <i class="bi bi-box-arrow-up-right"></i> Applied externally
                    </span>
                @else
                    {{-- In-app: alumni যে status দেয় সেটাই --}}
                    <span class="ma-status" style="background:{{ $meta['bg'] }};color:{{ $meta['color'] }};">
                        <i class="bi {{ $meta['icon'] }}"></i> {{ $meta['label'] }}
                    </span>
                @endif
            </div>

            <div class="ma-meta">
                <span><i class="bi bi-calendar-check"></i> Applied {{ $app->applied_at->format('d M Y') }}</span>
                <span><i class="bi bi-clock-history"></i> {{ $app->applied_at->diffForHumans() }}</span>
                <span class="ma-method ma-method-{{ $app->apply_method }}">{{ $app->apply_method === 'inapp' ? 'On Borobhai' : 'External' }}</span>
                @if($app->resume_url)
                    <span><i class="bi bi-paperclip"></i> <a href="{{ $app->resume_url }}" target="_blank" style="color:var(--bb-primary);text-decoration:none;">Resume attached</a></span>
                @endif
            </div>

            <div class="ma-actions">
                @if($app->jobPost && !$app->jobPost->trashed())
                    <a href="{{ route('jobs.show', $app->jobPost->id) }}" class="ma-btn ma-btn-view"><i class="bi bi-box-arrow-up-right"></i> View Job</a>
                @endif
                @if($app->apply_method === 'inapp' && in_array($app->status, ['pending', 'reviewed']) && (!$app->jobPost || !$app->jobPost->trashed()))
                    <button class="ma-btn ma-btn-withdraw" onclick="withdrawApp({{ $app->job_post_id }}, {{ $app->id }})"><i class="bi bi-x-circle"></i> Withdraw</button>
                @endif
            </div>
        </div>
    @empty
        <div class="ma-empty">
            <i class="bi bi-inbox d-block mb-2"></i>
            @if(!empty($search))
                <h5 class="fw-bold">No matches for "{{ $search }}"</h5>
                <p class="mb-0">Try a different job title or company name.</p>
            @else
                <h5 class="fw-bold">{{ $filter ? 'No applications in this category' : 'No applications yet' }}</h5>
                <p class="mb-0">{{ $filter ? 'Try a different filter.' : 'Start applying to jobs from your feed — they\'ll show up here.' }}</p>
            @endif
        </div>
    @endforelse

    {{-- PAGINATION --}}
    @if($applications->hasPages())
        <div class="ma-pagination">
            {{ $applications->links() }}
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const MA_CSRF = document.querySelector('meta[name="csrf-token"]').content;

// back button (bfcache) থেকে এলে stale state এড়াতে reload
window.addEventListener('pageshow', function (e) {
    if (e.persisted) location.reload();
});

function withdrawApp(jobId, appId){
    Swal.fire({
        title:'Withdraw application?',
        text:'This will remove your application from this job.',
        icon:'warning', showCancelButton:true,
        confirmButtonColor:'#ef4444', confirmButtonText:'Withdraw'
    }).then(r=>{
        if(!r.isConfirmed) return;
        fetch(`/jobs/${jobId}/withdraw`, {
            method:'POST', headers:{'X-CSRF-TOKEN':MA_CSRF,'Accept':'application/json'}
        })
        .then(r=>r.json())
        .then(d=>{
            if(!d.success){
                Swal.fire({ icon:'info', title:'Cannot withdraw', text: d.message || 'This application cannot be withdrawn.' });
                return;
            }
            const card = document.getElementById(`appCard-${appId}`);
            if(card){ card.style.transition='opacity .3s'; card.style.opacity='0'; setTimeout(()=>{ card.remove(); location.reload(); },300); }
            const Toast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:1800 });
            Toast.fire({ icon:'info', title:'Application withdrawn' });
        })
        .catch(()=>{ Swal.fire({icon:'error', title:'Network error'}); });
    });
}
</script>

</body>
</html>