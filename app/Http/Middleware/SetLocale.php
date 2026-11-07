<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var array<string, string> $allowedLocales */
        $allowedLocales = (array) config('app.locales', []);
        /** @var list<string> $localeKeys */
        $localeKeys = array_map(strval(...), array_keys($allowedLocales));
        $requestedLocale = $request->query('lang');

        if (! is_string($requestedLocale) || empty($requestedLocale)) {
            $requestedLocale = $request->getPreferredLanguage($localeKeys);
        }

        if (! is_string($requestedLocale) || ! array_key_exists($requestedLocale, $allowedLocales)) {
            $requestedLocale = (string) config('app.locale', 'en');
        }

        $request->session()->put('locale', $requestedLocale);
        app()->setLocale($requestedLocale);

        return $next($request);
    }
}
