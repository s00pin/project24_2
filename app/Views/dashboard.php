<?php
$listCount = count($lists ?? []);
$likedCount = count($likedItems ?? []);
$itemCount = 0;
foreach (($lists ?? []) as $list) {
    $itemCount += count($list['items'] ?? []);
}
?>

<section class="hero">
    <div class="hero-copy">
        <h2>Your Watch Dashboard</h2>
        <p>Keep lists organized, rename them any time, and manage the titles you have liked across movies and shows.</p>
        <div class="hero-actions">
            <a href="<?= base_url('media') ?>" class="btn btn-accent">Browse Movies</a>
            <a href="<?= base_url('show') ?>" class="btn btn-outline-light">Browse Shows</a>
            <a href="<?= base_url('logout') ?>" class="btn btn-outline-light">Logout</a>
        </div>
    </div>

    <aside class="hero-side">
        <h3>Your Snapshot</h3>
        <ul class="kpi-list">
            <li>Lists <span><?= esc((string) $listCount) ?></span></li>
            <li>Saved list items <span><?= esc((string) $itemCount) ?></span></li>
            <li>Liked titles <span><?= esc((string) $likedCount) ?></span></li>
        </ul>
    </aside>
</section>

<section class="panel" style="margin-bottom:1rem;">
    <div class="section-head">
        <h3>Create New List</h3>
        <p>Give each list a clear purpose so it is easy to revisit.</p>
    </div>

    <div style="display:flex;flex-wrap:wrap;gap:0.55rem;align-items:center;">
        <input type="text" id="new-list-name" class="form-control" placeholder="e.g. Weekend Picks, Family Night" style="max-width:350px;">
        <button type="button" class="btn btn-accent" id="create-list-btn">Create List</button>
    </div>
    <p class="action-feedback" id="list-manage-feedback"></p>
</section>

<section class="panel" style="margin-bottom:1rem;">
    <div class="section-head">
        <h3>Your Lists</h3>
        <p>Rename or delete custom lists and open saved titles.</p>
    </div>

    <?php if (!empty($lists)): ?>
        <?php foreach ($lists as $list): ?>
            <article style="padding:0.95rem 0;border-top:1px solid var(--line);">
                <div style="display:flex;flex-wrap:wrap;gap:0.55rem;align-items:center;margin-bottom:0.55rem;">
                    <h4 style="margin:0;font-family:'Instrument Serif',serif;font-size:1.5rem;font-weight:400;color:var(--heading);"><?= esc($list['name']) ?></h4>
                    <input type="text" class="rename-list-input" data-list-id="<?= esc((string) $list['id']) ?>" placeholder="Rename list" style="max-width:260px;">
                    <button type="button" class="btn btn-outline-light btn-sm rename-list-btn" data-list-id="<?= esc((string) $list['id']) ?>">Rename</button>
                    <?php if (!in_array($list['slug'] ?? '', ['favorites', 'watch-later'], true)): ?>
                        <button type="button" class="btn btn-outline-danger btn-sm delete-list-btn" data-list-id="<?= esc((string) $list['id']) ?>">Delete</button>
                    <?php endif; ?>
                </div>

                <?php if (!empty($list['items'])): ?>
                    <div class="catalog-grid">
                        <?php foreach ($list['items'] as $item): ?>
                            <a class="media-card" href="<?= esc($item['url']) ?>">
                                <img src="<?= esc($item['poster']) ?>" alt="<?= esc($item['title']) ?>" onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';">
                                <div class="card-meta">
                                    <div class="card-meta-main">
                                        <p class="card-title-text"><?= esc($item['title']) ?></p>
                                        <p class="card-subtext">Saved title</p>
                                    </div>
                                    <span class="card-badge">Open</span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-light-emphasis" style="margin:0;">No titles in this list yet.</p>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    <?php else: ?>
        <section class="empty-card">
            <h3>No Lists Yet</h3>
            <p>Create your first list to start organizing titles.</p>
        </section>
    <?php endif; ?>
</section>

<section class="panel">
    <div class="section-head">
        <h3>Your Likes</h3>
        <p>Quick access to all titles you liked.</p>
    </div>

    <?php if (!empty($likedItems)): ?>
        <div class="catalog-grid">
            <?php foreach ($likedItems as $item): ?>
                <a class="media-card" href="<?= esc($item['url']) ?>">
                    <img src="<?= esc($item['poster']) ?>" alt="<?= esc($item['title']) ?>" onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';">
                    <div class="card-meta">
                        <div class="card-meta-main">
                            <p class="card-title-text"><?= esc($item['title']) ?></p>
                            <p class="card-subtext">Liked title</p>
                        </div>
                        <span class="card-badge">Open</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <section class="empty-card">
            <h3>No Likes Yet</h3>
            <p>Use the Like button on movie or show details to build this section.</p>
        </section>
    <?php endif; ?>
</section>
