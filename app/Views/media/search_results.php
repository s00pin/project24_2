<div class="row row-cols-2 row-cols-md-4 g-3 media-grid">
    <?php
    $items = [];
    if (!empty($media) && is_array($media)) {
        $items = array_merge($items, $media);
    }
    if (!empty($shows) && is_array($shows)) {
        $items = array_merge($items, $shows);
    }
    ?>

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
                <div class="card h-100">
                    <a href="<?= $url ?>">
                        <img src="<?= esc($posterSrc) ?>" alt="<?= esc($item['title']) ?>" class="w-100" onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';">
                    </a>
                    <div class="pt-2">
                        <p class="mb-0 small text-light"><?= esc($item['title']) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="glass-card p-4">
                <h3>No Results</h3>
                <p class="mb-0">Try another search term.</p>
            </div>
        </div>
    <?php endif; ?>
</div>
