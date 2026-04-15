<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule automatic cleanup of incomplete registrations every hour
Schedule::command('registrations:cleanup')->hourly();

// Send renewal deadline warnings to unrenewed clubs — fires daily at 08:00, active Aug 21–31
Schedule::command('renewals:send-warnings')
    ->dailyAt('08:00')
    ->when(fn () => now()->month === 8 && now()->day >= 21);
