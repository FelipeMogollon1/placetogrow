<?php

namespace Tests\Feature\Users;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_user(): void
    {
       $this->seed();

        $user = User::factory()->create();
        $user->assignRole(Roles::ADMIN);

        $adminRole = $user->roles()->first();
        $adminRole->givePermissionTo(Permissions::USERS_STORE);

        $data = [
            'name' => 'Juan de Dios',
            'email' => 'juandios995@gmail.com',
            'password' => 'JuanDios995',
            'password_confirmation' => 'JuanDios995',
            'role' => Roles::GUEST,
        ];

        $this->actingAs($user)
            ->post(route('users.store'), $data)
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'Juan de Dios',
            'email' => 'juandios995@gmail.com',
        ]);
    }
}
