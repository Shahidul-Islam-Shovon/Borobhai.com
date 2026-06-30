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
    <title>Applicants · {{ $job->title }} — Borobhai.online</title>
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

        .ap-wrap { max-width:880px; margin:26px auto; padding:0 16px; }

        /* Job header */
        .ap-jobhead { background:#fff; border-radius:16px; box-shadow:0 1px 3px rgba(16,24,40,.06); border:1px solid var(--bb-line); padding:20px 24px; margin-bottom:20px; display:flex; gap:15px; align-items:center; }
        .ap-joblogo { width:54px; height:54px; border-radius:13px; background:var(--bb-primary-soft); color:var(--bb-primary); display:flex; align-items:center; justify-content:center; font-size:24px; font-weight:800; flex-shrink:0; }
        .ap-jobtitle { font-size:19px; font-weight:800; letter-spacing:-.3px; margin:0; }
        .ap-jobcompany { font-size:13.5px; color:var(--bb-muted); margin:2px 0 0; }
        .ap-count { margin-left:auto; text-align:center; flex-shrink:0; }
        .ap-count-num { font-size:28px; font-weight:800; color:var(--bb-primary); line-height:1; }
        .ap-count-label { font-size:11.5px; color:var(--bb-muted); }

        /* Search + filter */
        .ap-search { display:flex; gap:8px; margin-bottom:14px; }
        .ap-search-box { flex-grow:1; position:relative; }
        .ap-search-box i { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--bb-muted); font-size:15px; }
        .ap-search-input { width:100%; border:1.5px solid var(--bb-line); border-radius:11px; padding:11px 14px 11px 40px; font-size:13.5px; outline:none; transition:border-color .15s, box-shadow .15s; background:#fff; }
        .ap-search-input:focus { border-color:var(--bb-primary); box-shadow:0 0 0 3px rgba(79,70,229,.1); }
        .ap-search-btn { border:none; background:var(--bb-primary); color:#fff; border-radius:11px; padding:11px 20px; font-size:13.5px; font-weight:600; cursor:pointer; transition:background .15s; }
        .ap-search-btn:hover { background:#4338ca; }
        .ap-search-clear { border:1.5px solid var(--bb-line); background:#fff; color:var(--bb-muted); border-radius:11px; padding:11px 16px; font-size:13.5px; font-weight:600; text-decoration:none; display:inline-flex; align-items:center; }
        .ap-search-clear:hover { color:var(--bb-primary); border-color:var(--bb-primary); }
        .ap-filters { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:18px; }
        .ap-filter { font-size:12.5px; font-weight:600; padding:7px 14px; border-radius:20px; border:1.5px solid var(--bb-line); background:#fff; color:#4b5563; text-decoration:none; transition:all .15s; }
        .ap-filter:hover { border-color:var(--bb-primary); color:var(--bb-primary); }
        .ap-filter.active { background:var(--bb-primary); color:#fff; border-color:var(--bb-primary); }

        /* Applicant card */
        .ap-card { background:#fff; border-radius:14px; box-shadow:0 1px 3px rgba(16,24,40,.06); border:1px solid var(--bb-line); padding:18px; margin-bottom:13px; }
        .ap-card-top { display:flex; gap:13px; align-items:flex-start; }
        .ap-avatar { width:48px; height:48px; border-radius:50%; object-fit:cover; flex-shrink:0; background:linear-gradient(135deg,#4f46e5,#7c73f0); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:18px; }
        .ap-name { font-size:15.5px; font-weight:700; margin:0; }
        .ap-name a { color:var(--bb-ink); text-decoration:none; }
        .ap-name a:hover { color:var(--bb-primary); }
        .ap-contact { font-size:12.5px; color:var(--bb-muted); margin:3px 0 0; display:flex; flex-wrap:wrap; gap:12px; }
        .ap-contact span { display:inline-flex; align-items:center; gap:4px; }
        .ap-contact a { color:var(--bb-primary); text-decoration:none; }
        .ap-status-badge { font-size:11.5px; font-weight:700; padding:5px 11px; border-radius:20px; display:inline-flex; align-items:center; gap:5px; white-space:nowrap; }

        .ap-cover { font-size:13px; color:#4b5563; line-height:1.6; margin:13px 0 0; padding:12px 14px; background:var(--bb-bg); border-radius:10px; border-left:3px solid var(--bb-primary); }

        .ap-foot { display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin-top:14px; padding-top:13px; border-top:1px solid var(--bb-line); }
        .ap-resume { font-size:12.5px; font-weight:600; color:var(--bb-primary); text-decoration:none; display:inline-flex; align-items:center; gap:5px; padding:7px 13px; background:var(--bb-primary-soft); border-radius:9px; transition:all .15s; }
        .ap-resume:hover { background:var(--bb-primary); color:#fff; }
        .ap-applied { font-size:12px; color:var(--bb-muted); }
        .ap-method { font-size:10.5px; font-weight:700; padding:2px 8px; border-radius:5px; text-transform:uppercase; }
        .ap-method-inapp { background:#eef2ff; color:#4f46e5; }
        .ap-method-external { background:#fff7ed; color:#ea580c; }

        .ap-status-select { margin-left:auto; }
        .ap-status-select select { font-size:12.5px; font-weight:600; padding:7px 11px; border-radius:9px; border:1.5px solid var(--bb-line); background:#fff; color:var(--bb-ink); cursor:pointer; outline:none; }
        .ap-status-select select:focus { border-color:var(--bb-primary); }

        .ap-empty { background:#fff; border-radius:16px; padding:50px 20px; text-align:center; color:var(--bb-muted); border:1px solid var(--bb-line); }
        .ap-empty i { font-size:42px; color:#d1d5db; }

        /* Pagination */
        .ap-pagination .pagination { display:flex; gap:5px; list-style:none; padding:0; margin:0; flex-wrap:wrap; justify-content:center; }
        .ap-pagination .page-item .page-link { border:1.5px solid var(--bb-line); border-radius:9px; padding:7px 13px; font-size:13px; font-weight:600; color:#4b5563; text-decoration:none; background:#fff; display:inline-block; transition:all .15s; }
        .ap-pagination .page-item .page-link:hover { border-color:var(--bb-primary); color:var(--bb-primary); }
        .ap-pagination .page-item.active .page-link { background:var(--bb-primary); color:#fff; border-color:var(--bb-primary); }
        .ap-pagination .page-item.disabled .page-link { opacity:.45; pointer-events:none; }
    </style>
</head>
<body>

@include('partials.inner-navbar')

<div class="ap-wrap">

    {{-- JOB HEADER --}}
    <div class="ap-jobhead">
        <div class="ap-joblogo">{{ strtoupper(substr($job->company, 0, 1)) }}</div>
        <div>
            <h1 class="ap-jobtitle">{{ $job->title }}</h1>
            <p class="ap-jobcompany">{{ $job->company }}@if($job->location) · {{ $job->location }}@endif</p>
        </div>
        <div class="ap-count">
            <div class="ap-count-num">{{ $totalCount }}</div>
            <div class="ap-count-label">{{ Str::plural('Applicant', $totalCount) }}</div>
        </div>
    </div>

    {{-- SEARCH --}}
    <form method="GET" action="{{ route('jobs.applicants', $job) }}" class="ap-search">
        @if(!empty($filter))<input type="hidden" name="status" value="{{ $filter }}">@endif
        <div class="ap-search-box">
            <i class="bi bi-search"></i>
            <input type="text" name="q" class="ap-search-input" value="{{ $search ?? '' }}" placeholder="Search applicant by name or email...">
        </div>
        <button type="submit" class="ap-search-btn">Search</button>
        @if(!empty($search))
            <a href="{{ route('jobs.applicants', $job).($filter ? '?status='.$filter : '') }}" class="ap-search-clear" title="Clear"><i class="bi bi-x-lg"></i></a>
        @endif
    </form>

    {{-- STATUS FILTERS --}}
    <div class="ap-filters">
        @php
            $statuses = ['' => 'All', 'pending' => 'Pending', 'reviewed' => 'Under Review', 'shortlisted' => 'Shortlisted', 'accepted' => 'Accepted', 'rejected' => 'Not Selected'];
        @endphp
        @foreach($statuses as $val => $label)
            <a href="{{ route('jobs.applicants', $job) }}{{ http_build_query(array_filter(['status' => $val ?: null, 'q' => $search ?? null])) ? '?'.http_build_query(array_filter(['status' => $val ?: null, 'q' => $search ?? null])) : '' }}"
               class="ap-filter {{ ($filter ?? '') === $val ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    {{-- APPLICANTS --}}
    @forelse($applicants as $app)
        @php $meta = $app->status_meta; @endphp
        <div class="ap-card" id="apCard-{{ $app->id }}">
            <div class="ap-card-top">
                <div class="ap-avatar">
                    @if($app->user && $app->user->profile_picture)
                        <img src="{{ asset('storage/'.$app->user->profile_picture) }}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
                    @else
                        {{ strtoupper(substr($app->applicant_name, 0, 1)) }}
                    @endif
                </div>
                <div class="flex-grow-1" style="min-width:0;">
                    <h2 class="ap-name">
                        @if($app->user)
                            <a href="{{ route('profile.view', $app->user) }}" target="_blank">{{ $app->applicant_name }}</a>
                        @else
                            {{ $app->applicant_name }}
                        @endif
                    </h2>
                    <div class="ap-contact">
                        <span><i class="bi bi-envelope"></i> <a href="mailto:{{ $app->applicant_email }}">{{ $app->applicant_email }}</a></span>
                        @if($app->phone)<span><i class="bi bi-telephone"></i> {{ $app->phone }}</span>@endif
                        <span class="ap-method ap-method-{{ $app->apply_method }}">{{ $app->apply_method === 'inapp' ? 'On Borobhai' : 'External' }}</span>
                    </div>
                </div>
                @if($app->apply_method === 'external')
                    <span class="ap-status-badge" style="background:#eef2ff;color:#4f46e5;" title="Applied on the company site — managed by the company">
                        <i class="bi bi-box-arrow-up-right"></i> <span>Applied externally</span>
                    </span>
                @else
                    <span class="ap-status-badge" id="apStatusBadge-{{ $app->id }}" style="background:{{ $meta['bg'] }};color:{{ $meta['color'] }};">
                        <i class="bi {{ $meta['icon'] }}"></i> <span>{{ $meta['label'] }}</span>
                    </span>
                @endif
            </div>

            @if($app->cover_note)
                <p class="ap-cover">{{ $app->cover_note }}</p>
            @endif

            <div class="ap-foot">
                @if($app->resume_url)
                    <a href="{{ $app->resume_url }}" target="_blank" class="ap-resume"><i class="bi bi-file-earmark-pdf"></i> View Resume</a>
                @endif
                <span class="ap-applied"><i class="bi bi-calendar-check"></i> Applied {{ $app->applied_at->format('d M Y') }} · {{ $app->applied_at->diffForHumans() }}</span>

                @if($app->apply_method === 'external')
                    <span class="ap-ext-note" style="margin-left:auto;font-size:11.5px;color:#9ca3af;font-style:italic;display:inline-flex;align-items:center;gap:5px;">
                        <i class="bi bi-info-circle"></i> Managed on company site
                    </span>
                @else
                    <div class="ap-status-select">
                        <select onchange="updateStatus('{{ $app->getRouteKey() }}', this.value)">
                            <option value="pending"     {{ $app->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="reviewed"    {{ $app->status === 'reviewed' ? 'selected' : '' }}>Under Review</option>
                            <option value="shortlisted" {{ $app->status === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                            <option value="accepted"    {{ $app->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected"    {{ $app->status === 'rejected' ? 'selected' : '' }}>Not Selected</option>
                        </select>
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="ap-empty">
            <i class="bi bi-people d-block mb-2"></i>
            @if(!empty($search) || !empty($filter))
                <h5 class="fw-bold">No matching applicants</h5>
                <p class="mb-0">Try a different search or filter.</p>
            @else
                <h5 class="fw-bold">No applicants yet</h5>
                <p class="mb-0">When students apply to this job, they'll appear here.</p>
            @endif
        </div>
    @endforelse

    {{-- PAGINATION --}}
    @if($applicants->hasPages())
        <div class="mt-3 ap-pagination">
            {{ $applicants->links() }}
        </div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const AP_CSRF = document.querySelector('meta[name="csrf-token"]').content;
const apToast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:2000, timerProgressBar:true });

function updateStatus(appId, status){
    fetch(`/applications/${appId}/status`, {
        method:'POST',
        headers:{'X-CSRF-TOKEN':AP_CSRF,'Accept':'application/json','Content-Type':'application/json'},
        body: JSON.stringify({ status })
    })
    .then(r=>r.json())
    .then(d=>{
        if(!d.success){ Swal.fire({icon:'error',title:'Could not update'}); return; }
        const m = d.status_meta;
        const badge = document.getElementById(`apStatusBadge-${appId}`);
        if (badge) {
            badge.style.background = m.bg;
            badge.style.color = m.color;
            badge.innerHTML = `<i class="bi ${m.icon}"></i> <span>${m.label}</span>`;
        }
        apToast.fire({ icon:'success', title:'Status updated to '+m.label });
    })
    .catch(()=>Swal.fire({icon:'error',title:'Network error'}));
}
</script>

</body>
</html>