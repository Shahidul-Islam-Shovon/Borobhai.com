<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;  // ← এটা add করো

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ✅ এভাবে লিখতে হবে
Schedule::command('jobs:notify-deadlines')->dailyAt('08:00');