<?php

namespace App\Contracts;

use App\Infrastructure\Persistence\Models\Invoice;
use App\Infrastructure\Persistence\Models\Payment;
use App\Infrastructure\Persistence\Models\Subscription;
use App\Infrastructure\Persistence\Models\SubscriptionPayment;
use Dnetix\Redirection\Message\RedirectInformation;
use Dnetix\Redirection\Message\RedirectResponse;
use Illuminate\Http\Request;

interface PaymentGatewayContract
{
    public function connection(array $settings): self;
    public function createSession(Payment $payment, Request $request): RedirectResponse;
    public function queryPayment(Payment $payment): Payment;
    public function createSessionInvoice(Invoice $invoice, Request $request): RedirectResponse;
    public function queryInvoice(Invoice $invoice): Invoice;
    public function createSessionSubscription(Subscription $subscription, Request $request): RedirectResponse;
    public function querySubscription(Subscription $subscription): Subscription;

    public function collectSubscription(Subscription $subscription): RedirectInformation;
    public function querySubscriptionCollect(SubscriptionPayment $subscriptionPayment): SubscriptionPayment;

}
