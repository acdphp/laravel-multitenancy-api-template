<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Model;

trait WithDefaultLocale
{
    public static function bootWithDefaultLocale(): void
    {
        static::creating(static fn ($model) => $model->locale = config('app.default_locale'));
    }
}
