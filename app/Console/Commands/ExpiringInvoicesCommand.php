<?php

namespace App\Console\Commands;

use App\Constants\PaymentStatus;
use App\Infrastructure\Persistence\Models\Invoice;
use App\Jobs\Notify\NotifyUserAboutInvoice;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpiringInvoicesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expiring-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifies users about invoices that are expiring soon';

    public function handle(): void
    {
        Log::info('Starting command: app:expiring-invoices');

        $daysBeforeInvoiceExpiration = (int) config('notifications.days_before_invoice_expiration');

        $invoicesNotify = Invoice::where('status', PaymentStatus::PENDING->value)
            ->whereBetween('expiration_date', [Carbon::now(), Carbon::now()->addDays($daysBeforeInvoiceExpiration)])
            ->get();

        foreach ($invoicesNotify as $invoice) {
            NotifyUserAboutInvoice::dispatch($invoice);
            $this->info('Notification dispatched for invoice: ' . $invoice->reference);
        }

        Log::info('Finished command: app:expiring-invoices');
    }
}
