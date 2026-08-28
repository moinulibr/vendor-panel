<?php

use App\Http\Controllers\Api\V1\App\AuthController;
use App\Http\Controllers\Api\V1\App\CartController;
use App\Http\Controllers\Api\V1\App\FcmNotificationController;
use App\Http\Controllers\Api\V1\App\CategoryController;
use App\Http\Controllers\Api\V1\App\FavoriteController;
use App\Http\Controllers\Api\V1\App\ProductController;
use App\Http\Controllers\Api\V1\App\SettingsController;
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

        // Vendors & Retailers (For SR Shop selection & Filters)
        Route::get('/vendors', [AuthController::class, 'vendors']);
        Route::get('/retailers', [AuthController::class, 'retailers']);
        //shipping address
        Route::post('/create-retailer-shipping-address', [AuthController::class, 'createRetailerShippingAddress']);
        Route::post('/update-retailer-shipping-address/{shippingAddressId}', [AuthController::class, 'updateRetailerShippingAddress']);

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
            Route::get('/get-retailer-shipping-addresses/{retailer_id}', [AuthController::class, 'getRetailerShippingAddresses']);
            Route::delete('/delete-retailer-shipping-address/{shippingAddressId}', [AuthController::class, 'deleteRetailerShippingAddress']);
        });

        //Category
        Route::get('/get-categories', [CategoryController::class, 'getCategories']);

        // Product Routes
        Route::controller(ProductController::class)->group(function () {
            // Products Listing, Details & Creation
            Route::get('products', 'index');
            Route::get('products/{identifier}', 'show');

            Route::get('check-stock-quantity/{identifier}', 'checkStockQuantity');
            Route::post('products/search-by-image', 'searchByImage');

            // Categories & Brands
            Route::get('categories', 'categories');
            Route::get('brands', 'brands');
        });

        // Cart Routes
        Route::get('/cart', [CartController::class, 'index']);
        Route::post('/cart', [CartController::class, 'store']);
        Route::put('/cart-update/{cartItemId}', [CartController::class, 'update']);
        Route::delete('/cart/clear', [CartController::class, 'clear']);
        Route::delete('/cart-item-remove/{cartItemId}', [CartController::class, 'removeCart']);

        // Coupon Routes
        Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon']);
        Route::delete('/cart/remove-coupon', [CartController::class, 'removeCoupon']);

        // Favorites Routes
        Route::get('/favorites', [FavoriteController::class, 'index']);
        Route::post('/favorites/toggle', [FavoriteController::class, 'toggle']);

        //setting static data [temporary]
        Route::get('/get-setting-data', [SettingsController::class, 'getSettingData']);
        Route::get('/delivery-charge-info',[SettingsController::class, 'getDeliveryChargeInfo']);
        Route::get('/sr-order-create', [SettingsController::class, 'srOrderCreate']);
        Route::get('/sr-cart-create', [SettingsController::class, 'srCartCreate']);
        Route::get('/sr-favorite-create', [SettingsController::class, 'srFavoriteCreate']);

        Route::get('/order-edit-info', [SettingsController::class, 'srOrderEditInfo']);
    });
});
