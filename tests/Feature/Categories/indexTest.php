<?php

namespace Tests\Feature\Categories;

use App\Infrastructure\Persistence\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class indexTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_index_categories(): void
    {
        $user = User::factory()->create();
        $categories = Category::factory()->create();


        $response = $this->actingAs($user)
            ->assertAuthenticatedAs($user)
            ->get(route('categories.index'),compact('categories'));

        $response->assertOk()
            ->assertInertia(
                fn(AssertableInertia $page) => $page
                    ->component('Categories/Index')
                    ->has('categories')
            );
    }
}
