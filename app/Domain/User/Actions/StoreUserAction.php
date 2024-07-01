<?php

namespace App\Domain\User\Actions;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class StoreUserAction
{
    public function execute(array $data): RedirectResponse
    {

        $user = User::query()->create($data);

        $user->assignRole($data['role']);

        Log::info('user created successfully', ['id' => $user->id, 'name' => $user->name, 'email' => $user->email]);

        return to_route('users.index');
    }
}
