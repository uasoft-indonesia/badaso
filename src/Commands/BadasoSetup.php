<?php

namespace Uasoft\Badaso\Commands;

use Illuminate\Console\Command;
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
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup Badaso Modules';

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
        $this->addNodePackages();
        $this->addWebpackMix();
    }

    protected function addNodePackages()
    {
        $package_json = file_get_contents(base_path('package.json'));
        $decoded_json = json_decode($package_json, true);
        $decoded_json['devDependencies']['axios'] = '0.18';
        $decoded_json['devDependencies']['bootstrap'] = '4.0.0';
        $decoded_json['devDependencies']['popper.js'] = '1.12';
        $decoded_json['devDependencies']['cross-env'] = '5.1';
        $decoded_json['devDependencies']['jquery'] = '3.2';
        $decoded_json['devDependencies']['laravel-mix'] = '2.0';
        $decoded_json['devDependencies']['lodash'] = '4.17.4';
        $decoded_json['devDependencies']['vue'] = '2.5.7';

        $decoded_json['dependencies']['@johmun/vue-tags-input'] = '2.1.0';
        $decoded_json['dependencies']['chart.js'] = '2.8.0';
        $decoded_json['dependencies']['luxon'] = '1.25.0';
        $decoded_json['dependencies']['moment'] = '2.29.1';
        $decoded_json['dependencies']['material-icons'] = '0.3.1';
        $decoded_json['dependencies']['prismjs'] = '1.17.1';
        $decoded_json['dependencies']['vue-chartjs'] = '3.4.2';
        $decoded_json['dependencies']['vue-color'] = '2.7.1';
        $decoded_json['dependencies']['vue-datetime'] = '1.0.0-beta.14';
        $decoded_json['dependencies']['vue-draggable-nested-tree'] = '3.0.0-beta2';
        $decoded_json['dependencies']['vue-i18n'] = '8.22.4';
        $decoded_json['dependencies']['vue-prism-editor'] = '1.2.2';
        $decoded_json['dependencies']['vue-router'] = '3.1.3';
        $decoded_json['dependencies']['vue2-editor'] = '2.10.2';
        $decoded_json['dependencies']['vuedraggable'] = '2.24.3';
        $decoded_json['dependencies']['vuelidate'] = '0.7.6';
        $decoded_json['dependencies']['vuesax'] = '3.12.2';
        $decoded_json['dependencies']['vuex'] = '3.1.1';
        $decoded_json['dependencies']['vuex-persistedstate'] = '4.0.0-beta.1';
        $decoded_json['dependencies']['weekstart'] = '1.0.1';

        $encoded_json = json_encode($decoded_json, JSON_PRETTY_PRINT);
        file_put_contents(base_path('package.json'), $encoded_json);
    }

    protected function checkExist($file, $search)
    {
        return $this->file->exists($file) && !Str::contains($this->file->get($file), $search);
    }

    protected function addWebpackMix()
    {
        // mix
        $mix_file = base_path('webpack.mix.js');
        $search = 'Badaso';

        if ($this->checkExist($mix_file, $search)) {
            $data =
        <<<EOT

        // Badaso
        mix
            .js(
                "vendor/uasoft-indonesia/badaso/src/resources/js/app.js",
                "public/js/badaso.js"
            )
        EOT;

            $this->file->append($mix_file, $data);
        }
    }
}
