<?php

namespace Tests\Feature\Categories;

use App\Infrastructure\Persistence\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class destroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_destroy_categories(): void
    {
        $user = User::factory()->create();

        $category = Category::factory()->create();

        $response = $this->actingAs($user)
            ->delete(route('categories.destroy', $category->id));

        $response->assertRedirect(route('categories.index'));

    }
}
