<?php
$infobox_content[] = array(
    'kategorie' => _('Informationen'),
    'eintrag'   => array(
        array(
            'icon' => 'icons/16/black/info.png',
            'text' => '<a href="'. $controller->url_for('task/index/' . $portfolio->id) .'">'. _('Zurück zur Aufgabenübersicht') .'</a>'
        )
    )
);

$infobox = array('picture' => $infobox_picture, 'content' => $infobox_content);

$path = array(
    array(
        'portfolio/index',
        _('Übersicht')
    ),
    array(
        'task/index/' . $portfolio->id,
        $portfolio->name
    ),
    _('Neue Aufgabe anlegen')
);
?>

<?= $this->render_partial('task/js_templates.php') ?>

<form method="post" action="<?= $controller->url_for('task/add/' . $portfolio->id) ?>" class="warn-on-unload">
    <h1>
        <label>
            <input type="text" name="title" required="required" placeholder="<?= _('Titel der neuen Aufgabe') ?>"><br>
        </label>
    </h1>

    <label>
        <span><?= _('Aufgabe:') ?></span><br>
        <textarea name="content" required="required" class="add_toolbar"></textarea><br>
    </label>

    <label>
        <span><?= _('Zugeordnete Portfolios:') ?></span><br>
        <select id="sets" name="sets[]" multiple class="chosen" data-placeholder="<?= _('Wählen Sie Zuordnungen aus') ?>">
            <? foreach ($portfolios as $l_portfolio) : ?>
                <option value="<?= $l_portfolio->id ?>" <?= $l_portfolio->id == $portfolio->id ? 'selected="selected"' : '' ?>><?= htmlReady($l_portfolio->name) ?></option>
            <? endforeach ?>
        </select>
        <?= tooltipIcon('Neue Portfolios können Sie auf der Übersichtsseite erstellen.') ?>
    </label>

    <label>
        <span><?= _('Schlagworte:') ?></span><br>
        <select id="tags" name="tags[]" multiple data-placeholder="<?= _('Fügen Sie Schlagworte hinzu') ?>">
            <? foreach ($tags as $tag) : ?>
            <option><?= htmlReady($tag->tag) ?></option>
            <? endforeach ?>
        </select>
    </label>

    <div style="text-align: center">
        <div class="button-group">
            <?= Studip\Button::createAccept(_('Aufgabe erstellen')) ?>
            <?= Studip\LinkButton::createCancel(_('Abbrechen'), $controller->url_for('task/index/' . $portfolio->id)) ?>
        </div>
    </div>
</form>

<script>
    jQuery(document).ready(function() {
        jQuery('#sets').chosen({
            create_option_text: 'Portfolio erstellen'.toLocaleString()
        });
        jQuery('#tags').chosen({
            create_option: true,
            persistent_create_option: true,
            skip_no_results: true,
            create_option_text: 'Schlagwort erstellen'.toLocaleString()
        });
    });
</script>