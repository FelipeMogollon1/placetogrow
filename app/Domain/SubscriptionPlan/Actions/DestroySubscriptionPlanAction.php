<?php

namespace App\Domain\SubscriptionPlan\Actions;

use App\Infrastructure\Persistence\Models\SubscriptionPlan;

class DestroySubscriptionPlanAction
{
    public function execute(int $id)
    {
        $subscriptionPlan = SubscriptionPlan::findOrFail($id);
        $subscriptionPlan->active = false;
        $subscriptionPlan->save();

        return $subscriptionPlan->microsite_id;
    }
}
