<section class="glass-card p-4">
    <?= \Config\Services::validation()->listErrors() ?>

    <form action="/news/update/<?= esc($news['id']) ?>" method="post" class="d-grid gap-3">
        <?= csrf_field() ?>

        <div>
            <label for="title" class="form-label">Title</label>
            <input class="form-control" type="text" id="title" name="title" value="<?= esc($news['title']) ?>">
        </div>

        <div>
            <label for="body" class="form-label">Body</label>
            <textarea class="form-control" id="body" name="body" rows="5"><?= esc($news['body']) ?></textarea>
        </div>

        <button class="btn btn-accent" type="submit" name="submit">Update News Item</button>
    </form>
</section>
