<?php

namespace App\Constants;

enum CurrencyTypes: string
{
    case COP = 'COP';
    case USD = 'USD';
    case BOTH = 'BOTH';

    public static function getSelectableCurrencies(): array
    {
        return [
            self::COP->value,
            self::USD->value,
        ];
    }
    public static function getCurrencyType(): array
    {
        return array_map(fn($enum) => $enum->value, self::cases());
    }
}
