<?php

namespace App\Domain\Subscription\Actions;

use App\Constants\PaymentStatus;
use App\Infrastructure\Persistence\Models\Subscription;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
                $subscription->status = PaymentStatus::REJECTED;
                $subscription->save();

                return true;
            }

            Log::error('Failed to cancel subscription', [
                'response' => $response->json(),
                'status_code' => $response->status()
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Exception occurred while canceling subscription', ['exception' => $e->getMessage()]);
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
