<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectAdminToPanel
{
    /** URL vieja => ya existe su version bajo /admin */
    private const MAP = ['dashboard', 'letters', 'letters/create', 'sealed'];

    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->is_admin && $request->isMethod('GET') && in_array($request->path(), self::MAP, true)) {
            $qs = $request->getQueryString();

            return redirect('/admin/'.$request->path().($qs ? '?'.$qs : ''));
        }

        return $next($request);
    }
}
