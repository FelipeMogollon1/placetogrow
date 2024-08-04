<?php

namespace App\Jobs;

use App\Infrastructure\Persistence\Models\Payment;
use App\PaymentGateway\PlacetopayGateway;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SolutionPaymentJob implements ShouldQueue
{
    use Queueable;

    protected Payment $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;

    }

    public function handle(PlacetopayGateway $gateway): void
    {
        $gateway->queryPayment($this->payment);
    }
}
