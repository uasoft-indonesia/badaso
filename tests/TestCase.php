<?php

namespace Uasoft\Badaso\Tests;

use Orchestra\Testbench\TestCase as TestbenchTestCase;
use Uasoft\Badaso\Providers\BadasoServiceProvider;
use Uasoft\Badaso\Providers\DropboxServiceProvider;
use Uasoft\Badaso\Providers\GoogleDriveServiceProvider;
use Uasoft\Badaso\Providers\OrchestratorEventServiceProvider;

class TestCase extends TestbenchTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
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
