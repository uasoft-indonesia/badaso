<?php

namespace Database\Seeders\Badaso;

use Illuminate\Database\Seeder;
use Uasoft\Badaso\Models\Menu;

class MenusSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     *
     * @throws Exception
     */
    public function run()
    {
        \DB::beginTransaction();

        try {
            $menus = [
                0 => [
                    'key' => 'general',
                    'display_name' => 'General Menu',
                ],
                1 => [
                    'key' => 'core',
                    'display_name' => 'Core',
                ],
            ];

            $new_menus = [];
            foreach ($menus as $key => $value) {
                $menu = Menu::where('key', $value['key'])->first();
                if (isset($menu)) {
                    $menu->update($value);
                } else {
                    Menu::create($value);
                }
            }
        } catch (Exception $e) {
            throw new Exception('Exception occur '.$e);
            \DB::rollBack();
        }

        \DB::commit();
    }
}
