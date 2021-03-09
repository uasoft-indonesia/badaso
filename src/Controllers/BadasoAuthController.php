<?php

namespace Uasoft\Badaso\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use JWTAuth;
use stdClass;
use Tymon\JWTAuth\Exceptions\JWTException;
use Uasoft\Badaso\Exceptions\SingleException;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Helpers\AuthenticatedUser;
use Uasoft\Badaso\Mail\SendUserVerification;
use Uasoft\Badaso\Middleware\BadasoAuthenticate;
use Uasoft\Badaso\Models\User;
use Uasoft\Badaso\Models\UserVerification;
use Webpatser\Uuid\Uuid;

class BadasoAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware(BadasoAuthenticate::class, ['except' => ['login', 'register', 'forgetPassword', 'resetPassword', 'verify']]);
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

            $should_verify_email = true;
            if ($should_verify_email) {
                $user = auth()->user();
                if (is_null($user->email_verified_at)) {
                    throw new SingleException('Email is not verified');
                }
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
            DB::beginTransaction();
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

            $should_verify_email = true;
            if (!$should_verify_email) {
                $token = auth()->login($user);

                DB::commit();

                return $this->createNewToken($token, auth()->user());
            } else {
                $token = rand(111111, 999999);
                $token_lifetime = env('VERIFICATION_TOKEN_LIFETIME', 5);
                $expired_token = date('Y-m-d H:i:s', strtotime("+$token_lifetime minutes", strtotime(date('Y-m-d H:i:s'))));
                $data = [
                    'user_id' => $user->id,
                    'verification_token' => $token,
                    'expired_at' => $expired_token,
                    'count_incorrect' => 0,
                ];

                UserVerification::firstOrCreate($data);

                $this->sendVerificationToken(['user' => $user, 'token' => $token]);

                DB::commit();

                return ApiResponse::success([
                    'message' => 'An verification mail has been send to your email',
                ]);
            }
        } catch (Exception $e) {
            DB::rollBack();

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
            $data['user'] = json_decode(json_encode($user));

            return ApiResponse::success($data);
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

    public function sendVerificationToken($data)
    {
        return Mail::to($data['user']['email'])->queue(new SendUserVerification($data));
    }

    public function verify(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'exists:users'],
                'token' => ['required'],
            ]);

            $user = User::where('email', $request->email)->first();
            $user_verification = UserVerification::where('verification_token', $request->token)
                ->where('user_id', $user->id)
                ->first();

            if ($user_verification) {
                if (date('Y-m-d H:i:s') > $user->expired_at) {
                    $user_verification->delete();
                    throw new SingleException('Verification token expired');
                }
                $user->email_verified_at = date('Y-m-d H:i:s');
                $user->save();

                $user_verification->delete();
            } else {
                throw new SingleException('Invalid verification token');
            }

            $token = auth()->login($user);

            return $this->createNewToken($token, auth()->user());
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
