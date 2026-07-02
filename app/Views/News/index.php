<?php if (!empty($news) && is_array($news)): ?>
    <section class="news-stack">
        <?php foreach ($news as $news_item): ?>
            <article class="news-card">
                <h3><?= esc($news_item['title']) ?></h3>
                <p class="news-meta"><?= esc($news_item['body']) ?></p>
                <div class="hero-actions" style="margin-top:0.8rem;">
                    <a class="btn btn-outline-light btn-sm" href="/news/<?= esc($news_item['slug'], 'url') ?>">View</a>
                    <a class="btn btn-outline-light btn-sm" href="/news/edit/<?= esc($news_item['id']) ?>">Edit</a>
                    <form action="/news/delete/<?= esc($news_item['id']) ?>" method="post" class="inline-form">
                        <?= csrf_field() ?>
                        <button class="btn btn-outline-danger btn-sm" type="submit" onclick="return confirm('Delete this item?')">Delete</button>
                    </form>
                </div>
            </article>
        <?php endforeach ?>
    </section>
<?php else: ?>
    <section class="empty-card">
        <h3>No News</h3>
        <p>No news entries are available right now.</p>
    </section>
<?php endif ?>
