<?php

namespace Uasoft\Badaso\Middleware;

use Closure;
use Uasoft\Badaso\Helpers\CaseConvert;
use Uasoft\Badaso\Models\Configuration;
use Uasoft\Badaso\Helpers\AuthenticatedUser;
use Uasoft\Badaso\Helpers\ApiResponse;
use Illuminate\Contracts\Foundation\Application;

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
    protected $except = [];

    /**
     * When the maintenance down.
     *
     * @var array
     */
    protected $when_down = null;

    /**
     * URIs prefix.
     *
     * @var string
     */
    protected $api_prefix = null;
    protected $web_prefix = null;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->except = config('badaso.whitelist');
        $this->api_prefix = config('badaso.api_route_prefix');
        $this->web_prefix = config('badaso.admin_panel_route_prefix');
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
                dd('masuk');
            }

            if ($this->inExceptArray($request)) {
                return $next($request);
            }

            return redirect($this->web_prefix . '/maintenance');
        }

        return $next($request);
    }

    protected function isUnderMaintenance()
    {
        $maintenance = Configuration::where('key', 'maintenance')->firstOrFail();
        $this->when_down = (string) $maintenance->updated_at;
        return $maintenance->value === "1" ? true : false;
    }

    protected function isAdministrator()
    {
        $user_id = auth()->user();
        dd($user_id);
    }

    protected function inExceptArray($request)
    {
        $api = [];
        $web = [];

        foreach ($this->except['api'] as $key => $path) {
            $api[] = $this->api_prefix . $path;
        }

        foreach ($this->except['web'] as $key => $path) {
            $web[] = $this->web_prefix . $path;
        }

        $excepts = array_merge($web, $api);
        
        foreach ($excepts as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
}