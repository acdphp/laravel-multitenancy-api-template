<?php

use Illuminate\Support\Facades\Route;

/**
 * Unauthenticated endpoints
 */
Route::middleware([
    'throttle:60,1',
])->group(function () {
    Route::get('/health-check', static fn () => response()->json(['status' => 'ok']));
});

/**
 * Authenticated endpoints
 */
Route::middleware([
    'auth:api',
])->group(function () {
    Route::get('/health-check-auth', static fn () => response()->json(['status' => 'auth - ok']));

    Route::prefix('companies')
        ->group(base_path('routes/api/companies.php'));

    Route::prefix('users')
        ->group(base_path('routes/api/users.php'));
});

/**
 * Auth endpoints
 */
Route::prefix('auth')
    ->group(base_path('routes/api/auth.php'));
