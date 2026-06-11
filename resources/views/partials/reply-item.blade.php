@php
    use Illuminate\Support\Facades\Auth;
    $rEdited = $reply->updated_at && $reply->created_at
                && $reply->updated_at->gt($reply->created_at->addSeconds(1));
    $rLikeCount = $reply->likes->count();
    $rIsLiked   = $reply->likes->contains('user_id', Auth::id());
    // @নাম হাইলাইট
    $rContent = e($reply->content);
    $rContent = preg_replace('/@([\w\x{0980}-\x{09FF}.]+(?:\s[\w\x{0980}-\x{09FF}.]+)?)/u', '<span class="comment-mention">@$1</span>', $rContent);
@endphp

<div class="d-flex gap-2 mb-2 align-items-start comment-row reply-row" id="comment-container-{{ $reply->id }}">
    {{-- Avatar (ছোট) --}}
    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0 overflow-hidden"
         style="width:28px;height:28px;font-size:12px;">
        @if($reply->user->profile_picture)
            <img src="{{ asset('storage/'.$reply->user->profile_picture) }}" alt="{{ $reply->user->name }}" style="width:100%;height:100%;object-fit:cover;">
        @else
            {{ strtoupper(substr($reply->user->name ?? 'U', 0, 1)) }}
        @endif
    </div>

    <div class="flex-grow-1">
        <div class="d-flex align-items-start justify-content-between">
            <div class="bg-light px-3 py-2 rounded-4 d-inline-block border" style="max-width:100%;">
                <strong class="d-block text-dark" style="font-size:12px;">{{ $reply->user->name }}</strong>
                <span id="comment-text-{{ $reply->id }}" style="font-size:12.5px;word-break:break-word;">{!! $rContent !!}</span>
            </div>
            @if($reply->user_id === Auth::id())
                <div class="dropdown flex-shrink-0">
                    <button type="button" class="btn btn-link btn-sm text-muted p-0 border-0 shadow-none ms-1" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm p-1" style="min-width:100px;">
                        <li><a class="dropdown-item py-1 fs-7" href="javascript:void(0)" onclick="editComment(event, {{ $reply->id }})">
                            <i class="bi bi-pencil me-1"></i> Edit</a></li>
                        <li><a class="dropdown-item py-1 fs-7 text-danger" href="javascript:void(0)" onclick="deleteComment({{ $reply->id }}, {{ $reply->post_id }})">
                            <i class="bi bi-trash me-1"></i> Delete</a></li>
                    </ul>
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3 ms-2 mt-1" style="font-size:11px;">
            <span class="comment-like-btn {{ $rIsLiked ? 'liked' : '' }}" id="comment-like-{{ $reply->id }}"
                  onclick="toggleCommentLike({{ $reply->id }})" style="cursor:pointer;font-weight:600;">
                {{ $rIsLiked ? 'Liked' : 'Like' }}
            </span>
            <span class="comment-reply-btn" onclick="openReplyBox({{ $reply->parent_id }}, '{{ addslashes($reply->user->name) }}')" style="cursor:pointer;font-weight:600;color:#65676b;">
                Reply
            </span>
            <span class="text-muted comment-meta-{{ $reply->id }}">
                {{ $reply->created_at->diffForHumans() }}<span class="comment-edited-tag-{{ $reply->id }}">{{ $rEdited ? ' · Edited' : '' }}</span>
            </span>
            <span class="comment-like-count text-muted" id="comment-like-count-{{ $reply->id }}" style="{{ $rLikeCount > 0 ? '' : 'display:none;' }}">
                <i class="bi bi-hand-thumbs-up-fill text-primary"></i> <span class="clc-num">{{ $rLikeCount }}</span>
            </span>
        </div>
    </div>
</div>