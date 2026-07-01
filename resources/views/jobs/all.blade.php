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
    <title>All Jobs — Borobhai.online</title>
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
        .ja-head { margin-bottom:18px; }
        .ja-head h1 { font-size:26px; font-weight:800; letter-spacing:-.5px; margin:0 0 3px; }
        .ja-head p { color:var(--bb-muted); font-size:14px; margin:0; }

        /* Search + Sort bar */
        .ja-controls { display:flex; gap:10px; margin-bottom:14px; flex-wrap:wrap; }
        .ja-search-box { flex-grow:1; position:relative; min-width:200px; }
        .ja-search-box i { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--bb-muted); font-size:15px; }
        .ja-search-input { width:100%; border:1.5px solid var(--bb-line); border-radius:11px; padding:11px 38px 11px 40px; font-size:13.5px; outline:none; transition:border-color .15s, box-shadow .15s; background:#fff; }
        .ja-search-input:focus { border-color:var(--bb-primary); box-shadow:0 0 0 3px rgba(79,70,229,.1); }
        .ja-search-x { position:absolute; right:12px; top:50%; transform:translateY(-50%); color:var(--bb-muted); font-size:16px; text-decoration:none; transition:color .15s; }
        .ja-search-x:hover { color:#dc2626; }
        .ja-search-btn { border:none; background:var(--bb-primary); color:#fff; border-radius:11px; padding:11px 22px; font-size:13.5px; font-weight:600; cursor:pointer; transition:background .15s; }
        .ja-search-btn:hover { background:#4338ca; }
        .ja-sort { border:1.5px solid var(--bb-line); border-radius:11px; padding:11px 14px; font-size:13.5px; font-weight:600; color:#4b5563; background:#fff; cursor:pointer; outline:none; }
        .ja-sort:focus { border-color:var(--bb-primary); }

        /* Filter pills */
        .ja-filters { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:20px; }
        .ja-filter { font-size:12.5px; font-weight:600; padding:7px 14px; border-radius:20px; border:1.5px solid var(--bb-line); background:#fff; color:#4b5563; text-decoration:none; transition:all .15s; }
        .ja-filter:hover { border-color:var(--bb-primary); color:var(--bb-primary); }
        .ja-filter.active { background:var(--bb-primary); color:#fff; border-color:var(--bb-primary); }

        .ja-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:16px; }
        @media (max-width:680px){ .ja-grid { grid-template-columns:1fr; } }
        .ja-card { background:#fff; border-radius:16px; box-shadow:0 1px 3px rgba(16,24,40,.06); padding:18px; border:1px solid var(--bb-line); transition:box-shadow .2s, transform .2s; display:flex; flex-direction:column; }
        .ja-card:hover { box-shadow:0 8px 24px rgba(79,70,229,.10); transform:translateY(-2px); }
        .ja-top { display:flex; gap:12px; align-items:flex-start; }
        .ja-logo { width:48px; height:48px; border-radius:12px; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:21px; font-weight:800; background:var(--bb-primary-soft); color:var(--bb-primary); }
        .ja-title { font-size:16px; font-weight:800; color:var(--bb-ink); text-decoration:none; line-height:1.25; letter-spacing:-.2px; }
        .ja-title:hover { color:var(--bb-primary); }
        .ja-company { font-size:12.5px; color:var(--bb-muted); margin:2px 0 0; }

        .ja-applicants { display:inline-flex; align-items:center; gap:5px; font-size:12px; font-weight:700; margin:3px 0 0; }
        .ja-applicants i { font-size:12px; }
        .ja-applicants-link { color:var(--bb-primary); text-decoration:none; background:var(--bb-primary-soft); padding:3px 10px; border-radius:20px; transition:all .15s; }
        .ja-applicants-link:hover { background:var(--bb-primary); color:#fff; }
        .ja-applicants-muted { color:var(--bb-muted); }

        .ja-meta { display:flex; flex-wrap:wrap; gap:6px; margin:12px 0; }
        .ja-pill { font-size:11.5px; font-weight:600; padding:4px 10px; border-radius:7px; background:var(--bb-bg); color:#4b5563; display:inline-flex; align-items:center; gap:4px; }
        .ja-pill i { font-size:10px; }
        .ja-expiring { color:#ea580c !important; }
        .ja-expired { color:#dc2626 !important; }
        .ja-foot { margin-top:auto; padding-top:12px; }
        .ja-view { display:block; text-align:center; background:var(--bb-primary-soft); color:var(--bb-primary); border-radius:9px; padding:9px; font-size:13px; font-weight:700; text-decoration:none; transition:all .15s; }
        .ja-view:hover { background:var(--bb-primary); color:#fff; }
        .ja-empty { background:#fff; border-radius:16px; padding:50px 20px; text-align:center; color:var(--bb-muted); }

        /* Pagination */
        .ja-pagination .pagination { display:flex; gap:5px; list-style:none; padding:0; margin:0; flex-wrap:wrap; }
        .ja-pagination .page-item .page-link { border:1.5px solid var(--bb-line); border-radius:9px; padding:7px 13px; font-size:13px; font-weight:600; color:#4b5563; text-decoration:none; background:#fff; display:inline-block; transition:all .15s; }
        .ja-pagination .page-item .page-link:hover { border-color:var(--bb-primary); color:var(--bb-primary); }
        .ja-pagination .page-item.active .page-link { background:var(--bb-primary); color:#fff; border-color:var(--bb-primary); }
        .ja-pagination .page-item.disabled .page-link { opacity:.45; pointer-events:none; }
    </style>
</head>
<body>

@include('partials.inner-navbar')

<div class="ja-wrap">
    <div class="ja-head">
        <h1>Job Opportunities</h1>
        <p id="jaHeadCount">{{ $jobs->total() }} {{ Str::plural('opening', $jobs->total()) }} shared by alumni · internships and part-time roles first</p>
    </div>

    {{-- SEARCH + SORT --}}
    <form method="GET" action="{{ route('jobs.all') }}" class="ja-controls" id="jaSearchForm">
        @if(!empty($type))<input type="hidden" name="type" value="{{ $type }}">@endif
        <div class="ja-search-box">
            <i class="bi bi-search"></i>
            <input type="text" name="q" class="ja-search-input" value="{{ $search ?? '' }}" placeholder="Search title, company, location, or skill...">
            <a href="javascript:void(0)" class="ja-search-x" title="Clear search" style="display:{{ !empty($search) ? 'inline' : 'none' }};"><i class="bi bi-x-circle-fill"></i></a>
        </div>
        <select name="sort" class="ja-sort">
            <option value="default"  {{ ($sort ?? 'default') === 'default' ? 'selected' : '' }}>Recommended</option>
            <option value="newest"   {{ ($sort ?? '') === 'newest' ? 'selected' : '' }}>Newest first</option>
            <option value="deadline" {{ ($sort ?? '') === 'deadline' ? 'selected' : '' }}>Deadline soon</option>
        </select>
        <button type="submit" class="ja-search-btn">Search</button>
    </form>

    {{-- TYPE FILTERS --}}
    <div class="ja-filters">
        @php
            $types = ['' => 'All', 'Internship' => 'Internship', 'Part-time' => 'Part-time', 'Full-time' => 'Full-time', 'Remote' => 'Remote', 'Contract' => 'Contract', 'Freelance' => 'Freelance'];
        @endphp
        @foreach($types as $val => $label)
            <a href="{{ route('jobs.all', array_filter(['type' => $val ?: null, 'q' => $search ?? null, 'sort' => ($sort ?? 'default') !== 'default' ? $sort : null])) }}"
               data-type="{{ $val }}"
               class="ja-filter {{ ($type ?? '') === $val ? 'active' : '' }}">{{ $label }}</a>
        @endforeach
    </div>

    {{-- JOB CARDS (AJAX দিয়ে আপডেট হয়) --}}
    <div id="jaResults">
        @include('jobs.partials.all-cards', ['jobs' => $jobs, 'search' => $search, 'type' => $type])
    </div>

    {{-- PAGINATION (AJAX দিয়ে আপডেট হয়) --}}
    <div class="mt-4 d-flex justify-content-center ja-pagination" id="jaPagination">
        @if($jobs->hasPages()){!! $jobs->links() !!}@endif
    </div>

    {{-- loading overlay --}}
    <div id="jaLoading" style="display:none;text-align:center;padding:30px;color:var(--bb-muted);">
        <div class="spinner-border text-primary" style="width:1.8rem;height:1.8rem;"></div>
    </div>
</div>

<script>
const JA_BASE = "{{ route('jobs.all') }}";

// বর্তমান filter state পড়ি (memory থেকে — URL clean থাকে)
function jaGetState(){
    if (window.jaCurrentState) return Object.assign({}, window.jaCurrentState);
    // প্রথমবার — সব default (clean)
    return { q:'', type:'', sort:'default', page:'1' };
}

// state থেকে query string বানাই (খালি মান বাদ)
function jaBuildQuery(s){
    const params = new URLSearchParams();
    if (s.q)    params.set('q', s.q);
    if (s.type) params.set('type', s.type);
    if (s.sort && s.sort !== 'default') params.set('sort', s.sort);
    if (s.page && s.page !== '1') params.set('page', s.page);
    return params.toString();
}

// AJAX দিয়ে result লোড — URL বদলাই না (তাই reload দিলে সব clean হয়ে যায়)
function jaLoad(state, push = true){
    const qs = jaBuildQuery(state);
    // fetch করার URL এ query থাকবে, কিন্তু browser address bar বদলাব না
    const fetchUrl = JA_BASE + (qs ? '?' + qs : '');

    const results = document.getElementById('jaResults');
    const pager   = document.getElementById('jaPagination');
    const loading = document.getElementById('jaLoading');

    results.style.opacity = '.4';
    if (loading) loading.style.display = 'block';

    fetch(fetchUrl, { headers:{ 'X-Requested-With':'XMLHttpRequest', 'Accept':'application/json' } })
    .then(r => r.json())
    .then(d => {
        if (loading) loading.style.display = 'none';
        results.style.opacity = '1';
        if (!d.success) return;

        results.innerHTML = d.html;
        pager.innerHTML = d.pagination || '';

        // header count আপডেট
        const headP = document.getElementById('jaHeadCount');
        if (headP && d.total_text) headP.textContent = d.total_text + ' shared by alumni · internships and part-time roles first';

        // current filter state মনে রাখি (pagination এর জন্য) — কিন্তু URL বদলাই না
        window.jaCurrentState = state;

        // উপরে স্ক্রল (smooth)
        document.querySelector('.ja-wrap').scrollIntoView({ behavior:'smooth', block:'start' });
    })
    .catch(() => {
        if (loading) loading.style.display = 'none';
        results.style.opacity = '1';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    // search input এ টাইপ করলে ✕ দেখাও/লুকাও
    const searchInput = document.querySelector('.ja-search-input');
    const clearBtn = document.querySelector('.ja-search-x');
    if (searchInput && clearBtn) {
        searchInput.addEventListener('input', () => {
            clearBtn.style.display = searchInput.value.trim() ? 'inline' : 'none';
        });
    }

    // ---- SEARCH form ----
    const form = document.getElementById('jaSearchForm');
    if (form) {
        form.addEventListener('submit', e => {
            e.preventDefault();
            const s = jaGetState();
            s.q = form.querySelector('input[name="q"]').value.trim();
            s.page = '1';
            jaLoad(s);
        });
    }

    // ---- SORT dropdown ----
    const sortSel = document.querySelector('.ja-sort');
    if (sortSel) {
        sortSel.addEventListener('change', () => {
            const s = jaGetState();
            s.sort = sortSel.value;
            s.page = '1';
            jaLoad(s);
        });
    }

    // ---- TYPE filter pills + pagination links + clear (event delegation) ----
    document.addEventListener('click', e => {
        // type pill
        const pill = e.target.closest('.ja-filter');
        if (pill) {
            e.preventDefault();
            const s = jaGetState();
            s.type = pill.dataset.type || '';
            s.page = '1';
            // active ক্লাস সাথে সাথে আপডেট
            document.querySelectorAll('.ja-filter').forEach(f => f.classList.remove('active'));
            pill.classList.add('active');
            jaLoad(s);
            return;
        }

        // pagination link
        const pageLink = e.target.closest('#jaPagination a');
        if (pageLink) {
            e.preventDefault();
            const href = pageLink.getAttribute('href');
            const pageParam = new URL(href, window.location.origin).searchParams.get('page') || '1';
            const s = jaGetState();
            s.page = pageParam;
            jaLoad(s);
            return;
        }

        // search clear X
        const clearX = e.target.closest('.ja-search-x');
        if (clearX) {
            e.preventDefault();
            const s = jaGetState();
            s.q = '';
            s.page = '1';
            const inp = document.querySelector('.ja-search-input');
            if (inp) inp.value = '';
            clearX.style.display = 'none';
            jaLoad(s);
            return;
        }
    });
});

// empty state এর "Clear filters" বাটন
function jaClearAll(){
    const inp = document.querySelector('.ja-search-input');
    if (inp) inp.value = '';
    document.querySelectorAll('.ja-filter').forEach(f => f.classList.remove('active'));
    const allPill = document.querySelector('.ja-filter[data-type=""]');
    if (allPill) allPill.classList.add('active');
    const sortSel = document.querySelector('.ja-sort');
    if (sortSel) sortSel.value = 'default';
    jaLoad({ q:'', type:'', sort:'default', page:'1' });
}
</script>
</body>
</html>