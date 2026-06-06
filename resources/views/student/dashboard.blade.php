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
                @include('partials.post-card', ['post' => $post])
            @empty
                <div id="emptyFeedState" class="card p-5 text-center shadow-sm border-0 rounded-3 my-3 bg-white">
                    <div class="card-body">
                        <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                        <h5 class="fw-bold text-secondary">No post available right now</h5>
                        <p class="text-muted small mb-0">Be the first one to share something!</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Infinite Scroll: Loader + Sentinel --}}
        <div id="feedLoader" class="text-center py-4 d-none">
            <div class="spinner-border text-primary" role="status" style="width:2rem;height:2rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div id="feedEndMessage" class="text-center text-muted py-4 d-none">
            <i class="bi bi-check2-circle me-1"></i> You're all caught up!
        </div>

        {{-- Pagination data holder --}}
        <div id="feedMeta"
            data-next-page="2"
            data-has-more="{{ $posts->hasMorePages() ? '1' : '0' }}"></div>

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

                    <div id="editMediaSection">
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
                    </div>


                <div class="modal-footer border-top-0 pt-1">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="editSubmitBtn" class="btn btn-primary btn-sm px-4 fw-bold">Save Changes</button>
                </div>
            </form>
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

{{-- ==================== COMMENT MODAL (Premium) ==================== --}}
<div class="modal fade" id="commentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg" style="max-height:90vh;">

            <div class="modal-header border-bottom py-2">
                <h5 class="modal-title fw-bold mx-auto" style="font-size:17px;" id="commentModalTitle">Comments</h5>
                <button type="button" class="btn-close ms-0" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-0">
                <div id="commentModalPostPreview" class="p-3 border-bottom"></div>

                <div class="px-3 pt-2 pb-1">
                    <small class="text-muted fw-semibold" id="commentModalCount" style="font-size:12px;"></small>
                </div>

                <div id="commentModalList" class="px-3 pb-3">
                    <div class="text-center text-muted py-4">
                        <div class="spinner-border spinner-border-sm text-primary"></div>
                        <div class="small mt-2">Loading comments...</div>
                    </div>
                </div>

                <div id="commentModalViewMore" class="px-3 pb-3 d-none">
                    <button type="button" class="btn btn-link btn-sm text-muted text-decoration-none p-0 fw-semibold"
                            style="font-size:13px;" id="commentModalViewMoreBtn" data-offset="0">
                        <i class="bi bi-arrow-down-circle me-1"></i> View more comments
                    </button>
                </div>
            </div>

            <div class="modal-footer border-top p-2">
                <form id="commentModalForm" class="d-flex align-items-center gap-2 w-100">
                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                         style="width:34px;height:34px;font-size:13px;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="input-group align-items-center bg-light rounded-pill px-3 py-1 w-100 border">
                        <input type="hidden" id="commentModalPostId">
                        <input type="text" id="commentModalInput"
                               class="form-control border-0 bg-transparent shadow-none py-1"
                               placeholder="Write a comment..." style="font-size:13px;" autocomplete="off">
                        <button type="submit" class="btn btn-link p-0 text-primary ms-2 shadow-none border-0 d-flex align-items-center">
                            <i class="bi bi-send-fill" style="font-size:17px;"></i>
                        </button>
                    </div>
                </form>
            </div>

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
let bootstrapCommentModal     = null;
let isUploading               = false;
let removedImages             = [];
let removedVideos             = [];
let editSelectedFiles         = [];
let lastSelectedBg            = '';
let lastEditSelectedBg        = '';
let commentEditState          = { editing: false, commentId: null };

// ==========================================
// INIT
// ==========================================
document.addEventListener("DOMContentLoaded", function () {
     if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }
    if (sessionStorage.getItem('scrollToTop')) {
        sessionStorage.removeItem('scrollToTop');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    bootstrapEditModal        = new bootstrap.Modal(document.getElementById('editPostModal'));
    bootstrapShareModal       = new bootstrap.Modal(document.getElementById('fbShareModal'));
    bootstrapLightboxModal    = new bootstrap.Modal(document.getElementById('imageLightboxModal'));
    const cmEl = document.getElementById('commentModal');
    if (cmEl) bootstrapCommentModal = new bootstrap.Modal(cmEl);

    document.getElementById('lightboxCarousel').addEventListener('slide.bs.carousel', function () {
        document.querySelectorAll('#lightboxInner video').forEach(v => v.pause());
    });
    document.getElementById('imageLightboxModal').addEventListener('hidden.bs.modal', function () {
        document.querySelectorAll('#lightboxInner video').forEach(v => v.pause());
    });
    document.getElementById('lightboxCarousel').addEventListener('slid.bs.carousel', function (ev) {
        const counter = document.getElementById('lightboxCounter');
        if (counter && counter.dataset.total) {
            counter.textContent = `${ev.to + 1} / ${counter.dataset.total}`;
        }
    });

    document.getElementById('postContent').addEventListener('input', function () {
        const bgInp = document.getElementById('bg_color_input');
        if (this.value.length > 80) {
            if (bgInp && bgInp.value) { resetPostBg(false); }
        } else {
            if (lastSelectedBg && (!bgInp || !bgInp.value)) { selectPostBg(lastSelectedBg); }
        }
    });

    document.getElementById('editPostContent').addEventListener('input', function () {
        const bgInp = document.getElementById('edit_bg_color_input');
        if (this.value.length > 80) {
            if (bgInp && bgInp.value) { resetEditPostBg(false); }
        } else {
            if (lastEditSelectedBg && (!bgInp || !bgInp.value)) { selectEditPostBg(lastEditSelectedBg); }
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
                const wrap = document.createElement('div');
                wrap.style.cssText = 'position:relative;z-index:20;display:flex;justify-content:center;';
                const video = document.createElement('video');
                video.src = item.url;
                video.controls = true;
                video.className = 'd-block w-100 object-fit-contain';
                video.style.cssText = 'max-height:82vh;position:relative;z-index:20;';
                ['click','mousedown','mouseup','pointerdown','pointerup','touchstart','touchend']
                    .forEach(evt => video.addEventListener(evt, e => e.stopPropagation()));
                wrap.appendChild(video);
                slide.appendChild(wrap);
            }
            inner.appendChild(slide);
        });

        const carouselEl = document.getElementById('lightboxCarousel');
        let ci = bootstrap.Carousel.getInstance(carouselEl);
        if (!ci) ci = new bootstrap.Carousel(carouselEl, { ride: false, touch: false, interval: false });
        if (index > 0) ci.to(index);

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
    lastSelectedBg = cls;
    selectedMediaFiles = [];
    renderMediaPreviews();
}
function resetPostBg(clearMemory = true) {
    const w = document.getElementById('postInputWrapper');
    const t = document.getElementById('postContent');
    const b = document.getElementById('bg_color_input');
    if (w) { w.className = 'p-1 rounded bg-transparent'; w.style.minHeight = 'auto'; }
    if (t) { t.style.cssText = 'font-size:14px;text-align:left;color:inherit;'; t.placeholder = 'Start a post...'; }
    if (b) b.value = '';
    if (clearMemory) lastSelectedBg = '';
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

    if (modal) modal.hide();
    document.getElementById('postContent').value = '';
    resetPostBg();
    selectedMediaFiles = [];
    renderMediaPreviews();
    submitBtn.disabled = false;

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
                window.location.reload();
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
        if(d.success){
            bootstrapShareModal?.hide();
            Toast.fire({icon:'success',title:'Shared!',timer:1200});
            sessionStorage.setItem('scrollToTop', '1');
            window.scrollTo({ top: 0, behavior: 'auto' });
            setTimeout(() => { window.location.reload(); }, 800);
        }
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
    lastEditSelectedBg = cls;
    document.querySelectorAll('#editMediaPreviewContainer [data-server-path]').forEach(el => {
        const p = el.getAttribute('data-server-path'), tp = el.getAttribute('data-type');
        if (tp==='image') removedImages.push(p); else removedVideos.push(p);
    });
    editSelectedFiles = [];
    const pc = document.getElementById('editMediaPreviewContainer');
    if (pc) pc.innerHTML = '';
}
function resetEditPostBg(clearMemory = true) {
    const w = document.getElementById('editPostInputWrapper');
    const t = document.getElementById('editPostContent');
    const b = document.getElementById('edit_bg_color_input');
    if (w) { w.className='p-1 rounded bg-transparent'; w.style.minHeight='auto'; }
    if (t) { t.style.cssText='font-size:14px;text-align:left;color:inherit;'; }
    if (b) b.value='';
    if (clearMemory) lastEditSelectedBg = '';
}

// ==========================================
// EDIT MODAL PREPARE
// ==========================================
function prepareEditModal(el) {
    const id=el.getAttribute('data-id'), content=el.getAttribute('data-content'),
          imgs=el.getAttribute('data-images'), vids=el.getAttribute('data-video'),
          bg=el.getAttribute('data-bg-color'),
          isShared=el.getAttribute('data-is-shared')==='1';
    removedImages=[]; removedVideos=[]; editSelectedFiles=[];
    lastEditSelectedBg = '';
    document.getElementById('editPostId').value      = id;
    document.getElementById('editPostContent').value = content||'';
    document.getElementById('editMediaInput').value  = '';
    const pc=document.getElementById('editMediaPreviewContainer');
    if(pc) pc.innerHTML='';

    const mediaSection = document.getElementById('editMediaSection');
    const colorZone    = document.getElementById('editColorPlatesZone');
    if (isShared) {
        if (mediaSection) mediaSection.classList.add('d-none');
        if (colorZone)    colorZone.classList.add('d-none');
        resetEditPostBg();
        bootstrapEditModal?.show();
        return;
    } else {
        if (mediaSection) mediaSection.classList.remove('d-none');
    }

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
        mediaEl.addEventListener('click',()=>openLightbox(JSON.stringify([{type:'image',url:src}]),0));
    } else {
        mediaEl=document.createElement('video');
        mediaEl.src=src;
        mediaEl.muted=true;
        mediaEl.preload='metadata';
        mediaEl.className='w-100 h-100 rounded border';
        mediaEl.style.cssText='object-fit:cover;cursor:pointer;';
        mediaEl.addEventListener('click', function(e) {
            e.stopPropagation();
            if (!this.hasAttribute('data-expanded')) {
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
        const ov=document.createElement('div');
        ov.className='edit-play-overlay position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center rounded-circle';
        ov.style.cssText='width:36px;height:36px;background:rgba(0,0,0,0.65);pointer-events:none;z-index:5;';
        ov.innerHTML='<i class="bi bi-play-fill text-white" style="font-size:1rem;margin-left:2px;"></i>';
        col.appendChild(ov);
    }

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
            let res = {};
            try { res = JSON.parse(xhr.responseText); } catch(e){}
            const oldCard = document.getElementById(`postCard-${id}`);
            if (oldCard && res.html) { oldCard.outerHTML = res.html; }
            bootstrapEditModal?.hide();
            const Toast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:1500 });
            Toast.fire({ icon:'success', title:'Post updated!' });
            if(btn) btn.disabled=false;
        } else {
            if(btn) btn.disabled=false;
            Swal.fire({icon:'error',title:'Update Failed!'});
        }
    };

    xhr.send(fd);
});

// ==========================================
// INFINITE SCROLL
// ==========================================
let feedLoading  = false;

function loadMorePosts() {
    const meta = document.getElementById('feedMeta');
    if (!meta) return;
    if (feedLoading || meta.dataset.hasMore === '0') return;

    feedLoading = true;
    const nextPage = meta.dataset.nextPage;
    const loader   = document.getElementById('feedLoader');
    if (loader) loader.classList.remove('d-none');

    fetch(`{{ route('feed.load') }}?page=${nextPage}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        const container = document.getElementById('postsFeedContainer');
        if (container && data.html.trim()) {
            container.insertAdjacentHTML('beforeend', data.html);
        }
        meta.dataset.nextPage = data.next_page;
        meta.dataset.hasMore  = data.has_more ? '1' : '0';
        if (loader) loader.classList.add('d-none');
        if (!data.has_more) {
            const endMsg = document.getElementById('feedEndMessage');
            if (endMsg) endMsg.classList.remove('d-none');
        }
        feedLoading = false;
    })
    .catch(err => {
        console.error('Feed load error:', err);
        if (loader) loader.classList.add('d-none');
        feedLoading = false;
    });
}

window.addEventListener('scroll', function () {
    const scrollPos = window.innerHeight + window.scrollY;
    const threshold = document.body.offsetHeight - 300;
    if (scrollPos >= threshold) { loadMorePosts(); }
});

// ==========================================
// COMMENT MODAL (Premium) — সব কমেন্ট কাজ এখানে
// ==========================================
function openCommentModal(postId) {
    const list    = document.getElementById('commentModalList');
    const preview = document.getElementById('commentModalPostPreview');
    const viewMore= document.getElementById('commentModalViewMore');
    const countEl = document.getElementById('commentModalCount');

    document.getElementById('commentModalPostId').value = postId;
    commentEditState = { editing: false, commentId: null };
    document.getElementById('commentModalInput').value = '';
    document.getElementById('commentModalInput').placeholder = 'Write a comment...';

    const card = document.getElementById(`postCard-${postId}`);
    if (card && preview) {
        const author  = card.querySelector('.author-name-zone')?.innerText || 'User';
        const avatar  = card.querySelector('.author-avatar-zone')?.innerHTML || 'U';
        const colored = card.getAttribute('data-bg-color');
        const caption = card.querySelector('.dynamic-caption')?.innerHTML || '';

        let capHtml = `<p class="mb-0" style="font-size:14px;">${caption}</p>`;
        if (colored && colored !== 'null' && colored !== '')
            capHtml = `<div class="p-3 rounded text-center text-white fw-bold ${colored}" style="min-height:80px;font-size:16px;"><p class="mb-0">${caption}</p></div>`;

        preview.innerHTML = `
            <div class="d-flex align-items-center gap-2 mb-2">
              <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:38px;height:38px;font-size:14px;">${avatar}</div>
              <h6 class="m-0 fw-bold" style="font-size:14px;">${author}</h6>
            </div>${capHtml}`;
    }

    if (list) list.innerHTML = `
        <div class="text-center text-muted py-4">
            <div class="spinner-border spinner-border-sm text-primary"></div>
            <div class="small mt-2">Loading comments...</div>
        </div>`;
    if (viewMore) viewMore.classList.add('d-none');
    if (countEl) countEl.innerText = '';

    bootstrapCommentModal?.show();

    fetch(`/posts/${postId}/comments/load?offset=0`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) { if (list) list.innerHTML = '<div class="text-center text-muted py-4 small">Could not load comments.</div>'; return; }

        if (list) {
            list.innerHTML = data.html.trim()
                ? data.html
                : '<div class="text-center text-muted py-4 small" id="modalNoComment">No comments yet. Be the first!</div>';
        }

        if (countEl) {
            const totalText = card?.querySelector(`#comment-count-${postId}`)?.innerText || '';
            countEl.innerText = totalText;
        }

        if (viewMore) {
            const btn = document.getElementById('commentModalViewMoreBtn');
            if (data.has_more) {
                btn.setAttribute('data-offset', data.next_offset);
                btn.setAttribute('data-post-id', postId);
                viewMore.classList.remove('d-none');
            } else {
                viewMore.classList.add('d-none');
            }
        }
    })
    .catch(() => { if (list) list.innerHTML = '<div class="text-center text-muted py-4 small">Network error.</div>'; });
}

document.getElementById('commentModalViewMoreBtn')?.addEventListener('click', function () {
    const postId = this.getAttribute('data-post-id');
    const offset = this.getAttribute('data-offset');
    const original = this.innerHTML;
    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-1" style="width:12px;height:12px;"></span> Loading...';

    fetch(`/posts/${postId}/comments/load?offset=${offset}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        const list = document.getElementById('commentModalList');
        if (list && data.html.trim()) list.insertAdjacentHTML('beforeend', data.html);

        if (data.has_more) {
            this.setAttribute('data-offset', data.next_offset);
            this.disabled = false;
            this.innerHTML = original;
        } else {
            document.getElementById('commentModalViewMore').classList.add('d-none');
        }
    })
    .catch(() => { this.disabled = false; this.innerHTML = original; });
});

document.getElementById('commentModalForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    const postId = document.getElementById('commentModalPostId').value;
    const input  = document.getElementById('commentModalInput');
    const text   = input.value.trim();
    if (!text) return;

    if (commentEditState.editing && commentEditState.commentId) {
        const cid = commentEditState.commentId;
        fetch(`/comments/${cid}`, {
            method:'PUT',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},
            body: JSON.stringify({ content: text })
        })
        .then(r => r.json())
        .then(d => {
            if (!d.success) return;
            const span = document.getElementById(`comment-text-${cid}`);
            if (span) span.innerText = text;
            const meta = document.querySelector(`.comment-meta-${cid}`);
            if (meta) meta.innerHTML = `${d.updated_at || 'just now'}<span class="comment-edited-tag-${cid}"> · Edited</span>`;
            commentEditState = { editing: false, commentId: null };
            input.value = '';
            input.placeholder = 'Write a comment...';
        });
        return;
    }

    input.value = '';
    fetch(`/posts/${postId}/comments`, {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},
        body: JSON.stringify({ content: text })
    })
    .then(r => r.json())
    .then(d => {
        if (!d.success) return;
        document.getElementById('modalNoComment')?.remove();

        const html = `
        <div class="d-flex gap-2 mb-3 align-items-start comment-row" id="comment-container-${d.comment_id}">
            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width:32px;height:32px;font-size:13px;">${d.user_initial}</div>
            <div class="flex-grow-1">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="bg-light px-3 py-2 rounded-4 d-inline-block" style="max-width:100%;">
                        <strong class="d-block text-dark" style="font-size:12.5px;">${d.user_name}</strong>
                        <span id="comment-text-${d.comment_id}" style="font-size:13px;word-break:break-word;">${d.content}</span>
                    </div>
                    <div class="dropdown flex-shrink-0">
                        <button type="button" class="btn btn-link btn-sm text-muted p-0 border-0 shadow-none ms-1" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm p-1" style="min-width:100px;">
                            <li><a class="dropdown-item py-1 fs-7" href="javascript:void(0)" onclick="editComment(event, ${d.comment_id})"><i class="bi bi-pencil me-1"></i> Edit</a></li>
                            <li><a class="dropdown-item py-1 fs-7 text-danger" href="javascript:void(0)" onclick="deleteComment(${d.comment_id}, ${postId})"><i class="bi bi-trash me-1"></i> Delete</a></li>
                        </ul>
                    </div>
                </div>
                <small class="text-muted ms-2 comment-meta-${d.comment_id}" style="font-size:11px;">${d.created_at}<span class="comment-edited-tag-${d.comment_id}"></span></small>
            </div>
        </div>`;
        document.getElementById('commentModalList')?.insertAdjacentHTML('afterbegin', html);

        const feedCount = document.getElementById(`comment-count-${postId}`);
        if (feedCount && d.comment_count !== undefined) feedCount.innerText = `${d.comment_count} Comments`;
        const modalCount = document.getElementById('commentModalCount');
        if (modalCount && d.comment_count !== undefined) modalCount.innerText = `${d.comment_count} Comments`;
    });
});

function editComment(event, cid) {
    const span = document.getElementById(`comment-text-${cid}`);
    if (!span) return;
    const input = document.getElementById('commentModalInput');
    if (!input) return;
    commentEditState = { editing: true, commentId: cid };
    input.value = span.innerText;
    input.placeholder = 'Editing comment...';
    input.focus();
}

function deleteComment(cid, postId) {
    Swal.fire({ title:'Delete comment?', icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444' }).then(r => {
        if (!r.isConfirmed) return;
        fetch(`/comments/${cid}`, {
            method:'DELETE',
            headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'}
        })
        .then(r => r.json())
        .then(d => {
            if (!d.success) return;
            document.getElementById(`comment-container-${cid}`)?.remove();
            const feedCount = document.getElementById(`comment-count-${postId}`);
            if (feedCount && d.comment_count !== undefined) feedCount.innerText = `${d.comment_count} Comments`;
            const modalCount = document.getElementById('commentModalCount');
            if (modalCount && d.comment_count !== undefined) modalCount.innerText = `${d.comment_count} Comments`;
            if (commentEditState.commentId == cid) {
                commentEditState = { editing: false, commentId: null };
                const input = document.getElementById('commentModalInput');
                if (input) { input.value=''; input.placeholder='Write a comment...'; }
            }
        });
    });
}

</script>

</body>
</html>