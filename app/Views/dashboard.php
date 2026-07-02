<?php
$listCount = count($lists ?? []);
$likedCount = count($likedItems ?? []);
$itemCount = 0;
foreach (($lists ?? []) as $list) {
    $itemCount += count($list['items'] ?? []);
}
?>

<section class="hero">
    <div class="hero-grid">
        <div>
            <h2>Your Dashboard</h2>
            <p class="lead mb-0">Manage list names, add new lists, and keep track of all liked titles in one place.</p>
            <div class="hero-actions">
                <a href="<?= base_url('media') ?>" class="btn btn-accent">Browse Movies</a>
                <a href="<?= base_url('show') ?>" class="btn btn-outline-light">Browse Shows</a>
                <a href="<?= base_url('logout') ?>" class="btn btn-outline-light">Logout</a>
            </div>
        </div>

        <aside class="vibe-panel">
            <h3>Your Snapshot</h3>
            <ul class="vibe-list">
                <li><span>Lists</span> <?= esc((string) $listCount) ?></li>
                <li><span>Items in lists</span> <?= esc((string) $itemCount) ?></li>
                <li><span>Liked titles</span> <?= esc((string) $likedCount) ?></li>
            </ul>
        </aside>
    </div>
</section>

<section class="glass-card p-4 mb-3">
    <div class="section-heading">
        <h3>Create New List</h3>
        <p>List names are unique per user</p>
    </div>

    <div class="d-flex flex-wrap gap-2 align-items-center">
        <input type="text" id="new-list-name" class="form-control" placeholder="e.g. Friday Night, Drama Queue" style="max-width: 340px;">
        <button type="button" class="btn list-action-btn" id="create-list-btn">Create List</button>
    </div>
    <p class="action-feedback mt-2 mb-0" id="list-manage-feedback"></p>
</section>

<section class="glass-card p-4 mb-3">
    <div class="section-heading">
        <h3>Your Lists</h3>
        <p>Rename or delete custom lists and review saved titles</p>
    </div>

    <?php if (!empty($lists)): ?>
        <?php foreach ($lists as $list): ?>
            <div class="mb-4 pb-3" style="border-bottom:1px solid rgba(255,255,255,.14)">
                <div class="d-flex flex-wrap gap-2 align-items-center mb-2">
                    <h4 class="mb-0 me-2"><?= esc($list['name']) ?></h4>
                    <input type="text" class="form-control rename-list-input" data-list-id="<?= esc((string) $list['id']) ?>" placeholder="Rename list" style="max-width: 260px;">
                    <button type="button" class="btn btn-outline-light btn-sm rename-list-btn" data-list-id="<?= esc((string) $list['id']) ?>">Rename</button>
                    <?php if (!in_array($list['slug'] ?? '', ['favorites', 'watch-later'], true)): ?>
                        <button type="button" class="btn btn-outline-danger btn-sm delete-list-btn" data-list-id="<?= esc((string) $list['id']) ?>">Delete</button>
                    <?php endif; ?>
                </div>

                <?php if (!empty($list['items'])): ?>
                    <div class="row row-cols-2 row-cols-md-5 g-3 media-grid">
                        <?php foreach ($list['items'] as $item): ?>
                            <div class="col">
                                <a class="card h-100" href="<?= esc($item['url']) ?>">
                                    <img src="<?= esc($item['poster']) ?>" alt="<?= esc($item['title']) ?>" class="w-100" onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';">
                                    <div class="card-meta">
                                        <div class="card-meta-main">
                                            <p class="mb-0 card-title-text"><?= esc($item['title']) ?></p>
                                            <p class="mb-0 card-subtext">Saved title</p>
                                        </div>
                                        <span class="card-badge">Open</span>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-light-emphasis mb-0">No titles in this list yet.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-light-emphasis mb-0">No lists yet. Create your first list above.</p>
    <?php endif; ?>
</section>

<section class="glass-card p-4">
    <div class="section-heading">
        <h3>Your Likes</h3>
        <p>Titles you marked with Like</p>
    </div>

    <?php if (!empty($likedItems)): ?>
        <div class="row row-cols-2 row-cols-md-5 g-3 media-grid">
            <?php foreach ($likedItems as $item): ?>
                <div class="col">
                    <a class="card h-100" href="<?= esc($item['url']) ?>">
                        <img src="<?= esc($item['poster']) ?>" alt="<?= esc($item['title']) ?>" class="w-100" onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';">
                        <div class="card-meta">
                            <div class="card-meta-main">
                                <p class="mb-0 card-title-text"><?= esc($item['title']) ?></p>
                                <p class="mb-0 card-subtext">Liked title</p>
                            </div>
                            <span class="card-badge">Open</span>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-light-emphasis mb-0">No likes yet. Use the Like button on movie or show detail pages.</p>
    <?php endif; ?>
</section>
