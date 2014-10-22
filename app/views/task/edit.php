<?php
$infobox_content[] = array(
    'kategorie' => _('Aktionen'),
    'eintrag'   => array(
        array(
            'icon' => 'icons/16/black/link-intern.png',
            'text' => '<a href="'. $controller->url_for('task/index/' . $portfolio->id) .'">'. _('Zurück zur Aufgabenübersicht') .'</a>'
        )
    )
);

$infobox = array('picture' => $infobox_picture, 'content' => $infobox_content);

$path = array();
$path[] = array(
    'portfolio/index',
    _('Übersicht')
);

if ($portfolio->name) :
    $path[] = array(
        'task/index/' . $portfolio->id,
        $portfolio->name
    );
endif;

$path[] = $task->title;
?>

<?= $this->render_partial('task/js_templates.php') ?>
<?= $this->render_partial('file/js_templates.php') ?>

<form method="post" action="<?= $controller->url_for('task/update/' . $portfolio->id .'/'. $task_user->id) ?>" 
      class="warn-on-unload" id="edit-task-form" data-task-user-id="<?= $task_user->id ?>">
    <!-- Task -->
    <h1>
        <label>
            <? if ($perms['edit_task']): ?>
            <input type="text" name="title" required="required" value="<?= htmlReady($task->title) ?>"><br>
            <? else : ?>
            <?= htmlReady($task->title) ?>
            <? endif ?>
        </label>
    </h1>

    <? if ($task_user->user_id != $user->id): ?>
        <? foreach($task_user->perms as $id => $perm) :
            if ($perm->user_id == $user->id) :
                $my_perm = $perm;
            endif;
        endforeach; ?>

    <span>
        <?= sprintf(_('Besitzer: %s, ihr Status %s.'),
                '<a href="'. URLHelper::getLink('dispatch.php/profile?username='
                        . get_username($task_user->user_id)) .'">'
                    . get_fullname($task_user->user_id) . '</a>',
                $permissions[$my_perm->role]
        ) ?>
    </span>
    <? endif ?>

    

    <label <?= ($perms['edit_task'] ? '' : 'class="mark"') ?>>
        <span class="title"><?= _('Aufgabe:') ?></span><br>
        <? if ($perms['edit_task']): ?>
        <textarea name="content" required="required" class="add_toolbar"><?= htmlReady($task->content) ?></textarea><br>
        <? else : ?>
        <span class="task-description">
            <?= formatReady($task->content) ?>
        </span>
        <? endif ?>
    </label>

    <!-- Answer -->
    <? if ($perms['view_answer']) : ?>
        <? if ($task_user->user_id == $user->id && $task_user->closed) : ?>
        <span style="font-weight: bold; color: #CC0000;"><?= _('Diese Aufgabe wurde vom Betreuer geschlossen und kann deshalb nicht mehr bearbeitet werden!') ?></span>
        <? endif ?>
        <? if ($task->allow_text) : ?>
        <label <?= ($perms['edit_answer']) ? '' : 'class="mark"' ?>>
            <span><?= _('Antworttext:') ?></span><br>

            <? if ($perms['edit_answer']) : ?>
                <textarea name="task_user[answer]" class="add_toolbar"><?= htmlReady($task_user->answer) ?></textarea><br>
                <?= $this->render_partial('task/_edit_form_buttons') ?>
            <? else : ?>
            <?= $task_user->answer
                    ? formatReady($task_user->answer)
                    : '<span class="empty_text">' . _('Es wurde bisher keine Antwort eingegeben.') .'</span>' ?>
            <? endif ?>
        </label>
        <? endif ?>

        <br>

        <? if ($task->allow_files) : ?>
        <?= $this->render_partial('file/list', array(
            'files' => $task_user->files->findBy('type', 'answer'),
            'type' => 'answer',
            'edit' => $perms['edit_answer']
        )) ?>
        <br>
        <? endif ?>
    <? endif ?>


    <!-- Settings -->
    <? if ($perms['edit_settings']) : ?>
    <div class="two-columns clearfix">
        <label>
            <span><?= _('Zugeordnete Portfolios:') ?></span><br>
            <select id="sets" name="sets[]" required multiple class="chosen" data-placeholder="<?= _('Wählen Sie Zuordnungen aus') ?>">
                <? foreach ($portfolios as $l_portfolio) : ?>
                    <option <?= $l_portfolio->global ? 'disabled' : '' ?> value="<?= $l_portfolio->id ?>"
                            <?= in_array($l_portfolio->id, $task_portfolios) !== false ? 'selected="selected"' : '' ?>>
                        <?= htmlReady($l_portfolio->name) ?>
                    </option>
                <? endforeach ?>
            </select>
            <?= tooltipIcon('Neue Portfolios können Sie auf der Übersichtsseite erstellen.') ?>
        </label>

        <label>
            <span><?= _('Schlagworte:') ?></span><br>
            <select id="tags" name="tags[]" multiple data-placeholder="<?= _('Fügen Sie Schlagworte hinzu') ?>">
                <? foreach ($task->tags as $tag) : ?>
                    <option value="<?= htmlReady($tag->tag) ?>" selected="selected" <?= $tag->user_id == 'global' ? 'disabled' : '' ?>><?= htmlReady($tag->tag) ?></option>
                <? endforeach ?>

                <? foreach ($tags as $tag) : ?>
                    <? if (in_array($tag, $task_tags) === false) : ?>
                    <option value="<?= htmlReady($tag->tag) ?>"><?= htmlReady($tag->tag) ?></option>
                    <? endif ?>
                <? endforeach ?>
            </select>
        </label>
    </div>

    <?= $this->render_partial('task/_permissions') ?>
    <br>
    <? endif ?>


    <!-- Feedback -->
    <? if ($perms['view_feedback']) : ?>
        <label <?= ($perms['edit_feedback']) ? '' : 'class="mark"' ?>>
            <span><?= _('Feedback:') ?></span><br>

            <? if ($perms['edit_feedback']) : ?>
                <textarea name="task_user[feedback]" class="add_toolbar"><?= htmlReady($task_user->feedback->feedback) ?></textarea><br>
                <?= $this->render_partial('task/_edit_form_buttons') ?>
            <? else : ?>
            <?= $task_user->feedback
                    ? formatReady($task_user->feedback->feedback)
                    : '<span class="empty_text">' . _('Es wurde bisher kein Feedback eingegeben.') .'</span>' ?>

                <span class="editor">
                    <? if ($task_user->feedback->mkdate != $task_user->feedback->chdate) : ?>
                    Erstellt am <?= strftime($timeformat, $task_user->feedback->mkdate) ?>,
                    <? endif ?>

                    zuletzt bearbeitet von <a href="<?= URLHelper::getLink('dispatch.php/profile/?username=' . get_username($task_user->feedback->user_id)) ?>">
                        <?= get_fullname($task_user->feedback->user_id) ?></a>
                    am <?= strftime($timeformat, $task_user->feedback->chdate) ?>
                </span>
            <? endif ?>
        </label>

        <br>

        <? if ($task->allow_files) : ?>
        <?= $this->render_partial('file/list', array(
            'files' => $task_user->files->findBy('type', 'feedback'),
            'type' => 'feedback',
            'edit' => $perms['edit_feedback']
        )) ?>
        <br>
        <? endif ?>
    <? endif ?>


    <!-- Goal -->
    <? if ($perms['view_goal']) : ?>
    <br>
    <label <?= ($perms['edit_goal']) ? '' : 'class="mark"' ?>>
        <span><?= _('Zielvereinbarung:') ?></span><br>

        <? if ($perms['edit_goal']) : ?>
            <textarea name="task_user[goal]" class="add_toolbar"><?= htmlReady($task_user->goal) ?></textarea><br>
            <?= $this->render_partial('task/_edit_form_buttons') ?>
        <? else : ?>
        <?= $task_user->goal
                ? formatReady($task_user->goal)
                : '<span class="empty_text">' . _('Es wurde bisher keine Zielvereinbarung eingegeben.') .'</span>' ?>
        <? endif ?>
    </label>
    <? endif ?>

    <? if ($perms['close_task']) : ?>
    <label>
        <input type="checkbox" name="close" value="1" <?= $task_user->closed ? 'checked="checked"' : '' ?>>
        <span style="font-weight: bold"><?= _('Aufgabe schließen?') ?></span>
    </label>
    <? endif ?>
</form>

<br><br>

<script>
    jQuery(document).ready(function() {
        jQuery('#sets').chosen({
            width: "90%",
        });

        jQuery('#tags').chosen({
            width: "90%",
            create_option: true,
            persistent_create_option: true,
            skip_no_results: true,
            create_option_text: 'Schlagwort erstellen'.toLocaleString()
        }).change(function(event) {
            // store modified tags
            STUDIP.Portfolio.Tags.update();
        });
        
        <? foreach ($task_user->perms as $perm) : ?>
        STUDIP.Portfolio.Permissions.addTemplate({
            user: '<?= get_username($perm->user_id) ?>',
            fullname: '<?= get_fullname($perm->user_id) ?>',
            perm: '<?= $perm->role ?>',
            permission: '<?= $permissions[$perm->role] ?>'
        });
        <? endforeach ?>
    });
</script>