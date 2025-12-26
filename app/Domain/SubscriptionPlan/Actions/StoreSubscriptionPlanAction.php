<?php

namespace App\Domain\SubscriptionPlan\Actions;

use App\Infrastructure\Persistence\Models\SubscriptionPlan;

class StoreSubscriptionPlanAction
{
    public function execute(array $data): SubscriptionPlan
    {

        $description = is_array($data['description']) ? json_encode($data['description']) : $data['description'];

        return SubscriptionPlan::create([
            'name'                        => $data['name'],
            'description'                 => $description,
            'amount'                      => $data['amount'],
            'currency'                    => $data['currency'],
            'subscription_period'         => $data['subscription_period'],
            'expiration_time'             => $data['expiration_time'],
            'microsite_id'                => $data['microsite_id'],
        ]);
    }
}
