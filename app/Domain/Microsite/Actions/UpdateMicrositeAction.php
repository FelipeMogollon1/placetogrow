<?php

namespace App\Domain\Microsite\Actions;

use App\Infrastructure\Persistence\Models\Category;
use App\Infrastructure\Persistence\Models\Microsite;

class UpdateMicrositeAction
{
    public function execute(int $id, array $data): ?array
    {

        $category = Category::find($data['category_id']);

        $microsite = Microsite::find($id);

        if (!$microsite) {
            return ['error' => 'No se encontró el micrositio'];
        }

        $microsite->update($data);

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
