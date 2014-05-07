<div id="tagcloud">
<? foreach ($tags as $name => $combo) : ?>
    <a href="<?= $controller->url_for('task/index/' . $portfolio['id'] .'?tag=' . $name) ?>" class="<?= $filter == $name ? 'open' : 'closed' ?>">
        <?= htmlReady($name) ?> (<?= sizeof($combo) ?>)
    </a>
    <? if ($filter == $name) : ?>
    <div class="lvl2">
        <? foreach ($combo as $name) : ?>
        <a href="#" class="closed">
            <?= htmlReady($name) ?>
        </a>
        <? endforeach ?>
    </div>
    <? endif ?>
<? endforeach; ?>
</div>