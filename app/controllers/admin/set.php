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
        $this->portfolios = Portfolio\Tasksets::findBySQL('1');
    }

    public function new_action()
    {
        Navigation::activateItem('/admin/config/portfolio');
    }
    
    public function add_action()
    {
        SimpleORMap::expireTableScheme();

        $data = array(
            'name'    => Request::get('name'),
            'user_id' => $GLOBALS['user']->id
        );

        $taskset = Portfolio\Tasksets::create($data);
        
        foreach (Request::optionArray('studycourses') as $combo) {
            $study_combo = new Portfolio\TasksetsStudiengangCombos();
            $study_combo->portfolio_tasksets_id = $taskset->getId();
            
            foreach ($combo as $studiengang_id) {
                $studiengang = new \Portfolio_Studycourse($studiengang_id);
                $study_combo->studiengaenge[] = $studiengang;
            }
            
            $study_combo->store();
        }

        $this->redirect('admin/portfolio/index');
    }
    
    public function edit_action($set_id)
    {
        $this->redirect('admin/portfolio/index');
    }
    
    public function delete_action($set_id)
    {
        $this->redirect('admin/portfolio/index');
    }
    
    public function get_studycourses_action()
    {
        $this->render_json(Portfolio_Studycourse::findAndMapBySQL(function($data) {
            return array(
                'value' => $data->studiengang_id ,
                'name' => $data->name
            );
        }, '1 ORDER BY name ASC'));
    }
}