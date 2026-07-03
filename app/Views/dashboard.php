<?php
$listCount = count($lists ?? []);
$likedCount = count($likedItems ?? []);
$itemCount = 0;
foreach (($lists ?? []) as $list) {
    $itemCount += count($list['items'] ?? []);
}
?>

<section class="dashboard-wrap">
    <section class="dashboard-summary">
        <h2>Your watch dashboard</h2>
        <p>Organize titles into focused lists, rename the way you plan watch nights, and track everything you've liked.</p>

        <div class="hero-actions">
            <a href="<?= base_url('media') ?>" class="btn btn-primary">Browse movies</a>
            <a href="<?= base_url('show') ?>" class="btn btn-ghost">Browse shows</a>
            <a href="<?= base_url('logout') ?>" class="btn btn-ghost">Logout</a>
        </div>

        <div class="summary-grid">
            <div class="summary-card">
                <span class="summary-label">Lists</span>
                <span class="summary-value"><?= esc((string) $listCount) ?></span>
            </div>
            <div class="summary-card">
                <span class="summary-label">Saved list items</span>
                <span class="summary-value"><?= esc((string) $itemCount) ?></span>
            </div>
            <div class="summary-card">
                <span class="summary-label">Liked titles</span>
                <span class="summary-value"><?= esc((string) $likedCount) ?></span>
            </div>
        </div>
    </section>

    <section class="section-frame">
        <header class="section-head">
            <div>
                <p class="section-kicker">List manager</p>
                <h3>Create a new list</h3>
            </div>
            <p>Use clear names like "Friday thrillers" or "Watch with family".</p>
        </header>

        <div class="create-list-form">
            <input type="text" id="new-list-name" class="form-control" placeholder="List name">
            <button type="button" class="btn btn-primary" id="create-list-btn">Create list</button>
        </div>
        <p class="action-feedback" id="list-manage-feedback"></p>
    </section>

    <section class="section-frame">
        <header class="section-head">
            <div>
                <p class="section-kicker">Your lists</p>
                <h3>Manage saved collections</h3>
            </div>
            <p>Rename custom lists, delete what you no longer need, and open saved titles directly.</p>
        </header>

        <?php if (!empty($lists)): ?>
            <div class="list-stack">
                <?php foreach ($lists as $list): ?>
                    <article class="list-block">
                        <div class="list-head">
                            <h3><?= esc($list['name']) ?></h3>
                            <div class="rename-wrap">
                                <input
                                    type="text"
                                    class="rename-list-input form-control"
                                    data-list-id="<?= esc((string) $list['id']) ?>"
                                    placeholder="Rename list"
                                >
                                <button type="button" class="btn btn-ghost btn-sm rename-list-btn" data-list-id="<?= esc((string) $list['id']) ?>">Rename</button>
                                <?php if (!in_array($list['slug'] ?? '', ['favorites', 'watch-later'], true)): ?>
                                    <button type="button" class="btn btn-danger btn-sm delete-list-btn" data-list-id="<?= esc((string) $list['id']) ?>">Delete</button>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (!empty($list['items'])): ?>
                            <div class="mini-grid">
                                <?php foreach ($list['items'] as $item): ?>
                                    <a class="mini-card" href="<?= esc($item['url']) ?>">
                                        <img src="<?= esc($item['poster']) ?>" alt="<?= esc($item['title']) ?>" loading="lazy" onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';">
                                        <p><?= esc($item['title']) ?></p>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No titles in this list yet.</p>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <section class="empty-state">
                <h3>No lists yet</h3>
                <p>Create your first list above to start organizing watch picks.</p>
            </section>
        <?php endif; ?>
    </section>

    <section class="section-frame">
        <header class="section-head">
            <div>
                <p class="section-kicker">Liked titles</p>
                <h3>Your likes</h3>
            </div>
            <p>Quick access to every title you marked as liked.</p>
        </header>

        <?php if (!empty($likedItems)): ?>
            <div class="mini-grid">
                <?php foreach ($likedItems as $item): ?>
                    <a class="mini-card" href="<?= esc($item['url']) ?>">
                        <img src="<?= esc($item['poster']) ?>" alt="<?= esc($item['title']) ?>" loading="lazy" onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';">
                        <p><?= esc($item['title']) ?></p>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <section class="empty-state">
                <h3>No liked titles yet</h3>
                <p>Use the Like button on movie and show detail pages to populate this section.</p>
            </section>
        <?php endif; ?>
    </section>
</section>