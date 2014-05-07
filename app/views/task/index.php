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
    <h1><?= $portfolio['name'] ?></h1>

    <table style="border-collapse: collapse; width: 100%">
        <tr>
            <td style="width: 250px; vertical-align: top;">
                <?= $this->render_partial('task/_tagcloud') ?>
            </td>

            <td>
                <table class="default zebra">
                <thead>
                    <tr>
                        <th><?= _('Aufgabe') ?></th>
                        <th colspan="2" style="width: 5%"><?= _('Arbeit') ?></th>
                        <th colspan="2" style="width: 5%"><?= _('Feedback') ?></th>
                        <th colspan="2" style="width: 5%"><?= _('Aktionen') ?></th>
                    </tr>
                </thead>
                <tbody>
                <? foreach ($portfolio->tasks as $task) : ?>
                    <? implode(', ', $task->tags->pluck('tag')); ?>
                    <tr>
                        <td>
                            <a href="<?= $controller->url_for('admin/task/edit/' . $portfolio->id .'/'. $task->id) ?>">
                                <?= $task->title ?>
                            </a>
                        </td>

                        <!-- Arbeit -->
                        <td style="text-align: right">
                            <?= Assets::img('icons/16/black/file-text.png', array(
                                'title' => _('Antworttext')
                            )) ?>
                        </td>
                        <td>
                            <?= Assets::img('icons/16/black/file-generic.png', array(
                                'title' => _('Hochgeladene Dateien')
                            )) ?>
                        </td>


                        <!-- Feedback -->
                        <td style="text-align: right">
                            <?= Assets::img('icons/16/black/file-text.png', array(
                                'title' => _('Antworttext')
                            )) ?>
                        </td>
                        <td>
                            <?= Assets::img('icons/16/black/file-generic.png', array(
                                'title' => _('Hochgeladene Dateien')
                            )) ?>
                        </td>

                        <!-- Aktionen -->
                        <td>
                            <a href="<?= $controller->url_for('admin/task/edit/' . $portfolio->id .'/'. $task->id) ?>">
                                <?= Assets::img('icons/16/blue/edit.png') ?>
                            </a>
                        </td>

                        <td>
                            <a href="<?= $controller->url_for('admin/task/delete/' . $portfolio->id .'/'. $task->id) ?>">
                                <?= Assets::img('icons/16/blue/trash.png') ?>
                            </a>
                        </td>                    
                    </tr>
                <? endforeach ?>
                </tbody>
            </table>
            </td>
        </tr>    
    </table>
</div>