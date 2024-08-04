<?php

namespace App\Rules\Payment;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class UniqueDocumentCombination implements ValidationRule
{
    protected $payerDocumentType;

    public function __construct($payerDocumentType)
    {
        $this->payerDocumentType = $payerDocumentType;
    }

    public function passes($attribute, $value): bool
    {
        if (!$this->payerDocumentType || !$value) {
            return true;
        }

        return !DB::table('payments')
            ->where('payer_document', $value)
            ->where('payer_document_type', $this->payerDocumentType)
            ->exists();
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }
}
