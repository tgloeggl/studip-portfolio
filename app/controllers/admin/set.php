<?php
/**
 * SetController - Short description for file
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
class Admin_SetController extends PortfolioPluginController
{

    public function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);

        Portfolio\Perm::check('admin');
        
        Navigation::activateItem('/admin/config/portfolio');
    }

    public function index_action()
    {
        SimpleORMap::expireTableScheme();

        $this->portfolios = Portfolio\Tasksets::findBySQL('1');
    }

    public function new_action()
    {
        Navigation::activateItem('/admin/config/portfolio');
    }
    
    public function add_action()
    {
        $data = array(
            'name'    => Request::get('name'),
            'user_id' => $GLOBALS['user']->id
        );

        $taskset = Portfolio\Tasksets::create($data);

        foreach (Request::optionArray('studycourses') as $studycourses) {

            $combo = Portfolio\TasksetsStudiengangCombos::create(array(
                'tasksets_id' => $taskset->id
            ));

            foreach ($studycourses as $ids) {
                list($studiengang_id, $abschluss_id) = explode('_', $ids);

                $study_combo = Portfolio\StudiengangCombos::create(array(
                    'combo_id'       => $combo->id,
                    'studiengang_id' => $studiengang_id,
                    'abschluss_id'   => $abschluss_id
                ));
            }
        }
        
        $this->redirect('admin/set/index');
    }
    
    public function edit_action($set_id)
    {
        $this->redirect('admin/set/index');
    }
    
    public function delete_action($set_id)
    {
        $this->redirect('admin/set/index');
    }
    
    public function get_studycourses_action()
    {
        $return = array();
        $studiengaenge = Portfolio_Studycourse::findBySQL('1 ORDER BY name ASC');
        $abschluesse   = Portfolio\Abschluss::findBySQL('1 ORDER BY name ASC');
        
        foreach ($studiengaenge as $studiengang) {
            foreach ($abschluesse as $abschluss) {
                $return[] = array(
                    'value' => $studiengang->getId() .'_'. $abschluss->getId(),
                    'name'  => $studiengang->name .' * '. $abschluss->name
                );
            }
        }
        $this->render_json($return);
    }
}