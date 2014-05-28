<?php
/**
 * Tasks - presents a single task
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 3 of
 * the License, or (at your option) any later version.
 *
 * @author      Till Glöggler <tgloeggl@uos.de>
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GPL version 3
 * @category    Stud.IP
 * 
 */

namespace Portfolio;

class Tasks extends \Portfolio_SimpleORMap
{
    /**
     * creates new task, sets up relations
     * 
     * @param string $id
     */
    public function __construct($id = null)
    {
        $this->db_table = 'portfolio_tasks';

        $this->has_many['task_users'] = array(
            'class_name'        => 'Portfolio\TaskUsers',
            'assoc_foreign_key' => 'portfolio_tasks_id',
            'on_delete'      => 'delete',
            'on_store'       => 'store'
            
        );
        
        $this->has_many['perms'] = array(
            'class_name'        => 'Portfolio\Permissions',
            'assoc_foreign_key' => 'portfolio_tasks_id',
            'on_delete'      => 'delete',
            'on_store'       => 'store'
            
        );

        $this->has_and_belongs_to_many['tasksets'] = array(
            'class_name'  => 'Portfolio\Tasksets',
            'thru_table'  => 'portfolio_tasksets_tasks',
            'thru_key'    => 'portfolio_tasks_id',
            'thru_assoc_key' => 'portfolio_tasksets_id',
            'on_delete'      => 'delete',
            'on_store'       => 'store'
        );

        $this->has_and_belongs_to_many['tags'] = array(
            'class_name'     => 'Portfolio\Tags',
            'thru_table'     => 'portfolio_tags_tasks',
            'thru_key'       => 'portfolio_tasks_id',
            'thru_assoc_key' => 'portfolio_tags_id',
            'on_delete'      => 'delete',
            'on_store'       => 'store'
        );

        $this->has_and_belongs_to_many['portfolios'] = array(
            'class_name'     => 'Portfolio\Portfolios',
            'thru_table'     => 'portfolio_portfolios_tasks',
            'thru_key'       => 'portfolio_tasks_id',
            'thru_assoc_key' => 'portfolio_portfolios_id',
            'on_delete'      => 'delete',
            'on_store'       => 'store'
        );

        parent::__construct($id);
    }
}
