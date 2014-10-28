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
        $this->portfolios = Portfolio\Portfolios::findBySQL('global = 1');
    }

    public function new_action()
    {
        Navigation::activateItem('/admin/config/portfolio');
    }
    
    public function add_action()
    {
        $data = array(
            'name'        => Request::get('name'),
            'description' => Request::get('description'),
            'user_id'     => $GLOBALS['user']->id,
            'global'      => 1
        );

        $portfolio = Portfolio\Portfolios::create($data);

        foreach (Request::optionArray('studycourses') as $studycourses) {

            $combo = new Portfolio\PortfoliosStudiengangCombos();

            foreach ($studycourses as $ids) {
                list($studiengang_id, $abschluss_id) = explode('_', $ids);

                $study_combo = new Portfolio\StudiengangCombos();
                $study_combo->setData(array(
                    'studiengang_id' => $studiengang_id,
                    'abschluss_id'   => $abschluss_id
                ));

                $combo->study_combos[] = $study_combo;
            }
            
            $portfolio->combos[] = $combo;
        }
        
        $portfolio->store();
        
        $this->redirect('admin/set/index');
    }
    
    public function edit_action($set_id)
    {
        $this->portfolio = Portfolio\Portfolios::find($set_id);
    }
    
    public function update_action($set_id)
    {
        $portfolio = Portfolio\Portfolios::find($set_id);
        
        $portfolio->setData(array(
            'name'        => Request::get('name'),
            'description' => Request::get('description'),
            'global'      => 1
        ));

        foreach ($portfolio->combos as $key => $combo) {
            unset($portfolio->combos[$key]);
        }
        
        foreach (Request::optionArray('studycourses') as $studycourses) {

            $combo = new Portfolio\PortfoliosStudiengangCombos();

            foreach ($studycourses as $ids) {
                list($studiengang_id, $abschluss_id) = explode('_', $ids);

                $study_combo = new Portfolio\StudiengangCombos();
                $study_combo->setData(array(
                    'studiengang_id' => $studiengang_id,
                    'abschluss_id'   => $abschluss_id
                ));

                $combo->study_combos[] = $study_combo;
            }
            
            $portfolio->combos[] = $combo;
        }
        
        $portfolio->store();

        $this->redirect('admin/set/index');
    }
    
    public function delete_action($set_id)
    {
        
        $portfolio = Portfolio\Portfolios::find($set_id);
        $portfolio->delete();
        
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