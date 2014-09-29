<?php
/**
 * Portfolios - a collection of predefined and user-defined portfolios
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
     * creates a new portfolios, sets up relations
     *
     * @param string $id
     */
    public function __construct($id = null)
    {
        $this->db_table = 'portfolio_portfolios';

        $this->has_many['combos'] = array(
            'class_name'        => 'Portfolio\PortfoliosStudiengangCombos',
            'assoc_foreign_key' => 'portfolios_id',
            'on_delete'         => 'delete',
            'on_store'          => 'store'
        );

        $this->has_and_belongs_to_many['tasks'] = array(
            'class_name'     => 'Portfolio\Tasks',
            'thru_table'     => 'portfolio_portfolios_tasks',
            'thru_key'       => 'portfolio_portfolios_id',
            'thru_assoc_key' => 'portfolio_tasks_id',
            'on_delete'      => 'delete',
            'on_store'       => 'store'
        );

        $this->registerCallback('before_delete',  'destroyRelations');

        parent::__construct($id);
    }

    function destroyRelations()
    {
        $this->tasks = array();
        $this->store();
    }

    static function getPortfoliosForUser($user_id)
    {

        // get all studycourses for user
        $studycourses = \SimpleORMapCollection::createFromArray(
                \UserStudyCourse::findByUser($user_id)
        )->pluck('studiengang_id abschluss_id');

        // get portfolios for current user, preventing duplicates
        $portfolios = self::getPortfoliosWithStudycourses($studycourses);

        $ids = array();
        foreach ($portfolios as $p) {
            $ids[] = $p->id;
        }

        foreach(self::findByUser_Id($user_id) as $p) {
            if (in_array($p->id, $ids) === false) {
                $portfolios[] = $p;
            }
        }

        return $portfolios;
    }

    /**
     *
     * @param type $studycourses
     */
    static function getPortfoliosWithStudycourses($studycourses)
    {
        // get all portfolios
        $portfolios = \Portfolio\Portfolios::findBySQL('global = 1');

        // filter portfolios by studiengang-combos
        foreach ($portfolios as $pkey => $portfolio) {
            $remove_task = true;

            // check if there are any combos at all.  Portfolios with no combo are visible for all users
            if (!sizeof($portfolio->combos)) {
                $remove_task = false;
            } else {
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
            }

            if ($remove_task) {
                unset($portfolios[$pkey]);
            }
        }

        return $portfolios;
    }
}