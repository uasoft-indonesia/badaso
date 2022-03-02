<?php

namespace Uasoft\Badaso\Tests;

use Uasoft\Badaso\Providers\BadasoServiceProvider;
use Uasoft\Badaso\Providers\DropboxServiceProvider;
use Uasoft\Badaso\Providers\GoogleDriveServiceProvider;
use Uasoft\Badaso\Providers\OrchestratorEventServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup

        $this->artisan("db:wipe")->run();
        $this->artisan("migrate")->run();
    }

    protected function getPackageProviders($app)
    {
        return [
            BadasoServiceProvider::class,
            DropboxServiceProvider::class,
            GoogleDriveServiceProvider::class,
            OrchestratorEventServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
