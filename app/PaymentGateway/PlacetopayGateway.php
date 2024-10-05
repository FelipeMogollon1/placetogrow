<?php

namespace App\PaymentGateway;

use App\Constants\PaymentStatus;
use App\Constants\SubscriptionStatus;
use App\Contracts\PaymentGatewayContract;
use App\Exceptions\GatewayException;
use App\Infrastructure\Persistence\Models\Invoice;
use App\Infrastructure\Persistence\Models\Microsite;
use App\Infrastructure\Persistence\Models\Payment;
use App\Infrastructure\Persistence\Models\Subscription;
use App\Infrastructure\Persistence\Models\SubscriptionPayment;
use App\Infrastructure\Persistence\Models\SubscriptionPlan;
use Dnetix\Redirection\Message\RedirectInformation;
use Dnetix\Redirection\Message\RedirectResponse;
use Dnetix\Redirection\PlacetoPay;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Throwable;

class PlacetopayGateway implements PaymentGatewayContract
{
    protected $placetoPay;

    public function __construct(array $settings)
    {
        $this->placetoPay = $this->getPaymentPlacetoPay($settings);
    }

    public function connection(array $settings): self
    {
        $this->placetoPay = $this->getPaymentPlacetoPay($settings);
        return $this;
    }

    public function createSession(Payment $payment, Request $request): RedirectResponse
    {
        $microsite = Microsite::findOrFail($payment->microsite_id);

        try {
            $requestData = [
                'payer' => $this->getPayerData($payment),
                'payment' => $this->getPaymentData($payment),
                'expiration' => now()->addMinutes($microsite->payment_expiration_time)->toIso8601String(),
                'returnUrl' => route('returnBusiness', $payment->id),
                'ipAddress' => $request->ip(),
                'userAgent' => $request->userAgent(),
            ];

            $response = $this->placetoPay->request($requestData);

            $this->updateStatus($payment, $response);
            return $response;

        } catch (Throwable $exception) {
            report($exception);
            Log::error('An error occurred while making the payment', ['exception' => $exception->getMessage()]);
            throw new GatewayException($exception->getMessage());
        }
    }

    public function createSessionSubscription(Subscription $subscription, Request $request): RedirectResponse
    {
        $microsite = Microsite::findOrFail($subscription->microsite_id);

        try {
            $requestData = [
                'payer' => $this->getPayerData($subscription),
                'subscription' => [
                    'reference' => $subscription->reference,
                    'description' => $subscription->description,
                ],
                'expiration' => now()->addMinutes($microsite->payment_expiration_time)->toIso8601String(),
                'returnUrl' => route('returnSubscription', $subscription->id),
                'ipAddress' => $request->ip(),
                'userAgent' => $request->userAgent(),
            ];

            $response = $this->placetoPay->request($requestData);

            $this->updateSubscriptionStatus($subscription, $response);
            return $response;

        } catch (Throwable $exception) {
            report($exception);
            Log::error('An error occurred while creating the subscription session', ['exception' => $exception->getMessage()]);
            throw new GatewayException($exception->getMessage());
        }
    }

    public function createSessionInvoice(Invoice $invoice, Request $request): RedirectResponse
    {
        $microsite = Microsite::findOrFail($invoice->microsite_id);

        try {
            $requestData = [
                'payer' => $this->getPayerData($invoice),
                'payment' => $this->getPaymentData($invoice),
                'expiration' => now()->addMinutes($microsite->payment_expiration_time)->toIso8601String(),
                'returnUrl' => route('returnInvoice', $invoice->id),
                'ipAddress' => $request->ip(),
                'userAgent' => $request->userAgent(),

            ];

            $response = $this->placetoPay->request($requestData);
            $this->updateStatus($invoice, $response);
            return $response;

        } catch (Throwable $exception) {
            report($exception);
            Log::error('An error occurred while creating the bill invoice session', ['exception' => $exception->getMessage()]);
            throw new GatewayException($exception->getMessage());
        }
    }

    public function queryPayment(Payment $payment): Payment
    {
        if ($payment->request_id) {
            $response = $this->placetoPay->query($payment->request_id);
            if ($response->isSuccessful()) {
                if ($response->status()->isApproved()) {
                    $payment->status = PaymentStatus::APPROVED->value;
                    $payment->paid_at = new Carbon($response->status()->date());
                    $payment->receipt = Arr::get($response->payment(), 'receipt');
                } elseif ($response->status()->isRejected()) {
                    $payment->status = PaymentStatus::REJECTED->value;
                }
                $payment->save();
            }
        } else {
            $payment->status = PaymentStatus::REJECTED->value;
        }

        $payment->save();

        return $payment;
    }

    public function querySubscription(Subscription $subscription): Subscription
    {
        if ($subscription->request_id) {
            $response = $this->placetoPay->query($subscription->request_id);
            if ($response->isSuccessful()) {
                if ($response->status()->isApproved()) {
                    $instrumentData = $response->subscription()->instrumentToArray();

                    $subscription->status = SubscriptionStatus::ACTIVE->value;
                    $subscription->paid_at = new Carbon($response->status()->date());
                    $subscription->token = Crypt::encrypt($instrumentData[0]['value']);
                    $subscription->sub_token = Crypt::encrypt($instrumentData[1]['value']);
                    $subscription->franchiseName = $instrumentData[3]['value'];
                    $subscription->lastDigits = $instrumentData[5]['value'];
                    $subscription->validUntil = $instrumentData[6]['value'];

                } elseif ($response->status()->isRejected()) {
                    $subscription->status = SubscriptionStatus::REJECTED->value;
                }
            }

        } else {
            $subscription->status = SubscriptionStatus::CANCELLED->value;
        }

        $subscription->save();

        return $subscription;
    }

    public function queryInvoice(Invoice $invoice): Invoice
    {
        if ($invoice->request_id) {
            $response = $this->placetoPay->query($invoice->request_id);

            if ($response->isSuccessful()) {
                if ($response->status()->isApproved()) {
                    $invoice->status = PaymentStatus::APPROVED->value;
                    $invoice->paid_at = new Carbon($response->status()->date());
                    $invoice->receipt = Arr::get($response->payment(), 'receipt');
                } else {
                    $invoice->status = PaymentStatus::PENDING->value;
                }
            } else {
                $invoice->status = PaymentStatus::REJECTED->value;
            }

            $invoice->save();
        }

        return $invoice;
    }

    public function getPaymentPlacetoPay(array $settings): PlacetoPay
    {
        return new PlacetoPay([
            'login' => $settings['login'],
            'tranKey' => $settings['tranKey'],
            'baseUrl' => $settings['baseUrl'],
            'timeout' => $settings['timeout'] ?? 10,
        ]);
    }

    protected function getPayerData($entity): array
    {
        return array_filter([
            'document' => $entity->document ?? $entity->payer_document,
            'documentType' => $entity->document_type ?? $entity->payer_document_type,
            'name' => $entity->name ?? $entity->payer_name,
            'surname' => $entity->surname ?? $entity->payer_surname,
            'company' => $entity->company ?? $entity->payer_company,
            'email' => $entity->email ?? $entity->payer_email,
            'mobile' => $entity->mobile ?? $entity->payer_phone,
        ]);
    }

    protected function getPaymentData($entity): array
    {
        return array_filter([
            'reference' => $entity->reference,
            'description' => $entity->description,
            'amount' => [
                'currency' => $entity->currency ?? $entity->currency_type,
                'total' => $entity->amount,
            ],
        ]);
    }

    protected function updateStatus($entity, RedirectResponse $response): void
    {
        if ($response->isSuccessful()) {
            $entity->status = PaymentStatus::PENDING->value;
            $entity->process_identifier = $response->processUrl();
            $entity->request_id = $response->requestId();
        } else {
            $entity->status = PaymentStatus::REJECTED->value;
        }

        $entity->save();
    }

    protected function updateSubscriptionStatus($entity, RedirectResponse $response): void
    {
        if ($response->isSuccessful()) {
            $entity->status = SubscriptionStatus::PENDING->value;
            $entity->process_identifier = $response->processUrl();
            $entity->request_id = $response->requestId();
        } else {
            $entity->status = SubscriptionStatus::REJECTED->value;
        }

        $entity->save();
    }

    public function collectSubscription(Subscription $subscription): RedirectInformation
    {
        $subscriptionPlan = SubscriptionPlan::findOrFail($subscription->subscription_plan_id);

        try {
            $requestData = [
                'payer' => $this->getPayerData($subscription),
                'payment' => [
                    'reference' => $subscription->reference,
                    'description' => $subscription->description,
                    'amount' => [
                        'currency' => $subscriptionPlan->currency,
                        'total' => (int) ($subscriptionPlan->amount),
                    ],
                ],
                'instrument' => [
                    'token' => [
                        'token' => Crypt::decrypt($subscription->token),
                    ],
                ]
            ];

            $response = $this->placetoPay->collect($requestData);
            $this->updateCollectSubscriptionStatus($subscription, $subscriptionPlan, $response);

            return $response;

        } catch (Throwable $exception) {
            report($exception);
            Log::error('An error occurred while creating the subscription session', ['exception' => $exception->getMessage()]);
            throw new GatewayException($exception->getMessage());
        }
    }

    protected function updateCollectSubscriptionStatus($subscription, $subscriptionPlan, $response): void
    {
        $date = Carbon::parse($response->status()->date());
        $status = $response->status()->status();
        $data = [
            'subscription_id' => $subscription->id,
            'subscription_plan_id' => $subscriptionPlan->id,
            'status' => $status,
            'request_id' => $response->requestId(),
        ];

        if ($status === PaymentStatus::APPROVED->value) {
            $data = array_merge($data, [
                'currency' => $response->request()->payment()->amount()->currency(),
                'amount' => $response->request()->payment()->amount()->total(),
                'attempt_count' => 0,
                'paid_at' => $date,
            ]);
        } else {
            $data = array_merge($data, [
                'attempt_count' => 1,
                'last_attempt_at' => $date,
                'next_retry_at' => $date->copy()->addMinutes(5),
            ]);
        }
        SubscriptionPayment::create($data);
    }
}
