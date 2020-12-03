<?php

namespace App\Http\Middleware;

use Closure;

class ReportsAuth
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
        if(auth()->guest() || !auth()->user()->canReport()) {
            return redirect()->route('site.reports.show_login');
        }

        return $next($request);
    }
}
