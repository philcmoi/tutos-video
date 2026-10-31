<?php // templates/admin/dashboard.php
/** @var array $videos */
$videos = $videos ?? [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard — Tutos Vidéo</title>
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/style.css">
    <style>
        .admin-form { background:#fff; padding:1.5rem; border:1px solid var(--border);
                      border-radius:8px; margin-bottom:2rem; }
        .admin-form input, .admin-form textarea, .admin-form select {
            width:100%; padding:.5rem; margin:.3rem 0 .8rem;
            border:1px solid var(--border); border-radius:6px; font-family:inherit;
        }
        .admin-form button { padding:.6rem 1.2rem; background:var(--primary);
                             color:#fff; border:0; border-radius:6px; cursor:pointer; }
        .alert { padding:.8rem 1rem; border-radius:6px; margin-bottom:1rem; }
        .alert-success { background:#dcfce7; color:#166534; }
        .alert-error   { background:#fee2e2; color:#991b1b; }
        table { width:100%; border-collapse:collapse; background:#fff; }
        th, td { padding:.6rem; border-bottom:1px solid var(--border); text-align:left; font-size:.95rem; }
        th { background:#f1f5f9; }
        .btn-logout { float:right; color:#dc2626; text-decoration:none; }

        /* 🔙 Lien retour à l'accueil */
        .btn-back-home {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            margin-bottom: 1rem;
            font-size: .9rem;
            color: #555;
            text-decoration: none;
        }
        .btn-back-home:hover,
        .btn-back-home:focus { color: #111; text-decoration: underline; }
    </style>
</head>
<body>
<main class="container">

    <!-- 🔙 Retour à l'accueil — toujours visible -->
    <a class="btn-back-home" href="<?= BASE_URL ?>" title="Retour à l'accueil">
        ← Retour à l'accueil
    </a>

    <h1>Dashboard
        <a class="btn-logout" href="<?= BASE_URL ?>/admin/logout"
           onclick="return confirm('Se déconnecter ?');">Déconnexion</a>
    </h1>

    <?php if (!empty($_GET['success'])): ?>
        <p class="alert alert-success">Vidéo ajoutée ✅</p>
    <?php endif; ?>
    <?php if (!empty($_GET['error'])): ?>
        <p class="alert alert-error">
            <?= $_GET['error'] === 'url_invalide'
                ? 'URL YouTube invalide.'
                : 'Une erreur est survenue.' ?>
        </p>
    <?php endif; ?>

    <h2>Ajouter une vidéo</h2>
    <form class="admin-form" method="post" action="<?= BASE_URL ?>/admin/videos">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf'] ?? '') ?>">

        <label>URL YouTube (ou ID)</label>
        <input type="text" name="youtube_url" required
               placeholder="https://www.youtube.com/watch?v=xxxxxxxxxxx">

        <label>Titre</label>
        <input type="text" name="title" required maxlength="255">

        <label>Description</label>
        <textarea name="description" rows="4"></textarea>

        <label>Niveau</label>
        <select name="level">
            <option value="debutant">Débutant</option>
            <option value="intermediaire">Intermédiaire</option>
            <option value="avance">Avancé</option>
        </select>

        <label>Tags (séparés par des virgules)</label>
        <input type="text" name="tags" placeholder="php, mysql, sécurité">

        <button type="submit">Ajouter</button>
    </form>

    <h2>Vidéos existantes (<?= count($videos) ?>)</h2>
    <?php if (empty($videos)): ?>
        <p>Aucune vidéo enregistrée.</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>YouTube ID</th>
                    <th>Niveau</th>
                    <th>Vues</th>
                    <th>Publiée</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($videos as $v): ?>
                <tr>
                    <td><?= htmlspecialchars($v['title']) ?></td>
                    <td><code><?= htmlspecialchars($v['youtube_id']) ?></code></td>
                    <td><?= htmlspecialchars($v['level']) ?></td>
                    <td><?= (int)$v['views'] ?></td>
                    <td><?= $v['published'] ? '✅' : '❌' ?></td>
                    <td>
                        <form method="post"
                              action="<?= BASE_URL ?>/admin/videos/<?= (int)$v['id'] ?>/delete"
                              onsubmit="return confirm('Supprimer cette vidéo ?');"
                              style="display:inline">
                            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf'] ?? '') ?>">
                            <button type="submit"
                                    style="background:none;border:0;color:#dc2626;cursor:pointer;">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>
</body>
</html>