<?php

namespace App\PaymentGateway;

use App\Constants\AdditionalValueTypes;
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
use App\Jobs\Subscription\SolutionCollectSubscriptionJob;
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
    protected placetoPay $placetoPay;

    public function __construct(array $settings)
    {
        $this->connection($settings);
    }

    public function connection(array $settings): self
    {
        $this->placetoPay = new PlacetoPay([
            'login' => $settings['login'],
            'tranKey' => $settings['tranKey'],
            'baseUrl' => $settings['baseUrl'],
            'timeout' => $settings['timeout'] ?? 10,
        ]);
        return $this;
    }

    /**
     * @throws GatewayException
     */
    public function createSession(Payment $payment, Request $request): RedirectResponse
    {
        try {
            $requestData = [
                'payer' => $this->getPayerData($payment),
                'payment' => $this->getPaymentData($payment),
                'expiration' => now()->addMinutes($payment->microsite->payment_expiration_time)->toIso8601String(),
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
        try {
            $requestData = [
                'payer' => $this->getPayerData($subscription),
                'subscription' => [
                    'reference' => $subscription->reference,
                    'description' => $subscription->description,
                ],
                'expiration' => now()->addMinutes($subscription->microsite->payment_expiration_time)->toIso8601String(),
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
        try {
            $requestData = [
                'payer' => $this->getPayerData($invoice),
                'payment' => $this->getPaymentDataInvoice($invoice),
                'expiration' => now()->addMinutes($invoice->microsite->payment_expiration_time)->toIso8601String(),
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

    public function queryPayment(Payment $payment): Payment
    {
        if ($payment->request_id) {
            $response = $this->placetoPay->query($payment->request_id);
            if ($response->isSuccessful()) {
                if ($response->status()->isApproved()) {
                    $answer = $response->payment();
                    $answer = $answer[0]->toArray();

                    $payment->status = PaymentStatus::APPROVED->value;
                    $payment->paid_at = new Carbon($response->status()->date());
                    $payment->receipt = Arr::get($answer, 'receipt');
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
                    $answer = $response->payment();
                    $answer = $answer[0]->toArray();

                    $invoice->status = PaymentStatus::APPROVED->value;
                    $invoice->paid_at = new Carbon($response->status()->date());
                    $invoice->receipt = Arr::get($answer, 'receipt');
                    $invoice->amount = $response->request()->payment()->amount()->total();
                    $invoice->save();
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

    public function querySubscriptionCollect(SubscriptionPayment $subscriptionPayment): SubscriptionPayment
    {
        log::info('querySubscriptionCollect here: ' . $subscriptionPayment->subscription . ' ' . $subscriptionPayment->subscription_plan);
        try {
            Log::info('Starting subscription collect query.', ['subscription_payment_id' => $subscriptionPayment->id]);

            $requestData = $this->buildRequestData($subscriptionPayment->subscription, $subscriptionPayment->subscriptionPlan);
            $response = $this->placetoPay->collect($requestData);

            if ($response->requestId()) {
                Log::info('Received requestId from collect.', ['request_id' => $response->requestId()]);

                $status = $response->status()->status();

                $data = [
                    'request_id' => $response->requestId(),
                    'attempt_count' => $subscriptionPayment->attempt_count + 1,
                ];

                if ($status === PaymentStatus::APPROVED->value) {
                    Log::info('Payment approved.');

                    $this->updateSubscription($subscriptionPayment->subscription, $response, $subscriptionPayment->subscriptionPlan);
                    $data = array_merge($data, [
                        'status' => PaymentStatus::APPROVED->value,
                        'paid_at' => Carbon::parse($response->status()->date()),
                    ]);

                } elseif ($status === PaymentStatus::REJECTED->value) {
                    Log::warning('Payment rejected.');

                    $data = array_merge($data, [
                        'status' => PaymentStatus::PENDING->value,
                        'paid_at' => null,
                    ]);
                }

                $subscriptionPayment->update($data);

                if ($status === PaymentStatus::REJECTED->value) {
                    throw new GatewayException('Payment rejected. Retrying...');
                }
            } else {
                Log::error('Failed to retrieve requestId from subscription collect.');
            }

            return $subscriptionPayment;

        } catch (Throwable $exception) {
            Log::error('An error occurred during subscription collect.', [
                'subscription_payment_id' => $subscriptionPayment->id,
                'exception' => $exception->getMessage(),
            ]);
            report($exception);
            throw new GatewayException($exception->getMessage());
        }
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

    protected function getPaymentDataInvoice($invoice): array
    {
        $microsite = Microsite::with('form')->findOrFail($invoice->microsite_id);
        $amount = $invoice->amount;

        Log::info('amount: ' . Carbon::parse($invoice->expiration_date) . ' now: ' . now());

        if ($invoice->expiration_date < now()) {
            Log::info('expiration date is less than now');

            $additionalValue = $microsite->form->additionalValue;
            $additionalValueType = $microsite->form->additionalValueType;
            Log::info('additionalValue: ' . $additionalValue . ', additionalValueType: ' . $additionalValueType);

            if ($additionalValueType === AdditionalValueTypes::FIXED->value) {
                $amount += $additionalValue;
            } elseif ($additionalValueType === AdditionalValueTypes::PERCENTAGE->value) {
                $amount += $amount * ($additionalValue / 100);
            }
        }

        return [
            'reference' => $invoice->reference,
            'description' => $invoice->description,
            'amount' => [
                'currency' => $invoice->currency ?? $invoice->currency_type,
                'total' => $amount,
            ],
        ];
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
        try {
            Log::info('Starting subscription collect query.', ['subscription' => $subscription->id]);
            $subscriptionPlan = $subscription->subscriptionPlan;
            Log::info('subscriptionPlan: ' . $subscriptionPlan);

            $requestData = $this->buildRequestData($subscription, $subscriptionPlan);
            $response = $this->placetoPay->collect($requestData);

            $this->updateCollectSubscriptionStatus($subscription, $subscriptionPlan, $response);

            Log::info('status collect placetopay '. $response->status()->status());

            if ($response->status()->status() === PaymentStatus::APPROVED->value) {
                $this->updateSubscription($subscription, $response, $subscriptionPlan);
            }
            return $response;

        } catch (Throwable $exception) {
            report($exception);
            Log::error('An error occurred while creating the subscription session', ['exception' => $exception->getMessage()]);
            throw new GatewayException($exception->getMessage());
        }
    }

    /**
     * @throws \Exception
     */
    protected function updateSubscription($subscription, $response, $subscriptionPlan): void
    {
        $date = Carbon::parse($response->status()->date())->startOfDay();
        $nextBillingDate = $this->calculateNextBillingDate($date, $subscriptionPlan->subscription_period);

        $subscription->update([
            'next_billing_date' => $nextBillingDate,
            'total_charges' => $subscription->total_charges + 1,
        ]);
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
            'currency' => $response->request()->payment()->amount()->currency(),
            'amount' => $response->request()->payment()->amount()->total(),
            'attempt_count' => 0,
            'paid_at' => $status === PaymentStatus::APPROVED->value ? $date : null,
        ];

        $subscriptionPayment = SubscriptionPayment::create($data);

        if (!isset($data['paid_at']) && $status === PaymentStatus::REJECTED->value) {
            SolutionCollectSubscriptionJob::dispatch($subscriptionPayment);
        }
    }

    protected function buildRequestData(Subscription $subscription, SubscriptionPlan $subscriptionPlan): array
    {
        return [
            'payer' => $this->getPayerData($subscription),
            'payment' => [
                'reference' => $subscription->reference,
                'description' => $subscription->description,
                'amount' => [
                    'currency' => $subscriptionPlan->currency,
                    'total' => (int) $subscriptionPlan->amount,
                ],
            ],
            'instrument' => [
                'token' => [
                    'token' => Crypt::decrypt($subscription->token),
                ],
            ]
        ];
    }


    /**
     * @throws \Exception
     */
    protected function calculateNextBillingDate(Carbon $currentDate, string $period): Carbon
    {
        return match ($period) {
            'daily' => $currentDate->copy()->addDay(),
            'weekly' => $currentDate->copy()->addWeek(),
            'monthly' => $currentDate->copy()->addMonth(),
            'yearly' => $currentDate->copy()->addYear(),
            default => throw new \Exception("Invalid subscription period: $period"),
        };
    }
}
