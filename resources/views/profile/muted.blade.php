<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Muted Accounts · Borobhai.online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background:#f3f4f8; font-family:-apple-system,BlinkMacSystemFont,sans-serif; }
        .wrap { max-width:640px; margin:30px auto; padding:0 16px; }
        .card-box { background:#fff; border-radius:16px; box-shadow:0 1px 3px rgba(16,24,40,.06); padding:20px; }
        .muted-row { display:flex; align-items:center; gap:12px; padding:14px 0; border-bottom:1px solid #eceef1; }
        .muted-row:last-child { border-bottom:none; }
        .av { width:44px; height:44px; border-radius:50%; background:linear-gradient(135deg,#4f46e5,#7c73f0); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; overflow:hidden; flex-shrink:0; }
        .av img { width:100%; height:100%; object-fit:cover; }
        .name { font-weight:700; font-size:14.5px; color:#1e1f24; }
        .sub { font-size:12px; color:#6b7280; }
        .unmute-btn { border:1.5px solid #4f46e5; background:#fff; color:#4f46e5; border-radius:9px; padding:7px 16px; font-size:13px; font-weight:600; cursor:pointer; transition:all .15s; }
        .unmute-btn:hover { background:#4f46e5; color:#fff; }
        .empty { text-align:center; padding:50px 20px; color:#6b7280; }
        .empty i { font-size:40px; opacity:.5; display:block; margin-bottom:10px; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="d-flex align-items-center gap-2 mb-3">
        <a href="{{ url()->previous() }}" class="btn btn-light btn-sm rounded-circle"><i class="bi bi-arrow-left"></i></a>
        <h5 class="fw-bold mb-0">Muted Accounts</h5>
    </div>

    <div class="card-box">
        <div id="mutedList">
            @forelse($mutedUsers as $u)
                <div class="muted-row" id="muted-row-{{ $u['id'] }}">
                    <div class="av">
                        @if($u['profile_picture'])
                            <img src="{{ asset('storage/'.$u['profile_picture']) }}">
                        @else
                            {{ strtoupper(substr($u['name'], 0, 1)) }}
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <div class="name">{{ $u['name'] }}</div>
                        <div class="sub">
                            {{ ucfirst($u['role']) }}
                            @if($u['muted_until'])
                                · Muted until {{ \Carbon\Carbon::parse($u['muted_until'])->format('d M Y') }}
                            @endif
                        </div>
                    </div>
                    <button class="unmute-btn" onclick="unmuteUser({{ $u['id'] }}, this)">
                        <i class="bi bi-volume-up-fill me-1"></i> Unmute
                    </button>
                </div>
            @empty
                <div class="empty" id="mutedEmpty">
                    <i class="bi bi-volume-mute"></i>
                    <p class="fw-semibold mb-0">No muted accounts</p>
                    <p class="small">Accounts you mute from reports will appear here.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function unmuteUser(userId, btn) {
    btn.disabled = true;
    const orig = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

    fetch(`/muted-accounts/${userId}/unmute`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(d => {
        if (!d.success) {
            btn.disabled = false; btn.innerHTML = orig;
            Swal.fire({ icon: 'error', title: d.message || 'Failed' });
            return;
        }
        const row = document.getElementById('muted-row-' + userId);
        if (row) { row.style.opacity = '0'; setTimeout(() => {
            row.remove();
            if (!document.querySelector('.muted-row')) {
                document.getElementById('mutedList').innerHTML = `
                    <div class="empty">
                        <i class="bi bi-volume-mute"></i>
                        <p class="fw-semibold mb-0">No muted accounts</p>
                        <p class="small">Accounts you mute from reports will appear here.</p>
                    </div>`;
            }
        }, 250); }
        Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:2000 })
            .fire({ icon:'success', title: d.message });
    })
    .catch(() => {
        btn.disabled = false; btn.innerHTML = orig;
        Swal.fire({ icon:'error', title:'Network error' });
    });
}
</script>
</body>
</html>