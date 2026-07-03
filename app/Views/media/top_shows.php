<?php
$shows = is_array($show ?? null) ? $show : [];

$posterUrl = static function (?string $path, string $size = 'w500'): string {
    $normalized = ltrim((string) ($path ?? ''), '/');
    return $normalized !== ''
        ? 'https://image.tmdb.org/t/p/' . $size . '/' . $normalized
        : base_url('assets/image/logo.png');
};
?>

<section class="catalog-intro">
    <p class="section-kicker">Series archive</p>
    <h2>All shows</h2>
    <p>Open a show to inspect seasons, runtime details, likes, lists, and where to watch.</p>
    <div class="stats-row">
        <span><?= esc((string) count($shows)) ?> titles</span>
        <span>Lists + likes</span>
        <span>Provider lookup</span>
    </div>
</section>

<?php if (!empty($shows)): ?>
    <section class="content-section content-section-tight">
        <div class="catalog-grid">
            <?php foreach ($shows as $showItem): ?>
                <a class="media-tile" href="<?= base_url('show/' . esc((string) $showItem['id'], 'url')) ?>">
                    <img
                        src="<?= esc($posterUrl($showItem['poster'] ?? null, 'w500')) ?>"
                        alt="<?= esc($showItem['title'] ?? 'Show poster') ?>"
                        loading="lazy"
                        onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';"
                    >
                    <span class="media-title"><?= esc($showItem['title'] ?? 'Untitled show') ?></span>
                    <span class="media-meta">Show<?= !empty($showItem['begin_date']) ? ' - ' . esc(substr((string) $showItem['begin_date'], 0, 4)) : '' ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
<?php else: ?>
    <section class="empty-state">
        <h3>No shows found</h3>
        <p>Show entries are not available right now.</p>
    </section>
<?php endif; ?>