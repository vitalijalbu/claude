<?php

declare(strict_types=1);

namespace App\Providers;

use App\Helpers\SecretsHelper;
use Illuminate\Support\ServiceProvider;

class SecretsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => base_path('config/secrets.php'),
            ], 'config');
        }

        // Load Secrets
        if (config('secrets.enable-secrets')) {
            $secretsManager = new SecretsHelper;
            $secretsManager->loadSecrets();
        }
    }

    /**
     * Register the application services.
     */
    public function register() {}
}
