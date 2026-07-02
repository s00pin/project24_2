<?php
$isLoggedIn = (bool) session()->get('logged_in');
$username = (string) session()->get('username');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Movie and TV discovery app">
    <meta name="author" content="Swopnil Sapkota">
    <title><?= esc($title ?? 'Media Hub') ?> | Media Hub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= base_url('assets/css/main.css'); ?>">
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/image/logo.ico'); ?>">
</head>
<body>
<script>window.APP_BASE_URL = "<?= rtrim(base_url(), '/') ?>";</script>
<div class="site-shell">
    <header class="site-header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand brand-lockup" href="<?= base_url('home'); ?>">
                    <img src="<?= base_url('assets/image/logo.png'); ?>" alt="Media Hub logo" class="brand-logo">
                    <span class="brand-text">Media Hub</span>
                    <span class="brand-tag">Movies + TV</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <form class="search-wrap mx-lg-auto mt-3 mt-lg-0" action="<?= base_url('search') ?>" method="get">
                        <input id="search-input" class="form-control search-input" type="search" name="query" placeholder="Search movies or shows" autocomplete="off">
                        <button class="btn btn-accent ms-2" type="submit">Search</button>
                        <ul id="suggestions" class="suggestion-box"></ul>
                    </form>

                    <ul class="navbar-nav ms-auto mt-3 mt-lg-0 align-items-lg-center nav-cluster">
                        <li class="nav-item"><a class="nav-link top-nav" href="<?= base_url('home'); ?>">Home</a></li>
                        <li class="nav-item"><a class="nav-link top-nav" href="<?= base_url('media'); ?>">Movies</a></li>
                        <li class="nav-item"><a class="nav-link top-nav" href="<?= base_url('show'); ?>">Shows</a></li>
                        <?php if ($isLoggedIn): ?>
                            <li class="nav-item"><a class="nav-link top-nav" href="<?= base_url('dashboard'); ?>">Dashboard</a></li>
                            <li class="nav-item"><span class="nav-link user-pill"><?= esc($username) ?></span></li>
                            <li class="nav-item"><a class="btn btn-outline-light btn-sm ms-lg-2" href="<?= base_url('logout'); ?>">Logout</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="btn btn-outline-light btn-sm ms-lg-2" href="<?= base_url('login'); ?>">Login</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="page-content container">
        <?php if (!empty($title)): ?>
            <div class="page-head">
                <h1><?= esc($title) ?></h1>
            </div>
        <?php endif; ?>
