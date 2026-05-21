<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">

    <title>Borobhai.com News Feed</title>

    <style>
        /* --- LinkedIn/Facebook Style Custom CSS --- */
        body { font-family: 'Inter', -apple-system, sans-serif; background-color: #f3f2ef; color: rgba(0,0,0,0.9); padding-top: 20px; }
        .search-bar { background-color: #eef3f8; border: none; border-radius: 4px; padding-left: 35px; height: 34px; font-size: 14px; }
        .post-card, .sidebar-card { background: #ffffff; border-radius: 8px; border: 1px solid #e0dfdc; margin-bottom: 16px; overflow: hidden; }
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
        .custom-btn-hover:hover { background-color: #ebebeb; color: rgba(0,0,0,0.9) !important; border-radius: 4px; }
        .shared-post-box { border: 1px solid #e0dfdc; border-radius: 8px; margin-top: 12px; overflow: hidden; }
        .fs-7 { font-size: 13px !important; }
        .text-muted-soft { color: rgba(0,0,0,0.6) !important; }

        /* 🎨 Facebook Style Elements */
        .color-plate-btn { width: 24px; height: 24px; border-radius: 50%; cursor: pointer; border: 2px solid #fff; box-shadow: 0 0 4px rgba(0,0,0,0.2); transition: transform 0.2s; }
        .color-plate-btn:hover { transform: scale(1.15); }
        .fb-bg-1 { background: linear-gradient(135deg, #1877f2, #00c6ff) !important; color: white !important; }
        .fb-bg-2 { background: linear-gradient(135deg, #f12711, #f5af19) !important; color: white !important; }
        .fb-bg-3 { background: #1c1e21 !important; color: white !important; }
        .fb-bg-4 { background: linear-gradient(135deg, #e94e77, #6a1b9a) !important; color: white !important; }
        .fb-bg-5 { background: linear-gradient(135deg, #11998e, #38ef7d) !important; color: white !important; }
        .fb-colored-post-render { min-height: 200px; display: flex; align-items: center; justify-content: center; text-align: center; font-size: 22px; font-weight: bold; padding: 20px; border-radius: 8px; }
        
        .fb-image-grid { display: grid; gap: 4px; border-radius: 8px; overflow: hidden; }
        .grid-1 { grid-template-columns: 1fr; }
        .grid-2 { grid-template-columns: 1fr 1fr; }
        .grid-3 { grid-template-columns: 1fr 1fr; grid-template-rows: 200px 150px; }
        .grid-3 .grid-item-0 { grid-column: span 2; height: 250px; }
        .grid-4 { grid-template-columns: repeat(3, 1fr); }
        .grid-4 .grid-item-0 { grid-column: span 3; height: 250px; }
        .fb-grid-img { width: 100%; height: 100%; object-fit: cover; cursor: pointer; transition: opacity 0.2s; }
        .fb-grid-img:hover { opacity: 0.9; }
        .media-preview-container img, .media-preview-container video { width: 80px; height: 80px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd; }
    </style>

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
                        <div class="sidebar-stat"><span class="stat-label">Connections</span><span class="stat-value"></span></div>
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
                            <form id="createPostForm" enctype="multipart/form-data">
                                <div id="postInputWrapper" class="p-1 rounded bg-transparent">
                                    <textarea name="content" id="postContent" class="form-control border-0 shadow-none bg-transparent custom-textarea w-100" rows="2" placeholder="Start a post, {{ auth()->user()->name }}..."></textarea>
                                </div>
                                
                                <div id="mediaPreview" class="d-flex gap-2 flex-wrap my-2 media-preview-container"></div>
                                
                                <input type="hidden" id="bg_color_input" name="bg_color" value="">
                                <input type="file" id="imageInput" name="images[]" multiple accept="image/*" class="d-none" onchange="previewMedia(this, 'image')">
                                <input type="file" id="videoInput" name="video" accept="video/*" class="d-none" onchange="previewMedia(this, 'video')">

                                <div class="d-flex justify-content-between align-items-center mt-3 pt-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-link text-decoration-none d-flex align-items-center gap-1 fw-semibold p-1" style="color: #378fe9;" onclick="document.getElementById('imageInput').click()" title="Add Photo">
                                            <i class="bi bi-images fs-5"></i>
                                        </button>
                                        <button type="button" class="btn btn-link text-decoration-none d-flex align-items-center gap-1 fw-semibold p-1" style="color: #e93737;" onclick="document.getElementById('videoInput').click()" title="Add Video">
                                            <i class="bi bi-play-btn-fill fs-5"></i>
                                        </button>
                                        
                                        <div class="position-relative d-flex align-items-center ms-2">
                                            <button type="button" class="btn btn-light btn-sm rounded-circle p-1 border-0" onclick="toggleColorPlates()" title="Background Color">
                                                <i class="bi bi-palette-fill text-warning fs-5"></i>
                                            </button>
                                            <div id="colorPlatesZone" class="d-none d-flex gap-1 bg-white p-1 rounded-pill border shadow-sm position-absolute start-100 ms-2" style="z-index: 100; min-width: 160px;">
                                                <div class="color-plate-btn fb-bg-1" onclick="selectPostBg('fb-bg-1')"></div>
                                                <div class="color-plate-btn fb-bg-2" onclick="selectPostBg('fb-bg-2')"></div>
                                                <div class="color-plate-btn fb-bg-3" onclick="selectPostBg('fb-bg-3')"></div>
                                                <div class="color-plate-btn fb-bg-4" onclick="selectPostBg('fb-bg-4')"></div>
                                                <div class="color-plate-btn fb-bg-5" onclick="selectPostBg('fb-bg-5')"></div>
                                                <i class="bi bi-x-circle-fill text-muted ms-1" style="cursor:pointer; font-size:16px; margin-top:2px;" onclick="resetPostBg()"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" id="submitBtn" class="btn btn-brand px-4">Post</button>
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
                                        <button class="btn btn-link btn-sm text-muted p-0 border-0 shadow-none" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm p-1" style="min-width: 100px;">
                                            <li><a class="dropdown-item py-1 fs-7" href="javascript:void(0)" onclick="openEditModal({{ $post->id }}, '{{ addslashes($post->content) }}')"><i class="bi bi-pencil me-1"></i> Edit</a></li>
                                            <li><a class="dropdown-item py-1 fs-7 text-danger" href="javascript:void(0)" onclick="deletePost({{ $post->id }})"><i class="bi bi-trash me-1"></i> Delete</a></li>
                                        </ul>
                                    </div>
                                @endif
                            </div>

                            @if($post->bg_color && !$post->images && !$post->video)
                                <div class="fb-colored-post-render {{ $post->bg_color }} mb-3 dynamic-caption" data-bs-toggle="modal" data-bs-target="#commentModal-{{ $post->id }}" style="cursor: pointer;">
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
                                                <div class="position-relative grid-item-{{ $imgIndex }}" style="height: {{ $imgCount == 1 ? 'auto' : '180px' }};">
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
                                        </div>
                                    </div>
                                    @if($post->parentPost->content)
                                        <p class="small text-secondary mb-2" style="white-space: pre-line;">{{ $post->parentPost->content }}</p>
                                    @endif
                                    @if($post->parentPost->images)
                                        @php 
                                            $parentImages = is_array($post->parentPost->images) ? $post->parentPost->images : (json_decode($post->parentPost->images, true) ?? []); 
                                        @endphp
                                        @if(count($parentImages) > 0)
                                            <div class="text-center rounded border bg-white overflow-hidden" style="max-height: 280px;">
                                                <img src="{{ asset('storage/' . $parentImages[0]) }}" class="img-fluid object-fit-contain mx-auto d-block" style="max-height: 280px;">
                                            </div>
                                        @endif
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
                                <button onclick="toggleLike({{ $post->id }})" id="likeBtn-{{ $post->id }}" class="btn btn-link d-flex align-items-center justify-content-center gap-2 py-2 w-100 text-decoration-none custom-btn-hover {{ $post->isLikedByAuthUser() ? 'text-primary' : 'text-muted' }}">
                                    <i class="bi {{ $post->isLikedByAuthUser() ? 'bi-hand-thumbs-up-fill' : 'bi-hand-thumbs-up' }}"></i> Like
                                </button>
                                <button type="button" data-bs-toggle="modal" data-bs-target="#commentModal-{{ $post->id }}" class="btn btn-link text-muted d-flex align-items-center justify-content-center gap-2 py-2 w-100 text-decoration-none custom-btn-hover">
                                    <i class="bi bi-chat-dots"></i> Comment
                                </button>
                                <button onclick="openShareModal({{ $post->id }})" class="btn btn-link text-muted d-flex align-items-center justify-content-center gap-2 py-2 w-100 text-decoration-none custom-btn-hover">
                                    <i class="bi bi-share"></i> Share
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
                                            <div class="d-flex align-items-center gap-2 mb-3">
                                                <div class="rounded-circle overflow-hidden border d-flex align-items-center justify-content-center bg-secondary text-white fw-bold" style="width: 40px; height: 40px; font-size: 16px;">
                                                    @if($post->user->profile_picture)
                                                        <img src="{{ asset('storage/' . $post->user->profile_picture) }}" class="w-100 h-100 object-fit-cover">
                                                    @else
                                                        {{ strtoupper(substr($post->user->name, 0, 1)) }}
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 14px;">{{ $post->user->name }}</h6>
                                                    <small class="text-muted" style="font-size: 11px;">{{ $post->user->role ?? 'Member' }} • {{ $post->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>

                                            @if($post->bg_color && !$post->images && !$post->video)
                                                <div class="fb-colored-post-render {{ $post->bg_color }} mb-3">{!! nl2br(e($post->content)) !!}</div>
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
                                                    @if($imgCount > 0)
                                                        <div class="fb-image-grid grid-{{ $displayCount }} mb-3">
                                                            @foreach(array_slice($imagesArray, 0, $displayCount) as $imgIndex => $imagePath)
                                                                <div class="position-relative grid-item-{{ $imgIndex }}" style="height: {{ $imgCount == 1 ? 'auto' : '200px' }};">
                                                                    <img src="{{ asset('storage/' . $imagePath) }}" class="fb-grid-img img-fluid" onclick="openLightbox('{{ asset('storage/' . $imagePath) }}')">
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                @endif
                                            @endif
                                        </div>

                                        <div class="sticky-top bg-white pb-3 pt-1 border-bottom mb-3" style="z-index: 10;">
                                            <form onsubmit="submitComment(event, {{ $post->id }})">
                                                <div class="d-flex gap-2 align-items-end">
                                                    <textarea id="commentInput-{{ $post->id }}" class="form-control custom-textarea shadow-none p-2 border rounded-4 fs-7" rows="2" placeholder="Write a comment..." style="resize: none;" required></textarea>
                                                    <div class="d-flex gap-1">
                                                        <button type="submit" class="btn btn-primary btn-sm px-3 rounded-pill fw-bold fs-7">Post</button>
                                                    </div>
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
                                                            <button class="btn btn-link btn-sm text-muted p-0 border-0 shadow-none" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm p-1" style="min-width: 100px;">
                                                                <li><a class="dropdown-item py-1 fs-7" href="javascript:void(0)" onclick="editComment({{ $comment->id }})"><i class="bi bi-pencil me-1"></i> Edit</a></li>
                                                                <li><a class="dropdown-item py-1 fs-7 text-danger" href="javascript:void(0)" onclick="deleteComment({{ $comment->id }})"><i class="bi bi-trash me-1"></i> Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </div>
                                            @empty
                                                <div class="text-center py-3 text-muted dynamic-no-comment-{{ $post->id }}"><small>No comments yet.</small></div>
                                            @endforelse
                                        </div>
                                        @if($post->comments->count() > 15)
                                            <div class="text-center mt-2" id="viewMoreBtnZone-{{ $post->id }}">
                                                <button type="button" class="btn btn-link btn-sm text-decoration-none fw-bold text-primary fs-7" onclick="showAllComments({{ $post->id }})">View more comments...</button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="post-card p-5 text-center text-muted">
                            <h5>No Posts Found</h5>
                        </div>
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
                    <a href="#" class="text-decoration-none fw-semibold" style="font-size: 13px; color: rgba(0,0,0,0.6);">Show more <i class="bi bi-arrow-right"></i></a>
                </div>

                <div class="sidebar-card p-3">
                    <h5 class="widget-title">Active Now</h5>
                    <div class="user-item">
                        <div class="position-relative">
                            <img src="https://ui-avatars.com/api/?name=Nasrin&background=random" class="rounded-circle" width="40" height="40">
                            <div class="active-dot"></div>
                        </div>
                        <div>
                            <p class="user-name">Nasrin Akter</p>
                            <p class="user-role">Student at Sonargaon University</p>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-3 text-muted-soft px-3" style="font-size: 12px; line-height: 1.8;">
                    <span>LinkedIn/Facebook Clone © 2026</span>
                </div>
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

    <div class="modal fade" id="fbShareModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 8px;">
                <div class="modal-header border-bottom px-4 py-3">
                    <h5 class="modal-title fw-semibold text-dark fs-5">Share Post</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" onclick="closeShareModal()"></button>
                </div>
                <form id="fbShareForm" class="m-0">
                    <input type="hidden" id="targetSharePostId">
                    <div class="modal-body p-4">
                        <textarea id="shareComment" rows="2" class="form-control custom-textarea w-100 mb-3" placeholder="What do you want to talk about?"></textarea>
                        <div id="modalPostPreview" class="border rounded-3 p-3 bg-light user-select-none overflow-hidden"></div>
                    </div>
                    <div class="modal-footer border-top px-4 py-3">
                        <button type="submit" id="shareSubmitBtn" class="btn btn-brand">Share Now</button>
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
                <form id="editPostForm" class="m-0">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        let bootstrapEditModal = null;
        let bootstrapShareModal = null;
        let bootstrapLightboxModal = null;

        document.addEventListener("DOMContentLoaded", function() {
            bootstrapEditModal = new bootstrap.Modal(document.getElementById('editPostModal'));
            bootstrapShareModal = new bootstrap.Modal(document.getElementById('fbShareModal'));
            bootstrapLightboxModal = new bootstrap.Modal(document.getElementById('imageLightboxModal'));
        });

        // 🎨 Media & Color Selection Logic
        function toggleColorPlates() {
            document.getElementById('colorPlatesZone').classList.toggle('d-none');
        }

        function selectPostBg(className) {
            const wrapper = document.getElementById('postInputWrapper');
            const textarea = document.getElementById('postContent');
            
            wrapper.className = "p-2 rounded fb-colored-post-render " + className;
            textarea.style.fontSize = "22px";
            textarea.style.textAlign = "center";
            textarea.style.color = "white";
            textarea.placeholder = ""; 
            document.getElementById('bg_color_input').value = className;

            document.getElementById('imageInput').value = "";
            document.getElementById('videoInput').value = "";
            document.getElementById('mediaPreview').innerHTML = "";
        }

        function resetPostBg() {
            const wrapper = document.getElementById('postInputWrapper');
            const textarea = document.getElementById('postContent');
            
            wrapper.className = "p-1 rounded bg-transparent";
            textarea.style.fontSize = "14px";
            textarea.style.textAlign = "left";
            textarea.style.color = "inherit";
            textarea.placeholder = "Start a post...";
            document.getElementById('bg_color_input').value = "";
        }

        function previewMedia(input, type) {
            const previewContainer = document.getElementById('mediaPreview');
            resetPostBg(); 
            previewContainer.innerHTML = ""; 

            if (type === 'image' && input.files) {
                Array.from(input.files).forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        previewContainer.appendChild(img);
                    }
                    reader.readAsDataURL(file);
                });
            } else if (type === 'video' && input.files[0]) {
                const video = document.createElement('video');
                video.src = URL.createObjectURL(input.files[0]);
                video.muted = true;
                previewContainer.appendChild(video);
            }
        }

        function openLightbox(src) {
            document.getElementById('lightboxTargetImage').src = src;
            bootstrapLightboxModal.show();
        }

        // 📝 Submit Post
        document.getElementById('createPostForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = 'Posting...';

            fetch("{{ route('posts.store') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: new FormData(this)
            })
            .then(res => res.json())
            .then(data => { if(data.success) window.location.reload(); })
            .catch(err => { console.error(err); btn.disabled = false; btn.innerHTML = 'Post'; });
        });

        // 👍 Like Logic
        function toggleLike(postId) {
            fetch(`/posts/${postId}/like`, {
                method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            }).then(res => res.json()).then(data => {
                if(data.success) window.location.reload(); 
            });
        }

        // 💬 Comment Logic
        function submitComment(e, postId) {
            e.preventDefault();
            const inputField = document.getElementById(`commentInput-${postId}`);
            if(!inputField.value.trim()) return;

            fetch(`/posts/${postId}/comments`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ content: inputField.value.trim() })
            }).then(res => res.json()).then(data => {
                if(data.success) window.location.reload();
            });
        }

        function showAllComments(postId) {
            document.querySelectorAll(`.more-comments-${postId}`).forEach(c => c.classList.remove('d-none'));
            document.getElementById(`viewMoreBtnZone-${postId}`)?.remove();
        }

        function editComment(commentId) {
            const currentText = document.getElementById(`comment-text-${commentId}`).innerText;
            Swal.fire({
                title: 'Edit Comment', input: 'textarea', inputValue: currentText, showCancelButton: true, confirmButtonColor: '#4f46e5'
            }).then((res) => {
                if(res.isConfirmed && res.value.trim() !== '') {
                    fetch(`/comments/${commentId}`, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: JSON.stringify({ content: res.value.trim() })
                    }).then(r => r.json()).then(d => { if(d.success) window.location.reload(); });
                }
            });
        }

        function deleteComment(commentId) {
            Swal.fire({
                title: 'Delete comment?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d11124'
            }).then((res) => {
                if(res.isConfirmed) {
                    fetch(`/comments/${commentId}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } })
                    .then(r => r.json()).then(d => { if(d.success) window.location.reload(); });
                }
            });
        }

        // 🔄 Share Logic
        function openShareModal(postId) {
            document.getElementById('targetSharePostId').value = postId;
            document.getElementById('modalPostPreview').innerHTML = `<p class="text-muted text-center py-3">Ready to share this post!</p>`;
            bootstrapShareModal.show();
        }
        function closeShareModal() { bootstrapShareModal.hide(); }
        
        document.getElementById('fbShareForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('content', document.getElementById('shareComment').value.trim());

            fetch(`/posts/${document.getElementById('targetSharePostId').value}/share`, {
                method: 'POST', headers: { 'Accept': 'application/json' }, body: formData
            }).then(r => r.json()).then(d => { if(d.success) window.location.reload(); });
        });

        // ✏️ Edit Post Logic
        function openEditModal(postId, content) {
            document.getElementById('editPostId').value = postId;
            document.getElementById('editPostContent').value = content;
            bootstrapEditModal.show();
        }
        function closeEditModal() { bootstrapEditModal.hide(); }

        document.getElementById('editPostForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('_token', csrfToken);
            formData.append('_method', 'PUT');

            fetch(`/posts/${document.getElementById('editPostId').value}`, {
                method: 'POST', headers: { 'Accept': 'application/json' }, body: formData
            }).then(r => r.json()).then(d => { if(d.success) window.location.reload(); });
        });
    </script>
</x-app-layout>