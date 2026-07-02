<?php $showCount = count($show ?? []); ?>

<section class="library-hero">
    <p class="library-kicker">Shows</p>
    <h2>Show Library</h2>
    <p class="text-light-emphasis">Browse the complete show catalog and open details to manage likes, lists, and providers.</p>
    <div class="library-stats">
        <span><?= esc((string) $showCount) ?> total titles</span>
        <span>Personal lists supported</span>
        <span>Region provider lookup</span>
    </div>
</section>

<?php if (!empty($show) && is_array($show)): ?>
    <section class="panel">
        <div class="catalog-grid">
            <?php foreach ($show as $show_item): ?>
                <a class="media-card" href="<?= base_url('show/' . esc($show_item['id'], 'url')) ?>">
                    <img src="https://image.tmdb.org/t/p/w300/<?= esc(ltrim((string) $show_item['poster'], '/')) ?>" alt="<?= esc($show_item['title']) ?>" onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';">
                    <div class="card-meta">
                        <div class="card-meta-main">
                            <p class="card-title-text"><?= esc($show_item['title']) ?></p>
                            <p class="card-subtext">
                                Show<?= !empty($show_item['begin_date']) ? ' | ' . esc(substr((string) $show_item['begin_date'], 0, 4)) : '' ?>
                            </p>
                        </div>
                        <span class="card-badge">Show</span>
                    </div>
                </a>
            <?php endforeach ?>
        </div>
    </section>
<?php else: ?>
    <section class="empty-card">
        <h3>No Shows Found</h3>
        <p>Show entries are not available right now.</p>
    </section>
<?php endif ?>
