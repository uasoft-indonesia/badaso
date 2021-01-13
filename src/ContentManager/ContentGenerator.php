<?php

namespace Uasoft\Badaso\ContentManager;

class ContentGenerator
{
    /** @var string */
    public $indent_character = '    ';

    /** @var string */
    private $new_line_character = PHP_EOL;

    /** @var string Data type Delete Statement */
    const DELETE_STATEMENT = <<<'TXT'
    $data_type = Badaso::model('DataType')->where('name', '%s')->first();

                if ($data_type) {
                    Badaso::model('DataType')->where('name', '%s')->delete();
                }
    TXT;

    /** @var string Menu Insert Statement */
    const MENU_INSERT_STATEMENT = <<<'TXT'
    $menu = Badaso::model('Menu')->where('key', config('badaso.default_menu'))->firstOrFail();

                $menu_item = Badaso::model('MenuItem')->firstOrNew([
                    'menu_id' => $menu->id,
                    'title' => '%s',
                    'url' => '%s',
                ]);

                $order = Badaso::model('MenuItem')->highestOrderMenuItem();

                if (!$menu_item->exists) {
                    $menu_item->fill([
                        'target' => '_self',
                        'icon_class' => null,
                        'color' => null,
                        'parent_id' => null,
                        'order' => $order,
                    ])->save();
                }
    TXT;

    /** @var string Menu Delete Statement */
    const MENU_DELETE_STATEMENT = <<<'TXT'
    $menuItem = Badaso::model('MenuItem')::where('url', $data_type->slug);

                if ($menuItem->exists()) {
                    $menuItem->delete();
                }
    TXT;

    const DATATYPE_SLUG_STATEMENT = <<<'TXT'
    $data_type = Badaso::model('DataType')::where('name', '%s')->first();
    TXT;

    const GENERATE_PERMISSIONS_STATEMENT = <<<'TXT'
    Badaso::model('Permission')->generateFor('%s');
    TXT;

    const REMOVE_PERMISSIONS_STATEMENT = <<<'TXT'
    Badaso::model('Permission')->removeFrom('%s');
    TXT;

    /**
     * Format Content.
     *
     * @param $array
     * @param bool $indexed
     *
     * @return mixed|string|string[]|null
     */
    public function formatContent($array, $indexed = true)
    {
        $content = ($indexed)
            ? var_export($array, true)
            : preg_replace("/[0-9]+ \=\>/i", '', var_export($array, true));
        $in_string = false;
        $tab_count = 4;

        // replace array() with []
        $lines = explode("\n", $content);

        for ($i = 1; $i < count($lines); ++$i) {
            $lines[$i] = ltrim($lines[$i]);
            // Check for closing bracket
            if (strpos($lines[$i], ')') !== false) {
                --$tab_count;
            }

            // Insert tab count
            if ($in_string === false) {
                for ($j = 0; $j < $tab_count; ++$j) {
                    $lines[$i] = substr_replace($lines[$i], $this->indent_character, 0, 0);
                }
            }
            for ($j = 0; $j < strlen($lines[$i]); ++$j) {
                // skip character right after an escape \
                if ($lines[$i][$j] == '\\') {
                    ++$j;
                } // check string open/end
                elseif ($lines[$i][$j] == '\'') {
                    $in_string = !$in_string;
                }
            }
            // check for opening bracket
            if (strpos($lines[$i], '(') !== false) {
                ++$tab_count;
            }
        }
        $content = implode("\n", $lines);

        return $content;
    }

    /**
     * Get Delete Statement.
     *
     * @param $data_type
     */
    public function getDeleteStatement($data_type): string
    {
        return sprintf(self::DELETE_STATEMENT, $data_type->name, $data_type->name);
    }

    public function getDataTypeSlugStatement($data_type): string
    {
        return sprintf(self::DATATYPE_SLUG_STATEMENT, $data_type->name);
    }

    /**
     * Generate Menu Delete Statements.
     *
     * @param $data_type
     */
    public function generateMenuDeleteStatements($data_type): string
    {
        return sprintf(self::MENU_DELETE_STATEMENT, $data_type->slug);
    }

    /**
     * Get Permission Statements.
     *
     * @param $data_type
     * @param null $type
     */
    public function getPermissionStatement($data_type, $type = null): string
    {
        $permission = self::GENERATE_PERMISSIONS_STATEMENT;

        if (!is_null($type)) {
            $permission = self::REMOVE_PERMISSIONS_STATEMENT;
        }

        return sprintf($permission, $data_type->name);
    }

    /**
     * Get Menu Insert Statements.
     *
     * @param $data_type
     */
    public function getMenuInsertStatements($data_type): string
    {
        return sprintf(
            self::MENU_INSERT_STATEMENT,
            $data_type->display_name_plural,
            $data_type->slug
        );
    }

    /**
     * Generate Orchestra Seeder Content.
     *
     * @param string $class_name
     * @param string $content
     *
     * @return mixed|string|string[]|null
     */
    public function generateOrchestraSeederContent($class_name, $content)
    {
        if (strpos($class_name, FileGenerator::DELETED_SEEDER_SUFFIX) !== false) {
            $to_be_deleted_class_name = strstr(
                $class_name,
                FileGenerator::DELETED_SEEDER_SUFFIX,
                true
            );
            $bread_type_added_class = $to_be_deleted_class_name.FileGenerator::TYPE_SEEDER_SUFFIX;

            $bread_row_added_class = $to_be_deleted_class_name.FileGenerator::ROW_SEEDER_SUFFIX;

            $content = str_replace("\$this->seed({$bread_type_added_class}::class);", '', $content);
            $content = str_replace("\$this->seed({$bread_row_added_class}::class);", '', $content);
        }

        if (strpos($content, "\$this->seed({$class_name}::class)") === false) {
            if (
                strpos($content, '#orchestraseeder_start') &&
                strpos($content, '#orchestraseeder_end') &&
                strpos($content, '#orchestraseeder_start') < strpos($content, '#orchestraseeder_end')
            ) {
                $content = preg_replace(
                    "/(\#orchestraseeder_start.+?)(\#orchestraseeder_end)/us",
                    "$1\$this->seed({$class_name}::class);{$this->new_line_character}{$this->indent_character}{$this->indent_character}$2",
                    $content
                );
            } else {
                $content = preg_replace(
                    "/(run\(\).+?)}/us",
                    "$1{$this->indent_character}\$this->seed({$class_name}::class);{$this->new_line_character}{$this->indent_character}}",
                    $content
                );
            }
        }

        return $content;
    }
}
