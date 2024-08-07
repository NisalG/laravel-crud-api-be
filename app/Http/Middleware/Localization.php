<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        dump('auth(\'sanctum\')->check()',  auth('sanctum')->check());
        dd(auth('sanctum')->user()->locale);
        $locale = $request->get('locale', auth('sanctum')->check() ? auth('sanctum')->user()->locale : config('app.locale'));
        App::setLocale($locale);

        return $next($request);
    }
}
