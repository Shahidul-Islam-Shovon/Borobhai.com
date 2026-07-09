<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>

    <style>
        :root{
            --bl-indigo:#4f46e5;
            --bl-indigo-dark:#3730a3;
            --bl-purple:#7c3aed;
            --bl-bg:#f5f4fd;
            --bl-surface:#ffffff;
            --bl-surface-2:#f2f1fc;
            --bl-line:#e6e4f7;
            --bl-text:#1f2140;
            --bl-muted:#6b6d8a;
            --bl-danger:#e11d48;
            --bl-danger-soft:#fdecef;
            --bl-radius:18px;
        }

        .bl-page{
            background:
                radial-gradient(900px 380px at 15% -8%, rgba(124,58,237,0.10), transparent 60%),
                radial-gradient(700px 320px at 100% 0%, rgba(79,70,229,0.08), transparent 55%),
                var(--bl-bg);
            min-height:100vh;
            padding-top:1.5rem;
            padding-bottom:3rem;
            font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;
            color:var(--bl-text);
        }

        /* Constrain & center the whole page content */
        .bl-container{
            width:100%;
            max-width:520px;
            margin:0 auto;
            padding:0 16px;
        }

        /* Header */
        .bl-header{
            display:flex;
            align-items:center;
            gap:14px;
            margin-bottom:1.75rem;
        }
        .bl-back-btn{
            width:40px;height:40px;
            border-radius:50%;
            display:flex;align-items:center;justify-content:center;
            background:var(--bl-surface);
            border:1px solid var(--bl-line);
            color:var(--bl-indigo);
            text-decoration:none;
            box-shadow:0 2px 8px rgba(79,70,229,0.08);
            transition:background .2s ease, transform .15s ease, box-shadow .2s ease;
            flex-shrink:0;
        }
        .bl-back-btn:hover{
            background:var(--bl-indigo);
            color:#fff;
            transform:translateX(-2px);
            box-shadow:0 4px 14px rgba(79,70,229,0.28);
        }
        .bl-title{
            margin:0;
            font-size:1.2rem;
            font-weight:800;
            letter-spacing:.2px;
            display:flex;
            align-items:center;
            gap:10px;
            color:var(--bl-text);
        }
        .bl-title-icon{
            width:34px;height:34px;
            border-radius:10px;
            background:linear-gradient(135deg, var(--bl-indigo), var(--bl-purple));
            color:#fff;
            display:flex;align-items:center;justify-content:center;
            font-size:.95rem;
            box-shadow:0 4px 10px rgba(124,58,237,0.3);
        }
        .bl-subtitle{
            margin:2px 0 0;
            font-size:.8rem;
            color:var(--bl-muted);
        }

        /* Card */
        .bl-card{
            background:var(--bl-surface);
            border:1px solid var(--bl-line);
            border-radius:var(--bl-radius);
            overflow:hidden;
            box-shadow:0 18px 40px -22px rgba(79,70,229,0.28);
        }
        .bl-card-body{
            padding:8px;
        }

        /* Row item */
        .bl-item{
            display:flex;
            align-items:center;
            gap:14px;
            padding:14px 10px;
            border-bottom:1px solid var(--bl-line);
            transition:background .18s ease;
            border-radius:14px;
        }
        .bl-item:last-child{ border-bottom:none; }
        .bl-item:hover{ background:var(--bl-surface-2); }

        .bl-avatar{
            width:46px;height:46px;
            border-radius:50%;
            overflow:hidden;
            flex-shrink:0;
            background:linear-gradient(135deg, var(--bl-indigo), var(--bl-purple));
            display:flex;align-items:center;justify-content:center;
            font-weight:700;
            font-size:1rem;
            color:#fff;
            box-shadow:0 3px 10px rgba(124,58,237,0.25);
        }
        .bl-avatar img{
            width:100%;height:100%;object-fit:cover;
        }

        .bl-info{ flex:1; min-width:0; }
        .bl-name{
            margin:0;
            font-size:.95rem;
            font-weight:700;
            color:var(--bl-text);
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
        }
        .bl-name-link{
            color:inherit;
            text-decoration:none;
        }
        .bl-name-link:hover{
            color:var(--bl-indigo);
            text-decoration:underline;
        }
        .bl-role{
            margin:2px 0 0;
            font-size:.78rem;
            color:var(--bl-muted);
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
        }

        /* Buttons */
        .bl-btn{
            border:none;
            border-radius:999px;
            font-size:.8rem;
            font-weight:600;
            padding:8px 16px;
            display:inline-flex;
            align-items:center;
            gap:6px;
            white-space:nowrap;
            cursor:pointer;
            transition:transform .15s ease, background .2s ease, box-shadow .2s ease, opacity .2s ease;
        }
        .bl-btn:active{ transform:scale(0.96); }
        .bl-btn:disabled{ opacity:.55; cursor:default; transform:none; }

        .bl-btn-unblock{
            background:var(--bl-danger-soft);
            color:var(--bl-danger);
            border:1px solid rgba(225,29,72,0.18);
        }
        .bl-btn-unblock:hover:not(:disabled){
            background:var(--bl-danger);
            color:#fff;
            box-shadow:0 4px 12px rgba(225,29,72,0.25);
        }

        .bl-btn-add{
            background:linear-gradient(135deg, var(--bl-indigo), var(--bl-purple));
            color:#fff;
            box-shadow:0 4px 12px rgba(124,58,237,0.3);
        }
        .bl-btn-add:hover:not(:disabled){
            filter:brightness(1.06);
            box-shadow:0 6px 16px rgba(124,58,237,0.38);
        }

        .bl-btn-pending{
            background:var(--bl-surface-2);
            color:var(--bl-muted);
            border:1px dashed var(--bl-line);
            cursor:default;
        }

        /* Empty state */
        .bl-empty{
            text-align:center;
            padding:64px 20px;
        }
        .bl-empty-icon{
            width:66px;height:66px;
            border-radius:50%;
            background:var(--bl-surface-2);
            color:var(--bl-indigo);
            display:flex;align-items:center;justify-content:center;
            margin:0 auto 16px;
            font-size:1.7rem;
            box-shadow:inset 0 0 0 1px var(--bl-line);
        }
        .bl-empty p{
            margin:0;
            color:var(--bl-muted);
            font-size:.9rem;
        }

        /* Pagination */
        .bl-pagination-wrap{
            margin-top:1.25rem;
            display:flex;
            justify-content:center;
        }
        .bl-pagination-wrap :is(ul.pagination){
            margin:0;
        }
        .bl-pagination-wrap .page-link{
            background:var(--bl-surface);
            border-color:var(--bl-line);
            color:var(--bl-indigo);
        }
        .bl-pagination-wrap .page-item.active .page-link{
            background:linear-gradient(135deg, var(--bl-indigo), var(--bl-purple));
            border-color:transparent;
            color:#fff;
        }

        @media (max-width:576px){
            .bl-item{ gap:10px; padding:12px 6px; }
            .bl-avatar{ width:40px;height:40px;font-size:.9rem; }
            .bl-btn{ padding:7px 12px; font-size:.75rem; }
        }
    </style>
</head>
<body class="bl-page">

<div class="bl-container mt-3">

            {{-- Header --}}
            <div class="bl-header">
                <a href="{{ route('friends.index') }}" class="bl-back-btn">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <div>
                    <h5 class="bl-title">
                        <span class="bl-title-icon"><i class="bi bi-slash-circle"></i></span>
                        Block List
                    </h5>
                    <p class="bl-subtitle">People you've blocked can't find or message you</p>
                </div>
            </div>

            <div class="bl-card">
                <div class="bl-card-body" id="blockedListZone">
                    @forelse($blocked as $b)
                        @php $u = $b->receiver; @endphp
                        @if($u)
                        <div class="bl-item" id="blocked-{{ $u->id }}">
                            <a href="{{ route('profile.view', $u->id) }}" class="bl-avatar">
                                @if($u->profile_picture)
                                    <img src="{{ asset('storage/'.$u->profile_picture) }}" alt="{{ $u->name }}">
                                @else
                                    {{ strtoupper(substr($u->name,0,1)) }}
                                @endif
                            </a>
                            <div class="bl-info">
                                <p class="bl-name">
                                    <a href="{{ route('profile.view', $u->id) }}" class="bl-name-link">{{ $u->name }}</a>
                                </p>
                                <p class="bl-role">
                                    @if($u->department || $u->section)
                                        {{ $u->department }}@if($u->department && $u->section) · @endif{{ $u->section }}
                                    @else
                                        {{ ucfirst($u->role) }}
                                    @endif
                                </p>
                            </div>
                            <div id="blockedActionZone-{{ $u->id }}">
                                <button type="button" class="bl-btn bl-btn-unblock" onclick="unblockUser({{ $u->id }}, '{{ e($u->name) }}', this)">
                                    <i class="bi bi-slash-circle"></i> Unblock
                                </button>
                            </div>
                        </div>
                        @endif
                    @empty
                        <div class="bl-empty" id="blockedEmptyState">
                            <div class="bl-empty-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <p>You haven't blocked anyone yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            @if($blocked->hasPages())
                <div class="bl-pagination-wrap">
                    {{ $blocked->links() }}
                </div>
            @endif

</div>

<script>
function getCsrfToken() {
    var tag = document.querySelector('meta[name="csrf-token"]');
    return tag ? tag.content : '';
}

function unblockUser(userId, name, btnEl) {
    if (!confirm('Unblock ' + name + '? They will be able to find and add you again.')) return;
    btnEl.disabled = true;

    fetch('{{ route("friends.unblock") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify({ user_id: userId })
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        if (!d.success) {
            btnEl.disabled = false;
            alert(d.message || 'Something went wrong.');
            return;
        }

        // বাটনটাকে "Add Friend" এ পরিবর্তন করো — unblock হলে সাথে সাথে ফ্রেন্ড রিকোয়েস্ট পাঠানোর সুযোগ
        var zone = document.getElementById('blockedActionZone-' + userId);
        if (zone) {
            zone.innerHTML = '<button type="button" class="bl-btn bl-btn-add" onclick="sendFriendFromBlocked(' + userId + ', this)">'
                + '<i class="bi bi-person-plus-fill"></i> Add Friend</button>';
        }

        if (typeof Swal !== 'undefined') {
            Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, timerProgressBar: true })
                .fire({ icon: 'success', title: d.message || 'User unblocked' });
        }
    })
    .catch(function () {
        btnEl.disabled = false;
        alert('Network error.');
    });
}

function sendFriendFromBlocked(userId, btnEl) {
    btnEl.disabled = true;

    fetch('{{ route("friends.send") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify({ user_id: userId })
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        if (!d.success) {
            btnEl.disabled = false;
            alert(d.message || 'Could not send request.');
            return;
        }

        var zone = document.getElementById('blockedActionZone-' + userId);
        if (zone) {
            zone.innerHTML = '<span class="bl-btn bl-btn-pending">'
                + '<i class="bi bi-person-check-fill"></i> Request Sent</span>';
        }

        if (typeof Swal !== 'undefined') {
            Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, timerProgressBar: true })
                .fire({ icon: 'success', title: d.message || 'Friend request sent!' });
        }
    })
    .catch(function () {
        btnEl.disabled = false;
        alert('Network error.');
    });
}
</script>

</body>
</html>