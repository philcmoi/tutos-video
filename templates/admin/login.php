<?php // templates/admin/login.php
/** @var string $baseUrl */
$baseUrl = BASE_URL; // toujours défini par index.php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion — Tutos Vidéo</title>
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/style.css">
    <style>
        .login-form { max-width: 380px; margin: 4rem auto; background:#fff; padding:2rem;
                      border:1px solid var(--border); border-radius:8px; position: relative; }
        .login-form input { width:100%; padding:.6rem; margin:.4rem 0 1rem;
                            border:1px solid var(--border); border-radius:6px; }
        .login-form button { width:100%; padding:.7rem; background:var(--primary);
                             color:#fff; border:0; border-radius:6px; cursor:pointer; }
        .error { color:#dc2626; margin-bottom:1rem; }

        /* 🔙 Bouton « Retour à l'accueil » — habillé */
        .btn-back-home {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            margin-bottom: 1.25rem;
            padding: .5rem .9rem;
            background: #f8fafc;
            color: #374151;
            border: 1px solid var(--border, #e5e7eb);
            border-radius: 6px;
            font-size: .9rem;
            font-weight: 500;
            text-decoration: none;
            transition: background .2s ease, color .2s ease, border-color .2s ease, transform .1s ease;
        }
        .btn-back-home:hover,
        .btn-back-home:focus {
            background: #eef2ff;
            color: var(--primary, #6366f1);
            border-color: var(--primary, #6366f1);
            text-decoration: none;
            outline: none;
        }
        .btn-back-home:active {
            transform: translateY(1px);
        }
        .btn-back-home svg {
            flex-shrink: 0;
            transition: transform .2s ease;
        }
        .btn-back-home:hover svg {
            transform: translateX(-3px);
        }
    </style>
</head>
<body>
<main class="container">
    <form class="login-form" method="post" action="<?= BASE_URL ?>/admin/login">

        <!-- 🔙 Retour à l'accueil — habillé, hors du flux de soumission -->
        <a class="btn-back-home"
           href="<?= BASE_URL ?>"
           title="Retour à l'accueil">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 aria-hidden="true">
                <line x1="19" y1="12" x2="5" y2="12"/>
                <polyline points="12 19 5 12 12 5"/>
            </svg>
            Retour à l'accueil
        </a>

        <h1 style="margin-bottom:1rem;">Connexion admin</h1>

        <?php if (!empty($_GET['error'])): ?>
            <p class="error">Identifiants incorrects.</p>
        <?php endif; ?>

        <label for="username">Nom d'utilisateur</label>
        <input type="text" id="username" name="username" required autofocus autocomplete="username">

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required autocomplete="current-password">

        <button type="submit">Se connecter</button>
    </form>
</main>
</body>
</html>