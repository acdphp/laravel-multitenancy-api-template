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

    public static function array(): array
    {
        return array_combine(self::names(), self::values());
    }

    public static function arrayLower(): array
    {
        return array_combine(self::namesLower(), self::values());
    }

    public static function random(): self
    {
        return self::cases()[random_int(0, count(self::cases()) - 1)];
    }

    public function nameLower(): string
    {
        return strtolower($this->name);
    }

    public static function namesLower(): array
    {
        return array_map('strtolower', static::names());
    }

    public static function fromNamesLower(array $values): array
    {
        $arrayLower = static::arrayLower();

        return array_map(static fn ($value) => self::from($arrayLower[$value]), $values);
    }

    public static function casesNameLowerKeyed(): array
    {
        return array_combine(self::namesLower(), self::cases());
    }

    public static function casesExcept(self $role): array
    {
        /**
         * @phpstan-ignore-next-line
         */
        return array_filter(self::cases(), static fn ($case) => $case !== $role);
    }
}
