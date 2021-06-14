<?php

namespace Database\Seeders\Badaso;

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @throws Exception
     *
     * @return void
     */
    public function run()
    {
        \DB::beginTransaction();

        try {
            $roles = [
                0 => [
                    'id' => 1,
                    'name' => 'administrator',
                    'display_name' => 'Administrator',
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                1 => [
                    'id' => 2,
                    'name' => 'customer',
                    'display_name' => 'Customer',
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
            ];

            $new_roles = [];
            foreach ($roles as $key => $value) {
                $role = \DB::table('roles')
                        ->where('id', $value['id'])
                        ->first();

                if (isset($role)) {
                    continue;
                }
                $new_roles[] = $value;
            }

            \DB::table('roles')->insert($new_roles);

            \DB::commit();
        } catch (Exception $e) {
            throw new Exception('Exception occur '.$e);
            \DB::rollBack();
        }
    }
}
