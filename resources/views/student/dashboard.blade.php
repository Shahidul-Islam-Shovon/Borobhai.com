<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <title>Borobhai.com</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background-color: #f0f2f5; color: #1c1e21; }
        .navbar { background-color: #fff; box-shadow: 0 2px 4px rgba(0,0,0,.08); padding: .5rem 1rem; }
        .navbar-brand { font-weight: 700; color: #1877f2; font-size: 1.5rem; letter-spacing: -.5px; }
        .search-box { background-color: #f0f2f5; border-radius: 50px; padding: .5rem 1rem; display: flex; align-items: center; width: 240px; }
        .search-box input { background: transparent; border: none; outline: none; margin-left: 8px; font-size: .9rem; width: 100%; }
        .nav-icon-btn { width: 40px; height: 40px; border-radius: 50px; background-color: #e4e6eb; display: flex; align-items: center; justify-content: center; color: #050505; text-decoration: none; font-size: 1.2rem; border: none; }
        .nav-icon-btn:hover { background-color: #d8dadf; color: #050505; }
        .sidebar-link { display: flex; align-items: center; gap: 12px; padding: .5rem .75rem; color: #050505; text-decoration: none; font-weight: 600; font-size: .95rem; border-radius: 8px; }
        .sidebar-link:hover { background-color: #e4e6eb; }
        .sidebar-link.active { color: #1877f2; }
        .fb-post-card { background-color: #fff; border-radius: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.2); }
        .create-post-box { background-color: #fff; border-radius: 8px; box-shadow: 0 1px 2px rgba(0,0,0,0.2); padding: 1rem; }
        .create-post-avatar { width: 40px; height: 40px; border-radius: 50%; background-color: #65676b; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; }
        .mock-input { background-color: #f0f2f5; border-radius: 20px; padding: .5rem 1rem; color: #65676b; cursor: pointer; flex-grow: 1; }
        .mock-input:hover { background-color: #e4e6eb; }
        .post-action-btn { flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px; padding: .5rem; color: #65676b; text-decoration: none; font-weight: 600; font-size: .9rem; border-radius: 4px; }
        .post-action-btn:hover { background-color: #f2f2f2; }
        .fs-7 { font-size: 0.85rem !important; }
        .cursor-pointer { cursor: pointer !important; }
        .fb-bg-gradient-1 { background: linear-gradient(45deg, #f321d7, #2196f3) !important; }
        .fb-bg-gradient-2 { background: linear-gradient(45deg, #ff9800, #ff5722) !important; }
        .fb-bg-gradient-3 { background: linear-gradient(45deg, #4caf50, #00bcd4) !important; }
        .fb-bg-gradient-4 { background: linear-gradient(45deg, #1a2a6c, #b21f1f, #fdbb2d) !important; }
        .fb-bg-gradient-5 { background: linear-gradient(45deg, #00c6ff, #0072ff) !important; }
        .fb-color-circle { width: 28px; height: 28px; border-radius: 50%; display: inline-block; cursor: pointer; border: 2px solid #fff; box-shadow: 0 0 4px rgba(0,0,0,0.2); }
        .fb-colored-post-render { transition: all .3s ease; }
        /* Lightbox always on top */
        #imageLightboxModal { z-index: 1090 !important; }
        .lightbox-backdrop { z-index: 1085 !important; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-md sticky-top">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-2">
            <a style="color:black;" class="navbar-brand m-0" href="#">Borobhai.com</a>
            <div class="search-box d-none d-lg-flex">
                <i class="bi bi-search text-muted"></i>
                <input type="text" placeholder="Search In Borobhai">
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 ms-auto">
            <a href="#" class="nav-icon-btn d-md-none"><i class="bi bi-search"></i></a>
            <a href="#" class="nav-icon-btn"><i class="bi bi-messenger"></i></a>
            <a href="#" class="nav-icon-btn"><i class="bi bi-bell-fill"></i></a>
            <div class="dropdown">
                <button class="nav-icon-btn border-0" data-bs-toggle="dropdown">
                    <i class="bi bi-person-fill"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><span class="dropdown-item-text fw-bold text-dark">{{ Auth::user()->name }}</span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="container-fluid mt-3">
    <div class="row px-md-2">

        {{-- Sidebar --}}
        <div class="col-md-3 d-none d-md-block position-sticky" style="top:70px;height:fit-content;">
            <div class="d-flex flex-column gap-1">
                <a href="#" class="sidebar-link active"><i class="bi bi-house-door-fill text-primary"></i><span>Home</span></a>
                <a href="#" class="sidebar-link"><i class="bi bi-people-fill text-info"></i><span>Friends</span></a>
                <a href="#" class="sidebar-link"><i class="bi bi-bookmark-heart-fill text-warning"></i><span>Saved</span></a>
            </div>
        </div>

        {{-- Feed --}}
        <div class="col-12 col-md-6">

            {{-- Create Post Box --}}
            <div class="create-post-box mb-3">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="create-post-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</div>
                    <div class="mock-input" onclick="resetPostBg();" data-bs-toggle="modal" data-bs-target="#createPostModal">
                        What's on your mind, {{ explode(' ', Auth::user()->name)[0] }}?
                    </div>
                </div>
                <hr class="my-2">
                <div class="d-flex justify-content-between pt-1">
                    <button type="button" class="btn btn-link post-action-btn border-0 bg-transparent text-decoration-none shadow-none"
                        data-bs-toggle="modal" data-bs-target="#createPostModal"
                        onclick="resetPostBg(); setTimeout(()=>document.getElementById('postImageInput').click(),400);">
                        <i class="bi bi-images text-success fs-5"></i>
                        <span class="text-muted fs-7 fw-semibold">Photo/video</span>
                    </button>
                    <button type="button" class="btn btn-link post-action-btn border-0 bg-transparent text-decoration-none shadow-none"
                        data-bs-toggle="modal" data-bs-target="#createPostModal" onclick="toggleColorPlates();">
                        <i class="bi bi-palette-fill text-danger fs-5"></i>
                        <span class="text-muted fs-7 fw-semibold">Background color</span>
                    </button>
                </div>
            </div>

            {{-- Posts Feed --}}
            <div id="postsFeedContainer">
                @forelse($posts as $post)
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
                                        <small class="text-muted" style="font-size:11px;">{{ $post->created_at->diffForHumans() }}</small>
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
                                                    data-video="{{ is_array($post->video) ? json_encode($post->video) : $post->video }}">
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
                                          onclick="toggleComments({{ $post->id }})">{{ $post->comments->count() }} Comments</span>
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
                                        onclick="toggleComments({{ $post->id }})">
                                    <i class="bi bi-chat-right-text"></i> Comment
                                </button>
                                <button type="button" class="btn btn-link btn-sm text-decoration-none text-muted"
                                        onclick="openShareModal({{ $post->id }})">
                                    <i class="bi bi-reply-all-fill" style="transform:scaleX(-1);display:inline-block;"></i> Share
                                </button>
                            </div>

                            {{-- Comments --}}
                            <div id="commentZone-{{ $post->id }}" class="mt-2 d-none">
                                <form onsubmit="submitComment(event, {{ $post->id }})"
                                      class="d-flex align-items-center gap-2 pt-2 px-3 pb-3 border-top">
                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold small flex-shrink-0"
                                         style="width:32px;height:32px;font-size:12px;">
                                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="input-group align-items-center bg-light rounded-pill px-3 py-1 w-100 border">
                                        <input type="text" id="commentInput-{{ $post->id }}"
                                               class="form-control border-0 bg-transparent shadow-none py-1 fs-7"
                                               placeholder="Write a comment..." style="font-size:13px;">
                                        <button type="submit" class="btn btn-link p-0 text-primary ms-2 shadow-none border-0 d-flex align-items-center">
                                            <i class="bi bi-send-fill" style="font-size:16px;"></i>
                                        </button>
                                    </div>
                                </form>
                                <div id="commentList-{{ $post->id }}" class="mt-1">
                                    @forelse($post->comments as $comment)
                                        <div class="bg-light p-2 px-3 rounded-4 mb-2 d-flex justify-content-between align-items-start"
                                             id="comment-container-{{ $comment->id }}">
                                            <div class="flex-grow-1">
                                                <strong class="small text-dark d-block" style="font-size:12px;">{{ $comment->user->name }}</strong>
                                                <span class="small" id="comment-text-{{ $comment->id }}" style="font-size:13px;">{{ $comment->content }}</span>
                                            </div>
                                            @if($comment->user_id === Auth::id())
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-link btn-sm text-muted p-0 border-0 shadow-none"
                                                            data-bs-toggle="dropdown">
                                                        <i class="bi bi-three-dots"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm p-1" style="min-width:100px;">
                                                        <li><a class="dropdown-item py-1 fs-7" href="javascript:void(0)"
                                                               onclick="editComment(event, {{ $comment->id }})">
                                                            <i class="bi bi-pencil me-1"></i> Edit</a></li>
                                                        <li><a class="dropdown-item py-1 fs-7 text-danger" href="javascript:void(0)"
                                                               onclick="deleteComment({{ $comment->id }}, {{ $post->id }})">
                                                            <i class="bi bi-trash me-1"></i> Delete</a></li>
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                    @empty
                                        <div class="text-center text-muted py-2 small dynamic-no-comment-{{ $post->id }}">No comments yet.</div>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="card p-5 text-center shadow-sm border-0 rounded-3 my-3 bg-white">
                        <div class="card-body">
                            <i class="bi bi-newspaper fs-1 text-muted d-block mb-3"></i>
                            <h5 class="fw-bold text-secondary">No Posts Yet</h5>
                            <p class="text-muted small mb-0">Share something to start the conversation!</p>
                        </div>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</div>

{{-- ==================== MODALS ==================== --}}

{{-- Create Post Modal --}}
<div class="modal fade" id="createPostModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title fw-bold mx-auto">Create Post</h5>
                <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" onclick="resetPostBg();"></button>
            </div>
            <form id="ajaxPostForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="create-post-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</div>
                        <div>
                            <h6 class="m-0 fw-bold" style="font-size:14px;">{{ Auth::user()->name }}</h6>
                            <span class="badge bg-light text-muted border py-1 px-2" style="font-size:10px;">
                                <i class="bi bi-globe-americas me-1"></i>Public
                            </span>
                        </div>
                    </div>
                    <div id="postInputWrapper" class="p-1 rounded bg-transparent">
                        <textarea id="postContent" name="content"
                                  class="form-control border-0 bg-transparent shadow-none"
                                  rows="4" placeholder="Start a post..." style="resize:none;font-size:14px;"></textarea>
                    </div>
                    <div id="colorPlatesZone" class="my-2 d-none p-1 bg-light border rounded">
                        <div class="d-flex gap-1 align-items-center">
                            <span class="fb-color-circle bg-dark" onclick="resetPostBg();" title="Reset"></span>
                            <span class="fb-color-circle fb-bg-gradient-1" onclick="selectPostBg('fb-bg-gradient-1')"></span>
                            <span class="fb-color-circle fb-bg-gradient-2" onclick="selectPostBg('fb-bg-gradient-2')"></span>
                            <span class="fb-color-circle fb-bg-gradient-3" onclick="selectPostBg('fb-bg-gradient-3')"></span>
                            <span class="fb-color-circle fb-bg-gradient-4" onclick="selectPostBg('fb-bg-gradient-4')"></span>
                            <span class="fb-color-circle fb-bg-gradient-5" onclick="selectPostBg('fb-bg-gradient-5')"></span>
                        </div>
                    </div>
                    <input type="hidden" name="bg_color" id="bg_color_input">
                    <input type="file" id="postImageInput" class="d-none" multiple accept="image/*,video/*">
                    <div id="imagePreviewContainer" class="row g-1 my-2 d-none"></div>
                    <div class="border rounded p-2 d-flex justify-content-between align-items-center mt-3">
                        <span class="small fw-bold text-muted ps-1">Add to your post</span>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-light btn-sm rounded-circle p-2" id="triggerUploadBtn">
                                <i class="bi bi-images text-success"></i>
                            </button>
                            <button type="button" class="btn btn-light btn-sm rounded-circle p-2" onclick="toggleColorPlates();">
                                <i class="bi bi-palette text-danger"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="submit" id="submitBtn" class="btn btn-primary w-100 fw-bold py-2">Post</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Post Modal --}}
<div class="modal fade" id="editPostModal" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-1">
                <h5 class="modal-title fw-bold mx-auto" style="font-size:17px;">Edit Post</h5>
                <button type="button" class="btn-close ms-0" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPostForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="editPostId">
                <input type="hidden" id="edit_bg_color_input">
                <div class="modal-body pb-1">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="create-post-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</div>
                        <div>
                            <h6 class="m-0 fw-bold" style="font-size:14px;">{{ Auth::user()->name }}</h6>
                            <span class="badge bg-light text-muted border py-1 px-2" style="font-size:10px;">
                                <i class="bi bi-globe-americas me-1"></i>Public
                            </span>
                        </div>
                    </div>
                    <div id="editPostInputWrapper" class="p-1 rounded bg-transparent mb-2">
                        <textarea id="editPostContent" name="content"
                                  class="form-control border-0 bg-transparent shadow-none"
                                  rows="4" placeholder="What's on your mind?" style="resize:none;font-size:14px;"></textarea>
                    </div>
                    <div id="editColorPlatesZone" class="my-2 d-none p-2 bg-light border rounded">
                        <div class="d-flex gap-1 align-items-center flex-wrap">
                            <span class="fb-color-circle bg-dark" onclick="resetEditPostBg()"></span>
                            <span class="fb-color-circle fb-bg-gradient-1" onclick="selectEditPostBg('fb-bg-gradient-1')"></span>
                            <span class="fb-color-circle fb-bg-gradient-2" onclick="selectEditPostBg('fb-bg-gradient-2')"></span>
                            <span class="fb-color-circle fb-bg-gradient-3" onclick="selectEditPostBg('fb-bg-gradient-3')"></span>
                            <span class="fb-color-circle fb-bg-gradient-4" onclick="selectEditPostBg('fb-bg-gradient-4')"></span>
                            <span class="fb-color-circle fb-bg-gradient-5" onclick="selectEditPostBg('fb-bg-gradient-5')"></span>
                        </div>
                    </div>
                    <div id="editMediaPreviewContainer" class="row g-1 mb-2"></div>
                    <input type="file" id="editMediaInput" name="media[]" multiple class="d-none" accept="image/*,video/*">
                    <div class="border rounded p-2 d-flex justify-content-between align-items-center mt-2">
                        <span class="small fw-bold text-muted ps-1">Add to your post</span>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-light btn-sm rounded-circle p-2"
                                    onclick="document.getElementById('editMediaInput').click()">
                                <i class="bi bi-images text-success"></i>
                            </button>
                            <button type="button" class="btn btn-light btn-sm rounded-circle p-2"
                                    onclick="toggleEditColorPlates()">
                                <i class="bi bi-palette text-danger"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-1">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="editSubmitBtn" class="btn btn-primary btn-sm px-4 fw-bold">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Comment Modal --}}
<div class="modal fade" id="editCommentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-3 border-0 shadow">
            <div class="modal-header pb-0 border-bottom-0">
                <h6 class="modal-title fw-bold">Edit Comment</h6>
                <button type="button" class="btn-close small" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-2">
                <input type="hidden" id="editTargetCommentId">
                <textarea id="editCommentInput" class="form-control form-control-sm" rows="2" style="resize:none;font-size:13px;"></textarea>
            </div>
            <div class="modal-footer pt-0 border-top-0 justify-content-end gap-1">
                <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" onclick="submitUpdateComment()">Update</button>
            </div>
        </div>
    </div>
</div>

{{-- Share Modal --}}
<div class="modal fade" id="fbShareModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold mx-auto" style="font-size:17px;">Share Post</h5>
                <button type="button" class="btn-close ms-0" data-bs-dismiss="modal"></button>
            </div>
            <form id="fbShareForm">
                <div class="modal-body">
                    <input type="hidden" id="targetSharePostId">
                    <textarea id="shareComment" class="form-control border-0 shadow-none ps-0" rows="2"
                              placeholder="Say something..." style="resize:none;font-size:14px;"></textarea>
                    <div id="modalPostPreview" class="p-3 border rounded bg-white text-start mt-2"></div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" onclick="closeShareModal()">Cancel</button>
                    <button type="submit" id="shareSubmitBtn" class="btn btn-primary btn-sm px-4">Share Now</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Lightbox Modal --}}
<div class="modal fade" id="imageLightboxModal" tabindex="-1" style="background:rgba(0,0,0,0.92);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0 position-relative">
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                    data-bs-dismiss="modal" style="z-index:50;"></button>
            <div class="modal-body p-0">
                <div id="lightboxCarousel" class="carousel slide"
                     data-bs-ride="false" data-bs-touch="false" data-bs-interval="false">
                    <div class="carousel-inner" id="lightboxInner"></div>
                </div>
            </div>
            <div class="d-flex justify-content-center align-items-center gap-3 py-3" id="lightboxNavBar">
                <button type="button"
                        class="btn btn-light rounded-circle d-flex align-items-center justify-content-center"
                        style="width:44px;height:44px;" onclick="lightboxPrev()">
                    <i class="bi bi-chevron-left fw-bold"></i>
                </button>
                <span id="lightboxCounter" class="text-white small fw-bold"></span>
                <button type="button"
                        class="btn btn-light rounded-circle d-flex align-items-center justify-content-center"
                        style="width:44px;height:44px;" onclick="lightboxNext()">
                    <i class="bi bi-chevron-right fw-bold"></i>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ==================== SCRIPTS ==================== --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ==========================================
// GLOBAL STATE
// ==========================================
let selectedMediaFiles        = [];
let bootstrapEditModal        = null;
let bootstrapShareModal       = null;
let bootstrapLightboxModal    = null;
let bootstrapCommentEditModal = null;
let isUploading               = false;
let removedImages             = [];
let removedVideos             = [];
let editSelectedFiles         = [];

// ==========================================
// INIT
// ==========================================
document.addEventListener("DOMContentLoaded", function () {
    // Post/Share পরে reload হলে top এ scroll করো
    if (sessionStorage.getItem('scrollToTop')) {
        sessionStorage.removeItem('scrollToTop');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    bootstrapEditModal        = new bootstrap.Modal(document.getElementById('editPostModal'));
    bootstrapShareModal       = new bootstrap.Modal(document.getElementById('fbShareModal'));
    bootstrapLightboxModal    = new bootstrap.Modal(document.getElementById('imageLightboxModal'));
    const ecEl = document.getElementById('editCommentModal');
    if (ecEl) bootstrapCommentEditModal = new bootstrap.Modal(ecEl);

    // Pause videos on slide — registered ONCE
    document.getElementById('lightboxCarousel').addEventListener('slide.bs.carousel', function () {
        document.querySelectorAll('#lightboxInner video').forEach(v => v.pause());
    });
    // Pause videos on modal close — registered ONCE
    document.getElementById('imageLightboxModal').addEventListener('hidden.bs.modal', function () {
        document.querySelectorAll('#lightboxInner video').forEach(v => v.pause());
    });
    // Update counter on slide — registered ONCE, reads total from data attr
    document.getElementById('lightboxCarousel').addEventListener('slid.bs.carousel', function (ev) {
        const counter = document.getElementById('lightboxCounter');
        if (counter && counter.dataset.total) {
            counter.textContent = `${ev.to + 1} / ${counter.dataset.total}`;
        }
    });

    // Facebook color: auto-remove if text is long (>80 chars)
    document.getElementById('postContent').addEventListener('input', function () {
        const bgInp = document.getElementById('bg_color_input');
        if (bgInp && bgInp.value && this.value.length > 80) {
            resetPostBg();
        }
    });
});

// ==========================================
// RELOAD WARNING
// ==========================================
window.addEventListener('beforeunload', function (e) {
    if (isUploading) { e.preventDefault(); e.returnValue = ''; }
});

// ==========================================
// LIGHTBOX
// ==========================================
function openLightbox(mediaJson, index = 0) {
    try {
        const mediaItems = typeof mediaJson === 'string' ? JSON.parse(mediaJson) : mediaJson;
        const inner = document.getElementById('lightboxInner');
        if (!inner) return;
        inner.innerHTML = '';

        mediaItems.forEach((item, i) => {
            const slide = document.createElement('div');
            slide.className = `carousel-item ${i === index ? 'active' : ''}`;

            if (item.type === 'image') {
                const img = document.createElement('img');
                img.src = item.url;
                img.className = 'd-block w-100 object-fit-contain';
                img.style.maxHeight = '82vh';
                slide.appendChild(img);
            } else {
                // video wrapper above any potential overlaps
                const wrap = document.createElement('div');
                wrap.style.cssText = 'position:relative;z-index:20;display:flex;justify-content:center;';
                const video = document.createElement('video');
                video.src = item.url;
                video.controls = true;
                video.className = 'd-block w-100 object-fit-contain';
                video.style.cssText = 'max-height:82vh;position:relative;z-index:20;';
                // Stop ALL pointer events from reaching carousel
                ['click','mousedown','mouseup','pointerdown','pointerup','touchstart','touchend']
                    .forEach(evt => video.addEventListener(evt, e => e.stopPropagation()));
                wrap.appendChild(video);
                slide.appendChild(wrap);
            }
            inner.appendChild(slide);
        });

        // Reuse or init carousel
        const carouselEl = document.getElementById('lightboxCarousel');
        let ci = bootstrap.Carousel.getInstance(carouselEl);
        if (!ci) ci = new bootstrap.Carousel(carouselEl, { ride: false, touch: false, interval: false });
        if (index > 0) ci.to(index);

        // Counter
        const navBar  = document.getElementById('lightboxNavBar');
        const counter = document.getElementById('lightboxCounter');
        if (mediaItems.length <= 1) {
            if (navBar) navBar.style.display = 'none';
        } else {
            if (navBar) navBar.style.display = '';
            if (counter) {
                counter.dataset.total = mediaItems.length;
                counter.textContent   = `${index + 1} / ${mediaItems.length}`;
            }
        }

        if (bootstrapLightboxModal) bootstrapLightboxModal.show();
    } catch (e) { console.error("Lightbox error:", e); }
}

function lightboxPrev() {
    document.querySelectorAll('#lightboxInner video').forEach(v => v.pause());
    const ci = bootstrap.Carousel.getInstance(document.getElementById('lightboxCarousel'));
    if (ci) ci.prev();
}
function lightboxNext() {
    document.querySelectorAll('#lightboxInner video').forEach(v => v.pause());
    const ci = bootstrap.Carousel.getInstance(document.getElementById('lightboxCarousel'));
    if (ci) ci.next();
}

// ==========================================
// NEW POST: COLOR
// ==========================================
function toggleColorPlates() {
    const z = document.getElementById('colorPlatesZone');
    if (z) z.classList.toggle('d-none');
}
function selectPostBg(cls) {
    const w = document.getElementById('postInputWrapper');
    const t = document.getElementById('postContent');
    const b = document.getElementById('bg_color_input');
    if (w && t) {
        w.className = `p-4 rounded text-center text-white fw-bold d-flex align-items-center justify-content-center fb-colored-post-render ${cls}`;
        w.style.minHeight = '200px';
        t.style.cssText = 'font-size:22px;text-align:center;color:#fff;';
        t.placeholder = "What's on your mind?";
    }
    if (b) b.value = cls;
    selectedMediaFiles = [];
    renderMediaPreviews();
}
function resetPostBg() {
    const w = document.getElementById('postInputWrapper');
    const t = document.getElementById('postContent');
    const b = document.getElementById('bg_color_input');
    if (w) { w.className = 'p-1 rounded bg-transparent'; w.style.minHeight = 'auto'; }
    if (t) { t.style.cssText = 'font-size:14px;text-align:left;color:inherit;'; t.placeholder = 'Start a post...'; }
    if (b) b.value = '';
}

// ==========================================
// NEW POST: MEDIA PREVIEW
// ==========================================
const imageInput       = document.getElementById('postImageInput');
const previewContainer = document.getElementById('imagePreviewContainer');

document.getElementById('triggerUploadBtn')?.addEventListener('click', () => imageInput.click());

imageInput?.addEventListener('change', function () {
    const files = Array.from(this.files);
    for (let f of files) {
        if (f.size > 100 * 1024 * 1024) {
            Swal.fire({ icon: 'error', title: 'File too large!', text: `"${f.name}" max 100MB.` });
            this.value = ''; return;
        }
    }
    resetPostBg();
    files.forEach(f => selectedMediaFiles.push(f));
    renderMediaPreviews();
    this.value = '';
});

function renderMediaPreviews() {
    if (!previewContainer) return;
    previewContainer.innerHTML = '';
    if (!selectedMediaFiles.length) { previewContainer.classList.add('d-none'); return; }
    previewContainer.classList.remove('d-none');
    selectedMediaFiles.forEach((file, idx) => {
        const col = document.createElement('div');
        col.className = 'col-4 col-md-3 position-relative';
        col.style.height = '100px';
        let el;
        if (file.type.startsWith('video/')) {
            el = document.createElement('video');
            el.muted = true;
            const pi = document.createElement('div');
            pi.className = 'position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center rounded-circle';
            pi.style.cssText = 'width:32px;height:32px;background:rgba(0,0,0,0.6);pointer-events:none;z-index:5;';
            pi.innerHTML = '<i class="bi bi-play-fill text-white" style="font-size:.9rem;margin-left:2px;"></i>';
            col.appendChild(pi);
        } else {
            el = document.createElement('img');
        }
        el.src = URL.createObjectURL(file);
        el.className = 'w-100 h-100 object-fit-cover rounded border';
        const xBtn = document.createElement('button');
        xBtn.type = 'button';
        xBtn.className = 'btn btn-dark btn-sm position-absolute top-0 end-0 m-1 rounded-circle';
        xBtn.style.cssText = 'background:rgba(0,0,0,0.7);border:none;width:22px;height:22px;display:flex;align-items:center;justify-content:center;z-index:10;padding:0;';
        xBtn.innerHTML = '<i class="bi bi-x-lg" style="font-size:10px;color:#fff;"></i>';
        xBtn.addEventListener('click', e => { e.preventDefault(); selectedMediaFiles.splice(idx,1); renderMediaPreviews(); });
        col.appendChild(el); col.appendChild(xBtn);
        previewContainer.appendChild(col);
    });
}

// ==========================================
// OPTIMISTIC POST SUBMIT
// ==========================================
document.getElementById('ajaxPostForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    const content   = document.getElementById('postContent').value.trim();
    const bgColor   = document.getElementById('bg_color_input').value;
    const submitBtn = document.getElementById('submitBtn');
    const modal     = bootstrap.Modal.getInstance(document.getElementById('createPostModal'));

    if (!content && !selectedMediaFiles.length) {
        Swal.fire({ icon: 'warning', title: 'Empty Post!', text: 'কিছু লিখুন অথবা মিডিয়া দিন!' });
        return;
    }
    const captured = [...selectedMediaFiles];

    // Close modal + reset
    if (modal) modal.hide();
    document.getElementById('postContent').value = '';
    resetPostBg();
    selectedMediaFiles = [];
    renderMediaPreviews();
    submitBtn.disabled = false;

    // Placeholder
    const pid     = 'opt-' + Date.now();
    const uName   = '{{ Auth::user()->name }}';
    const uInit   = '{{ strtoupper(substr(Auth::user()->name ?? "U", 0, 1)) }}';
    const html    = `
    <div class="card mb-3 fb-post-card shadow-sm border-0 rounded-3" id="${pid}">
      <div class="card-body p-3">
        <div class="d-flex align-items-center gap-2 mb-3">
          <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:38px;height:38px;">${uInit}</div>
          <div>
            <h6 class="m-0 fw-bold" style="font-size:14px;">${uName}</h6>
            <small class="text-muted" style="font-size:11px;">
              <span class="spinner-border spinner-border-sm text-primary me-1" style="width:10px;height:10px;"></span>Posting...
            </small>
          </div>
        </div>
        ${bgColor
            ? `<div class="p-4 rounded text-center text-white fw-bold ${bgColor}" style="min-height:160px;font-size:22px;opacity:.85;"><p class="mb-0">${content.replace(/\n/g,'<br>')}</p></div>`
            : `<p class="mb-0 text-muted" style="font-size:14px;">${content.replace(/\n/g,'<br>')}</p>`}
        ${captured.length ? `<div class="mt-2 p-3 bg-light rounded text-center text-muted small"><i class="bi bi-cloud-upload text-primary fs-4 d-block mb-1"></i>${captured.length} file uploading...</div>` : ''}
        <div class="progress mt-3" style="height:4px;">
          <div id="bar-${pid}" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width:5%;"></div>
        </div>
      </div>
    </div>`;

    const feed = document.getElementById('postsFeedContainer');
    if (feed) feed.insertAdjacentHTML('afterbegin', html);

    // Modal পুরো বন্ধ হওয়ার পরে scroll করো
    const createModalEl = document.getElementById('createPostModal');
    createModalEl.addEventListener('hidden.bs.modal', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }, { once: true });

    const fd = new FormData();
    fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    fd.append('content', content);
    fd.append('bg_color', bgColor);
    captured.forEach(f => fd.append('media[]', f));

    const xhr = new XMLHttpRequest();
    xhr.open('POST', "{{ route('posts.store') }}", true);
    xhr.setRequestHeader('Accept', 'application/json');
    isUploading = true;

    xhr.upload.addEventListener('progress', ev => {
        if (ev.lengthComputable) {
            const bar = document.getElementById(`bar-${pid}`);
            if (bar) bar.style.width = (Math.round(ev.loaded/ev.total*90)+5)+'%';
        }
    });

    xhr.onreadystatechange = function () {
        if (xhr.readyState !== 4) return;
        isUploading = false;
        if (xhr.status === 200 || xhr.status === 201) {
            const bar = document.getElementById(`bar-${pid}`);
            if (bar) { bar.style.width='100%'; bar.classList.replace('bg-primary','bg-success'); bar.classList.remove('progress-bar-animated'); }
            setTimeout(() => {
            sessionStorage.setItem('scrollToTop', '1');
            sessionStorage.setItem('showPostSuccess', '1');
            window.location.reload();
            // Post success toast
            if (sessionStorage.getItem('showPostSuccess')) {
            sessionStorage.removeItem('showPostSuccess');
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
            Toast.fire({
                icon: 'success',
                title: 'Post Published!',
                text: 'Your post has been published successfully.'
            });
        }
        }, 1000);
        } else {
            document.getElementById(pid)?.remove();
            Swal.fire({ icon:'error', title:'Post not published!', text:'There was an issue uploading the post.' });
        }
    };
    xhr.send(fd);
});

// ==========================================
// LIKE
// ==========================================
function toggleLike(postId) {
    fetch(`/posts/${postId}/like`, {
        method:'POST',
        headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{
        if (!d.success) return;
        const btn  = document.getElementById(`likeBtn-${postId}`);
        const zone = document.getElementById(`like-zone-${postId}`);
        btn.className = d.liked ? 'btn btn-link btn-sm text-decoration-none text-primary fw-bold' : 'btn btn-link btn-sm text-decoration-none text-muted';
        btn.innerHTML = d.liked ? '<i class="bi bi-hand-thumbs-up-fill"></i> Like' : '<i class="bi bi-hand-thumbs-up"></i> Like';
        if (zone) zone.innerHTML = d.like_count > 0 ? `<i class="bi bi-heart-fill text-danger"></i> <span>${d.like_count} Likes</span>` : '';
    });
}

// ==========================================
// COMMENTS
// ==========================================
function toggleComments(postId) {
    document.getElementById(`commentZone-${postId}`)?.classList.toggle('d-none');
}
function submitComment(event, postId) {
    event.preventDefault();
    const input = document.getElementById(`commentInput-${postId}`);
    if (!input?.value.trim()) return;
    const text = input.value.trim(); input.value = '';
    fetch(`/posts/${postId}/comments`, {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},
        body: JSON.stringify({content:text})
    }).then(r=>r.json()).then(d=>{
        if (!d.success) return;
        const c = document.getElementById(`comment-count-${postId}`);
        if (c) c.innerText = `${d.comment_count} Comments`;
        document.querySelector(`.dynamic-no-comment-${postId}`)?.remove();
        document.getElementById(`commentList-${postId}`)?.insertAdjacentHTML('afterbegin',`
        <div class="bg-light p-2 px-3 rounded-4 mb-2 d-flex justify-content-between align-items-start" id="comment-container-${d.comment_id}">
          <div class="flex-grow-1">
            <strong class="small text-dark d-block" style="font-size:12px;">${d.user_name}</strong>
            <span class="small" id="comment-text-${d.comment_id}" style="font-size:13px;">${d.content}</span>
          </div>
          <div class="dropdown">
            <button type="button" class="btn btn-link btn-sm text-muted p-0 border-0 shadow-none" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm p-1" style="min-width:100px;">
              <li><a class="dropdown-item py-1 fs-7" href="javascript:void(0)" onclick="editComment(event,${d.comment_id})"><i class="bi bi-pencil me-1"></i>Edit</a></li>
              <li><a class="dropdown-item py-1 fs-7 text-danger" href="javascript:void(0)" onclick="deleteComment(${d.comment_id},${postId})"><i class="bi bi-trash me-1"></i>Delete</a></li>
            </ul>
          </div>
        </div>`);
    });
}
function editComment(event, cid) {
    document.getElementById('editTargetCommentId').value = cid;
    bootstrapCommentEditModal?.show();
    setTimeout(()=>{ const f=document.getElementById('editCommentInput'); if(f){f.value=document.getElementById(`comment-text-${cid}`).innerText;f.focus();} },400);
}
function submitUpdateComment() {
    const cid  = document.getElementById('editTargetCommentId').value;
    const text = document.getElementById('editCommentInput').value.trim();
    if (!text) return;
    fetch(`/comments/${cid}`,{method:'PUT',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:JSON.stringify({content:text})})
    .then(r=>r.json()).then(d=>{ if(d.success){document.getElementById(`comment-text-${cid}`).innerText=text; bootstrapCommentEditModal?.hide();} });
}
function deleteComment(cid, postId) {
    Swal.fire({title:'Delete comment?',icon:'warning',showCancelButton:true,confirmButtonColor:'#ef4444'}).then(r=>{
        if (!r.isConfirmed) return;
        fetch(`/comments/${cid}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'}})
        .then(r=>r.json()).then(d=>{
            if(d.success){ document.getElementById(`comment-container-${cid}`)?.remove(); const c=document.getElementById(`comment-count-${postId}`); if(c&&d.comment_count!==undefined) c.innerText=`${d.comment_count} Comments`; }
        });
    });
}

// ==========================================
// DELETE POST
// ==========================================
function deletePost(id) {
    Swal.fire({title:'Are you sure?',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33'}).then(r=>{
        if (!r.isConfirmed) return;
        fetch(`/posts/${id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}})
        .then(r=>r.json()).then(d=>{ if(d.success) window.location.reload(); });
    });
}

// ==========================================
// SHARE
// ==========================================
function openShareModal(postId) {
    document.getElementById('targetSharePostId').value = postId;
    document.getElementById('shareComment').value = '';
    const card = document.getElementById(`postCard-${postId}`);
    if (!card) return;
    const author   = card.querySelector('.author-name-zone')?.innerText || 'User';
    const avatar   = card.querySelector('.author-avatar-zone')?.innerHTML || 'U';
    const colored  = card.getAttribute('data-bg-color');
    const caption  = card.querySelector('.dynamic-caption')?.innerHTML || '';
    const grid     = card.querySelector('.dynamic-media-container-zone');

    let capHtml = `<div style="font-size:13px;"><p class="mb-0">${caption}</p></div>`;
    if (colored && colored !== 'null' && colored !== '')
        capHtml = `<div class="p-3 rounded text-center text-white fw-bold ${colored}" style="min-height:80px;font-size:16px;"><p class="mb-0">${caption}</p></div>`;

    let gridHtml = '';
    if (grid) {
        const clone = grid.cloneNode(true);
        clone.querySelectorAll('img,video').forEach(el => {
            el.removeAttribute('onclick');
            if (el.tagName === 'VIDEO') el.removeAttribute('controls');
        });
        gridHtml = `<div class="mt-2 rounded overflow-hidden">${clone.outerHTML}</div>`;
    }

    document.getElementById('modalPostPreview').innerHTML = `
    <div class="d-flex align-items-center gap-2 mb-2">
      <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:32px;height:32px;font-size:12px;">${avatar}</div>
      <h6 class="m-0 fw-bold" style="font-size:13px;">${author}</h6>
    </div>${capHtml}${gridHtml}`;

    bootstrapShareModal?.show();
}
function closeShareModal() { bootstrapShareModal?.hide(); }

document.getElementById('fbShareForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    const pId = document.getElementById('targetSharePostId').value;
    const comment = document.getElementById('shareComment').value.trim();
    const btn = document.getElementById('shareSubmitBtn');
    btn.disabled = true;
    const Toast = Swal.mixin({toast:true,position:'top-end',showConfirmButton:false});
    Toast.fire({icon:'info',title:'Sharing...'});
    fetch(`/posts/${pId}/share`,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},body:JSON.stringify({content:comment})})
    .then(r=>r.json()).then(d=>{

        if(d.success){ bootstrapShareModal?.hide(); Toast.fire({icon:'success',title:'Shared!',timer:1200}); setTimeout(() => {
            sessionStorage.setItem('scrollToTop', '1');
            window.location.reload();
        }, 1300); }
        else { btn.disabled=false; Swal.fire({icon:'error',title:'Failed!',text:'There was an error sharing the post.'}); }

    }).catch(()=>{ btn.disabled=false; Swal.fire({icon:'error',title:'Network Error!'}); });
});

// ==========================================
// EDIT POST: COLOR
// ==========================================
function toggleEditColorPlates() {
    document.getElementById('editColorPlatesZone')?.classList.toggle('d-none');
}
function selectEditPostBg(cls) {
    const w = document.getElementById('editPostInputWrapper');
    const t = document.getElementById('editPostContent');
    const b = document.getElementById('edit_bg_color_input');
    if (w && t) {
        w.className = `p-4 rounded text-center text-white fw-bold d-flex align-items-center justify-content-center fb-colored-post-render ${cls}`;
        w.style.minHeight = '200px';
        t.style.cssText = 'font-size:22px;text-align:center;color:#fff;';
        t.className = 'form-control border-0 bg-transparent shadow-none w-100';
    }
    if (b) b.value = cls;
    document.querySelectorAll('#editMediaPreviewContainer [data-server-path]').forEach(el => {
        const p = el.getAttribute('data-server-path'), tp = el.getAttribute('data-type');
        if (tp==='image') removedImages.push(p); else removedVideos.push(p);
    });
    editSelectedFiles = [];
    const pc = document.getElementById('editMediaPreviewContainer');
    if (pc) pc.innerHTML = '';
}
function resetEditPostBg() {
    const w = document.getElementById('editPostInputWrapper');
    const t = document.getElementById('editPostContent');
    const b = document.getElementById('edit_bg_color_input');
    if (w) { w.className='p-1 rounded bg-transparent'; w.style.minHeight='auto'; }
    if (t) { t.style.cssText='font-size:14px;text-align:left;color:inherit;'; }
    if (b) b.value='';
}

// ==========================================
// EDIT MODAL PREPARE
// ==========================================
function prepareEditModal(el) {
    const id=el.getAttribute('data-id'), content=el.getAttribute('data-content'),
          imgs=el.getAttribute('data-images'), vids=el.getAttribute('data-video'),
          bg=el.getAttribute('data-bg-color');
    removedImages=[]; removedVideos=[]; editSelectedFiles=[];
    document.getElementById('editPostId').value      = id;
    document.getElementById('editPostContent').value = content||'';
    document.getElementById('editMediaInput').value  = '';
    const pc=document.getElementById('editMediaPreviewContainer');
    if(pc) pc.innerHTML='';
    bg && bg!=='null' && bg.trim() ? selectEditPostBg(bg) : resetEditPostBg();

    if(imgs && imgs!=='null' && imgs.trim()) {
        try { const arr=JSON.parse(imgs); if(Array.isArray(arr)) arr.forEach(i=>renderEditPreviewItem(i,'image',false)); }
        catch(e){}
    }
    if(vids && vids!=='null' && vids.trim()) {
        try {
            const p=JSON.parse(vids), arr=Array.isArray(p)?p:[p];
            arr.forEach(v=>{ if(v&&v.trim()) renderEditPreviewItem(v,'video',false); });
        } catch(e) { if(typeof vids==='string'&&vids.trim()) renderEditPreviewItem(vids.trim(),'video',false); }
    }
    bootstrapEditModal?.show();
}

// ==========================================
// EDIT PREVIEW RENDERER
// Fix: videos play INLINE inside edit modal (no lightbox conflict)
// ==========================================
function renderEditPreviewItem(pathOrFile, type, isNew=false) {
    const container=document.getElementById('editMediaPreviewContainer');
    if(!container) return;
    const col=document.createElement('div');
    col.className='col-4 position-relative';
    col.style.height='110px';
    if(!isNew){ col.setAttribute('data-server-path',pathOrFile); col.setAttribute('data-type',type); }

    const src=isNew?URL.createObjectURL(pathOrFile):`{{ asset('storage') }}/${pathOrFile}`;
    let mediaEl;

    if(type==='image') {
        mediaEl=document.createElement('img');
        mediaEl.src=src;
        mediaEl.className='w-100 h-100 rounded border';
        mediaEl.style.cssText='object-fit:cover;cursor:pointer;';
        // Image: open in lightbox (no conflict since it's not playing)
        mediaEl.addEventListener('click',()=>openLightbox(JSON.stringify([{type:'image',url:src}]),0));
    } else {
        // Video: play INLINE inside edit modal thumbnail (avoids modal stacking conflict)
        mediaEl=document.createElement('video');
        mediaEl.src=src;
        mediaEl.muted=true;
        mediaEl.preload='metadata';
        mediaEl.className='w-100 h-100 rounded border';
        mediaEl.style.cssText='object-fit:cover;cursor:pointer;';

        mediaEl.addEventListener('click', function(e) {
            e.stopPropagation();
            if (!this.hasAttribute('data-expanded')) {
                // First click: expand and show controls
                this.setAttribute('data-expanded','1');
                this.controls=true;
                this.muted=false;
                this.style.objectFit='contain';
                col.style.height='160px';
                const ov=col.querySelector('.edit-play-overlay');
                if(ov) ov.style.display='none';
                this.play().catch(()=>{});
            }
        });

        // ▶ Play indicator
        const ov=document.createElement('div');
        ov.className='edit-play-overlay position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center rounded-circle';
        ov.style.cssText='width:36px;height:36px;background:rgba(0,0,0,0.65);pointer-events:none;z-index:5;';
        ov.innerHTML='<i class="bi bi-play-fill text-white" style="font-size:1rem;margin-left:2px;"></i>';
        col.appendChild(ov);
    }

    // × Remove button
    const xBtn=document.createElement('button');
    xBtn.type='button';
    xBtn.className='btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle p-0 d-flex align-items-center justify-content-center';
    xBtn.style.cssText='width:22px;height:22px;font-size:11px;z-index:10;';
    xBtn.innerHTML='<i class="bi bi-x-lg"></i>';
    xBtn.addEventListener('click',function(e){
        e.stopPropagation();
        if(!isNew){ if(type==='image') removedImages.push(pathOrFile); else removedVideos.push(pathOrFile); }
        else { const idx=editSelectedFiles.indexOf(pathOrFile); if(idx>-1) editSelectedFiles.splice(idx,1); }
        col.remove();
    });

    col.appendChild(mediaEl);
    col.appendChild(xBtn);
    container.appendChild(col);
}

// ==========================================
// EDIT: NEW FILE SELECTION
// ==========================================
document.getElementById('editMediaInput')?.addEventListener('change', function () {
    Array.from(this.files).forEach(f=>{
        editSelectedFiles.push(f);
        renderEditPreviewItem(f, f.type.startsWith('video/')?'video':'image', true);
    });
    this.value='';
});

// ==========================================
// EDIT POST SUBMIT
// ==========================================
document.getElementById('editPostForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    const id=document.getElementById('editPostId')?.value;
    if(!id) return;
    const btn=document.getElementById('editSubmitBtn');
    if(btn) btn.disabled=true;
    const fd=new FormData();
    fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    fd.append('content', document.getElementById('editPostContent')?.value||'');
    fd.append('bg_color', document.getElementById('edit_bg_color_input')?.value||'');
    fd.append('removed_images', JSON.stringify(removedImages));
    fd.append('removed_videos', JSON.stringify(removedVideos));
    editSelectedFiles.forEach(f=>fd.append('media[]',f));
    const xhr=new XMLHttpRequest();
    xhr.open('POST',`/posts/${id}`,true);
    xhr.setRequestHeader('Accept','application/json');
    xhr.onreadystatechange=function(){
        if(xhr.readyState!==4) return;
        if(xhr.status===200||xhr.status===201){
            Swal.fire({icon:'success',title:'Updated!',timer:1000}).then(()=>window.location.reload());
        } else {
            if(btn) btn.disabled=false;
            Swal.fire({icon:'error',title:'Update Failed!'});
        }
    };
    xhr.send(fd);
});
</script>

</body>
</html>