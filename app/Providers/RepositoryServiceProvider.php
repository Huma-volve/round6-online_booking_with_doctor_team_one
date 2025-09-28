<?php

namespace App\Providers;

use App\Repositories\Contracts\DoctorRepositoryInterface;
use App\Repositories\Contracts\HistoryRepositoryInterface;
use App\Repositories\Contracts\MajorRepositoryInterface;
use App\Repositories\Eloquent\EloquentDoctorRepository;
use App\Repositories\Eloquent\EloquentHistoryRepository;
use App\Repositories\Eloquent\EloquentMajorRepository;
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
