<?php

namespace App\Listeners;

use App\Models\User;
use Laravel\Passport\Events\AccessTokenCreated;
use Laravel\Passport\Token;

class RevokeExistingTokens
{
    public function handle(AccessTokenCreated $event): void
    {
        $user = User::find($event->userId);

        $user
            ->tokens()
            ->offset(1)
            ->limit(PHP_INT_MAX)
            ->get()
            ->map(fn (Token $token) => $token->revoke());
    }
}
