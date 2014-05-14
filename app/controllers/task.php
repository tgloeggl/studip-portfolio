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
class TaskController extends PortfolioPluginController
{
    function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);
        
        Navigation::activateItem('/profile/portfolio');
    }

    public function index_action($portfolio_id)
    {
        $this->portfolio = \Portfolio\Tasksets::find($portfolio_id);

        $this->filter = false;
        if (Request::getArray('tag')) {
            $this->filter = Request::getArray('tag');
        }

        // get tags
        foreach ($this->portfolio->tasks as $task) {
            $tags = $task->tags->pluck('tag');
            // check if the task has all the filtered tags
            $show = true;

            if ($this->filter) {
                foreach($this->filter as $ftag) {
                    if (in_array($ftag, $tags) === false) {
                        $show = false;
                    }
                }
            }
            
            // collect all tag-combinations and group tasks by tags
            foreach ($tags as $tag) {
                if ($show) {
                    $this->tasks_by_tag[$tag][] = $task;
                }
                foreach ($tags as $tag2) {
                    if ($tag != $tag2) {
                        $this->tags[$tag][] = $tag2;
                    }
                }
            }
        }
        
        
        #var_dump($this->tasks_by_tag);
    }
    
    public function new_action($portfolio_id)
    {
        
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