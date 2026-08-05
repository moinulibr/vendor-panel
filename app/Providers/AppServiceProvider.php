<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use App\Models\Setting;
use App\Repositories\DeviceToken\Interface\UserDeviceTokenRepositoryInterface;
use App\Repositories\DeviceToken\UserDeviceTokenRepository;
use App\Repositories\Otp\Interface\OtpRepositoryInterface;
use App\Repositories\Otp\OtpRepository;
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
