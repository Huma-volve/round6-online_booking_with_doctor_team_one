<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\RegisterRepositoryInterface;
use App\Repositories\RegisterRepository;
use App\Repositories\LoginRepositoryInterface;
use App\Repositories\LoginRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(RegisterRepositoryInterface::class,RegisterRepository::class);
        $this->app->bind(LoginRepositoryInterface::class,LoginRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
