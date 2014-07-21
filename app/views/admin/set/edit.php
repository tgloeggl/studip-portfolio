<?php
$infobox_content[] = array(
    'kategorie' => _('Informationen'),
    'eintrag'   => array(
        array(
            'icon' => 'icons/16/black/info.png',
            'text' => _('Nutzer erhalten automatisch Zugriff auf dieses Aufgabenset, wenn sie in eine der ausgewählten Studiengangskombinationen studieren.')
        )
    )
);

$infobox = array('picture' => $infobox_picture, 'content' => $infobox_content);
?>

<?= $this->render_partial('admin/set/js_templates') ?>

<h1><?= htmlReady($portfolio->name) ?></h1>
<form method="post" action="<?= $controller->url_for('admin/set/update/' . $portfolio->id) ?>">
    <label>
        <span class="label"><?= _('Name des Sets:') ?></span><br>
        <input type="text" name="name" value="<?= htmlReady($portfolio->name) ?>"><br>
    </label>
    <br>
    <span class="label"><?= _('Freigeben für Studiengänge:') ?></span><br>

    <div class="studiengang_combos"></div>

    <?= Studip\LinkButton::create(_('Weitere Studiengangskombination hinzufügen'), 'javascript:STUDIP.Portfolio.Admin.addCombo();') ?>

    <div style="text-align: center">
        <div class="button-group">
            <?= Studip\Button::createAccept(_('Aufgabenset speichern')) ?>
            <?= Studip\LinkButton::createCancel(_('Abbrechen'), $controller->url_for('admin/set/index')) ?>
        </div>
    </div>
</form>

<script>
    jQuery(document).ready(function() {
        <? foreach ($portfolio->combos as $combo) :
            $ids = array();
            
            foreach ($combo->study_combos as $scomb) :
                $ids[] = $scomb->studiengang['id'] .'_'. $scomb->abschluss['id'];
            endforeach;
            ?>
            STUDIP.Portfolio.Admin.addCombo(['<?= implode("', '", $ids) ?>']);
        <? endforeach ?>
    });
</script>