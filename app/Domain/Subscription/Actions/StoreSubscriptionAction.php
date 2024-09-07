<?php

namespace App\Domain\Subscription\Actions;

use App\Infrastructure\Persistence\Models\Subscription;
use Illuminate\Support\Str;

class StoreSubscriptionAction
{
    public function execute(array $data)
    {

        return Subscription::create([
            'reference' => $data['reference'] ?? Str::upper(Str::random(10)),
            'description' => $data['description'] ?? "You just subscribed " . $data['name'],
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
