<?php

namespace App\Jobs\Notify;

use App\Infrastructure\Persistence\Models\Invoice;
use App\Notifications\InvoiceExpirationNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyUserAboutInvoice implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;

    }

    public function handle(): void
    {
        try {
            $this->invoice->notify(new InvoiceExpirationNotification($this->invoice));
        } catch (\Exception $e) {
            Log::error('Error at sending invoice expiration notification: ' . $e->getMessage());
        }
    }
}
