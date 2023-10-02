<?php

use Laravel\Passport\Http\Controllers\AccessTokenController;
use App\Http\Controllers\AuthController;

Route::middleware([
    'throttle:5,1',
])->group(function () {
    Route::middleware(['tenancy.scope.bypass'])->post('/login', [AccessTokenController::class, 'issueToken']);
    Route::middleware(['tenancy.creating.bypass'])->post('/register', [AuthController::class, 'register']);
});

Route::middleware([
    'auth:api',
])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
