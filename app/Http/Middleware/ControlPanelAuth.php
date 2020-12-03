<?php

namespace App\Http\Middleware;

use Closure;

class ControlPanelAuth {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->guest() || !auth()->user()->canAccessControlPanel()) {
            auth()->logout();

            if ($request->ajax()) {
                return response('Unauthorized', 401);
            } else {
                return redirect()->guest(route('control_panel.show_login'));
            }
        }

        return $next($request);
    }
}
