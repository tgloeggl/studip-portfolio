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
                <? if(isset($filter[0]) && $filter[0] != $tag) continue; ?>

                <table class="default zebra" data-tag="<?= htmlReady($tag) ?>">
                    <caption><?= htmlReady($tag) ?></caption>
                    <thead>
                        <tr>
                            <th><?= _('Aufgabe') ?></th>
                            <th colspan="2" style="width: 5%"><?= _('Arbeit') ?></th>
                            <th colspan="2" style="width: 5%"><?= _('Feedback') ?></th>
                            <th colspan="2" style="width: 5%"><?= _('Aktionen') ?></th>
                        </tr>
                    </thead>
                    <tbody>

                        <? foreach ($tasks as $task) : ?>
                            <?= $this->render_partial('task/_task', compact('task')); ?>
                        <? endforeach ?>
                    </tbody>
                </table>
                <? endforeach ?>

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