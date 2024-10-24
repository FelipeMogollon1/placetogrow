<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class ImportInvoicesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('invoices.import');
    }


    public function rules(): array
    {
        return [
            'invoices' => 'required|file|mimes:csv,xlsx,xls',
            'microsite_id' => 'required|integer|exists:microsites,id',
            'expiration_date' => 'required|date|after:today',
        ];
    }
}
