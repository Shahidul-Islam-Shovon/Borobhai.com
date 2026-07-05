<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Report Decision — Borobhai</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bb-primary: #4f46e5;
            --bb-primary-soft: #eef2ff;
            --bb-surface: #f4f5fb;
            --bb-border: #e5e7eb;
            --bb-text: #111827;
            --bb-muted: #6b7280;
            --bb-danger: #dc2626;
            --bb-warning: #d97706;
            --bb-success: #16a34a;
        }
        * { box-sizing: border-box; }
        body { background: var(--bb-surface); font-family: 'Segoe UI', system-ui, sans-serif; color: var(--bb-text); min-height: 100vh; }

        .topbar { background:#fff; border-bottom:1px solid var(--bb-border); padding:16px 28px; display:flex; align-items:center; gap:12px; }
        .topbar-brand { font-weight:800; color:var(--bb-primary); font-size:1.05rem; }

        .wrap { max-width: 680px; margin: 32px auto 80px; padding: 0 16px; }

        .status-hero {
            border-radius: 18px; padding: 26px 28px; margin-bottom: 20px;
            display:flex; align-items:center; gap:16px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color:#fff; box-shadow: 0 10px 30px -8px rgba(79,70,229,.45);
        }
        .status-hero.deleted { background: linear-gradient(135deg, #dc2626, #ef4444); box-shadow: 0 10px 30px -8px rgba(220,38,38,.4); }
        .status-hero.warned  { background: linear-gradient(135deg, #d97706, #f59e0b); box-shadow: 0 10px 30px -8px rgba(217,119,6,.4); }
        .status-hero .icon-badge { width:52px; height:52px; border-radius:14px; background:rgba(255,255,255,.18); display:flex; align-items:center; justify-content:center; font-size:1.5rem; flex-shrink:0; }
        .status-hero h4 { margin:0; font-size:1.05rem; font-weight:800; }
        .status-hero p { margin:2px 0 0; font-size:0.82rem; opacity:.92; }

        .card { background:#fff; border:1px solid var(--bb-border); border-radius:16px; margin-bottom:18px; overflow:hidden; box-shadow:0 2px 14px rgba(15,23,42,.04); }
        .card-head { padding:16px 22px; border-bottom:1px solid #f1f5f9; font-weight:700; font-size:0.86rem; display:flex; align-items:center; gap:8px; color:var(--bb-text); }
        .card-body { padding:20px 22px; }

        .meta-row { display:flex; justify-content:space-between; padding:8px 0; font-size:0.84rem; border-bottom:1px dashed #f1f5f9; }
        .meta-row:last-child { border-bottom:none; }
        .meta-row .k { color:var(--bb-muted); font-weight:600; }
        .meta-row .v { color:var(--bb-text); font-weight:600; text-align:right; }

        .content-preview { background:#f8fafc; border:1px solid #eef1f6; border-radius:12px; padding:16px; font-size:0.92rem; line-height:1.6; white-space:pre-wrap; word-break:break-word; }
        .media-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(110px,1fr)); gap:8px; margin-top:12px; }
        .media-grid img { width:100%; height:90px; object-fit:cover; border-radius:8px; border:1px solid #eef1f6; }

        .note-block { background:#fff7ed; border:1px solid #fed7aa; border-radius:12px; padding:16px 18px; font-size:0.88rem; color:#7c2d12; position:relative; }
        .note-block .tag { position:absolute; top:-10px; left:14px; background:#d97706; color:#fff; font-size:0.62rem; font-weight:800; padding:3px 10px; border-radius:6px; text-transform:uppercase; letter-spacing:.4px; }

        .badge-pill { display:inline-flex; align-items:center; gap:5px; font-size:0.68rem; font-weight:700; padding:4px 10px; border-radius:20px; text-transform:uppercase; letter-spacing:.3px; }
        .bp-warn { background:#fff7ed; color:#d97706; border:1px solid #fed7aa; }
        .bp-del  { background:#fef2f2; color:#dc2626; border:1px solid #fca5a5; }
        .bp-ok   { background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0; }

        textarea.form-control { border-radius:10px; border:1.5px solid #e5e7eb; font-size:0.88rem; }
        textarea.form-control:focus { border-color:var(--bb-primary); box-shadow:0 0 0 3px rgba(79,70,229,.12); }
        .btn-appeal { background:var(--bb-primary); color:#fff; border:none; border-radius:10px; padding:10px 20px; font-weight:700; font-size:0.85rem; }
        .btn-appeal:hover { background:#4338ca; color:#fff; }

        .alert-soft { border-radius:12px; font-size:0.85rem; border:none; }
    </style>
</head>
<body>

<div class="topbar">
    <span class="topbar-brand"><i class="bi bi-shield-check me-1"></i> Borobhai</span>
    <span class="text-muted" style="font-size:0.8rem;">· Report Decision Center</span>
</div>

<div class="wrap">

    {{-- Hero status --}}
    @php
        $heroClass = $report->action_taken === 'deleted' ? 'deleted' : ($report->action_taken === 'warned' ? 'warned' : '');
        $heroIcon  = $report->action_taken === 'deleted' ? 'bi-trash3-fill' : ($report->action_taken === 'warned' ? 'bi-exclamation-triangle-fill' : 'bi-shield-check');
        $heroTitle = match($report->action_taken) {
            'deleted' => 'Your content was removed',
            'warned'  => 'You received a warning',
            'reviewed_note' => 'Admin reviewed your report',
            default => 'Report Update',
        };
    @endphp
    <div class="status-hero {{ $heroClass }}">
        <div class="icon-badge"><i class="bi {{ $heroIcon }}"></i></div>
        <div>
            <h4>{{ $heroTitle }}</h4>
            <p>Report #{{ $report->id }} · {{ ucfirst($contentType) }} · {{ $report->created_at->format('d M Y, h:i A') }}</p>
        </div>
    </div>

    {{-- Report meta --}}
    <div class="card">
        <div class="card-head"><i class="bi bi-info-circle text-primary"></i> Report Summary</div>
        <div class="card-body">
            <div class="meta-row"><span class="k">Type</span><span class="v">{{ ucfirst($contentType) }}</span></div>
            <div class="meta-row"><span class="k">Reason</span><span class="v">{{ ucfirst($report->reason ?? '-') }}</span></div>
            @if($report->details)
            <div class="meta-row"><span class="k">Details</span><span class="v">{{ $report->details }}</span></div>
            @endif
            <div class="meta-row">
                <span class="k">Status</span>
                <span class="v">
                    @if($report->action_taken === 'warned')
                        <span class="badge-pill bp-warn"><i class="bi bi-exclamation-triangle"></i> Warned</span>
                    @elseif($report->action_taken === 'deleted')
                        <span class="badge-pill bp-del"><i class="bi bi-trash3"></i> Content Deleted</span>
                    @else
                        <span class="badge-pill bp-ok"><i class="bi bi-check-circle"></i> Reviewed</span>
                    @endif
                </span>
            </div>
        </div>
    </div>

    {{-- Content preview --}}
    @if($content)
    <div class="card">
        <div class="card-head">
            <i class="bi bi-file-text text-primary"></i> Reported Content
            @if($isDeleted)
                <span class="badge-pill bp-del ms-auto"><i class="bi bi-trash3"></i> Deleted</span>
            @endif
        </div>
        <div class="card-body">
            <div class="content-preview">{{ $content }}</div>
            @if(!empty($images))
            <div class="media-grid">
                @foreach($images as $img)
                    <img src="{{ asset('storage/'.$img) }}" alt="media">
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Admin note --}}
    @if($report->admin_note)
    <div class="card">
        <div class="card-head"><i class="bi bi-chat-left-quote text-warning"></i> Admin's Note</div>
        <div class="card-body">
            <div class="note-block">
                <span class="tag">Admin</span>
                {{ $report->admin_note }}
            </div>
        </div>
    </div>
    @endif

    {{-- Appeal section --}}
    <div class="card">
        <div class="card-head"><i class="bi bi-megaphone text-primary"></i> Appeal</div>
        <div class="card-body">
            @if($report->appeal_status === 'pending')
                <div class="alert alert-info alert-soft mb-0">
                    <i class="bi bi-hourglass-split me-1"></i> Your appeal has been submitted. An admin will review it shortly.
                </div>
                @if($report->appeal_message)
                <div class="content-preview mt-3" style="font-size:0.85rem;">{{ $report->appeal_message }}</div>
                @endif
            @elseif($report->appeal_status === 'reviewed')
                <div class="alert alert-success alert-soft mb-0">
                    <i class="bi bi-check-circle-fill me-1"></i> Your appeal has been reviewed and resolved. Everything has been restored.
                </div>
            @else
                <p class="text-muted mb-2" style="font-size:0.85rem;">If you believe this decision is a mistake, you can submit an appeal below.</p>
                <form id="appealForm">
                    <textarea id="appealMessage" class="form-control mb-3" rows="4" placeholder="Explain why you think this decision is incorrect..."></textarea>
                    <button type="submit" class="btn btn-appeal">
                        <i class="bi bi-send-fill me-1"></i> Submit Appeal
                    </button>
                </form>
            @endif
        </div>
    </div>

</div>

<script>
document.getElementById('appealForm')?.addEventListener('submit', function(e){
    e.preventDefault();
    const btn = e.target.querySelector('button');
    const msg = document.getElementById('appealMessage').value.trim();
    if (!msg) return alert('Please write a reason.');

    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Sending...';

    fetch("{{ route('reports.appeal', $report->hashid) }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ message: msg })
    })
    .then(r => r.json())
    .then(d => {
        alert(d.message);
        if (d.success) location.reload();
        else { btn.disabled = false; btn.innerHTML = '<i class="bi bi-send-fill me-1"></i> Submit Appeal'; }
    })
    .catch(() => {
        alert('Network error.');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-send-fill me-1"></i> Submit Appeal';
    });
});
</script>
</body>
</html>