<?php

namespace App\Constants;

enum SubscriptionStatus: string
{
    case ACTIVE = 'ACTIVE';
    case PENDING = 'PENDING';
    case CANCELLED = 'CANCELLED';
    case REJECTED = 'REJECTED';
    case EXPIRED = 'EXPIRED';
    case PAUSED = 'PAUSED';

    public static function getAllSubscriptionStatus(): array
    {
        return array_map(fn ($enum) => $enum->value, self::cases());
    }

}
