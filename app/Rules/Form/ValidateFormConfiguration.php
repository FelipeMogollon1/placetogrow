<?php

namespace App\Rules\Form;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateFormConfiguration implements ValidationRule
{
    protected array $existingFields;

    public function __construct(array $existingFields = [])
    {
        $this->existingFields = $existingFields;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->passes($value)) {
            $fail('Required fields cannot be deleted.');
        }
    }

    public function passes($value): bool
    {
        if (!is_array($value) || !isset($value['fields']) || !is_array($value['fields'])) {
            return false;
        }

        foreach ($value['fields'] as $field) {
            if (isset($field['required']) && $field['required'] === 'true') {
                if (isset($field['active']) && $field['active'] === 'false') {
                    return false;
                }
            }
        }

        return true;
    }


}
