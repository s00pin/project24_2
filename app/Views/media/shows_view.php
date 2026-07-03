<?php
$isLoggedIn = (bool) session()->get('logged_in');
$activeListIds = array_map('intval', $activeListIds ?? []);

$posterUrl = static function (?string $path, string $size = 'w500'): string {
    $normalized = ltrim((string) ($path ?? ''), '/');
    return $normalized !== ''
        ? 'https://image.tmdb.org/t/p/' . $size . '/' . $normalized
        : base_url('assets/image/logo.png');
};

$backdropUrl = $posterUrl($show['background'] ?? null, 'original');
$posterImage = $posterUrl($show['poster'] ?? null, 'w500');
?>

<section class="detail-hero js-title-detail" data-media-type="show" data-media-id="<?= esc((string) $show['id']) ?>" style="--detail-backdrop:url('<?= esc($backdropUrl) ?>');">
    <div class="detail-hero-scrim">
        <div class="detail-hero-inner">
            <img class="detail-poster" src="<?= esc($posterImage) ?>" alt="<?= esc($show['title']) ?> poster" loading="lazy" onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';">

            <div class="detail-copy">
                <p class="detail-type">Show</p>
                <h2><?= esc($show['title']) ?></h2>
                <p class="detail-summary"><?= esc($show['overview']) ?></p>

                <ul class="detail-meta" aria-label="Show details">
                    <li><span>Run</span><strong><?= esc($show['begin_date'] ?: 'Unknown') ?> - <?= esc($show['end_date'] ?: 'Ongoing') ?></strong></li>
                    <li><span>Format</span><strong><?= esc((string) ($show['seasons'] ?? '0')) ?> seasons, <?= esc((string) ($show['episodes'] ?? '0')) ?> episodes</strong></li>
                    <li><span>Runtime</span><strong><?= esc((string) ($show['runtime'] ?? '0')) ?> min</strong></li>
                    <li><span>Language</span><strong><?= esc($show['language'] ?: 'Unknown') ?></strong></li>
                    <li><span>Genre</span><strong><?= esc($show['genre'] ?: 'Unknown') ?></strong></li>
                    <li><span>Likes</span><strong id="likes-count"><?= esc((string) $likesCount) ?></strong></li>
                </ul>

                <?php if ($isLoggedIn): ?>
                    <div class="title-actions">
                        <button
                            class="action-pill like-btn <?= !empty($isLiked) ? 'is-active' : '' ?>"
                            type="button"
                            data-like-toggle="1"
                            data-media-type="show"
                            data-media-id="<?= esc((string) $show['id']) ?>"
                        >
                            <?= !empty($isLiked) ? 'Liked' : 'Like' ?>
                        </button>

                        <div class="list-dropdown" data-list-dropdown="1">
                            <button class="action-pill list-dropdown-toggle" type="button">Add to lists</button>
                            <div class="list-dropdown-menu">
                                <?php foreach (($userLists ?? []) as $list): ?>
                                    <?php $listId = (int) $list['id']; ?>
                                    <button
                                        class="list-option <?= in_array($listId, $activeListIds, true) ? 'active' : '' ?>"
                                        type="button"
                                        data-list-option="1"
                                        data-list-id="<?= esc((string) $listId) ?>"
                                        data-media-type="show"
                                        data-media-id="<?= esc((string) $show['id']) ?>"
                                    >
                                        <span><?= esc($list['name']) ?></span>
                                        <span class="list-option-mark"><?= in_array($listId, $activeListIds, true) ? 'Added' : 'Add' ?></span>
                                    </button>
                                <?php endforeach; ?>
                                <a href="<?= base_url('dashboard') ?>" class="list-manage-link">Manage lists</a>
                            </div>
                        </div>
                    </div>
                    <p class="action-feedback" id="list-feedback"></p>
                <?php else: ?>
                    <div class="auth-inline">
                        <p>Sign in to like this show or save it to your lists.</p>
                        <a href="<?= base_url('login') ?>" class="btn btn-ghost btn-sm">Login</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($moreLikeThis ?? [])): ?>
    <section class="detail-related">
        <header class="content-head">
            <div>
                <p class="section-kicker">More like this</p>
                <h3>If this show worked for you</h3>
            </div>
            <p class="section-note">Similar series from your catalog.</p>
        </header>

        <div class="poster-flow related-strip" role="list">
            <?php foreach ($moreLikeThis as $series): ?>
                <a class="media-tile" role="listitem" href="<?= base_url('show/' . esc((string) $series['id'], 'url')); ?>">
                    <img
                        src="<?= esc($posterUrl($series['poster'] ?? null, 'w500')) ?>"
                        alt="<?= esc($series['title'] ?? 'Related show') ?>"
                        loading="lazy"
                        onerror="this.onerror=null;this.src='<?= esc(base_url('assets/image/logo.png')) ?>';"
                    >
                    <span class="media-title"><?= esc($series['title'] ?? 'Untitled show') ?></span>
                    <span class="media-meta">Show<?= !empty($series['year']) ? ' - ' . esc((string) $series['year']) : '' ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<section class="detail-extra">
    <h3>Where to watch in your region</h3>
    <div class="provider-status" id="providers-status">Choose cookie settings to load provider availability.</div>
    <div class="provider-grid" id="providers-grid"></div>
</section>
