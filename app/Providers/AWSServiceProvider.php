<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AWSService;

class AWSServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        /*
        binds the class name AWSService to an instance of the 
        AWSService class. This means only one instance will be 
        created throughout the application.
        */
        $this->app->singleton(AWSService::class, function ($app) {
            return new AWSService();
        });

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Resolve the AWSService to trigger the setting of environment variables
        $this->app->make(AWSService::class);
    }
}
