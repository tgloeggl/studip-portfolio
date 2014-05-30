<!-- files already there -->
<table class="default zebra">
    <thead>
        <tr>
            <th style="width:60%"><?= _('Datei') ?></th>
            <th style="width:10%"><?= _('Größe') ?></th>
            <th style="width:20%"><?= _('Datum') ?></th>
            <? if ($edit) : ?>
            <th style="width:10%"><?= _('Aktionen') ?></th>
            <? endif ?>
        </tr>
    </thead>
    <tbody <?= $edit ? 'id="uploaded_files"' : '' ?>>
<? if(!empty($files)) foreach($files as $file) : ?>
    <tr data-fileid="<?= $file->id ?>">
        <td>
            <a href="<?= GetDownloadLink($file->document->id, $file->document->name)?>" target="_blank">
                <?= $file->document->name ?>
            </a>
        </td>
        <td><?= round((($file->document->filesize / 1024) * 100) / 100, 2) ?> kb</td>
        <td><?= strftime($timeformat, $file->document->mkdate) ?></td>
        
        <? if ($edit) : ?>
        <td>
            <? if ($user->id == $file->document->user_id) : ?>
            <a href="javascript:STUDIP.Portfolio.File.removeFile('<?= $seminar_id ?>', '<?= $file->id ?>')">
                <?= Assets::img('icons/16/blue/trash.png') ?>
            </a>
            <? endif ?>
        </td>
        <? endif ?>
    </tr>
<? endforeach ?>
    </tbody>
</table>
<br>

<? if ($edit) : ?>
    <?= $this->render_partial('file/upload', compact('type')) ?>
<? endif ?>
