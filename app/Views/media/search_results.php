<?php
$items = [];
if (!empty($media) && is_array($media)) {
    $items = array_merge($items, $media);
}
if (!empty($shows) && is_array($shows)) {
    $items = array_merge($items, $shows);
}
$totalCount = count($items);
?>

<section class="library-hero search-page">
    <p class="library-kicker">Search</p>
    <h2>Search Results</h2>
    <?php if (!empty($query ?? '')): ?>
        <p class="text-light-emphasis">Results for "<?= esc((string) $query) ?>"</p>
    <?php else: ?>
        <p class="text-light-emphasis">Showing all available titles.</p>
    <?php endif; ?>
    <div class="library-stats">
        <span><?= esc((string) $totalCount) ?> results</span>
        <span><?= esc((string) count($media ?? [])) ?> movies</span>
        <span><?= esc((string) count($shows ?? [])) ?> shows</span>
    </div>
</section>

<?php if (!empty($items)): ?>
    <section class="panel">
        <div class="catalog-grid">
            <?php foreach ($items as $item): ?>
                <?php
                $isMovie = array_key_exists('poster_image', $item);
                $url = $isMovie ? base_url('media/' . $item['id']) : base_url('show/' . $item['id']);
                $poster = $isMovie ? ($item['poster_image'] ?? '') : ($item['poster'] ?? '');
                $posterSrc = trim((string) $poster) !== ''
                    ? 'https://image.tmdb.org/t/p/w300/' . ltrim((string) $poster, '/')
                    : base_url('assets/image/logo.png');
                ?>
                <a class="media-card" href="<?= esc($url) ?>">
                    <img src="<?= esc($posterSrc) ?>" alt="<?= esc($item['title']) ?>" onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';">
                    <div class="card-meta">
                        <div class="card-meta-main">
                            <p class="card-title-text"><?= esc($item['title']) ?></p>
                            <p class="card-subtext"><?= $isMovie ? 'Movie' : 'Show' ?></p>
                        </div>
                        <span class="card-badge">Open</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
<?php else: ?>
    <section class="empty-card search-page">
        <h3>No Results</h3>
        <p>Try another title, keyword, or broader search phrase.</p>
    </section>
<?php endif; ?>
