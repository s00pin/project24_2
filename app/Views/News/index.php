<?php if (!empty($news) && is_array($news)): ?>
    <div class="d-grid gap-3">
        <?php foreach ($news as $news_item): ?>
            <article class="glass-card p-4">
                <h3><?= esc($news_item['title']) ?></h3>
                <p class="mb-3"><?= esc($news_item['body']) ?></p>
                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-sm btn-outline-light" href="/news/<?= esc($news_item['slug'], 'url') ?>">View</a>
                    <a class="btn btn-sm btn-outline-light" href="/news/edit/<?= esc($news_item['id']) ?>">Edit</a>
                    <form action="/news/delete/<?= esc($news_item['id']) ?>" method="post">
                        <?= csrf_field() ?>
                        <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('Delete this item?')">Delete</button>
                    </form>
                </div>
            </article>
        <?php endforeach ?>
    </div>
<?php else: ?>
    <div class="glass-card p-4">
        <h3>No News</h3>
        <p class="mb-0">Unable to find any news for you.</p>
    </div>
<?php endif ?>
