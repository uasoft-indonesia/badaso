<?php

namespace Uasoft\Badaso\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Uasoft\Badaso\Helpers\ApiResponse;
use Uasoft\Badaso\Helpers\Redis\ConfigurationRedis;

class BadasoMaintenanceController extends Controller
{
    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    private $app;

    /**
     * The URIs that should be accessible while maintenance mode is enabled.
     *
     * @var array
     */
    private $excepts = [];

    /**
     * URIs prefix.
     *
     * @var string
     */
    private $prefix = null;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->excepts = config('badaso.whitelist.badaso');
        $this->prefix = config('badaso.admin_panel_route_prefix');
    }

    public function isMaintenance(Request $request)
    {
        if ($this->isUnderMaintenance() || $this->app->isDownForMaintenance()) {
            if ($this->isAdministrator()) {
                return ApiResponse::success(['maintenance' => false]);
            }

            if ($this->inExceptArray($request)) {
                return ApiResponse::success(['maintenance' => false]);
            }

            return ApiResponse::success(['maintenance' => true]);
        }

        return ApiResponse::success(['maintenance' => false]);
    }

    private function inExceptArray($request)
    {
        foreach ($this->excepts as $except) {
            $except = '/'.$this->prefix.$except;
            $path = trim($request->path, '/');

            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except) || $path == $except) {
                return true;
            }
        }

        return false;
    }

    private function isUnderMaintenance()
    {
        $model_configuration = ConfigurationRedis::get();
        $maintenance = $model_configuration->where('key', 'maintenance')->firstOrFail();

        return $maintenance->value === '1' ? true : false;
    }

    private function isAdministrator()
    {
        $guard = config('badaso.authenticate.guard');
        $user = Auth::guard($guard)->user();
        if (isset($user)) {
            $roles = $user->roles ?? null;
            if (isset($roles)) {
                $role = $roles->first() ?? null;
                if (isset($role)) {
                    $role_name = $role->name ?? null;
                    if ($role_name === 'administrator') {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
