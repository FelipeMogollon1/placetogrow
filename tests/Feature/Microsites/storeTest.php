<?php

namespace Tests\Feature\Microsites;

use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use App\Constants\MicrositesTypes;
use App\Infrastructure\Persistence\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class storeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_store_microsites(): void
    {

        Storage::fake('public');

        $user = User::factory()->create();

        $logo = UploadedFile::fake()->image('logo.jpg');

        $microsite = [
            'name' => 'andres',
            'logo' => $logo,
            'document_type' => DocumentTypes::CC,
            'document' => '1321657',
            'microsite_type' => MicrositesTypes::INVOICE,
            'currency' => CurrencyTypes::COP,
            'payment_expiration_time' => 12,
            'category_id' => Category::factory()->create()->id,
        ];


        $response = $this->actingAs($user)
            ->post(route('microsites.store'), $microsite);


        $response->assertRedirect(route('microsites.index'));


        Storage::disk('public')->assertExists('logo/' . $logo->hashName());


        $this->assertDatabaseHas('microsites', [
            'name' => 'andres',
            'logo' => 'logo/' . $logo->hashName(),
            'document_type' => DocumentTypes::CC,
            'document' => '1321657',
            'microsite_type' => MicrositesTypes::INVOICE,
            'currency' => CurrencyTypes::COP,
            'payment_expiration_time' => 12,
            'category_id' => Category::first()->id,
        ]);
    }
}
