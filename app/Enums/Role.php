<?php

namespace App\Enums;

use App\Enums\Traits\EnumUtils;

enum Role: int
{
    use EnumUtils;

    case SUPER_ADMIN = 1;
    case ADMIN = 2;
    case STAFF = 3;

    public function isAdmin(): bool
    {
        return
            $this === self::SUPER_ADMIN ||
            $this === self::ADMIN;
    }
}
