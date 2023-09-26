<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    public function rules(): array
    {
        return (new UpsertUserRequest())->baseRules() + $this->companyRules();
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
