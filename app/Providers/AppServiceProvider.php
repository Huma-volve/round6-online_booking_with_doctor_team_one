<?php

namespace App\Providers;

use App\Repositories\Interfaces\PageRepositoryInterface;
use App\Repositories\PageRepository;
use App\Repositories\Interfaces\FaqRepositoryInterface;
use App\Repositories\FaqRepository;

use App\Repositories\Interfaces\UserProfileRepositoryInterface;
use App\Repositories\Interfaces\UserAddressRepositoryInterface;
use App\Repositories\UserProfileRepository;
use App\Repositories\UserAddressRepository;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind PageRepositoryInterface to PageRepository implementation
        $this->app->bind(
            PageRepositoryInterface::class,
            PageRepository::class
        );

        // Bind FaqRepositoryInterface to FaqRepository implementation
        $this->app->bind(
            FaqRepositoryInterface::class,
            FaqRepository::class
        );
        $this->app->bind(
            UserProfileRepositoryInterface::class,
            UserProfileRepository::class
        );
        $this->app->bind(
            UserAddressRepositoryInterface::class,
            UserAddressRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    RateLimiter::for('search-history', function ($request) {
        return [
            // Limit: 30 requests per minute per user (or IP if not logged in)
            Limit::perMinute(30)->by(optional($request->user())->id ?: $request->ip()),
        ];
    });
    }
}
