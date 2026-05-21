<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">

    <title>News Feed</title>
</head>
<body>
    <div class="py-5 bg-light min-vh-screen">
    <div class="container" style="max-width: 720px;">

        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <div class="d-flex gap-3">
                <div class="flex-shrink-0">
                    @if(auth()->user()->profile_picture)
                        <img src="{{ asset('storage/'.auth()->user()->profile_picture) }}" class="rounded-circle object-fit-cover border" style="width: 48px; height: 48px;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center font-weight-bold shadow-sm border" style="width: 48px; height: 48px; font-size: 18px;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <form id="ajaxPostForm" enctype="multipart/form-data">
                        @csrf
                        <textarea name="content" id="postContent" rows="3" class="form-control border-light-subtle rounded-3 p-3 shadow-none custom-textarea" placeholder="What's on your mind, {{ auth()->user()->name }}?..."></textarea>
                        
                        <div id="imagePreviewContainer" class="mt-3 position-relative d-none border rounded-3 overflow-hidden bg-dark-subtle text-center" style="max-height: 200px;">
                            <img id="imagePreview" src="#" class="img-fluid object-fit-contain w-100 h-100" style="max-height: 200px;">
                            <button type="button" id="removeImageBtn" class="btn btn-dark btn-sm position-absolute top-0 end-0 m-2 rounded-circle opacity-75">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>

                        <input type="file" name="image" id="postImageInput" accept="image/*" class="d-none">
                        
                        <div class="d-flex justify-content-between align-items-center pt-3 mt-3 border-top border-light">
                            <button type="button" id="triggerUploadBtn" class="btn btn-link text-secondary p-2 group-hover-primary" title="Add Media">
                                <i class="bi bi-image fs-4"></i>
                            </button>
                            <button type="submit" id="submitBtn" class="btn btn-primary px-4 py-2 rounded-3 fw-bold shadow-sm">
                                Publish Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="newsfeedContainer">
            @forelse($posts as $post)
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4" id="postCard-{{ $post->id }}">
                    
                    <div class="d-flex align-items-center justify-content-between mb-3 position-relative">
                        <div class="d-flex align-items-center gap-3">
                            <div class="flex-shrink-0 author-avatar-zone">
                                @if($post->user && $post->user->profile_picture)
                                    <img src="{{ asset('storage/'.$post->user->profile_picture) }}" class="rounded-circle object-fit-cover" style="width: 44px; height: 44px;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center font-weight-bold" style="width: 44px; height: 44px; font-size: 14px;">
                                        {{ $post->user ? strtoupper(substr($post->user->name, 0, 1)) : 'U' }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark author-name-zone">{{ $post->user ? $post->user->name : 'Unknown User' }}</h6>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <span class="badge bg-primary-subtle text-primary text-uppercase font-monospace tracking-wider author-role-zone" style="font-size: 10px;">{{ $post->user ? $post->user->role : 'USER' }}</span>
                                    <small class="text-muted">• {{ $post->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>

                        @if($post->user_id === auth()->id())
                            <div class="dropdown">
                                <button class="btn btn-link text-muted p-2 rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="box-shadow: none;">
                                    <i class="bi bi-three-dots-vertical fs-5"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-3 py-2">
                                    <li>
                                        <button type="button" onclick="openEditModal({{ $post->id }}, `{{ e($post->content) }}`, '{{ $post->image ? asset('storage/' . $post->image) : '' }}')" class="dropdown-item d-flex align-items-center gap-2 text-secondary py-2 fs-7 fw-medium">
                                            <i class="bi bi-pencil-square"></i> Edit Post
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button" onclick="deletePost({{ $post->id }})" class="dropdown-item d-flex align-items-center gap-2 text-danger py-2 fs-7 fw-medium">
                                            <i class="bi bi-trash3"></i> Delete Post
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>

                    @if($post->content)
                        <div class="text-dark fs-6 mb-3 px-1 dynamic-caption" style="white-space: pre-line; line-height: 1.6;">
                            {!! nl2br(e($post->content)) !!}
                        </div>
                    @endif

                    @if($post->image)
                        <div class="rounded-3 border overflow-hidden bg-light mb-3 text-center d-flex align-items-center justify-content-center dynamic-image-wrapper" style="max-height: 250px;">
                            <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid object-fit-contain mx-auto" style="max-height: 250px; width: auto;">
                        </div>
                    @endif

                    @if($post->parent_id && $post->parentPost)
                        <div class="card border border-light-subtle rounded-3 p-3 bg-light-subtle text-start mt-3">
                            <div class="d-flex align-items-center gap-2.5 mb-2">
                                <div class="flex-shrink-0">
                                    @if($post->parentPost->user && $post->parentPost->user->profile_picture)
                                        <img src="{{ asset('storage/'.$post->parentPost->user->profile_picture) }}" class="rounded-circle object-fit-cover" style="width: 32px; height: 32px;">
                                    @else
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center font-weight-bold" style="width: 32px; height: 32px; font-size: 11px;">
                                            {{ $post->parentPost->user ? strtoupper(substr($post->parentPost->user->name, 0, 1)) : 'U' }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark style-fs-sm" style="font-size: 13px;">{{ $post->parentPost->user ? $post->parentPost->user->name : 'Original Author' }}</h6>
                                    <small class="text-uppercase text-muted fw-semibold font-monospace" style="font-size: 9px;">{{ $post->parentPost->user ? $post->parentPost->user->role : 'POST' }}</small>
                                </div>
                            </div>
                            <div class="text-secondary fs-7 mb-2" style="white-space: pre-line; font-size: 13px;">
                                {{ $post->parentPost->content }}
                            </div>
                            @if($post->parentPost->image)
                                <div class="rounded-3 border overflow-hidden bg-white text-center d-flex align-items-center justify-content-center mt-2" style="max-height: 180px;">
                                    <img src="{{ asset('storage/' . $post->parentPost->image) }}" class="img-fluid object-fit-contain mx-auto" style="max-height: 180px; width: auto;">
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="border-top border-light pt-2 mt-3 d-flex text-muted fs-7 font-weight-bold">
                        <button class="btn btn-link text-muted d-flex align-items-center justify-content-center gap-2 py-2 w-100 text-decoration-none custom-btn-hover">
                            <i class="bi bi-hand-thumbs-up"></i> Like
                        </button>
                        <button type="button" onclick="openShareModal({{ $post->id }})" class="btn btn-link text-muted d-flex align-items-center justify-content-center gap-2 py-2 w-100 text-decoration-none custom-btn-hover">
                            <i class="bi bi-reply-all fs-5" style="transform: scaleX(-1);"></i> Share
                        </button>
                    </div>

                </div>
            @empty
                <div id="emptyStateCard" class="card border border-dashed rounded-4 p-5 text-center text-muted mx-auto" style="max-width: 500px;">
                    <div class="py-4">
                        <i class="bi bi-rss fs-1 text-secondary opacity-50 mb-2 d-block"></i>
                        <p class="mb-0 fw-semibold">No updates in the feed yet</p>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</div>

<div class="modal fade" id="fbShareModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom border-light-subtle bg-light px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-6">Share Post</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close" onclick="closeShareModal()"></button>
            </div>
            <form id="fbShareForm" class="m-0">
                @csrf
                <input type="hidden" id="targetSharePostId">
                
                <div class="modal-body p-4" style="max-height: 60vh; overflow-y: auto;">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        @if(auth()->user()->profile_picture)
                            <img src="{{ asset('storage/'.auth()->user()->profile_picture) }}" class="rounded-circle object-fit-cover" style="width: 40px; height: 40px;">
                        @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center font-weight-bold" style="width: 40px; height: 40px; font-size: 14px;">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        @endif
                        <div>
                            <h6 class="mb-0 fw-bold text-dark fs-7">{{ auth()->user()->name }}</h6>
                            <span class="badge bg-light text-secondary border mt-1" style="font-size: 9px;">SHARING TO FEED</span>
                        </div>
                    </div>
                    
                    <textarea id="shareComment" rows="3" class="form-control border-light-subtle rounded-3 p-3 shadow-none custom-textarea mb-3" placeholder="Say something about this shared post..."></textarea>
                    
                    <div id="modalPostPreview" class="border rounded-3 p-3 bg-light user-select-none" style="max-height: 200px; overflow-y: auto;"></div>
                </div>
                
                <div class="modal-footer border-top border-light-subtle bg-light px-4 py-3">
                    <button type="button" class="btn btn-outline-secondary px-4 rounded-3 fw-bold fs-7" data-bs-dismiss="modal" onclick="closeShareModal()">Cancel</button>
                    <button type="submit" id="shareSubmitBtn" class="btn btn-primary px-4 rounded-3 fw-bold fs-7">Share Now</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editPostModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-bottom border-light-subtle bg-light px-4 py-3">
                <h5 class="modal-title fw-bold text-dark fs-6">Edit Post</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close" onclick="closeEditModal()"></button>
            </div>
            <form id="editPostForm" enctype="multipart/form-data" class="m-0">
                @csrf
                <input type="hidden" id="editPostId">
                <input type="hidden" id="deleteOriginalImage" name="delete_image" value="0">
                
                <div class="modal-body p-4" style="max-height: 60vh; overflow-y: auto;">
                    <textarea id="editPostContent" name="content" rows="3" class="form-control border-light-subtle rounded-3 p-3 shadow-none custom-textarea mb-3" placeholder="Update your thought..."></textarea>
                    
                    <div id="editImagePreviewContainer" class="position-relative d-none border rounded-3 overflow-hidden bg-light text-center mb-3 flex-shrink-0" style="height: 150px;">
                        <img id="editImagePreview" src="#" class="img-fluid object-fit-contain w-100 h-100" style="max-height: 150px;">
                        <button type="button" id="removeEditImageBtn" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle shadow-sm">
                            <i class="bi bi-trash fs-7"></i>
                        </button>
                    </div>

                    <input type="file" name="image" id="editPostImageInput" accept="image/*" class="d-none">
                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 border">
                        <span class="text-secondary fw-bold" style="font-size: 12px;">Update Media / Photo</span>
                        <button type="button" id="triggerEditUploadBtn" class="btn btn-outline-primary btn-sm rounded-2 px-2" title="Change Photo">
                            <i class="bi bi-camera fs-5"></i>
                        </button>
                    </div>
                </div>
               
                <div class="modal-footer border-top border-light-subtle bg-light px-4 py-3">
                    <button type="button" class="btn btn-outline-secondary px-4 rounded-3 fw-bold fs-7" data-bs-dismiss="modal" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" id="editSubmitBtn" class="btn btn-primary px-4 rounded-3 fw-bold fs-7">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .custom-textarea { resize: none; border-color: #e2e8f0; }
    .custom-textarea:focus { border-color: #4f46e5; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1) !important; }
    .custom-btn-hover:hover { background-color: rgba(79, 70, 229, 0.05); color: #4f46e5 !important; border-radius: 12px; transition: all 0.2s ease-in-out; }
    .fs-7 { font-size: 13px !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // বুটস্ট্রাপ ৫ মোডাল অবজেক্ট ইনিশিয়ালাইজেশন
    let bootstrapEditModal = null;
    let bootstrapShareModal = null;

    document.addEventListener("DOMContentLoaded", function() {
        bootstrapEditModal = new bootstrap.Modal(document.getElementById('editPostModal'));
        bootstrapShareModal = new bootstrap.Modal(document.getElementById('fbShareModal'));
    });

    // মিডিয়া ক্রিয়েট প্রিভিউ কন্ট্রোল
    const imageInput = document.getElementById('postImageInput');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const previewImage = document.getElementById('imagePreview');

    document.getElementById('triggerUploadBtn').addEventListener('click', function() {
        imageInput.click();
    });
    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function() {
                previewImage.setAttribute('src', this.result);
                previewContainer.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        }
    });
    document.getElementById('removeImageBtn').addEventListener('click', function() {
        imageInput.value = '';
        previewImage.setAttribute('src', '#');
        previewContainer.classList.add('d-none');
    });

    // নতুন পোস্ট সাবমিট
    document.getElementById('ajaxPostForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const content = document.getElementById('postContent').value.trim();
        const hasFile = imageInput.files && imageInput.files.length > 0;
        
        if (content === "" && !hasFile) {
            Swal.fire({ icon: 'warning', title: 'Empty Post!', text: 'Please write something before publishing.', confirmButtonColor: '#0d6efd' });
            return;
        }

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerText = 'Publishing...';

        const formData = new FormData(this);
        fetch("{{ route('posts.store') }}", {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 
                'Accept': 'application/json' 
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => { if(data.success) window.location.reload(); })
        .catch(err => {
            console.error(err);
            submitBtn.disabled = false;
            submitBtn.innerText = 'Publish Post';
        });
    });

    // এডিট মোডাল ইমেজ আপলোডার
    const editImageInput = document.getElementById('editPostImageInput');
    const editPreviewContainer = document.getElementById('editImagePreviewContainer');
    const editPreviewImage = document.getElementById('editImagePreview');
    const deleteOriginalImageInput = document.getElementById('deleteOriginalImage');

    document.getElementById('triggerEditUploadBtn').addEventListener('click', function() {
        editImageInput.click();
    });
    editImageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function() {
                editPreviewImage.setAttribute('src', this.result);
                editPreviewContainer.classList.remove('d-none');
                deleteOriginalImageInput.value = "0";
            }
            reader.readAsDataURL(file);
        }
    });
    document.getElementById('removeEditImageBtn').addEventListener('click', function() {
        editImageInput.value = '';
        editPreviewImage.setAttribute('src', '#');
        editPreviewContainer.classList.add('d-none');
        deleteOriginalImageInput.value = "1";
    });

    // এডিট মোডাল ওপেন (বুটস্ট্রাপ ইঞ্জিন)
    function openEditModal(postId, currentContent, currentImageUrl) {
        document.getElementById('editPostId').value = postId;
        document.getElementById('editPostContent').value = currentContent;
        deleteOriginalImageInput.value = "0";
        editImageInput.value = '';
        
        if(currentImageUrl && currentImageUrl !== '') {
            editPreviewImage.setAttribute('src', currentImageUrl);
            editPreviewContainer.classList.remove('d-none');
        } else {
            editPreviewImage.setAttribute('src', '#');
            editPreviewContainer.classList.add('d-none');
        }
        
        bootstrapEditModal.show();
    }

    function closeEditModal() {
        bootstrapEditModal.hide();
    }

    // এডিট ফর্ম সাবমিশন
    document.getElementById('editPostForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const postId = document.getElementById('editPostId').value;
        const updatedContent = document.getElementById('editPostContent').value.trim();
        const submitBtn = document.getElementById('editSubmitBtn');
        const hasNewFile = editImageInput.files && editImageInput.files.length > 0;
        const isImageVisible = !editPreviewContainer.classList.contains('d-none');

        if(updatedContent === "" && !hasNewFile && !isImageVisible) {
            Swal.fire({ icon: 'warning', title: 'Empty Post', text: 'You cannot save an entirely blank post.', confirmButtonColor: '#0d6efd' });
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerText = 'Saving...';

        const editData = new FormData(this);
        editData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        editData.append('_method', 'PUT');

        fetch(`/posts/${postId}`, {
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            body: editData
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                closeEditModal();
                Swal.fire({ icon: 'success', title: 'Updated!', text: 'Post updated successfully.', timer: 1200, showConfirmButton: false });
                setTimeout(() => window.location.reload(), 1000);
            }
        })
        .catch(err => {
            console.error(err);
            submitBtn.disabled = false;
            submitBtn.innerText = 'Save Changes';
        });
    });

    // ডিলিট পোস্ট ফাংশন
    function deletePost(postId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this action!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/posts/${postId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire({ icon: 'success', title: 'Deleted!', text: 'Your post has been removed.', timer: 1200, showConfirmButton: false });
                        document.getElementById(`postCard-${postId}`).remove();
                    }
                })
                .catch(err => console.error(err));
            }
        });
    }

    // শেয়ার মোডাল ওপেন (বুটস্ট্রাপ ইঞ্জিন)
    function openShareModal(postId) {
        document.getElementById('targetSharePostId').value = postId;
        const postCard = document.getElementById(`postCard-${postId}`);
        const authorName = postCard.querySelector('.author-name-zone').innerText;
        const authorRole = postCard.querySelector('.author-role-zone').innerText;
        const avatarInner = postCard.querySelector('.author-avatar-zone').innerHTML;
        
        const captionEl = postCard.querySelector('.dynamic-caption');
        const captionHtml = captionEl ? `<p class="small text-secondary mt-1 mb-0">${captionEl.innerHTML}</p>` : '';
        
        const imageWrapper = postCard.querySelector('.dynamic-image-wrapper img');
        const imageHtml = imageWrapper ? `<div class="mt-2 rounded border bg-white text-center d-flex align-items-center justify-content-center" style="max-height: 120px;"><img src="${imageWrapper.src}" class="img-fluid object-fit-contain" style="max-height: 120px;"></div>` : '';
        
        document.getElementById('modalPostPreview').innerHTML = `
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="rounded-circle overflow-hidden border d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">${avatarInner}</div>
                <div>
                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 12px;">${authorName}</h6>
                    <small class="text-uppercase text-muted fw-semibold" style="font-size: 8px;">${authorRole}</small>
                </div>
            </div>
            ${captionHtml}
            ${imageHtml}
        `;

        bootstrapShareModal.show();
    }

    function closeShareModal() {
        bootstrapShareModal.hide();
        document.getElementById('shareComment').value = '';
    }

    // শেয়ার ফর্ম সাবমিশন
    document.getElementById('fbShareForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const postId = document.getElementById('targetSharePostId').value;
        const comment = document.getElementById('shareComment').value.trim();
        const shareSubmitBtn = document.getElementById('shareSubmitBtn');

        shareSubmitBtn.disabled = true;
        shareSubmitBtn.innerText = 'Sharing...';

        const shareData = new FormData();
        shareData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        shareData.append('content', comment);

        fetch(`/posts/${postId}/share`, {
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            body: shareData
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Server error');
            return data;
        })
        .then(data => {
            if(data.success) {
                closeShareModal();
                Swal.fire({ icon: 'success', title: 'Shared!', text: 'Post shared successfully.', timer: 1200, showConfirmButton: false });
                setTimeout(() => window.location.reload(), 1000);
            }
        })
        .catch(err => {
            console.error(err);
            Swal.fire({ icon: 'error', title: 'Oops...', text: err.message });
            shareSubmitBtn.disabled = false;
            shareSubmitBtn.innerText = 'Share Now';
        });
    });
</script>
</body>
</html>