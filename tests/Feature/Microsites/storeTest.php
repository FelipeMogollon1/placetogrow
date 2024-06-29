<?php

namespace Tests\Feature\Microsites;

use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use App\Constants\MicrositesTypes;
use App\Infrastructure\Persistence\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_microsites(): void
    {
        $user = User::factory()->create();

        $logo = UploadedFile::fake()->image('logo.jpg');

        $microsite = [
            'slug' => 'slug_prueba',
            'name' => 'andres',
            'logo' => $logo,
            'document_type' => DocumentTypes::CC,
            'document' => '1321657',
            'microsite_type' => MicrositesTypes::INVOICE,
            'currency' => CurrencyTypes::COP,
            'payment_expiration_time' => 12,
            'category_id' => Category::factory()->create()->id,
        ];

        $this->actingAs($user)
            ->post(route('microsites.store'), $microsite)
            ->assertRedirect(route('microsites.index'));

        $this->assertDatabaseHas('microsites', [
            'slug' => 'slug_prueba',
            'name' => 'andres',
            'logo' => 'logo.jpg', // Aquí podría ser necesario ajustar dependiendo de cómo se almacene el logo en la base de datos
            'document_type' => DocumentTypes::CC,
            'document' => '1321657',
            'microsite_type' => MicrositesTypes::INVOICE,
            'currency' => CurrencyTypes::COP,
            'payment_expiration_time' => 12,
            'category_id' => Category::factory()->create()->id,
            ]);
      }
}
