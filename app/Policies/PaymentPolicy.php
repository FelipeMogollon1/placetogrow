<?php

namespace App\Policies;

use App\Constants\Permissions;
use App\Infrastructure\Persistence\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::PAYMENTS_INDEX);
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::PAYMENTS_SHOW);
    }

}
