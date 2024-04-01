<?php

namespace Uasoft\Badaso\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Uasoft\Badaso\Models\User;
use Uasoft\Badaso\Models\UserRole;

class CallHelperTest
{
    public static $KEY_TOKEN_ADMIN_AUTHORIZE = 'TOKEN_ADMIN_AUTHORIZE';
    public static $ADMINISTRATOR_ROLE_ID = 1;

    public static function getTokenUserAdminAuthorize()
    {
        return Cache::store('file')->get(self::$KEY_TOKEN_ADMIN_AUTHORIZE);
    }

    public static function getTokenUserAdminAuthorizeBearer()
    {
        $authorize = self::getTokenUserAdminAuthorize();

        return "Bearer {$authorize}";
    }

    public static function setTokenUserAdminAuthorize($token)
    {
        Cache::store('file')->set(self::$KEY_TOKEN_ADMIN_AUTHORIZE, $token);
    }

    public static function getDataCreateOrUpdateUserAdmin()
    {
        $name = env('BADASO_USER_NAME', 'badaso.test');
        $username = $name;
        $email = "{$name}@test.com";
        $password = Hash::make($name);
        $email_verified_at = date('Y-m-d H:i:s');

        return [
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'email_verified_at' => $email_verified_at,
        ];
    }

    public static function getUrlApiV1Prefix($path)
    {
        $api_prefix = config('badaso.api_route_prefix', 'badaso-api');

        return "{$api_prefix}/v1{$path}";
    }

    public static function getUserAdminRole()
    {
        $data_create_or_update_user_admin = CallHelperTest::getDataCreateOrUpdateUserAdmin();

        $user = User::where('email', $data_create_or_update_user_admin['email'])
            ->first();

        if (! isset($user)) {
            $user = User::create($data_create_or_update_user_admin);
        } else {
            $user->update($data_create_or_update_user_admin);
        }

        $user_role = UserRole::where('user_id', $user->id)->first();
        if (! isset($user_role)) {
            $user_role = UserRole::create(['user_id' => $user->id, 'role_id' => self::$ADMINISTRATOR_ROLE_ID]);
        }

        return $user;
    }

    public static function handleUserAdminAuthorize(TestCase $test_case)
    {
        $user = self::getUserAdminRole();
        $response = $test_case->json('POST', CallHelperTest::getUrlApiV1Prefix('/auth/login'), [
            'email' => $user->email,
            'password' => $user->name,
            'remember' => false,
        ]);
        $response->assertSuccessful();

        // get access token from request login and save token to cache
        $token_authorize = $response->json('data.accessToken');
        CallHelperTest::setTokenUserAdminAuthorize($token_authorize);
        $bearer_token_authorize = "Bearer {$token_authorize}";

        // get access token from cache
        $cache_token_authorize = CallHelperTest::getTokenUserAdminAuthorize();
        $bearer_cache_token_authorize = CallHelperTest::getTokenUserAdminAuthorizeBearer();

        // test same response access token with cache access token
        $test_case->assertSame($token_authorize, $cache_token_authorize);
        $test_case->assertSame($bearer_token_authorize, $bearer_cache_token_authorize);
    }

    public static function handleDeleteUserAdmin()
    {
        $name = env('BADASO_USER_NAME', 'badaso.test');
        $email = "{$name}@test.com";
        User::where('email', $email)->first()->delete();
    }

    public static function withAuthorizeBearer(TestCase $test_case): TestCase
    {
        return $test_case->withHeader('Authorization', self::getTokenUserAdminAuthorizeBearer());
    }

    public static function setCache($key, $value)
    {
        Cache::store('file')->set($key, $value);
    }

    public static function getCache($key)
    {
        return Cache::store('file')->get($key);
    }

    public static function clearCache()
    {
        Cache::store('file')->clear();
    }

    public static function login(TestCase $test_case)
    {
        $user = self::getUserAdminRole();

        $login = $test_case->post(CallHelperTest::getApiAuth('login'), [
            'email' => $user->email,
            'password' => $user->name,
            'remember' => false,
        ]);

        return $login->json('data.accessToken');
    }

    public static function getApiAuth($path)
    {
        return 'badaso-api/v1/auth/'.$path;
    }

    public static function getApiV1($path)
    {
        return 'badaso-api/module/post/v1'.$path;
    }
}
