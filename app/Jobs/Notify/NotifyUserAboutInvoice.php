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
use Illuminate\Support\Facades\Notification;

class NotifyUserAboutInvoice implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected Invoice $invoice;
    public int $tries = 5;
    public int $backoff = 60;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function handle(): void
    {
        try {
            Log::info('start job invoice notify');

            Notification::route('mail', $this->invoice->email)
                ->notify(new InvoiceExpirationNotification($this->invoice));

        } catch (\Exception $e) {
            Log::error('Error at sending invoice expiration notification: ' . $e->getMessage());
        }
    }

}
