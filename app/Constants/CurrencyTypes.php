<?php

namespace App\Constants;

enum CurrencyTypes: string
{
    case COP = 'COP';
    case USD = 'USD';

    public static function getCurrencyType(): array
    {
        return array_map(fn($enum) => $enum->value, self::cases());
    }
}
