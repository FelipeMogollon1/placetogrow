<?php

namespace Tests\Feature\Categories;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class EditCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_edit_categories(): void
    {
        $this->seed();

        $user = User::factory()->create();
        $user->assignRole(Roles::ADMIN);

        $adminRole = $user->roles()->first();
        $adminRole->givePermissionTo(Permissions::CATEGORIES_EDIT);

        $category = Category::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('categories.edit', $category->id));

        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Categories/Edit')
            );
    }
}