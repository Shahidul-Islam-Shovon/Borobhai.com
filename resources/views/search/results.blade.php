<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">
    <title>Search · Borobhai.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bb-primary: #4f46e5; --bb-primary-dark: #4338ca; --bb-primary-soft: #eef2ff;
            --bb-ink: #1e1f24; --bb-muted: #6b7280; --bb-line: #eceef1;
            --bb-bg: #f0f2f5; --bb-card: #ffffff;
        }
        * { box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: var(--bb-bg); color: var(--bb-ink); margin: 0; }
        .navbar { background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,.08); padding: .5rem 1rem; position: sticky; top: 0; z-index: 100; }
        .navbar-brand { font-weight: 700; color: var(--bb-primary); font-size: 1.5rem; letter-spacing: -.5px; text-decoration: none; }
        .sr-topsearch { background: var(--bb-bg); border-radius: 50px; padding: .5rem 1rem; display: flex; align-items: center; width: 320px; max-width: 100%; }
        .sr-topsearch input { background: transparent; border: none; outline: none; margin-left: 8px; font-size: .92rem; width: 100%; }
        .nav-icon-btn { width: 40px; height: 40px; border-radius: 50px; background: #e4e6eb; display: flex; align-items: center; justify-content: center; color: #050505; text-decoration: none; font-size: 1.2rem; border: none; }
        .nav-icon-btn:hover { background: #d8dadf; }
        .sr-wrap { max-width: 720px; margin: 22px auto; padding: 0 14px; }
        .sr-head { margin-bottom: 16px; }
        .sr-head h1 { font-size: 1.35rem; font-weight: 800; letter-spacing: -.4px; margin: 0 0 3px; }
        .sr-head p { font-size: .9rem; color: var(--bb-muted); margin: 0; }
        .sr-head .sr-q { color: var(--bb-primary); }
        .sr-tabs { display: flex; gap: 8px; margin-bottom: 18px; overflow-x: auto; padding-bottom: 4px; scrollbar-width: none; }
        .sr-tabs::-webkit-scrollbar { display: none; }
        .sr-tab { flex-shrink: 0; font-size: .86rem; font-weight: 600; color: var(--bb-muted); background: #fff; border: 1.5px solid var(--bb-line); border-radius: 20px; padding: 7px 16px; text-decoration: none; transition: all .15s ease; white-space: nowrap; }
        .sr-tab:hover { border-color: #c7d2fe; color: var(--bb-primary); }
        .sr-tab.active { background: var(--bb-primary); border-color: var(--bb-primary); color: #fff; }
        .sr-card { background: var(--bb-card); border-radius: 14px; box-shadow: 0 1px 3px rgba(16,24,40,.06); padding: 12px 14px; margin-bottom: 12px; display: flex; align-items: center; gap: 14px; transition: box-shadow .18s ease; }
        .sr-card:hover { box-shadow: 0 6px 22px rgba(79,70,229,.10); }
        .sr-avatar { width: 64px; height: 64px; border-radius: 50%; flex-shrink: 0; overflow: hidden; background: linear-gradient(135deg, var(--bb-primary), #7c73f0); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 26px; text-decoration: none; }
        .sr-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .sr-info { flex-grow: 1; min-width: 0; }
        .sr-name { font-size: 1.02rem; font-weight: 700; color: var(--bb-ink); text-decoration: none; letter-spacing: -.2px; display: inline-block; }
        .sr-name:hover { text-decoration: underline; color: var(--bb-primary); }
        .sr-sub { font-size: .85rem; color: var(--bb-muted); margin: 2px 0 0; display: flex; align-items: center; gap: 5px; flex-wrap: wrap; }
        .sr-topic { font-size: .8rem; color: var(--bb-primary); margin-top: 3px; display: flex; align-items: center; gap: 5px; }
        .sr-rolechip { display: inline-flex; align-items: center; gap: 4px; font-size: 10.5px; font-weight: 700; letter-spacing: .2px; padding: 1px 9px; border-radius: 12px; text-transform: capitalize; }
        .sr-role-student { background: #eef2ff; color: #4f46e5; }
        .sr-role-alumni  { background: #fef3c7; color: #d97706; }
        .sr-role-teacher { background: #f3e8ff; color: #7c3aed; }

        /* ===== Friend button states ===== */
        .sr-btn { flex-shrink: 0; border: none; border-radius: 9px; cursor: pointer; font-size: .84rem; font-weight: 700; padding: 9px 16px; display: inline-flex; align-items: center; gap: 6px; transition: all .15s ease; }
        .sr-btn-add      { background: var(--bb-primary-soft); color: var(--bb-primary); }
        .sr-btn-add:hover { background: var(--bb-primary); color: #fff; }
        .sr-btn-pending  { background: #eef2ff; color: #4f46e5; border: 1.5px solid #c7d2fe; }
        .sr-btn-pending:hover { background: #fef2f2; color: #dc2626; border-color: #fca5a5; }
        .sr-btn-friends  { background: #f3f4f8; color: #374151; border: 1.5px solid #e5e7eb; }
        .sr-btn-friends:hover { background: #fee2e2; color: #dc2626; }
        .sr-btn-accept   { background: #059669; color: #fff; }
        .sr-btn-accept:hover { background: #047857; }
        .sr-btn-blocked  { background: #fee2e2; color: #dc2626; }

        @media (max-width: 520px) {
            .sr-btn span { display: none; }
            .sr-btn { padding: 9px 12px; }
            .sr-avatar { width: 54px; height: 54px; font-size: 22px; }
        }
        .sr-empty { text-align: center; padding: 60px 20px; color: var(--bb-muted); }
        .sr-empty i { font-size: 3rem; color: #cbd5e1; display: block; margin-bottom: 14px; }
        .sr-empty h3 { font-size: 1.1rem; font-weight: 700; color: #475569; margin: 0 0 6px; }
        .sr-pagination { margin-top: 18px; display: flex; justify-content: center; }
        .sr-pagination .pagination { --bs-pagination-color: var(--bb-primary); --bs-pagination-active-bg: var(--bb-primary); --bs-pagination-active-border-color: var(--bb-primary); }
    </style>
</head>
<body>

@include('partials.inner-navbar')

<div class="sr-wrap">

    <div class="sr-head">
        @if($query !== '')
            <h1>Search results</h1>
            <p>{{ $total }} result{{ $total === 1 ? '' : 's' }} for "<span class="sr-q">{{ $query }}</span>"</p>
        @else
            <h1>Search Borobhai</h1>
            <p>Find people by name, department, skills, or thesis topic.</p>
        @endif
    </div>

    @if($query !== '')
    <div class="sr-tabs">
        @php
            $tabs = [
                'all'     => ['All', 'bi-grid-fill'],
                'student' => ['Students', 'bi-backpack-fill'],
                'alumni'  => ['Alumni', 'bi-mortarboard-fill'],
                'teacher' => ['Teachers', 'bi-easel2-fill'],
                'topic'   => ['Thesis Topic', 'bi-journal-text'],
            ];
        @endphp
        @foreach($tabs as $key => $tab)
            <a href="{{ route('search.index', ['q' => $query, 'filter' => $key]) }}"
               class="sr-tab {{ $filter === $key ? 'active' : '' }}">
                <i class="bi {{ $tab[1] }} me-1"></i>{{ $tab[0] }}
            </a>
        @endforeach
    </div>
    @endif

    @if($query === '')
        <div class="sr-empty">
            <i class="bi bi-search"></i>
            <h3>Start typing to search</h3>
            <p>Search for people, departments, skills, or research topics across Borobhai.</p>
        </div>
    @elseif($total === 0)
        <div class="sr-empty">
            <i class="bi bi-emoji-frown"></i>
            <h3>No results found</h3>
            <p>We couldn't find anything for "{{ $query }}". Try a different keyword.</p>
        </div>
    @else
        @foreach($results as $user)
            @php
                $job   = $user->currentJob ?? null;
                $desig = $job->designation ?? $job->title ?? $job->position ?? null;
                $comp  = $job->company ?? $job->company_name ?? $job->organization ?? null;
                if ($job && ($desig || $comp)) {
                    $subline = trim(($desig ?: 'Works') . ($comp ? ' at ' . $comp : ''));
                } else {
                    $sp = array_filter([$user->department, $user->session]);
                    $subline = implode(' · ', $sp);
                }
                $doc = $user->documents->first();
                $matchedTopic = $doc->topic ?? $doc->title ?? null;
                $roleClass = 'sr-role-' . $user->role;
                $roleIcon = match($user->role) {
                    'student' => 'bi-backpack-fill',
                    'alumni'  => 'bi-mortarboard-fill',
                    'teacher' => 'bi-easel2-fill',
                    default   => 'bi-person-fill',
                };
                $fs = $user->friendshipStatus ?? 'none';
            @endphp

            <div class="sr-card">
                {{-- Avatar — click করলে profile এ যাবে --}}
                <a href="{{ route('profile.view', $user) }}" class="sr-avatar">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/'.$user->profile_picture) }}" alt="{{ $user->name }}">
                    @else
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    @endif
                </a>

                {{-- Info --}}
                <div class="sr-info">
                    <a href="{{ route('profile.view', $user) }}" class="sr-name">{{ $user->name }}</a>
                    <span class="sr-rolechip {{ $roleClass }} ms-1">
                        <i class="bi {{ $roleIcon }}"></i> {{ ucfirst($user->role) }}
                    </span>
                    @if($subline)
                        <div class="sr-sub">{{ $subline }}</div>
                    @endif
                    @if($matchedTopic && $filter === 'topic')
                        <div class="sr-topic">
                            <i class="bi bi-journal-text"></i> {{ \Illuminate\Support\Str::limit($matchedTopic, 60) }}
                        </div>
                    @endif
                </div>

                {{-- Friend button — state aware --}}
                <div id="sr-wrap-{{ $user->id }}">
                    @if($fs === 'accepted')
                        <button class="sr-btn sr-btn-friends" onclick="srAction('unfriend',{{ $user->id }},this)">
                            <i class="bi bi-people-fill"></i> <span>Friends</span>
                        </button>
                    @elseif($fs === 'pending_sent')
                        <button class="sr-btn sr-btn-pending" onclick="srAction('cancel',{{ $user->id }},this)">
                            <i class="bi bi-person-check-fill"></i> <span>Requested</span>
                        </button>
                    @elseif($fs === 'pending_received')
                        <button class="sr-btn sr-btn-accept" onclick="srAction('accept',{{ $user->id }},this)">
                            <i class="bi bi-person-plus-fill"></i> <span>Accept</span>
                        </button>
                    @elseif($fs === 'blocked')
                        <button class="sr-btn sr-btn-blocked" onclick="srAction('unblock',{{ $user->id }},this)">
                            <i class="bi bi-slash-circle"></i> <span>Blocked</span>
                        </button>
                    @else
                        <button class="sr-btn sr-btn-add" onclick="srAction('send',{{ $user->id }},this)">
                            <i class="bi bi-person-plus-fill"></i> <span>Add Friend</span>
                        </button>
                    @endif
                </div>
            </div>
        @endforeach

        <div class="sr-pagination">
            {{ $results->links() }}
        </div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const Toast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:2000, timerProgressBar:true });

const endpoints = {
    send:     '/friends/send',
    cancel:   '/friends/cancel',
    accept:   '/friends/accept',
    unfriend: '/friends/unfriend',
    unblock:  '/friends/unblock',
};

function srAction(action, userId, btn) {
    if (action === 'unfriend' && !confirm('Remove this person from your friends?')) return;

    btn.disabled = true;
    const orig = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:13px;height:13px;border-width:2px;"></span>';

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
        btn.disabled = false;
        if (!d.success) {
            btn.innerHTML = orig;
            Toast.fire({ icon: 'error', title: d.message || 'Something went wrong' });
            return;
        }

        const wrap = document.getElementById('sr-wrap-' + userId);
        if (!wrap) return;

        switch (d.status) {
            case 'pending_sent':
                wrap.innerHTML = `<button class="sr-btn sr-btn-pending" onclick="srAction('cancel',${userId},this)">
                    <i class="bi bi-person-check-fill"></i> <span>Requested</span></button>`;
                break;
            case 'accepted':
                wrap.innerHTML = `<button class="sr-btn sr-btn-friends" onclick="srAction('unfriend',${userId},this)">
                    <i class="bi bi-people-fill"></i> <span>Friends</span></button>`;
                break;
            case 'none':
            default:
                wrap.innerHTML = `<button class="sr-btn sr-btn-add" onclick="srAction('send',${userId},this)">
                    <i class="bi bi-person-plus-fill"></i> <span>Add Friend</span></button>`;
                break;
        }

        Toast.fire({ icon: 'success', title: d.message || 'Done!' });
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = orig;
        Toast.fire({ icon: 'error', title: 'Network error. Please try again.' });
    });
}
</script>
</body>
</html>