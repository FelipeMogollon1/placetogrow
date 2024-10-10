<?php

namespace App\Http\Requests\Form;

use App\Rules\Form\ValidateFormConfiguration;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'configuration' => [
                'required',
                'array',
                new ValidateFormConfiguration()
            ],
            'footer' => ['nullable'],
            'header' => ['nullable'],
            'color' => ['nullable'],
            'additional_info' => [
                'nullable',
                'string',
            ],
            'expiration_additional_info' => [
                'nullable',
                'string',
            ],
            'tries' => [
                'required',
                'integer',
                'min:1',
            ],
            'backoff' => [
                'required',
                'integer',
                'min:1',
            ],
            'additionalValue' => [
                'nullable',
                'integer',
                'min:1',
                'required_with:additionalValueType',
            ],
            'additionalValueType' => [
                'nullable',
                'string',
                Rule::in(['fixedValue', 'percentage']),
                'required_with:additionalValue',
            ],
        ];
    }
}
