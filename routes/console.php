<?php

use App\Jobs\EscalateComplaintJob;
use Illuminate\Support\Facades\Schedule;

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



// প্রতিদিন রাত ১২টায় ব্যাকগ্রাউন্ডে চেক করবে
Schedule::job(new EscalateComplaintJob)->daily();
