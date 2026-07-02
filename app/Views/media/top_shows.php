<?php $showCount = count($show ?? []); ?>

<section class="library-hero glass-card">
    <p class="library-kicker">Shows</p>
    <h2>Show Library</h2>
    <p class="text-light-emphasis mb-3">Browse all show entries, open detail pages, and organize titles in personal lists.</p>
    <div class="library-stats">
        <span><?= esc((string) $showCount) ?> titles</span>
        <span>Region-based providers</span>
        <span>List and like actions</span>
    </div>
</section>

<div class="row row-cols-2 row-cols-md-4 g-3 media-grid">
    <?php if (!empty($show) && is_array($show)): ?>
        <?php foreach ($show as $show_item): ?>
            <div class="col">
                <a class="card h-100" href="<?= base_url('show/' . esc($show_item['id'], 'url')) ?>">
                    <img src="https://image.tmdb.org/t/p/w300/<?= esc(ltrim((string) $show_item['poster'], '/')) ?>" alt="<?= esc($show_item['title']) ?>" class="w-100" onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';">
                    <div class="card-meta">
                        <div class="card-meta-main">
                            <p class="mb-0 card-title-text"><?= esc($show_item['title']) ?></p>
                            <p class="mb-0 card-subtext">
                                Show<?= !empty($show_item['begin_date']) ? ' | ' . esc(substr((string) $show_item['begin_date'], 0, 4)) : '' ?>
                            </p>
                        </div>
                        <span class="card-badge">Show</span>
                    </div>
                </a>
            </div>
        <?php endforeach ?>
    <?php else: ?>
        <div class="col-12">
            <div class="glass-card p-4">
                <h3>No Shows Found</h3>
                <p class="mb-0">No show entries are available right now.</p>
            </div>
        </div>
    <?php endif ?>
</div>
