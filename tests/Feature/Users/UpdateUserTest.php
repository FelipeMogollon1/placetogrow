<?php

namespace Tests\Feature\Users;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_user(): void
    {
        $this->seed();

        $user = User::factory()->create();
        $user->assignRole(Roles::ADMIN);

        $adminRole = $user->roles()->first();
        $adminRole->givePermissionTo(Permissions::USERS_UPDATE);

        $userToUpdate = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'originalemail@example.com',
            'password' => bcrypt('OriginalPassword'),
        ]);

        $data = [
            'name' => 'Juan de Dios',
            'email' => 'juandios995@gmail.com',
            'password' => 'JuanDios995',
            'password_confirmation' => 'JuanDios995',
        ];

        $response = $this->actingAs($user)
            ->put(route('users.update', $userToUpdate->id), $data);

        $response->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $userToUpdate->id,
            'name' => $data['name'],
            'email' => $data['email'],
        ]);
    }
}
