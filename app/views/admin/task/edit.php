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

<div id="portfolio">
    <h1><?= $task->title ?></h1>
    <form method="post" action="<?= $controller->url_for('admin/task/add') ?>">
        <label>
            <span><?= _('Titel:') ?></span><br>
            <input type="text" name="title" required="required" value="<?= htmlReady($task->title) ?>"><br>
        </label>
        
        <label>
            <span><?= _('Aufgabenbeschreibung:') ?></span><br>
            <textarea name="content" required="required"><?= htmlReady($task->content) ?></textarea><br>
        </label>
        
        <label>
            <span><?= _('Enthalten in Aufgabensets:') ?></span><br>
            <select name="sets" multiple class="chosen" data-placeholder="<?= _('Wählen Sie Zuordnungen aus') ?>">
                <? foreach ($portfolios as $portfolio) : ?>
                    <option value="<?= $portfolio->id ?>" <?= in_array($portfolio->id, $task->tasksets->pluck('id'))  !== false ? 'selected="selected"' : '' ?>><?= $portfolio->name ?></option>
                <? endforeach ?>
            </select>
        </label>
        
        <label>
            <span><?= _('Tags:') ?></span><br>
            <select name="tags" multiple data-placeholder="<?= _('Fügen Sie Tags hinzu') ?>">
                <? foreach ($tags as $tag) : ?>
                <option <?= in_array($tag->tag, $task->tags->pluck('tag'))  !== false ? 'selected="selected"' : '' ?>><?= $tag->tag ?></option>
                <? endforeach ?>
            </select>
        </label>        

        <label>
            <input type="checkbox" name="allow_text" <?= $task->allow_text ? 'checked="checked"' : '' ?>>
            <?= _('Texteingabe erlauben?') ?>
        </label>
        
        <label>
            <input type="checkbox" name="allow_files" <?= $task->allow_files ? 'checked="checked"' : '' ?>>
            <?= _('Dateiupload erlauben?') ?>
        </label>
            
        <div style="text-align: center">
            <div class="button-group">
                <?= Studip\Button::createAccept(_('Aufgabe speichern')) ?>
                <?= Studip\LinkButton::createCancel(_('Abbrechen'), $controller->url_for('admin/task/index/' . $portfolio_id)) ?>
            </div>
        </div>
    </form>
</div>
<script>
    jQuery(document).ready(function() {
        jQuery('select[name=sets]').chosen();
        jQuery('select[name=tags]').chosen({
            create_option: true,
            skip_no_results: true,
            create_option_text: 'Tag erstellen'.toLocaleString()
        });
    });
</script>