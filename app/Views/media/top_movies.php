<section class="library-hero glass-card mb-4">
    <p class="library-kicker">Collection</p>
    <h2>Movie Library</h2>
    <p class="text-light-emphasis">Explore the full movie catalog with clean cards and quick access to details, likes, and lists.</p>
    <div class="library-stats">
        <span><?= esc((string) count($media ?? [])) ?> titles</span>
        <span>Live suggestions enabled</span>
        <span>TMDB fallback search</span>
    </div>
</section>

<div class="row row-cols-2 row-cols-md-4 g-3 media-grid">
    <?php if (!empty($media) && is_array($media)): ?>
        <?php foreach ($media as $media_item): ?>
            <div class="col">
                <a class="card h-100" href="<?= base_url('media/' . esc($media_item['id'], 'url')) ?>">
                    <img src="https://image.tmdb.org/t/p/w300/<?= esc(ltrim((string) $media_item['poster_image'], '/')) ?>" alt="<?= esc($media_item['title']) ?>" class="w-100">
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
                <h3>No Movies</h3>
                <p class="mb-0">Unable to find any movies right now.</p>
            </div>
        </div>
    <?php endif ?>
</div>
