<?php

namespace App\Http\Requests\Form;

use App\Rules\Form\ValidateFormConfiguration;
use Illuminate\Foundation\Http\FormRequest;


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
            'additional_information' => ['nullable'],
        ];
    }
}
