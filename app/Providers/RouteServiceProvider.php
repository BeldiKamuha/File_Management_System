<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/home';

    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            // API Routes
            Route::prefix('api')
                ->middleware('api')
                // ->namespace($this->namespace) // Commented out for FQCN usage
                ->group(base_path('routes/api.php'));

            // Web Routes
            Route::middleware('web')
                // ->namespace($this->namespace) // Commented out for FQCN usage
                ->group(base_path('routes/web.php'));
        });
    }

    protected function configureRateLimiting()
    {
        // Define rate limiters if needed
    }
}