<?php

namespace Uasoft\Badaso\Commands;

use DOMDocument;
use Illuminate\Console\Command;

class BadasoTestSetup extends Command
{
    public static $PHPUNIT_XML_PATH = 'phpunit.xml';
    public static $BADASO_UNIT_TEST_PATHS = [
        './packages/badaso/core/tests/Unit',
    ];
    public static $BADASO_FEATURE_TEST_PATHS = [
        './packages/badaso/core/tests/Feature',
    ];

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
        $data = file_get_contents($phpunit_xml_path);

        $document = new DOMDocument();
        $document->loadXML($data);

        $test_suite_features = $document
            ->getElementsByTagName('testsuite');

        foreach ($test_suite_features as $index_test_suite_feature => $test_suite_feature) {
            $attribute_name = $test_suite_feature->getAttribute('name');
            switch ($attribute_name) {
                case 'Unit':
                    $directories = $test_suite_feature->getElementsByTagName('directory');

                    // to input xml path badaso test
                    $badaso_test_paths = self::$BADASO_UNIT_TEST_PATHS;

                    // gets all path now in xml phpunit.xml
                    $now_test_paths = [];
                    foreach ($directories as $_ => $directory) {
                        $now_test_paths[] = $directory->nodeValue;
                    }

                    // check and add directory test
                    foreach ($badaso_test_paths as $_ => $badaso_test_path) {
                        if (! in_array($badaso_test_path, $now_test_paths)) {
                            $new_directory = $document->createElement('directory', $badaso_test_path);
                            $new_directory->setAttribute('suffix', 'Test.php');

                            $test_suite_features->item($index_test_suite_feature)->appendChild($new_directory);
                        }
                    }
                    break;
                case 'Feature':
                    $directories = $test_suite_feature->getElementsByTagName('directory');

                    // to input xml path badaso test
                    $badaso_test_paths = self::$BADASO_FEATURE_TEST_PATHS;

                    // gets all path now in xml phpunit.xml
                    $now_test_paths = [];
                    foreach ($directories as $_ => $directory) {
                        $now_test_paths[] = $directory->nodeValue;
                    }

                    // check and add directory test
                    foreach ($badaso_test_paths as $_ => $badaso_test_path) {
                        if (! in_array($badaso_test_path, $now_test_paths)) {
                            $new_directory = $document->createElement('directory', $badaso_test_path);
                            $new_directory->setAttribute('suffix', 'Test.php');

                            $test_suite_features->item($index_test_suite_feature)->appendChild($new_directory);
                        }
                    }

                    break;
            }
        }

        // save xml
        file_put_contents($phpunit_xml_path, $document->saveHTML());
    }
}
