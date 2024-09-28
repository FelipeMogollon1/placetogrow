<?php

namespace App\Services;

use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class InvoiceValidationService
{
    public function validate(array $data): void
    {
        $rules = [
            'reference' => 'nullable|string',
            'name' => 'required|string',
            'surname' => 'required|string',
            'email' => [
                'required',
                'email',
                'regex:/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/'
            ],
            'document_type' => [
                'required',
                'string',
                Rule::in(DocumentTypes::getDocumentTypes())
            ],
            'document' => ['required', 'max:255', function ($attribute, $value, $fail) use ($data) {
                $patterns = [
                    'CC' => '/^[1-9][0-9]{3,9}$/',
                    'CE' => '/^([a-zA-Z]{1,5})?[1-9][0-9]{3,7}$/',
                    'TI' => '/^[1-9][0-9]{4,11}$/',
                    'NIT' => '/^[1-9]\d{6,9}$/',
                ];
                $documentType = $data['document_type'];

                if (!isset($patterns[$documentType])) {
                    $fail("Tipo de documento $documentType no reconocido.");
                    return;
                }

                if (!preg_match($patterns[$documentType], $value)) {
                    $fail("El $attribute no es válido para el tipo de documento $documentType.");
                }
            }],
            'description' => 'nullable|string',
            'currency_type' => ['required', Rule::in(CurrencyTypes::getCurrencyType())],
            'amount' => array_merge([
                'required',
                'numeric',
                'min:1',
                'max:999999999',
            ], $this->amountRule($data['currency_type'])),
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    private function amountRule(string $currencyType): array
    {
        $rules = [];

        if ($currencyType === CurrencyTypes::USD->value) {
            $rules[] = 'max:99999';
        } elseif ($currencyType === CurrencyTypes::COP->value) {
            $rules[] = 'max:999999999';
        }
        return $rules;
    }
}
