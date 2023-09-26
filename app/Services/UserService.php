<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserService
{
    public function uploadAvatar(User $user, UploadedFile $file): void
    {
        // Delete existing avatar
        if ($old = $user->getRawOriginal('avatar')) {
            Storage::delete($old);
        }

        $key = Str::uuid();
        $path = $file->storeAs(
            'user_avatar',
            "{$user->id}.{$key}.{$file->extension()}"
        );

        $user->avatar = $path;
        $user->save();
    }
}
