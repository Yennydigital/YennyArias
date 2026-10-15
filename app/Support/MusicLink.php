<?php

namespace App\Support;

/** Solo acepta enlaces https de YouTube, Spotify, Apple Music, Deezer y Amazon Music. */
class MusicLink
{
    private const HOSTS = [
        'youtube.com', 'youtu.be', 'spotify.com',
        'music.apple.com', 'deezer.com', 'music.amazon.com',
    ];

    public static function clean(?string $url): ?string
    {
        $url = trim((string) $url);
        if ($url === '' || strlen($url) > 500) {
            return null;
        }

        $p = parse_url($url);
        if (! $p || ($p['scheme'] ?? '') !== 'https' || isset($p['user']) || isset($p['pass'])) {
            return null;
        }

        $host = strtolower($p['host'] ?? '');
        if ($host === '') {
            return null;
        }

        if (preg_match('/^music\.amazon\.[a-z.]+$/', $host)) {
            return $url;
        }

        foreach (self::HOSTS as $allowed) {
            if ($host === $allowed || str_ends_with($host, '.' . $allowed)) {
                return $url;
            }
        }

        return null;
    }
}
