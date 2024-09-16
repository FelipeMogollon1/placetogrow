<?php

namespace App\Contracts;

use App\Infrastructure\Persistence\Models\Invoice;
use App\Infrastructure\Persistence\Models\Payment;
use App\Infrastructure\Persistence\Models\Subscription;
use Dnetix\Redirection\Message\RedirectResponse;
use Illuminate\Http\Request;

interface PaymentGatewayContract
{
    public function connection(array $settings): self;

    public function createSession(Payment $payment, Request $request): RedirectResponse;
    public function queryPayment(Payment $payment): Payment;

    public function createSessionInvoice(Invoice $invoice, Request $request): RedirectResponse;


    public function createSessionSubscription(Subscription $subscription, Request $request): RedirectResponse;
    public function cancelSubscription(Subscription $subscription, Request $request): RedirectResponse;
    public function querySubscription(Subscription $subscription): Subscription;

    public function queryInvoice(Invoice $invoice): Invoice;

}
