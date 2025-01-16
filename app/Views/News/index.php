<h2><?= esc($title) ?></h2>
<?php if (!empty($news) && is_array($news)): ?>
    <?php foreach ($news as $news_item): ?>
        <div>
            <h3><?= esc($news_item['title']) ?></h3>
            <div class="main">
                <?= esc($news_item['body']) ?>
            </div>
            <p>
                <a href="/news/<?= esc($news_item['slug'], 'url') ?>">View article</a> | 
                <a href="/news/edit/<?= esc($news_item['id']) ?>">Edit</a> | 
                <form action="/news/delete/<?= esc($news_item['id']) ?>" method="post" style="display:inline">
                    <?= csrf_field() ?>
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this item?')">Delete</button>
                </form>
            </p>
        </div>
    <?php endforeach ?>
<?php else: ?>
    <h3>No News</h3>
    <p>Unable to find any news for you.</p>
<?php endif ?>
