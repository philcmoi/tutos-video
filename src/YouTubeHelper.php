<?php
// src/YouTubeHelper.php
namespace App;

class YouTubeHelper
{
    /**
     * Extrait l'ID depuis une URL YouTube complète
     */
    public static function extractId(string $url): ?string
    {
        $patterns = [
            '/(?:youtube\.com\/watch\?v=)([a-zA-Z0-9_-]{11})/',
            '/(?:youtu\.be\/)([a-zA-Z0-9_-]{11})/',
            '/(?:youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/',
            '/(?:youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/',
        ];
        foreach ($patterns as $p) {
            if (preg_match($p, $url, $m)) {
                return $m[1];
            }
        }
        // Si c'est déjà un ID
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return $url;
        }
        return null;
    }

    /**
     * Génère l'URL de la miniature
     */
    public static function thumbnail(string $videoId, string $quality = 'hqdefault'): string
    {
        return "https://img.youtube.com/vi/{$videoId}/{$quality}.jpg";
    }

    /**
     * Récupère les métadonnées via l'API (optionnel)
     */
    public static function fetchMetadata(string $videoId, string $apiKey): ?array
    {
        if (empty($apiKey)) return null;

        $url = 'https://www.googleapis.com/youtube/v3/videos?' . http_build_query([
            'id' => $videoId,
            'part' => 'snippet,contentDetails',
            'key' => $apiKey,
        ]);

        $response = @file_get_contents($url);
        if ($response === false) return null;

        $data = json_decode($response, true);
        if (empty($data['items'][0])) return null;

        $item = $data['items'][0];
        return [
            'title' => $item['snippet']['title'] ?? '',
            'description' => $item['snippet']['description'] ?? '',
            'thumbnail' => $item['snippet']['thumbnails']['high']['url'] ?? '',
            'duration' => self::parseDuration($item['contentDetails']['duration'] ?? ''),
        ];
    }

    private static function parseDuration(string $iso): int
    {
        // PT1H30M15S -> secondes
        preg_match('/PT(?:(\d+)H)?(?:(\d+)M)?(?:(\d+)S)?/', $iso, $m);
        return ((int)($m[1] ?? 0)) * 3600 + ((int)($m[2] ?? 0)) * 60 + (int)($m[3] ?? 0);
    }
}