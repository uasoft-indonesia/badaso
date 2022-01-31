<?php

namespace Database\Seeders\Badaso;

use Illuminate\Database\Seeder;
use Uasoft\Badaso\Models\Role;

class RolesSeeder extends Seeder
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
            $roles = [
                0 => [
                    'name' => 'administrator',
                    'display_name' => 'Administrator',
                ],
                1 => [
                    'name' => 'customer',
                    'display_name' => 'Customer',
                ],
            ];

            $new_roles = [];
            foreach ($roles as $key => $value) {
                $role = Role::where('name', $value['name'])->first();
                if (isset($role)) {
                    $role->update($value);
                } else {
                    Role::create($value);
                }
            }

            \DB::commit();
        } catch (Exception $e) {
            throw new Exception('Exception occur '.$e);
            \DB::rollBack();
        }
    }
}
