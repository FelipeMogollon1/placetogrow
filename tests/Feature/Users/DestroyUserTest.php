<?php

namespace Tests\Feature\Users;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\User;
use Tests\TestCase;

class DestroyUserTest extends TestCase
{
    public function test_can_destroy_user(): void
    {
        $this->seed();

        $user = User::factory()->create();
        $user->assignRole(Roles::ADMIN);

        $adminRole = $user->roles()->first();
        $adminRole->givePermissionTo(Permissions::USERS_DESTROY);

        $userToDelete = User::factory()->create();

        $this->actingAs($user)
            ->delete(route('users.destroy', $userToDelete->id))
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseMissing('users', ['id' => $userToDelete->id]);
    }
}
