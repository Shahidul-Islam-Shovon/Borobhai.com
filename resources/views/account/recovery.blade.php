<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recover Account — Borobhai</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .card { background: #fff; border-radius: 20px; padding: 40px; max-width: 440px; width: 100%; box-shadow: 0 8px 32px rgba(15,23,42,0.08); border: 1px solid #e2e8f0; text-align: center; }
        .icon { width: 64px; height: 64px; background: #fef2f2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 1.8rem; }
        h2 { font-size: 1.3rem; font-weight: 800; color: #0f172a; margin-bottom: 8px; }
        p { color: #64748b; font-size: 0.88rem; margin-bottom: 6px; line-height: 1.6; }
        .warning { background: #fff7ed; border: 1px solid #fed7aa; border-radius: 10px; padding: 14px; margin: 20px 0; font-size: 0.82rem; color: #92400e; }
        .btn-primary { width: 100%; padding: 12px; background: #16a34a; color: #fff; border: none; border-radius: 10px; font-weight: 700; font-size: 0.9rem; cursor: pointer; }
        .btn-primary:hover { background: #15803d; }
        .btn-danger { width: 100%; padding: 12px; background: #fff; color: #dc2626; border: 1px solid #fca5a5; border-radius: 10px; font-weight: 600; font-size: 0.85rem; cursor: pointer; margin-top: 10px; }
    </style>
</head>
<body>
<div class="card">
    <div class="icon">⚠️</div>
    <h2>Account Scheduled for Deletion</h2>
    <p>You requested to delete your account. Your data will be <strong>permanently removed</strong> after 30 days.</p>

    @php
        $deletionDate = \Carbon\Carbon::parse(auth()->user()->deletion_requested_at)->addDays(30);
        $daysLeft = (int) \Carbon\Carbon::now()->diffInDays($deletionDate, false);
    @endphp

    <div class="warning">
        ⏳ <strong>{{ $daysLeft }} day{{ $daysLeft !== 1 ? 's' : '' }} remaining</strong> before permanent deletion.<br>
        Deadline: {{ $deletionDate->format('d M Y, g:i a') }}
    </div>

    <p>Changed your mind? You can recover your account right now.</p>

    <form method="POST" action="{{ route('account.recover') }}">
        @csrf
        <input type="hidden" name="confirm" value="1">
        <button type="submit" class="btn-primary">✅ Recover My Account</button>
    </form>

    <form method="POST" action="{{ route('logout') }}" style="margin-top: 10px;">
        @csrf
        <button type="submit" class="btn-danger">Sign Out (Keep Deletion Scheduled)</button>
    </form>
</div>
</body>
</html>