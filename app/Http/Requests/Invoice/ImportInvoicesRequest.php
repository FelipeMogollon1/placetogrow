<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class ImportInvoicesRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'invoices' => 'required|file|mimes:csv,xlsx,xls|max:2048',
            'microsite_id' => 'required|integer|exists:microsites,id',
        ];
    }
}
