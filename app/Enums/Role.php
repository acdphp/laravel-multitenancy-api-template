<?php

namespace App\Enums;

use App\Enums\Traits\EnumUtils;

enum Role: int
{
    use EnumUtils;

    case SUPER_ADMIN = 1;
    case ADMIN = 2;
    case STAFF = 3;

    public static function isAdminRole(Role $role): bool
    {
        return
            $role === self::SUPER_ADMIN ||
            $role === self::ADMIN;
    }
}
