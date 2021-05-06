<?php

namespace Uasoft\Badaso\Commands;

use Illuminate\Console\Command;
use Uasoft\Badaso\Helpers\Firebase\FirebasePublishFile;

class BadasoFirebaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'badaso:firebase-sw';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make file worker js firebase notification';

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
        FirebasePublishFile::publishNow();
        $this->info('firebase-messaging-sw.json created');
    }
}
