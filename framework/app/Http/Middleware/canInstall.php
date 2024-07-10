<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Class canInstall
 * @package Froiden\LaravelInstaller\Middleware
 */

class canInstall
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

        if ($this->alreadyInstalled()) {
            return redirect('admin');
        }

        return $next($request);
    }

    /**
     * If application is already installed.
     *
     * @return bool
     */
    public function alreadyInstalled()
    {
        return (file_exists(storage_path('installed')) && file_get_contents(storage_path('installed')) == "version4.0.3");
    }

}
