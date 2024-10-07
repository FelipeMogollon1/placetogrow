<?php

namespace App\Jobs\Subscription;

use App\Constants\PaymentStatus;
use App\Contracts\PaymentGatewayContract;
use App\Domain\Subscription\Actions\DestroySubscriptionAction;
use App\Exceptions\GatewayException;
use App\Infrastructure\Persistence\Models\Subscription;
use App\Infrastructure\Persistence\Models\SubscriptionPayment;
use App\PaymentGateway\PlacetopayGateway;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SolutionCollectSubscriptionJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected SubscriptionPayment $subscriptionPayment;
    protected PlacetopayGateway $paymentGateway;

    public int $tries = 5;
    // public int $backoff = 300;

    public function __construct(SubscriptionPayment $subscriptionPayment)
    {
        $this->paymentGateway = resolve(PaymentGatewayContract::class);
        $this->subscriptionPayment = $subscriptionPayment;

    }

    /**
     * @throws GatewayException
     */
    public function handle(): void
    {
        $this->paymentGateway->querySubscriptionCollect($this->subscriptionPayment);
    }

    public function failed(Exception $exception): void
    {
        Log::error("El Job SolutionCollectSubscriptionJob falló para la suscripción ID: {$this->subscriptionPayment->subscription_id}. Error: {$exception->getMessage()}");

        $this->subscriptionPayment->update([
            'status' => PaymentStatus::REJECTED->value,
            'last_attempt_at' => now(),
        ]);

        $subscription = Subscription::findOrFail($this->subscriptionPayment->subscription_id);
        (new DestroySubscriptionAction())->execute($subscription);

        // Notification::send($this->subscriptionPayment->user, new SubscriptionFailedNotification($this->subscriptionPayment));

    }
}
