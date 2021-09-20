<?php

namespace Uasoft\Badaso\Helpers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use stdClass;
use Uasoft\Badaso\Models\PersonalAccessToken;
use Uasoft\Badaso\Models\User;

class TokenManagement
{
    public static $REMEMBER_ABILITY = 'remember';
    public static $EXPIRED_DATE_ABILITY = 'expired_date';
    public static $KEY_GET_TOKEN = 'get_token';

    public User $user;
    public string $token;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getRememberAbility(bool $remember = false): string
    {
        $key = self::$REMEMBER_ABILITY;

        return "{$key}:{$remember}";
    }

    public function getTokenName(string $email): string
    {
        $key = self::$KEY_GET_TOKEN;

        return Hash::make("{$key}:{$email}");
    }

    public function getExpiredDateAbility(bool $remember): string
    {
        $key = self::$EXPIRED_DATE_ABILITY;

        if ($remember) {
            $expired_time = intval(config('sanctum.expired_remember'));
        // minutes, hours, day
        } else {
            $expired_time = intval(config('sanctum.expired_no_remember'));
            // minutes, hours, day
        }

        $expired_date = date('Y-m-d H:i:s', strtotime("+{$expired_time} minutes"));

        return "{$key}:{$expired_date}";
    }

    public function createToken(bool $remember = false): self
    {
        $user = $this->user;
        $email = $user->email;

        $remember_ability = $this->getRememberAbility($remember);
        $expired_date_ability = $this->getExpiredDateAbility($remember);
        $token_name = $this->getTokenName($email);

        $this->token = (string) $this->user->createToken($token_name, [$remember_ability, $expired_date_ability])->plainTextToken;

        return $this;
    }

    public function refreshToken(): self
    {
        $personal_access_token = $this->user->currentAccessToken();
        $remember_ability = $this->findAbility(self::$REMEMBER_ABILITY, $personal_access_token);
        $remember = $remember_ability->value;

        $personal_access_token->delete();

        return $this->createToken($remember);
    }

    public function deleteToken(): void
    {
        $personal_access_token = $this->user->currentAccessToken();
        $personal_access_token->delete();
    }

    public function response(): object
    {
        $obj = new stdClass();
        $obj->access_token = $this->token;
        $obj->token_type = 'Bearer';
        $obj->user = $this->user;

        return ApiResponse::success($obj);
    }

    public function hasTimeoutConnection()
    {
        $personal_access_token = $this->user->currentAccessToken();

        $expired_date_ability = $this->findAbility(self::$EXPIRED_DATE_ABILITY, $personal_access_token);
        $expired_date = $expired_date_ability->value;
        $expired_time = strtotime($expired_date);

        $date_now_time = strtotime(date('Y-m-d H:i:s'));

        if ($expired_date < $date_now_time) {
            $personal_access_token->delete();
        }
    }

    public function findAbility($ability_search, PersonalAccessToken $personal_access_token): object
    {
        $abilities = $personal_access_token->abilities;

        $abilities = array_filter($abilities, function ($ability) use ($ability_search) {
            return strstr($ability, $ability_search);
        });

        $ability = array_values($abilities)[0];

        $pos_split = strpos($ability, ':');
        $ability_key = trim(substr($ability, 0, $pos_split));
        $ability_value = trim(substr($ability, $pos_split + 1));

        return (object) [
            'origin' => $ability,
            'key' => $ability_key,
            'value' => $ability_value,
        ];
    }

    public static function fromUser(User $user): self
    {
        return new TokenManagement($user);
    }

    public static function fromAuth(): self
    {
        $user = Auth::guard(config('badaso.authenticate.guard'))->user();

        return new TokenManagement($user);
    }
}
