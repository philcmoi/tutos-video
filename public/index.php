<?php
// public/index.php
declare(strict_types=1);

session_start();

// --- Autoload manuel (PSR-4 simplifié) ---
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $file = __DIR__ . '/../src/' . str_replace('\\', '/', $relative) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use App\Database;
use App\Router;
use App\Video;

$config = require __DIR__ . '/../config/config.php';
$db = Database::getInstance($config['db']);
$videoModel = new Video($db);
$router = new Router();

// Préfixe d'URL sous lequel est installé le projet
// (mettre '' si VirtualHost dédié à la racine)
define('BASE_PATH', '/tutos-video/public');

// URL de base vers index.php (utilisée pour tous les liens/formulaires)
define('BASE_URL', BASE_PATH . '/index.php');

// --- Token CSRF (généré une fois par session) ---
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

// --- Page d'accueil ---
$router->get('/', function () use ($videoModel, $config) {
    $page = max(1, (int)($_GET['page'] ?? 1));
    $search = trim($_GET['q'] ?? '');
    $data = $videoModel->listAll($page, 12, $search ?: null);
    require __DIR__ . '/../templates/home.php';
});

// --- Détail d'une vidéo ---
$router->get('/video/{slug}', function ($params) use ($videoModel) {
    $video = $videoModel->findBySlug($params['slug']);
    if (!$video) {
        http_response_code(404);
        exit('Vidéo introuvable');
    }
    $videoModel->incrementViews((int)$video['id']);
    require __DIR__ . '/../templates/video.php';
});

// --- API JSON pour AJAX ---
$router->get('/api/videos', function () use ($videoModel) {
    header('Content-Type: application/json; charset=utf-8');
    $page = max(1, (int)($_GET['page'] ?? 1));
    $search = $_GET['q'] ?? null;
    echo json_encode($videoModel->listAll($page, 12, $search));
});

// --- Espace admin (protégé) ---
$router->get('/admin', function () use ($db) {
    if (empty($_SESSION['admin'])) {
        header('Location: ' . BASE_URL . '/admin/login');
        exit;
    }
    $videos = $db->query("SELECT * FROM videos ORDER BY created_at DESC")->fetchAll();
    require __DIR__ . '/../templates/admin/dashboard.php';
});

$router->get('/admin/login', function () {
    require __DIR__ . '/../templates/admin/login.php';
});

$router->post('/admin/login', function () use ($db) {
    $stmt = $db->prepare("SELECT * FROM admins WHERE username = :u");
    $stmt->execute([':u' => $_POST['username'] ?? '']);
    $admin = $stmt->fetch();

    if ($admin && password_verify($_POST['password'] ?? '', $admin['password_hash'])) {
        $_SESSION['admin'] = $admin['id'];
        header('Location: ' . BASE_URL . '/admin');
    } else {
        header('Location: ' . BASE_URL . '/admin/login?error=1');
    }
    exit;
});

$router->get('/admin/logout', function () {
    unset($_SESSION['admin']);
    header('Location: ' . BASE_URL . '/admin/login');
    exit;
});

// --- Ajout d'une vidéo ---
$router->post('/admin/videos', function () use ($videoModel) {
    if (empty($_SESSION['admin'])) {
        http_response_code(403);
        exit;
    }

    // Vérification CSRF
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(403);
        exit('CSRF invalide');
    }

    $youtubeId = \App\YouTubeHelper::extractId($_POST['youtube_url'] ?? '');
    if (!$youtubeId) {
        header('Location: ' . BASE_URL . '/admin?error=url_invalide');
        exit;
    }

    $videoModel->create([
        'title'       => $_POST['title'],
        'youtube_id'  => $youtubeId,
        'description' => $_POST['description'] ?? '',
        'category_id' => (int)($_POST['category_id'] ?? 0) ?: null,
        'level'       => $_POST['level'] ?? 'debutant',
        'tags'        => $_POST['tags'] ?? null,
    ]);

    header('Location: ' . BASE_URL . '/admin?success=1');
    exit;
});

// --- Suppression d'une vidéo ---
$router->post('/admin/videos/{id}/delete', function ($params) use ($videoModel) {
    if (empty($_SESSION['admin'])) {
        http_response_code(403);
        exit;
    }
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(403);
        exit('CSRF invalide');
    }
    $videoModel->delete((int)$params['id']);
    header('Location: ' . BASE_URL . '/admin?deleted=1');
    exit;
});

// --- Normalisation de l'URI ---
// Priorité à PATH_INFO : permet d'utiliser index.php/video/slug, index.php/api/videos,
// index.php/admin, etc. SANS rewrite Apache ni VirtualHost.
if (!empty($_SERVER['PATH_INFO'])) {
    $uri = $_SERVER['PATH_INFO'];
} else {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

    // Écrase les doubles slashes (localhost//tutos → localhost/tutos)
    $uri = preg_replace('#/+#', '/', $uri);

    // Retire BASE_PATH + /index.php si présent
    $basePrefix = BASE_PATH . '/index.php';
    if (str_starts_with($uri, $basePrefix)) {
        $uri = substr($uri, strlen($basePrefix)) ?: '/';
    } elseif (str_starts_with($uri, BASE_PATH)) {
        $uri = substr($uri, strlen(BASE_PATH)) ?: '/';
    }
}

// Écrase les doubles slashes au cas où PATH_INFO en contiendrait
$uri = preg_replace('#/+#', '/', $uri);

// Racine explicite
if ($uri === '' || $uri === '/index.php') {
    $uri = '/';
}

// --- Dispatch ---
$router->dispatch($_SERVER['REQUEST_METHOD'], $uri);