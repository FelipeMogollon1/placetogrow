<?php

namespace App\Jobs;

use App\Contracts\PaymentGatewayContract;
use App\Infrastructure\Persistence\Models\Invoice;
use App\PaymentGateway\PlacetopayGateway;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SolutionInvoiceJob implements ShouldQueue
{
    use Queueable;

    protected Invoice $invoice;
    protected PlacetopayGateway $paymentGateway;
    public function __construct(Invoice $invoice)
    {
        $this->paymentGateway = resolve(PaymentGatewayContract::class);
        $this->invoice = $invoice;
    }

    public function handle(): void
    {
        $this->paymentGateway->queryInvoice($this->invoice);
    }
}
