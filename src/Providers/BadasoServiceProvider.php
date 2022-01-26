<?php

namespace Uasoft\Badaso\Providers;

use Arcanedev\LogViewer\LogViewerServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use L5Swagger\L5SwaggerServiceProvider;
use Larapack\DoctrineSupport\DoctrineSupportServiceProvider;
use Uasoft\Badaso\Badaso;
use Uasoft\Badaso\Commands\AdminCommand;
use Uasoft\Badaso\Commands\BackupCommand;
use Uasoft\Badaso\Commands\BadasoFirebaseCommand;
use Uasoft\Badaso\Commands\BadasoSetup;
use Uasoft\Badaso\Commands\BadasoTestSetup;
use Uasoft\Badaso\Commands\GenerateSeederCommand;
use Uasoft\Badaso\Facades\Badaso as FacadesBadaso;
use Uasoft\Badaso\Middleware\CheckForMaintenanceMode;
use Uasoft\Badaso\Middleware\GenerateForSwagger;

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

        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('web', CheckForMaintenanceMode::class);
        $router->pushMiddlewareToGroup('web', GenerateForSwagger::class);

        $this->app->singleton('badaso', function () {
            return new Badaso();
        });

        $this->loadMigrationsFrom(__DIR__.'/../Migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'badaso');
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'badaso');

        $this->publishes([
            __DIR__.'/../Config/badaso.php' => config_path('badaso.php'),
            __DIR__.'/../Config/log-viewer.php' => config_path('log-viewer.php'),
            __DIR__.'/../Config/backup.php' => config_path('backup.php'),
            __DIR__.'/../Seeder/Configurations' => database_path('seeders/Badaso'),
            __DIR__.'/../Seeder/CRUD' => database_path('seeders/Badaso/CRUD'),
            __DIR__.'/../Images/badaso-images/' => storage_path('app/public/photos/shares'),
            __DIR__.'/../Seeder/ManualGenerate' => database_path('seeders/Badaso/ManualGenerate'),
            __DIR__.'/../resources/customization/' => resource_path('js/badaso'),
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/badaso'),
            __DIR__.'/../Swagger/swagger_models' => app_path('Http/Swagger/swagger_models'),
            __DIR__.'/../Config/lfm.php' => config_path('lfm.php'),
            __DIR__.'/../Config/firebase.php' => config_path('firebase.php'),
            __DIR__.'/../Config/l5-swagger.php' => config_path('l5-swagger.php'),
            __DIR__.'/../resources/views/vendor' => resource_path('views/vendor'),
            __DIR__.'/../Config/badaso-hidden-tables.php' => config_path('badaso-hidden-tables.php'),
            __DIR__.'/../Config/badaso-watch-tables.php' => config_path('badaso-watch-tables.php'),
            __DIR__.'/../Config/analytics.php' => config_path('analytics.php'),
            __DIR__.'/../Config/octane.php' => config_path('octane.php'),
        ], 'Badaso');

        $this->publishes([
            __DIR__.'/../Config/badaso.php' => config_path('badaso.php'),
            __DIR__.'/../Config/log-viewer.php' => config_path('log-viewer.php'),
            __DIR__.'/../Config/backup.php' => config_path('backup.php'),
            __DIR__.'/../Config/lfm.php' => config_path('lfm.php'),
            __DIR__.'/../Config/firebase.php' => config_path('firebase.php'),
            __DIR__.'/../Config/l5-swagger.php' => config_path('l5-swagger.php'),
            __DIR__.'/../Config/badaso-hidden-tables.php' => config_path('badaso-hidden-tables.php'),
            __DIR__.'/../Config/badaso-watch-tables.php' => config_path('badaso-watch-tables.php'),
            __DIR__.'/../Config/analytics.php' => config_path('analytics.php'),
        ], 'BadasoConfig');

        $this->publishes([
            __DIR__.'/../Seeder/Configurations' => database_path('seeders/Badaso'),
            __DIR__.'/../Seeder/CRUD' => database_path('seeders/Badaso/CRUD'),
            __DIR__.'/../Seeder/ManualGenerate' => database_path('seeders/Badaso/ManualGenerate'),
        ], 'BadasoSeeder');

        $this->publishes([
            __DIR__.'/../resources/customization/' => resource_path('js/badaso'),
            __DIR__.'/../Images/badaso-images/' => storage_path('app/public/photos/shares'),
            __DIR__.'/../resources/views/vendor' => resource_path('views/vendor'),
            // __DIR__.'/../resources/lang' => resource_path('lang/vendor/badaso'),
        ], 'BadasoResource');
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
        $this->app->register(L5SwaggerServiceProvider::class);
        $this->registerConsoleCommands();
    }

    /**
     * Register the commands accessible from the Console.
     */
    private function registerConsoleCommands()
    {
        $this->commands(BadasoSetup::class);
        $this->commands(AdminCommand::class);
        $this->commands(BackupCommand::class);
        $this->commands(GenerateSeederCommand::class);
        $this->commands(BadasoFirebaseCommand::class);
        $this->commands(BadasoTestSetup::class);
    }
}
