<?php
/**
 * TaskController - Short description for file
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
class Admin_TaskController extends PortfolioPluginController
{

    public function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);

        Portfolio\Perm::check('admin');
        
        Navigation::activateItem('/admin/config/portfolio');
    }
    
    public function index_action($portfolio_id)
    {
        $this->portfolio = Portfolio\Tasksets::find($portfolio_id);
    }
    
    public function new_action($portfolio_id)
    {
        $this->portfolio_id = $portfolio_id;
        $this->portfolios = Portfolio\Tasksets::findBySQL('1 ORDER BY name DESC');
    }
    
    public function add_action($portfolio_id)
    {
        
        $this->redirect('admin/task/index/' . $portfolio_id);
    }
    
    public function edit_action($portfolio_id, $task_id)
    {
        $this->redirect('admin/task/index/' . $portfolio_id);
    }
    
    public function delete_action($portfolio_id, $task_id)
    {
        $this->redirect('admin/task/index/' . $portfolio_id);
    }
}