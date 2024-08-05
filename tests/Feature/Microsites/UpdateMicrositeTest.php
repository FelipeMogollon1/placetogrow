<?php

namespace Tests\Feature\Microsites;

use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use App\Constants\MicrositesTypes;
use App\Constants\Permissions;
use App\Constants\Roles;
use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\Form;
use App\Infrastructure\Persistence\Models\Microsite;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdateMicrositeTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_microsite(): void
    {
        $this->seed();

        Storage::fake('public');

        $user = User::factory()->create();
        $user->assignRole(Roles::SA->value);

        $adminRole = $user->roles()->first();
        $adminRole->givePermissionTo(Permissions::MICROSITES_UPDATE->value);

        $logo = UploadedFile::fake()->image('logo.jpg');
        $logoPath = $logo->store('logo', ['disk' => 'public']);

        $category = Category::factory()->create();

        $form = User::factory()->create();

        $microsite = Microsite::factory()->create([
            'name' => 'andres',
            'logo' => $logoPath,
            'document_type' => DocumentTypes::CC->value,
            'document' => '1321657',
            'microsite_type' => MicrositesTypes::INVOICE->value,
            'currency' => CurrencyTypes::COP->value,
            'payment_expiration_time' => 12,
            'category_id' => $category->id,
            'user_id' => $user->id,
            'form_id' => $form->id
        ]);

        $form = Form::factory()->create([
            'id' => $microsite->form_id,
            'configuration' => json_encode(['old_config' => 'value']),
        ]);

        $updatedLogo = UploadedFile::fake()->image('updated_logo.jpg');
        $updatedLogoPath = $updatedLogo->store('logo', ['disk' => 'public']);

        $updatedData = [
            'name' => 'updated_name',
            'logo' => $updatedLogo,
            'document_type' => DocumentTypes::CE->value,
            'document' => '7654321',
            'microsite_type' => MicrositesTypes::INVOICE->value,
            'currency' => CurrencyTypes::USD->value,
            'payment_expiration_time' => 24,
            'category_id' => $category->id,
            'user_id' => $user->id,
            'form_id' =>$form->id
        ];

        $response = $this->actingAs($user)
            ->put(route('microsites.update', $microsite->id), $updatedData);

        $response->assertRedirect(route('microsites.index'));

        $microsite->refresh();
        $form->refresh();

        $this->assertDatabaseHas('microsites', [
            'id' => $microsite->id,
            'name' => $updatedData['name'],
            'document_type' => $updatedData['document_type'],
            'document' => $updatedData['document'],
            'microsite_type' => $updatedData['microsite_type'],
            'currency' => $updatedData['currency'],
            'payment_expiration_time' => $updatedData['payment_expiration_time'],
            'category_id' => $updatedData['category_id'],
            'user_id' => $updatedData['user_id'],
            'form_id' =>$updatedData['form_id'],
        ]);

        $this->assertEquals($updatedLogoPath, $microsite->logo);

        $this->assertDatabaseHas('forms', [
            'id' => $microsite->form_id,
            'configuration' => json_encode($this->getExpectedFormConfiguration($updatedData['microsite_type'])),
        ]);
    }

    private function getExpectedFormConfiguration(string $micrositeType): array
    {
        $filePath = base_path("app/Domain/Form/Json/{$micrositeType}.json");

        if (!file_exists($filePath)) {
            return [];
        }

        $json = file_get_contents($filePath);
        return json_decode($json, true);
    }
}
