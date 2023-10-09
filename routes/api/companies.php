<?php

use App\Http\Controllers\CompanyController;

Route::controller(CompanyController::class)->group(function () {
    Route::middleware(['role:super_admin'])->group(function () {
        Route::get('/', 'index');
        Route::get('/{company}', 'show')->where('company', '[0-9]+');
        Route::post('/', 'store');
        Route::put('/{company}', 'update')->where('company', '[0-9]+');
        Route::delete('/{company}', 'destroy');
        Route::post('/{company}/logo', 'storeLogo')->where('company', '[0-9]+');
        Route::post('/{company}/restore', 'restore')->withTrashed();
        Route::post('/{company}/switch', 'switch');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::put('/mine', 'updateMine');
        Route::post('/mine/logo', 'storeLogoMine');
    });

    Route::get('/mine', 'mine');
});
