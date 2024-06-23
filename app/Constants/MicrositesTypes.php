<?php

namespace App\Constants;

class MicrositesTypes
{
    public const INVOICE = 'Factura';
    public const SUBSCRIPTION = 'Suscripción';
    public const DONATION = 'Donación';

    public static function getMicrositesTypes(): array
    {
        return (new \ReflectionClass(self::class))->getConstants();
    }

}
