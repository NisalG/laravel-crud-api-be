<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\DeleteOldPostsJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

//Scheduling a command
// Schedule::command('app:send-daily-post-count-email example@example.com John')
//     // ->dailyAt('10:00')
//     ->everyMinute();

//Scheduling a job 
// This works, but according to the documentation, it is better to be done in the 'bootstrap\app.php' >> 'withSchedule()' for clarity
// Couldn't find a working example for that, therefore use this one 
$data = [
    'somedata' => 'somedata',
];
Schedule::job(new DeleteOldPostsJob($data))->everyMinute();