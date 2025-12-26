<?php

namespace App\Console\Commands;

use App\Constants\SubscriptionStatus;
use App\Infrastructure\Persistence\Models\Subscription;
use App\Jobs\Subscription\SolutionAutomaticSubscriptionPayment;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DailySubscriptionCollectionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-subscription-collection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processes active subscriptions for daily billing';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Log::info('Start processing daily subscription collections');

        $subscriptionCollects = Subscription::where('status', SubscriptionStatus::ACTIVE->value)
            ->whereDate('next_billing_date', Carbon::now()->toDateString())
            ->get();

        foreach ($subscriptionCollects as $subscriptionCollect) {
            SolutionAutomaticSubscriptionPayment::dispatch($subscriptionCollect);
            $this->info('Dispatched payment job for subscription: ' . $subscriptionCollect->reference);
        }

        Log::info('Finished processing daily subscription collections');
    }
}
