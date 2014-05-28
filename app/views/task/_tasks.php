<table class="default zebra" data-tag="<?= htmlReady($tag) ?>">
    <colgroup>
        <col style="width: 44%">
        <col style="width: 50%">
        <col span="2" style="width: 2%">
        <col span="2" style="width: 2%">
        <col span="2" style="width: 2%">
    </colgroup>
    <caption><?= htmlReady($tag) ?></caption>
    <thead>
        <tr>
            <th><?= _('Aufgabe') ?></th>
            <th><?= _('Tags') ?></th>
            <th colspan="2"><?= _('Arbeit') ?></th>
            <th colspan="2"><?= _('Feedback') ?></th>
            <th colspan="2"><?= _('Aktionen') ?></th>
        </tr>
    </thead>
    <tbody>

        <? foreach ($tasks as $task) : ?>
            <?= $this->render_partial('task/_task', compact('task')); ?>
        <? endforeach ?>
    </tbody>
</table>