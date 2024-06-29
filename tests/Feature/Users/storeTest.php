<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class storeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_use(): void
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Juan de Dios',
            'email' => 'JuanDios995@gmail.com',
            'password' => 'JuanDios995',
        ];

       $this->actingAs($user)
            ->post(route('users.store'),$data)
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users',[
            'name' => 'Juan de Dios',
            'email' => 'JuanDios995@gmail.com',
        ]);
    }

}
