<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Post #{{ $post->id }} · Borobhai</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bb-primary: #4f46e5;
            --bb-primary-soft: #eef2ff;
            --bb-surface: #f8f9fc;
            --bb-card: #ffffff;
            --bb-border: #e5e7eb;
            --bb-text: #111827;
            --bb-muted: #6b7280;
            --bb-danger: #dc2626;
            --bb-warning: #d97706;
            --bb-success: #16a34a;
        }

        * { box-sizing: border-box; }

        body {
            background: var(--bb-surface);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: var(--bb-text);
            min-height: 100vh;
        }

        /* ── Top bar ── */
        .ps-topbar {
            background: #fff;
            border-bottom: 1px solid var(--bb-border);
            padding: 14px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .ps-back {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--bb-primary);
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            padding: 7px 14px;
            border-radius: 8px;
            background: var(--bb-primary-soft);
            transition: background .15s;
        }
        .ps-back:hover { background: #e0e7ff; color: var(--bb-primary); }
        .ps-topbar-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--bb-text);
        }
        .ps-topbar-sub {
            font-size: 0.75rem;
            color: var(--bb-muted);
            margin-left: 4px;
        }

        /* ── Layout ── */
        .ps-layout {
            max-width: 780px;
            margin: 32px auto;
            padding: 0 16px 80px;
        }

        /* ── Report badge ── */
        .ps-report-banner {
            background: #fef2f2;
            border: 1.5px solid #fecaca;
            border-radius: 12px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }
        .ps-report-banner i { color: var(--bb-danger); font-size: 1.2rem; }
        .ps-report-banner strong { color: var(--bb-danger); font-size: 0.82rem; }
        .ps-report-banner span { color: #991b1b; font-size: 0.78rem; }

        /* ── Post card ── */
        .ps-card {
            background: var(--bb-card);
            border: 1px solid var(--bb-border);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
        }

        /* Author row */
        .ps-author {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 22px 16px;
            border-bottom: 1px solid #f3f4f6;
        }
        .ps-avatar {
            width: 46px; height: 46px;
            border-radius: 50%;
            object-fit: cover;
            background: linear-gradient(135deg, #4f46e5, #7c73f0);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            flex-shrink: 0;
            overflow: hidden;
        }
        .ps-author-name {
            font-weight: 700;
            font-size: 0.92rem;
            color: var(--bb-text);
            text-decoration: none;
        }
        .ps-author-name:hover { color: var(--bb-primary); }
        .ps-author-meta {
            font-size: 0.72rem;
            color: var(--bb-muted);
            margin-top: 2px;
        }
        .ps-role-badge {
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 20px;
            margin-left: 6px;
            display: inline-flex;
            align-items: center;
            gap: 3px;
        }
        .rb-alumni  { background: #fef3c7; color: #d97706; }
        .rb-teacher { background: #f3e8ff; color: #7c3aed; }
        .rb-student { background: #eef2ff; color: #4f46e5; }

        /* Content */
        .ps-content {
            padding: 20px 22px;
            font-size: 0.94rem;
            line-height: 1.7;
            color: var(--bb-text);
            white-space: pre-wrap;
            word-break: break-word;
        }

        /* Media grid */
        .ps-media {
            padding: 0 22px 20px;
        }
        .ps-media-grid {
            display: grid;
            gap: 4px;
            border-radius: 12px;
            overflow: hidden;
        }
        .ps-media-grid.count-1 { grid-template-columns: 1fr; }
        .ps-media-grid.count-2 { grid-template-columns: 1fr 1fr; }
        .ps-media-grid.count-3 { grid-template-columns: 1fr 1fr; }
        .ps-media-grid.count-3 .ps-media-item:first-child { grid-column: 1 / -1; }
        .ps-media-grid.count-4 { grid-template-columns: 1fr 1fr; }
        .ps-media-item {
            aspect-ratio: 4/3;
            overflow: hidden;
            background: #f3f4f6;
            position: relative;
        }
        .ps-media-item img, .ps-media-item video {
            width: 100%; height: 100%;
            object-fit: cover;
        }

        /* Stats bar */
        .ps-stats {
            display: flex;
            gap: 20px;
            padding: 14px 22px;
            border-top: 1px solid #f3f4f6;
            border-bottom: 1px solid #f3f4f6;
        }
        .ps-stat {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.78rem;
            color: var(--bb-muted);
            font-weight: 600;
        }
        .ps-stat i { font-size: 0.9rem; }
        .ps-stat.likes i { color: #ef4444; }
        .ps-stat.comments i { color: var(--bb-primary); }
        .ps-stat.shares i { color: var(--bb-success); }

        /* Admin actions */
        .ps-actions {
            padding: 16px 22px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .ps-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.78rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all .15s;
            text-decoration: none;
        }
        .ps-btn-warn    { background: #fef3c7; color: #d97706; }
        .ps-btn-warn:hover { background: #fde68a; color: #b45309; }
        .ps-btn-ban     { background: #ffe4e6; color: #e11d48; }
        .ps-btn-ban:hover { background: #fecdd3; }
        .ps-btn-delete  { background: var(--bb-danger); color: #fff; }
        .ps-btn-delete:hover { background: #b91c1c; }
        .ps-btn-dismiss { background: #f0fdf4; color: var(--bb-success); }
        .ps-btn-dismiss:hover { background: #dcfce7; }
        .ps-btn-profile { background: var(--bb-primary-soft); color: var(--bb-primary); }
        .ps-btn-profile:hover { background: #e0e7ff; }

        /* Comments section */
        .ps-comments {
            background: var(--bb-card);
            border: 1px solid var(--bb-border);
            border-radius: 16px;
            margin-top: 16px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
        }
        .ps-comments-head {
            padding: 16px 22px;
            font-weight: 700;
            font-size: 0.88rem;
            border-bottom: 1px solid #f3f4f6;
            color: var(--bb-text);
        }
        .ps-comment-item {
            display: flex;
            gap: 10px;
            padding: 14px 22px;
            border-bottom: 1px solid #f9fafb;
        }
        .ps-comment-item:last-child { border-bottom: none; }
        .ps-comment-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #7c73f0);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.75rem;
            flex-shrink: 0; overflow: hidden;
        }
        .ps-comment-name { font-weight: 700; font-size: 0.78rem; color: var(--bb-text); }
        .ps-comment-text { font-size: 0.82rem; color: #374151; margin-top: 3px; line-height: 1.5; }
        .ps-comment-time { font-size: 0.68rem; color: var(--bb-muted); margin-top: 4px; }
        .ps-empty-comments { padding: 24px; text-align: center; color: var(--bb-muted); font-size: 0.82rem; }

        /* Toast */
        .ps-toast {
            position: fixed; bottom: 24px; right: 24px;
            background: #1f2937; color: #fff;
            padding: 12px 20px; border-radius: 10px;
            font-size: 0.82rem; font-weight: 600;
            display: flex; align-items: center; gap: 8px;
            z-index: 9999; transform: translateY(80px); opacity: 0;
            transition: all .3s cubic-bezier(.34,1.56,.64,1);
            max-width: 320px;
        }
        .ps-toast.show { transform: translateY(0); opacity: 1; }
        .ps-toast.success { border-left: 4px solid var(--bb-success); }
        .ps-toast.error   { border-left: 4px solid var(--bb-danger); }
    </style>
</head>
<body>

{{-- Top bar --}}
<div class="ps-topbar">
    <a href="{{ url('http://127.0.0.1:8000/admin/dashboard') }}" class="ps-back">
        <i class="bi bi-arrow-left"></i> Back
    </a>
    <div>
        <span class="ps-topbar-title">Post Review</span>
        <span class="ps-topbar-sub">· ID #{{ $post->id }}</span>
    </div>
    <div class="ms-auto d-flex align-items-center gap-2">
        <span style="font-size:0.72rem;color:var(--bb-muted);">
            <i class="bi bi-clock me-1"></i>{{ $post->created_at->format('d M Y, h:i A') }}
        </span>
    </div>
</div>

<div class="ps-layout">

    {{-- Report banner (report দিয়ে এলে) --}}
    @if(isset($report))
    <div class="ps-report-banner">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>
            <strong>Reported Content &nbsp;·&nbsp; Reason: {{ ucfirst($report->reason) }}</strong><br>
            @if($report->details)
            <span>{{ $report->details }}</span>
            @endif
        </div>
        <span class="ms-auto" style="font-size:0.7rem;color:#991b1b;white-space:nowrap;">
            {{ $report->created_at->diffForHumans() }}
        </span>
    </div>
    @endif

    {{-- Main post card --}}
    <div class="ps-card">

        {{-- Author --}}
        <div class="ps-author">
            <div class="ps-avatar">
                @if($post->user->profile_picture)
                    <img src="{{ asset('storage/'.$post->user->profile_picture) }}">
                @else
                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                @endif
            </div>
            <div class="flex-grow-1">
                <div>
                    <a href="{{ route('profile.view', $post->user) }}" class="ps-author-name" target="_blank">
                        {{ $post->user->name }}
                    </a>
                    @php
                        $roleClass = match($post->user->role) { 'alumni'=>'rb-alumni', 'teacher'=>'rb-teacher', default=>'rb-student' };
                        $roleIcon  = match($post->user->role) { 'alumni'=>'bi-mortarboard-fill', 'teacher'=>'bi-easel2-fill', default=>'bi-backpack-fill' };
                    @endphp
                    <span class="ps-role-badge {{ $roleClass }}">
                        <i class="bi {{ $roleIcon }}"></i> {{ ucfirst($post->user->role) }}
                    </span>
                </div>
                <div class="ps-author-meta">
                    {{ $post->user->email }}
                    &nbsp;·&nbsp;
                    <i class="bi bi-clock" style="font-size:0.65rem;"></i>
                    {{ $post->created_at->diffForHumans() }}
                    @if($post->updated_at != $post->created_at)
                        &nbsp;·&nbsp;<i class="bi bi-pencil-square" style="font-size:0.65rem;"></i> Edited
                    @endif
                </div>
            </div>
        </div>

        {{-- Content --}}
        @if($post->content)
        <div class="ps-content">{{ $post->content }}</div>
        @endif

        {{-- Media --}}
        @php
            $postImages = $post->images ?? [];
            $postVideos = [];
            if (!empty($post->video) && $post->video !== 'null') {
                $postVideos = is_array($post->video)
                    ? $post->video
                    : (json_decode($post->video, true) ?: [$post->video]);
            }
            $mediaCount = count($postImages) + count($postVideos);
        @endphp
        @if($mediaCount > 0)
        <div class="ps-media">
            @php $gridCount = min($mediaCount, 4); @endphp
            <div class="ps-media-grid count-{{ $gridCount }}">
                @php $shown = 0; @endphp
                @foreach($postVideos as $vid)
                    @if($shown >= 4) @break @endif
                    <div class="ps-media-item">
                        <video src="{{ asset('storage/'.$vid) }}" controls></video>
                    </div>
                    @php $shown++; @endphp
                @endforeach
                @foreach($postImages as $img)
                    @if($shown >= 4) @break @endif
                    <div class="ps-media-item">
                        <img src="{{ asset('storage/'.$img) }}" alt="Media">
                    </div>
                    @php $shown++; @endphp
                @endforeach
            </div>
        </div>
        @endif

        {{-- Stats --}}
        <div class="ps-stats">
            <div class="ps-stat likes">
                <i class="bi bi-heart-fill"></i>
                {{ $post->likes_count ?? $post->likes()->count() }} Likes
            </div>
            <div class="ps-stat comments">
                <i class="bi bi-chat-fill"></i>
                {{ $post->comments_count ?? $post->comments()->count() }} Comments
            </div>
            @if(isset($post->shares_count))
            <div class="ps-stat shares">
                <i class="bi bi-share-fill"></i>
                {{ $post->shares_count }} Shares
            </div>
            @endif
            <div class="ps-stat ms-auto">
                <i class="bi bi-eye"></i>
                <span style="color:var(--bb-muted);">Post ID #{{ $post->id }}</span>
            </div>
        </div>

        {{-- Admin actions --}}
<div class="ps-actions">
    <a href="{{ route('profile.view', $post->user) }}" class="ps-btn ps-btn-profile" target="_blank">
        <i class="bi bi-person-fill"></i> View Profile
    </a>

    @if(isset($report))
    <button class="ps-btn ps-btn-warn" onclick="adminActionWithNote('warn', {{ $report->id }})">
        <i class="bi bi-exclamation-triangle"></i> Warn User
    </button>
    <button class="ps-btn ps-btn-ban" onclick="showSuspendModal({{ $post->user->id }})">
        <i class="bi bi-slash-circle"></i> Suspend
    </button>
    <button class="ps-btn ps-btn-delete" onclick="adminActionWithNote('delete-content', {{ $report->id }})">
        <i class="bi bi-trash3-fill"></i> Delete Post
    </button>
    <button class="ps-btn ps-btn-dismiss" onclick="adminAction('dismiss', {{ $report->id }})">
        <i class="bi bi-check-lg"></i> Dismiss Report
    </button>
    @else
    <button class="ps-btn ps-btn-delete" onclick="directDelete({{ $post->id }})">
        <i class="bi bi-trash3-fill"></i> Delete Post
    </button>
    @endif
</div>

@if(isset($report))
{{-- Review Note সেকশন --}}
<div class="ps-card mt-3" style="padding:20px 22px;">
    <h6 class="fw-bold mb-2" style="font-size:0.85rem;"><i class="bi bi-pencil-square text-primary me-2"></i>Admin Review Note</h6>
    <textarea id="reviewNoteBox" class="form-control mb-2" rows="3" placeholder="ইউজারকে জানানোর জন্য মন্তব্য লিখুন...">{{ $report->admin_note }}</textarea>
    <button class="ps-btn ps-btn-profile" onclick="submitReviewNote({{ $report->id }})">
        <i class="bi bi-send-fill"></i> Send Review to User
    </button>
</div>
@endif
    </div>

    {{-- Comments --}}
    @if($post->comments && $post->comments->count())
    <div class="ps-comments">
        <div class="ps-comments-head">
            <i class="bi bi-chat-dots me-2" style="color:var(--bb-primary);"></i>
            Comments ({{ $post->comments->count() }})
        </div>
        @foreach($post->comments->take(20) as $comment)
        <div class="ps-comment-item">
            <div class="ps-comment-avatar">
                @if($comment->user?->profile_picture)
                    <img src="{{ asset('storage/'.$comment->user->profile_picture) }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    {{ strtoupper(substr($comment->user?->name ?? '?', 0, 1)) }}
                @endif
            </div>
            <div class="flex-grow-1">
                <div class="ps-comment-name">
                    {{ $comment->user?->name ?? 'Deleted User' }}
                    @if($comment->user)
                    <span class="ps-role-badge {{ match($comment->user->role) { 'alumni'=>'rb-alumni', 'teacher'=>'rb-teacher', default=>'rb-student' } }}" style="font-size:0.58rem;padding:1px 6px;">
                        {{ ucfirst($comment->user->role) }}
                    </span>
                    @endif
                </div>
                <div class="ps-comment-text">{{ $comment->content }}</div>
                <div class="ps-comment-time">{{ $comment->created_at->diffForHumans() }}</div>
            </div>
        </div>
        @endforeach
        @if($post->comments->count() > 20)
        <div class="ps-empty-comments" style="font-size:0.75rem;">
            Showing 20 of {{ $post->comments->count() }} comments
        </div>
        @endif
    </div>
    @else
    <div class="ps-comments">
        <div class="ps-empty-comments">
            <i class="bi bi-chat-slash" style="font-size:1.5rem;display:block;margin-bottom:6px;"></i>
            No comments on this post
        </div>
    </div>
    @endif

</div>

{{-- Suspend modal --}}
<div class="modal fade" id="suspendModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius:14px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Suspend User</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2">
                <p style="font-size:0.8rem;color:#6b7280;">Choose suspension duration:</p>
                <div class="d-grid gap-2">
                    <button class="btn btn-warning btn-sm fw-bold" onclick="doSuspend('temp')">
                        <i class="bi bi-clock me-2"></i>7 Days Suspension
                    </button>
                    <button class="btn btn-danger btn-sm fw-bold" onclick="doSuspend('perm')">
                        <i class="bi bi-ban me-2"></i>Permanent Ban
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Toast --}}
<div class="ps-toast" id="psToast"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
let suspendUserId = null;

function showToast(message, type = 'success') {
    const t = document.getElementById('psToast');
    t.className = 'ps-toast ' + type;
    t.innerHTML = '<i class="bi bi-' + (type === 'success' ? 'check-circle-fill' : 'x-circle-fill') + '"></i> ' + message;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3500);
}

function adminAction(action, reportId) {
    const labels = {
        warn: { msg: 'Send warning notification to user?', btnText: 'Yes, Warn', color: '#d97706' },
        dismiss: { msg: 'Dismiss this report as resolved?', btnText: 'Dismiss', color: '#16a34a' },
        'delete-content': { msg: 'Permanently delete this post?', btnText: 'Delete', color: '#dc2626' },
    };
    const c = labels[action];
    if (!confirm(c.msg)) return;

    const isDelete = action === 'delete-content';
    fetch('/admin/reports/' + reportId + '/' + action, {
        method: isDelete ? 'DELETE' : 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            showToast(d.message, 'success');
            if (action === 'delete-content') {
                setTimeout(() => window.history.back(), 1500);
            }
        } else {
            showToast(d.message || 'Something went wrong.', 'error');
        }
    })
    .catch(() => showToast('Network error.', 'error'));
}

function adminActionWithNote(action, reportId) {
    const labels = {
        warn: { msg: 'Write a warning note for the user (they will see this):' },
        'delete-content': { msg: 'Write a reason for deleting this post (the user will see this):' },
    };
    const c = labels[action];
    const note = prompt(c.msg, '');
    if (note === null) return;

    const isDelete = action === 'delete-content';
    fetch('/admin/reports/' + reportId + '/' + action, {
        method: isDelete ? 'DELETE' : 'POST',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ note: note })
    })
    .then(r => r.json())
    .then(d => {
        showToast(d.message || 'Done.', d.success ? 'success' : 'error');
        if (d.success && action === 'delete-content') setTimeout(() => window.history.back(), 1500);
    })
    .catch(() => showToast('Network error.', 'error'));
}

function submitReviewNote(reportId) {
    const box = document.getElementById('reviewNoteBox');
    const note = box.value.trim();
    if (!note) return showToast('Please write a note.', 'error');

    fetch('/admin/reports/' + reportId + '/review', {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ note: note })
    })
    .then(r => r.json())
    .then(d => {
        showToast(d.message, d.success ? 'success' : 'error');
        if (d.success) box.value = ''; // ✅ শুধু বক্স খালি — রিলোড নেই
    })
    .catch(() => showToast('Network error.', 'error'));
}


function showSuspendModal(userId) {
    suspendUserId = userId;
    new bootstrap.Modal(document.getElementById('suspendModal')).show();
}

function doSuspend(type) {
    if (!suspendUserId) return;
    fetch('/admin/users/' + suspendUserId + '/suspension', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ type: type })
    })
    .then(r => r.json())
    .then(d => {
        bootstrap.Modal.getInstance(document.getElementById('suspendModal'))?.hide();
        showToast(d.message || 'User suspended.', d.success ? 'success' : 'error');
    })
    .catch(() => showToast('Network error.', 'error'));
}

function directDelete(postId) {
    if (!confirm('Permanently delete this post?')) return;
    fetch('/admin/posts/' + postId + '/delete', {
        method: 'DELETE',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
    })
    .then(r => r.json())
    .then(d => {
        showToast(d.message || 'Post deleted.', d.success ? 'success' : 'error');
        if (d.success) setTimeout(() => window.history.back(), 1500);
    })
    .catch(() => showToast('Network error.', 'error'));
}
</script>
</body>
</html>