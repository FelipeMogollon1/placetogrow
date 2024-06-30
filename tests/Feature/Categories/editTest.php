<?php

namespace Tests\Feature\Categories;

use App\Infrastructure\Persistence\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class editTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_edit_categories(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)
            ->assertAuthenticatedAs($user)
            ->get(route('categories.edit', $category->id));

        $response->assertOK()
            ->assertInertia(
                fn(AssertableInertia $page) => $page
                    ->component('Categories/Edit')
            );
    }
}
