<?php

namespace App\Http\Middleware;

use App\Models\Misc;
use Closure;

class SiteClosed
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
        if(Misc::siteIsClosed()) {
            return redirect('closed');
        }

        return $next($request);
    }
}
