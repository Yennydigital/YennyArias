<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Gifs
{
    public static function search(string $q, int $offset = 0, int $limit = 24): array
    {
        $q = mb_substr(trim($q), 0, 50);
        $endpoint = $q === '' ? 'trending' : 'search';

        return Cache::remember("gifs:$endpoint:".md5($q).":$offset", 600, function () use ($q, $endpoint, $offset, $limit) {
            $params = [
                'api_key' => config('services.giphy.key'),
                'limit'   => $limit,
                'offset'  => $offset,
                'rating'  => 'g',
                'lang'    => 'es',
            ];
            if ($q !== '') {
                $params['q'] = $q;
            }

            $res = Http::timeout(5)->get("https://api.giphy.com/v1/gifs/$endpoint", $params);
            if ($res->failed()) {
                return [];
            }

            return collect($res->json('data', []))
                ->map(fn ($g) => [
                    'id'      => $g['id'],
                    'preview' => $g['images']['fixed_width_small']['url'] ?? null,
                ])
                ->filter(fn ($g) => $g['preview'])
                ->values()->all();
        });
    }

    public static function url(string $id): ?string
    {
        return preg_match('/^[A-Za-z0-9]{5,30}$/', $id)
            ? "https://media.giphy.com/media/{$id}/giphy.gif"
            : null;
    }
}
