<?php

use App\Http\Controllers\UserController;

Route::controller(UserController::class)->group(function () {
    // Company admin
    Route::get('/', 'index');
    Route::get('/{user}', 'show')->where('user', '[0-9]+');
    Route::post('/', 'store');
    Route::put('/{user}', 'update')->where('user', '[0-9]+');
    Route::delete('/{user}', 'destroy');
    Route::post('/{user}/avatar', 'storeAvatar')->where('user', '[0-9]+');
    Route::post('/{user}/restore', 'restore')->withTrashed();

    // Global
    Route::get('/mine', 'mine');
    Route::put('/mine', 'updateMine');
    Route::post('/mine/avatar', 'storeAvatarMine');
});
