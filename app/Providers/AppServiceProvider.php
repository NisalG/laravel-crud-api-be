<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Post;
use App\Observers\PostObserver;
use Illuminate\Support\Facades\URL;
use App\Contracts\PostRepositoryInterface;
use App\Repositories\PostRepository;
use App\Contracts\CategoryRepositoryInterface;
use App\Repositories\CategoryRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Post::observe(PostObserver::class); // Register the Observer
        
        //Force HTTPS
        // if (env('APP_ENV') !== 'local') {
        //     URL::forceScheme('https');
        // }
    }
}
