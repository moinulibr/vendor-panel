<?php

use App\Http\Controllers\Api\V1\App\AuthController;
use App\Http\Controllers\Api\V1\App\FcmNotificationController;
use App\Http\Controllers\Api\V1\App\CategoryController;
use App\Http\Controllers\Api\V1\App\ProductController;
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

    // Guest Routes - Public routes
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    // Protected routes requiring Sanctum token
    Route::middleware(['auth:sanctum'])->group(function () {

        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
        Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);
        Route::post('/update-profile', [AuthController::class, 'updateProfile']);

        Route::post('/update-profile-picture', [AuthController::class, 'profilePictureUpdate']);

        //fcm token api
        Route::post('store-fcm-token', [FcmNotificationController::class, 'storeFcmToken']);
        Route::post('remove-fcm-token', [FcmNotificationController::class, 'removeFcmToken']);
        
        //real file notification route here

        // Internal Staff Only Routes (access_type = 1) - SR only
        Route::middleware(['access.type:1'])->group(function () {
            // SR / Staff specific APIs
        });

        // External / Retailer Routes (access_type = 2) = Retailer only
        Route::middleware(['access.type:2'])->group(function () {
            // Retailer specific APIs
            Route::post('/create-retailer-shipping-address', [AuthController::class, 'createRetailerShippingAddress']);
            Route::get('/get-retailer-shipping-addresses/{retailer_id}', [AuthController::class, 'getRetailerShippingAddresses']);
            
        });

        //Category
        Route::get('/get-categories', [CategoryController::class, 'getCategories']);

        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/products/{id_or_slug}', [ProductController::class, 'getProductDetails']);
        Route::get('/categories', [ProductController::class, 'categories']);
        Route::get('/brands', [ProductController::class, 'brands']);

    });
});
