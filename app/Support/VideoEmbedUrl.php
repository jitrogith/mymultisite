<?php

namespace App\Support;

class VideoEmbedUrl
{
    public static function make(string $source, string $url): ?string
    {
        $parts = parse_url($url);
        if (! is_array($parts) || ! in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true)) {
            return null;
        }

        $host = strtolower($parts['host'] ?? '');
        $path = trim($parts['path'] ?? '', '/');

        return match ($source) {
            'youtube' => self::youtube($host, $path, $parts['query'] ?? ''),
            'vimeo' => self::vimeo($host, $path),
            'dailymotion' => self::dailymotion($host, $path),
            'videopress' => self::videopress($host, $path),
            default => null,
        };
    }

    private static function youtube(string $host, string $path, string $query): ?string
    {
        if (! in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'youtube-nocookie.com', 'www.youtube-nocookie.com', 'youtu.be'], true)) {
            return null;
        }

        parse_str($query, $parameters);
        $id = $host === 'youtu.be'
            ? explode('/', $path)[0]
            : (($path === 'watch' && isset($parameters['v'])) ? $parameters['v'] : (preg_match('#^(?:embed|shorts)/([A-Za-z0-9_-]+)$#', $path, $matches) ? $matches[1] : ''));

        return preg_match('/^[A-Za-z0-9_-]{6,20}$/', $id) === 1
            ? 'https://www.youtube-nocookie.com/embed/'.$id
            : null;
    }

    private static function vimeo(string $host, string $path): ?string
    {
        if (! in_array($host, ['vimeo.com', 'www.vimeo.com', 'player.vimeo.com'], true)) {
            return null;
        }

        $id = preg_match('#^(?:video/)?(\d+)(?:/.*)?$#', $path, $matches) ? $matches[1] : '';

        return $id !== '' ? 'https://player.vimeo.com/video/'.$id : null;
    }

    private static function dailymotion(string $host, string $path): ?string
    {
        if (! in_array($host, ['dailymotion.com', 'www.dailymotion.com', 'dai.ly', 'www.dai.ly'], true)) {
            return null;
        }

        $id = preg_match('#^(?:video/)?([A-Za-z0-9]+)(?:_[^/]*)?$#', $path, $matches) ? $matches[1] : '';

        return $id !== '' ? 'https://www.dailymotion.com/embed/video/'.$id : null;
    }

    private static function videopress(string $host, string $path): ?string
    {
        if (! in_array($host, ['videopress.com', 'www.videopress.com', 'videos.files.wordpress.com'], true)) {
            return null;
        }

        $id = preg_match('#^(?:v/)?([A-Za-z0-9]+)(?:/.*)?$#', $path, $matches) ? $matches[1] : '';

        return $id !== '' ? 'https://videopress.com/embed/'.$id : null;
    }
}
