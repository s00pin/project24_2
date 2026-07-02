<section class="glass-card p-4">
    <?= session()->getFlashdata('error') ?>
    <?= validation_list_errors() ?>

    <form action="/news" method="post" class="d-grid gap-3">
        <?= csrf_field() ?>

        <div>
            <label for="title" class="form-label">Title</label>
            <input class="form-control" type="text" id="title" name="title" value="<?= set_value('title') ?>">
        </div>

        <div>
            <label for="body" class="form-label">Text</label>
            <textarea class="form-control" id="body" name="body" rows="5"><?= set_value('body') ?></textarea>
        </div>

        <button class="btn btn-accent" type="submit" name="submit">Create News Item</button>
    </form>
</section>
