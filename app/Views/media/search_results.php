<?php
$mediaItems = is_array($media ?? null) ? $media : [];
$showItems = is_array($shows ?? null) ? $shows : [];

$results = [];

foreach ($mediaItems as $item) {
    $results[] = [
        'id' => (int) ($item['id'] ?? 0),
        'title' => (string) ($item['title'] ?? 'Untitled movie'),
        'type' => 'media',
        'label' => 'Movie',
        'poster' => (string) ($item['poster_image'] ?? ''),
        'year' => !empty($item['release_date']) ? substr((string) $item['release_date'], 0, 4) : '',
    ];
}

foreach ($showItems as $item) {
    $results[] = [
        'id' => (int) ($item['id'] ?? 0),
        'title' => (string) ($item['title'] ?? 'Untitled show'),
        'type' => 'show',
        'label' => 'Show',
        'poster' => (string) ($item['poster'] ?? ''),
        'year' => !empty($item['begin_date']) ? substr((string) $item['begin_date'], 0, 4) : '',
    ];
}

usort($results, static fn(array $a, array $b): int => strcasecmp($a['title'], $b['title']));

$posterUrl = static function (?string $path, string $size = 'w500'): string {
    $normalized = ltrim((string) ($path ?? ''), '/');
    return $normalized !== ''
        ? 'https://image.tmdb.org/t/p/' . $size . '/' . $normalized
        : base_url('assets/image/logo.png');
};
?>

<section class="catalog-intro">
    <p class="section-kicker">Search results</p>
    <h2><?= !empty($query ?? '') ? 'Results for "' . esc((string) $query) . '"' : 'Browse all available titles' ?></h2>
    <p>Search across imported movies and shows. If local results are empty, the app imports from TMDB automatically.</p>
    <div class="stats-row">
        <span><?= esc((string) count($results)) ?> results</span>
        <span><?= esc((string) count($mediaItems)) ?> movies</span>
        <span><?= esc((string) count($showItems)) ?> shows</span>
    </div>
</section>

<?php if (!empty($results)): ?>
    <section class="content-section content-section-tight">
        <div class="catalog-grid">
            <?php foreach ($results as $item): ?>
                <?php $href = $item['type'] === 'show' ? base_url('show/' . $item['id']) : base_url('media/' . $item['id']); ?>
                <a class="media-tile" href="<?= esc($href) ?>">
                    <img
                        src="<?= esc($posterUrl($item['poster'], 'w500')) ?>"
                        alt="<?= esc($item['title']) ?>"
                        loading="lazy"
                        onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';"
                    >
                    <span class="media-title"><?= esc($item['title']) ?></span>
                    <span class="media-meta"><?= esc($item['label']) ?><?= $item['year'] !== '' ? ' - ' . esc($item['year']) : '' ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
<?php else: ?>
    <section class="empty-state">
        <h3>No matching titles</h3>
        <p>Try another keyword or a shorter title.</p>
    </section>
<?php endif; ?>