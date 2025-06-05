<?php

declare(strict_types=1);

namespace App\Providers;

use App\Guards\AtlasGuard;
use Carbon\CarbonImmutable;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(Generator::class, function () {
            $faker = Factory::create();
            $faker->addProvider(new CustomProvider($faker));

            return $faker;
        });

        $this->app->bind(Generator::class . ':' . config('app.faker_locale'), Generator::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        ResourceCollection::withoutWrapping();

        Date::use(CarbonImmutable::class);

        // Model::preventLazyLoading(! app()->isProduction());

        // DB::prohibitDestructiveCommands(
        //     app()->isProduction()
        // );

        // Model::shouldBeStrict();

        if (app()->isProduction()) {
            URL::forceScheme('https');
        }

        Auth::extend('atlas', function (Application $app, string $name, array $config) {
            return new AtlasGuard(
                Auth::createUserProvider($config['provider']),
                $app->make('request'),
            );
        });

        Scramble::registerApi('v1', [
            'info' => [
                'version' => 'v1',
                'description' => 'This is the Tech Analysis API description',
                'api_path' => 'api/v1/',
            ],
            'servers' => [
                'Local' => '/api',
                'Demo' => '',
                'Test' => '',
                'Prod' => '',
            ],
        ])
            ->routes(function (Route $route) {
                return Str::startsWith($route->uri, 'api/v1/');
            })->afterOpenApiGenerated(function (OpenApi $openApi) {
                $openApi->secure(
                    SecurityScheme::http('bearer')
                );
            });

        Gate::define('viewApiDocs', function ($user = null) {
            return true;
        });

        // Builder::macro('dynamicFilter', function (Request $request, array $filterable) {
        //     /** @var Builder $builder */
        //     $builder = $this;

        //     return (new DynamicQueryFilter($builder, $request, $filterable))->apply();
        // });

        // Builder::macro('dynamicSort', function (Request $request, array $filterable) {
        //     /** @var Builder $builder */
        //     $builder = $this;

        //     return (new DynamicQueryFilter($builder, $request, $filterable))->applySort(
        //         $request->input('sort_key', 'created_at'),
        //         $request->input('sort_direction', 'desc'),
        //     );
        // });

        // Builder::macro('dynamicFilterAndSort', function (Request $request, array $filterable) {
        //     /** @var Builder $builder */
        //     $builder = $this;

        //     return (new DynamicQueryFilter($builder, $request, $filterable))->applyWithSort();
        // });

    }
}
