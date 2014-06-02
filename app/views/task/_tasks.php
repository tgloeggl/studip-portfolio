<table class="default zebra tasks" data-tag="<?= htmlReady($tag) ?>">
    <colgroup>
        <col style="width: 44%">
        <col style="width: 50%">
        <? if (!empty($task_users)) : ?>
        <col style="width: 10%">
        <? endif ?>
        <col span="2" style="width: 2%">
        <col span="2" style="width: 2%">
        <col span="2" style="width: 2%">
    </colgroup>
    <caption><?= htmlReady($tag) ?></caption>
    <thead>
        <tr>
            <th><?= _('Aufgabe') ?></th>
            <th><?= _('Tags') ?></th>
            <? if (!empty($task_users)) : ?>
            <th><?= _('Erstellt von') ?></th>
            <? endif ?>
            <th colspan="2"><?= _('Arbeit') ?></th>
            <th colspan="2"><?= _('Feedback') ?></th>
            <th colspan="2"><?= _('Aktionen') ?></th>
        </tr>
    </thead>
    <tbody>

        <? if (!empty($task_users)) :
            foreach ($task_users as $task_user) :
                $tags = $task_user->task->tags->pluck('tag');
                $task = $task_user->task;
                $show_creator = true;
                ?>
                <?= $this->render_partial('task/_task', compact('task', 'tags', 'task_user', 'show_creator')); ?>
            <? endforeach ?>
        <? else : ?>
            <? foreach ($tasks as $task) :
                $tags = $task->tags->pluck('tag');
                $task_user = $task->task_users->findOneBy('user_id', $user->id);
                ?>

                <?= $this->render_partial('task/_task', compact('task', 'tags', 'task_user')); ?>
            <? endforeach ?>
        <? endif ?>
    </tbody>
</table>