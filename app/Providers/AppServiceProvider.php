<?php

namespace App\Providers;

use App\Repositories\Interfaces\PageRepositoryInterface;
use App\Repositories\PageRepository;
use App\Repositories\Interfaces\FaqRepositoryInterface;
use App\Repositories\FaqRepository;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
