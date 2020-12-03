<?php

namespace App\Http\Middleware;

use Closure;

class SetSiteLocale
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
        if(count(siteLocales()) == 1) {
            $locale = defaultLocale();
        } else {
            $locale = $request->segment(1);

            if(!in_array($locale, siteLocales())) {
                //dd(defaultLocale());
                return redirect()->route('site.home', defaultLocale());
            }
        }

        app()->setLocale($locale);

        session()->put(['site.locale' => $locale]);
        config()->set('site.current_locale', $locale);

        return $next($request);
    }
}
