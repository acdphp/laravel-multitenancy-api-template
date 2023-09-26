<?php

namespace DataResponses;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Tests\DataResponses\CompanyResponse;

class UserResponse
{
    /**
     * @see UserResource::toArray()
     */
    public static function resource(User $user, array $override = []): array
    {
        $response = $override + [
            'id' => $user->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'address' => $user->address,
            'country' => $user->country,
            'telephone' => $user->telephone,
            'locale' => $user->locale->lowerName(),
            'role' => $user->role->value,
            'avatar' => $user->avatar ? Storage::url($user->avatar) : null,
        ];

        if ($user->relationLoaded('company')) {
            $response['company'] = CompanyResponse::resource($user->company);
        }

        return $response;
    }
}
