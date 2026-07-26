<?php

use App\Http\Controllers\Api\V1\App\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


/*
|--------------------------------------------------------------------------
| Mobile App API Routes (Version 1)
| Base Path: /api/v1/app
|--------------------------------------------------------------------------
*/

Route::prefix('v1/app')->group(function () {

    // Public routes
    Route::post('/login', [AuthController::class, 'login']);

    // Protected routes requiring Sanctum token
    Route::middleware(['auth:sanctum'])->group(function () {

        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // Internal Staff Only Routes (access_type = 1)
        Route::middleware(['access.type:1'])->group(function () {
            // SR / Staff specific APIs
        });

        // External / Retailer Routes (access_type = 2)
        Route::middleware(['access.type:2'])->group(function () {
            // Merchant specific APIs
        });
    });
});
