<?php

namespace App\Http\Requests;

use App\Enums\Locale;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpsertCompanyRequest extends FormRequest
{
    public function rules(): array
    {
        // Compare own company id when updating own
        if (! ($companyId = $this->company?->id) && $this->isMethod('put')) {
            $companyId = $this->user()->company_id;
        }

        return [
            'name' => [
                'required',
                'string',
                'min:5',
                'max:255',
                Rule::unique('companies', 'name')->when($companyId, fn ($unique) => $unique->ignore($companyId)),
            ],
            'country' => ['nullable', 'string', 'size:2'],
            'locale' => ['sometimes', Rule::in(Locale::lowerNames())],
        ];
    }
}
