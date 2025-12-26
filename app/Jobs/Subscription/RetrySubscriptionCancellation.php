<?php

namespace App\Jobs\Subscription;

use App\Domain\Subscription\Actions\DestroySubscriptionAction;
use App\Infrastructure\Persistence\Models\Subscription;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RetrySubscriptionCancellation implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public Subscription $subscription;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function handle(DestroySubscriptionAction $action): void
    {
        if (!$action->execute($this->subscription)) {
            Log::warning('Retrying subscription cancellation for Subscription ID: ' . $this->subscription->id);
            $this->release(60);
        }
    }
}
