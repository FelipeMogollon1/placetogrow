<?php

namespace App\Domain\Subscription\Actions;

use App\Constants\SubscriptionStatus;
use App\Infrastructure\Persistence\Models\Subscription;
use App\Infrastructure\Persistence\Models\SubscriptionPlan;
use Illuminate\Support\Str;

class StoreSubscriptionAction
{
    public function execute(array $data): bool | Subscription
    {
        $subscriptionExists = Subscription::select(
            'subscriptions.id',
            'subscriptions.email',
            'subscriptions.document_type',
            'subscriptions.document',
            'subscriptions.status'
        )
            ->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->where('subscriptions.email', $data['email'])
            ->where('subscriptions.document_type', $data['document_type'])
            ->where('subscriptions.document', $data['document'])
            ->where('subscription_plans.id', $data['subscription_plan_id'])
            ->where('subscriptions.status', SubscriptionStatus::ACTIVE->value)
            ->exists();

        if ($subscriptionExists) {
            return false;
        }

        $subscriptionPlans = SubscriptionPlan::findOrFail($data['subscription_plan_id']);

        return  Subscription::create([
            'reference' => $data['reference'] ?? Str::upper(Str::random(10)),
            'description' => $data['description'] ?? "You just subscribed " . $subscriptionPlans->name,
            'name' => $data['name'] ?? null,
            'surname' => $data['surname'] ?? null,
            'email' => $data['email'] ?? null,
            'document_type' => $data['document_type'] ?? null,
            'document' => $data['document'] ?? null,
            'process_url' => $data['process_url'] ?? null,
            'request_id' => $data['request_id'] ?? null,
            'mobile' => $data['mobile'] ?? null,
            'company' => $data['company'] ?? null,
            'token' => $data['token'] ?? null,
            'sub_token' => $data['sub_token'] ?? null,
            'subscription_plan_id' => $data['subscription_plan_id'],
            'microsite_id' => $data['microsite_id']
        ]);

    }

}
