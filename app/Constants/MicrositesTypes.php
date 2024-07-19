<?php

namespace App\Constants;

enum MicrositesTypes: string
{
    case INVOICE = 'Factura';
    case SUBSCRIPTION = 'Suscripción';
    case DONATION = 'Donación';

    public static function getMicrositesTypes(): array
    {
        return array_map(fn($enum) => $enum->value, self::cases());
    }
}
