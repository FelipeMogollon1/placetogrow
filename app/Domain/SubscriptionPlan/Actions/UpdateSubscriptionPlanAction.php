<?php

namespace App\Domain\SubscriptionPlan\Actions;

use App\Infrastructure\Persistence\Models\SubscriptionPlan;

class UpdateSubscriptionPlanAction
{
    public function execute(string $id, array $data): SubscriptionPlan
    {
        $subscriptionPlan = SubscriptionPlan::findOrFail($id);

        if (isset($data['description'])) {
            $data['description'] = is_array($data['description']) ? json_encode($data['description']) : $data['description'];
        }

        $subscriptionPlan->update($data);

        return $subscriptionPlan;
    }

}
