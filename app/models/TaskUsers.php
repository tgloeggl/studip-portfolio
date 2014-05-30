<?php
/**
 * TaskUsers - represents an entry in task_users
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

namespace Portfolio;

class TaskUsers extends \Portfolio_SimpleORMap
{
    /**
     * creates new task_user, sets up relations
     *
     * @param string $id
     */
    public function __construct($id = null)
    {
        $this->db_table = 'portfolio_task_users';

        $this->has_many['files'] = array(
            'class_name'  => 'Portfolio\TaskUserFiles',
            'assoc_foreign_key' => 'portfolio_task_users_id',
        );

        $this->belongs_to['task'] = array(
            'class_name'  => 'Portfolio\Tasks',
            'foreign_key' => 'portfolio_tasks_id',
        );

        parent::__construct($id);

    }
}
