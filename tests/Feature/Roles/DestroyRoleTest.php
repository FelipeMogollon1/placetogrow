<?php

namespace Tests\Feature\Roles;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DestroyRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_destroy_role(): void
    {
        $this->seed();

        $user = User::factory()->create();
        $user->assignRole(Roles::ADMIN);

        $adminRole = $user->roles()->first();
        $adminRole->givePermissionTo(Permissions::ROLES_DESTROY);

        $response = $this->actingAs($user)
            ->delete(route('roles.destroy', $adminRole->id));

        $response->assertRedirect(route('roles.index'));

    }
}
