<?php

namespace App\Policies;

use App\Constants\Permissions;
use App\Infrastructure\Persistence\Models\User;

class SubscriptionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::SUBSCRIPTIONS_INDEX);
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::SUBSCRIPTIONS_SHOW);
    }

    public function edit(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::SUBSCRIPTIONS_EDIT);
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::SUBSCRIPTIONS_UPDATE);
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::SUBSCRIPTIONS_DESTROY);
    }
}
