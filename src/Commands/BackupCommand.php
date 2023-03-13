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
        $backup_disk = env('BACKUP_DISK', '');
        if (is_null($backup_target) || $backup_target == '') {
            $this->warn('No target to backup.');

            return;
        }

        if (is_null($backup_disk) || $backup_disk == '') {
            $this->warn('No disk specified. Set to env some of [s3, google, dropbox]');

            return;
        }

        $backup_done = true;

        switch ($backup_target) {
            case 'all':
                $this->info("Running backup $backup_target to $backup_disk");
                Artisan::call('backup:run');
                break;
            case 'database':
                $this->info("Running backup $backup_target to $backup_disk");
                Artisan::call('backup:run', [
                    '--only-db' => true,
                ]);
                break;
            case 'files':
                $this->info("Running backup $backup_target to $backup_disk");
                Artisan::call('backup:run', [
                    '--only-files' => true,
                ]);
                break;
            default:
                $backup_done = false;
                $this->warn('Invalid Backup Target. Set to env one of [all, database, files]');
                break;
        }

        if ($backup_done) {
            $this->Info('Backup done');
        }
    }
}
