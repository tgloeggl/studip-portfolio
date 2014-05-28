<?php

$infobox_content[] = array(
    'kategorie' => _('Aktionen'),
    'eintrag'   => array(
        array(
            'icon' => 'icons/16/black/info.png',
            'text' => sprintf(_('%sNeue Aufgabe anlegen%s'), '<a href="'.  $controller->url_for('task/new/' . $portfolio->id) .'">', '</a>')
        )
    )
);

$infobox = array('picture' => $infobox_picture, 'content' => $infobox_content);    
?>
<div id="portfolio">
    <h1><?= htmlReady($portfolio['name']) ?></h1>

    <table style="border-collapse: collapse; width: 100%">
        <tr>
            <td style="width: 250px; vertical-align: top; vertical-align: top">
                <?= $this->render_partial('task/_tagcloud') ?>
            </td>

            <td style="vertical-align: top" id="tasks">
                <? foreach ($tasks_by_tag as $tag => $tasks) : ?>
                <?= $this->render_partial('task/_tasks', compact('tag', 'tasks')) ?>
                <? endforeach ?>

                <? if (!empty($tagless_tasks)) : ?>
                <?= $this->render_partial('task/_tasks', array(
                    'tasks' => $tagless_tasks,
                    'tag'   => _('Aufgaben ohne Tags')
                )) ?>
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