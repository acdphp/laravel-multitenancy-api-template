<?php

namespace App\Enums\Traits;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Names;
use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

trait EnumUtils
{
    use InvokableCases,
        Names,
        Options,
        Values;

    public function lowerName(): string
    {
        return strtolower($this->name);
    }

    public static function random(): self
    {
        return self::cases()[random_int(0, count(self::cases()) - 1)];
    }

    public static function lowerNames(): array
    {
        return array_map('strtolower', static::names());
    }
}
