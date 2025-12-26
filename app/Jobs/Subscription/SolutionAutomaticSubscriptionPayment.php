<?php

namespace App\Jobs\Subscription;

use App\Constants\SubscriptionStatus;
use App\Contracts\PaymentGatewayContract;
use App\Domain\Subscription\Actions\DestroySubscriptionAction;
use App\Exceptions\GatewayException;
use App\Infrastructure\Persistence\Models\Subscription;
use App\PaymentGateway\PlacetopayGateway;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SolutionAutomaticSubscriptionPayment implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected Subscription $subscription;
    protected PlacetopayGateway $paymentGateway;

    public function __construct(Subscription $subscription)
    {
        $this->paymentGateway = resolve(PaymentGatewayContract::class);
        $this->subscription = $subscription;
    }

    /**
     * @throws GatewayException
     */
    public function handle(): void
    {
        $expirationTime = $this->subscription->subscriptionPlan->expiration_time;
        $totalCharges = $this->subscription->total_charges;
        $validUntil = $this->subscription->validUntil;

        Log::info('value validUntil: '. $validUntil . ' now: '. Carbon::now());
        Log::info('value expirationTime: '. $expirationTime);
        Log::info('value totalCharges: '. $totalCharges);

        if ($validUntil > Carbon::now()) {
            if ($totalCharges < $expirationTime) {
                $this->paymentGateway->collectSubscription($this->subscription);
            } else {
                $this->expireSubscription();
            }
        } else {
            $this->expireSubscription();
        }
    }

    private function expireSubscription(): void
    {
        (new DestroySubscriptionAction())->execute($this->subscription);
        $this->subscription->status = SubscriptionStatus::EXPIRED->value;
        $this->subscription->save();
    }
}
