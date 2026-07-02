<section class="library-hero glass-card mb-4">
    <p class="library-kicker">Collection</p>
    <h2>Show Library</h2>
    <p class="text-light-emphasis">Browse TV shows with richer detail views and personalized list actions.</p>
    <div class="library-stats">
        <span><?= esc((string) count($show ?? [])) ?> titles</span>
        <span>Region watch providers</span>
        <span>Like-based ranking</span>
    </div>
</section>

<div class="row row-cols-2 row-cols-md-4 g-3 media-grid">
    <?php if (!empty($show) && is_array($show)): ?>
        <?php foreach ($show as $show_item): ?>
            <div class="col">
                <a class="card h-100" href="<?= base_url('show/' . esc($show_item['id'], 'url')) ?>">
                    <img src="https://image.tmdb.org/t/p/w300/<?= esc(ltrim((string) $show_item['poster'], '/')) ?>" alt="<?= esc($show_item['title']) ?>" class="w-100">
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
                <h3>No Shows</h3>
                <p class="mb-0">Unable to find any shows right now.</p>
            </div>
        </div>
    <?php endif ?>
</div>
