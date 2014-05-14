<div id="tagcloud">
<? foreach ($tags as $name => $combo) : ?>
    <!-- <a href="<?= $controller->url_for('task/index/' . $portfolio['id'] .'?tag[]=' . $name) ?>" class="closed"> -->
    <section>
        <a href="#" class="closed" data-tag="<?= htmlReady($name) ?>">
            <?= htmlReady($name) ?> (<?= sizeof($combo) ?>)
        </a>

        <div class="lvl2" <?= ($filter[0] == $name) ? '' : 'style="display: none"' ?>>
            <? foreach ($combo as $name2) : ?>
            <!-- <a href="<?= $controller->url_for('task/index/' . $portfolio['id'] .'?tag[]=' . $name .'&tag[]=' . $name2) ?>"> -->
            <a href="#" data-tag="<?= htmlReady($name2) ?>">
                <?= htmlReady($name2) ?>
            </a>
            <? endforeach ?>
        </div>
    </section>
<? endforeach; ?>
</div>
