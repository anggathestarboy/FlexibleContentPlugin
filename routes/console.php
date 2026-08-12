<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('media-library:clean')
    ->weeklyOn(1, '11:00');
Schedule::command('media-library:regenerate --only-missing')
    ->dailyAt('4:20');
