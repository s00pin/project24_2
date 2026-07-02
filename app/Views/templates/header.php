<?php
$isLoggedIn = (bool) session()->get('logged_in');
$username = (string) session()->get('username');
$cssAssetPath = FCPATH . 'assets/css/main.css';
$cssVersion = is_file($cssAssetPath) ? (string) filemtime($cssAssetPath) : (string) time();
$path = trim((string) service('uri')->getPath(), '/');
$navPath = $path === '' ? 'home' : $path;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Media Hub movie and TV catalog">
    <meta name="author" content="Swopnil Sapkota">
    <title><?= esc($title ?? 'Media Hub') ?> | Media Hub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500&family=Instrument+Serif:ital@0;1&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css') . '?v=' . $cssVersion; ?>">
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/image/logo.ico'); ?>">
</head>
<body>
<script>window.APP_BASE_URL = "<?= rtrim(base_url(), '/') ?>";</script>
<div class="site-shell">
    <header class="site-header">
        <div class="shell header-shell">
            <a class="brand-lockup" href="<?= base_url('home'); ?>">
                <img src="<?= base_url('assets/image/logo.png'); ?>" alt="Media Hub logo" class="brand-logo">
                <div class="brand-text-wrap">
                    <span class="brand-text">Media Hub</span>
                    <span class="brand-tag">Curated Watch Picks</span>
                </div>
            </a>

            <button class="menu-toggle" type="button" id="menu-toggle" aria-expanded="false" aria-controls="site-nav" aria-label="Toggle navigation">
                <span></span><span></span><span></span>
            </button>

            <div class="site-nav-wrap" id="site-nav">
                <form class="search-wrap" action="<?= base_url('search') ?>" method="get">
                    <input id="search-input" class="search-input" type="search" name="query" placeholder="Search movies and shows" autocomplete="off">
                    <button class="btn btn-accent" type="submit">Search</button>
                    <ul id="suggestions" class="suggestion-box"></ul>
                </form>

                <nav class="site-nav" aria-label="Primary">
                    <a class="top-nav <?= in_array($navPath, ['', 'home'], true) ? 'active' : '' ?>" href="<?= base_url('home'); ?>">Home</a>
                    <a class="top-nav <?= str_starts_with($navPath, 'media') ? 'active' : '' ?>" href="<?= base_url('media'); ?>">Movies</a>
                    <a class="top-nav <?= str_starts_with($navPath, 'show') ? 'active' : '' ?>" href="<?= base_url('show'); ?>">Shows</a>
                    <a class="top-nav <?= str_starts_with($navPath, 'search') ? 'active' : '' ?>" href="<?= base_url('search'); ?>">Search</a>
                    <?php if ($isLoggedIn): ?>
                        <a class="top-nav <?= str_starts_with($navPath, 'dashboard') ? 'active' : '' ?>" href="<?= base_url('dashboard'); ?>">Dashboard</a>
                        <span class="user-pill"><?= esc($username) ?></span>
                        <a class="btn btn-outline-light btn-sm" href="<?= base_url('logout'); ?>">Logout</a>
                    <?php else: ?>
                        <a class="btn btn-outline-light btn-sm" href="<?= base_url('login'); ?>">Login</a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </header>

    <main class="page-content shell">
        <?php if (!empty($title)): ?>
            <div class="page-head">
                <h1><?= esc($title) ?></h1>
            </div>
        <?php endif; ?>
