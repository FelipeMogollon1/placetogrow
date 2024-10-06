<?php

namespace App\Http\Requests\Profile;

use App\Constants\DocumentTypes;
use App\Infrastructure\Persistence\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
                 Rule::unique(User::class)->ignore($this->user()->id)
            ],
            'document_type' => [
                'nullable',
                'string',
                'max:255',
                Rule::in(DocumentTypes::getDocumentTypes()),
            ],
            'document' => [
                'nullable',
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
        ];
    }
}
