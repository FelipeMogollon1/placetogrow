<?php

namespace App\Http\Requests\Payment;

use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use App\Constants\PaymentStatus;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'reference' => ['nullable', 'string', 'unique:payments'],
            'receipt' => ['nullable', 'string'],
            'payer_name' => ['nullable', 'string'],
            'payer_surname' => ['nullable', 'string'],
            'payer_email' => ['nullable', 'email'],
            'payer_phone' => ['nullable', 'numeric'],
            'payer_company' => ['nullable', 'string'],
            'payer_document_type' => ['nullable', 'string', 'in:' . implode(',', DocumentTypes::getDocumentTypes())],
            'payer_document' => ['required', 'string', 'unique:payments'],
            'description' => ['nullable', 'string'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'paid_at' => ['nullable', 'date'],
            'currency' => ['nullable', 'string', 'in:' . implode(',', CurrencyTypes::getCurrencyType())],
            'status' => ['nullable', 'string', 'in:' . implode(',', PaymentStatus::getPaymentStatus())],
            'process_identifier' => ['nullable', 'integer'],
            'user_id' => ['nullable', 'exists:users,id'],
            'microsite_id' => ['nullable', 'exists:microsites,id'],
            'additional_data' => ['nullable', 'json'],
        ];
    }
}
