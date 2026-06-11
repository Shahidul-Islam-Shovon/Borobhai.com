@php
    use Illuminate\Support\Facades\Auth;
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
@endphp

<div class="bb-jobcard" id="jobCard-{{ $job->id }}">
    <div class="bb-jobcard-top">
        <div class="bb-jobcard-logo" style="background:{{ $tc[0] }};color:{{ $tc[1] }};">
            {{ strtoupper(substr($job->company, 0, 1)) }}
        </div>
        <div class="bb-jobcard-headinfo">
            <a href="{{ route('jobs.show', $job->id) }}" class="bb-jobcard-title">{{ $job->title }}</a>
            <p class="bb-jobcard-company">{{ $job->company }}@if($job->location) · {{ $job->location }}@endif</p>
            @if($expired)
                <span class="bb-job-expired"><i class="bi bi-x-circle"></i> Deadline over</span>
            @elseif($expiringSoon)
                <span class="bb-job-expiring"><i class="bi bi-alarm"></i> Expiring soon</span>
            @endif
        </div>
        @if($job->user_id === Auth::id())
            <div class="dropdown">
                <button class="bb-jobcard-more" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm p-1" style="min-width:110px;">
                    <li><a class="dropdown-item py-1 fs-7 text-danger" href="javascript:void(0)" onclick="deleteJob({{ $job->id }})"><i class="bi bi-trash me-1"></i> Delete</a></li>
                </ul>
            </div>
        @endif
    </div>

    <div class="bb-jobcard-meta">
        <span class="bb-jobcard-tag" style="background:{{ $tc[0] }};color:{{ $tc[1] }};"><i class="bi {{ $tc[2] }}"></i> {{ $job->job_type }}</span>
        @if($job->salary)<span class="bb-jobcard-pill"><i class="bi bi-cash-stack"></i> {{ $job->salary }}</span>@endif
        @if($job->experience)<span class="bb-jobcard-pill"><i class="bi bi-bar-chart"></i> {{ $job->experience }}</span>@endif
        @if($job->deadline)<span class="bb-jobcard-pill"><i class="bi bi-calendar-event"></i> {{ $job->deadline->format('d M Y') }}</span>@endif
    </div>

    <a href="{{ route('jobs.show', $job->id) }}" class="bb-jobcard-btn">
        <i class="bi bi-box-arrow-up-right me-1"></i> View Details & Apply
    </a>
</div>