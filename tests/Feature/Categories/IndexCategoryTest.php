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
        $adminRole->givePermissionTo(Permissions::CATEGORIES_INDEX->value);

        Category::factory()->count(3)->create();

        $response = $this->actingAs($user)
            ->get(route('categories.index'));

        $totalCategories = Category::select(['id', 'name', 'description'])
            ->orderby('name', 'asc')
            ->paginate(5);

        $categories = $totalCategories->toArray()['data'];

        $response->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page) => $page
                    ->component('Categories/Index')
                    ->has('categories.data', count($categories))
                    ->where('categories.data.0.id', $categories[0]['id'])
                    ->where('categories.data.1.id', $categories[1]['id'])
                    ->where('categories.data.2.id', $categories[2]['id'])
            );
    }


}
