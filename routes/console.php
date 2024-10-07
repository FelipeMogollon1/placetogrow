<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:transactions-consult')
    ->withoutOverlapping(10)->everyThirtySeconds();

