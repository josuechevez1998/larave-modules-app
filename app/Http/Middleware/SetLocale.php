<?php

namespace App\Http\Middleware;

use App\Support\Locale\SupportedLocales;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->user()?->locale
            ?? $request->session()->get('locale')
            ?? config('app.locale');

        if (! SupportedLocales::isValid(is_string($locale) ? $locale : null)) {
            $locale = SupportedLocales::DEFAULT;
        }

        app()->setLocale($locale);

        return $next($request);
    }
}
