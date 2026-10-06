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
        $requestedLocale = \request('lang');

        if (empty($requestedLocale)) {
            $requestedLocale = $request->getPreferredLanguage(array_keys($allowedLocales));
        }

        if (! array_key_exists($requestedLocale, $allowedLocales)) {
            $requestedLocale = config('app.locale');
        }

        $request->session()->put('locale', $requestedLocale);
        app()->setLocale($requestedLocale);

        return $next($request);
    }
}
