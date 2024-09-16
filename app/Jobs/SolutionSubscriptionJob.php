<?php

namespace App\Jobs;

use App\Constants\PaymentStatus;
use App\Contracts\PaymentGatewayContract;
use App\Infrastructure\Persistence\Models\Subscription;
use App\PaymentGateway\PlacetopayGateway;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SolutionSubscriptionJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;


    protected Subscription $subscription;
    protected PlacetopayGateway $paymentGateway;

    public int $tries = 5;
    public function __construct(Subscription $subscription)
    {
        $this->paymentGateway = resolve(PaymentGatewayContract::class);
        $this->subscription = $subscription;
    }


    public function handle(): void
    {
        $this->paymentGateway->querySubscription($this->subscription);
    }

    public function failed(\Exception $exception): void
    {
        $this->subscription->status = PaymentStatus::REJECTED->value;
        $this->subscription->save();
    }
}
