<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🎓</text></svg>">
    <title>{{ $job->title }} · {{ $job->company }} — Borobhai.com</title>
    <style>
        :root {
            --bb-primary:#4f46e5; --bb-primary-dark:#4338ca; --bb-primary-soft:#eef2ff;
            --bb-ink:#1e1f24; --bb-muted:#6b7280; --bb-line:#eceef1; --bb-bg:#f3f4f8; --bb-card:#fff;
        }
        * { font-family:'Inter',-apple-system,sans-serif; }
        body { background:var(--bb-bg); color:var(--bb-ink); margin:0; }
        .jp-nav { background:#fff; box-shadow:0 1px 3px rgba(16,24,40,.06); padding:12px 0; position:sticky; top:0; z-index:100; }
        .jp-brand { font-weight:800; color:var(--bb-primary); font-size:21px; text-decoration:none; letter-spacing:-.5px; }
        .jp-back { color:var(--bb-muted); text-decoration:none; font-weight:600; font-size:14px; display:inline-flex; align-items:center; gap:6px; transition:color .15s; }
        .jp-back:hover { color:var(--bb-primary); }

        .jp-wrap { max-width:880px; margin:24px auto; padding:0 16px; }

        /* Hero */
        .jp-hero { background:#fff; border-radius:20px; box-shadow:0 1px 3px rgba(16,24,40,.06); overflow:hidden; }
        .jp-hero-band { height:90px; background:linear-gradient(120deg,#4f46e5,#7c73f0 60%,#a78bfa); }
        .jp-hero-body { padding:0 28px 26px; margin-top:-38px; }
        .jp-logo {
            width:78px; height:78px; border-radius:18px; background:#fff; box-shadow:0 4px 14px rgba(16,24,40,.12);
            display:flex; align-items:center; justify-content:center; font-size:34px; font-weight:800;
            color:var(--bb-primary); border:3px solid #fff;
        }
        .jp-title { font-size:27px; font-weight:800; letter-spacing:-.6px; margin:16px 0 4px; line-height:1.15; }
        .jp-company { font-size:15.5px; color:var(--bb-muted); font-weight:600; margin:0 0 14px; }
        .jp-company i { color:var(--bb-primary); }

        .jp-meta-row { display:flex; flex-wrap:wrap; gap:8px; }
        .jp-chip {
            display:inline-flex; align-items:center; gap:6px; font-size:13px; font-weight:600;
            padding:7px 13px; border-radius:10px; background:var(--bb-bg); color:#374151;
        }
        .jp-chip i { font-size:13px; color:var(--bb-primary); }
        .jp-chip-type { background:var(--bb-primary-soft); color:var(--bb-primary); }

        /* Expiry banners */
        .jp-banner { margin:16px 0 0; padding:12px 16px; border-radius:12px; font-size:13.5px; font-weight:600; display:flex; align-items:center; gap:8px; }
        .jp-banner-warn { background:#fff7ed; color:#c2410c; border:1px solid #fed7aa; }
        .jp-banner-dead { background:#fef2f2; color:#b91c1c; border:1px solid #fecaca; }

        /* Body sections */
        .jp-section { background:#fff; border-radius:18px; box-shadow:0 1px 3px rgba(16,24,40,.06); padding:24px 28px; margin-top:18px; }
        .jp-sec-title { font-size:17px; font-weight:800; letter-spacing:-.3px; margin:0 0 12px; display:flex; align-items:center; gap:9px; }
        .jp-sec-title i { color:var(--bb-primary); }
        .jp-text { font-size:14.5px; line-height:1.7; color:#374151; white-space:pre-line; margin:0; }
        .jp-list { margin:0; padding:0; list-style:none; }
        .jp-list li { font-size:14.5px; line-height:1.6; color:#374151; padding:7px 0 7px 28px; position:relative; border-bottom:1px solid #f3f4f6; }
        .jp-list li:last-child { border-bottom:none; }
        .jp-list li::before { content:'\F26A'; font-family:'bootstrap-icons'; position:absolute; left:2px; top:7px; color:var(--bb-primary); font-size:13px; }

        .jp-skills { display:flex; flex-wrap:wrap; gap:8px; }
        .jp-skill { font-size:13px; font-weight:600; padding:6px 13px; border-radius:20px; background:var(--bb-primary-soft); color:var(--bb-primary); }

        /* Apply card */
        .jp-apply { position:sticky; bottom:0; }
        .jp-apply-card { background:#fff; border-radius:18px; box-shadow:0 -2px 20px rgba(16,24,40,.08); padding:18px 24px; margin-top:18px; display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; }
        .jp-apply-info { font-size:13px; color:var(--bb-muted); }
        .jp-apply-info strong { color:var(--bb-ink); font-size:15px; display:block; }
        .jp-apply-btn {
            background:linear-gradient(135deg,#4f46e5,#7c73f0); color:#fff; border:none;
            border-radius:12px; padding:13px 30px; font-size:15px; font-weight:700; cursor:pointer;
            text-decoration:none; display:inline-flex; align-items:center; gap:8px; transition:all .15s;
            box-shadow:0 4px 14px rgba(79,70,229,.35);
        }
        .jp-apply-btn:hover { transform:translateY(-1px); box-shadow:0 6px 20px rgba(79,70,229,.45); color:#fff; }
        .jp-apply-btn.disabled { background:#d1d5db; color:#6b7280; box-shadow:none; cursor:not-allowed; pointer-events:auto; }

        .jp-poster { display:flex; align-items:center; gap:10px; margin-top:6px; }
        .jp-poster-av { width:34px; height:34px; border-radius:50%; object-fit:cover; background:linear-gradient(135deg,#4f46e5,#7c73f0); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; }
        .jp-poster-name { font-size:13px; font-weight:600; }
        .jp-poster-name small { color:var(--bb-muted); font-weight:400; display:block; font-size:11.5px; }

        @media (max-width:576px){
            .jp-title { font-size:22px; }
            .jp-section, .jp-hero-body { padding-left:18px; padding-right:18px; }
            .jp-apply-card { flex-direction:column; align-items:stretch; }
            .jp-apply-btn { justify-content:center; }
        }

        /* Apply modal form */
        .bb-job-label { display:block; font-size:12.5px; font-weight:600; color:#374151; margin-bottom:5px; }
        .bb-job-input { width:100%; border:1.5px solid #e4e6eb; border-radius:10px; padding:9px 12px; font-size:13.5px; outline:none; transition:border-color .15s, box-shadow .15s; background:#fff; }
        .bb-job-input:focus { border-color:#4f46e5; box-shadow:0 0 0 3px rgba(79,70,229,.12); }
        textarea.bb-job-input { resize:vertical; }
        .apply-dialog { height:calc(100vh - 3.5rem); }
        .apply-content { max-height:100%; display:flex; flex-direction:column; overflow:hidden; }
        .apply-form { display:flex; flex-direction:column; min-height:0; flex:1 1 auto; overflow:hidden; }
        .apply-body { overflow-y:auto; flex:1 1 auto; min-height:0; }
        .apply-footer { flex:0 0 auto; }
        @media (max-width:576px){ .apply-dialog { height:calc(100vh - 1rem); } }
    </style>
</head>
<body>

<nav class="jp-nav">
    <div class="jp-wrap d-flex align-items-center justify-content-between" style="margin:0 auto;">
        <a href="{{ route('home') }}" class="jp-brand">Borobhai.com</a>
        <a href="{{ route('home') }}" class="jp-back"><i class="bi bi-arrow-left"></i> Back to Feed</a>
    </div>
</nav>

@php
    $expired = $job->is_expired;
    $isOwner = auth()->id() === $job->user_id;
    $expiringSoon = $job->is_expiring_soon;
@endphp

<div class="jp-wrap">

    {{-- HERO --}}
    <div class="jp-hero">
        <div class="jp-hero-band"></div>
        <div class="jp-hero-body">
            <div class="jp-logo">{{ strtoupper(substr($job->company, 0, 1)) }}</div>
            <h1 class="jp-title">{{ $job->title }}</h1>
            <p class="jp-company"><i class="bi bi-building"></i> {{ $job->company }}@if($job->location) &nbsp;·&nbsp; <i class="bi bi-geo-alt"></i> {{ $job->location }}@endif</p>

            <div class="jp-meta-row">
                <span class="jp-chip jp-chip-type"><i class="bi bi-briefcase-fill"></i> {{ $job->job_type }}</span>
                @if($job->salary)<span class="jp-chip"><i class="bi bi-cash-stack"></i> {{ $job->salary }}</span>@endif
                @if($job->experience)<span class="jp-chip"><i class="bi bi-bar-chart-line"></i> {{ $job->experience }}</span>@endif
                @if($job->category)<span class="jp-chip"><i class="bi bi-tag"></i> {{ $job->category }}</span>@endif
                @if($job->deadline)<span class="jp-chip"><i class="bi bi-calendar-event"></i> Apply by {{ $job->deadline->format('d M Y') }}</span>@endif
            </div>

            @if($expired)
                <div class="jp-banner jp-banner-dead"><i class="bi bi-x-circle-fill"></i> The application deadline has passed. This job is no longer accepting applicants.</div>
            @elseif($expiringSoon)
                <div class="jp-banner jp-banner-warn"><i class="bi bi-alarm-fill"></i> Expiring soon — apply before {{ $job->deadline->format('d M Y') }}.</div>
            @endif
        </div>
    </div>

    {{-- DESCRIPTION --}}
    <div class="jp-section">
        <h2 class="jp-sec-title"><i class="bi bi-file-text"></i> Job Description</h2>
        {!! bb_format_text($job->description) !!}
    </div>

    {{-- REQUIREMENTS --}}
    @if($job->requirements)
    <div class="jp-section">
        <h2 class="jp-sec-title"><i class="bi bi-check2-square"></i> Requirements</h2>
        {!! bb_format_text($job->requirements) !!}
    </div>
    @endif

    {{-- SKILLS --}}
    @if(count($job->skills_array))
    <div class="jp-section">
        <h2 class="jp-sec-title"><i class="bi bi-stars"></i> Skills</h2>
        <div class="jp-skills">
            @foreach($job->skills_array as $skill)
                <span class="jp-skill">{{ $skill }}</span>
            @endforeach
        </div>
    </div>
    @endif

    {{-- POSTED BY --}}
    <div class="jp-section">
        <h2 class="jp-sec-title"><i class="bi bi-person-badge"></i> Posted by</h2>
        <div class="jp-poster">
            <div class="jp-poster-av">
                @if($job->user->profile_picture)
                    <img src="{{ asset('storage/'.$job->user->profile_picture) }}" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
                @else
                    {{ strtoupper(substr($job->user->name, 0, 1)) }}
                @endif
            </div>
            <div class="jp-poster-name">
                {{ $job->user->name }}
                <small>Alumni · posted {{ $job->created_at->diffForHumans() }}</small>
            </div>
        </div>
    </div>

    {{-- APPLY --}}
    <div class="jp-apply">
        <div class="jp-apply-card">
            @if($isOwner)
                <div class="jp-apply-info">
                    <strong><i class="bi bi-person-badge"></i> This is your job posting</strong>
                    Manage and review applicants here.
                </div>
                <a href="{{ route('jobs.applicants', $job->id) }}" class="jp-apply-btn"><i class="bi bi-people-fill"></i> View Applicants</a>
            @elseif($expired)
                <div class="jp-apply-info">
                    <strong>Applications closed</strong>
                    The deadline for this position has passed.
                </div>
                <button class="jp-apply-btn disabled" onclick="deadlineWarn()"><i class="bi bi-lock-fill"></i> Applications Closed</button>
            @elseif($hasApplied)
                <div class="jp-apply-info">
                    <strong style="color:#16a34a;"><i class="bi bi-check-circle-fill"></i> Application submitted</strong>
                    You have already applied to this job.
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('jobs.myApplications') }}" class="jp-apply-btn" style="background:#eef2ff;color:var(--bb-primary);box-shadow:none;"><i class="bi bi-clock-history"></i> View in History</a>
                    @if(in_array($myAppStatus, ['pending', 'reviewed']))
                        <button class="jp-apply-btn" style="background:#fef2f2;color:#dc2626;box-shadow:none;" onclick="withdrawApplication({{ $job->id }})"><i class="bi bi-x-circle"></i> Withdraw</button>
                    @endif
                </div>
            @else
                <div class="jp-apply-info">
                    <strong>Ready to apply?</strong>
                    Apply directly on Borobhai, or via the company.
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button class="jp-apply-btn" onclick="openApplyModal()"><i class="bi bi-send-fill"></i> Apply on Borobhai</button>
                    @php
                        $applyHref = $job->apply_type === 'email'
                            ? 'mailto:'.$job->apply_value.'?subject='.urlencode('Application for '.$job->title)
                            : (str_starts_with($job->apply_value, 'http') ? $job->apply_value : 'https://'.$job->apply_value);
                    @endphp
                    <button class="jp-apply-btn" style="background:#fff;color:var(--bb-primary);border:1.5px solid var(--bb-primary);box-shadow:none;"
                            data-job-id="{{ $job->id }}"
                            data-apply-type="{{ $job->apply_type }}"
                            data-apply-href="{{ $applyHref }}"
                            onclick="applyExternalBtn(this)">
                        <i class="bi bi-box-arrow-up-right"></i> Apply via Company
                    </button>
                </div>
            @endif
        </div>
    </div>

    @if(!$isOwner && !$expired && !$hasApplied)
    {{-- ==================== APPLY MODAL ==================== --}}
    <div class="modal fade" id="applyModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered apply-dialog">
            <div class="modal-content border-0 shadow-lg rounded-4 apply-content">
                <div class="modal-header border-bottom">
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Apply for this position</h5>
                        <small class="text-muted">{{ $job->title }} · {{ $job->company }}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="applyForm" class="apply-form" enctype="multipart/form-data">
                    <div class="modal-body p-4 apply-body">
                        <input type="hidden" name="apply_method" value="inapp">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="bb-job-label">Full Name *</label>
                                <input type="text" name="applicant_name" id="ap_name" class="bb-job-input" value="{{ Auth::user()->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="bb-job-label">Email *</label>
                                <input type="email" name="applicant_email" id="ap_email" class="bb-job-input" value="{{ Auth::user()->email }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="bb-job-label">Phone</label>
                                <input type="text" name="phone" id="ap_phone" class="bb-job-input" value="{{ Auth::user()->phone ?? '' }}" placeholder="01XXXXXXXXX">
                            </div>
                            <div class="col-md-6">
                                <label class="bb-job-label">Resume / CV <span class="text-muted" style="font-weight:400;">(PDF/Word, max 5MB)</span></label>
                                <input type="file" name="resume" id="ap_resume" class="bb-job-input" accept=".pdf,.doc,.docx">
                            </div>
                            <div class="col-12">
                                <label class="bb-job-label">Cover Note <span class="text-muted" style="font-weight:400;">(why you're a good fit)</span></label>
                                <textarea name="cover_note" id="ap_cover" class="bb-job-input" rows="4" placeholder="Briefly introduce yourself and explain why you're interested..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer apply-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="jp-apply-btn" id="applySubmitBtn" onclick="submitApply()" style="padding:10px 24px;"><i class="bi bi-send-fill me-1"></i> Submit Application</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function deadlineWarn() {
    const Toast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:3000, timerProgressBar:true });
    Toast.fire({ icon:'warning', title:'Deadline over — applications are closed for this job.' });
}

const APPLY_CSRF = document.querySelector('meta[name="csrf-token"]').content;

// back button (bfcache) থেকে এলে stale state এড়াতে reload
window.addEventListener('pageshow', function (e) {
    if (e.persisted) location.reload();
});
const applyToast = Swal.mixin({ toast:true, position:'top-end', showConfirmButton:false, timer:2400, timerProgressBar:true });
let applyModalObj = null;
document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('applyModal');
    if (el) applyModalObj = bootstrap.Modal.getOrCreateInstance(el);
});

function openApplyModal(){ applyModalObj?.show(); }

// in-app apply submit
function submitApply(){
    const form = document.getElementById('applyForm');
    const btn = document.getElementById('applySubmitBtn');
    const name = document.getElementById('ap_name').value.trim();
    const email = document.getElementById('ap_email').value.trim();
    if (!name || !email) { applyToast.fire({icon:'error', title:'Name and email are required'}); return; }

    btn.disabled = true; const orig = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Submitting...';
    const fd = new FormData(form);

    fetch("{{ route('jobs.apply', $job->id) }}", {
        method:'POST', headers:{'X-CSRF-TOKEN':APPLY_CSRF,'Accept':'application/json'}, body:fd
    })
    .then(r=>r.json())
    .then(d=>{
        btn.disabled=false; btn.innerHTML=orig;
        if(!d.success){
            let msg = d.message || 'Could not apply.';
            if (d.errors) msg = Object.values(d.errors).flat().join('\n');
            Swal.fire({icon:'error', title:'Failed', text:msg});
            return;
        }
        applyModalObj?.hide();
        Swal.fire({
            icon:'success', title:'Application Submitted!',
            text:'Your application has been sent. Track it in your Job History.',
            confirmButtonColor:'#4f46e5'
        }).then(()=>location.reload());
    })
    .catch(()=>{ btn.disabled=false; btn.innerHTML=orig; Swal.fire({icon:'error',title:'Network error'}); });
}

// external apply — track করে তারপর company তে পাঠাও
function applyExternalBtn(btn){
    applyExternal(btn.dataset.jobId, btn.dataset.applyType, btn.dataset.applyHref);
}

function applyExternal(jobId, applyType, applyHref){
    fetch(`/jobs/${jobId}/apply`, {
        method:'POST',
        headers:{'X-CSRF-TOKEN':APPLY_CSRF,'Accept':'application/json','Content-Type':'application/json'},
        body: JSON.stringify({ apply_method:'external' })
    })
    .then(r=>r.json())
    .then(d=>{
        if (d.success || d.message) {
            applyToast.fire({ icon:'success', title:'Tracked in your Job History' });
        }
        // company site/email এ পাঠাও
        if (applyType === 'email') {
            window.location.href = applyHref;   // mailto — একই ট্যাবে mail client খোলে
        } else {
            window.open(applyHref, '_blank', 'noopener,noreferrer');  // link — নতুন ট্যাব
        }
        // status আপডেট দেখাতে reload
        setTimeout(()=>location.reload(), 1200);
    })
    .catch(()=>{
        // track fail করলেও company তে পাঠাও
        if (applyType === 'email') window.location.href = applyHref;
        else window.open(applyHref, '_blank', 'noopener,noreferrer');
    });
}

// withdraw
function withdrawApplication(jobId){
    Swal.fire({
        title:'Withdraw application?', icon:'warning', showCancelButton:true,
        confirmButtonColor:'#ef4444', confirmButtonText:'Withdraw'
    }).then(r=>{
        if(!r.isConfirmed) return;
        fetch(`/jobs/${jobId}/withdraw`, {
            method:'POST', headers:{'X-CSRF-TOKEN':APPLY_CSRF,'Accept':'application/json'}
        })
        .then(r=>r.json())
        .then(d=>{
            if(!d.success) return;
            applyToast.fire({icon:'info', title:'Application withdrawn'});
            setTimeout(()=>location.reload(), 1000);
        });
    });
}
</script>

</body>
</html>