<?php

namespace App\Domain\Payment;



use App\Infrastructure\Persistence\Models\Payment;

class StorePaymentAction
{
    public function execute(array $data): ?Payment
    {
        return Payment::query()->create($data);
    }
}
