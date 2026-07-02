<?php $movieCount = count($media ?? []); ?>

<section class="library-hero glass-card">
    <p class="library-kicker">Movies</p>
    <h2>Movie Library</h2>
    <p class="text-light-emphasis mb-3">Browse all movie entries, open detail pages, and add titles to likes and lists.</p>
    <div class="library-stats">
        <span><?= esc((string) $movieCount) ?> titles</span>
        <span>Detail pages with providers</span>
        <span>List and like actions</span>
    </div>
</section>

<div class="row row-cols-2 row-cols-md-4 g-3 media-grid">
    <?php if (!empty($media) && is_array($media)): ?>
        <?php foreach ($media as $media_item): ?>
            <div class="col">
                <a class="card h-100" href="<?= base_url('media/' . esc($media_item['id'], 'url')) ?>">
                    <img src="https://image.tmdb.org/t/p/w300/<?= esc(ltrim((string) $media_item['poster_image'], '/')) ?>" alt="<?= esc($media_item['title']) ?>" class="w-100" onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';">
                    <div class="card-meta">
                        <div class="card-meta-main">
                            <p class="mb-0 card-title-text"><?= esc($media_item['title']) ?></p>
                            <p class="mb-0 card-subtext">
                                Movie<?= !empty($media_item['release_date']) ? ' | ' . esc(substr((string) $media_item['release_date'], 0, 4)) : '' ?>
                            </p>
                        </div>
                        <span class="card-badge">Movie</span>
                    </div>
                </a>
            </div>
        <?php endforeach ?>
    <?php else: ?>
        <div class="col-12">
            <div class="glass-card p-4">
                <h3>No Movies Found</h3>
                <p class="mb-0">No movie entries are available right now.</p>
            </div>
        </div>
    <?php endif ?>
</div>
