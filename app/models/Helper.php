<?php
/**
 * Helper.php - description
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License
 * version 3 as published by the Free Software Foundation.
 *
 * @author      Till Glöggler <tgloeggl@uos.de>
 * @license     https://www.gnu.org/licenses/agpl-3.0.html AGPL version 3
 */

namespace Portfolio;

class Helper
{
    /**
     * This method returns a diff for the two passed array, listing added 
     * and deleted elements
     * 
     * @param array   $previous  this is how the list looked before the change
     * @param array   $current   this is how the list looks now
     * 
     * @return array  an array containing the removed elements at 'deleted'
     *                and the added elements at 'added'
     */
    static function pick($previous, $current) {
        $previous = $previous ?: array();
        $current  = $current ?: array();

        return array(
            'deleted' => array_udiff($previous, $current, array('self', 'udiff_compare')),
            'added'   => array_udiff($current, $previous, array('self', 'udiff_compare'))
        );
    }

    private static function udiff_compare($a, $b)
    {
        return strcmp(serialize($a), serialize($b));
    }


    static function sortTasksByTags($tasks)
    {
        $tasks_by_tag = $tagless_tasks = $tags = array();

        // get tags
        foreach ($tasks as $task) {
            $ttags = $task->tags->orderBy('tag')->pluck('tag');

            if (empty($ttags)) {
                $tagless_tasks[] = $task;
            } else {
                // collect all tag-combinations and group tasks by tags
                foreach ($ttags as $tag) {
                    $tasks_by_tag[$tag][] = $task;

                    if (!$tags[$tag]) {
                        $tags[$tag] = array();
                    }

                    foreach ($tags as $tag2 => $taglist) {
                        if ($tag != $tag2) {
                            $tags[$tag][] = $tag2;
                        }
                    }
                }
            }
        }

        return compact('tasks_by_tag', 'tagless_tasks', 'tags');
    }

    static function sortTaskUsersByTags($task_users)
    {
        $tasks_by_tag = $tagless_tasks = $tags = array();

        // get tags
        foreach ($task_users as $task_user) {
            $task = $task_user->task;

            $ttags = $task->tags->orderBy('tag')->pluck('tag');

            if (empty($ttags)) {
                $tagless_tasks[] = $task;
            } else {
                // collect all tag-combinations and group tasks by tags
                foreach ($ttags as $tag) {
                    $tasks_by_tag[$tag][] = $task;

                    if (!$tags[$tag]) {
                        $tags[$tag] = array();
                    }

                    foreach ($tags as $tag2 => $taglist) {
                        if ($tag != $tag2) {
                            $tags[$tag][] = $tag2;
                        }
                    }
                }
            }
        }

        return compact('tasks_by_tag', 'tagless_tasks', 'tags');
    }

    static function getForeignTasksForUser($user_id)
    {
        $task_users = array();

        $perms = Permissions::findByUser_id($user_id);

        foreach ($perms as $perm) {
            $task_users[] = $perm->task_user;
        }

        return $task_users;
    }

    function return_bytes($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);

        switch($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }

    function bytesToSize($bytes) {
        if($bytes == 0) return '0 Byte';
        $k = 1000;
        $sizes = array('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $i = floor(log($bytes) / log($k));
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }
}
