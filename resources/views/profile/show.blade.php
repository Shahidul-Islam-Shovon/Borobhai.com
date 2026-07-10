<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
    {{-- Cropper.js for cover + avatar cropping --}}
    <link href="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.css" rel="stylesheet">
    <title>{{ $user->name }} · Borobhai.online</title>
    <style>
        :root {
            --bb-primary: #4f46e5;
            --bb-primary-dark: #4338ca;
            --bb-primary-soft: #eef2ff;
            --bb-ink: #1e1f24;
            --bb-muted: #6b7280;
            --bb-line: #eceef1;
            --bb-bg: #f3f4f8;
            --bb-card: #ffffff;
            --bb-shadow: 0 1px 3px rgba(16,24,40,.06), 0 1px 2px rgba(16,24,40,.04);
            --bb-shadow-lg: 0 10px 35px rgba(16,24,40,.10);
            --bb-radius: 16px;
            --bb-shadow-hover: 0 8px 28px rgba(79,70,229,.10), 0 2px 6px rgba(16,24,40,.06);
        }
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }
        body { background: var(--bb-bg); color: var(--bb-ink); }

        .bb-nav { background:#fff; box-shadow:0 2px 4px rgba(0,0,0,.06); padding:.5rem 1rem; position:sticky; top:0; z-index:1020; }
        .bb-brand { font-weight:800; color:var(--bb-primary); font-size:1.4rem; letter-spacing:-.5px; text-decoration:none; }
        .bb-nav-btn { width:40px; height:40px; border-radius:50%; background:#e4e6eb; display:flex; align-items:center; justify-content:center; color:#050505; text-decoration:none; font-size:1.1rem; border:none; }
        .bb-nav-btn:hover { background:#d8dadf; }

        .bb-profile-wrap { max-width: 940px; margin: 0 auto; padding: 20px 12px 60px; }

        .bb-cover {
            position: relative; height: 280px; border-radius: 18px; overflow: hidden;
            background: linear-gradient(135deg, #4f46e5 0%, #7c73f0 50%, #a78bfa 100%);
            box-shadow: var(--bb-shadow);
        }
        .bb-cover img { width:100%; height:100%; object-fit:cover; display:block; }
        .bb-cover-edit {
            position:absolute; right:16px; bottom:16px; z-index:3;
            background:rgba(255,255,255,.92); border:none; border-radius:10px;
            padding:8px 14px; font-size:13px; font-weight:600; color:var(--bb-ink);
            cursor:pointer; display:flex; align-items:center; gap:6px; transition:background .15s;
            backdrop-filter: blur(4px);
        }
        .bb-cover-edit:hover { background:#fff; }

        .bb-head-card {
            background:var(--bb-card); border-radius:18px; box-shadow:var(--bb-shadow);
            padding: 0 28px 24px; margin-top:-70px; position:relative; z-index:2;
        }
        .bb-avatar-wrap { position:relative; width:150px; height:150px; margin-top:-75px; }
        .bb-avatar-lg {
            width:150px; height:150px; border-radius:50%; border:5px solid #fff;
            object-fit:cover; background:linear-gradient(135deg,var(--bb-primary),#7c73f0);
            display:flex; align-items:center; justify-content:center; color:#fff; font-size:60px; font-weight:800;
            box-shadow:0 4px 14px rgba(79,70,229,.3);
        }
        .bb-avatar-edit {
            position:absolute; right:6px; bottom:6px; width:38px; height:38px; border-radius:50%;
            background:var(--bb-primary); color:#fff; border:3px solid #fff; cursor:pointer;
            display:flex; align-items:center; justify-content:center; font-size:15px; transition:background .15s;
        }
        .bb-avatar-edit:hover { background:var(--bb-primary-dark); }

        .bb-name { font-size:28px; font-weight:800; letter-spacing:-.5px; margin:14px 0 2px; }
        .bb-headline { font-size:15px; color:var(--bb-muted); margin:0 0 10px; }

        /* Profile detail sections (Education/Experience/Cert) */
        .bb-headline-sub { font-size:13px; color:var(--bb-muted); margin:0 0 8px; display:flex; align-items:center; gap:5px; }
        .bb-headline-sub i { font-size:13px; }
        .bb-add-btn {
            background:var(--bb-primary-soft); color:var(--bb-primary); border:none;
            border-radius:9px; padding:7px 14px; font-size:13px; font-weight:600;
            display:inline-flex; align-items:center; gap:5px; cursor:pointer; transition:all .15s;
        }
        .bb-add-btn:hover { background:var(--bb-primary); color:#fff; }
        .bb-timeline-item {
            display:flex; gap:14px; padding:14px 0; border-bottom:1px solid var(--bb-line); position:relative;
        }
        .bb-timeline-item:last-child { border-bottom:none; padding-bottom:0; }
        .bb-timeline-item:first-child { padding-top:0; }
        .bb-timeline-icon {
            width:44px; height:44px; border-radius:11px; flex-shrink:0;
            background:var(--bb-primary-soft); color:var(--bb-primary);
            display:flex; align-items:center; justify-content:center; font-size:19px;
        }
        .bb-timeline-body { flex-grow:1; min-width:0; }
        .bb-timeline-title { font-size:15px; font-weight:700; color:var(--bb-ink); margin:0 0 2px; }
        .bb-timeline-sub { font-size:13.5px; font-weight:600; color:#374151; margin:0 0 2px; }
        .bb-timeline-meta { font-size:12.5px; color:var(--bb-muted); margin:0; }
        .bb-timeline-desc { font-size:13px; color:#4b5563; margin:6px 0 0; line-height:1.5; white-space:pre-line; }
        .bb-cred-link { color:var(--bb-primary); text-decoration:none; font-weight:600; }
        .bb-cred-link:hover { text-decoration:underline; }
        .bb-timeline-actions { display:flex; gap:4px; flex-shrink:0; }
        .bb-timeline-actions button {
            width:32px; height:32px; border-radius:8px; border:none; background:transparent;
            color:var(--bb-muted); cursor:pointer; font-size:14px; transition:all .15s;
        }
        .bb-timeline-actions button:hover { background:var(--bb-bg); color:var(--bb-ink); }
        .bb-timeline-actions button.text-danger:hover { background:#fef2f2; color:#ef4444 !important; }

        /* Research & Publications (documents) */
        .bb-doc-item { display:flex; gap:14px; padding:16px 0; border-bottom:1px solid var(--bb-line); }
        .bb-doc-item:last-child { border-bottom:none; padding-bottom:0; }
        .bb-doc-item:first-child { padding-top:0; }
        .bb-doc-icon {
            width:48px; height:48px; border-radius:12px; flex-shrink:0;
            display:flex; align-items:center; justify-content:center; font-size:24px;
            background:#f3f4f8; color:#6b7280;
        }
        .bb-doc-pdf { background:#fef2f2; color:#dc2626; }
        .bb-doc-doc, .bb-doc-docx, .bb-doc-odt, .bb-doc-rtf { background:#eff6ff; color:#2563eb; }
        .bb-doc-ppt, .bb-doc-pptx { background:#fff7ed; color:#ea580c; }
        .bb-doc-body { flex-grow:1; min-width:0; }
        .bb-doc-toprow { display:flex; align-items:center; gap:4px; margin-bottom:2px; }
        .bb-doc-type {
            font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.4px;
            color:var(--bb-primary); background:var(--bb-primary-soft); padding:2px 8px; border-radius:6px;
        }
        .bb-doc-year { font-size:12px; color:var(--bb-muted); }
        .bb-doc-title { font-size:15px; font-weight:700; color:var(--bb-ink); margin:3px 0 2px; }
        .bb-doc-topic { font-size:12.5px; color:var(--bb-primary); margin:0 0 3px; font-weight:600; }
        .bb-doc-topic i { font-size:11px; }
        .bb-doc-desc { font-size:13px; color:#4b5563; margin:0 0 6px; line-height:1.5; white-space:pre-line; }
        .bb-doc-filerow { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
        .bb-doc-file {
            display:inline-flex; align-items:center; gap:5px; font-size:12.5px; font-weight:600;
            color:var(--bb-primary); text-decoration:none; background:var(--bb-bg);
            padding:4px 10px; border-radius:8px; transition:background .15s;
            max-width:100%; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
        }
        .bb-doc-file:hover { background:var(--bb-primary); color:#fff; }
        .bb-doc-size { font-size:11.5px; color:var(--bb-muted); }

        /* My Job Posts */
        .bb-myjob-item { display:flex; gap:13px; padding:14px 0; border-bottom:1px solid var(--bb-line); }
        .bb-myjob-item:last-child { border-bottom:none; padding-bottom:0; }
        .bb-myjob-item:first-child { padding-top:0; }
        .bb-myjob-logo { width:46px; height:46px; border-radius:11px; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:20px; font-weight:800; }
        .bb-myjob-body { flex-grow:1; min-width:0; }
        .bb-myjob-title { font-size:15px; font-weight:700; color:var(--bb-ink); text-decoration:none; letter-spacing:-.2px; }
        .bb-myjob-title:hover { color:var(--bb-primary); }
        .bb-myjob-company { font-size:12.5px; color:var(--bb-muted); margin:1px 0 6px; }
        .bb-myjob-meta { display:flex; flex-wrap:wrap; align-items:center; gap:6px 12px; }
        .bb-myjob-tag { font-size:11px; font-weight:700; color:var(--bb-primary); background:var(--bb-primary-soft); padding:2px 9px; border-radius:6px; }
        .bb-myjob-date { font-size:11.5px; color:var(--bb-muted); display:inline-flex; align-items:center; gap:4px; }
        .bb-myjob-date i { font-size:10px; }
        .bb-myjob-status { font-size:11px; font-weight:700; display:inline-flex; align-items:center; gap:4px; padding:2px 9px; border-radius:6px; }
        .bb-myjob-status i { font-size:9px; }
        .bb-st-active { background:#dcfce7; color:#16a34a; }
        .bb-st-soon   { background:#fff7ed; color:#ea580c; }
        .bb-st-closed { background:#fef2f2; color:#dc2626; }

        /* View Applicants link (profile job posts) */
        .bb-myjob-applicants { display:inline-flex; align-items:center; gap:6px; margin-top:9px; font-size:12.5px; font-weight:600; color:var(--bb-primary); background:var(--bb-primary-soft); padding:6px 13px; border-radius:8px; text-decoration:none; transition:all .15s; }
        .bb-myjob-viewall { display:flex; align-items:center; justify-content:center; gap:7px; margin-top:14px; padding:11px; font-size:13px; font-weight:600; color:var(--bb-primary); background:var(--bb-primary-soft); border-radius:10px; text-decoration:none; transition:all .15s; }
        .bb-myjob-viewall:hover { background:var(--bb-primary); color:#fff; }
        .bb-myjob-applicants:hover { background:var(--bb-primary); color:#fff; }
        .bb-myjob-applicants i { font-size:12px; }

        /* Post Job modal (profile edit) */
        .bb-job-label { display:block; font-size:12.5px; font-weight:600; color:#374151; margin-bottom:5px; }
        .bb-job-input { width:100%; border:1.5px solid #e4e6eb; border-radius:10px; padding:9px 12px; font-size:13.5px; outline:none; transition:border-color .15s, box-shadow .15s; background:#fff; }
        .bb-job-input:focus { border-color:#4f46e5; box-shadow:0 0 0 3px rgba(79,70,229,.12); }
        textarea.bb-job-input { resize:vertical; }
        .bb-job-submit-btn { background:linear-gradient(135deg,#4f46e5,#7c73f0); color:#fff; border:none; border-radius:10px; padding:9px 22px; font-size:14px; font-weight:700; display:inline-flex; align-items:center; transition:all .15s; }
        .bb-job-submit-btn:hover { box-shadow:0 4px 14px rgba(79,70,229,.4); }
        .bb-job-submit-btn:disabled { opacity:.6; }
        .postjob-dialog { height:calc(100vh - 3.5rem); }
        .postjob-content { max-height:100%; display:flex; flex-direction:column; overflow:hidden; }
        .postjob-form { display:flex; flex-direction:column; min-height:0; flex:1 1 auto; overflow:hidden; }
        .postjob-body { overflow-y:auto; flex:1 1 auto; min-height:0; }
        .postjob-footer { flex:0 0 auto; }
        @media (max-width:576px){ .postjob-dialog { height:calc(100vh - 1rem); } }

        .bb-role-pill {
            display:inline-flex; align-items:center; gap:5px; font-size:12px; font-weight:700;
            padding:5px 13px; border-radius:20px; letter-spacing:.3px; text-transform:uppercase;
        }
        .bb-role-alumni  { background:#fef3c7; color:#d97706; }
        .bb-role-student { background:#eef2ff; color:#4f46e5; }
        .bb-role-teacher { background:#f3e8ff; color:#7c3aed; }

        .bb-edit-profile-btn {
            background:var(--bb-primary); color:#fff; border:none; border-radius:10px;
            padding:9px 20px; font-size:14px; font-weight:600; cursor:pointer;
            display:inline-flex; align-items:center; gap:7px; transition:background .15s;
        }
        .bb-edit-profile-btn:hover { background:var(--bb-primary-dark); }

        .bb-stat-row { display:flex; gap:26px; margin-top:14px; }
        .bb-stat b { font-size:18px; font-weight:800; }
        .bb-stat span { font-size:13px; color:var(--bb-muted); margin-left:5px; }

        .bb-card { background:var(--bb-card); border-radius:16px; box-shadow:var(--bb-shadow); padding:22px 24px; margin-top:18px; }
        .bb-card-title { font-size:17px; font-weight:700; letter-spacing:-.2px; margin:0 0 16px; display:flex; align-items:center; gap:9px; }
        .bb-card-title i { color:var(--bb-primary); }

        .bb-info-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:16px 24px; }
        .bb-info-item { display:flex; gap:12px; align-items:flex-start; }
        .bb-info-icon { width:38px; height:38px; border-radius:10px; background:var(--bb-primary-soft); color:var(--bb-primary); display:flex; align-items:center; justify-content:center; font-size:17px; flex-shrink:0; }
        .bb-info-label { font-size:12px; color:var(--bb-muted); margin:0; }
        .bb-info-value { font-size:14.5px; font-weight:600; margin:0; word-break:break-word; }

        .bb-bio-text { font-size:15px; line-height:1.65; color:#374151; white-space:pre-line; }

        .bb-chips { display:flex; flex-wrap:wrap; gap:9px; }
        .bb-chip {
            background:var(--bb-primary-soft); color:var(--bb-primary-dark);
            font-size:13px; font-weight:600; padding:7px 15px; border-radius:20px;
            border:1px solid #e0e7ff;
        }

        .bb-socials { display:flex; gap:12px; flex-wrap:wrap; }
        .bb-social-link {
            display:flex; align-items:center; gap:9px; padding:11px 18px; border-radius:12px;
            text-decoration:none; font-size:14px; font-weight:600; border:1.5px solid var(--bb-line);
            color:var(--bb-ink); transition:all .15s;
        }
        .bb-social-link:hover { border-color:var(--bb-primary); color:var(--bb-primary); transform:translateY(-2px); }
        .bb-social-link i { font-size:18px; }
        .bb-li i { color:#0a66c2; }
        .bb-gh i { color:#181717; }
        .bb-fb i { color:#1877f2; }

        .bb-empty { color:var(--bb-muted); font-size:14px; font-style:italic; }

        .bb-modal-input {
            border:1.5px solid var(--bb-line); border-radius:10px; padding:10px 13px;
            font-size:14px; width:100%; transition:border-color .15s; background:#fff;
        }
        .bb-modal-input:focus { outline:none; border-color:var(--bb-primary); }
        .bb-modal-label { font-size:13px; font-weight:600; color:var(--bb-ink); margin-bottom:6px; display:block; }

        /* Edit modal: scrollable body + sticky footer (Fix #3) */
        #editProfileModal .modal-dialog { max-height: 92vh; }
        #editProfileModal .modal-content { max-height: 92vh; background:#fff; border-radius:16px; }
        #editProfileModal .modal-header { background:#fff; border-radius:16px 16px 0 0; }
        #editProfileModal .modal-body { overflow-y: auto; background:#fff; }
        #editProfileModal .modal-footer {
            position: sticky; bottom: 0; background: #fff; z-index: 5;
            border-top: 1px solid var(--bb-line); border-radius:0 0 16px 16px;
        }

        /* Cropper modal */
        #cropModal .modal-body { background:#1e1f24; }
        .bb-crop-stage { max-height:60vh; }
        .bb-crop-stage img { max-width:100%; display:block; }

        @media (max-width:768px) {
            .bb-info-grid { grid-template-columns:1fr; }
            .bb-cover { height:180px; }
            .bb-head-card { padding:0 18px 20px; margin-top:-50px; }
            .bb-avatar-wrap, .bb-avatar-lg { width:110px; height:110px; }
            .bb-avatar-lg { font-size:42px; }
            .bb-avatar-wrap { margin-top:-55px; }
            .bb-name { font-size:22px; }
        }

/* ===== FEED + TABS CSS (reused from dashboard) ===== */
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
.bb-avatar-img { width:100%; height:100%; border-radius:50%; object-fit:cover; }
.bb-avatar { overflow:hidden; }
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

/* Single image/video — natural ratio, capped height */
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

/* Multi-media grids — FIXED heights, object-fit cover (no black gaps) */
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


/* ==========================================
   PROFILE TABS (Facebook-style)
   ========================================== */
.bb-tabs-bar {
    background:#fff; border-radius:14px; box-shadow:var(--bb-shadow);
    margin-top:18px; padding:4px; display:flex; gap:4px;
}
.bb-tab-btn {
    flex:1; border:none; background:transparent; cursor:pointer;
    padding:12px 10px; border-radius:10px; font-size:14.5px; font-weight:600;
    color:var(--bb-muted); display:flex; align-items:center; justify-content:center; gap:7px;
    transition:all .15s ease;
}
.bb-tab-btn i { font-size:16px; }
.bb-tab-btn:hover { background:var(--bb-bg); color:var(--bb-ink); }
.bb-tab-btn.active { background:var(--bb-primary-soft); color:var(--bb-primary); }

.bb-tab-panel { display:none; }
.bb-tab-panel.active { display:block; animation:bbFade .25s ease; }
@keyframes bbFade { from{opacity:0; transform:translateY(6px);} to{opacity:1; transform:translateY(0);} }

.bb-tab-loader { text-align:center; padding:50px 0; color:var(--bb-muted); }

/* Posts tab feed width fix */
.bb-profile-feed { max-width:680px; margin:0 auto; }

/* Photos & Videos grid */
.bb-media-grid {
    display:grid; grid-template-columns:repeat(3,1fr); gap:6px;
}
.bb-media-grid-item {
    position:relative; aspect-ratio:1/1; overflow:hidden; border-radius:10px;
    cursor:pointer; background:#000;
}
.bb-media-grid-item img, .bb-media-grid-item video {
    width:100%; height:100%; object-fit:cover; display:block;
    transition:transform .25s ease;
}
.bb-media-grid-item:hover img, .bb-media-grid-item:hover video { transform:scale(1.05); }
.bb-media-grid-play {
    position:absolute; top:50%; left:50%; transform:translate(-50%,-50%);
    width:44px; height:44px; border-radius:50%; background:rgba(0,0,0,.6);
    display:flex; align-items:center; justify-content:center; color:#fff; font-size:20px;
    pointer-events:none;
}
.bb-media-empty, .bb-posts-empty {
    text-align:center; padding:60px 20px; color:var(--bb-muted);
    background:#fff; border-radius:16px; box-shadow:var(--bb-shadow); margin-top:18px;
}
.bb-media-empty i, .bb-posts-empty i { font-size:42px; display:block; margin-bottom:12px; opacity:.5; }

@media (max-width:768px) {
    .bb-tab-btn { font-size:13px; padding:10px 6px; }
    .bb-tab-btn span { display:none; }
    .bb-media-grid { grid-template-columns:repeat(3,1fr); gap:4px; }
}

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

.bb-friend-btn{display:inline-flex;align-items:center;gap:7px;font-size:.88rem;font-weight:700;padding:9px 18px;border-radius:10px;border:none;cursor:pointer;transition:all .15s ease;margin-right:6px;}
.bb-friend-add{background:#4f46e5;color:#fff;}.bb-friend-add:hover{background:#4338ca;}
.bb-friend-pending{background:#eef2ff;color:#4f46e5;border:1.5px solid #c7d2fe;}
.bb-friend-pending:hover{background:#fef2f2;color:#dc2626;}
.bb-friend-cancel-hint{font-size:.78rem;opacity:.75;}
.bb-friend-accept{background:#059669;color:#fff;}.bb-friend-accept:hover{background:#047857;}
.bb-friend-decline{background:#f3f4f8;color:#374151;}.bb-friend-decline:hover{background:#fee2e2;color:#dc2626;}
.bb-friend-already{background:#f3f4f8;color:#374151;}.bb-friend-already:hover{background:#e5e7eb;}
.bb-friend-blocked{background:#fee2e2;color:#dc2626;border:1.5px solid #fca5a5;}
.bb-mutual-count{font-size:.82rem;color:#6b7280;margin-bottom:8px;display:flex;align-items:center;gap:5px;}
.bb-mutual-count i{color:#4f46e5;}

/* ===== Mutual Friends Circles (profile page) ===== */
.bb-mutual-section { margin-top: 14px; }
.bb-mutual-label {
    font-size: 13px; color: #6b7280; font-weight: 600;
    margin-bottom: 8px; display: flex; align-items: center; gap: 5px;
}
.bb-mutual-circles { display: flex; align-items: center; }
.bb-mutual-circle-wrap {
    position: relative;
    margin-right: -8px;
    z-index: 1;
    transition: z-index 0s;
}
.bb-mutual-circle-wrap:hover { z-index: 10; }
.bb-mutual-circle {
    width: 40px; height: 40px; border-radius: 50%; border: 3px solid #fff;
    overflow: hidden; background: linear-gradient(135deg,#4f46e5,#7c73f0);
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 15px; text-decoration: none;
    box-shadow: 0 2px 6px rgba(0,0,0,.12);
    transition: transform .15s ease; cursor: pointer;
}
.bb-mutual-circle:hover { transform: scale(1.12) translateY(-3px); }
.bb-mutual-circle img { width: 100%; height: 100%; object-fit: cover; }
.bb-mutual-more {
    width: 40px; height: 40px; border-radius: 50%; border: 3px solid #fff;
    background: #e4e6eb; color: #374151; display: flex; align-items: center;
    justify-content: center; font-size: 11px; font-weight: 700; cursor: pointer;
    margin-left: 4px; box-shadow: 0 2px 6px rgba(0,0,0,.12);
    flex-shrink: 0;
}
.bb-mutual-more:hover { background: #d8dadf; }

/* Hover card */
.bb-mutual-hovercard {
    display: none; position: absolute; bottom: calc(100% + 12px); left: 50%;
    transform: translateX(-50%); background: #fff; border-radius: 14px;
    padding: 14px; z-index: 999; width: 200px;
    box-shadow: 0 8px 28px rgba(16,24,40,.14); border: 1px solid #eceef1;
    pointer-events: none;
}
.bb-mutual-hovercard::after {
    content: ''; position: absolute; top: 100%; left: 50%; transform: translateX(-50%);
    border: 7px solid transparent; border-top-color: #fff;
}
.bb-mutual-circle-wrap:hover .bb-mutual-hovercard { display: block; animation: hcIn .15s ease; }
@keyframes hcIn { from{opacity:0;transform:translateX(-50%) translateY(6px);} to{opacity:1;transform:translateX(-50%) translateY(0);} }
.bb-hc-avatar {
    width: 40px; height: 40px; border-radius: 50%; overflow: hidden;
    background: linear-gradient(135deg,#4f46e5,#7c73f0); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 16px; flex-shrink: 0;
}
.bb-hc-avatar img { width: 100%; height: 100%; object-fit: cover; }

    </style>
</head>
<body>

@if($isAdminReviewMode ?? false)
    <nav style="background:#0f172a;padding:.7rem 1.2rem;position:sticky;top:0;z-index:100;box-shadow:0 2px 8px rgba(0,0,0,.15);">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2 text-white">
                <i class="bi bi-shield-lock-fill text-warning"></i>
                <span class="fw-bold" style="font-size:14px;">
                    Admin Review Mode — Viewing: {{ $user->name }}
                </span>
            </div>
            <a href="{{ route('admin.dashboard') }}"
               style="background:#1e293b;color:#fff;border-radius:8px;padding:7px 14px;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </nav>
@else
    @include('partials.inner-navbar')
@endif

@php
    $role = $user->role;
    $isAlumni = $role === 'alumni';
    $isTeacher = $role === 'teacher';
    $isStudent = $role === 'student';
    $skillsList = is_array($user->skills) ? $user->skills : [];
@endphp

<div class="bb-profile-wrap">

    {{-- Cover Photo --}}
    <div class="bb-cover">
        @if($user->cover_photo)
            <img src="{{ asset('storage/'.$user->cover_photo) }}" alt="cover" id="coverImg" style="cursor:zoom-in;" onclick="openImageView(this.src)">
        @endif
        @if($isOwner)
            <button class="bb-cover-edit" onclick="document.getElementById('coverInput').click()">
                <i class="bi bi-camera-fill"></i> Edit cover
            </button>
            <input type="file" id="coverInput" class="d-none" accept="image/*">
        @endif
    </div>

    {{-- Header Card --}}
    <div class="bb-head-card">
        <div class="d-flex flex-wrap justify-content-between align-items-end">
            <div class="d-flex flex-wrap align-items-end gap-3">
                <div class="bb-avatar-wrap">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/'.$user->profile_picture) }}" class="bb-avatar-lg" id="avatarImg" alt="avatar" style="cursor:zoom-in;" onclick="openImageView(this.src)">
                    @else
                        <div class="bb-avatar-lg" id="avatarImg">{{ strtoupper(substr($user->name,0,1)) }}</div>
                    @endif
                    @if($isOwner)
                        <button class="bb-avatar-edit" onclick="document.getElementById('avatarInput').click()" title="Change photo">
                            <i class="bi bi-camera-fill"></i>
                        </button>
                        <input type="file" id="avatarInput" class="d-none" accept="image/*">
                    @endif
                </div>
                <div class="pb-2">
                    <h1 class="bb-name" id="displayName">{{ $user->name }}</h1>

                    {{-- নামের নিচে role badge (Teacher / Alumni / Student) --}}
                    <div class="mb-2">
                        @if($isTeacher)
                            <span class="bb-role-pill bb-role-teacher"><i class="bi bi-easel2-fill"></i> Teacher</span>
                        @elseif($isAlumni)
                            <span class="bb-role-pill bb-role-alumni"><i class="bi bi-mortarboard-fill"></i> Alumni</span>
                        @else
                            <span class="bb-role-pill bb-role-student"><i class="bi bi-backpack-fill"></i> Student</span>
                        @endif
                    </div>

                    @php
                        $latestEdu = $user->latestEducation;
                        $curJob = $user->currentJob;
                    @endphp
                    <p class="bb-headline">
                        @if($curJob)
                            <span class="fw-semibold" style="color:var(--bb-ink);">{{ $curJob->designation }}</span> @ {{ $curJob->company }}
                        @elseif($isTeacher)
                            Educator & Researcher
                        @elseif($isAlumni)
                            Alumni
                        @else
                            Currently a Student
                        @endif
                    </p>
                    @if($latestEdu || $user->department || $user->session)
                        <p class="bb-headline-sub">
                            <i class="bi bi-mortarboard"></i>
                            {{ $latestEdu ? $latestEdu->institution : ($user->department ?? '') }}
                            @if($user->session) · Session {{ $user->session }} @endif
                        </p>
                    @endif
                </div>
            </div>

            
            {{-- friend start --}}
            @if($isOwner)
                <div class="pb-2">
                    <button class="bb-edit-profile-btn" onclick="openEditModal()">
                        <i class="bi bi-pencil-fill"></i> Edit Profile
                    </button>
                    <a href="{{ route('muted.index') }}" class="bb-edit-profile-btn" style="background:#fff;color:#4f46e5;border:1.5px solid #4f46e5;text-decoration:none;">
                        <i class="bi bi-volume-mute-fill"></i> Muted Accounts
                    </a>
                </div>
            @elseif($isAdminReviewMode ?? false)
                {{-- Admin Review Mode — Add Friend ও Report দুটোই হাইড, শুধু ভিউ --}}
            @else
                {{-- অন্যের profile — friend + report button এখানে --}}
                <div class="pb-2">
                @php
                    $meId         = Auth::id();
                    $friendStatus = \App\Models\Friendship::statusWith($meId, $user->id);
                    $mutualCount  = \App\Models\Friendship::mutualCount($meId, $user->id);
                @endphp

                    @if($mutualCount > 0)
                        <div class="bb-mutual-count">
                            <i class="bi bi-people-fill"></i>
                            {{ $mutualCount }} mutual friend{{ $mutualCount > 1 ? 's' : '' }}
                        </div>
                    @endif

                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div id="friendBtnWrap-{{ $user->id }}">
                            @if($friendStatus === 'none')
                                <button class="bb-friend-btn bb-friend-add"
                                        onclick="friendAction('send', {{ $user->id }}, this)">
                                    <i class="bi bi-person-plus-fill"></i> Add Friend
                                </button>

                            @elseif($friendStatus === 'pending_sent')
                                <button class="bb-friend-btn bb-friend-pending"
                                        onclick="friendAction('cancel', {{ $user->id }}, this)">
                                    <i class="bi bi-person-check-fill"></i> Request Sent
                                    <span class="bb-friend-cancel-hint">· Cancel</span>
                                </button>

                            @elseif($friendStatus === 'pending_received')
                                <button class="bb-friend-btn bb-friend-accept"
                                        onclick="friendAction('accept', {{ $user->id }}, this)">
                                    <i class="bi bi-check-lg"></i> Accept
                                </button>
                                <button class="bb-friend-btn bb-friend-decline"
                                        onclick="friendAction('decline', {{ $user->id }}, this)">
                                    <i class="bi bi-x-lg"></i> Decline
                                </button>

                            @elseif($friendStatus === 'accepted')
                                <div class="dropdown d-inline-block">
                                    <button class="bb-friend-btn bb-friend-already dropdown-toggle"
                                            data-bs-toggle="dropdown">
                                        <i class="bi bi-people-fill"></i> Friends
                                    </button>
                                    <ul class="dropdown-menu shadow border-0 rounded-3">
                                        <li>
                                            <button class="dropdown-item text-danger py-2"
                                                    onclick="friendAction('unfriend', {{ $user->id }}, this)">
                                                <i class="bi bi-person-x me-2"></i> Unfriend
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item py-2"
                                                    onclick="friendAction('block', {{ $user->id }}, this)">
                                                <i class="bi bi-slash-circle me-2"></i> Block
                                            </button>
                                        </li>
                                    </ul>
                                </div>

                            @elseif($friendStatus === 'blocked')
                                <button class="bb-friend-btn bb-friend-blocked"
                                        onclick="friendAction('unblock', {{ $user->id }}, this)">
                                    <i class="bi bi-slash-circle"></i> Blocked · Unblock
                                </button>
                            @endif
                        </div>

                        @php $alreadyReportedUser = \App\Models\Report::isReportedByMe(Auth::id(), 'user', $user->id); @endphp
                        <div id="reportBtnWrap-{{ $user->id }}">
                            @if($alreadyReportedUser)
                                <span class="badge bg-danger-subtle text-danger border" style="font-size:.72rem;font-weight:700;padding:8px 14px;border-radius:10px;">
                                    <i class="bi bi-flag-fill me-1"></i> You reported this User
                                </span>
                            @else
                                <button class="bb-friend-btn" style="background:#fff;color:#6b7280;border:1.5px solid #eceef1;"
                                        onclick="bbOpenReport('user', {{ $user->id }}, '{{ e($user->name) }}')">
                                    <i class="bi bi-flag"></i> Report
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
                    </div>

                    <div class="bb-stat-row">
                        <div class="bb-stat"><b>{{ $postCount }}</b><span>Posts</span></div>
                        <div class="bb-stat"><b>0</b><span>Connections</span></div>
                    </div>
            {{-- friend end --}}
           
           {{-- Mutual Friends Circles --}}
        @if(!$isOwner)
        @php
            $meId2        = Auth::id();
            $mutualCount  = \App\Models\Friendship::mutualCount($meId2, $user->id);
            $mutualsList  = $mutualCount > 0 ? \App\Models\Friendship::mutualFriends($meId2, $user->id) : collect();
        @endphp
        @if($mutualCount > 0)
        <div class="bb-mutual-section">
            <div class="bb-mutual-label">
                <i class="bi bi-people-fill text-primary"></i>
                {{ $mutualCount }} mutual friend{{ $mutualCount > 1 ? 's' : '' }}
            </div>
            <div class="bb-mutual-circles">
                @foreach($mutualsList->take(6) as $m)
                <div class="bb-mutual-circle-wrap">
                    <a href="{{ route('profile.view', $m) }}" class="bb-mutual-circle">
                        @if($m->profile_picture)
                            <img src="{{ asset('storage/'.$m->profile_picture) }}" alt="{{ $m->name }}">
                        @else
                            {{ strtoupper(substr($m->name, 0, 1)) }}
                        @endif
                    </a>
                    {{-- Hover card --}}
                    <div class="bb-mutual-hovercard">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="bb-hc-avatar">
                                @if($m->profile_picture)
                                    <img src="{{ asset('storage/'.$m->profile_picture) }}" alt="{{ $m->name }}">
                                @else
                                    {{ strtoupper(substr($m->name, 0, 1)) }}
                                @endif
                            </div>
                            <div>
                                <div style="font-size:13px;font-weight:700;color:#1e1f24;">{{ $m->name }}</div>
                                <div style="font-size:11px;color:#6b7280;">
                                    {{ ucfirst($m->role) }}@if($m->department) · {{ $m->department }}@endif
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('profile.view', $m) }}"
                           style="display:block;text-align:center;background:#eef2ff;color:#4f46e5;font-size:12px;font-weight:600;border-radius:8px;padding:6px;">
                            View Profile
                        </a>
                    </div>
                </div>
                @endforeach

                @if($mutualCount > 6)
                <div class="bb-mutual-more"
                     onclick="showMutualModal()">
                    +{{ $mutualCount - 6 }}
                </div>
                @endif
            </div>
        </div>
        @endif
        @endif
    </div>

    {{-- ==================== PROFILE TABS ==================== --}}
    <div class="bb-tabs-bar">
        <button class="bb-tab-btn" data-tab="details" onclick="switchTab('details')">
            <i class="bi bi-person-vcard"></i> <span>{{ $isStudent ? 'Student Details' : 'Profile Details' }}</span>
        </button>
        <button class="bb-tab-btn active" data-tab="posts" onclick="switchTab('posts')">
            <i class="bi bi-grid-1x2-fill"></i> <span>All Posts</span>
        </button>
        <button class="bb-tab-btn" data-tab="media" onclick="switchTab('media')">
            <i class="bi bi-images"></i> <span>Photos & Videos</span>
        </button>
    </div>

    {{-- ===== TAB 1: DETAILS ===== --}}
    <div class="bb-tab-panel" id="tab-details">

    {{-- About --}}
    <div class="bb-card">
        <h2 class="bb-card-title"><i class="bi bi-person-lines-fill"></i> About</h2>
        @if($user->bio)
            <p class="bb-bio-text">{{ $user->bio }}</p>
        @else
            <p class="bb-empty">{{ $isOwner ? 'Add a short bio to tell people about yourself.' : 'No bio added yet.' }}</p>
        @endif
    </div>

    {{-- Academic & Contact (teacher হলে শুধু Contact, academic field বাদ) --}}
    <div class="bb-card">
        <h2 class="bb-card-title"><i class="bi bi-mortarboard-fill"></i> {{ $isTeacher ? 'Contact Information' : 'Academic & Contact' }}</h2>
        <div class="bb-info-grid">
            @if(!$isTeacher)
            <div class="bb-info-item">
                <div class="bb-info-icon"><i class="bi bi-building"></i></div>
                <div><p class="bb-info-label">Department</p><p class="bb-info-value">{{ $user->department ?? '—' }}</p></div>
            </div>
            <div class="bb-info-item">
                <div class="bb-info-icon"><i class="bi bi-calendar3"></i></div>
                <div><p class="bb-info-label">Session</p><p class="bb-info-value">{{ $user->session ?? '—' }}</p></div>
            </div>
            <div class="bb-info-item">
                <div class="bb-info-icon"><i class="bi bi-grid-3x3-gap"></i></div>
                <div><p class="bb-info-label">Section</p><p class="bb-info-value">{{ $user->section ?? '—' }}</p></div>
            </div>
            <div class="bb-info-item">
                <div class="bb-info-icon"><i class="bi bi-bar-chart-steps"></i></div>
                <div><p class="bb-info-label">Semester</p><p class="bb-info-value">{{ $user->semester ?? '—' }}</p></div>
            </div>
            @endif
            @if($isOwner)
            <div class="bb-info-item">
                <div class="bb-info-icon"><i class="bi bi-telephone-fill"></i></div>
                <div><p class="bb-info-label">Phone <span class="badge bg-secondary" style="font-size:9px;">Private</span></p>
                <p class="bb-info-value">{{ $user->phone ?? '—' }}</p></div>
            </div>
            @endif
            <div class="bb-info-item">
                <div class="bb-info-icon"><i class="bi bi-geo-alt-fill"></i></div>
                <div><p class="bb-info-label">Location</p><p class="bb-info-value">{{ $user->location ?? '—' }}</p></div>
            </div>
            @if($isOwner)
            <div class="bb-info-item">
                <div class="bb-info-icon"><i class="bi bi-envelope-fill"></i></div>
                <div><p class="bb-info-label">Email <span class="badge bg-secondary" style="font-size:9px;">Private</span></p>
                <p class="bb-info-value">{{ $user->email }}</p></div>
            </div>
            @endif
            <div class="bb-info-item">
                <div class="bb-info-icon"><i class="bi bi-heart-fill"></i></div>
                <div><p class="bb-info-label">Interests</p><p class="bb-info-value">{{ $user->interests ?? '—' }}</p></div>
            </div>
        </div>
    </div>

    {{-- Skills --}}
    <div class="bb-card">
        <h2 class="bb-card-title"><i class="bi bi-stars"></i> Skills</h2>
        @if(count($skillsList) > 0)
            <div class="bb-chips">
                @foreach($skillsList as $skill)
                    <span class="bb-chip">{{ $skill }}</span>
                @endforeach
            </div>
        @else
            <p class="bb-empty">{{ $isOwner ? 'Add your skills (e.g. PHP, Laravel, Python).' : 'No skills added yet.' }}</p>
        @endif
    </div>

    {{-- Social Links --}}
    <div class="bb-card">
        <h2 class="bb-card-title"><i class="bi bi-link-45deg"></i> Connect</h2>
        @if($user->linkedin_url || $user->github_url || $user->facebook_url)
            <div class="bb-socials">
                @if($user->linkedin_url)
                    <a href="{{ $user->linkedin_url }}" target="_blank" class="bb-social-link bb-li"><i class="bi bi-linkedin"></i> LinkedIn</a>
                @endif
                @if($user->github_url)
                    <a href="{{ $user->github_url }}" target="_blank" class="bb-social-link bb-gh"><i class="bi bi-github"></i> GitHub</a>
                @endif
                @if($user->facebook_url)
                    <a href="{{ $user->facebook_url }}" target="_blank" class="bb-social-link bb-fb"><i class="bi bi-facebook"></i> Facebook</a>
                @endif
            </div>
        @else
            <p class="bb-empty">{{ $isOwner ? 'Add your social links so people can connect with you.' : 'No links added yet.' }}</p>
        @endif
    </div>

    {{-- ===== EDUCATION ===== --}}
<div class="bb-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="bb-card-title m-0"><i class="bi bi-mortarboard"></i> Education</h2>
        @if($isOwner)
            <button class="bb-add-btn" onclick="openEduModal()"><i class="bi bi-plus-lg"></i> Add</button>
        @endif
    </div>
    <div id="eduList">
            @forelse($user->educations as $edu)
                <div class="bb-timeline-item" id="edu-{{ $edu->id }}">
                    <div class="bb-timeline-icon"><i class="bi bi-mortarboard-fill"></i></div>
                    <div class="bb-timeline-body">
                        <h6 class="bb-timeline-title">{{ $edu->degree }}{{ $edu->field ? ' · '.$edu->field : '' }}</h6>
                        <p class="bb-timeline-sub">{{ $edu->institution }}</p>
                        <p class="bb-timeline-meta">
                            {{ $edu->start_date ? $edu->start_date->format('M Y') : '' }}
                            @if($edu->start_date) - {{ $edu->is_current ? 'Present' : ($edu->end_date ? $edu->end_date->format('M Y') : '') }} @endif
                            {{ $edu->result ? ' · Result: '.$edu->result : '' }}
                        </p>
                    </div>
                    @if($isOwner)
                        <div class="bb-timeline-actions">
                            <button onclick="editEdu({{ $edu->id }})" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button onclick="deleteDetail('education', {{ $edu->id }}, 'edu-{{ $edu->id }}')" title="Delete" class="text-danger"><i class="bi bi-trash3"></i></button>
                        </div>
                    @endif
                </div>
            @empty
                <p class="bb-empty" id="eduEmpty">{{ $isOwner ? 'Add your education history (SSC, HSC, Bachelor...).' : 'No education added yet.' }}</p>
            @endforelse
        </div>
    </div>

    {{-- ===== EXPERIENCE ===== --}}
    <div class="bb-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="bb-card-title m-0"><i class="bi bi-briefcase"></i> Experience</h2>
            @if($isOwner)
                <button class="bb-add-btn" onclick="openExpModal()"><i class="bi bi-plus-lg"></i> Add</button>
            @endif
        </div>
        <div id="expList">
            @forelse($user->experiences as $exp)
                <div class="bb-timeline-item" id="exp-{{ $exp->id }}">
                    <div class="bb-timeline-icon"><i class="bi bi-briefcase-fill"></i></div>
                    <div class="bb-timeline-body">
                        <h6 class="bb-timeline-title">{{ $exp->designation }}</h6>
                        <p class="bb-timeline-sub">{{ $exp->company }}{{ $exp->employment_type ? ' · '.$exp->employment_type : '' }}</p>
                        <p class="bb-timeline-meta">
                            {{ $exp->start_date ? $exp->start_date->format('M Y') : '' }}
                            @if($exp->start_date) - {{ $exp->is_current ? 'Present' : ($exp->end_date ? $exp->end_date->format('M Y') : '') }} @endif
                            {{ $exp->location ? ' · '.$exp->location : '' }}
                        </p>
                        @if($exp->description)
                            <p class="bb-timeline-desc">{{ $exp->description }}</p>
                        @endif
                    </div>
                    @if($isOwner)
                        <div class="bb-timeline-actions">
                            <button onclick="editExp({{ $exp->id }})" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button onclick="deleteDetail('experience', {{ $exp->id }}, 'exp-{{ $exp->id }}')" title="Delete" class="text-danger"><i class="bi bi-trash3"></i></button>
                        </div>
                    @endif
                </div>
            @empty
                <p class="bb-empty" id="expEmpty">{{ $isOwner ? 'Add your work experience or internships.' : 'No experience added yet.' }}</p>
            @endforelse
        </div>
    </div>

    {{-- ===== CERTIFICATIONS ===== --}}
    <div class="bb-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="bb-card-title m-0"><i class="bi bi-patch-check"></i> Certifications & Training</h2>
            @if($isOwner)
                <button class="bb-add-btn" onclick="openCertModal()"><i class="bi bi-plus-lg"></i> Add</button>
            @endif
        </div>
        <div id="certList">
            @forelse($user->certifications as $cert)
                <div class="bb-timeline-item" id="cert-{{ $cert->id }}">
                    <div class="bb-timeline-icon"><i class="bi bi-patch-check-fill"></i></div>
                    <div class="bb-timeline-body">
                        <h6 class="bb-timeline-title">{{ $cert->title }}</h6>
                        <p class="bb-timeline-sub">{{ $cert->organization ?? '' }}</p>
                        <p class="bb-timeline-meta">
                            {{ $cert->issue_date ? $cert->issue_date->format('M Y') : '' }}
                            @if($cert->credential_url)
                                · <a href="{{ $cert->credential_url }}" target="_blank" class="bb-cred-link">Show credential</a>
                            @endif
                        </p>
                    </div>
                    @if($isOwner)
                        <div class="bb-timeline-actions">
                            <button onclick="editCert({{ $cert->id }})" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button onclick="deleteDetail('certification', {{ $cert->id }}, 'cert-{{ $cert->id }}')" title="Delete" class="text-danger"><i class="bi bi-trash3"></i></button>
                        </div>
                    @endif
                </div>
            @empty
                <p class="bb-empty" id="certEmpty">{{ $isOwner ? 'Add certifications or trainings you have completed.' : 'No certifications added yet.' }}</p>
            @endforelse
        </div>
    </div>

    {{-- ===== RESEARCH & PUBLICATIONS (Thesis / Project / Research) ===== --}}
    <div class="bb-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="bb-card-title m-0"><i class="bi bi-file-earmark-text"></i> Research & Publications</h2>
            @if($isOwner)
                <button class="bb-add-btn" onclick="openDocModal()"><i class="bi bi-plus-lg"></i> Add</button>
            @endif
        </div>
        <div id="docList">
            @forelse($user->documents as $doc)
                <div class="bb-doc-item" id="doc-{{ $doc->id }}">
                    <div class="bb-doc-icon bb-doc-{{ $doc->file_type }}">
                        <i class="bi {{ in_array($doc->file_type, ['pdf']) ? 'bi-file-earmark-pdf' : (in_array($doc->file_type, ['doc','docx','odt','rtf']) ? 'bi-file-earmark-word' : (in_array($doc->file_type, ['ppt','pptx']) ? 'bi-file-earmark-slides' : 'bi-file-earmark-text')) }}"></i>
                    </div>
                    <div class="bb-doc-body">
                        <div class="bb-doc-toprow">
                            <span class="bb-doc-type">{{ $doc->type }}</span>
                            @if($doc->publication_year)<span class="bb-doc-year">· {{ $doc->publication_year }}</span>@endif
                        </div>
                        <h6 class="bb-doc-title">{{ $doc->title }}</h6>
                        @if($doc->topic)<p class="bb-doc-topic"><i class="bi bi-tag"></i> {{ $doc->topic }}</p>@endif
                        @if($doc->description)<p class="bb-doc-desc">{{ $doc->description }}</p>@endif
                        <div class="bb-doc-filerow">
                            <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="bb-doc-file">
                                <i class="bi bi-paperclip"></i> {{ $doc->file_name }}
                            </a>
                            <span class="bb-doc-size">{{ $doc->readable_size }}</span>
                        </div>
                    </div>
                    @if($isOwner)
                        <div class="bb-timeline-actions">
                            <button onclick="editDoc({{ $doc->id }})" title="Edit"><i class="bi bi-pencil"></i></button>
                            <button onclick="deleteDoc({{ $doc->id }}, 'doc-{{ $doc->id }}')" title="Delete" class="text-danger"><i class="bi bi-trash3"></i></button>
                        </div>
                    @endif
                </div>
            @empty
                <p class="bb-empty" id="docEmpty">{{ $isOwner ? 'Upload your thesis, project, or research papers (PDF, Word, PPT).' : 'No research or publications added yet.' }}</p>
            @endforelse
        </div>
    </div>

    {{-- ===== MY JOB POSTS (alumni only) ===== --}}
    @if($isAlumni || $isTeacher)
    <div class="bb-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="bb-card-title m-0"><i class="bi bi-briefcase"></i> Job Posts {{ $totalJobCount ? '· '.$totalJobCount : '' }}</h2>
            @if($totalJobCount > 5)
                <a href="{{ route('jobs.all') }}" class="bb-add-btn" style="text-decoration:none;font-size:13px;">View all ({{ $totalJobCount }})</a>
            @endif
        </div>
        <div id="myJobList">

            @forelse($user->jobPosts as $job)
            @include('partials.myjob-item', ['job' => $job])
        @empty
            <p class="bb-empty" id="myJobEmpty">{{ $isOwner ? 'You have not posted any jobs yet. Use "Post A Job" from your feed.' : 'No jobs posted yet.' }}</p>
        @endforelse
         {{-- ৫টার বেশি job থাকলে View all বাটন --}}
        @if($totalJobCount > 5)
            <a href="{{ route('jobs.all') }}" class="bb-myjob-viewall">
                <i class="bi bi-grid"></i> View all {{ $totalJobCount }} jobs
            </a>
        @endif
        </div>
    </div>
    @endif 

    {{-- DELETE AND DEACTIVATE ACCOUNT START --}}
    {{-- Danger Zone --}}
<div class="card mt-4" style="border: 1px solid #fca5a5; border-radius: 16px; overflow: hidden;">
    <div class="card-header" style="background: #fef2f2; border-bottom: 1px solid #fca5a5; padding: 16px 20px;">
        <h6 class="fw-bold mb-0 text-danger" style="font-size: 0.88rem;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Danger Zone
        </h6>
    </div>
    <div class="card-body p-4">

        {{-- Deactivate --}}
        <div class="d-flex align-items-start justify-content-between gap-3 pb-4" style="border-bottom: 1px solid #f1f5f9;">
            <div>
                <div class="fw-bold text-dark" style="font-size: 0.88rem;">Deactivate Account</div>
                <div class="text-muted" style="font-size: 0.78rem; margin-top: 4px;">
                    Your profile will be hidden. You can reactivate anytime by logging in.
                    Messages will be disabled while deactivated.
                </div>
            </div>
            <button onclick="showDeactivateModal()" class="btn btn-outline-warning btn-sm fw-600" style="white-space:nowrap; font-size: 0.8rem;">
                <i class="bi bi-pause-circle me-1"></i> Deactivate
            </button>
        </div>

        {{-- Delete --}}
        <div class="d-flex align-items-start justify-content-between gap-3 pt-4">
            <div>
                <div class="fw-bold text-danger" style="font-size: 0.88rem;">Delete Account</div>
                <div class="text-muted" style="font-size: 0.78rem; margin-top: 4px;">
                    Your account will be scheduled for deletion. You have <strong>30 days</strong> to log in
                    and cancel. After that, all your data will be permanently removed.
                </div>
            </div>
            <button onclick="showDeleteModal()" class="btn btn-danger btn-sm fw-600" style="white-space:nowrap; font-size: 0.8rem;">
                <i class="bi bi-trash3 me-1"></i> Delete Account
            </button>
        </div>

    </div>
</div>

{{-- Deactivate Modal --}}
<div class="modal fade" id="deactivateModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header" style="border-bottom: 1px solid #f1f5f9;">
                <h5 class="modal-title fw-bold" style="font-size: 1rem;">
                    <i class="bi bi-pause-circle text-warning me-2"></i> Deactivate Account
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning" style="border-radius: 10px; font-size: 0.82rem;">
                    <i class="bi bi-info-circle me-1"></i>
                    Your profile, posts, and connections will be hidden. You can reactivate anytime by logging back in.
                </div>
                <form method="POST" action="{{ route('account.deactivate') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 0.82rem;">Confirm your password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                        @error('password') <div class="text-danger" style="font-size:0.75rem;">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn btn-warning w-100 fw-bold">
                        <i class="bi bi-pause-circle me-1"></i> Yes, Deactivate My Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header" style="border-bottom: 1px solid #f1f5f9;">
                <h5 class="modal-title fw-bold text-danger" style="font-size: 1rem;">
                    <i class="bi bi-trash3 me-2"></i> Delete Account
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-danger" style="border-radius: 10px; font-size: 0.82rem;">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    <strong>This cannot be undone after 30 days.</strong> Your account, posts, messages,
                    and all data will be permanently deleted.
                </div>
                <p style="font-size: 0.82rem; color: #64748b;">
                    You have <strong>30 days</strong> to log in and cancel the deletion.
                </p>
                <form method="POST" action="{{ route('account.delete') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size: 0.82rem;">Confirm your password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                        @error('password') <div class="text-danger" style="font-size:0.75rem;">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn btn-danger w-100 fw-bold">
                        <i class="bi bi-trash3 me-1"></i> Yes, Schedule Account Deletion
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
    {{-- DELETE END --}}

    </div>{{-- /TAB 1: Details --}}

    

    {{-- ===== TAB 2: ALL POSTS ===== --}}
    <div class="bb-tab-panel active" id="tab-posts">
        <div class="bb-profile-feed mt-3">

            {{-- Create Post Box (only owner) --}}
            @if($isOwner)
            <div class="create-post-box mb-3">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <a href="{{ route('profile.show') }}" class="create-post-avatar" style="text-decoration:none;" title="Your profile">
                        @if(Auth::user()->profile_picture)
                            <img src="{{ asset('storage/'.Auth::user()->profile_picture) }}" alt="me" class="cpa-img">
                        @else
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        @endif
                    </a>
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
            @endif

            {{-- Posts load here (AJAX) --}}
            <div id="profilePostsContainer">
                <div class="bb-tab-loader" id="postsLoader">
                    <div class="spinner-border text-primary"></div>
                    <div class="small mt-2">Loading posts...</div>
                </div>
            </div>
        </div>
    </div>{{-- /TAB 2 --}}

    {{-- ===== TAB 3: PHOTOS & VIDEOS ===== --}}
    <div class="bb-tab-panel" id="tab-media">
        <div id="profileMediaContainer">
            <div class="bb-tab-loader" id="mediaLoader">
                <div class="spinner-border text-primary"></div>
                <div class="small mt-2">Loading media...</div>
            </div>
        </div>
    </div>{{-- /TAB 3 --}}

</div>

{{-- ==================== EDIT PROFILE MODAL ==================== --}}
@if($isOwner)
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold">Edit Profile</h5>
                <button type="button" class="btn-close" id="editModalCloseBtn"></button>
            </div>
            <form id="editProfileForm">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="bb-modal-label">Full Name *</label>
                            <input type="text" name="name" class="bb-modal-input" value="{{ $user->name }}" required>
                        </div>
                        <div class="col-12">
                            <label class="bb-modal-label">Bio / About</label>
                            <textarea name="bio" class="bb-modal-input" rows="3" placeholder="Tell people about yourself...">{{ $user->bio }}</textarea>
                        </div>
                        @if(!$isTeacher)
                        <div class="col-md-6">
                            <label class="bb-modal-label">Department</label>
                            <input type="text" name="department" class="bb-modal-input" value="{{ $user->department }}" placeholder="e.g. CSE">
                        </div>
                        <div class="col-md-6">
                            <label class="bb-modal-label">Session</label>
                            <input type="text" name="session" class="bb-modal-input" value="{{ $user->session }}" placeholder="e.g. 2020-21">
                        </div>
                        <div class="col-md-6">
                            <label class="bb-modal-label">Section</label>
                            <input type="text" name="section" class="bb-modal-input" value="{{ $user->section }}" placeholder="e.g. 27M1">
                        </div>
                        <div class="col-md-6">
                            <label class="bb-modal-label">Semester / Year</label>
                            <input type="text" name="semester" class="bb-modal-input" value="{{ $user->semester }}" placeholder="e.g. 7th">
                        </div>
                        @endif
                        <div class="col-md-6">
                            <label class="bb-modal-label">Phone</label>
                            <input type="text" name="phone" class="bb-modal-input" value="{{ $user->phone }}" placeholder="e.g. 01XXXXXXXXX">
                        </div>
                        <div class="col-md-6">
                            <label class="bb-modal-label">Location</label>
                            <input type="text" name="location" class="bb-modal-input" value="{{ $user->location }}" placeholder="e.g. Dhaka, Bangladesh">
                        </div>
                        <div class="col-12">
                            <label class="bb-modal-label">Skills <span class="text-muted fw-normal">(comma separated)</span></label>
                            <input type="text" name="skills" class="bb-modal-input" value="{{ is_array($user->skills) ? implode(', ', $user->skills) : '' }}" placeholder="e.g. PHP, Laravel, Python">
                        </div>
                        <div class="col-12">
                            <label class="bb-modal-label">Interests</label>
                            <input type="text" name="interests" class="bb-modal-input" value="{{ $user->interests }}" placeholder="e.g. Web Development, AI, Football">
                        </div>
                        <div class="col-md-4">
                            <label class="bb-modal-label">LinkedIn URL</label>
                            <input type="url" name="linkedin_url" class="bb-modal-input" value="{{ $user->linkedin_url }}" placeholder="https://...">
                        </div>
                        <div class="col-md-4">
                            <label class="bb-modal-label">GitHub URL</label>
                            <input type="url" name="github_url" class="bb-modal-input" value="{{ $user->github_url }}" placeholder="https://...">
                        </div>
                        <div class="col-md-4">
                            <label class="bb-modal-label">Facebook URL</label>
                            <input type="url" name="facebook_url" class="bb-modal-input" value="{{ $user->facebook_url }}" placeholder="https://...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" id="editModalCancelBtn">Cancel</button>
                    <button type="submit" id="saveProfileBtn" class="bb-edit-profile-btn">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ==================== DETAIL MODALS (owner only) ==================== --}}
@if($isOwner)

{{-- Education Modal --}}
<div class="modal fade" id="eduModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="eduModalTitle">Add Education</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="eduForm">
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="edu_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="bb-modal-label">Degree / Level *</label>
                            <input type="text" name="degree" id="edu_degree" class="bb-modal-input" placeholder="e.g. SSC, HSC, BSc" required>
                        </div>
                        <div class="col-md-6">
                            <label class="bb-modal-label">Field / Group</label>
                            <input type="text" name="field" id="edu_field" class="bb-modal-input" placeholder="e.g. Science, CSE">
                        </div>
                        <div class="col-12">
                            <label class="bb-modal-label">Institution *</label>
                            <input type="text" name="institution" id="edu_institution" class="bb-modal-input" placeholder="e.g. Dhaka College" required>
                        </div>
                        <div class="col-md-6">
                            <label class="bb-modal-label">Result / CGPA</label>
                            <input type="text" name="result" id="edu_result" class="bb-modal-input" placeholder="e.g. 5.00 / 3.75">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_current" id="edu_is_current" value="1" onchange="toggleEduEnd()">
                                <label class="form-check-label" for="edu_is_current" style="font-size:13px;">Currently studying here</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="bb-modal-label">Start Date</label>
                            <input type="date" name="start_date" id="edu_start_date" class="bb-modal-input">
                        </div>
                        <div class="col-md-6" id="edu_end_wrap">
                            <label class="bb-modal-label">End Date</label>
                            <input type="date" name="end_date" id="edu_end_date" class="bb-modal-input">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="bb-edit-profile-btn" id="eduSaveBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Experience Modal --}}
<div class="modal fade" id="expModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="expModalTitle">Add Experience</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="expForm">
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="exp_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="bb-modal-label">Designation *</label>
                            <input type="text" name="designation" id="exp_designation" class="bb-modal-input" placeholder="e.g. Software Engineer" required>
                        </div>
                        <div class="col-md-6">
                            <label class="bb-modal-label">Company *</label>
                            <input type="text" name="company" id="exp_company" class="bb-modal-input" placeholder="e.g. Tech Soft BD" required>
                        </div>
                        <div class="col-md-6">
                            <label class="bb-modal-label">Employment Type</label>
                            <select name="employment_type" id="exp_employment_type" class="bb-modal-input">
                                <option value="">Select...</option>
                                <option>Full-time</option>
                                <option>Part-time</option>
                                <option>Internship</option>
                                <option>Freelance</option>
                                <option>Contract</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="bb-modal-label">Location</label>
                            <input type="text" name="location" id="exp_location" class="bb-modal-input" placeholder="e.g. Dhaka / Remote">
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_current" id="exp_is_current" onchange="toggleExpEnd()">
                                <label class="form-check-label" for="exp_is_current" style="font-size:13px;">I currently work here</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="bb-modal-label">Start Date</label>
                            <input type="date" name="start_date" id="exp_start_date" class="bb-modal-input">
                        </div>
                        <div class="col-md-6" id="exp_end_wrap">
                            <label class="bb-modal-label">End Date</label>
                            <input type="date" name="end_date" id="exp_end_date" class="bb-modal-input">
                        </div>
                        <div class="col-12">
                            <label class="bb-modal-label">Description</label>
                            <textarea name="description" id="exp_description" class="bb-modal-input" rows="3" placeholder="What did you do there?"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="bb-edit-profile-btn" id="expSaveBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Certification Modal --}}
<div class="modal fade" id="certModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="certModalTitle">Add Certification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="certForm">
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="cert_id">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="bb-modal-label">Title *</label>
                            <input type="text" name="title" id="cert_title" class="bb-modal-input" placeholder="e.g. Laravel Certified Developer" required>
                        </div>
                        <div class="col-md-6">
                            <label class="bb-modal-label">Issuing Organization</label>
                            <input type="text" name="organization" id="cert_organization" class="bb-modal-input" placeholder="e.g. Udemy">
                        </div>
                        <div class="col-md-6">
                            <label class="bb-modal-label">Issue Date</label>
                            <input type="date" name="issue_date" id="cert_issue_date" class="bb-modal-input">
                        </div>
                        <div class="col-12">
                            <label class="bb-modal-label">Credential URL</label>
                            <input type="url" name="credential_url" id="cert_credential_url" class="bb-modal-input" placeholder="https://...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="bb-edit-profile-btn" id="certSaveBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- Document Modal (Thesis/Project/Research) --}}
<div class="modal fade" id="docModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="docModalTitle">Add Research / Publication</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="docForm">
                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="doc_id">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="bb-modal-label">Title *</label>
                            <input type="text" name="title" id="doc_title" class="bb-modal-input" placeholder="e.g. Smart Attendance System using Face Recognition" required>
                        </div>
                        <div class="col-md-5">
                            <label class="bb-modal-label">Type *</label>
                            <select name="type" id="doc_type" class="bb-modal-input" required>
                                <option>Thesis</option>
                                <option>Project</option>
                                <option>Research Paper</option>
                                <option>Conference Paper</option>
                                <option>Journal Article</option>
                                <option>Other</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="bb-modal-label">Topic / Subject <span class="text-muted" style="font-weight:400;">(helps search)</span></label>
                            <input type="text" name="topic" id="doc_topic" class="bb-modal-input" placeholder="e.g. Machine Learning, IoT, Cybersecurity">
                        </div>
                        <div class="col-md-4">
                            <label class="bb-modal-label">Year</label>
                            <input type="number" name="publication_year" id="doc_year" class="bb-modal-input" placeholder="2024" min="1950" max="2099">
                        </div>
                        <div class="col-12">
                            <label class="bb-modal-label">Description</label>
                            <textarea name="description" id="doc_description" class="bb-modal-input" rows="3" placeholder="Short summary of the work..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="bb-modal-label">Document File <span id="doc_file_req">*</span></label>
                            <input type="file" name="file" id="doc_file" class="bb-modal-input" accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.odt,.rtf">
                            <small class="text-muted" style="font-size:11.5px;">PDF, Word, PowerPoint, or text · max 20MB</small>
                            <div id="doc_current_file" class="mt-2" style="display:none;font-size:12.5px;">
                                <i class="bi bi-paperclip"></i> <span id="doc_current_file_name" class="fw-semibold"></span>
                                <span class="text-muted">(keep current file if no new one chosen)</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="bb-edit-profile-btn" id="docSaveBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endif


{{-- ==================== CROP MODAL ==================== --}}
<div class="modal fade" id="cropModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="cropTitle">Adjust photo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div class="bb-crop-stage">
                    <img id="cropImage" src="">
                </div>
            </div>
            <div class="modal-footer">
                <div class="me-auto d-flex gap-2">
                    <button type="button" class="btn btn-light btn-sm" onclick="cropZoom(0.1)"><i class="bi bi-zoom-in"></i></button>
                    <button type="button" class="btn btn-light btn-sm" onclick="cropZoom(-0.1)"><i class="bi bi-zoom-out"></i></button>
                    <button type="button" class="btn btn-light btn-sm" onclick="cropRotate()"><i class="bi bi-arrow-clockwise"></i></button>
                </div>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="cropSaveBtn" class="bb-edit-profile-btn" onclick="applyCrop()">Apply & Upload</button>
            </div>
        </div>
    </div>
</div>
@endif

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
                        <div class="create-post-avatar">
                            @if(Auth::user()->profile_picture)
                                <img src="{{ asset('storage/'.Auth::user()->profile_picture) }}" alt="me" class="cpa-img">
                            @else
                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <h6 class="m-0 fw-bold" style="font-size:14px;">{{ Auth::user()->name }}</h6>                           
                    <div class="dropdown mt-1">
                        <button class="btn btn-light btn-sm border py-1 px-2 dropdown-toggle"
                                style="font-size:11px;font-weight:600;"
                                data-bs-toggle="dropdown" id="privacyBtn">
                            <i class="bi bi-globe-americas text-primary me-1"></i>
                            <span id="privacyLabel">Public</span>
                        </button>
                        <ul class="dropdown-menu shadow border-0 rounded-3" style="font-size:13px;min-width:160px;">
                            <li><a class="dropdown-item py-2" href="#" onclick="setPrivacy('public','bi-globe-americas text-primary','Public')">
                                <i class="bi bi-globe-americas text-primary me-2"></i> Public</a></li>
                            <li><a class="dropdown-item py-2" href="#" onclick="setPrivacy('friends','bi-people-fill text-success','Friends')">
                                <i class="bi bi-people-fill text-success me-2"></i> Friends</a></li>
                            <li><a class="dropdown-item py-2" href="#" onclick="setPrivacy('only_me','bi-lock-fill text-warning','Only Me')">
                                <i class="bi bi-lock-fill text-warning me-2"></i> Only Me</a></li>
                        </ul>
                    </div>
                    <input type="hidden" name="privacy" id="privacyInput" value="public">
                           
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
                        <div class="create-post-avatar">
                            @if(Auth::user()->profile_picture)
                                <img src="{{ asset('storage/'.Auth::user()->profile_picture) }}" alt="me" class="cpa-img">
                            @else
                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                            @endif
                        </div>
                        <div>
                           <h6 class="m-0 fw-bold" style="font-size:14px;">{{ Auth::user()->name }}</h6>

                            <div class="dropdown mt-1">
                        <button class="btn btn-light btn-sm border py-1 px-2 dropdown-toggle"
                                style="font-size:11px;font-weight:600;"
                                data-bs-toggle="dropdown" id="editPrivacyBtn">
                            <i class="bi bi-globe-americas text-primary me-1"></i>
                            <span id="editPrivacyLabel">Public</span>
                        </button>
                        <ul class="dropdown-menu shadow border-0 rounded-3" style="font-size:13px;min-width:160px;">
                            <li><a class="dropdown-item py-2" href="#" onclick="setEditPrivacy('public','bi-globe-americas text-primary','Public')">
                                <i class="bi bi-globe-americas text-primary me-2"></i> Public</a></li>
                            <li><a class="dropdown-item py-2" href="#" onclick="setEditPrivacy('friends','bi-people-fill text-success','Friends')">
                                <i class="bi bi-people-fill text-success me-2"></i> Friends</a></li>
                            <li><a class="dropdown-item py-2" href="#" onclick="setEditPrivacy('only_me','bi-lock-fill text-warning','Only Me')">
                                <i class="bi bi-lock-fill text-warning me-2"></i> Only Me</a></li>
                        </ul>
                    </div>
                    <input type="hidden" id="editPrivacyInput" value="public">
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
{{-- ===== SHARE MODAL (Facebook Style) ===== --}}
<div class="modal fade" id="fbShareModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:500px;">
        <div class="modal-content border-0 shadow-lg rounded-4">
 
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold mx-auto" style="font-size:17px;">Share</h5>
                <button type="button" class="btn-close ms-0" data-bs-dismiss="modal" onclick="resetShareModal()"></button>
            </div>
 
            <form id="fbShareForm">
                <div class="modal-body p-0">
                    <input type="hidden" id="targetSharePostId">
                    <input type="hidden" id="sharePostType" value="post"> {{-- post / job --}}
 
                    {{-- User info + privacy --}}
                    <div class="d-flex align-items-center gap-2 px-3 pt-3 pb-2">
                        <div style="width:40px;height:40px;border-radius:50%;overflow:hidden;flex-shrink:0;background:linear-gradient(135deg,#4f46e5,#7c73f0);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:16px;">
                            @if(Auth::user()->profile_picture)
                                <img src="{{ asset('storage/'.Auth::user()->profile_picture) }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <div style="font-size:14px;font-weight:700;color:#1e1f24;">{{ Auth::user()->name }}</div>
                            <div class="d-flex align-items-center gap-1 mt-1">
                                {{-- Feed button --}}
                                <span style="font-size:11px;font-weight:600;background:#eef2ff;color:#4f46e5;padding:3px 10px;border-radius:20px;">
                                    <i class="bi bi-house-door-fill me-1"></i>Feed
                                </span>
                                {{-- Privacy dropdown --}}
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm border py-1 px-2 dropdown-toggle"
                                            style="font-size:11px;font-weight:600;"
                                            data-bs-toggle="dropdown" id="sharePrivacyBtn">
                                        <i class="bi bi-people-fill text-success me-1"></i>
                                        <span id="sharePrivacyLabel">Friends</span>
                                    </button>
                                    <ul class="dropdown-menu shadow border-0 rounded-3" style="font-size:13px;min-width:160px;">
                                        <li><a class="dropdown-item py-2" href="#" onclick="setSharePrivacy('public','bi-globe-americas text-primary','Public')">
                                            <i class="bi bi-globe-americas text-primary me-2"></i> Public</a></li>
                                        <li><a class="dropdown-item py-2" href="#" onclick="setSharePrivacy('friends','bi-people-fill text-success','Friends')">
                                            <i class="bi bi-people-fill text-success me-2"></i> Friends</a></li>
                                        <li><a class="dropdown-item py-2" href="#" onclick="setSharePrivacy('only_me','bi-lock-fill text-warning','Only Me')">
                                            <i class="bi bi-lock-fill text-warning me-2"></i> Only Me</a></li>
                                    </ul>
                                </div>
                                <input type="hidden" id="sharePrivacyInput" value="friends">
                            </div>
                        </div>
                    </div>
 
                    {{-- Caption textarea --}}
                    <div class="px-3 pb-2">
                        <textarea id="shareComment"
                                  class="form-control border-0 shadow-none"
                                  rows="3"
                                  placeholder="Say something about this..."
                                  style="resize:none;font-size:15px;padding:4px 0;"></textarea>
                    </div>
 
                    {{-- Post preview --}}
                    <div id="modalPostPreview" class="mx-3 mb-3 border rounded-3 overflow-hidden bg-light" style="font-size:13px;"></div>
 
                    <hr class="my-0">
 
                    {{-- Send in Messenger --}}
                    <div class="px-3 py-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span style="font-size:14px;font-weight:700;color:#1e1f24;">Send in Messenger</span>
                            <button type="button" class="btn btn-light btn-sm rounded-circle" style="width:32px;height:32px;padding:0;" title="Search contacts">
                                <i class="bi bi-search" style="font-size:13px;"></i>
                            </button>
                        </div>
 
                        {{-- Friend circles for messenger --}}
                        <div id="messengerContacts" class="d-flex gap-3 overflow-auto pb-2" style="scrollbar-width:none;">
                            {{-- JS দিয়ে load হবে --}}
                            <div class="text-center text-muted small py-2">
                                <div class="spinner-border spinner-border-sm text-primary"></div>
                            </div>
                        </div>
                    </div>
 
                    <hr class="my-0">
 
                    {{-- Share to options --}}
                    <div class="px-3 py-2">
                        <div style="font-size:14px;font-weight:700;color:#1e1f24;margin-bottom:10px;">Share to</div>
 
                        {{-- Copy link --}}
                        <button type="button" class="w-100 d-flex align-items-center gap-3 btn btn-light rounded-3 mb-2 p-3 text-start"
                                onclick="copyPostLink()">
                            <div style="width:40px;height:40px;border-radius:50%;background:#e4e6eb;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0;">
                                <i class="bi bi-link-45deg"></i>
                            </div>
                            <div>
                                <div style="font-size:14px;font-weight:700;color:#1e1f24;">Copy Link</div>
                                <div style="font-size:12px;color:#6b7280;">Anyone with the link can view</div>
                            </div>
                        </button>
                    </div>
 
                </div>
 
                {{-- Footer --}}
                <div class="modal-footer border-top py-2 px-3">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" onclick="resetShareModal()">Cancel</button>
                    <button type="submit" id="shareSubmitBtn" class="btn btn-primary px-4 fw-bold">
                        <i class="bi bi-share-fill me-1"></i> Share Now
                    </button>
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
                        @if(Auth::user()->profile_picture)
                            <img src="{{ asset('storage/'.Auth::user()->profile_picture) }}" alt="me" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        @endif
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

{{-- ==================== IMAGE VIEW (Lightbox) — সবার জন্য ==================== --}}
<div class="modal fade" id="imageViewModal" tabindex="-1" aria-hidden="true" style="background:rgba(0,0,0,.92);">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0">
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" style="z-index:10;"></button>
            <div class="modal-body p-0 text-center">
                <img id="imageViewTarget" src="" style="max-width:100%; max-height:88vh; object-fit:contain; border-radius:8px;">
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.6.1/dist/cropper.min.js"></script>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;
const Toast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:2200, timerProgressBar:true });

// ছবি বড় করে দেখা (cover + avatar) — সবার জন্য
let imageViewModal = null;
document.addEventListener('DOMContentLoaded', () => {
    imageViewModal = new bootstrap.Modal(document.getElementById('imageViewModal'));
});
function openImageView(src) {
    if (!src) return;
    document.getElementById('imageViewTarget').src = src;
    imageViewModal?.show();
}

@if($isOwner)
let editModal = null, cropModal = null;
let cropper = null, cropType = 'profile';
let formInitialState = '';   // পরিবর্তন ট্র্যাক করতে
let allowEditClose = false;  // warning bypass flag

document.addEventListener('DOMContentLoaded', () => {
    editModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
    cropModal = new bootstrap.Modal(document.getElementById('cropModal'));

    // Cancel / X — পরিবর্তন থাকলে warning (Fix #4)
    document.getElementById('editModalCancelBtn').addEventListener('click', attemptCloseEdit);
    document.getElementById('editModalCloseBtn').addEventListener('click', attemptCloseEdit);
});

function serializeForm() {
    const fd = new FormData(document.getElementById('editProfileForm'));
    let s = '';
    for (const [k,v] of fd.entries()) s += k+'='+v+'&';
    return s;
}

function openEditModal() {
    allowEditClose = false;
    editModal?.show();
    // modal খোলার পর initial state ধরে রাখো
    setTimeout(() => { formInitialState = serializeForm(); }, 200);
}

// পরিবর্তন আছে কিনা দেখে বন্ধ করার চেষ্টা (Fix #4)
function attemptCloseEdit() {
    const changed = serializeForm() !== formInitialState;
    if (!changed) { editModal?.hide(); return; }

    Swal.fire({
        title: 'Are you sure?',
        text: 'Your changes will not be saved.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'OK, discard',
        cancelButtonText: 'Keep editing'
    }).then(r => {
        if (r.isConfirmed) { editModal?.hide(); }
        // Cancel চাপলে কিছুই হবে না — এডিট চলমান থাকবে
    });
}

// ---- Save profile info (AJAX) — Fix #1: reload ছাড়া, টোস্ট auto ----
document.getElementById('editProfileForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    const btn = document.getElementById('saveProfileBtn');
    btn.disabled = true; btn.innerText = 'Saving...';

    const fd = new FormData(this);
    fetch("{{ route('profile.update.info') }}", {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: fd
    })
    .then(r => r.json())
    .then(d => {
        btn.disabled = false; btn.innerText = 'Save changes';
        if (!d.success) { Swal.fire({icon:'error', title:'Update failed'}); return; }

        // initial state আপডেট করো (যাতে পরে warning না আসে)
        formInitialState = serializeForm();
        editModal?.hide();
        Toast.fire({ icon:'success', title: d.message || 'Profile updated!' });

        // পেজের তথ্য live আপডেট (reload ছাড়া)
        applyProfileToPage(d.user);
    })
    .catch(() => {
        btn.disabled = false; btn.innerText = 'Save changes';
        Swal.fire({icon:'error', title:'Network error'});
    });
});

// সেভ হওয়া তথ্য পেজে বসাও (reload ছাড়াই দেখা যাবে)
function applyProfileToPage(u) {
    if (!u) { return; }
    const set = (sel, val) => { const el = document.querySelector(sel); if (el) el.textContent = val || '—'; };

    document.getElementById('displayName').textContent = u.name;

    // headline
    const headline = document.querySelector('.bb-headline');
    if (headline) {
        let h = u.department || 'Department not set';
        if (u.session) h += ' · Session ' + u.session;
        headline.textContent = h;
    }

    // info grid (teacher হলে dept/session/section/semester নেই — তাই index ভিন্ন)
    const vals = document.querySelectorAll('.bb-info-value');
    @if($isTeacher)
        // teacher: [0]=phone, [1]=location, [2]=email, [3]=interests
        if (vals[0]) vals[0].textContent = u.phone || '—';
        if (vals[1]) vals[1].textContent = u.location || '—';
        if (vals[3]) vals[3].textContent = u.interests || '—';
    @else
        // student/alumni: [0..3]=dept/session/section/semester, [4]=phone, [5]=location, [6]=email, [7]=interests
        if (vals[0]) vals[0].textContent = u.department || '—';
        if (vals[1]) vals[1].textContent = u.session || '—';
        if (vals[2]) vals[2].textContent = u.section || '—';
        if (vals[3]) vals[3].textContent = u.semester || '—';
        if (vals[4]) vals[4].textContent = u.phone || '—';
        if (vals[5]) vals[5].textContent = u.location || '—';
        if (vals[7]) vals[7].textContent = u.interests || '—';
    @endif

    // Bio
    const bioCard = document.querySelectorAll('.bb-card')[1]; // About card
    if (bioCard) {
        const bioP = bioCard.querySelector('.bb-bio-text, .bb-empty');
        if (bioP) {
            if (u.bio) { bioP.textContent = u.bio; bioP.className = 'bb-bio-text'; }
            else { bioP.textContent = 'Add a short bio to tell people about yourself.'; bioP.className = 'bb-empty'; }
        }
    }
}

// ======== CROP + PHOTO UPLOAD (Fix #2) ========
function startCrop(file, type) {
    if (!file) return;
    if (file.size > 10 * 1024 * 1024) { Swal.fire({icon:'error', title:'File too large!', text:'Max 10MB.'}); return; }

    cropType = type;
    document.getElementById('cropTitle').textContent = type === 'cover' ? 'Adjust cover photo' : 'Adjust profile photo';

    const reader = new FileReader();
    reader.onload = (ev) => {
        const img = document.getElementById('cropImage');
        img.src = ev.target.result;
        cropModal.show();

        // আগের cropper destroy
        if (cropper) { cropper.destroy(); cropper = null; }
        // modal পুরো খোলার পর cropper init
        setTimeout(() => {
            cropper = new Cropper(img, {
                aspectRatio: type === 'cover' ? 3/1 : 1/1,   // cover 3:1, avatar 1:1
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                background: false,
                responsive: true,
            });
        }, 300);
    };
    reader.readAsDataURL(file);
}

function cropZoom(amt) { if (cropper) cropper.zoom(amt); }
function cropRotate() { if (cropper) cropper.rotate(90); }

function applyCrop() {
    if (!cropper) return;
    const btn = document.getElementById('cropSaveBtn');
    btn.disabled = true; btn.innerText = 'Uploading...';

    const w = cropType === 'cover' ? 1200 : 500;
    const h = cropType === 'cover' ? 400 : 500;

    cropper.getCroppedCanvas({ width:w, height:h, imageSmoothingQuality:'high' }).toBlob((blob) => {
        const fd = new FormData();
        fd.append('photo', blob, 'cropped.jpg');
        fd.append('type', cropType);

        fetch("{{ route('profile.update.photo') }}", {
            method:'POST',
            headers:{ 'X-CSRF-TOKEN': CSRF, 'Accept':'application/json' },
            body: fd
        })
        .then(r => r.json())
        .then(d => {
            btn.disabled = false; btn.innerText = 'Apply & Upload';
            if (!d.success) { Swal.fire({icon:'error', title:'Upload failed'}); return; }
            cropModal.hide();

            // live বদলাও (reload ছাড়া) — Fix #1
            if (cropType === 'cover') {
                const cover = document.querySelector('.bb-cover');
                let im = document.getElementById('coverImg');
                if (!im) { im = document.createElement('img'); im.id='coverImg'; cover.insertBefore(im, cover.firstChild); }
                im.src = d.url + '?t=' + Date.now();
            } else {
                const av = document.getElementById('avatarImg');
                if (av.tagName === 'IMG') { av.src = d.url + '?t=' + Date.now(); }
                else {
                    const im = document.createElement('img');
                    im.id='avatarImg'; im.className='bb-avatar-lg'; im.src = d.url + '?t=' + Date.now();
                    av.replaceWith(im);
                }
            }
            Toast.fire({ icon:'success', title: d.message || 'Photo updated!' });
        })
        .catch(() => { btn.disabled = false; btn.innerText='Apply & Upload'; Swal.fire({icon:'error', title:'Network error'}); });
    }, 'image/jpeg', 0.9);
}

document.getElementById('avatarInput')?.addEventListener('change', function(){ startCrop(this.files[0], 'profile'); this.value=''; });
document.getElementById('coverInput')?.addEventListener('change', function(){ startCrop(this.files[0], 'cover'); this.value=''; });

// crop modal বন্ধ হলে cropper destroy
document.getElementById('cropModal')?.addEventListener('hidden.bs.modal', () => {
    if (cropper) { cropper.destroy(); cropper = null; }
});
@endif
</script>

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
    // Saved পেজ থেকে এসে নির্দিষ্ট পোস্টে স্ক্রল + হাইলাইট
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

    // ফিডের সব inline ভিডিওর প্রথম ফ্রেম থাম্বনেইল হিসেবে দেখানো
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

    const feed = document.getElementById('postsFeedContainer') || document.getElementById('profilePostsContainer');
    if (feed) {
        // profile এ "No posts" placeholder থাকলে সরাও
        const emptyState = feed.querySelector('.bb-posts-empty');
        if (emptyState) emptyState.remove();
        feed.insertAdjacentHTML('afterbegin', html);
    }

    const fd = new FormData();
    fd.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    fd.append('content', content);
    fd.append('bg_color', bgColor);
    fd.append('privacy', document.getElementById('privacyInput')?.value || 'public');
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

            let res = {};
            try { res = JSON.parse(xhr.responseText); } catch(e){}

            setTimeout(() => {
                const optCard = document.getElementById(pid);
                if (res.html) {
                    if (optCard) {
                        optCard.outerHTML = res.html;
                    } else {
                        const feedC = document.getElementById('profilePostsContainer') || document.getElementById('postsFeedContainer');
                        if (feedC) feedC.insertAdjacentHTML('afterbegin', res.html);
                    }
                    if (window.bbPrimeVideos) window.bbPrimeVideos();
                } else {
                    if (optCard) optCard.remove();
                }
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
// SAVE / UNSAVE (Facebook-style toggle)
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
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
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


// Share privacy
function setSharePrivacy(value, iconClass, label) {
    document.getElementById('sharePrivacyInput').value = value;
    document.getElementById('sharePrivacyLabel').textContent = label;
    const btn = document.getElementById('sharePrivacyBtn');
    if (btn) btn.querySelector('i').className = 'bi ' + iconClass + ' me-1';
}
 
// Share modal open
function openShareModal(postId, type = 'post') {
    document.getElementById('targetSharePostId').value = postId;
    document.getElementById('sharePostType').value = type;
    document.getElementById('shareComment').value = '';
 
    // Privacy reset
    setSharePrivacy('friends', 'bi-people-fill text-success', 'Friends');
 
    // Post preview
    const card = document.getElementById(`postCard-${postId}`) || document.getElementById(`jobCard-${postId}`);
    const preview = document.getElementById('modalPostPreview');
    if (card && preview) {
        if (type === 'job') {
            // Job card preview
            const title   = card.querySelector('.bb-jobcard-title')?.innerText || '';
            const company = card.querySelector('.bb-jobcard-company')?.innerText || '';
            const tag     = card.querySelector('.bb-jobcard-tag')?.innerText || '';
            preview.innerHTML = `
            <div class="p-3">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-briefcase-fill text-primary"></i>
                    <span style="font-size:13px;font-weight:700;">${title}</span>
                </div>
                <div style="font-size:12px;color:#6b7280;">${company}</div>
                ${tag ? `<span style="font-size:11px;background:#eef2ff;color:#4f46e5;padding:2px 8px;border-radius:6px;font-weight:600;">${tag}</span>` : ''}
            </div>`;
        } else {
            // Normal post preview
            const author  = card.querySelector('.author-name-zone')?.innerText || 'User';
            const avatar  = card.querySelector('.author-avatar-zone')?.innerHTML || '';
            const colored = card.getAttribute('data-bg-color');
            const caption = card.querySelector('.dynamic-caption')?.innerHTML || '';
            const grid    = card.querySelector('.dynamic-media-container-zone');
 
            let capHtml = `<p class="mb-0" style="font-size:13px;color:#374151;">${caption}</p>`;
            if (colored && colored !== 'null' && colored !== '') {
                capHtml = `<div class="p-3 rounded text-center text-white fw-bold ${colored}" style="min-height:70px;font-size:15px;"><p class="mb-0">${caption}</p></div>`;
            }
 
            let gridHtml = '';
            if (grid) {
                const clone = grid.cloneNode(true);
                clone.querySelectorAll('img,video').forEach(el => {
                    el.removeAttribute('onclick');
                    if (el.tagName === 'VIDEO') el.removeAttribute('controls');
                });
                gridHtml = `<div class="overflow-hidden" style="max-height:200px;">${clone.outerHTML}</div>`;
            }
 
            preview.innerHTML = `
            <div class="p-3 pb-2">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div style="width:28px;height:28px;border-radius:50%;overflow:hidden;background:linear-gradient(135deg,#4f46e5,#7c73f0);color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;">${avatar}</div>
                    <span style="font-size:13px;font-weight:700;">${author}</span>
                </div>
                ${capHtml}
            </div>
            ${gridHtml}`;
        }
    }
 
    // Messenger contacts load
    loadMessengerContacts();
 
    bootstrapShareModal?.show();
}
 
function closeShareModal() {
    bootstrapShareModal?.hide();
    resetShareModal();
}
 
function resetShareModal() {
    document.getElementById('shareComment').value = '';
    setSharePrivacy('friends', 'bi-people-fill text-success', 'Friends');
}
 
// Messenger contacts (friends list)
function loadMessengerContacts() {
    const zone = document.getElementById('messengerContacts');
    if (!zone) return;
 
    zone.innerHTML = '<div class="text-center text-muted small py-2"><div class="spinner-border spinner-border-sm text-primary"></div></div>';
 
    fetch('/friends/messenger-contacts', {
        headers: { 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(d => {
        if (!d.contacts || !d.contacts.length) {
            zone.innerHTML = '<div class="text-muted small py-2">No friends yet</div>';
            return;
        }
        let html = '';
        d.contacts.forEach(c => {
            const pic = c.profile_picture
                ? `<img src="/storage/${c.profile_picture}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`
                : `<span style="font-size:16px;font-weight:700;">${c.name.charAt(0).toUpperCase()}</span>`;
            html += `
            <div class="text-center flex-shrink-0" style="cursor:pointer;width:64px;" onclick="sendToMessenger(${c.id}, '${c.name.replace(/'/g,"\\'")}')">
                <div style="width:52px;height:52px;border-radius:50%;overflow:hidden;background:linear-gradient(135deg,#4f46e5,#7c73f0);color:#fff;display:flex;align-items:center;justify-content:center;margin:0 auto 4px;">
                    ${pic}
                </div>
                <div style="font-size:11px;font-weight:600;color:#1e1f24;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${c.name.split(' ')[0]}</div>
            </div>`;
        });
        zone.innerHTML = html;
    })
    .catch(() => {
        zone.innerHTML = '<div class="text-muted small py-2">Could not load contacts</div>';
    });
}
 
// Send to messenger (chat box open)
function sendToMessenger(userId, name) {
    const postId = document.getElementById('targetSharePostId').value;
    const type   = document.getElementById('sharePostType').value;
 
    // Chat box open করি
    if (typeof openChatBox === 'function') {
        openChatBox(userId, name, '', '', '0');
        // message pre-fill
        setTimeout(() => {
            const input = document.getElementById('chatinput-' + userId);
            if (input) {
                const link = window.location.origin + (type === 'job' ? '/jobs/' + postId : '/#postCard-' + postId);
                input.value = '📎 Check this out: ' + link;
                input.dispatchEvent(new Event('input'));
            }
        }, 300);
    }
 
    bootstrapShareModal?.hide();
 
    Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:2000, timerProgressBar:true })
        .fire({ icon:'success', title:`Sent to ${name}!` });
}
 
// Copy link
function copyPostLink() {
    const postId = document.getElementById('targetSharePostId').value;
    const type   = document.getElementById('sharePostType').value;
    const link   = window.location.origin + (type === 'job' ? '/jobs/' + postId : '/#postCard-' + postId);
 
    navigator.clipboard.writeText(link).then(() => {
        Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:2000, timerProgressBar:true })
            .fire({ icon:'success', title:'Link copied!' });
    }).catch(() => {
        prompt('Copy this link:', link);
    });
}
 
// Share form submit (share to feed)
document.getElementById('fbShareForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    const postId  = document.getElementById('targetSharePostId').value;
    const type    = document.getElementById('sharePostType').value;
    const comment = document.getElementById('shareComment').value.trim();
    const privacy = document.getElementById('sharePrivacyInput').value;
    const btn     = document.getElementById('shareSubmitBtn');
 
    // Job share — feed এ শেয়ার হবে না, শুধু link copy
    if (type === 'job') {
        copyPostLink();
        bootstrapShareModal?.hide();
        return;
    }
 
    btn.disabled = true;
    Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false })
        .fire({ icon:'info', title:'Sharing...' });
 
    fetch(`/posts/${postId}/share`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ content: comment, privacy: privacy })
    })
    .then(r => r.json())
    .then(d => {
        btn.disabled = false;
        if (!d.success) {
            Swal.fire({ icon:'error', title:'Failed!', text: d.message || 'Could not share.' });
            return;
        }
        bootstrapShareModal?.hide();
        resetShareModal();
        Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:2000, timerProgressBar:true })
            .fire({ icon:'success', title:'Shared to your feed!' });
 
        if (d.html) {
            const feedC = document.getElementById('postsFeedContainer') || document.getElementById('profilePostsContainer');
            if (feedC) {
                feedC.insertAdjacentHTML('afterbegin', d.html);
                if (window.bbPrimeVideos) window.bbPrimeVideos();
                feedC.querySelector('.bb-posts-empty')?.remove();
                feedC.querySelector('#emptyFeedState')?.remove();
            }
        }
    })
    .catch(() => {
        btn.disabled = false;
        Swal.fire({ icon:'error', title:'Network Error!' });
    });
});
 
// Job share button (job-card.blade.php এ)
function openJobShareModal(jobId) {
    openShareModal(jobId, 'job');
}


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
        // ✅ এটা যোগ করো:
        const editPrivacy2 = el.getAttribute('data-privacy') || 'public';
        const privacyMap2 = {
            'public':  ['bi-globe-americas text-primary', 'Public'],
            'friends': ['bi-people-fill text-success',    'Friends'],
            'only_me': ['bi-lock-fill text-warning',      'Only Me'],
        };
        const [ic2, lb2] = privacyMap2[editPrivacy2] || privacyMap2['public'];
        setEditPrivacy(editPrivacy2, ic2, lb2);
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
    fd.append('privacy',document.getElementById('editPrivacyInput')?.value||'public');
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
// INFINITE SCROLL (profile এ নিষ্ক্রিয় — no-op)
// ==========================================
function loadMorePosts() { /* profile page: ট্যাবে সব পোস্ট একসাথে লোড হয়, infinite scroll লাগে না */ }
</script>

<script>
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

// ==========================================
// PROFILE TAB SWITCHING (AJAX, lazy load)
// ==========================================
const PROFILE_USER_ID = @json($user->hashid);
const PROFILE_IS_OWNER = {{ $isOwner ? 'true' : 'false' }};
let postsLoaded = false;
let mediaLoaded = false;

function switchTab(tab) {
    // বাটন active
    document.querySelectorAll('.bb-tab-btn').forEach(b => {
        b.classList.toggle('active', b.dataset.tab === tab);
    });
    // panel active
    document.querySelectorAll('.bb-tab-panel').forEach(p => p.classList.remove('active'));
    const panel = document.getElementById('tab-' + tab);
    if (panel) panel.classList.add('active');

    // lazy load
    if (tab === 'posts' && !postsLoaded) { loadProfilePosts(); }
    if (tab === 'media' && !mediaLoaded) { loadProfileMedia(); }
}

function profileTabUrl(tab) {
    // নিজের হলে /profile/tab/content, অন্যের হলে /profile/{id}/tab/content
    return PROFILE_IS_OWNER
        ? `{{ route('profile.tab') }}?tab=${tab}`
        : `/profile/${PROFILE_USER_ID}/tab/content?tab=${tab}`;
}

// ----- POSTS লোড -----
function loadProfilePosts() {
    const container = document.getElementById('profilePostsContainer');
    fetch(profileTabUrl('posts'), {
        headers: { 'X-Requested-With':'XMLHttpRequest', 'Accept':'application/json' }
    })
    .then(r => r.json())
    .then(d => {
        postsLoaded = true;
        if (!d.success) { container.innerHTML = '<div class="bb-posts-empty">Could not load posts.</div>'; return; }
        if (d.count === 0) {
            container.innerHTML = `<div class="bb-posts-empty"><i class="bi bi-inbox"></i><h5 class="fw-bold text-secondary">No posts yet</h5><p class="small">${PROFILE_IS_OWNER ? 'Share your first post!' : 'This user has not posted anything.'}</p></div>`;
            return;
        }
        container.innerHTML = d.html;
        if (window.bbPrimeVideos) window.bbPrimeVideos(container);

        // মাত্র করা পোস্টে স্ক্রল + হাইলাইট
        const newId = sessionStorage.getItem('profileNewPostId');
        if (newId) {
            sessionStorage.removeItem('profileNewPostId');
            setTimeout(() => {
                const card = document.getElementById('postCard-' + newId);
                if (card) {
                    card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    card.style.transition = 'box-shadow .35s ease';
                    card.style.boxShadow = '0 0 0 3px var(--bb-primary)';
                    setTimeout(() => { card.style.boxShadow = ''; }, 2500);
                }
            }, 300);
        }
    })
    .catch(() => { postsLoaded = true; container.innerHTML = '<div class="bb-posts-empty">Network error.</div>'; });
}

// ----- MEDIA লোড -----
function loadProfileMedia() {
    const container = document.getElementById('profileMediaContainer');
    fetch(profileTabUrl('media'), {
        headers: { 'X-Requested-With':'XMLHttpRequest', 'Accept':'application/json' }
    })
    .then(r => r.json())
    .then(d => {
        mediaLoaded = true;
        if (!d.success) { container.innerHTML = '<div class="bb-media-empty">Could not load media.</div>'; return; }
        if (d.count === 0) {
            container.innerHTML = `<div class="bb-media-empty"><i class="bi bi-images"></i><h5 class="fw-bold text-secondary">No photos or videos yet</h5></div>`;
            return;
        }
        // grid বানাও
        let grid = '<div class="bb-media-grid">';
        const mediaJson = JSON.stringify(d.media).replace(/"/g, '&quot;');
        d.media.forEach((m, i) => {
            if (m.type === 'image') {
                grid += `<div class="bb-media-grid-item" onclick="openProfileMedia(${i})"><img src="${m.url}" loading="lazy"></div>`;
            } else {
                grid += `<div class="bb-media-grid-item" onclick="openProfileMedia(${i})"><video src="${m.url}" preload="metadata" muted></video><div class="bb-media-grid-play"><i class="bi bi-play-fill"></i></div></div>`;
            }
        });
        grid += '</div>';
        container.innerHTML = grid;
        // media array global এ রাখি lightbox এর জন্য
        window.profileMediaArr = d.media;
        if (window.bbPrimeVideos) window.bbPrimeVideos(container);
    })
    .catch(() => { mediaLoaded = true; container.innerHTML = '<div class="bb-media-empty">Network error.</div>'; });
}

// media grid এ ক্লিক → feed এর lightbox (openLightbox) ব্যবহার
function openProfileMedia(index) {
    if (window.profileMediaArr && typeof openLightbox === 'function') {
        openLightbox(JSON.stringify(window.profileMediaArr), index);
    }
}

// পেজ লোডেই Posts ট্যাব ডিফল্ট — তাই পোস্ট লোড করো
document.addEventListener('DOMContentLoaded', () => {
    loadProfilePosts();
});


// ==========================================
// PROFILE DETAILS (Education/Experience/Cert) CRUD
// ==========================================
@if($isOwner)
let eduModalObj=null, expModalObj=null, certModalObj=null;
document.addEventListener('DOMContentLoaded', () => {
    eduModalObj  = new bootstrap.Modal(document.getElementById('eduModal'));
    expModalObj  = new bootstrap.Modal(document.getElementById('expModal'));
    certModalObj = new bootstrap.Modal(document.getElementById('certModal'));
});

// সার্ভার থেকে আসা ডেটা (edit এর জন্য)
const eduData  = @json($user->educations);
const expData  = @json($user->experiences);
const certData = @json($user->certifications);

const DETAIL_CSRF = document.querySelector('meta[name="csrf-token"]').content;
const detailToast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:1800, timerProgressBar:true });

function fmtDate(d){ return d ? d.substring(0,10) : ''; }
function monthYear(d){ if(!d) return ''; const dt=new Date(d); return dt.toLocaleString('en',{month:'short',year:'numeric'}); }

/* ---------- EDUCATION ---------- */
function toggleEduEnd(){ document.getElementById('edu_end_wrap').style.display = document.getElementById('edu_is_current').checked ? 'none':''; }
function openEduModal(){
    document.getElementById('eduForm').reset();
    document.getElementById('edu_id').value='';
    document.getElementById('eduModalTitle').innerText='Add Education';
    toggleEduEnd();
    eduModalObj.show();
}
function editEdu(id){
    const e = eduData.find(x=>x.id===id); if(!e) return;
    document.getElementById('edu_id').value=e.id;
    document.getElementById('edu_degree').value=e.degree||'';
    document.getElementById('edu_field').value=e.field||'';
    document.getElementById('edu_institution').value=e.institution||'';
    document.getElementById('edu_result').value=e.result||'';
    document.getElementById('edu_start_date').value=fmtDate(e.start_date);
    document.getElementById('edu_end_date').value=fmtDate(e.end_date);
    document.getElementById('edu_is_current').checked=!!e.is_current;
    document.getElementById('eduModalTitle').innerText='Edit Education';
    toggleEduEnd();
    eduModalObj.show();
}
document.getElementById('eduForm').addEventListener('submit', function(ev){
    ev.preventDefault();
    saveDetail(this, "{{ route('profile.education.store') }}", 'eduSaveBtn', eduModalObj, renderEdu, eduData);
});
function renderEdu(item){
    document.getElementById('eduEmpty')?.remove();
    const html = `
    <div class="bb-timeline-item" id="edu-${item.id}">
        <div class="bb-timeline-icon"><i class="bi bi-mortarboard-fill"></i></div>
        <div class="bb-timeline-body">
            <h6 class="bb-timeline-title">${item.degree}${item.field?' · '+item.field:''}</h6>
            <p class="bb-timeline-sub">${item.institution}</p>
            <p class="bb-timeline-meta">${item.duration||''}${item.result?' · Result: '+item.result:''}</p>
        </div>
        <div class="bb-timeline-actions">
            <button onclick="editEdu(${item.id})" title="Edit"><i class="bi bi-pencil"></i></button>
            <button onclick="deleteDetail('education',${item.id},'edu-${item.id}')" title="Delete" class="text-danger"><i class="bi bi-trash3"></i></button>
        </div>
    </div>`;
    upsertItem('eduList','edu-'+item.id, html, eduData, item);
}

/* ---------- EXPERIENCE ---------- */
function toggleExpEnd(){ document.getElementById('exp_end_wrap').style.display = document.getElementById('exp_is_current').checked ? 'none':''; }
function openExpModal(){
    document.getElementById('expForm').reset();
    document.getElementById('exp_id').value='';
    document.getElementById('expModalTitle').innerText='Add Experience';
    toggleExpEnd();
    expModalObj.show();
}
function editExp(id){
    const e = expData.find(x=>x.id===id); if(!e) return;
    document.getElementById('exp_id').value=e.id;
    document.getElementById('exp_designation').value=e.designation||'';
    document.getElementById('exp_company').value=e.company||'';
    document.getElementById('exp_employment_type').value=e.employment_type||'';
    document.getElementById('exp_location').value=e.location||'';
    document.getElementById('exp_start_date').value=fmtDate(e.start_date);
    document.getElementById('exp_end_date').value=fmtDate(e.end_date);
    document.getElementById('exp_is_current').checked=!!e.is_current;
    document.getElementById('exp_description').value=e.description||'';
    document.getElementById('expModalTitle').innerText='Edit Experience';
    toggleExpEnd();
    expModalObj.show();
}
document.getElementById('expForm').addEventListener('submit', function(ev){
    ev.preventDefault();
    saveDetail(this, "{{ route('profile.experience.store') }}", 'expSaveBtn', expModalObj, renderExp, expData);
});
function renderExp(item){
    document.getElementById('expEmpty')?.remove();
    const html = `
    <div class="bb-timeline-item" id="exp-${item.id}">
        <div class="bb-timeline-icon"><i class="bi bi-briefcase-fill"></i></div>
        <div class="bb-timeline-body">
            <h6 class="bb-timeline-title">${item.designation}</h6>
            <p class="bb-timeline-sub">${item.company}${item.employment_type?' · '+item.employment_type:''}</p>
            <p class="bb-timeline-meta">${item.duration||''}${item.location?' · '+item.location:''}</p>
            ${item.description?`<p class="bb-timeline-desc">${item.description}</p>`:''}
        </div>
        <div class="bb-timeline-actions">
            <button onclick="editExp(${item.id})" title="Edit"><i class="bi bi-pencil"></i></button>
            <button onclick="deleteDetail('experience',${item.id},'exp-${item.id}')" title="Delete" class="text-danger"><i class="bi bi-trash3"></i></button>
        </div>
    </div>`;
    upsertItem('expList','exp-'+item.id, html, expData, item);
}

/* ---------- CERTIFICATION ---------- */
function openCertModal(){
    document.getElementById('certForm').reset();
    document.getElementById('cert_id').value='';
    document.getElementById('certModalTitle').innerText='Add Certification';
    certModalObj.show();
}
function editCert(id){
    const e = certData.find(x=>x.id===id); if(!e) return;
    document.getElementById('cert_id').value=e.id;
    document.getElementById('cert_title').value=e.title||'';
    document.getElementById('cert_organization').value=e.organization||'';
    document.getElementById('cert_issue_date').value=fmtDate(e.issue_date);
    document.getElementById('cert_credential_url').value=e.credential_url||'';
    document.getElementById('certModalTitle').innerText='Edit Certification';
    certModalObj.show();
}
document.getElementById('certForm').addEventListener('submit', function(ev){
    ev.preventDefault();
    saveDetail(this, "{{ route('profile.certification.store') }}", 'certSaveBtn', certModalObj, renderCert, certData);
});
function renderCert(item){
    document.getElementById('certEmpty')?.remove();
    const html = `
    <div class="bb-timeline-item" id="cert-${item.id}">
        <div class="bb-timeline-icon"><i class="bi bi-patch-check-fill"></i></div>
        <div class="bb-timeline-body">
            <h6 class="bb-timeline-title">${item.title}</h6>
            <p class="bb-timeline-sub">${item.organization||''}</p>
            <p class="bb-timeline-meta">${item.issue_date?monthYear(item.issue_date):''}${item.credential_url?` · <a href="${item.credential_url}" target="_blank" class="bb-cred-link">Show credential</a>`:''}</p>
        </div>
        <div class="bb-timeline-actions">
            <button onclick="editCert(${item.id})" title="Edit"><i class="bi bi-pencil"></i></button>
            <button onclick="deleteDetail('certification',${item.id},'cert-${item.id}')" title="Delete" class="text-danger"><i class="bi bi-trash3"></i></button>
        </div>
    </div>`;
    upsertItem('certList','cert-'+item.id, html, certData, item);
}

/* ---------- শেয়ার্ড হেল্পার ---------- */
function saveDetail(form, url, btnId, modalObj, renderFn, dataArr){
    const btn=document.getElementById(btnId);
    btn.disabled=true; const orig=btn.innerText; btn.innerText='Saving...';
    const fd=new FormData(form);

    // checkbox স্পষ্টভাবে 1/0 (unchecked হলে FormData তে থাকেই না)
    const chk = form.querySelector('input[type="checkbox"][name="is_current"]');
    if (chk) {
        fd.set('is_current', chk.checked ? '1' : '0');
        if (chk.checked) fd.set('end_date', '');  // currently হলে end_date খালি
    }
    // খালি date string পরিষ্কার (validation এ যেন না আটকায়)
    ['start_date','end_date','issue_date'].forEach(k => {
        if (fd.has(k) && fd.get(k) === '') fd.delete(k);
    });

    fetch(url,{ method:'POST', headers:{'X-CSRF-TOKEN':DETAIL_CSRF,'Accept':'application/json'}, body:fd })
    .then(r=>r.json())
    .then(d=>{
        btn.disabled=false; btn.innerText=orig;
        if(!d.success){ Swal.fire({icon:'error',title:'Save failed'}); return; }
        modalObj.hide();
        renderFn(d.item);
        detailToast.fire({icon:'success', title:d.message||'Saved!'});
    })
    .catch(()=>{ btn.disabled=false; btn.innerText=orig; Swal.fire({icon:'error',title:'Network error'}); });
}

// নতুন হলে যোগ, এডিট হলে রিপ্লেস + ডেটা array আপডেট
function upsertItem(listId, itemId, html, dataArr, item){
    const existing = document.getElementById(itemId);
    if(existing){
        existing.outerHTML = html;
        const i = dataArr.findIndex(x=>x.id===item.id);
        if(i>-1) dataArr[i]=item; else dataArr.push(item);
    } else {
        document.getElementById(listId).insertAdjacentHTML('afterbegin', html);
        dataArr.push(item);
    }
}

function deleteDetail(type, id, elId){
    Swal.fire({ title:'Delete this?', icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444', confirmButtonText:'Delete' })
    .then(r=>{
        if(!r.isConfirmed) return;
        fetch(`/profile/${type}/${id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':DETAIL_CSRF,'Accept':'application/json'} })
        .then(r=>r.json())
        .then(d=>{
            if(!d.success){ Swal.fire({icon:'error',title:'Delete failed'}); return; }
            document.getElementById(elId)?.remove();
            detailToast.fire({icon:'success', title:d.message||'Removed'});
        });
    });
}
@endif

// ==========================================
// DOCUMENTS (Thesis/Project/Research) CRUD
// ==========================================
@if($isOwner)
let docModalObj = null;
document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('docModal');
    if (el) docModalObj = new bootstrap.Modal(el);
});

const docData = @json($user->documents);

function docIcon(ft){
    if(ft === 'pdf') return 'bi-file-earmark-pdf';
    if(['doc','docx','odt','rtf'].includes(ft)) return 'bi-file-earmark-word';
    if(['ppt','pptx'].includes(ft)) return 'bi-file-earmark-slides';
    return 'bi-file-earmark-text';
}

function openDocModal(){
    document.getElementById('docForm').reset();
    document.getElementById('doc_id').value = '';
    document.getElementById('doc_file_req').style.display = '';
    document.getElementById('doc_file').required = true;
    document.getElementById('doc_current_file').style.display = 'none';
    document.getElementById('docModalTitle').innerText = 'Add Research / Publication';
    docModalObj.show();
}

function editDoc(id){
    const e = docData.find(x=>x.id===id); if(!e) return;
    document.getElementById('docForm').reset();
    document.getElementById('doc_id').value = e.id;
    document.getElementById('doc_title').value = e.title || '';
    document.getElementById('doc_type').value = e.type || 'Thesis';
    document.getElementById('doc_topic').value = e.topic || '';
    document.getElementById('doc_year').value = e.publication_year || '';
    document.getElementById('doc_description').value = e.description || '';
    // এডিটে ফাইল ঐচ্ছিক
    document.getElementById('doc_file_req').style.display = 'none';
    document.getElementById('doc_file').required = false;
    document.getElementById('doc_current_file').style.display = '';
    document.getElementById('doc_current_file_name').innerText = e.file_name || '';
    document.getElementById('docModalTitle').innerText = 'Edit Research / Publication';
    docModalObj.show();
}

document.getElementById('docForm')?.addEventListener('submit', function(ev){
    ev.preventDefault();
    const btn = document.getElementById('docSaveBtn');
    btn.disabled = true; const orig = btn.innerText; btn.innerText = 'Saving...';
    const fd = new FormData(this);
    // খালি ফাইল ইনপুট পাঠাব না
    if (!document.getElementById('doc_file').files.length) fd.delete('file');

    fetch("{{ route('profile.document.store') }}", {
        method:'POST', headers:{'X-CSRF-TOKEN':DETAIL_CSRF,'Accept':'application/json'}, body:fd
    })
    .then(r=>r.json())
    .then(d=>{
        btn.disabled=false; btn.innerText=orig;
        if(!d.success){ Swal.fire({icon:'error',title:'Save failed'}); return; }
        docModalObj.hide();
        renderDoc(d.item);
        detailToast.fire({icon:'success', title:d.message||'Saved!'});
    })
    .catch(()=>{ btn.disabled=false; btn.innerText=orig; Swal.fire({icon:'error',title:'Upload error',text:'File may be too large or invalid type.'}); });
});

function renderDoc(item){
    document.getElementById('docEmpty')?.remove();
    const ic = docIcon(item.file_type);
    const html = `
    <div class="bb-doc-item" id="doc-${item.id}">
        <div class="bb-doc-icon bb-doc-${item.file_type}"><i class="bi ${ic}"></i></div>
        <div class="bb-doc-body">
            <div class="bb-doc-toprow">
                <span class="bb-doc-type">${item.type}</span>
                ${item.publication_year ? `<span class="bb-doc-year">· ${item.publication_year}</span>` : ''}
            </div>
            <h6 class="bb-doc-title">${item.title}</h6>
            ${item.topic ? `<p class="bb-doc-topic"><i class="bi bi-tag"></i> ${item.topic}</p>` : ''}
            ${item.description ? `<p class="bb-doc-desc">${item.description}</p>` : ''}
            <div class="bb-doc-filerow">
                <a href="${item.file_url}" target="_blank" class="bb-doc-file"><i class="bi bi-paperclip"></i> ${item.file_name}</a>
                <span class="bb-doc-size">${item.readable_size}</span>
            </div>
        </div>
        <div class="bb-timeline-actions">
            <button onclick="editDoc(${item.id})" title="Edit"><i class="bi bi-pencil"></i></button>
            <button onclick="deleteDoc(${item.id}, 'doc-${item.id}')" title="Delete" class="text-danger"><i class="bi bi-trash3"></i></button>
        </div>
    </div>`;
    const existing = document.getElementById('doc-'+item.id);
    if(existing){
        existing.outerHTML = html;
        const i = docData.findIndex(x=>x.id===item.id);
        if(i>-1) docData[i]=item; else docData.push(item);
    } else {
        document.getElementById('docList').insertAdjacentHTML('afterbegin', html);
        docData.push(item);
    }
}

function deleteDoc(id, elId){
    Swal.fire({ title:'Delete this document?', icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444', confirmButtonText:'Delete' })
    .then(r=>{
        if(!r.isConfirmed) return;
        fetch(`/profile/document/${id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':DETAIL_CSRF,'Accept':'application/json'} })
        .then(r=>r.json())
        .then(d=>{
            if(!d.success){ Swal.fire({icon:'error',title:'Delete failed'}); return; }
            document.getElementById(elId)?.remove();
            detailToast.fire({icon:'success', title:d.message||'Removed'});
        });
    });
}

// নিজের পোস্ট করা job ডিলিট (profile থেকে)
function deleteMyJob(id){
    Swal.fire({ title:'Delete this job post?', icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444', confirmButtonText:'Delete' })
    .then(r=>{
        if(!r.isConfirmed) return;
        fetch(`/jobs/${id}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':DETAIL_CSRF,'Accept':'application/json'} })
        .then(r=>r.json())
        .then(d=>{
            if(!d.success){ Swal.fire({icon:'error',title:'Delete failed'}); return; }
            document.getElementById(`myjob-${id}`)?.remove();
            detailToast.fire({icon:'success', title:'Job deleted'});
        });
    });
}

// ===== Job Edit (profile থেকে) =====
let pjModalObj = null;
document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('postJobModal');
    if (el) pjModalObj = bootstrap.Modal.getOrCreateInstance(el);
});

function editJobById(id){
    fetch(`/jobs/${id}/data`, { headers:{'Accept':'application/json'} })
    .then(r=>r.json())
    .then(d=>{
        if(!d.success){ Swal.fire({icon:'error',title:'Could not load job'}); return; }
        const job = d.job;
        const f = document.getElementById('postJobForm');
        if (!f) return;
        f.reset();
        document.getElementById('job_id').value = job.id;
        document.getElementById('job_title').value = job.title || '';
        document.getElementById('job_company').value = job.company || '';
        document.getElementById('job_location').value = job.location || '';
        document.getElementById('job_type').value = job.job_type || 'Full-time';
        document.getElementById('job_experience').value = job.experience || '';
        document.getElementById('job_salary').value = job.salary || '';
        document.getElementById('job_category').value = job.category || '';
        document.getElementById('job_deadline').value = job.deadline || '';
        document.getElementById('job_description').value = job.description || '';
        document.getElementById('job_requirements').value = job.requirements || '';
        document.getElementById('job_skills').value = job.skills || '';
        document.getElementById('job_apply_type').value = job.apply_type || 'link';
        document.getElementById('job_apply_value').value = job.apply_value || '';
        document.getElementById('postJobModalTitle').innerHTML = '<i class="bi bi-pencil-square text-primary me-1"></i> Edit Job';
        document.getElementById('jobSubmitBtn').innerHTML = '<i class="bi bi-check2 me-1"></i> Update Job';
        pjModalObj?.show();
    })
    .catch(()=>Swal.fire({icon:'error',title:'Network error'}));
}

function submitJobForm(){
    const form = document.getElementById('postJobForm');
    const btn = document.getElementById('jobSubmitBtn');
    btn.disabled = true; const orig = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';
    const fd = new FormData(form);

    fetch("{{ route('jobs.store') }}", {
        method:'POST', headers:{'X-CSRF-TOKEN':DETAIL_CSRF,'Accept':'application/json'}, body:fd
    })
    .then(r=>{
        if (!r.ok && r.status !== 422) throw new Error('Server error ' + r.status);
        return r.json();
    })
    .then(d=>{
        btn.disabled=false; btn.innerHTML=orig;
        if(!d.success){
            let msg = d.message || 'Could not save job.';
            if (d.errors) msg = Object.values(d.errors).flat().join('\n');
            Swal.fire({icon:'error',title:'Failed',text:msg});
            return;
        }
        pjModalObj?.hide();
        const isEdit = !!document.getElementById('job_id').value;
        detailToast.fire({icon:'success', title: isEdit ? 'Job updated!' : 'Job posted!'});

        if (d.profile_html) {
            const existing = document.getElementById(`myjob-${d.job_id}`);
            if (existing) {
                existing.outerHTML = d.profile_html;   // edit — রিপ্লেস
            } else {
                document.getElementById('myJobEmpty')?.remove();
                document.getElementById('myJobList')?.insertAdjacentHTML('afterbegin', d.profile_html); // নতুন
            }
        }
        document.getElementById('job_id').value = '';
    })
    .catch(()=>{ btn.disabled=false; btn.innerHTML=orig; Swal.fire({icon:'error',title:'Network error'}); });
}
@endif




window.MY_PROFILE_PIC = @json(Auth::user()->profile_picture ? asset('storage/'.Auth::user()->profile_picture) : null);
window.MY_INITIAL = @json(strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)));

// ==========================================
// COMMENT LIKE
// ==========================================
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

// ==========================================
// COMMENT REPLY
// ==========================================
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


function setPrivacy(value,iconClass,label){document.getElementById('privacyInput').value=value;document.getElementById('privacyLabel').textContent=label;const btn=document.getElementById('privacyBtn');btn.querySelector('i').className='bi '+iconClass+' me-1';}

function setEditPrivacy(value,iconClass,label){document.getElementById('editPrivacyInput').value=value;if(label)document.getElementById('editPrivacyLabel').textContent=label;const btn=document.getElementById('editPrivacyBtn');if(btn&&iconClass)btn.querySelector('i').className='bi '+iconClass+' me-1';}

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


@if($isAlumni || $isTeacher)
{{-- ==================== POST A JOB MODAL (profile edit) ==================== --}}
<div class="modal fade" id="postJobModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg postjob-dialog">
        <div class="modal-content border-0 shadow-lg rounded-4 postjob-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="postJobModalTitle"><i class="bi bi-briefcase-fill text-primary me-1"></i> Post A Job</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="postJobForm" class="postjob-form">
                <div class="modal-body p-4 postjob-body">
                    <input type="hidden" name="id" id="job_id">
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="bb-job-label">Job Title *</label>
                            <input type="text" name="title" id="job_title" class="bb-job-input" placeholder="e.g. Junior Laravel Developer" required>
                        </div>
                        <div class="col-md-5">
                            <label class="bb-job-label">Company *</label>
                            <input type="text" name="company" id="job_company" class="bb-job-input" placeholder="e.g. Tech Soft BD" required>
                        </div>
                        <div class="col-md-6">
                            <label class="bb-job-label">Location</label>
                            <input type="text" name="location" id="job_location" class="bb-job-input" placeholder="e.g. Dhaka / Remote">
                        </div>
                        <div class="col-md-6">
                            <label class="bb-job-label">Job Type *</label>
                            <select name="job_type" id="job_type" class="bb-job-input" required>
                                <option>Full-time</option>
                                <option>Part-time</option>
                                <option>Internship</option>
                                <option>Remote</option>
                                <option>Contract</option>
                                <option>Freelance</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="bb-job-label">Experience</label>
                            <input type="text" name="experience" id="job_experience" class="bb-job-input" placeholder="e.g. 1-2 years / Fresher">
                        </div>
                        <div class="col-md-6">
                            <label class="bb-job-label">Salary</label>
                            <input type="text" name="salary" id="job_salary" class="bb-job-input" placeholder="e.g. 30k-50k BDT / Negotiable">
                        </div>
                        <div class="col-md-6">
                            <label class="bb-job-label">Category</label>
                            <input type="text" name="category" id="job_category" class="bb-job-input" placeholder="e.g. IT, Marketing, Design">
                        </div>
                        <div class="col-md-6">
                            <label class="bb-job-label">Application Deadline</label>
                            <input type="date" name="deadline" id="job_deadline" class="bb-job-input">
                        </div>
                        <div class="col-12">
                            <label class="bb-job-label">Job Description *</label>
                            <textarea name="description" id="job_description" class="bb-job-input" rows="4" placeholder="Describe the role, responsibilities..." required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="bb-job-label">Requirements</label>
                            <textarea name="requirements" id="job_requirements" class="bb-job-input" rows="3" placeholder="Educational qualification, must-haves..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="bb-job-label">Skills <span class="text-muted" style="font-weight:400;">(comma separated)</span></label>
                            <input type="text" name="skills" id="job_skills" class="bb-job-input" placeholder="e.g. PHP, Laravel, MySQL, Git">
                        </div>
                        <div class="col-md-4">
                            <label class="bb-job-label">Apply Via</label>
                            <select name="apply_type" id="job_apply_type" class="bb-job-input">
                                <option value="link">Website Link</option>
                                <option value="email">Email</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="bb-job-label">Apply Link / Email *</label>
                            <input type="text" name="apply_value" id="job_apply_value" class="bb-job-input" placeholder="https://apply.example.com or hr@company.com" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer postjob-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="bb-job-submit-btn" id="jobSubmitBtn" onclick="submitJobForm()"><i class="bi bi-send-fill me-1"></i> Post Job</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif


{{-- Mutual Modal --}}
<div class="modal fade" id="mutualFriendsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Mutual Friends</h6>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2" id="mutualModalBody">
                <div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>
            </div>
        </div>
    </div>
</div>

{{-- Global Emoji Popover --}}
<div id="bbEmojiPopover"><emoji-picker class="light"></emoji-picker></div>

<script>
// ==========================================
// EMOJI PICKER (shared — post + comment)
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
            const target = document.querySelector(btn.getAttribute('data-target'));
            if (!target) return;
            if (popover.style.display === 'block' && currentTarget === target) {
                popover.style.display = 'none'; currentTarget = null; return;
            }
            currentTarget = target;
            const r = btn.getBoundingClientRect();
            popover.style.display = 'block';
            let top = r.bottom + window.scrollY + 6;
            if (r.bottom + 350 > window.innerHeight) top = r.top + window.scrollY - 350 - 6;
            let left = r.left + window.scrollX - 150;
            if (left < 8) left = 8;
            if (left + 320 > window.innerWidth) left = window.innerWidth - 328;
            popover.style.top = top + 'px';
            popover.style.left = left + 'px';
            return;
        }
        if (popover.style.display === 'block' && !popover.contains(ev.target)) {
            popover.style.display = 'none'; currentTarget = null;
        }
    });
})();


function friendAction(action, userId, btnEl) {
    const endpoints = {
        send:     '/friends/send',
        accept:   '/friends/accept',
        decline:  '/friends/decline',
        cancel:   '/friends/cancel',
        unfriend: '/friends/unfriend',
        block:    '/friends/block',
        unblock:  '/friends/unblock',
    };
 
    const confirmMsg = {
        unfriend: 'Remove this person from your friends?',
        block:    "Block this user? They won't be able to find you.",
        cancel:   'Cancel this friend request?',
    };
 
    if (['unfriend', 'block', 'cancel'].includes(action)) {
        if (!confirm(confirmMsg[action])) return;
    }
 
    if (btnEl) btnEl.disabled = true;
 
    fetch(endpoints[action], {
        method: 'POST',
        headers: {
            'Content-Type':  'application/json',
            'Accept':        'application/json',
            'X-CSRF-TOKEN':  document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ user_id: userId }),
    })
    .then(r => r.json())
    .then(d => {
        if (btnEl) btnEl.disabled = false;
        if (!d.success) { alert(d.message || 'Something went wrong.'); return; }
 
        const wrap = document.getElementById('friendBtnWrap-' + userId);
        if (wrap) updateFriendBtn(wrap, d.status, userId);
 
        // sidebar pending request card সরাও
        if (action === 'accept' || action === 'decline') {
            const card = document.getElementById('freq-' + userId);
            if (card) {
                card.style.transition = 'opacity .3s';
                card.style.opacity = '0';
                setTimeout(() => card.remove(), 300);
            }
        }
 
        if (typeof Swal !== 'undefined') {
            Swal.mixin({
                toast: true, position: 'top-end',
                showConfirmButton: false, timer: 2000, timerProgressBar: true
            }).fire({ icon: 'success', title: d.message });
        }
    })
    .catch(() => {
        if (btnEl) btnEl.disabled = false;
        alert('Network error. Please try again.');
    });
}
 
function updateFriendBtn(wrap, status, userId) {
    const btns = {
        none: `<button class="bb-friend-btn bb-friend-add" onclick="friendAction('send',${userId},this)"><i class="bi bi-person-plus-fill"></i> Add Friend</button>`,
        pending_sent: `<button class="bb-friend-btn bb-friend-pending" onclick="friendAction('cancel',${userId},this)"><i class="bi bi-person-check-fill"></i> Request Sent <span class="bb-friend-cancel-hint">· Cancel</span></button>`,
        pending_received: `
            <button class="bb-friend-btn bb-friend-accept" onclick="friendAction('accept',${userId},this)"><i class="bi bi-check-lg"></i> Accept</button>
            <button class="bb-friend-btn bb-friend-decline" onclick="friendAction('decline',${userId},this)"><i class="bi bi-x-lg"></i> Decline</button>`,
        accepted: `<div class="dropdown d-inline-block">
            <button class="bb-friend-btn bb-friend-already dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-people-fill"></i> Friends</button>
            <ul class="dropdown-menu shadow border-0 rounded-3">
                <li><button class="dropdown-item text-danger py-2" onclick="friendAction('unfriend',${userId},this)"><i class="bi bi-person-x me-2"></i> Unfriend</button></li>
                <li><button class="dropdown-item py-2" onclick="friendAction('block',${userId},this)"><i class="bi bi-slash-circle me-2"></i> Block</button></li>
            </ul></div>`,
        blocked: `<button class="bb-friend-btn bb-friend-blocked" onclick="friendAction('unblock',${userId},this)"><i class="bi bi-slash-circle"></i> Blocked · Unblock</button>`,
    };
    if (btns[status]) wrap.innerHTML = btns[status];
}


let _mutualModal2 = null;
document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('mutualFriendsModal');
    if (el) _mutualModal2 = new bootstrap.Modal(el);
});

function showMutualModal() {
    if (!_mutualModal2) return;
    _mutualModal2.show();
    fetch('/friends/{{ $user->id }}/mutual', { headers: { 'Accept': 'application/json' } })
    .then(r => r.json())
    .then(d => {
        if (!d.mutuals || !d.mutuals.length) {
            document.getElementById('mutualModalBody').innerHTML =
                '<p class="text-muted text-center small py-2">No mutual friends.</p>';
            return;
        }
        let html = '';
        d.mutuals.forEach(m => {
            const pic = m.profile_picture
                ? `<img src="/storage/${m.profile_picture}" style="width:100%;height:100%;object-fit:cover;">`
                : m.name.charAt(0).toUpperCase();
            html += `<a href="/profile/${m.id}"
                style="display:flex;align-items:center;gap:10px;padding:9px 0;border-bottom:1px solid #f3f4f8;text-decoration:none;">
                <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#7c73f0);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;overflow:hidden;flex-shrink:0;">${pic}</div>
                <div>
                    <div style="font-size:13.5px;font-weight:700;color:#1e1f24;">${m.name}</div>
                    <div style="font-size:11.5px;color:#6b7280;">${m.department || m.role}</div>
                </div>
            </a>`;
        });
        document.getElementById('mutualModalBody').innerHTML = html;
    });
}

// ==========================================
// REPORT MODAL
// ==========================================
let _reportModal = null;
document.addEventListener('DOMContentLoaded', () => {
    const rmEl = document.getElementById('reportModal');
    if (rmEl) _reportModal = new bootstrap.Modal(rmEl);
});

function bbOpenReport(type, id, name) {
    if (!_reportModal) return;
    document.getElementById('rType').value = type;
    document.getElementById('rId').value   = id;
    document.getElementById('rTargetName').textContent = name || '';
    document.getElementById('rReason').value = '';
    document.getElementById('rReasons').classList.remove('d-none');
    document.getElementById('rDetailsSection').classList.add('d-none');
    _reportModal.show();
}

function rSelectReason(r) {
    document.getElementById('rReason').value = r;
    document.getElementById('rReasons').classList.add('d-none');
    document.getElementById('rDetailsSection').classList.remove('d-none');
}

function rSubmit() {
    fetch('/report', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            type:    document.getElementById('rType').value,
            id:      document.getElementById('rId').value,
            reason:  document.getElementById('rReason').value,
            details: document.getElementById('rDetails').value
        })
    })
    .then(r => r.json())
    .then(d => {
        if (_reportModal) _reportModal.hide();
        Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:2500, timerProgressBar:true })
            .fire({ icon: d.success ? 'success' : 'warning', title: d.message });

        // সফল হলে বাটনের জায়গায় "reported" ব্যাজ বসাও (reload ছাড়া)
        if (d.success) {
            const type = document.getElementById('rType').value;
            const id   = document.getElementById('rId').value;
            const wrap = document.getElementById(type === 'user' ? 'reportBtnWrap-' + id : null);
            if (wrap) {
                wrap.innerHTML = `<span class="badge bg-danger-subtle text-danger border" style="font-size:.72rem;font-weight:700;padding:8px 14px;border-radius:10px;"><i class="bi bi-flag-fill me-1"></i> You reported this User</span>`;
            }
        }
    })
    .catch(() => {
        if (_reportModal) _reportModal.hide();
        Swal.fire({ icon:'error', title:'Network error' });
    });
}

function showDeactivateModal() {
    new bootstrap.Modal(document.getElementById('deactivateModal')).show();
}
function showDeleteModal() {
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

</script>

{{-- Report Modal --}}
<div class="modal fade" id="reportModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold">Report <span id="rTargetName"></span></h6>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">Why are you reporting this?</p>
                <input type="hidden" id="rType"><input type="hidden" id="rId"><input type="hidden" id="rReason">
                <div id="rReasons">
                    <button class="w-100 text-start btn btn-light border mb-2 py-2" onclick="rSelectReason('spam')"><i class="bi bi-envelope-exclamation-fill text-warning me-2"></i> Spam</button>
                    <button class="w-100 text-start btn btn-light border mb-2 py-2" onclick="rSelectReason('harassment')"><i class="bi bi-exclamation-triangle-fill text-danger me-2"></i> Harassment or bullying</button>
                    <button class="w-100 text-start btn btn-light border mb-2 py-2" onclick="rSelectReason('fake')"><i class="bi bi-person-fill-slash text-secondary me-2"></i> Fake / Impersonation</button>
                    <button class="w-100 text-start btn btn-light border mb-2 py-2" onclick="rSelectReason('inappropriate')"><i class="bi bi-shield-fill-exclamation text-danger me-2"></i> Inappropriate content</button>
                    <button class="w-100 text-start btn btn-light border mb-2 py-2" onclick="rSelectReason('other')"><i class="bi bi-three-dots text-muted me-2"></i> Something else</button>
                </div>
                <div id="rDetailsSection" class="d-none">
                    <textarea id="rDetails" class="form-control rounded-3 mt-2" rows="3" placeholder="Add details (optional)..." style="font-size:13px;"></textarea>
                    <div class="d-flex gap-2 mt-3">
                        <button class="btn btn-light btn-sm" onclick="document.getElementById('rDetailsSection').classList.add('d-none');document.getElementById('rReasons').classList.remove('d-none')">Back</button>
                        <button class="btn btn-danger btn-sm px-4 fw-bold" onclick="rSubmit()">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>