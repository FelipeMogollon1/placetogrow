<?php

namespace App\Domain\Subscription\Actions;

use App\Constants\SubscriptionStatus;
use App\Infrastructure\Persistence\Models\Subscription;
use App\Jobs\Subscription\RetrySubscriptionCancellation;
use App\Notifications\SubscriptionCancelledNotification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DestroySubscriptionAction
{
    private string $paymentLogin;
    private string $paymentTranKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->paymentLogin = config('payment.services.placetopay.settings.login');
        $this->paymentTranKey = config('payment.services.placetopay.settings.tranKey');
        $this->baseUrl = config('payment.services.placetopay.settings.baseUrl');
    }

    public function execute(Subscription $subscription): bool
    {
        try {
            $subscriptionToken = Crypt::decrypt($subscription->token);

            $authPayload = $this->generateAuthPayload();
            $requestData = [
                'auth' => $authPayload,
                'locale' => 'en_US',
                'instrument' => [
                    'token' => [
                        'token' => $subscriptionToken,
                    ],
                ],
            ];

            $response = Http::post($this->baseUrl . 'api/instrument/invalidate', $requestData);

            if ($response->successful() && $response->json('status.status') === 'OK') {
                $subscription->status = SubscriptionStatus::CANCELLED->value;
                $subscription->save();

                Notification::route('mail', $subscription->email)
                    ->notify(new SubscriptionCancelledNotification($subscription));

                return true;

            } elseif ($response->json('status.status') === 'FAILED' && $response->json('status.reason') === 'XN') {
                $subscription->status = SubscriptionStatus::CANCELLED->value;
                $subscription->save();

                return true;
            }

            RetrySubscriptionCancellation::dispatch($subscription)->delay(now()->addMinutes(1));

            Log::error('Failed to cancel subscription', [
                'response' => $response->json(),
                'status_code' => $response->status()
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Exception occurred while canceling subscription', ['exception' => $e->getMessage()]);
            RetrySubscriptionCancellation::dispatch($subscription)->delay(now()->addMinutes(1));
            return false;
        }
    }

    private function generateAuthPayload(): array
    {
        $seed = Carbon::now()->toIso8601String();
        $rawNonce = Str::random();
        $encodedNonce = base64_encode($rawNonce);
        $encodedTranKey = base64_encode(hash('sha256', $encodedNonce . $seed . $this->paymentTranKey, true));

        return [
            'login' => $this->paymentLogin,
            'tranKey' => $encodedTranKey,
            'nonce' => base64_encode($encodedNonce),
            'seed' => $seed,
        ];
    }
}
