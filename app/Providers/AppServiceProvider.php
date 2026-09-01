<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use App\Models\Setting;
use App\Repositories\Cart\CartRepository;
use App\Repositories\Cart\Interface\CartRepositoryInterface;
use App\Repositories\Coupon\CouponRepository;
use App\Repositories\Coupon\Interface\CouponRepositoryInterface;
use App\Repositories\DeviceToken\Interface\UserDeviceTokenRepositoryInterface;
use App\Repositories\DeviceToken\UserDeviceTokenRepository;
use App\Repositories\Favorite\FavoriteRepository;
use App\Repositories\Favorite\Interface\FavoriteRepositoryInterface;
use App\Repositories\Notification\Interface\NotificationRepositoryInterface;
use App\Repositories\Notification\NotificationRepository;
use App\Repositories\Otp\Interface\OtpRepositoryInterface;
use App\Repositories\Otp\OtpRepository;
use App\Repositories\Product\Interface\ProductRepositoryInterface;
use App\Repositories\Product\ProductRepository;
use App\Repositories\User\Interface\UserRepositoryInterface;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Cache;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
        $this->app->bind(
            OtpRepositoryInterface::class,
            OtpRepository::class);

        $this->app->bind(
            UserDeviceTokenRepositoryInterface::class,
            UserDeviceTokenRepository::class
        );

        $this->app->bind(
            NotificationRepositoryInterface::class,
            NotificationRepository::class
        );

        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class
        );

        $this->app->bind(CouponRepositoryInterface::class, CouponRepository::class);
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
        $this->app->bind(FavoriteRepositoryInterface::class, FavoriteRepository::class);
        
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        Cache::rememberForever('info', function() {
            return Setting::first()->toArray();
        });
    }
}
