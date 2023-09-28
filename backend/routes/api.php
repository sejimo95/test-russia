<?php

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\AmocrmController;
use App\Http\Controllers\Api\V1\Panel\ContactController;
use App\Http\Controllers\Api\V1\Panel\DealController;
use App\Http\Controllers\Api\V1\Panel\LogController;
use App\Http\Controllers\Api\V1\Webhooks\WebhookController;
use Illuminate\Support\Facades\Route;

// api v1
Route::prefix('v1')->group( function () {

// auth
    Route::prefix('auth')->group( function () {
//        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('is-login', [AuthController::class, 'isLogin']);

        Route::any('callback/amocrm', [AmocrmController::class, 'callbackAmocrm']);
    });

    // panel
    Route::prefix('panel')->middleware('auth:api')->group(function () {
        Route::get('deals', [DealController::class, 'index']);
        Route::post('contact', [ContactController::class, 'store']);
        Route::get('log', [LogController::class, 'index']);
    });

    // webhooks
    Route::post('webhooks', [WebhookController::class, 'index']);

});
