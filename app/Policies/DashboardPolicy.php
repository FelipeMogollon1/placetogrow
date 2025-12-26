<?php

namespace App\Policies;

use App\Constants\Permissions;
use App\Infrastructure\Persistence\Models\User;

class DashboardPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::DASHBOARD_INDEX);
    }
}
