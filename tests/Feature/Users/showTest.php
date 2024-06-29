<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class showTest extends TestCase
{

    public function test_can_show_use(): void
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Juan de Dios',
            'email' => 'JuanDios995@gmail.com',
            'password' => 'JuanDios995',
        ];

        $response = $this->actingAs($user)
            ->assertAuthenticatedAs($user)
            ->get(route('users.show',$data));

        $response->assertOk()
            ->assertInertia(
                fn(AssertableInertia $page) => $page
                    ->component('Users/Show')
            );
    }
}

