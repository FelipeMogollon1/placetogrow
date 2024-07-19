<?php

namespace Tests\Feature\Users;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_user(): void
    {
        $this->seed();

        $user = User::factory()->create();
        $user->assignRole(Roles::ADMIN);

        $adminRole = $user->roles()->first();
        $adminRole->givePermissionTo(Permissions::USERS_CREATE);

        $response = $this->actingAs($user)->get(route('users.create'));
        $response->assertOk()
            ->assertInertia(fn(AssertableInertia $page) => $page
                ->component('Users/Create')
            );
    }
}
