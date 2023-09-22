<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CompanyService
{
    public function uploadLogo(Company $company, UploadedFile $file): void
    {
        // Delete existing logo
        if ($oldLogo = $company->getRawOriginal('logo')) {
            Storage::delete($oldLogo);
        }

        $key = Str::uuid();
        $path = $file->storeAs(
            'company_logos',
            "{$company->id}.{$key}.{$file->extension()}"
        );

        $company->logo = $path;
        $company->save();
    }
}
