@php
    use Illuminate\Support\Facades\Auth;
    $jt = strtolower($job->job_type);
    $logoColor = str_contains($jt,'intern') ? 'background:#fff7ed;color:#ea580c;'
               : (str_contains($jt,'part') ? 'background:#eff6ff;color:#2563eb;'
               : 'background:var(--bb-primary-soft);color:var(--bb-primary);');
    $isOwner = Auth::id() === $job->user_id;
@endphp
<div class="bb-myjob-item" id="myjob-{{ $job->id }}">
    <div class="bb-myjob-logo" style="{{ $logoColor }}">{{ strtoupper(substr($job->company,0,1)) }}</div>
    <div class="bb-myjob-body">
        <a href="{{ route('jobs.show', $job->id) }}" class="bb-myjob-title">{{ $job->title }}</a>
        <p class="bb-myjob-company">{{ $job->company }}@if($job->location) · {{ $job->location }}@endif</p>
        <div class="bb-myjob-meta">
            <span class="bb-myjob-tag">{{ $job->job_type }}</span>
            <span class="bb-myjob-date"><i class="bi bi-clock"></i> Posted {{ $job->created_at->diffForHumans() }}</span>
            @if($job->is_expired)
                <span class="bb-myjob-status bb-st-closed"><i class="bi bi-x-circle"></i> Closed</span>
            @elseif($job->is_expiring_soon)
                <span class="bb-myjob-status bb-st-soon"><i class="bi bi-alarm"></i> Expiring soon</span>
            @else
                <span class="bb-myjob-status bb-st-active"><i class="bi bi-broadcast"></i> Active</span>
            @endif
            @if($job->deadline)
                <span class="bb-myjob-date"><i class="bi bi-calendar-event"></i> Deadline {{ $job->deadline->format('d M Y') }}</span>
            @endif
        </div>
    </div>
    @if($isOwner)
        <div class="bb-timeline-actions">
            <button onclick="editJobById({{ $job->id }})" title="Edit"><i class="bi bi-pencil"></i></button>
            <button onclick="deleteMyJob({{ $job->id }})" title="Delete" class="text-danger"><i class="bi bi-trash3"></i></button>
        </div>
    @endif
</div>