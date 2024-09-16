<?php

namespace App\Domain\User\Actions;

use App\Infrastructure\Persistence\Models\User;

class UpdateUserAction
{
    public function execute(int $id, array $data): bool
    {
        $user = User::findOrFail($id);

        $updateSuccess = $user->update($data);

        if ($updateSuccess) {
            $user->syncRoles([$data['role']]);

            return true;
        }

        return false;
    }
}
