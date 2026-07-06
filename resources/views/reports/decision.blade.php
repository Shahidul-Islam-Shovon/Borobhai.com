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

        .post-card-mini { padding: 18px 22px; }
        .pcm-author { display:flex; align-items:center; gap:12px; margin-bottom:12px; }
        .pcm-avatar {
            width:44px; height:44px; border-radius:50%; overflow:hidden; flex-shrink:0;
            background: linear-gradient(135deg,#4f46e5,#7c73f0); color:#fff;
            display:flex; align-items:center; justify-content:center; font-weight:700; font-size:1rem;
        }
        .pcm-avatar img { width:100%; height:100%; object-fit:cover; }
        .pcm-name { font-weight:700; font-size:0.92rem; color:var(--bb-text); display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
        .pcm-role { font-size:0.62rem; font-weight:700; padding:2px 8px; border-radius:20px; }
        .pcm-role.rb-alumni  { background:#fef3c7; color:#d97706; }
        .pcm-role.rb-teacher { background:#f3e8ff; color:#7c3aed; }
        .pcm-role.rb-student { background:#eef2ff; color:#4f46e5; }
        .pcm-meta { font-size:0.72rem; color:var(--bb-muted); margin-top:2px; }
        .pcm-content { font-size:0.9rem; line-height:1.6; color:var(--bb-text); white-space:pre-wrap; word-break:break-word; margin-bottom:12px; }
        .pcm-media { width:100%; max-height:420px; object-fit:cover; border-radius:12px; margin-bottom:10px; display:block; }
        .pcm-footer { display:flex; gap:18px; align-items:center; padding-top:12px; border-top:1px solid #f1f5f9; font-size:0.78rem; color:var(--bb-muted); font-weight:600; }

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
        .status-hero.success-hero { background: linear-gradient(135deg, #16a34a, #22c55e); box-shadow: 0 10px 30px -8px rgba(22,163,74,.4); }
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
    // ✅ appeal reviewed হলে সবসময় সবচেয়ে বেশি priority পাবে
    if ($report->appeal_status === 'reviewed') {
        $heroClass = 'success-hero';
        $heroIcon  = 'bi-check-circle-fill';
        $heroTitle = 'Appeal Approved — Everything Restored';
    } elseif ($report->action_taken === 'deleted') {
        $heroClass = 'deleted';
        $heroIcon  = 'bi-trash3-fill';
        $heroTitle = 'Your content was removed';
    } elseif ($report->action_taken === 'warned') {
        $heroClass = 'warned';
        $heroIcon  = 'bi-exclamation-triangle-fill';
        $heroTitle = 'You received a warning';
    } else {
        $heroClass = '';
        $heroIcon  = 'bi-shield-check';
        $heroTitle = 'Report Update';
    }
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
    {{-- Reported Post Card (real feed-style card) --}}
@if($contentType === 'post' && $post)
<div class="card">
    <div class="card-head">
        <i class="bi bi-file-text text-primary"></i> Reported Post
        @if($isDeleted)
            <span class="badge-pill bp-del ms-auto"><i class="bi bi-trash3"></i> Deleted</span>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="post-card-mini">
            <div class="pcm-author">
                <div class="pcm-avatar">
                    @if($post->user?->profile_picture)
                        <img src="{{ asset('storage/'.$post->user->profile_picture) }}">
                    @else
                        {{ strtoupper(substr($post->user?->name ?? '?', 0, 1)) }}
                    @endif
                </div>
                <div class="flex-grow-1">
                    <div class="pcm-name">
                        {{ $post->user?->name ?? 'Deleted User' }}
                        @if($post->user)
                        @php
                            $roleClass = match($post->user->role) { 'alumni'=>'rb-alumni', 'teacher'=>'rb-teacher', default=>'rb-student' };
                        @endphp
                        <span class="pcm-role {{ $roleClass }}">{{ ucfirst($post->user->role) }}</span>
                        @endif
                    </div>
                    <div class="pcm-meta">
                        {{ $post->user?->email ?? '—' }} &nbsp;·&nbsp; {{ $post->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>

            @if($post->content)
            <div class="pcm-content">{{ $post->content }}</div>
            @endif

            @php
                $images = $post->images ?? [];
                $videos = [];
                if (!empty($post->video) && $post->video !== 'null') {
                    $videos = is_array($post->video) ? $post->video : (json_decode($post->video, true) ?: [$post->video]);
                }
            @endphp

            @if(!empty($videos))
                @foreach($videos as $vid)
                <video src="{{ asset('storage/'.$vid) }}" controls class="pcm-media"></video>
                @endforeach
            @endif
            @if(!empty($images))
                @foreach($images as $img)
                <img src="{{ asset('storage/'.$img) }}" class="pcm-media" alt="media">
                @endforeach
            @endif

            <div class="pcm-footer">
                <span><i class="bi bi-heart-fill text-danger"></i> {{ $post->likes_count ?? 0 }} Likes</span>
                <span><i class="bi bi-chat-fill text-primary"></i> {{ $post->comments_count ?? 0 }} Comments</span>
                <span class="ms-auto text-muted">Post ID #{{ $post->id }}</span>
            </div>
        </div>
    </div>
</div>
@elseif($contentType === 'job' && $job)
<div class="card">
    <div class="card-head">
        <i class="bi bi-briefcase text-primary"></i> Reported Job
        @if($isDeleted)
            <span class="badge-pill bp-del ms-auto"><i class="bi bi-trash3"></i> Deleted</span>
        @endif
    </div>
    <div class="card-body">
        <div class="content-preview mb-3">
            <strong>{{ $job->title }}</strong><br>
            <span class="text-muted" style="font-size:0.85rem;">{{ $job->company }}</span>
        </div>
        @if(!$isDeleted)
        <a href="{{ route('jobs.show', $job) }}" target="_blank" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-box-arrow-up-right me-1"></i> View Full Job Details
        </a>
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
            <div class="alert alert-info alert-soft mb-2">
                <i class="bi bi-hourglass-split me-1"></i>
                @if($isAdminViewer)
                    This user has submitted an appeal. Review the message below.
                @else
                    Your appeal has been submitted. An admin will review it shortly.
                @endif
            </div>
            @if($report->appeal_message)
            <div class="content-preview mb-3" style="font-size:0.85rem;">{{ $report->appeal_message }}</div>
            @endif

            @if($isAdminViewer)
            <button class="btn btn-appeal" onclick="markReviewedFromDecision()">
                <i class="bi bi-check-circle-fill me-1"></i> Accept Appeal & Restore
            </button>
            @endif

        @elseif($report->appeal_status === 'reviewed')
            <div class="alert alert-success alert-soft mb-0">
                <i class="bi bi-check-circle-fill me-1"></i> This appeal has been reviewed and resolved. Everything has been restored.
            </div>
        @elseif(!$isAdminViewer)
            <p class="text-muted mb-2" style="font-size:0.85rem;">If you believe this decision is a mistake, you can submit an appeal below.</p>
            <form id="appealForm">
                <textarea id="appealMessage" class="form-control mb-3" rows="4" placeholder="Explain why you think this decision is incorrect..."></textarea>
                <button type="submit" class="btn btn-appeal">
                    <i class="bi bi-send-fill me-1"></i> Submit Appeal
                </button>
            </form>
        @else
            <p class="text-muted mb-0" style="font-size:0.85rem;">No appeal has been submitted for this report yet.</p>
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

function markReviewedFromDecision() {
    if (!confirm('Accept this appeal? The report will be cancelled and everything restored.')) return;
    fetch("/admin/reports/{{ $report->id }}/mark-reviewed", {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(r => r.json())
    .then(d => { alert(d.message); if (d.success) location.reload(); })
    .catch(() => alert('Network error.'));
}


</script>
</body>
</html>