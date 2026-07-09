@php
    $isJob = $report->type === 'job';
    $uStatus = $report->targetUserStatus ?? 'active';
    $relatedCount = ($groupedByUser[$report->targetUser?->id] ?? collect())->count();
@endphp

<tr id="report-row-{{ $report->id }}" class="{{ !$report->admin_seen ? 'table-warning' : '' }}">
    <td class="fw-bold text-secondary">
        @if(!$report->admin_seen)
            <span class="badge bg-danger" style="font-size:0.55rem;">NEW</span>
        @else •
        @endif
    </td>

    @if(!$isJob)
    {{-- Type column — শুধু content table এ --}}
    <td>
        @php $typeColor = match($report->type) { 'post'=>'primary','user'=>'danger',default=>'secondary' }; @endphp
        <span class="badge bg-{{ $typeColor }}-subtle text-{{ $typeColor }} border border-{{ $typeColor }}-subtle"
              style="font-size:0.65rem;font-weight:700;text-transform:uppercase;">{{ $report->type }}</span>
        <div class="text-muted mt-1" style="font-size:0.62rem;">{{ $report->created_at->diffForHumans() }}</div>
    </td>
    @endif

    <td>
        <div class="fw-semibold text-dark" style="font-size:0.78rem;">{{ $report->targetTitle }}</div>
        @if($report->targetLink)
            <a href="{{ $report->targetLink }}" target="_blank" rel="noopener noreferrer" class="text-primary" style="font-size:0.68rem;">
                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>
                {{ $isJob ? 'View Job' : ($report->type === 'post' ? 'View Post' : 'Visit Profile') }}
            </a>
        @else
            <span class="text-muted" style="font-size:0.68rem;">[Content removed]</span>
        @endif
    </td>

    <td>
        @if($report->targetUser)
        <div class="fw-semibold" style="font-size:0.78rem;">{{ $report->targetUser->name }}</div>
        <div class="text-muted" style="font-size:0.68rem;">{{ $report->targetUser->email }}</div>
        @if(!$isJob)
        <div class="mt-1 d-flex flex-wrap gap-1">
            @if($uStatus === 'suspended_temp')
                <span class="status-badge temp-suspended user-status-badge-{{ $report->targetUser->id }}"><i class="fa-solid fa-clock"></i> 7d Suspended</span>
            @elseif($uStatus === 'suspended_perm')
                <span class="status-badge perm-suspended user-status-badge-{{ $report->targetUser->id }}"><i class="fa-solid fa-ban"></i> Permanent Banned</span>
            @else
                <span class="status-badge active user-status-badge-{{ $report->targetUser->id }}"><i class="fa-solid fa-circle" style="font-size:5px;"></i> Active</span>
            @endif
        </div>
        @endif
        @if($relatedCount > 1)
            <span class="badge bg-danger-subtle text-danger border mt-1 d-inline-block" style="font-size:0.62rem;">
                <i class="fa-solid fa-layer-group"></i> {{ $relatedCount }} active reports
            </span>
        @endif
        @else
        <span class="text-muted" style="font-size:0.72rem;">User deleted</span>
        @endif
    </td>

    <td>
        <span class="text-muted" style="font-size:0.72rem;">{{ ucfirst($report->reason) }}</span>
        @if($report->details)
        <div class="text-muted" style="font-size:0.65rem;">{{ \Illuminate\Support\Str::limit($report->details, 30) }}</div>
        @endif
    </td>

    <td>
        @if($report->appeal_status === 'pending')
            <div class="mb-2 p-2" style="background:#fffbeb;border:1px solid #fde68a;border-radius:8px;">
                <div class="fw-bold text-warning" style="font-size:0.65rem;text-transform:uppercase;"><i class="fa-solid fa-megaphone"></i> Appealed</div>
                <div class="text-dark mt-1" style="font-size:0.7rem;">{{ \Illuminate\Support\Str::limit($report->appeal_message, 60) }}</div>
            </div>
            <button onclick="markReviewed({{ $report->id }})" class="btn-action-pill activate w-100 justify-content-center">
                <i class="fa-solid fa-check-double"></i> Accept Appeal
            </button>
        @else
            <span class="text-muted" style="font-size:0.7rem;">— No appeal —</span>
        @endif
    </td>

    <td style="text-align:right;">
        <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" style="font-size:0.75rem;border-radius:8px;">
                <i class="fa-solid fa-gavel me-1"></i> Take Action
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="font-size:0.8rem;">
                @if(!$report->admin_seen)
                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); markAsRead({{ $report->id }}, this)"><i class="fa-solid fa-eye text-primary me-2"></i>Mark as Read</a></li>
                    <li><hr class="dropdown-divider"></li>
                @endif

                @if($report->targetUser)
                <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); adminAction('warn', {{ $report->id }})"><i class="fa-solid fa-triangle-exclamation text-warning me-2"></i>Warn User</a></li>
                @endif

                @if($report->type === 'user' && $report->targetUser)
                    <li><span id="suspend-btn-{{ $report->targetUser->id }}" class="d-block">
                        @if(in_array($uStatus, ['suspended_temp','suspended_perm']))
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); suspendFromReport({{ $report->targetUser->id }}, 'active')"><i class="fa-solid fa-circle-check text-success me-2"></i>Remove Suspension</a>
                        @else
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); suspendFromReport({{ $report->targetUser->id }}, 'temp')"><i class="fa-solid fa-clock text-warning me-2"></i>Suspend 7 Days</a>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); suspendFromReport({{ $report->targetUser->id }}, 'perm')"><i class="fa-solid fa-ban text-danger me-2"></i>Permanent Ban</a>
                        @endif
                    </span></li>
                @elseif($report->type === 'post')
                    <li><a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); adminAction('delete-content', {{ $report->id }})"><i class="fa-regular fa-trash-can me-2"></i>Delete Post</a></li>
                @elseif($isJob)
                    <li>
                        @if($report->targetUser)
                        <span id="suspend-btn-{{ $report->targetUser->id }}" class="d-block">
                            @if(in_array($uStatus, ['suspended_temp','suspended_perm']))
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); suspendFromReport({{ $report->targetUser->id }}, 'active')"><i class="fa-solid fa-circle-check text-success me-2"></i>Remove Suspension</a>
                            @else
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); suspendFromReport({{ $report->targetUser->id }}, 'temp')"><i class="fa-solid fa-clock text-warning me-2"></i>Suspend 7 Days</a>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); suspendFromReport({{ $report->targetUser->id }}, 'perm')"><i class="fa-solid fa-ban text-danger me-2"></i>Permanent Ban</a>
                            @endif
                        </span>
                        @endif
                    </li>
                    <li><a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); adminAction('delete-content', {{ $report->id }})"><i class="fa-regular fa-trash-can me-2"></i>Delete Job</a></li>
                @endif

                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); adminAction('dismiss', {{ $report->id }})"><i class="fa-solid fa-ban text-muted me-2"></i>Dismiss (No Violation)</a></li>
                <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); completeReport({{ $report->id }})"><i class="fa-solid fa-check-double text-success me-2"></i>Mark Resolved</a></li>
            </ul>
        </div>
    </td>
</tr>