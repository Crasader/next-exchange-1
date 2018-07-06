<?php

namespace App\Http\Middleware;

use Closure;
use Cookie;

class CheckReferral
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( !$request->hasCookie('referral') AND $request->query('ref') ) {
            Cookie::queue('referral', $request->query('ref'));
        }
        return $next($request);
    }
}
