<?php

namespace Tests\DataResponses;

use App\Models\Company;
use Illuminate\Support\Facades\Storage;

class CompanyResponse
{
    /**
     * @see CompanyResource::toArray()
     */
    public static function resource(Company $company, array $override = []): array
    {
        return $override + [
            'id' => $company->id,
            'name' => $company->name,
            'logo' => $company->logo ? Storage::url($company->logo) : null,
            'country' => $company->country,
            'locale' => $company->locale->lowerName(),
        ];
    }
}
