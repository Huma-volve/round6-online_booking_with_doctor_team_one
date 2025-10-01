<?php

namespace App\Providers;

use App\Repositories\Contracts\DoctorRepositoryInterface;
use App\Repositories\Contracts\HistoryRepositoryInterface;
use App\Repositories\Contracts\MajorRepositoryInterface;
use App\Repositories\Eloquent\EloquentDoctorRepository;
use App\Repositories\Eloquent\EloquentHistoryRepository;
use App\Repositories\Eloquent\EloquentMajorRepository;
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

        $this->app->bind(DoctorRepositoryInterface::class, EloquentDoctorRepository::class);
        $this->app->bind(MajorRepositoryInterface::class, EloquentMajorRepository::class);
        $this->app->bind(HistoryRepositoryInterface::class, EloquentHistoryRepository::class);

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
