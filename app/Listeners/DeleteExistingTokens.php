<?php

namespace App\Listeners;

use App\Models\User;
use Laravel\Passport\Events\AccessTokenCreated;

class DeleteExistingTokens
{
    public function handle(AccessTokenCreated $event): void
    {
        User::findOrFail($event->userId)
            ->tokens()
            ->offset(1)
            ->limit(PHP_INT_MAX)
            ->delete();
    }
}
