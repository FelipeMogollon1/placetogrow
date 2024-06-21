<?php

namespace App\Domain\Microsite\Actions;

use App\Infrastructure\Persistence\Models\Microsite;

class UpdatePartialMicrositeAction
{

    public function execute(int $micrositeId, array $data): ?array
    {
        $microsite = Microsite::find($micrositeId);

        if (!$microsite) {
            return ['error' => 'No se encontró el micrositio'];
        }

        foreach ($data as $key => $value) {
            $microsite->$key = $value;
        }
        $microsite->save();


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
