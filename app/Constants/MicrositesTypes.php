<?php

namespace App\Constants;

class MicrositesTypes
{
    public const INVOICE = 'invoice';
    public const SUBSCRIPTION = 'subscription';
    public const DONATION = 'donation';

    public static function getMicrositesTypes(): array
    {
        return (new \ReflectionClass(self::class))->getConstants();
    }

}
