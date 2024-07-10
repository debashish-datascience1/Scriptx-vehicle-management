<?php

namespace App\Http\Middleware;

use Closure;

class IsInstalled
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
        if (!$this->alreadyInstalled()) {
            return redirect("installation");
        }

        return $next($request);
    }

    public function alreadyInstalled()
    {
        return (file_exists(storage_path('installed')) && file_get_contents(storage_path('installed')) == "version4.0.3");
    }
}
