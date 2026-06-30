<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <title>Notifications · Borobhai.online</title>
    <style>
        :root { --bb-primary:#4f46e5; --bb-ink:#1e1f24; --bb-muted:#6b7280; }
        body { background:#f0f2f5; font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif; }

        .bb-np-navbar {
            background:#fff; box-shadow:0 2px 4px rgba(0,0,0,.08);
            padding:.6rem 1rem; position:sticky; top:0; z-index:100;
            display:flex; align-items:center; gap:14px;
        }
        .bb-np-back {
            width:40px; height:40px; border-radius:50%; background:#e4e6eb;
            display:flex; align-items:center; justify-content:center;
            color:#050505; text-decoration:none; font-size:1.1rem; flex-shrink:0;
        }
        .bb-np-back:hover { background:#d8dadf; color:#050505; }
        .bb-np-brand { font-weight:800; font-size:1.15rem; color:#000; text-decoration:none; }

        .bb-np-wrap { max-width:680px; margin:22px auto; padding:0 12px; }
        .bb-np-card { background:#fff; border-radius:16px; box-shadow:0 1px 3px rgba(16,24,40,.06); overflow:hidden; }
        .bb-np-head {
            display:flex; align-items:center; justify-content:space-between;
            padding:18px 20px 14px;
        }
        .bb-np-title { font-size:22px; font-weight:800; color:#111827; letter-spacing:-.4px; margin:0; }
        .bb-np-markall {
            font-size:13px; font-weight:600; color:var(--bb-primary);
            border:none; background:#eef2ff; cursor:pointer; padding:7px 14px; border-radius:8px;
        }
        .bb-np-markall:hover { background:#e0e7ff; }

        .bb-np-group-label {
            font-size:13px; font-weight:700; color:var(--bb-ink);
            padding:14px 20px 6px; letter-spacing:-.2px;
        }

        .bb-np-item {
            display:flex; align-items:flex-start; gap:13px;
            padding:13px 20px; cursor:pointer; position:relative;
            transition:background .12s; text-decoration:none;
        }
        .bb-np-item:hover { background:#f5f7ff; }
        .bb-np-item.unread { background:#eef2ff; }
        .bb-np-item.unread .bb-np-msg { font-weight:600; }

        .bb-np-avatar {
            width:52px; height:52px; min-width:52px; border-radius:50%;
            overflow:hidden; background:linear-gradient(135deg,#4f46e5,#7c73f0);
            color:#fff; display:flex; align-items:center; justify-content:center;
            font-weight:700; font-size:19px; flex-shrink:0;
        }
        .bb-np-avatar img { width:100%; height:100%; object-fit:cover; }
        .bb-np-body { flex:1; min-width:0; padding-top:2px; }
        .bb-np-msg  { font-size:14.5px; color:var(--bb-ink); line-height:1.45; word-break:break-word; margin-bottom:4px; }
        .bb-np-time { font-size:12.5px; color:var(--bb-muted); display:flex; align-items:center; gap:6px; }
        .bb-np-dot  { width:11px; height:11px; border-radius:50%; background:var(--bb-primary); flex-shrink:0; align-self:center; }

        .bb-np-empty { text-align:center; padding:60px 20px; color:#9ca3af; }
        .bb-np-empty i { font-size:3rem; display:block; margin-bottom:12px; }

        .bb-np-pagination { padding:18px 20px; display:flex; justify-content:center; }
        .bb-np-pagination .pagination { margin:0; }
        .bb-np-pagination .page-link { color:var(--bb-primary); border-radius:8px; margin:0 3px; border:none; }
        .bb-np-pagination .active .page-link { background:var(--bb-primary); color:#fff; }
    </style>
</head>
<body>

<nav class="bb-np-navbar">
    <a href="{{ route('home') }}" class="bb-np-back"><i class="bi bi-arrow-left"></i></a>
    <a href="{{ route('home') }}" class="bb-np-brand">Borobhai.online</a>
</nav>

<div class="bb-np-wrap">
    <div class="bb-np-card">

        <div class="bb-np-head">
            <h1 class="bb-np-title">Notifications</h1>
            @if($notifications->total() > 0)
                <button class="bb-np-markall" onclick="pageMarkAllRead()">
                    <i class="bi bi-check2-all me-1"></i> Mark all read
                </button>
            @endif
        </div>

        @forelse($groups as $label => $items)
            <div class="bb-np-group-label">{{ $label }}</div>
            @foreach($items as $n)
                @php $m = app(\App\Http\Controllers\NotificationController::class)->metaFor($n); @endphp
                @php
                    $actor = $n->actor;
                    $pic   = $actor?->profile_picture ? asset('storage/'.$actor->profile_picture) : null;
                @endphp
                <div class="bb-np-item {{ $n->is_read ? '' : 'unread' }}"
                     data-id="{{ $n->id }}"
                     data-action="{{ $m['action'] }}"
                     data-target="{{ $m['target'] }}"
                     data-read="{{ $n->is_read ? 1 : 0 }}"
                     onclick="pageNotifClick(this)">
                    <div class="bb-np-avatar">
                        @if($pic)<img src="{{ $pic }}" alt="">@else{{ strtoupper(substr($actor?->name ?? 'U',0,1)) }}@endif
                    </div>
                    <div class="bb-np-body">
                        <div class="bb-np-msg">{{ $n->message }}</div>
                        <div class="bb-np-time">{!! $m['icon'] !!} {{ $n->created_at->diffForHumans() }}</div>
                    </div>
                    @if(!$n->is_read)<span class="bb-np-dot"></span>@endif
                </div>
            @endforeach
        @empty
            <div class="bb-np-empty">
                <i class="bi bi-bell-slash"></i>
                <p class="mb-0">No notifications yet</p>
            </div>
        @endforelse

        @if($notifications->hasPages())
            <div class="bb-np-pagination">{{ $notifications->links() }}</div>
        @endif

    </div>
</div>

<script>
function pageNotifClick(el) {
    var id = el.dataset.id;
    if (el.dataset.read === '0') {
        el.classList.remove('unread');
        el.querySelector('.bb-np-dot')?.remove();
        el.dataset.read = '1';
        fetch('/notifications/' + id + '/read', {
            method:'POST',
            headers:{ 'Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content }
        }).catch(function(){});
    }
    routeFromPage(el.dataset.action, el.dataset.target);
}

function routeFromPage(action, target) {
    if (action === 'profile')  { location.href = '/profile/' + target; return; }
    if (action === 'job')      { location.href = '/jobs/' + target;    return; }
    if (action === 'home')     { location.href = '/';                  return; }
    if (action === 'comments') { location.href = '/?open_comments=' + target; return; }
    if (action === 'post')     { location.href = '/?goto_post=' + target; }   // ← hash এর বদলে query
}


function pageMarkAllRead() {
    fetch('/notifications/read-all', {
        method:'POST',
        headers:{ 'Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(function(){
        document.querySelectorAll('.bb-np-item.unread').forEach(function(el){
            el.classList.remove('unread');
            el.querySelector('.bb-np-dot')?.remove();
            el.dataset.read = '1';
        });
    });
}
</script>

</body>
</html>