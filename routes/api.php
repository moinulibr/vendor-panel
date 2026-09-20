<?php

use App\Http\Controllers\Api\V1\App\AuthController;
use App\Http\Controllers\Api\V1\App\CartController;
use App\Http\Controllers\Api\V1\App\FcmNotificationController;
use App\Http\Controllers\Api\V1\App\FavoriteController;
use App\Http\Controllers\Api\V1\App\NotificationController;
use App\Http\Controllers\Api\V1\App\ProductController;
use App\Http\Controllers\Api\V1\App\SettingsController;
use App\Http\Controllers\Api\V1\App\TestingFeaturesController;
use App\Http\Controllers\Api\V1\App\UserDeviceAndFcmTokenController;
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
        Route::get('/vendors', [AuthController::class, 'getVendors']);
        Route::get('/users-list', [AuthController::class, 'getUsersList']);
        //shipping address
        Route::get('/get-shipping-addresses', [AuthController::class, 'getShippingAddresses']);
        Route::post('/create-shipping-address', [AuthController::class, 'createShippingAddress']);
        Route::post('/update-shipping-address/{shippingAddressId}', [AuthController::class, 'updateShippingAddress']);
        Route::post('/switch-user-type', [AuthController::class, 'switchingUserType']);
        Route::delete('/delete-shipping-address/{shippingAddressId}', [AuthController::class, 'deleteShippingAddress']);

        //fcm token api
        //Route::post('store-fcm-toke', [FcmNotificationController::class, 'storeFcmToken']);
        //Route::post('remove-fcm-token', [FcmNotificationController::class, 'removeFcmToken']);
        Route::post('store-fcm-token', [UserDeviceAndFcmTokenController::class, 'storeFcmToken']);
        Route::post('remove-fcm-token', [UserDeviceAndFcmTokenController::class, 'removeFcmToken']);
        //test notification
        Route::get('/send-notification', [TestingFeaturesController::class, 'testingNotification']);

        //real file notification route here
        Route::prefix('notifications')->controller(NotificationController::class)->group(function () {
            Route::get('/', 'index');                      // GET  /api/v1/app/notifications
            Route::get('read/{id}', 'markAsRead');       // GET /api/v1/app/notifications/{id}/read
            Route::get('/read-all', 'markAllAsRead');     // GET /api/v1/app/notifications/read-all
            Route::delete('/{id}', 'deleteSingleNotification'); // DELETE /api/v1/app/notifications/{id}
        });
        
        // Internal Staff Only Routes (access_type = 1) - SR only
        Route::middleware(['access.type:1'])->group(function () {
            // SR / Staff specific APIs
        });

        // External / Dealer or Exclusive Client (user) Routes (access_type = 2)
        Route::middleware(['access.type:2'])->group(function () {
            // specific APIs
        });

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
        Route::get('/feature/delivery',[SettingsController::class, 'featureDelivery']);
        Route::get('/features/status', [SettingsController::class, 'getFeaturesStatus']);
        Route::get('/features/coupon-and-discount', [SettingsController::class, 'featureCouponAndDiscount']);

    });
});
