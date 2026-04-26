<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\ReportStripeUsage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule Daily Stripe Usage Sync for 12:01 AM
Schedule::command(ReportStripeUsage::class)->dailyAt('00:01')->withoutOverlapping();

// Re-verify Custom Domains and SSL Health twice daily
Schedule::command('monitor:connectivity')->twiceDaily(1, 13)->withoutOverlapping();
