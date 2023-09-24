<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Panel\ContactController;
use App\Http\Controllers\Api\V1\Panel\DealController;
use Illuminate\Support\Facades\Route;

// api v1
Route::prefix('v1')->group( function () {

// auth
    Route::prefix('auth')->group( function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('is-login', [AuthController::class, 'isLogin']);
    });

    // panel
    Route::prefix('panel')
//        ->middleware('auth:api')
        ->group( function () {
            Route::get('deals', [DealController::class, 'index']);
            Route::get('contact', [ContactController::class, 'store']);

        });

});
