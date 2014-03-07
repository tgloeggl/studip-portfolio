<?php

$infobox_content[] = array(
    'kategorie' => _('Aktionen'),
    'eintrag'   => array(
        array(
            'icon' => 'icons/16/black/info.png',
            'text' => sprintf(_('%sNeues Aufgabenset anlegen%s'), '<a href="'.  $controller->url_for('admin/set/new') .'">', '</a>')
        )
    )
);

$infobox = array('picture' => 'infobox/schedules.jpg', 'content' => $infobox_content);
?>

<div id="portfolio">
    <h1><?= _('Vorhandene Aufgabensets') ?></h1>
    <? if (empty($portfolios)) : ?>
        <?= MessageBox::info(sprintf(_('Es sind bisher keine Aufgabensets vorhanden. %sLegen Sie ein neues Aufgabenset an.%s'),
            '<a href="'. $controller->url_for('admin/set/new') .'">', '</a>')) ?>
    <? else : ?>
        <table class="default zebra">
            <thead>
                <tr>
                    <th><?= _('Name') ?></th>
                    <th><?= _('Studiengänge') ?></th>
                </tr>
            </thead>
            <tbody>
            <? foreach ($portfolios as $portfolio) : ?>
                <tr>
                    <td>
                        <a href="<?= $controller->url_for('admin/task/index/' . $portfolio['id']) ?>">
                            <?= $portfolio['name'] ?>
                        </a>
                    </td>
                    <td>
                        <ul style="margin: 0px; padding-left: 0px;">
                            <? foreach ($portfolio->combos as $combo) : ?>
                                <? foreach ($combo->study_combos as $study_combo) : ?>
                                <li>
                                    <?= $study_combo->studiengang->name ?> *
                                    <?= $study_combo->abschluss->name ?>
                                </li>
                                <? endforeach; ?>
                            <? endforeach; ?>
                        </ul>
                    </td>
                </tr>
            <? endforeach ?>
            </tbody>
        </table>
    <? endif ?>
</div>
