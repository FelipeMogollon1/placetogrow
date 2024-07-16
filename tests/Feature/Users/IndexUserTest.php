<?php

namespace Tests\Feature\Users;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class IndexUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_index_user(): void
    {
        $this->seed();

        $user = User::factory()->create();

        $user->assignRole(Roles::ADMIN);

        $adminRole = $user->roles()->first();
        $adminRole->givePermissionTo(Permissions::USERS_INDEX);

        $response = $this->actingAs($user)
            ->assertAuthenticatedAs($user)
            ->get(route('users.index'));

        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                ->component('Users/Index')
                ->has('users')
            );
    }


}
