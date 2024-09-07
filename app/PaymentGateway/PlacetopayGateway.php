<?php

namespace App\PaymentGateway;

use App\Constants\MicrositesTypes;
use App\Constants\PaymentStatus;
use App\Contracts\PaymentGatewayContract;
use App\Infrastructure\Persistence\Models\Microsite;
use App\Infrastructure\Persistence\Models\Payment;
use App\Infrastructure\Persistence\Models\Subscription;
use Dnetix\Redirection\Message\RedirectResponse;
use Dnetix\Redirection\PlacetoPay;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Throwable;

class PlacetopayGateway implements PaymentGatewayContract
{
    public PlacetoPay $placetopay;

    public function connection(array $settings): self
    {
        $this->placetopay = $this->getPaymentPlacetoPay($settings);
        return $this;
    }

     public function createSession(Payment $payment, Request $request): RedirectResponse
    {
        $microsite = Microsite::findOrFail($payment->microsite_id);

        $payerData = array_filter([
            'document' => $payment->payer_document,
            'documentType' => $payment->payer_document_type,
            'name' => $payment->payer_name,
            'surname' => $payment->payer_surname,
            'company' => $payment->payer_company,
            'email' => $payment->payer_email,
            'mobile' => $payment->payer_phone,
        ]);

        $paymentData = array_filter([
            'reference' => $payment->reference,
            'description' => $payment->description,
            'amount' => [
                'currency' => $payment->currency,
                'total' => $payment->amount,
            ],
        ]);

        try {
            $requestData = [
                'payer' => $payerData,
                'expiration' => now()->addMinutes($microsite->payment_expiration_time)->toIso8601String(),
                'returnUrl' => route('returnBusiness', $payment->id),
                'ipAddress' => $request->ip(),
                'userAgent' => $request->userAgent(),
            ];

            $requestData['payment'] = $paymentData;

            $response = $this->placetopay->request($requestData);

            if ($response->isSuccessful()) {
                $payment->status = PaymentStatus::PENDING->value;
                $payment->process_identifier = $response->processUrl();
                $payment->request_id = $response->requestId();

            } else {
                $payment->status = PaymentStatus::REJECTED->value;
            }
            $payment->save();

            return $response;

        } catch (Throwable $exception) {
            report($exception);
            throw new GatewayException($exception->getMessage());
        }
    }

    public function createSessionSubscription(Subscription $subscription, Request $request):RedirectResponse
    {
        $microsite = Microsite::findOrFail($subscription->microsite_id);

        $payerData = array_filter([
            'document' => $subscription->document,
            'documentType' => $subscription->document_type,
            'name' => $subscription->name,
            'surname' => $subscription->surname,
            'company' => $subscription->company,
            'email' => $subscription->email,
            'mobile' => $subscription->mobile,
        ]);

        $subscriptionData = array_filter([
            'reference' => $subscription->reference,
            'description' => $subscription->description,
        ]);

        try {
            $requestData = [
                'payer' => $payerData,
                'expiration' => now()->addMinutes($microsite->payment_expiration_time)->toIso8601String(),
                'returnUrl' => route('returnSubscription', $subscription->id),
                'ipAddress' => $request->ip(),
                'userAgent' => $request->userAgent(),
            ];

            $requestData['subscription'] = $subscriptionData;
            $response = $this->placetopay->request($requestData);

            if ($response->isSuccessful()) {
                $subscription->status = PaymentStatus::PENDING->value;
                $subscription->process_identifier = $response->processUrl();
                $subscription->request_id = $response->requestId();
             } else {
                $subscription->status = PaymentStatus::REJECTED->value;
            }
            $subscription->save();

            return $response;

        } catch (Throwable $exception) {
            report($exception);
            throw new GatewayException($exception->getMessage());
        }
    }

    public function queryPayment(Payment $payment): Payment
    {
        $response = $this->placetopay->query($payment->request_id);

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
        return $payment;
    }

    public function querySubscription(Subscription $subscription):Subscription
    {
        $response = $this->placetopay->query($subscription->request_id);

        if ($response->isSuccessful()) {
            if ($response->status()->isApproved()) {
                $subscription->status = PaymentStatus::APPROVED->value;
                $subscription->paid_at = new Carbon($response->status()->date());
                $subscription->receipt = Arr::get($response->payment(), 'receipt');
            } elseif ($response->status()->isRejected()) {
                $subscription->status = PaymentStatus::REJECTED->value;
            }
            $subscription->save();
        }
        return $subscription;
    }

    public function getPaymentPlacetoPay(array $settings):PlacetoPay
    {
        return new PlacetoPay([
            'login' => Arr::get($settings, 'login'),
            'tranKey' => Arr::get($settings, 'tranKey'),
            'baseUrl' => Arr::get($settings, 'baseUrl'),
            'timeout' => 10,
        ]);
    }
}
