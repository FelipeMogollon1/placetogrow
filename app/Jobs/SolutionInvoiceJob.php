<?php

namespace App\Jobs;

use App\Constants\PaymentStatus;
use App\Contracts\PaymentGatewayContract;
use App\Infrastructure\Persistence\Models\Invoice;
use App\PaymentGateway\PlacetopayGateway;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SolutionInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Invoice $invoice;
    protected PlacetopayGateway $paymentGateway;
    public int $tries = 5;
    public function __construct(Invoice $invoice)
    {
        $this->paymentGateway = resolve(PaymentGatewayContract::class);
        $this->invoice = $invoice;
    }

    public function handle(): void
    {
        $this->paymentGateway->queryInvoice($this->invoice);
    }

    public function failed(\Exception $exception): void
    {
        $this->invoice->status = PaymentStatus::REJECTED->value;
        $this->invoice->save();
    }
}
