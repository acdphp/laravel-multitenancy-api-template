<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    public function rules(): array
    {
        return (new UpsertUserRequest())->baseRules() + [
            'password' => ['required', 'string', 'min:6', 'max:50'],
        ] + $this->companyRules();
    }

    protected function companyRules(): array
    {
        $companyRules = (new UpsertCompanyRequest())->rules();
        $rules = [];

        foreach ($companyRules as $key => $companyRule) {
            $rules['company.'.$key] = $companyRule;
        }

        return $rules;
    }
}
