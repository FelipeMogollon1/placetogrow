<?php

namespace App\Jobs;

use App\Constants\PaymentStatus;
use App\Contracts\PaymentGatewayContract;
use App\Infrastructure\Persistence\Models\Payment;
use App\PaymentGateway\PlacetopayGateway;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SolutionPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Payment $payment;
    protected PlacetopayGateway $paymentGateway;

    public int $tries = 5;

    public function __construct(Payment $payment)
    {
        $this->paymentGateway = resolve(PaymentGatewayContract::class);
        $this->payment = $payment;
    }

    public function handle(): void
    {
        $this->paymentGateway->queryPayment($this->payment);
    }

    public function failed(\Exception $exception): void
    {
        $this->payment->status = PaymentStatus::REJECTED->value;
        $this->payment->save();
    }
}
