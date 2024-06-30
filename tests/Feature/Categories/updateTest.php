<?php

namespace Tests\Feature\Categories;

use App\Infrastructure\Persistence\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class updateTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_categories(): void
    {
        $user = User::factory()->create();

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
