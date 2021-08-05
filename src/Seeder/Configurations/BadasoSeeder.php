<?php

namespace Database\Seeders\Badaso;

use Illuminate\Database\Seeder;

class BadasoSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(RolePermissionsSeeder::class);
        $this->call(MenusSeeder::class);
        $this->call(FixedMenuItemSeeder::class);
        $this->call(ConfigurationsSeeder::class);
    }
}
