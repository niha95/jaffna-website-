<?php

namespace App\Http\Middleware;

use Closure;

class SetControlPanelLocale
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
        $locale = $request->segment(2);

        if(in_array($locale, config('control_panel.locales'))) {
            app()->setLocale($locale);

            session()->put(['control_panel.locale' => $locale]);
        } else {
            return redirect()->route('control_panel.home', config('control_panel.default_locale'));
        }

        return $next($request);
    }
}
