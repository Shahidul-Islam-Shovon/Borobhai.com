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
        .sr-tab {
            flex-shrink: 0; font-size: .86rem; font-weight: 600; color: var(--bb-muted);
            background: #fff; border: 1.5px solid var(--bb-line); border-radius: 20px;
            padding: 7px 16px; text-decoration: none; transition: all .15s ease; white-space: nowrap;
        }
        .sr-tab:hover { border-color: #c7d2fe; color: var(--bb-primary); }
        .sr-tab.active { background: var(--bb-primary); border-color: var(--bb-primary); color: #fff; }

        .sr-card {
            background: var(--bb-card); border-radius: 14px; box-shadow: 0 1px 3px rgba(16,24,40,.06);
            padding: 12px 14px; margin-bottom: 12px; display: flex; align-items: center; gap: 14px;
            transition: box-shadow .18s ease;
        }
        .sr-card:hover { box-shadow: 0 6px 22px rgba(79,70,229,.10); }
        .sr-avatar {
            width: 64px; height: 64px; border-radius: 50%; flex-shrink: 0; overflow: hidden;
            background: linear-gradient(135deg, var(--bb-primary), #7c73f0);
            color: #fff; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 26px; text-decoration: none;
        }
        .sr-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .sr-info { flex-grow: 1; min-width: 0; }
        .sr-name {
            font-size: 1.02rem; font-weight: 700; color: var(--bb-ink); text-decoration: none;
            letter-spacing: -.2px; display: inline-block;
        }
        .sr-name:hover { text-decoration: underline; color: var(--bb-primary); }
        .sr-sub { font-size: .85rem; color: var(--bb-muted); margin: 2px 0 0; display: flex; align-items: center; gap: 5px; flex-wrap: wrap; }
        .sr-topic { font-size: .8rem; color: var(--bb-primary); margin-top: 3px; display: flex; align-items: center; gap: 5px; }
        .sr-topic i { font-size: .72rem; }

        .sr-rolechip {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: 10.5px; font-weight: 700; letter-spacing: .2px;
            padding: 1px 9px; border-radius: 12px; text-transform: capitalize;
        }
        .sr-rolechip i { font-size: 9px; }
        .sr-role-student { background: #eef2ff; color: #4f46e5; }
        .sr-role-alumni  { background: #fef3c7; color: #d97706; }
        .sr-role-teacher { background: #f3e8ff; color: #7c3aed; }

        .sr-friend-btn {
            flex-shrink: 0; border: none; border-radius: 9px; cursor: pointer;
            background: var(--bb-primary-soft); color: var(--bb-primary);
            font-size: .84rem; font-weight: 700; padding: 9px 16px;
            display: inline-flex; align-items: center; gap: 6px; transition: all .15s ease;
        }
        .sr-friend-btn:hover { background: var(--bb-primary); color: #fff; }
        .sr-friend-btn i { font-size: 14px; }
        @media (max-width: 520px) {
            .sr-friend-btn span { display: none; }
            .sr-friend-btn { padding: 9px 12px; }
            .sr-avatar { width: 54px; height: 54px; font-size: 22px; }
        }

        .sr-empty { text-align: center; padding: 60px 20px; color: var(--bb-muted); }
        .sr-empty i { font-size: 3rem; color: #cbd5e1; display: block; margin-bottom: 14px; }
        .sr-empty h3 { font-size: 1.1rem; font-weight: 700; color: #475569; margin: 0 0 6px; }
        .sr-empty p { font-size: .9rem; margin: 0; }

        .sr-pagination { margin-top: 18px; display: flex; justify-content: center; }
        .sr-pagination .pagination { --bs-pagination-color: var(--bb-primary); --bs-pagination-active-bg: var(--bb-primary); --bs-pagination-active-border-color: var(--bb-primary); }
    </style>
</head>
<body>

{{-- ===== Navbar ===== --}}
<nav class="navbar">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-2 w-100">
            <a class="navbar-brand m-0" href="{{ route('home') }}">Borobhai.com</a>
            <form action="{{ route('search.index') }}" method="GET" class="sr-topsearch ms-2">
                <i class="bi bi-search text-muted"></i>
                <input type="text" name="q" value="{{ $query }}" placeholder="Search Borobhai" autocomplete="off">
            </form>
            <a href="{{ route('home') }}" class="nav-icon-btn ms-auto" title="Home"><i class="bi bi-house-door-fill"></i></a>
        </div>
    </div>
</nav>

<div class="sr-wrap">

    {{-- ===== Header ===== --}}
    <div class="sr-head">
        @if($query !== '')
            <h1>Search results</h1>
            <p>{{ $total }} result{{ $total === 1 ? '' : 's' }} for "<span class="sr-q">{{ $query }}</span>"</p>
        @else
            <h1>Search Borobhai</h1>
            <p>Find people by name, department, skills, or thesis topic.</p>
        @endif
    </div>

    {{-- ===== Filter Tabs ===== --}}
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

    {{-- ===== Results ===== --}}
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
                $job   = $user->currentJob;
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
            @endphp
            <div class="sr-card">
                {{-- avatar --}}
                <a href="{{ route('profile.view', $user->id) }}" class="sr-avatar">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/'.$user->profile_picture) }}" alt="{{ $user->name }}">
                    @else
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    @endif
                </a>

                {{-- info --}}
                <div class="sr-info">
                    <a href="{{ route('profile.view', $user->id) }}" class="sr-name">{{ $user->name }}</a>
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

                {{-- add friend (UI ready — backend later) --}}
                <button type="button" class="sr-friend-btn" onclick="sendFriendRequest({{ $user->id }}, this)">
                    <i class="bi bi-person-plus-fill"></i> <span>Add Friend</span>
                </button>
            </div>
        @endforeach

        {{-- pagination --}}
        <div class="sr-pagination">
            {{ $results->links() }}
        </div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Friend request — Friends module এখনো হয়নি, তাই আপাতত placeholder
// পরে Module 5 বানানোর সময় এই function এ আসল AJAX বসবে
function sendFriendRequest(userId, btn) {
    const Toast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:2200, timerProgressBar:true });
    Toast.fire({ icon:'info', title:'Friend requests coming soon!' });
    // ভবিষ্যতে:
    // fetch(`/friends/request/${userId}`, {...}) দিয়ে আসল request
}
</script>
</body>
</html>