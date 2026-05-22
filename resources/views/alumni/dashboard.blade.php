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
            background-color: #f3f2ef;
            color: rgba(0,0,0,0.9);
            padding-top: 70px;
        }
        
        .linkedin-navbar { background-color: #ffffff; border-bottom: 1px solid #e0dfdc; height: 60px; }
        .nav-icon-link { color: rgba(0,0,0,0.6); display: flex; flex-direction: column; align-items: center; font-size: 12px; text-decoration: none; min-width: 80px; transition: color 0.2s; }
        .nav-icon-link i { font-size: 24px; margin-bottom: -2px; }
        .nav-icon-link:hover, .nav-icon-link.active { color: rgba(0,0,0,0.9); }
        .nav-icon-link.active { border-bottom: 2px solid rgba(0,0,0,0.9); padding-bottom: 3px; }
        
        .search-bar { background-color: #eef3f8; border: none; border-radius: 4px; padding-left: 35px; height: 34px; font-size: 14px; }
        .search-icon { position: absolute; left: 10px; top: 7px; color: #606266; }

        .post-card, .sidebar-card { background: #ffffff; border-radius: 8px; border: 1px solid #e0dfdc; margin-bottom: 16px; overflow: visible; }
        .sidebar-card { overflow: hidden; }

        .profile-cover { height: 60px; background: #a0b4b7; }
        .profile-pic-container { margin-top: -38px; text-align: center; }
        .profile-pic-sidebar { width: 72px; height: 72px; border: 2px solid #ffffff; border-radius: 50%; background: #ffffff; object-fit: cover; }
        .sidebar-stat { display: flex; justify-content: space-between; font-size: 12px; padding: 4px 12px; cursor: pointer; }
        .sidebar-stat:hover { background-color: #ebebeb; }
        .stat-label { color: rgba(0,0,0,0.6); font-weight: 600; }
        .stat-value { color: #0a66c2; font-weight: 600; }

        .widget-title { font-size: 14px; font-weight: 600; color: rgba(0,0,0,0.9); margin-bottom: 12px; }
        .job-item, .user-item { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 12px; cursor: pointer; }
        .job-item:hover .job-title { color: #0a66c2; text-decoration: underline; }
        .job-icon { width: 40px; height: 40px; background: #f3f2ef; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #555; }
        .job-title { font-size: 14px; font-weight: 600; color: rgba(0,0,0,0.9); margin: 0; }
        .job-company { font-size: 12px; color: rgba(0,0,0,0.6); margin: 0; }
        
        .active-dot { width: 10px; height: 10px; background-color: #31a24c; border: 2px solid #fff; border-radius: 50%; position: absolute; bottom: 0; right: 0; }
        .user-name { font-size: 14px; font-weight: 600; margin: 0; color: rgba(0,0,0,0.9); }
        .user-role { font-size: 12px; color: rgba(0,0,0,0.6); margin: 0; }

        .custom-textarea { resize: none; border: 1px solid #b5b5b5; border-radius: 24px; padding: 10px 20px; font-size: 14px; min-height: 48px; }
        .custom-textarea:hover { background-color: #f3f2ef; }
        .custom-textarea:focus { border-color: #0a66c2; border-width: 2px; outline: none; background-color: #fff; box-shadow: none; }
        
        .btn-brand { background-color: #0a66c2; color: #fff; font-weight: 600; border-radius: 24px; padding: 6px 16px; border: none; font-size: 14px; }
        .btn-brand:hover { background-color: #004182; color: #fff; }
        
        .action-btn { font-weight: 600; color: rgba(0,0,0,0.6); border-radius: 4px; font-size: 14px; padding: 10px; text-decoration: none; }
        .action-btn:hover { background-color: #ebebeb; color: rgba(0,0,0,0.9); }

        .shared-post-box { border: 1px solid #e0dfdc; border-radius: 8px; margin-top: 12px; overflow: hidden; }
        .fs-7 { font-size: 13px !important; }
        .text-muted-soft { color: rgba(0,0,0,0.6) !important; }

        /* 🎨 Facebook Style Elements (Colors & Grid) */
        .color-plate-btn { width: 24px; height: 24px; border-radius: 50%; cursor: pointer; border: 2px solid #fff; box-shadow: 0 0 4px rgba(0,0,0,0.2); transition: transform 0.2s; }
        .color-plate-btn:hover { transform: scale(1.15); }
        .fb-bg-1 { background: linear-gradient(135deg, #1877f2, #00c6ff) !important; color: white !important; }
        .fb-bg-2 { background: linear-gradient(135deg, #f12711, #f5af19) !important; color: white !important; }
        .fb-bg-3 { background: #1c1e21 !important; color: white !important; }
        .fb-bg-4 { background: linear-gradient(135deg, #e94e77, #6a1b9a) !important; color: white !important; }
        .fb-bg-5 { background: linear-gradient(135deg, #11998e, #38ef7d) !important; color: white !important; }
        .fb-bg-6 { background: linear-gradient(135deg, #ff9a9e, #fecfef) !important; color: #1c1e21 !important; }
        
        .fb-colored-post-render { min-height: 200px; display: flex; align-items: center; justify-content: center; text-align: center; font-size: 24px; font-weight: bold; padding: 20px; border-radius: 8px; }

        .fb-image-grid { display: grid; gap: 4px; border-radius: 8px; overflow: hidden; }
        .grid-1 { grid-template-columns: 1fr; }
        .grid-2 { grid-template-columns: 1fr 1fr; }
        .grid-3 { grid-template-columns: 1fr 1fr; grid-template-rows: 200px 150px; }
        .grid-3 .grid-item-0 { grid-column: span 2; height: 250px; }
        .grid-4 { grid-template-columns: repeat(3, 1fr); }
        .grid-4 .grid-item-0 { grid-column: span 3; height: 250px; }
        .fb-grid-img { width: 100%; height: 100%; object-fit: cover; cursor: pointer; transition: opacity 0.2s; }
        .fb-grid-img:hover { opacity: 0.9; }
    </style>
</head>
<body>

    <div class="container pb-5">
        <div class="row">
            
            <div class="col-lg-3 d-none d-lg-block">
                <div class="sidebar-card">
                    <div class="profile-cover"></div>
                    <div class="profile-pic-container pb-3 border-bottom">
                        @if(auth()->user()->profile_picture)
                            <img src="{{ asset('storage/'.auth()->user()->profile_picture) }}" class="profile-pic-sidebar">
                        @else
                            <div class="profile-pic-sidebar mx-auto d-flex align-items-center justify-content-center bg-secondary text-white fs-3 fw-bold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <h6 class="mt-3 mb-1 fw-bold text-dark px-2">{{ auth()->user()->name }}</h6>
                        <p class="text-muted-soft mb-0" style="font-size: 12px; padding: 0 10px;">{{ auth()->user()->role ?? 'Software Engineer | Web Developer' }}</p>
                        <button class="m-2 btn btn-sm btn-success">View Profile</button>
                    </div>
                    
                    <div class="py-2 border-bottom">
                        <div class="sidebar-stat"><span class="stat-label">Connections</span><span class="stat-value">120</span></div>
                    </div>
                    <div class="p-3 text-center">
                        <a href="#" class="text-decoration-none fw-semibold" style="font-size: 12px; color: rgba(0,0,0,0.6);">My items</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="post-card p-3 mb-3">
                    <div class="d-flex gap-2">
                        <div class="flex-shrink-0">
                            @if(auth()->user()->profile_picture)
                                <img src="{{ asset('storage/'.auth()->user()->profile_picture) }}" class="rounded-circle object-fit-cover" style="width: 48px; height: 48px;">
                            @else
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 48px; height: 48px; font-size: 20px;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <form id="ajaxPostForm" enctype="multipart/form-data">
                                @csrf
                                <div id="postInputWrapper" class="p-1 rounded bg-transparent">
                                    <textarea name="content" id="postContent" class="form-control border-0 shadow-none bg-transparent custom-textarea w-100" rows="2" placeholder="Start a post, {{ auth()->user()->name }}..."></textarea>
                                </div>
                                
                                <div id="imagePreviewContainer" class="mt-3 d-none row g-2"></div>
                                
                                <input type="hidden" id="bg_color_input" name="bg_color" value="">
                                <input type="file" name="image" id="postImageInput" accept="image/*,video/*" class="d-none" multiple>

                                <div class="d-flex justify-content-between align-items-center mt-3 pt-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" id="triggerUploadBtn" class="btn btn-link text-decoration-none d-flex align-items-center gap-2 fw-semibold p-1" style="color: #378fe9;" title="Add Photo/Video">
                                            <i class="bi bi-images fs-5"></i> <span class="d-none d-sm-inline" style="color: rgba(0,0,0,0.6); font-size: 14px;">Media</span>
                                        </button>
                                        
                                        <div class="position-relative d-flex align-items-center ms-2">
                                            <button type="button" class="btn btn-light btn-sm rounded-circle p-1 border-0" onclick="toggleColorPlates()" title="Background Color">
                                                <i class="bi bi-palette-fill text-warning fs-5"></i>
                                            </button>
                                            <div id="colorPlatesZone" class="d-none d-flex gap-1 bg-white p-1 rounded-pill border shadow-sm position-absolute start-100 ms-2" style="z-index: 100;">
                                                <div class="color-plate-btn fb-bg-1" onclick="selectPostBg('fb-bg-1')"></div>
                                                <div class="color-plate-btn fb-bg-2" onclick="selectPostBg('fb-bg-2')"></div>
                                                <div class="color-plate-btn fb-bg-3" onclick="selectPostBg('fb-bg-3')"></div>
                                                <div class="color-plate-btn fb-bg-4" onclick="selectPostBg('fb-bg-4')"></div>
                                                <div class="color-plate-btn fb-bg-5" onclick="selectPostBg('fb-bg-5')"></div>
                                                <div class="color-plate-btn fb-bg-6" onclick="selectPostBg('fb-bg-6')"></div>
                                                <i class="bi bi-x-circle-fill text-muted ms-1" style="cursor:pointer; font-size:16px; margin-top:2px;" onclick="resetPostBg()"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" id="submitBtn" class="btn btn-brand">Post</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <hr class="mb-3" style="border-color: #bfbfbf;">
                <div id="newsfeedContainer">
                    @forelse($posts as $post)
                        <div class="post-card p-3 mb-3" id="postCard-{{ $post->id }}">
                            
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="author-avatar-zone rounded-circle overflow-hidden border d-flex align-items-center justify-content-center bg-secondary text-white fw-bold" style="width: 40px; height: 40px; font-size: 16px;">
                                        @if($post->user->profile_picture)
                                            <img src="{{ asset('storage/' . $post->user->profile_picture) }}" class="w-100 h-100 object-fit-cover">
                                        @else
                                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold text-dark author-name-zone" style="font-size: 14px;">{{ $post->user->name }}</h6>
                                        <small class="text-uppercase text-muted fw-semibold author-role-zone" style="font-size: 10px;">{{ $post->user->role ?? 'Member' }}</small>
                                        <small class="text-muted d-block" style="font-size: 10px;">{{ $post->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>

                                @if($post->user_id === auth()->id())
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-link btn-sm text-muted p-0 border-0 shadow-none" data-bs-toggle="dropdown">
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm p-1" style="min-width: 100px;">
                                            <li>
                                                <a class="dropdown-item py-1 fs-7" href="javascript:void(0)" onclick="openEditModal({{ $post->id }}, '{{ addslashes($post->content) }}')">
                                                    <i class="bi bi-pencil me-1"></i> Edit
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

                            @if($post->bg_color && !$post->images && !$post->image && !$post->video)
                                <div class="fb-colored-post-render {{ $post->bg_color }} mb-3 shadow-sm dynamic-caption" data-bs-toggle="modal" data-bs-target="#commentModal-{{ $post->id }}" style="cursor: pointer;">
                                    {!! nl2br(e($post->content)) !!}
                                </div>
                            @else
                                @if($post->content)
                                    <div class="dynamic-caption mb-2 text-dark fs-6" style="white-space: pre-line;">{!! nl2br(e($post->content)) !!}</div>
                                @endif

                                @if($post->video)
                                    <div class="video-wrapper rounded border bg-black text-center mb-2 overflow-hidden dynamic-image-wrapper">
                                        <video src="{{ asset('storage/' . $post->video) }}" controls class="w-100" style="max-height: 400px;"></video>
                                    </div>
                                @endif

                                @if($post->images)
                                    @php 
                                        $imagesArray = is_array($post->images) ? $post->images : (json_decode($post->images, true) ?? []);
                                        $imgCount = count($imagesArray);
                                        $displayCount = $imgCount > 4 ? 4 : $imgCount; 
                                    @endphp
                                    @if($imgCount > 0)
                                        <div class="fb-image-grid grid-{{ $displayCount }} mb-2 dynamic-image-wrapper">
                                            @foreach(array_slice($imagesArray, 0, $displayCount) as $imgIndex => $imagePath)
                                                <div class="position-relative grid-item-{{ $imgIndex }}" style="height: {{ $imgCount == 1 ? 'auto' : '220px' }};">
                                                    <img src="{{ asset('storage/' . $imagePath) }}" class="fb-grid-img img-fluid" onclick="openLightbox('{{ asset('storage/' . $imagePath) }}')">
                                                    @if($imgIndex === 3 && $imgCount > 4)
                                                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center text-white fw-bold fs-4" onclick="openLightbox('{{ asset('storage/' . $imagePath) }}')" style="cursor: pointer;">
                                                            +{{ $imgCount - 4 }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @elseif($post->image)
                                    <div class="dynamic-image-wrapper rounded border bg-light text-center mb-2 overflow-hidden" style="max-height: 380px;">
                                        <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid object-fit-contain w-100" style="max-height: 380px;">
                                    </div>
                                @endif
                            @endif

                            @if($post->parentPost)
                                <div class="shared-post-box p-3 bg-light rounded border mb-2">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="rounded-circle overflow-hidden border d-flex align-items-center justify-content-center bg-secondary text-white fw-bold" style="width: 32px; height: 32px; font-size: 14px;">
                                            @if($post->parentPost->user->profile_picture)
                                                <img src="{{ asset('storage/' . $post->parentPost->user->profile_picture) }}" class="w-100 h-100 object-fit-cover">
                                            @else
                                                {{ strtoupper(substr($post->parentPost->user->name, 0, 1)) }}
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark" style="font-size: 13px;">{{ $post->parentPost->user->name }}</h6>
                                            <small class="text-muted" style="font-size: 10px;">{{ $post->parentPost->user->role ?? 'Member' }}</small>
                                        </div>
                                    </div>
                                    @if($post->parentPost->content)
                                        <p class="small text-secondary mb-2" style="white-space: pre-line;">{{ $post->parentPost->content }}</p>
                                    @endif
                                    @if($post->parentPost->image)
                                        <div class="text-center rounded border bg-white overflow-hidden" style="max-height: 280px;">
                                            <img src="{{ asset('storage/' . $post->parentPost->image) }}" class="img-fluid object-fit-contain mx-auto d-block" style="max-height: 280px;">
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center mt-3 mb-2 border-bottom pb-2">
                                <div class="d-flex align-items-center gap-2 text-muted small" id="like-zone-{{ $post->id }}">
                                    @if($post->likes->count() > 0)
                                        <i class="bi bi-heart-fill text-danger"></i>
                                        <span class="like-count-text">{{ $post->likes->count() }} Likes</span>
                                    @endif
                                </div>
                                <div class="text-muted small">
                                    <span id="comment-count-{{ $post->id }}" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#commentModal-{{ $post->id }}">
                                        {{ $post->comments->count() }} Comments
                                    </span>
                                </div>
                            </div>

                            <div class="d-flex text-muted fs-7 fw-bold">
                                <button type="button" onclick="toggleLike({{ $post->id }})" id="likeBtn-{{ $post->id }}" 
                                        class="btn btn-link d-flex align-items-center justify-content-center gap-2 py-2 w-100 text-decoration-none custom-btn-hover {{ $post->isLikedByAuthUser() ? 'text-primary' : 'text-muted' }}">
                                    <i class="bi {{ $post->isLikedByAuthUser() ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up' }}"></i> Like
                                </button>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#commentModal-{{ $post->id }}" class="btn btn-link text-muted d-flex align-items-center justify-content-center gap-2 py-2 w-100 text-decoration-none custom-btn-hover">
                                    <i class="bi bi-chat-dots"></i> Comment
                                </button>
                                <button type="button" onclick="openShareModal({{ $post->id }})" class="btn btn-link text-muted d-flex align-items-center justify-content-center gap-2 py-2 w-100 text-decoration-none custom-btn-hover">
                                    <i class="bi bi-share"></i> Share
                                </button>
                                <button type="button" onclick="toggleSavePost({{ $post->id }})" id="saveBtn-{{ $post->id }}" class="btn btn-link text-muted d-flex align-items-center justify-content-center gap-2 py-2 w-100 text-decoration-none custom-btn-hover">
                                    <i class="bi bi-bookmark"></i> Save
                                </button>
                            </div>
                        </div> 

                        <div class="modal fade" id="commentModal-{{ $post->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
                                <div class="modal-content border-0 shadow-lg rounded-4">
                                    <div class="modal-header border-bottom py-2 px-3">
                                        <h6 class="modal-title fw-bold text-center w-100 ps-4">{{ $post->user->name }}'s Post</h6>
                                        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                                    </div>
                                    
                                    <div class="modal-body p-3" style="max-height: 80vh;">
                                        <div class="p-3 border rounded-3 bg-white mb-4 shadow-sm">
                                            @if($post->bg_color && !$post->images && !$post->image && !$post->video)
                                                <div class="fb-colored-post-render {{ $post->bg_color }}">{!! nl2br(e($post->content)) !!}</div>
                                            @else
                                                @if($post->content)
                                                    <div class="mb-3 text-dark fs-6" style="white-space: pre-line;">{!! nl2br(e($post->content)) !!}</div>
                                                @endif
                                                @if($post->video)
                                                    <div class="rounded border bg-black text-center mb-3 overflow-hidden">
                                                        <video src="{{ asset('storage/' . $post->video) }}" controls class="w-100" style="max-height: 400px;"></video>
                                                    </div>
                                                @endif
                                                @if($post->images)
                                                    <div class="fb-image-grid grid-{{ $displayCount }} mb-2">
                                                        @foreach(array_slice($imagesArray, 0, $displayCount) as $imgIndex => $imagePath)
                                                            <div class="position-relative grid-item-{{ $imgIndex }}" style="height: {{ $imgCount == 1 ? 'auto' : '150px' }};">
                                                                <img src="{{ asset('storage/' . $imagePath) }}" class="fb-grid-img img-fluid">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif($post->image)
                                                    <div class="rounded border bg-light text-center mb-3 overflow-hidden" style="max-height: 400px;">
                                                        <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid object-fit-contain w-100" style="max-height: 400px;">
                                                    </div>
                                                @endif
                                            @endif
                                        </div>

                                        <div class="sticky-top bg-white pb-3 pt-1 border-bottom mb-3" style="z-index: 10;">
                                            <form onsubmit="submitComment(event, {{ $post->id }})">
                                                <div class="d-flex gap-2 align-items-end">
                                                    <textarea id="commentInput-{{ $post->id }}" class="form-control custom-textarea shadow-none p-2 border rounded-4 fs-7" rows="2" placeholder="Write a comment..." style="resize: none;" required></textarea>
                                                    <button type="submit" class="btn btn-primary btn-sm px-3 rounded-pill fw-bold fs-7">Post</button>
                                                </div>
                                            </form>
                                        </div>

                                        <div id="commentList-{{ $post->id }}">
                                            @forelse($post->comments as $index => $comment)
                                                <div class="bg-light p-2 px-3 rounded-4 mb-2 d-flex justify-content-between align-items-start comment-node-item {{ $index >= 15 ? 'd-none more-comments-' . $post->id : '' }}" id="comment-container-{{ $comment->id }}">
                                                    <div class="flex-grow-1">
                                                        <strong class="small text-dark d-block" style="font-size: 12px;">{{ $comment->user->name }}</strong>
                                                        <span class="small text-dark-50" id="comment-text-{{ $comment->id }}" style="font-size: 13px;">{{ $comment->content }}</span>
                                                    </div>
                                                    
                                                    @if($comment->user_id === auth()->id())
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-link btn-sm text-muted p-0 border-0 shadow-none" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm p-1" style="min-width: 100px;">
                                                                <li><a class="dropdown-item py-1 fs-7" href="javascript:void(0)" onclick="editComment(event, {{ $comment->id }})"><i class="bi bi-pencil me-1"></i> Edit</a></li>
                                                                <li><a class="dropdown-item py-1 fs-7 text-danger" href="javascript:void(0)" onclick="deleteComment({{ $comment->id }}, {{ $post->id }})"><i class="bi bi-trash me-1"></i> Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </div>
                                            @empty
                                                <div class="text-center py-3 text-muted dynamic-no-comment-{{ $post->id }}"><small>No comments yet.</small></div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="post-card p-5 text-center text-muted"><h5>No Posts Found</h5></div>
                    @endforelse
                </div>
            </div>

            <div class="col-lg-3 d-none d-lg-block">
                <div class="sidebar-card p-3">
                    <h5 class="widget-title">Popular Jobs for You</h5>
                    <div class="job-item">
                        <div class="job-icon">L</div>
                        <div>
                            <p class="job-title">Laravel Developer</p>
                            <p class="job-company">Tech Soft BD • Dhaka</p>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25" style="font-size: 10px;">Actively recruiting</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="fbShareModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 8px;">
                <div class="modal-header border-bottom px-4 py-3">
                    <h5 class="modal-title fw-semibold text-dark fs-5">Share Post</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" onclick="closeShareModal()"></button>
                </div>
                <form id="fbShareForm" class="m-0">
                    @csrf
                    <input type="hidden" id="targetSharePostId">
                    <div class="modal-body p-4">
                        <textarea id="shareComment" rows="2" class="form-control custom-textarea w-100 mb-3" placeholder="What do you want to talk about?"></textarea>
                        <div id="modalPostPreview" class="border rounded-3 p-3 bg-light user-select-none overflow-hidden"></div>
                    </div>
                    <div class="modal-footer border-top px-4 py-3">
                        <button type="button" class="btn fw-semibold" style="color: rgba(0,0,0,0.6);" data-bs-dismiss="modal" onclick="closeShareModal()">Cancel</button>
                        <button type="submit" id="shareSubmitBtn" class="btn btn-brand">Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editPostModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 8px;">
                <div class="modal-header border-bottom px-4 py-3">
                    <h5 class="modal-title fw-semibold text-dark fs-5">Edit Post</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" onclick="closeEditModal()"></button>
                </div>
                <form id="editPostForm" enctype="multipart/form-data" class="m-0">
                    @csrf
                    <input type="hidden" id="editPostId">
                    <div class="modal-body p-4">
                        <textarea id="editPostContent" name="content" rows="4" class="form-control custom-textarea w-100 mb-3"></textarea>
                    </div>
                    <div class="modal-footer border-top px-4 py-3">
                        <button type="submit" id="editSubmitBtn" class="btn btn-brand">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageLightboxModal" tabindex="-1" aria-hidden="true" style="background: rgba(0,0,0,0.9);">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-header border-0 p-0 position-absolute top-0 end-0 m-3" style="z-index: 1050;">
                    <button type="button" class="btn-close btn-close-white shadow-none fs-4" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img id="lightboxTargetImage" src="" class="img-fluid rounded shadow-lg" style="max-height: 90vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        let selectedMediaFiles = [];
        let bootstrapEditModal = null;
        let bootstrapShareModal = null;
        let bootstrapLightboxModal = null;

        document.addEventListener("DOMContentLoaded", function() {
            bootstrapEditModal = new bootstrap.Modal(document.getElementById('editPostModal'));
            bootstrapShareModal = new bootstrap.Modal(document.getElementById('fbShareModal'));
            bootstrapLightboxModal = new bootstrap.Modal(document.getElementById('imageLightboxModal'));
        });

        // 🎨 Color Palette Logic
        function toggleColorPlates() { document.getElementById('colorPlatesZone').classList.toggle('d-none'); }
        function selectPostBg(className) {
            const wrapper = document.getElementById('postInputWrapper');
            const textarea = document.getElementById('postContent');
            wrapper.className = "p-2 rounded fb-colored-post-render " + className;
            textarea.style.fontSize = "24px"; textarea.style.textAlign = "center"; textarea.style.color = "white"; textarea.placeholder = ""; 
            document.getElementById('bg_color_input').value = className;
            
            // কালার দিলে মিডিয়া ক্লিয়ার হয়ে যাবে
            selectedMediaFiles = [];
            renderMediaPreviews();
        }
        function resetPostBg() {
            const wrapper = document.getElementById('postInputWrapper');
            const textarea = document.getElementById('postContent');
            wrapper.className = "p-1 rounded bg-transparent";
            textarea.style.fontSize = "14px"; textarea.style.textAlign = "left"; textarea.style.color = "inherit"; textarea.placeholder = "Start a post...";
            document.getElementById('bg_color_input').value = "";
        }

        // 📸 Media Upload Logic (Unlimited Files with Error Handling)
        const imageInput = document.getElementById('postImageInput');
        const previewContainer = document.getElementById('imagePreviewContainer');

        document.getElementById('triggerUploadBtn').addEventListener('click', function() { imageInput.click(); });

        imageInput.addEventListener('change', function() {
            const files = Array.from(this.files);
            const MAX_SIZE = 50 * 1024 * 1024; // 50MB Limit
            
            for(let file of files) {
                if(file.size > MAX_SIZE) {
                    Swal.fire({ icon: 'error', title: 'File too large!', text: `"${file.name}" is too big.`, confirmButtonColor: '#0a66c2' });
                    imageInput.value = '';
                    return;
                }
            }

            // ছবি সিলেক্ট করলে কালার ক্লিয়ার হবে
            resetPostBg();
            
            files.forEach(file => selectedMediaFiles.push(file));
            renderMediaPreviews();
            imageInput.value = ''; 
        });

        function renderMediaPreviews() {
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
                } else {
                    mediaElement = document.createElement('img');
                    mediaElement.src = URL.createObjectURL(file);
                    mediaElement.className = 'w-100 h-100 object-fit-cover rounded border';
                }
                
                const closeBtn = document.createElement('button');
                closeBtn.type = 'button';
                closeBtn.className = 'btn btn-dark btn-sm position-absolute top-0 end-0 m-1 rounded-circle';
                closeBtn.style.cssText = 'background: rgba(0,0,0,0.7); border:none; width: 22px; height: 22px; display: flex; align-items: center; justify-content: center; z-index: 10; padding:0;';
                closeBtn.innerHTML = '<i class="bi bi-x-lg" style="font-size: 10px; color:#fff;"></i>';
                
                closeBtn.addEventListener('click', function() {
                    selectedMediaFiles.splice(index, 1);
                    renderMediaPreviews();
                });
                
                col.appendChild(mediaElement);
                col.appendChild(closeBtn);
                previewContainer.appendChild(col);
            });
        }

        // 🚀 Post Submission (With Warning for Freezing)
        document.getElementById('ajaxPostForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const content = document.getElementById('postContent').value.trim();
            const hasFile = selectedMediaFiles.length > 0;
            const hasColor = document.getElementById('bg_color_input').value !== "";

            if (content === "" && !hasFile) {
                Swal.fire({ icon: 'warning', title: 'খালি পোস্ট!', text: 'কিছু লিখুন অথবা মিডিয়া সিলেক্ট করুন।', confirmButtonColor: '#0a66c2' });
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Posting...';

            const formData = new FormData(this);
            formData.delete('image');
            selectedMediaFiles.forEach(file => formData.append('images[]', file));

            fetch("{{ route('posts.store') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' },
                body: formData
            })
            .then(async res => {
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'সার্ভার এরর বা ফাইল অতিরিক্ত বড়।');
                return data;
            })
            .then(data => {
                if(data.success) window.location.reload();
            })
            .catch(err => {
                Swal.fire({ icon: 'error', title: 'পোস্ট ব্যর্থ!', text: err.message });
                submitBtn.disabled = false; submitBtn.innerText = 'Post';
            });
        });

        // 👍 Like Logic
        function toggleLike(postId) {
            const likeBtn = document.getElementById(`likeBtn-${postId}`);
            const likeZone = document.getElementById(`like-zone-${postId}`);
            
            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' }
            }).then(res => res.json()).then(data => {
                if(data.success) {
                    if(data.liked) {
                        likeBtn.classList.remove('text-muted'); likeBtn.classList.add('text-primary');
                        likeBtn.innerHTML = `<i class="bi bi-hand-thumbs-up-fill"></i> Like`;
                    } else {
                        likeBtn.classList.remove('text-primary'); likeBtn.classList.add('text-muted');
                        likeBtn.innerHTML = `<i class="bi bi-hand-thumbs-up"></i> Like`;
                    }
                    likeZone.innerHTML = data.like_count > 0 ? `<i class="bi bi-heart-fill text-danger"></i> <span class="like-count-text">${data.like_count} Likes</span>` : '';
                }
            });
        }

        // 💬 Comment Logic (Ajax Append)
        function submitComment(e, postId) {
            e.preventDefault();
            const inputField = document.getElementById(`commentInput-${postId}`);
            const commentContent = inputField.value.trim();
            if(!commentContent) return;

            fetch(`/posts/${postId}/comments`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' },
                body: JSON.stringify({ content: commentContent })
            }).then(res => res.json()).then(data => {
                if(data.success) {
                    inputField.value = '';
                    const mainCounter = document.getElementById(`comment-count-${postId}`);
                    if(mainCounter) mainCounter.innerText = `${data.comment_count} Comments`;
                    
                    const noCommentMsg = document.querySelector(`.dynamic-no-comment-${postId}`);
                    if(noCommentMsg) noCommentMsg.remove();

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

        // ✏️ Edit Comment (NO RELOAD)
        function editComment(e, commentId) {
    e.preventDefault();
    const commentTextElement = document.getElementById(`comment-text-${commentId}`);
    const currentText = commentTextElement.innerText;

    Swal.fire({
        title: 'Edit Comment',
        input: 'textarea',
        inputValue: currentText,
        inputAttributes: {
            'aria-label': 'Type your comment here',
            'style': 'min-height: 100px;'
        },
        showCancelButton: true,
        confirmButtonText: 'Update',
        confirmButtonColor: '#0a66c2',
        didOpen: () => {
            // কারসর ফোকাস নিশ্চিত করা
            const textarea = Swal.getPopup().querySelector('textarea');
            textarea.focus();
            textarea.setSelectionRange(textarea.value.length, textarea.value.length);
        }
    }).then((result) => {
        if (result.isConfirmed && result.value.trim() !== '') {
            // আপনার আগের fetch কলটি এখানে বসান...
        }
    });
}

        // 🗑️ Delete Comment
        function deleteComment(commentId, postId) {
            Swal.fire({
                title: 'Delete comment?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/comments/${commentId}`, {
                        method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' }
                    }).then(res => res.json()).then(data => {
                        if(data.success) {
                            document.getElementById(`comment-container-${commentId}`).remove();
                            const mainCounter = document.getElementById(`comment-count-${postId}`);
                            if(mainCounter && data.comment_count !== undefined) mainCounter.innerText = `${data.comment_count} Comments`;
                        }
                    });
                }
            });
        }

        // 🔖 Toggle Save Post
        function toggleSavePost(postId) {
            const saveBtn = document.getElementById(`saveBtn-${postId}`);
            const isSaved = saveBtn.innerHTML.includes('bi-bookmark-fill'); // Check UI state

            fetch(`/posts/${postId}/save`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Accept': 'application/json' }
            }).then(res => res.json()).then(data => {
                // UI Toggle Logic
                if (isSaved) {
                    saveBtn.innerHTML = `<i class="bi bi-bookmark"></i> Save`;
                    Swal.fire({ icon: 'info', title: 'Post Unsaved', timer: 1200, showConfirmButton: false });
                } else {
                    saveBtn.innerHTML = `<i class="bi bi-bookmark-fill text-warning"></i> Unsave`;
                    Swal.fire({ icon: 'success', title: 'Post Saved', timer: 1200, showConfirmButton: false });
                }
            }).catch(err => {
                // Front-end test toggle if API fails
                if (isSaved) {
                    saveBtn.innerHTML = `<i class="bi bi-bookmark"></i> Save`;
                    Swal.fire({ icon: 'info', title: 'Post Unsaved', timer: 1200, showConfirmButton: false });
                } else {
                    saveBtn.innerHTML = `<i class="bi bi-bookmark-fill text-warning"></i> Unsave`;
                    Swal.fire({ icon: 'success', title: 'Post Saved', timer: 1200, showConfirmButton: false });
                }
            });
        }

        // 🔄 Share Post Logic
        function openShareModal(postId) {
            document.getElementById('targetSharePostId').value = postId;
            const postCard = document.getElementById(`postCard-${postId}`);
            const authorName = postCard.querySelector('.author-name-zone').innerText;
            const avatarInner = postCard.querySelector('.author-avatar-zone').innerHTML;
            const captionEl = postCard.querySelector('.dynamic-caption');
            const captionHtml = captionEl ? `<div class="small text-secondary mt-1 mb-0">${captionEl.innerHTML}</div>` : '';
            
            let imageHtml = '';
            let imageWrapper = postCard.querySelector('.dynamic-image-wrapper img, .dynamic-image-wrapper video');
            if(imageWrapper) {
                if(imageWrapper.tagName.toLowerCase() === 'img') {
                    imageHtml = `<div class="mt-2 rounded border bg-white text-center d-flex align-items-center justify-content-center" style="max-height:150px; overflow:hidden;"><img src="${imageWrapper.getAttribute('src')}" class="img-fluid object-fit-contain" style="max-height:150px;"></div>`;
                } else {
                    imageHtml = `<div class="mt-2 rounded border bg-black text-center" style="max-height:150px; overflow:hidden;"><video src="${imageWrapper.getAttribute('src')}" class="img-fluid w-100" style="max-height:150px;"></video></div>`;
                }
            }

            document.getElementById('modalPostPreview').innerHTML = `
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-circle overflow-hidden border d-flex align-items-center justify-content-center bg-secondary text-white fw-bold" style="width:28px; height:28px; font-size:12px;">${avatarInner}</div>
                    <h6 class="mb-0 fw-bold text-dark" style="font-size:13px;">${authorName}</h6>
                </div>
                ${captionHtml}
                ${imageHtml}
            `;
            bootstrapShareModal.show();
        }
        function closeShareModal() { bootstrapShareModal.hide(); }

        document.getElementById('fbShareForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const postId = document.getElementById('targetSharePostId').value;
            const shareSubmitBtn = document.getElementById('shareSubmitBtn');
            shareSubmitBtn.disabled = true; shareSubmitBtn.innerHTML = 'Posting...';

            const shareData = new FormData();
            shareData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            shareData.append('content', document.getElementById('shareComment').value.trim());

            fetch(`/posts/${postId}/share`, {
                method: 'POST', headers: { 'Accept': 'application/json' }, body: shareData
            }).then(res => res.json()).then(data => {
                if(data.success) window.location.reload();
            });
        });

        function openLightbox(src) {
            document.getElementById('lightboxTargetImage').src = src;
            bootstrapLightboxModal.show();
        }
    </script>
</body>
</html>