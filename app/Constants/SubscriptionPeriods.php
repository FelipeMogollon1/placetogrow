<?php

namespace App\Constants;

enum SubscriptionPeriods: string
{
    case DAILY = 'daily';
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';

    public static function getAllSubscriptionPeriods(): array
    {
        return array_map(fn ($enum) => $enum->value, self::cases());
    }
}
