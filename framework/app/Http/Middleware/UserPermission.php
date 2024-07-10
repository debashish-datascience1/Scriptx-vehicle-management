<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class UserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $m)
    {
        if ($m != "S") {
            if (Auth::user()->user_type == "S" || Auth::user()->user_type == "O") {
                $modules = unserialize(Auth::user()->getMeta('module'));

                if ($m == 0 && Auth::user()->user_type == "S") {
                    return $next($request);
                }
                if (!in_array($m, $modules)) {
                    abort(404);
                    // return redirect("/");
                }
            }
        }
        if ($m == "S") {
            if (Auth::user()->user_type != "S") {
                abort(404);
            }
        }

        return $next($request);
    }
}
