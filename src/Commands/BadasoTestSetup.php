<?php

namespace Uasoft\Badaso\Commands;

use Illuminate\Console\Command;

class BadasoTestSetup extends Command
{
    public static $PHPUNIT_XML_PATH = 'phpunit.xml';
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'badaso-test:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish xml directory to file ./project/phpunit.xml';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $phpunit_xml_path = base_path(self::$PHPUNIT_XML_PATH);

        $phpunit_xml_content = <<<'XML'
        <?xml version="1.0" encoding="UTF-8"?>
        <phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
                 xsi:noNamespaceSchemaLocation="./vendor/phpunit/phpunit/phpunit.xsd" 
                 bootstrap="vendor/autoload.php" 
                 colors="true"
                 executionOrder="depends,defects"
                 failOnRisky="true"
                 failOnWarning="true"
                 failOnEmptyTestSuite="true"
                 beStrictAboutOutputDuringTests="true">
            <testsuites>
                <testsuite name="Unit">
                    <directory suffix="Test.php">./tests/Unit</directory>
                    <directory suffix="Test.php">./vendor/badaso/core/tests/Unit</directory>
                </testsuite>
                <testsuite name="Feature">
                    <directory suffix="Test.php">./tests/Feature</directory>
                    <directory suffix="Test.php">./vendor/badaso/core/tests/Feature</directory>
                </testsuite>
            </testsuites>
            <source>
                <include>
                    <!-- Uncomment line below to include app directory -->
                    <!-- <directory suffix=".php">./app</directory> -->
                    <directory suffix=".php">./vendor/badaso/core/src/Commands</directory>
                    <directory suffix=".php">./vendor/badaso/core/src/Controllers</directory>
                    <directory suffix=".php">./vendor/badaso/core/src/ContentManager</directory>
                    <directory suffix=".php">./vendor/badaso/core/src/Events</directory>
                    <directory suffix=".php">./vendor/badaso/core/src/Exceptions</directory>
                    <directory suffix=".php">./vendor/badaso/core/src/Helpers</directory>
                    <directory suffix=".php">./vendor/badaso/core/src/Listeners</directory>
                    <directory suffix=".php">./vendor/badaso/core/src/Mail</directory>
                    <directory suffix=".php">./vendor/badaso/core/src/Middleware</directory>
                    <directory suffix=".php">./vendor/badaso/core/src/Models</directory>
                    <directory suffix=".php">./vendor/badaso/core/src/OrchestratorHandlers</directory>
                    <directory suffix=".php">./vendor/badaso/core/src/Providers</directory>
                    <directory suffix=".php">./vendor/badaso/core/src/Routes</directory>
                    <directory suffix=".php">./vendor/badaso/core/src/Traits</directory>
                    <directory suffix=".php">./vendor/badaso/core/src/Widgets</directory>
                    <file>./vendor/badaso/core/src/Badaso.php</file>
                </include>
                <exclude>
                    <directory>./vendor/badaso/core/tests</directory>
                    <directory>./tests</directory>
                </exclude>
            </source>
            <coverage>
                <report>
                    <clover outputFile="clover.xml"/>
                    <html outputDirectory="coverage-html" lowUpperBound="50" highLowerBound="80"/>
                    <text outputFile="coverage.txt" showUncoveredFiles="false" showOnlySummary="true"/>
                </report>
            </coverage>
            <php>
                <server name="APP_ENV" value="testing"/>
                <server name="BCRYPT_ROUNDS" value="4"/>
                <server name="CACHE_DRIVER" value="array"/>
                <!-- <server name="DB_CONNECTION" value="sqlite"/> -->
                <!-- <server name="DB_DATABASE" value=":memory:"/> -->
                <server name="MAIL_MAILER" value="array"/>
                <server name="QUEUE_CONNECTION" value="sync"/>
                <server name="SESSION_DRIVER" value="array"/>
                <server name="TELESCOPE_ENABLED" value="false"/>
            </php>
        </phpunit>
        XML;

        file_put_contents($phpunit_xml_path, $phpunit_xml_content);
        
        $this->info('PHPUnit configuration file has been created successfully!');
        $this->line('');
        $this->line('To run tests with coverage, use one of these commands:');
        $this->line('  ./vendor/bin/phpunit --coverage-html coverage-html');
        $this->line('  ./vendor/bin/phpunit --coverage-clover clover.xml');
        $this->line('  ./vendor/bin/phpunit --coverage-text');
        
        return 0;
    }
}