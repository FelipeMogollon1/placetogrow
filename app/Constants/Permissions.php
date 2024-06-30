<?php

namespace App\Constants;

class Permissions
{
    public const USERS_INDEX = 'users.index';
    public const USERS_SHOW = 'users.show';
    public const USERS_STORE = 'users.store';
    public const USERS_EDIT  = 'users.edit';
    public const USERS_UPDATE  = 'users.update';
    public const USERS_DESTROY = 'users.destroy';


    public const MICROSITES_INDEX = 'microsites.index';
    public const MICROSITES_SHOW = 'microsites.show';
    public const MICROSITES_STORE = 'microsites.store';
    public const MICROSITES_EDIT  = 'microsites.edit';
    public const MICROSITES_UPDATE  = 'microsites.update';
    public const MICROSITES_DESTROY = 'microsites.destroy';


    public const CATEGORIES_INDEX = 'categories.index';
    public const CATEGORIES_SHOW = 'categories.show';
    public const CATEGORIES_STORE = 'categories.store';
    public const CATEGORIES_EDIT  = 'categories.edit';
    public const CATEGORIES_UPDATE  = 'categories.update';
    public const CATEGORIES_DESTROY = 'categories.destroy';


    public static function  getAllPermissions(): array
    {
        return (new \ReflectionClass(self::class))->getConstants();
    }

    public static function getGuestPermissions(): array
    {
        return [
          self::MICROSITES_INDEX,
        ];
    }
}
