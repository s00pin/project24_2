<?php
$results = is_array($results ?? null) ? $results : [];
$query = trim((string) ($query ?? ''));
$counts = is_array($counts ?? null) ? $counts : [];
$filters = is_array($filters ?? null) ? $filters : [];

$type = (string) ($filters['type'] ?? 'all');
$sort = (string) ($filters['sort'] ?? 'random');
$yearFrom = (int) ($filters['year_from'] ?? 0);
$yearTo = (int) ($filters['year_to'] ?? 0);

$totalCount = (int) ($counts['results'] ?? count($results));
$movieCount = (int) ($counts['movies'] ?? 0);
$showCount = (int) ($counts['shows'] ?? 0);

$posterUrl = static function (?string $path, string $size = 'w500'): string {
    $normalized = ltrim((string) ($path ?? ''), '/');
    return $normalized !== ''
        ? 'https://image.tmdb.org/t/p/' . $size . '/' . $normalized
        : base_url('assets/image/logo.png');
};

$randomParams = ['sort' => 'random'];
if ($query !== '') {
    $randomParams['query'] = $query;
}
if ($type !== 'all') {
    $randomParams['type'] = $type;
}
if ($yearFrom > 0) {
    $randomParams['year_from'] = (string) $yearFrom;
}
if ($yearTo > 0) {
    $randomParams['year_to'] = (string) $yearTo;
}
$randomUrl = base_url('search') . '?' . http_build_query($randomParams);
?>

<section class="catalog-intro">
    <p class="section-kicker">Search workspace</p>
    <h2><?= $query !== '' ? 'Results for "' . esc($query) . '"' : 'Explore the catalog with filters' ?></h2>
    <p>Search results are randomized by default. Narrow down by type and year, then switch sorting when needed.</p>
    <div class="stats-row">
        <span><?= esc((string) $totalCount) ?> results</span>
        <span><?= esc((string) $movieCount) ?> movies</span>
        <span><?= esc((string) $showCount) ?> shows</span>
    </div>
</section>

<section class="content-section content-section-tight">
    <form class="search-filter-form" action="<?= base_url('search') ?>" method="get">
        <div class="search-filter-grid">
            <label class="search-filter-field" for="search-page-query">
                <span>Keyword</span>
                <input id="search-page-query" class="form-control" type="search" name="query" value="<?= esc($query) ?>" placeholder="Try Batman, detective, thriller...">
            </label>

            <label class="search-filter-field" for="search-page-type">
                <span>Type</span>
                <select id="search-page-type" class="form-control" name="type">
                    <option value="all" <?= $type === 'all' ? 'selected' : '' ?>>All</option>
                    <option value="media" <?= $type === 'media' ? 'selected' : '' ?>>Movies</option>
                    <option value="show" <?= $type === 'show' ? 'selected' : '' ?>>Shows</option>
                </select>
            </label>

            <label class="search-filter-field" for="search-page-year-from">
                <span>Year from</span>
                <input id="search-page-year-from" class="form-control" type="number" name="year_from" min="1900" max="2100" step="1" value="<?= $yearFrom > 0 ? esc((string) $yearFrom) : '' ?>" placeholder="1990">
            </label>

            <label class="search-filter-field" for="search-page-year-to">
                <span>Year to</span>
                <input id="search-page-year-to" class="form-control" type="number" name="year_to" min="1900" max="2100" step="1" value="<?= $yearTo > 0 ? esc((string) $yearTo) : '' ?>" placeholder="2026">
            </label>

            <label class="search-filter-field" for="search-page-sort">
                <span>Sort</span>
                <select id="search-page-sort" class="form-control" name="sort">
                    <option value="random" <?= $sort === 'random' ? 'selected' : '' ?>>Random</option>
                    <option value="title" <?= $sort === 'title' ? 'selected' : '' ?>>Title (A-Z)</option>
                    <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest year</option>
                    <option value="oldest" <?= $sort === 'oldest' ? 'selected' : '' ?>>Oldest year</option>
                </select>
            </label>
        </div>

        <div class="search-filter-actions">
            <button class="btn btn-primary" type="submit">Apply filters</button>
            <a class="btn btn-ghost" href="<?= esc($randomUrl) ?>">Randomize</a>
            <a class="btn btn-ghost" href="<?= base_url('search') ?>">Reset</a>
        </div>
    </form>
</section>

<?php if (!empty($results)): ?>
    <section class="content-section content-section-tight">
        <div class="catalog-grid">
            <?php foreach ($results as $item): ?>
                <?php $href = $item['type'] === 'show' ? base_url('show/' . $item['id']) : base_url('media/' . $item['id']); ?>
                <a class="media-tile" href="<?= esc($href) ?>">
                    <img
                        src="<?= esc($posterUrl($item['poster'] ?? '', 'w500')) ?>"
                        alt="<?= esc((string) ($item['title'] ?? 'Title poster')) ?>"
                        loading="lazy"
                        onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';"
                    >
                    <span class="media-title"><?= esc((string) ($item['title'] ?? 'Untitled')) ?></span>
                    <span class="media-meta"><?= esc((string) ($item['label'] ?? 'Title')) ?><?= !empty($item['year']) ? ' - ' . esc((string) $item['year']) : '' ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
<?php else: ?>
    <section class="empty-state">
        <h3>No matching titles</h3>
        <p>Try a broader keyword or clear one of the active filters.</p>
    </section>
<?php endif; ?>
