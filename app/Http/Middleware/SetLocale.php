<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $allowedLocales = config('app.locales');
        $requestedLocale = $request->query('lang', config('app.locale'));

        if (! in_array($requestedLocale, array_keys($allowedLocales), true)) {
            $requestedLocale = config('app.locale');
        }

        $request->session()->put('locale', $requestedLocale);
        app()->setLocale($requestedLocale);

        return $next($request);
    }
}
