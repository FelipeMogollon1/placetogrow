<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('app:transactions-consult')
    ->withoutOverlapping(10)->everyFifteenMinutes()->runInBackground();

Schedule::command('app:daily-subscription-collection')
    ->withoutOverlapping(10)->dailyAt('10:00')->runInBackground();

Schedule::command('app:expiring-invoices')
    ->withoutOverlapping(10)->dailyAt('11:00')->runInBackground();

Schedule::command('app:expiring-subscriptions')
    ->withoutOverlapping(10)->dailyAt('12:00')->runInBackground();

Schedule::command('app:upcoming-subscription-collections')
    ->withoutOverlapping(10)->dailyAt('13:00')->runInBackground();

