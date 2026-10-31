<?php // templates/home.php
/** @var array $data */
$q = $_GET['q'] ?? '';
$hasResults = !empty($data['items']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Portfolio de tutoriels vidéo informatiques : PHP, MySQL, Linux, DevOps. Une sélection soignée, classée par niveau.">
    <meta name="theme-color" content="#6366f1">
    <title>Tutos Vidéo — Portfolio de tutoriels informatiques</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|jetbrains-mono:400,500,600" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/style.css">
</head>
<body>

<!-- ================= HEADER ================= -->
<header class="site-header">
    <div class="container">
        <a class="brand" href="<?= BASE_URL ?>" aria-label="Accueil Tutos Vidéo">
            <span class="brand-logo" aria-hidden="true"></span>
            <span class="brand-name">Tutos<span class="brand-accent">Vidéo</span></span>
        </a>
        <nav class="nav-main" aria-label="Navigation principale">
            <a href="<?= BASE_URL ?>">Tutoriels</a>
            <a href="<?= BASE_URL ?>?q=php">PHP</a>
            <a href="<?= BASE_URL ?>?q=linux">Linux</a>
            <a href="<?= BASE_URL ?>/admin/login" class="nav-admin">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15" aria-hidden="true">
                    <path d="M12 2a5 5 0 1 0 0 10 5 5 0 0 0 0-10z"/>
                    <path d="M4 22c0-4 3.6-7 8-7s8 3 8 7"/>
                </svg>
                Admin
            </a>
        </nav>
    </div>
</header>

<!-- ================= HERO ================= -->
<section class="hero">
    <div class="hero-glow hero-glow--1" aria-hidden="true"></div>
    <div class="hero-glow hero-glow--2" aria-hidden="true"></div>

    <div class="container">
        <div class="hero-content">
            <span class="hero-eyebrow">
                <span class="dot-pulse" aria-hidden="true"></span>
                Portfolio · Tutoriels vidéo soignés
            </span>

            <h1 class="hero-title">
                Apprenez l'informatique<br>
                <span class="hero-gradient">par la pratique.</span>
            </h1>

            <p class="hero-subtitle">
                Une sélection de tutoriels vidéo — <strong>PHP</strong>, <strong>MySQL</strong>,
                <strong>Linux</strong>, <strong>DevOps</strong> — pensés pour aller droit au but,
                sans blabla.
            </p>

            <form class="search-form" method="get" action="<?= BASE_URL ?>" role="search" aria-label="Rechercher un tutoriel">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="search" name="q" placeholder="Rechercher (php, linux, mysql, docker…)"
                       value="<?= htmlspecialchars($q) ?>" autocomplete="off"
                       aria-label="Mot-clé de recherche" maxlength="100">
                <button type="submit">
                    <span class="btn-label">Chercher</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15" aria-hidden="true">
                        <line x1="5" y1="12" x2="19" y2="12"/>
                        <polyline points="12 5 19 12 12 19"/>
                    </svg>
                </button>
            </form>

            <!-- Stats placés directement dans .hero-content (hors .hero-grid/.hero-grid-item) -->
            <div class="stats">
                <div class="stat">
                    <span class="stat-value"><?= (int)$data['total'] ?></span>
                    <span class="stat-label">tutoriels</span>
                </div>
                <div class="stat">
                    <span class="stat-value">3</span>
                    <span class="stat-label">niveaux</span>
                </div>
                <div class="stat">
                    <span class="stat-value">100%</span>
                    <span class="stat-label">gratuit</span>
                </div>
                <div class="stat stat--per-page">
                    <span class="stat-value"><?= (int)($data['perPage'] ?? 9) ?></span>
                    <span class="stat-label">par page</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ================= GRILLE DE VIDÉOS ================= -->
<main class="container">

    <header class="section-header">
        <div>
            <span class="section-eyebrow"><?= $q !== '' ? 'Recherche' : 'Bibliothèque' ?></span>
            <h2 class="section-title">
                <?php if ($q !== ''): ?>
                    Résultats pour « <span class="text-primary"><?= htmlspecialchars($q) ?></span> »
                <?php else: ?>
                    Derniers tutoriels
                <?php endif; ?>
            </h2>
        </div>
        <?php if ($q !== ''): ?>
            <a class="section-link" href="<?= BASE_URL ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15" aria-hidden="true">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Voir tout
            </a>
        <?php endif; ?>
    </header>

    <?php if (!$hasResults): ?>
        <div class="empty-state">
            <h3>
                <?= $q !== '' ? 'Aucun tutoriel pour cette recherche' : 'Les tutoriels arrivent bientôt' ?>
            </h3>
            <p>
                <?= $q !== ''
                    ? 'Essayez un autre mot-clé ou parcourez la bibliothèque complète.'
                    : 'Les premières vidéos seront publiées très prochainement. Revenez vite !' ?>
            </p>
            <?php if ($q !== ''): ?>
                <a class="btn btn--primary" href="<?= BASE_URL ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15" aria-hidden="true">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                    Voir tous les tutoriels
                </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="video-grid">
            <?php foreach ($data['items'] as $v): ?>
                <a class="video-card" href="<?= BASE_URL ?>/video/<?= htmlspecialchars($v['slug']) ?>">
                    <div class="video-card-thumb">
                        <img src="https://img.youtube.com/vi/<?= htmlspecialchars($v['youtube_id']) ?>/hqdefault.jpg"
                             alt="<?= htmlspecialchars($v['title']) ?>" loading="lazy"
                             onerror="this.onerror=null;this.src='https://img.youtube.com/vi/<?= htmlspecialchars($v['youtube_id']) ?>/mqdefault.jpg';">
                        <span class="play" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                        </span>
                        <span class="badge badge--<?= htmlspecialchars($v['level']) ?>">
                            <?= htmlspecialchars(ucfirst($v['level'])) ?>
                        </span>
                    </div>
                    <div class="video-card-body">
                        <h3 class="video-card-title"><?= htmlspecialchars($v['title']) ?></h3>
                        <p class="video-card-desc">
                            <?= number_format((int)$v['views'], 0, ',', ' ') ?> vues<?php if (!empty($v['category_name'])): ?> · <?= htmlspecialchars($v['category_name']) ?><?php endif; ?>
                        </p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <?php
        $totalPages = (int)ceil($data['total'] / $data['perPage']);
        if ($totalPages > 1):
            $currentPage = (int)($data['page'] ?? 1);
            $searchQuery = !empty($q) ? '&q=' . urlencode($q) : '';
            $start = max(1, $currentPage - 2);
            $end = min($totalPages, $currentPage + 2);
        ?>
            <nav class="pagination" aria-label="Pagination">
                <?php if ($currentPage > 1): ?>
                    <a href="<?= BASE_URL ?>?page=<?= $currentPage - 1 ?><?= $searchQuery ?>" aria-label="Page précédente">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15" aria-hidden="true">
                            <polyline points="15 18 9 12 15 6"/>
                        </svg>
                    </a>
                <?php else: ?>
                    <span aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15">
                            <polyline points="15 18 9 12 15 6"/>
                        </svg>
                    </span>
                <?php endif; ?>

                <?php if ($start > 1): ?>
                    <a href="<?= BASE_URL ?>?page=1<?= $searchQuery ?>">1</a>
                    <?php if ($start > 2): ?><span>…</span><?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <?php if ($i === $currentPage): ?>
                        <span aria-current="page"><?= $i ?></span>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>?page=<?= $i ?><?= $searchQuery ?>"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ($end < $totalPages): ?>
                    <?php if ($end < $totalPages - 1): ?><span>…</span><?php endif; ?>
                    <a href="<?= BASE_URL ?>?page=<?= $totalPages ?><?= $searchQuery ?>"><?= $totalPages ?></a>
                <?php endif; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="<?= BASE_URL ?>?page=<?= $currentPage + 1 ?><?= $searchQuery ?>" aria-label="Page suivante">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15" aria-hidden="true">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </a>
                <?php else: ?>
                    <span aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="15" height="15">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </span>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</main>

<!-- ================= FOOTER ================= -->
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <a class="brand" href="<?= BASE_URL ?>">
                    <span class="brand-logo" aria-hidden="true"></span>
                    <span class="brand-name">Tutos<span class="brand-accent">Vidéo</span></span>
                </a>
                <p style="margin-top: var(--space-3); color: #9ca3af;">Portfolio de tutoriels informatiques</p>
            </div>
            <div>
                <h4 class="footer-title">Navigation</h4>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL ?>">Tutoriels</a></li>
                    <li><a href="<?= BASE_URL ?>?q=php">PHP</a></li>
                    <li><a href="<?= BASE_URL ?>?q=linux">Linux</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-title">Admin</h4>
                <ul class="footer-links">
                    <li><a href="<?= BASE_URL ?>/admin/login">Connexion</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            © <?= date('Y') ?> · Fait avec soin.
        </div>
    </div>
</footer>

</body>
</html>