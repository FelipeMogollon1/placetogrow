<?php

namespace App\Domain\Payment;



use App\Constants\CurrencyTypes;
use App\Infrastructure\Persistence\Models\Microsite;
use App\Infrastructure\Persistence\Models\Payment;
use Illuminate\Support\Str;

class StorePaymentAction
{
    public function execute(array $data): ?Payment
    {
        $microsite = Microsite::findOrFail($data['microsite_id']);

        if (empty($data['description'])) {
            $data['description'] = "Estás en la pasarela de pagos de " . $microsite->name;
        }

        if (empty($data['payer_document'])) {
            $data['payer_document_type'] = null;
        }

        return Payment::create([
            'reference' => Str::upper(Str::random(10)),
            'name' => $data['name'] ?? null,
            'payer_name' => $data['payer_name'] ?? null,
            'payer_surname' => $data['payer_surname'] ?? null,
            'payer_email' => $data['payer_email'] ?? null,
            'payer_phone' => $data['payer_phone'] ?? null,
            'payer_document_type' => $data['payer_document_type'] ?? null,
            'payer_document' => $data['payer_document'] ?? null,
            'description' => $data['description'],
            'amount' => $data['amount'],
            'currency' => $data['currency'] ?? $microsite->currency ,
            'microsite_id' => $data['microsite_id']
        ]);
    }
}
