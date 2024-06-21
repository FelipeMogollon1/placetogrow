<?php

namespace App\Domain\Microsite\Actions;

use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\Microsite;
use Illuminate\Support\Str;

class StoreMicrositeAction
{
    public function execute(array $data): array
    {

        $category = Category::find($data['category_id']);
        $categoryId = $category ? $category->id : null;

        $microsite = Microsite::create([
            'slug' => Str::slug($data['name'], '_') . '_' . Str::random(10, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'),
            'name' => $data['name'],
            'logo' => $data['logo'],
            'document_type' => $data['document_type'],
            'document' => $data['document'],
            'microsite_type' => $data['microsite_type'],
            'currency' => $data['currency'],
            'payment_expiration_time' => $data['payment_expiration_time'],
            'category_id' => $categoryId,
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        return [
            'id' => $microsite->id,
            'slug' => $microsite->slug,
            'name' => $microsite->name,
            'document_type' => $microsite->document_type,
            'document' => $microsite->document,
            'logo' => $microsite->logo,
            'microsite_type' => $microsite->microsite_type,
            'currency' => $microsite->currency,
            "payment_expiration_time" => $microsite->payment_expiration_time,
            "category_id" => $microsite->category_id,
        ];

    }
}
