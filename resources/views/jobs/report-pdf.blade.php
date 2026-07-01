<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page { margin: 0; }
    * { font-family: DejaVu Sans, sans-serif; margin:0; padding:0; box-sizing:border-box; }
    body { color:#1e1f24; font-size:12px; line-height:1.4; padding:42px 46px 90px; }

    /* ===== HEADER ===== */
    .header { width:100%; margin-bottom:6px; }
    .header td { vertical-align:top; }
    .brand-name { font-size:26px; font-weight:bold; color:#4f46e5; letter-spacing:-.5px; }
    .brand-sub { font-size:11px; color:#6b7280; margin-top:3px; }
    .brand-tag { display:inline-block; font-size:8.5px; color:#4f46e5; background:#eef2ff;
                 padding:3px 9px; border-radius:10px; margin-top:10px; font-weight:bold;
                 text-transform:uppercase; letter-spacing:.4px; }

    .job-box { text-align:right; }
    .job-title { font-size:17px; font-weight:bold; color:#1e1f24; margin-bottom:8px; word-wrap:break-word; }
    .job-row { font-size:11px; color:#6b7280; margin:4px 0; }
    .job-row b { color:#1e1f24; }

    .divider { height:3px; background:#4f46e5; border-radius:3px; margin:16px 0 4px; }

    /* ===== SECTION ===== */
    .section-title { font-size:15px; font-weight:bold; color:#1e1f24; margin:22px 0 4px; }
    .section-sub { font-size:10px; color:#9ca3af; margin-bottom:14px; }

    /* ===== APPLICANTS TABLE ===== */
    table.applicants { width:100%; border-collapse:collapse; }
    table.applicants th { background:#4f46e5; color:#fff; font-size:9.5px; text-align:left;
                          padding:11px 12px; text-transform:uppercase; letter-spacing:.4px; font-weight:bold;
                          border-right:1px solid #6366f1; }
    table.applicants th:last-child { border-right:none; }
    table.applicants td { padding:12px; border:1px solid #eceef1; font-size:11px; vertical-align:middle; }
    table.applicants tbody tr:nth-child(even) td { background:#f9fafb; }

    .ap-avatar { width:38px; height:38px; border-radius:50%; }
    .ap-avatar-fallback { width:38px; height:38px; border-radius:50%; background:#4f46e5;
                          color:#fff; text-align:center; line-height:38px; font-weight:bold; font-size:15px; }
    .ap-name { font-weight:bold; color:#1e1f24; font-size:12px; }
    .ap-contact { color:#4b5563; font-size:10.5px; }
    .ap-date { color:#6b7280; font-size:10px; }

    .status { display:inline-block; padding:4px 12px; border-radius:12px; font-size:9.5px; font-weight:bold; }
    .st-pending     { background:#f3f4f6; color:#6b7280; }
    .st-reviewed    { background:#eff6ff; color:#2563eb; }
    .st-shortlisted { background:#dcfce7; color:#16a34a; }
    .st-accepted    { background:#dcfce7; color:#15803d; }
    .st-rejected    { background:#fef2f2; color:#dc2626; }
    .st-external    { background:#fff7ed; color:#ea580c; }

    .no-applicants { text-align:center; padding:44px 20px; color:#9ca3af; font-size:13px;
                     background:#f9fafb; border:1px dashed #d1d5db; border-radius:12px; margin-top:8px; }
    .no-applicants b { color:#6b7280; font-size:15px; }

    /* ===== FOOTER (page bottom এ fixed) ===== */
    .footer { position:fixed; bottom:0; left:0; right:0; padding:14px 46px;
              border-top:1px solid #e5e7eb; font-size:9px; color:#9ca3af;
              text-align:center; line-height:1.6; background:#fff; }
    .footer b { color:#4f46e5; }
</style>
</head>
<body>

    {{-- ===== HEADER ===== --}}
    <table class="header">
        <tr>
            <td style="width:45%;">
                <div class="brand-name">Borobhai.online</div>
                <div class="brand-sub">An Alumni Networking System</div>
                <div class="brand-tag">Job Applicants Report</div>
            </td>
            <td style="width:55%;" class="job-box">
                <div class="job-title">{{ $job->title }}</div>
                <div class="job-row"><b>Company:</b> {{ $job->company }}</div>
                <div class="job-row"><b>Type:</b> {{ $job->job_type }}</div>
                @if($job->experience || $job->salary)
                    <div class="job-row"><b>Experience / Salary:</b>
                        {{ $job->experience ?: 'N/A' }}@if($job->salary) · {{ $job->salary }}@endif
                    </div>
                @endif
                <div class="job-row"><b>Posted by:</b> {{ $job->user->name ?? 'Unknown' }}</div>
                <div class="job-row"><b>Posted on:</b> {{ $job->created_at->format('d M Y') }}</div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    {{-- ===== APPLICANTS ===== --}}
    <div class="section-title">Applicants ({{ $applicants->count() }})</div>
    <div class="section-sub">Everyone who applied to this position and their current status</div>

    @if($applicants->count())
        <table class="applicants">
            <thead>
                <tr>
                    <th style="width:8%;"></th>
                    <th style="width:23%;">Name</th>
                    <th style="width:26%;">Email</th>
                    <th style="width:15%;">Phone</th>
                    <th style="width:14%;">Applied On</th>
                    <th style="width:14%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applicants as $app)
                    @php
                        $pic = $app->user && $app->user->profile_picture
                            ? public_path('storage/' . $app->user->profile_picture)
                            : null;
                        $hasPic = $pic && file_exists($pic);

                        $stClass = $app->apply_method === 'external' ? 'st-external' : 'st-' . $app->status;
                        $stLabel = $app->apply_method === 'external' ? 'External' : ucfirst($app->status);
                    @endphp
                    <tr>
                        <td>
                            @if($hasPic)
                                <img src="{{ $pic }}" class="ap-avatar">
                            @else
                                <div class="ap-avatar-fallback">{{ strtoupper(substr($app->applicant_name, 0, 1)) }}</div>
                            @endif
                        </td>
                        <td><span class="ap-name">{{ $app->applicant_name }}</span></td>
                        <td class="ap-contact">{{ $app->applicant_email }}</td>
                        <td class="ap-contact">{{ $app->phone ?: '—' }}</td>
                        <td class="ap-date">{{ $app->applied_at ? $app->applied_at->format('d M Y') : '—' }}</td>
                        <td><span class="status {{ $stClass }}">{{ $stLabel }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-applicants">
            <b>No applicants yet</b><br>
            <span style="font-size:11px;">No one has applied to this job posting so far.</span>
        </div>
    @endif

    {{-- ===== FOOTER (bottom fixed) ===== --}}
    <div class="footer">
        Generated by <b>Borobhai.online</b> — An Alumni Networking System<br>
        Confidential · For the job poster's use only · {{ $generated->format('d M Y, g:i A') }}
    </div>

</body>
</html>