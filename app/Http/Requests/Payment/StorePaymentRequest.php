<?php

namespace App\Http\Requests\Payment;

use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use App\Constants\PaymentStatus;
use App\Rules\Payment\DocumentNumber;
use App\Rules\Payment\UniqueDocumentCombination;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'reference' => ['nullable', 'string', 'unique:payments,reference'],
            'receipt' => ['nullable', 'string'],
            'payer_name' => [
                'nullable',
                'string',
                Rule::requiredIf($this->hasAnyPayerFields())
            ],
            'payer_surname' => [
                'nullable',
                'string',
                Rule::requiredIf($this->hasAnyPayerFields())
            ],
            'payer_email' => [
                'nullable',
                'email',
                Rule::requiredIf($this->hasAnyPayerFields())
            ],
            'payer_phone' => ['nullable', 'numeric', 'min:10'],
            'payer_company' => ['nullable', 'string'],
            'payer_document_type' => [
                'nullable',
                'in:' . implode(',', DocumentTypes::getDocumentTypes()),
                Rule::requiredIf($this->hasAnyPayerFields())
            ],
            'payer_document' => [
                'nullable',
                new DocumentNumber($this->input('payer_document_type')),
                new UniqueDocumentCombination($this->input('payer_document_type')),
                Rule::requiredIf($this->hasAnyPayerFields())
            ],

            'description' => ['nullable', 'string'],
            'currency' => ['required', 'string', 'in:' . implode(',', CurrencyTypes::getCurrencyType())],
            'amount' => [
                'required',
                'numeric',
                'min:0',
                'max:999999999',
                Rule::requiredIf($this->hasAnyMountCurrency())
            ],
            'paid_at' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'in:' . implode(',', PaymentStatus::getPaymentStatus())],
            'process_identifier' => ['nullable', 'integer'],
            'user_id' => ['nullable', 'exists:users,id'],
            'microsite_id' => ['nullable', 'exists:microsites,id'],
            'additional_data' => ['nullable', 'json'],
        ];
    }

    private function hasAnyPayerFields(): bool
    {
        return $this->input('payer_name') !== null ||
                $this->input('payer_surname') !== null ||
                $this->input('payer_email') !== null ||
                $this->input('payer_document_type') !== null ||
                $this->input('payer_document') !== null;
    }

    private function hasAnyMountCurrency(): bool
    {
        $currency = $this->input('currency');
        $amount = $this->input('amount');

        $isValidCurrency = in_array($currency, CurrencyTypes::getCurrencyType());
        $isAmountValid = floatval($amount) > 99999;

        Log::info('Currency: ' . $currency);
        Log::info('Amount: ' . $amount);
        Log::info('Currency valid: ' . ($isValidCurrency ? 'true' : 'false'));
        Log::info('Amount greater than 99999: ' . ($isAmountValid ? 'true' : 'false'));

        return $isValidCurrency && $isAmountValid;
    }
}
