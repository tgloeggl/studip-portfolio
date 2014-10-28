<?php

// usersmy only delete portfolios they own
if ($portfolio->user_id == $user->id) :
    if (empty($tasks_by_tag) && empty($tagless_tasks)) :
        $entries[] = array(
            'icon' => 'icons/16/black/trash.png',
            'text' => sprintf(_('%sDieses Portfolio löschen%s'), '<a class="confirm" href="'.  $controller->url_for('portfolio/delete/' . $portfolio->id) .'">', '</a>')
        );
    else :
        $entries[] = array(
            'icon' => 'icons/16/grey/trash.png',
            'text' => sprintf(_('%sDieses Portfolio löschen%s'), '<span disabled>', '</span> '. tooltipIcon(
                _('Sie können dieses Portfolio nicht löschen, solange noch Aufgaben zugeordnet sind.')
            ))
        );
    endif;
endif;

$entries[] = array(
    'icon' => 'icons/16/black/info.png',
    'text' => sprintf(_('%sNeue Aufgabe anlegen%s'), '<a href="'.  $controller->url_for('task/new/' . $portfolio->id) .'">', '</a>')
);

$infobox_content[] = array(
    'kategorie' => _('Aktionen'),
    'eintrag'   => $entries
);

$infobox = array('picture' => $infobox_picture, 'content' => $infobox_content);

$path = array(
    array(
        'portfolio/index',
        _('Übersicht')
    ),
    $portfolio->name
);
?>

<h1 data-id="<?= $portfolio->id ?>">
    <span><?= htmlReady($portfolio['name']) ?></span>
    <? if ($portfolio->user_id == $user->id) : ?>
    <span class="edit_portfolio"></span>
    <? endif ?>
</h1>

<div class="set-description">
    <?= formatReady($portfolio['description']) ?>
</div>

<?= $this->render_partial('task/_task_list') ?>

<script>
    (function ($) {
        $(document).ready(function() {
            STUDIP.Portfolio.Homepage.init();
        });
    })(jQuery);
</script>