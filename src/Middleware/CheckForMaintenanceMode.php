<?php

namespace Uasoft\Badaso\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Uasoft\Badaso\Models\Configuration;

class CheckForMaintenanceMode
{
    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * The URIs that should be accessible while maintenance mode is enabled.
     *
     * @var array
     */
    protected $excepts = [];

    /**
     * URIs prefix.
     *
     * @var string
     */
    protected $prefix = null;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->excepts = config('badaso.whitelist.web');
        $this->prefix = config('badaso.admin_panel_route_prefix');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle($request, Closure $next)
    {
        if ($this->isUnderMaintenance() || $this->app->isDownForMaintenance()) {
            if ($this->isAdministrator()) {
                return $next($request);
            }

            if ($this->inExceptArray($request)) {
                return $next($request);
            }

            return redirect($this->prefix.'/maintenance');
        }

        return $next($request);
    }

    protected function isUnderMaintenance()
    {
        $maintenance = Configuration::where('key', 'maintenance')->firstOrFail();

        return $maintenance->value === '1' ? true : false;
    }

    protected function inExceptArray($request)
    {
        foreach ($this->excepts as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }

    protected function isAdministrator()
    {
        $user = auth()->user();
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
