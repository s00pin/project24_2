<?php
$featuredMovies = is_array($featuredMovies ?? null) ? $featuredMovies : [];
$featuredShows = is_array($featuredShows ?? null) ? $featuredShows : [];
$popularMovies = is_array($popularMovies ?? null) ? $popularMovies : [];
$popularShows = is_array($popularShows ?? null) ? $popularShows : [];

$posterUrl = static function (?string $path, string $size = 'w500'): string {
    $normalized = ltrim((string) ($path ?? ''), '/');
    return $normalized !== ''
        ? 'https://image.tmdb.org/t/p/' . $size . '/' . $normalized
        : base_url('assets/image/logo.png');
};

$heroMovie = $featuredMovies[0] ?? $popularMovies[0] ?? null;
?>

<section class="landing-minimal">
    <div class="landing-copy">
        <p class="landing-kicker">Reel Atlas</p>
        <h2>Find something worth watching in minutes.</h2>
        <p class="landing-sub">
            No endless scrolling. Start from curated picks, jump into details fast, and save titles into your own lists only when they matter.
        </p>

        <div class="hero-actions">
            <a href="<?= base_url('media'); ?>" class="btn btn-primary">Browse movies</a>
            <a href="<?= base_url('show'); ?>" class="btn btn-ghost">Browse shows</a>
            <a href="<?= base_url('search'); ?>" class="btn btn-ghost">Search titles</a>
            <?php if (! session()->get('logged_in')): ?>
                <a href="<?= base_url('login'); ?>" class="btn btn-ghost">Login</a>
            <?php endif; ?>
        </div>

        <a class="scroll-link" href="#home-content">See curated picks below</a>
    </div>

    <?php if (! empty($heroMovie)): ?>
        <a class="landing-feature" href="<?= base_url('media/' . esc((string) $heroMovie['id'], 'url')); ?>">
            <img
                src="<?= esc($posterUrl($heroMovie['poster_image'] ?? null, 'w500')) ?>"
                alt="<?= esc($heroMovie['title'] ?? 'Featured movie') ?>"
                loading="lazy"
                onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';"
            >
            <div>
                <p class="feature-kicker">Tonight's pick</p>
                <h3><?= esc($heroMovie['title'] ?? 'Untitled movie') ?></h3>
                <p><?= ! empty($heroMovie['release_date']) ? esc(substr((string) $heroMovie['release_date'], 0, 4)) : 'Movie' ?></p>
            </div>
        </a>
    <?php endif; ?>
</section>

<section id="home-content" class="content-section">
    <header class="content-head">
        <div>
            <p class="section-kicker">Featured movies</p>
            <h3>Start with these movies</h3>
        </div>
        <a class="section-link" href="<?= base_url('media') ?>">View all movies</a>
    </header>

    <?php if (! empty($featuredMovies)): ?>
        <div class="poster-flow" role="list">
            <?php foreach ($featuredMovies as $movie): ?>
                <a class="media-tile" role="listitem" href="<?= base_url('media/' . esc((string) $movie['id'], 'url')); ?>">
                    <img
                        src="<?= esc($posterUrl($movie['poster_image'] ?? null, 'w500')) ?>"
                        alt="<?= esc($movie['title'] ?? 'Movie poster') ?>"
                        loading="lazy"
                        onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';"
                    >
                    <span class="media-title"><?= esc($movie['title'] ?? 'Untitled movie') ?></span>
                    <span class="media-meta">Movie<?= ! empty($movie['release_date']) ? ' - ' . esc(substr((string) $movie['release_date'], 0, 4)) : '' ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <section class="empty-state">
            <h3>No movie picks yet</h3>
            <p>Once movies are imported, this rail will populate.</p>
        </section>
    <?php endif; ?>
</section>

<section class="content-section">
    <header class="content-head">
        <div>
            <p class="section-kicker">Featured shows</p>
            <h3>Then check these series</h3>
        </div>
        <a class="section-link" href="<?= base_url('show') ?>">View all shows</a>
    </header>

    <?php if (! empty($featuredShows)): ?>
        <div class="poster-flow" role="list">
            <?php foreach ($featuredShows as $show): ?>
                <a class="media-tile" role="listitem" href="<?= base_url('show/' . esc((string) $show['id'], 'url')); ?>">
                    <img
                        src="<?= esc($posterUrl($show['poster'] ?? null, 'w500')) ?>"
                        alt="<?= esc($show['title'] ?? 'Show poster') ?>"
                        loading="lazy"
                        onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';"
                    >
                    <span class="media-title"><?= esc($show['title'] ?? 'Untitled show') ?></span>
                    <span class="media-meta">Show<?= ! empty($show['begin_date']) ? ' - ' . esc(substr((string) $show['begin_date'], 0, 4)) : '' ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <section class="empty-state">
            <h3>No show picks yet</h3>
            <p>Import shows and this rail will fill automatically.</p>
        </section>
    <?php endif; ?>
</section>

<section class="content-section">
    <header class="content-head">
        <div>
            <p class="section-kicker">Community signal</p>
            <h3>Most liked right now</h3>
        </div>
        <p class="section-note">Based on user likes in this app.</p>
    </header>

    <div class="ranking-columns">
        <ol class="ranking-list">
            <?php foreach ($popularMovies as $movie): ?>
                <li>
                    <a href="<?= base_url('media/' . esc((string) $movie['id'], 'url')); ?>">
                        <strong><?= esc($movie['title'] ?? 'Untitled movie') ?></strong>
                        <span>Movie - <?= esc((string) ($movie['likes_count'] ?? 0)) ?> likes</span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ol>

        <ol class="ranking-list">
            <?php foreach ($popularShows as $show): ?>
                <li>
                    <a href="<?= base_url('show/' . esc((string) $show['id'], 'url')); ?>">
                        <strong><?= esc($show['title'] ?? 'Untitled show') ?></strong>
                        <span>Show - <?= esc((string) ($show['likes_count'] ?? 0)) ?> likes</span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
</section>
