<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Tests\TestCase;

class updateTest extends TestCase
{
    public function test_can_update_use(): void
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Juan de Dios',
            'email' => 'JuanDios995@gmail.com',
            'password' => 'JuanDios995',
        ];


        $this->actingAs($user)
            ->put(route('users.update', $user->id), $data)
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],

        ]);
    }
}
