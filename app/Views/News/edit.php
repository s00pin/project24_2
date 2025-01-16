<h2><?= esc($title) ?></h2>

<?= \Config\Services::validation()->listErrors() ?>

<form action="/news/update/<?= esc($news['id']) ?>" method="post">
    <?= csrf_field() ?>

    <label for="title">Title</label>
    <input type="input" name="title" value="<?= esc($news['title']) ?>" /><br />

    <label for="body">Body</label>
    <textarea name="body"><?= esc($news['body']) ?></textarea><br />

    <input type="submit" name="submit" value="Update news item" />
</form>
