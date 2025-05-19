<?php

namespace Uasoft\Badaso\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\VarExporter\VarExporter;
use Uasoft\Badaso\Helpers\Firebase\FirebasePublishFile;

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
    protected $description = 'Setup Badaso';

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

        $this->addingBadasoEnv();
        $this->updatePackageJson();
        $this->updateWebpackMix();
        $this->publishBadasoProvider();
        $this->publishLaravelBackupProvider();
        $this->publishLaravelActivityLogProvider();
        $this->publishLaravelFileManager();
        $this->publishLaravelAnalytics();
        $this->publicFileFirebaseServiceWorker();
        $this->addingBadasoAuthConfig();
        $this->generateSwagger();
    }

    protected function generateSwagger()
    {
        try {
            $this->call('l5-swagger:generate');
        } catch (\Exception $e) {
            //throw $th;
        }
    }

    protected function updatePackageJson()
    {
        $package_json = file_get_contents(base_path('package.json'));
        $decoded_json = json_decode($package_json, true);

        $decoded_json['devDependencies']['axios'] = '^1.7.2';
        $decoded_json['devDependencies']['laravel-mix'] = '^6.0.49';
        $decoded_json['devDependencies']['lodash'] = '^4.17.21';
        $decoded_json['devDependencies']['postcss'] = '^8.4.39';

        $decoded_json['dependencies']['copy-files-from-to'] = '^3.11.0';
        $decoded_json['dependencies']['popper.js'] = '^1.12';
        $decoded_json['dependencies']['cross-env'] = '^7.0.3';
        $decoded_json['dependencies']['vue'] = '^3.4.31';
        $decoded_json['dependencies']['@vue/compat'] = '^3.4.31';
        $decoded_json['dependencies']['vue-loader'] = '^17.4.2';
        $decoded_json['dependencies']['@vue/compiler-sfc'] = '^3.1.0';
        $decoded_json['dependencies']['@vuelidate/core'] = '^2.0.3';
        $decoded_json['dependencies']['@vuelidate/validators'] = '^2.0.4';
        $decoded_json['dependencies']['sass'] = '^1.77.7';
        $decoded_json['dependencies']['sass-loader'] = '^14.2.1';
        $decoded_json['dependencies']['resolve-url-loader'] = '^5.0.0';
        $decoded_json['dependencies']['dompurify'] = '^3.2.4';

        $decoded_json['dependencies']['@tinymce/tinymce-vue'] = '^4.0.7';
        $decoded_json['dependencies']['body-scroll-lock'] = '^4.0.0-beta.0';
        $decoded_json['dependencies']['chart.js'] = '^4.4.3';
        $decoded_json['dependencies']['firebase'] = '^8.4.2';
        $decoded_json['dependencies']['jspdf'] = '^2.3.1';
        $decoded_json['dependencies']['jspdf-autotable'] = '^3.8.2';
        $decoded_json['dependencies']['luxon'] = '^3.4.4';
        $decoded_json['dependencies']['material-icons'] = '^1.13.12';
        $decoded_json['dependencies']['moment'] = '^2.30.1';
        $decoded_json['dependencies']['prismjs'] = '^1.29.0';
        $decoded_json['dependencies']['tinymce'] = '^5.7.1';
        $decoded_json['dependencies']['uuid'] = '^10.0.0';
        $decoded_json['dependencies']['vue-chartjs'] = '^5.3.1';
        $decoded_json['dependencies']['vue3-colorpicker'] = '^2.3.0';
        $decoded_json['dependencies']['vue-datetime'] = '^1.0.0-beta.14';
        $decoded_json['dependencies']['vue-datetime3'] = '^1.0.16';
        $decoded_json['dependencies']['vue-draggable-nested-tree'] = '^3.0.0-beta2';
        $decoded_json['dependencies']['vue3-draggable'] = '^2.0.9';
        $decoded_json['dependencies']['vue-gtag-next'] = '^1.14.0';
        $decoded_json['dependencies']['vue-i18n'] = '^9.13.1';
        $decoded_json['dependencies']['vue-json-excel'] = '^0.3.0';
        $decoded_json['dependencies']['vue-prism-editor'] = '^2.0.0-alpha.2';
        $decoded_json['dependencies']['vue-router'] = '^4.4.0';
        $decoded_json['dependencies']['vue3-editor'] = '^0.1.1';
        $decoded_json['dependencies']['vue3-tags-input'] = '^1.0.12';
        $decoded_json['dependencies']['vuedraggable'] = '^2.24.3';
        $decoded_json['dependencies']['vue-draggable-next'] = '^2.2.1';
        $decoded_json['dependencies']['vuelidate'] = '^0.7.7';
        $decoded_json['dependencies']['vuesax3'] = '^4.2.1';
        $decoded_json['dependencies']['vuex'] = '^4.1.0';
        $decoded_json['dependencies']['vuex-persistedstate'] = '^4.0.0-beta.1';
        $decoded_json['dependencies']['weekstart'] = '^2.0.0';

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
        mix.js("vendor/badaso/core/src/resources/js/app.js", "public/js/badaso.js")
            .sass("vendor/badaso/core/src/resources/js/assets/scss/style.scss", "public/css/badaso.css")
            .vue()
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
            '--tag' => 'config',
        ];
        if ($this->force) {
            $command_params['--force'] = true;
        }
        Artisan::call('vendor:publish', $command_params);

        $this->info('Laravel activity log provider published');
    }

    protected function publishLaravelFileManager()
    {
        $command_params = ['--tag' => 'lfm_public'];
        if ($this->force) {
            $command_params['--force'] = true;
        }
        Artisan::call('vendor:publish', $command_params);

        $this->info('File Manager provider published');
    }

    protected function publicFileFirebaseServiceWorker()
    {
        FirebasePublishFile::publishNow();
    }

    protected function addingBadasoAuthConfig()
    {
        try {
            $path_config_auth = config_path('auth.php');
            $config_auth = require $path_config_auth;

            $config_auth['defaults'] = [
                'guard' => 'badaso_guard',
                'passwords' => 'users',
            ];
            $config_auth['guards']['badaso_guard'] = [
                'driver' => 'jwt',
                'provider' => 'badaso_users',
            ];
            $config_auth['providers']['badaso_users'] = [
                'driver' => 'eloquent',
                'model' => \Uasoft\Badaso\Models\User::class,
            ];

            $exported_config_auth = VarExporter::export($config_auth);
            $exported_config_auth = <<<PHP
                <?php
                return {$exported_config_auth} ;
                PHP;
            file_put_contents($path_config_auth, $exported_config_auth);
            $this->info('Adding badaso auth config');
        } catch (\Exception $e) {
            $this->error('Failed adding badaso auth config ', $e->getMessage());
        }
    }

    protected function envListUpload()
    {
        return [
            'JWT_SECRET' => '',
            'BADASO_AUTH_TOKEN_LIFETIME' => '',
            'BADASO_LICENSE_KEY' => '',
            'ARCANEDEV_LOGVIEWER_MIDDLEWARE' => '',
            'MIX_BADASO_MAINTENANCE' => 'false',
            'MIX_BADASO_PLUGINS' => '',
            'MIX_DEFAULT_MENU' => 'general',
            'MIX_BADASO_MENU' => '${MIX_DEFAULT_MENU}',
            'MIX_ADMIN_PANEL_ROUTE_PREFIX' => 'badaso-dashboard',
            'MIX_BADASO_SECRET_LOGIN_PREFIX' => 'badaso-secret-login',
            'MIX_API_ROUTE_PREFIX' => 'badaso-api',
            'MIX_LOG_VIEWER_ROUTE' => '"log-viewer"',
            'MIX_FIREBASE_API_KEY' => '',
            'MIX_FIREBASE_AUTH_DOMAIN' => '',
            'MIX_FIREBASE_PROJECT_ID' => '',
            'MIX_FIREBASE_STORAGE_BUCKET' => '',
            'MIX_FIREBASE_MESSAGE_SEENDER' => '',
            'MIX_FIREBASE_APP_ID' => '',
            'MIX_FIREBASE_MEASUREMENT_ID' => '',
            'MIX_FIREBASE_WEB_PUSH_CERTIFICATES' => '',
            'MIX_FIREBASE_SERVER_KEY' => '',
            'FILESYSTEM_DRIVER' => 'public',
            'AWS_ACCESS_KEY_ID' => '',
            'AWS_SECRET_ACCESS_KEY' => '',
            'AWS_DEFAULT_REGION' => '',
            'AWS_BUCKET' => '',
            'AWS_URL' => '',
            'GOOGLE_DRIVE_CLIENT_ID' => '',
            'GOOGLE_DRIVE_CLIENT_SECRET' => '',
            'GOOGLE_DRIVE_REFRESH_TOKEN' => '',
            'GOOGLE_DRIVE_FOLDER_ID' => '',
            'DROPBOX_AUTH_TOKEN' => '',
            'BACKUP_TARGET' => '',
            'BACKUP_DISK' => '',
            'MIX_DATE_FORMAT' => '',
            'MIX_DATETIME_FORMAT' => '',
            'MIX_TIME_FORMAT' => '',
            'ANALYTICS_VIEW_ID' => '',
            'MIX_ANALYTICS_TRACKING_ID' => '',
            'MIX_API_DOCUMENTATION_ANNOTATION_ROUTE' => 'api-annotation',
            'MIX_API_DOCUMENTATION_ROUTE' => 'api-docs',
            'BADASO_TABLE_PREFIX' => 'badaso_',
            'OCTANE_SERVER' => 'swoole',
            'REDIS_CLIENT' => 'predis',
            'WORKSPACE_PUID' => '1000',
            'WORKSPACE_PGID' => '1000',
            'WWWGROUP' => '1000',
            'WWWUSER' => '1000',
        ];
    }

    protected function addingBadasoEnv()
    {
        try {
            $env_path = base_path('.env');

            $env_file = file_get_contents($env_path);
            $arr_env_file = explode("\n", $env_file);

            $env_will_adding = $this->envListUpload();

            $new_env_adding = [];
            foreach ($env_will_adding as $key_add_env => $val_add_env) {
                $status_adding = true;
                foreach ($arr_env_file as $key_env_file => $val_env_file) {
                    $val_env_file = trim($val_env_file);
                    if (substr($val_env_file, 0, 1) != '#' && $val_env_file != '' && strstr($val_env_file, $key_add_env)) {
                        $status_adding = false;
                        break;
                    }
                }
                if ($status_adding) {
                    $new_env_adding[] = "{$key_add_env}={$val_add_env}";
                }
            }

            foreach ($new_env_adding as $index_env_add => $val_env_add) {
                $arr_env_file[] = $val_env_add;
            }

            $env_file = join("\n", $arr_env_file);
            file_put_contents($env_path, $env_file);

            $this->info('Adding badaso env');
        } catch (\Exception $e) {
            $this->error('Failed adding badaso env '.$e->getMessage());
        }
    }

    protected function publishLaravelAnalytics()
    {
        $command_params = [
            '--provider' => "Spatie\Analytics\AnalyticsServiceProvider",
        ];
        if ($this->force) {
            $command_params['--force'] = true;
        }
        Artisan::call('vendor:publish', $command_params);

        $this->info('Laravel analytics provider published');
    }
}
