<?php
/**
 * PortfolioController - Short description for file
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
class PortfolioController extends PortfolioPluginController 
{

    public function index_action()
    {
        Navigation::activateItem('/profile/portfolio');

        // get all studycourses for user
        $studycourses = SimpleORMapCollection::createFromArray(
                UserStudyCourse::findByUser($this->container['user']->id)
        )->pluck('studiengang_id abschluss_id');
        
        // get all tasksets
        $this->portfolios = \Portfolio\Tasksets::findBySQL('1');

        // filter tasksets by studiengang-combos
        foreach ($this->portfolios as $pkey => $portfolio) {
            $remove_task = true;
            
            // check if combo if the user meets all requirements for one complete combo
            foreach ($portfolio->combos as $combo) {
                $has_studycourse = true;
                
                // check the studycourses for the current combo
                foreach ($combo->study_combos as $study_combo) {
                    $needle = array(
                        $study_combo->studiengang->getId(),
                        $study_combo->abschluss->getId()
                    );

                    if (in_array($needle, $studycourses) === false) {
                        $has_studycourse = false;
                    }
                }
                
                if ($has_studycourse) {
                    $remove_task = false;
                }
            }
            
            if ($remove_task) {
                unset($this->portfolios[$pkey]);
            }
        }

    }
}
