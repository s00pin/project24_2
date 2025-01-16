
<div class="container ">
    <div class="row row-cols-1 row-cols-md-4 g-4">
        <?php if (!empty($show) && is_array($show)): ?>
            <?php foreach ($show as $show_item): ?>
                <div class="col">
                    <div class="card rounded-4 text-center h-100 w-100 shadow border-0">
                        <a href="/show/<?= esc($show_item['id'], 'url') ?>">
                            <img src="https://image.tmdb.org/t/p/w300_and_h450_bestv2/<?= esc($show_item['poster']) ?>" alt="<?= esc($show_item['title']) ?>" class="card-img-top img-fluid rounded-4" style="object-fit: cover; height: 100%;">
                        </a>
                    </div>
                    
                </div>
            <?php endforeach ?>
    </div>
</div>

<?php else: ?>

    <h3>No Movies</h3>

    <p>Unable to find any movies for you.</p>

<?php endif ?>