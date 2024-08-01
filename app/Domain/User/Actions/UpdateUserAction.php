<?php

namespace App\Domain\User\Actions;

use App\Infrastructure\Persistence\Models\User;
use Illuminate\Http\RedirectResponse;

class UpdateUserAction
{
    public function execute(int $id, array $data): RedirectResponse
    {
        $user = User::find($id);
        $user->update($data);
        return to_route('users.index');
    }
}
