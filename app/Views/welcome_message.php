<?php
$featuredMovieCount = count($featuredMovies ?? []);
$featuredShowCount = count($featuredShows ?? []);
$popularMovieCount = count($popularMovies ?? []);
$popularShowCount = count($popularShows ?? []);
?>

<section class="hero">
    <div class="hero-copy">
        <h2>Find What To Watch Without the Scroll Fatigue</h2>
        <p>Use Media Hub to browse movies and shows, check provider availability in your region, and save picks into personal lists you can revisit later.</p>
        <div class="hero-actions">
            <a href="<?= base_url('media'); ?>" class="btn btn-accent">Browse Movies</a>
            <a href="<?= base_url('show'); ?>" class="btn btn-outline-light">Browse Shows</a>
            <a href="<?= base_url('search'); ?>" class="btn btn-outline-light">Search Everything</a>
            <?php if (! session()->get('logged_in')): ?>
                <a href="<?= base_url('login'); ?>" class="btn btn-outline-light">Login</a>
            <?php endif; ?>
        </div>
    </div>

    <aside class="hero-side">
        <h3>Catalog Snapshot</h3>
        <ul class="kpi-list">
            <li>Featured movies <span><?= esc((string) $featuredMovieCount) ?></span></li>
            <li>Featured shows <span><?= esc((string) $featuredShowCount) ?></span></li>
            <li>Popular movies <span><?= esc((string) $popularMovieCount) ?></span></li>
            <li>Popular shows <span><?= esc((string) $popularShowCount) ?></span></li>
        </ul>
    </aside>
</section>

<section class="panel" style="margin-bottom:1rem;">
    <div class="section-head">
        <h3>Featured Movies</h3>
        <p>Recently added and high-interest movie picks</p>
    </div>
    <div class="catalog-grid">
        <?php foreach ($featuredMovies ?? [] as $movie): ?>
            <a class="media-card" href="<?= base_url('media/' . esc($movie['id'], 'url')); ?>">
                <img src="https://image.tmdb.org/t/p/w300/<?= esc(ltrim((string) $movie['poster_image'], '/')); ?>" alt="<?= esc($movie['title']); ?>" onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';">
                <div class="card-meta">
                    <div class="card-meta-main">
                        <p class="card-title-text"><?= esc($movie['title']) ?></p>
                        <p class="card-subtext">Movie</p>
                    </div>
                    <span class="card-badge">Open</span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="panel" style="margin-bottom:1rem;">
    <div class="section-head">
        <h3>Featured Shows</h3>
        <p>TV titles selected from the latest catalog updates</p>
    </div>
    <div class="catalog-grid">
        <?php foreach ($featuredShows ?? [] as $show): ?>
            <a class="media-card" href="<?= base_url('show/' . esc($show['id'], 'url')); ?>">
                <img src="https://image.tmdb.org/t/p/w300/<?= esc(ltrim((string) $show['poster'], '/')); ?>" alt="<?= esc($show['title']); ?>" onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';">
                <div class="card-meta">
                    <div class="card-meta-main">
                        <p class="card-title-text"><?= esc($show['title']) ?></p>
                        <p class="card-subtext">Show</p>
                    </div>
                    <span class="card-badge">Open</span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section class="panel">
    <div class="section-head">
        <h3>Popular by Likes</h3>
        <p>Ranked from user likes in Media Hub</p>
    </div>
    <div class="grid-two">
        <ol class="rank-list">
            <?php foreach ($popularMovies ?? [] as $movie): ?>
                <li>
                    <a class="rank-item" href="<?= base_url('media/' . esc($movie['id'], 'url')); ?>">
                        <strong><?= esc($movie['title']) ?></strong>
                        <span>Movie</span>
                        <span>Likes: <?= esc((string) ($movie['likes_count'] ?? 0)) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ol>

        <ol class="rank-list">
            <?php foreach ($popularShows ?? [] as $show): ?>
                <li>
                    <a class="rank-item" href="<?= base_url('show/' . esc($show['id'], 'url')); ?>">
                        <strong><?= esc($show['title']) ?></strong>
                        <span>Show</span>
                        <span>Likes: <?= esc((string) ($show['likes_count'] ?? 0)) ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>
