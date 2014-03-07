<?php
/**
 * Tags - Short description for file
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

class Tags extends \Portfolio_SimpleORMap
{
    /**
     * creates new tags, sets up relations
     * 
     * @param string $id
     */
    public function __construct($id = null)
    {
        $this->db_table = 'portfolio_tags';

        $this->has_and_belongs_to_many = array(
            'tasks' => array(
                'class_name'     => 'Portfolio\Tasks',
                'thru_table'     => 'portfolio_tags_tasks',
                'thru_key'       => 'portfolio_tags_id',
                'thru_assoc_key' => 'portfolio_task_users_id',
                'on_delete'      => 'delete',
                'on_store'       => 'store'
            ),
        );

        parent::__construct($id);
    }
}