<?php

namespace Database\Seeders;

use App\Constants\DocumentTypes;
use App\Constants\MicrositesTypes;
use App\Constants\SubscriptionStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Random\RandomException;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $subscriptionPlanIds = DB::table('subscription_plans')->pluck('id');
        $micrositeIds = DB::table('microsites')
            ->where('microsite_type', MicrositesTypes::SUBSCRIPTION->value)->pluck('id');

        if ($subscriptionPlanIds->isEmpty() || $micrositeIds->isEmpty()) {
            return;
        }

        foreach ($subscriptionPlanIds as $subscriptionPlanId) {
            DB::table('subscriptions')->insert([
                'reference' => strtoupper(uniqid('SUB-')),
                'description' => 'You just subscribed to Plan ID ' . $subscriptionPlanId,
                'name' => 'Guest',
                'surname' => 'Invitado',
                'email' => 'guest@microsites.com',
                'document_type' => DocumentTypes::CC->value,
                'process_url' => null,
                'request_id' => strtoupper(substr(uniqid(), -6)),
                'document' => '333333333',
                'mobile' => null,
                'company' => null,
                'paid_at' => now(),
                'token' => $this->generateRandomToken(),
                'sub_token' => $this->generateRandomToken(),
                'franchiseName' => 'Visa',
                'lastDigits' => '1111',
                'validUntil' => now()->addMonths(6),
                'status' => SubscriptionStatus::ACTIVE->value,
                'process_identifier' => 'https://checkout-co.placetopay.dev/spa/session/' . uniqid(),
                'next_billing_date' => now()->addMonth(),
                'total_charges' => 0,
                'subscription_plan_id' => $subscriptionPlanId,
                'microsite_id' => $micrositeIds->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * @throws RandomException
     */
    private function generateRandomToken(): string
    {
        return bin2hex(random_bytes(32));
    }
}
