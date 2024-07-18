<?php

namespace App\Constants;

enum Roles: string
{
    case ADMIN = 'admin';
    case GUEST = 'guest';

    public static function getAllRoles(): array
    {
        return array_map(fn($enum) => $enum->value, self::cases());
    }
}
