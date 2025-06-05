<?php

declare(strict_types=1);

namespace App\Atlas\Providers;

use App\Atlas\Guards\AtlasGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AtlasServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::extend('atlas', function (Application $app, string $name, array $config) {
            return new AtlasGuard(
                Auth::createUserProvider($config['provider']),
                $app->make('request')
            );
        });
    }
}
