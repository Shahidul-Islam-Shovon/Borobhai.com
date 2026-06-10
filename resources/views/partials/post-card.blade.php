@php
    use Illuminate\Support\Facades\Auth;
@endphp

<div class="bb-post-card" id="postCard-{{ $post->id }}" data-bg-color="{{ $post->bg_color }}">

    {{-- Post Header --}}
    <div class="bb-post-head">
        <div class="bb-head-left">
            <a href="{{ $post->user_id === Auth::id() ? route('profile.show') : route('profile.view', $post->user_id) }}" class="bb-avatar author-avatar-zone" style="text-decoration:none;" title="View profile">
                @if($post->user->profile_picture)
                    <img src="{{ asset('storage/'.$post->user->profile_picture) }}" alt="{{ $post->user->name }}" class="bb-avatar-img">
                @else
                    {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                @endif
            </a>
            <div class="bb-head-meta">
                <a href="{{ $post->user_id === Auth::id() ? route('profile.show') : route('profile.view', $post->user_id) }}" class="bb-author author-name-zone bb-author-link">{{ $post->user->name }}</a>
                <span class="bb-time"><i class="bi bi-globe-americas"></i> {{ $post->updated_at->diffForHumans() }}</span>
            </div>
        </div>
        @if($post->user_id === Auth::id())
            <div class="dropdown">
                <button class="bb-more-btn" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-1">
                    <li>
                        <a class="dropdown-item py-2 px-3 rounded" href="javascript:void(0)"
                            onclick="prepareEditModal(this)"
                            data-id="{{ $post->id }}"
                            data-content="{{ $post->content }}"
                            data-bg-color="{{ $post->bg_color }}"
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
        @endif
    </div>

    {{-- Post Caption --}}
    @php
        $hasImages      = is_array($post->images) && count($post->images) > 0;
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
                {{-- Inline playable video (Facebook-style) --}}
                <div class="bb-video-wrap">
                    <video src="{{ $mediaItems[0]['url'] }}" class="bb-inline-video" preload="metadata" controls playsinline></video>
                    <button type="button" class="bb-expand-btn" title="Expand"
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
                class="bb-action-btn {{ $post->likes->contains('user_id', Auth::id()) ? 'active-like' : '' }}"
                id="likeBtn-{{ $post->id }}" onclick="toggleLike({{ $post->id }})">
            <i class="bi {{ $post->likes->contains('user_id', Auth::id()) ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up' }}"></i>
            <span>Like</span>
        </button>
        <button type="button" class="bb-action-btn" onclick="openCommentModal({{ $post->id }})">
            <i class="bi bi-chat-square-text"></i>
            <span>Comment</span>
        </button>
        <button type="button" class="bb-action-btn" onclick="openShareModal({{ $post->id }})">
            <i class="bi bi-share"></i>
            <span>Share</span>
        </button>
        <button type="button"
                class="bb-action-btn {{ $isSaved ? 'active-save' : '' }}"
                id="saveBtn-{{ $post->id }}" onclick="toggleSave({{ $post->id }})">
            <i class="bi {{ $isSaved ? 'bi-bookmark-fill' : 'bi-bookmark' }}" id="saveIcon-{{ $post->id }}"></i>
            <span id="saveText-{{ $post->id }}">{{ $isSaved ? 'Saved' : 'Save' }}</span>
        </button>
    </div>

</div>