<?php

namespace App\Enums;

use App\Enums\Traits\EnumUtils;

enum Locale: int
{
    use EnumUtils;

    case EN = 1;
}
