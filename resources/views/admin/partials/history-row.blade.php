<tr class="{{ !$r->history_seen ? 'table-info' : '' }}" data-report-id="{{ $r->id }}">
    <td class="fw-bold text-secondary">
        @if(!$r->history_seen)
            <span class="badge bg-primary" style="font-size:0.55rem;">NEW</span>
        @else
            •
        @endif
    </td>
    <td>
        <span class="badge bg-secondary-subtle text-secondary border" style="font-size:0.65rem;font-weight:700;text-transform:uppercase;">{{ $r->type }}</span>
    </td>
    <td style="font-size:0.78rem;">{{ $r->targetTitle }}</td>
    <td style="font-size:0.72rem;color:#64748b;">{{ ucfirst($r->reason) }}</td>
    <td>
        @php
            $outcome = match(true) {
                $r->appeal_status === 'reviewed' => ['active', 'fa-check-double', 'Appeal Approved — Restored'],
                $r->action_taken === 'deleted' => ['perm-suspended', 'fa-trash-can', 'Content Removed'],
                $r->action_taken === 'dismissed_no_violation' => ['active', 'fa-check', 'Dismissed — No Violation'],
                default => ['active', 'fa-check-double', 'Marked Resolved'],
            };
        @endphp
        <span class="status-badge {{ $outcome[0] }}"><i class="fa-solid {{ $outcome[1] }}"></i> {{ $outcome[2] }}</span>
        @if($r->was_warned)
            <div class="text-warning mt-1" style="font-size:0.62rem;"><i class="fa-solid fa-triangle-exclamation"></i> User was also warned</div>
        @endif
    </td>
    <td data-order="{{ $r->updated_at->timestamp }}" style="font-size:0.72rem;color:#64748b;">{{ $r->updated_at->format('d M Y, g:i a') }}</td>

    <td style="text-align:right;">
        @if($r->appeal_status === 'reviewed')
            <span class="badge bg-success d-block mb-1" style="font-size:0.65rem;">Appeal Approved</span>
        @elseif($r->appeal_status === 'ignored')
            <span class="badge bg-secondary d-block mb-1" style="font-size:0.65rem;">Appeal Ignored</span>
        @else
            <span class="text-muted d-block mb-1" style="font-size:0.7rem;">— No appeal —</span>
        @endif
        @if(!$r->history_seen)
            <button type="button" class="btn-action-pill activate" onclick="markAsRead({{ $r->id }}, this)">
                <i class="fa-solid fa-eye"></i> Mark as Read
            </button>
        @else
            <span class="text-muted" style="font-size:0.68rem;"><i class="fa-solid fa-check"></i> Read</span>
        @endif
    </td>

</tr>