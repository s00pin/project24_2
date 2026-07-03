<?php
$movies = is_array($media ?? null) ? $media : [];

$posterUrl = static function (?string $path, string $size = 'w500'): string {
    $normalized = ltrim((string) ($path ?? ''), '/');
    return $normalized !== ''
        ? 'https://image.tmdb.org/t/p/' . $size . '/' . $normalized
        : base_url('assets/image/logo.png');
};
?>

<section class="catalog-intro">
    <p class="section-kicker">Movie archive</p>
    <h2>All movies</h2>
    <p>Open a title to view synopsis, likes, list controls, and streaming providers.</p>
    <div class="stats-row">
        <span><?= esc((string) count($movies)) ?> titles</span>
        <span>Lists + likes</span>
        <span>Provider lookup</span>
    </div>
</section>

<?php if (!empty($movies)): ?>
    <section class="content-section content-section-tight">
        <div class="catalog-grid">
            <?php foreach ($movies as $movie): ?>
                <a class="media-tile" href="<?= base_url('media/' . esc((string) $movie['id'], 'url')) ?>">
                    <img
                        src="<?= esc($posterUrl($movie['poster_image'] ?? null, 'w500')) ?>"
                        alt="<?= esc($movie['title'] ?? 'Movie poster') ?>"
                        loading="lazy"
                        onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';"
                    >
                    <span class="media-title"><?= esc($movie['title'] ?? 'Untitled movie') ?></span>
                    <span class="media-meta">Movie<?= !empty($movie['release_date']) ? ' - ' . esc(substr((string) $movie['release_date'], 0, 4)) : '' ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
<?php else: ?>
    <section class="empty-state">
        <h3>No movies found</h3>
        <p>Movie entries are not available right now.</p>
    </section>
<?php endif; ?>