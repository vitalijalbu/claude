<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocaleFromCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): mixed  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $supportedLocales = config('app.supported_locales', ['en', 'it']);

        $cookieName = 'locale';

        $locale = $request->cookie($cookieName);

        if (! $locale) {
            $locale = $request->getPreferredLanguage($supportedLocales);
        }

        if (! $locale || ! in_array($locale, $supportedLocales)) {
            $locale = config('app.locale', 'en');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
