<?php
/**
 * Portfolios - Short description for file
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

class Portfolios extends \Portfolio_SimpleORMap
{
    /**
     * creates new portfolios, sets up relations
     * 
     * @param string $id
     */
    public function __construct($id = null)
    {
        $this->db_table = 'portfolio_portfolios';

        $this->has_and_belongs_to_many = array(
            'task_users' => array(
                'class_name'     => 'Portfolio\TaskUsers',
                'thru_table'     => 'portfolio_tags_task_users',
                'thru_key'       => 'portfolio_portfolios_id',
                'thru_assoc_key' => 'portfolio_task_users_id',
                'on_delete'      => 'delete',
                'on_store'       => 'store'
            ),
        );

        parent::__construct($id);
    }
}