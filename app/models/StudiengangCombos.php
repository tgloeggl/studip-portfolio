<?php
/**
 * StudiengangCombos.php - Short description for file
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

class StudiengangCombos extends \Portfolio_SimpleORMap
{
    /**
     * creates new entry for combo, sets up relations
     * 
     * @param string $id
     */
    public function __construct($id = null)
    {
        $this->db_table = 'portfolio_studiengang_combos';

        /*
        $this->belongs_to['studiengang_combos'] = array(
            'class_name'  => 'Portfolio\TasksetsStudiengangCombos',
            'foreign_key' => 'portfolio_studiengang_combos_id',
        );
         * 
         */
        
        $this->has_one['studiengang'] = array(
            'class_name'        => '\Portfolio_StudyCourse',
            'foreign_key'       => 'studiengang_id',
            'foreign_assoc_key' => 'studiengang_id'
        );

        $this->has_one['abschluss'] = array(
            'class_name'        => '\Portfolio_StudyCourse',
            'foreign_key'       => 'studiengang_id',
            'foreign_assoc_key' => 'studiengang_id'
        );
        
        parent::__construct($id);
    }
}