<?php

namespace App\Constants;

enum SubscriptionStatus: string
{
    case CANCEL = 'cancel';

    public static function getAllSubscriptionStatus(): array
    {
        return array_map(fn ($enum) => $enum->value, self::cases());
    }
}
