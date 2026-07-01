@if($jobs->count())
    <div class="ja-grid">
        @foreach($jobs as $job)
            @php
                $jt = strtolower($job->job_type);
                $logoColor = str_contains($jt,'intern') ? 'background:#fff7ed;color:#ea580c;'
                           : (str_contains($jt,'part') ? 'background:#eff6ff;color:#2563eb;' : '');
                $isOwner   = $job->user_id === \Illuminate\Support\Facades\Auth::id();
                $appCount  = $job->applications_count ?? 0;
            @endphp
            <div class="ja-card">
                <div class="ja-top">
                    <div class="ja-logo" style="{{ $logoColor }}">{{ strtoupper(substr($job->company,0,1)) }}</div>
                    <div class="flex-grow-1" style="min-width:0;">
                        <a href="{{ route('jobs.show', $job) }}" class="ja-title">{{ $job->title }}</a>
                        {{-- applicant সংখ্যা: owner হলে clickable (list দেখবে), নাহলে শুধু সংখ্যা --}}
                        @if($isOwner)
                            <a href="{{ route('jobs.applicants', $job) }}" class="ja-applicants ja-applicants-link"
                               title="View who applied">
                                <i class="bi bi-people-fill"></i> {{ $appCount }} {{ Str::plural('applicant', $appCount) }}
                            </a>
                        @else
                            <span class="ja-applicants ja-applicants-muted" title="Total applicants">
                                <i class="bi bi-people"></i> {{ $appCount }} {{ Str::plural('applicant', $appCount) }}
                            </span>
                        @endif
                        <p class="ja-company">{{ $job->company }}@if($job->location) · {{ $job->location }}@endif</p>
                    </div>
                </div>
                <div class="ja-meta">
                    <span class="ja-pill"><i class="bi bi-briefcase"></i> {{ $job->job_type }}</span>
                    @if($job->salary)<span class="ja-pill"><i class="bi bi-cash-stack"></i> {{ $job->salary }}</span>@endif
                    @if($job->is_expired)
                        <span class="ja-pill ja-expired"><i class="bi bi-x-circle"></i> Deadline over</span>
                    @elseif($job->is_expiring_soon)
                        <span class="ja-pill ja-expiring"><i class="bi bi-alarm"></i> Expiring soon</span>
                    @elseif($job->deadline)
                        <span class="ja-pill"><i class="bi bi-calendar-event"></i> {{ $job->deadline->format('d M') }}</span>
                    @endif
                </div>
                <div class="ja-foot">
                    @if($isOwner)
                        <a href="{{ route('jobs.show', $job) }}" class="ja-view" style="background:var(--bb-primary-soft);color:var(--bb-primary);">View Details</a>
                    @else
                        <a href="{{ route('jobs.show', $job) }}" class="ja-view">View Details & Apply</a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="ja-empty">
        <i class="bi bi-briefcase fs-1 d-block mb-2"></i>
        @if(!empty($search) || !empty($type))
            <h5 class="fw-bold">No jobs match your search</h5>
            <p class="mb-2">Try different keywords or filters.</p>
            <a href="javascript:void(0)" onclick="jaClearAll()" class="ja-view" style="display:inline-block;padding:9px 24px;">Clear filters</a>
        @else
            <h5 class="fw-bold">No jobs available right now</h5>
            <p class="mb-0">Check back soon — alumni post new opportunities regularly.</p>
        @endif
    </div>
@endif