<?
foreach ($tags as $key => $val) {
    $tags[$key] = htmlReady($val);
}
?>
<tr data-tags='["<?= implode('", "', $tags) ?>"]' class="task">
    <td>
        <a href="<?= $controller->url_for('task/edit/' . $portfolio->id .'/'. $task->id .'/'. $task_user->id) ?>">
            <?= htmlReady($task->title) ?>
        </a>
    </td>
    
    <!-- Tags -->
    <td>
        <?= implode(' &bullet; ', array_filter($task->tags->pluck('tag'), function($element) { return htmlReady($element); })) ?>
    </td>

    <? if ($show_creator) : ?>
    <td>
        <?= get_fullname($task_user->user_id) ?>
    </td>
    <? endif ?>

    <!-- Arbeit -->
    <td style="text-align: right; vertical-align: top;">
        <?= (!$task_user || $task_user->answer === null) ? '0' : strlen($task_user->answer) ?>
        <?= Assets::img('icons/16/black/file-text.png', array(
            'title' => _('Antworttext')
        )) ?>
    </td>
    <td style="vertical-align: top;">
        <?= $task_user ? sizeof($task_user->files->findBy('type', 'answer')) : 0 ?>
        <?= Assets::img('icons/16/black/file-generic.png', array(
            'title' => _('Hochgeladene Dateien')
        )) ?>
    </td>


    <!-- Feedback -->
    <td style="text-align: right; vertical-align: top;">
        <?= (!$task_user || $task_user->feedback === null) ? '0' : strlen($task_user->feedback->feedback) ?>
        <?= Assets::img('icons/16/black/file-text.png', array(
            'title' => _('Antworttext')
        )) ?>
    </td>
    <td style="vertical-align: top;">
        <?= $task_user ? sizeof($task_user->files->findBy('type', 'feedback')) : 0 ?>
        <?= Assets::img('icons/16/black/file-generic.png', array(
            'title' => _('Hochgeladene Dateien')
        )) ?>
    </td>

    <!-- Aktionen -->
    <td style="vertical-align: top;">
        <a href="<?= $controller->url_for('task/edit/' . $portfolio->id .'/'. $task->id) ?>">
            <?= Assets::img('icons/16/blue/edit.png') ?>
        </a>
    </td>

    <td style="vertical-align: top;">
        <? if ($task->user_id != $user->id) : ?>
            <?= Assets::img('icons/16/grey/trash.png', array(
                'title' => _('Diese Aufgabe kann nicht gelöscht werden, da es sich um eine Vorgabe handelt.')
            )) ?>
        <? else : ?>
        <a href="<?= $controller->url_for('task/delete/' . $portfolio->id .'/'. $task->id) ?>"
           class="confirm" title="<?= _('Diese Aufgabe löschen') ?>">
            <?= Assets::img('icons/16/blue/trash.png') ?>
        </a>
        <? endif ?>
    </td>                    
</tr>