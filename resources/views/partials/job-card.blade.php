@php
    use Illuminate\Support\Facades\Auth;
    $hid = $job->getRouteKey();   // hashid — URL, DOM id, JS arg সব এতেই
    $expired = $job->is_expired;
    $expiringSoon = $job->is_expiring_soon;
    $typeColors = [
        'Internship' => ['#fff7ed', '#ea580c', 'bi-mortarboard'],
        'Part-time'  => ['#eff6ff', '#2563eb', 'bi-clock-history'],
        'Remote'     => ['#f0fdf4', '#16a34a', 'bi-laptop'],
        'Full-time'  => ['#eef2ff', '#4f46e5', 'bi-briefcase'],
        'Contract'   => ['#fdf4ff', '#a21caf', 'bi-file-earmark-text'],
        'Freelance'  => ['#fefce8', '#ca8a04', 'bi-person-workspace'],
    ];
    $tc = $typeColors[$job->job_type] ?? ['#eef2ff', '#4f46e5', 'bi-briefcase'];
    $isSaved = isset($job->is_saved_by_me) ? ($job->is_saved_by_me > 0) : false;
    $hasApplied = isset($appliedJobIds) && in_array($job->id, $appliedJobIds); // server-side raw — ঠিক আছে
    $isOwner = $job->user_id === Auth::id();   // নিজের job এ apply নয় — শুধু View Details
@endphp

<div class="bb-jobcard" id="jobCard-{{ $hid }}">
    <div class="bb-jobcard-top">
        <div class="bb-jobcard-logo" style="background:{{ $tc[0] }};color:{{ $tc[1] }};">
            {{ strtoupper(substr($job->company, 0, 1)) }}
        </div>
        <div class="bb-jobcard-headinfo">
            <a href="{{ route('jobs.show', $job) }}" class="bb-jobcard-title">{{ $job->title }}</a>
            <p class="bb-jobcard-company">{{ $job->company }}@if($job->location) · {{ $job->location }}@endif</p>
            <p class="bb-jobcard-posted"><i class="bi bi-clock"></i> Posted at {{ $job->created_at->format('d M, g:i A') }}</p>
        </div>
        <div class="d-flex align-items-start gap-1">
            <button class="bb-job-save-btn {{ $isSaved ? 'saved' : '' }}" id="jobSaveBtn-{{ $hid }}"
                    onclick="toggleJobSave('{{ $hid }}')" title="{{ $isSaved ? 'Saved' : 'Save job' }}">
                <i class="bi {{ $isSaved ? 'bi-bookmark-fill' : 'bi-bookmark' }}"></i>
            </button>
            @if($isOwner)
                <div class="dropdown">
                    <button class="bb-jobcard-more" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm p-1" style="min-width:110px;">
                        <li><a class="dropdown-item py-1 fs-7" href="javascript:void(0)" onclick="editJobById('{{ $hid }}')"><i class="bi bi-pencil me-1"></i> Edit</a></li>
                        <li><a class="dropdown-item py-1 fs-7 text-danger" href="javascript:void(0)" onclick="deleteJob('{{ $hid }}')"><i class="bi bi-trash me-1"></i> Delete</a></li>
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div class="bb-jobcard-meta">
        <span class="bb-jobcard-tag" style="background:{{ $tc[0] }};color:{{ $tc[1] }};"><i class="bi {{ $tc[2] }}"></i> {{ $job->job_type }}</span>
        @if($job->salary)<span class="bb-jobcard-pill"><i class="bi bi-cash-stack"></i> {{ $job->salary }}</span>@endif
        @if($job->experience)<span class="bb-jobcard-pill"><i class="bi bi-bar-chart"></i> {{ $job->experience }}</span>@endif
        @if($job->deadline)<span class="bb-jobcard-pill"><i class="bi bi-calendar-event"></i> {{ $job->deadline->format('d M Y') }}</span>@endif
    </div>

    @if($isOwner)
        <a href="{{ route('jobs.show', $job) }}" class="bb-jobcard-btn" style="background:var(--bb-primary-soft);color:var(--bb-primary);">
            <i class="bi bi-eye me-1"></i> View Details
        </a>
    @elseif($expired)
        <a href="{{ route('jobs.show', $job) }}" class="bb-jobcard-btn" style="background:var(--bb-bg);color:#6b7280;">
            <i class="bi bi-eye me-1"></i> See Details
        </a>
    @elseif($hasApplied)
        <a href="{{ route('jobs.show', $job) }}" class="bb-jobcard-btn" style="background:#dcfce7;color:#16a34a;">
            <i class="bi bi-check-circle-fill me-1"></i> Already Applied · View
        </a>
    @else
        <a href="{{ route('jobs.show', $job) }}" class="bb-jobcard-btn">
            <i class="bi bi-box-arrow-up-right me-1"></i> View Details & Apply
        </a>
    @endif

    @if($expired)
        <div class="bb-jobcard-foot bb-foot-expired"><i class="bi bi-x-circle"></i> Deadline over</div>
    @elseif($expiringSoon)
        <div class="bb-jobcard-foot bb-foot-expiring"><i class="bi bi-alarm"></i> Expiring soon — apply before {{ $job->deadline->format('d M Y') }}</div>
    @endif
</div>