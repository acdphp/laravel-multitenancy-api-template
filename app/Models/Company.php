<?php

namespace App\Models;

use App\Enums\Locale;
use App\Models\Traits\WithDefaultLocale;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * App\Models\Company
 *
 * @property int $id
 * @property string $name
 * @property string|null $logo
 * @property string|null $country
 * @property Locale $locale
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @mixin Eloquent
 */
class Company extends Model
{
    use HasFactory,
        SoftDeletes,
        WithDefaultLocale;

    protected $fillable = [
        'name',
        'logo',
        'country',
        'locale',
    ];

    protected $casts = [
        'locale' => Locale::class,
    ];
}
