<?php

namespace App\Jobs\Notify;

use App\Infrastructure\Persistence\Models\Subscription;
use App\Notifications\SubscriptionNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyUserAboutSubscription implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $subscription;
    /**
     * Create a new job instance.
     */
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->subscription->notify(new SubscriptionNotification($this->subscription));
    }
}
