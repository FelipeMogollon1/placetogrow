<?php

namespace App\Constants;

class Roles
{
    public const ADMIN = 'admin';
    public const GUEST = 'guest';

    public static function getAllRoles(): array
    {
        return (new \ReflectionClass(self::class))->getConstants();
    }

}
