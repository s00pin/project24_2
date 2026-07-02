<?php $movieCount = count($media ?? []); ?>

<section class="library-hero">
    <p class="library-kicker">Movies</p>
    <h2>Movie Library</h2>
    <p class="text-light-emphasis">Browse the complete movie catalog and open details to view likes, lists, and providers.</p>
    <div class="library-stats">
        <span><?= esc((string) $movieCount) ?> total titles</span>
        <span>Personal lists supported</span>
        <span>Region provider lookup</span>
    </div>
</section>

<?php if (!empty($media) && is_array($media)): ?>
    <section class="panel">
        <div class="catalog-grid">
            <?php foreach ($media as $media_item): ?>
                <a class="media-card" href="<?= base_url('media/' . esc($media_item['id'], 'url')) ?>">
                    <img src="https://image.tmdb.org/t/p/w300/<?= esc(ltrim((string) $media_item['poster_image'], '/')) ?>" alt="<?= esc($media_item['title']) ?>" onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';">
                    <div class="card-meta">
                        <div class="card-meta-main">
                            <p class="card-title-text"><?= esc($media_item['title']) ?></p>
                            <p class="card-subtext">
                                Movie<?= !empty($media_item['release_date']) ? ' | ' . esc(substr((string) $media_item['release_date'], 0, 4)) : '' ?>
                            </p>
                        </div>
                        <span class="card-badge">Movie</span>
                    </div>
                </a>
            <?php endforeach ?>
        </div>
    </section>
<?php else: ?>
    <section class="empty-card">
        <h3>No Movies Found</h3>
        <p>Movie entries are not available right now.</p>
    </section>
<?php endif ?>
