<?php

namespace App\Jobs\Notify;

use App\Infrastructure\Persistence\Models\Subscription;
use App\Notifications\SubscriptionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotifyUserAboutSubscription implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected Subscription $subscription;
    public int $tries = 5;
    public int $backoff = 60;


      public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function handle(): void
    {
        try {
            log::info('start job subscription notify');
            Log::info('Subscription email: ' . $this->subscription->email);

            Notification::route('mail', $this->subscription->email)
                ->notify(new SubscriptionNotification($this->subscription));

        } catch (\Exception $e) {
            Log::error('Error at sending subscription expiration notification: ' . $e->getMessage());
        }

    }
}
