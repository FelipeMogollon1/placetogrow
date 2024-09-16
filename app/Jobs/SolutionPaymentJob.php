<?php

namespace App\Jobs;

use App\Contracts\PaymentGatewayContract;
use App\Infrastructure\Persistence\Models\Payment;
use App\PaymentGateway\PlacetopayGateway;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SolutionPaymentJob implements ShouldQueue
{
    use Queueable;

    protected Payment $payment;
    protected PlacetopayGateway $paymentGateway;

    public function __construct(Payment $payment)
    {
        $this->paymentGateway = resolve(PaymentGatewayContract::class);
        $this->payment = $payment;
    }

    public function handle(): void
    {
        $this->paymentGateway->queryPayment($this->payment);
    }
}
