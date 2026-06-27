<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobPost;
use App\Models\BbNotification;
use Illuminate\Support\Str;



class SendJobDeadlineNotifications extends Command
{
    protected $signature   = 'jobs:notify-deadlines';
    protected $description = 'Notify applicants about upcoming and expired job deadlines';

    public function handle()
    {
        // ================================================
        // 3 দিনের মধ্যে deadline — applicants কে notify
        // ================================================
        $comingSoon = JobPost::whereDate('deadline', now()->addDays(3)->toDateString())
            ->with('applications')
            ->get();

        $soonCount = 0;
        foreach ($comingSoon as $job) {
            foreach ($job->applications as $application) {
                BbNotification::send(
                    $application->user_id,
                    $job->user_id,
                    'job_deadline_soon',
                    'Deadline is approaching (3 days left) for: "' . Str::limit($job->title, 50) . '"',
                    'job',
                    $job->id
                );
                $soonCount++;
            }
        }

        // ================================================
        // আজকে deadline — expire হয়ে গেছে
        // ================================================
        $expired = JobPost::whereDate('deadline', now()->toDateString())
            ->with('applications')
            ->get();

        $expiredCount = 0;
        foreach ($expired as $job) {
            foreach ($job->applications as $application) {
                BbNotification::send(
                    $application->user_id,
                    $job->user_id,
                    'job_deadline_expired',
                    'Application deadline has passed for: "' . Str::limit($job->title, 50) . '"',
                    'job',
                    $job->id
                );
                $expiredCount++;
            }
        }

        $this->info("Deadline soon: {$soonCount} notifications sent.");
        $this->info("Deadline expired: {$expiredCount} notifications sent.");

        return 0;
    }
}