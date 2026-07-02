<section class="panel" style="max-width:780px;">
    <div class="section-head">
        <h3>Edit News</h3>
        <p>Update title or text and save changes.</p>
    </div>

    <?= \Config\Services::validation()->listErrors() ?>

    <form action="/news/update/<?= esc($news['id']) ?>" method="post" style="display:grid;gap:0.9rem;">
        <?= csrf_field() ?>

        <div>
            <label for="title" class="form-label">Title</label>
            <input class="form-control" type="text" id="title" name="title" value="<?= esc($news['title']) ?>">
        </div>

        <div>
            <label for="body" class="form-label">Body</label>
            <textarea class="form-control" id="body" name="body" rows="6"><?= esc($news['body']) ?></textarea>
        </div>

        <button class="btn btn-accent" type="submit" name="submit" style="justify-self:start;">Update News Item</button>
    </form>
</section>
