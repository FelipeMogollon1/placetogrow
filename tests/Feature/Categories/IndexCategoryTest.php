<?php

namespace Tests\Feature\Categories;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class IndexCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_index_categories(): void
    {
        $this->seed();

        $user = User::factory()->create();
        $user->assignRole(Roles::ADMIN);

        $adminRole = $user->roles()->first();
        $adminRole->givePermissionTo(Permissions::CATEGORIES_INDEX);

        Category::factory()->count(3)->create();

        $response = $this->actingAs($user)
            ->get(route('categories.index'));

        $totalCategories = Category::all();

        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Categories/Index')
                    ->has('categories', $totalCategories->count())
                    ->where('categories.0.id', $totalCategories[0]->id)
                    ->where('categories.1.id', $totalCategories[1]->id)
            );
    }
}
