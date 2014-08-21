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
        <a href="<?= URLHelper::getLink('dispatch.php/profile?username=' . get_username($task_user->user_id)) ?>">
            <?= get_fullname($task_user->user_id) ?>
        </a>
    </td>
    <? endif ?>

    <!-- Arbeit -->
    <td style="text-align: right; vertical-align: top;">
        <?= (!$task_user || $task_user->answer === null || !trim(strip_tags($task_user->answer)))
            ? '0' : sizeof(explode(' ', trim(strip_tags($task_user->answer)))) ?>
        <?= Assets::img('icons/16/black/file-text.png', array(
            'title' => _('Anzahl der eingegebenen Wörter beim Antworttext')
        )) ?>
    </td>
    <td style="vertical-align: top;">
        <?= $task_user ? sizeof($task_user->files->findBy('type', 'answer')) : 0 ?>
        <?= Assets::img('icons/16/black/file-generic.png', array(
            'title' => _('Anzahl der als Antwort hochgeladenen Dateien')
        )) ?>
    </td>


    <!-- Feedback -->
    <td style="text-align: right; vertical-align: top;">
        <?= (!$task_user || $task_user->feedback === null || !trim(strip_tags($task_user->feedback->feedback)))
            ? '0' : sizeof(explode(' ', trim(strip_tags($task_user->feedback->feedback)))) ?>
        <?= Assets::img('icons/16/black/file-text.png', array(
            'title' => _('Anzahl der eingegebenen Wörter beim Feedback')
        )) ?>
    </td>
    <td style="vertical-align: top;">
        <?= $task_user ? sizeof($task_user->files->findBy('type', 'feedback')) : 0 ?>
        <?= Assets::img('icons/16/black/file-generic.png', array(
            'title' => _('Anzahl der als Feedback hochgeladenen Dateien')
        )) ?>
    </td>

    <!-- Aktionen -->
    <td style="vertical-align: top;">
        <a href="<?= $controller->url_for('task/edit/' . $portfolio->id .'/'. $task->id) ?>" title="<?= _('Diese Aufgabe bearbeiten') ?>">
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
           class="confirm" title="<?= _('Diese Aufgabe löschen') ?>" title="<?= _('Diese Aufgabe löschen') ?>">
            <?= Assets::img('icons/16/blue/trash.png') ?>
        </a>
        <? endif ?>
    </td>                    
</tr>