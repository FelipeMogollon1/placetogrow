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
    protected ?PlacetoPay $placetopay = null;

    public function connection(): self
    {
        $service = config('payment.services.current');
        $gateway = config('payment.services.'.$service);
        $settings = Arr::get($gateway, 'settings');

        $this->placetopay = new PlacetoPay([
            'login' => Arr::get($settings, 'login'),
            'tranKey' => Arr::get($settings, 'tranKey'),
            'baseUrl' => Arr::get($settings, 'baseUrl'),
            'timeout' => 10,
        ]);
        return $this;
    }

    public function createSession(Payment $payment, Request $request): RedirectResponse
    {
        $this->connection();

        if (!$this->placetopay) {
            throw new \Exception('PlacetoPay not initialized');
        }

        $totalPrice = $payment->amount;
        try {
            $requestData = [
                'payment' => [
                    'reference' => '12321313',
                    'description' => $payment->description,
                    'amount' => [
                        'currency' => 'COP',
                        'total' => $totalPrice,
                    ],
                ],
                'expiration' => date('c', strtotime('+30 minutes')),
                'returnUrl' => route('Welcome'),
                'ipAddress' => $request->ip(),
                'userAgent' => $request->userAgent(),
            ];
            $response = $this->placetopay->request($requestData);
            if ($response->isSuccessful()) {
                $payment->status = PaymentStatus::PENDING->value;
                $payment->process_identifier = $response->processUrl();
                $payment->request_id = $response->requestId();
            } elseif($response->status()->isRejected()) {
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
        $this->connection();
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
}
