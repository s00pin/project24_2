<?php if (!empty($news) && is_array($news)): ?>
    <section class="news-grid">
        <?php foreach ($news as $newsItem): ?>
            <article class="news-card">
                <h3><?= esc($newsItem['title']) ?></h3>
                <p class="news-body"><?= esc($newsItem['body']) ?></p>
                <div class="news-actions">
                    <a class="btn btn-ghost btn-sm" href="/news/<?= esc($newsItem['slug'], 'url') ?>">View</a>
                    <a class="btn btn-ghost btn-sm" href="/news/edit/<?= esc($newsItem['id']) ?>">Edit</a>
                    <form action="/news/delete/<?= esc($newsItem['id']) ?>" method="post" class="inline-form">
                        <?= csrf_field() ?>
                        <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm('Delete this item?')">Delete</button>
                    </form>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
<?php else: ?>
    <section class="empty-state">
        <h3>No news yet</h3>
        <p>Create a news post to start the archive.</p>
    </section>
<?php endif; ?>