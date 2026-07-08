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
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">
    <title>Borobhai.online</title>

    <style>
    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        background-color: #f0f2f5;
        color: #1c1e21;
    }
    .navbar {
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        padding: 0.5rem 1rem;
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
        cursor: pointer;
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
    }
    .sidebar-link.active {
        color: #1877f2;
    }
    .create-post-box {
        background-color: #fff;
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
        overflow: hidden;
        flex-shrink: 0;
    }
    .cpa-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
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
    }
    .fs-7 {
        font-size: 0.85rem !important;
    }
    .fb-bg-gradient-1 {
        background: linear-gradient(45deg, #f321d7, #2196f3) !important;
    }
    .fb-bg-gradient-2 {
        background: linear-gradient(45deg, #ff9800, #ff5722) !important;
    }
    .fb-bg-gradient-3 {
        background: linear-gradient(45deg, #4caf50, #00bcd4) !important;
    }
    .fb-bg-gradient-4 {
        background: linear-gradient(45deg, #1a2a6c, #b21f1f, #fdbb2d) !important;
    }
    .fb-bg-gradient-5 {
        background: linear-gradient(45deg, #00c6ff, #0072ff) !important;
    }
    .fb-color-circle {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: inline-block;
        cursor: pointer;
        border: 2px solid #fff;
        box-shadow: 0 0 4px rgba(0, 0, 0, 0.2);
    }
    .fb-colored-post-render {
        transition: all 0.3s ease;
    }
    #imageLightboxModal {
        z-index: 1090 !important;
    }

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
        --bb-shadow: 0 1px 3px rgba(16, 24, 40, 0.06), 0 1px 2px rgba(16, 24, 40, 0.04);
        --bb-shadow-hover: 0 8px 28px rgba(79, 70, 229, 0.1), 0 2px 6px rgba(16, 24, 40, 0.06);
    }
    .bb-post-card {
        background: var(--bb-card);
        border-radius: var(--bb-radius);
        box-shadow: var(--bb-shadow);
        margin-bottom: 18px;
        overflow: hidden;
        transition: box-shadow 0.25s ease;
    }
    .bb-post-card:hover {
        box-shadow: var(--bb-shadow-hover);
    }
    .bb-post-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px 8px;
    }
    .bb-head-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .bb-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--bb-primary), #7c73f0);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(79, 70, 229, 0.25);
        overflow: hidden;
        padding: 0;
    }
    .bb-avatar:has(img) {
        background: none;
    }
    .bb-avatar-sm {
        width: 30px;
        height: 30px;
        font-size: 12px;
        box-shadow: none;
    }
    .bb-avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }
    .bb-head-meta {
        line-height: 1.25;
    }
    .bb-author {
        margin: 0;
        font-weight: 700;
        font-size: 14.5px;
        color: var(--bb-ink);
        letter-spacing: -0.2px;
    }
    .bb-author-link {
        text-decoration: none;
        display: inline-block;
    }
    .bb-author-link:hover {
        color: var(--bb-primary);
        text-decoration: underline;
    }
    .bb-time {
        font-size: 11.5px;
        color: var(--bb-muted);
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .bb-more-btn {
        border: none;
        background: transparent;
        color: var(--bb-muted);
        width: 34px;
        height: 34px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.15s ease;
    }
    .bb-more-btn:hover {
        background: var(--bb-bg);
        color: var(--bb-ink);
    }
    .bb-author-role {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: 0.2px;
        padding: 1px 8px;
        border-radius: 12px;
        width: fit-content;
        margin: 1px 0;
    }
    .bb-author-role-alumni {
        background: #fef3c7;
        color: #d97706;
    }
    .bb-author-role-student {
        background: #eef2ff;
        color: #4f46e5;
    }
    .bb-author-role-teacher {
        background: #f3e8ff;
        color: #7c3aed;
    }
    .bb-caption {
        padding: 2px 16px 12px;
        font-size: 14.5px;
        line-height: 1.55;
        color: var(--bb-ink);
        word-break: break-word;
    }
    .bb-color-caption {
        margin: 4px 16px 12px;
        border-radius: 12px;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #fff;
        font-weight: 700;
        font-size: 22px;
        padding: 24px;
        word-break: break-word;
    }
    .bb-color-caption-sm {
        min-height: 120px;
        font-size: 16px;
        margin: 8px 14px;
    }
    .bb-media-zone {
        background: #000;
        overflow: hidden;
        line-height: 0;
    }
    .bb-media-single {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #000;
        width: 100%;
    }
    .bb-single-img {
        width: 100%;
        max-height: 560px;
        object-fit: contain;
        display: block;
        cursor: pointer;
    }
    .bb-video-wrap {
        position: relative;
        width: 100%;
        display: flex;
        justify-content: center;
        background: #000;
    }
    .bb-inline-video {
        width: 100%;
        max-height: 560px;
        object-fit: contain;
        display: block;
        background: #000;
    }
    .bb-expand-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 5;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        border: none;
        background: rgba(0, 0, 0, 0.55);
        color: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.2s ease;
        font-size: 14px;
    }
    .bb-video-wrap:hover .bb-expand-btn {
        opacity: 1;
    }
    .bb-grid {
        display: flex;
        gap: 3px;
        width: 100%;
    }
    .bb-grid-2 {
        height: 300px;
    }
    .bb-grid-3 {
        height: 340px;
    }
    .bb-grid-3-side {
        display: flex;
        flex-direction: column;
        gap: 3px;
        flex: 1;
        min-width: 0;
    }
    .bb-grid-4 {
        flex-wrap: wrap;
        height: 480px;
    }
    .bb-grid-4 .bb-tile {
        width: calc(50% - 1.5px);
        height: calc(50% - 1.5px);
        flex: none;
    }
    .bb-tile {
        position: relative;
        flex: 1;
        min-width: 0;
        overflow: hidden;
        background: #000;
    }
    .bb-tile-big {
        flex: 2;
    }
    .bb-tile-media {
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: pointer;
        display: block;
    }
    .bb-play-badge {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 54px;
        height: 54px;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.55);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #fff;
        font-size: 1.8rem;
        transition:
            transform 0.15s ease,
            background 0.15s ease;
    }
    .bb-play-badge:hover {
        transform: translate(-50%, -50%) scale(1.08);
        background: rgba(0, 0, 0, 0.7);
    }
    .bb-play-sm {
        width: 40px;
        height: 40px;
        font-size: 1.2rem;
    }
    .bb-more-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.55);
        color: #fff;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        cursor: pointer;
        line-height: 1;
    }
    .bb-shared {
        margin: 0 16px 12px;
        border: 1px solid var(--bb-line);
        border-radius: 12px;
        overflow: hidden;
    }
    .bb-shared-head {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 14px 6px;
    }
    .bb-stats {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 18px 8px;
        font-size: 13px;
        color: var(--bb-muted);
    }
    .bb-like-stat {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .bb-like-bubble {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: var(--bb-primary);
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
    }
    .bb-stat-link {
        cursor: pointer;
        transition: color 0.15s ease;
    }
    .bb-stat-link:hover {
        color: var(--bb-primary);
        text-decoration: underline;
    }
    .bb-actions {
        display: flex;
        padding: 4px 8px;
        border-top: 1px solid var(--bb-line);
    }
    .bb-action-btn {
        flex: 1;
        border: none;
        background: transparent;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 4px;
        border-radius: 8px;
        margin: 4px 2px;
        color: var(--bb-muted);
        font-weight: 600;
        font-size: 13.5px;
        transition:
            background 0.15s ease,
            color 0.15s ease;
    }
    .bb-action-btn i {
        font-size: 17px;
    }
    .bb-action-btn:hover {
        background: var(--bb-bg);
    }
    .bb-action-btn.active-like {
        color: var(--bb-primary);
    }
    .bb-action-btn.active-save {
        color: #f59e0b;
    }
    @media (max-width: 576px) {
        .bb-action-btn span {
            display: none;
        }
        .bb-action-btn i {
            font-size: 19px;
        }
        .bb-grid-2,
        .bb-grid-3 {
            height: 220px;
        }
        .bb-grid-4 {
            height: 360px;
        }
    }
    .bb-role-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.3px;
        padding: 6px 14px;
        border-radius: 20px;
        border: 1.5px solid transparent;
        transition: transform 0.15s ease;
    }
    .bb-role-student {
        background: #eef2ff;
        color: #4f46e5;
        border-color: #c7d2fe;
    }
    .bb-role-alumni {
        background: #fef3c7;
        color: #d97706;
        border-color: #fde68a;
    }
    .bb-role-teacher {
        background: #f3e8ff;
        color: #7c3aed;
        border-color: #ddd6fe;
    }
    .bb-post-job-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: linear-gradient(135deg, #4f46e5, #7c73f0);
        color: #fff;
        border: none;
        border-radius: 20px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
        transition: all 0.15s ease;
    }
    .bb-post-job-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.4);
    }
    @media (max-width: 767px) {
        .bb-post-job-btn {
            padding: 8px 11px;
            border-radius: 50%;
        }
    }
    .bb-job-label {
        display: block;
        font-size: 12.5px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 5px;
    }
    .bb-job-input {
        width: 100%;
        border: 1.5px solid #e4e6eb;
        border-radius: 10px;
        padding: 9px 12px;
        font-size: 13.5px;
        outline: none;
        transition:
            border-color 0.15s,
            box-shadow 0.15s;
        background: #fff;
    }
    .bb-job-input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
    }
    textarea.bb-job-input {
        resize: vertical;
    }
    .bb-job-submit-btn {
        background: linear-gradient(135deg, #4f46e5, #7c73f0);
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 9px 22px;
        font-size: 14px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        transition: all 0.15s;
    }
    .bb-job-submit-btn:hover {
        box-shadow: 0 4px 14px rgba(79, 70, 229, 0.4);
    }
    .bb-job-submit-btn:disabled {
        opacity: 0.6;
    }
    .postjob-dialog {
        height: calc(100vh - 3.5rem);
    }
    .postjob-content {
        max-height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .postjob-form {
        display: flex;
        flex-direction: column;
        min-height: 0;
        flex: 1 1 auto;
        overflow: hidden;
    }
    .postjob-body {
        overflow-y: auto;
        flex: 1 1 auto;
        min-height: 0;
    }
    .postjob-footer {
        flex: 0 0 auto;
    }
    @media (max-width: 576px) {
        .postjob-dialog {
            height: calc(100vh - 1rem);
        }
    }
    .bb-jobcard {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(16, 24, 40, 0.06);
        padding: 16px 18px;
        margin-bottom: 18px;
        border: 1px solid var(--bb-line);
        transition: box-shadow 0.2s;
    }
    .bb-jobcard:hover {
        box-shadow: 0 8px 24px rgba(79, 70, 229, 0.1);
    }
    .bb-jobcard-top {
        display: flex;
        align-items: flex-start;
        gap: 13px;
    }
    .bb-jobcard-logo {
        width: 52px;
        height: 52px;
        border-radius: 13px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 23px;
        font-weight: 800;
    }
    .bb-jobcard-headinfo {
        flex-grow: 1;
        min-width: 0;
    }
    .bb-jobcard-title {
        font-size: 16.5px;
        font-weight: 800;
        color: var(--bb-ink);
        text-decoration: none;
        letter-spacing: -0.3px;
        display: inline-block;
        line-height: 1.25;
    }
    .bb-jobcard-title:hover {
        color: var(--bb-primary);
    }
    .bb-jobcard-company {
        font-size: 13px;
        color: var(--bb-muted);
        margin: 2px 0 0;
        font-weight: 500;
    }
    .bb-job-expiring {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11.5px;
        font-weight: 700;
        color: #ea580c;
        margin-top: 5px;
    }
    .bb-job-expired {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11.5px;
        font-weight: 700;
        color: #dc2626;
        margin-top: 5px;
    }
    .bb-jobcard-more {
        border: none;
        background: transparent;
        color: var(--bb-muted);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        cursor: pointer;
        flex-shrink: 0;
    }
    .bb-jobcard-more:hover {
        background: var(--bb-bg);
    }
    .bb-jobcard-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        margin: 13px 0;
    }
    .bb-jobcard-tag,
    .bb-jobcard-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        font-weight: 600;
        padding: 5px 11px;
        border-radius: 8px;
    }
    .bb-jobcard-pill {
        background: var(--bb-bg);
        color: #4b5563;
    }
    .bb-jobcard-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        background: var(--bb-primary-soft);
        color: var(--bb-primary);
        border: none;
        border-radius: 10px;
        padding: 10px;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s;
    }
    .bb-jobcard-btn:hover {
        background: var(--bb-primary);
        color: #fff;
    }
    .bb-jobcard-posted {
        font-size: 11.5px;
        color: var(--bb-muted);
        margin: 3px 0 0;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .bb-job-save-btn {
        border: none;
        background: transparent;
        color: var(--bb-muted);
        width: 34px;
        height: 34px;
        border-radius: 50%;
        cursor: pointer;
        flex-shrink: 0;
        font-size: 17px;
        transition: all 0.15s;
    }
    .bb-job-save-btn:hover {
        background: var(--bb-bg);
        color: #f59e0b;
    }
    .bb-job-save-btn.saved {
        color: #f59e0b;
    }
    .bb-jobcard-foot {
        margin-top: 12px;
        padding-top: 11px;
        border-top: 1px solid var(--bb-line);
        font-size: 12.5px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .bb-foot-expiring {
        color: #ea580c;
    }
    .bb-foot-expired {
        color: #dc2626;
    }
    .bb-teacher-feed {
        background: linear-gradient(180deg, #faf8ff 0%, #f4f0fb 100%) !important;
    }
    .bb-teacher-feed .navbar {
        border-top: 3px solid #7c3aed;
    }
    .bb-teacher-feed .bb-role-badge {
        background: #f3e8ff !important;
        color: #7c3aed !important;
        border-color: #ddd6fe !important;
    }
    .bb-teacher-ribbon {
        display: flex;
        align-items: center;
        gap: 9px;
        background: linear-gradient(135deg, #7c3aed, #6d28d9);
        color: #fff;
        border-radius: 14px;
        padding: 13px 18px;
        margin-bottom: 16px;
        box-shadow: 0 4px 16px rgba(124, 58, 237, 0.25);
    }
    .bb-teacher-ribbon i {
        font-size: 22px;
    }
    .bb-teacher-ribbon .bb-tr-title {
        font-size: 15px;
        font-weight: 700;
    }
    .bb-teacher-ribbon .bb-tr-sub {
        font-size: 12px;
        opacity: 0.85;
    }
    .bb-side-card {
        background: var(--bb-card);
        border-radius: var(--bb-radius);
        box-shadow: var(--bb-shadow);
        overflow: hidden;
    }
    .bb-side-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px 8px;
    }
    .bb-side-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--bb-ink);
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .bb-side-link {
        font-size: 12px;
        color: var(--bb-primary);
        text-decoration: none;
        font-weight: 600;
    }
    .bb-side-link:hover {
        text-decoration: underline;
    }
    .bb-side-body {
        padding: 4px 10px 12px;
    }
    .bb-job-item {
        display: flex;
        gap: 10px;
        padding: 8px 6px;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.15s ease;
        text-decoration: none;
    }
    .bb-job-item:hover {
        background: var(--bb-bg);
    }
    .bb-job-logo {
        width: 40px;
        height: 40px;
        border-radius: 9px;
        flex-shrink: 0;
        background: var(--bb-primary-soft);
        color: var(--bb-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 17px;
    }
    .bb-job-info {
        min-width: 0;
    }
    .bb-job-title {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--bb-ink);
        margin: 0 0 1px;
    }
    .bb-job-company {
        font-size: 12px;
        color: var(--bb-muted);
        margin: 0 0 3px;
    }
    .bb-job-tag {
        font-size: 10.5px;
        color: #16a34a;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }
    .bb-active-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 6px;
        border-radius: 10px;
        cursor: pointer;
        transition: background 0.15s ease;
        text-decoration: none;
    }
    .bb-active-item:hover {
        background: var(--bb-bg);
    }
    .bb-active-avatar {
        position: relative;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        flex-shrink: 0;
        background: linear-gradient(135deg, var(--bb-primary), #7c73f0);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
        overflow: hidden;
    }
    /* active-now dot — JS দিয়ে inline style করব, ::after নয় */
    .bb-active-meta {
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 0;
    }
    .bb-active-name {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--bb-ink);
    }
    .bb-mini-badge {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        font-size: 9.5px;
        font-weight: 700;
        letter-spacing: 0.2px;
        padding: 1px 7px;
        border-radius: 12px;
        width: fit-content;
        text-transform: uppercase;
    }
    .bb-mini-alumni {
        background: #fef3c7;
        color: #d97706;
    }
    .bb-mini-student {
        background: #eef2ff;
        color: #4f46e5;
    }
    .bb-mini-teacher {
        background: #f3e8ff;
        color: #7c3aed;
    }
    .bb-suggest-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 6px;
        border-radius: 10px;
        transition: background 0.15s ease;
    }
    .bb-suggest-item:hover {
        background: var(--bb-bg);
    }
    .bb-suggest-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        flex-shrink: 0;
        background: linear-gradient(135deg, var(--bb-primary), #7c73f0);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
    }
    .bb-suggest-info {
        flex-grow: 1;
        min-width: 0;
    }
    .bb-suggest-name {
        font-size: 13.5px;
        font-weight: 700;
        color: var(--bb-ink);
        margin: 0 0 1px;
    }
    .bb-suggest-role {
        font-size: 11.5px;
        color: var(--bb-muted);
        margin: 0;
    }
    .bb-connect-btn {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        flex-shrink: 0;
        border: 1.5px solid var(--bb-primary);
        background: #fff;
        color: var(--bb-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 15px;
        transition: all 0.15s ease;
    }
    .bb-connect-btn:hover {
        background: var(--bb-primary);
        color: #fff;
    }
    .bb-right-sidebar {
        position: sticky;
        top: 70px;
        max-height: calc(100vh - 85px);
        overflow-y: auto;
        padding-bottom: 10px;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    .bb-right-sidebar::-webkit-scrollbar {
        width: 6px;
    }
    .bb-right-sidebar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .bb-friend-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 0.88rem;
        font-weight: 700;
        padding: 9px 18px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
        margin-right: 6px;
    }
    .bb-friend-add {
        background: #4f46e5;
        color: #fff;
    }
    .bb-friend-add:hover {
        background: #4338ca;
    }
    .bb-friend-pending {
        background: #eef2ff;
        color: #4f46e5;
        border: 1.5px solid #c7d2fe;
    }
    .bb-friend-pending:hover {
        background: #fef2f2;
        color: #dc2626;
    }
    .bb-friend-cancel-hint {
        font-size: 0.78rem;
        opacity: 0.75;
    }
    .bb-friend-accept {
        background: #059669;
        color: #fff;
    }
    .bb-friend-decline {
        background: #f3f4f8;
        color: #374151;
    }
    .bb-friend-already {
        background: #f3f4f8;
        color: #374151;
    }
    .bb-friend-blocked {
        background: #fee2e2;
        color: #dc2626;
    }
    .bb-search-wrap {
        position: relative;
    }
    .bb-search-box {
        background: #f0f2f5;
        border-radius: 50px;
        padding: 0.45rem 1rem;
        display: flex;
        align-items: center;
        width: 260px;
        transition: width 0.2s ease;
    }
    .bb-search-box:focus-within {
        width: 320px;
        background: #e4e6eb;
    }
    .bb-search-box input {
        background: transparent;
        border: none;
        outline: none;
        margin-left: 8px;
        font-size: 0.9rem;
        width: 100%;
    }
    .bb-search-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        width: 360px;
        background: #fff;
        border-radius: 14px;
        z-index: 9999;
        display: none;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(16, 24, 40, 0.14);
        border: 1px solid #eceef1;
    }
    .bb-search-dropdown.show {
        display: block;
        animation: sdIn 0.18s ease;
    }
    @keyframes sdIn {
        from {
            opacity: 0;
            transform: translateY(-6px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .bb-sd-label {
        font-size: 11px;
        font-weight: 700;
        color: #6b7280;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        padding: 12px 14px 6px;
    }
    .bb-sd-item {
        display: flex;
        align-items: center;
        gap: 11px;
        padding: 9px 14px;
        cursor: pointer;
        transition: background 0.12s;
        text-decoration: none;
    }
    .bb-sd-item:hover,
    .bb-sd-item.active {
        background: #f3f4f8;
    }
    .bb-sd-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        flex-shrink: 0;
        overflow: hidden;
        background: linear-gradient(135deg, #4f46e5, #7c73f0);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 17px;
    }
    .bb-sd-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .bb-sd-info {
        flex-grow: 1;
        min-width: 0;
    }
    .bb-sd-name {
        font-size: 0.9rem;
        font-weight: 700;
        color: #1e1f24;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .bb-sd-sub {
        font-size: 0.78rem;
        color: #6b7280;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 1px;
    }
    .bb-sd-topic {
        font-size: 0.74rem;
        color: #4f46e5;
        margin-top: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .bb-sd-rolechip {
        font-size: 9.5px;
        font-weight: 700;
        padding: 1px 7px;
        border-radius: 12px;
        flex-shrink: 0;
    }
    .bb-sd-student {
        background: #eef2ff;
        color: #4f46e5;
    }
    .bb-sd-alumni {
        background: #fef3c7;
        color: #d97706;
    }
    .bb-sd-teacher {
        background: #f3e8ff;
        color: #7c3aed;
    }
    .bb-sd-footer {
        border-top: 1px solid #eceef1;
        padding: 10px 14px;
        font-size: 0.86rem;
        font-weight: 700;
        color: #4f46e5;
        text-align: center;
        text-decoration: none;
        display: block;
        transition: background 0.12s;
    }
    .bb-sd-footer:hover {
        background: #f3f4f8;
    }
    .bb-sd-spinner {
        text-align: center;
        padding: 20px;
        color: #6b7280;
        font-size: 0.88rem;
    }
    .bb-sd-empty {
        text-align: center;
        padding: 20px 14px;
        color: #9ca3af;
        font-size: 0.86rem;
    }
    .bb-emoji-btn {
        border: none;
        background: transparent;
        cursor: pointer;
        color: #65676b;
        font-size: 20px;
        padding: 4px 8px;
        border-radius: 8px;
        transition:
            background 0.15s,
            color 0.15s;
        display: inline-flex;
        align-items: center;
    }
    .bb-emoji-btn:hover {
        background: #f0f2f5;
        color: #f59e0b;
    }
    #bbEmojiPopover {
        position: absolute;
        z-index: 3000;
        display: none;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.18);
        border-radius: 12px;
        overflow: hidden;
    }
    #bbEmojiPopover emoji-picker {
        --background: #fff;
        --border-color: #e4e6eb;
        --indicator-color: #4f46e5;
        --num-columns: 8;
        --emoji-size: 1.3rem;
        height: 340px;
    }
    .comment-like-btn {
        color: #65676b;
        cursor: pointer;
        font-weight: 600;
    }
    .comment-like-btn:hover {
        text-decoration: underline;
    }
    .comment-like-btn.liked {
        color: #4f46e5;
    }
    .comment-like-count {
        font-size: 11px;
    }
    .reply-row {
        padding-left: 6px;
    }
    .replies-zone {
        border-left: 2px solid #eceef1;
        padding-left: 10px;
        margin-left: 6px;
    }
    .reply-input-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .reply-input-box {
        flex-grow: 1;
        border: 1px solid #e4e6eb;
        border-radius: 20px;
        background: #f0f2f5;
        padding: 6px 14px;
        font-size: 12.5px;
        outline: none;
    }
    .reply-input-box:focus {
        border-color: #4f46e5;
        background: #fff;
    }
    .reply-send-btn {
        border: none;
        background: transparent;
        color: #4f46e5;
        cursor: pointer;
        font-size: 16px;
        padding: 2px 6px;
    }
    .reply-send-btn:disabled {
        opacity: 0.4;
        cursor: default;
    }
    .comment-mention {
        color: #4f46e5;
        font-weight: 600;
    }
    .reply-field {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-grow: 1;
        background: #f0f2f5;
        border: 1px solid #e4e6eb;
        border-radius: 20px;
        padding: 2px 6px 2px 12px;
    }
    .reply-field:focus-within {
        border-color: #4f46e5;
        background: #fff;
    }
    .reply-field .reply-input-box {
        border: none;
        background: transparent;
        padding: 5px 4px;
    }
    .reply-mention-tag {
        display: inline-flex;
        align-items: center;
        background: #e0e7ff;
        color: #4f46e5;
        font-weight: 600;
        font-size: 11.5px;
        padding: 2px 8px;
        border-radius: 12px;
        white-space: nowrap;
    }

    /* ===== CHAT BOX ===== */
        .bb-chat-box {
        width: 320px;
        background: #fff;
        border-radius: 12px 12px 0 0;
        box-shadow: 0 -2px 20px rgba(16, 24, 40, 0.16);
        display: flex;
        flex-direction: column;
        height: 500px;
        transition: height 0.25s ease, box-shadow 0.2s ease;
        border: 1px solid #e4e6eb;
        border-bottom: none;
        overflow: hidden;
    }
    .bb-chat-box.minimized {
        height: 52px;
    }
    .bb-chat-box.minimized .bb-chat-body-zone,
    .bb-chat-box.minimized .bb-chat-footer-zone {
        display: none;
    }
    .bb-chat-box.bb-chat-highlight {
        animation: bbChatBoxPulse 1s ease-in-out infinite;
    }
    @keyframes bbChatBoxPulse {
        0%, 100% { box-shadow: 0 -2px 20px rgba(16,24,40,0.16); }
        50% { box-shadow: 0 -2px 26px rgba(79,70,229,0.55), 0 0 0 3px rgba(79,70,229,0.35); }
    }
            .bb-chat-head {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        cursor: pointer;
        flex-shrink: 0;
        background: #fff;
        border-bottom: 1px solid #f0f2f5;
        border-radius: 12px 12px 0 0;
    }
    .bb-chat-head:hover {
        background: #f3f4f8;
    }
    .bb-chat-head-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        flex-shrink: 0;
        background: linear-gradient(135deg, #4f46e5, #7c73f0);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        overflow: hidden;
        position: relative;
    }
    .bb-chat-head-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .bb-chat-online-dot {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #22c55e;
        border: 2px solid #fff;
    }
    .bb-chat-head-info {
        flex-grow: 1;
        min-width: 0;
    }
    .bb-chat-head-name {
        font-size: 13.5px;
        font-weight: 700;
        color: #1e1f24;
        margin: 0;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .bb-chat-head-status {
        font-size: 11px;
        color: #6b7280;
        margin: 0;
    }
    .bb-chat-head-actions {
        display: flex;
        gap: 4px;
        flex-shrink: 0;
    }
    .bb-chat-head-btn {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: none;
        background: transparent;
        color: #6b7280;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: background 0.12s;
    }
    .bb-chat-head-btn:hover {
        background: #e4e6eb;
    }
    .bb-chat-search {
        padding: 8px 10px;
        border-bottom: 1px solid #f0f2f5;
        flex-shrink: 0;
    }
    .bb-chat-search-input {
        width: 100%;
        border: none;
        background: #f0f2f5;
        border-radius: 20px;
        padding: 6px 12px;
        font-size: 12.5px;
        outline: none;
    }
    .bb-chat-messages {
        flex-grow: 1;
        overflow-y: auto;
        padding: 10px 12px;
        display: flex;
        flex-direction: column;
        gap: 6px;
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }
    .bb-chat-messages::-webkit-scrollbar {
        width: 4px;
    }
    .bb-chat-messages::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .bb-chat-msg {
        max-width: 80%;
        padding: 8px 12px;
        border-radius: 18px;
        font-size: 13px;
        line-height: 1.45;
        word-break: break-word;
    }
    .bb-chat-msg.mine {
        background: #4f46e5;
        color: #fff;
        align-self: flex-end;
        border-radius: 18px 18px 4px 18px;
    }
    .bb-chat-msg.theirs {
        background: #f0f2f5;
        color: #1e1f24;
        align-self: flex-start;
        border-radius: 18px 18px 18px 4px;
    }
    .bb-chat-footer {
        padding: 8px 10px;
        border-top: 1px solid #f0f2f5;
        display: flex;
        align-items: center;
        gap: 7px;
        flex-shrink: 0;
    }
    .bb-chat-input {
        flex-grow: 1;
        border: none;
        background: #f0f2f5;
        border-radius: 20px;
        padding: 8px 14px;
        font-size: 13px;
        outline: none;
        resize: none;
        max-height: 80px;
        overflow-y: auto;
    }
    .bb-chat-input:focus {
        background: #e9eaf0;
    }
    .bb-chat-send-btn {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: none;
        background: #4f46e5;
        color: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.12s;
    }
    .bb-chat-send-btn:hover {
        background: #4338ca;
    }
    .bb-chat-attach-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: none;
        background: transparent;
        color: #6b7280;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.12s;
    }
    .bb-chat-attach-btn:hover {
        background: #f0f2f5;
        color: #4f46e5;
    }
    .bb-chat-no-msg {
        text-align: center;
        color: #9ca3af;
        font-size: 12px;
        padding: 20px 0;
    }
    #postsFeedContainer .bb-post-head {
        flex-wrap: nowrap;
    }
    /* ===== NOTIFICATION ===== */
    .bb-notif-item {
        display:flex; align-items:flex-start; gap:12px;
        padding:11px 14px; cursor:pointer; position:relative;
        transition:background .12s; border-bottom:1px solid #f3f4f6;
    }
    .bb-notif-item:hover  { background:#f5f7ff; }
    .bb-notif-unread      { background:#eef2ff; }
    .bb-notif-unread .bb-notif-msg { font-weight:600; }

    .bb-notif-avatar {
        width:46px; height:46px; min-width:46px; border-radius:50%;
        overflow:hidden; background:linear-gradient(135deg,#4f46e5,#7c73f0);
        color:#fff; display:flex; align-items:center; justify-content:center;
        font-weight:700; font-size:17px; flex-shrink:0;
    }
    .bb-notif-body { flex:1; min-width:0; }
    .bb-notif-msg  { font-size:13.5px; color:#1e1f24; line-height:1.45; word-break:break-word; margin-bottom:3px; }
    .bb-notif-time { font-size:11.5px; color:#6b7280; display:flex; align-items:center; gap:5px; }
    .bb-notif-dot  { width:9px; height:9px; border-radius:50%; background:#2563eb; flex-shrink:0; align-self:center; margin-left:4px; }

    .bb-notif-loading { padding:32px 16px; text-align:center; color:#9ca3af; font-size:13px; }
    .bb-notif-loading i { display:block; font-size:20px; margin-bottom:6px; }

    #notifList::-webkit-scrollbar       { width:4px; }
    #notifList::-webkit-scrollbar-track { background:transparent; }
    #notifList::-webkit-scrollbar-thumb { background:#d1d5db; border-radius:4px; }
    #notifList::-webkit-scrollbar-thumb:hover { background:#9ca3af; }

    @keyframes bellShake {
        0%,100% { transform:rotate(0); }
        20% { transform:rotate(-15deg); } 40% { transform:rotate(15deg); }
        60% { transform:rotate(-10deg); } 80% { transform:rotate(10deg); }
    }
    .bell-shake { animation:bellShake .5s ease; }
    .bb-notif-empty   { text-align:center; padding:40px 16px; color:#9ca3af; }
    .bb-notif-empty i { font-size:2rem; display:block; margin-bottom:8px; }
    
    /* Notification scrollbar */
    #notifList::-webkit-scrollbar       { width: 4px; }
    #notifList::-webkit-scrollbar-track { background: transparent; }
    #notifList::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
    #notifList::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    
    /* Bell shake animation */
    @keyframes bellShake {
        0%,100% { transform: rotate(0); }
        20%      { transform: rotate(-15deg); }
        40%      { transform: rotate(15deg); }
        60%      { transform: rotate(-10deg); }
        80%      { transform: rotate(10deg); }
    }
    .bell-shake { animation: bellShake .5s ease; }
    
    .bb-notif-empty { text-align: center; padding: 40px 16px; color: #9ca3af; }
    .bb-notif-empty i { font-size: 2rem; display: block; margin-bottom: 8px; }
    #messengerPanel {
    box-shadow: 0 10px 32px rgba(0,0,0,0.12) !important;
    }

    .msg-conv-item {
        padding: 10px 12px;
        border-bottom: 1px solid #f0f2f5;
        cursor: pointer;
        transition: background 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .msg-conv-item:hover {
        background: #f9fafb;
    }

    .msg-conv-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4f46e5, #7c73f0);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        flex-shrink: 0;
        overflow: hidden;
    }

    .msg-conv-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .msg-conv-info {
        flex: 1;
        min-width: 0;
    }

    .msg-conv-name {
        font-weight: 600;
        font-size: 13px;
        color: #1e1f24;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .msg-conv-last {
        font-size: 12px;
        color: #6b7280;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 2px;
    }

    .msg-conv-badge {
        background: #4f46e5;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        min-width: 20px;
        height: 20px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .msg-conv-time {
        font-size: 11px;
        color: #9ca3af;
        flex-shrink: 0;
    }
    
    .bb-msg-row { display:flex; align-items:center; gap:2px; position:relative; margin-bottom:2px; }
    .bb-msg-row.mine { justify-content:flex-end; }
    .bb-msg-row.theirs { justify-content:flex-start; }
    .bb-msg-bubble-wrap { display:flex; flex-direction:column; max-width:75%; position:relative; }
    .bb-msg-actions { display:none; align-items:center; gap:1px; flex-shrink:0; white-space:nowrap; }
    .bb-msg-row:hover .bb-msg-actions {
    display: flex;
    }
    /* মোবাইলে hover কাজ করে না, তাই মেসেজে tap করলেও দেখাবে */
    .bb-msg-row.bb-touch-active .bb-msg-actions {
        display: flex;
    }
    .bb-msg-action-btn { width:22px;height:22px;border:none;background:transparent;color:#6b7280;cursor:pointer;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:12px;flex-shrink:0;padding:0; }
    .bb-msg-action-btn:hover { background:#e5e7eb; }
    .bb-msg-menu { position:absolute; top:100%; margin-top:2px; background:#fff; border-radius:10px; box-shadow:0 4px 20px rgba(0,0,0,.18); z-index:300; width:160px; padding:4px; display:none; }
    .bb-msg-menu.show { display:block; }
    .bb-msg-menu-item { padding:7px 10px; font-size:12.5px; cursor:pointer; border-radius:6px; display:flex; align-items:center; gap:8px; color:#1e1f24; white-space:nowrap; }
    .bb-msg-menu-item:hover { background:#f3f4f6; }
    .bb-msg-menu-item.danger { color:#dc2626; }
    
    .bb-react-bar { position:absolute; top:-36px; background:#fff; border-radius:20px; box-shadow:0 2px 10px rgba(0,0,0,.18); padding:4px 6px; display:none; gap:3px; z-index:300; width:max-content; }
    .bb-react-bar.show { display:flex; }
    .bb-msg-row.mine .bb-react-bar { right:0; }
    .bb-msg-row.theirs .bb-react-bar { left:0; }

    .bb-react-emoji { cursor:pointer; font-size:15px; padding:2px 3px; border-radius:50%; transition:transform .1s; }
    .bb-react-emoji:hover { transform:scale(1.3); }
    .bb-msg-reactions { display:flex; gap:2px; margin-top:2px; flex-wrap:wrap; }
    .bb-msg-reaction-pill { background:#fff; border:1px solid #e5e7eb; border-radius:10px; font-size:10px; padding:0 5px; display:flex; align-items:center; gap:2px; cursor:pointer; }
    .bb-reply-preview-inline { border-left:3px solid #4f46e5; background:rgba(79,70,229,.08); padding:3px 8px; border-radius:6px; margin-bottom:3px; font-size:11px; cursor:pointer; color:#374151; }
    .bb-reply-compose { display:none; align-items:center; justify-content:space-between; background:#f0f2f5; border-radius:8px; padding:6px 10px; margin-bottom:6px; font-size:12px; }
    .bb-search-box-chat { display:none; padding:8px 10px; border-bottom:1px solid #f0f2f5; }
    .bb-search-box-chat input { width:100%; border:1px solid #e5e7eb; border-radius:16px; padding:5px 12px; font-size:12.5px; outline:none; }
    .bb-gallery-modal-overlay, .bb-forward-modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,.6); z-index:5000; display:none; align-items:center; justify-content:center; }
    .bb-gallery-modal, .bb-forward-modal { background:#fff; border-radius:14px; width:360px; max-height:75vh; display:flex; flex-direction:column; overflow:hidden; }
    .bb-gallery-modal-head, .bb-forward-modal-head { padding:14px 16px; border-bottom:1px solid #eceef1; display:flex; align-items:center; justify-content:space-between; font-weight:700; }
    .bb-gallery-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:3px; overflow-y:auto; padding:8px; }
    .bb-gallery-grid img, .bb-gallery-grid video { width:100%; height:100px; object-fit:cover; cursor:pointer; border-radius:4px; }
    .bb-forward-contact-item { display:flex; align-items:center; gap:10px; padding:9px 14px; cursor:pointer; }
    .bb-forward-contact-item:hover { background:#f3f4f6; }
    .bb-forward-search { padding:8px 14px; border-bottom:1px solid #f0f2f5; }
    .bb-forward-search input { width:100%; border:1px solid #e5e7eb; border-radius:16px; padding:6px 12px; font-size:12.5px; outline:none; }

    .bb-msg-media-grid {
    display: grid;
    gap: 3px;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 4px;
    max-width: 260px;
    }
    .bb-msg-media-grid-1 { grid-template-columns: 1fr; }
    .bb-msg-media-grid-1 .bb-msg-media-cell { height: 220px; }

    .bb-msg-media-grid-2 { grid-template-columns: 1fr 1fr; }
    .bb-msg-media-grid-2 .bb-msg-media-cell { height: 150px; }

    .bb-msg-media-grid-3 {
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 1fr 1fr;
    }
    .bb-msg-media-grid-3 .bb-msg-media-cell:first-child {
        grid-row: span 2;
        height: 153px;
    }
    .bb-msg-media-grid-3 .bb-msg-media-cell:not(:first-child) { height: 75px; }

    .bb-msg-media-grid-4 {
        grid-template-columns: 1fr 1fr;
        grid-template-rows: 1fr 1fr;
    }
    .bb-msg-media-grid-4 .bb-msg-media-cell { height: 108px; }

    .bb-msg-media-cell {
        position: relative;
        overflow: hidden;
        cursor: pointer;
        background: #000;
    }
    .bb-msg-media-cell img,
    .bb-msg-media-cell video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .bb-msg-media-play {
        position: absolute; top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 32px; height: 32px;
        background: rgba(0,0,0,.55);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
    }
    .bb-msg-media-play i { color: #fff; font-size: 15px; }
    .bb-msg-media-more {
        position: absolute; inset: 0;
        background: rgba(0,0,0,.55);
        color: #fff; font-weight: 700; font-size: 20px;
        display: flex; align-items: center; justify-content: center;
    }
</style>

 
</head>

@php $role = Auth::user()->role; @endphp
<body class="{{ $role === 'teacher' ? 'bb-teacher-feed' : '' }}">


<div id="bbJumpOverlay" style="display:none;position:fixed;inset:0;z-index:99999;background:rgba(243,244,248,.30);backdrop-filter:blur(2px) saturate(110%);-webkit-backdrop-filter:blur(3.5px) saturate(110%);align-items:center;justify-content:center;flex-direction:column;">
    <div style="display:flex;flex-direction:column;align-items:center;gap:16px;padding:28px 36px;border-radius:20px;background:rgba(255,255,255,.55);box-shadow:0 8px 40px rgba(79,70,229,.18);border:1px solid rgba(255,255,255,.6);">
        <div style="width:54px;height:54px;border:5px solid rgba(79,70,229,.15);border-top-color:#4f46e5;border-radius:50%;animation:bbSpin .8s linear infinite;"></div>
        <div id="bbJumpText" style="font-size:14.5px;font-weight:700;color:#4f46e5;letter-spacing:.2px;">Please Wait We Taking You To Tour Post…</div>
    </div>
</div>

<style>
@keyframes bbSpin { to { transform: rotate(360deg); } }
</style>

{{-- ==================== NAVBAR ==================== --}}
<nav class="navbar navbar-expand-md sticky-top">
    <div class="container-fluid">
        <div class="d-flex align-items-center gap-2">
            <a style="color:black;" class="navbar-brand m-0 fw-bold" href="{{ route('home') }}">Borobhai.online</a>
            <div class="bb-search-wrap d-none d-lg-block">
                <div class="bb-search-box">
                    <i class="bi bi-search text-muted"></i>
                    <input type="text" id="bbLiveSearch" placeholder="Search Borobhai..." autocomplete="off">
                </div>
                <div class="bb-search-dropdown" id="bbSearchDropdown"></div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2 ms-auto">
            @if($canPostJobs ?? false)
                <button type="button" class="bb-post-job-btn" onclick="openPostJobModal()">
                    <i class="bi bi-briefcase-fill"></i>
                    <span class="d-none d-md-inline">Post A Job</span>
                </button>
            @endif
            
            @if($role === 'alumni')
                <span class="bb-role-badge d-none d-sm-inline-flex bb-role-alumni"><i class="bi bi-mortarboard-fill"></i> Alumni Feed</span>
            <a href="{{ route('profile.show') }}" class="create-post-avatar" style="text-decoration:none;">
                        @if(Auth::user()->profile_picture)
                            <img src="{{ asset('storage/'.Auth::user()->profile_picture) }}" alt="me" class="cpa-img">
                        @else
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        @endif
            </a>
            @elseif($role === 'teacher')
                <span class="bb-role-badge d-none d-sm-inline-flex bb-role-teacher"><i class="bi bi-easel2-fill"></i> Teacher Feed</span>
            @else
                <span class="bb-role-badge d-none d-sm-inline-flex bb-role-student"><i class="bi bi-backpack-fill"></i> Student Feed</span>
            @endif
            <a href="{{ route('search.index') }}" class="nav-icon-btn d-md-none"><i class="bi bi-search"></i></a>

            {{-- Messenger Icon (নোটিফিকেশন এর পরে) --}}
            <div class="nav-item dropdown" id="messengerDropdown">
                <button class="nav-link position-relative" type="button" id="messengerBtn" onclick="toggleMessengerDropdown()" style="border:none;background:transparent;color:#6b7280;font-size:20px;">
                    <i class="fa-brands fa-facebook-messenger"></i>
                    <span id="messengerBadge" class="badge bg-danger position-absolute top-0 start-100 translate-middle" style="display:none;font-size:10px;">0</span>
                </button>
                
                {{-- Conversation List Dropdown --}}
                <div id="messengerPanel" class="position-absolute bg-white rounded shadow-lg" style="display:none;top:100%;right:0;width:360px;max-height:500px;overflow-y:auto;z-index:1050;border:1px solid #e5e7eb;">
                    <div style="padding:12px;border-bottom:1px solid #e5e7eb;">
                        <h6 style="margin:0;font-weight:700;font-size:15px;">Messages</h6>
                    </div>
                    <div id="conversationList" style="max-height:440px;overflow-y:auto;">
                        <div style="text-align:center;color:#9ca3af;padding:20px;">Loading...</div>
                    </div>
                </div>
            </div>

            {{-- NOTIFICATION BELL --}}
            <div class="position-relative" id="notifWrap">       
            {{-- Bell button --}}
            <button class="nav-icon-btn border-0 position-relative" id="notifBellBtn" onclick="toggleNotifDropdown()">
                <i class="bi bi-bell-fill" style="font-size:20px;"></i>
                <span id="notifBadge"
                    style="display:none;position:absolute;top:-4px;right:-4px;
                            min-width:18px;height:18px;padding:0 4px;border-radius:9px;
                            background:#dc2626;color:#fff;font-size:10px;font-weight:700;
                            line-height:18px;text-align:center;border:2px solid #fff;">0</span>
            </button>
        
            {{-- Dropdown panel --}}
            <div id="notifDropdown"
                style="display:none;position:absolute;right:0;top:calc(100% + 10px);
                        width:380px;max-width:calc(100vw - 24px);
                        background:#fff;border-radius:16px;
                        box-shadow:0 8px 40px rgba(16,24,40,.16);
                        border:1px solid #e5e7eb;z-index:9999;overflow:hidden;">
        
                {{-- Header --}}
                <div style="padding:14px 16px 10px;border-bottom:1px solid #f3f4f6;
                            display:flex;align-items:center;justify-content:space-between;">
                    <span style="font-size:17px;font-weight:800;color:#111827;letter-spacing:-.3px;">Notifications</span>
                    <button onclick="markAllRead()"
                            style="font-size:12px;font-weight:600;color:#4f46e5;
                                border:none;background:transparent;cursor:pointer;padding:4px 8px;
                                border-radius:6px;transition:background .15s;"
                            onmouseover="this.style.background='#eef2ff'" onmouseout="this.style.background='transparent'">
                        Mark all read
                    </button>
                </div>
        
                {{-- List --}}
                <div id="notifList" style="max-height:460px;overflow-y:auto;scroll-behavior:smooth;">
                    <div style="padding:32px 16px;text-align:center;color:#9ca3af;font-size:13px;">
                        <i class="bi bi-arrow-clockwise" style="font-size:20px;display:block;margin-bottom:6px;"></i>
                        Loading…
                    </div>
                </div>
        
                {{-- Footer --}}
                <div style="padding:10px 16px;border-top:1px solid #f3f4f6;text-align:center;">
                   <a href="{{ route('notifications.all') }}" style="font-size:13px;font-weight:600;color:#4f46e5;text-decoration:none;">
                        See all notifications
                    </a>
                </div>
            </div>
        </div>
            {{-- NOTIFICATION BELL END --}}

                            {{-- Profile dropdown --}}
                            <div class="dropdown">
                                <button class="nav-icon-btn border-0" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-fill"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow">
                                    <li><span class="dropdown-item-text fw-bold text-dark">{{ Auth::user()->name }}</span></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a href="{{ route('profile.show') }}" class="dropdown-item"><i class="bi bi-person-circle me-2"></i>View Profile</a></li>
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

                        </div>{{-- ms-auto div end --}}
                    </div>
                </nav>
        </div>
    </div>
</nav>

<div class="container-fluid mt-3">
    <div class="row px-md-2">

        {{-- ==================== LEFT SIDEBAR ==================== --}}
        <div class="col-md-3 d-none d-md-block position-sticky" style="top:70px;height:fit-content;">
            <div class="d-flex flex-column gap-1">
                <a href="{{ route('home') }}" class="sidebar-link active"><i class="bi bi-house-door-fill text-primary"></i><span>Home</span></a>
                <a href="{{ route('friends.index') }}" class="sidebar-link"><i class="bi bi-people-fill text-info"></i><span>See Friend List</span></a>

                @php
                    $pendingRequests = \App\Models\Friendship::where('receiver_id', Auth::id())
                        ->where('status', 'pending')
                        ->with('sender:id,name,role,profile_picture')
                        ->latest()->limit(5)->get();
                @endphp
                @if($pendingRequests->count() > 0)
                <div class="bb-side-card mb-3">
                    <div class="bb-side-head">
                        <span class="bb-side-title">
                            <i class="bi bi-person-plus-fill text-primary"></i> Friend Requests
                            <span class="badge bg-primary rounded-pill ms-1" style="font-size:10px;">{{ $pendingRequests->count() }}</span>
                        </span>
                    </div>
                    <div class="bb-side-body">
                        @foreach($pendingRequests as $req)
                        <div class="bb-suggest-item" id="freq-{{ $req->sender->id }}">
                            <a href="{{ route('profile.view', $req->sender) }}" class="bb-suggest-avatar" style="text-decoration:none;overflow:hidden;">
                                @if($req->sender->profile_picture)
                                    <img src="{{ asset('storage/'.$req->sender->profile_picture) }}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                                @else
                                    {{ strtoupper(substr($req->sender->name,0,1)) }}
                                @endif
                            </a>
                            <div class="bb-suggest-info">
                                <a href="{{ route('profile.view', $req->sender) }}" class="bb-suggest-name" style="text-decoration:none;">{{ $req->sender->name }}</a>
                                <p class="bb-suggest-role">{{ ucfirst($req->sender->role) }}</p>
                                <div class="d-flex gap-1 mt-1">
                                    <button class="btn btn-primary btn-sm py-0 px-2" style="font-size:11px;" onclick="friendAction('accept', {{ $req->sender->id }}, this)">Accept</button>
                                    <button class="btn btn-light btn-sm py-0 px-2" style="font-size:11px;" onclick="friendAction('decline', {{ $req->sender->id }}, this)">Decline</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <a href="{{ route('saved.index') }}" class="sidebar-link"><i class="bi bi-bookmark-heart-fill text-warning"></i><span>Saved</span></a>
                <a href="{{ route('jobs.myApplications') }}" class="sidebar-link"><i class="bi bi-briefcase-fill text-primary"></i><span>Job History</span></a>
                <a href="{{ route('search.index') }}" class="sidebar-link"><i class="bi bi-search text-primary"></i><span>Search People</span></a>
            </div>
        </div>

        {{-- ==================== FEED ==================== --}}
        <div class="col-12 col-md-6">
            @if($role === 'teacher')
            <div class="bb-teacher-ribbon">
                <i class="bi bi-easel2-fill"></i>
                <div>
                    <div class="bb-tr-title">Teacher Workspace</div>
                    <div class="bb-tr-sub">Share knowledge, research & resources with the community</div>
                </div>
            </div>
            @endif

            <div class="create-post-box mb-3">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <a href="{{ route('profile.show') }}" class="create-post-avatar" style="text-decoration:none;">
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
                    <button type="button" class="btn btn-link post-action-btn border-0 bg-transparent text-decoration-none shadow-none" data-bs-toggle="modal" data-bs-target="#createPostModal" onclick="resetPostBg(); setTimeout(()=>document.getElementById('postImageInput').click(),400);">
                        <i class="bi bi-images text-success fs-5"></i><span class="text-muted fs-7 fw-semibold">Photo/video</span>
                    </button>
                    <button type="button" class="btn btn-link post-action-btn border-0 bg-transparent text-decoration-none shadow-none" data-bs-toggle="modal" data-bs-target="#createPostModal" onclick="toggleColorPlates();">
                        <i class="bi bi-palette-fill text-danger fs-5"></i><span class="text-muted fs-7 fw-semibold">Background color</span>
                    </button>
                </div>
            </div>

            {{-- Feed Filter --}}
            <div class="d-flex gap-2 mb-3">
                <button id="filterAll" onclick="setFeedFilter('all')" class="btn btn-sm fw-700" style="border-radius:20px;font-size:13px;font-weight:600;background:#4f46e5;color:#fff;border:none;padding:7px 16px;">
                    <i class="bi bi-grid-fill me-1"></i> All Posts
                </button>
                <button id="filterFriends" onclick="setFeedFilter('friends')" class="btn btn-sm fw-700" style="border-radius:20px;font-size:13px;font-weight:600;background:#fff;color:#6b7280;border:1.5px solid #eceef1;padding:7px 16px;">
                    <i class="bi bi-people-fill me-1"></i> Friends
                </button>
                <button id="filterPublic" onclick="setFeedFilter('public')" class="btn btn-sm fw-700" style="border-radius:20px;font-size:13px;font-weight:600;background:#fff;color:#6b7280;border:1.5px solid #eceef1;padding:7px 16px;">
                    <i class="bi bi-globe-americas me-1"></i> Public
                </button>
            </div>

            <div id="postsFeedContainer">
                @forelse($feedItems as $item)
                    @if($item['type'] === 'job')
                        @include('partials.job-card', ['job' => $item['model'], 'appliedJobIds' => $appliedJobIds ?? []])
                    @else
                        @include('partials.post-card', ['post' => $item['model']])
                    @endif
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

            <div id="feedLoader" class="text-center py-4 d-none">
                <div class="spinner-border text-primary" role="status" style="width:2rem;height:2rem;"><span class="visually-hidden">Loading...</span></div>
            </div>
            <div id="feedEndMessage" class="text-center text-muted py-4 d-none"><i class="bi bi-check2-circle me-1"></i> You're all caught up!</div>
            <div id="feedMeta" data-has-more="{{ $hasMore ? '1' : '0' }}"></div>
        </div>

        {{-- ==================== RIGHT SIDEBAR ==================== --}}
        <div class="col-md-3 d-none d-md-block bb-right-sidebar">

            @if($role === 'teacher' || $role === 'alumni')
            <div class="bb-side-card mb-3">
                <div class="bb-side-head">
                    <span class="bb-side-title"><i class="bi bi-briefcase-fill text-primary"></i> Recent Jobs</span>
                    <a href="{{ route('jobs.all') }}" class="bb-side-link">See all</a>
                </div>
                <div class="bb-side-body">
                    @forelse($recentJobs ?? [] as $job)
                        @php $jt=strtolower($job->job_type); $lc=str_contains($jt,'intern')?'background:#fff7ed;color:#ea580c;':(str_contains($jt,'part')?'background:#eff6ff;color:#2563eb;':'background:var(--bb-primary-soft);color:var(--bb-primary);'); @endphp
                        <a href="{{ route('jobs.show', $job) }}" class="bb-job-item">
                            <div class="bb-job-logo" style="{{ $lc }}">{{ strtoupper(substr($job->company,0,1)) }}</div>
                            <div class="bb-job-info">
                                <h6 class="bb-job-title">{{ \Illuminate\Support\Str::limit($job->title, 28) }}</h6>
                                <p class="bb-job-company">{{ \Illuminate\Support\Str::limit($job->company, 22) }}</p>
                                @if($job->is_expired) <span class="bb-job-tag" style="color:#dc2626;"><i class="bi bi-x-circle"></i> Deadline over</span>
                                @elseif($job->is_expiring_soon) <span class="bb-job-tag" style="color:#ea580c;"><i class="bi bi-alarm"></i> Expiring soon</span>
                                @else <span class="bb-job-tag"><i class="bi bi-briefcase"></i> {{ $job->job_type }}</span> @endif
                            </div>
                        </a>
                    @empty
                        <p class="text-muted text-center small py-3 mb-0">No jobs posted yet.</p>
                    @endforelse
                </div>
            </div>
            @endif

            {{-- Active Now --}}
            <div class="bb-side-card mb-3">
                <div class="bb-side-head">
                    <span class="bb-side-title"><i class="bi bi-circle-fill text-success" style="font-size:9px;"></i> Active Now</span>
                </div>
                <div class="bb-side-body" id="activeNowZone">
                    @forelse($activeUsers ?? [] as $au)
                        @php
                            $isOnline = $au->last_seen && $au->last_seen >= now()->subSeconds(40);
                            $lastSeenText = \App\Http\Controllers\PostController::formatLastSeen($au->last_seen);
                            $dotColor = $isOnline ? '#22c55e' : '#9ca3af';
                        @endphp
                        <a href="#" class="bb-active-item"
                           onclick="event.preventDefault(); openChatBox({{ $au->id }}, '{{ e($au->name) }}', '{{ $au->profile_picture ? asset('storage/'.$au->profile_picture) : '' }}', '{{ $lastSeenText }}', '{{ $isOnline ? '1' : '0' }}', '{{ $au->hashid }}')">
                            <div class="bb-active-avatar">
                                @if($au->profile_picture)
                                    <img src="{{ asset('storage/'.$au->profile_picture) }}" style="width:100%;height:100%;object-fit:cover;">
                                @else
                                    {{ strtoupper(substr($au->name,0,1)) }}
                                @endif
                                <span style="position:absolute;bottom:0;right:0;width:11px;height:11px;border-radius:50%;background:{{ $dotColor }};border:2px solid #fff;display:block;flex-shrink:0;"></span>
                            </div>
                            <div class="bb-active-meta">
                                <span class="bb-active-name">{{ $au->name }}</span>
                                <span class="bb-mini-badge" style="background:{{ $isOnline ? '#dcfce7' : '#f3f4f6' }};color:{{ $isOnline ? '#16a34a' : '#6b7280' }};">
                                    @if($isOnline)<i class="bi bi-circle-fill" style="font-size:7px;color:#16a34a;"></i>@endif
                                    {{ $lastSeenText }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="text-muted small px-2 py-3 text-center">No friends active recently.</div>
                    @endforelse
                </div>
            </div>

            {{-- Suggested Contact --}}
            <div class="bb-side-card mb-3">
                <div class="bb-side-head">
                    <span class="bb-side-title"><i class="bi bi-person-plus-fill text-primary"></i> Suggested Contact</span>
                    <a href="{{ route('friends.suggested') }}" class="bb-side-link">See all</a>
                </div>
                <div class="bb-side-body" id="suggestedPeopleZone">
                    @forelse($suggested ?? [] as $su)
                    <div class="bb-suggest-item" id="suggest-{{ $su->id }}">
                        <a href="{{ route('profile.view', hashid($su->id)) }}" class="bb-suggest-avatar" style="text-decoration:none;overflow:hidden;">
                            @if($su->profile_picture)
                                <img src="{{ asset('storage/'.$su->profile_picture) }}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">
                            @else
                                {{ strtoupper(substr($su->name,0,1)) }}
                            @endif
                        </a>
                        <div class="bb-suggest-info">
                            <a href="{{ route('profile.view', hashid($su->id)) }}" class="bb-suggest-name" style="text-decoration:none;color:inherit;">{{ $su->name }}</a>
                            @if($su->department || $su->section)
                            <p class="bb-suggest-role mb-0">{{ $su->department }}@if($su->department && $su->section) · @endif{{ $su->section }}</p>
                            @endif
                            @if($su->role === 'alumni' && $su->current_company)
                                <p class="mb-0" style="font-size:10.5px;color:#16a34a;font-weight:600;"><i class="bi bi-briefcase-fill"></i> {{ \Illuminate\Support\Str::limit($su->current_company, 18) }}</p>
                            @elseif($su->role === 'alumni')
                                <p class="mb-0" style="font-size:10.5px;color:#d97706;font-weight:600;"><i class="bi bi-mortarboard-fill"></i> Fresh Graduate</p>
                            @elseif($su->role === 'student')
                                <p class="mb-0" style="font-size:10.5px;color:#4f46e5;font-weight:600;"><i class="bi bi-backpack-fill"></i> Student</p>
                            @elseif($su->role === 'teacher')
                                <p class="mb-0" style="font-size:10.5px;color:#7c3aed;font-weight:600;"><i class="bi bi-easel2-fill"></i> Teacher</p>
                            @endif
                            @if(isset($su->mutual) && $su->mutual > 0)
                                <p class="mb-0" style="font-size:10.5px;color:#6b7280;cursor:pointer;" onclick="showMutualFriends({{ $su->id }}, '{{ e($su->name) }}')">
                                    <i class="bi bi-people-fill text-primary"></i> {{ $su->mutual }} mutual
                                </p>
                            @endif
                        </div>
                        @if($su->is_pending)
                            <button type="button" class="bb-connect-btn" style="background:#4f46e5;border-color:#4f46e5;color:#fff;" onclick="suggestAction('cancel', {{ $su->id }}, this)" title="Cancel Request"><i class="bi bi-check-lg"></i></button>
                        @else
                            <button type="button" class="bb-connect-btn" onclick="suggestAction('send', {{ $su->id }}, this)" title="Add Friend"><i class="bi bi-person-plus"></i></button>
                        @endif
                    </div>
                    @empty
                        <div class="text-muted small px-2 py-3 text-center">No suggestions right now.</div>
                    @endforelse
                </div>
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
                        <div class="create-post-avatar">
                            @if(Auth::user()->profile_picture) <img src="{{ asset('storage/'.Auth::user()->profile_picture) }}" alt="me" class="cpa-img">
                            @else {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }} @endif
                            
                        </div>
                        <div>
                            <h6 class="m-0 fw-bold" style="font-size:14px;">{{ Auth::user()->name ?? 'User' }}</h6>
                            <div class="dropdown mt-1">
                            <button class="btn btn-light btn-sm border py-1 px-2 dropdown-toggle" style="font-size:11px;font-weight:600;" data-bs-toggle="dropdown" id="privacyBtn">
                                <i class="bi bi-globe-americas me-1 text-primary"></i>
                                <span id="privacyLabel">Public</span>
                            </button>
                            <ul class="dropdown-menu shadow border-0 rounded-3" style="font-size:13px;min-width:160px;">
                                <li>
                                    <a class="dropdown-item py-2" href="#" onclick="setPrivacy('public','bi-globe-americas text-primary','Public')">
                                        <i class="bi bi-globe-americas text-primary me-2"></i> Public
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="#" onclick="setPrivacy('friends','bi-people-fill text-success','Friends')">
                                        <i class="bi bi-people-fill text-success me-2"></i> Friends
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2" href="#" onclick="setPrivacy('only_me','bi-lock-fill text-warning','Only Me')">
                                        <i class="bi bi-lock-fill text-warning me-2"></i> Only Me
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <input type="hidden" name="privacy" id="privacyInput" value="public">
                        </div>
                    </div>
                    <div id="postInputWrapper" class="p-1 rounded bg-transparent">
                        <textarea id="postContent" name="content" class="form-control border-0 bg-transparent shadow-none" rows="4" placeholder="Start a post..." style="resize:none;font-size:14px;"></textarea>
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
                            <button type="button" class="btn btn-light btn-sm rounded-circle p-2" id="triggerUploadBtn"><i class="bi bi-images text-success"></i></button>
                            <button type="button" class="btn btn-light btn-sm rounded-circle p-2" onclick="toggleColorPlates();"><i class="bi bi-palette text-danger"></i></button>
                            <button type="button" class="bb-emoji-btn" data-target="#postContent" title="Emoji"><i class="bi bi-emoji-smile text-warning"></i></button>
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
                            @if(Auth::user()->profile_picture) <img src="{{ asset('storage/'.Auth::user()->profile_picture) }}" alt="me" class="cpa-img">
                            @else {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }} @endif
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
        <li>
            <a class="dropdown-item py-2" href="#"
               onclick="setEditPrivacy('public','bi-globe-americas text-primary','Public')">
                <i class="bi bi-globe-americas text-primary me-2"></i> Public
            </a>
        </li>
        <li>
            <a class="dropdown-item py-2" href="#"
               onclick="setEditPrivacy('friends','bi-people-fill text-success','Friends')">
                <i class="bi bi-people-fill text-success me-2"></i> Friends
            </a>
        </li>
        <li>
            <a class="dropdown-item py-2" href="#"
               onclick="setEditPrivacy('only_me','bi-lock-fill text-warning','Only Me')">
                <i class="bi bi-lock-fill text-warning me-2"></i> Only Me
            </a>
        </li>
    </ul>
</div>
<input type="hidden" id="editPrivacyInput" value="public">
                        </div>
                    </div>
                    <div id="editPostInputWrapper" class="p-1 rounded bg-transparent mb-2">
                        <textarea id="editPostContent" name="content" class="form-control border-0 bg-transparent shadow-none" rows="4" placeholder="What's on your mind?" style="resize:none;font-size:14px;"></textarea>
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
                                <button type="button" class="btn btn-light btn-sm rounded-circle p-2" onclick="document.getElementById('editMediaInput').click()"><i class="bi bi-images text-success"></i></button>
                                <button type="button" class="btn btn-light btn-sm rounded-circle p-2" onclick="toggleEditColorPlates()"><i class="bi bi-palette text-danger"></i></button>
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
    </div>

    {{-- Search input —  নতুন যোগ করো --}}
    <div class="position-relative mb-2">
        <input type="text"
               id="messengerSearchInput"
               placeholder="Search friends..."
               oninput="searchMessengerContacts()"
               class="form-control form-control-sm rounded-pill"
               style="font-size:12.5px;padding-left:32px;border-color:#e4e6eb;">
        <i class="bi bi-search position-absolute"
           style="left:11px;top:50%;transform:translateY(-50%);font-size:12px;color:#9ca3af;pointer-events:none;"></i>
    </div>

    <div id="messengerContacts" class="d-flex gap-3 overflow-auto pb-2" style="scrollbar-width:none;">
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

{{-- Comment Modal --}}
<div class="modal fade" id="commentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content rounded-4 border-0 shadow-lg" style="max-height:90vh;">
            <div class="modal-header border-bottom py-2">
                <h5 class="modal-title fw-bold mx-auto" style="font-size:17px;">Comments</h5>
                <button type="button" class="btn-close ms-0" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <div id="commentModalPostPreview" class="p-3 border-bottom"></div>
                <div class="px-3 pt-2 pb-1"><small class="text-muted fw-semibold" id="commentModalCount" style="font-size:12px;"></small></div>
                <div id="commentModalList" class="px-3 pb-3">
                    <div class="text-center text-muted py-4"><div class="spinner-border spinner-border-sm text-primary"></div><div class="small mt-2">Loading comments...</div></div>
                </div>
                <div id="commentModalViewMore" class="px-3 pb-3 d-none">
                    <button type="button" class="btn btn-link btn-sm text-muted text-decoration-none p-0 fw-semibold" style="font-size:13px;" id="commentModalViewMoreBtn" data-offset="0">
                        <i class="bi bi-arrow-down-circle me-1"></i> View more comments
                    </button>
                </div>
            </div>
            <div class="modal-footer border-top p-2 flex-column align-items-stretch">
                <div id="commentEditNotice" class="d-none d-flex align-items-center justify-content-between px-2 py-1 mb-2 rounded" style="background:#eef2ff;font-size:12px;">
                    <span class="text-primary fw-semibold"><i class="bi bi-pencil-square me-1"></i> Editing comment</span>
                    <button type="button" class="btn btn-sm btn-link text-danger p-0 text-decoration-none fw-semibold" onclick="cancelCommentEdit()"><i class="bi bi-x-lg"></i> Cancel</button>
                </div>
                <form id="commentModalForm" class="d-flex align-items-center gap-2 w-100">
                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0 overflow-hidden" style="width:34px;height:34px;font-size:13px;">
                        @if(Auth::user()->profile_picture) <img src="{{ asset('storage/'.Auth::user()->profile_picture) }}" alt="me" style="width:100%;height:100%;object-fit:cover;">
                        @else {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }} @endif
                    </div>
                    <div class="input-group align-items-center bg-light rounded-pill px-3 py-1 w-100 border">
                        <input type="hidden" id="commentModalPostId">
                        <input type="text" id="commentModalInput" class="form-control border-0 bg-transparent shadow-none py-1" placeholder="Write a comment..." style="font-size:13px;" autocomplete="off">
                        <button type="button" class="bb-emoji-btn p-0 me-1" data-target="#commentModalInput" title="Emoji" style="font-size:17px;"><i class="bi bi-emoji-smile"></i></button>
                        <button type="submit" class="btn btn-link p-0 text-primary ms-1 shadow-none border-0 d-flex align-items-center"><i class="bi bi-send-fill" style="font-size:17px;"></i></button>
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
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" style="z-index:50;"></button>
            <div class="modal-body p-0">
                <div id="lightboxCarousel" class="carousel slide" data-bs-ride="false" data-bs-touch="false" data-bs-interval="false">
                    <div class="carousel-inner" id="lightboxInner"></div>
                </div>
            </div>
            <div class="d-flex justify-content-center align-items-center gap-3 py-3" id="lightboxNavBar">
                <button type="button" class="btn btn-light rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;" onclick="lightboxPrev()"><i class="bi bi-chevron-left fw-bold"></i></button>
                <span id="lightboxCounter" class="text-white small fw-bold"></span>
                <button type="button" class="btn btn-light rounded-circle d-flex align-items-center justify-content-center" style="width:44px;height:44px;" onclick="lightboxNext()"><i class="bi bi-chevron-right fw-bold"></i></button>
            </div>
        </div>
    </div>
</div>

{{-- Post A Job Modal (alumni only) --}}
@if($canPostJobs ?? false)
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
                        <div class="col-md-7"><label class="bb-job-label">Job Title *</label><input type="text" name="title" id="job_title" class="bb-job-input" placeholder="e.g. Junior Laravel Developer" required></div>
                        <div class="col-md-5"><label class="bb-job-label">Company *</label><input type="text" name="company" id="job_company" class="bb-job-input" placeholder="e.g. Tech Soft BD" required></div>
                        <div class="col-md-6"><label class="bb-job-label">Location</label><input type="text" name="location" id="job_location" class="bb-job-input" placeholder="e.g. Dhaka / Remote"></div>
                        <div class="col-md-6"><label class="bb-job-label">Job Type *</label>
                            <select name="job_type" id="job_type" class="bb-job-input" required>
                                <option>Full-time</option><option>Part-time</option><option>Internship</option><option>Remote</option><option>Contract</option><option>Freelance</option>
                            </select>
                        </div>
                        <div class="col-md-6"><label class="bb-job-label">Experience</label><input type="text" name="experience" id="job_experience" class="bb-job-input" placeholder="e.g. 1-2 years / Fresher"></div>
                        <div class="col-md-6"><label class="bb-job-label">Salary</label><input type="text" name="salary" id="job_salary" class="bb-job-input" placeholder="e.g. 30k-50k BDT / Negotiable"></div>
                        <div class="col-md-6"><label class="bb-job-label">Category</label><input type="text" name="category" id="job_category" class="bb-job-input" placeholder="e.g. IT, Marketing, Design"></div>
                        <div class="col-md-6"><label class="bb-job-label">Application Deadline</label><input type="date" name="deadline" id="job_deadline" class="bb-job-input"></div>
                        <div class="col-12"><label class="bb-job-label">Job Description *</label><textarea name="description" id="job_description" class="bb-job-input" rows="4" placeholder="Describe the role, responsibilities..." required></textarea></div>
                        <div class="col-12"><label class="bb-job-label">Requirements</label><textarea name="requirements" id="job_requirements" class="bb-job-input" rows="3" placeholder="Educational qualification, must-haves..."></textarea></div>
                        <div class="col-12"><label class="bb-job-label">Skills <span class="text-muted fw-normal">(comma separated)</span></label><input type="text" name="skills" id="job_skills" class="bb-job-input" placeholder="e.g. PHP, Laravel, MySQL, Git"></div>
                        <div class="col-md-4"><label class="bb-job-label">Apply Via</label><select name="apply_type" id="job_apply_type" class="bb-job-input"><option value="link">Website Link</option><option value="email">Email</option></select></div>
                        <div class="col-md-8"><label class="bb-job-label">Apply Link / Email *</label><input type="text" name="apply_value" id="job_apply_value" class="bb-job-input" placeholder="https://apply.example.com or hr@company.com" required></div>
                    </div>
                </div>
                <div class="modal-footer postjob-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="bb-job-submit-btn" id="jobSubmitBtn"><i class="bi bi-send-fill me-1"></i> Post Job</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- Mutual Friends Modal --}}
<div class="modal fade" id="mutualFriendsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold fs-6" id="mutualModalTitle">Mutual Friends</h6>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-2" id="mutualModalBody">
                <div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>
            </div>
        </div>
    </div>
</div>

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

{{-- Chat Boxes Container --}}
<div id="chatBoxesContainer" style="position:fixed;bottom:0;right:16px;display:flex;align-items:flex-end;gap:10px;z-index:2000;"></div>

{{-- Global Emoji Popover --}}
<div id="bbEmojiPopover"><emoji-picker class="light"></emoji-picker></div>

{{-- ==================== SCRIPTS ==================== --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


{{-- UPDATE MAIN JS DATA START --}}
<script>
// ============================================================
// ALUMNI DASHBOARD — MAIN JAVASCRIPT
// resources/views/alumni/dashboard.blade.php
// ============================================================


// ============================================================
// SECTION 1: GLOBAL STATE
// সব var (let নয়) — TDZ error এড়াতে
// ============================================================
var selectedMediaFiles   = [];
var bootstrapEditModal   = null;
var bootstrapShareModal  = null;
var bootstrapLightboxModal = null;
var bootstrapCommentModal = null;
var isUploading          = false;
var removedImages        = [];
var removedVideos        = [];
var editSelectedFiles    = [];
var lastSelectedBg       = '';
var lastEditSelectedBg   = '';
var commentEditState     = { editing: false, commentId: null };
var _mutualModal         = null;
var _reportModal         = null;
var feedLoading          = false;
var feedPage             = 1;
var currentFeedFilter    = 'all';
var _messengerConvCache = [];

// ============================================================
// SECTION 2: DOM READY INIT
// ============================================================
document.addEventListener('DOMContentLoaded', function () {

    if ('scrollRestoration' in history) history.scrollRestoration = 'manual';

    var params = new URLSearchParams(location.search);
    var openComments = params.get('open_comments');
    var gotoPost     = params.get('goto_post');

    // URL এখনই পরিষ্কার করি — reload এ আর trigger হবে না
    if (openComments || gotoPost) {
        history.replaceState(null, '', location.pathname);
    }

    // ── goto_post: post এ scroll + highlight ──────────────────
     if (gotoPost) {
        showJumpOverlay('Taking you to the post…');
        var tries = 0;
        (function tryScroll() {
            var card = document.getElementById('postCard-' + gotoPost);
            if (card) {
                hideJumpOverlay();
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                card.style.transition = 'box-shadow .35s ease';
                card.style.boxShadow  = '0 0 0 3px #4f46e5';
                setTimeout(function () { card.style.boxShadow = ''; }, 2500);
                return;
            }
            // Post এখনো DOM এ নেই — আরও feed এনে খুঁজি
            if (typeof loadMorePosts === 'function') loadMorePosts();
            if (tries++ < 30) setTimeout(tryScroll, 600);
            else hideJumpOverlay();
        })();
    }

    // ── open_comments: comment modal খোলা ────────────────────
    if (openComments) {
        showJumpOverlay('Opening comments…');
        var tries2 = 0;
        (function tryOpen() {
            var card = document.getElementById('postCard-' + openComments);
            if (card && typeof openCommentModal === 'function') {
                hideJumpOverlay();
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(function () { openCommentModal(openComments); }, 400);
                return;
            }
            if (typeof loadMorePosts === 'function') loadMorePosts();
            if (tries2++ < 30) setTimeout(tryOpen, 600);
            else hideJumpOverlay();
        })();
    }

    // ── Legacy hash support (#postCard-X) ────────────────────
    if (window.location.hash && window.location.hash.startsWith('#postCard-')) {
        var targetId = window.location.hash.substring(1);
        history.replaceState(null, '', location.pathname);
        showJumpOverlay('Taking you to the post…');
        var tries3 = 0;
        (function tryHash() {
            var target = document.getElementById(targetId);
            if (target) {
                hideJumpOverlay();
                target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                target.style.transition = 'box-shadow .35s ease';
                target.style.boxShadow  = '0 0 0 3px #4f46e5';
                setTimeout(function () { target.style.boxShadow = ''; }, 2500);
                return;
            }
            if (typeof loadMorePosts === 'function') loadMorePosts();
            if (tries3++ < 30) setTimeout(tryHash, 600);
            else hideJumpOverlay();
        })();
    }

    // Session scroll top
    if (sessionStorage.getItem('scrollToTop')) {
        sessionStorage.removeItem('scrollToTop');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // Bootstrap modal instances
    bootstrapEditModal      = new bootstrap.Modal(document.getElementById('editPostModal'));
    bootstrapShareModal     = new bootstrap.Modal(document.getElementById('fbShareModal'));
    bootstrapLightboxModal  = new bootstrap.Modal(document.getElementById('imageLightboxModal'));

    var cmEl = document.getElementById('commentModal');
    if (cmEl) bootstrapCommentModal = new bootstrap.Modal(cmEl);

    var mmEl = document.getElementById('mutualFriendsModal');
    if (mmEl) _mutualModal = new bootstrap.Modal(mmEl);

    var rmEl = document.getElementById('reportModal');
    if (rmEl) _reportModal = new bootstrap.Modal(rmEl);

    // Lightbox: video pause on slide
    document.getElementById('lightboxCarousel').addEventListener('slide.bs.carousel', function () {
        document.querySelectorAll('#lightboxInner video').forEach(function (v) { v.pause(); });
    });
    document.getElementById('imageLightboxModal').addEventListener('hidden.bs.modal', function () {
        document.querySelectorAll('#lightboxInner video').forEach(function (v) { v.pause(); });
    });
    document.getElementById('lightboxCarousel').addEventListener('slid.bs.carousel', function (ev) {
        var counter = document.getElementById('lightboxCounter');
        if (counter && counter.dataset.total) {
            counter.textContent = (ev.to + 1) + ' / ' + counter.dataset.total;
        }
    });

    // Post content — bg color auto reset
    document.getElementById('postContent').addEventListener('input', function () {
        var b = document.getElementById('bg_color_input');
        if (this.value.length > 80) {
            if (b && b.value) resetPostBg(false);
        } else {
            if (lastSelectedBg && (!b || !b.value)) selectPostBg(lastSelectedBg);
        }
    });

    // Edit post content — bg color auto reset
    document.getElementById('editPostContent').addEventListener('input', function () {
        var b = document.getElementById('edit_bg_color_input');
        if (this.value.length > 80) {
            if (b && b.value) resetEditPostBg(false);
        } else {
            if (lastEditSelectedBg && (!b || !b.value)) selectEditPostBg(lastEditSelectedBg);
        }
    });

    // Video thumbnail prime
    primeVideoThumbnails();
    window.bbPrimeVideos = primeVideoThumbnails;

    // Reload হলেও খোলা চ্যাটবক্সগুলো ফিরিয়ে আনো
    restoreOpenChats();
});

// Upload চলাকালীন page leave warning
window.addEventListener('beforeunload', function (e) {
    if (isUploading) { e.preventDefault(); e.returnValue = ''; }
});


// ============================================================
// SECTION 3: VIDEO THUMBNAIL
// ============================================================
function primeVideoThumbnails(scope) {
    scope = scope || document;
    scope.querySelectorAll('video.bb-inline-video, video.bb-tile-media').forEach(function (v) {
        if (v.dataset.primed) return;
        v.dataset.primed = '1';
        v.preload = 'metadata';
        v.addEventListener('loadedmetadata', function () {
            try { if (v.currentTime === 0) v.currentTime = 0.1; } catch (e) {}
        }, { once: true });
    });
}


// ============================================================
// SECTION 4: LIGHTBOX
// ============================================================
function openLightbox(mediaJson, index) {
    index = index || 0;
    try {
        var items  = typeof mediaJson === 'string' ? JSON.parse(mediaJson) : mediaJson;
        var inner  = document.getElementById('lightboxInner');
        if (!inner) return;
        inner.innerHTML = '';

        items.forEach(function (item, i) {
            var slide = document.createElement('div');
            slide.className = 'carousel-item' + (i === index ? ' active' : '');

            if (item.type === 'image') {
                var img = document.createElement('img');
                img.src       = item.url;
                img.className = 'd-block w-100 object-fit-contain';
                img.style.maxHeight = '82vh';
                slide.appendChild(img);
            } else {
                var wrap  = document.createElement('div');
                wrap.style.cssText = 'position:relative;z-index:20;display:flex;justify-content:center;';
                var video = document.createElement('video');
                video.src       = item.url;
                video.controls  = true;
                video.className = 'd-block w-100 object-fit-contain';
                video.style.cssText = 'max-height:82vh;position:relative;z-index:20;';
                ['click','mousedown','mouseup','pointerdown','pointerup','touchstart','touchend'].forEach(function (evt) {
                    video.addEventListener(evt, function (e) { e.stopPropagation(); });
                });
                wrap.appendChild(video);
                slide.appendChild(wrap);
            }
            inner.appendChild(slide);
        });

        var carouselEl = document.getElementById('lightboxCarousel');
        var ci = bootstrap.Carousel.getInstance(carouselEl);
        if (!ci) ci = new bootstrap.Carousel(carouselEl, { ride: false, touch: false, interval: false });
        if (index > 0) ci.to(index);

        var navBar  = document.getElementById('lightboxNavBar');
        var counter = document.getElementById('lightboxCounter');
        if (items.length <= 1) {
            if (navBar) navBar.style.display = 'none';
        } else {
            if (navBar) navBar.style.display = '';
            if (counter) {
                counter.dataset.total = items.length;
                counter.textContent   = (index + 1) + ' / ' + items.length;
            }
        }
        if (bootstrapLightboxModal) bootstrapLightboxModal.show();
    } catch (e) {
        console.error('Lightbox error:', e);
    }
}

function lightboxPrev() {
    document.querySelectorAll('#lightboxInner video').forEach(function (v) { v.pause(); });
    var ci = bootstrap.Carousel.getInstance(document.getElementById('lightboxCarousel'));
    if (ci) ci.prev();
}

function lightboxNext() {
    document.querySelectorAll('#lightboxInner video').forEach(function (v) { v.pause(); });
    var ci = bootstrap.Carousel.getInstance(document.getElementById('lightboxCarousel'));
    if (ci) ci.next();
}


// ============================================================
// SECTION 5: CREATE POST — COLOR BG
// ============================================================
function toggleColorPlates() {
    var z = document.getElementById('colorPlatesZone');
    if (z) z.classList.toggle('d-none');
}

function selectPostBg(cls) {
    var w = document.getElementById('postInputWrapper');
    var t = document.getElementById('postContent');
    var b = document.getElementById('bg_color_input');
    if (w && t) {
        w.className    = 'p-4 rounded text-center text-white fw-bold d-flex align-items-center justify-content-center fb-colored-post-render ' + cls;
        w.style.minHeight = '200px';
        t.style.cssText   = 'font-size:22px;text-align:center;color:#fff;';
        t.placeholder     = "What's on your mind?";
    }
    if (b) b.value = cls;
    lastSelectedBg   = cls;
    selectedMediaFiles = [];
    renderMediaPreviews();
}

function resetPostBg(clearMemory) {
    if (clearMemory === undefined) clearMemory = true;
    var w = document.getElementById('postInputWrapper');
    var t = document.getElementById('postContent');
    var b = document.getElementById('bg_color_input');
    if (w) { w.className = 'p-1 rounded bg-transparent'; w.style.minHeight = 'auto'; }
    if (t) { t.style.cssText = 'font-size:14px;text-align:left;color:inherit;'; t.placeholder = 'Start a post...'; }
    if (b) b.value = '';
    if (clearMemory) lastSelectedBg = '';
}


// ============================================================
// SECTION 6: CREATE POST — MEDIA PREVIEW
// ============================================================
var imageInput       = document.getElementById('postImageInput');
var previewContainer = document.getElementById('imagePreviewContainer');

document.getElementById('triggerUploadBtn')?.addEventListener('click', function () {
    imageInput.click();
});

imageInput?.addEventListener('change', function () {
    var files = Array.from(this.files);
    for (var i = 0; i < files.length; i++) {
        if (files[i].size > 100 * 1024 * 1024) {
            Swal.fire({ icon: 'error', title: 'File too large!', text: '"' + files[i].name + '" max 100MB.' });
            this.value = '';
            return;
        }
    }
    resetPostBg();
    files.forEach(function (f) { selectedMediaFiles.push(f); });
    renderMediaPreviews();
    this.value = '';
});

function renderMediaPreviews() {
    if (!previewContainer) return;
    previewContainer.innerHTML = '';
    if (!selectedMediaFiles.length) {
        previewContainer.classList.add('d-none');
        return;
    }
    previewContainer.classList.remove('d-none');

    selectedMediaFiles.forEach(function (file, idx) {
        var col = document.createElement('div');
        col.className  = 'col-4 col-md-3 position-relative';
        col.style.height = '100px';

        var el;
        if (file.type.startsWith('video/')) {
            el = document.createElement('video');
            el.muted = true;
            var pi = document.createElement('div');
            pi.className = 'position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center rounded-circle';
            pi.style.cssText = 'width:32px;height:32px;background:rgba(0,0,0,0.6);pointer-events:none;z-index:5;';
            pi.innerHTML = '<i class="bi bi-play-fill text-white" style="font-size:.9rem;margin-left:2px;"></i>';
            col.appendChild(pi);
        } else {
            el = document.createElement('img');
        }
        el.src       = URL.createObjectURL(file);
        el.className = 'w-100 h-100 object-fit-cover rounded border';

        var xBtn = document.createElement('button');
        xBtn.type      = 'button';
        xBtn.className = 'btn btn-dark btn-sm position-absolute top-0 end-0 m-1 rounded-circle';
        xBtn.style.cssText = 'background:rgba(0,0,0,0.7);border:none;width:22px;height:22px;display:flex;align-items:center;justify-content:center;z-index:10;padding:0;';
        xBtn.innerHTML = '<i class="bi bi-x-lg" style="font-size:10px;color:#fff;"></i>';
        xBtn.addEventListener('click', (function (i) {
            return function (e) {
                e.preventDefault();
                selectedMediaFiles.splice(i, 1);
                renderMediaPreviews();
            };
        })(idx));

        col.appendChild(el);
        col.appendChild(xBtn);
        previewContainer.appendChild(col);
    });
}


// ============================================================
// SECTION 7: CREATE POST SUBMIT (OPTIMISTIC UI)
// ============================================================
document.getElementById('ajaxPostForm')?.addEventListener('submit', function (e) {
    e.preventDefault();

    var content  = document.getElementById('postContent').value.trim();
    var bgColor  = document.getElementById('bg_color_input').value;
    var privacy  = document.getElementById('privacyInput')?.value || 'public';
    var modal    = bootstrap.Modal.getInstance(document.getElementById('createPostModal'));

    if (!content && !selectedMediaFiles.length) {
        Swal.fire({ icon: 'warning', title: 'Empty Post!', text: 'Please write something first!' });
        return;
    }

    var captured = selectedMediaFiles.slice();
    if (modal) modal.hide();
    document.getElementById('postContent').value = '';
    resetPostBg();
    selectedMediaFiles = [];
    renderMediaPreviews();

    var pid   = 'opt-' + Date.now();
    var uName = '{{ Auth::user()->name }}';
    var uInit = '{{ strtoupper(substr(Auth::user()->name ?? "U", 0, 1)) }}';

    var html = '<div class="card mb-3 border-0 rounded-3 shadow-sm" id="' + pid + '">'
             + '<div class="card-body p-3">'
             + '<div class="d-flex align-items-center gap-2 mb-3">'
             + '<div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:38px;height:38px;">' + uInit + '</div>'
             + '<div><h6 class="m-0 fw-bold" style="font-size:14px;">' + uName + '</h6>'
             + '<small class="text-muted" style="font-size:11px;"><span class="spinner-border spinner-border-sm text-primary me-1" style="width:10px;height:10px;"></span>Posting...</small></div></div>'
             + (bgColor
                ? '<div class="p-4 rounded text-center text-white fw-bold ' + bgColor + '" style="min-height:160px;font-size:22px;opacity:.85;"><p class="mb-0">' + content.replace(/\n/g, '<br>') + '</p></div>'
                : '<p class="mb-0 text-muted" style="font-size:14px;">' + content.replace(/\n/g, '<br>') + '</p>')
             + (captured.length ? '<div class="mt-2 p-3 bg-light rounded text-center text-muted small"><i class="bi bi-cloud-upload text-primary fs-4 d-block mb-1"></i>' + captured.length + ' file uploading...</div>' : '')
             + '<div class="progress mt-3" style="height:4px;"><div id="bar-' + pid + '" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width:5%;"></div></div>'
             + '</div></div>';

    var feed = document.getElementById('postsFeedContainer');
    if (feed) feed.insertAdjacentHTML('afterbegin', html);

    var fd = new FormData();
    fd.append('_token',   document.querySelector('meta[name="csrf-token"]').content);
    fd.append('content',  content);
    fd.append('bg_color', bgColor);
    fd.append('privacy',  privacy);
    captured.forEach(function (f) { fd.append('media[]', f); });

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '{{ route("posts.store") }}', true);
    xhr.setRequestHeader('Accept', 'application/json');
    isUploading = true;

    xhr.upload.addEventListener('progress', function (ev) {
        if (ev.lengthComputable) {
            var bar = document.getElementById('bar-' + pid);
            if (bar) bar.style.width = (Math.round(ev.loaded / ev.total * 90) + 5) + '%';
        }
    });

    xhr.onreadystatechange = function () {
        if (xhr.readyState !== 4) return;
        isUploading = false;

        if (xhr.status === 200 || xhr.status === 201) {
            var bar = document.getElementById('bar-' + pid);
            if (bar) {
                bar.style.width = '100%';
                bar.classList.replace('bg-primary', 'bg-success');
                bar.classList.remove('progress-bar-animated');
            }
            var res = {};
            try { res = JSON.parse(xhr.responseText); } catch (ex) {}

            setTimeout(function () {
                var optCard = document.getElementById(pid);
                if (res.html) {
                    if (optCard) optCard.outerHTML = res.html;
                    else {
                        var fc = document.getElementById('postsFeedContainer');
                        if (fc) fc.insertAdjacentHTML('afterbegin', res.html);
                    }
                    if (window.bbPrimeVideos) window.bbPrimeVideos();
                } else {
                    if (optCard) optCard.remove();
                }
                document.getElementById('emptyFeedState')?.remove();
                Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1800, timerProgressBar: true })
                    .fire({ icon: 'success', title: res.message || 'Posted!' });
            }, 600);
        } else {
            document.getElementById(pid)?.remove();
            Swal.fire({ icon: 'error', title: 'Post not published!', text: 'There was an issue uploading the post.' });
        }
    };
    xhr.send(fd);
});


// ============================================================
// SECTION 8: LIKE / SAVE / DELETE POST
// ============================================================
function toggleLike(postId) {
    fetch('/posts/' + postId + '/like', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        if (!d.success) return;
        var btn  = document.getElementById('likeBtn-' + postId);
        var zone = document.getElementById('like-zone-' + postId);
        btn.className = d.liked ? 'bb-action-btn active-like' : 'bb-action-btn';
        btn.innerHTML = d.liked
            ? '<i class="bi bi-hand-thumbs-up-fill"></i> <span>Like</span>'
            : '<i class="bi bi-hand-thumbs-up"></i> <span>Like</span>';
        if (zone) zone.innerHTML = d.like_count > 0
            ? '<span class="bb-like-bubble"><i class="bi bi-hand-thumbs-up-fill"></i></span> <span class="like-count-text">' + d.like_count + '</span>'
            : '';
    });
}

function toggleSave(postId) {
    fetch('/posts/' + postId + '/save', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        if (!d.success) return;
        var btn  = document.getElementById('saveBtn-' + postId);
        var icon = document.getElementById('saveIcon-' + postId);
        var text = document.getElementById('saveText-' + postId);
        if (d.saved) {
            if (btn)  btn.className  = 'bb-action-btn active-save';
            if (icon) icon.className = 'bi bi-bookmark-fill';
            if (text) text.innerText = 'Saved';
        } else {
            if (btn)  btn.className  = 'bb-action-btn';
            if (icon) icon.className = 'bi bi-bookmark';
            if (text) text.innerText = 'Save';
        }
        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, timerProgressBar: true })
            .fire({ icon: d.saved ? 'success' : 'info', title: d.message });
    })
    .catch(function () { Swal.fire({ icon: 'error', title: 'Something went wrong!' }); });
}

function deletePost(id) {
    Swal.fire({ title: 'Are you sure?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33' })
    .then(function (r) {
        if (!r.isConfirmed) return;
        fetch('/posts/' + id, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (!d.success) return;
            var card = document.getElementById('postCard-' + id);
            if (card) {
                card.style.transition = 'opacity .3s ease';
                card.style.opacity    = '0';
                setTimeout(function () { card.remove(); }, 300);
            }
            Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 })
                .fire({ icon: 'success', title: 'Post deleted' });
        });
    });
}


// ============================================================
// SECTION 9: SHARE MODAL
// ============================================================
function setSharePrivacy(value, iconClass, label) {
    document.getElementById('sharePrivacyInput').value = value;
    document.getElementById('sharePrivacyLabel').textContent = label;
    var btn = document.getElementById('sharePrivacyBtn');
    if (btn) btn.querySelector('i').className = 'bi ' + iconClass + ' me-1';
}

function openShareModal(postId, type) {
    type = type || 'post';
    document.getElementById('targetSharePostId').value = postId;
    document.getElementById('sharePostType').value     = type;
    document.getElementById('shareComment').value      = '';
    setSharePrivacy('friends', 'bi-people-fill text-success', 'Friends');

    var card    = document.getElementById('postCard-' + postId) || document.getElementById('jobCard-' + postId);
    var preview = document.getElementById('modalPostPreview');

    if (card && preview) {
        if (type === 'job') {
            var title   = card.querySelector('.bb-jobcard-title')?.innerText   || '';
            var company = card.querySelector('.bb-jobcard-company')?.innerText || '';
            var tag     = card.querySelector('.bb-jobcard-tag')?.innerText     || '';
            preview.innerHTML = '<div class="p-3">'
                + '<div class="d-flex align-items-center gap-2 mb-1"><i class="bi bi-briefcase-fill text-primary"></i>'
                + '<span style="font-size:13px;font-weight:700;">' + title + '</span></div>'
                + '<div style="font-size:12px;color:#6b7280;">' + company + '</div>'
                + (tag ? '<span style="font-size:11px;background:#eef2ff;color:#4f46e5;padding:2px 8px;border-radius:6px;font-weight:600;">' + tag + '</span>' : '')
                + '</div>';
        } else {
            var author  = card.querySelector('.author-name-zone')?.innerText         || 'User';
            var avatar  = card.querySelector('.author-avatar-zone')?.innerHTML       || '';
            var colored = card.getAttribute('data-bg-color');
            var caption = card.querySelector('.dynamic-caption')?.innerHTML          || '';
            var grid    = card.querySelector('.dynamic-media-container-zone');

            var capHtml = '<p class="mb-0" style="font-size:13px;color:#374151;">' + caption + '</p>';
            if (colored && colored !== 'null' && colored !== '') {
                capHtml = '<div class="p-3 rounded text-center text-white fw-bold ' + colored + '" style="min-height:70px;font-size:15px;"><p class="mb-0">' + caption + '</p></div>';
            }

            var gridHtml = '';
            if (grid) {
                var clone = grid.cloneNode(true);
                clone.querySelectorAll('img,video').forEach(function (el) {
                    el.removeAttribute('onclick');
                    if (el.tagName === 'VIDEO') el.removeAttribute('controls');
                });
                gridHtml = '<div class="overflow-hidden" style="max-height:200px;">' + clone.outerHTML + '</div>';
            }

            preview.innerHTML = '<div class="p-3 pb-2">'
                + '<div class="d-flex align-items-center gap-2 mb-2">'
                + '<div style="width:28px;height:28px;border-radius:50%;overflow:hidden;background:linear-gradient(135deg,#4f46e5,#7c73f0);color:#fff;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;">' + avatar + '</div>'
                + '<span style="font-size:13px;font-weight:700;">' + author + '</span></div>'
                + capHtml + '</div>' + gridHtml;
        }
    }

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


var _messengerContactsCache = [];

function loadMessengerContacts() {
    var zone = document.getElementById('messengerContacts');
    if (!zone) return;
    zone.innerHTML = '<div class="text-center text-muted small py-2"><div class="spinner-border spinner-border-sm text-primary"></div></div>';

    fetch('/friends/messenger-contacts', { headers: { 'Accept': 'application/json' } })
    .then(function (r) { if (!r.ok) throw new Error('HTTP ' + r.status); return r.json(); })
    .then(function (d) {
        if (!d.contacts || !d.contacts.length) {
            zone.innerHTML = '<div class="text-muted small py-2 text-center"><i class="bi bi-people" style="font-size:1.5rem;display:block;margin-bottom:4px;opacity:.4;"></i>No friends yet</div>';
            return;
        }
        _messengerContactsCache = d.contacts;
        renderMessengerContacts(d.contacts);
    })
    .catch(function (err) {
        console.warn('Messenger contacts error:', err);
        zone.innerHTML = '<div class="text-muted small py-2 text-center">Could not load contacts</div>';
    });
}

function renderMessengerContacts(contacts) {
    var zone = document.getElementById('messengerContacts');
    if (!zone) return;
    if (!contacts.length) {
        zone.innerHTML = '<div class="text-muted small py-2 text-center">No match found</div>';
        return;
    }
    var html = '';
    contacts.forEach(function(c) {
        var pic = c.profile_picture
            ? '<img src="/storage/' + c.profile_picture + '" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">'
            : '<span style="font-size:16px;font-weight:700;">' + c.name.charAt(0).toUpperCase() + '</span>';
        html += '<div class="text-center flex-shrink-0" style="cursor:pointer;width:64px;" onclick="sendToMessenger(' + c.id + ', \'' + c.name.replace(/'/g, "\\'") + '\', \'' + (c.hashid || c.id) + '\')">'
              + '<div style="width:52px;height:52px;border-radius:50%;overflow:hidden;background:linear-gradient(135deg,#4f46e5,#7c73f0);color:#fff;display:flex;align-items:center;justify-content:center;margin:0 auto 4px;">' + pic + '</div>'
              + '<div style="font-size:11px;font-weight:600;color:#1e1f24;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + c.name.split(' ')[0] + '</div>'
              + '</div>';
    });
    zone.innerHTML = html;
}

function searchMessengerContacts() {
    var input = document.getElementById('messengerSearchInput');
    if (!input) return;
    var q = input.value.trim().toLowerCase();
    if (!q) { renderMessengerContacts(_messengerContactsCache); return; }
    var filtered = _messengerContactsCache.filter(function(c) {
        return c.name.toLowerCase().includes(q);
    });
    renderMessengerContacts(filtered);
}

function sendToMessenger(userId, name, userHash) {
    var postId = document.getElementById('targetSharePostId').value;
    var type   = document.getElementById('sharePostType').value;
    if (typeof openChatBox === 'function') {
        openChatBox(userId, name, '', '', '0', userHash || userId);
        setTimeout(function () {
            var input = document.getElementById('chatinput-' + userId);
            if (input) {
                var link = window.location.origin + (type === 'job' ? '/jobs/' + postId : '/#postCard-' + postId);
                input.value = '📎 Check this out: ' + link;
                input.dispatchEvent(new Event('input'));
            }
        }, 300);
    }
    bootstrapShareModal?.hide();
    Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, timerProgressBar: true })
        .fire({ icon: 'success', title: 'Sent to ' + name + '!' });
}

function copyPostLink() {
    var postId = document.getElementById('targetSharePostId').value;
    var type   = document.getElementById('sharePostType').value;
    var link   = window.location.origin + (type === 'job' ? '/jobs/' + postId : '/#postCard-' + postId);
    navigator.clipboard.writeText(link)
    .then(function () {
        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, timerProgressBar: true })
            .fire({ icon: 'success', title: 'Link copied!' });
    })
    .catch(function () { prompt('Copy this link:', link); });
}

function openJobShareModal(jobId) {
    openShareModal(jobId, 'job');
}

document.getElementById('fbShareForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    var postId  = document.getElementById('targetSharePostId').value;
    var type    = document.getElementById('sharePostType').value;
    var comment = document.getElementById('shareComment').value.trim();
    var privacy = document.getElementById('sharePrivacyInput').value;
    var btn     = document.getElementById('shareSubmitBtn');

    if (type === 'job') { copyPostLink(); bootstrapShareModal?.hide(); return; }

    btn.disabled = true;
    Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false }).fire({ icon: 'info', title: 'Sharing...' });

    fetch('/posts/' + postId + '/share', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
        body: JSON.stringify({ content: comment, privacy: privacy })
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        btn.disabled = false;
        if (!d.success) { Swal.fire({ icon: 'error', title: 'Failed!', text: d.message || 'Could not share.' }); return; }
        bootstrapShareModal?.hide();
        resetShareModal();
        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, timerProgressBar: true })
            .fire({ icon: 'success', title: 'Shared to your feed!' });
        if (d.html) {
            var feedC = document.getElementById('postsFeedContainer') || document.getElementById('profilePostsContainer');
            if (feedC) {
                feedC.insertAdjacentHTML('afterbegin', d.html);
                if (window.bbPrimeVideos) window.bbPrimeVideos();
                feedC.querySelector('.bb-posts-empty')?.remove();
                feedC.querySelector('#emptyFeedState')?.remove();
            }
        }
    })
    .catch(function () { btn.disabled = false; Swal.fire({ icon: 'error', title: 'Network Error!' }); });
});


// ============================================================
// SECTION 10: EDIT POST
// ============================================================
function toggleEditColorPlates() {
    document.getElementById('editColorPlatesZone')?.classList.toggle('d-none');
}

function selectEditPostBg(cls) {
    var w = document.getElementById('editPostInputWrapper');
    var t = document.getElementById('editPostContent');
    var b = document.getElementById('edit_bg_color_input');
    if (w && t) {
        w.className    = 'p-4 rounded text-center text-white fw-bold d-flex align-items-center justify-content-center fb-colored-post-render ' + cls;
        w.style.minHeight = '200px';
        t.style.cssText   = 'font-size:22px;text-align:center;color:#fff;';
        t.className       = 'form-control border-0 bg-transparent shadow-none w-100';
    }
    if (b) b.value = cls;
    lastEditSelectedBg = cls;

    // bg select করলে existing media সরিয়ে দাও
    document.querySelectorAll('#editMediaPreviewContainer [data-server-path]').forEach(function (el) {
        var p  = el.getAttribute('data-server-path');
        var tp = el.getAttribute('data-type');
        if (tp === 'image') removedImages.push(p); else removedVideos.push(p);
    });
    editSelectedFiles = [];
    var pc = document.getElementById('editMediaPreviewContainer');
    if (pc) pc.innerHTML = '';
}

function resetEditPostBg(clearMemory) {
    if (clearMemory === undefined) clearMemory = true;
    var w = document.getElementById('editPostInputWrapper');
    var t = document.getElementById('editPostContent');
    var b = document.getElementById('edit_bg_color_input');
    if (w) { w.className = 'p-1 rounded bg-transparent'; w.style.minHeight = 'auto'; }
    if (t) t.style.cssText = 'font-size:14px;text-align:left;color:inherit;';
    if (b) b.value = '';
    if (clearMemory) lastEditSelectedBg = '';
}

// Edit modal খুলুন — privacy সহ সব data সেট করে
function prepareEditModal(el) {
    var id       = el.getAttribute('data-id');
    var content  = el.getAttribute('data-content');
    var imgs     = el.getAttribute('data-images');
    var vids     = el.getAttribute('data-video');
    var bg       = el.getAttribute('data-bg-color');
    var isShared = el.getAttribute('data-is-shared') === '1';

    removedImages = []; removedVideos = []; editSelectedFiles = []; lastEditSelectedBg = '';

    document.getElementById('editPostId').value      = id;
    document.getElementById('editPostContent').value = content || '';
    document.getElementById('editMediaInput').value  = '';

    var pc = document.getElementById('editMediaPreviewContainer');
    if (pc) pc.innerHTML = '';

    var ms = document.getElementById('editMediaSection');
    var cz = document.getElementById('editColorPlatesZone');

    // Privacy সেট করো (সবসময়)
    var editPrivacy = el.getAttribute('data-privacy') || 'public';
    var privacyMap  = {
        'public':  ['bi-globe-americas text-primary', 'Public'],
        'friends': ['bi-people-fill text-success',    'Friends'],
        'only_me': ['bi-lock-fill text-warning',      'Only Me']
    };
    var pm = privacyMap[editPrivacy] || privacyMap['public'];
    setEditPrivacy(editPrivacy, pm[0], pm[1]);

    // Shared post — media ও color section লুকাও
    if (isShared) {
        if (ms) ms.classList.add('d-none');
        if (cz) cz.classList.add('d-none');
        resetEditPostBg();
        bootstrapEditModal?.show();
        return;
    }

    if (ms) ms.classList.remove('d-none');
    (bg && bg !== 'null' && bg.trim()) ? selectEditPostBg(bg) : resetEditPostBg();

    if (imgs && imgs !== 'null' && imgs.trim()) {
        try {
            var arr = JSON.parse(imgs);
            if (Array.isArray(arr)) arr.forEach(function (i) { renderEditPreviewItem(i, 'image', false); });
        } catch (ex) {}
    }
    if (vids && vids !== 'null' && vids.trim()) {
        try {
            var p   = JSON.parse(vids);
            var arr = Array.isArray(p) ? p : [p];
            arr.forEach(function (v) { if (v && v.trim()) renderEditPreviewItem(v, 'video', false); });
        } catch (ex) {
            if (typeof vids === 'string' && vids.trim()) renderEditPreviewItem(vids.trim(), 'video', false);
        }
    }

    bootstrapEditModal?.show();
}

function renderEditPreviewItem(pathOrFile, type, isNew) {
    var container = document.getElementById('editMediaPreviewContainer');
    if (!container) return;

    var col = document.createElement('div');
    col.className  = 'col-4 position-relative';
    col.style.height = '110px';
    if (!isNew) {
        col.setAttribute('data-server-path', pathOrFile);
        col.setAttribute('data-type', type);
    }

    var src     = isNew ? URL.createObjectURL(pathOrFile) : '{{ asset("storage") }}/' + pathOrFile;
    var mediaEl;

    if (type === 'image') {
        mediaEl = document.createElement('img');
        mediaEl.src       = src;
        mediaEl.className = 'w-100 h-100 rounded border';
        mediaEl.style.cssText = 'object-fit:cover;cursor:pointer;';
        mediaEl.addEventListener('click', function () {
            openLightbox(JSON.stringify([{ type: 'image', url: src }]), 0);
        });
    } else {
        mediaEl = document.createElement('video');
        mediaEl.src     = src;
        mediaEl.muted   = true;
        mediaEl.preload = 'metadata';
        mediaEl.className = 'w-100 h-100 rounded border';
        mediaEl.style.cssText = 'object-fit:cover;cursor:pointer;';
        mediaEl.addEventListener('click', function (e) {
            e.stopPropagation();
            if (!this.hasAttribute('data-expanded')) {
                this.setAttribute('data-expanded', '1');
                this.controls = true;
                this.muted    = false;
                this.style.objectFit = 'contain';
                col.style.height = '160px';
                var ov = col.querySelector('.edit-play-overlay');
                if (ov) ov.style.display = 'none';
                this.play().catch(function () {});
            }
        });
        var ov = document.createElement('div');
        ov.className = 'edit-play-overlay position-absolute top-50 start-50 translate-middle d-flex align-items-center justify-content-center rounded-circle';
        ov.style.cssText = 'width:36px;height:36px;background:rgba(0,0,0,0.65);pointer-events:none;z-index:5;';
        ov.innerHTML = '<i class="bi bi-play-fill text-white" style="font-size:1rem;margin-left:2px;"></i>';
        col.appendChild(ov);
    }

    var xBtn = document.createElement('button');
    xBtn.type      = 'button';
    xBtn.className = 'btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle p-0 d-flex align-items-center justify-content-center';
    xBtn.style.cssText = 'width:22px;height:22px;font-size:11px;z-index:10;';
    xBtn.innerHTML = '<i class="bi bi-x-lg"></i>';
    xBtn.addEventListener('click', (function (pof, t, n, colRef) {
        return function (e) {
            e.stopPropagation();
            if (!n) {
                if (t === 'image') removedImages.push(pof); else removedVideos.push(pof);
            } else {
                var idx = editSelectedFiles.indexOf(pof);
                if (idx > -1) editSelectedFiles.splice(idx, 1);
            }
            colRef.remove();
        };
    })(pathOrFile, type, isNew, col));

    col.appendChild(mediaEl);
    col.appendChild(xBtn);
    container.appendChild(col);
}

document.getElementById('editMediaInput')?.addEventListener('change', function () {
    Array.from(this.files).forEach(function (f) {
        editSelectedFiles.push(f);
        renderEditPreviewItem(f, f.type.startsWith('video/') ? 'video' : 'image', true);
    });
    this.value = '';
});

document.getElementById('editPostForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    var id  = document.getElementById('editPostId')?.value;
    if (!id) return;
    var btn = document.getElementById('editSubmitBtn');
    if (btn) btn.disabled = true;

    var fd = new FormData();
    fd.append('_token',         document.querySelector('meta[name="csrf-token"]').content);
    fd.append('content',        document.getElementById('editPostContent')?.value || '');
    fd.append('bg_color',       document.getElementById('edit_bg_color_input')?.value || '');
    fd.append('privacy',        document.getElementById('editPrivacyInput')?.value || 'public');
    fd.append('removed_images', JSON.stringify(removedImages));
    fd.append('removed_videos', JSON.stringify(removedVideos));
    editSelectedFiles.forEach(function (f) { fd.append('media[]', f); });

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/posts/' + id, true);
    xhr.setRequestHeader('Accept', 'application/json');
    xhr.onreadystatechange = function () {
        if (xhr.readyState !== 4) return;
        if (xhr.status === 200 || xhr.status === 201) {
            var res = {};
            try { res = JSON.parse(xhr.responseText); } catch (ex) {}
            var oldCard = document.getElementById('postCard-' + id);
            if (oldCard && res.html) { oldCard.outerHTML = res.html; if (window.bbPrimeVideos) window.bbPrimeVideos(); }
            bootstrapEditModal?.hide();
            Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 })
                .fire({ icon: 'success', title: 'Post updated!' });
            if (btn) btn.disabled = false;
        } else {
            if (btn) btn.disabled = false;
            Swal.fire({ icon: 'error', title: 'Update Failed!' });
        }
    };
    xhr.send(fd);
});


// ============================================================
// SECTION 11: JOB DELETE / SAVE
// ============================================================
function deleteJob(id) {
    Swal.fire({ title: 'Delete this job?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Delete' })
    .then(function (r) {
        if (!r.isConfirmed) return;
        fetch('/jobs/' + id, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (!d.success) return;
            var card = document.getElementById('jobCard-' + id);
            if (card) { card.style.transition = 'opacity .3s'; card.style.opacity = '0'; setTimeout(function () { card.remove(); }, 300); }
            Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500 })
                .fire({ icon: 'success', title: 'Job deleted' });
        });
    });
}

function toggleJobSave(id) {
    var btn = document.getElementById('jobSaveBtn-' + id);
    if (btn && btn.dataset.busy === '1') return;
    if (btn) btn.dataset.busy = '1';

    fetch('/jobs/' + id + '/save', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        if (btn) btn.dataset.busy = '0';
        if (!d.success) return;
        if (btn) {
            btn.classList.toggle('saved', d.saved);
            var ic = btn.querySelector('i');
            if (ic) ic.className = d.saved ? 'bi bi-bookmark-fill' : 'bi bi-bookmark';
            btn.title = d.saved ? 'Saved' : 'Save job';
        }
        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1600, timerProgressBar: true })
            .fire({ icon: d.saved ? 'success' : 'info', title: d.message });
    })
    .catch(function () {
        var b = document.getElementById('jobSaveBtn-' + id);
        if (b) b.dataset.busy = '0';
        Swal.fire({ icon: 'error', title: 'Something went wrong' });
    });
}


// ============================================================
// SECTION 12: FEED FILTER + INFINITE SCROLL
// ============================================================
function setFeedFilter(filter) {
    currentFeedFilter = filter;

    var btnMap = { all: 'filterAll', friends: 'filterFriends', public: 'filterPublic' };
    Object.entries(btnMap).forEach(function (entry) {
        var key = entry[0], id = entry[1];
        var btn = document.getElementById(id);
        if (!btn) return;
        if (key === filter) {
            btn.style.background = '#4f46e5';
            btn.style.color      = '#fff';
            btn.style.border     = 'none';
        } else {
            btn.style.background = '#fff';
            btn.style.color      = '#6b7280';
            btn.style.border     = '1.5px solid #eceef1';
        }
    });

    feedPage    = 1;
    feedLoading = false;
    document.getElementById('feedMeta').dataset.hasMore = '1';
    document.getElementById('feedEndMessage')?.classList.add('d-none');

    fetch('{{ route("feed.load") }}?page=1&filter=' + filter, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        var container = document.getElementById('postsFeedContainer');
        if (container) container.innerHTML = data.html || '';
        document.getElementById('feedMeta').dataset.hasMore = data.has_more ? '1' : '0';
        if (!data.has_more) document.getElementById('feedEndMessage')?.classList.remove('d-none');
        if (window.bbPrimeVideos) window.bbPrimeVideos();
    });
}

function loadMorePosts() {
    var meta = document.getElementById('feedMeta');
    if (!meta) return;
    if (feedLoading || meta.dataset.hasMore === '0') return;
    feedLoading = true;
    feedPage++;

    var loader = document.getElementById('feedLoader');
    if (loader) loader.classList.remove('d-none');

    fetch('{{ route("feed.load") }}?page=' + feedPage + '&filter=' + currentFeedFilter, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        var container = document.getElementById('postsFeedContainer');
        if (container && data.html && data.html.trim()) {
            container.insertAdjacentHTML('beforeend', data.html);
            if (window.bbPrimeVideos) window.bbPrimeVideos(container);
        }
        meta.dataset.hasMore = data.has_more ? '1' : '0';
        if (loader) loader.classList.add('d-none');
        if (!data.has_more) {
            var em = document.getElementById('feedEndMessage');
            if (em) em.classList.remove('d-none');
        }
        feedLoading = false;
    })
    .catch(function () {
        if (loader) loader.classList.add('d-none');
        feedLoading = false;
    });
}

window.addEventListener('scroll', function () {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 300) loadMorePosts();
});


// ============================================================
// SECTION 13: COMMENT MODAL
// ============================================================
function openCommentModal(postId) {
    var list     = document.getElementById('commentModalList');
    var preview  = document.getElementById('commentModalPostPreview');
    var viewMore = document.getElementById('commentModalViewMore');
    var countEl  = document.getElementById('commentModalCount');

    document.getElementById('commentModalPostId').value = postId;
    commentEditState = { editing: false, commentId: null };
    document.getElementById('commentModalInput').value       = '';
    document.getElementById('commentModalInput').placeholder = 'Write a comment...';
    document.getElementById('commentEditNotice')?.classList.add('d-none');

    var card = document.getElementById('postCard-' + postId);
    if (card && preview) {
        var author  = card.querySelector('.author-name-zone')?.innerText   || 'User';
        var avatar  = card.querySelector('.author-avatar-zone')?.innerHTML || 'U';
        var colored = card.getAttribute('data-bg-color');
        var caption = card.querySelector('.dynamic-caption')?.innerHTML    || '';

         var capHtml = '<p class="mb-0" style="font-size:14px;">' + caption + '</p>';
        if (colored && colored !== 'null' && colored !== '') {
            capHtml = '<div class="p-3 rounded text-center text-white fw-bold ' + colored + '" style="min-height:80px;font-size:16px;"><p class="mb-0">' + caption + '</p></div>';
        }

        // পোস্টের media grid clone করি (থাকলে)
        var mediaHtml = '';
        var mediaGrid = card.querySelector('.dynamic-media-container-zone');
        if (mediaGrid) {
            var mClone = mediaGrid.cloneNode(true);
            mClone.querySelectorAll('img,video').forEach(function (el) {
                el.removeAttribute('onclick');
                if (el.tagName === 'VIDEO') el.removeAttribute('controls');
            });
            mClone.querySelectorAll('.bb-play-badge,.bb-expand-btn,.bb-more-overlay').forEach(function (el) {
                el.removeAttribute('onclick');
            });
            mediaHtml = '<div class="rounded overflow-hidden mt-2" style="max-height:240px;">' + mClone.outerHTML + '</div>';
        }

        preview.innerHTML = '<div class="d-flex align-items-center gap-2 mb-2">'
            + '<div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:38px;height:38px;font-size:14px;">' + avatar + '</div>'
            + '<h6 class="m-0 fw-bold" style="font-size:14px;">' + author + '</h6></div>'
            + capHtml + mediaHtml;
    }

    if (list) list.innerHTML = '<div class="text-center text-muted py-4"><div class="spinner-border spinner-border-sm text-primary"></div><div class="small mt-2">Loading comments...</div></div>';
    if (viewMore) viewMore.classList.add('d-none');
    if (countEl) countEl.innerText = '';

    bootstrapCommentModal?.show();

    fetch('/posts/' + postId + '/comments/load?offset=0', {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        if (!data.success) { if (list) list.innerHTML = '<div class="text-center text-muted py-4 small">Could not load comments.</div>'; return; }
        if (list) list.innerHTML = data.html.trim() ? data.html : '<div class="text-center text-muted py-4 small" id="modalNoComment">No comments yet. Be the first!</div>';
        if (countEl) { var tt = card?.querySelector('#comment-count-' + postId)?.innerText || ''; countEl.innerText = tt; }
        if (viewMore) {
            var vBtn = document.getElementById('commentModalViewMoreBtn');
            if (data.has_more) {
                vBtn.setAttribute('data-offset',  data.next_offset);
                vBtn.setAttribute('data-post-id', postId);
                viewMore.classList.remove('d-none');
            } else {
                viewMore.classList.add('d-none');
            }
        }
    })
    .catch(function () { if (list) list.innerHTML = '<div class="text-center text-muted py-4 small">Network error.</div>'; });

}


document.getElementById('commentModalViewMoreBtn')?.addEventListener('click', function () {
    var postId   = this.getAttribute('data-post-id');
    var offset   = this.getAttribute('data-offset');
    var original = this.innerHTML;
    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-1" style="width:12px;height:12px;"></span> Loading...';
    var self = this;

    fetch('/posts/' + postId + '/comments/load?offset=' + offset, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(function (r) { return r.json(); })
    .then(function (data) {
        var list = document.getElementById('commentModalList');
        if (list && data.html.trim()) list.insertAdjacentHTML('beforeend', data.html);
        if (data.has_more) {
            self.setAttribute('data-offset', data.next_offset);
            self.disabled  = false;
            self.innerHTML = original;
        } else {
            document.getElementById('commentModalViewMore').classList.add('d-none');
        }
    })
    .catch(function () { self.disabled = false; self.innerHTML = original; });
});

document.getElementById('commentModalForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    var postId = document.getElementById('commentModalPostId').value;
    var input  = document.getElementById('commentModalInput');
    var text   = input.value.trim();
    if (!text) return;

    // Edit mode
    if (commentEditState.editing && commentEditState.commentId) {
        var cid = commentEditState.commentId;
        fetch('/comments/' + cid, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            body: JSON.stringify({ content: text })
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (!d.success) return;
            var span = document.getElementById('comment-text-' + cid);
            if (span) span.innerText = text;
            var meta = document.querySelector('.comment-meta-' + cid);
            if (meta) meta.innerHTML = (d.updated_at || 'just now') + '<span class="comment-edited-tag-' + cid + '"> · Edited</span>';
            commentEditState = { editing: false, commentId: null };
            input.value = ''; input.placeholder = 'Write a comment...';
            document.getElementById('commentEditNotice')?.classList.add('d-none');
        });
        return;
    }

    // New comment
    input.value = '';
    fetch('/posts/' + postId + '/comments', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
        body: JSON.stringify({ content: text })
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        if (!d.success) return;
        document.getElementById('modalNoComment')?.remove();
        var avatar = d.user_picture ? '<img src="' + d.user_picture + '" style="width:100%;height:100%;object-fit:cover;">' : d.user_initial;
        var html = '<div class="comment-thread" id="comment-thread-' + d.comment_id + '">'
                 + '<div class="d-flex gap-2 mb-2 align-items-start comment-row" id="comment-container-' + d.comment_id + '">'
                 + '<div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0 overflow-hidden" style="width:32px;height:32px;font-size:13px;">' + avatar + '</div>'
                 + '<div class="flex-grow-1">'
                 + '<div class="d-flex align-items-start justify-content-between">'
                 + '<div class="bg-light px-3 py-2 rounded-4 d-inline-block border" style="max-width:100%;">'
                 + '<strong class="d-block text-dark" style="font-size:12.5px;">' + d.user_name + '</strong>'
                 + '<span id="comment-text-' + d.comment_id + '" style="font-size:13px;word-break:break-word;">' + d.content + '</span></div>'
                 + '<div class="dropdown flex-shrink-0"><button type="button" class="btn btn-link btn-sm text-muted p-0 border-0 shadow-none ms-1" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>'
                 + '<ul class="dropdown-menu dropdown-menu-end shadow-sm p-1" style="min-width:100px;">'
                 + '<li><a class="dropdown-item py-1" style="font-size:.85rem;" href="javascript:void(0)" onclick="editComment(event,' + d.comment_id + ')"><i class="bi bi-pencil me-1"></i> Edit</a></li>'
                 + '<li><a class="dropdown-item py-1 text-danger" style="font-size:.85rem;" href="javascript:void(0)" onclick="deleteComment(' + d.comment_id + ',' + postId + ')"><i class="bi bi-trash me-1"></i> Delete</a></li>'
                 + '</ul></div></div>'
                 + '<div class="d-flex align-items-center gap-3 ms-2 mt-1" style="font-size:11.5px;">'
                 + '<span class="comment-like-btn" id="comment-like-' + d.comment_id + '" onclick="toggleCommentLike(' + d.comment_id + ')" style="cursor:pointer;font-weight:600;">Like</span>'
                 + '<span class="comment-reply-btn" onclick="openReplyBox(' + d.comment_id + ')" style="cursor:pointer;font-weight:600;color:#65676b;">Reply</span>'
                 + '<span class="text-muted comment-meta-' + d.comment_id + '">' + d.created_at + '<span class="comment-edited-tag-' + d.comment_id + '"></span></span>'
                 + '<span class="comment-like-count text-muted" id="comment-like-count-' + d.comment_id + '" style="display:none;"><i class="bi bi-hand-thumbs-up-fill text-primary"></i> <span class="clc-num">0</span></span>'
                 + '</div>'
                 + '<div class="reply-box-zone mt-2 d-none" id="reply-box-' + d.comment_id + '"></div>'
                 + '<div class="replies-zone mt-2" id="replies-zone-' + d.comment_id + '"></div>'
                 + '</div></div></div>';

        document.getElementById('commentModalList')?.insertAdjacentHTML('afterbegin', html);
        var fc = document.getElementById('comment-count-' + postId);
        if (fc && d.comment_count !== undefined) fc.innerText = d.comment_count + ' comments';
        var mc = document.getElementById('commentModalCount');
        if (mc && d.comment_count !== undefined) mc.innerText = d.comment_count + ' comments';
    });
});

function editComment(event, cid) {
    var span = document.getElementById('comment-text-' + cid);
    if (!span) return;
    var input = document.getElementById('commentModalInput');
    if (!input) return;
    commentEditState = { editing: true, commentId: cid };
    input.value       = span.innerText;
    input.placeholder = 'Editing comment...';
    input.focus();
    document.getElementById('commentEditNotice')?.classList.remove('d-none');
}

function cancelCommentEdit() {
    commentEditState = { editing: false, commentId: null };
    var input = document.getElementById('commentModalInput');
    if (input) { input.value = ''; input.placeholder = 'Write a comment...'; }
    document.getElementById('commentEditNotice')?.classList.add('d-none');
}

function deleteComment(cid, postId) {
    Swal.fire({ title: 'Delete comment?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444' })
    .then(function (r) {
        if (!r.isConfirmed) return;
        fetch('/comments/' + cid, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (!d.success) return;
            document.getElementById('comment-container-' + cid)?.remove();
            var fc = document.getElementById('comment-count-' + postId);
            if (fc && d.comment_count !== undefined) fc.innerText = d.comment_count + ' comments';
            var mc = document.getElementById('commentModalCount');
            if (mc && d.comment_count !== undefined) mc.innerText = d.comment_count + ' comments';
            if (commentEditState.commentId == cid) {
                commentEditState = { editing: false, commentId: null };
                var input = document.getElementById('commentModalInput');
                if (input) { input.value = ''; input.placeholder = 'Write a comment...'; }
                document.getElementById('commentEditNotice')?.classList.add('d-none');
            }
        });
    });
}


// ============================================================
// SECTION 14: COMMENT LIKE + REPLY
// ============================================================
window.MY_PROFILE_PIC = @json(Auth::user()->profile_picture ? asset('storage/'.Auth::user()->profile_picture) : null);
window.MY_INITIAL     = @json(strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)));

function toggleCommentLike(commentId) {
    fetch('/comments/' + commentId + '/like', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        if (!d.success) return;
        var btn = document.getElementById('comment-like-' + commentId);
        var cw  = document.getElementById('comment-like-count-' + commentId);
        if (btn) { btn.classList.toggle('liked', d.liked); btn.innerText = d.liked ? 'Liked' : 'Like'; }
        if (cw) {
            var num = cw.querySelector('.clc-num');
            if (num) num.innerText = d.like_count;
            cw.style.display = d.like_count > 0 ? '' : 'none';
        }
    });
}

function openReplyBox(parentId, mentionName) {
    var zone  = document.getElementById('reply-box-' + parentId);
    if (!zone) return;
    var input = document.getElementById('reply-input-' + parentId);
    var myPic = window.MY_PROFILE_PIC;
    var myInit = window.MY_INITIAL || 'U';
    var avatar = myPic ? '<img src="' + myPic + '" style="width:100%;height:100%;object-fit:cover;">' : myInit;

    if (zone.classList.contains('d-none') || zone.dataset.open !== '1') {
        zone.innerHTML = '<div class="reply-input-wrap">'
            + '<div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0 overflow-hidden" style="width:28px;height:28px;font-size:12px;">' + avatar + '</div>'
            + '<div class="reply-field flex-grow-1">'
            + '<span class="reply-mention-tag" id="reply-mention-' + parentId + '" style="display:none;"></span>'
            + '<input type="text" class="reply-input-box" id="reply-input-' + parentId + '" placeholder="Write a reply..." autocomplete="off"'
            + ' onkeydown="if(event.key===\'Enter\'){event.preventDefault();submitReply(' + parentId + ');}else if(event.key===\'Backspace\'&&this.value===\'\'){clearReplyMention(' + parentId + ');}">'
            + '</div>'
            + '<button type="button" class="bb-emoji-btn p-0" data-target="#reply-input-' + parentId + '" title="Emoji" style="font-size:15px;"><i class="bi bi-emoji-smile"></i></button>'
            + '<button type="button" class="reply-send-btn" onclick="submitReply(' + parentId + ')" title="Send"><i class="bi bi-send-fill"></i></button>'
            + '</div>';
        zone.classList.remove('d-none');
        zone.dataset.open = '1';
        input = document.getElementById('reply-input-' + parentId);
    }

    var tag = document.getElementById('reply-mention-' + parentId);
    if (mentionName && tag) {
        tag.textContent    = '@' + mentionName;
        tag.style.display  = 'inline-flex';
        tag.dataset.mention = mentionName;
    }
    setTimeout(function () { if (input) input.focus(); }, 50);
}

function clearReplyMention(parentId) {
    var tag = document.getElementById('reply-mention-' + parentId);
    if (tag) { tag.style.display = 'none'; tag.textContent = ''; tag.dataset.mention = ''; }
}

function submitReply(parentId) {
    var input   = document.getElementById('reply-input-' + parentId);
    if (!input) return;
    var text    = input.value.trim();
    var tag     = document.getElementById('reply-mention-' + parentId);
    var mention = tag && tag.dataset.mention ? tag.dataset.mention : '';
    if (!text && !mention) return;

    var finalText = mention ? '@' + mention + ' ' + text : text;
    var postId    = document.getElementById('commentModalPostId').value;
    input.disabled = true;

    fetch('/posts/' + postId + '/comments', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
        body: JSON.stringify({ content: finalText, parent_id: parentId })
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        if (!d.success) { input.disabled = false; return; }
        var rz     = document.getElementById('replies-zone-' + parentId);
        var avatar = d.user_picture ? '<img src="' + d.user_picture + '" style="width:100%;height:100%;object-fit:cover;">' : d.user_initial;
        var dc     = highlightMentions(d.content);

        var html = '<div class="d-flex gap-2 mb-2 align-items-start comment-row reply-row" id="comment-container-' + d.comment_id + '">'
                 + '<div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0 overflow-hidden" style="width:28px;height:28px;font-size:12px;">' + avatar + '</div>'
                 + '<div class="flex-grow-1">'
                 + '<div class="d-flex align-items-start justify-content-between">'
                 + '<div class="bg-light px-3 py-2 rounded-4 d-inline-block border" style="max-width:100%;">'
                 + '<strong class="d-block text-dark" style="font-size:12px;">' + d.user_name + '</strong>'
                 + '<span id="comment-text-' + d.comment_id + '" style="font-size:12.5px;word-break:break-word;">' + dc + '</span></div>'
                 + '<div class="dropdown flex-shrink-0"><button type="button" class="btn btn-link btn-sm text-muted p-0 border-0 shadow-none ms-1" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>'
                 + '<ul class="dropdown-menu dropdown-menu-end shadow-sm p-1" style="min-width:100px;">'
                 + '<li><a class="dropdown-item py-1" style="font-size:.85rem;" href="javascript:void(0)" onclick="editComment(event,' + d.comment_id + ')"><i class="bi bi-pencil me-1"></i> Edit</a></li>'
                 + '<li><a class="dropdown-item py-1 text-danger" style="font-size:.85rem;" href="javascript:void(0)" onclick="deleteComment(' + d.comment_id + ',' + postId + ')"><i class="bi bi-trash me-1"></i> Delete</a></li>'
                 + '</ul></div></div>'
                 + '<div class="d-flex align-items-center gap-3 ms-2 mt-1" style="font-size:11px;">'
                 + '<span class="comment-like-btn" id="comment-like-' + d.comment_id + '" onclick="toggleCommentLike(' + d.comment_id + ')" style="cursor:pointer;font-weight:600;">Like</span>'
                 + '<span class="comment-reply-btn" onclick="openReplyBox(' + parentId + ',\'' + d.user_name.replace(/'/g, "\\'") + '\')" style="cursor:pointer;font-weight:600;color:#65676b;">Reply</span>'
                 + '<span class="text-muted comment-meta-' + d.comment_id + '">' + d.created_at + '<span class="comment-edited-tag-' + d.comment_id + '"></span></span>'
                 + '<span class="comment-like-count text-muted" id="comment-like-count-' + d.comment_id + '" style="display:none;"><i class="bi bi-hand-thumbs-up-fill text-primary"></i> <span class="clc-num">0</span></span>'
                 + '</div></div></div>';

        if (rz) rz.insertAdjacentHTML('beforeend', html);

        var zone = document.getElementById('reply-box-' + parentId);
        if (zone) { zone.classList.add('d-none'); zone.dataset.open = '0'; zone.innerHTML = ''; }

        var fc = document.getElementById('comment-count-' + postId);
        if (fc && d.comment_count !== undefined) fc.innerText = d.comment_count + ' comments';
        var mc = document.getElementById('commentModalCount');
        if (mc && d.comment_count !== undefined) mc.innerText = d.comment_count + ' comments';
    })
    .catch(function () { input.disabled = false; });
}

function highlightMentions(text) {
    return text.replace(/@([\w\u0980-\u09FF.]+(?:\s[\w\u0980-\u09FF.]+)?)/g, '<span class="comment-mention">@$1</span>');
}


// ============================================================
// SECTION 15: FRIEND ACTIONS
// ============================================================
function friendAction(action, userId, btnEl) {
    var endpoints = {
        send:     '/friends/send',
        accept:   '/friends/accept',
        decline:  '/friends/decline',
        cancel:   '/friends/cancel',
        unfriend: '/friends/unfriend',
        block:    '/friends/block',
        unblock:  '/friends/unblock'
    };
    var confirmMsg = {
        unfriend: 'Remove this person from your friends?',
        block:    "Block this user? They won't be able to find you.",
        cancel:   'Cancel this friend request?'
    };
    if (['unfriend', 'block', 'cancel'].includes(action) && !confirm(confirmMsg[action])) return;
    if (btnEl) btnEl.disabled = true;

    fetch(endpoints[action], {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ user_id: userId })
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        if (btnEl) btnEl.disabled = false;
        if (!d.success) { alert(d.message || 'Something went wrong.'); return; }
        var wrap = document.getElementById('friendBtnWrap-' + userId);
        if (wrap) updateFriendBtn(wrap, d.status, userId);
        if (action === 'accept' || action === 'decline') {
            var card = document.getElementById('freq-' + userId);
            if (card) { card.style.transition = 'opacity .3s'; card.style.opacity = '0'; setTimeout(function () { card.remove(); }, 300); }
        }
        if (typeof Swal !== 'undefined') Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, timerProgressBar: true }).fire({ icon: 'success', title: d.message });
    })
    .catch(function () { if (btnEl) btnEl.disabled = false; alert('Network error.'); });
}

function suggestAction(action, userId, btnEl) {
    var endpoint = action === 'send' ? '/friends/send' : '/friends/cancel';
    if (action === 'cancel' && !confirm('Cancel this friend request?')) return;
    if (btnEl) btnEl.disabled = true;

    fetch(endpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ user_id: userId })
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        if (btnEl) btnEl.disabled = false;
        if (!d.success) { alert(d.message || 'Error'); return; }
        if (action === 'send') {
            btnEl.style.background  = '#4f46e5';
            btnEl.style.borderColor = '#4f46e5';
            btnEl.style.color       = '#fff';
            btnEl.innerHTML         = '<i class="bi bi-check-lg"></i>';
            btnEl.title             = 'Cancel Request';
            btnEl.onclick           = function () { suggestAction('cancel', userId, this); };
        } else {
            btnEl.style.background  = '';
            btnEl.style.borderColor = '';
            btnEl.style.color       = '';
            btnEl.innerHTML         = '<i class="bi bi-person-plus"></i>';
            btnEl.title             = 'Add Friend';
            btnEl.onclick           = function () { suggestAction('send', userId, this); };
        }
        if (typeof Swal !== 'undefined') Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, timerProgressBar: true }).fire({ icon: 'success', title: d.message });
    })
    .catch(function () { if (btnEl) btnEl.disabled = false; alert('Network error.'); });
}

function updateFriendBtn(wrap, status, userId) {
    var btns = {
        none:             '<button class="bb-friend-btn bb-friend-add" onclick="friendAction(\'send\',' + userId + ',this)"><i class="bi bi-person-plus-fill"></i> Add Friend</button>',
        pending_sent:     '<button class="bb-friend-btn bb-friend-pending" onclick="friendAction(\'cancel\',' + userId + ',this)"><i class="bi bi-person-check-fill"></i> Request Sent <span class="bb-friend-cancel-hint">· Cancel</span></button>',
        pending_received: '<button class="bb-friend-btn bb-friend-accept" onclick="friendAction(\'accept\',' + userId + ',this)"><i class="bi bi-check-lg"></i> Accept</button>'
                        + '<button class="bb-friend-btn bb-friend-decline" onclick="friendAction(\'decline\',' + userId + ',this)"><i class="bi bi-x-lg"></i> Decline</button>',
        accepted:         '<div class="dropdown d-inline-block"><button class="bb-friend-btn bb-friend-already dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-people-fill"></i> Friends</button>'
                        + '<ul class="dropdown-menu shadow border-0 rounded-3">'
                        + '<li><button class="dropdown-item text-danger py-2" onclick="friendAction(\'unfriend\',' + userId + ',this)"><i class="bi bi-person-x me-2"></i> Unfriend</button></li>'
                        + '<li><button class="dropdown-item py-2" onclick="friendAction(\'block\',' + userId + ',this)"><i class="bi bi-slash-circle me-2"></i> Block</button></li>'
                        + '</ul></div>',
        blocked:          '<button class="bb-friend-btn bb-friend-blocked" onclick="friendAction(\'unblock\',' + userId + ',this)"><i class="bi bi-slash-circle"></i> Blocked · Unblock</button>'
    };
    if (btns[status]) wrap.innerHTML = btns[status];
}


// ============================================================
// SECTION 16: ACTIVE NOW
// ============================================================
// ============================================================
// SECTION 16: ACTIVE NOW (Facebook-style real-time)
// ============================================================
function refreshActiveNow() {
    fetch('/active-now', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        // desktop sidebar — একই থাকলে বসাব না (blink বন্ধ)
        var z = document.getElementById('activeNowZone');
        if (z && d.html !== undefined && d.html !== null) {
            if (z.getAttribute('data-sig') !== d.html) {
                z.innerHTML = d.html;
                z.setAttribute('data-sig', d.html);
            }
        }
        // mobile drawer — একই থাকলে বসাব না
        var dz = document.getElementById('bbdActiveZone');
        if (dz && d.drawerHtml !== undefined && d.drawerHtml !== null) {
            if (dz.getAttribute('data-sig') !== d.drawerHtml) {
                dz.innerHTML = d.drawerHtml;
                dz.setAttribute('data-sig', d.drawerHtml);
            }
        }
    })
    .catch(function () {});
}
// HEARTBEAT — page খোলা থাকলে last_seen fresh রাখে (reload ছাড়া)
function sendHeartbeat() {
    fetch('/heartbeat', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    }).catch(function () {});
}

// tab hidden হলে heartbeat পাঠাব না (ব্যান্ডউইথ বাঁচে, offline detect হয়)
var _hbTimer = null, _anTimer = null;
function startLiveActive() {
    sendHeartbeat();      // সাথে সাথে একবার
    refreshActiveNow();
    if (_hbTimer) clearInterval(_hbTimer);
    if (_anTimer) clearInterval(_anTimer);
    _hbTimer = setInterval(function () {
        if (!document.hidden) sendHeartbeat();
    }, 10000);            // প্রতি ১০s heartbeat
    _anTimer = setInterval(refreshActiveNow, 10000);  // প্রতি 1০s panel refresh
}

// প্রথম heartbeat একটু দেরিতে (blade render content আগে দেখাতে)
setTimeout(startLiveActive, 1500);

// tab আবার visible হলে সাথে সাথে heartbeat + refresh
document.addEventListener('visibilitychange', function () {
    if (!document.hidden) { sendHeartbeat(); refreshActiveNow(); }
});

// ============================================================
// SECTION 16B: LIVE LIKE / COMMENT COUNTS (Facebook-style)
// ============================================================
function syncLiveCounts() {
    // screen এ থাকা সব post card এর id সংগ্রহ
    var cards = document.querySelectorAll('[id^="postCard-"]');
    if (!cards.length) return;

    var ids = [];
    cards.forEach(function (c) {
        var id = c.id.replace('postCard-', '');
        if (id) ids.push(id);
    });
    if (!ids.length) return;

    fetch('/feed/live-counts?ids=' + ids.join(','), {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        if (!d.counts) return;
        Object.keys(d.counts).forEach(function (pid) {
            var c = d.counts[pid];

            // --- like count ---
            var zone = document.getElementById('like-zone-' + pid);
            if (zone) {
                var numEl = zone.querySelector('.like-count-text');
                if (c.likes > 0) {
                    if (numEl) {
                        if (numEl.innerText != c.likes) numEl.innerText = c.likes;
                    } else {
                        zone.innerHTML = '<span class="bb-like-bubble"><i class="bi bi-hand-thumbs-up-fill"></i></span> <span class="like-count-text">' + c.likes + '</span>';
                    }
                } else if (numEl) {
                    zone.innerHTML = '';
                }
            }

            // --- like button state (অন্য device এ নিজে like দিলে sync) ---
            var btn = document.getElementById('likeBtn-' + pid);
            if (btn) {
                var isActive = btn.classList.contains('active-like');
                if (c.liked && !isActive) {
                    btn.className = 'bb-action-btn active-like';
                    btn.innerHTML = '<i class="bi bi-hand-thumbs-up-fill"></i> <span>Like</span>';
                } else if (!c.liked && isActive) {
                    btn.className = 'bb-action-btn';
                    btn.innerHTML = '<i class="bi bi-hand-thumbs-up"></i> <span>Like</span>';
                }
            }

            // --- comment count ---
            var cc = document.getElementById('comment-count-' + pid);
            if (cc) {
                var newText = c.comments + ' comments';
                if (cc.innerText !== newText) cc.innerText = newText;
            }
        });
    })
    .catch(function () {});
}

// প্রতি 6 সেকেন্ডে নীরবে sync 
setInterval(syncLiveCounts, 6000);

// ============================================================
// SECTION 17: NOTIFICATIONS
// ============================================================

// ============================================================
// SECTION 17: NOTIFICATIONS (Facebook-style)
// ============================================================
var notifOpen = false, lastNotifCount = 0;

function toggleNotifDropdown() {
    var drop = document.getElementById('notifDropdown');
    notifOpen = !notifOpen;
    drop.style.display = notifOpen ? 'block' : 'none';
    if (notifOpen) { loadNotifications(); updateBadge(0); } // খুললেই badge clear
}
function closeNotifPanel() {
    document.getElementById('notifDropdown').style.display = 'none';
    notifOpen = false;
}
function loadNotifications() {
    var list = document.getElementById('notifList');
    list.innerHTML = '<div class="bb-notif-loading"><i class="bi bi-arrow-clockwise"></i>Loading…</div>';
    fetch('/notifications', { headers:{ 'Accept':'application/json','X-Requested-With':'XMLHttpRequest' } })
    .then(r=>r.json())
    .then(d=>{
        list.innerHTML = (d.html && d.html.trim())
            ? d.html
            : '<div class="bb-notif-empty"><i class="bi bi-bell-slash"></i><p class="mb-0">No notifications yet</p></div>';
    })
    .catch(()=>{ list.innerHTML='<div class="bb-notif-empty"><p class="mb-0">Could not load.</p></div>'; });
}

// একটা item: read + close + navigate
function onNotifClick(el) {
    var id = el.dataset.id;
    if (el.dataset.read === '0') {
        el.classList.remove('bb-notif-unread');
        el.querySelector('.bb-notif-dot')?.remove();
        el.dataset.read = '1';
        fetch('/notifications/' + id + '/read', {
            method:'POST',
            headers:{ 'Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content }
        }).catch(()=>{});
    }
    closeNotifPanel();
    routeNotification(el.dataset.action, el.dataset.target);
}

function routeNotification(action, target) {
    var onHome = (location.pathname === '/' || location.pathname === '');
    if (action === 'profile') { location.href = '/profile/' + target; return; }
    if (action === 'job')     { location.href = '/jobs/' + target;    return; }
    if (action === 'report-decision') { window.open('/reports/' + target + '/decision', '_blank'); return; } // ⬅️ নতুন ট্যাব
    if (action === 'home')    { if (!onHome) location.href = '/'; return; }
    if (action === 'comments') {
        if (onHome) jumpAndComment(target);
        else location.href = '/?open_comments=' + target;
        return;
    }
    if (action === 'post') {
        if (onHome) jumpToPost(target);
        else location.href = '/?goto_post=' + target;
    }
}

function showJumpOverlay(text) {
    var ov = document.getElementById('bbJumpOverlay');
    var tx = document.getElementById('bbJumpText');
    if (tx && text) tx.innerText = text;
    if (ov) ov.style.display = 'flex';
}
function hideJumpOverlay() {
    var ov = document.getElementById('bbJumpOverlay');
    if (ov) ov.style.display = 'none';
}


// JUMP
function jumpToPost(postId) {
    showJumpOverlay('Taking you to the post…');
    var n = 0;
    (function go(){
        var t = document.getElementById('postCard-' + postId);
        if (t) {
            hideJumpOverlay();
            t.scrollIntoView({behavior:'smooth', block:'center'});
            t.style.transition='box-shadow .35s ease';
            t.style.boxShadow='0 0 0 3px #4f46e5';
            setTimeout(()=>t.style.boxShadow='', 2500);
            if (location.hash) history.replaceState(null, '', location.pathname + location.search);
            return;
        }
        if (typeof loadMorePosts === 'function') loadMorePosts();
        if (n++ < 30) setTimeout(go, 600);
        else hideJumpOverlay();
    })();
}

// আগে post এ scroll (দরকারে আরও feed load) তারপর comment modal — caption/media সহ
function jumpAndComment(postId) {
    showJumpOverlay('Opening comments…');
    var n = 0;
    (function go(){
        var t = document.getElementById('postCard-' + postId);
        if (t && typeof openCommentModal === 'function') {
            hideJumpOverlay();
            t.scrollIntoView({behavior:'smooth', block:'center'});
            setTimeout(function(){ openCommentModal(postId); }, 400);
            return;
        }
        if (typeof loadMorePosts === 'function') loadMorePosts();
        if (n++ < 30) setTimeout(go, 600);
        else hideJumpOverlay();
    })();
}

function markAllRead() {
    fetch('/notifications/read-all', {
        method:'POST',
        headers:{ 'Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(()=>{
        document.querySelectorAll('#notifList .bb-notif-item').forEach(function(el){
            el.classList.remove('bb-notif-unread');
            el.querySelector('.bb-notif-dot')?.remove();
            el.dataset.read = '1';
        });
        updateBadge(0);
    });
}

function updateBadge(count) {
    var badge = document.getElementById('notifBadge');
    var bell  = document.getElementById('notifBellBtn');
    lastNotifCount = count;
    if (count > 0) {
        badge.textContent = count > 99 ? '99+' : count;
        badge.style.display = 'block';
        if (bell) { bell.classList.add('bell-shake'); setTimeout(()=>bell.classList.remove('bell-shake'),600); }
    } else { badge.style.display = 'none'; }
}

document.addEventListener('click', function(e){
    var wrap = document.getElementById('notifWrap');
    if (wrap && !wrap.contains(e.target) && notifOpen) closeNotifPanel();
});

function pollNotifications() {
    fetch('/notifications/poll', { headers:{ 'Accept':'application/json' } })
    .then(r=>r.json())
    .then(d=>{ var c = d.count || 0; if (c > 0 && notifOpen) loadNotifications(); updateBadge(c); })
    .catch(()=>{});
}
pollNotifications();
setInterval(pollNotifications, 15000);

// ============================================================
// SECTION 18: MUTUAL FRIENDS MODAL
// ============================================================
function showMutualFriends(userId, name) {
    if (!_mutualModal) return;
    document.getElementById('mutualModalTitle').textContent  = 'Mutual Friends with ' + name;
    document.getElementById('mutualModalBody').innerHTML     = '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary"></div></div>';
    _mutualModal.show();

    fetch('/friends/' + userId + '/mutual', { headers: { 'Accept': 'application/json' } })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        if (!d.mutuals || !d.mutuals.length) {
            document.getElementById('mutualModalBody').innerHTML = '<p class="text-muted text-center small py-2">No mutual friends found.</p>';
            return;
        }
        var html = '';
        d.mutuals.forEach(function (m) {
            var pic = m.profile_picture
                ? '<img src="/storage/' + m.profile_picture + '" style="width:100%;height:100%;object-fit:cover;">'
                : m.name.charAt(0).toUpperCase();
            html += '<a href="/profile/' + m.id + '" style="display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid #f3f4f8;text-decoration:none;">'
                  + '<div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#7c73f0);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;overflow:hidden;flex-shrink:0;">' + pic + '</div>'
                  + '<div><div style="font-size:13.5px;font-weight:700;color:#1e1f24;">' + m.name + '</div>'
                  + '<div style="font-size:11.5px;color:#6b7280;">' + (m.department || m.role) + '</div></div>'
                  + '</a>';
        });
        document.getElementById('mutualModalBody').innerHTML = html;
    });
}


// ============================================================
// SECTION 19: REPORT
// ============================================================
function openReport(type, id, name) {
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
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({
            type:    document.getElementById('rType').value,
            id:      document.getElementById('rId').value,
            reason:  document.getElementById('rReason').value,
            details: document.getElementById('rDetails').value
        })
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        if (_reportModal) _reportModal.hide();
        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true })
            .fire({ icon: d.success ? 'success' : 'warning', title: d.message });
    });
}


// ============================================================
// SECTION 20: CHAT BOX (with message polling) — CORRECTED
// ============================================================


function saveOpenChatsState() {
    var state = [];
    Object.keys(openChatBoxes).forEach(function(uid) {
        var box = openChatBoxes[uid];
        state.push({
            userId: uid,
            name: box.getAttribute('data-name') || '',
            pic: box.getAttribute('data-pic') || '',
            hash: box.getAttribute('data-hash') || uid,
            minimized: box.classList.contains('minimized')
        });
    });
    try { localStorage.setItem('bbOpenChats', JSON.stringify(state)); } catch (e) {}
}

function restoreOpenChats() {
    var raw;
    try { raw = localStorage.getItem('bbOpenChats'); } catch (e) { return; }
    if (!raw) return;
    var state;
    try { state = JSON.parse(raw); } catch (e) { return; }
    if (!Array.isArray(state) || !state.length) return;

    state.forEach(function (c) {
        openChatBox(c.userId, c.name, c.pic, '', '0', c.hash, true);
        if (c.minimized) {
            var box = openChatBoxes[c.userId];
            if (box) {
                box.classList.add('minimized');
                stopMessagePolling(c.userId); // মিনিমাইজড অবস্থায় read মার্ক করা যাবে না
            }
        }
    });
}


var openChatBoxes = {};

var chatMsgCache = {};      // userId -> {msgId: msgObj}
var replyToState = {};      // userId -> msgId or null
var REACT_EMOJIS = ['👍','❤️','😂','😮','😢'];

var _msgTimers = {};
var _chatUnreadKnown = {};
var _lastSoundAt = 0;
var _audioCtx = null;
var _badgeCountKnown = null;
var _pollMinimizedTimer = null;


function openChatBox(userId, name, pic, lastSeen, isOnline, userHash, skipSave) {
    userHash = userHash || userId;
    if (openChatBoxes[userId]) {
        openChatBoxes[userId].classList.remove('minimized');
        openChatBoxes[userId].classList.remove('bb-chat-highlight');
        saveOpenChatsState();
        return;
    }

    var container = document.getElementById('chatBoxesContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'chatBoxesContainer';
        container.style.cssText = 'position:fixed;bottom:0;right:0;display:flex;gap:10px;padding:10px;z-index:1000;';
        document.body.appendChild(container);
    }

    var box = document.createElement('div');
    box.className = 'bb-chat-box';
    box.id = 'chatbox-' + userId;
    box.setAttribute('data-user-id', userId);
    box.setAttribute('data-name', name || '');
    box.setAttribute('data-pic', pic || '');
    box.setAttribute('data-hash', userHash);

    var avatarContent = pic ? '<img src="' + pic + '" style="width:100%;height:100%;object-fit:cover;">' : name.charAt(0).toUpperCase();
    var onlineDot = isOnline === '1' ? '<span style="position:absolute;bottom:2px;right:2px;width:10px;height:10px;background:#22c55e;border:2px solid white;border-radius:50%;display:block;"></span>' : '';
    var statusText = isOnline === '1' ? '<i class="bi bi-circle-fill text-success" style="font-size:7px;"></i> Active now' : lastSeen;

    box.innerHTML =
        '<div style="background:#4f46e5;color:white;padding:12px;border-radius:12px 12px 0 0;display:flex;align-items:center;justify-content:space-between;cursor:pointer;user-select:none;flex-shrink:0;" onclick="toggleChatMinimize(' + userId + ')">'
        + '<div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0;">'
        + '<div style="width:40px;height:40px;border-radius:50%;background:rgba(255,255,255,.3);display:flex;align-items:center;justify-content:center;font-weight:bold;color:white;position:relative;overflow:hidden;flex-shrink:0;">' + avatarContent + onlineDot + '</div>'
        + '<div style="flex:1;min-width:0;">'
        + '<p style="margin:0;font-weight:700;font-size:14px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + escHtml(name) + '</p>'
        + '<p style="margin:0;font-size:11px;opacity:.9;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + statusText + '</p>'
        + '</div></div>'
        + '<div style="display:flex;gap:4px;" onclick="event.stopPropagation();">'
        + '<button onclick="toggleChatSearch(' + userId + ')" style="background:rgba(255,255,255,.2);border:none;color:white;width:28px;height:28px;border-radius:50%;cursor:pointer;font-size:12px;"><i class="bi bi-search"></i></button>'
        + '<button onclick="openMediaGallery(' + userId + ')" style="background:rgba(255,255,255,.2);border:none;color:white;width:28px;height:28px;border-radius:50%;cursor:pointer;font-size:12px;"><i class="bi bi-images"></i></button>'
        + '<button onclick="toggleChatMinimize(' + userId + ')" style="background:rgba(255,255,255,.2);border:none;color:white;width:28px;height:28px;border-radius:50%;cursor:pointer;font-size:13px;"><i class="bi bi-dash-lg"></i></button>'
        + '<button onclick="closeChatBox(' + userId + ')" style="background:rgba(255,255,255,.2);border:none;color:white;width:28px;height:28px;border-radius:50%;cursor:pointer;font-size:13px;"><i class="bi bi-x-lg"></i></button>'
        + '</div></div>'
        + '<div class="bb-search-box-chat" id="chatSearchBox-' + userId + '">'
        + '<input type="text" placeholder="Search in conversation..." oninput="searchInThread(' + userId + ',this.value)">'
        + '</div>'
        + '<div id="msgZone-' + userId + '" class="bb-chat-body-zone" style="flex:1;overflow-y:auto;padding:12px;background:#fafbfc;display:flex;flex-direction:column;gap:4px;min-height:0;align-content:flex-start;">'
        + '<div id="msgPlaceholder-' + userId + '" style="text-align:center;color:#9ca3af;margin:auto;"><i class="bi bi-chat-dots" style="font-size:2rem;display:block;margin-bottom:8px;color:#d1d5db;"></i>Start a conversation</div>'
        + '</div>'
        + '<div class="bb-chat-footer-zone" style="padding:12px;border-top:1px solid #e5e7eb;flex-shrink:0;">'
        + '<div class="bb-reply-compose" id="replyCompose-' + userId + '">'
        + '<div style="min-width:0;flex:1;"><div style="font-weight:700;color:#4f46e5;font-size:11px;" id="replyComposeSender-' + userId + '"></div>'
        + '<div style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#374151;" id="replyComposeText-' + userId + '"></div></div>'
        + '<button type="button" onclick="clearReplyTo(' + userId + ')" style="background:transparent;border:none;color:#6b7280;cursor:pointer;font-size:14px;padding:2px 6px;">✕</button>'
        + '</div>'
        + '<div id="mediaPreview-' + userId + '" style="margin-bottom:8px;display:none;flex-wrap:wrap;gap:6px;max-height:70px;overflow-y:auto;"></div>'
        + '<input type="file" id="mediaInput-' + userId + '" multiple accept="image/*,video/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar" style="display:none;" onchange="handleMediaSelect(' + userId + ')">'
        + '<div style="display:flex;gap:4px;align-items:flex-end;">'
        + '<button type="button" onclick="document.getElementById(\'mediaInput-' + userId + '\').click()" title="Attach media" style="background:transparent;border:none;color:#4f46e5;width:30px;height:30px;border-radius:50%;cursor:pointer;font-size:17px;flex-shrink:0;display:flex;align-items:center;justify-content:center;"><i class="bi bi-image-fill"></i></button>'
        + '<textarea id="msgInput-' + userId + '" placeholder="Aa" rows="1" style="flex:1;padding:8px;border:1px solid #d1d5db;border-radius:6px;font-size:13px;font-family:inherit;resize:none;" onkeydown="if(event.key===\'Enter\'&&!event.shiftKey){event.preventDefault();sendMessageWithMedia(' + userId + ');}" oninput="this.style.height=\'auto\';this.style.height=Math.min(this.scrollHeight,80)+\'px\'"></textarea>'
        + '<button type="button" class="bb-emoji-btn" data-target="#msgInput-' + userId + '" title="Emoji" style="width:30px;height:30px;flex-shrink:0;font-size:17px;padding:0;display:flex;align-items:center;justify-content:center;"><i class="bi bi-emoji-smile"></i></button>'
        + '<button onclick="sendMessageWithMedia(' + userId + ')" style="background:#4f46e5;color:white;border:none;padding:8px 12px;border-radius:6px;cursor:pointer;font-size:13px;width:44px;flex-shrink:0;"><i class="bi bi-send-fill"></i></button>'
        + '</div></div>';

    var zone = box.querySelector('[id^="msgZone-"]');
    if (zone) {
        zone.setAttribute('data-last-id', '0');
        zone.setAttribute('data-oldest-id', '0');
        zone.setAttribute('data-has-more', '0');
        zone.addEventListener('scroll', function () {
            if (zone.scrollTop < 40) loadOlderMessages(userId);
        });
    }

    container.appendChild(box);
    openChatBoxes[userId] = box;
    replyToState[userId] = null;

    startMessagePolling(userId);
    if (!skipSave) saveOpenChatsState();

    setTimeout(function () {
        var input = document.getElementById('msgInput-' + userId);
        if (input) input.focus();
    }, 100);
}


function toggleChatMinimize(userId) {
    var box = openChatBoxes[userId];
    if (!box) return;
    if (box.classList.contains('minimized')) {
        unminimizeChatBox(userId);
    } else {
        box.classList.add('minimized');
        box.classList.remove('bb-chat-highlight');
        stopMessagePolling(userId);
        saveOpenChatsState();
    }
}

function unminimizeChatBox(userId) {
    var box = openChatBoxes[userId];
    if (!box) return;
    box.classList.remove('minimized');
    box.classList.remove('bb-chat-highlight');
    delete _chatUnreadKnown[userId];
    fetchMessages(userId);       // এখনই থ্রেড ফেচ — read মার্ক হবে
    startMessagePolling(userId); // normal polling আবার চালু
    updateMessengerBadge();      // navbar badge সাথে সাথে আপডেট
    saveOpenChatsState();
}

function closeChatBox(userId) {
    stopMessagePolling(userId);
    delete _chatUnreadKnown[userId];
    var box = openChatBoxes[userId];
    if (box) box.remove();
    delete openChatBoxes[userId];
    saveOpenChatsState();
}

// ============================================================
// MESSAGE POLLING — প্রতি ৫ সেকেন্ডে নতুন message fetch
// ============================================================
function startMessagePolling(userId) {
    if (_msgTimers[userId]) clearInterval(_msgTimers[userId]);
    fetchMessages(userId);  // এখনই একবার
    _msgTimers[userId] = setInterval(function() { fetchMessages(userId); }, 5000);
}

function stopMessagePolling(userId) {
    if (_msgTimers[userId]) {
        clearInterval(_msgTimers[userId]);
        delete _msgTimers[userId];
    }
}

function fetchMessages(userId) {
    fetch('/message/thread/' + userId, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(d => {
        if (!d.messages) return;
        var zone = document.getElementById('msgZone-' + userId);
        if (!zone) return;
        if (!chatMsgCache[userId]) chatMsgCache[userId] = {};

        var placeholder = document.getElementById('msgPlaceholder-' + userId);
        var lastIdBefore = parseInt(zone.getAttribute('data-last-id') || '0');
        var appendedNew = false;

        d.messages.forEach(function (msg) {
            var cached = chatMsgCache[userId][msg.id];
            var changed = !cached || JSON.stringify(cached) !== JSON.stringify(msg);
            chatMsgCache[userId][msg.id] = msg;

            var existingRow = zone.querySelector('.bb-msg-row[data-msg-id="' + msg.id + '"]');
            if (existingRow) {
                if (changed) existingRow.outerHTML = renderMessageRow(msg, userId);
            } else {
                if (placeholder) { placeholder.remove(); placeholder = null; }
                zone.insertAdjacentHTML('beforeend', renderMessageRow(msg, userId));
                if (msg.id > lastIdBefore) appendedNew = true;
            }
        });

        var allIds = d.messages.map(m => m.id);
        if (allIds.length) {
            zone.setAttribute('data-last-id', Math.max(allIds.reduce((a,b)=>Math.max(a,b), lastIdBefore)));
            zone.setAttribute('data-oldest-id', Math.min(...allIds));
        }
        zone.setAttribute('data-has-more', d.has_more ? '1' : '0');

        if (appendedNew) zone.scrollTop = zone.scrollHeight;
    })
    .catch(err => console.error('Fetch error:', err));
}

function canEditMessage(msg) {
    if (!msg.created_at_ts) return true; // fallback, backend আপডেট না হলে পুরনো আচরণ
    var FIFTEEN_MIN = 15 * 60 * 1000;
    return (Date.now() - msg.created_at_ts) <= FIFTEEN_MIN;
}

function renderMessageRow(msg, userId) {
    var side = msg.is_mine ? 'mine' : 'theirs';
    var bubbleBg = msg.is_mine ? 'background:#4f46e5;color:#fff;' : 'background:#e5e7eb;color:#1e1f24;';
    var radius = msg.is_mine ? '14px 14px 4px 14px' : '14px 14px 14px 4px';

    var forwardedHtml = msg.forwarded
        ? '<div style="font-size:10px;color:#9ca3af;font-style:italic;display:flex;align-items:center;gap:3px;margin-bottom:2px;' + (msg.is_mine ? 'justify-content:flex-end;' : '') + '"><i class="bi bi-arrow-90deg-right"></i> Forwarded</div>'
        : '';

    var replyHtml = '';
    if (msg.reply_to) {
        var replyBg = msg.is_mine ? 'background:rgba(255,255,255,.2);border-left-color:#fff;' : 'background:rgba(79,70,229,.1);border-left-color:#4f46e5;';
        replyHtml = '<div class="bb-reply-preview-inline" onclick="jumpToMessage(' + userId + ',' + msg.reply_to.id + ')" style="' + replyBg + (msg.is_mine ? 'color:#fff;' : 'color:#374151;') + '">'
            + '<strong>' + escHtml(msg.reply_to.sender_name) + '</strong>: ' + escHtml((msg.reply_to.message || '').substring(0, 40))
            + '</div>';
    }

    var bodyHtml;
    if (msg.is_deleted) {
        bodyHtml = '<div style="padding:2px 4px;font-style:italic;opacity:.7;font-size:11.5px;">This message was deleted</div>';
    } else {
        var mediaHtml = renderMsgMedia(msg.media || []);
        var textHtml = msg.message ? '<div style="padding:2px 4px;">' + escHtml(msg.message) + '</div>' : '';
        bodyHtml = replyHtml + mediaHtml + textHtml;
    }

    var reactionsHtml = '';
    var reactions = msg.reactions || {};
    var reactKeys = Object.keys(reactions).filter(e => reactions[e] && reactions[e].length);
    if (reactKeys.length) {
        reactionsHtml = '<div class="bb-msg-reactions" style="' + (msg.is_mine ? 'justify-content:flex-end;' : '') + '">';
        reactKeys.forEach(function (e) {
            reactionsHtml += '<span class="bb-msg-reaction-pill" onclick="openReactModal(' + msg.id + ',' + userId + ')">' + e + ' ' + reactions[e].length + '</span>';
        });
        reactionsHtml += '</div>';
    }

    var reactBarHtml = '<div class="bb-react-bar" id="reactBar-' + msg.id + '">' + REACT_EMOJIS.map(e =>
        '<span class="bb-react-emoji" onclick="reactToMessage(' + msg.id + ',\'' + e + '\',' + userId + ')">' + e + '</span>'
    ).join('') + '</div>';

    var menuItems = [];
    if (!msg.is_deleted) {
        menuItems.push('<div class="bb-msg-menu-item" onclick="startReplyTo(' + userId + ',' + msg.id + ')"><i class="bi bi-reply-fill"></i> Reply</div>');
        menuItems.push('<div class="bb-msg-menu-item" onclick="openForwardModal(' + msg.id + ')"><i class="bi bi-arrow-90deg-right"></i> Forward</div>');
        if (msg.is_mine) {
            if (canEditMessage(msg)) {
                menuItems.push('<div class="bb-msg-menu-item" onclick="editMessageUI(' + userId + ',' + msg.id + ')"><i class="bi bi-pencil"></i> Edit</div>');
            }
            menuItems.push('<div class="bb-msg-menu-item danger" onclick="deleteMessageUI(' + userId + ',' + msg.id + ',\'me\')"><i class="bi bi-trash"></i> Delete for me</div>');
            menuItems.push('<div class="bb-msg-menu-item danger" onclick="deleteMessageUI(' + userId + ',' + msg.id + ',\'everyone\')"><i class="bi bi-trash-fill"></i> Delete for everyone</div>');
        } else {
            menuItems.push('<div class="bb-msg-menu-item danger" onclick="deleteMessageUI(' + userId + ',' + msg.id + ',\'me\')"><i class="bi bi-trash"></i> Delete for me</div>');
        }
    }

    var dotsHtml = msg.is_deleted ? '' :
        '<div class="bb-msg-actions">'
        + '<button class="bb-msg-action-btn" onclick="toggleReactBar(event,' + msg.id + ')"><i class="bi bi-emoji-smile"></i></button>'
        + '<button class="bb-msg-action-btn" onclick="toggleMsgMenu(event,' + msg.id + ')"><i class="bi bi-three-dots"></i></button>'
        + '</div>'
        + '<div class="bb-msg-menu" id="msgMenu-' + msg.id + '">' + menuItems.join('') + '</div>';

    return '<div class="bb-msg-row ' + side + '" data-msg-id="' + msg.id + '">'
        + (side === 'mine' ? dotsHtml : '')
        + '<div class="bb-msg-bubble-wrap">'
        + forwardedHtml
        + '<div style="position:relative;padding:6px 8px;border-radius:' + radius + ';font-size:12px;word-wrap:break-word;' + bubbleBg + '">'
        + reactBarHtml + bodyHtml
        + '<div style="font-size:10px;opacity:.7;margin-top:2px;padding:0 4px;">' + msg.created_at + '</div>'
        + '</div>' + reactionsHtml + '</div>'
        + (side === 'theirs' ? dotsHtml : '')
        + '</div>';
}

function sendMessage(userId) {
    var input = document.getElementById('msgInput-' + userId);
    if (!input) return;

    var text = input.value.trim();
    if (!text) return;

    var zone = document.getElementById('msgZone-' + userId);
    var tempDiv = null;

    if (zone) {
        var placeholder = document.getElementById('msgPlaceholder-' + userId);
        if (placeholder) placeholder.remove();

        tempDiv = document.createElement('div');
        tempDiv.setAttribute('data-msg-id', 'temp-' + Date.now());
        tempDiv.style.cssText = 'padding:8px 12px;border-radius:10px;margin-bottom:6px;word-wrap:break-word;font-size:12px;max-width:80%;background:#4f46e5;color:#fff;margin-left:auto;';
        tempDiv.innerHTML = '<div>' + escHtml(text) + '</div><div style="font-size:10px;opacity:.7;margin-top:2px;">Sending...</div>';
        zone.appendChild(tempDiv);
        zone.scrollTop = zone.scrollHeight;
    }

    input.value = '';
    input.style.height = 'auto';

    fetch('/message/send', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ recipient_id: userId, message: text })
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            if (tempDiv) {
                tempDiv.setAttribute('data-msg-id', d.message_id);
                var timeDiv = tempDiv.querySelector('div:last-child');
                if (timeDiv) timeDiv.textContent = 'Just now';
            }
            if (zone) {
                var currentLast = parseInt(zone.getAttribute('data-last-id') || '0');
                if (d.message_id > currentLast) {
                    zone.setAttribute('data-last-id', d.message_id);
                }
            }
        } else {
            if (tempDiv) tempDiv.remove();
            alert('Error: ' + (d.error || 'Could not send'));
        }
    })
    .catch(err => {
        console.error('Send error:', err);
        if (tempDiv) tempDiv.remove();
        alert('Network error');
    });
}


function escHtml(s) {
    return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}

function ensureReactModal() {
    if (document.getElementById('bbReactModalOverlay')) return;
    var el = document.createElement('div');
    el.id = 'bbReactModalOverlay';
    el.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:6000;display:none;align-items:center;justify-content:center;';
    el.innerHTML = '<div style="background:#fff;border-radius:14px;width:300px;max-height:60vh;display:flex;flex-direction:column;overflow:hidden;">'
        + '<div style="padding:12px 16px;border-bottom:1px solid #eceef1;display:flex;align-items:center;justify-content:space-between;">'
        + '<div id="reactModalTabs" style="display:flex;gap:12px;font-size:15px;"></div>'
        + '<button onclick="closeReactModal()" style="border:none;background:transparent;font-size:16px;cursor:pointer;">✕</button>'
        + '</div><div id="reactModalList" style="overflow-y:auto;padding:6px 0;"></div></div>';
    document.body.appendChild(el);
    el.addEventListener('click', function (ev) { if (ev.target === el) closeReactModal(); });
}

function openReactModal(msgId, userId) {
    var msg = (chatMsgCache[userId] || {})[msgId];
    if (!msg) return;
    var reactions = msg.reactions || {};
    var emojis = Object.keys(reactions).filter(e => reactions[e] && reactions[e].length);
    if (!emojis.length) return;

    ensureReactModal();
    var tabsEl = document.getElementById('reactModalTabs');
    var listEl = document.getElementById('reactModalList');
    var activeEmoji = emojis[0];

    function renderTabs() {
        tabsEl.innerHTML = emojis.map(e =>
            '<span onclick="selectReactTab(\'' + e + '\')" style="cursor:pointer;padding:4px 8px;border-radius:8px;' + (e === activeEmoji ? 'background:#eef2ff;' : '') + '">' + e + ' ' + reactions[e].length + '</span>'
        ).join('');
    }
    function renderList(e) {
        listEl.innerHTML = reactions[e].map(u =>
            '<div style="display:flex;align-items:center;gap:10px;padding:8px 16px;">'
            + '<div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#7c73f0);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;">' + u.name.charAt(0).toUpperCase() + '</div>'
            + '<span style="font-size:13px;">' + escHtml(u.name) + '</span></div>'
        ).join('');
    }
    window.selectReactTab = function (e) { activeEmoji = e; renderTabs(); renderList(e); };

    renderTabs();
    renderList(activeEmoji);
    document.getElementById('bbReactModalOverlay').style.display = 'flex';
}

function closeReactModal() {
    var el = document.getElementById('bbReactModalOverlay');
    if (el) el.style.display = 'none';
}

// ===== THREE-DOT MENU =====
document.addEventListener('click', function (e) {
    var row = e.target.closest('.bb-msg-row');
    document.querySelectorAll('.bb-msg-row.bb-touch-active').forEach(function (r) {
        if (r !== row) r.classList.remove('bb-touch-active');
    });
    if (row && !e.target.closest('.bb-msg-actions') && !e.target.closest('.bb-msg-menu')) {
        row.classList.toggle('bb-touch-active');
    }
    if (!e.target.closest('.bb-msg-menu') && !e.target.closest('.bb-msg-action-btn')) {
        document.querySelectorAll('.bb-msg-menu.show').forEach(m => m.classList.remove('show'));
    }
    if (!e.target.closest('.bb-react-bar') && !e.target.closest('.bb-msg-action-btn')) {
        document.querySelectorAll('.bb-react-bar.show').forEach(m => m.classList.remove('show'));
    }
});

function toggleReactBar(e, msgId) {
    e.stopPropagation();
    var bar = document.getElementById('reactBar-' + msgId);
    if (!bar) return;
    var wasOpen = bar.classList.contains('show');
    document.querySelectorAll('.bb-react-bar.show, .bb-msg-menu.show').forEach(m => m.classList.remove('show'));
    if (!wasOpen) bar.classList.add('show');
}

function toggleMsgMenu(e, msgId) {
    e.stopPropagation();
    var menu = document.getElementById('msgMenu-' + msgId);
    if (!menu) return;
    var wasOpen = menu.classList.contains('show');
    document.querySelectorAll('.bb-msg-menu.show').forEach(m => m.classList.remove('show'));
    if (wasOpen) return;

    var btn = e.target.closest('.bb-msg-action-btn');
    var row = btn.closest('.bb-msg-row');
    var rowRect = row.getBoundingClientRect();
    var btnRect = btn.getBoundingClientRect();
    menu.style.top = (btnRect.bottom - rowRect.top) + 'px';
    menu.style.left = row.classList.contains('mine') ? 'auto' : (btnRect.left - rowRect.left) + 'px';
    menu.style.right = row.classList.contains('mine') ? (rowRect.right - btnRect.right) + 'px' : 'auto';
    menu.classList.add('show');
}

// ===== EDIT (inline in input box, Facebook style) =====
var editState = {}; // userId -> msgId

function editMessageUI(userId, msgId) {
    document.querySelectorAll('.bb-msg-menu.show').forEach(m => m.classList.remove('show'));
    var msg = (chatMsgCache[userId] || {})[msgId];
    if (!msg) return;

    editState[userId] = msgId;
    clearReplyTo(userId);

    var input = document.getElementById('msgInput-' + userId);
    if (input) {
        input.value = msg.message || '';
        input.focus();
        input.style.height = 'auto';
        input.style.height = Math.min(input.scrollHeight, 80) + 'px';
    }

    var box = document.getElementById('editCompose-' + userId);
    if (!box) {
        box = document.createElement('div');
        box.id = 'editCompose-' + userId;
        box.className = 'bb-reply-compose';
        box.style.display = 'flex';
        box.innerHTML = '<div style="min-width:0;flex:1;"><div style="font-weight:700;color:#f59e0b;font-size:11px;"><i class="bi bi-pencil-fill"></i> Editing message</div></div>'
            + '<button type="button" onclick="cancelEditMessage(' + userId + ')" style="background:transparent;border:none;color:#6b7280;cursor:pointer;font-size:14px;padding:2px 6px;">✕</button>';
        var replyBox = document.getElementById('replyCompose-' + userId);
        replyBox.parentNode.insertBefore(box, replyBox);
    }
    box.style.display = 'flex';
}

function cancelEditMessage(userId) {
    delete editState[userId];
    var box = document.getElementById('editCompose-' + userId);
    if (box) box.style.display = 'none';
    var input = document.getElementById('msgInput-' + userId);
    if (input) { input.value = ''; input.style.height = 'auto'; }
}

function submitEditMessage(userId) {
    var msgId = editState[userId];
    var input = document.getElementById('msgInput-' + userId);
    var newText = input.value.trim();
    if (!newText) return;

    fetch('/message/' + msgId + '/edit', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
        body: JSON.stringify({ message: newText })
    })
    .then(r => r.json())
    .then(d => {
        if (!d.success) {
        Swal.fire({ icon: 'warning', title: d.error || 'Cannot edit this message anymore' });
        cancelEditMessage(userId);
        return;
    }
        if (!d.success) return;
        var msg = chatMsgCache[userId][msgId];
        var row = document.querySelector('.bb-msg-row[data-msg-id="' + msgId + '"]');
        if (row && msg) {
            msg.message = newText;
            chatMsgCache[userId][msgId] = msg;
            row.outerHTML = renderMessageRow(msg, userId);
        }
        cancelEditMessage(userId);
        input.value = ''; input.style.height = 'auto';
    });
}

// ===== DELETE =====
function deleteMessageUI(userId, msgId, action) {
    document.querySelectorAll('.bb-msg-menu.show').forEach(m => m.classList.remove('show'));
    if (!confirm(action === 'everyone' ? 'Delete this message for everyone?' : 'Delete this message for you?')) return;

    fetch('/message/' + msgId + '/delete', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
        body: JSON.stringify({ action: action })
    })
    .then(r => r.json())
    .then(d => {
        if (!d.success) return;
        var row = document.querySelector('.bb-msg-row[data-msg-id="' + msgId + '"]');
        if (action === 'me') {
            if (row) row.remove();
        } else if (row) {
            var msg = chatMsgCache[userId][msgId];
            msg.is_deleted = true;
            row.outerHTML = renderMessageRow(msg, userId);
        }
    });
}

// ===== REACT =====
function reactToMessage(msgId, emoji, userId) {
    fetch('/message/' + msgId + '/react', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
        body: JSON.stringify({ emoji: emoji })
    })
    .then(r => r.json())
    .then(d => {
        document.querySelectorAll('.bb-react-bar.show').forEach(m => m.classList.remove('show'));
        if (d.success) fetchMessages(userId);
    });
}

// ===== REPLY =====
function startReplyTo(userId, msgId) {
    document.querySelectorAll('.bb-msg-menu.show').forEach(m => m.classList.remove('show'));
    var msg = (chatMsgCache[userId] || {})[msgId];
    if (!msg) return;
    replyToState[userId] = msgId;

    var box = document.getElementById('replyCompose-' + userId);
    var senderEl = document.getElementById('replyComposeSender-' + userId);
    var textEl = document.getElementById('replyComposeText-' + userId);
    if (senderEl) senderEl.textContent = msg.is_mine ? 'You' : (document.getElementById('chatbox-' + userId)?.getAttribute('data-name') || 'User');
    if (textEl) textEl.textContent = msg.message || '[Media]';
    if (box) box.style.display = 'flex';

    var input = document.getElementById('msgInput-' + userId);
    if (input) input.focus();
}

function clearReplyTo(userId) {
    replyToState[userId] = null;
    var box = document.getElementById('replyCompose-' + userId);
    if (box) box.style.display = 'none';
}

function jumpToMessage(userId, msgId) {
    var row = document.querySelector('.bb-msg-row[data-msg-id="' + msgId + '"]');
    if (row) {
        row.scrollIntoView({ behavior: 'smooth', block: 'center' });
        row.style.transition = 'background .3s';
        var inner = row.querySelector('[style*="border-radius"]');
        if (inner) {
            var old = inner.style.boxShadow;
            inner.style.boxShadow = '0 0 0 2px #f59e0b';
            setTimeout(() => inner.style.boxShadow = old, 1200);
        }
    }
}

// ===== FORWARD =====
var _forwardMsgId = null;

function openForwardModal(msgId) {
    document.querySelectorAll('.bb-msg-menu.show').forEach(m => m.classList.remove('show'));
    _forwardMsgId = msgId;

    var overlay = document.getElementById('bbForwardOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'bbForwardOverlay';
        overlay.className = 'bb-forward-modal-overlay';
        overlay.innerHTML = '<div class="bb-forward-modal">'
            + '<div class="bb-forward-modal-head"><span>Forward Message</span><button onclick="closeForwardModal()" style="border:none;background:transparent;font-size:18px;cursor:pointer;">✕</button></div>'
            + '<div class="bb-forward-search"><input type="text" placeholder="Search friends..." oninput="renderForwardContacts(this.value)"></div>'
            + '<div id="forwardContactList" style="overflow-y:auto;flex:1;"></div>'
            + '</div>';
        document.body.appendChild(overlay);
        overlay.addEventListener('click', function (e) { if (e.target === overlay) closeForwardModal(); });
    }
    overlay.style.display = 'flex';

    fetch('/friends/messenger-contacts', { headers: { 'Accept': 'application/json' } })
    .then(r => r.json())
    .then(d => { window._forwardContacts = d.contacts || []; renderForwardContacts(''); })
    .catch(() => {});
}

function renderForwardContacts(q) {
    var list = document.getElementById('forwardContactList');
    if (!list) return;
    var contacts = (window._forwardContacts || []).filter(c => c.name.toLowerCase().includes((q || '').toLowerCase()));
    if (!contacts.length) { list.innerHTML = '<div class="text-muted text-center small py-3">No contacts found</div>'; return; }

    list.innerHTML = contacts.map(c => {
        var pic = c.profile_picture ? '<img src="/storage/' + c.profile_picture + '" style="width:100%;height:100%;object-fit:cover;">' : c.name.charAt(0).toUpperCase();
        return '<div class="bb-forward-contact-item" onclick="sendForward(' + c.id + ',\'' + escHtml(c.name).replace(/'/g,"\\'") + '\')">'
            + '<div style="width:38px;height:38px;border-radius:50%;overflow:hidden;background:linear-gradient(135deg,#4f46e5,#7c73f0);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;">' + pic + '</div>'
            + '<span style="font-size:13px;font-weight:600;">' + escHtml(c.name) + '</span></div>';
    }).join('');
}

function sendForward(recipientId, name) {
    if (!_forwardMsgId) return;
    fetch('/message/' + _forwardMsgId + '/forward', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
        body: JSON.stringify({ recipient_id: recipientId })
    })
    .then(r => r.json())
    .then(d => {
        closeForwardModal();
        if (d.success) {
            Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1800 }).fire({ icon: 'success', title: 'Forwarded to ' + name });
        }
    });
}

function closeForwardModal() {
    var overlay = document.getElementById('bbForwardOverlay');
    if (overlay) overlay.style.display = 'none';
    _forwardMsgId = null;
}

// ===== SEARCH IN CONVERSATION =====
var _searchTimer = {};
function toggleChatSearch(userId) {
    var box = document.getElementById('chatSearchBox-' + userId);
    if (!box) return;
    var showing = box.style.display === 'block';
    box.style.display = showing ? 'none' : 'block';
    if (!showing) box.querySelector('input').focus();
    else { box.querySelector('input').value = ''; fetchMessages(userId); }
}

function searchInThread(userId, q) {
    clearTimeout(_searchTimer[userId]);
    _searchTimer[userId] = setTimeout(function () {
        if (!q.trim()) { renderThreadMessages(userId, Object.values(chatMsgCache[userId] || {})); return; }
        fetch('/message/thread/' + userId + '/search?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(d => {
            (d.messages || []).forEach(m => { if (!chatMsgCache[userId]) chatMsgCache[userId] = {}; chatMsgCache[userId][m.id] = m; });
            renderThreadMessages(userId, d.messages || [], true);
        });
    }, 300);
}

function renderThreadMessages(userId, messages, isSearch) {
    var zone = document.getElementById('msgZone-' + userId);
    if (!zone) return;
    zone.innerHTML = messages.length
        ? messages.map(m => renderMessageRow(m, userId)).join('')
        : '<div style="text-align:center;color:#9ca3af;margin:auto;">' + (isSearch ? 'No matches found' : 'Start a conversation') + '</div>';
    if (!isSearch) zone.scrollTop = zone.scrollHeight;
}

// ===== INFINITE SCROLL (older messages) =====
var _loadingOlder = {};
function loadOlderMessages(userId) {
    var zone = document.getElementById('msgZone-' + userId);
    if (!zone || _loadingOlder[userId]) return;
    if (zone.getAttribute('data-has-more') !== '1') return;

    var oldestId = zone.getAttribute('data-oldest-id');
    if (!oldestId || oldestId === '0') return;

    _loadingOlder[userId] = true;
    var prevHeight = zone.scrollHeight;

    fetch('/message/thread/' + userId + '/older?before_id=' + oldestId, { headers: { 'Accept': 'application/json' } })
    .then(r => r.json())
    .then(d => {
        _loadingOlder[userId] = false;
        if (!d.messages || !d.messages.length) { zone.setAttribute('data-has-more', '0'); return; }

        if (!chatMsgCache[userId]) chatMsgCache[userId] = {};
        d.messages.forEach(m => chatMsgCache[userId][m.id] = m);

        var html = d.messages.map(m => renderMessageRow(m, userId)).join('');
        zone.insertAdjacentHTML('afterbegin', html);
        zone.setAttribute('data-oldest-id', d.messages[0].id);
        zone.setAttribute('data-has-more', d.has_more ? '1' : '0');
        zone.scrollTop = zone.scrollHeight - prevHeight;
    })
    .catch(() => { _loadingOlder[userId] = false; });
}

// ===== MEDIA GALLERY =====
function openMediaGallery(userId) {
    var overlay = document.getElementById('bbGalleryOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'bbGalleryOverlay';
        overlay.className = 'bb-gallery-modal-overlay';
        overlay.innerHTML = '<div class="bb-gallery-modal">'
            + '<div class="bb-gallery-modal-head"><span>Shared Media</span><button onclick="closeMediaGallery()" style="border:none;background:transparent;font-size:18px;cursor:pointer;">✕</button></div>'
            + '<div class="bb-gallery-grid" id="galleryGrid"><div class="text-center text-muted small py-4" style="grid-column:1/-1;">Loading...</div></div>'
            + '</div>';
        document.body.appendChild(overlay);
        overlay.addEventListener('click', function (e) { if (e.target === overlay) closeMediaGallery(); });
    }
    overlay.style.display = 'flex';

    fetch('/message/thread/' + userId + '/media', { headers: { 'Accept': 'application/json' } })
    .then(r => r.json())
    .then(d => {
        var grid = document.getElementById('galleryGrid');
        var media = d.media || [];
        if (!media.length) { grid.innerHTML = '<div class="text-center text-muted small py-4" style="grid-column:1/-1;">No media shared yet</div>'; return; }

        var visualMedia = media.filter(f => f.type === 'image' || f.type === 'video');
        var lbData = JSON.stringify(visualMedia.map(f => ({ type: f.type, url: f.url }))).replace(/"/g, '&quot;');

        grid.innerHTML = media.map(function (f, idx) {
            if (f.type === 'image') {
                var vIdx = visualMedia.findIndex(v => v.url === f.url);
                return '<img src="' + f.url + '" onclick="openLightbox(\'' + lbData + '\',' + vIdx + ')">';
            } else if (f.type === 'video') {
                var vIdx2 = visualMedia.findIndex(v => v.url === f.url);
                return '<video src="' + f.url + '" onclick="openLightbox(\'' + lbData + '\',' + vIdx2 + ')" muted></video>';
            } else {
                return '<a href="' + f.url + '" target="_blank" download style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100px;background:#f3f4f6;border-radius:4px;text-decoration:none;color:#374151;font-size:10px;text-align:center;padding:4px;"><i class="bi bi-file-earmark-fill" style="font-size:22px;"></i>' + escHtml(f.name.substring(0, 12)) + '</a>';
            }
        }).join('');
    })
    .catch(() => {});
}

function closeMediaGallery() {
    var overlay = document.getElementById('bbGalleryOverlay');
    if (overlay) overlay.style.display = 'none';
}

// ============================================================
// SECTION 21: LIVE SEARCH
// ============================================================
(function () {
    var input    = document.getElementById('bbLiveSearch');
    var dropdown = document.getElementById('bbSearchDropdown');
    if (!input || !dropdown) return;

    var timer = null, activeIdx = -1;

    input.addEventListener('focus', function () {
        if (this.value.trim().length < 2) showRecent();
    });

    input.addEventListener('input', function () {
        clearTimeout(timer);
        var q = this.value.trim();
        if (q.length < 2) { showRecent(); return; }
        dropdown.innerHTML = '<div class="bb-sd-spinner"><i class="bi bi-search me-1"></i> Searching...</div>';
        dropdown.classList.add('show');
        timer = setTimeout(function () { doSearch(q); }, 320);
    });

    input.addEventListener('keydown', function (e) {
        var items = dropdown.querySelectorAll('.bb-sd-item');
        if (e.key === 'ArrowDown')  { e.preventDefault(); activeIdx = Math.min(activeIdx + 1, items.length - 1); hl(items); }
        else if (e.key === 'ArrowUp')   { e.preventDefault(); activeIdx = Math.max(activeIdx - 1, 0); hl(items); }
        else if (e.key === 'Enter')  { e.preventDefault(); if (activeIdx >= 0 && items[activeIdx]) items[activeIdx].click(); else go(); }
        else if (e.key === 'Escape') { close(); input.blur(); }
    });

    document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) close();
    });

    function close()   { dropdown.classList.remove('show'); dropdown.innerHTML = ''; activeIdx = -1; }
    function go()      { var q = input.value.trim(); if (q) window.location.href = '/search?q=' + encodeURIComponent(q); }
    function hl(items) { items.forEach(function (el, i) { el.classList.toggle('active', i === activeIdx); }); }

    function showRecent() {
        fetch('/search/recent', { headers: { 'Accept': 'application/json' } })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (!d.searches || !d.searches.length) { close(); return; }
            var html = '<div class="bb-sd-label" style="display:flex;align-items:center;justify-content:space-between;"><span>Recent Searches</span>'
                     + '<button onclick="clearAllRecent(event)" style="font-size:11px;font-weight:600;color:#4f46e5;border:none;background:transparent;cursor:pointer;padding:0;">Clear all</button></div>';
            d.searches.forEach(function (s) {
                html += '<div class="bb-sd-item" onclick="window.location.href=\'/search?q=' + encodeURIComponent(s.query) + '\'">'
                      + '<div class="bb-sd-avatar" style="background:#f3f4f8;color:#6b7280;font-size:16px;"><i class="bi bi-clock-history"></i></div>'
                      + '<div class="bb-sd-info"><div class="bb-sd-name">' + esc(s.query) + '</div></div>'
                      + '<button onclick="deleteRecent(event,' + s.id + ')" style="border:none;background:transparent;color:#9ca3af;cursor:pointer;padding:2px 6px;border-radius:6px;" title="Remove"><i class="bi bi-x-lg" style="font-size:11px;"></i></button>'
                      + '</div>';
            });
            dropdown.innerHTML = html;
            dropdown.classList.add('show');
        })
        .catch(function () { close(); });
    }

    window.deleteRecent = function (e, id) {
        e.stopPropagation();
        fetch('/search/recent/' + id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } })
        .then(function () { showRecent(); });
    };
    window.clearAllRecent = function (e) {
        e.stopPropagation();
        fetch('/search/recent', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' } })
        .then(function () { close(); });
    };

    function doSearch(q) {
        fetch('/search/live?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function (r) { return r.json(); })
        .then(function (data) { render(data.results || [], q); })
        .catch(function () { dropdown.innerHTML = '<div class="bb-sd-empty">Something went wrong.</div>'; });
    }

    function render(results, q) {
        activeIdx = -1;
        if (!results.length) { dropdown.innerHTML = '<div class="bb-sd-empty"><i class="bi bi-search me-1"></i> No results for "' + esc(q) + '"</div>'; return; }
        var html = '<div class="bb-sd-label">People</div>';
        results.forEach(function (r) {
            var av  = r.avatar ? '<img src="' + esc(r.avatar) + '" alt="">' : esc(r.initial || 'U');
            var top = r.topic ? '<div class="bb-sd-topic"><i class="bi bi-journal-text"></i> ' + esc(r.topic.substring(0, 55)) + (r.topic.length > 55 ? '…' : '') + '</div>' : '';
            html += '<a href="/search?q=' + encodeURIComponent(r.name) + '" class="bb-sd-item">'
                  + '<div class="bb-sd-avatar">' + av + '</div>'
                  + '<div class="bb-sd-info"><div class="bb-sd-name">' + hlq(esc(r.name), q) + '</div>'
                  + (r.sub ? '<div class="bb-sd-sub">' + esc(r.sub) + '</div>' : '') + top + '</div>'
                  + '<span class="bb-sd-rolechip bb-sd-' + r.role + '">' + esc(r.role_label || r.role) + '</span>'
                  + '</a>';
        });
        html += '<a href="/search?q=' + encodeURIComponent(q) + '" class="bb-sd-footer"><i class="bi bi-search me-1"></i> See all results for "' + esc(q) + '"</a>';
        dropdown.innerHTML = html;
        dropdown.classList.add('show');
    }

    function hlq(text, q) {
        if (!q) return text;
        return text.replace(new RegExp('(' + q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi'), '<mark style="background:#dbeafe;padding:0 2px;border-radius:2px;">$1</mark>');
    }
    function esc(s) { return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'); }
})();


var mediaFiles = {};  // userId -> File[]

function handleMediaSelect(userId) {
    var input = document.getElementById('mediaInput-' + userId);
    var newFiles = Array.from(input.files);
    if (!mediaFiles[userId]) mediaFiles[userId] = [];

    var oversized = newFiles.find(f => f.size > 25 * 1024 * 1024);
    if (oversized) {
        alert('"' + oversized.name + '" size too large. Max 25MB per file.');
        input.value = '';
        return;
    }

    mediaFiles[userId] = mediaFiles[userId].concat(newFiles);
    input.value = ''; // একই ফাইল আবার সিলেক্ট করা যাবে
    updateMediaPreview(userId);
}

function getFileKind(file) {
    if (file.type.startsWith('image/')) return 'image';
    if (file.type.startsWith('video/')) return 'video';
    var ext = file.name.split('.').pop().toLowerCase();
    if (ext === 'pdf') return 'pdf';
    if (ext === 'doc' || ext === 'docx') return 'doc';
    if (ext === 'xls' || ext === 'xlsx') return 'xls';
    if (ext === 'ppt' || ext === 'pptx') return 'ppt';
    if (ext === 'zip' || ext === 'rar' || ext === '7z') return 'zip';
    return 'file';
}

var FILE_KIND_ICON = {
    pdf:  { icon: 'bi-file-earmark-pdf-fill',   color: '#dc2626', label: 'PDF'  },
    doc:  { icon: 'bi-file-earmark-word-fill',  color: '#2563eb', label: 'DOC'  },
    xls:  { icon: 'bi-file-earmark-excel-fill', color: '#16a34a', label: 'XLS'  },
    ppt:  { icon: 'bi-file-earmark-ppt-fill',   color: '#ea580c', label: 'PPT'  },
    zip:  { icon: 'bi-file-earmark-zip-fill',   color: '#6b7280', label: 'ZIP'  },
    file: { icon: 'bi-file-earmark-fill',       color: '#6b7280', label: 'FILE' }
};

function updateMediaPreview(userId) {
    var preview = document.getElementById('mediaPreview-' + userId);
    if (!preview) return;
    preview.innerHTML = '';

    if (!mediaFiles[userId] || mediaFiles[userId].length === 0) {
        preview.style.display = 'none';
        return;
    }
    preview.style.display = 'flex';

    mediaFiles[userId].forEach((file, idx) => {
        var kind = getFileKind(file);
        var thumb = document.createElement('div');
        thumb.title = file.name;
        thumb.style.cssText = 'position:relative;width:56px;height:56px;border-radius:8px;overflow:hidden;flex-shrink:0;background:#e5e7eb;';

        if (kind === 'image') {
            var img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            img.style.cssText = 'width:100%;height:100%;object-fit:cover;';
            thumb.appendChild(img);
        } else if (kind === 'video') {
            var vid = document.createElement('video');
            vid.muted = true;
            vid.src = URL.createObjectURL(file);
            vid.style.cssText = 'width:100%;height:100%;object-fit:cover;';
            thumb.appendChild(vid);
            var playIcon = document.createElement('div');
            playIcon.style.cssText = 'position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:20px;height:20px;background:rgba(0,0,0,.55);border-radius:50%;display:flex;align-items:center;justify-content:center;pointer-events:none;';
            playIcon.innerHTML = '<i class="bi bi-play-fill" style="color:#fff;font-size:11px;"></i>';
            thumb.appendChild(playIcon);
        } else {
            var info = FILE_KIND_ICON[kind] || FILE_KIND_ICON.file;
            var ext = file.name.split('.').pop().toUpperCase();
            thumb.style.background = '#f3f4f6';
            thumb.innerHTML = '<div style="display:flex;flex-direction:column;align-items:center;justify-content:center;width:100%;height:100%;gap:2px;">'
                + '<i class="bi ' + info.icon + '" style="font-size:20px;color:' + info.color + ';"></i>'
                + '<span style="font-size:8px;font-weight:700;color:' + info.color + ';">' + ext + '</span>'
                + '</div>';
        }

        var xBtn = document.createElement('button');
        xBtn.type = 'button';
        xBtn.innerHTML = '✕';
        xBtn.style.cssText = 'position:absolute;top:1px;right:1px;width:16px;height:16px;border-radius:50%;background:rgba(0,0,0,.65);color:#fff;border:none;font-size:9px;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:0;';
        xBtn.onclick = (function (i) {
            return function (e) {
                e.preventDefault();
                e.stopPropagation();
                removeMedia(userId, i);
            };
        })(idx);
        thumb.appendChild(xBtn);

        preview.appendChild(thumb);
    });
}

function removeMedia(userId, idx) {
    if (mediaFiles[userId]) {
        mediaFiles[userId].splice(idx, 1);
        updateMediaPreview(userId);
    }
}

function sendMessageWithMedia(userId) {
    if (editState[userId]) { submitEditMessage(userId); return; }
    var input = document.getElementById('msgInput-' + userId);
    var text = input.value.trim();
    var files = mediaFiles[userId] || [];

    if (!text && files.length === 0) {
        alert('Write a message or select media');
        return;
    }

    var formData = new FormData();
    formData.append('recipient_id', userId);
    formData.append('message', text);
    files.forEach(f => formData.append('media[]', f));
    if (replyToState[userId]) formData.append('reply_to_id', replyToState[userId]);

    fetch('/message/send', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: formData
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            input.value = '';
            input.style.height = 'auto';
            mediaFiles[userId] = [];
            updateMediaPreview(userId);
            clearReplyTo(userId);
            setTimeout(() => fetchMessages(userId), 1000);
        } else alert('Error: ' + (d.error || 'Send failed'));
    })
    .catch(err => alert('Network error'));
}

// ============================================================
// SECTION 21B: MESSENGER DROPDOWN (navbar icon + conversation list)
// ============================================================

var _messengerBadgeTimer = null;
var _messengerOpen = false;

function toggleMessengerDropdown() {
    _messengerOpen = !_messengerOpen;
    var panel = document.getElementById('messengerPanel');
    if (_messengerOpen) {
        panel.style.display = 'block';
        fetchConversationList();
        startMessengerBadgePolling();
    } else {
        panel.style.display = 'none';
        stopMessengerBadgePolling();
    }
}

function fetchConversationList(search = '') {
    fetch('/message/conversations' + (search ? '?q=' + encodeURIComponent(search) : ''), {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(d => {
        if (d.conversations) {
            _messengerConvCache = d.conversations;
            var list = document.getElementById('conversationList');
            if (d.conversations.length === 0) {
                list.innerHTML = '<div style="text-align:center;color:#9ca3af;padding:20px;">No conversations yet</div>';
                return;
            }

            list.innerHTML = d.conversations.map(conv => {
                var avatar = conv.avatar 
                    ? '<img src="' + conv.avatar + '">'
                    : conv.name.charAt(0).toUpperCase();
                var badge = conv.unread > 0 
                    ? '<div class="msg-conv-badge">' + (conv.unread > 9 ? '9+' : conv.unread) + '</div>'
                    : '';

                return '<div class="msg-conv-item" onclick="openConversationChat(' + conv.user_id + ', \'' + escHtml(conv.name) + '\', \'' + (conv.avatar || '') + '\')">'
                    + '<div class="msg-conv-avatar">' + avatar + '</div>'
                    + '<div class="msg-conv-info">'
                    + '<div class="msg-conv-name">' + escHtml(conv.name) + '</div>'
                    + '<div class="msg-conv-last">' + (conv.last_message || 'No messages') + '</div>'
                    + '</div>'
                    + badge
                    + '<div class="msg-conv-time">' + (conv.last_at || '') + '</div>'
                    + '</div>';
            }).join('');
        }
    })
    .catch(err => console.error('Conversation list error:', err));
}

function updateMessengerBadge() {
    fetch('/message/unread-count', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(d => {
        var count = d.count || 0;
        var badge = document.getElementById('messengerBadge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count > 9 ? '9+' : count;
                badge.style.display = 'block';
            } else {
                badge.style.display = 'none';
            }
        }
        if (_badgeCountKnown !== null && count > _badgeCountKnown) {
            playNotifSound();
        }
        _badgeCountKnown = count;
    })
    .catch(() => {});
}

function startMessengerBadgePolling() {
    if (_messengerBadgeTimer) clearInterval(_messengerBadgeTimer);
    updateMessengerBadge();  // immediately
    _messengerBadgeTimer = setInterval(updateMessengerBadge, 10000);  // every 10s
}

function stopMessengerBadgePolling() {
    if (_messengerBadgeTimer) clearInterval(_messengerBadgeTimer);
    _messengerBadgeTimer = null;
}

// Open conversation in chat box (or focus if already open)
function openConversationChat(userId, name, avatar) {
    var box = openChatBoxes[userId];
    if (box) {
        unminimizeChatBox(userId);
        document.getElementById('messengerPanel').style.display = 'none';
        _messengerOpen = false;
        return;
    }
    var conv = (_messengerConvCache || []).find(c => c.user_id == userId);
    var isOnline = conv && conv.is_online ? '1' : '0';
    var lastSeen = conv ? conv.last_seen_text : '';
    var hash = conv ? conv.hash : userId;
    openChatBox(userId, name, avatar || '', lastSeen, isOnline, hash);
    document.getElementById('messengerPanel').style.display = 'none';
    _messengerOpen = false;
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    var btn = document.getElementById('messengerBtn');
    var panel = document.getElementById('messengerPanel');
    if (btn && panel && !btn.contains(e.target) && !panel.contains(e.target)) {
        if (_messengerOpen) {
            toggleMessengerDropdown();
        }
    }
});

// সাউন্ড — মেসেঞ্জার আইকন + মিনিমাইজড চ্যাটবক্স দুটোর জন্যই একটাই
function playNotifSound() {
    var now = Date.now();
    if (now - _lastSoundAt < 1500) return; // ডাবল সাউন্ড আটকাও
    _lastSoundAt = now;
    try {
        if (!_audioCtx) _audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        if (_audioCtx.state === 'suspended') _audioCtx.resume();
        var osc  = _audioCtx.createOscillator();
        var gain = _audioCtx.createGain();
        osc.type = 'sine';
        osc.frequency.setValueAtTime(880, _audioCtx.currentTime);
        osc.frequency.setValueAtTime(660, _audioCtx.currentTime + 0.08);
        gain.gain.setValueAtTime(0.18, _audioCtx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, _audioCtx.currentTime + 0.35);
        osc.connect(gain);
        gain.connect(_audioCtx.destination);
        osc.start();
        osc.stop(_audioCtx.currentTime + 0.35);
    } catch (e) {}
}

// মিনিমাইজড চ্যাটবক্সের unread বাড়লো কিনা চেক (read মার্ক করে না — শুধু list করে)
function pollMinimizedAndBadge() {
    updateMessengerBadge();

    var minimizedIds = Object.keys(openChatBoxes).filter(function (uid) {
        return openChatBoxes[uid].classList.contains('minimized');
    });
    if (!minimizedIds.length) return;

    fetch('/message/conversations', {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        if (!d.conversations) return;
        d.conversations.forEach(function (conv) {
            var uid = String(conv.user_id);
            if (minimizedIds.indexOf(uid) === -1) return;
            var prev = (_chatUnreadKnown[uid] !== undefined) ? _chatUnreadKnown[uid] : conv.unread;
            if (conv.unread > prev) {
                var box = openChatBoxes[uid];
                if (box) box.classList.add('bb-chat-highlight');
                playNotifSound();
            }
            _chatUnreadKnown[uid] = conv.unread;
        });
    })
    .catch(function () {});
}

if (_pollMinimizedTimer) clearInterval(_pollMinimizedTimer);
_pollMinimizedTimer = setInterval(pollMinimizedAndBadge, 6000);
setTimeout(pollMinimizedAndBadge, 2000); // baseline নীরবে সেট করার জন্য

function escHtml(s) {
    return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
}


function renderMsgMedia(media) {
    if (!media || !media.length) return '';

    var visualItems = media.filter(function (f) { return f.type === 'image' || f.type === 'video'; });
    var fileItems    = media.filter(function (f) { return f.type !== 'image' && f.type !== 'video'; });

    var html = '';

    if (visualItems.length) {
        var lbData = JSON.stringify(visualItems.map(function (f) {
            return { type: f.type, url: f.url };
        })).replace(/"/g, '&quot;');

        var total = visualItems.length;
        var showCount = Math.min(total, 4);
        var showMore = total > 4;

        html += '<div class="bb-msg-media-grid bb-msg-media-grid-' + showCount + '">';
        for (var i = 0; i < showCount; i++) {
            var f = visualItems[i];
            var isLast = showMore && i === 3;
            html += '<div class="bb-msg-media-cell" onclick="openLightbox(\'' + lbData + '\',' + i + ')">';
            if (f.type === 'image') {
                html += '<img src="' + f.url + '" loading="lazy">';
            } else {
                html += '<video src="' + f.url + '" muted preload="metadata"></video>'
                      + '<div class="bb-msg-media-play"><i class="bi bi-play-fill"></i></div>';
            }
            if (isLast) {
                html += '<div class="bb-msg-media-more">+' + (total - 4) + '</div>';
            }
            html += '</div>';
        }
        html += '</div>';
    }

    if (fileItems.length) {
        html += '<div style="display:flex;flex-direction:column;gap:4px;margin-bottom:4px;">';
        fileItems.forEach(function (f) {
            var ext = (f.name.split('.').pop() || 'FILE').toUpperCase();
            var kindIcon = {
                PDF: ['bi-file-earmark-pdf-fill', '#dc2626'], DOC: ['bi-file-earmark-word-fill', '#2563eb'], DOCX: ['bi-file-earmark-word-fill', '#2563eb'],
                XLS: ['bi-file-earmark-excel-fill', '#16a34a'], XLSX: ['bi-file-earmark-excel-fill', '#16a34a'],
                PPT: ['bi-file-earmark-ppt-fill', '#ea580c'], PPTX: ['bi-file-earmark-ppt-fill', '#ea580c'],
                ZIP: ['bi-file-earmark-zip-fill', '#6b7280'], RAR: ['bi-file-earmark-zip-fill', '#6b7280']
            }[ext] || ['bi-file-earmark-fill', '#6b7280'];
            html += '<a href="' + f.url + '" target="_blank" download style="display:flex;align-items:center;gap:8px;background:rgba(255,255,255,.85);color:#1e1f24;padding:8px 10px;border-radius:8px;text-decoration:none;min-width:170px;">'
                  + '<i class="bi ' + kindIcon[0] + '" style="font-size:22px;color:' + kindIcon[1] + ';flex-shrink:0;"></i>'
                  + '<div style="min-width:0;"><div style="font-size:11.5px;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:130px;">' + escHtml(f.name) + '</div>'
                  + '<div style="font-size:10px;color:#6b7280;">' + ext + ' · ' + formatFileSize(f.size) + '</div></div>'
                  + '</a>';
        });
        html += '</div>';
    }

    return html;
}


function formatFileSize(bytes) {
    if (!bytes) return '';
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return Math.round(bytes / 1024) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}

// ============================================================
// SECTION 22: JOB POST MODAL (alumni only)
// ============================================================
@if($canPostJobs ?? false)
var jobModalObj = null;
document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('postJobModal');
    if (el) jobModalObj = bootstrap.Modal.getOrCreateInstance(el);
});

function openPostJobModal() {
    var form = document.getElementById('postJobForm');
    form.reset();
    document.getElementById('job_id').value           = '';
    document.getElementById('postJobModalTitle').innerHTML = '<i class="bi bi-briefcase-fill text-primary me-1"></i> Post A Job';
    document.getElementById('jobSubmitBtn').innerHTML  = '<i class="bi bi-send-fill me-1"></i> Post Job';
    jobModalObj?.show();
}

function editJobById(id) {
    fetch('/jobs/' + id + '/data', { headers: { 'Accept': 'application/json' } })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        if (!d.success) { Swal.fire({ icon: 'error', title: 'Could not load job' }); return; }
        var job  = d.job;
        var form = document.getElementById('postJobForm');
        form.reset();
        document.getElementById('job_id').value          = job.id;
        document.getElementById('job_title').value        = job.title || '';
        document.getElementById('job_company').value      = job.company || '';
        document.getElementById('job_location').value     = job.location || '';
        document.getElementById('job_type').value         = job.job_type || 'Full-time';
        document.getElementById('job_experience').value   = job.experience || '';
        document.getElementById('job_salary').value       = job.salary || '';
        document.getElementById('job_category').value     = job.category || '';
        document.getElementById('job_deadline').value     = job.deadline || '';
        document.getElementById('job_description').value  = job.description || '';
        document.getElementById('job_requirements').value = job.requirements || '';
        document.getElementById('job_skills').value       = job.skills || '';
        document.getElementById('job_apply_type').value   = job.apply_type || 'link';
        document.getElementById('job_apply_value').value  = job.apply_value || '';
        document.getElementById('postJobModalTitle').innerHTML = '<i class="bi bi-pencil-square text-primary me-1"></i> Edit Job';
        document.getElementById('jobSubmitBtn').innerHTML  = '<i class="bi bi-check2 me-1"></i> Update Job';
        jobModalObj?.show();
    })
    .catch(function () { Swal.fire({ icon: 'error', title: 'Network error' }); });
}

(function () {
    var form = document.getElementById('postJobForm');
    if (!form) return;
    form.addEventListener('submit', function (ev) {
        ev.preventDefault();
        var btn    = document.getElementById('jobSubmitBtn');
        var isEdit = !!document.getElementById('job_id').value;
        btn.disabled = true;
        var orig = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>' + (isEdit ? 'Updating...' : 'Posting...');

        fetch('{{ route("jobs.store") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' },
            body: new FormData(form)
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            btn.disabled  = false;
            btn.innerHTML = orig;
            if (!d.success) {
                var msg = d.message || 'Could not save job.';
                if (d.errors) msg = Object.values(d.errors).flat().join('\n');
                Swal.fire({ icon: 'error', title: 'Failed', text: msg });
                return;
            }
            jobModalObj?.hide();
            form.reset();
            document.getElementById('job_id').value = '';
            Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2200, timerProgressBar: true })
                .fire({ icon: 'success', title: isEdit ? 'Job updated!' : 'Job posted successfully!' });
            if (d.html) {
                if (isEdit) {
                    var old = document.getElementById('jobCard-' + d.job_id);
                    if (old) old.outerHTML = d.html;
                } else {
                    var fc = document.getElementById('postsFeedContainer');
                    if (fc) { fc.insertAdjacentHTML('afterbegin', d.html); document.getElementById('emptyFeedState')?.remove(); }
                }
            }
        })
        .catch(function () { btn.disabled = false; btn.innerHTML = orig; Swal.fire({ icon: 'error', title: 'Network error' }); });
    });
})();
@endif


// ============================================================
// SECTION 23: EMOJI PICKER
// ============================================================
(function () {
    var popover = document.getElementById('bbEmojiPopover');
    var picker  = popover.querySelector('emoji-picker');
    var currentTarget = null;

    picker.addEventListener('emoji-click', function (e) {
        var emoji = e.detail.unicode;
        if (!currentTarget) return;
        var el    = currentTarget;
        var start = el.selectionStart != null ? el.selectionStart : el.value.length;
        var end   = el.selectionEnd   != null ? el.selectionEnd   : el.value.length;
        el.value  = el.value.slice(0, start) + emoji + el.value.slice(end);
        var pos   = start + emoji.length;
        el.focus();
        try { el.setSelectionRange(pos, pos); } catch (err) {}
        el.dispatchEvent(new Event('input', { bubbles: true }));
    });

    document.addEventListener('click', function (ev) {
        var btn = ev.target.closest('.bb-emoji-btn');
        if (btn) {
            ev.preventDefault();
            var target = document.querySelector(btn.getAttribute('data-target'));
            if (!target) return;
            if (popover.style.display === 'block' && currentTarget === target) {
                popover.style.display = 'none'; currentTarget = null; return;
            }
            currentTarget = target;
            var r    = btn.getBoundingClientRect();
            popover.style.display = 'block';
            var top  = r.bottom + window.scrollY + 6;
            if (r.bottom + 350 > window.innerHeight) top = r.top + window.scrollY - 350 - 6;
            var left = r.left + window.scrollX - 150;
            if (left < 8) left = 8;
            if (left + 320 > window.innerWidth) left = window.innerWidth - 328;
            popover.style.top  = top  + 'px';
            popover.style.left = left + 'px';
            return;
        }
        if (popover.style.display === 'block' && !popover.contains(ev.target)) {
            popover.style.display = 'none'; currentTarget = null;
        }
    });
})();


// ============================================================
// SECTION 24: PRIVACY HELPERS
// ============================================================
function setPrivacy(value, iconClass, label) {
    document.getElementById('privacyInput').value = value;
    document.getElementById('privacyLabel').textContent = label;
    var btn = document.getElementById('privacyBtn');
    if (btn) btn.querySelector('i').className = 'bi ' + iconClass + ' me-1';
}

function setEditPrivacy(value, iconClass, label) {
    document.getElementById('editPrivacyInput').value = value;
    if (label) document.getElementById('editPrivacyLabel').textContent = label;
    var btn = document.getElementById('editPrivacyBtn');
    if (btn && iconClass) btn.querySelector('i').className = 'bi ' + iconClass + ' me-1';
}


</script>
{{-- UPDATE MAIN JS DATA END --}}

@include('partials.mobile-nav')
@include('partials.chat-box-modal')

</body>
</html>