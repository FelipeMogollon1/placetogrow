<?php

namespace Tests\Feature\Categories;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class storeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_categories(): void
    {

        $user = User::factory()->create();

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
