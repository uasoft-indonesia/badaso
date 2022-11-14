<?php

namespace Uasoft\Badaso\Tests\Feature;

use Tests\TestCase;
use Uasoft\Badaso\Helpers\CallHelperTest;

class BadasoApiConfigurationTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testMaintenanceWithoutKey()
    {
        $set_key_maintenance = config(['badaso.badaso_maintenance' => null]);
        $request_data = [
            'path' => '/badaso-dashboard/login',
        ];

        $path_secret_login = config('badaso.secret_login_prefix');
        $request_data_secret_login = [
            'path' => '/badaso-dashboard/'.$path_secret_login,
        ];

        $response = $this->json('POST', CallHelperTest::getUrlApiV1Prefix('/maintenance'), $request_data);
        $data = $response['data'];
        $this->assertFalse($data['maintenance']);

        $response_secret_login = $this->json('POST', CallHelperTest::getUrlApiV1Prefix('/maintenance'), $request_data_secret_login);
        $data_secret_login = $response_secret_login['data'];
        $this->assertFalse($data_secret_login['maintenance']);
    }

    public function testMaintenanceWithKeyTrue()
    {
        $set_key_maintenance = config(['badaso.badaso_maintenance' => true]);
        $request_data = [
            'path' => '/badaso-dashboard/login',
        ];

        $path_secret_login = config('badaso.secret_login_prefix');
        $request_data_secret_login = [
            'path' => '/badaso-dashboard/'.$path_secret_login,
        ];

        $response = $this->json('POST', CallHelperTest::getUrlApiV1Prefix('/maintenance'), $request_data);
        $data = $response['data'];
        $this->assertTrue($data['maintenance']);

        $response_secret_login = $this->json('POST', CallHelperTest::getUrlApiV1Prefix('/maintenance'), $request_data_secret_login);
        $data_secret_login = $response_secret_login['data'];
        $this->assertFalse($data_secret_login['maintenance']);
    }

    public function testMaintenanceWithKeyFalse()
    {
        $set_key_maintenance = config(['badaso.badaso_maintenance' => false]);
        $request_data_login = [
            'path' => '/badaso-dashboard/login',
        ];

        $path_secret_login = config('badaso.secret_login_prefix');
        $request_data_secret_login = [
            'path' => '/badaso-dashboard/'.$path_secret_login,
        ];

        $response_login = $this->json('POST', CallHelperTest::getUrlApiV1Prefix('/maintenance'), $request_data_login);
        $data_login = $response_login['data'];
        $this->assertFalse($data_login['maintenance']);

        $response_secret_login = $this->json('POST', CallHelperTest::getUrlApiV1Prefix('/maintenance'), $request_data_secret_login);
        $data_secret_login = $response_secret_login['data'];
        $this->assertFalse($data_secret_login['maintenance']);
    }
}
