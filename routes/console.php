<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:transactions-consult')
    ->withoutOverlapping(10)->everyFifteenMinutes()->runInBackground();

Schedule::command('app:expiring-invoices-subscriptions')
    ->withoutOverlapping(10)->dailyAt('10:00')->runInBackground();
