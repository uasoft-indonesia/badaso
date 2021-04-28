<?php

namespace Uasoft\Badaso\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BadasoSetup extends Command
{
    protected $file;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'badaso:setup';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'badaso:setup {--force=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup Badaso Modules';

    private $force = false;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->file = app('files');
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->force = $this->options()['force'] == 'true' || $this->options()['force'] == null;

        $this->updatePackageJson();
        $this->updateWebpackMix();
        $this->publishBadasoProvider();
        $this->publishLaravelBackupProvider();
        $this->publishLaravelActivityLogProvider();
        $this->publishLaravelFileManager();
        $this->uploadDefaultUserImage();
    }

    protected function updatePackageJson()
    {
        $package_json = file_get_contents(base_path('package.json'));
        $decoded_json = json_decode($package_json, true);
        $decoded_json['devDependencies']['axios'] = '^0.18';
        $decoded_json['devDependencies']['bootstrap'] = '^4.0.0';
        $decoded_json['devDependencies']['popper.js'] = '^1.12';
        $decoded_json['devDependencies']['cross-env'] = '^5.1';
        $decoded_json['devDependencies']['jquery'] = '^3.2';
        $decoded_json['devDependencies']['laravel-mix'] = '^2.0';
        $decoded_json['devDependencies']['lodash'] = '^4.17.4';
        $decoded_json['devDependencies']['vue'] = '^2.5.7';

        $decoded_json['dependencies']['@johmun/vue-tags-input'] = '^2.1.0';
        $decoded_json['dependencies']['chart.js'] = '^2.8.0';
        $decoded_json['dependencies']['jspdf'] = '^2.3.1';
        $decoded_json['dependencies']['jspdf-autotable'] = '^3.5.14';
        $decoded_json['dependencies']['luxon'] = '^1.25.0';
        $decoded_json['dependencies']['moment'] = '^2.29.1';
        $decoded_json['dependencies']['material-icons'] = '^0.3.1';
        $decoded_json['dependencies']['prismjs'] = '^1.17.1';
        $decoded_json['dependencies']['vue-chartjs'] = '^3.4.2';
        $decoded_json['dependencies']['vue-color'] = '^2.7.1';
        $decoded_json['dependencies']['vue-datetime'] = '^1.0.0-beta.14';
        $decoded_json['dependencies']['vue-draggable-nested-tree'] = '^3.0.0-beta2';
        $decoded_json['dependencies']['vue-i18n'] = '^8.22.4';
        $decoded_json['dependencies']['vue-json-excel'] = '^0.3.0';
        $decoded_json['dependencies']['uuid'] = '^8.3.2';
        $decoded_json['dependencies']['vue-prism-editor'] = '^1.2.2';
        $decoded_json['dependencies']['vue-router'] = '^3.1.3';
        $decoded_json['dependencies']['vue2-editor'] = '^2.10.2';
        $decoded_json['dependencies']['vuedraggable'] = '^2.24.3';
        $decoded_json['dependencies']['vuelidate'] = '^0.7.6';
        $decoded_json['dependencies']['vuesax'] = '^3.12.2';
        $decoded_json['dependencies']['vuex'] = '^3.1.1';
        $decoded_json['dependencies']['vuex-persistedstate'] = '^4.0.0-beta.1';
        $decoded_json['dependencies']['weekstart'] = '^1.0.1';

        $encoded_json = json_encode($decoded_json, JSON_PRETTY_PRINT);
        file_put_contents(base_path('package.json'), $encoded_json);

        $this->info('package.json updated');
    }

    protected function checkExist($file, $search)
    {
        return $this->file->exists($file) && ! Str::contains($this->file->get($file), $search);
    }

    protected function updateWebpackMix()
    {
        // mix
        $mix_file = base_path('webpack.mix.js');
        $search = 'Badaso';

        if ($this->checkExist($mix_file, $search)) {
            $data =
                <<<'EOT'

        // Badaso
        mix
            .js(
                "vendor/uasoft-indonesia/badaso/src/resources/js/app.js",
                "public/js/badaso.js"
            )
        EOT;

            $this->file->append($mix_file, $data);
        }

        $this->info('webpack.mix.js updated');
    }

    protected function publishBadasoProvider()
    {
        $command_params = ['--tag' => 'Badaso'];
        if ($this->force) {
            $command_params['--force'] = true;
        }

        Artisan::call('vendor:publish', $command_params);

        $this->info('Badaso provider published');
    }

    protected function publishLaravelBackupProvider()
    {
        $command_params = [
            '--provider' => "Spatie\Backup\BackupServiceProvider",
        ];
        if ($this->force) {
            $command_params['--force'] = true;
        }

        Artisan::call('vendor:publish', $command_params);

        $this->info('Laravel backup provider published');
    }

    protected function publishLaravelActivityLogProvider()
    {
        $command_params = [
            '--provider' => "Spatie\Activitylog\ActivitylogServiceProvider",
            '--tag'      => 'config',
        ];
        if ($this->force) {
            $command_params['--force'] = true;
        }
        Artisan::call('vendor:publish', $command_params);

        $this->info('Laravel activity log provider published');
    }

    protected function uploadDefaultUserImage()
    {
        $img = file_get_contents(public_path('/badaso-images/default-user.png'));
        Storage::disk(config('badaso.storage.disk', 'public'))->put('users/default.png', $img);
    }

    protected function publishLaravelFileManager()
    {
        $command_params = ['--tag' => 'lfm_public'];
        if ($this->force) {
            $command_params['--force'] = true;
        }
        Artisan::call('vendor:publish', $command_params);

        $this->info('Fime Manager provider published');
    }
}
