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

<h1><?= _('Aufgabe bearbeiten') ?></h1>
<form method="post" action="<?= $controller->url_for('task/update/' . $portfolio->id .'/'. $task_user->id) ?>">
    <!-- Task -->
    <label <?= ($perms['edit_task'] ? '' : 'class="mark"') ?>>
        <span><?= _('Titel:') ?></span><br>
        <? if ($perms['edit_task']): ?>
        <input type="text" name="title" required="required" value="<?= htmlReady($task->title) ?>"><br>
        <? else : ?>
        <?= htmlReady($task->title) ?>
        <? endif ?>
    </label>

    <label <?= ($perms['edit_task'] ? '' : 'class="mark"') ?>>
        <span><?= _('Aufgabenbeschreibung:') ?></span><br>
        <? if ($perms['edit_task']): ?>
        <textarea name="content" required="required" class="add_toolbar"><?= htmlReady($task->content) ?></textarea><br>
        <? else : ?>
        <?= formatReady($task->content) ?>
        <? endif ?>
    </label>

    <!-- Settings -->
    <? if ($perms['edit_settings']) : ?>
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

    <?= $this->render_partial('task/_permissions') ?>
    <br>
    <? endif ?>

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

    <!-- Feedback -->
    <? if ($perms['view_feedback']) : ?>
        <label <?= ($perms['edit_feedback']) ? '' : 'class="mark"' ?>>
            <span><?= _('Feedback:') ?></span><br>

            <? if ($perms['edit_feedback']) : ?>
                <textarea name="task_user[feedback]" class="add_toolbar"><?= htmlReady($task_user->feedback) ?></textarea><br>
                <?= $this->render_partial('task/_edit_form_buttons') ?>
            <? else : ?>
            <?= $task_user->feedback
                    ? formatReady($task_user->feedback)
                    : '<span class="empty_text">' . _('Es wurde bisher kein Feedback eingegeben.') .'</span>' ?>
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

<script>
    jQuery(document).ready(function() {
        jQuery('#sets').chosen({
            create_option_text: 'Portfolio erstellen'.toLocaleString()
        });
        jQuery('#tags').chosen({
            create_option: true,
            persistent_create_option: true,
            skip_no_results: true,
            create_option_text: 'Schlagwort erstellen'.toLocaleString()
        });
        
        <? foreach ($task_user->perms as $perm) : ?>
        STUDIP.Portfolio.Homepage.addPermissionTemplate({
            user: '<?= get_username($perm->user_id) ?>',
            fullname: '<?= get_fullname($perm->user_id) ?>',
            perm: '<?= $perm->role ?>',
            permission: '<?= $perm->role == 'tutor' 
                ? _('Betreuer') : ($perm->role == 'student'
                ?  _('Kommilitone') :  _('Nachfolgebetreuer')) ?>'
        });
        <? endforeach ?>
    });
</script>