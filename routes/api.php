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

    // Public API Endpoint
    Route::post('/login', [AuthController::class, 'login']);

    // Protected API Endpoints (Requires Sanctum Token & SR/Merchant Middleware Check)
    Route::middleware(['auth:sanctum', 'app.user:sr,merchant'])->group(function () {

        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);

        // Next: Product, Category, and Order endpoints will be attached here
    });
});