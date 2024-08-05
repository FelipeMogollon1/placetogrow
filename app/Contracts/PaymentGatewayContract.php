<?php

namespace App\Contracts;

use App\Infrastructure\Persistence\Models\Payment;
use Illuminate\Http\Request;


interface PaymentGatewayContract
{
    public function connection(array $settings): self;
    public function createSession(Payment $payment, Request $request);
    public function queryPayment(Payment $payment): Payment;
}
