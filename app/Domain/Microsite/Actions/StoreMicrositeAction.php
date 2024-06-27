<?php

namespace App\Domain\Microsite\Actions;

use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\Microsite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class StoreMicrositeAction
{
    public function execute(array $data): RedirectResponse
    {
       if ($data['logo']) {
            $data['logo'] = $data['logo']->store('logo', ['disk' => 'public']);
        }

        $category = Category::find($data['category_id']);

        Microsite::create([
            'slug' => Str::slug($data['name'], '_') . '_' . Str::random(10, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'),
            'name' => $data['name'],
            'logo' => $data['logo'] ?? null,
            'document_type' => $data['document_type'],
            'document' => $data['document'],
            'microsite_type' => $data['microsite_type'],
            'currency' => $data['currency'],
            'payment_expiration_time' => $data['payment_expiration_time'],
            'category_id' => $category->id,
        ]);

        return to_route('microsites.index');
    }
}
