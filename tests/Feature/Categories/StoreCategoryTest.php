<?php

namespace Tests\Feature\Categories;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreCategoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_categories(): void
    {
        $this->seed();

        $user = User::factory()->create();
        $user->assignRole(Roles::ADMIN);

        $adminRole = $user->roles()->first();
        $adminRole->givePermissionTo(Permissions::CATEGORIES_STORE);

        $category = [
            'name' => 'name categories new',
            'description' => 'Description categories new',
        ];

        $response = $this->actingAs($user)
            ->post(route('categories.store'), $category);

        $response->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', [
            'name' => 'name categories new',
            'description' => 'Description categories new',
        ]);
    }
}
