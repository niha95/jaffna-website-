<?php

namespace App\Http\Middleware;

use Closure;

class SetReportsLocale
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
        app()->setLocale('ar');

        return $next($request);
    }
}
