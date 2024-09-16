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
            'payment_expiration_time' => ['required', 'integer', 'min:5', 'max:18446744073709551614'],
            'category_id' => ['required', 'integer'],
            'user_id' => ['nullable', 'integer'],
            'form_id' => ['nullable'],
        ];
    }

}
