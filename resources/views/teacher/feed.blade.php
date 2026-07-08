
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="yLeZsHdJiPbawa5jvfsPVpaGFzX1iVZyb5Jhpr6w">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">
    <title>Borobhai.online</title>
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
        .create-post-avatar { width: 40px; height: 40px; border-radius: 50%; background-color: #65676b; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; overflow:hidden; flex-shrink:0; }
        .cpa-img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
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

        /* ==========================================
   BOROBHAI PREMIUM FEED STYLES (reusable)
   ========================================== */

:root {
    --bb-primary: #4f46e5;
    --bb-primary-dark: #4338ca;
    --bb-primary-soft: #eef2ff;
    --bb-ink: #1e1f24;
    --bb-muted: #6b7280;
    --bb-line: #eceef1;
    --bb-bg: #f3f4f8;
    --bb-card: #ffffff;
    --bb-radius: 16px;
    --bb-shadow: 0 1px 3px rgba(16,24,40,.06), 0 1px 2px rgba(16,24,40,.04);
    --bb-shadow-hover: 0 8px 28px rgba(79,70,229,.10), 0 2px 6px rgba(16,24,40,.06);
}

/* ===== POST CARD ===== */
.bb-post-card {
    background: var(--bb-card);
    border-radius: var(--bb-radius);
    box-shadow: var(--bb-shadow);
    margin-bottom: 18px;
    overflow: hidden;
    transition: box-shadow .25s ease;
}
.bb-post-card:hover { box-shadow: var(--bb-shadow-hover); }

/* ===== HEADER ===== */
.bb-post-head { display:flex; align-items:center; justify-content:space-between; padding:14px 16px 8px; }
.bb-head-left { display:flex; align-items:center; gap:10px; }
.bb-avatar {
    width:42px; height:42px; border-radius:50%;
    background: linear-gradient(135deg, var(--bb-primary), #7c73f0);
    color:#fff; display:flex; align-items:center; justify-content:center;
    font-weight:700; font-size:16px; flex-shrink:0;
    box-shadow: 0 2px 6px rgba(79,70,229,.25);
}
.bb-avatar-sm { width:30px; height:30px; font-size:12px; box-shadow:none; }
.bb-avatar-img { width:100%; height:100%; border-radius:50%; object-fit:cover; flex-shrink:0; }
.bb-avatar { overflow:hidden; padding:0; }
.bb-avatar:has(img) { background:none; }
.bb-head-meta { line-height:1.25; }
.bb-author { margin:0; font-weight:700; font-size:14.5px; color:var(--bb-ink); letter-spacing:-.2px; }
.bb-author-link { text-decoration:none; display:inline-block; }
.bb-author-link:hover { color:var(--bb-primary); text-decoration:underline; }
.bb-time { font-size:11.5px; color:var(--bb-muted); display:flex; align-items:center; gap:4px; }
.bb-time i { font-size:10px; }
.bb-more-btn {
    border:none; background:transparent; color:var(--bb-muted);
    width:34px; height:34px; border-radius:50%; cursor:pointer;
    display:flex; align-items:center; justify-content:center; transition:background .15s ease;
}
.bb-more-btn:hover { background:var(--bb-bg); color:var(--bb-ink); }

/* নামের নিচে role label (author) */
.bb-author-role {
    display: inline-flex; align-items: center; gap: 3px;
    font-size: 10.5px; font-weight: 700; letter-spacing: .2px;
    padding: 1px 8px; border-radius: 12px; width: fit-content; margin: 1px 0;
}
.bb-author-role i { font-size: 9px; }
.bb-author-role-alumni  { background: #fef3c7; color: #d97706; }
.bb-author-role-student { background: #eef2ff; color: #4f46e5; }
.bb-author-role-teacher { background: #f3e8ff; color: #7c3aed; }

/* ===== CAPTION ===== */
.bb-caption { padding:2px 16px 12px; font-size:14.5px; line-height:1.55; color:var(--bb-ink); word-break:break-word; }
.bb-color-caption {
    margin:4px 16px 12px; border-radius:12px; min-height:200px;
    display:flex; align-items:center; justify-content:center; text-align:center;
    color:#fff; font-weight:700; font-size:22px; padding:24px; word-break:break-word;
}
.bb-color-caption-sm { min-height:120px; font-size:16px; margin:8px 14px; }

/* ===== MEDIA ===== */
.bb-media-zone { background:#000; overflow:hidden; line-height:0; }
.bb-media-single { position:relative; display:flex; align-items:center; justify-content:center; background:#000; width:100%; }
.bb-single-img { width:100%; max-height:560px; object-fit:contain; display:block; cursor:pointer; }
.bb-video-wrap { position:relative; width:100%; display:flex; justify-content:center; background:#000; }
.bb-inline-video { width:100%; max-height:560px; object-fit:contain; display:block; background:#000; }
.bb-expand-btn {
    position:absolute; top:10px; right:10px; z-index:5;
    width:34px; height:34px; border-radius:8px; border:none;
    background:rgba(0,0,0,.55); color:#fff; cursor:pointer;
    display:flex; align-items:center; justify-content:center;
    opacity:0; transition:opacity .2s ease; font-size:14px;
}
.bb-video-wrap:hover .bb-expand-btn { opacity:1; }
.bb-grid { display:flex; gap:3px; width:100%; }
.bb-grid-2 { height:300px; }
.bb-grid-3 { height:340px; }
.bb-grid-3-side { display:flex; flex-direction:column; gap:3px; flex:1; min-width:0; }
.bb-grid-4 { flex-wrap:wrap; height:480px; }
.bb-grid-4 .bb-tile { width:calc(50% - 1.5px); height:calc(50% - 1.5px); flex:none; }
.bb-tile { position:relative; flex:1; min-width:0; overflow:hidden; background:#000; }
.bb-tile-big { flex:2; }
.bb-tile-media { width:100%; height:100%; object-fit:cover; cursor:pointer; display:block; }
.bb-play-badge {
    position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);
    width:54px; height:54px; border-radius:50%; background:rgba(0,0,0,.55);
    display:flex; align-items:center; justify-content:center; cursor:pointer;
    color:#fff; font-size:1.8rem; transition:transform .15s ease, background .15s ease;
}
.bb-play-badge:hover { transform:translate(-50%,-50%) scale(1.08); background:rgba(0,0,0,.7); }
.bb-play-sm { width:40px; height:40px; font-size:1.2rem; }
.bb-more-overlay {
    position:absolute; inset:0; background:rgba(0,0,0,.55); color:#fff; font-weight:700;
    display:flex; align-items:center; justify-content:center; font-size:2rem; cursor:pointer; line-height:1;
}

/* ===== SHARED POST ===== */
.bb-shared { margin:0 16px 12px; border:1px solid var(--bb-line); border-radius:12px; overflow:hidden; }
.bb-shared-head { display:flex; align-items:center; gap:8px; padding:12px 14px 6px; }

/* ===== STATS ===== */
.bb-stats { display:flex; align-items:center; justify-content:space-between; padding:10px 18px 8px; font-size:13px; color:var(--bb-muted); }
.bb-like-stat { display:flex; align-items:center; gap:6px; }
.bb-like-bubble {
    width:20px; height:20px; border-radius:50%; background:var(--bb-primary);
    color:#fff; display:inline-flex; align-items:center; justify-content:center; font-size:10px;
}
.bb-stat-link { cursor:pointer; transition:color .15s ease; }
.bb-stat-link:hover { color:var(--bb-primary); text-decoration:underline; }

/* ===== ACTION BUTTONS ===== */
.bb-actions { display:flex; padding:4px 8px; border-top:1px solid var(--bb-line); }
.bb-action-btn {
    flex:1; border:none; background:transparent; cursor:pointer;
    display:flex; align-items:center; justify-content:center; gap:7px;
    padding:9px 4px; border-radius:8px; margin:4px 2px;
    color:var(--bb-muted); font-weight:600; font-size:13.5px; transition:background .15s ease, color .15s ease;
}
.bb-action-btn i { font-size:17px; }
.bb-action-btn:hover { background:var(--bb-bg); }
.bb-action-btn.active-like { color:var(--bb-primary); }
.bb-action-btn.active-save { color:#f59e0b; }

@media (max-width:576px) {
    .bb-action-btn span { display:none; }
    .bb-action-btn i { font-size:19px; }
    .bb-grid-2, .bb-grid-3 { height:220px; }
    .bb-grid-4 { height:360px; }
}

/* ===== ROLE BADGE (navbar) ===== */
.bb-role-badge {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 12px; font-weight: 700; letter-spacing: .3px;
    padding: 6px 14px; border-radius: 20px;
    border: 1.5px solid transparent; transition: transform .15s ease;
}
.bb-role-badge i { font-size: 13px; }
.bb-role-student { background: #eef2ff; color: #4f46e5; border-color: #c7d2fe; }
.bb-role-alumni  { background: #fef3c7; color: #d97706; border-color: #fde68a; }
.bb-role-teacher { background: #f3e8ff; color: #7c3aed; border-color: #ddd6fe; }

/* Post A Job button (navbar) */
.bb-post-job-btn {
    display:inline-flex; align-items:center; gap:7px;
    background:linear-gradient(135deg, #4f46e5, #7c73f0); color:#fff;
    border:none; border-radius:20px; padding:8px 16px;
    font-size:13px; font-weight:700; letter-spacing:.2px;
    box-shadow:0 2px 8px rgba(79,70,229,.3); transition:all .15s ease;
}
.bb-post-job-btn:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(79,70,229,.4); }
.bb-post-job-btn i { font-size:14px; }
@media (max-width:767px){ .bb-post-job-btn { padding:8px 11px; border-radius:50%; } }

/* Post Job modal form */
.bb-job-label { display:block; font-size:12.5px; font-weight:600; color:#374151; margin-bottom:5px; }
.bb-job-input {
    width:100%; border:1.5px solid #e4e6eb; border-radius:10px;
    padding:9px 12px; font-size:13.5px; outline:none; transition:border-color .15s, box-shadow .15s;
    background:#fff;
}
.bb-job-input:focus { border-color:#4f46e5; box-shadow:0 0 0 3px rgba(79,70,229,.12); }
textarea.bb-job-input { resize:vertical; }
.bb-job-submit-btn {
    background:linear-gradient(135deg,#4f46e5,#7c73f0); color:#fff; border:none;
    border-radius:10px; padding:9px 22px; font-size:14px; font-weight:700;
    display:inline-flex; align-items:center; transition:all .15s;
}
.bb-job-submit-btn:hover { box-shadow:0 4px 14px rgba(79,70,229,.4); }
.bb-job-submit-btn:disabled { opacity:.6; }

.postjob-dialog { height:calc(100vh - 3.5rem); }
.postjob-content { max-height:100%; display:flex; flex-direction:column; overflow:hidden; }
.postjob-form { display:flex; flex-direction:column; min-height:0; flex:1 1 auto; overflow:hidden; }
.postjob-body { overflow-y:auto; flex:1 1 auto; min-height:0; }
.postjob-footer { flex:0 0 auto; }
@media (max-width:576px){ .postjob-dialog { height:calc(100vh - 1rem); } }

/* ===== RIGHT SIDEBAR (reusable) ===== */
.bb-side-card {
    background: var(--bb-card); border-radius: var(--bb-radius);
    box-shadow: var(--bb-shadow); overflow: hidden;
}
.bb-side-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 16px 8px;
}
.bb-side-title {
    font-size: 14px; font-weight: 700; color: var(--bb-ink);
    display: flex; align-items: center; gap: 7px; letter-spacing: -.2px;
}
.bb-side-link { font-size: 12px; color: var(--bb-primary); text-decoration: none; font-weight: 600; }
.bb-side-link:hover { text-decoration: underline; }
.bb-side-body { padding: 4px 10px 12px; }

/* Job item */
.bb-job-item {
    display: flex; gap: 10px; padding: 8px 6px; border-radius: 10px;
    cursor: pointer; transition: background .15s ease;
}
.bb-job-item:hover { background: var(--bb-bg); }
.bb-job-logo {
    width: 40px; height: 40px; border-radius: 9px; flex-shrink: 0;
    background: var(--bb-primary-soft); color: var(--bb-primary);
    display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 17px;
}
.bb-job-info { min-width: 0; }
.bb-job-title { font-size: 13.5px; font-weight: 700; color: var(--bb-ink); margin: 0 0 1px; }
.bb-job-company { font-size: 12px; color: var(--bb-muted); margin: 0 0 3px; }
.bb-job-tag {
    font-size: 10.5px; color: #16a34a; font-weight: 600;
    display: inline-flex; align-items: center; gap: 3px;
}
.bb-job-tag i { font-size: 9px; }

/* Active now */
.bb-active-item {
    display: flex; align-items: center; gap: 10px; padding: 6px;
    border-radius: 10px; cursor: pointer; transition: background .15s ease;
}
.bb-active-item:hover { background: var(--bb-bg); }
.bb-active-avatar {
    position: relative; width: 38px; height: 38px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, var(--bb-primary), #7c73f0);
    color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 15px;
}
.bb-active-avatar::after {
    content: ''; position: absolute; bottom: 0; right: 0;
    width: 11px; height: 11px; border-radius: 50%;
    background: #22c55e; border: 2px solid #fff;
}
.bb-active-name { font-size: 13.5px; font-weight: 600; color: var(--bb-ink); }

/* Suggested people */
.bb-suggest-item {
    display: flex; align-items: center; gap: 10px; padding: 8px 6px;
    border-radius: 10px; transition: background .15s ease;
}
.bb-suggest-item:hover { background: var(--bb-bg); }
.bb-suggest-avatar {
    width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
    background: linear-gradient(135deg, var(--bb-primary), #7c73f0);
    color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px;
}
.bb-suggest-info { flex-grow: 1; min-width: 0; }
.bb-suggest-name { font-size: 13.5px; font-weight: 700; color: var(--bb-ink); margin: 0 0 1px; }
.bb-suggest-role { font-size: 11.5px; color: var(--bb-muted); margin: 0; }
.bb-connect-btn {
    width: 34px; height: 34px; border-radius: 9px; flex-shrink: 0;
    border: 1.5px solid var(--bb-primary); background: #fff; color: var(--bb-primary);
    display: flex; align-items: center; justify-content: center; cursor: pointer;
    font-size: 15px; transition: all .15s ease;
}
.bb-connect-btn:hover { background: var(--bb-primary); color: #fff; }

/* Right sidebar own scroll */
.bb-right-sidebar {
    position: sticky; top: 70px; max-height: calc(100vh - 85px);
    overflow-y: auto; padding-bottom: 10px;
    scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent;
}
.bb-right-sidebar::-webkit-scrollbar { width: 6px; }
.bb-right-sidebar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
.bb-right-sidebar::-webkit-scrollbar-track { background: transparent; }

.bb-active-meta { display: flex; flex-direction: column; gap: 2px; min-width: 0; }

/* Mini role badge (Alumni / Student / Teacher) */
.bb-mini-badge {
    display: inline-flex; align-items: center; gap: 3px;
    font-size: 9.5px; font-weight: 700; letter-spacing: .2px;
    padding: 1px 7px; border-radius: 12px; width: fit-content; text-transform: uppercase;
}
.bb-mini-badge i { font-size: 9px; }
.bb-mini-alumni  { background: #fef3c7; color: #d97706; }
.bb-mini-student { background: #eef2ff; color: #4f46e5; }
.bb-mini-teacher { background: #f3e8ff; color: #7c3aed; }

/* ===== EMOJI PICKER ===== */
.bb-emoji-btn {
    border:none; background:transparent; cursor:pointer; color:#65676b;
    font-size:20px; padding:4px 8px; border-radius:8px; transition:background .15s, color .15s;
    display:inline-flex; align-items:center;
}
.bb-emoji-btn:hover { background:#f0f2f5; color:#f59e0b; }
#bbEmojiPopover {
    position:absolute; z-index:3000; display:none;
    box-shadow:0 8px 30px rgba(0,0,0,.18); border-radius:12px; overflow:hidden;
}
#bbEmojiPopover emoji-picker {
    --background:#fff;
    --border-color:#e4e6eb;
    --indicator-color:#4f46e5;
    --num-columns:8;
    --emoji-size:1.3rem;
    height:340px;
}

/* ===== COMMENT LIKE + REPLY ===== */
.comment-like-btn { color:#65676b; }
.comment-like-btn:hover { text-decoration:underline; }
.comment-like-btn.liked { color:#4f46e5; }
.comment-reply-btn:hover { text-decoration:underline; }
.comment-like-count { font-size:11px; }
.reply-row { padding-left:6px; }
.replies-zone { border-left:2px solid #eceef1; padding-left:10px; margin-left:6px; }
.reply-input-wrap { display:flex; align-items:center; gap:8px; }
.reply-input-box {
    flex-grow:1; border:1px solid #e4e6eb; border-radius:20px; background:#f0f2f5;
    padding:6px 14px; font-size:12.5px; outline:none;
}
.reply-input-box:focus { border-color:#4f46e5; background:#fff; }
.reply-send-btn { border:none; background:transparent; color:#4f46e5; cursor:pointer; font-size:16px; padding:2px 6px; }
.reply-send-btn:disabled { opacity:.4; cursor:default; }
.comment-mention { color:#4f46e5; font-weight:600; }
.reply-field { display:flex; align-items:center; gap:6px; flex-grow:1; background:#f0f2f5; border:1px solid #e4e6eb; border-radius:20px; padding:2px 6px 2px 12px; }
.reply-field:focus-within { border-color:#4f46e5; background:#fff; }
.reply-field .reply-input-box { border:none; background:transparent; padding:5px 4px; }
.reply-field .reply-input-box:focus { border:none; background:transparent; }
.reply-mention-tag {
    display:inline-flex; align-items:center; background:#e0e7ff; color:#4f46e5;
    font-weight:600; font-size:11.5px; padding:2px 8px; border-radius:12px; white-space:nowrap;
}

/* ===== JOB CARD (in feed) ===== */
.bb-jobcard {
    background:#fff; border-radius:16px; box-shadow:0 1px 3px rgba(16,24,40,.06);
    padding:16px 18px; margin-bottom:18px; border:1px solid var(--bb-line);
    transition:box-shadow .2s;
}
.bb-jobcard:hover { box-shadow:0 8px 24px rgba(79,70,229,.10); }
.bb-jobcard-top { display:flex; align-items:flex-start; gap:13px; }
.bb-jobcard-logo {
    width:52px; height:52px; border-radius:13px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center; font-size:23px; font-weight:800;
}
.bb-jobcard-headinfo { flex-grow:1; min-width:0; }
.bb-jobcard-title {
    font-size:16.5px; font-weight:800; color:var(--bb-ink); text-decoration:none;
    letter-spacing:-.3px; display:inline-block; line-height:1.25;
}
.bb-jobcard-title:hover { color:var(--bb-primary); }
.bb-jobcard-company { font-size:13px; color:var(--bb-muted); margin:2px 0 0; font-weight:500; }
.bb-job-expiring { display:inline-flex; align-items:center; gap:4px; font-size:11.5px; font-weight:700; color:#ea580c; margin-top:5px; }
.bb-job-expired  { display:inline-flex; align-items:center; gap:4px; font-size:11.5px; font-weight:700; color:#dc2626; margin-top:5px; }
.bb-jobcard-more { border:none; background:transparent; color:var(--bb-muted); width:32px; height:32px; border-radius:50%; cursor:pointer; flex-shrink:0; }
.bb-jobcard-more:hover { background:var(--bb-bg); }
.bb-jobcard-meta { display:flex; flex-wrap:wrap; gap:7px; margin:13px 0; }
.bb-jobcard-tag, .bb-jobcard-pill {
    display:inline-flex; align-items:center; gap:5px; font-size:12px; font-weight:600;
    padding:5px 11px; border-radius:8px;
}
.bb-jobcard-tag i, .bb-jobcard-pill i { font-size:11px; }
.bb-jobcard-pill { background:var(--bb-bg); color:#4b5563; }
.bb-jobcard-btn {
    display:flex; align-items:center; justify-content:center; width:100%;
    background:var(--bb-primary-soft); color:var(--bb-primary); border:none;
    border-radius:10px; padding:10px; font-size:13.5px; font-weight:700; text-decoration:none;
    transition:all .15s;
}
.bb-jobcard-btn:hover { background:var(--bb-primary); color:#fff; }
.bb-jobcard-posted { font-size:11.5px; color:var(--bb-muted); margin:3px 0 0; display:flex; align-items:center; gap:5px; }
.bb-jobcard-posted i { font-size:10px; }
.bb-job-save-btn { border:none; background:transparent; color:var(--bb-muted); width:34px; height:34px; border-radius:50%; cursor:pointer; flex-shrink:0; font-size:17px; transition:all .15s; }
.bb-job-save-btn:hover { background:var(--bb-bg); color:#f59e0b; }
.bb-job-save-btn.saved { color:#f59e0b; }
.bb-jobcard-foot { margin-top:12px; padding-top:11px; border-top:1px solid var(--bb-line); font-size:12.5px; font-weight:700; display:flex; align-items:center; gap:6px; }
.bb-foot-expiring { color:#ea580c; }
.bb-foot-expired { color:#dc2626; }

/* ============================================
   TEACHER FEED — PREMIUM VIOLET THEME
   শুধু teacher এর জন্য আলাদা প্রিমিয়াম লুক
   ============================================ */
.bb-teacher-feed {
    background: linear-gradient(180deg, #faf8ff 0%, #f4f0fb 100%) !important;
}
.bb-teacher-feed .navbar {
    border-top: 3px solid #7c3aed;
    box-shadow: 0 2px 12px rgba(124,58,237,.08);
}
.bb-teacher-feed .navbar-brand { color: #7c3aed !important; }
.bb-teacher-feed .bb-role-badge {
    background: #f3e8ff !important; color: #7c3aed !important; border-color: #ddd6fe !important;
}
.bb-teacher-feed .create-post-box {
    border: 1px solid #ede9fe; box-shadow: 0 2px 10px rgba(124,58,237,.06);
}
.bb-teacher-feed .create-post-avatar {
    background: linear-gradient(135deg, #7c3aed, #a78bfa) !important;
}
.bb-teacher-feed .sidebar-link.active { color: #7c3aed !important; }
.bb-teacher-feed .sidebar-link.active i { color: #7c3aed !important; }
.bb-teacher-feed .bb-post-card:hover {
    box-shadow: 0 8px 28px rgba(124,58,237,.12), 0 2px 6px rgba(16,24,40,.06);
}
.bb-teacher-feed .bb-avatar {
    background: linear-gradient(135deg, #7c3aed, #a78bfa);
    box-shadow: 0 2px 6px rgba(124,58,237,.25);
}
.bb-teacher-feed .bb-author-link:hover { color: #7c3aed; }
.bb-teacher-feed .bb-action-btn.active-like { color: #7c3aed; }
.bb-teacher-feed .bb-side-title i { color: #7c3aed; }

/* Teacher Workspace ribbon */
.bb-teacher-ribbon {
    display: flex; align-items: center; gap: 9px;
    background: linear-gradient(135deg, #7c3aed, #6d28d9);
    color: #fff; border-radius: 14px;
    padding: 13px 18px; margin-bottom: 16px;
    box-shadow: 0 4px 16px rgba(124,58,237,.25);
}
.bb-teacher-ribbon i { font-size: 22px; }
.bb-teacher-ribbon .bb-tr-title { font-size: 15px; font-weight: 700; letter-spacing:-.2px; }
.bb-teacher-ribbon .bb-tr-sub { font-size: 12px; opacity:.85; }

/* LIVE SEARCH DROPDOWN */
.bb-search-wrap { position: relative; }
.bb-search-box {
    background: #f0f2f5; border-radius: 50px; padding: .45rem 1rem;
    display: flex; align-items: center; width: 260px; transition: width .2s ease;
}
.bb-search-box:focus-within { width: 320px; background: #e4e6eb; }
.bb-search-box input { background: transparent; border: none; outline: none; margin-left: 8px; font-size: .9rem; width: 100%; }
.bb-search-dropdown {
    position: absolute; top: calc(100% + 8px); left: 0; width: 360px;
    background: #fff; border-radius: 14px; z-index: 9999; display: none; overflow: hidden;
    box-shadow: 0 8px 32px rgba(16,24,40,.14); border: 1px solid #eceef1;
}
.bb-search-dropdown.show { display: block; animation: sdIn .18s ease; }
@keyframes sdIn { from { opacity:0; transform:translateY(-6px); } to { opacity:1; transform:translateY(0); } }
.bb-sd-label { font-size:11px; font-weight:700; color:#6b7280; letter-spacing:.5px; text-transform:uppercase; padding:12px 14px 6px; }
.bb-sd-item { display:flex; align-items:center; gap:11px; padding:9px 14px; cursor:pointer; transition:background .12s; text-decoration:none; }
.bb-sd-item:hover, .bb-sd-item.active { background:#f3f4f8; }
.bb-sd-avatar {
    width:42px; height:42px; border-radius:50%; flex-shrink:0; overflow:hidden;
    background:linear-gradient(135deg,#4f46e5,#7c73f0); color:#fff;
    display:flex; align-items:center; justify-content:center; font-weight:700; font-size:17px;
}
.bb-sd-avatar img { width:100%; height:100%; object-fit:cover; }
.bb-sd-info { flex-grow:1; min-width:0; }
.bb-sd-name { font-size:.9rem; font-weight:700; color:#1e1f24; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.bb-sd-sub { font-size:.78rem; color:#6b7280; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:1px; }
.bb-sd-topic { font-size:.74rem; color:#4f46e5; margin-top:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.bb-sd-rolechip { font-size:9.5px; font-weight:700; padding:1px 7px; border-radius:12px; flex-shrink:0; }
.bb-sd-student { background:#eef2ff; color:#4f46e5; }
.bb-sd-alumni  { background:#fef3c7; color:#d97706; }
.bb-sd-teacher { background:#f3e8ff; color:#7c3aed; }
.bb-sd-footer {
    border-top:1px solid #eceef1; padding:10px 14px; font-size:.86rem; font-weight:700;
    color:#4f46e5; text-align:center; text-decoration:none; display:block; transition:background .12s;
}
.bb-sd-footer:hover { background:#f3f4f8; }
.bb-sd-spinner { text-align:center; padding:20px; color:#6b7280; font-size:.88rem; }
.bb-sd-empty { text-align:center; padding:20px 14px; color:#9ca3af; font-size:.86rem; }
.bb-friend-btn {
    display:inline-flex;align-items:center;gap:7px;
    font-size:.88rem;font-weight:700;padding:9px 18px;
    border-radius:10px;border:none;cursor:pointer;
    transition:all .15s ease;margin-right:6px;
}
.bb-friend-add{background:#4f46e5;color:#fff;}
.bb-friend-add:hover{background:#4338ca;}
.bb-friend-pending{background:#eef2ff;color:#4f46e5;border:1.5px solid #c7d2fe;}
.bb-friend-pending:hover{background:#fef2f2;color:#dc2626;}
.bb-friend-cancel-hint{font-size:.78rem;opacity:.75;}
.bb-friend-accept{background:#059669;color:#fff;}
.bb-friend-decline{background:#f3f4f8;color:#374151;}
.bb-friend-already{background:#f3f4f8;color:#374151;}
.bb-friend-blocked{background:#fee2e2;color:#dc2626;}
.bb-mutual-count{font-size:.82rem;color:#6b7280;margin-bottom:8px;display:flex;align-items:center;gap:5px;}
.bb-mutual-count i{color:#4f46e5;}

        </style>
</head>
<body class="bb-teacher-feed">

<nav class="navbar navbar-expand-md sticky-top">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-2">
            <a style="color:black;" class="navbar-brand m-0" href="http://127.0.0.1:8000">Borobhai.online</a>

            <div class="bb-search-wrap d-none d-lg-block">
                <div class="bb-search-box">
                    <i class="bi bi-search text-muted"></i>
                    <input type="text" id="bbLiveSearch"
                        placeholder="Search Borobhai..."
                        autocomplete="off">
                </div>
                <div class="bb-search-dropdown" id="bbSearchDropdown"></div>
            </div>

        </div>
        <div class="d-flex align-items-center gap-2 ms-auto">

            
            
            
                            <span class="bb-role-badge d-none d-sm-inline-flex bb-role-teacher">
                    <i class="bi bi-easel2-fill"></i> Teacher Feed
                </span>
            
            <a href="{{ route('search.index') }}" class="nav-icon-btn d-md-none"><i class="bi bi-search"></i></a>
            <a href="#" class="nav-icon-btn"><i class="fa-brands fa-facebook-messenger"></i></a>
            <a href="#" class="nav-icon-btn"><i class="bi bi-bell-fill"></i></a>
            <div class="dropdown">
                <button class="nav-icon-btn border-0" data-bs-toggle="dropdown">
                    <i class="bi bi-person-fill"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><span class="dropdown-item-text fw-bold text-dark">teacher</span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a href="http://127.0.0.1:8000/profile" class="dropdown-item">
                            <i class="bi bi-person-circle me-2"></i>View Profile
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="http://127.0.0.1:8000/logout" method="POST">
                            <input type="hidden" name="_token" value="yLeZsHdJiPbawa5jvfsPVpaGFzX1iVZyb5Jhpr6w" autocomplete="off">                            <button type="submit" class="dropdown-item text-danger">
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

        
        <div class="col-md-3 d-none d-md-block position-sticky" style="top:70px;height:fit-content;">
            <div class="d-flex flex-column gap-1">
                <a href="http://127.0.0.1:8000" class="sidebar-link active"><i class="bi bi-house-door-fill text-primary"></i><span>Home</span></a>

                <a href="http://127.0.0.1:8000/friends" class="sidebar-link">
                    <i class="bi bi-people-fill text-info"></i>
                    <span>See Friend List</span>
                </a>

                
                
                
                <a href="http://127.0.0.1:8000/saved" class="sidebar-link"><i class="bi bi-bookmark-heart-fill text-warning"></i><span>Saved</span></a>

                <a href="http://127.0.0.1:8000/my-applications" class="sidebar-link"><i class="bi bi-briefcase-fill text-primary"></i><span>Job History</span></a>

                <a href="http://127.0.0.1:8000/search" class="sidebar-link">
                    <i class="bi bi-search text-primary"></i><span>Search People</span>
                </a>

            </div>
        </div>

        
        <div class="col-12 col-md-6">

            
                        <div class="bb-teacher-ribbon">
                <i class="bi bi-easel2-fill"></i>
                <div>
                    <div class="bb-tr-title">Teacher Workspace</div>
                    <div class="bb-tr-sub">Share knowledge, research & resources with the community</div>
                </div>
            </div>
            
            
            <div class="create-post-box mb-3">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <a href="http://127.0.0.1:8000/profile" class="create-post-avatar" style="text-decoration:none;" title="Go to your profile">
                                                    <img src="http://127.0.0.1:8000/storage/profiles/STdJLDKjO8EDMviAEFQFQ5qQfEhiDO3v4InTSTWc.jpg" alt="me" class="cpa-img">
                                            </a>
                    <div class="mock-input" onclick="resetPostBg();" data-bs-toggle="modal" data-bs-target="#createPostModal">
                        What's on your mind, teacher?
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

            
            
            <div id="postsFeedContainer">
                                                <div class="bb-post-card" id="postCard-124" data-bg-color="">

    
    <div class="bb-post-head">
        <div class="bb-head-left">
            <a href="http://127.0.0.1:8000/profile/2" class="bb-avatar author-avatar-zone" style="text-decoration:none;" title="View profile">
                                    <img src="http://127.0.0.1:8000/storage/profiles/sh0UKM4ATo9mWi948wD1YecF3ln8g8nW4n0tkDcO.jpg" alt="Shahidul Islam Shovon" class="bb-avatar-img">
                            </a>
            <div class="bb-head-meta">
                <a href="http://127.0.0.1:8000/profile/2" class="bb-author author-name-zone bb-author-link">Shahidul Islam Shovon</a>
                
                                <span class="bb-author-role
                     bb-author-role-student ">
                                            <i class="bi bi-backpack-fill"></i> Student
                                    </span>
                <span class="bb-time"><i class="bi bi-globe-americas"></i> 21 hours ago</span>
            </div>
        </div>
            </div>

    
    
        <div class="bb-caption">
        <p class="mb-0 dynamic-caption">তোমাকে না পেয়েই এত ভালবাসি<br />
তোমাকে পেয়ে গেল কত ভালবাসতাম 😅</p>
    </div>
    
    
    
        <div class="bb-media-zone dynamic-media-container-zone" data-media-json="[{&quot;type&quot;:&quot;video&quot;,&quot;url&quot;:&quot;http:\/\/127.0.0.1:8000\/stream\/video\/posts\/videos\/JLafR4ZI4QpiS2H0xtFPT6WKJMw1Ty3LSSLhcO5i.mp4&quot;}]">

                                    
                <div class="bb-video-wrap">
                    <video src="http://127.0.0.1:8000/stream/video/posts/videos/JLafR4ZI4QpiS2H0xtFPT6WKJMw1Ty3LSSLhcO5i.mp4" class="bb-inline-video" preload="metadata" controls playsinline></video>
                    <button type="button" class="bb-expand-btn" title="Expand"
                            onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),0)">
                        <i class="bi bi-arrows-fullscreen"></i>
                    </button>
                </div>
            
            </div>
    
    
    
    
    <div class="bb-stats">
        <div id="like-zone-124" class="bb-like-stat">
                            <span class="bb-like-bubble"><i class="bi bi-hand-thumbs-up-fill"></i></span>
                <span class="like-count-text">1</span>
                    </div>
        <div>
            <span class="bb-stat-link" id="comment-count-124"
                  onclick="openCommentModal(124)">2 comments</span>
        </div>
    </div>

    
        <div class="bb-actions">
        <button type="button"
                class="bb-action-btn "
                id="likeBtn-124" onclick="toggleLike(124)">
            <i class="bi bi-hand-thumbs-up"></i>
            <span>Like</span>
        </button>
        <button type="button" class="bb-action-btn" onclick="openCommentModal(124)">
            <i class="bi bi-chat-square-text"></i>
            <span>Comment</span>
        </button>
        <button type="button" class="bb-action-btn" onclick="openShareModal(124)">
            <i class="bi bi-share"></i>
            <span>Share</span>
        </button>
        <button type="button"
                class="bb-action-btn "
                id="saveBtn-124" onclick="toggleSave(124)">
            <i class="bi bi-bookmark" id="saveIcon-124"></i>
            <span id="saveText-124">Save</span>
        </button>
    </div>

</div>                                                                <div class="bb-post-card" id="postCard-123" data-bg-color="">

    
    <div class="bb-post-head">
        <div class="bb-head-left">
            <a href="http://127.0.0.1:8000/profile/2" class="bb-avatar author-avatar-zone" style="text-decoration:none;" title="View profile">
                                    <img src="http://127.0.0.1:8000/storage/profiles/sh0UKM4ATo9mWi948wD1YecF3ln8g8nW4n0tkDcO.jpg" alt="Shahidul Islam Shovon" class="bb-avatar-img">
                            </a>
            <div class="bb-head-meta">
                <a href="http://127.0.0.1:8000/profile/2" class="bb-author author-name-zone bb-author-link">Shahidul Islam Shovon</a>
                
                                <span class="bb-author-role
                     bb-author-role-student ">
                                            <i class="bi bi-backpack-fill"></i> Student
                                    </span>
                <span class="bb-time"><i class="bi bi-globe-americas"></i> 21 hours ago</span>
            </div>
        </div>
            </div>

    
    
    
    
    
        <div class="bb-media-zone dynamic-media-container-zone" data-media-json="[{&quot;type&quot;:&quot;video&quot;,&quot;url&quot;:&quot;http:\/\/127.0.0.1:8000\/stream\/video\/posts\/videos\/9FAU28ASOaQn7uqjjsE8nEHqbATIMyk9W4QNXPSx.mp4&quot;}]">

                                    
                <div class="bb-video-wrap">
                    <video src="http://127.0.0.1:8000/stream/video/posts/videos/9FAU28ASOaQn7uqjjsE8nEHqbATIMyk9W4QNXPSx.mp4" class="bb-inline-video" preload="metadata" controls playsinline></video>
                    <button type="button" class="bb-expand-btn" title="Expand"
                            onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),0)">
                        <i class="bi bi-arrows-fullscreen"></i>
                    </button>
                </div>
            
            </div>
    
    
    
    
    <div class="bb-stats">
        <div id="like-zone-123" class="bb-like-stat">
                            <span class="bb-like-bubble"><i class="bi bi-hand-thumbs-up-fill"></i></span>
                <span class="like-count-text">1</span>
                    </div>
        <div>
            <span class="bb-stat-link" id="comment-count-123"
                  onclick="openCommentModal(123)">0 comments</span>
        </div>
    </div>

    
        <div class="bb-actions">
        <button type="button"
                class="bb-action-btn "
                id="likeBtn-123" onclick="toggleLike(123)">
            <i class="bi bi-hand-thumbs-up"></i>
            <span>Like</span>
        </button>
        <button type="button" class="bb-action-btn" onclick="openCommentModal(123)">
            <i class="bi bi-chat-square-text"></i>
            <span>Comment</span>
        </button>
        <button type="button" class="bb-action-btn" onclick="openShareModal(123)">
            <i class="bi bi-share"></i>
            <span>Share</span>
        </button>
        <button type="button"
                class="bb-action-btn "
                id="saveBtn-123" onclick="toggleSave(123)">
            <i class="bi bi-bookmark" id="saveIcon-123"></i>
            <span id="saveText-123">Save</span>
        </button>
    </div>

</div>                                                                <div class="bb-post-card" id="postCard-122" data-bg-color="">

    
    <div class="bb-post-head">
        <div class="bb-head-left">
            <a href="http://127.0.0.1:8000/profile/2" class="bb-avatar author-avatar-zone" style="text-decoration:none;" title="View profile">
                                    <img src="http://127.0.0.1:8000/storage/profiles/sh0UKM4ATo9mWi948wD1YecF3ln8g8nW4n0tkDcO.jpg" alt="Shahidul Islam Shovon" class="bb-avatar-img">
                            </a>
            <div class="bb-head-meta">
                <a href="http://127.0.0.1:8000/profile/2" class="bb-author author-name-zone bb-author-link">Shahidul Islam Shovon</a>
                
                                <span class="bb-author-role
                     bb-author-role-student ">
                                            <i class="bi bi-backpack-fill"></i> Student
                                    </span>
                <span class="bb-time"><i class="bi bi-globe-americas"></i> 21 hours ago</span>
            </div>
        </div>
            </div>

    
    
        <div class="bb-caption">
        <p class="mb-0 dynamic-caption">Its argentina</p>
    </div>
    
    
    
        <div class="bb-media-zone dynamic-media-container-zone" data-media-json="[{&quot;type&quot;:&quot;image&quot;,&quot;url&quot;:&quot;http:\/\/127.0.0.1:8000\/storage\/posts\/images\/3DbeODtYWirN4DwBqmnUQBZYAhPl2psTdTgF4TLr.jpg&quot;}]">

                                    <div class="bb-media-single">
                    <img src="http://127.0.0.1:8000/storage/posts/images/3DbeODtYWirN4DwBqmnUQBZYAhPl2psTdTgF4TLr.jpg" class="bb-single-img"
                         onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),0)">
                </div>
            
            </div>
    
    
    
    
    <div class="bb-stats">
        <div id="like-zone-122" class="bb-like-stat">
                            <span class="bb-like-bubble"><i class="bi bi-hand-thumbs-up-fill"></i></span>
                <span class="like-count-text">1</span>
                    </div>
        <div>
            <span class="bb-stat-link" id="comment-count-122"
                  onclick="openCommentModal(122)">0 comments</span>
        </div>
    </div>

    
        <div class="bb-actions">
        <button type="button"
                class="bb-action-btn "
                id="likeBtn-122" onclick="toggleLike(122)">
            <i class="bi bi-hand-thumbs-up"></i>
            <span>Like</span>
        </button>
        <button type="button" class="bb-action-btn" onclick="openCommentModal(122)">
            <i class="bi bi-chat-square-text"></i>
            <span>Comment</span>
        </button>
        <button type="button" class="bb-action-btn" onclick="openShareModal(122)">
            <i class="bi bi-share"></i>
            <span>Share</span>
        </button>
        <button type="button"
                class="bb-action-btn "
                id="saveBtn-122" onclick="toggleSave(122)">
            <i class="bi bi-bookmark" id="saveIcon-122"></i>
            <span id="saveText-122">Save</span>
        </button>
    </div>

</div>                                                                <div class="bb-post-card" id="postCard-121" data-bg-color="">

    
    <div class="bb-post-head">
        <div class="bb-head-left">
            <a href="http://127.0.0.1:8000/profile/8" class="bb-avatar author-avatar-zone" style="text-decoration:none;" title="View profile">
                                    J
                            </a>
            <div class="bb-head-meta">
                <a href="http://127.0.0.1:8000/profile/8" class="bb-author author-name-zone bb-author-link">Jafar Hossain</a>
                
                                <span class="bb-author-role
                     bb-author-role-teacher
                    ">
                                            <i class="bi bi-easel2-fill"></i> Teacher
                                    </span>
                <span class="bb-time"><i class="bi bi-globe-americas"></i> 3 days ago</span>
            </div>
        </div>
            </div>

    
    
        <div class="bb-caption">
        <p class="mb-0 dynamic-caption">❤️</p>
    </div>
    
    
    
    
    
    
    
    <div class="bb-stats">
        <div id="like-zone-121" class="bb-like-stat">
                            <span class="bb-like-bubble"><i class="bi bi-hand-thumbs-up-fill"></i></span>
                <span class="like-count-text">2</span>
                    </div>
        <div>
            <span class="bb-stat-link" id="comment-count-121"
                  onclick="openCommentModal(121)">0 comments</span>
        </div>
    </div>

    
        <div class="bb-actions">
        <button type="button"
                class="bb-action-btn "
                id="likeBtn-121" onclick="toggleLike(121)">
            <i class="bi bi-hand-thumbs-up"></i>
            <span>Like</span>
        </button>
        <button type="button" class="bb-action-btn" onclick="openCommentModal(121)">
            <i class="bi bi-chat-square-text"></i>
            <span>Comment</span>
        </button>
        <button type="button" class="bb-action-btn" onclick="openShareModal(121)">
            <i class="bi bi-share"></i>
            <span>Share</span>
        </button>
        <button type="button"
                class="bb-action-btn "
                id="saveBtn-121" onclick="toggleSave(121)">
            <i class="bi bi-bookmark" id="saveIcon-121"></i>
            <span id="saveText-121">Save</span>
        </button>
    </div>

</div>                                                                <div class="bb-jobcard" id="jobCard-6">
    <div class="bb-jobcard-top">
        <div class="bb-jobcard-logo" style="background:#eff6ff;color:#2563eb;">
            S
        </div>
        <div class="bb-jobcard-headinfo">
            <a href="http://127.0.0.1:8000/jobs/6" class="bb-jobcard-title">Video Editor</a>
            <p class="bb-jobcard-company">Shahidul Graphics and Multimedia · Narayanganj</p>
            <p class="bb-jobcard-posted"><i class="bi bi-clock"></i> Posted at 20 Jun, 8:29 AM</p>
        </div>
        <div class="d-flex align-items-start gap-1">
            <button class="bb-job-save-btn " id="jobSaveBtn-6"
                    onclick="toggleJobSave(6)" title="Save job">
                <i class="bi bi-bookmark"></i>
            </button>
                    </div>
    </div>

    <div class="bb-jobcard-meta">
        <span class="bb-jobcard-tag" style="background:#eff6ff;color:#2563eb;"><i class="bi bi-clock-history"></i> Part-time</span>
        <span class="bb-jobcard-pill"><i class="bi bi-cash-stack"></i> 12,000</span>        <span class="bb-jobcard-pill"><i class="bi bi-bar-chart"></i> Fresher</span>        <span class="bb-jobcard-pill"><i class="bi bi-calendar-event"></i> 25 Jun 2026</span>    </div>

            <a href="http://127.0.0.1:8000/jobs/6" class="bb-jobcard-btn">
            <i class="bi bi-box-arrow-up-right me-1"></i> View Details & Apply
        </a>
    
            <div class="bb-jobcard-foot bb-foot-expiring"><i class="bi bi-alarm"></i> Expiring soon — apply before 25 Jun 2026</div>
    </div>                                                                <div class="bb-post-card" id="postCard-120" data-bg-color="">

    
    <div class="bb-post-head">
        <div class="bb-head-left">
            <a href="http://127.0.0.1:8000/profile/5" class="bb-avatar author-avatar-zone" style="text-decoration:none;" title="View profile">
                                    <img src="http://127.0.0.1:8000/storage/profiles/aVuhR8kSgWyZEnRlfmvY1zFzre2gqNXIB6LePsyR.jpg" alt="Shahidul Islam Khan" class="bb-avatar-img">
                            </a>
            <div class="bb-head-meta">
                <a href="http://127.0.0.1:8000/profile/5" class="bb-author author-name-zone bb-author-link">Shahidul Islam Khan</a>
                
                                <span class="bb-author-role
                     bb-author-role-alumni
                    ">
                                            <i class="bi bi-mortarboard-fill"></i> Alumni
                                    </span>
                <span class="bb-time"><i class="bi bi-globe-americas"></i> 6 days ago</span>
            </div>
        </div>
            </div>

    
    
    
    
    
        <div class="bb-media-zone dynamic-media-container-zone" data-media-json="[{&quot;type&quot;:&quot;image&quot;,&quot;url&quot;:&quot;http:\/\/127.0.0.1:8000\/storage\/posts\/images\/TEa5kLhI8JCMw5MCdMv0wvihqZj9iWe03DyodMC9.png&quot;}]">

                                    <div class="bb-media-single">
                    <img src="http://127.0.0.1:8000/storage/posts/images/TEa5kLhI8JCMw5MCdMv0wvihqZj9iWe03DyodMC9.png" class="bb-single-img"
                         onclick="openLightbox(this.closest('[data-media-json]').getAttribute('data-media-json'),0)">
                </div>
            
            </div>
    
    
    
    
    <div class="bb-stats">
        <div id="like-zone-120" class="bb-like-stat">
                            <span class="bb-like-bubble"><i class="bi bi-hand-thumbs-up-fill"></i></span>
                <span class="like-count-text">1</span>
                    </div>
        <div>
            <span class="bb-stat-link" id="comment-count-120"
                  onclick="openCommentModal(120)">0 comments</span>
        </div>
    </div>

    
        <div class="bb-actions">
        <button type="button"
                class="bb-action-btn "
                id="likeBtn-120" onclick="toggleLike(120)">
            <i class="bi bi-hand-thumbs-up"></i>
            <span>Like</span>
        </button>
        <button type="button" class="bb-action-btn" onclick="openCommentModal(120)">
            <i class="bi bi-chat-square-text"></i>
            <span>Comment</span>
        </button>
        <button type="button" class="bb-action-btn" onclick="openShareModal(120)">
            <i class="bi bi-share"></i>
            <span>Share</span>
        </button>
        <button type="button"
                class="bb-action-btn "
                id="saveBtn-120" onclick="toggleSave(120)">
            <i class="bi bi-bookmark" id="saveIcon-120"></i>
            <span id="saveText-120">Save</span>
        </button>
    </div>

</div>                                    </div>

        
        <div id="feedLoader" class="text-center py-4 d-none">
            <div class="spinner-border text-primary" role="status" style="width:2rem;height:2rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div id="feedEndMessage" class="text-center text-muted py-4 d-none">
            <i class="bi bi-check2-circle me-1"></i> You're all caught up!
        </div>

        
        <div id="feedMeta"
            data-next-cursor=""
            data-has-more="1"></div>
              </div>

        
        <div class="col-md-3 d-none d-md-block bb-right-sidebar">

            
            <div class="bb-side-card mb-3">
                <div class="bb-side-head">
                    <span class="bb-side-title"><i class="bi bi-circle-fill text-success" style="font-size:9px;"></i> Active Now</span>
                </div>

                <div class="bb-side-body" id="activeNowZone">
                    
                    
                                            <div class="text-muted small px-2 py-3">No friends active right now.</div>
                                    </div>

            </div>

            
            <div class="bb-side-card mb-3">
                <div class="bb-side-head">
                    <span class="bb-side-title"><i class="bi bi-person-plus-fill text-primary"></i> Suggested Contact</span>
                </div>
                <div class="bb-side-body" id="suggestedPeopleZone">
                 
                   
                        <div class="bb-suggest-item" id="suggest-2">
        <a href="http://127.0.0.1:8000/profile/2"
           class="bb-suggest-avatar" style="text-decoration:none;overflow:hidden;">
                            <img src="http://127.0.0.1:8000/storage/profiles/sh0UKM4ATo9mWi948wD1YecF3ln8g8nW4n0tkDcO.jpg"
                     style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                    </a>
        <div class="bb-suggest-info">
            <a href="http://127.0.0.1:8000/profile/2"
               class="bb-suggest-name" style="text-decoration:none;color:inherit;">
                Shahidul Islam Shovon
            </a>
            <p class="bb-suggest-role">
                CSE
                            </p>
        </div>
                    <button type="button"
                    class="bb-connect-btn"
                    onclick="suggestAction('send', 2, this)"
                    title="Add Friend">
                <i class="bi bi-person-plus"></i>
            </button>
            </div>
    <div class="bb-suggest-item" id="suggest-3">
        <a href="http://127.0.0.1:8000/profile/3"
           class="bb-suggest-avatar" style="text-decoration:none;overflow:hidden;">
                            A
                    </a>
        <div class="bb-suggest-info">
            <a href="http://127.0.0.1:8000/profile/3"
               class="bb-suggest-name" style="text-decoration:none;color:inherit;">
                Ayesha Akter
            </a>
            <p class="bb-suggest-role">
                Student
                            </p>
        </div>
                    <button type="button"
                    class="bb-connect-btn"
                    onclick="suggestAction('send', 3, this)"
                    title="Add Friend">
                <i class="bi bi-person-plus"></i>
            </button>
            </div>
    <div class="bb-suggest-item" id="suggest-4">
        <a href="http://127.0.0.1:8000/profile/4"
           class="bb-suggest-avatar" style="text-decoration:none;overflow:hidden;">
                            E
                    </a>
        <div class="bb-suggest-info">
            <a href="http://127.0.0.1:8000/profile/4"
               class="bb-suggest-name" style="text-decoration:none;color:inherit;">
                Ertiza
            </a>
            <p class="bb-suggest-role">
                Alumni
                            </p>
        </div>
                    <button type="button"
                    class="bb-connect-btn"
                    onclick="suggestAction('send', 4, this)"
                    title="Add Friend">
                <i class="bi bi-person-plus"></i>
            </button>
            </div>
    <div class="bb-suggest-item" id="suggest-5">
        <a href="http://127.0.0.1:8000/profile/5"
           class="bb-suggest-avatar" style="text-decoration:none;overflow:hidden;">
                            <img src="http://127.0.0.1:8000/storage/profiles/aVuhR8kSgWyZEnRlfmvY1zFzre2gqNXIB6LePsyR.jpg"
                     style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                    </a>
        <div class="bb-suggest-info">
            <a href="http://127.0.0.1:8000/profile/5"
               class="bb-suggest-name" style="text-decoration:none;color:inherit;">
                Shahidul Islam Khan
            </a>
            <p class="bb-suggest-role">
                EEE
                            </p>
        </div>
                    <button type="button"
                    class="bb-connect-btn"
                    onclick="suggestAction('send', 5, this)"
                    title="Add Friend">
                <i class="bi bi-person-plus"></i>
            </button>
            </div>
    <div class="bb-suggest-item" id="suggest-6">
        <a href="http://127.0.0.1:8000/profile/6"
           class="bb-suggest-avatar" style="text-decoration:none;overflow:hidden;">
                            F
                    </a>
        <div class="bb-suggest-info">
            <a href="http://127.0.0.1:8000/profile/6"
               class="bb-suggest-name" style="text-decoration:none;color:inherit;">
                Fatiha Islam
            </a>
            <p class="bb-suggest-role">
                Alumni
                            </p>
        </div>
                    <button type="button"
                    class="bb-connect-btn"
                    onclick="suggestAction('send', 6, this)"
                    title="Add Friend">
                <i class="bi bi-person-plus"></i>
            </button>
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
                <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" onclick="resetPostBg();"></button>
            </div>
            <form id="ajaxPostForm" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="yLeZsHdJiPbawa5jvfsPVpaGFzX1iVZyb5Jhpr6w" autocomplete="off">                <div class="modal-body">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="create-post-avatar">
                                                            <img src="http://127.0.0.1:8000/storage/profiles/STdJLDKjO8EDMviAEFQFQ5qQfEhiDO3v4InTSTWc.jpg" alt="me" class="cpa-img">
                                                    </div>
                        <div>
                            <h6 class="m-0 fw-bold" style="font-size:14px;">teacher</h6>
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
                            <button type="button" class="bb-emoji-btn" data-target="#postContent" title="Emoji">
                                <i class="bi bi-emoji-smile text-warning"></i>
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


<div class="modal fade" id="editPostModal" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3 border-0 shadow">
            <div class="modal-header border-bottom-0 pb-1">
                <h5 class="modal-title fw-bold mx-auto" style="font-size:17px;">Edit Post</h5>
                <button type="button" class="btn-close ms-0" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPostForm" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="yLeZsHdJiPbawa5jvfsPVpaGFzX1iVZyb5Jhpr6w" autocomplete="off">                <input type="hidden" id="editPostId">
                <input type="hidden" id="edit_bg_color_input">
                <div class="modal-body pb-1">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="create-post-avatar">
                                                            <img src="http://127.0.0.1:8000/storage/profiles/STdJLDKjO8EDMviAEFQFQ5qQfEhiDO3v4InTSTWc.jpg" alt="me" class="cpa-img">
                                                    </div>
                        <div>
                            <h6 class="m-0 fw-bold" style="font-size:14px;">teacher</h6>
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

            <div class="modal-footer border-top p-2 flex-column align-items-stretch">
                <div id="commentEditNotice" class="d-none d-flex align-items-center justify-content-between px-2 py-1 mb-2 rounded" style="background:#eef2ff;font-size:12px;">
                    <span class="text-primary fw-semibold"><i class="bi bi-pencil-square me-1"></i> Editing comment</span>
                    <button type="button" class="btn btn-sm btn-link text-danger p-0 text-decoration-none fw-semibold" onclick="cancelCommentEdit()">
                        <i class="bi bi-x-lg"></i> Cancel
                    </button>
                </div>
                <form id="commentModalForm" class="d-flex align-items-center gap-2 w-100">
                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0 overflow-hidden"
                         style="width:34px;height:34px;font-size:13px;">
                                                    <img src="http://127.0.0.1:8000/storage/profiles/STdJLDKjO8EDMviAEFQFQ5qQfEhiDO3v4InTSTWc.jpg" alt="me" style="width:100%;height:100%;object-fit:cover;">
                                            </div>
                    <div class="input-group align-items-center bg-light rounded-pill px-3 py-1 w-100 border">
                        <input type="hidden" id="commentModalPostId">
                        <input type="text" id="commentModalInput"
                               class="form-control border-0 bg-transparent shadow-none py-1"
                               placeholder="Write a comment..." style="font-size:13px;" autocomplete="off">
                        <button type="button" class="bb-emoji-btn p-0 me-1" data-target="#commentModalInput" title="Emoji" style="font-size:17px;">
                            <i class="bi bi-emoji-smile"></i>
                        </button>
                        <button type="submit" class="btn btn-link p-0 text-primary ms-1 shadow-none border-0 d-flex align-items-center">
                            <i class="bi bi-send-fill" style="font-size:17px;"></i>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>


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
    if (window.location.hash && window.location.hash.startsWith('#postCard-')) {
        const targetId = window.location.hash.substring(1);

        const tryHighlight = (attempt = 0) => {
            const target = document.getElementById(targetId);

            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                target.style.transition = 'box-shadow .35s ease';
                target.style.boxShadow = '0 0 0 3px #4f46e5';
                setTimeout(() => { target.style.boxShadow = ''; }, 2500);
                return;
            }

            if (attempt < 15) {
                if (typeof loadMorePosts === 'function') loadMorePosts();
                setTimeout(() => tryHighlight(attempt + 1), 600);
            }
        };

        setTimeout(() => tryHighlight(0), 500);
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

    function primeVideoThumbnails(scope = document) {
        scope.querySelectorAll('video.bb-inline-video, video.bb-tile-media').forEach(v => {
            if (v.dataset.primed) return;
            v.dataset.primed = '1';
            v.preload = 'metadata';
            v.addEventListener('loadedmetadata', () => {
                try { if (v.currentTime === 0) v.currentTime = 0.1; } catch(e){}
            }, { once: true });
        });
    }
    primeVideoThumbnails();
    window.bbPrimeVideos = primeVideoThumbnails;
});

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
        Swal.fire({ icon: 'warning', title: 'Empty Post!', text: 'Please Write A Post First !'});
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
    const uName   = 'teacher';
    const uInit   = 'T';
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

    const fd = new FormData();
    fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    fd.append('content', content);
    fd.append('bg_color', bgColor);
    captured.forEach(f => fd.append('media[]', f));

    const xhr = new XMLHttpRequest();
    xhr.open('POST', "http://127.0.0.1:8000/posts", true);
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

            let res = {};
            try { res = JSON.parse(xhr.responseText); } catch(e){}

            setTimeout(() => {
                const optCard = document.getElementById(pid);
                if (res.html) {
                    if (optCard) {
                        optCard.outerHTML = res.html;
                    } else {
                        const feedC = document.getElementById('postsFeedContainer');
                        if (feedC) feedC.insertAdjacentHTML('afterbegin', res.html);
                    }
                    if (window.bbPrimeVideos) window.bbPrimeVideos();
                } else {
                    if (optCard) optCard.remove();
                }
                document.getElementById('emptyFeedState')?.remove();

                const Toast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:1800, timerProgressBar:true });
                Toast.fire({ icon:'success', title: res.message || 'Posted!' });
            }, 600);
        } else {
            document.getElementById(pid)?.remove();
            Swal.fire({ icon:'error', title:'Post not published!', text:'There was an issue uploading the post.' });
        }
    };
    xhr.send(fd);
});


// ==========================================
// SAVE / UNSAVE
// ==========================================
function toggleSave(postId) {
    const btn  = document.getElementById(`saveBtn-${postId}`);
    const icon = document.getElementById(`saveIcon-${postId}`);
    const text = document.getElementById(`saveText-${postId}`);

    fetch(`/posts/${postId}/save`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(d => {
        if (!d.success) return;

        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, timerProgressBar: true
        });

        if (d.saved) {
            if (btn)  btn.className = 'bb-action-btn active-save';
            if (icon) icon.className = 'bi bi-bookmark-fill';
            if (text) text.innerText = 'Saved';
            Toast.fire({ icon: 'success', title: d.message || 'Saved!' });
        } else {
            if (btn)  btn.className = 'bb-action-btn';
            if (icon) icon.className = 'bi bi-bookmark';
            if (text) text.innerText = 'Save';
            Toast.fire({ icon: 'info', title: d.message || 'Removed from saved' });
        }
    })
    .catch(() => {
        Swal.fire({ icon: 'error', title: 'Something went wrong!' });
    });
}

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
        btn.className = d.liked ? 'bb-action-btn active-like' : 'bb-action-btn';
        btn.innerHTML = d.liked ? '<i class="bi bi-hand-thumbs-up-fill"></i> <span>Like</span>' : '<i class="bi bi-hand-thumbs-up"></i> <span>Like</span>';
        if (zone) zone.innerHTML = d.like_count > 0 ? `<span class="bb-like-bubble"><i class="bi bi-hand-thumbs-up-fill"></i></span> <span class="like-count-text">${d.like_count}</span>` : '';
    });
}

// ==========================================
// DELETE POST
// ==========================================
function deletePost(id) {
    Swal.fire({title:'Are you sure?',icon:'warning',showCancelButton:true,confirmButtonColor:'#d33'}).then(r=>{
        if (!r.isConfirmed) return;
        fetch(`/posts/${id}`,{method:'DELETE',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}})
        .then(r=>r.json()).then(d=>{
            if(!d.success) return;
            const card = document.getElementById(`postCard-${id}`);
            if (card) {
                card.style.transition = 'opacity .3s ease';
                card.style.opacity = '0';
                setTimeout(() => card.remove(), 300);
            }
            const Toast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:1500 });
            Toast.fire({ icon:'success', title:'Post deleted' });
        });
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
            if (d.html) {
                const feedC = document.getElementById('postsFeedContainer');
                if (feedC) {
                    feedC.insertAdjacentHTML('afterbegin', d.html);
                    if (window.bbPrimeVideos) window.bbPrimeVideos();
                    document.getElementById('emptyFeedState')?.remove();
                }
            }
            btn.disabled = false;
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

    const src=isNew?URL.createObjectURL(pathOrFile):`http://127.0.0.1:8000/storage/${pathOrFile}`;
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
            if (oldCard && res.html) { oldCard.outerHTML = res.html; if (window.bbPrimeVideos) window.bbPrimeVideos(); }
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
// INFINITE SCROLL (unified feed — page based)
// ==========================================
let feedLoading = false;
let feedPage    = 1;

function loadMorePosts() {
    const meta = document.getElementById('feedMeta');
    if (!meta) return;
    if (feedLoading || meta.dataset.hasMore === '0') return;

    feedLoading = true;
    feedPage++;
    const loader = document.getElementById('feedLoader');
    if (loader) loader.classList.remove('d-none');

    fetch(`http://127.0.0.1:8000/feed/load?page=${feedPage}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        const container = document.getElementById('postsFeedContainer');
        if (container && data.html && data.html.trim()) {
            container.insertAdjacentHTML('beforeend', data.html);
            if (window.bbPrimeVideos) window.bbPrimeVideos(container);
        }

        meta.dataset.hasMore = data.has_more ? '1' : '0';
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
</script>

<script>
// ==========================================
// COMMENT MODAL (Premium)
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
    document.getElementById('commentEditNotice')?.classList.add('d-none');

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
            document.getElementById('commentEditNotice')?.classList.add('d-none');
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
        <div class="comment-thread" id="comment-thread-${d.comment_id}">
        <div class="d-flex gap-2 mb-2 align-items-start comment-row" id="comment-container-${d.comment_id}">
            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0 overflow-hidden" style="width:32px;height:32px;font-size:13px;">${d.user_picture ? `<img src="${d.user_picture}" style="width:100%;height:100%;object-fit:cover;">` : d.user_initial}</div>
            <div class="flex-grow-1">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="bg-light px-3 py-2 rounded-4 d-inline-block border" style="max-width:100%;">
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
                <div class="d-flex align-items-center gap-3 ms-2 mt-1" style="font-size:11.5px;">
                    <span class="comment-like-btn" id="comment-like-${d.comment_id}" onclick="toggleCommentLike(${d.comment_id})" style="cursor:pointer;font-weight:600;">Like</span>
                    <span class="comment-reply-btn" onclick="openReplyBox(${d.comment_id})" style="cursor:pointer;font-weight:600;color:#65676b;">Reply</span>
                    <span class="text-muted comment-meta-${d.comment_id}">${d.created_at}<span class="comment-edited-tag-${d.comment_id}"></span></span>
                    <span class="comment-like-count text-muted" id="comment-like-count-${d.comment_id}" style="display:none;"><i class="bi bi-hand-thumbs-up-fill text-primary"></i> <span class="clc-num">0</span></span>
                </div>
                <div class="reply-box-zone mt-2 d-none" id="reply-box-${d.comment_id}"></div>
                <div class="replies-zone mt-2" id="replies-zone-${d.comment_id}"></div>
            </div>
        </div>
        </div>`;
        document.getElementById('commentModalList')?.insertAdjacentHTML('afterbegin', html);

        const feedCount = document.getElementById(`comment-count-${postId}`);
        if (feedCount && d.comment_count !== undefined) feedCount.innerText = `${d.comment_count} comments`;
        const modalCount = document.getElementById('commentModalCount');
        if (modalCount && d.comment_count !== undefined) modalCount.innerText = `${d.comment_count} comments`;
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
    document.getElementById('commentEditNotice')?.classList.remove('d-none');
}

function cancelCommentEdit() {
    commentEditState = { editing: false, commentId: null };
    const input = document.getElementById('commentModalInput');
    if (input) { input.value = ''; input.placeholder = 'Write a comment...'; }
    document.getElementById('commentEditNotice')?.classList.add('d-none');
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
            if (feedCount && d.comment_count !== undefined) feedCount.innerText = `${d.comment_count} comments`;
            const modalCount = document.getElementById('commentModalCount');
            if (modalCount && d.comment_count !== undefined) modalCount.innerText = `${d.comment_count} comments`;
            if (commentEditState.commentId == cid) {
                commentEditState = { editing: false, commentId: null };
                const input = document.getElementById('commentModalInput');
                if (input) { input.value=''; input.placeholder='Write a comment...'; }
                document.getElementById('commentEditNotice')?.classList.add('d-none');
            }
        });
    });
}

window.MY_PROFILE_PIC = "http:\/\/127.0.0.1:8000\/storage\/profiles\/STdJLDKjO8EDMviAEFQFQ5qQfEhiDO3v4InTSTWc.jpg";
window.MY_INITIAL = "T";

function toggleCommentLike(commentId) {
    fetch(`/comments/${commentId}/like`, {
        method:'POST',
        headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'}
    })
    .then(r=>r.json())
    .then(d=>{
        if(!d.success) return;
        const btn = document.getElementById(`comment-like-${commentId}`);
        const countWrap = document.getElementById(`comment-like-count-${commentId}`);
        if (btn) {
            btn.classList.toggle('liked', d.liked);
            btn.innerText = d.liked ? 'Liked' : 'Like';
        }
        if (countWrap) {
            const num = countWrap.querySelector('.clc-num');
            if (num) num.innerText = d.like_count;
            countWrap.style.display = d.like_count > 0 ? '' : 'none';
        }
    });
}

function openReplyBox(parentId, mentionName) {
    const zone = document.getElementById(`reply-box-${parentId}`);
    if (!zone) return;

    let input = document.getElementById(`reply-input-${parentId}`);

    const myPic = window.MY_PROFILE_PIC;
    const myInit = window.MY_INITIAL || 'U';
    const avatar = myPic
        ? `<img src="${myPic}" style="width:100%;height:100%;object-fit:cover;">`
        : myInit;

    if (zone.classList.contains('d-none') || zone.dataset.open !== '1') {
        zone.innerHTML = `
            <div class="reply-input-wrap">
                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0 overflow-hidden" style="width:28px;height:28px;font-size:12px;">${avatar}</div>
                <div class="reply-field flex-grow-1">
                    <span class="reply-mention-tag" id="reply-mention-${parentId}" style="display:none;"></span>
                    <input type="text" class="reply-input-box" id="reply-input-${parentId}" placeholder="Write a reply..." autocomplete="off"
                           onkeydown="if(event.key==='Enter'){event.preventDefault();submitReply(${parentId});} else if(event.key==='Backspace' && this.value==='' ){clearReplyMention(${parentId});}">
                </div>
                <button type="button" class="bb-emoji-btn p-0" data-target="#reply-input-${parentId}" title="Emoji" style="font-size:15px;"><i class="bi bi-emoji-smile"></i></button>
                <button type="button" class="reply-send-btn" onclick="submitReply(${parentId})" title="Send"><i class="bi bi-send-fill"></i></button>
            </div>`;
        zone.classList.remove('d-none');
        zone.dataset.open = '1';
        input = document.getElementById(`reply-input-${parentId}`);
    }

    const tag = document.getElementById(`reply-mention-${parentId}`);
    if (mentionName && tag) {
        tag.textContent = '@' + mentionName;
        tag.style.display = 'inline-flex';
        tag.dataset.mention = mentionName;
    }

    setTimeout(() => input?.focus(), 50);
}

function clearReplyMention(parentId) {
    const tag = document.getElementById(`reply-mention-${parentId}`);
    if (tag) { tag.style.display = 'none'; tag.textContent = ''; tag.dataset.mention = ''; }
}

function submitReply(parentId) {
    const input = document.getElementById(`reply-input-${parentId}`);
    if (!input) return;
    let text = input.value.trim();
    const tag = document.getElementById(`reply-mention-${parentId}`);
    const mention = tag && tag.dataset.mention ? tag.dataset.mention : '';
    if (!text && !mention) return;

    const finalText = mention ? `@${mention} ${text}` : text;

    const postId = document.getElementById('commentModalPostId').value;
    input.disabled = true;

    fetch(`/posts/${postId}/comments`, {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},
        body: JSON.stringify({ content: finalText, parent_id: parentId })
    })
    .then(r=>r.json())
    .then(d=>{
        if(!d.success) { input.disabled=false; return; }

        const repliesZone = document.getElementById(`replies-zone-${parentId}`);
        const avatar = d.user_picture
            ? `<img src="${d.user_picture}" style="width:100%;height:100%;object-fit:cover;">`
            : d.user_initial;

        const displayContent = highlightMentions(d.content);

        const html = `
        <div class="d-flex gap-2 mb-2 align-items-start comment-row reply-row" id="comment-container-${d.comment_id}">
            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0 overflow-hidden" style="width:28px;height:28px;font-size:12px;">${avatar}</div>
            <div class="flex-grow-1">
                <div class="d-flex align-items-start justify-content-between">
                    <div class="bg-light px-3 py-2 rounded-4 d-inline-block border" style="max-width:100%;">
                        <strong class="d-block text-dark" style="font-size:12px;">${d.user_name}</strong>
                        <span id="comment-text-${d.comment_id}" style="font-size:12.5px;word-break:break-word;">${displayContent}</span>
                    </div>
                    <div class="dropdown flex-shrink-0">
                        <button type="button" class="btn btn-link btn-sm text-muted p-0 border-0 shadow-none ms-1" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm p-1" style="min-width:100px;">
                            <li><a class="dropdown-item py-1 fs-7" href="javascript:void(0)" onclick="editComment(event, ${d.comment_id})"><i class="bi bi-pencil me-1"></i> Edit</a></li>
                            <li><a class="dropdown-item py-1 fs-7 text-danger" href="javascript:void(0)" onclick="deleteComment(${d.comment_id}, ${postId})"><i class="bi bi-trash me-1"></i> Delete</a></li>
                        </ul>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3 ms-2 mt-1" style="font-size:11px;">
                    <span class="comment-like-btn" id="comment-like-${d.comment_id}" onclick="toggleCommentLike(${d.comment_id})" style="cursor:pointer;font-weight:600;">Like</span>
                    <span class="comment-reply-btn" onclick="openReplyBox(${parentId}, '${d.user_name.replace(/'/g, "\\'")}')" style="cursor:pointer;font-weight:600;color:#65676b;">Reply</span>
                    <span class="text-muted comment-meta-${d.comment_id}">${d.created_at}<span class="comment-edited-tag-${d.comment_id}"></span></span>
                    <span class="comment-like-count text-muted" id="comment-like-count-${d.comment_id}" style="display:none;"><i class="bi bi-hand-thumbs-up-fill text-primary"></i> <span class="clc-num">0</span></span>
                </div>
            </div>
        </div>`;
        repliesZone?.insertAdjacentHTML('beforeend', html);

        const zone = document.getElementById(`reply-box-${parentId}`);
        if (zone) { zone.classList.add('d-none'); zone.dataset.open='0'; zone.innerHTML=''; }

        const feedCount = document.getElementById(`comment-count-${postId}`);
        if (feedCount && d.comment_count !== undefined) feedCount.innerText = `${d.comment_count} comments`;
        const modalCount = document.getElementById('commentModalCount');
        if (modalCount && d.comment_count !== undefined) modalCount.innerText = `${d.comment_count} comments`;
    })
    .catch(()=>{ input.disabled=false; });
}

function highlightMentions(text) {
    return text.replace(/@([\w\u0980-\u09FF.]+(?:\s[\w\u0980-\u09FF.]+)?)/g, '<span class="comment-mention">@$1</span>');
}
</script>




<div id="bbEmojiPopover"><emoji-picker class="light"></emoji-picker></div>

<script>
// ==========================================
// EMOJI PICKER (shared)
// ==========================================
(function(){
    const popover = document.getElementById('bbEmojiPopover');
    const picker  = popover.querySelector('emoji-picker');
    let currentTarget = null;

    picker.addEventListener('emoji-click', e => {
        const emoji = e.detail.unicode;
        if (!currentTarget) return;
        const el = currentTarget;
        const start = el.selectionStart ?? el.value.length;
        const end   = el.selectionEnd ?? el.value.length;
        el.value = el.value.slice(0, start) + emoji + el.value.slice(end);
        const pos = start + emoji.length;
        el.focus();
        try { el.setSelectionRange(pos, pos); } catch(err){}
        el.dispatchEvent(new Event('input', { bubbles:true }));
    });

    document.addEventListener('click', function(ev){
        const btn = ev.target.closest('.bb-emoji-btn');
        if (btn) {
            ev.preventDefault();
            const targetSel = btn.getAttribute('data-target');
            const target = document.querySelector(targetSel);
            if (!target) return;

            if (popover.style.display === 'block' && currentTarget === target) {
                popover.style.display = 'none';
                currentTarget = null;
                return;
            }
            currentTarget = target;
            const r = btn.getBoundingClientRect();
            popover.style.display = 'block';
            let top = r.bottom + window.scrollY + 6;
            if (r.bottom + 350 > window.innerHeight) {
                top = r.top + window.scrollY - 350 - 6;
            }
            let left = r.left + window.scrollX - 150;
            if (left < 8) left = 8;
            if (left + 320 > window.innerWidth) left = window.innerWidth - 328;
            popover.style.top = top + 'px';
            popover.style.left = left + 'px';
            return;
        }
        if (popover.style.display === 'block' && !popover.contains(ev.target)) {
            popover.style.display = 'none';
            currentTarget = null;
        }
    });
})();
</script>

</body>
</html>