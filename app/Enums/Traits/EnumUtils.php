<?php

namespace App\Enums\Traits;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

trait EnumUtils
{
    use InvokableCases;
    use Names;
    use Values;
    use Options;

    public static function random(): self
    {
        return self::cases()[random_int(0, count(self::cases()) - 1)];
    }
}
