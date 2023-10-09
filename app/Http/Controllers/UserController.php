<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadImageRequest;
use App\Http\Requests\UpsertUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return UserResource::collection(User::paginate());
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function store(UpsertUserRequest $request): UserResource
    {
        return new UserResource(User::create($request->validated())->fresh());
    }

    public function update(UpsertUserRequest $request, User $user): UserResource
    {
        $user->update($request->validated());

        return new UserResource($user->fresh());
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return $this->emptyResponse();
    }

    public function restore(User $user): UserResource
    {
        $user->restore();

        return new UserResource($user);
    }

    public function storeAvatar(
        UploadImageRequest $request,
        User $user,
        UserService $userService
    ): JsonResponse {
        $userService->uploadAvatar($user, $request->file('image'));

        return $this->emptyResponse();
    }

    public function mine(Request $request): UserResource
    {
        return $this->show(($request->user()));
    }

    public function updateMine(UpsertUserRequest $request): UserResource
    {
        return $this->update($request, $request->user());
    }

    public function storeAvatarMine(UploadImageRequest $request, UserService $userService): JsonResponse
    {
        return $this->storeAvatar($request, $request->user(), $userService);
    }
}
