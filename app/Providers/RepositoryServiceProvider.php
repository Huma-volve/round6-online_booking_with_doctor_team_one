<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\RegisterRepositoryInterface;
use App\Repositories\RegisterRepository;
use App\Repositories\Interfaces\LoginRepositoryInterface;
use App\Repositories\LoginRepository;
use App\Repositories\Interfaces\SocialAuthInterface;
use App\Repositories\GoogleAuthRepository;
use App\Repositories\FacebookAuthRepository;
use App\Repositories\ForgetPasswordRepository;
use App\Repositories\Interfaces\ForgetPasswordInterface;




class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(RegisterRepositoryInterface::class, RegisterRepository::class);
        $this->app->bind(LoginRepositoryInterface::class, LoginRepository::class);
        $this->app->bind(SocialAuthInterface::class, GoogleAuthRepository::class);
        $this->app->bind(SocialAuthInterface::class, FacebookAuthRepository::class);
        $this->app->bind(ForgetPasswordInterface::class,ForgetPasswordRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
