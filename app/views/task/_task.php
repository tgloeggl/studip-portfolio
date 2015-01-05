<?
foreach ($tags as $key => $val) {
    $tags[$key] = htmlReady($val);
}
?>
<tr data-tags='["<?= implode('", "', $tags) ?>"]' class="task">
    <td>
        <a href="<?= $controller->url_for('task/edit/' . ($portfolio->id ?: 0) .'/'. $task->id .'/'. $task_user->id) ?>">
            <?= htmlReady($task->title) ?>
        </a>
    </td>
    
    <!-- Tags -->
    <td>
        <?= implode(' &bullet; ', array_map(function($element) { return htmlReady($element); }, $task->tags->orderBy('tag')->pluck('tag'))) ?>
    </td>

    <? if ($show_creator) : ?>
    <td>
        <a href="<?= URLHelper::getLink('dispatch.php/profile?username=' . get_username($task_user->user_id)) ?>">
            <?= get_fullname($task_user->user_id) ?>
        </a>
    </td>
    <? endif ?>

    <!-- Arbeit -->
    <td style="vertical-align: top;" colspan="2">
        <? if ($task_user && (   $task_user->answer !== null && trim(strip_tags($task_user->answer))
                              || sizeof($task_user->files->findBy('type', 'answer')))) : ?>
            <?= Assets::img('icons/16/green/accept', array('title' => _('Aufgabe wurde bereits bearbeitet'))) ?>
        <? endif ?>
    </td>

    <!-- Feedback -->
    <td style="vertical-align: top;" colspan="2">
        <? if ($task_user && (   $task_user->feedback !== null && trim(strip_tags($task_user->feedback->feedback))
                              || sizeof($task_user->files->findBy('type', 'feedback')))) : ?>
            <?= Assets::img('icons/16/green/accept', array('title' => _('Feedback liegt vor'))) ?>
        <? endif ?>
    </td>

    <!-- Aktionen -->
    <td style="vertical-align: top;">
        <a href="<?= $controller->url_for('task/edit/' . ($portfolio->id ?: 0) .'/'. $task->id .'/'. $task_user->id) ?>" title="<?= _('Diese Aufgabe bearbeiten') ?>">
            <?= Assets::img('icons/16/blue/edit.png') ?>
        </a>
    </td>

    <td style="vertical-align: top;">
        <? if ($task->user_id != $user->id) : ?>
            <?= Assets::img('icons/16/grey/trash.png', array(
                'title' => _('Diese Aufgabe kann nicht gelöscht werden, da Sie nicht von Ihnen erstellt wurde.')
            )) ?>
        <? else : ?>
        <a href="<?= $controller->url_for('task/delete/' . $portfolio->id .'/'. $task->id) ?>"
           class="confirm" title="<?= _('Diese Aufgabe löschen') ?>" title="<?= _('Diese Aufgabe löschen') ?>">
            <?= Assets::img('icons/16/blue/trash.png') ?>
        </a>
        <? endif ?>
    </td>                    
</tr>