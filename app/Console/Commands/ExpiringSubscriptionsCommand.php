<?php

namespace App\Console\Commands;

use App\Constants\SubscriptionPeriods;
use App\Constants\SubscriptionStatus;
use App\Infrastructure\Persistence\Models\Subscription;
use App\Jobs\Notify\NotifyUserAboutSubcriptionEnding;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ExpiringSubscriptionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expiring-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifies users about subscriptions that are expiring soon';

    public function handle(): void
    {
        Log::info('Starting command: app:expiring-subscriptions');

        $daysBeforeSubscriptionExpiration = (int) config('notifications.days_before_subscription_expiration');

        $subscriptionsNotifyEnding = Subscription::join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->select('subscriptions.id', 'subscriptions.reference')
            ->where('subscription_plans.subscription_period', '<>', SubscriptionPeriods::DAILY->value)
            ->whereColumn('subscriptions.total_charges', 'subscription_plans.expiration_time')
            ->where('subscriptions.status', SubscriptionStatus::ACTIVE->value)
            ->whereBetween('next_billing_date', [Carbon::now(), Carbon::now()->addDays($daysBeforeSubscriptionExpiration)])
            ->get();

        foreach ($subscriptionsNotifyEnding as $subscriptionNotifyEnding) {
            NotifyUserAboutSubcriptionEnding::dispatch($subscriptionNotifyEnding);
            $this->info('Notification dispatched for subscription ending: ' . $subscriptionNotifyEnding->reference);
        }

        Log::info('Finished command: app:expiring-subscriptions');
    }
}
