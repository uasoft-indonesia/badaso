<?php

namespace Uasoft\Badaso\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class BackupCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'badaso:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command run laravel backup base on ENV.';

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
     * @return mixed
     */
    public function handle()
    {
        $backup_target = env('BACKUP_TARGET', '');
        if (!is_null($backup_target) && $backup_target != '') {
            switch ($backup_target) {
                case 'all':
                    Artisan::call('backup:run');
                    break;
                case 'db':
                    Artisan::call('backup:run', [
                        '--only-db' => true,
                    ]);
                    break;
                case 'files':
                    Artisan::call('backup:run', [
                        '--only-files' => true,
                    ]);
                    break;
                default:
                    // code...
                    break;
            }
        }
    }
}
