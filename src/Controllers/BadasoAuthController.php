<?php

namespace Uasoft\Badaso\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Uasoft\Badaso\Exceptions\SingleException;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Helpers\AuthenticatedUser;
use Uasoft\Badaso\Helpers\Config;
use Uasoft\Badaso\Helpers\TokenManagement;
use Uasoft\Badaso\Mail\ForgotPassword;
use Uasoft\Badaso\Mail\SendUserVerification;
use Uasoft\Badaso\Models\Configuration;
use Uasoft\Badaso\Models\EmailReset;
use Uasoft\Badaso\Models\PasswordReset;
use Uasoft\Badaso\Models\Role;
use Uasoft\Badaso\Models\User;
use Uasoft\Badaso\Models\UserRole;
use Uasoft\Badaso\Models\UserVerification;
use Uasoft\Badaso\Traits\FileHandler;

class BadasoAuthController extends Controller
{
    use FileHandler;

    public function __construct()
    {
        $this->middleware(config('badaso.middleware.authenticate'), ['except' => ['secretLogin', 'login', 'register', 'forgetPassword', 'resetPassword', 'verify', 'reRequestVerification', 'validateTokenForgetPassword']]);
    }

    public function secretLogin(Request $request)
    {
        try {
            $remember = $request->get('remember', false);
            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];
            $request->validate([
                'email' => [
                    'required',
                    function ($attribute, $value, $fail) use ($credentials) {
                        if (! $token = Auth::attempt($credentials)) {
                            $fail(__('badaso::validation.auth.invalid_credentials'));
                        }
                    },
                ],
                'password' => ['required'],
            ]);
            $user = Auth::guard(config('badaso.authenticate.guard'))->user();

            // verify email verified at
            $should_verify_email = Config::get('adminPanelVerifyEmail') == '1' ? true : false;
            if ($should_verify_email) {
                if (is_null($user->email_verified_at)) {
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

                    return ApiResponse::success();
                }
            }
            activity('Authentication')
            ->causedBy(auth()->user() ?? null)
                ->withProperties(['attributes' => auth()->user()])
                ->log('Login has been success');

            return TokenManagement::fromUser($user)->createToken($remember)->response();
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function login(Request $request)
    {
        try {
            $remember = $request->get('remember', false);
            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];
            $request->validate([
                'email' => [
                    'required',
                    function ($attribute, $value, $fail) use ($credentials) {
                        if (! $token = Auth::attempt($credentials)) {
                            $fail(__('badaso::validation.auth.invalid_credentials'));
                        }
                    },
                ],
                'password' => ['required'],
            ]);
            $user = Auth::guard(config('badaso.authenticate.guard'))->user();

            // verify email verified at
            $should_verify_email = Config::get('adminPanelVerifyEmail') == '1' ? true : false;
            if ($should_verify_email) {
                if (is_null($user->email_verified_at)) {
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

                    return ApiResponse::success();
                }
            }
            activity('Authentication')
            ->causedBy(auth()->user() ?? null)
                ->withProperties(['attributes' => auth()->user()])
                ->log('Login has been success');

            return TokenManagement::fromUser($user)->createToken($remember)->response();
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function logout(Request $request)
    {
        try {
            TokenManagement::fromAuth()->deleteToken();
            activity('Authentication')
            ->causedBy(auth()->user() ?? null)
                ->withProperties(['attributes' => auth()->user()])
                ->log('Logout has been success');

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
                'username' => 'required|string|max:255|alpha_num',
                'phone' => 'required|numeric|min:6',
                'email' => 'required|string|email|max:255|unique:Uasoft\Badaso\Models\User',
                'password' => 'required|string|min:6|confirmed',
                'address' => 'required|string|max:255',
                'gender' => 'required|string',
            ]);

            $user = User::create([
                'name' => $request->get('name'),
                'username' => $request->get('username'),
                'phone' => $request->get('phone'),
                'address' => $request->get('address'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'gender' => $request->get('gender'),
            ]);

            $role = $this->getCustomerRole();

            $user_role = new UserRole();
            $user_role->user_id = $user->id;
            $user_role->role_id = $role->id;
            $user_role->save();

            $should_verify_email = Config::get('adminPanelVerifyEmail') == '1' ? true : false;
            if (! $should_verify_email) {
                $auth = Auth::login($user);

                DB::commit();

                activity('Authentication')
                    ->causedBy(auth()->user() ?? null)
                    ->withProperties(['attributes' => [
                        'user' => $user,
                        'role' => $user_role,
                    ]])
                    ->performedOn($user)
                    ->event('created')
                    ->log('Register has been created');

                return TokenManagement::fromUser($user)->createToken()->response();
            } else {
                User::where('email', $request->get('email'))->update([
                    'last_sent_token_at' => date('Y-m-d H:i:s'),
                ]);
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
                activity('Authentication')
                ->causedBy(auth()->user() ?? null)
                    ->withProperties(['attributes' => [
                        'user' => $user,
                        'role' => $user_role,
                    ]])
                    ->performedOn($user)
                    ->event('created')
                    ->log('Register has been created');

                return ApiResponse::success([
                    'message' => __('badaso::validation.verification.email_sended'),
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
            return TokenManagement::fromAuth()->refreshToken()->response();
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function getAuthenticatedUser()
    {
        try {
            if (! $user = AuthenticatedUser::getUser()) {
                throw new SingleException(__('badaso::validation.auth.user_not_found'));
            }

            $data['user'] = json_decode(json_encode($user));

            return ApiResponse::success($data);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function sendVerificationToken($data)
    {
        return Mail::to($data['user']['email'])->queue(new SendUserVerification($data));
    }

    public function verify(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'exists:Uasoft\Badaso\Models\User'],
                'token' => ['required'],
            ]);

            $user = User::where('email', $request->email)->first();
            $user_verification = UserVerification::where('verification_token', $request->token)
                ->where('user_id', $user->id)
                ->first();

            if ($user_verification) {
                if (strtotime(date('Y-m-d H:i:s')) > strtotime($user_verification->expired_at)) {
                    // $user_verification->delete();
                    throw new SingleException('EXPIRED');
                }
                $user->email_verified_at = date('Y-m-d H:i:s');
                $user->save();

                $user_verification->delete();
            } else {
                throw new SingleException(__('badaso::validation.verification.invalid_verification_token'));
            }

            Auth::login($user);

            return TokenManagement::fromAuth()->createToken()->response();
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            if (! $user = Auth::guard(config('badaso.authenticate.guard'))->user()) {
                throw new SingleException(__('badaso::validation.auth.user_not_found'));
            }

            $request->validate([
                'old_password' => [
                    'required',
                    function ($attribute, $value, $fail) use ($user) {
                        if (! Hash::check($value, $user->password)) {
                            $fail(__('badaso::validation.auth.wrong_old_password'));
                        }
                    },
                ],
                'new_password' => [
                    'required',
                    'confirmed',
                    'string',
                    'min:6',
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
            activity('Authentication')
            ->causedBy(auth()->user() ?? null)
                ->withProperties(['attributes' => $request->all()])
                ->performedOn($user)
                ->event('updated')
                ->log('Change password has been updated');

            return ApiResponse::success($user);
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function forgetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email', 'exists:Uasoft\Badaso\Models\User,email'],
            ]);

            $token = rand(111111, 999999);

            PasswordReset::insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            $user = User::where('email', $request->email)->first();
            Mail::to($request->email)->send(new ForgotPassword($user, $token));

            return ApiResponse::success();
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function validateTokenForgetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email', 'exists:Uasoft\Badaso\Models\User,email'],
                'token' => [
                    'required',
                    'exists:Uasoft\Badaso\Models\PasswordReset,token',
                    function ($attribute, $value, $fail) use ($request) {
                        $password_resets = PasswordReset::where('token', $request->token)->where('email', $request->email)->get();
                        $password_reset = collect($password_resets)->first();
                        if (is_null($password_reset)) {
                            $fail('Token or Email invalid');
                        }
                    },
                ],
            ]);

            return ApiResponse::success();
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email', 'exists:Uasoft\Badaso\Models\User,email'],
                'token' => [
                    'required',
                    'exists:Uasoft\Badaso\Models\PasswordReset,token',
                    function ($attribute, $value, $fail) use ($request) {
                        $password_resets = PasswordReset::where('token', $request->token)->where('email', $request->email)->get();
                        $password_reset = collect($password_resets)->first();
                        if (is_null($password_reset)) {
                            $fail('Token or Email invalid');
                        }
                    },
                ],
            ]);

            $password_resets = PasswordReset::where('token', $request->token)->where('email', $request->email)->get();

            $password_reset = collect($password_resets)->first();

            $request->validate([
                'token' => [
                    function ($attribute, $value, $fail) use ($password_reset) {
                        if (is_null($password_reset)) {
                            $fail('Token Invalid');
                        }
                    },
                ],
            ]);

            $user = User::where('email', $password_reset->email)->first();
            $user->password = Hash::make($request->password);
            $saved = $user->save();

            if ($saved) {
                PasswordReset::where('token', $request->token)->delete();
            }

            return ApiResponse::success();
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }

    public function reRequestVerification(Request $request)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'email' => 'required|string|email|max:255',
            ]);

            $user = User::where('email', $request->email)->first();
            $time_wait_to_resend_token = Configuration::where('key', 'timeWaitResendToken')->first();
            $date_now = date('Y-m-d H:i:s');
            $time_out_token = date('Y-m-d H:i:s', strtotime($user->last_sent_token_at.' +  '.$time_wait_to_resend_token->value.' second'));
            $user_verification = UserVerification::where('user_id', $user->id)
                ->first();

            if ($date_now < $time_out_token) {
                throw new SingleException(__('badaso::validation.verification.time_wait_loading'));
            }

            User::where('email', $request->get('email'))->update([
                'last_sent_token_at' => date('Y-m-d H:i:s'),
            ]);

            if (! $user_verification) {
                throw new SingleException(__('badaso::validation.verification.verification_not_found'));
            }

            $token = rand(111111, 999999);
            $token_lifetime = env('VERIFICATION_TOKEN_LIFETIME', 5);
            $expired_token = date('Y-m-d H:i:s', strtotime("+$token_lifetime minutes", strtotime(date('Y-m-d H:i:s'))));

            $user_verification->verification_token = $token;
            $user_verification->expired_at = $expired_token;
            $user_verification->save();

            $this->sendVerificationToken(['user' => $user, 'token' => $token]);

            DB::commit();

            return ApiResponse::success([
                'message' => __('badaso::validation.verification.email_sended'),
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    protected function getCustomerRole()
    {
        $name_role = Configuration::where('key', 'defaultRoleRegistration')->select('value')->first();
        $role = Role::where('name', $name_role->value)->first();

        if (is_null($role)) {
            $role = new Role();
            $role->name = 'customer';
            $role->display_name = 'Customer';
            $role->save();
        }

        return $role;
    }

    public function updateProfile(Request $request)
    {
        DB::beginTransaction();

        try {
            $guard = config('badaso.authenticate.guard');

            if (! $user = Auth::guard($guard)->user()) {
                throw new SingleException(__('badaso::validation.auth.user_not_found'));
            }

            $user_id = Auth::guard($guard)->user()->id;

            $request->validate([
                'name' => 'required|string|max:255',
                'username' => "required|string|max:255|alpha_num|unique:Uasoft\Badaso\Models\User,username,{$user_id}",
                'avatar' => 'nullable',
                'phone' => 'nullable',
                'address' => 'nullable',
                'gender' => 'nullable',
            ]);

            $user = User::find($user->id);

            $user->name = $request->name;
            $user->username = $request->username;
            $user->phone = $request->phone;
            $user->address = $request->address;
            $user->avatar = $request->avatar;
            $user->additional_info = $request->additional_info;
            $user->gender = $request->gender;
            $user->save();

            DB::commit();
            activity('Authentication')
            ->causedBy(auth()->user() ?? null)
                ->withProperties(['attributes' => [
                    'old' => auth()->user(),
                    'new' => $user,
                ]])
                ->performedOn($user)
                ->event('updated')
                ->log('Update profile has been updated');

            return ApiResponse::success($user);
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function updateEmail(Request $request)
    {
        DB::beginTransaction();

        try {
            if (! $user = Auth::guard(config('badaso.authenticate.guard'))->user()) {
                throw new SingleException(__('badaso::validation.auth.user_not_found'));
            }

            $request->validate([
                'email' => 'required|email|unique:Uasoft\Badaso\Models\User,email',
            ]);

            $user = User::find($user->id);

            $should_verify_email = Config::get('adminPanelVerifyEmail') == '1' ? true : false;
            if ($should_verify_email) {
                $token = rand(111111, 999999);
                $token_lifetime = env('VERIFICATION_TOKEN_LIFETIME', 5);
                $expired_token = date('Y-m-d H:i:s', strtotime("+$token_lifetime minutes", strtotime(date('Y-m-d H:i:s'))));
                $data = [
                    'user_id' => $user->id,
                    'email' => $request->email,
                    'verification_token' => $token,
                    'expired_at' => $expired_token,
                    'count_incorrect' => 0,
                ];

                EmailReset::firstOrCreate($data);

                $user->email = $request->email;

                $this->sendVerificationToken(['user' => $user, 'token' => $token]);

                DB::commit();
                activity('Authentication')
                ->causedBy(auth()->user() ?? null)
                    ->withProperties(['attributes' => [
                        'old' => auth()->user()->email,
                        'new' => $user->email,
                    ]])
                    ->performedOn($user)
                    ->event('updated')
                    ->log('Update email has been updated');

                return ApiResponse::success([
                    'should_verify_email' => true,
                    'message' => __('badaso::validation.verification.email_sended'),
                ]);
            } else {
                $user->email = $request->email;
                $user->save();
            }

            DB::commit();

            return ApiResponse::success([
                'should_verify_email' => false,
                'user' => $user,
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return ApiResponse::failed($e);
        }
    }

    public function verifyEmail(Request $request)
    {
        try {
            if (! $user = Auth::guard(config('badaso.authenticate.guard'))->user()) {
                throw new SingleException(__('badaso::validation.auth.user_not_found'));
            }

            $request->validate([
                'email' => ['required', 'unique:Uasoft\Badaso\Models\User', 'email'],
                'token' => ['required'],
            ]);

            $emai_reset = EmailReset::where('verification_token', $request->token)
                ->where('user_id', $user->id)
                ->first();

            $user = User::find($user->id);

            if ($emai_reset) {
                if (strtotime(date('Y-m-d H:i:s')) > strtotime($emai_reset->expired_at)) {
                    // $user_verification->delete();
                    throw new SingleException('EXPIRED');
                }
                $user->email = $request->email;
                $user->email_verified_at = date('Y-m-d H:i:s');
                $user->save();

                $emai_reset->delete();
            } else {
                throw new SingleException(__('badaso::validation.verification.invalid_verification_token'));
            }

            Auth::login($user);

            return TokenManagement::fromAuth()->createToken()->response();
        } catch (Exception $e) {
            return ApiResponse::failed($e);
        }
    }
}
