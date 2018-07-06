<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckIsAdmin
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
        $user_info = User::where('id', '=', auth()->user()->id)->with('roles')->select('id')->first();

        if(@$user_info->roles[0]->slug == 'admin'){

        } else{
            return redirect('/');
        }
        return $next($request);
    }
}
