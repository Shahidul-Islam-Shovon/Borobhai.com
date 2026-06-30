<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Suggested Contacts · Borobhai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --bb-primary:#4f46e5; --bb-ink:#1e1f24; --bb-muted:#6b7280; --bb-line:#eceef1; --bb-bg:#f3f4f8; --bb-card:#fff; }
        body { background:var(--bb-bg); font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif; }
        .navbar { background:#fff; box-shadow:0 2px 4px rgba(0,0,0,.08); padding:.5rem 1rem; }
        .page-title { font-size:1.5rem; font-weight:800; color:var(--bb-ink); letter-spacing:-.4px; }
        .filter-pill { display:inline-flex; align-items:center; gap:6px; padding:7px 16px; border-radius:20px; border:1.5px solid var(--bb-line); background:#fff; color:var(--bb-muted); font-size:.85rem; font-weight:600; text-decoration:none; transition:all .15s; cursor:pointer; }
        .filter-pill:hover, .filter-pill.active { background:var(--bb-primary); color:#fff; border-color:var(--bb-primary); }

        .people-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(260px,1fr)); gap:16px; }
        .person-card { background:var(--bb-card); border-radius:16px; box-shadow:0 1px 3px rgba(16,24,40,.06); overflow:hidden; transition:box-shadow .2s; }
        .person-card:hover { box-shadow:0 8px 24px rgba(79,70,229,.10); }
        .person-card-top { padding:20px 16px 12px; text-align:center; }
        .person-avatar { width:72px; height:72px; border-radius:50%; margin:0 auto 10px; background:linear-gradient(135deg,#4f46e5,#7c73f0); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:26px; overflow:hidden; }
        .person-avatar img { width:100%; height:100%; object-fit:cover; }
        .person-name { font-size:15px; font-weight:700; color:var(--bb-ink); margin:0 0 3px; }
        .person-meta { font-size:12px; color:var(--bb-muted); margin:0 0 4px; }
        .person-company { font-size:11.5px; color:#16a34a; font-weight:600; margin:0 0 2px; }
        .bb-role-chip { display:inline-flex; align-items:center; gap:4px; font-size:10.5px; font-weight:700; padding:2px 9px; border-radius:12px; }
        .chip-alumni  { background:#fef3c7; color:#d97706; }
        .chip-student { background:#eef2ff; color:#4f46e5; }
        .chip-teacher { background:#f3e8ff; color:#7c3aed; }
        .person-mutual { font-size:11.5px; color:var(--bb-muted); margin-top:6px; display:flex; align-items:center; justify-content:center; gap:4px; cursor:pointer; }
        .person-mutual:hover { color:var(--bb-primary); text-decoration:underline; }

        .person-card-foot { padding:10px 14px 14px; display:flex; gap:7px; }
        .btn-add { flex:1; background:var(--bb-primary); color:#fff; border:none; border-radius:10px; padding:9px; font-size:13.5px; font-weight:700; cursor:pointer; transition:all .15s; }
        .btn-add:hover { background:#4338ca; }
        .btn-add.pending { background:#eef2ff; color:var(--bb-primary); border:1.5px solid #c7d2fe; }
        .btn-add.pending:hover { background:#fee2e2; color:#dc2626; border-color:#fca5a5; }
        .btn-not-interested { width:38px; height:38px; border-radius:10px; border:1.5px solid var(--bb-line); background:#fff; color:var(--bb-muted); cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .15s; flex-shrink:0; }
        .btn-not-interested:hover { background:#fee2e2; color:#dc2626; border-color:#fca5a5; }
        .btn-report { width:38px; height:38px; border-radius:10px; border:1.5px solid var(--bb-line); background:#fff; color:var(--bb-muted); cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .15s; flex-shrink:0; }
        .btn-report:hover { background:#fff7ed; color:#ea580c; border-color:#fed7aa; }

        /* MUTUAL MODAL */
        .mutual-list-item { display:flex; align-items:center; gap:10px; padding:8px 0; border-bottom:1px solid var(--bb-line); }
        .mutual-list-item:last-child { border:none; }
        .mutual-avatar-sm { width:36px; height:36px; border-radius:50%; background:linear-gradient(135deg,#4f46e5,#7c73f0); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; overflow:hidden; flex-shrink:0; }
        .mutual-avatar-sm img { width:100%; height:100%; object-fit:cover; }

        /* REPORT MODAL */
        .report-reason-btn { display:flex; align-items:center; gap:10px; width:100%; padding:11px 14px; border-radius:10px; border:1.5px solid var(--bb-line); background:#fff; color:var(--bb-ink); font-size:13.5px; font-weight:600; cursor:pointer; transition:all .15s; margin-bottom:7px; }
        .report-reason-btn:hover, .report-reason-btn.selected { border-color:var(--bb-primary); background:#eef2ff; color:var(--bb-primary); }
        .report-reason-btn i { font-size:16px; width:20px; }
    </style>
</head>
<body>

@include('partials.inner-navbar')

<div class="container py-4" style="max-width:1100px;">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h1 class="page-title mb-1">People You May Know</h1>
            <p class="text-muted small mb-0">Connect with alumni, students and teachers from your institution</p>
        </div>
        <a href="{{ route('friends.index') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-people-fill me-1"></i> My Friends
        </a>
    </div>

    {{-- Filters --}}
    <div class="d-flex gap-2 flex-wrap mb-4">
        <a href="{{ route('friends.suggested') }}" class="filter-pill {{ !request('filter') || request('filter')=='all' ? 'active' : '' }}">
            <i class="bi bi-grid-3x3-gap-fill"></i> All
        </a>
        <a href="{{ route('friends.suggested', ['filter'=>'alumni']) }}" class="filter-pill {{ request('filter')=='alumni' ? 'active' : '' }}">
            <i class="bi bi-mortarboard-fill"></i> Alumni
        </a>
        <a href="{{ route('friends.suggested', ['filter'=>'student']) }}" class="filter-pill {{ request('filter')=='student' ? 'active' : '' }}">
            <i class="bi bi-backpack-fill"></i> Students
        </a>
        <a href="{{ route('friends.suggested', ['filter'=>'teacher']) }}" class="filter-pill {{ request('filter')=='teacher' ? 'active' : '' }}">
            <i class="bi bi-easel2-fill"></i> Teachers
        </a>
    </div>

    {{-- Grid --}}
    <div class="people-grid" id="peopleGrid">
        @forelse($users as $u)
        <div class="person-card" id="pcard-{{ $u->id }}">
            <div class="person-card-top">
                <a href="{{ route('profile.view', $u) }}">
                    <div class="person-avatar">
                        @if($u->profile_picture)
                            <img src="{{ asset('storage/'.$u->profile_picture) }}">
                        @else
                            {{ strtoupper(substr($u->name,0,1)) }}
                        @endif
                    </div>
                </a>
                <a href="{{ route('profile.view', $u) }}" style="text-decoration:none;">
                    <p class="person-name">{{ $u->name }}</p>
                </a>

                @if($u->department || $u->section)
                <p class="person-meta">
                    {{ $u->department }}@if($u->department && $u->section) · @endif{{ $u->section }}
                </p>
                @endif

                @if($u->role === 'alumni' && $u->current_company)
                <p class="person-company"><i class="bi bi-briefcase-fill me-1"></i>{{ $u->current_company }}</p>
                @elseif($u->role === 'student')
                <p class="person-meta"><i class="bi bi-backpack-fill me-1"></i>Student</p>
                @elseif($u->role === 'teacher')
                <p class="person-meta"><i class="bi bi-easel2-fill me-1"></i>Teacher</p>
                @endif

                <span class="bb-role-chip chip-{{ $u->role }}">{{ ucfirst($u->role) }}</span>

                @if($u->mutual > 0)
                <div class="person-mutual" onclick="loadMutuals({{ $u->id }}, '{{ e($u->name) }}')">
                    <i class="bi bi-people-fill text-primary" style="font-size:12px;"></i>
                    {{ $u->mutual }} mutual friend{{ $u->mutual>1?'s':'' }}
                </div>
                @endif
            </div>

            <div class="person-card-foot">
                @if($u->is_pending)
                <button class="btn-add pending" id="addbtn-{{ $u->id }}"
                    onclick="suggestAction('cancel', {{ $u->id }}, this)">
                    <i class="bi bi-person-check-fill me-1"></i> Requested · Cancel
                </button>
                @else
                <button class="btn-add" id="addbtn-{{ $u->id }}"
                    onclick="suggestAction('send', {{ $u->id }}, this)">
                    <i class="bi bi-person-plus-fill me-1"></i> Add Friend
                </button>
                @endif

                <button class="btn-not-interested" title="Not interested"
                    onclick="notInterested({{ $u->id }}, this)">
                    <i class="bi bi-x-lg"></i>
                </button>

                <button class="btn-report" title="Report"
                    onclick="openReport('user', {{ $u->id }}, '{{ e($u->name) }}')">
                    <i class="bi bi-flag-fill"></i>
                </button>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5 text-muted">
            <i class="bi bi-people fs-1 d-block mb-2"></i>
            <p>No suggestions right now. Check back later!</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-4">{{ $users->links() }}</div>
</div>

{{-- MUTUAL MODAL --}}
<div class="modal fade" id="mutualModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold" id="mutualTitle">Mutual Friends</h6>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2" id="mutualBody">
                <div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>
            </div>
        </div>
    </div>
</div>

{{-- REPORT MODAL --}}
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Report <span id="reportTargetName"></span></h6>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Why are you reporting this?</p>
                <input type="hidden" id="reportType">
                <input type="hidden" id="reportId">
                <input type="hidden" id="reportReason">
                <div id="reportReasons">
                    <button class="report-reason-btn" onclick="selectReason('spam')"><i class="bi bi-envelope-exclamation-fill text-warning"></i> Spam</button>
                    <button class="report-reason-btn" onclick="selectReason('harassment')"><i class="bi bi-exclamation-triangle-fill text-danger"></i> Harassment or bullying</button>
                    <button class="report-reason-btn" onclick="selectReason('fake')"><i class="bi bi-person-fill-slash text-secondary"></i> Fake profile or impersonation</button>
                    <button class="report-reason-btn" onclick="selectReason('inappropriate')"><i class="bi bi-shield-fill-exclamation text-danger"></i> Inappropriate content</button>
                    <button class="report-reason-btn" onclick="selectReason('other')"><i class="bi bi-three-dots text-muted"></i> Something else</button>
                </div>
                <div id="reportDetailsSection" class="d-none mt-3">
                    <textarea id="reportDetails" class="form-control border rounded-3" rows="3" placeholder="Add more details (optional)..." style="font-size:13px;"></textarea>
                    <div class="d-flex gap-2 mt-3">
                        <button class="btn btn-light btn-sm" onclick="document.getElementById('reportDetailsSection').classList.add('d-none'); document.getElementById('reportReasons').classList.remove('d-none');">Back</button>
                        <button class="btn btn-danger btn-sm px-4 fw-bold" onclick="submitReport()">Submit Report</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const Toast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:2200, timerProgressBar:true });

function suggestAction(action, userId, btnEl) {
    const endpoint = action === 'send' ? '/friends/send' : '/friends/cancel';
    if (action === 'cancel' && !confirm('Cancel this friend request?')) return;
    if (btnEl) btnEl.disabled = true;

    fetch(endpoint, {
        method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({ user_id: userId })
    })
    .then(r=>r.json()).then(d=>{
        if (btnEl) btnEl.disabled = false;
        if (!d.success) { alert(d.message || 'Error'); return; }
        if (action === 'send') {
            btnEl.className = 'btn-add pending';
            btnEl.innerHTML = '<i class="bi bi-person-check-fill me-1"></i> Requested · Cancel';
            btnEl.onclick = function(){ suggestAction('cancel', userId, this); };
        } else {
            btnEl.className = 'btn-add';
            btnEl.innerHTML = '<i class="bi bi-person-plus-fill me-1"></i> Add Friend';
            btnEl.onclick = function(){ suggestAction('send', userId, this); };
        }
        Toast.fire({ icon:'success', title:d.message });
    }).catch(()=>{ if(btnEl) btnEl.disabled=false; });
}

function notInterested(userId, btnEl) {
    if (btnEl) btnEl.disabled = true;
    fetch('/friends/not-interested', {
        method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({ user_id: userId })
    })
    .then(r=>r.json()).then(d=>{
        if (d.success) {
            const card = document.getElementById('pcard-' + userId);
            if (card) { card.style.transition='opacity .3s,transform .3s'; card.style.opacity='0'; card.style.transform='scale(.95)'; setTimeout(()=>card.remove(),300); }
        }
    }).catch(()=>{ if(btnEl) btnEl.disabled=false; });
}

// ---- MUTUAL ----
let mutualModalObj = new bootstrap.Modal(document.getElementById('mutualModal'));
function loadMutuals(userId, name) {
    document.getElementById('mutualTitle').textContent = 'Mutual Friends with ' + name;
    document.getElementById('mutualBody').innerHTML = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>';
    mutualModalObj.show();
    fetch('/friends/' + userId + '/mutual', { headers:{'Accept':'application/json'} })
    .then(r=>r.json()).then(d=>{
        if (!d.mutuals || !d.mutuals.length) { document.getElementById('mutualBody').innerHTML = '<p class="text-muted text-center small py-2">No mutual friends found.</p>'; return; }
        let html = '';
        d.mutuals.forEach(m => {
            const pic = m.profile_picture ? `<img src="/storage/${m.profile_picture}" style="width:100%;height:100%;object-fit:cover;">` : m.name.charAt(0).toUpperCase();
            html += `<div class="mutual-list-item"><div class="mutual-avatar-sm">${pic}</div><div><div style="font-size:13.5px;font-weight:700;">${m.name}</div><div style="font-size:11.5px;color:#6b7280;">${m.department||ucfirst(m.role)}</div></div></div>`;
        });
        document.getElementById('mutualBody').innerHTML = html;
    });
}

// ---- REPORT ----
let reportModalObj = new bootstrap.Modal(document.getElementById('reportModal'));
function openReport(type, id, name) {
    document.getElementById('reportType').value = type;
    document.getElementById('reportId').value = id;
    document.getElementById('reportTargetName').textContent = name;
    document.getElementById('reportReason').value = '';
    document.getElementById('reportReasons').classList.remove('d-none');
    document.getElementById('reportDetailsSection').classList.add('d-none');
    document.querySelectorAll('.report-reason-btn').forEach(b => b.classList.remove('selected'));
    reportModalObj.show();
}
function selectReason(reason) {
    document.getElementById('reportReason').value = reason;
    document.querySelectorAll('.report-reason-btn').forEach(b => b.classList.remove('selected'));
    event.currentTarget.classList.add('selected');
    document.getElementById('reportReasons').classList.add('d-none');
    document.getElementById('reportDetailsSection').classList.remove('d-none');
}
function submitReport() {
    const type = document.getElementById('reportType').value;
    const id   = document.getElementById('reportId').value;
    const reason = document.getElementById('reportReason').value;
    const details = document.getElementById('reportDetails').value;

    fetch('/report', {
        method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF},
        body: JSON.stringify({ type, id, reason, details })
    })
    .then(r=>r.json()).then(d=>{
        reportModalObj.hide();
        Toast.fire({ icon: d.success ? 'success':'warning', title: d.message });
    });
}
function ucfirst(s) { return s.charAt(0).toUpperCase() + s.slice(1); }
</script>
</body>
</html>