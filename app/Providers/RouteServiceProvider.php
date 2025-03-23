<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\Http\Controllers';
    public function boot()
    {
        parent::boot();
        RateLimiter::for(
            'api',
            function (Request $request) {
                return Limit::perMinute(120)->by(
                    $request->user()?->id ?: $request->ip(),
                );
            },
        );
    }
    public function map()
    {
        $this->mapAuthRoutes();
        $this->mapApiRoutes();
        $this->mapDashRoutes();
        $this->mapWebRoutes();
        $this->mapArtisanRoutes();
    }

    protected function mapAuthRoutes()
    {
        Route::middleware('api')
            ->prefix('auth')
            ->group(base_path('routes/auth.php'));
    }
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(
                base_path(
                    'routes/api.php'
                ),
            );
    }

    protected function mapDashRoutes()
    {
        Route::prefix('dash')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(
                base_path(
                    'routes/dash.php',
                ),
            );
    }
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(
                base_path(
                    'routes/web.php',
                ),
            );
    }
    protected function mapArtisanRoutes()
    {
        Route::prefix('artisan')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(
                base_path(
                    'routes/artisan.php',
                ),
            );
    }
}
