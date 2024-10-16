<?php

use App\Jobs\Notify\NotifyUserAboutInvoice;
use Illuminate\Support\Facades\Schedule;

Schedule::command('app:transactions-consult')
    ->withoutOverlapping(10)->everyFifteenMinutes()->runInBackground();

Schedule::command('notify:expiring-invoices-subscriptions')
    ->withoutOverlapping(10)->dailyAt('13:00')->runInBackground();
