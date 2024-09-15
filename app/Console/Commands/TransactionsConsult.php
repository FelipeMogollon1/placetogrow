<?php

namespace App\Console\Commands;

use App\Infrastructure\Persistence\Models\Invoice;
use App\Infrastructure\Persistence\Models\Payment;
use App\Infrastructure\Persistence\Models\Subscription;
use App\Jobs\SoluctionInvoiceJob;
use App\Jobs\SolutionInvoiceJob;
use App\Jobs\SolutionPaymentJob;
use App\Jobs\SolutionSubscriptionJob;
use Illuminate\Console\Command;

class TransactionsConsult extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:transactions-consult';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $payments = Payment::where('status', 'pending')->orWhereNull('status')->get();
        foreach ($payments as $payment) {
            SolutionPaymentJob::dispatch($payment);
        }

        $subscriptions = Subscription::where('status', 'PENDING')->orWhereNull('status')->get();
        foreach ($subscriptions as $subscription) {
            SolutionSubscriptionJob::dispatch($subscription);
        }

        $invoices = Invoice::where('status', 'pending')->get();
        foreach ($invoices as $invoice) {
            SolutionInvoiceJob::dispatch($invoice);
        }

    }
}
