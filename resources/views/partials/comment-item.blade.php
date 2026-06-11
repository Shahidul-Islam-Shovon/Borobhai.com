@php
    use Illuminate\Support\Facades\Auth;
    $isEdited = $comment->updated_at && $comment->created_at
                && $comment->updated_at->gt($comment->created_at->addSeconds(1));
    $likeCount = $comment->likes->count();
    $isLiked   = $comment->likes->contains('user_id', Auth::id());
@endphp

{{-- ===== একটি কমেন্ট (মূল) ===== --}}
<div class="comment-thread" id="comment-thread-{{ $comment->id }}">
<div class="d-flex gap-2 mb-2 align-items-start comment-row" id="comment-container-{{ $comment->id }}">
    {{-- Avatar --}}
    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0 overflow-hidden"
         style="width:32px;height:32px;font-size:13px;">
        @if($comment->user->profile_picture)
            <img src="{{ asset('storage/'.$comment->user->profile_picture) }}" alt="{{ $comment->user->name }}" style="width:100%;height:100%;object-fit:cover;">
        @else
            {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}
        @endif
    </div>

    {{-- Bubble + meta --}}
    <div class="flex-grow-1">
        <div class="d-flex align-items-start justify-content-between">
            <div class="bg-light px-3 py-2 rounded-4 d-inline-block border" style="max-width:100%;">
                <strong class="d-block text-dark" style="font-size:12.5px;">{{ $comment->user->name }}</strong>
                <span id="comment-text-{{ $comment->id }}" style="font-size:13px;word-break:break-word;">{{ $comment->content }}</span>
            </div>
            @if($comment->user_id === Auth::id())
                <div class="dropdown flex-shrink-0">
                    <button type="button" class="btn btn-link btn-sm text-muted p-0 border-0 shadow-none ms-1"
                            data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm p-1" style="min-width:100px;">
                        <li><a class="dropdown-item py-1 fs-7" href="javascript:void(0)"
                               onclick="editComment(event, {{ $comment->id }})">
                            <i class="bi bi-pencil me-1"></i> Edit</a></li>
                        <li><a class="dropdown-item py-1 fs-7 text-danger" href="javascript:void(0)"
                               onclick="deleteComment({{ $comment->id }}, {{ $comment->post_id }})">
                            <i class="bi bi-trash me-1"></i> Delete</a></li>
                    </ul>
                </div>
            @endif
        </div>

        {{-- Action row: Like · Reply · time --}}
        <div class="d-flex align-items-center gap-3 ms-2 mt-1" style="font-size:11.5px;">
            <span class="comment-like-btn {{ $isLiked ? 'liked' : '' }}" id="comment-like-{{ $comment->id }}"
                  onclick="toggleCommentLike({{ $comment->id }})" style="cursor:pointer;font-weight:600;">
                {{ $isLiked ? 'Liked' : 'Like' }}
            </span>
            <span class="comment-reply-btn" onclick="openReplyBox({{ $comment->id }})" style="cursor:pointer;font-weight:600;color:#65676b;">
                Reply
            </span>
            <span class="text-muted comment-meta-{{ $comment->id }}">
                {{ $comment->created_at->diffForHumans() }}<span class="comment-edited-tag-{{ $comment->id }}">{{ $isEdited ? ' · Edited' : '' }}</span>
            </span>
            <span class="comment-like-count text-muted" id="comment-like-count-{{ $comment->id }}" style="{{ $likeCount > 0 ? '' : 'display:none;' }}">
                <i class="bi bi-hand-thumbs-up-fill text-primary"></i> <span class="clc-num">{{ $likeCount }}</span>
            </span>
        </div>

        {{-- Reply box (hidden, খোলে Reply চাপলে) --}}
        <div class="reply-box-zone mt-2 d-none" id="reply-box-{{ $comment->id }}"></div>

        {{-- Replies --}}
        <div class="replies-zone mt-2" id="replies-zone-{{ $comment->id }}">
            @foreach($comment->replies as $reply)
                @include('partials.reply-item', ['reply' => $reply])
            @endforeach
        </div>
    </div>
</div>
</div>