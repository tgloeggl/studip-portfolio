<?php
/**
 * FileController - Short description for file
 *
 * Long description for file (if any)...
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 3 of
 * the License, or (at your option) any later version.
 *
 * @author      Till Glöggler <tgloeggl@uos.de>
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GPL version 3
 * @category    Stud.IP
 */
class FileController extends PortfolioPluginController
{
    function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);
        $this->user = $this->container['user'];
    }

    function remove_file_action($file_id)
    {
        $file = new Portfolio\TaskUserFiles($file_id);

        // only delete file, if it belongs to the current user
        if ($file->document->user_id == $this->user->id) {
            delete_document($file->document->getId());
            $file->delete();
        }

        $this->render_nothing();
    }

    function post_files_action($task_user_id, $type)
    {
        if (empty($_POST) && $_SERVER['CONTENT_LENGTH'] > Helper::return_bytes(ini_get('post_max_size'))) {
            throw new Trails_Exception(413, 'Datei zu groß'); // Request Entity Too Large
        }

        $user_id = $this->user->id;
        $task_user = new Portfolio\TaskUsers($task_user_id);
        $task      = new Portfolio\Tasks($task_user->portfolio_tasks_id);
        $perms = Portfolio\Perm::get($user_id, $task_user);

        if (!$task->allow_files) {
            throw new AccessDeniedException(_('Für diese Aufgabe dürfen keine Dateien hochgeladen werden.'));
        }

        if ($type == 'answer' && !$perms['edit_answer']) {
            throw new AccessDeniedException(_('Sie haben keine Rechte zum Bearbeiten dieser Aufgabe.'));
        }

        if ($type == 'feedback' && !$perms['edit_feedback']) {
            throw new AccessDeniedException(_('Sie haben keine Rechte zum Bearbeiten dieser Aufgabe.'));
        }

        if (!Request::isPost() || in_array($type, words('answer feedback')) === false) {
            throw new AccessDeniedException("Kein Zugriff");
        }

        $output = array();

        foreach ($_FILES as $file) {
            $GLOBALS['msg'] = '';
            validate_upload($file);

            if ($GLOBALS['msg']) {
                $output['errors'][] = $file['name'] . ': ' . studip_utf8encode(decodeHTML(trim(substr($GLOBALS['msg'],6, -1), '?')));
                continue;
            }

            if ($file['size']) {
                $dokument_id = md5(uniqid());

                $document['dokument_id'] = $dokument_id;
                $document['name'] = $document['filename'] = studip_utf8decode(strtolower($file['name']));
                $document['user_id'] = $user_id;
                $document['author_name'] = get_fullname($user_id);
                $document['seminar_id'] = $user_id; // use the user_id here, preserves downloadibility
                $document['range_id'] = null;
                $document['filesize'] = $file['size'];

                $data = array(
                    'portfolio_task_users_id' => $task_user_id,
                    'dokument_id'             => $dokument_id,
                    'type'                    => $type,
                );

                $taskfile = Portfolio\TaskUserFiles::create($data);

                if ($newfile = StudipDocument::createWithFile($file['tmp_name'], $document)) {
                    $output[] = array(
                        'url'     => GetDownloadLink($newfile->getId(), $newfile['filename']),
                        'id'      => $taskfile->id,
                        'name'    => $newfile->name,
                        'date'    => strftime($this->timeformat, time()),
                        'size'    => $newfile->filesize,
                        'creator' => get_fullname($user_id)
                    );
                } else {
                    throw new Exception('Konnte Datei nicht auf dem Server erstellen! Fehlende Schreibrechte?');
                }
            }
        }

        $this->render_json($output);
    }
}