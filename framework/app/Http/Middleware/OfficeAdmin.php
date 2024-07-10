<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class OfficeAdmin
{

    public function handle($request, Closure $next)
    {

        if (Auth::user()->user_type == "D" && Auth::user()->id != $request->get("id")) {
            return redirect("admin");
        }
        return $next($request);
    }
    public function alreadyInstalled()
    {
        return (file_exists(storage_path('installed')) && file_get_contents(storage_path('installed')) == "version4.0.3");
    }
}
