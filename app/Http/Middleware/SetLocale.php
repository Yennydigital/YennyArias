<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', 'es');
        app()->setLocale(in_array($locale, ['es', 'en'], true) ? $locale : 'es');
        app()->setFallbackLocale('es');

        return $next($request);
    }
}
