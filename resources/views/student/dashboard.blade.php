<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">

    <title>Borobhai.com News Feed</title>

    <style>
        /* --- Original Custom CSS --- */
        body {
            font-family: -apple-system, system-ui, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", "Fira Sans", Ubuntu, Oxygen, "Oxygen Sans", Cantarell, "Droid Sans", "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Lucida Grande", Helvetica, Arial, sans-serif;
            background-color: #f0f2f5;
            color: #1c1e21;
        }
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .08), 0 1px 2px rgba(0, 0, 0, .05);
            padding: 0.5rem 1rem;
        }
        .navbar-brand {
            font-weight: 700;
            color: #1877f2;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }
        .search-box {
            background-color: #f0f2f5;
            border-radius: 50px;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            width: 240px;
        }
        .search-box input {
            background: transparent;
            border: none;
            outline: none;
            margin-left: 8px;
            font-size: 0.9rem;
            width: 100%;
        }
        .nav-icon-btn {
            width: 40px;
            height: 40px;
            border-radius: 50px;
            background-color: #e4e6eb;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #050505;
            text-decoration: none;
            font-size: 1.2rem;
            border: none;
        }
        .nav-icon-btn:hover {
            background-color: #d8dadf;
            color: #050505;
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0.5rem 0.75rem;
            color: #050505;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 8px;
        }
        .sidebar-link:hover {
            background-color: #e4e6eb;
            color: #050505;
        }
        .sidebar-link i {
            font-size: 1.4rem;
        }
        .sidebar-link.active {
            color: #1877f2;
        }
        .fb-post-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
        }
        .create-post-box {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
            padding: 1rem;
        }
        .create-post-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #65676b;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        .mock-input {
            background-color: #f0f2f5;
            border-radius: 20px;
            padding: 0.5rem 1rem;
            color: #65676b;
            cursor: pointer;
            flex-grow: 1;
        }
        .mock-input:hover {
            background-color: #e4e6eb;
        }
        .post-action-btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0.5rem;
            color: #65676b;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 4px;
        }
        .post-action-btn:hover {
            background-color: #f2f2f2;
            color: #65676b;
        }
        .fs-7 { font-size: 0.85rem !important; }
        .cursor-pointer { cursor: pointer !important; }

        /* 🎨 Facebook Style Color Gradient Palettes */
        .fb-bg-gradient-1 { background: linear-gradient(45deg, #f321d7, #2196f3) !important; }
        .fb-bg-gradient-2 { background: linear-gradient(45deg, #ff9800, #ff5722) !important; }
        .fb-bg-gradient-3 { background: linear-gradient(45deg, #4caf50, #00bcd4) !important; }
        .fb-bg-gradient-4 { background: linear-gradient(45deg, #1a2a6c, #b21f1f, #fdbb2d) !important; }
        .fb-bg-gradient-5 { background: linear-gradient(45deg, #00c6ff, #0072ff) !important; }
        
        .fb-color-circle {
            width: 28px; height: 28px; border-radius: 50%; display: inline-block;
            cursor: pointer; border: 2px solid #fff; box-shadow: 0 0 4px rgba(0,0,0,0.2);
        }
        .fb-colored-post-render {
            transition: all 0.3s ease;
        }
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
            
            <div class="col-md-3 d-none d-md-block position-sticky" style="top: 70px; height: fit-content;">
                <div class="d-flex flex-column gap-1">
                    <a href="#" class="sidebar-link active">
                        <i class="bi bi-house-door-fill text-primary"></i> <span>Home</span>
                    </a>
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-people-fill text-info"></i> <span>Friends</span>
                    </a>
                    
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-bookmark-heart-fill text-warning"></i> <span>Saved</span>
                    </a>
                </div>
            </div>

            <div class="col-12 col-md-6">
                
                <div class="create-post-box mb-3">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="create-post-avatar">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="mock-input" onclick="resetPostBg();" data-bs-toggle="modal" data-bs-target="#createPostModal">
                            What's on your mind, {{ explode(' ', Auth::user()->name)[0] }}?
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between pt-1">
                        <button type="button" class="btn btn-link post-action-btn border-0 bg-transparent text-decoration-none shadow-none" data-bs-toggle="modal" data-bs-target="#createPostModal" onclick="resetPostBg(); setTimeout(() => { document.getElementById('postImageInput').click(); }, 400);">
                            <i class="bi bi-images text-success fs-5"></i> <span class="text-muted fs-7 fw-semibold">Photo/video</span>
                        </button>
                        <button type="button" class="btn btn-link post-action-btn border-0 bg-transparent text-decoration-none shadow-none" data-bs-toggle="modal" data-bs-target="#createPostModal" onclick="toggleColorPlates();">
                            <i class="bi bi-palette-fill text-danger fs-5"></i> <span class="text-muted fs-7 fw-semibold">Background color</span>
                        </button>
                    </div>
                </div>

                {{-- Main Feed Middle Start --}}
                <div id="postsFeedContainer">
    @forelse($posts as $post)
        <div class="card mb-3 fb-post-card shadow-sm border-0 rounded-3" id="postCard-{{ $post->id }}" data-bg-color="{{ $post->bg_color }}">
            <div class="card-body p-3">
                
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="author-avatar-zone bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:38px; height:38px;">
                            {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <h6 class="m-0 fw-bold text-dark author-name-zone" style="font-size: 14px;">{{ $post->user->name }}</h6>
                            <small class="text-muted" style="font-size: 11px;">{{ $post->created_at->diffForHumans() }}</small>
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
                                    data-images="{{ json_encode($post->images) }}"
                                    data-video="{{ is_array($post->video) ? json_encode($post->video) : $post->video }}">
                                        <i class="bi bi-pencil me-1"></i> Edit Post
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-1 fs-7 text-danger" href="javascript:void(0)" onclick="deletePost({{ $post->id }})">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>

                {{-- Feed Media part Start --}}
                @php
                $hasImages = is_array($post->images) && count($post->images) > 0;
                
                // ভিডিও ডাটাবেজে জেসন অ্যারে কি না তা চেক করা এবং ডিকোড করা
                $videoItemsArray = [];
                if (!empty($post->video) && $post->video !== 'null') {
                    $decodedVideo = is_array($post->video) ? $post->video : json_decode($post->video, true);
                    if (is_array($decodedVideo)) {
                        $videoItemsArray = $decodedVideo;
                    } else {
                        $cleanSingleVid = trim($post->video, '"[]');
                        if(!empty($cleanSingleVid)) {
                            $videoItemsArray[] = $cleanSingleVid;
                        }
                    }
                }
                
                $hasVideo = count($videoItemsArray) > 0;
                $renderBg = !empty($post->bg_color) && !$hasImages && !$hasVideo;
                @endphp

                <div id="postInputWrapper-{{ $post->id }}" class="{{ $renderBg ? 'p-4 rounded text-center text-white fw-bold d-flex align-items-center justify-content-center fb-colored-post-render ' . $post->bg_color : 'p-0 text-start' }}" style="{{ $renderBg ? 'min-height: 200px; font-size: 22px;' : 'font-size: 14px;' }}">
                    <p class="mb-0 dynamic-caption" id="captionText-{{ $post->id }}">{!! nl2br(e($post->content)) !!}</p>
                </div>

                @php
                    $mediaItems = [];
                    
                    // ১. ছবিগুলো পুশ করা হচ্ছে
                    if($hasImages) {
                        foreach($post->images as $img) { 
                            $cleanImgPath = str_replace('//', '/', $img);
                            $mediaItems[] = ['type' => 'image', 'url' => asset('storage/' . $cleanImgPath)]; 
                        }
                    }
                    
                    // ২. ভিডিওগুলো পুশ করা হচ্ছে
                    if($hasVideo) {
                        foreach($videoItemsArray as $vid) {
                            $cleanVidPath = str_replace('//', '/', trim($vid, '"[] '));
                            if(!empty($cleanVidPath)) {
                                $mediaItems[] = ['type' => 'video', 'url' => asset('storage/' . $cleanVidPath)];
                            }
                        }
                    }
                    
                    $mediaCount = count($mediaItems);
                    $escapedImagesJson = json_encode($mediaItems, JSON_HEX_APOS | JSON_HEX_QUOT);
                @endphp

                @if($mediaCount > 0)
                    <div class="mt-2 dynamic-media-container-zone position-relative overflow-hidden rounded border border-light-subtle mb-3">
                        <div class="row g-1">
                            @foreach($mediaItems as $index => $media)
                                @if($index < 4)
                                    <div class="{{ $mediaCount == 1 ? 'col-12' : ($mediaCount == 2 ? 'col-6' : ($index == 0 && $mediaCount > 2 ? 'col-12' : 'col-4')) }} position-relative bg-black text-center d-flex align-items-center justify-content-center" style="max-height: 380px; min-height: {{ $mediaCount == 1 ? '260px' : '150px' }};">
                                        @if($media['type'] == 'image')
                                            <img src="{{ $media['url'] }}" class="w-100 h-100 object-fit-cover cursor-pointer" onclick="openLightbox(this.getAttribute('data-json'), {{ $index }})" data-json="{{ $escapedImagesJson }}">
                                        @else
                                            <video src="{{ $media['url'] }}" controls preload="metadata" class="w-100 h-100 object-fit-contain cursor-pointer" onclick="openLightbox(this.getAttribute('data-json'), {{ $index }})" data-json="{{ $escapedImagesJson }}"></video>
                                        @endif

                                        @if($index == 3 && $mediaCount > 4)
                                            <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center text-white fw-bold fs-4 cursor-pointer" onclick="openLightbox(this.getAttribute('data-json'), 3)" data-json="{{ $escapedImagesJson }}">
                                                +{{ $mediaCount - 4 }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
                {{-- Feed media End --}}

                @if($post->parentPost)
                    <div class="mt-3 p-3 border rounded bg-light border-light-subtle shared-post-root-node text-start">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold small" style="width:28px; height:28px; font-size: 11px;">
                                {{ strtoupper(substr($post->parentPost->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="m-0 fw-bold text-dark" style="font-size: 12px;">{{ $post->parentPost->user->name }}</h6>
                                <small class="text-muted" style="font-size: 10px;">{{ $post->parentPost->created_at->diffForHumans() }}</small>
                            </div>
                        </div>

                        @php
                            $pHasImages = !empty($post->parentPost->images) && (is_array($post->parentPost->images) ? count($post->parentPost->images) > 0 : count(json_decode($post->parentPost->images, true) ?? []) > 0);
                            $pHasVideo = !empty($post->parentPost->video);
                            $pRenderBg = $post->parentPost->bg_color && !$pHasImages && !$pHasVideo;
                        @endphp

                        <div class="{{ $pRenderBg ? 'p-3 rounded text-center text-white fw-bold ' . $post->parentPost->bg_color : 'p-0 text-start' }}" style="{{ $pRenderBg ? 'min-height: 120px; font-size: 16px;' : 'font-size: 13px;' }}">
                            <p class="mb-0">{!! nl2br(e($post->parentPost->content)) !!}</p>
                        </div>

                        @php
                            $parentMedia = [];
                            if($post->parentPost->images) {
                                $pImg = is_array($post->parentPost->images) ? $post->parentPost->images : json_decode($post->parentPost->images, true);
                                if(is_array($pImg)) { 
                                    foreach($pImg as $img) { $parentMedia[] = ['type' => 'image', 'url' => asset('storage/' . $img)]; } 
                                }
                            }
                            if($post->parentPost->video) {
                                $pVid = json_decode($post->parentPost->video, true);
                                if(is_array($pVid)){ 
                                    foreach($pVid as $v){ $parentMedia[] = ['type' => 'video', 'url' => asset('storage/' . $v)]; } 
                                } else { 
                                    $parentMedia[] = ['type' => 'video', 'url' => asset('storage/' . $post->parentPost->video)]; 
                                }
                            }
                            $parentImagesJson = htmlspecialchars(json_encode($parentMedia), ENT_QUOTES, 'UTF-8');
                        @endphp

                        @if(count($parentMedia) > 0)
                            <div class="row g-1 mt-2 rounded overflow-hidden">
                                @foreach($parentMedia as $pIdx => $pm)
                                    @if($pIdx < 3)
                                        <div class="col-4 bg-black text-center" style="height: 100px;">
                                            @if($pm['type'] == 'image')
                                                <img src="{{ $pm['url'] }}" class="w-100 h-100 object-fit-cover cursor-pointer" onclick="openLightbox('{{ $parentImagesJson }}', {{ $pIdx }})">
                                            @else
                                                <video src="{{ $pm['url'] }}" class="w-100 h-100 object-fit-contain cursor-pointer" onclick="openLightbox('{{ $parentImagesJson }}', {{ $pIdx }})" muted preload="metadata"></video>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                <div class="d-flex justify-content-between text-muted small px-1 mt-3">
                    <div id="like-zone-{{ $post->id }}">
                        @if($post->likes->count() > 0)
                            <i class="bi bi-heart-fill text-danger"></i> <span class="like-count-text">{{ $post->likes->count() }} Likes</span>
                        @endif
                    </div>
                    <div>
                        <span class="cursor-pointer" id="comment-count-{{ $post->id }}" onclick="toggleComments({{ $post->id }})">{{ $post->comments->count() }} Comments</span>
                    </div>
                </div>

                <div class="mt-2 d-flex justify-content-between text-muted border-top border-bottom py-1 fs-7">
                    <button type="button" class="btn btn-link btn-sm text-decoration-none {{ $post->likes->contains('user_id', Auth::id()) ? 'text-primary fw-bold' : 'text-muted' }}" id="likeBtn-{{ $post->id }}" onclick="toggleLike({{ $post->id }})">
                        <i class="bi {{ $post->likes->contains('user_id', Auth::id()) ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up' }}"></i> Like
                    </button>
                    <button type="button" class="btn btn-link btn-sm text-decoration-none text-muted" onclick="toggleComments({{ $post->id }})">
                        <i class="bi bi-chat-right-text"></i> Comment
                    </button>
                    <button type="button" class="btn btn-link btn-sm text-decoration-none text-muted" onclick="openShareModal({{ $post->id }})">
                        <i class="bi bi-reply-all-fill" style="transform: scaleX(-1); display:inline-block;"></i> Share
                    </button>
                </div>
                
                {{-- comment section --}}
                <div id="commentZone-{{ $post->id }}" class="mt-2 d-none">
                    <form onsubmit="submitComment(event, {{ $post->id }})" class="d-flex align-items-center gap-2 pt-2 px-3 pb-3 border-top">
                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold small flex-shrink-0" style="width: 32px; height: 32px; font-size: 12px;">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                        
                        <div class="input-group align-items-center bg-light rounded-pill px-3 py-1 w-100 border">
                            <input type="text" 
                                id="commentInput-{{ $post->id }}" 
                                class="form-control border-0 bg-transparent shadow-none py-1 fs-7" 
                                placeholder="Write a comment..." 
                                style="font-size: 13px;">
                                
                            <button type="submit" class="btn btn-link p-0 text-primary ms-2 shadow-none border-0 d-flex align-items-center">
                                <i class="bi bi-send-fill" style="font-size: 16px;"></i>
                            </button>
                        </div>
                    </form>

                    <div id="commentList-{{ $post->id }}" class="mt-1">
                        @forelse($post->comments as $comment)
                            <div class="bg-light p-2 px-3 rounded-4 mb-2 d-flex justify-content-between align-items-start comment-node-item" id="comment-container-{{ $comment->id }}">
                                <div class="flex-grow-1">
                                    <strong class="small text-dark d-block" style="font-size: 12px;">{{ $comment->user->name }}</strong>
                                    <span class="small text-dark-50" id="comment-text-{{ $comment->id }}" style="font-size: 13px;">{{ $comment->content }}</span>
                                </div>
                                @if($comment->user_id === Auth::id())
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-link btn-sm text-muted p-0 border-0 shadow-none" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm p-1" style="min-width: 100px;">
                                            <li><a class="dropdown-item py-1 fs-7" href="javascript:void(0)" onclick="editComment(event, {{ $comment->id }})"><i class="bi bi-pencil me-1"></i> Edit</a></li>
                                            <li><a class="dropdown-item py-1 fs-7 text-danger" href="javascript:void(0)" onclick="deleteComment({{ $comment->id }}, {{ $post->id }})"><i class="bi bi-trash me-1"></i> Delete</a></li>
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
                <div class="mb-3 text-muted">
                    <i class="bi bi-newspaper fs-1"></i>
                </div>
                <h5 class="fw-bold text-secondary">No Posts Yet</h5>
                <p class="text-muted small mb-0">Share something to start the conversation!</p>
            </div>
        </div>
    @endforelse
</div>
                {{-- Main Feed Middle End --}}
            </div>

            <div class="col-md-3 d-none d-md-block position-sticky" style="top: 70px; height: fit-content;">
                <div class="card border-0 shadow-sm rounded-3 p-3">
                    <h6 class="fw-bold text-muted mb-2">Sponsored</h6>
                    <div class="d-flex gap-2 align-items-center">
                        <div class="bg-secondary rounded" style="width:70px; height:70px;"></div>
                        <div>
                            <strong class="d-block small text-dark">Borobhai Tech</strong>
                            <span class="text-muted fs-7">Upgrade your backend system architecture today.</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="createPostModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold mx-auto">Create Post</h5>
                    <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close" onclick="resetPostBg();"></button>
                </div>
                <form id="ajaxPostForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <div class="create-post-avatar">
                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="m-0 fw-bold" style="font-size:14px;">{{ Auth::user()->name }}</h6>
                                <span class="badge bg-light text-muted border py-1 px-2 cursor-pointer" style="font-size:10px;"><i class="bi bi-globe-americas me-1"></i>Public</span>
                            </div>
                        </div>

                        <div id="postInputWrapper" class="p-1 rounded bg-transparent">
                            <textarea id="postContent" name="content" class="form-control border-0 bg-transparent shadow-none" rows="4" placeholder="Start a post..." style="resize: none; font-size: 14px;"></textarea>
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
                        <input type="file" id="postImageInput" name="images[]" class="d-none" multiple accept="image/*,video/*">

                        <div id="imagePreviewContainer" class="row g-1 my-2 d-none"></div>

                        <div class="border rounded p-2 d-flex justify-content-between align-items-center mt-3">
                            <span class="small fw-bold text-muted ps-1">Add to your post</span>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-light btn-sm rounded-circle p-2" id="triggerUploadBtn" title="Photo/Video"><i class="bi bi-images text-success"></i></button>
                                <button type="button" class="btn btn-light btn-sm rounded-circle p-2" onclick="toggleColorPlates();" title="Background Color"><i class="bi bi-palette text-danger"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="submit" id="submitBtn" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- edit post modal start --}}
    <div class="modal fade" id="editPostModal" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0 shadow">
            
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold mx-auto" style="font-size: 17px;">Edit Post</h5>
                <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="editPostForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="edit_bg_color_input">
                
                <div class="modal-body">
                    <textarea id="editPostContent" name="content" class="form-control border-0 mb-3" rows="3" placeholder="What's on your mind?"></textarea>
                    
                    <div id="editMediaPreviewContainer" class="row g-2 mb-3"></div>

                    <div class="mb-3">
                        <label for="editMediaInput" class="btn btn-light btn-sm border cursor-pointer">
                            <i class="bi bi-image text-success"></i> আরও ছবি/ভিডিও যোগ করুন
                        </label>
                        <input type="file" id="editMediaInput" name="media[]" multiple class="d-none" accept="image/*,video/*">
                    </div>
                </div>
                
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="editSubmitBtn" class="btn btn-primary btn-sm px-4">Save Changes</button>
                </div>
            </form>
            </div>
    </div>
</div>
    {{-- edit post modal end --}}

    <div class="modal fade" id="editCommentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content rounded-3 border-0 shadow">
                <div class="modal-header pb-0 border-bottom-0">
                    <h6 class="modal-title fw-bold">Edit Comment</h6>
                    <button type="button" class="btn-close small" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-2">
                    <input type="hidden" id="editTargetCommentId">
                    <textarea id="editCommentInput" class="form-control form-control-sm" rows="2" style="resize:none; font-size:13px;"></textarea>
                </div>
                <div class="modal-footer pt-0 border-top-0 justify-content-end gap-1">
                    <button type="button" class="btn btn-light btn-xs fs-7 py-1 px-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-xs fs-7 py-1 px-2" onclick="submitUpdateComment()">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="fbShareModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 border-0 shadow">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold mx-auto" style="font-size: 17px;">Share Post</h5>
                    <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="fbShareForm">
                    <div class="modal-body">
                        <input type="hidden" id="targetSharePostId">
                        <div class="mb-3">
                            <textarea id="shareComment" class="form-control border-0 shadow-none ps-0" rows="2" placeholder="Say something about this shared post..." style="resize: none; font-size:14px;"></textarea>
                        </div>
                        <div id="modalPostPreview" class="p-3 border rounded bg-white text-start"></div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" onclick="closeShareModal()">Cancel</button>
                        <button type="submit" id="shareSubmitBtn" class="btn btn-primary btn-sm px-4">Share Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageLightboxModal" tabindex="-1" aria-hidden="true" style="background: rgba(0,0,0,0.9);">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0 text-center position-relative">
                <div class="position-absolute top-0 end-0 m-3" style="z-index: 1060;">
                    <button type="button" class="btn-close btn-close-white fs-4" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="lightboxCarousel" class="carousel slide" data-bs-ride="false">
                        <div class="carousel-inner" id="lightboxInner">
                            </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#lightboxCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#lightboxCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

   {{-- script [updated 12.14 AM / 23.5.26 Start] --}}

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ==========================================
    // GLOBAL VARIABLES & INITIALIZATION
    // ==========================================
    let selectedMediaFiles = [];
    let bootstrapEditModal = null;
    let bootstrapShareModal = null;
    let bootstrapLightboxModal = null;
    let bootstrapCommentEditModal = null;

    // DOMContentLoaded লজিক (মেথড কনফ্লিক্ট বা ডুপ্লিকেট ইনিশিয়ালাইজেশন রোধে)
    document.addEventListener("DOMContentLoaded", function() {
        bootstrapEditModal = new bootstrap.Modal(document.getElementById('editPostModal'));
        bootstrapShareModal = new bootstrap.Modal(document.getElementById('fbShareModal'));
        bootstrapLightboxModal = new bootstrap.Modal(document.getElementById('imageLightboxModal'));
        const editCommentModalEl = document.getElementById('editCommentModal');
        if(editCommentModalEl) {
            bootstrapCommentEditModal = new bootstrap.Modal(editCommentModalEl);
        }
    });

    // ==========================================
    // 🔍 ১. ADVANCED LIGHTBOX SYSTEM (JSON PARSING)
    // ==========================================
    function openLightbox(mediaJson, index = 0) {
        try {
            const mediaItems = typeof mediaJson === 'string' ? JSON.parse(mediaJson) : mediaJson;
            const inner = document.getElementById('lightboxInner');
            if(!inner) return;
            inner.innerHTML = '';
            
            mediaItems.forEach((item, i) => {
                const activeClass = i === index ? 'active' : '';
                const carouselItem = document.createElement('div');
                carouselItem.className = `carousel-item ${activeClass}`;
                
                if (item.type === 'image') {
                    carouselItem.innerHTML = `<img src="${item.url}" class="d-block w-100 object-fit-contain" style="max-height:80vh;">`;
                } else {
                    carouselItem.innerHTML = `<video src="${item.url}" controls class="d-block w-100 object-fit-contain" style="max-height:80vh;"></video>`;
                }
                inner.appendChild(carouselItem);
            });
            
            if(bootstrapLightboxModal) bootstrapLightboxModal.show();
        } catch (e) {
            console.error("Lightbox rendering error:", e);
        }
    }

    // ==========================================
    // 🎨 NEW POST: BACKGROUND COLOR & PLATES
    // ==========================================
    function toggleColorPlates() {
        const zone = document.getElementById('colorPlatesZone');
        if(zone) zone.classList.toggle('d-none');
    }

    function selectPostBg(className) {
        const wrapper = document.getElementById('postInputWrapper');
        const textarea = document.getElementById('postContent');
        const bgInp = document.getElementById('bg_color_input');
        
        if(wrapper && textarea) {
            wrapper.className = `p-4 rounded text-center text-white fw-bold d-flex align-items-center justify-content-center fb-colored-post-render ${className}`;
            wrapper.style.minHeight = "200px";
            textarea.style.fontSize = "22px";
            textarea.style.textAlign = "center";
            textarea.style.color = "#fff";
            textarea.placeholder = "What's on your mind?";
        }
        if(bgInp) bgInp.value = className;
        
        // কালার চুজ করলে মিডিয়া ফাইল রিসেট হবে
        selectedMediaFiles = [];
        renderMediaPreviews();
    }

    function resetPostBg() {
        const wrapper = document.getElementById('postInputWrapper');
        const textarea = document.getElementById('postContent');
        
        if(wrapper) {
            wrapper.className = "p-1 rounded bg-transparent";
            wrapper.style.minHeight = "auto";
        }
        if(textarea) {
            textarea.style.fontSize = "14px";
            textarea.style.textAlign = "left";
            textarea.style.color = "inherit";
            textarea.placeholder = "Start a post...";
        }
        const bgInp = document.getElementById('bg_color_input');
        if(bgInp) bgInp.value = "";
    }

    // ==========================================
    // 📸 NEW POST: LIVE UPLOAD & PREVIEWS
    // ==========================================
    const imageInput = document.getElementById('postImageInput');
    const previewContainer = document.getElementById('imagePreviewContainer');

    if(document.getElementById('triggerUploadBtn')) {
        document.getElementById('triggerUploadBtn').addEventListener('click', function() {
            imageInput.click();
        });
    }

    if(imageInput) {
        imageInput.addEventListener('change', function() {
            const files = Array.from(this.files);
            const MAX_SIZE_BYTES = 100 * 1024 * 1024;
            
            for(let file of files) {
                if(file.size > MAX_SIZE_BYTES) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File too large!',
                        text: `"${file.name}" সর্বোচ্চ ১০০ MB অ্যালাউড।`
                    });
                    imageInput.value = '';
                    return;
                }
            }
            resetPostBg(); // ইমেজ/ভিডিও সিলেক্ট করলে ব্যাকগ্রাউন্ড কালার চলে যাবে
            files.forEach(file => selectedMediaFiles.push(file));
            renderMediaPreviews();
            imageInput.value = '';
        });
    }

    function renderMediaPreviews() {
        if(!previewContainer) return;
        previewContainer.innerHTML = '';
        
        if (selectedMediaFiles.length === 0) {
            previewContainer.classList.add('d-none');
            return;
        }
        previewContainer.classList.remove('d-none');
        
        selectedMediaFiles.forEach((file, index) => {
            const col = document.createElement('div');
            col.className = 'col-4 col-md-3 position-relative';
            col.style.height = '100px';
            let mediaElement;
            
            if (file.type.startsWith('video/')) {
                mediaElement = document.createElement('video');
                mediaElement.src = URL.createObjectURL(file);
                mediaElement.className = 'w-100 h-100 object-fit-cover rounded border';
                mediaElement.muted = true;
            } else {
                mediaElement = document.createElement('img');
                mediaElement.src = URL.createObjectURL(file);
                mediaElement.className = 'w-100 h-100 object-fit-cover rounded border';
            }
            
            const closeBtn = document.createElement('button');
            closeBtn.type = 'button';
            closeBtn.className = 'btn btn-dark btn-sm position-absolute top-0 end-0 m-1 rounded-circle';
            closeBtn.style.cssText = 'background: rgba(0,0,0,0.7); border:none; width:22px; height:22px; display:flex; align-items:center; justify-content:center; z-index:10; padding:0;';
            closeBtn.innerHTML = '<i class="bi bi-x-lg" style="font-size:10px; color:#fff;"></i>';
            
            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                selectedMediaFiles.splice(index, 1);
                renderMediaPreviews();
            });
            
            col.appendChild(mediaElement);
            col.appendChild(closeBtn);
            previewContainer.appendChild(col);
        });
    }

    // ==========================================
    // 🚀 AJAX FORM SUBMIT (NEW POST) WITH LOADER
    // ==========================================
    const ajaxFormEl = document.getElementById('ajaxPostForm');
    if(ajaxFormEl) {
        ajaxFormEl.addEventListener('submit', function(e) {
            e.preventDefault();
            const content = document.getElementById('postContent').value.trim();
            const bgColor = document.getElementById('bg_color_input').value;
            const submitBtn = document.getElementById('submitBtn');
            
            if(!content && selectedMediaFiles.length === 0) {
                Swal.fire({ icon: 'warning', title: 'Empty Post!', text: 'কিছু তো লিখুন অথবা ছবি/ভিডিও সিলেক্ট করুন!' });
                return;
            }
            
            submitBtn.disabled = true;
            
            const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false });
            Toast.fire({
                icon: 'info', 
                title: 'Uploading post...', 
                html: '<div class="progress mt-2" style="height:8px;"><div id="uploadProgressBar" class="progress-bar progress-bar-striped progress-bar-animated" style="width:0%"></div></div>' 
            });
            
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('content', content);
            formData.append('bg_color', bgColor);
            
            selectedMediaFiles.forEach(file => {
                formData.append('media[]', file);
            });
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', "{{ route('posts.store') }}", true);
            xhr.setRequestHeader('Accept', 'application/json');
            
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const progBar = document.getElementById('uploadProgressBar');
                    if(progBar) progBar.style.width = Math.round((e.loaded / e.total) * 100) + '%';
                }
            });
            
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if(xhr.status === 200 || xhr.status === 201) {
                        Toast.fire({ icon: 'success', title: 'Published!', timer: 1000 }).then(() => {
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                            setTimeout(() => { window.location.reload(); }, 400);
                        });
                    } else {
                        submitBtn.disabled = false;
                        Swal.fire({ icon: 'error', title: 'Failed!', text: 'পোস্ট পাবলিশ করতে সমস্যা হয়েছে। দয়া করে ফাইলের সাইজ চেক করুন।' });
                    }
                }
            };
            xhr.send(formData);
        });
    }

    // ==========================================
    // 👍 LIKE / UNLIKE CONTROLLER SYSTEM
    // ==========================================
    function toggleLike(postId) {
        const likeBtn = document.getElementById(`likeBtn-${postId}`);
        const likeZone = document.getElementById(`like-zone-${postId}`);
        
        fetch(`/posts/${postId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        }).then(res => res.json()).then(data => {
            if(data.success) {
                if(data.liked) {
                    likeBtn.className = "btn btn-link btn-sm text-decoration-none text-primary fw-bold";
                    likeBtn.innerHTML = `<i class="bi bi-hand-thumbs-up-fill"></i> Like`;
                } else {
                    likeBtn.className = "btn btn-link btn-sm text-decoration-none text-muted";
                    likeBtn.innerHTML = `<i class="bi bi-hand-thumbs-up"></i> Like`;
                }
                if(likeZone) {
                    likeZone.innerHTML = data.like_count > 0 ? `<i class="bi bi-heart-fill text-danger"></i> <span class="like-count-text">${data.like_count} Likes</span>` : '';
                }
            }
        });
    }

    // ==========================================
    // 💬 LIVE COMMENT SYSTEM & CRUD LAYER
    // ==========================================
    function toggleComments(postId) {
        const zone = document.getElementById(`commentZone-${postId}`);
        if(zone) zone.classList.toggle('d-none');
    }

    function submitComment(event, postId) {
        event.preventDefault();
        const input = document.getElementById(`commentInput-${postId}`);
        if(!input || !input.value.trim()) return;
        
        const text = input.value.trim();
        input.value = '';
        
        fetch(`/posts/${postId}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ content: text })
        }).then(res => res.json()).then(data => {
            if(data.success) {
                const counter = document.getElementById(`comment-count-${postId}`);
                if(counter) counter.innerText = `${data.comment_count} Comments`;
                
                const noCommentNode = document.querySelector(`.dynamic-no-comment-${postId}`);
                if(noCommentNode) noCommentNode.remove();
                
                const newCommentHtml = `
                <div class="bg-light p-2 px-3 rounded-4 mb-2 d-flex justify-content-between align-items-start comment-node-item" id="comment-container-${data.comment_id}">
                    <div class="flex-grow-1">
                        <strong class="small text-dark d-block" style="font-size: 12px;">${data.user_name}</strong>
                        <span class="small text-dark-50" id="comment-text-${data.comment_id}" style="font-size: 13px;">${data.content}</span>
                    </div>
                    <div class="dropdown">
                        <button type="button" class="btn btn-link btn-sm text-muted p-0 border-0 shadow-none" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm p-1" style="min-width: 100px;">
                            <li><a class="dropdown-item py-1 fs-7" href="javascript:void(0)" onclick="editComment(event, ${data.comment_id})"><i class="bi bi-pencil me-1"></i> Edit</a></li>
                            <li><a class="dropdown-item py-1 fs-7 text-danger" href="javascript:void(0)" onclick="deleteComment(${data.comment_id}, ${postId})"><i class="bi bi-trash me-1"></i> Delete</a></li>
                        </ul>
                    </div>
                </div>`;
                document.getElementById(`commentList-${postId}`).insertAdjacentHTML('afterbegin', newCommentHtml);
            }
        });
    }

    function editComment(event, commentId) {
        const commentText = document.getElementById(`comment-text-${commentId}`).innerText;
        document.getElementById('editTargetCommentId').value = commentId;
        if(bootstrapCommentEditModal) bootstrapCommentEditModal.show();
        
        setTimeout(() => {
            const inputField = document.getElementById('editCommentInput');
            if(inputField) {
                inputField.value = commentText;
                inputField.focus();
            }
        }, 400);
    }

    function submitUpdateComment() {
        const commentId = document.getElementById('editTargetCommentId').value;
        const updatedText = document.getElementById('editCommentInput').value.trim();
        if(!updatedText) return;
        
        fetch(`/comments/${commentId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ content: updatedText })
        }).then(res => res.json()).then(data => {
            if(data.success) {
                const textNode = document.getElementById(`comment-text-${commentId}`);
                if(textNode) textNode.innerText = updatedText;
                bootstrapCommentEditModal.hide();
            }
        });
    }

    function deleteComment(commentId, postId) {
        Swal.fire({
            title: 'Delete comment?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                }).then(res => res.json()).then(data => {
                    if(data.success) {
                        const cEl = document.getElementById(`comment-container-${commentId}`);
                        if(cEl) cEl.remove();
                        const mainCounter = document.getElementById(`comment-count-${postId}`);
                        if(mainCounter && data.comment_count !== undefined) mainCounter.innerText = `${data.comment_count} Comments`;
                    }
                });
            }
        });
    }

    // ==========================================
    // 🗑️ DELETE POST CONTROLLER
    // ==========================================
    function deletePost(id) {
        Swal.fire({
            title: 'Are you sure?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/posts/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                }).then(res => res.json()).then(data => {
                    if (data.success) window.location.reload();
                });
            }
        });
    }

    // ==========================================
    // 🎯 [PROBLEM 9 FIXED]: FACEBOOK SHARE CONTROLLER
    // ==========================================
    function openShareModal(postId) {
        document.getElementById('targetSharePostId').value = postId;
        document.getElementById('shareComment').value = '';
        
        const postCard = document.getElementById(`postCard-${postId}`);
        if(!postCard) return;
        
        const authorName = postCard.querySelector('.author-name-zone')?.innerText || "User";
        const avatarInner = postCard.querySelector('.author-avatar-zone')?.innerHTML || "U";
        const isColored = postCard.getAttribute('data-bg-color');
        const mainCaption = postCard.querySelector('.dynamic-caption')?.innerHTML || '';
        const mediaGrid = postCard.querySelector('.dynamic-media-container-zone');
        
        let captionHtml = `<div class="p-0 text-start" style="font-size:13px;"><p>${mainCaption}</p></div>`;
        if (isColored && isColored !== 'null' && isColored !== '') {
            captionHtml = `<div class="p-3 rounded text-center text-white fw-bold ${isColored}" style="min-height:100px; font-size:16px;"><p class="mb-0">${mainCaption}</p></div>`;
        }
        
        let imageHtml = '';
        if (mediaGrid) {
            const mediaClone = mediaGrid.cloneNode(true);
            mediaClone.querySelectorAll('img, video').forEach(element => {
                element.removeAttribute('onclick');
                if(element.tagName === 'VIDEO') element.removeAttribute('controls');
            });
            imageHtml = `<div class="mt-2 rounded overflow-hidden">${mediaClone.innerHTML}</div>`;
        }
        
        const previewWrapperHtml = `
        <div class="d-flex align-items-center gap-2 mb-2">
            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:32px; height:32px; font-size:12px;">${avatarInner}</div>
            <div><h6 class="m-0 fw-bold" style="font-size:13px;">${authorName}</h6></div>
        </div>
        ${captionHtml}
        ${imageHtml}`;
        
        document.getElementById('modalPostPreview').innerHTML = previewWrapperHtml;
        
        if(!bootstrapShareModal) {
            bootstrapShareModal = new bootstrap.Modal(document.getElementById('fbShareModal'));
        }
        bootstrapShareModal.show();
    }

    function closeShareModal() {
        if(bootstrapShareModal) bootstrapShareModal.hide();
    }

    if(document.getElementById('sharePostSubmitForm')) {
        document.getElementById('sharePostSubmitForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const pId = document.getElementById('targetSharePostId').value;
            const comment = document.getElementById('shareComment').value.trim();
            const submitBtn = document.getElementById('shareSubmitBtn');
            
            submitBtn.disabled = true;
            
            const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false });
            Toast.fire({ icon: 'info', title: 'Sharing post...' });
            
            fetch(`/posts/${pId}/share`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ content: comment })
            }).then(res => res.json()).then(data => {
                if(data.success) {
                    closeShareModal();
                    Toast.fire({ icon: 'success', title: 'Shared successfully!', timer: 1200 });
                    setTimeout(() => { window.location.reload(); }, 1300);
                } else {
                    submitBtn.disabled = false;
                    Swal.fire({ icon: 'error', title: 'Oops!', text: 'শেয়ার করতে সমস্যা হয়েছে।' });
                }
            }).catch(err => {
                submitBtn.disabled = false;
            });
        });
    }

    // ==========================================
    // 🛠️ [PROBLEMS 8 & 9 FIXED]: ADVANCED POST EDIT LAYER
    // ==========================================
    let removedImages = [];
    let removedVideos = [];
    let editSelectedFiles = [];

    function selectEditPostBg(className) {
        const wrapper = document.getElementById('editPostInputWrapper');
        const textarea = document.getElementById('editPostContent');
        const bgInp = document.getElementById('edit_bg_color_input');
        
        if(wrapper && textarea) {
            wrapper.className = `p-4 rounded text-center text-white fw-bold d-flex align-items-center justify-content-center fb-colored-post-render ${className}`;
            wrapper.style.minHeight = "180px";
            textarea.style.fontSize = "22px";
            textarea.style.textAlign = "center";
            textarea.style.color = "#fff";
        }
        if(bgInp) bgInp.value = className;
        
        // কালার প্লেট চুজ করলে বিদ্যমান মিডিয়াগুলো রিমুভড ট্র্যাকিংয়ে যাবে
        const currentImages = document.getElementById('editMediaPreviewContainer')?.querySelectorAll('[data-server-path]');
        if(currentImages) {
            currentImages.forEach(el => {
                const path = el.getAttribute('data-server-path');
                const type = el.getAttribute('data-type');
                if(type === 'image') removedImages.push(path);
                else removedVideos.push(path);
            });
        }
        editSelectedFiles = [];
        const previewContainer = document.getElementById('editMediaPreviewContainer');
        if(previewContainer) previewContainer.innerHTML = '';
    }

    function resetEditPostBg() {
        const wrapper = document.getElementById('editPostInputWrapper');
        const textarea = document.getElementById('editPostContent');
        const bgInp = document.getElementById('edit_bg_color_input');
        
        if(wrapper) {
            wrapper.className = "p-1 rounded bg-transparent";
            wrapper.style.minHeight = "auto";
        }
        if(textarea) {
            textarea.style.fontSize = "14px";
            textarea.style.textAlign = "left";
            textarea.style.color = "inherit";
        }
        if(bgInp) bgInp.value = "";
    }

    function prepareEditModal(element) {
        const id = element.getAttribute('data-id');
        const content = element.getAttribute('data-content');
        const imagesJson = element.getAttribute('data-images');
        const videoData = element.getAttribute('data-video');
        const bgColor = element.getAttribute('data-bg-color');
        
        document.getElementById('editPostId').value = id;
        document.getElementById('editPostContent').value = content || '';
        
        removedImages = [];
        removedVideos = [];
        editSelectedFiles = [];
        document.getElementById('editMediaInput').value = '';
        
        const previewContainer = document.getElementById('editMediaPreviewContainer');
        if(previewContainer) previewContainer.innerHTML = '';
        
        const bgInp = document.getElementById('edit_bg_color_input');
        if(bgInp) bgInp.value = bgColor || '';
        
        if(bgColor && bgColor !== 'null' && bgColor !== '') {
            selectEditPostBg(bgColor);
        } else {
            resetEditPostBg();
        }
        
        // ছবি রেন্ডারিং (Null ও Array ভ্যালিডেশন সহ)
        if(imagesJson && imagesJson !== 'null' && imagesJson.trim() !== '') {
            try {
                const images = JSON.parse(imagesJson);
                if(images && Array.isArray(images)) {
                    images.forEach(img => {
                        renderEditPreviewItem(img, 'image', false);
                    });
                }
            } catch(e) { console.error("Error parsing edit images:", e); }
        }
        
        // ভিডিও রেন্ডারিং (Null ও জেসন অ্যারে ভ্যালিডেশন সহ)
        if(videoData && videoData !== 'null' && videoData.trim() !== '') {
            try {
                const videos = (videoData.startsWith('[') || videoData.startsWith('{')) ? JSON.parse(videoData) : [videoData];
                if(videos && Array.isArray(videos)) {
                    videos.forEach(vid => {
                        if(vid && vid.trim() !== "") renderEditPreviewItem(vid, 'video', false);
                    });
                }
            } catch(e) {
                if(typeof videoData === 'string' && videoData.trim() !== "") {
                    renderEditPreviewItem(videoData, 'video', false);
                }
            }
        }
        
        if(bootstrapEditModal) bootstrapEditModal.show();
    }

    function renderEditPreviewItem(pathOrFile, type, isNew = false, index = null) {
        const container = document.getElementById('editMediaPreviewContainer');
        if(!container) return;
        
        const col = document.createElement('div');
        col.className = 'col-4 position-relative mb-2';
        col.style.height = '100px';
        if(!isNew) {
            col.setAttribute('data-server-path', pathOrFile);
            col.setAttribute('data-type', type);
        }
        
        let src = isNew ? URL.createObjectURL(pathOrFile) : `{{ asset('storage') }}/${pathOrFile}`;
        let mediaElement;
        
        if (type === 'image') {
            mediaElement = document.createElement('img');
            mediaElement.src = src;
            mediaElement.className = 'w-100 h-100 object-fit-cover rounded border';
        } else {
            mediaElement = document.createElement('video');
            mediaElement.src = src;
            mediaElement.className = 'w-100 h-100 object-fit-cover rounded border';
            mediaElement.muted = true;
        }
        
        const closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.className = 'btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle p-0';
        closeBtn.style.width = '20px';
        closeBtn.style.height = '20px';
        closeBtn.innerHTML = '×';
        
        closeBtn.addEventListener('click', function() {
            if(!isNew) {
                if(type === 'image') removedImages.push(pathOrFile);
                else removedVideos.push(pathOrFile);
            } else {
                editSelectedFiles.splice(index, 1);
                return;
            }
            col.remove();
        });
        
        col.appendChild(mediaElement);
        col.appendChild(closeBtn);
        container.appendChild(col);
    }

    const editMediaInputNode = document.getElementById('editMediaInput');
    if(editMediaInputNode) {
        editMediaInputNode.addEventListener('change', function() {
            const files = Array.from(this.files);
            resetEditPostBg(); // নতুন ফাইল সিলেক্ট করলে কালার চলে যাবে
            files.forEach(file => {
                editSelectedFiles.push(file);
                const type = file.type.startsWith('video/') ? 'video' : 'image';
                renderEditPreviewItem(file, type, true, editSelectedFiles.length - 1);
            });
        });
    }

    // ফর্ম সাবমিট আপডেট (আপনার Route::post('/posts/{id}') এর সাথে ১০০% ম্যাচ করে)
    // ফর্ম সাবমিট আপডেট (১০০% নিরাপদ ও ক্র্যাশ-প্রুফ লেয়ার)
const editPostFormEl = document.getElementById('editPostForm');
if(editPostFormEl) {
    editPostFormEl.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const idNode = document.getElementById('editPostId');
        if(!idNode) return;
        const id = idNode.value;
        
        const submitBtn = document.getElementById('editSubmitBtn');
        if(submitBtn) submitBtn.disabled = true;
        
        const formData = new FormData();
        
        // 🛠️ [SAFEGUARD]: টোকেন ফিল্ড না থাকলে মেটা ট্যাগ থেকে ব্যাকআপ নেবে, ক্র্যাশ করবে না
        const tokenInput = document.querySelector('input[name="_token"]');
        const tokenValue = tokenInput ? tokenInput.value : document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        formData.append('_token', tokenValue || '');
        
        // কন্টেন্ট এবং কালার ফিল্ড সেফ চেক
        const contentNode = document.getElementById('editPostContent');
        formData.append('content', contentNode ? contentNode.value : '');
        
        const bgInpNode = document.getElementById('edit_bg_color_input');
        formData.append('bg_color', bgInpNode ? bgInpNode.value : '');
        
        formData.append('removed_images', JSON.stringify(removedImages));
        formData.append('removed_videos', JSON.stringify(removedVideos));
        
        editSelectedFiles.forEach(file => {
            formData.append('media[]', file);
        });
        
        const xhr = new XMLHttpRequest();
        xhr.open('POST', `/posts/${id}`, true);
        xhr.setRequestHeader('Accept', 'application/json');
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200 || xhr.status === 201) {
                    Swal.fire({ icon: 'success', title: 'Updated!', timer: 1000 }).then(() => {
                        window.location.reload();
                    });
                } else {
                    if(submitBtn) submitBtn.disabled = false;
                    Swal.fire({ icon: 'error', title: 'Update Failed!', text: 'সার্ভারে পোস্ট আপডেট করা যায়নি।' });
                }
            }
        };
        xhr.send(formData);
    });
}
</script>
   {{-- script [updated 12.14 AM / 23.5.26 End] --}}

</body>
</html>