<?php

namespace App\Domain\Subscription\Actions;

use App\Infrastructure\Persistence\Models\Subscription;

class UpdateSubscriptionAction
{
    public function execute(string $id, array $data)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->update([
            'subscription_plan_id' => $data['subscription_plan_id'],
            'total_charges' => 0
        ]);

        return $subscription;
    }
}
