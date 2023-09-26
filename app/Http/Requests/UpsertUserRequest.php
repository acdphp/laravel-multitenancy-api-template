<?php

namespace App\Http\Requests;

use App\Enums\Locale;
use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpsertUserRequest extends FormRequest
{
    public function rules(): array
    {
        return $this->baseRules() + [
            'role' => ['required', 'string', Rule::in($this->upsertableRoles())],
        ];
    }

    public function baseRules(): array
    {
        // Compare own id when updating self
        if (! ($userId = $this->user?->id) && $this->isMethod('put')) {
            $userId = $this->user()->id;
        }

        return [
            'first_name' => ['required', 'string', 'min:1', 'max:255'],
            'last_name' => ['required', 'string', 'min:1', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('companies')->when($userId, fn ($unique) => $unique->ignore($userId)),
            ],
            'password' => ['required', 'string', 'min:6', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:2'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'locale' => ['sometimes', Rule::in(Locale::lowerNames())],
        ];
    }

    protected function upsertableRoles(): array
    {
        return [Role::STAFF()];
    }
}
