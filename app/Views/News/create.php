<section class="panel" style="max-width:780px;">
    <div class="section-head">
        <h3>Create News</h3>
        <p>Add a new entry for the news section.</p>
    </div>

    <?= session()->getFlashdata('error') ?>
    <?= validation_list_errors() ?>

    <form action="/news" method="post" style="display:grid;gap:0.9rem;">
        <?= csrf_field() ?>

        <div>
            <label for="title" class="form-label">Title</label>
            <input class="form-control" type="text" id="title" name="title" value="<?= set_value('title') ?>">
        </div>

        <div>
            <label for="body" class="form-label">Text</label>
            <textarea class="form-control" id="body" name="body" rows="6"><?= set_value('body') ?></textarea>
        </div>

        <button class="btn btn-accent" type="submit" name="submit" style="justify-self:start;">Create News Item</button>
    </form>
</section>
