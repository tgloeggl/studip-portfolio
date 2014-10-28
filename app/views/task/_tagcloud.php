<h3 style="margin: 5px 0;">
    <?= _('Schlagworte:') ?>
</h3>


<div>
<? foreach ($tags as $name => $combo) : ?>
    <section>
        <a href="#" class="closed" data-tag="<?= htmlReady($name) ?>">
            <?= htmlReady($name) ?> <!-- (<?= sizeof($combo) ?>) -->
        </a>

        <? /*
        <div class="lvl2" <?= ($filter[0] == $name) ? '' : 'style="display: none"' ?>>
            <? foreach ($combo as $name2) : ?>
            <a href="#" data-tag="<?= htmlReady($name2) ?>">
                <?= htmlReady($name2) ?>
            </a>
            <? endforeach ?>
        </div>
        */ ?>
    </section>
<? endforeach; ?>
</div>
