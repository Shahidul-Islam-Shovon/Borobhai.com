<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">
    <title>Friends · Borobhai.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bb-primary: #4f46e5; --bb-primary-soft: #eef2ff;
            --bb-ink: #1e1f24; --bb-muted: #6b7280;
            --bb-line: #eceef1; --bb-bg: #f0f2f5; --bb-card: #ffffff;
        }
        body { background: var(--bb-bg); color: var(--bb-ink); font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; }

        /* Navbar */
        .navbar { background:#fff; box-shadow:0 2px 4px rgba(0,0,0,.08); padding:.5rem 1rem; position:sticky; top:0; z-index:100; }
        .navbar-brand { font-weight:800; color:var(--bb-primary); font-size:1.4rem; letter-spacing:-.5px; text-decoration:none; }
        .nav-icon-btn { width:40px; height:40px; border-radius:50%; background:#e4e6eb; display:flex; align-items:center; justify-content:center; color:#050505; text-decoration:none; font-size:1.1rem; border:none; }
        .nav-icon-btn:hover { background:#d8dadf; }

        /* Layout */
        .fr-wrap { max-width: 900px; margin: 24px auto; padding: 0 14px; }

        /* Tabs */
        .fr-tabs { display:flex; gap:8px; margin-bottom:20px; border-bottom:2px solid var(--bb-line); padding-bottom:0; }
        .fr-tab {
            font-size:.92rem; font-weight:600; color:var(--bb-muted);
            background:transparent; border:none; padding:10px 18px;
            cursor:pointer; border-bottom:3px solid transparent; margin-bottom:-2px;
            transition:all .15s ease;
        }
        .fr-tab:hover { color:var(--bb-primary); }
        .fr-tab.active { color:var(--bb-primary); border-bottom-color:var(--bb-primary); }
        .fr-tab .badge { font-size:10px; padding:2px 6px; }

        /* Section */
        .fr-section { display:none; }
        .fr-section.active { display:block; }
        .fr-section-title { font-size:1.1rem; font-weight:700; color:var(--bb-ink); margin-bottom:16px; }

        /* Friend Card */
        .fr-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:14px; }
        .fr-card {
            background:var(--bb-card); border-radius:14px;
            box-shadow:0 1px 3px rgba(16,24,40,.06);
            overflow:hidden; transition:box-shadow .18s ease;
        }
        .fr-card:hover { box-shadow:0 6px 20px rgba(79,70,229,.10); }
        .fr-card-cover {
            height:80px; background:linear-gradient(135deg, var(--bb-primary), #7c73f0);
        }
        .fr-card-body { padding:0 14px 14px; text-align:center; }
        .fr-card-avatar {
            width:72px; height:72px; border-radius:50%; border:4px solid #fff;
            background:linear-gradient(135deg, var(--bb-primary), #7c73f0);
            color:#fff; display:flex; align-items:center; justify-content:center;
            font-weight:700; font-size:26px; margin:-36px auto 8px; overflow:hidden;
            box-shadow:0 2px 8px rgba(79,70,229,.2);
        }
        .fr-card-avatar img { width:100%; height:100%; object-fit:cover; }
        .fr-card-name { font-size:.95rem; font-weight:700; color:var(--bb-ink); text-decoration:none; display:block; margin-bottom:3px; }
        .fr-card-name:hover { color:var(--bb-primary); }
        .fr-card-role { font-size:.78rem; color:var(--bb-muted); margin-bottom:10px; }
        .fr-card-btn {
            width:100%; border:none; border-radius:9px; padding:7px;
            font-size:.84rem; font-weight:600; cursor:pointer; transition:all .15s;
        }
        .fr-btn-unfriend { background:#f3f4f8; color:#374151; }
        .fr-btn-unfriend:hover { background:#fee2e2; color:#dc2626; }
        .fr-btn-accept { background:var(--bb-primary); color:#fff; }
        .fr-btn-accept:hover { background:#4338ca; }
        .fr-btn-decline { background:#f3f4f8; color:#374151; margin-top:5px; }
        .fr-btn-decline:hover { background:#fee2e2; color:#dc2626; }
        .fr-btn-cancel { background:#f3f4f8; color:var(--bb-muted); }
        .fr-btn-cancel:hover { background:#fee2e2; color:#dc2626; }

        /* Role chips */
        .chip-student { background:#eef2ff; color:#4f46e5; }
        .chip-alumni  { background:#fef3c7; color:#d97706; }
        .chip-teacher { background:#f3e8ff; color:#7c3aed; }
        .fr-chip { font-size:10px; font-weight:700; padding:2px 8px; border-radius:12px; }

        /* Empty state */
        .fr-empty { text-align:center; padding:50px 20px; color:var(--bb-muted); }
        .fr-empty i { font-size:3rem; color:#cbd5e1; display:block; margin-bottom:12px; }
        .fr-empty h5 { font-size:1rem; font-weight:700; color:#475569; margin:0 0 6px; }
        .fr-empty p { font-size:.88rem; margin:0; }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('home') }}" class="navbar-brand">Borobhai.com</a>
        </div>
        <div class="d-flex align-items-center gap-2 ms-auto">
            <a href="{{ route('home') }}" class="nav-icon-btn" title="Home"><i class="bi bi-house-door-fill"></i></a>
            <a href="{{ route('search.index') }}" class="nav-icon-btn" title="Search"><i class="bi bi-search"></i></a>
        </div>
    </div>
</nav>

<div class="fr-wrap">

    <h1 style="font-size:1.5rem;font-weight:800;margin-bottom:18px;">
        <i class="bi bi-people-fill text-primary me-2"></i>Friends
    </h1>

    {{-- Tabs --}}
    <div class="fr-tabs">
        <button class="fr-tab active" onclick="switchTab('friends')">
            Friends
            @if($friends->count() > 0)
                <span class="badge bg-secondary rounded-pill ms-1">{{ $friends->count() }}</span>
            @endif
        </button>
        <button class="fr-tab" onclick="switchTab('received')">
            Requests
            @if($pendingReceived->count() > 0)
                <span class="badge bg-danger rounded-pill ms-1">{{ $pendingReceived->count() }}</span>
            @endif
        </button>
        <button class="fr-tab" onclick="switchTab('sent')">
            Sent
            @if($pendingSent->count() > 0)
                <span class="badge bg-secondary rounded-pill ms-1">{{ $pendingSent->count() }}</span>
            @endif
        </button>
    </div>

    {{-- ===== Tab 1: Friends ===== --}}
    <div class="fr-section active" id="tab-friends">
        @if($friends->count() === 0)
            <div class="fr-empty">
                <i class="bi bi-people"></i>
                <h5>No friends yet</h5>
                <p>Search for people and send friend requests.</p>
                <a href="{{ route('search.index') }}" class="btn btn-primary btn-sm mt-3">
                    <i class="bi bi-search me-1"></i> Find People
                </a>
            </div>
        @else
            <div class="fr-grid">
                @foreach($friends as $fr)
                @php
                    $job = $fr->currentJob;
                    $sub = $job
                        ? ($job->designation ?? '') . ($job->company ? ' at '.$job->company : '')
                        : ($fr->department ?? ucfirst($fr->role));
                @endphp
                <div class="fr-card" id="fr-card-{{ $fr->id }}">
                    <div class="fr-card-cover"></div>
                    <div class="fr-card-body">
                        <a href="{{ route('profile.view', $fr->id) }}" class="fr-card-avatar">
                            @if($fr->profile_picture)
                                <img src="{{ asset('storage/'.$fr->profile_picture) }}" alt="{{ $fr->name }}">
                            @else
                                {{ strtoupper(substr($fr->name,0,1)) }}
                            @endif
                        </a>
                        <a href="{{ route('profile.view', $fr->id) }}" class="fr-card-name">{{ $fr->name }}</a>
                        <div class="fr-card-role">
                            <span class="fr-chip chip-{{ $fr->role }}">{{ ucfirst($fr->role) }}</span>
                            @if($sub)
                                <div class="mt-1" style="font-size:.78rem;">{{ $sub }}</div>
                            @endif
                        </div>
                        <button class="fr-card-btn fr-btn-unfriend"
                                onclick="frAction('unfriend', {{ $fr->id }}, this)">
                            <i class="bi bi-person-check-fill me-1"></i> Friends
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ===== Tab 2: Received Requests ===== --}}
    <div class="fr-section" id="tab-received">
        @if($pendingReceived->count() === 0)
            <div class="fr-empty">
                <i class="bi bi-inbox"></i>
                <h5>No pending requests</h5>
                <p>When someone sends you a friend request, it will appear here.</p>
            </div>
        @else
            <div class="fr-grid">
                @foreach($pendingReceived as $req)
                @php $u = $req->sender; @endphp
                <div class="fr-card" id="fr-card-{{ $u->id }}">
                    <div class="fr-card-cover"></div>
                    <div class="fr-card-body">
                        <a href="{{ route('profile.view', $u->id) }}" class="fr-card-avatar">
                            @if($u->profile_picture)
                                <img src="{{ asset('storage/'.$u->profile_picture) }}" alt="{{ $u->name }}">
                            @else
                                {{ strtoupper(substr($u->name,0,1)) }}
                            @endif
                        </a>
                        <a href="{{ route('profile.view', $u->id) }}" class="fr-card-name">{{ $u->name }}</a>
                        <div class="fr-card-role">
                            <span class="fr-chip chip-{{ $u->role }}">{{ ucfirst($u->role) }}</span>
                            @if($u->department)
                                <div class="mt-1" style="font-size:.78rem;">{{ $u->department }}</div>
                            @endif
                        </div>
                        <button class="fr-card-btn fr-btn-accept"
                                onclick="frAction('accept', {{ $u->id }}, this)">
                            <i class="bi bi-check-lg me-1"></i> Accept
                        </button>
                        <button class="fr-card-btn fr-btn-decline"
                                onclick="frAction('decline', {{ $u->id }}, this)">
                            <i class="bi bi-x-lg me-1"></i> Decline
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ===== Tab 3: Sent Requests ===== --}}
    <div class="fr-section" id="tab-sent">
        @if($pendingSent->count() === 0)
            <div class="fr-empty">
                <i class="bi bi-send"></i>
                <h5>No sent requests</h5>
                <p>Friend requests you send will appear here.</p>
            </div>
        @else
            <div class="fr-grid">
                @foreach($pendingSent as $req)
                @php $u = $req->receiver; @endphp
                <div class="fr-card" id="fr-card-{{ $u->id }}">
                    <div class="fr-card-cover"></div>
                    <div class="fr-card-body">
                        <a href="{{ route('profile.view', $u->id) }}" class="fr-card-avatar">
                            @if($u->profile_picture)
                                <img src="{{ asset('storage/'.$u->profile_picture) }}" alt="{{ $u->name }}">
                            @else
                                {{ strtoupper(substr($u->name,0,1)) }}
                            @endif
                        </a>
                        <a href="{{ route('profile.view', $u->id) }}" class="fr-card-name">{{ $u->name }}</a>
                        <div class="fr-card-role">
                            <span class="fr-chip chip-{{ $u->role }}">{{ ucfirst($u->role) }}</span>
                            @if($u->department)
                                <div class="mt-1" style="font-size:.78rem;">{{ $u->department }}</div>
                            @endif
                        </div>
                        <button class="fr-card-btn fr-btn-cancel"
                                onclick="frAction('cancel', {{ $u->id }}, this)">
                            <i class="bi bi-x-circle me-1"></i> Cancel Request
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Tab switching
function switchTab(tab) {
    document.querySelectorAll('.fr-tab').forEach((t, i) => {
        t.classList.toggle('active', ['friends','received','sent'][i] === tab);
    });
    document.querySelectorAll('.fr-section').forEach(s => s.classList.remove('active'));
    document.getElementById('tab-' + tab)?.classList.add('active');
}

// Friend action
function frAction(action, userId, btnEl) {
    const endpoints = {
        accept:  '/friends/accept',
        decline: '/friends/decline',
        cancel:  '/friends/cancel',
        unfriend:'/friends/unfriend',
        block:   '/friends/block',
    };

    if (action === 'unfriend' && !confirm('Remove this person from your friends?')) return;

    if (btnEl) btnEl.disabled = true;

    fetch(endpoints[action], {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ user_id: userId }),
    })
    .then(r => r.json())
    .then(d => {
        if (!d.success) {
            if (btnEl) btnEl.disabled = false;
            alert(d.message || 'Something went wrong.');
            return;
        }

        // card সরাও
        const card = document.getElementById('fr-card-' + userId);
        if (card) {
            card.style.transition = 'opacity .3s, transform .3s';
            card.style.opacity = '0';
            card.style.transform = 'scale(.95)';
            setTimeout(() => card.remove(), 300);
        }

        Swal.mixin({
            toast: true, position: 'top-end',
            showConfirmButton: false, timer: 2000, timerProgressBar: true
        }).fire({ icon: 'success', title: d.message });
    })
    .catch(() => {
        if (btnEl) btnEl.disabled = false;
        alert('Network error.');
    });
}
</script>
</body>
</html>