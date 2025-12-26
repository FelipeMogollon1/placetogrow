<?php

namespace App\Domain\SubscriptionPlan\Actions;

use App\Infrastructure\Persistence\Models\SubscriptionPlan;
use Illuminate\Support\Facades\DB;

class UpdateSubscriptionPlanAction
{
    public function execute(string $id, array $data): int
    {
        return DB::transaction(function () use ($id, $data) {
            $subscriptionPlan = SubscriptionPlan::findOrFail($id);
            $subscriptionPlan->active = false;
            $subscriptionPlan->save();

            if (isset($data['description']) && is_array($data['description'])) {
                $data['description'] = json_encode($data['description']);
            }

            $newPlan = $subscriptionPlan->replicate()->fill($data);
            $newPlan->active = true;
            $newPlan->save();

            return $subscriptionPlan->microsite_id;
        });
    }
}
