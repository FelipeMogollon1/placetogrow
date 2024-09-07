<?php

namespace App\Http\Requests\Subscription;

use App\Constants\DocumentTypes;
use App\Rules\Payment\DocumentNumber;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSubscriptionRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reference' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'regex:/^[^@]+@[^@]+\.[a-zA-Z]{2,}$/',
                $this->payerFieldsRule()
            ],
            'document_type' => [
                'required',
                'string',
                Rule::in(DocumentTypes::getDocumentTypes()),
                $this->payerFieldsRule(),
            ],
            'process_url' => ['nullable', 'url'],
            'request_id' => ['nullable', 'string', 'max:255'],
            'document' => [
                'required',
                'string',
                $this->getDocumentValidationRule(),
                $this->payerFieldsRule(),
            ],
            'mobile' => ['nullable', 'numeric', 'digits_between:7,10'],
            'company' => ['nullable', 'string', 'max:255'],
            'token' => ['nullable', 'string'],
            'sub_token' => ['nullable', 'string'],
            'subscription_plan_id' => ['required', 'exists:subscription_plans,id'],
            'microsite_id' => ['required', 'exists:microsites,id'],
        ];
    }

    private function payerFieldsRule(): string
    {
        return Rule::requiredIf($this->hasAnyPayerFields());
    }

    private function hasAnyPayerFields(): bool
    {
        return $this->has('name') ||
            $this->has('surname') ||
            $this->has('email') ||
            $this->has('document_type') ||
            $this->has('document');
    }

    private function getDocumentValidationRule(): DocumentNumber
    {
        $documentType = $this->input('document_type');
        return new DocumentNumber($documentType);
    }
}
