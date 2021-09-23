<?php

namespace Uasoft\Badaso\Helpers;

use Symfony\Component\VarExporter\VarExporter;

class WatchTableConfig
{
    public $watch_tables = [];
    private $path_watch_tables;

    public function __construct()
    {
        $this->path_watch_tables = config_path('badaso-watch-tables.php');
        $this->watch_tables = [];
    }

    public function addWatchTable($table_name): self
    {
        if (! in_array($table_name, $this->watch_tables)) {
            $this->watch_tables[count($this->watch_tables)] = $table_name;
            $this->saveWatchTable();
        }

        return $this;
    }

    public function deleteWatchTable($table_name): self
    {
        $this->watch_tables = array_filter(
            $this->watch_tables,
            function ($item_table_name) use ($table_name) {
                return $item_table_name != $table_name;
            }
        );
        $this->saveWatchTable();

        return $this;
    }

    public function saveWatchTable(): void
    {
        $exported = VarExporter::export($this->watch_tables);
        file_put_contents($this->path_watch_tables, $this->formatFileConfig($exported));
    }

    private function formatFileConfig($exported_config): string
    {
        return <<<PHP
        <?php

        return {$exported_config};
        PHP;
    }

    public static function get()
    {
        return new self();
    }
}
