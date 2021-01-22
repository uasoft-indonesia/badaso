<?php

namespace Uasoft\Badaso\Commands;

use Exception;
use Illuminate\Console\Command;
use Uasoft\Badaso\ContentManager\FileGenerator;

class GenerateSeederCommand extends Command
{
    protected $suffix = 'TableSeeder';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'badaso:generate-seeder {tables}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Seed files for tables, except for CRUD_DATA.';

    /** @var FileGenerator */
    private $file_generator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(FileGenerator $file_generator)
    {
        parent::__construct();

        $this->file_generator = $file_generator;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $tables = explode(',', $this->argument('tables'));

        try {
            foreach ($tables as $table) {
                $this->printResult(
                    $table,
                    $this->file_generator->generateSeedFile($table, $this->suffix)
                );
            }
        } catch (Exception $exception) {
            $this->printResult($table, false);
        }
    }

    /**
     * Print Result.
     */
    public function printResult(string $table, bool $isSuccess = true)
    {
        if ($isSuccess) {
            $this->info("Created a seed file from table {$table}");

            return;
        }

        $this->error("Could not create seed file from table {$table}");
    }
}
