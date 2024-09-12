<?php

namespace App\Constants;

enum SubscriptionStatus: string
{
    public const ACTIVE = 'active';
    public const PENDING = 'pending';
    public const CANCELLED = 'cancelled';
    public const EXPIRED = 'expired';
    public const PAUSED = 'paused';

    public static function getAllSubscriptionStatus(): array
    {
        return array_map(fn ($enum) => $enum->value, self::cases());
    }
}
