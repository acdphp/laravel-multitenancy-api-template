<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\UpsertCompanyRequest;
use App\Http\Requests\UploadImageRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CompanyController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return CompanyResource::collection(Company::paginate());
    }

    public function show(Company $company): CompanyResource
    {
        return new CompanyResource($company);
    }

    public function store(UpsertCompanyRequest $request): CompanyResource
    {
        return new CompanyResource(Company::create($request->validated())->fresh());
    }

    public function update(UpsertCompanyRequest $request, Company $company): CompanyResource
    {
        $company->update($request->validated());

        return new CompanyResource($company);
    }

    public function destroy(Company $company): JsonResponse
    {
        $company->delete();

        return $this->emptyResponse();
    }

    public function restore(Company $company): CompanyResource
    {
        $company->restore();

        return new CompanyResource($company);
    }

    public function storeLogo(
        UploadImageRequest $request,
        Company $company,
        CompanyService $companyService
    ): JsonResponse {
        $companyService->uploadLogo($company, $request->file('image'));

        return $this->emptyResponse();
    }

    public function mine(Request $request): CompanyResource
    {
        return $this->show(($request->user()->company));
    }

    public function updateMine(UpsertCompanyRequest $request): CompanyResource
    {
        return $this->update($request, $request->user()->company);
    }

    public function storeLogoMine(UploadImageRequest $request, CompanyService $companyService): JsonResponse
    {
        return $this->storeLogo($request, $request->user()->company, $companyService);
    }

    public function switch(Request $request, Company $company): CompanyResource
    {
        $request->user()->company()->associate($company)->save();

        return new CompanyResource($company);
    }
}
