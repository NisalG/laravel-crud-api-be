<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\S3Service;

class S3ServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        /*
        binds the class name S3Service to an instance of the 
        S3Service class. This means only one instance will be 
        created throughout the application.
        */
        $this->app->singleton(S3Service::class, function ($app) {
            return new S3Service();
        });

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
