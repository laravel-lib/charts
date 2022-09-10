<?php

declare(strict_types=1);

namespace LaravelLib\Charts;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\Registrar as RouteRegistrar;

class ChartsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/charts.php', 'charts');

        $this->app->singleton(Registrar::class, fn (Application $app) => new Registrar(
            $app,
            $app->make(Repository::class),
            $app->make(RouteRegistrar::class)
        ));
    }

    public function boot(Repository $config, Registrar $charts): void
    {
        $this->publishes([__DIR__ . '/Config/charts.php' => config_path('charts.php')], 'charts');

        $routeNamePrefix = $config->get('charts.global_route_name_prefix');
        Blade::directive('chart', function ($expression) use ($routeNamePrefix) {
            return "<?php echo route('{$routeNamePrefix}.'.{$expression}); ?>";
        });

        if ($this->app->runningInConsole()) {
            $this->commands([Commands\CreateChart::class]);
        }
    }
}
