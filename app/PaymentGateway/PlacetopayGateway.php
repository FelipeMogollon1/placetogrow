<?php

namespace App\PaymentGateway;

use App\Constants\PaymentStatus;
use App\Contracts\PaymentGatewayContract;
use App\Infrastructure\Persistence\Models\Payment;
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
        $totalPrice = $payment->amount;

        $payerData = array_filter([
            'document' => $payment->payer_document,
            'documentType' => $payment->payer_document_type,
            'name' => $payment->payer_name,
            'surname' => $payment->payer_surname,
            'email' => $payment->payer_email,
        ], function ($value) {
            return $value !== null && $value !== '';
        });

        try {
            $requestData = [
                'payer' => $payerData,
                'payment' => [
                    'reference' => $payment->reference,
                    'description' => $payment->description,
                    'amount' => [
                        'currency' => $payment->currency,
                        'total' => $totalPrice,
                    ],
                ],
                'expiration' => date('c', strtotime('+30 minutes')),
                'returnUrl' => route('returnBusiness', $payment->id),
                'ipAddress' => $request->ip(),
                'userAgent' => $request->userAgent(),
            ];
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

    public function queryPayment(Payment $payment): Payment
    {
//        $this->connection();

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
