<?php
/**
 * Permissions - Short description for file
 *
 * Long description for file (if any)...
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 3 of
 * the License, or (at your option) any later version.
 *
 * @author      Till Gl�ggler <tgloeggl@uos.de>
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GPL version 3
 * @category    Stud.IP
 */

namespace Portfolio;

class Permissions extends \Portfolio_SimpleORMap
{
    /**
     * creates new permissions, sets up relations
     * 
     * @param string $id
     */
    public function __construct($id = null)
    {
        $this->db_table = 'portfolio_permissions';

        $this->belongs_to['task_user'] = array(
            'class_name'  => 'Portfolio\TaskUsers',
            'foreign_key' => 'portfolio_task_users_id',
        );

        parent::__construct($id);
    }
}