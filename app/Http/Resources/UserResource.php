<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin User
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'address' => $this->address,
            'country' => $this->country,
            'telephone' => $this->telephone,
            'locale' => $this->locale->lowerName(),
            'role' => $this->role->value,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'avatar' => $this->avatar ? Storage::url($this->avatar) : null,
        ];
    }
}
