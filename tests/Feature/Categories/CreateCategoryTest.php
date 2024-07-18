<?php

namespace Tests\Feature\Categories;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class CreateCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_category(): void
    {
        $this->seed();

        $user = User::factory()->create();
        $user->assignRole(Roles::ADMIN);

        $adminRole = $user->roles()->first();
        $adminRole->givePermissionTo(Permissions::CATEGORIES_CREATE);

        $response = $this->actingAs($user)
            ->assertAuthenticatedAs($user)
            ->get(route('categories.create'));

        $response->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Categories/Create'));

    }
}
