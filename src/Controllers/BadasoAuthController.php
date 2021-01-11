<?php

namespace Uasoft\Badaso\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use stdClass;
use Tymon\JWTAuth\Exceptions\JWTException;
use Uasoft\Badaso\Exceptions\SingleException;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Helpers\AuthenticatedUser;
use Uasoft\Badaso\Middleware\BadasoAuthenticate;
use Uasoft\Badaso\Models\User;
use Webpatser\Uuid\Uuid;

class BadasoAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(BadasoAuthenticate::class, ['except' => ['login', 'register', 'forgetPassword', 'resetPassword']]);
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required'],
                'password' => ['required'],
            ]);

            $token = null;

            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];

            // if (!$token = JWTAuth::attempt($credentials)) {
            if (!$token = auth()->attempt($credentials)) {
                throw new SingleException(__('badaso::validation.auth.invalid_credentials'));
            }

            return $this->createNewToken($token, auth()->user());
        } catch (JWTException $e) {
            return ApiResponse::failed($e);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function logout(Request $request)
    {
        try {
            auth()->logout();
            // auth()->invalidate();

            return ApiResponse::success();
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
            ]);

            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
            ]);

            // $token = JWTAuth::fromUser($user);
            $token = auth()->login($user);

            return $this->createNewToken($token, auth()->user());
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function refreshToken(Request $request)
    {
        try {
            return $this->createNewToken(auth()->refresh(), auth()->user());

            return ApiResponse::success();
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function getAuthenticatedUser()
    {
        try {
            if (!$user = AuthenticatedUser::getUser()) {
                throw new SingleException(__('badaso::validation.auth.user_not_found'));
            }

            // $user->token_payload = auth()->payload();

            return ApiResponse::success(json_decode(json_encode($user)));
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    protected function createNewToken($token, $user)
    {
        $obj = new stdClass();
        $obj->access_token = $token;
        $obj->token_type = 'bearer';
        $obj->user = $user;
        $obj->expires_in = auth()->factory()->getTTL() * 60;

        return ApiResponse::success($obj);
    }

    public function verify(Request $request)
    {
        try {
            $request->validate([
                'verification_code' => ['required'],
            ]);

            return ApiResponse::success($request->all);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            if (!$user = auth()->user()) {
                throw new SingleException(__('badaso::validation.auth.user_not_found'));
            }

            $request->validate([
                'old_password' => [
                    'required',
                    function ($attribute, $value, $fail) use ($user) {
                        if (!Hash::check($value, $user->password)) {
                            $fail(__('badaso::validation.auth.wrong_old_password'));
                        }
                    },
                ],
                'new_password' => [
                    'required',
                    'confirmed',
                    'string',
                    'min:6',
                    'confirmed',
                    function ($attribute, $value, $fail) use ($user) {
                        if (Hash::check($value, $user->password)) {
                            $fail(__('badaso::validation.auth.password_not_changes'));
                        }
                    },
                ],
            ]);

            $user = User::find($user->id);
            $user->password = Hash::make($request->new_password);
            $user->save();

            return ApiResponse::success($user);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function forgetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email', 'exists:users,email'],
            ]);

            $token = Uuid::generate();

            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            // SEND MAIL HERE

            return ApiResponse::success();
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required|exists:password_resets,token',
                'new_password' => [
                    'required',
                    'confirmed',
                    'string',
                    'min:6',
                    'confirmed',
                ],
            ]);

            $password_resets = DB::SELECT('SELECT * FROM password_resets WHERE token = :token', [
                'token' => $request->token,
            ]);

            $password_reset = collect($password_resets)->first();

            $user = User::where('email', $password_reset->email)->first();
            $user->password = Hash::make($request->new_password);
            $user->save();

            return ApiResponse::success();
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }
}
