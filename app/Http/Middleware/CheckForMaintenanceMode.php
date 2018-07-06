<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Symfony\Component\Http\Kernel\Exception\Http\Exception;

class CheckForMaintenanceMode
{

    protected $request;
    protected $app;

    public function __construct(Application $app, Request $request)

    {
        $this->app = $app;
        $this->request = $request;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Illuminate\Http\Request $request
     * @param  Closure $next
     * @return mixed
     */

    public function handle($request, Closure $next)

    {
        if ($this->app->isDownForMaintenance() &&
            !in_array($this->request->getClientIp(), ['127.0.0.1'])) //add IP addresses you want to exclude
        {
            throw new Http\Exception(503);

        }
        return $next($request);

    }
}