<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class SuperAdmin {

	public function handle($request, Closure $next) {
		if (Auth::user()->user_type != "S") {
			return redirect("admin");
		}
		return $next($request);
	}
}
