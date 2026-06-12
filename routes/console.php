<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Queue Worker — process all queued jobs including IndexNow pings
|--------------------------------------------------------------------------
*/
Schedule::command('queue:work --stop-when-empty')
    ->everyMinute()
    ->withoutOverlapping();

/*
|--------------------------------------------------------------------------
| IndexNow — Weekly Full-Site Submission
|--------------------------------------------------------------------------
| Pings all 5 engines (Bing, Yandex, Yep, Naver, Seznam) with the complete
| site URL list every Monday at 03:00 (server time, low traffic).
| This catches any pages that may have been missed by the per-post triggers.
|
| Individual blog create/update/delete actions fire their own pings
| via IndexNowPingJob dispatched from Admin\BlogController.
|--------------------------------------------------------------------------
*/
Schedule::command('indexnow:submit')
    ->weeklyOn(1, '03:00') // Every Monday at 3 AM
    ->withoutOverlapping()
    ->runInBackground();

