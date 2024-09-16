<?php

namespace App\Domain\User\Actions;

use App\Infrastructure\Persistence\Models\User;

class StoreUserAction
{
    public function execute(array $data): bool
    {
        User::query()->create($data)->assignRole($data['role']);

        return true;
    }
}
