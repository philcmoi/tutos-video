<?php
// bootstrap.php — point d'entrée commun (racine du projet)
// Définit les constantes UNE SEULE FOIS, avec des noms non ambigus.

if (!defined('BASE_PATH')) {
    // Acquis projet : BASE_PATH est utilisé comme PRÉFIXE URL dans les templates
    // (ex : <?= BASE_PATH ?>/assets/css/style.css ?>).
    define('BASE_PATH', '/tutos-video');
}

if (!defined('BASE_DIR')) {
    // Chemin disque réel (jamais exposé dans le HTML)
    define('BASE_DIR', __DIR__);
}

// Session commune à tout le site (login, admin, flash messages…)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper : affichage sûr
function e(?string $s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Helper : jeton CSRF
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_check(?string $token): bool
{
    return is_string($token)
        && !empty($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

// Helper : redirection interne
function redirect(string $path): void
{
    header('Location: ' . BASE_PATH . $path);
    exit;
}