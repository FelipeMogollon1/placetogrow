<?php

namespace Tests\Feature\Users;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class EditUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_edit_user(): void
    {
        $this->seed();

        $user = User::factory()->create();

        $user->assignRole(Roles::ADMIN);

        $adminRole = $user->roles()->first();
        $adminRole->givePermissionTo(Permissions::USERS_EDIT);

        $userToEdit = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('users.edit', $userToEdit->id));

        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                ->component('Users/Edit')
            );
    }

}
