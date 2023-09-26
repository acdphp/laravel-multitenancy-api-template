<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\UserResource;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegistrationRequest $request): ?UserResource
    {
        if ($request->get('validation_mode')) {
            return null;
        }

        $company = Company::create($request->validated('company'));

        $user = new User(array_merge($request->safe()->except('company')));
        $user->company()->associate($company);
        $user->role = config('auth.default_registration_role');
        $user->save();

        return new UserResource($user);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        return $this->emptyResponse();
    }
}
