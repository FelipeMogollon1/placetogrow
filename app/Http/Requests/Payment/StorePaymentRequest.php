<?php

namespace App\Http\Requests\Payment;

use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use App\Constants\PaymentStatus;
use App\Rules\Payment\DocumentNumber;
use Illuminate\Foundation\Http\FormRequest;
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
            'reference' => ['nullable', 'string'],
            'receipt' => ['nullable', 'string'],
            'payer_name' => ['nullable', 'string', $this->payerFieldsRule()],
            'payer_surname' => ['nullable', 'string', $this->payerFieldsRule()],
            'payer_email' => [
                'nullable',
                'email',
                'regex:/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/',
                $this->payerFieldsRule()
            ],
            'payer_phone' => ['nullable', 'numeric', 'digits_between:7,10'],
            'payer_company' => ['nullable', 'string'],
            'payer_document_type' => [
                'nullable',
                'string',
                Rule::in(DocumentTypes::getDocumentTypes()),
                $this->payerFieldsRule(),
            ],
            'payer_document' => [
                'nullable',
                'string',
                $this->getDocumentValidationRule(),
                $this->payerFieldsRule(),
            ],
            'description' => ['nullable', 'string'],
            'currency' => [
                'required',
                'string',
                Rule::in(CurrencyTypes::getCurrencyType()),
            ],
            'amount' => array_merge([
                'required',
                'min:1',
                'max:999999999',
            ], $this->amountRule()),
            'paid_at' => ['nullable', 'date'],
            'status' => ['nullable', 'string', Rule::in(PaymentStatus::getPaymentStatus())],
            'process_identifier' => ['nullable', 'integer'],
            'user_id' => ['nullable', 'exists:users,id'],
            'microsite_id' => ['nullable', 'exists:microsites,id'],
            'additional_data' => ['nullable', 'json'],
        ];
    }


    private function payerFieldsRule(): string
    {
        return Rule::requiredIf($this->hasAnyPayerFields());
    }

    private function hasAnyPayerFields(): bool
    {
        return $this->has('payer_name') ||
            $this->has('payer_surname') ||
            $this->has('payer_email') ||
            $this->has('payer_document_type') ||
            $this->has('payer_document');
    }

    private function amountRule(): array
    {
        $rules = [];

        if ($this->input('currency') === CurrencyTypes::USD->value) {
            $rules[] = 'max:99999';
        }else if ($this->input('currency') === CurrencyTypes::COP->value)
            $rules[] = 'max:999999999';
        return $rules;
    }

    private function getDocumentValidationRule(): DocumentNumber
    {
        $documentType = $this->input('payer_document_type');
        return new DocumentNumber($documentType);
    }

}
