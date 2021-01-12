<?php

use Illuminate\Database\Seeder;

class SiteManagementSeeder extends Seeder
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
            $settings = [
                0 => [
                    'key' => 'adminPanelTitle',
                    'display_name' => 'Admin Panel Title',
                    'value' => 'Badaso',
                    'details' => '',
                    'type' => 'text',
                    'order' => 1,
                    'group' => 'adminPanel',
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                1 => [
                    'key' => 'dashboardDescription',
                    'display_name' => 'Admin Panel Description',
                    'value' => 'Badaso, SPA CRUD Generator',
                    'details' => '',
                    'type' => 'text',
                    'order' => 2,
                    'group' => 'adminPanel',
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                2 => [
                    'key' => 'adminPanelLogo',
                    'display_name' => 'Admin Panel Logo',
                    'value' => '',
                    'details' => '',
                    'type' => 'upload_image',
                    'order' => 3,
                    'group' => 'adminPanel',
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                3 => [
                    'key' => 'adminPanelHeaderColor',
                    'display_name' => 'Admin Panel Header Color',
                    'value' => '#2962ff',
                    'details' => '',
                    'type' => 'color_picker',
                    'order' => 4,
                    'group' => 'adminPanel',
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                4 => [
                    'key' => 'landingPageTitle',
                    'display_name' => 'Landing Page Title',
                    'value' => 'Badaso',
                    'details' => '',
                    'type' => 'text',
                    'order' => 1,
                    'group' => 'landingPage',
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
            ];

            $new_settings = [];
            foreach ($settings as $key => $value) {
                $setting = \DB::table('configurations')->where('key', $value['key'])->first();
                if (isset($setting)) {
                    continue;
                }
                $new_settings[] = $value;
            }

            \DB::table('configurations')->insert($new_settings);

            \DB::commit();
        } catch (Exception $e) {
            throw new Exception('Exception occur '.$e);
            \DB::rollBack();
        }
    }
}
