@php
    use Illuminate\Support\Facades\Auth;
    $meId = Auth::id();

    // Privacy config
    $privacy = $post->privacy ?? 'public';
    $privacyConfig = match($privacy) {
        'friends' => ['icon' => 'bi-people-fill',    'label' => 'Friends',  'color' => 'text-success'],
        'only_me' => ['icon' => 'bi-lock-fill',      'label' => 'Only Me',  'color' => 'text-warning'],
        default   => ['icon' => 'bi-globe-americas', 'label' => 'Public',   'color' => 'text-primary'],
    };

    // Friendship status with post author
    $isMyPost = $post->user_id === $meId;
    $friendshipStatus = 'none';
    if (!$isMyPost) {

        $fs = \App\Models\Friendship::where(function($q) use ($meId, $post) {
        $q->where(function($inner) use ($meId, $post) {
            $inner->where('sender_id', $meId)
                ->where('receiver_id', $post->user_id);
        })->orWhere(function($inner) use ($meId, $post) {
            $inner->where('sender_id', $post->user_id)
                ->where('receiver_id', $meId);
        });
        })->first();


        if ($fs) {
            if ($fs->status === 'accepted') $friendshipStatus = 'accepted';
            elseif ($fs->status === 'pending') {
                $friendshipStatus = $fs->sender_id === $meId ? 'pending_sent' : 'pending_received';
            }
            elseif ($fs->status === 'blocked') $friendshipStatus = 'blocked';
        }
    }
@endphp

<div class="bb-post-card" id="postCard-{{ $post->id }}" data-bg-color="{{ $post->bg_color }}" data-privacy="{{ $privacy }}">

    {{-- Post Header --}}
    <div class="bb-post-head">
        <div class="bb-head-left">
            <a href="{{ $isMyPost ? route('profile.show') : route('profile.view', $post->user) }}"
               class="bb-avatar author-avatar-zone" style="text-decoration:none;">
                @if($post->user->profile_picture)
                    <img src="{{ asset('storage/'.$post->user->profile_picture) }}" alt="{{ $post->user->name }}" class="bb-avatar-img">
                @else
                    {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                @endif
            </a>
            <div class="bb-head-meta">
                <a href="{{ $isMyPost ? route('profile.show') : route('profile.view', $post->user) }}"
                   class="bb-author author-name-zone bb-author-link">{{ $post->user->name }}</a>

                {{-- Role badge --}}
                @php $pRole = $post->user->role; @endphp
                <span class="bb-author-role {{ match($pRole) { 'teacher' => 'bb-author-role-teacher', 'alumni' => 'bb-author-role-alumni', default => 'bb-author-role-student' } }}">
                    <i class="bi {{ match($pRole) { 'teacher' => 'bi-easel2-fill', 'alumni' => 'bi-mortarboard-fill', default => 'bi-backpack-fill' } }}"></i>
                    {{ ucfirst($pRole) }}
                </span>

                {{-- Time + Privacy icon --}}
                <span class="bb-time">
                    <i class="bi {{ $privacyConfig['icon'] }} {{ $privacyConfig['color'] }}" title="{{ $privacyConfig['label'] }}"></i>
                    {{ $post->updated_at->diffForHumans() }}
                </span>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            {{-- Add Friend button (অন্যের post এ) --}}
            @if(!$isMyPost && $friendshipStatus !== 'blocked')
                <div id="postFriendWrap-{{ $post->user_id }}">
                    @if($friendshipStatus === 'accepted')
                        {{-- already friends — কিছু দেখাব না --}}
                    @elseif($friendshipStatus === 'pending_sent')
                        <button class="btn btn-sm" style="font-size:11px;font-weight:700;background:#eef2ff;color:#4f46e5;border:1.5px solid #c7d2fe;border-radius:20px;padding:4px 10px;"
                                onclick="postCardFriend('cancel', {{ $post->user_id }}, this)">
                            <i class="bi bi-person-check-fill me-1"></i>Requested
                        </button>
                    @elseif($friendshipStatus === 'pending_received')
                        <button class="btn btn-sm" style="font-size:11px;font-weight:700;background:#059669;color:#fff;border-radius:20px;padding:4px 10px;"
                                onclick="postCardFriend('accept', {{ $post->user_id }}, this)">
                            <i class="bi bi-person-plus-fill me-1"></i>Accept
                        </button>
                    @else
                        <button class="btn btn-sm" style="font-size:11px;font-weight:700;background:#eef2ff;color:#4f46e5;border:1.5px solid #c7d2fe;border-radius:20px;padding:4px 10px;"
                                onclick="postCardFriend('send', {{ $post->user_id }}, this)">
                            <i class="bi bi-person-plus-fill me-1"></i>Add Friend
                        </button>
                    @endif
                </div>
            @endif

            {{-- Three dots menu (নিজের post) --}}
            @if($isMyPost)
                <div class="dropdown">
                    <button class="bb-more-btn" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-1">
                        <li>
                            <a class="dropdown-item py-2 px-3 rounded" href="javascript:void(0)"
                               onclick="prepareEditModal(this)"
                               data-id="{{ $post->id }}"
                               data-content="{{ $post->content }}"
                               data-bg-color="{{ $post->bg_color }}"
                               data-privacy="{{ $privacy }}"
                               data-images="{{ json_encode($post->images) }}"
                               data-video="{{ is_array($post->video) ? json_encode($post->video) : $post->video }}"
                               data-is-shared="{{ $post->parent_id ? '1' : '0' }}">
                                <i class="bi bi-pencil-square me-2"></i> Edit post
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 px-3 rounded text-danger" href="javascript:void(0)"
                               onclick="deletePost({{ $post->id }})">
                                <i class="bi bi-trash3 me-2"></i> Delete
                            </a>
                        </li>
                    </ul>
                </div>
            @else
                {{-- অন্যের post এ report option --}}
                {{-- অন্যের post এ report option --}}
                <div class="dropdown">
                    <button class="bb-more-btn" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-1">
                        <li>
                            <a class="dropdown-item py-2 px-3 rounded text-danger" href="javascript:void(0)"
                            onclick="bbOpenReport('post', {{ $post->id }}, '{{ e($post->user->name) }}', true)">
                                <i class="bi bi-flag me-2"></i> Report post
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 px-3 rounded text-danger" href="javascript:void(0)"
                            onclick="bbOpenReport('user', {{ $post->user_id }}, '{{ e($post->user->name) }}', false)">
                                <i class="bi bi-person-x me-2"></i> Report This User {{ $post->user->name }}
                            </a>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </div>

    {{-- Post Caption --}}
    @php
        $hasImages       = is_array($post->images) && count($post->images) > 0;
        $videoItemsArray = [];
        if (!empty($post->video) && $post->video !== 'null') {
            $decoded = is_array($post->video) ? $post->video : json_decode($post->video, true);
            if (is_array($decoded)) {
                $videoItemsArray = $decoded;
            } else {
                $clean = trim($post->video, '"[]');
                if (!empty($clean)) $videoItemsArray[] = $clean;
            }
        }
        $hasVideo = count($videoItemsArray) > 0;
        $renderBg = !empty($post->bg_color) && !$hasImages && !$hasVideo;
    @endphp

    @if(trim($post->content) !== '' || $renderBg)
    <div class="{{ $renderBg ? 'bb-color-caption '.$post->bg_color : 'bb-caption' }}">
        <p class="mb-0 dynamic-caption">{!! nl2br(e($post->content)) !!}</p>
    </div>
    @endif

    {{-- Media Grid --}}
    @php
        $mediaItems = [];
        if ($hasImages) {
            foreach ($post->images as $img) {
                $mediaItems[] = ['type' => 'image', 'url' => asset('storage/'.str_replace('//', '/', $img))];
            }
        }
        if ($hasVideo) {
            foreach ($videoItemsArray as $vid) {
                $cleanVid = str_replace('//', '/', trim($vid, '"[] '));
                if (!empty($cleanVid)) $mediaItems[] = ['type' => 'video', 'url' => url('stream/video/'.$cleanVid)];
            }
        }
        $mediaCount        = count($mediaItems);
        $escapedImagesJson = json_encode($mediaItems, JSON_HEX_APOS | JSON_HEX_QUOT);
    @endphp

    @if($mediaCount > 0)
    <div class="bb-media-zone dynamic-media-container-zone" data-media-json="{{ $escapedImagesJson }}">
        @if($mediaCount === 1)
            @if($mediaItems[0]['type'] === 'image')
                <div class="bb-media-single">
                    <img src="{{ $mediaItems[0]['url'] }}" class="bb-single-img"
                         onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),0)">
                </div>
            @else
                <div class="bb-video-wrap">
                    <video src="{{ $mediaItems[0]['url'] }}" class="bb-inline-video" preload="metadata" controls playsinline></video>
                    <button type="button" class="bb-expand-btn"
                            onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),0)">
                        <i class="bi bi-arrows-fullscreen"></i>
                    </button>
                </div>
            @endif
        @elseif($mediaCount === 2)
            <div class="bb-grid bb-grid-2">
                @foreach($mediaItems as $i => $media)
                    <div class="bb-tile">
                        @if($media['type'] === 'image')
                            <img src="{{ $media['url'] }}" class="bb-tile-media"
                                 onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $i }})">
                        @else
                            <video src="{{ $media['url'] }}" class="bb-tile-media" preload="metadata"
                                   onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $i }})"></video>
                            <div class="bb-play-badge" onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $i }})"><i class="bi bi-play-fill"></i></div>
                        @endif
                    </div>
                @endforeach
            </div>
        @elseif($mediaCount === 3)
            <div class="bb-grid bb-grid-3">
                <div class="bb-tile bb-tile-big">
                    @if($mediaItems[0]['type'] === 'image')
                        <img src="{{ $mediaItems[0]['url'] }}" class="bb-tile-media"
                             onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),0)">
                    @else
                        <video src="{{ $mediaItems[0]['url'] }}" class="bb-tile-media" preload="metadata"
                               onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),0)"></video>
                        <div class="bb-play-badge" onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),0)"><i class="bi bi-play-fill"></i></div>
                    @endif
                </div>
                <div class="bb-grid-3-side">
                    @foreach([1,2] as $i)
                        <div class="bb-tile">
                            @if($mediaItems[$i]['type'] === 'image')
                                <img src="{{ $mediaItems[$i]['url'] }}" class="bb-tile-media"
                                     onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $i }})">
                            @else
                                <video src="{{ $mediaItems[$i]['url'] }}" class="bb-tile-media" preload="metadata"
                                       onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $i }})"></video>
                                <div class="bb-play-badge bb-play-sm" onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $i }})"><i class="bi bi-play-fill"></i></div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @elseif($mediaCount === 4)
            <div class="bb-grid bb-grid-4">
                @foreach($mediaItems as $i => $media)
                    <div class="bb-tile">
                        @if($media['type'] === 'image')
                            <img src="{{ $media['url'] }}" class="bb-tile-media"
                                 onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $i }})">
                        @else
                            <video src="{{ $media['url'] }}" class="bb-tile-media" preload="metadata"
                                   onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $i }})"></video>
                            <div class="bb-play-badge bb-play-sm" onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $i }})"><i class="bi bi-play-fill"></i></div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            @php $visibleItems = array_slice($mediaItems,0,4); $remaining = $mediaCount-4; @endphp
            <div class="bb-grid bb-grid-4">
                @foreach($visibleItems as $i => $media)
                    <div class="bb-tile">
                        @if($media['type'] === 'image')
                            <img src="{{ $media['url'] }}" class="bb-tile-media"
                                 onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $i }})">
                        @else
                            <video src="{{ $media['url'] }}" class="bb-tile-media" preload="metadata"
                                   onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $i }})"></video>
                            <div class="bb-play-badge bb-play-sm" onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $i }})"><i class="bi bi-play-fill"></i></div>
                        @endif
                        @if($i === 3 && $remaining > 0)
                            <div class="bb-more-overlay"
                                 onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),3)">
                                +{{ $remaining }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    @endif

    {{-- Shared / Parent Post --}}
    @if($post->parentPost)
        @php
            $parentMedia = [];
            if ($post->parentPost->images) {
                $pImgs = is_array($post->parentPost->images) ? $post->parentPost->images : json_decode($post->parentPost->images, true);
                if (is_array($pImgs)) foreach ($pImgs as $img) $parentMedia[] = ['type'=>'image','url'=>asset('storage/'.$img)];
            }
            if ($post->parentPost->video) {
                $pVids = json_decode($post->parentPost->video, true);
                if (is_array($pVids)) { foreach ($pVids as $v) $parentMedia[] = ['type'=>'video','url'=>url('stream/video/'.$v)]; }
                else { $parentMedia[] = ['type'=>'video','url'=>url('stream/video/'.$post->parentPost->video)]; }
            }
            $pCount          = count($parentMedia);
            $pHasImages      = !empty($post->parentPost->images);
            $pHasVideo       = !empty($post->parentPost->video);
            $pRenderBg       = $post->parentPost->bg_color && !$pHasImages && !$pHasVideo;
            $parentMediaJson = json_encode($parentMedia, JSON_HEX_APOS | JSON_HEX_QUOT);
        @endphp
        <div class="bb-shared">
            <div class="bb-shared-head">
                <div class="bb-avatar bb-avatar-sm">
                    @if($post->parentPost->user->profile_picture)
                        <img src="{{ asset('storage/'.$post->parentPost->user->profile_picture) }}" alt="{{ $post->parentPost->user->name }}" class="bb-avatar-img">
                    @else
                        {{ strtoupper(substr($post->parentPost->user->name ?? 'U', 0, 1)) }}
                    @endif
                </div>
                <div>
                    <h6 class="bb-author" style="font-size:13px;">{{ $post->parentPost->user->name }}</h6>
                    <span class="bb-time">{{ $post->parentPost->created_at->diffForHumans() }}</span>
                </div>
            </div>
            @if(trim($post->parentPost->content) !== '' || $pRenderBg)
            <div class="{{ $pRenderBg ? 'bb-color-caption bb-color-caption-sm '.$post->parentPost->bg_color : 'bb-caption' }}" style="{{ $pRenderBg ? '' : 'padding:0 14px 8px;' }}">
                <p class="mb-0">{!! nl2br(e($post->parentPost->content)) !!}</p>
            </div>
            @endif
            @if($pCount > 0)
            <div class="bb-media-zone" data-media-json="{{ $parentMediaJson }}">
                @if($pCount === 1)
                    @if($parentMedia[0]['type'] === 'image')
                        <div class="bb-media-single" style="max-height:300px;">
                            <img src="{{ $parentMedia[0]['url'] }}" class="bb-single-img" style="max-height:300px;"
                                 onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),0)">
                        </div>
                    @else
                        <div class="bb-video-wrap">
                            <video src="{{ $parentMedia[0]['url'] }}" class="bb-inline-video" style="max-height:300px;" preload="metadata" controls playsinline></video>
                        </div>
                    @endif
                @else
                    @php $pShow = min($pCount,2); $pMore = $pCount - $pShow; @endphp
                    <div class="bb-grid bb-grid-2" style="height:220px;">
                        @for($pIdx = 0; $pIdx < $pShow; $pIdx++)
                            @php $pm = $parentMedia[$pIdx]; @endphp
                            <div class="bb-tile">
                                @if($pm['type'] === 'image')
                                    <img src="{{ $pm['url'] }}" class="bb-tile-media"
                                         onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $pIdx }})">
                                @else
                                    <video src="{{ $pm['url'] }}" class="bb-tile-media" preload="metadata"
                                           onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $pIdx }})"></video>
                                    <div class="bb-play-badge bb-play-sm" onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $pIdx }})"><i class="bi bi-play-fill"></i></div>
                                @endif
                                @if($pIdx === $pShow - 1 && $pMore > 0)
                                    <div class="bb-more-overlay" style="font-size:1.4rem;"
                                         onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $pIdx }})">
                                        +{{ $pMore }}
                                    </div>
                                @endif
                            </div>
                        @endfor
                    </div>
                @endif
            </div>
            @endif
        </div>
    @endif

    {{-- Like / Comment Counts --}}
    <div class="bb-stats">
        <div id="like-zone-{{ $post->id }}" class="bb-like-stat">
            @if($post->likes->count() > 0)
                <span class="bb-like-bubble"><i class="bi bi-hand-thumbs-up-fill"></i></span>
                <span class="like-count-text">{{ $post->likes->count() }}</span>
            @endif
        </div>
        <div>
            <span class="bb-stat-link" id="comment-count-{{ $post->id }}"
                  onclick="openCommentModal({{ $post->id }})">{{ $post->comments_count ?? $post->comments->count() }} comments</span>
        </div>
    </div>

    {{-- Action Buttons --}}
    @php $isSaved = $post->isSavedByCurrentUser(); @endphp
    <div class="bb-actions">
        <button type="button"
                class="bb-action-btn {{ $post->likes->contains('user_id', $meId) ? 'active-like' : '' }}"
                id="likeBtn-{{ $post->id }}" onclick="toggleLike({{ $post->id }})">
            <i class="bi {{ $post->likes->contains('user_id', $meId) ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up' }}"></i>
            <span>Like</span>
        </button>
        <button type="button" class="bb-action-btn" onclick="openCommentModal({{ $post->id }})">
            <i class="bi bi-chat-square-text"></i><span>Comment</span>
        </button>
        <button type="button" class="bb-action-btn" onclick="openShareModal({{ $post->id }})">
            <i class="bi bi-share"></i><span>Share</span>
        </button>
        <button type="button"
                class="bb-action-btn {{ $isSaved ? 'active-save' : '' }}"
                id="saveBtn-{{ $post->id }}" onclick="toggleSave({{ $post->id }})">
            <i class="bi {{ $isSaved ? 'bi-bookmark-fill' : 'bi-bookmark' }}" id="saveIcon-{{ $post->id }}"></i>
            <span id="saveText-{{ $post->id }}">{{ $isSaved ? 'Saved' : 'Save' }}</span>
        </button>
    </div>

</div>

@once
<script>
// Post card এ friend action
function postCardFriend(action, userId, btn) {
    const endpoints = {
        send:   '/friends/send',
        cancel: '/friends/cancel',
        accept: '/friends/accept',
    };
    if (btn) btn.disabled = true;
    fetch(endpoints[action], {
        method: 'POST',
        headers: { 'Content-Type':'application/json', 'Accept':'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ user_id: userId }),
    })
    .then(r => r.json())
    .then(d => {
        if (btn) btn.disabled = false;
        if (!d.success) return;
        // সব post card এ ঐ user এর button update করো
        document.querySelectorAll(`#postFriendWrap-${userId}`).forEach(wrap => {
            if (d.status === 'pending_sent') {
                wrap.innerHTML = `<button class="btn btn-sm" style="font-size:11px;font-weight:700;background:#eef2ff;color:#4f46e5;border:1.5px solid #c7d2fe;border-radius:20px;padding:4px 10px;"
                    onclick="postCardFriend('cancel',${userId},this)">
                    <i class="bi bi-person-check-fill me-1"></i>Requested</button>`;
            } else if (d.status === 'accepted') {
                wrap.innerHTML = '';
            } else {
                wrap.innerHTML = `<button class="btn btn-sm" style="font-size:11px;font-weight:700;background:#eef2ff;color:#4f46e5;border:1.5px solid #c7d2fe;border-radius:20px;padding:4px 10px;"
                    onclick="postCardFriend('send',${userId},this)">
                    <i class="bi bi-person-plus-fill me-1"></i>Add Friend</button>`;
            }
        });
        if (typeof Swal !== 'undefined') {
            Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:2000, timerProgressBar:true }).fire({ icon:'success', title: d.message });
        }
    })
    .catch(() => { if (btn) btn.disabled = false; });
}
</script>
@endonce

@once
<div class="modal fade" id="bbReportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Report <span id="bbReportName"></span></h6>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Why are you reporting this?</p>
                <input type="hidden" id="bbReportType">
                <input type="hidden" id="bbReportId">
                <input type="hidden" id="bbReportReason">
                <div id="bbReportReasons">
                    <button class="bb-report-reason-btn" onclick="bbSelectReason(this,'spam')">Spam</button>
                    <button class="bb-report-reason-btn" onclick="bbSelectReason(this,'harassment')">Harassment or bullying</button>
                    <button class="bb-report-reason-btn" onclick="bbSelectReason(this,'fake')">Fake profile or impersonation</button>
                    <button class="bb-report-reason-btn" onclick="bbSelectReason(this,'inappropriate')">Inappropriate content</button>
                    <button class="bb-report-reason-btn" onclick="bbSelectReason(this,'other')">Something else</button>
                </div>
                <div id="bbReportDetailsSection" class="d-none mt-3">
                    <textarea id="bbReportDetails" class="form-control border rounded-3 mb-2" rows="3" placeholder="Add more details (optional)..." style="font-size:13px;"></textarea>
                    <div id="bbMuteOptionWrap" class="form-check mb-3" style="display:none;">
                        <input class="form-check-input" type="checkbox" id="bbMuteCheckbox">
                        <label class="form-check-label" style="font-size:13px;" for="bbMuteCheckbox">
                            Also hide posts from this person for 30 days
                        </label>
                    </div>
                    <div id="bbHideOptionWrap" class="form-check mb-2" style="display:none;">
                        <input class="form-check-input" type="checkbox" id="bbHideCheckbox" checked>
                        <label class="form-check-label" style="font-size:13px;" for="bbHideCheckbox">
                            Hide this post from my feed
                        </label>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-light btn-sm" onclick="document.getElementById('bbReportDetailsSection').classList.add('d-none'); document.getElementById('bbReportReasons').classList.remove('d-none');">Back</button>
                        <button class="btn btn-danger btn-sm px-4 fw-bold" onclick="bbSubmitReport()">Submit Report</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bb-report-reason-btn { display:flex; align-items:center; width:100%; padding:11px 14px; border-radius:10px; border:1.5px solid #eceef1; background:#fff; color:#1e1f24; font-size:13.5px; font-weight:600; cursor:pointer; transition:all .15s; margin-bottom:7px; }
.bb-report-reason-btn:hover, .bb-report-reason-btn.selected { border-color:#4f46e5; background:#eef2ff; color:#4f46e5; }
</style>

<script>
function bbOpenReport(type, id, name, showMute) {
    document.getElementById('bbReportType').value = type;
    document.getElementById('bbReportId').value = id;
    document.getElementById('bbReportName').textContent = name;
    document.getElementById('bbReportReason').value = '';
    document.getElementById('bbReportReasons').classList.remove('d-none');
    document.getElementById('bbReportDetailsSection').classList.add('d-none');
    document.getElementById('bbHideOptionWrap').style.display = showMute ? 'block' : 'none';
    document.getElementById('bbHideCheckbox').checked = true;
    document.querySelectorAll('.bb-report-reason-btn').forEach(b => b.classList.remove('selected'));
    document.getElementById('bbMuteOptionWrap').style.display = showMute ? 'block' : 'none';
    document.getElementById('bbMuteCheckbox').checked = false;
    new bootstrap.Modal(document.getElementById('bbReportModal')).show();
}
function bbSelectReason(el, reason) {
    document.getElementById('bbReportReason').value = reason;
    document.querySelectorAll('.bb-report-reason-btn').forEach(b => b.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('bbReportReasons').classList.add('d-none');
    document.getElementById('bbReportDetailsSection').classList.remove('d-none');
}
function bbSubmitReport() {
    const type = document.getElementById('bbReportType').value;
    const id = document.getElementById('bbReportId').value;
    const reason = document.getElementById('bbReportReason').value;
    const details = document.getElementById('bbReportDetails').value;
    const mute = document.getElementById('bbMuteCheckbox').checked;
    const hide = document.getElementById('bbHideCheckbox')?.checked || false;
    // body: JSON.stringify({ type, id, reason, details, mute_user: mute, hide_post: hide })

    fetch('/report', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ type, id, reason, details, mute_user: mute })
    })
    .then(r => r.json())
    .then(d => {
        bootstrap.Modal.getInstance(document.getElementById('bbReportModal'))?.hide();
        if (typeof Swal !== 'undefined') {
            Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2800 })
                .fire({ icon: d.success ? 'success' : 'warning', title: d.message });
        } else alert(d.message);

        if (d.success && type === 'post' && hide) {
            document.getElementById('postCard-' + id)?.remove();
        }
    })
    .catch(() => alert('Network error.'));
}
</script>
@endonce