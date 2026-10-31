<?php
// config/config.php
return [
    'db' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'name' => getenv('DB_NAME') ?: 'tutos_video',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'name' => 'Tutos Vidéo',
        'url' => getenv('APP_URL') ?: 'https://votredomaine.fr',
        'debug' => getenv('APP_DEBUG') === 'true',
    ],
    'youtube' => [
        'api_key' => getenv('YOUTUBE_API_KEY') ?: '',
    ],
];