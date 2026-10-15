<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/** Titulo, artista y portada de un link ya validado por MusicLink. Nunca lanza excepciones. */
class MusicMeta
{
    /** @return array{provider:string,title:string,artist:?string,thumb:?string}|null */
    public static function fetch(string $url): ?array
    {
        $key = 'musicmeta:' . sha1($url);
        $hit = Cache::get($key);
        if ($hit !== null) {
            return $hit === false ? null : $hit;
        }

        try {
            $meta = self::lookup($url);
        } catch (\Throwable $e) {
            $meta = null;
        }

        Cache::put($key, $meta ?? false, $meta ? now()->addDays(30) : now()->addMinutes(10));

        return $meta;
    }

    protected static function lookup(string $url): ?array
    {
        $p = parse_url($url);
        $host = strtolower($p['host'] ?? '');
        $provider = $title = $artist = $thumb = null;

        if (str_contains($host, 'spotify.com')) {
            $provider = 'Spotify';
            $j = self::json('https://open.spotify.com/oembed', ['url' => $url]);
            $title = $j['title'] ?? null;
            $thumb = $j['thumbnail_url'] ?? null;
        } elseif (str_contains($host, 'youtube.com') || $host === 'youtu.be') {
            $provider = 'YouTube';
            $j = self::json('https://www.youtube.com/oembed', ['url' => $url, 'format' => 'json']);
            $title = $j['title'] ?? null;
            $artist = $j['author_name'] ?? null;
            $thumb = $j['thumbnail_url'] ?? null;
        } elseif (str_contains($host, 'deezer.com')) {
            $provider = 'Deezer';
            $j = self::json('https://api.deezer.com/oembed', ['url' => $url, 'format' => 'json']);
            $title = $j['title'] ?? null;
            $artist = $j['author_name'] ?? null;
            $thumb = $j['thumbnail_url'] ?? null;
        } elseif ($host === 'music.apple.com') {
            $provider = 'Apple Music';
            parse_str($p['query'] ?? '', $q);
            $path = trim($p['path'] ?? '', '/');
            $id = $q['i'] ?? basename($path);
            if (! ctype_digit((string) $id)) {
                return null;
            }
            $seg = explode('/', $path)[0] ?? '';
            $cc = preg_match('/^[a-z]{2}$/i', $seg) ? strtolower($seg) : 'us';
            $j = self::json('https://itunes.apple.com/lookup', ['id' => $id, 'country' => $cc]);
            $r = $j['results'][0] ?? [];
            $title = $r['trackName'] ?? ($r['collectionName'] ?? null);
            $artist = $r['artistName'] ?? null;
            $thumb = isset($r['artworkUrl100']) ? str_replace('100x100', '400x400', $r['artworkUrl100']) : null;
        } else {
            return null;
        }

        if (! is_string($title) || trim($title) === '') {
            return null;
        }

        return [
            'provider' => $provider,
            'title' => Str::limit(trim($title), 120),
            'artist' => is_string($artist) && $artist !== '' ? Str::limit($artist, 80) : null,
            'thumb' => is_string($thumb) ? self::image($thumb) : null,
        ];
    }

    protected static function json(string $endpoint, array $query): array
    {
        $r = Http::timeout(4)->acceptJson()->get($endpoint, $query);

        return $r->ok() ? ($r->json() ?? []) : [];
    }

    /** Descarga la portada y la devuelve como data URI (solo jpeg/png/webp, max ~700 KB). */
    protected static function image(string $url): ?string
    {
        $p = parse_url($url);
        $host = $p['host'] ?? '';
        if (($p['scheme'] ?? '') !== 'https' || $host === '' || filter_var($host, FILTER_VALIDATE_IP)) {
            return null;
        }

        $r = Http::timeout(4)->get($url);
        $type = strtolower(strtok((string) $r->header('Content-Type'), ';'));
        if (! $r->ok() || ! preg_match('#^image/(jpeg|png|webp)$#', $type) || strlen($r->body()) > 700000) {
            return null;
        }

        return 'data:' . $type . ';base64,' . base64_encode($r->body());
    }
}
