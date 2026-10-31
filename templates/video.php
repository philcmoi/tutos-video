<?php // templates/video.php
/** @var array $video */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= htmlspecialchars(mb_substr($video['description'] ?? $video['title'], 0, 155)) ?>">
    <title><?= htmlspecialchars($video['title']) ?> — Tutos Vidéo</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|jetbrains-mono:400,500" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/style.css">
</head>
<body>

<header class="site-header">
    <div class="header-inner">
        <a class="brand" href="<?= BASE_URL ?>">
            <span class="brand-mark">&lt;/&gt;</span>
            <span class="brand-name">Tutos<span class="brand-accent">Vidéo</span></span>
        </a>
        <nav class="site-nav">
            <a href="<?= BASE_URL ?>">← Tous les tutoriels</a>
        </nav>
    </div>
</header>

<main class="container" style="max-width: 900px;">
    <p style="margin-bottom: 1rem;">
        <a href="<?= BASE_URL ?>" style="color: var(--muted); font-size: 0.9rem;">
            ← Retour aux tutoriels
        </a>
    </p>

    <h1><?= htmlspecialchars($video['title']) ?></h1>

    <p class="meta">
        <span class="badge badge-<?= htmlspecialchars($video['level']) ?>"
              style="position: static; display: inline-block; margin-right: 0.6rem;">
            <?= htmlspecialchars(ucfirst($video['level'])) ?>
        </span>
        <?= (int)$video['views'] ?> vue<?= (int)$video['views'] > 1 ? 's' : '' ?>
        <?php if (!empty($video['category_name'])): ?>
            · <?= htmlspecialchars($video['category_name']) ?>
        <?php endif; ?>
    </p>

    <div class="video-player">
        <iframe
            src="https://www.youtube-nocookie.com/embed/<?= htmlspecialchars($video['youtube_id']) ?>"
            title="<?= htmlspecialchars($video['title']) ?>"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
            loading="lazy"></iframe>
    </div>

    <?php if (!empty($video['description'])): ?>
        <div class="description"><?= nl2br(htmlspecialchars($video['description'])) ?></div>
    <?php endif; ?>

    <?php if (!empty($video['tags'])): ?>
        <div style="margin-top: 2rem;">
            <?php foreach (array_filter(array_map('trim', explode(',', $video['tags']))) as $tag): ?>
                <a class="badge"
                   href="<?= BASE_URL ?>?q=<?= urlencode($tag) ?>"
                   style="position: static; display: inline-block; background: var(--primary-soft);
                          color: var(--primary); margin: 0.2rem; text-decoration: none;">
                    #<?= htmlspecialchars($tag) ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<footer class="site-footer">
    <div class="footer-inner">
        <p>© <?= date('Y') ?> <strong>TutosVidéo</strong></p>
        <p class="footer-links">
            <a href="<?= BASE_URL ?>">Tutoriels</a>
            <span>·</span>
            <a href="<?= BASE_URL ?>/admin/login">Espace admin</a>
        </p>
    </div>
</footer>

</body>
</html>