<section class="glass-card p-4">
    <h2 class="mb-2">Hello, <?= esc((string) session()->get('username')) ?></h2>
    <p class="text-light-emphasis mb-3">Create as many lists as you want, rename them, and curate your own ranking board.</p>
    <div class="d-flex flex-wrap gap-2">
        <a href="<?= base_url('media') ?>" class="btn btn-accent">Go to Movies</a>
        <a href="<?= base_url('show') ?>" class="btn btn-outline-light">Go to Shows</a>
        <a href="<?= base_url('logout') ?>" class="btn btn-outline-light">Logout</a>
    </div>
</section>

<section class="glass-card p-4 mt-3">
    <h3 class="mb-3">Create New List</h3>
    <div class="d-flex flex-wrap gap-2 align-items-center">
        <input type="text" id="new-list-name" class="form-control" placeholder="e.g. Weekend Binge, Sci-Fi Gems" style="max-width: 340px;">
        <button type="button" class="btn list-action-btn" id="create-list-btn">Create List</button>
    </div>
    <p class="action-feedback mt-2 mb-0" id="list-manage-feedback"></p>
</section>

<section class="glass-card p-4 mt-3">
    <h3 class="mb-3">Your Lists</h3>
    <?php if (!empty($lists)): ?>
        <?php foreach ($lists as $list): ?>
            <div class="mb-4 pb-3" style="border-bottom:1px solid rgba(255,255,255,.12)">
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
                                    <div class="pt-2"><p class="mb-0 small text-light"><?= esc($item['title']) ?></p></div>
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

<section class="glass-card p-4 mt-3">
    <h3 class="mb-3">Your Likes</h3>
    <?php if (!empty($likedItems)): ?>
        <div class="row row-cols-2 row-cols-md-5 g-3 media-grid">
            <?php foreach ($likedItems as $item): ?>
                <div class="col">
                    <a class="card h-100" href="<?= esc($item['url']) ?>">
                        <img src="<?= esc($item['poster']) ?>" alt="<?= esc($item['title']) ?>" class="w-100" onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';">
                        <div class="pt-2"><p class="mb-0 small text-light"><?= esc($item['title']) ?></p></div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-light-emphasis mb-0">No likes yet. Use the Like button on any movie or show page.</p>
    <?php endif; ?>
</section>
