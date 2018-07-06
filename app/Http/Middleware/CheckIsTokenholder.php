<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Access;

class CheckIsTokenholder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $user_info = Access::where('user_id', '=', auth()->user()->id)->where('active', '=', '1')->first();

        if(@$user_info){

        } else{
            return response(view('access_denied_only_tokenholders'));
        }
        return $next($request);
    }
}
