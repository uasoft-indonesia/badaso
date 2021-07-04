<?php

namespace Database\Seeders\Badaso;

use Illuminate\Database\Seeder;

class SiteManagementSeeder extends Seeder
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
            $settings = [
                0 => [
                    'key' => 'adminPanelTitle',
                    'display_name' => 'Admin Panel Title',
                    'value' => 'Badaso',
                    'details' => '',
                    'type' => 'text',
                    'order' => 1,
                    'group' => 'adminPanel',
                    'can_delete' => 0,
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                1 => [
                    'key' => 'adminPanelDescription',
                    'display_name' => 'Admin Panel Description',
                    'value' => 'Badaso, SPA CRUD Generator',
                    'details' => '',
                    'type' => 'text',
                    'order' => 2,
                    'group' => 'adminPanel',
                    'can_delete' => 0,
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                2 => [
                    'key' => 'adminPanelLogo',
                    'display_name' => 'Admin Panel Logo',
                    'value' => 'files/shares/logo.webp',
                    'details' => '{"type":"shares-only"}',
                    'type' => 'upload_image',
                    'order' => 3,
                    'group' => 'adminPanel',
                    'can_delete' => 0,
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                3 => [
                    'key' => 'adminPanelHeaderColor',
                    'display_name' => 'Admin Panel Header Color',
                    'value' => '#ffffff',
                    'details' => '',
                    'type' => 'color_picker',
                    'order' => 4,
                    'group' => 'adminPanel',
                    'can_delete' => 0,
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
                    'can_delete' => 0,
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                5 => [
                    'key' => 'adminPanelHeaderFontColor',
                    'display_name' => 'Admin Panel Header Font Color',
                    'value' => '#06bbd3',
                    'details' => '',
                    'type' => 'color_picker',
                    'order' => 5,
                    'group' => 'adminPanel',
                    'can_delete' => 0,
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                6 => [
                    'key' => 'adminPanelVerifyEmail',
                    'display_name' => 'Should verify email after register',
                    'value' => '1',
                    'details' => '',
                    'type' => 'switch',
                    'order' => 6,
                    'group' => 'adminPanel',
                    'can_delete' => 0,
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                7 => [
                    'key' => 'adminPanelLogoConfig',
                    'display_name' => 'Admin Panel Logo Config',
                    'value' => 'logo_only',
                    'details' => '{"items":[{"label":"Logo Only","value":"logo_only"},{"label":"Text Only","value":"text_only"},{"label":"Logo & Text","value":"logo_and_text"}]}',
                    'type' => 'select',
                    'order' => 1,
                    'group' => 'adminPanel',
                    'can_delete' => 0,
                    'created_at' => '2021-03-16 05:36:00',
                    'updated_at' => '2021-03-16 05:36:00',
                ],
                8 => [
                    'key' => 'favicon',
                    'display_name' => 'Favicon',
                    'value' => 'files/shares/favicon.png',
                    'details' => '{"type":"shares-only"}',
                    'type' => 'upload_image',
                    'order' => 7,
                    'group' => 'adminPanel',
                    'can_delete' => 0,
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                9 => [
                    'key' => 'maintenance',
                    'display_name' => 'Maintenance for all pages.',
                    'value' => '0',
                    'details' => 'Admin still can access the dashboard.',
                    'type' => 'switch',
                    'order' => 8,
                    'group' => 'adminPanel',
                    'can_delete' => 0,
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
                10 => [
                    'key' => 'authBackgroundImage',
                    'display_name' => 'Background image for login page.',
                    'value' => 'files/shares/auth-bg.jpg',
                    'details' => 'Admin still can access the dashboard.',
                    'type' => 'upload_image',
                    'order' => 9,
                    'group' => 'adminPanel',
                    'can_delete' => 0,
                    'created_at' => '2021-01-01 15:26:06',
                    'updated_at' => '2021-01-01 15:26:06',
                ],
            ];

            foreach ($settings as $key => $value) {
                \DB::table('configurations')->where('key', $value['key'])->delete();
            }

            \DB::table('configurations')->insert($settings);

            \DB::commit();
        } catch (Exception $e) {
            throw new Exception('Exception occur '.$e);
            \DB::rollBack();
        }
    }
}
