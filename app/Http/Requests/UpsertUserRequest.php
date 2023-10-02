<?php

namespace App\Http\Requests;

use App\Enums\Locale;
use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpsertUserRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = $this->baseRules();

        // Only admin or above can update role
        if ($this->user()->role->isAdmin()) {
            $rules += [
                'role' => ['required', 'integer', Rule::in($this->upsertableRoles())],
            ];
        }

        return $rules;
    }

    public function baseRules(): array
    {
        return [
            'firstname' => ['required', 'string', 'min:1', 'max:255'],
            'lastname' => ['required', 'string', 'min:1', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
                    ->when($user = $this->selfUpdateUser(), fn ($unique) => $unique->ignore($user->id)),
            ],
            'address' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:2'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'locale' => ['sometimes', Rule::in(Locale::lowerNames())],
        ];
    }

    protected function selfUpdateUser(): User|null
    {
        if (
            empty($this->user) &&
            $this->isMethod('put') &&
            auth()->hasUser()
        ) {
            /**
             * @var User $user
             */
            $user = auth()->user();
            return $user;
        }

        return null;
    }

    protected function upsertableRoles(): array
    {
        $roles = [Role::STAFF()];

        // Include self role for updating self
        if ($user = $this->selfUpdateUser()) {
            $roles[] = $user->role->value;
        }

        return $roles;
    }
}
