<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <title>Saved Posts - Borobhai.online</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background-color: #f0f2f5; color: #1c1e21; }
        .navbar { background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,.08); padding: .5rem 1rem; }
        .navbar-brand { font-weight: 700; color: #1877f2; font-size: 1.5rem; letter-spacing: -.5px; }
        .nav-icon-btn { width: 40px; height: 40px; border-radius: 50px; background-color: #e4e6eb; display: flex; align-items: center; justify-content: center; color: #050505; text-decoration: none; font-size: 1.2rem; border: none; }
        .nav-icon-btn:hover { background-color: #d8dadf; }
        .saved-card { background:#fff; border-radius:12px; box-shadow:0 1px 2px rgba(0,0,0,.1); transition:transform .15s ease, box-shadow .15s ease; cursor:pointer; text-decoration:none; color:inherit; display:block; }
        .saved-card:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,.12); color:inherit; }
        .saved-archived { background:#fafafa; }
        .saved-archived:hover { transform:none; box-shadow:0 1px 2px rgba(0,0,0,.1); }
        .saved-thumb { width:90px; height:90px; border-radius:8px; object-fit:cover; flex-shrink:0; background:#000; }
        .saved-thumb-placeholder { width:90px; height:90px; border-radius:8px; flex-shrink:0; display:flex; align-items:center; justify-content:center; }
        .fs-7 { font-size:.85rem !important; }
        .line-clamp-2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
        .fb-bg-gradient-1 { background: linear-gradient(45deg, #f321d7, #2196f3) !important; }
        .fb-bg-gradient-2 { background: linear-gradient(45deg, #ff9800, #ff5722) !important; }
        .fb-bg-gradient-3 { background: linear-gradient(45deg, #4caf50, #00bcd4) !important; }
        .fb-bg-gradient-4 { background: linear-gradient(45deg, #1a2a6c, #b21f1f, #fdbb2d) !important; }
        .fb-bg-gradient-5 { background: linear-gradient(45deg, #00c6ff, #0072ff) !important; }
    </style>
</head>
<body>

@include('partials.inner-navbar')

<div class="container py-4" style="max-width:680px;">

    <div class="d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-bookmark-heart-fill text-warning fs-3"></i>
        <h4 class="fw-bold m-0">Saved</h4>
    </div>

    {{-- ===== SAVED JOBS ===== --}}
    @if(isset($savedJobs) && $savedJobs->count())
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-briefcase-fill text-primary"></i>
            <h6 class="fw-bold m-0" style="font-size:15px;">Saved Jobs · {{ $savedJobs->count() }}</h6>
        </div>
        @foreach($savedJobs as $job)
            @php
                $jt = strtolower($job->job_type);
                $logoColor = str_contains($jt,'intern') ? 'background:#fff7ed;color:#ea580c;'
                           : (str_contains($jt,'part') ? 'background:#eff6ff;color:#2563eb;'
                           : 'background:#eef2ff;color:#4f46e5;');
                $archived = $job->trashed();
                $expired = $job->is_expired;
                $expiringSoon = $job->is_expiring_soon;
                $jobSavedAt = $job->pivot->created_at ?? null;
            @endphp
            <div class="saved-card p-3 mb-3 {{ $archived ? 'saved-archived' : '' }}" id="savedJob-{{ $job->id }}">
                <div class="d-flex gap-3 align-items-center">
                    <div class="saved-thumb-placeholder" style="{{ $logoColor }} font-weight:800;font-size:30px;{{ $archived ? 'opacity:.55;' : '' }}">
                        {{ strtoupper(substr($job->company, 0, 1)) }}
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        @if($archived)
                            <span class="fw-bold mb-1 d-block text-dark" style="font-size:14px;">{{ $job->title }}</span>
                        @else
                            <a href="{{ route('jobs.show', $job->id) }}" class="fw-bold mb-1 d-block text-dark text-decoration-none" style="font-size:14px;">{{ $job->title }}</a>
                        @endif
                        <p class="text-muted mb-1" style="font-size:13px;">{{ $job->company }}@if($job->location) · {{ $job->location }}@endif</p>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge rounded-pill" style="background:#eef2ff;color:#4f46e5;font-size:10.5px;">{{ $job->job_type }}</span>
                            @if($archived)
                                <small style="font-size:11px;color:#9ca3af;font-weight:600;"><i class="bi bi-archive"></i> No longer available</small>
                            @elseif($expired)
                                <small style="font-size:11px;color:#dc2626;font-weight:600;"><i class="bi bi-x-circle"></i> Deadline over</small>
                            @elseif($expiringSoon)
                                <small style="font-size:11px;color:#ea580c;font-weight:600;"><i class="bi bi-alarm"></i> Expiring soon</small>
                            @endif
                            <small class="text-muted" style="font-size:11px;">
                                <i class="bi bi-bookmark-fill text-warning me-1"></i>Saved {{ $jobSavedAt ? $jobSavedAt->diffForHumans() : '' }}
                            </small>
                        </div>
                    </div>
                    <button type="button"
                            class="btn btn-sm btn-light border rounded-circle flex-shrink-0"
                            style="width:36px;height:36px;"
                            onclick="unsaveJob({{ $job->id }}, this)"
                            title="Remove from saved">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>
        @endforeach

        <hr class="my-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-file-post-fill text-secondary"></i>
            <h6 class="fw-bold m-0" style="font-size:15px;">Saved Posts</h6>
        </div>
    @endif

    @forelse($savedPosts as $post)
        @php
            // শেয়ার করা পোস্ট হলে — থাম্বনেইল সবসময় parent থেকে নাও
            $source = ($post->parent_id && $post->parentPost) ? $post->parentPost : $post;

            // caption: শেয়ার পোস্টে নিজের caption থাকলে সেটা, নাহলে parent/নিজের
            $ownCaption = trim($post->content);
            $captionSource = ($ownCaption !== '') ? $post : $source;

            // থাম্বনেইল ঠিক করা ($source থেকে)
            $thumbUrl = null;
            $thumbType = null;
            if (is_array($source->images) && count($source->images) > 0) {
                $thumbUrl = asset('storage/'.str_replace('//','/',$source->images[0]));
                $thumbType = 'image';
            } elseif (!empty($source->video) && $source->video !== 'null') {
                $vid = is_array($source->video) ? ($source->video[0] ?? null) : $source->video;
                if ($vid) { $thumbUrl = asset('storage/'.str_replace('//','/',trim($vid,'"[] '))); $thumbType = 'video'; }
            }

            // টেক্সট প্রিভিউ
            $previewText = trim($captionSource->content) !== '' ? $captionSource->content : '(No caption)';

            // থাম্বনেইলের bg_color ($source থেকে)
            $thumbBgColor = $source->bg_color ?? null;

            // কবে সেভ করা (pivot)
            $savedAt = $post->pivot->created_at ?? null;
        @endphp

        <a href="{{ route('home') }}#postCard-{{ $post->id }}" class="saved-card p-3 mb-3">
            <div class="d-flex gap-3 align-items-center">

                {{-- Thumbnail --}}
                @if($thumbType === 'image')
                    <img src="{{ $thumbUrl }}" class="saved-thumb" alt="post">
                @elseif($thumbType === 'video')
                    <div class="saved-thumb position-relative" style="background:#000;">
                        <video src="{{ $thumbUrl }}" class="saved-thumb" preload="metadata" style="object-fit:cover;"></video>
                        <div class="position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center rounded-circle" style="width:30px;height:30px;background:rgba(0,0,0,.6);">
                            <i class="bi bi-play-fill text-white"></i>
                        </div>
                    </div>
                @elseif(!empty($thumbBgColor))
                    <div class="saved-thumb-placeholder {{ $thumbBgColor }}">
                        <i class="bi bi-card-text text-white fs-4"></i>
                    </div>
                @else
                    <div class="saved-thumb-placeholder bg-light border">
                        <i class="bi bi-card-text text-muted fs-4"></i>
                    </div>
                @endif

                {{-- Content --}}
                <div class="flex-grow-1 overflow-hidden">
                    <h6 class="fw-bold mb-1" style="font-size:14px;">{{ $post->user->name ?? 'Unknown' }}</h6>
                    <p class="text-muted mb-1 line-clamp-2" style="font-size:13px;">{{ $previewText }}</p>
                    <small class="text-muted" style="font-size:11px;">
                        <i class="bi bi-bookmark-fill text-warning me-1"></i>
                        Saved {{ $savedAt ? $savedAt->diffForHumans() : '' }}
                    </small>
                </div>

                {{-- Unsave button --}}
                <button type="button"
                        class="btn btn-sm btn-light border rounded-circle flex-shrink-0"
                        style="width:36px;height:36px;"
                        onclick="event.preventDefault(); event.stopPropagation(); unsaveFromList({{ $post->id }}, this);"
                        title="Remove from saved">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        </a>
    @empty
        <div class="text-center py-5 bg-white rounded-4 shadow-sm">
            <i class="bi bi-bookmark fs-1 text-muted d-block mb-3"></i>
            <h5 class="fw-bold text-secondary">No saved posts yet</h5>
            <p class="text-muted small mb-0">Posts you save will appear here.</p>
        </div>
    @endforelse

    {{-- Pagination --}}
    @if($savedPosts->hasPages())
        <div class="mt-4">
            {{ $savedPosts->links() }}
        </div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function unsaveJob(jobId, btn) {
    Swal.fire({
        title: 'Remove this job from saved?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Remove'
    }).then(r => {
        if (!r.isConfirmed) return;
        fetch(`/jobs/${jobId}/save`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(d => {
            if (!d.success) return;
            const card = document.getElementById(`savedJob-${jobId}`);
            if (card) {
                card.style.transition = 'opacity .3s ease';
                card.style.opacity = '0';
                setTimeout(() => card.remove(), 300);
            }
            const Toast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:2000 });
            Toast.fire({ icon:'info', title:'Removed from saved' });
        });
    });
}

function unsaveFromList(postId, btn) {
    Swal.fire({
        title: 'Remove from saved?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Remove'
    }).then(r => {
        if (!r.isConfirmed) return;
        fetch(`/posts/${postId}/save`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(d => {
            if (!d.success) return;
            const card = btn.closest('.saved-card');
            if (card) {
                card.style.transition = 'opacity .3s ease';
                card.style.opacity = '0';
                setTimeout(() => {
                    card.remove();
                    if (!document.querySelector('.saved-card')) {
                        location.reload();
                    }
                }, 300);
            }
            const Toast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:2000 });
            Toast.fire({ icon:'info', title:'Removed from saved' });
        });
    });
}
</script>

</body>
</html>