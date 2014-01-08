<?php
/**
 * TasksetsStudiengangCombos - a combo for a tasksets
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

class TasksetsStudiengangCombos extends \Portfolio_SimpleORMap
{
    /**
     * creates a new studiengang-combo for a taskset, sets up relations
     * 
     * @param string $id
     */
    public function __construct($id = null)
    {
        $this->db_table = 'portfolio_tasksets_studiengang_combos';

        $this->has_many['studiengaenge'] = array(
            'class_name'     => '\Portfolio_StudyCourse',
            'thru_table'     => 'portfolio_studiengang_combos',
            'thru_key'       => 'combo_id',
            'thru_assoc_key' => 'studiengang_id',            
        );
        
        $this->belongs_to['tasksets'] = array(
            'class_name'  => 'Portfolio\Tasksets',
            'foreign_key' => 'portfolio_tasksets_id',
        );
        
        parent::__construct($id);
    }
}
