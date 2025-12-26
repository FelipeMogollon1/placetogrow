<?php

namespace App\Http\Requests\User;

use App\Constants\DocumentTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                'unique:users,email',
            ],
            'document_type' => [
                'required',
                'string',
                'max:255',
                Rule::in(DocumentTypes::getDocumentTypes()),
            ],
            'document' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) {
                    $patterns = [
                        'CC' => '/^[1-9][0-9]{3,9}$/',
                        'CE' => '/^([a-zA-Z]{1,5})?[1-9][0-9]{3,7}$/',
                        'TI' => '/^[1-9][0-9]{4,11}$/',
                        'NIT' => '/^[1-9]\d{6,9}$/',
                    ];
                    $documentType = $this->input('document_type');

                    if (!isset($patterns[$documentType])) {
                        $fail("Tipo de documento '$documentType' no reconocido.");
                        return;
                    }

                    if (!preg_match($patterns[$documentType], $value)) {
                        $fail("El campo '$attribute' no es válido para el tipo de documento '$documentType'.");
                    }
                }
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                Rules\Password::defaults()
            ],
            'role' => 'required|string|exists:roles,name',
        ];
    }


    protected function prepareForValidation(): void
    {
        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower($this->input('email')),
            ]);
        }
    }

}
