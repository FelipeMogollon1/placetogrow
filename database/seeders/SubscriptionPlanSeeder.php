<?php

namespace Database\Seeders;

use App\Constants\CurrencyTypes;
use App\Constants\SubscriptionPeriods;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionPlanSeeder extends Seeder
{
    public function run()
    {
        $micrositeIds = DB::table('microsites')
            ->where('microsite_type', 'subscription')
            ->pluck('id');

        if ($micrositeIds->isEmpty()) {
            return;
        }

        foreach (range(1, 10) as $index) {
            DB::table('subscription_plans')->insert([
                'name' => 'Plan Subscription ' . $index,
                'amount' => rand(10, 100),
                'currency' => CurrencyTypes::COP->value,
                'subscription_period' => SubscriptionPeriods::getAllSubscriptionPeriods()[array_rand(SubscriptionPeriods::getAllSubscriptionPeriods())], // Selección aleatoria
                'expiration_time' => rand(6, 48),
                'microsite_id' => $micrositeIds->random(),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
