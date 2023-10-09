<?php

use App\Http\Controllers\AuthController;
use Laravel\Passport\Http\Controllers\AccessTokenController;

Route::middleware([
    'throttle:5,1',
])->group(function () {
    Route::middleware(['tenancy.scope.bypass'])->post('/login', [AccessTokenController::class, 'issueToken']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware([
    'auth:api',
])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
