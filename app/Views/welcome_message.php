<?php
$featuredMovieCount = count($featuredMovies ?? []);
$featuredShowCount = count($featuredShows ?? []);
$popularMovieCount = count($popularMovies ?? []);
$popularShowCount = count($popularShows ?? []);
?>

<section class="hero">
    <div class="hero-grid">
        <div>
            <h2>Find Your Next Watch in Minutes</h2>
            <p class="lead mb-0">Search movies and shows, open detail pages with streaming providers, and save titles to likes or custom lists.</p>
            <div class="hero-actions">
                <a href="<?= base_url('media'); ?>" class="btn btn-accent">Open Movies</a>
                <a href="<?= base_url('show'); ?>" class="btn btn-outline-light">Open Shows</a>
                <a href="<?= base_url('search'); ?>" class="btn btn-outline-light">Search All</a>
                <?php if (! session()->get('logged_in')): ?>
                    <a href="<?= base_url('login'); ?>" class="btn btn-outline-light">Login</a>
                <?php endif; ?>
            </div>
        </div>

        <aside class="vibe-panel">
            <h3>Catalog Snapshot</h3>
            <ul class="vibe-list">
                <li><span>Featured movies</span> <?= esc((string) $featuredMovieCount) ?></li>
                <li><span>Featured shows</span> <?= esc((string) $featuredShowCount) ?></li>
                <li><span>Popular movies</span> <?= esc((string) $popularMovieCount) ?></li>
                <li><span>Popular shows</span> <?= esc((string) $popularShowCount) ?></li>
            </ul>
        </aside>
    </div>
</section>

<section class="mb-4">
    <div class="section-heading">
        <h3>Featured Movies</h3>
        <p>Selected from the current movie catalog</p>
    </div>
    <div class="row row-cols-2 row-cols-md-4 g-3 media-grid">
        <?php foreach ($featuredMovies ?? [] as $movie): ?>
            <div class="col">
                <a class="card h-100" href="<?= base_url('media/' . esc($movie['id'], 'url')); ?>">
                    <img src="https://image.tmdb.org/t/p/w300/<?= esc(ltrim((string) $movie['poster_image'], '/')); ?>" alt="<?= esc($movie['title']); ?>" class="w-100" onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';">
                    <div class="card-meta">
                        <div class="card-meta-main">
                            <p class="mb-0 card-title-text"><?= esc($movie['title']) ?></p>
                            <p class="mb-0 card-subtext">Movie</p>
                        </div>
                        <span class="card-badge">Open</span>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="mb-4">
    <div class="section-heading">
        <h3>Featured Shows</h3>
        <p>Selected from the current TV catalog</p>
    </div>
    <div class="row row-cols-2 row-cols-md-4 g-3 media-grid">
        <?php foreach ($featuredShows ?? [] as $show): ?>
            <div class="col">
                <a class="card h-100" href="<?= base_url('show/' . esc($show['id'], 'url')); ?>">
                    <img src="https://image.tmdb.org/t/p/w300/<?= esc(ltrim((string) $show['poster'], '/')); ?>" alt="<?= esc($show['title']); ?>" class="w-100" onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';">
                    <div class="card-meta">
                        <div class="card-meta-main">
                            <p class="mb-0 card-title-text"><?= esc($show['title']) ?></p>
                            <p class="mb-0 card-subtext">Show</p>
                        </div>
                        <span class="card-badge">Open</span>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section>
    <div class="section-heading">
        <h3>Popular by Likes</h3>
        <p>Based on likes from Media Hub users</p>
    </div>

    <div class="rank-grid">
        <?php foreach ($popularMovies ?? [] as $movie): ?>
            <a class="rank-card text-decoration-none" href="<?= base_url('media/' . esc($movie['id'], 'url')); ?>">
                <h4><?= esc($movie['title']) ?></h4>
                <p>Type: Movie</p>
                <p>Likes: <span class="count"><?= esc((string) ($movie['likes_count'] ?? 0)) ?></span></p>
            </a>
        <?php endforeach; ?>

        <?php foreach ($popularShows ?? [] as $show): ?>
            <a class="rank-card text-decoration-none" href="<?= base_url('show/' . esc($show['id'], 'url')); ?>">
                <h4><?= esc($show['title']) ?></h4>
                <p>Type: Show</p>
                <p>Likes: <span class="count"><?= esc((string) ($show['likes_count'] ?? 0)) ?></span></p>
            </a>
        <?php endforeach; ?>
    </div>
</section>
