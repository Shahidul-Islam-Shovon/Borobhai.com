@php
    use Illuminate\Support\Facades\Auth;
@endphp

<div class="card mb-3 fb-post-card shadow-sm border-0 rounded-3"
     id="postCard-{{ $post->id }}"
     data-bg-color="{{ $post->bg_color }}">
    <div class="card-body p-3">

        {{-- Post Header --}}
        <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="d-flex align-items-center gap-2">
                <div class="author-avatar-zone bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold"
                     style="width:38px;height:38px;">
                    {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <h6 class="m-0 fw-bold text-dark author-name-zone" style="font-size:14px;">{{ $post->user->name }}</h6>
                    <small class="text-muted" style="font-size:11px;">{{ $post->updated_at->diffForHumans() }}</small>
                </div>
            </div>
            @if($post->user_id === Auth::id())
                <div class="dropdown">
                    <button class="btn btn-link text-muted p-0 border-0 shadow-none" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow p-1">
                        <li>
                            <a class="dropdown-item py-1 fs-7" href="javascript:void(0)"
                                onclick="prepareEditModal(this)"
                                data-id="{{ $post->id }}"
                                data-content="{{ $post->content }}"
                                data-bg-color="{{ $post->bg_color }}"
                                data-images="{{ json_encode($post->images) }}"
                                data-video="{{ is_array($post->video) ? json_encode($post->video) : $post->video }}"
                                data-is-shared="{{ $post->parent_id ? '1' : '0' }}">
                                <i class="bi bi-pencil me-1"></i> Edit Post
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-1 fs-7 text-danger" href="javascript:void(0)"
                                onclick="deletePost({{ $post->id }})">
                                <i class="bi bi-trash me-1"></i> Delete
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

        <div class="{{ $renderBg ? 'p-4 rounded text-center text-white fw-bold d-flex align-items-center justify-content-center fb-colored-post-render '.$post->bg_color : 'p-0 text-start' }}"
             style="{{ $renderBg ? 'min-height:200px;font-size:22px;' : 'font-size:14px;' }}">
            <p class="mb-0 dynamic-caption">{!! nl2br(e($post->content)) !!}</p>
        </div>

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
                    if (!empty($cleanVid)) $mediaItems[] = ['type' => 'video', 'url' => asset('storage/'.$cleanVid)];
                }
            }
            $mediaCount        = count($mediaItems);
            $escapedImagesJson = json_encode($mediaItems, JSON_HEX_APOS | JSON_HEX_QUOT);
        @endphp

        @if($mediaCount > 0)
        <div class="mt-2 overflow-hidden rounded mb-3 dynamic-media-container-zone"
             style="background:#000;"
             data-media-json="{{ $escapedImagesJson }}">

            @if($mediaCount === 1)
                <div class="position-relative bg-black d-flex align-items-center justify-content-center"
                     style="max-height:400px;min-height:260px;">
                    @if($mediaItems[0]['type'] === 'image')
                        <img src="{{ $mediaItems[0]['url'] }}" class="w-100 cursor-pointer"
                             style="max-height:400px;object-fit:contain;"
                             onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),0)">
                    @else
                        <video src="{{ $mediaItems[0]['url'] }}" preload="metadata" class="w-100 cursor-pointer"
                               style="max-height:400px;object-fit:contain;"
                               onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),0)"></video>
                        <div class="position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center rounded-circle"
                             style="width:56px;height:56px;background:rgba(0,0,0,0.6);pointer-events:none;">
                            <i class="bi bi-play-fill text-white" style="font-size:1.8rem;margin-left:4px;"></i>
                        </div>
                    @endif
                </div>

            @elseif($mediaCount === 2)
                <div class="d-flex" style="height:280px;gap:2px;">
                    @foreach($mediaItems as $i => $media)
                        <div class="position-relative bg-black overflow-hidden" style="flex:1;">
                            @if($media['type'] === 'image')
                                <img src="{{ $media['url'] }}" class="w-100 h-100 object-fit-cover cursor-pointer"
                                     onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $i }})">
                            @else
                                <video src="{{ $media['url'] }}" preload="metadata" class="w-100 h-100 cursor-pointer"
                                       style="object-fit:cover;"
                                       onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $i }})"></video>
                                <div class="position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center rounded-circle"
                                     style="width:48px;height:48px;background:rgba(0,0,0,0.6);pointer-events:none;">
                                    <i class="bi bi-play-fill text-white" style="font-size:1.5rem;margin-left:3px;"></i>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

            @elseif($mediaCount === 3)
                <div class="d-flex" style="height:320px;gap:2px;">
                    <div class="position-relative bg-black overflow-hidden" style="flex:2;">
                        @if($mediaItems[0]['type'] === 'image')
                            <img src="{{ $mediaItems[0]['url'] }}" class="w-100 h-100 object-fit-cover cursor-pointer"
                                 onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),0)">
                        @else
                            <video src="{{ $mediaItems[0]['url'] }}" preload="metadata" class="w-100 h-100 cursor-pointer"
                                   style="object-fit:cover;"
                                   onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),0)"></video>
                            <div class="position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center rounded-circle"
                                 style="width:56px;height:56px;background:rgba(0,0,0,0.6);pointer-events:none;">
                                <i class="bi bi-play-fill text-white" style="font-size:1.8rem;margin-left:4px;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="d-flex flex-column" style="flex:1;gap:2px;">
                        @foreach([1,2] as $i)
                            <div class="position-relative bg-black overflow-hidden" style="flex:1;">
                                @if($mediaItems[$i]['type'] === 'image')
                                    <img src="{{ $mediaItems[$i]['url'] }}" class="w-100 h-100 object-fit-cover cursor-pointer"
                                         onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $i }})">
                                @else
                                    <video src="{{ $mediaItems[$i]['url'] }}" preload="metadata" class="w-100 h-100 cursor-pointer"
                                           style="object-fit:cover;"
                                           onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $i }})"></video>
                                    <div class="position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center rounded-circle"
                                         style="width:40px;height:40px;background:rgba(0,0,0,0.6);pointer-events:none;">
                                        <i class="bi bi-play-fill text-white" style="font-size:1.2rem;margin-left:3px;"></i>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

            @elseif($mediaCount === 4)
                <div class="d-flex flex-wrap" style="height:480px;gap:2px;">
                    @foreach($mediaItems as $i => $media)
                        <div class="position-relative bg-black overflow-hidden"
                             style="width:calc(50% - 1px);height:calc(50% - 1px);">
                            @if($media['type'] === 'image')
                                <img src="{{ $media['url'] }}" class="w-100 h-100 object-fit-cover cursor-pointer"
                                     onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $i }})">
                            @else
                                <video src="{{ $media['url'] }}" preload="metadata" class="w-100 h-100 cursor-pointer"
                                       style="object-fit:cover;"
                                       onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $i }})"></video>
                                <div class="position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center rounded-circle"
                                     style="width:44px;height:44px;background:rgba(0,0,0,0.6);pointer-events:none;">
                                    <i class="bi bi-play-fill text-white" style="font-size:1.4rem;margin-left:3px;"></i>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

            @else
                @php $visibleItems = array_slice($mediaItems,0,4); $remaining = $mediaCount-4; @endphp
                <div class="d-flex flex-wrap" style="height:480px;gap:2px;">
                    @foreach($visibleItems as $i => $media)
                        <div class="position-relative bg-black overflow-hidden"
                             style="width:calc(50% - 1px);height:calc(50% - 1px);">
                            @if($media['type'] === 'image')
                                <img src="{{ $media['url'] }}" class="w-100 h-100 object-fit-cover cursor-pointer"
                                     onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $i }})">
                            @else
                                <video src="{{ $media['url'] }}" preload="metadata" class="w-100 h-100 cursor-pointer"
                                       style="object-fit:cover;"
                                       onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $i }})"></video>
                                <div class="position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center rounded-circle"
                                     style="width:44px;height:44px;background:rgba(0,0,0,0.6);pointer-events:none;">
                                    <i class="bi bi-play-fill text-white" style="font-size:1.4rem;margin-left:3px;"></i>
                                </div>
                            @endif
                            @if($i === 3 && $remaining > 0)
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center text-white fw-bold cursor-pointer"
                                     style="background:rgba(0,0,0,0.55);font-size:2rem;"
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
                    if (is_array($pVids)) { foreach ($pVids as $v) $parentMedia[] = ['type'=>'video','url'=>asset('storage/'.$v)]; }
                    else { $parentMedia[] = ['type'=>'video','url'=>asset('storage/'.$post->parentPost->video)]; }
                }
                $pCount          = count($parentMedia);
                $pHasImages      = !empty($post->parentPost->images);
                $pHasVideo       = !empty($post->parentPost->video);
                $pRenderBg       = $post->parentPost->bg_color && !$pHasImages && !$pHasVideo;
                $parentMediaJson = json_encode($parentMedia, JSON_HEX_APOS | JSON_HEX_QUOT);
            @endphp
            <div class="mt-3 p-3 border rounded bg-light border-light-subtle text-start">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold"
                         style="width:28px;height:28px;font-size:11px;">
                        {{ strtoupper(substr($post->parentPost->user->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <h6 class="m-0 fw-bold text-dark" style="font-size:12px;">{{ $post->parentPost->user->name }}</h6>
                        <small class="text-muted" style="font-size:10px;">{{ $post->parentPost->created_at->diffForHumans() }}</small>
                    </div>
                </div>

                {{-- Parent Caption --}}
                <div class="{{ $pRenderBg ? 'p-3 rounded text-center text-white fw-bold '.$post->parentPost->bg_color : 'p-0 text-start' }}"
                     style="{{ $pRenderBg ? 'min-height:120px;font-size:16px;' : 'font-size:13px;' }}">
                    <p class="mb-0">{!! nl2br(e($post->parentPost->content)) !!}</p>
                </div>

                {{-- Parent Media Grid (Facebook-style mini) --}}
                @if($pCount > 0)
                <div class="mt-2 overflow-hidden rounded" style="background:#000;" data-media-json="{{ $parentMediaJson }}">
                    @if($pCount === 1)
                        <div class="position-relative d-flex align-items-center justify-content-center bg-black" style="max-height:220px;min-height:120px;">
                            @if($parentMedia[0]['type'] === 'image')
                                <img src="{{ $parentMedia[0]['url'] }}" class="w-100 cursor-pointer"
                                     style="max-height:220px;object-fit:contain;"
                                     onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),0)">
                            @else
                                <video src="{{ $parentMedia[0]['url'] }}" preload="metadata" class="w-100 cursor-pointer"
                                       style="max-height:220px;object-fit:contain;"
                                       onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),0)"></video>
                                <div class="position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center rounded-circle"
                                     style="width:44px;height:44px;background:rgba(0,0,0,0.6);pointer-events:none;">
                                    <i class="bi bi-play-fill text-white" style="font-size:1.3rem;margin-left:3px;"></i>
                                </div>
                            @endif
                        </div>
                    @elseif($pCount === 2)
                        <div class="d-flex" style="height:160px;gap:2px;">
                            @foreach($parentMedia as $pIdx => $pm)
                                <div class="position-relative bg-black overflow-hidden" style="flex:1;">
                                    @if($pm['type'] === 'image')
                                        <img src="{{ $pm['url'] }}" class="w-100 h-100 object-fit-cover cursor-pointer"
                                             onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $pIdx }})">
                                    @else
                                        <video src="{{ $pm['url'] }}" preload="metadata" class="w-100 h-100 cursor-pointer"
                                               style="object-fit:cover;"
                                               onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $pIdx }})"></video>
                                        <div class="position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center rounded-circle"
                                             style="width:36px;height:36px;background:rgba(0,0,0,0.6);pointer-events:none;">
                                            <i class="bi bi-play-fill text-white" style="font-size:1rem;margin-left:2px;"></i>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        @php $pShow = min($pCount,3); $pMore = $pCount - $pShow; @endphp
                        <div class="d-flex" style="height:160px;gap:2px;">
                            @for($pIdx = 0; $pIdx < $pShow; $pIdx++)
                                @php $pm = $parentMedia[$pIdx]; @endphp
                                <div class="position-relative bg-black overflow-hidden" style="flex:1;">
                                    @if($pm['type'] === 'image')
                                        <img src="{{ $pm['url'] }}" class="w-100 h-100 object-fit-cover cursor-pointer"
                                             onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $pIdx }})">
                                    @else
                                        <video src="{{ $pm['url'] }}" preload="metadata" class="w-100 h-100 cursor-pointer"
                                               style="object-fit:cover;"
                                               onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),{{ $pIdx }})"></video>
                                        <div class="position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center rounded-circle"
                                             style="width:32px;height:32px;background:rgba(0,0,0,0.6);pointer-events:none;">
                                            <i class="bi bi-play-fill text-white" style="font-size:.9rem;margin-left:2px;"></i>
                                        </div>
                                    @endif
                                    @if($pIdx === $pShow - 1 && $pMore > 0)
                                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center text-white fw-bold cursor-pointer"
                                             style="background:rgba(0,0,0,0.55);font-size:1.5rem;"
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
        <div class="d-flex justify-content-between text-muted small px-1 mt-3">
            <div id="like-zone-{{ $post->id }}">
                @if($post->likes->count() > 0)
                    <i class="bi bi-heart-fill text-danger"></i>
                    <span class="like-count-text">{{ $post->likes->count() }} Likes</span>
                @endif
            </div>
             <div>
                <span class="cursor-pointer" id="comment-count-{{ $post->id }}"
                      onclick="openCommentModal({{ $post->id }})">{{ $post->comments_count ?? $post->comments->count() }} Comments</span>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="mt-2 d-flex justify-content-between text-muted border-top border-bottom py-1 fs-7">
            <button type="button"
                    class="btn btn-link btn-sm text-decoration-none {{ $post->likes->contains('user_id', Auth::id()) ? 'text-primary fw-bold' : 'text-muted' }}"
                    id="likeBtn-{{ $post->id }}" onclick="toggleLike({{ $post->id }})">
                <i class="bi {{ $post->likes->contains('user_id', Auth::id()) ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up' }}"></i> Like
            </button>

             <button type="button" class="btn btn-link btn-sm text-decoration-none text-muted"
                    onclick="openCommentModal({{ $post->id }})">
                <i class="bi bi-chat-right-text"></i> Comment
            </button>

            <button type="button" class="btn btn-link btn-sm text-decoration-none text-muted"
                    onclick="openShareModal({{ $post->id }})">
                <i class="bi bi-reply-all-fill" style="transform:scaleX(-1);display:inline-block;"></i> Share
            </button>
        </div>

        

    </div>
</div>