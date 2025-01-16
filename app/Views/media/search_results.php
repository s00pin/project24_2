
<div class="container">
    <div class="row row-cols-1 row-cols-md-4 g-4">
        <?php
        $items = [];

        if (!empty($media) && is_array($media)) {
            $items = array_merge($items, $media);
        }

        if (!empty($shows) && is_array($shows)) {
            $items = array_merge($items, $shows);
        }

        if (!empty($items)):
            foreach ($items as $item):
                $url = isset($item['poster_image']) ? "/media/{$item['id']}" : "/show/{$item['id']}";
                $poster = isset($item['poster_image']) ? $item['poster_image'] : $item['poster'];
                $title = $item['title'];
                ?>
                <div class="col">
                    <div class="card rounded-4 text-center h-100 w-100 shadow border-0">
                        <a href="<?= $url ?>">
                            <img src="https://image.tmdb.org/t/p/w300_and_h450_bestv2/<?= esc($poster, 'url') ?>" alt="<?= esc($title) ?>" class="card-img-top img-fluid rounded-4" style="object-fit: cover; height: 100%;">
                        </a>
                    </div>
                </div>
            <?php
            endforeach;
        else:
            ?>
            <div class="col-12">
                <h3>No Movies or Shows</h3>
                <p>Unable to find any movies or shows for you.</p>
            </div>
        <?php
        endif;
        ?>
    </div>
</div>