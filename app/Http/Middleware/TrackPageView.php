<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;

class TrackPageView
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        try {
            $isHtml = str_contains((string) $response->headers->get('Content-Type'), 'text/html');
            $ua = (string) $request->userAgent();

            if (
                $request->isMethod('GET')
                && ! $request->ajax()
                && $response->getStatusCode() === 200
                && $isHtml
                && ! $request->is('admin*', 'livewire*', 'api/*', 'up')
                && ! preg_match('/bot|crawl|spider|slurp|curl|wget|preview|facebookexternalhit/i', $ua)
            ) {
                PageView::create([
                    'path' => '/' . ltrim($request->path(), '/'),
                    'ip_hash' => hash('sha256', $request->ip() . config('app.key')),
                    'referer' => $request->headers->get('referer') ? substr($request->headers->get('referer'), 0, 255) : null,
                    'user_agent' => substr($ua, 0, 255),
                    'user_id' => $request->user()?->id,
                ]);
            }
        } catch (\Throwable $e) {
            // nunca romper la página por un error de métricas
        }

        return $response;
    }
}
