<?php

namespace App\Http\Requests\Microsite;

use App\Constants\CurrencyTypes;
use App\Constants\DocumentTypes;
use App\Constants\MicrositesTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMicrositeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slug' => 'nullable|string|max:40',
            'name' => 'required','string','max:255',
            'logo' => 'nullable|mimes:png,jpg,jpeg,ico|max:2040',
            'document_type' => ['required', Rule::in(DocumentTypes::getDocumentTypes())],
            'document' => ['required', 'max:20', 'regex:/^[a-zA-Z0-9 \-]+$/'],
            'microsite_type' => ['required','string', Rule::in(MicrositesTypes::getMicrositesTypes())],
            'currency' => ['required', Rule::in(CurrencyTypes::getCurrencyType())],
            'payment_expiration_time' => ['required','max:18446744073709551614'],
            'category_id' => ['required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.string' => 'El slug debe ser una cadena de texto.',
            'slug.max' => 'El slug no debe exceder 255 caracteres.',

            'name.required' => 'El campo nombre es requerido.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe exceder 255 caracteres.',

            'logo.mimes' => 'El logo debe ser un archivo de tipo: png, jpg, jpeg ó ico.',
            'logo.max' => 'El logo no puede ser mayor a 2040 kilobytes.',

            'document_type.required' => 'El campo tipo de documento es requerido.',
            'document_type.in' => 'El tipo de documento seleccionado no es válido.',

            'document.required' => 'El campo documento es requerido.',
            'document.regex' => 'El campo documento solo puede contener letras y números.',
            'document.string' => 'El documento debe ser una cadena de texto.',
            'document.max' => 'El documento no debe exceder 20 caracteres.',

            'microsite_type.required' => 'El tipo de micrositio es requerido.',
            'microsite_type.string' => 'El tipo de micrositio debe ser una cadena de texto.',
            'microsite_type.in' => 'El tipo de micrositio  seleccionado no es válido.',

            'currency.required' => 'El campo moneda es requerido.',
            'currency.in' => 'La moneda seleccionada no es válida.',

            'payment_expiration_time.required' => 'El campo tiempo de expiración de pago es requerido.',
            'payment_expiration_time.max' => 'El tiempo de expiración de pago debe ser menor.',

            'category_id.required' => 'El campo categoría es requerido.',
            'category_id.integer' => 'La categoría de expiración de pago debe ser un número entero.',
        ];
    }
}
