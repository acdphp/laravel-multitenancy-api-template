<?php

use Illuminate\Support\Facades\Route;

/**
 * Unauthenticated endpoints
 */
Route::middleware([
    'throttle:60,1',
])->group(function () {
    // Health check
    Route::get('/health-check', static fn () => response()->json(['status' => 'ok']));
});

/**
 * Authenticated endpoints
 */
Route::middleware([
    'auth:api',
])->group(function () {
    // Health check
    Route::get('/health-check-auth', static fn () => response()->json(['status' => 'auth - ok']));
});
