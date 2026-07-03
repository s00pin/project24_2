<section class="section-frame" style="max-width: 820px;">
    <header class="section-head">
        <div>
            <p class="section-kicker">Newsroom</p>
            <h3>Edit news post</h3>
        </div>
        <p>Update title or body and save your changes.</p>
    </header>

    <?= \Config\Services::validation()->listErrors() ?>

    <form action="/news/update/<?= esc($news['id']) ?>" method="post" class="form-grid">
        <?= csrf_field() ?>

        <div>
            <label for="title" class="form-label">Title</label>
            <input class="form-control" type="text" id="title" name="title" value="<?= esc($news['title']) ?>">
        </div>

        <div>
            <label for="body" class="form-label">Body</label>
            <textarea class="form-control" id="body" name="body" rows="8"><?= esc($news['body']) ?></textarea>
        </div>

        <button class="btn btn-primary" type="submit" name="submit">Update news item</button>
    </form>
</section>