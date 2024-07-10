<?php

namespace App\Http\Middleware;

use App;
use Auth;
use Closure;
use Hyvikk;

class SetLocale
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
            return redirect('installation');
        }
        if (Auth::user()->getMeta('language') != null) {
            App::setLocale(Auth::user()->getMeta('language'));
        } else {
            App::setLocale(Hyvikk::get('language'));
        }
        return $next($request);
    }
    public function alreadyInstalled()
    {
        return (file_exists(storage_path('installed')) && file_get_contents(storage_path('installed')) == "version4.0.3");
    }
}
