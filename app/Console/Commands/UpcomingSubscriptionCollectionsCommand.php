<?php

namespace App\Console\Commands;

use App\Constants\SubscriptionPeriods;
use App\Constants\SubscriptionStatus;
use App\Infrastructure\Persistence\Models\Subscription;
use App\Jobs\Notify\NotifyUserAboutSubscription;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpcomingSubscriptionCollectionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:upcoming-subscription-collections';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifies users about upcoming subscription collections';

    public function handle(): void
    {
        Log::info('Starting command: app:upcoming-subscription-collections');

        $daysBeforeSubscriptionCollection = (int) config('notifications.days_before_subscription_collection');

        $subscriptionsNotify = Subscription::join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->select('subscriptions.id, subscriptions.email, subscriptions.name, subscriptions.surname, subscriptions.reference, subscriptions.total_charges, subscription_plans.expiration_time')
            ->where('subscription_plans.subscription_period', '<>', SubscriptionPeriods::DAILY->value)
            ->whereColumn('subscriptions.total_charges', '<', 'subscription_plans.expiration_time')
            ->where('subscriptions.status', SubscriptionStatus::ACTIVE->value)
            ->whereBetween('next_billing_date', [Carbon::now(), Carbon::now()->addDays($daysBeforeSubscriptionCollection)])
            ->get();

        foreach ($subscriptionsNotify as $subscriptionNotify) {
            NotifyUserAboutSubscription::dispatch($subscriptionNotify);
            $this->info('Notification dispatched for subscription: ' . $subscriptionNotify->reference);
        }

        Log::info('Finished command: app:upcoming-subscription-collections');
    }
}
