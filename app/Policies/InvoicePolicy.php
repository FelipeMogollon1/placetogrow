<?php

namespace App\Policies;

use App\Constants\Permissions;
use App\Infrastructure\Persistence\Models\User;

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::INVOICES_INDEX);
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::INVOICES_SHOW);
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::INVOICES_DESTROY);
    }

}
