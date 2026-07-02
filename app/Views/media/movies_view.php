<?php
$isLoggedIn = (bool) session()->get('logged_in');
$activeListIds = array_map('intval', $activeListIds ?? []);
?>
<section class="detail-shell detail-modern">
    <div class="detail-backdrop" style="background-image:url('https://image.tmdb.org/t/p/original/<?= esc(ltrim((string) ($media['background_image'] ?? ''), '/')) ?>')"></div>

    <div class="detail-content js-title-detail" data-media-type="media" data-media-id="<?= esc((string) $media['id']) ?>">
        <div class="detail-header-row">
            <img src="https://image.tmdb.org/t/p/w500/<?= esc(ltrim((string) ($media['poster_image'] ?? ''), '/')) ?>" alt="<?= esc($media['title']) ?> poster" class="detail-poster">

            <div class="detail-main">
                <p class="detail-type">Movie</p>
                <h2><?= esc($media['title']) ?></h2>
                <p class="text-light-emphasis detail-overview"><?= esc($media['overview']) ?></p>

                <div class="meta-list">
                    <div class="meta-pill"><strong>Release</strong><br><?= esc($media['release_date']) ?></div>
                    <div class="meta-pill"><strong>Language</strong><br><?= esc($media['original_language']) ?></div>
                    <div class="meta-pill"><strong>Genre</strong><br><?= esc($media['genre']) ?></div>
                    <div class="meta-pill"><strong>Likes</strong><br><span id="likes-count"><?= esc((string) $likesCount) ?></span></div>
                </div>

                <?php if ($isLoggedIn): ?>
                    <div class="detail-actions mt-3">
                        <button
                            class="btn list-action-btn like-btn <?= !empty($isLiked) ? 'is-active' : '' ?>"
                            type="button"
                            data-like-toggle="1"
                            data-media-type="media"
                            data-media-id="<?= esc((string) $media['id']) ?>"
                        >
                            <?= !empty($isLiked) ? 'Liked' : 'Like' ?>
                        </button>

                        <div class="list-dropdown" data-list-dropdown="1">
                            <button class="btn list-action-btn alt list-dropdown-toggle" type="button">Add to Lists</button>
                            <div class="list-dropdown-menu">
                                <?php foreach (($userLists ?? []) as $list): ?>
                                    <?php $lid = (int) $list['id']; ?>
                                    <button
                                        class="list-option <?= in_array($lid, $activeListIds, true) ? 'active' : '' ?>"
                                        type="button"
                                        data-list-option="1"
                                        data-list-id="<?= esc((string) $lid) ?>"
                                        data-media-type="media"
                                        data-media-id="<?= esc((string) $media['id']) ?>"
                                    >
                                        <span class="list-option-title"><?= esc($list['name']) ?></span>
                                        <span class="list-option-mark"><?= in_array($lid, $activeListIds, true) ? 'Added' : 'Add' ?></span>
                                    </button>
                                <?php endforeach; ?>
                                <a href="<?= base_url('dashboard') ?>" class="list-manage-link">Manage all lists</a>
                            </div>
                        </div>
                    </div>
                    <p class="action-feedback mt-2 mb-0" id="list-feedback"></p>
                <?php else: ?>
                    <div class="auth-gate">
                        <p class="mb-2">Sign in to like this title and add it to your custom lists.</p>
                        <a href="<?= base_url('login') ?>" class="btn btn-sm btn-outline-light">Login to unlock</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <section class="providers-wrap mt-3">
            <h3 class="providers-title">Where to watch in your region</h3>
            <div class="providers-status" id="providers-status">Waiting for consent to use cookies and location.</div>
            <div class="providers-grid" id="providers-grid"></div>
        </section>
    </div>
</section>
