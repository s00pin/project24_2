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

<section class="library-hero glass-card search-page mb-3">
    <p class="library-kicker">Search</p>
    <h2>Search Results</h2>
    <?php if (!empty($query ?? '')): ?>
        <p class="text-light-emphasis mb-2">Showing results for "<?= esc((string) $query) ?>".</p>
    <?php else: ?>
        <p class="text-light-emphasis mb-2">Showing all available movie and show entries.</p>
    <?php endif; ?>
    <div class="library-stats">
        <span><?= esc((string) $totalCount) ?> results</span>
        <span><?= esc((string) count($media ?? [])) ?> movies</span>
        <span><?= esc((string) count($shows ?? [])) ?> shows</span>
    </div>
</section>

<div class="row row-cols-2 row-cols-md-4 g-3 media-grid">
    <?php if (!empty($items)): ?>
        <?php foreach ($items as $item): ?>
            <?php
            $isMovie = array_key_exists('poster_image', $item);
            $url = $isMovie ? base_url('media/' . $item['id']) : base_url('show/' . $item['id']);
            $poster = $isMovie ? ($item['poster_image'] ?? '') : ($item['poster'] ?? '');
            $posterSrc = trim((string) $poster) !== ''
                ? 'https://image.tmdb.org/t/p/w300/' . ltrim((string) $poster, '/')
                : base_url('assets/image/logo.png');
            ?>
            <div class="col">
                <a class="card h-100" href="<?= esc($url) ?>">
                    <img src="<?= esc($posterSrc) ?>" alt="<?= esc($item['title']) ?>" class="w-100" onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';">
                    <div class="card-meta">
                        <div class="card-meta-main">
                            <p class="mb-0 card-title-text"><?= esc($item['title']) ?></p>
                            <p class="mb-0 card-subtext"><?= $isMovie ? 'Movie' : 'Show' ?></p>
                        </div>
                        <span class="card-badge">Open</span>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="glass-card p-4">
                <h3>No Results</h3>
                <p class="mb-0">Try another title or keyword.</p>
            </div>
        </div>
    <?php endif; ?>
</div>
