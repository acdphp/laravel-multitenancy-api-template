<?php

namespace App\Models\Traits;

trait WithDefaultLocale
{
    public static function bootWithDefaultLocale(): void
    {
        static::creating(static fn ($model) => $model->locale = config('app.default_locale'));
    }
}
