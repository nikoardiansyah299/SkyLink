<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register admin middleware alias if router available
        if ($this->app->has('router')) {
            $router = $this->app->get('router');
            // aliasMiddleware method exists on Illuminate\Routing\Router
            if (method_exists($router, 'aliasMiddleware')) {
                $router->aliasMiddleware('admin', \App\Http\Middleware\EnsureAdmin::class);
            }
        }
    }
}
