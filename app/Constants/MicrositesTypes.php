<?php

namespace App\Constants;

enum MicrositesTypes: string
{
    case INVOICE = 'invoice';
    case SUBSCRIPTION = 'subscription';
    case DONATION = 'donation';

    public static function getMicrositesTypes(): array
    {
        return array_map(fn ($enum) => $enum->value, self::cases());
    }
}
