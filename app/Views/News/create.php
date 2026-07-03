<section class="section-frame" style="max-width: 820px;">
    <header class="section-head">
        <div>
            <p class="section-kicker">Newsroom</p>
            <h3>Create news post</h3>
        </div>
        <p>Share an update with a clear title and useful context.</p>
    </header>

    <?= session()->getFlashdata('error') ?>
    <?= validation_list_errors() ?>

    <form action="/news" method="post" class="form-grid">
        <?= csrf_field() ?>

        <div>
            <label for="title" class="form-label">Title</label>
            <input class="form-control" type="text" id="title" name="title" value="<?= set_value('title') ?>">
        </div>

        <div>
            <label for="body" class="form-label">Text</label>
            <textarea class="form-control" id="body" name="body" rows="8"><?= set_value('body') ?></textarea>
        </div>

        <button class="btn btn-primary" type="submit" name="submit">Create news item</button>
    </form>
</section>