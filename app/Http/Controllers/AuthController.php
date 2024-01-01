<?php

namespace App\Http\Controllers;

use Acdphp\Multitenancy\Facades\Tenancy;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class AuthController extends Controller
{
    /**
     * @throws Throwable
     */
    public function register(RegistrationRequest $request): ?UserResource
    {
        if ($request->get('validation_mode')) {
            return null;
        }

        DB::beginTransaction();
        $company = Company::create($request->validated('company'));
        Tenancy::setTenantIdResolver(static fn () => $company->id);

        $user = User::create(array_merge($request->safe()->except('company')) + [
            'role' => config('auth.default_registration_role'),
        ]);
        DB::commit();

        return new UserResource($user->load('company'));
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        return $this->emptyResponse();
    }
}
