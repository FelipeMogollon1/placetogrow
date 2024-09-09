<?php

namespace App\Domain\Microsite\Actions;

use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\Form;
use App\Infrastructure\Persistence\Models\Microsite;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Support\Str;

class StoreMicrositeAction
{
    public function execute(array $data): ?Microsite
    {
        if ($data['logo']) {
            $data['logo'] = $data['logo']->store('logo', ['disk' => 'public']);
        }

        $category = Category::find($data['category_id']);
        $user = User::find($data['user_id']);

        $form = Form::create([
            'configuration' => $this->jsonForm($data['microsite_type']),
        ]);

        return Microsite::create([
            'slug' => Str::slug($data['name'], '_') . '_' . Str::random(10, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'),
            'name' => $data['name'],
            'logo' => $data['logo'] ?? null,
            'document_type' => $data['document_type'],
            'document' => $data['document'],
            'microsite_type' => $data['microsite_type'],
            'currency' => $data['currency'],
            'payment_expiration_time' => $data['payment_expiration_time'],
            'category_id' => $category->id,
            'user_id' => $user->id,
            'form_id' => $form->id,
        ]);
    }

    public function jsonForm(string $microsite_type): array
    {
        $filePath = base_path("app/Domain/Form/Json/{$microsite_type}.json");

        if (!file_exists($filePath)) {
            return [];
        }

        $json = file_get_contents($filePath);
        return json_decode($json, true);
    }

}
