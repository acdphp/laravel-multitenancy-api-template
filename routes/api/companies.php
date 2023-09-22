<?php

use App\Http\Controllers\CompanyController;

Route::controller(CompanyController::class)->group(function () {
    // Super admin only
    Route::get('/', 'index');
    Route::get('/{company}', 'show')->where('company', '[0-9]+');
    Route::post('/', 'store');
    Route::put('/{company}', 'update')->where('company', '[0-9]+');
    Route::delete('/{company}', 'destroy');
    Route::post('/{company}/logo', 'storeLogo')->where('company', '[0-9]+');
    Route::post('/{company}/restore', 'restore')->withTrashed();
    Route::post('/{company}/switch', 'switch');

    // Company admin
    Route::put('/mine', 'updateMine');
    Route::post('/mine/logo', 'storeLogoMine');

    // Global
    Route::get('/mine', 'mine');
});
