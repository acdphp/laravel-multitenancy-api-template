<?php

namespace App\Models;

use Acdphp\Multitenancy\Traits\BelongsToTenant;
use App\Enums\Locale;
use App\Enums\Role;
use App\Models\Traits\WithDefaultLocale;
use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Passport\HasApiTokens;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property mixed $password
 * @property int $company_id
 * @property string|null $remember_token
 * @property string $firstname
 * @property string $lastname
 * @property Role $role
 * @property string|null $address
 * @property string|null $country
 * @property Locale|null $locale
 * @property string|null $telephone
 * @property string|null $avatar
 * @property-read Company|null $company
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @mixin Eloquent
 */
class User extends Authenticatable
{
    use BelongsToTenant,
        HasApiTokens,
        HasFactory,
        Notifiable,
        SoftDeletes,
        WithDefaultLocale;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'company_id',
        'address',
        'country',
        'telephone',
        'avatar',
        'locale',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'locale' => Locale::class,
        'role' => Role::class,
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
