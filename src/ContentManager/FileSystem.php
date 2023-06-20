<?php

namespace Uasoft\Badaso\ContentManager;

use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem as LaravelFileSystem;
use Illuminate\Support\Composer;
use Illuminate\Support\Str;
use Laravel\Octane\RoadRunner\ServerProcessInspector as RoadRunnerServerProcessInspector;
use Laravel\Octane\Swoole\ServerProcessInspector as SwooleServerProcessInspector;
use Uasoft\Badaso\Helpers\MigrationParser;

class FileSystem
{
    /** @var LaravelFileSystem */
    private $filesystem;

    /** @var Composer */
    private $composer;

    private $parser;

    /**
     * Create the event listener.
     */
    public function __construct(LaravelFileSystem $filesystem, Composer $composer, MigrationParser $parser)
    {
        $this->filesystem = $filesystem;
        $this->composer = $composer;
        $this->parser = $parser;
    }

    /**
     * Get seeder file.
     */
    public function getSeederFile(string $name, string $path): string
    {
        return $path.'/'.$name.'.php';
    }

    /**
     * Get migration file.
     */
    public function getMigrationFile(string $name, string $path): string
    {
        return $path.'/'.$name.'.php';
    }

    public function getMigrationFileNoDate(string $name, string $path): string
    {
        $filePath = glob($path.'*');
        $fileName = '';
        foreach ($filePath as $value) {
            if (strpos($value, $name) !== false) {
                $fileName = $value;
            }
        }

        return $fileName;
    }

    /**
     * Get migration filename.
     */
    public function getMigrationFileName(string $name, string $prefix, string $className): string
    {
        $random = lcfirst(substr($className, -4));

        return Carbon::now()->format('Y_m_d_his').'_'.$prefix.'_'.$name.'_table_'.$random;
    }

    /**
     * Get alter migration filename.
     */
    public function getAlterMigrationFileName(array $name, string $className): string
    {
        $prefix = $this->parser->convertPascalToSnake($className);

        return Carbon::now()->format('Y_m_d_his').'_'.$prefix;
    }

    /**
     * Get migration filename without date.
     */
    public function getMigrationFileNameNoDate(string $name, string $prefix): string
    {
        return $prefix.'_'.$name.'_table';
    }

    /**
     * Get Seed Folder Path.
     */
    public function getSeedCRUDFolderPath(): string
    {
        $path = base_path().'/database/seeders/Badaso/CRUD';
        if (! file_exists($path)) {
            mkdir($path, 0777);
        }

        return $path;
    }

    /**
     * Get Seed Folder Path.
     */
    public function getSeedManualGenerateFolderPath(): string
    {
        $path = base_path().'/database/seeders/Badaso/ManualGenerate';
        if (! file_exists($path)) {
            mkdir($path, 0777);
        }

        return $path;
    }

    /**
     * Get Migration Folder Path.
     */
    public function getMigrationFolderPath(): string
    {
        $path = base_path().'/database/migrations/badaso/';
        if (! file_exists($path)) {
            mkdir($path, 0777);
        }

        return $path;
    }

    /**
     * Get Stub Path.
     */
    public function getStubPath(): string
    {
        return __DIR__.DIRECTORY_SEPARATOR;
    }

    /**
     * Delete Seed File.
     */
    public function deleteSeedFiles(string $file_name): bool
    {
        $seeder_file = $this->getSeederFile($file_name, $this->getSeedCRUDFolderPath());

        if ($this->filesystem->exists($seeder_file)) {
            return $this->filesystem->delete($seeder_file);
        }

        return false;
    }

    /**
     * Delete Migration File.
     */
    public function deleteMigrationFiles(string $file_name): string
    {
        if ($this->filesystem->exists($file_name)) {
            return $this->filesystem->delete($file_name);
        }

        return false;
    }

    /**
     * Generate Seeder Class Name.
     */
    public function generateSeederClassName(string $model_slug, string $suffix): string
    {
        $model_string = '';

        $model_name = explode('-', $model_slug);
        foreach ($model_name as $model_name_exploded) {
            $model_string .= ucfirst($model_name_exploded);
        }

        return ucfirst($model_string).$suffix;
    }

    /**
     * Generate Migration Class Name.
     */
    public function generateMigrationClassName(string $model_slug, string $prefix): string
    {
        $model_string = '';
        $model_slug = str_replace('_', '', $model_slug);
        $model_name = explode('-', $model_slug);
        foreach ($model_name as $model_name_exploded) {
            $model_string .= ucfirst($model_name_exploded);
        }

        $randomise = Str::lower($this->parser->getRandomCharacter(4));

        return ucfirst($prefix).ucfirst($model_string).'Table'.ucfirst($randomise);
    }

    /**
     * Generate Alter Migration Class Name.
     */
    public function generateAlterMigrationClassName(array $table, string $prefix): string
    {
        $current_model_name = ucfirst($table['current_name']);
        $modified_model_name = ucfirst($table['modified_name']);
        $current_model_name = str_replace('_', '', $current_model_name);
        $modified_model_name = str_replace('_', '', $modified_model_name);

        if ($prefix == 'rename') {
            return ucfirst($prefix).ucfirst($current_model_name).'To'.ucfirst($modified_model_name).'Table';
        } else {
            $randomise = Str::lower($this->parser->getRandomCharacter(4));

            return ucfirst($prefix).ucfirst($modified_model_name).'Table'.ucfirst($randomise);
        }
    }

    /**
     * Add Content to Seeder file.
     */
    public function addContentToSeederFile(string $seeder_file, string $seeder_contents, bool $run_composer_dump_autoload = true): bool
    {
        if (! $this->filesystem->put($seeder_file, $seeder_contents)) {
            return false;
        }

        if ($run_composer_dump_autoload) {
            $this->safeDumpAutoloads();
        }

        return true;
    }

    public function addContentToMigrationFile(string $migration_file, string $migration_contents): bool
    {
        if (! $this->filesystem->put($migration_file, $migration_contents)) {
            return false;
        }

        $this->safeDumpAutoloads();

        return true;
    }

    private function safeDumpAutoloads()
    {
        $inspector = null;
        $server = config('octane.server');
        switch ($server) {
            case 'swoole':
                $inspector = app(SwooleServerProcessInspector::class);
                break;
            case 'roadrunner':
                $inspector = app(RoadRunnerServerProcessInspector::class);
                break;
        }

        if (isset($inspector)) {
            if ($inspector->serverIsRunning()) {
                $inspector->reloadServer();
            } else {
                if (env('APP_ENV') == 'local') {
                    $this->composer->dumpAutoloads();
                }
            }
        } else {
            if (env('APP_ENV') == 'local') {
                $this->composer->dumpAutoloads();
            }
        }
    }

    /**
     * Get File Content.
     *
     * @param  $file
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getFileContent($file): string
    {
        return $this->filesystem->get($file);
    }
}
