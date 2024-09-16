<?php

namespace App\Jobs;

use App\Contracts\PaymentGatewayContract;
use App\Infrastructure\Persistence\Models\Subscription;
use App\PaymentGateway\PlacetopayGateway;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SolutionSubscriptionJob implements ShouldQueue
{
    use Queueable;

    protected Subscription $subscription;
    protected PlacetopayGateway $paymentGateway;
    public function __construct(Subscription $subscription)
    {
        $this->paymentGateway = resolve(PaymentGatewayContract::class);
        $this->subscription = $subscription;
    }


    public function handle(): void
    {
        $this->paymentGateway->querySubscription($this->subscription);
    }
}
