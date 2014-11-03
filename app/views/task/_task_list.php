<table style="border-collapse: collapse; width: 100%">
    <tr>
        <? /* if (!empty($tags)) : ?>
        <td class="tags">
            <?= $this->render_partial('task/_tagcloud') ?>
        </td>
        <? endif */ ?>

        <td id="tasks">
            <? if ($portfolio['description']) : ?>
            <div class="set-description">
                <?= formatReady($portfolio['description']) ?>
                <br><br>
            </div>
            <? endif ?>

            <? if (empty($tasks_by_tag) && empty($tagless_tasks)) : ?>
                <?= MessageBox::info(sprintf(_('Es sind bisher keine Aufgaben in diesem Portfolio vorhanden. %sLegen Sie eine neue Aufgabe an.%s'),
                    '<a href="'. $controller->url_for('task/new/' . $portfolio->id) .'">', '</a>')) ?>
            <? else : ?>
                <? foreach ($tasks_by_tag as $tag => $tasks) : ?>
                <?= $this->render_partial('task/_tasks', compact('tag', 'tasks')) ?>
                <? endforeach ?>

                <? if (!empty($tagless_tasks)) : ?>
                <?= $this->render_partial('task/_tasks', array(
                    'tasks' => $tagless_tasks,
                    'tag'   => _('Aufgaben ohne Schlagworte')
                )) ?>
                <? endif ?>
            <? endif ?>
        </td>
    </tr>
</table>
