<?php

use Illuminate\Database\Seeder;

class BadasoSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
        $this->call(SiteManagementSeeder::class);
    }
}
