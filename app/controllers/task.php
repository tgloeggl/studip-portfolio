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
 * @author      Till Gl�ggler <tgloeggl@uos.de>
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
        // TODO: check perms for portfolio

        $this->portfolio = \Portfolio\Tasksets::find($portfolio_id);
        
        if (!$this->portfolio) {
            $this->redirect('portfolio');
        }

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
    }
    
    public function new_action($portfolio_id)
    {
        // get all studycourses for user
        $studycourses = SimpleORMapCollection::createFromArray(
                UserStudyCourse::findByUser($this->container['user']->id)
        )->pluck('studiengang_id abschluss_id');
        
        $this->portfolios = \Portfolio\Tasksets::getTasksetsWithStudycourses($studycourses);
        $this->my_portfolios = \Portfolio\Portfolios::findByUser_Id($this->container['user']->id);
    }
    
    public function add_action($portfolio_id)
    {
        $user_id = $this->container['user']->id;

        $data = array(
            'user_id'     => $user_id,
            'title'       => Request::get('title'),
            'content'     => Request::get('content'),
            'allow_text'  => 1,
            'allow_files' => 1
        );

        $task = Portfolio\Tasks::create($data);

        // add the task to the correct portfolio
        foreach (Request::getArray('sets') as $set_json) {
            if ($set = json_decode($set_json)) {
                if ($set->type == 'global') {
                    // add the global portfolio to the task
                    $task->tasksets[] = Portfolio\Tasksets::find($set->value);
                } else {
                    // add the local portfolio to the task
                    $task->portfolios[] = Portfolio\Portfolios::find($set->value);
                }
            } else {
                echo 'N: '. $set_json .'<br>';
                
                // $portfolio = 
                // we hav a new (local) portfolio
                
            }
        }        

        foreach (Request::optionArray('sets') as $pid) {
            $taskset_combo = Portfolio\Tasksets::find($pid);
            $task->tasksets[] = $taskset_combo;
        }

        foreach (Request::getArray('tags') as $tag_name) {
            if (!$tag = current(Portfolio\Tags::findBySQL('user_id = ? AND tag = ?', array($user_id, $tag_name)))) {
                $data = array(
                    'user_id' => $user_id,
                    'tag'     => $tag_name
                );
                $tag = Portfolio\Tags::create($data);
            }
            
            $task->tags[] = $tag;
        }
        
        $task->store();
        
        $this->redirect('admin/task/index/' . $portfolio_id);
        
        
        
        
        
        var_dump($_REQUEST);
        foreach (Request::getArray('sets') as $set_json) {
            if ($set = json_decode($set_json)) {
                if ($set->type == 'global') {
                    echo 'G: '. $set->value .'<br>';
                } else {
                    echo $set->value .'<br>';
                }
            } else {
                echo 'N: '. $set_json .'<br>';
                
                // we hav a new (local) portfolio
            }
        }
        die;
        $this->redirect('task/index/' . $portfolio_id);
    }
    
    public function edit_action($portfolio_id, $task_id)
    {
        $this->redirect('task/index/' . $portfolio_id);
    }
    
    public function delete_action($portfolio_id, $task_id)
    {
        $this->redirect('task/index/' . $portfolio_id);
    }
}
