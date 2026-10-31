<?php // templates/admin/login.php ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion — Tutos Vidéo</title>
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/style.css">
    <style>
        .login-form { max-width: 380px; margin: 4rem auto; background:#fff; padding:2rem;
                      border:1px solid var(--border); border-radius:8px; }
        .login-form input { width:100%; padding:.6rem; margin:.4rem 0 1rem;
                            border:1px solid var(--border); border-radius:6px; }
        .login-form button { width:100%; padding:.7rem; background:var(--primary);
                             color:#fff; border:0; border-radius:6px; cursor:pointer; }
        .error { color:#dc2626; margin-bottom:1rem; }
    </style>
</head>
<body>
<main class="container">
    <form class="login-form" method="post" action="<?= BASE_PATH ?>/admin/login">
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