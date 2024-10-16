<?php

namespace App\Console\Commands;

use App\Constants\PaymentStatus;
use App\Constants\SubscriptionStatus;
use App\Infrastructure\Persistence\Models\Invoice;
use App\Infrastructure\Persistence\Models\Subscription;
use App\Jobs\Notify\NotifyUserAboutInvoice;
use App\Jobs\Notify\NotifyUserAboutSubscription;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpiringInvoicesSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:Expiring-invoices-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifies users about invoices and subscriptions that are expiring in 5 days';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Log::info('start the command app:Expiring-invoices-subscriptions');

        $invoicesNotify = Invoice::where('status', PaymentStatus::PENDING->value)
            ->whereDate('expiration_date', now()->addDays(5))->get();

        foreach ($invoicesNotify as $invoice) {
            NotifyUserAboutInvoice::dispatch($invoice);
        }

        $subscriptionsNotify = Subscription::where('status', SubscriptionStatus::ACTIVE->value)
            ->whereDate('next_billing_date', now()->addDays(5))->get();

        foreach ($subscriptionsNotify as $subscription) {
            NotifyUserAboutSubscription::dispatch($subscription);
            $this->info('Notification dispatched for subscription: ' . $subscription->reference);
        }
        Log::info('finish the command app:Expiring-invoices-subscriptions');

    }
}
