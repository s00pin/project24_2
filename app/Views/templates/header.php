<?php
$isLoggedIn = (bool) session()->get('logged_in');
$username = (string) session()->get('username');
$cssAssetPath = FCPATH . 'assets/css/main.css';
$cssVersion = is_file($cssAssetPath) ? (string) filemtime($cssAssetPath) : (string) time();

$path = trim((string) service('uri')->getPath(), '/');
$cleanPath = preg_replace('#^index\.php/?#i', '', $path);
$cleanPath = trim((string) $cleanPath, '/');
$navPath = $cleanPath === '' ? 'home' : strtolower($cleanPath);
$routeRoot = explode('/', $navPath)[0];

$kickerMap = [
    'home' => 'Discover',
    'media' => 'Movies',
    'show' => 'Shows',
    'search' => 'Search',
    'dashboard' => 'Dashboard',
    'login' => 'Account',
    'news' => 'Newsroom',
];

$pageTitle = (string) ($title ?? 'Reel Atlas');
$pageKicker = $kickerMap[$routeRoot] ?? 'Media Hub';
$bodyClass = 'route-' . preg_replace('/[^a-z0-9-]+/', '-', str_replace('/', '-', $navPath));
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Reel Atlas movie and TV discovery workspace">
    <meta name="author" content="Swopnil Sapkota">
    <title><?= esc($pageTitle) ?> | Reel Atlas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700;800&family=Bungee&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') . '?v=' . $cssVersion; ?>">
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/image/logo.ico'); ?>">
</head>
<body class="<?= esc($bodyClass) ?>">
<script>window.APP_BASE_URL = "<?= rtrim(base_url(), '/') ?>";</script>
<div class="site-shell">
    <header class="site-header">
        <div class="shell header-shell">
            <a class="brand-lockup" href="<?= base_url('home'); ?>" aria-label="Reel Atlas home">
                <span class="brand-mark" aria-hidden="true"></span>
                <span class="brand-lines">
                    <strong>Reel Atlas</strong>
                    <small>find, track, and remember what to watch</small>
                </span>
            </a>

            <button class="menu-toggle" type="button" id="menu-toggle" aria-expanded="false" aria-controls="site-nav" aria-label="Toggle navigation">
                <span></span><span></span><span></span>
            </button>

            <div class="site-nav-wrap" id="site-nav">
                <form class="quick-search" action="<?= base_url('search') ?>" method="get" role="search">
                    <label class="sr-only" for="search-input">Search titles</label>
                    <input
                        id="search-input"
                        class="search-input"
                        type="search"
                        name="query"
                        placeholder="Search films, series, and keywords"
                        autocomplete="off"
                    >
                    <button class="btn btn-primary btn-sm" type="submit">Search</button>
                    <ul id="suggestions" class="suggestion-box" role="listbox" aria-label="Search suggestions"></ul>
                </form>

                <nav class="site-nav" aria-label="Primary">
                    <a class="site-link <?= in_array($navPath, ['', 'home'], true) ? 'active' : '' ?>" href="<?= base_url('home'); ?>">Home</a>
                    <a class="site-link <?= str_starts_with($navPath, 'media') ? 'active' : '' ?>" href="<?= base_url('media'); ?>">Movies</a>
                    <a class="site-link <?= str_starts_with($navPath, 'show') ? 'active' : '' ?>" href="<?= base_url('show'); ?>">Shows</a>
                    <a class="site-link <?= str_starts_with($navPath, 'search') ? 'active' : '' ?>" href="<?= base_url('search'); ?>">Search</a>
                    <a class="site-link <?= str_starts_with($navPath, 'news') ? 'active' : '' ?>" href="<?= base_url('news'); ?>">News</a>
                    <?php if ($isLoggedIn): ?>
                        <a class="site-link <?= str_starts_with($navPath, 'dashboard') ? 'active' : '' ?>" href="<?= base_url('dashboard'); ?>">Dashboard</a>
                        <span class="user-chip"><?= esc($username) ?></span>
                        <a class="btn btn-ghost btn-sm" href="<?= base_url('logout'); ?>">Logout</a>
                    <?php else: ?>
                        <a class="btn btn-ghost btn-sm" href="<?= base_url('login'); ?>">Login</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>

    <main class="page-shell shell">
        <?php if (!empty($title) && $routeRoot !== 'home'): ?>
            <section class="page-banner">
                <p class="page-banner-kicker"><?= esc($pageKicker) ?></p>
                <h1><?= esc($title) ?></h1>
            </section>
        <?php endif; ?>
