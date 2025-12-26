<?php

namespace App\Constants;

enum AdditionalValueTypes: string
{
    case PERCENTAGE = 'PERCENTAGE';
    case FIXED = 'FIXED';

    public static function getAdditionalValueTypes(): array
    {
        return array_map(fn ($enum) => $enum->value, self::cases());
    }
}
