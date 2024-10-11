<?php

namespace App\Console\Commands;

use App\Constants\PaymentStatus;
use App\Constants\SubscriptionStatus;
use App\Infrastructure\Persistence\Models\Invoice;
use App\Infrastructure\Persistence\Models\Payment;
use App\Infrastructure\Persistence\Models\Subscription;
use App\Jobs\Invoice\SolutionInvoiceJob;
use App\Jobs\Payment\SolutionPaymentJob;
use App\Jobs\Subscription\SolutionAutomaticSubscriptionPayment;
use App\Jobs\Subscription\SolutionSubscriptionJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        Log::info('start the command transactions-consult');
        $payments = Payment::where('status', PaymentStatus::PENDING->value)->orWhereNull('status')->get();
        foreach ($payments as $payment) {
            SolutionPaymentJob::dispatch($payment);
        }

        $subscriptions = Subscription::where('status', SubscriptionStatus::PENDING->value)->get();
        foreach ($subscriptions as $subscription) {
            SolutionSubscriptionJob::dispatch($subscription);
        }

        $subscriptionCollects = Subscription::where('status', SubscriptionStatus::ACTIVE->value)
            ->whereDate('next_billing_date', Carbon::now()->toDateString())->get();
        foreach ($subscriptionCollects as $subscriptionCollect) {
            SolutionAutomaticSubscriptionPayment::dispatch($subscriptionCollect);
        }

        $invoices = Invoice::where('status', PaymentStatus::PENDING->value)->get();
        foreach ($invoices as $invoice) {
            SolutionInvoiceJob::dispatch($invoice);
        }
        Log::info('finish the command transactions-consult');
    }
}
