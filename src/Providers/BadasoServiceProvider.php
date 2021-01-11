<?php

namespace Uasoft\Badaso\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Uasoft\Badaso\Badaso;
use Uasoft\Badaso\Commands\AdminCommand;
use Uasoft\Badaso\Facades\Badaso as FacadesBadaso;

class BadasoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('Badaso', FacadesBadaso::class);

        $this->app->singleton('badaso', function () {
            return new Badaso();
        });

        $this->loadMigrationsFrom(__DIR__.'/../Migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'Badaso');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');

        $this->publishes([
            __DIR__.'/../Config/badaso.php' => config_path('badaso.php'),
            __DIR__.'/../Seeder/' => database_path('seeds'),
            __DIR__.'/../resources/js/' => resource_path('js/badaso'),
            __DIR__.'/../Images/' => public_path(),
        ], 'Badaso');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConsoleCommands();
    }

    /**
     * Register the commands accessible from the Console.
     */
    private function registerConsoleCommands()
    {
        $this->commands(AdminCommand::class);
    }
}
