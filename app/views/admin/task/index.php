<?php

$infobox_content[] = array(
    'kategorie' => _('Aktionen'),
    'eintrag'   => array(
        array(
            'icon' => 'icons/16/black/info.png',
            'text' => sprintf(_('%sNeue Aufgaben anlegen%s'), '<a href="'.  $controller->url_for('admin/task/new/' . $portfolio->id) .'">', '</a>')
        )
    )
);

$infobox = array('picture' => 'infobox/schedules.jpg', 'content' => $infobox_content);
?>


<div id="portfolio">
    <h1><?= sprintf(_('Aufgaben im Set "%s"'), $portfolio->name) ?></h1>
    <? if (empty($tasks)) : ?>
        <?= MessageBox::info(sprintf(_('Es sind bisher keine Aufgaben in diesem Aufgabensets vorhanden. %sLegen Sie eine neue Aufgabe an.%s'),
            '<a href="'. $controller->url_for('admin/task/new/' . $portfolio->id) .'">', '</a>')) ?>
    <? else : ?>
        <table class="default zebra">
            <thead>
                <tr>
                    <th><?= _('Name') ?></th>
                    <th><?= _('Enthalten in Sets') ?></th>
                </tr>
            </thead>
            <tbody>
            <? foreach ($tasks as $task) : ?>
                <tr>
                    <td>
                        <a href="<?= $controller->url_for('admin/task/edit/' . $task['id']) ?>">
                            <?= $task['name'] ?>
                        </a>
                    </td>
                    <td>
                        <? /*
                        <ul style="margin: 0px; padding-left: 0px;">
                            <? foreach ($portfolio->studiengang_combos as $st) : ?>
                            <li>
                                <?= implode(', ', array_map(function($data) { return $data['name']; }, $st->studiengaenge->toArray())) ?>
                            </li>
                            <? endforeach; ?>
                        </ul> */ ?>
                    </td>
                </tr>
            <? endforeach ?>
            </tbody>
        </table>
    <? endif ?>
</div>
