<?php
/**
 * Tasksets - a collection of predefined tasks
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

namespace Portfolio;

class Tasksets extends \Portfolio_SimpleORMap
{
    /**
     * creates a new taskset, sets up relations
     * 
     * @param string $id
     */
    public function __construct($id = null)
    {
        $this->db_table = 'portfolio_tasksets';

        /*
        $this->has_many['tasks'] = array(
            'class_name'        => 'Portfolio\Tasks',
            'assoc_foreign_key' => 'taskset_id',
        );
         * 
         */

        /*
        $this->has_many['studiengang_combos'] = array(
            'class_name'        => 'Portfolio\TasksetsStudiengangCombos',
            'assoc_foreign_key' => 'portfolio_tasksets_id',
        );
         * 
         */

        $this->has_many['combos'] = array(
            'class_name'        => 'Portfolio\TasksetsStudiengangCombos',
            'assoc_foreign_key' => 'tasksets_id',
            'on_delete'         => 'delete',
            'on_store'          => 'store'
        );

        $this->has_and_belongs_to_many = array(
            'tasks' => array(
                'class_name'  => 'Portfolio\Tasks',
                'thru_table'  => 'portfolio_tasksets_tasks',
                'thru_key'    => 'portfolio_tasksets_id',
                'thru_assoc_key' => 'portfolio_tasks_id',
                'on_delete'      => 'delete',
                'on_store'       => 'store'
            ),
        );         

        parent::__construct($id);
    }
}