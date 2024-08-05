<?php

namespace Tests\Feature\Categories;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_categories(): void
    {
        $this->seed();

        $user = User::factory()->create();
        $user->assignRole(Roles::SA->value);

        $adminRole = $user->roles()->first();
        $adminRole->givePermissionTo(Permissions::CATEGORIES_EDIT->value);

        $category = Category::factory()->create([
            'name' => 'name categories new',
            'description' => 'Description categories new',
        ]);

        $updatedData = [
            'name' => 'name categories new Second',
            'description' => 'Description categories Second',
        ];

        $response = $this->actingAs($user)
            ->put(route('categories.update', $category->id), $updatedData);

        $response->assertRedirect(route('categories.index'));

        $category->refresh();

        $this->assertDatabaseHas('categories', $updatedData);
    }
}
