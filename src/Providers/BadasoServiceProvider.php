<?php

namespace Uasoft\Badaso\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Larapack\DoctrineSupport\DoctrineSupportServiceProvider;
use Arcanedev\LogViewer\LogViewerServiceProvider;
use Uasoft\Badaso\Badaso;
use Uasoft\Badaso\Commands\AdminCommand;
use Uasoft\Badaso\Commands\BDOSeed;
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
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'badaso');

        $this->publishes([
            __DIR__.'/../Config/badaso.php' => config_path('badaso.php'),
            __DIR__.'/../Config/log-viewer.php' => config_path('log-viewer.php'),
            __DIR__.'/../Seeder/Configurations' => database_path('seeds'),
            __DIR__.'/../Seeder/CRUDData' => database_path('seeds/CRUDData'),
            __DIR__.'/../resources/js/' => resource_path('js/badaso'),
            __DIR__.'/../Images/' => public_path(),
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/badaso'),
        ], 'Badaso');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(OrchestratorEventServiceProvider::class);
        $this->app->register(DoctrineSupportServiceProvider::class);
        $this->app->register(DropboxServiceProvider::class);
        $this->app->register(GoogleDriveServiceProvider::class);
        $this->app->register(LogViewerServiceProvider::class);
        $this->registerConsoleCommands();
    }

    /**
     * Register the commands accessible from the Console.
     */
    private function registerConsoleCommands()
    {
        $this->commands(AdminCommand::class);
        $this->commands(BDOSeed::class);
    }
}
