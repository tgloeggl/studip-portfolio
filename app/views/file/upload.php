<!-- multi file upload -->
<script>
    $(document).ready(function() {
        STUDIP.Portfolio.File.maxFilesize = <?= $max = $GLOBALS['UPLOAD_TYPES']['default']["file_sizes"][$user->perms] ?>;
    });
</script>

<!-- Workaround for broken jquery.widget & jquery.metadata. data-url at #fileupload gets wrongly interpreted as regexp and fails -->
<input type="hidden" name="upload_url" value="<?= $controller->url_for('file/post_files/' . $task->id .'/'. $type) ?>">

<div style="position: relative; display: inline-block;">
    <a class="button" style="overflow: hidden; position: relative;">
        <?= _('Datei(en) hinzufügen') ?>
        <input id="fileupload" type="file" multiple name="file"
            data-sequential-uploads="true"
            style="opacity: 0; position: absolute; left: -2px; top: -2px; height: 105%; cursor: pointer;">
    </a>
</div>

<?= \Studip\LinkButton::create(_('Datei(en) hochladen'), "javascript:STUDIP.Portfolio.File.upload()",
        array('id' => 'upload_button', 'class' => 'disabled')) ?>

<b><?= _('Maximal erlaubte Größe pro Datei') ?>: <?= round($max / 1024 / 1024, 2) ?> MB</b><br>

<table class="default zebra">
    <tbody id="files_to_upload">
        
    </tbody>
</table>