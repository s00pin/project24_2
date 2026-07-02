<section class="hero mb-4">
    <h2>Browse movies and shows by title.</h2>
    <p class="lead mb-4">Explore the catalog first, then sign in to save likes and custom lists.</p>
    <div class="d-flex flex-wrap gap-2">
        <a href="<?= base_url('media'); ?>" class="btn btn-accent">Explore Movies</a>
        <a href="<?= base_url('show'); ?>" class="btn btn-outline-light">Browse Shows</a>
        <?php if (! session()->get('logged_in')): ?>
            <a href="<?= base_url('login'); ?>" class="btn btn-outline-light">Login for Personal Features</a>
        <?php endif; ?>
    </div>
</section>

<section class="mb-4">
    <h3 class="mb-3">Featured Movies</h3>
    <div class="row row-cols-2 row-cols-md-4 g-3 media-grid">
        <?php foreach ($featuredMovies ?? [] as $movie): ?>
            <div class="col">
                <a class="card" href="<?= base_url('media/' . esc($movie['id'], 'url')); ?>">
                    <img src="https://image.tmdb.org/t/p/w300/<?= esc(ltrim((string) $movie['poster_image'], '/')); ?>" alt="<?= esc($movie['title']); ?>" class="w-100">
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section>
    <h3 class="mb-3">Featured Shows</h3>
    <div class="row row-cols-2 row-cols-md-4 g-3 media-grid">
        <?php foreach ($featuredShows ?? [] as $show): ?>
            <div class="col">
                <a class="card" href="<?= base_url('show/' . esc($show['id'], 'url')); ?>">
                    <img src="https://image.tmdb.org/t/p/w300/<?= esc(ltrim((string) $show['poster'], '/')); ?>" alt="<?= esc($show['title']); ?>" class="w-100">
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="mt-4">
    <h3 class="mb-3">Popular Right Now (By Likes)</h3>
    <div class="row row-cols-2 row-cols-md-6 g-3 media-grid">
        <?php foreach ($popularMovies ?? [] as $movie): ?>
            <div class="col">
                <a class="card" href="<?= base_url('media/' . esc($movie['id'], 'url')); ?>">
                    <img src="https://image.tmdb.org/t/p/w300/<?= esc(ltrim((string) ($movie['poster_image'] ?? ''), '/')); ?>" alt="<?= esc($movie['title']); ?>" class="w-100" onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';">
                </a>
                <p class="small mt-1 mb-0 text-light"><?= esc($movie['title']) ?></p>
                <p class="small text-light-emphasis mb-0">Likes: <?= esc((string) ($movie['likes_count'] ?? 0)) ?></p>
            </div>
        <?php endforeach; ?>
        <?php foreach ($popularShows ?? [] as $show): ?>
            <div class="col">
                <a class="card" href="<?= base_url('show/' . esc($show['id'], 'url')); ?>">
                    <img src="https://image.tmdb.org/t/p/w300/<?= esc(ltrim((string) ($show['poster'] ?? ''), '/')); ?>" alt="<?= esc($show['title']); ?>" class="w-100" onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';">
                </a>
                <p class="small mt-1 mb-0 text-light"><?= esc($show['title']) ?></p>
                <p class="small text-light-emphasis mb-0">Likes: <?= esc((string) ($show['likes_count'] ?? 0)) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>
