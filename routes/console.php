<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('app:transactions-consult')
    ->withoutOverlapping(10)->everyThirtySeconds();

