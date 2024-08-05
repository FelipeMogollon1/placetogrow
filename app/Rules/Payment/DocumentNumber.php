<?php

namespace App\Rules\Payment;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DocumentNumber implements ValidationRule
{
    protected $documentType;

    public function __construct($documentType)
    {
        $this->documentType = $documentType;
    }

    public function passes($attribute, $value)
    {
        $patterns = [
            'CC' => '/^[1-9][0-9]{3,9}$/',
            'CE' => '/^([a-zA-Z]{1,5})?[1-9][0-9]{3,7}$/',
            'TI' => '/^[1-9][0-9]{4,11}$/',
            'NIT' => '/^[1-9]\d{6,9}$/',
        ];

        if (!isset($patterns[$this->documentType])) {
            return false;
        }

        return preg_match($patterns[$this->documentType], $value);
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }
}
