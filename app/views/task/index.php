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
?>
<div id="portfolio">
    <h1 data-id="<?= $portfolio->id ?>">
        <span><?= htmlReady($portfolio['name']) ?></span>
        <? if ($portfolio->user_id == $user->id) : ?>
        <span class="edit_portfolio"></span>
        <? endif ?>
    </h1>

    <table style="border-collapse: collapse; width: 100%">
        <tr>
            <? if (!empty($tags)) : ?>
            <td class="tags">
                <?= $this->render_partial('task/_tagcloud') ?>
            </td>
            <? endif ?>

            <td id="tasks">
                <? if (empty($tasks_by_tag) && empty($tagless_tasks)) : ?>
                    <?= MessageBox::info(sprintf(_('Es sind bisher keine Aufgaben in diesem Portfolio vorhanden. %sLegen Sie eine neue Aufgabe an.%s'),
                        '<a href="'. $controller->url_for('task/new/' . $portfolio->id) .'">', '</a>')) ?>
                <? else : ?>
                    <? foreach ($tasks_by_tag as $tag => $tasks) : ?>
                    <?= $this->render_partial('task/_tasks', compact('tag', 'tasks')) ?>
                    <? endforeach ?>

                    <? if (!empty($tagless_tasks)) : ?>
                    <?= $this->render_partial('task/_tasks', array(
                        'tasks' => $tagless_tasks,
                        'tag'   => _('Aufgaben ohne Tags')
                    )) ?>
                    <? endif ?>
                <? endif ?>
            </td>
        </tr>
    </table>
</div>

<script>
    (function ($) {
        $(document).ready(function() {
            STUDIP.Portfolio.Homepage.init();
        });
    })(jQuery);
</script>