<?php

namespace App\Domain\User\Actions;

use App\Infrastructure\Persistence\Models\User;

class DestroyUserAction
{
    public function execute(User $user): bool
    {
        if ($user->microsites()->exists())
        {
            return true;
        }

        return false;
    }
}
